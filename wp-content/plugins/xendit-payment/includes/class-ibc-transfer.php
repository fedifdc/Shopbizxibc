<?php
/**
 * IBC Token Transfer via BSC (Binance Smart Chain)
 * Handles ERC20 token transfers using proper crypto libraries
 */

if (!defined('ABSPATH')) {
    exit;
}

// Load composer autoloader
require_once plugin_dir_path(__DIR__) . 'vendor/autoload.php';

use kornrunner\Keccak;
use Elliptic\EC;
use Web3p\RLP\RLP;

class IBC_Transfer {

    private $rpc_url;
    private $contract_address;
    private $token_decimals;
    private $hot_wallet_address;
    private $hot_wallet_private_key;
    private $chain_id;

    public function __construct() {
        $options = get_option('xendit_payment_settings');
        $this->rpc_url = isset($options['bsc_rpc_url']) ? $options['bsc_rpc_url'] : 'https://bsc-dataseed.binance.org/';
        $this->contract_address = isset($options['ibc_token_contract']) ? strtolower($options['ibc_token_contract']) : '';
        $this->token_decimals = isset($options['ibc_token_decimals']) ? intval($options['ibc_token_decimals']) : 18;
        $this->hot_wallet_address = isset($options['bsc_hot_wallet_address']) ? strtolower($options['bsc_hot_wallet_address']) : '';
        $this->hot_wallet_private_key = isset($options['bsc_hot_wallet_private_key']) ? $options['bsc_hot_wallet_private_key'] : '';
        $this->chain_id = isset($options['bsc_chain_id']) ? intval($options['bsc_chain_id']) : 56;
    }

    /**
     * Calculate IBC token bonus amount from Rupiah withdraw
     */
    public function calculate_ibc_amount($rupiah_amount) {
        $bonus_pct = $this->get_bonus_percentage();
        $ibc_price = $this->get_ibc_price_idr();

        if ($ibc_price <= 0 || $bonus_pct <= 0) {
            return 0;
        }

        $bonus_idr = $rupiah_amount * ($bonus_pct / 100);
        return $bonus_idr / $ibc_price;
    }

    public function get_bonus_percentage() {
        $options = get_option('xendit_payment_settings');
        return isset($options['ibc_bonus_percentage']) ? floatval($options['ibc_bonus_percentage']) : 0;
    }

    public function get_ibc_price_idr() {
        $options = get_option('xendit_payment_settings');
        return isset($options['ibc_token_price_idr']) ? floatval($options['ibc_token_price_idr']) : 0;
    }

    public function is_enabled() {
        return (
            !empty($this->contract_address) &&
            !empty($this->hot_wallet_address) &&
            !empty($this->hot_wallet_private_key) &&
            $this->get_bonus_percentage() > 0 &&
            $this->get_ibc_price_idr() > 0
        );
    }

    /**
     * JSON-RPC call to BSC node
     */
    private function rpc_call($method, $params = []) {
        $payload = json_encode([
            'jsonrpc' => '2.0',
            'method' => $method,
            'params' => $params,
            'id' => 1
        ]);

        $ch = curl_init($this->rpc_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        // Bypass SSL verification for local WAMP environments
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            $error = curl_error($ch);
            curl_close($ch);
            throw new Exception('BSC RPC Error: ' . $error);
        }
        curl_close($ch);

        $result = json_decode($response, true);
        if (isset($result['error'])) {
            throw new Exception('BSC RPC Error: ' . ($result['error']['message'] ?? json_encode($result['error'])));
        }

        return $result['result'] ?? null;
    }

    /**
     * Convert a decimal string to hex (for large numbers)
     */
    private function bcdec_to_hex($dec) {
        $hex = '';
        $dec = rtrim($dec, '.0');
        if ($dec === '' || $dec === '0') return '0';
        while (bccomp($dec, '0') > 0) {
            $remainder = bcmod($dec, '16');
            $hex = dechex(intval($remainder)) . $hex;
            $dec = bcdiv($dec, '16', 0);
        }
        return $hex ?: '0';
    }

    /**
     * Encode ERC20 transfer(address,uint256) call data
     */
    private function encode_transfer_data($to_address, $token_amount) {
        $method_id = 'a9059cbb'; // keccak256("transfer(address,uint256)")

        $to_hex = str_pad(strtolower(str_replace('0x', '', $to_address)), 64, '0', STR_PAD_LEFT);

        // Convert token amount to smallest unit (with decimals)
        $amount_wei = bcmul(strval($token_amount), bcpow('10', strval($this->token_decimals), 0), 0);
        $amount_hex = str_pad($this->bcdec_to_hex($amount_wei), 64, '0', STR_PAD_LEFT);

        return '0x' . $method_id . $to_hex . $amount_hex;
    }

    /**
     * Sign and send an ERC20 transfer transaction
     */
    public function transfer_tokens($to_address, $token_amount, $withdraw_id = 0) {
        try {
            if (!$this->is_enabled()) {
                return ['success' => false, 'tx_hash' => '', 'error' => 'IBC transfer not configured'];
            }

            if (empty($to_address) || !preg_match('/^0x[a-fA-F0-9]{40}$/', $to_address)) {
                return ['success' => false, 'tx_hash' => '', 'error' => 'Invalid BSC wallet address'];
            }

            if ($token_amount <= 0) {
                return ['success' => false, 'tx_hash' => '', 'error' => 'Invalid token amount'];
            }

            // Get nonce
            $nonce_hex = $this->rpc_call('eth_getTransactionCount', [$this->hot_wallet_address, 'pending']);
            $nonce = hexdec(str_replace('0x', '', $nonce_hex));

            // Get gas price
            $gas_price_hex = $this->rpc_call('eth_gasPrice');
            $gas_price = hexdec(str_replace('0x', '', $gas_price_hex));

            // Gas limit for ERC20 transfer
            $gas_limit = 100000;

            // Encode transfer data
            $data = $this->encode_transfer_data($to_address, $token_amount);

            // Build raw transaction
            $tx_hash = $this->sign_and_send_tx($nonce, $gas_price, $gas_limit, $this->contract_address, 0, $data);

            error_log("IBC Transfer OK - WD#{$withdraw_id}: {$token_amount} IBC → {$to_address}, TX: {$tx_hash}");

            return ['success' => true, 'tx_hash' => $tx_hash, 'error' => ''];

        } catch (Exception $e) {
            error_log("IBC Transfer FAIL - WD#{$withdraw_id}: " . $e->getMessage());
            return ['success' => false, 'tx_hash' => '', 'error' => $e->getMessage()];
        }
    }

    /**
     * Sign transaction with private key and broadcast
     */
    private function sign_and_send_tx($nonce, $gas_price, $gas_limit, $to, $value, $data) {
        $private_key = str_replace('0x', '', $this->hot_wallet_private_key);

        // Build unsigned tx fields for RLP encoding
        $tx_data = [
            $this->int_to_hex_rlp($nonce),
            $this->int_to_hex_rlp($gas_price),
            $this->int_to_hex_rlp($gas_limit),
            $this->hex_to_bin($to),
            $this->int_to_hex_rlp($value),
            $this->hex_to_bin($data),
            $this->int_to_hex_rlp($this->chain_id),
            '',
            '',
        ];

        // RLP encode
        $rlp = new RLP();
        $encoded = $rlp->encode($tx_data);

        // Keccak256 hash
        $hash = Keccak::hash(hex2bin($encoded), 256);

        // ECDSA sign
        $ec = new EC('secp256k1');
        $key = $ec->keyFromPrivate($private_key);
        $signature = $key->sign($hash, ['canonical' => true]);

        $r = $signature->r->toString(16);
        $s = $signature->s->toString(16);
        $v = $signature->recoveryParam + ($this->chain_id * 2 + 35);

        // Build signed tx
        $signed_tx_data = [
            $this->int_to_hex_rlp($nonce),
            $this->int_to_hex_rlp($gas_price),
            $this->int_to_hex_rlp($gas_limit),
            $this->hex_to_bin($to),
            $this->int_to_hex_rlp($value),
            $this->hex_to_bin($data),
            $this->int_to_hex_rlp($v),
            hex2bin(str_pad($r, 64, '0', STR_PAD_LEFT)),
            hex2bin(str_pad($s, 64, '0', STR_PAD_LEFT)),
        ];

        $signed_encoded = $rlp->encode($signed_tx_data);
        $raw_tx = '0x' . $signed_encoded;

        // Send
        $tx_hash = $this->rpc_call('eth_sendRawTransaction', [$raw_tx]);

        return $tx_hash;
    }

    /**
     * Convert integer to binary for RLP
     */
    private function int_to_hex_rlp($val) {
        if ($val === 0 || $val === '0') return '';
        $hex = dechex($val);
        if (strlen($hex) % 2 !== 0) $hex = '0' . $hex;
        return hex2bin($hex);
    }

    /**
     * Convert hex string to binary
     */
    private function hex_to_bin($hex) {
        $hex = str_replace('0x', '', $hex);
        if (strlen($hex) % 2 !== 0) $hex = '0' . $hex;
        return hex2bin($hex);
    }

    /**
     * Convert large hex string to decimal using BCMath
     */
    private function bchexdec($hex) {
        $hex = strtolower(trim($hex));
        if ($hex === '') return '0';
        $dec = '0';
        $len = strlen($hex);
        for ($i = 1; $i <= $len; $i++) {
            $dec = bcadd($dec, bcmul(strval(hexdec($hex[$i - 1])), bcpow('16', strval($len - $i))));
        }
        return $dec;
    }

    /**
     * Get hot wallet BNB balance (for gas fees)
     */
    public function get_bnb_balance() {
        try {
            $result = $this->rpc_call('eth_getBalance', [$this->hot_wallet_address, 'latest']);
            $hex = str_replace('0x', '', $result);
            $wei = $this->bchexdec($hex);
            return bcdiv($wei, bcpow('10', '18', 0), 6);
        } catch (Exception $e) {
            return 'Error: ' . $e->getMessage();
        }
    }

    /**
     * Get hot wallet IBC token balance
     */
    public function get_ibc_balance() {
        try {
            $data = '0x70a08231' . str_pad(str_replace('0x', '', $this->hot_wallet_address), 64, '0', STR_PAD_LEFT);
            $result = $this->rpc_call('eth_call', [
                ['to' => $this->contract_address, 'data' => $data],
                'latest'
            ]);
            $hex = str_replace('0x', '', $result);
            $balance_raw = $this->bchexdec($hex);
            return bcdiv($balance_raw, bcpow('10', strval($this->token_decimals), 0), 4);
        } catch (Exception $e) {
            return 'Error: ' . $e->getMessage();
        }
    }
}
?>

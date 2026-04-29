<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class WhatsApp_Settings_Page {

    public function __construct() {
        add_action('admin_menu', [$this, 'add_settings_page']);
        add_action('admin_init', [$this, 'register_settings']);
    }

    public function add_settings_page() {
        add_menu_page(
            'WhatsApp Settings',
            'WhatsApp Settings',
            'manage_options',
            'whatsapp-settings',
            [$this, 'settings_page_content'],
            'dashicons-whatsapp',
            80
        );
    }

    public function register_settings() {
        // Register API key setting
        register_setting('whatsapp_settings_group', 'whatsapp_api_key');
      	register_setting('whatsapp_settings_group', 'whatsapp_secret_key');

        // Register custom message setting
        register_setting('whatsapp_settings_group', 'whatsapp_otp_message', [
            'default' => 'Your OTP is: %otp%'
        ]);

        // Add sections and fields for the settings page
        add_settings_section(
            'whatsapp_main_section',
            'API Settings',
            null,
            'whatsapp-settings'
        );

        add_settings_field(
            'whatsapp_api_key',
            'WhatsApp API Key',
            [$this, 'api_key_field_callback'],
            'whatsapp-settings',
            'whatsapp_main_section'
        );
       add_settings_field(
            'whatsapp_secret_key',
            'WhatsApp Secret Key',
            [$this, 'secret_key_field_callback'],
            'whatsapp-settings',
            'whatsapp_main_section'
        );

        add_settings_section(
            'whatsapp_otp_section',
            'OTP Message Settings',
            null,
            'whatsapp-settings'
        );

        add_settings_field(
            'whatsapp_otp_message',
            'OTP Message Template',
            [$this, 'otp_message_field_callback'],
            'whatsapp-settings',
            'whatsapp_otp_section'
        );
    }
  
  public function secret_key_field_callback() {
        $secret_key = get_option('whatsapp_secret_key', '');
        echo '<input type="text" name="whatsapp_secret_key" value="' . esc_attr($secret_key) . '" class="regular-text">';
    }

    public function api_key_field_callback() {
        $api_key = get_option('whatsapp_api_key', '');
        echo '<input type="text" name="whatsapp_api_key" value="' . esc_attr($api_key) . '" class="regular-text">';
    }

    public function otp_message_field_callback() {
        $message = get_option('whatsapp_otp_message', 'Your OTP is: %otp%');
        echo '<textarea name="whatsapp_otp_message" rows="5" class="large-text">' . esc_textarea($message) . '</textarea>';
        echo '<p class="description">Use <strong>%otp%</strong> to place the OTP code in the message template.</p>';
    }

    public function settings_page_content() {
        ?>
        <div class="wrap">
            <h1>WhatsApp Settings</h1>
            <form method="post" action="options.php">
                <?php
                settings_fields('whatsapp_settings_group');
                do_settings_sections('whatsapp-settings');
                submit_button();
                ?>
            </form>
        </div>
        <style>
            .wrap {
                background-color: #f7f7f7;
                padding: 20px;
                border-radius: 8px;
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
                max-width: 700px;
                margin: 0 auto;
            }

            h1 {
                font-size: 2em;
                color: #333;
                text-align: center;
            }

            .form-table th {
                background-color: #f1f1f1;
                padding: 10px;
                color: #333;
            }

            .form-table td {
                padding: 10px;
            }

            .button {
                background-color: #ff8c00;
                color: #fff;
                border: none;
                padding: 10px 20px;
                border-radius: 5px;
                cursor: pointer;
                transition: background-color 0.3s;
            }

            .button:hover {
                background-color: #e07b00;
            }

            .large-text {
                font-size: 1rem;
                line-height: 1.5;
                width: 100%;
                padding: 10px;
                border-radius: 5px;
                border: 1px solid #ccc;
                box-sizing: border-box;
            }

            .description {
                font-size: 0.875em;
                color: #777;
            }
        </style>
    <?php
    }
}


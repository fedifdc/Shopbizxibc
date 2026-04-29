<?php
/**
 * Template that show the funds history
 *
 * @package YITH\AccountFunds\Templates
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<div class="history_funds">
	<?php echo do_shortcode( '[yith_ywf_show_history pagination="yes"]' ); ?>
</div>

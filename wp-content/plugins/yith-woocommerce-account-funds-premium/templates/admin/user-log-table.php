<?php
/**
 * Show in admin the users fund log
 *
 * @package YITH\AccountFunds\Templates\Admin
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$current_tab = isset( $_GET['tab'] ) ? wp_unslash( $_GET['tab'] ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

$list_table_args = array(
	'type'  => 'list-table',
	'class' => '',
	'title' => '',
);
if ( isset( $_GET['action'] ) && 'update' === $_GET['action'] && isset( $_GET['user_id'] ) ) {
	$list_table_args['list_table_class']     = 'YITH_YWF_Users_Log_Table';
	$list_table_args['list_table_class_dir'] = YITH_FUNDS_INC . 'tables/class.yith-ywf-users-log-table.php';
	$list_table_args['title']                = __( 'User Log', 'yith-woocommerce-delivery-date' );
	$list_table_args['id']                   = 'ywf_user_log';
	$list_table_args['args']                 = wp_unslash( $_GET['user_id'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
} else {
	$list_table_args['list_table_class']     = 'YITH_WC_Funds_User_List_Table';
	$list_table_args['list_table_class_dir'] = YITH_FUNDS_INC . 'tables/class.yith-ywf-user-list.php';
	$page_title                              = __( 'User Funds', 'yith-woocommerce-delivery-date' );
	$list_table_args['id']                   = 'ywf_user_list';
}
$message = '';
if ( isset( $_POST['nonce'] ) && wp_verify_nonce( wp_unslash( $_POST['nonce'] ), basename( __FILE__ ) ) && isset( $_POST['user_id'] ) && isset( $_POST['user_funds'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

	$user_id   = wp_unslash( $_POST['user_id'] ); // phpcs:ignore  WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
	$user      = get_user_by( 'id', $user_id );
	$fund_user = new YITH_YWF_Customer( $user_id );
	$new_funds = floatval( $_POST['user_funds'] );  // phpcs:ignore  WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
	$old_funds = floatval( $fund_user->get_funds() );
	$desc_op   = isset( $_POST['admin_desc_op'] ) ? esc_html( wp_unslash( $_POST['admin_desc_op'] ) ) : ''; // phpcs:ignore  WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

	if ( $new_funds !== $old_funds ) {
		$diff_funds = $new_funds - $old_funds;

		$fund_user->set_funds( $new_funds );

		$log_args = array(
			'user_id'        => $user_id,
			'editor_id'      => get_current_user_id(),
			'type_operation' => 'admin_op',
			'fund_user'      => $diff_funds,
			'description'    => $desc_op,
		);

		$email_args = array(
			'user_id'       => $user_id,
			'log_date'      => date_i18n( wc_date_format(), gmdate( wc_date_format() ) ),
			'before_funds'  => $old_funds,
			'after_funds'   => $new_funds,
			'change_reason' => $desc_op,
		);

		WC()->mailer();
		do_action( 'ywf_send_advise_user_fund_email_notification', $email_args );
		YWF_Log()->add_log( $log_args );

		$message = __( 'User funds edited successfully', 'yith-woocommerce-account-funds' );
	}
}
?>

<div id="yith_funds_panel_<?php echo esc_attr( $current_tab ); ?>" class="yith-plugin-fw  yit-admin-panel-container">
	<div class="yit-admin-panel-content-wrap">
		<form id="plugin-fw-wc" method="post">

			<?php
			if ( isset( $_GET['action'] ) && 'update' === $_GET['action'] ) {
				$user_id    = wp_unslash( $_GET['user_id'] );  // phpcs:ignore  WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
				$user       = get_user_by( 'id', $user_id );
				$fund_user  = new YITH_YWF_Customer( $user_id );
				$user_name  = $user->display_name;
				$page_title = sprintf( '<h2>%s %s</h2>', __( 'Edit funds for', 'yith-woocommerce-account-funds' ), esc_html( $user_name ) );
				$url_args   = esc_url( remove_query_arg( array( 'user_id', 'action' ) ) );
				?>
				<?php echo $page_title; //phpcs:ignore WordPress.Security.EscapeOutput ?>
				<?php if ( ! empty( $message ) ) : ?>
					<div id="message" class="updated below-h2"><p><?php echo esc_html( $message ); ?></p></div>
				<?php endif; ?>
				<table class="form-table">
					<tbody>
					<tr valign="top" class="titledesc">
						<th scope="row"><label
								for="old_funds"><?php echo sprintf( '%s (%s)', esc_html__( 'Current funds', 'yith-woocommerce-account-funds' ), get_woocommerce_currency_symbol() ); //phpcs:ignore WordPress.Security.EscapeOutput ?></label>
						</th>
						<td class="forminp">
							<input type="number" min="0" required step="any" name="user_funds" value="<?php echo esc_attr( $fund_user->get_funds() ); ?>">
							<input type="hidden" name="nonce" value="<?php echo esc_attr( wp_create_nonce( basename( __FILE__ ) ) ); ?>">
							<input type="hidden" name="user_id" value="<?php echo esc_attr( $user_id ); ?>">
							<span
								class="description"><?php esc_html_e( 'Edit user\'s funds!', 'yith-woocommerce-account-funds' ); ?></span>
						</td>
					</tr>
					<tr valign="top" class="titledesc">
						<th scope="row"><label
								for="admin_desc_op"><?php esc_html_e( 'Description', 'yith-woocommerce-account-funds' ); ?></label>
						</th>
						<td class="forminp">
							<textarea style="width: 500px;" required name="admin_desc_op" rows="5" cols="30"></textarea>
							<p class="description"><?php esc_html_e( 'Enter a brief description', 'yith-woocommerce-account-funds' ); ?></p>
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<p class="submit" style="float: left;margin: 0 10px 0 0;">
								<a class="button-secondary" href="<?php echo esc_url( $url_args ); ?>"><?php esc_html_e( 'Return to user list', 'yith-woocommerce-account-funds' ); ?></a>
								<input type="submit" class="button-primary" value="<?php esc_attr_e( 'Change user fund', 'yith-woocommerce-account-funds' ); ?>">
							</p>
						</td>
					</tr>
					</tbody>
				</table>
				<input type="hidden" name="action" value="<?php echo esc_attr( wp_unslash( $_GET['action'] ) );  // phpcs:ignore  WordPress.Security.ValidatedSanitizedInput.InputNotSanitized ?>">
				<?php
			}
			?>


			<table class="form-table">
				<tbody>
				<tr valign="top">
					<td><?php yith_plugin_fw_get_field( $list_table_args, true ); ?></td>
				</tr>
				</tbody>
			</table>

		</form>

	</div>
</div>

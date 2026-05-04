<?php

function plugin_resi_do_send_email( $order = false, $test = false, $custom_customer_email = false ) {
	$options = get_option( 'plugin_resi_options' );

	// Send to Admin First.
	$order_shipped_email = wc_locate_template( 'templates/emails/order-shipped.php', false, PLUGIN_RESI_PATH );

	$mailer = WC()->mailer();
	if ( 'yes' !== $order->get_meta( 'shipped_email_sent', true ) ) {
		$email_heading = __( 'Your order has been shipped', 'plugin-resi' );
	} else {
		$email_heading = __( 'Your order tracking number', 'plugin-resi' );
	}
	$sent_to_admin = true;
	$plain_text    = false;

	// get the preview email content.
	if ( ! empty( $options['email_body'] ) ) {
		$admin_email = plugin_resi_get_email_content( $options['email_body'], $order, $email_heading, $sent_to_admin, $plain_text );
	} else {
		ob_start();
		include $order_shipped_email;
		$admin_email = ob_get_clean();
	}

	// create a new email.
	$email_admin = new WC_Email();

	// wrap the content with the email template and then add styles.
	$admin_email = $email_admin->style_inline( $admin_email );

	// Send to customer.
	$mailer = WC()->mailer();

	$sent_to_admin = false;

	// get the preview email content.
	if ( ! empty( $options['email_body'] ) ) {
		$customer_email = plugin_resi_get_email_content( $options['email_body'], $order, $email_heading, $sent_to_admin, $plain_text );
	} else {
		ob_start();
		include $order_shipped_email;
		$customer_email = ob_get_clean();
	}

	// create a new email.
	$email_customer = new WC_Email();

	// wrap the content with the email template and then add styles.
	$customer_email = $email_customer->style_inline( $customer_email );

	// custom customer email or not.
	if ( $custom_customer_email ) {
		$billing_email = $custom_customer_email;
	} else {
		$billing_email = $order->get_billing_email() == false ? get_userdata( $order->post->post_author ) : $order->get_billing_email();
	}

	if ( ! empty( $options['email_subject'] ) ) {
		$email_subject = $options['email_subject'];
		$email_subject = str_replace( '{order_number}', '#' . $order->get_order_number(), $email_subject );
	} else {
		$email_subject = __( 'Order shipping information', 'plugin-resi' );
	}

	if ( ! $test ) {
		add_filter( 'wp_mail_content_type', 'plugin_resi_set_content_type' );

		$email_customer->send(
			$billing_email,
			$email_subject,
			$customer_email, // + _billing_email
			apply_filters( 'woocommerce_email_headers', '', 'shipped', $order, null ),
			array()
		);

		$order->update_meta_data( 'shipped_email_sent', 'yes' );
		$order->save();

		$options = get_option( 'plugin_resi_options' );

		// also send email to admin or not.
		if ( isset( $options['email_to_admin'] ) && 'yes' === $options['email_to_admin'] ) {
			$email_customer->send(
				get_option( 'admin_email' ),
				$email_subject,
				$admin_email,
				apply_filters( 'woocommerce_email_headers', '', 'shipped', $order, null ),
				array()
			);
		}
	}
}

function plugin_resi_set_content_type( $content_type ) {
	return 'text/html';
}

function plugin_resi_get_email_content( $email_body, $order, $email_heading, $sent_to_admin, $plain_text ) {
	$user = get_userdata( $order->get_customer_id() );

	$order_number = sprintf(
		/* Translators: %s: order number */
		__( 'Order #%s', 'woocommerce' ),
		$order->get_order_number()
	);

	$order_number_link = '<a class="link" href="' . admin_url( 'post.php?post=' . $order->get_id() . '&action=edit' ) . '">' . $order_number . '</a>';

	$order_date = sprintf(
		'<time datetime="%s">%s</time>',
		date_i18n( 'c', strtotime( $order->get_date_created() ) ),
		date_i18n( wc_date_format(), strtotime( $order->get_date_created() ) )
	);

	$resi   = plugin_resi_get_order_data( $order->get_id() );
	$detail = plugin_resi_get_ekspedisi( $resi['courier'] );

	$name = $detail ? $detail['nama'] : $resi['courier'];
	if ( ! empty( $resi['service'] ) ) {
		$name .= ' - ' . $resi['service'];
	}
	$url  = $detail ? $detail['url'] : '';
	$resi = $resi['tracking'];

	$shipping_details = '<table class="td" cellspacing="0" cellpadding="6" style="width: 100%; font-family: \'Helvetica Neue\', Helvetica, Roboto, Arial, sans-serif;" border="1">
		<thead>
			<tr>
				<th class="td" scope="col" style="text-align:left;">' . __( 'Shipping Courier', 'plugin-resi' ) . '</th>
				<th class="td" scope="col" style="text-align:left;">' . __( 'Website', 'plugin-resi' ) . '</th>
				<th class="td" scope="col" style="text-align:left;">' . __( 'Tracking Number', 'plugin-resi' ) . '</th>
			</tr>
		</thead>
		<tbody>
		<tr>
			<td class="td" scope="col">' . $name . '</td>
			<td class="td" scope="col" >
				<a href="' . $url . '">' . $url . '</a>
			</td>
			<td class="td" scope="col" >' . $resi . '</td>
		</tr>
		</tbody>
	</table>';

	$email_body = str_replace( '{user}', $user->user_login, $email_body );
	$email_body = str_replace( '{order_date}', $order_date, $email_body );
	$email_body = str_replace( '{order_number}', '#' . $order->get_order_number(), $email_body );
	$email_body = str_replace( '{shipping_details}', $shipping_details, $email_body );
	$email_body = wpautop( $email_body );

	ob_start();
	do_action( 'woocommerce_email_header', $email_heading );
	$email_header = ob_get_clean();

	ob_start();
	do_action( 'woocommerce_email_customer_details', $order, $sent_to_admin, $plain_text, null );
	$customer_details = ob_get_clean();

	ob_start();
	do_action( 'woocommerce_email_footer' );
	$email_footer = ob_get_clean();

	return $email_header . $email_body . $customer_details . $email_footer;
}

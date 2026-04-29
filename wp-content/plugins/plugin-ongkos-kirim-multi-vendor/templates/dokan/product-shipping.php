<div class="dokan-other-options dokan-edit-row dokan-clearfix">
	<div class="dokan-section-heading" data-togglehandler="dokan_product_shipping">
		<h2><i class="fa fa-cog" aria-hidden="true"></i> <?php esc_html_e( 'Shipping', 'pokmv' ); ?></h2>
		<p><?php esc_html_e( 'Set your product shipping', 'pokmv' ); ?></p>
		<a href="#" class="dokan-section-toggle">
			<i class="fa fa-sort-desc fa-flip-vertical" aria-hidden="true"></i>
		</a>
		<div class="dokan-clearfix"></div>
	</div>

	<div class="dokan-section-content">
		<div class="dokan-form-group">
			<label for="_weight" class="form-label"><?php echo esc_html__( 'Weight', 'pokmv' ) . ' (' . get_option( 'woocommerce_weight_unit' ) . ')'; ?></label>
			<?php dokan_post_input_box( $post_id, '_weight', array( 'placeholder' => wc_format_localized_decimal( 0 ), 'step' => 0.1, 'min' => 0 ), 'number' ); ?>
		</div>
		<?php if ( wc_product_dimensions_enabled() ) : ?>
			<div class="dokan-form-group">
				<label class="form-label"><?php printf( __( 'Dimensions (%s)', 'pokmv' ), get_option( 'woocommerce_dimension_unit' ) ); ?></label>
				<div class="pokmv-row">
					<div class="pokmv-col-third">
						<?php dokan_post_input_box( $post_id, '_length', array( 'placeholder' => __( 'Length', 'pokmv' ), 'min' => 0 ), 'number' ); ?>	
					</div>
					<div class="pokmv-col-third">
						<?php dokan_post_input_box( $post_id, '_width', array( 'placeholder' => __( 'Width', 'pokmv' ), 'min' => 0 ), 'number' ); ?>	
					</div>
					<div class="pokmv-col-third">
						<?php dokan_post_input_box( $post_id, '_height', array( 'placeholder' => __( 'Height', 'pokmv' ), 'min' => 0 ), 'number' ); ?>	
					</div>
				</div>
			</div>
		<?php endif; ?>

		<div class="dokan-clearfix"></div>

		<div class="dokan-form-group">
			<?php $enable_insurance = ( 'yes' === get_post_meta( $post_id, 'enable_insurance', true ) ) ? 'yes' : 'no'; ?>
			<?php dokan_post_input_box( $post_id, 'enable_insurance', array('value' => $enable_insurance, 'label' => __( 'Add shipping insurance fee on checkout', 'pokmv' ) ), 'checkbox' ); ?>
		</div>

		<div class="dokan-form-group">
			<?php $enable_timber_packing = ( 'yes' === get_post_meta( $post_id, 'enable_timber_packing', true ) ) ? 'yes' : 'no'; ?>
			<?php dokan_post_input_box( $post_id, 'enable_timber_packing', array('value' => $enable_timber_packing, 'label' => __( 'Add timber packing fee on checkout', 'pokmv' ) ), 'checkbox' ); ?>
		</div>

	</div>
</div>
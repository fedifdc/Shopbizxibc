<div id="tpc-setting-form">
	<div class="panels">
		<div class="left-panel">

			<?php do_action( 'tpc_form_setting_start', $this->setting ); ?>

			<div class="panel-section">
				<h3><?php esc_html_e( 'Available Fields', 'tpc' ) ?></h3>
				<p><?php esc_html_e( 'Click on these buttons to add the field to the form. Each field can only be used once.', 'tpc' ) ?></p>
				<p class="buttons">
					<?php foreach ( $fields as $key => $field ) : ?>
						<button <?php echo false !== stripos( $settings['form_html'], '[' . $key . ']' ) ? 'disabled' : '' ?> type="button" class="button" data-field="<?php echo esc_attr( $key ) ?>"><?php echo esc_html( $field['name'] ) ?><?php echo $field['required'] && 'recaptcha' !== $key ? ' (required)' : ''; ?></button>
					<?php endforeach; ?>
				</p>
				<textarea name="tpc_setting[form_html]" id="form-editor"><?php echo esc_textarea( $settings['form_html'] ); ?></textarea>
			</div>
			<div class="panel-section">
				<h3><?php esc_html_e( 'Custom CSS', 'tpc' ) ?></h3>
				<p><?php esc_html_e( 'Use this field to add custom style to your form', 'tpc' ) ?></p>
				<textarea name="tpc_setting[form_css]" id="css-editor"><?php echo esc_textarea( $settings['form_css'] ); ?></textarea>
			</div>

			<?php do_action( 'tpc_form_setting_end', $this->setting ); ?>

		</div>
		<div class="right-panel">
			<h3><?php esc_html_e( 'Field Setting', 'tpc' ) ?></h3>
			<div id="field-setting">
				<?php foreach ( $fields as $key => $field ) : ?>
					<h4 class="accr-<?php echo esc_attr( $key ) ?> <?php echo false !== stripos( $settings['form_html'], '[' . $key . ']' ) ? 'active' : '' ?>"><?php echo esc_html( $field['name'] ); ?></h4>
					<div class="accr-<?php echo esc_attr( $key ) ?> content form-wrap">
						<label>
							<?php esc_html_e( 'Required field?', 'tpc' ) ?>
							<?php if ( $field['required'] ) : ?>
								<input type="hidden" name="tpc_setting[field_<?php echo esc_attr( $key ) ?>_required]" value="1">
								<input type="checkbox" value="1" <?php echo $field['required'] ? 'disabled' : '' ?> checked>
							<?php else: ?>
								<input type="checkbox" name="tpc_setting[field_<?php echo esc_attr( $key ) ?>_required]" value="1" <?php echo $settings[ 'field_' . $key . '_required' ] ? 'checked' : '' ?>>
							<?php endif; ?>
						</label>
						<label>
							<?php esc_html_e( 'Additional class', 'tpc' ) ?>
							<input type="text" name="tpc_setting[field_<?php echo esc_attr( $key ) ?>_class]" value="<?php echo esc_attr( $settings[ 'field_' . $key . '_class' ] ) ?>">
						</label>
						<?php if ( isset( $field['additional'] ) ) : ?>
							<?php foreach ( $field['additional'] as $akey => $afield ) : ?>
								<label>
									<?php echo esc_html( $afield['label'] ) ?>
									<input type="<?php echo esc_html( $afield['type'] ) ?>" name="tpc_setting[field_<?php echo esc_attr( $key ) ?>_<?php echo esc_html( $akey ) ?>]" value="<?php echo esc_attr( $settings[ 'field_' . $key . '_' . $akey ] ) ?>">
								</label>
							<?php endforeach; ?>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</div>
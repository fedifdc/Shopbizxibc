<?php
/**
 * Admin Import form
 *
 * @link       https://www.solwininfotech.com/
 * @since      1.0.0
 *
 * @package    Blog_Designer_PRO
 * @subpackage Blog_Designer_PRO/admin
 * @author     Solwin Infotech <info@solwininfotech.com>
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
global $import_success, $import_error;
?>
<div class="wrap">
	<h2>
		<?php esc_html_e( 'Import Blog Layouts', 'blog-designer-pro' ); ?>
	</h2>
	<?php
	if ( isset( $import_error ) ) {
		?>
		<div class="error notice">
			<p><?php echo esc_html( $import_error ); ?></p>
		</div>
		<?php
	}
	if ( isset( $import_success ) ) {
		?>
		<div class="updated notice">
			<p><?php echo esc_html( $import_success ); ?></p>
		</div>
		<?php
	}
	?>
	<div class="narrow">
		<p>
			<?php esc_html_e( 'Select import type and Choose a .txt file to upload, then click Upload file and import', 'blog-designer-pro' ); ?>
		</p>
		<form method="post" id="bdp-import-upload-form" class="bdp-upload-form" enctype="multipart/form-data" name="bdp-import-upload-form">
			<p>
				<?php wp_nonce_field( 'bdp_import', 'bdp_import_nonce' ); ?>
				<label><?php esc_html_e( 'Import Type', 'blog-designer-pro' ); ?> : </label>
				<select id="layout_import_types" name="layout_import_types">
					<option value=""><?php esc_html_e( 'Please Select', 'blog-designer-pro' ); ?></option>
					<option value="blog_layouts"><?php esc_html_e( 'Blog Layouts', 'blog-designer-pro' ); ?></option>
					<option value="archive_layouts"><?php esc_html_e( 'Archive Layouts', 'blog-designer-pro' ); ?></option>
					<option value="single_layouts"><?php esc_html_e( 'Single Layouts', 'blog-designer-pro' ); ?></option>
					<?php if ( Bdp_Woocommerce::is_woocommerce_plugin() ) { ?>
					<option value="product_archive_layouts"><?php esc_html_e( 'Product Archive Layouts', 'blog-designer-pro' ); ?></option>
					<option value="product_single_layouts"><?php esc_html_e( 'Product Single Layouts', 'blog-designer-pro' ); ?></option>
						<?php
					}
					if ( is_plugin_active( 'easy-digital-downloads/easy-digital-downloads.php' ) ) {
						?>
						<option value="download_archive_layouts"><?php esc_html_e( 'Download Product Archive Layouts', 'blog-designer-pro' ); ?></option>
						<option value="download_single_layouts"><?php esc_html_e( 'Download Product Single Layouts', 'blog-designer-pro' ); ?></option>
					<?php } ?>
				</select>
			</p>
			<p>
				<label for="bdp_import_layout"><?php esc_html_e( 'Choose a file from your computer', 'blog-designer-pro' ); ?> : </label>
				<input type="file" id="bdp_import_layout" name="bdp_import">
				<?php esc_html_e( 'To download Sample Blog Layout, please', 'blog-designer-pro' ); ?> <a class="download-sample-layout" href="<?php echo esc_url( BLOGDESIGNERPRO_URL ) . '/admin/assets/sample_layout.txt'; ?>" download><?php esc_html_e( 'click here', 'blog-designer-pro' ); ?></a>
			</p>
			<p>
				<strong><?php esc_html_e( 'Note', 'blog-designer-pro' ); ?>:</strong> <?php esc_html_e( 'If you have a query or face any issue while importing layout, please refer', 'blog-designer-pro' ); ?> <a href="<?php echo esc_url( 'https://www.solwininfotech.com/documents/wordpress/blog-designer-pro/#import_export' ); ?>" target="_blank"><?php esc_html_e( 'Blog Designer PRO Document', 'blog-designer-pro' ); ?></a>
			</p>
			<p class="submit">
				<input id="submit" class="button button-primary" type="submit" value="<?php esc_attr_e( 'Upload file and import', 'blog-designer-pro' ); ?>" name="submit">
			</p>

		</form>
	</div>
</div>

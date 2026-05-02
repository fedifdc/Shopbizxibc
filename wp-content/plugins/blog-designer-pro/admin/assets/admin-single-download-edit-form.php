<?php
/**
 * Add/Edit Single Layout setting page
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

global $wp_version, $wpdb, $bdp_errors, $bdp_success, $bdp_settings;
if ( isset( $_GET['page'] ) && 'single_edd_download' === $_GET['page'] ) {
	$page                 = esc_attr( sanitize_text_field( wp_unslash( $_GET['page'] ) ) );
	$bdp_settings         = array();
	$custom_single_type   = '';
	$single_template_name = '';
	if ( isset( $_GET['id'] ) && ! empty( $_GET['id'] ) && is_numeric( $_GET['id'] ) ) {
		$single_id       = intval( $_GET['id'] );
		$table_name      = $wpdb->prefix . 'bdp_single_ed_download';
		$get_allsettings = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}bdp_single_ed_download WHERE ID = %d", $single_id ), ARRAY_A );
		if ( ! isset( $get_allsettings[0]['settings'] ) ) {
			echo '<div class="updated notice">';
			wp_die( esc_html__( 'You attempted to edit an item that doesn’t exist. Perhaps it was deleted?', 'blog-designer-pro' ) );
			echo '</div>';
		}
		$allsettings = $get_allsettings[0]['settings'];
		if ( is_serialized( $allsettings ) ) {
			$bdp_settings         = maybe_unserialize( $allsettings );
			$custom_single_type   = $get_allsettings[0]['single_download_template'];
			$single_template_name = $get_allsettings[0]['single_download_name'];
		}
	}
}
$font_family   = Bdp_Utility::default_recognized_font_faces();
$template_name = isset( $bdp_settings['template_name'] ) ? $bdp_settings['template_name'] : 'classical';

$tempate_list = Bdp_Template::single_blog_template_list();

if ( 'brite' === $template_name || 'minimal' === $template_name || 'clicky' === $template_name ) {
	$winter_category_txt = esc_html__( 'Choose Tags Background Color', 'blog-designer-pro' );
} else {
	$winter_category_txt = esc_html__( 'Choose Category Background Color', 'blog-designer-pro' );
}
?>
<div class="wrap">
	<?php
	if ( isset( $bdp_errors ) ) {
		if ( is_wp_error( $bdp_errors ) ) {
			?>
			<div class="error notice">
				<p><?php echo esc_html( $bdp_errors->get_error_message() ); ?></p>
			</div>
			<?php
		}
	}
	if ( isset( $bdp_success ) ) {
		?>
		<div class="updated notice">
			<p><?php echo wp_kses( $bdp_success, Bdp_Admin_Functions::args_kses() ); ?></p>
		</div>
		<?php
	}
	if ( isset( $_GET['message'] ) && 'single_added_msg' === $_GET['message'] ) {
		?>
		<div class="updated notice">
			<p><?php esc_html_e( 'Single layout added successfully.', 'blog-designer-pro' ); ?></p>
		</div>
		<?php
	}
	?>
	<h1 class="bdp-shortcode-div">
		<div class= "pull-left bdp_edit_layout">
		<?php
		if ( isset( $_GET['action'] ) && 'edit' === $_GET['action'] && isset( $_GET['id'] ) ) {
			esc_html_e( 'Edit Single Product Design', 'blog-designer-pro' );
		} else {
			esc_html_e( 'Add Single Product Layout', 'blog-designer-pro' );
		}
		?>
		</div>
		<?php
		if ( isset( $_GET['action'] ) && 'edit' === $_GET['action'] && isset( $_GET['id'] ) ) {
			?>
			<div class="pull-right">
			<a class="page-title-action bdp_create_new_layout" href="?page=single_edd_download">
				<?php esc_html_e( 'Create New Product Single Layout', 'blog-designer-pro' ); ?>
			</a>
			</div>
			<?php
		}
		?>
	</h1>
	<?php
	if ( isset( $_GET['message'] ) && 'shortcode_duplicate_msg' === $_GET['message'] ) {
		?>
		<div class="updated notice">
			<p><?php esc_html_e( 'Layout duplicated successfully.  Please Select Products Categories or Tags and Select Products for this layout.', 'blog-designer-pro' ); ?></p>
		</div>
		<?php
	}
	?>
	<form method="post" id="single-layout-form" action="" class="bd-form-class single-layout-download">
		<?php
		wp_nonce_field( 'bdp-download-shortcode-form-submit', 'bdp-download-submit-nonce' );
		$page = '';
		if ( isset( $_GET['page'] ) && '' != $_GET['page'] ) {
			$page = esc_attr( sanitize_text_field( wp_unslash( $_GET['page'] ) ) );
			?>
			<input type="hidden" name="originalpage" class="bdporiginalpage" value="<?php echo esc_attr( $page ); ?>">
		<?php } ?>
		<div id="poststuff">
			<div class="postbox-container bd-settings-wrappers bd_poststuff">
				<div class="bd-header-wrapper">
					<div class="bd-logo-wrapper pull-left">
						<h3><?php esc_html_e( 'Blog designer settings', 'blog-designer-pro' ); ?></h3>
					</div>
					<div class="pull-right">
						<a id="bdp-btn-single" title="<?php esc_html_e( 'Save Changes', 'blog-designer-pro' ); ?>" class="show_single_save button submit_fixed button-primary">
							<span><?php esc_html_e( 'Save Changes', 'blog-designer-pro' ); ?></span>
						</a>
						<input type="submit" style="display:none;" class="button-primary bdp_single_save_btn" name="savedata" value="<?php esc_attr_e( 'Save Changes', 'blog-designer-pro' ); ?>" />
					</div>
				</div>
				<div class="bd-menu-setting">
					<?php
					$bdpgeneral_class                   = '';
					$bdpmedia_class                     = '';
					$bdpstandard_class                  = '';
					$bdptitle_class                     = '';
					$bdpauthor_class                    = '';
					$bdpcontent_class                   = '';
					$bdprelatd_class                    = '';
					$bdpsinglepostnavigation_class      = '';
					$bdpsocial_class                    = '';
					$bdpsingleeddsetting_class          = '';
					$bdpacffieldssetting_class          = '';
					$bdpgeneral_class_show              = '';
					$bdpmedia_class_show                = '';
					$bdpstandard_class_show             = '';
					$bdptitle_class_show                = '';
					$bdpauthor_class_show               = '';
					$bdpcontent_class_show              = '';
					$bdprelated_class_show              = '';
					$bdpsinglepostnavigation_class_show = '';
					$bdpsocial_class_show               = '';
					$bdpsingleeddsetting_class_show     = '';
					$bdpacffieldssetting_class_show     = '';
					if ( Bdp_Posts::postbox_classes( 'bdpsinglegeneral', $page ) ) {
						$bdpgeneral_class      = 'bd-active-tab';
						$bdpgeneral_class_show = 'display:block';
					} elseif ( Bdp_Posts::postbox_classes( 'bdpsinglestandard', $page ) ) {
						$bdpstandard_class      = 'bd-active-tab';
						$bdpstandard_class_show = 'display:block';
					} elseif ( Bdp_Posts::postbox_classes( 'bdpsingletitle', $page ) ) {
						$bdptitle_class      = 'bd-active-tab';
						$bdptitle_class_show = 'display:block';
					} elseif ( Bdp_Posts::postbox_classes( 'bdpsinglepostauthor', $page ) ) {
						$bdpauthor_class      = 'bd-active-tab';
						$bdpauthor_class_show = 'display:block';
					} elseif ( Bdp_Posts::postbox_classes( 'bdpsingleconent', $page ) ) {
						$bdpcontent_class      = 'bd-active-tab';
						$bdpcontent_class_show = 'display:block';
					} elseif ( Bdp_Posts::postbox_classes( 'bdpsinglemedia', $page ) ) {
						$bdpmedia_class      = 'bd-active-tab';
						$bdpmedia_class_show = 'display:block';
					} elseif ( Bdp_Posts::postbox_classes( 'bdpsingleeddsetting', $page ) ) {
						$bdpsingleeddsetting_class      = 'bd-active-tab';
						$bdpsingleeddsetting_class_show = 'display:block';
					} elseif ( Bdp_Posts::postbox_classes( 'bdpsinglerelated', $page ) ) {
						$bdprelatd_class       = 'bd-active-tab';
						$bdprelated_class_show = 'display:block';
					} elseif ( Bdp_Posts::postbox_classes( 'bdpsinglepostnavigation', $page ) ) {
						$bdpsinglepostnavigation_class      = 'bd-active-tab';
						$bdpsinglepostnavigation_class_show = 'display:block';
					} elseif ( Bdp_Posts::postbox_classes( 'bdpacffield', $page ) ) {
						$bdpsocial_class      = 'bd-active-tab';
						$bdpsocial_class_show = 'display:block';
					} elseif ( Bdp_Posts::postbox_classes( 'bdpsinglesocial', $page ) ) {
						$bdpsocial_class      = 'bd-active-tab';
						$bdpsocial_class_show = 'display:block';
					} else {
						$bdpgeneral_class      = 'bd-active-tab';
						$bdpgeneral_class_show = 'display:block';
					}
					?>
					<ul class="bd-setting-handle">
						<li data-show="bdpsinglegeneral" class=<?php echo esc_attr( $bdpgeneral_class ); ?>>
						<div class="bdp_tab_icon">
							<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"><path fill="currentColor" d="M19.14 12.94c.04-.3.06-.61.06-.94c0-.32-.02-.64-.07-.94l2.03-1.58a.49.49 0 0 0 .12-.61l-1.92-3.32a.488.488 0 0 0-.59-.22l-2.39.96c-.5-.38-1.03-.7-1.62-.94l-.36-2.54a.484.484 0 0 0-.48-.41h-3.84c-.24 0-.43.17-.47.41l-.36 2.54c-.59.24-1.13.57-1.62.94l-2.39-.96c-.22-.08-.47 0-.59.22L2.74 8.87c-.12.21-.08.47.12.61l2.03 1.58c-.05.3-.09.63-.09.94s.02.64.07.94l-2.03 1.58a.49.49 0 0 0-.12.61l1.92 3.32c.12.22.37.29.59.22l2.39-.96c.5.38 1.03.7 1.62.94l.36 2.54c.05.24.24.41.48.41h3.84c.24 0 .44-.17.47-.41l.36-2.54c.59-.24 1.13-.56 1.62-.94l2.39.96c.22.08.47 0 .59-.22l1.92-3.32c.12-.22.07-.47-.12-.61l-2.01-1.58zM12 15.6c-1.98 0-3.6-1.62-3.6-3.6s1.62-3.6 3.6-3.6s3.6 1.62 3.6 3.6s-1.62 3.6-3.6 3.6z"/></svg>
						</div>
							<span><?php esc_html_e( 'General Settings', 'blog-designer-pro' ); ?></span>
						</li>
						<li data-show="bdpsinglestandard" class=<?php echo esc_attr( $bdpstandard_class ); ?>>
						<div class="bdp_tab_icon">
							<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"><path fill="currentColor" d="M10.788 3.102c.495-1.003 1.926-1.003 2.421 0l2.358 4.778l5.273.766c1.107.16 1.549 1.522.748 2.303l-.905.882a6.5 6.5 0 0 0-9.441 7.43l-3.96 2.081c-.99.52-2.147-.32-1.958-1.423l.9-5.251l-3.815-3.72c-.801-.78-.359-2.141.748-2.302L8.43 7.88l2.358-4.778Zm3.49 10.873a2 2 0 0 1-1.441 2.496l-.584.145a5.729 5.729 0 0 0 .006 1.807l.54.13a2 2 0 0 1 1.45 2.51l-.187.631c.44.386.94.7 1.484.922l.494-.519a2 2 0 0 1 2.899 0l.498.525a5.276 5.276 0 0 0 1.483-.912l-.198-.686a2 2 0 0 1 1.441-2.497l.584-.144a5.718 5.718 0 0 0-.006-1.807l-.54-.13a2 2 0 0 1-1.45-2.51l.187-.631a5.278 5.278 0 0 0-1.484-.922l-.493.518a2 2 0 0 1-2.9 0l-.498-.525c-.544.22-1.044.53-1.483.913l.198.686Zm3.222 5.024c-.8 0-1.45-.671-1.45-1.5c0-.828.65-1.5 1.45-1.5c.8 0 1.45.672 1.45 1.5c0 .829-.65 1.5-1.45 1.5Z"/></svg>
						</div>
							<span><?php esc_html_e( 'Standard Settings', 'blog-designer-pro' ); ?></span>
						</li>
						<li data-show="bdpsingletitle" class=<?php echo esc_attr( $bdptitle_class ); ?>>
						<div class="bdp_tab_icon">
							<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16"><path fill="currentColor" fill-rule="evenodd" d="M.75 2a.75.75 0 0 0-.75.75v1.5a.75.75 0 0 0 1.5 0V3.5h2.75v9h-.5a.75.75 0 0 0 0 1.5h2.5a.75.75 0 0 0 0-1.5h-.5v-9H8.5v.75a.75.75 0 0 0 1.5 0v-1.5A.75.75 0 0 0 9.25 2H.75ZM8 7.75A.75.75 0 0 1 8.75 7h6.5a.75.75 0 0 1 0 1.5h-6.5A.75.75 0 0 1 8 7.75Zm0 3.5a.75.75 0 0 1 .75-.75h6.5a.75.75 0 0 1 0 1.5h-6.5a.75.75 0 0 1-.75-.75Z" clip-rule="evenodd"/></svg>
						</div>
							<span><?php esc_html_e( 'Title Settings', 'blog-designer-pro' ); ?></span>
						</li>
						<li data-show="bdpsingleconent" class=<?php echo esc_attr( $bdpcontent_class ); ?>>
						<div class="bdp_tab_icon">
							<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"><path fill="currentColor" d="M14 2H6c-1.11 0-2 .89-2 2v16c0 1.11.89 2 2 2h7.81c-.53-.91-.81-1.95-.81-3c0-.33.03-.67.08-1H6v-2h7.81c.46-.8 1.1-1.5 1.87-2H6v-2h12v1.08c.33-.05.67-.08 1-.08s.67.03 1 .08V8l-6-6m-1 7V3.5L18.5 9H13m5 6v3h-3v2h3v3h2v-3h3v-2h-3v-3h-2Z"/></svg>
						</div>
							<span><?php esc_html_e( 'Content Settings', 'blog-designer-pro' ); ?></span>
						</li>
						<li data-show="bdpsinglemedia" class=<?php echo esc_attr( $bdpmedia_class ); ?>>
						<div class="bdp_tab_icon">
							<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"><path fill="currentColor" d="M2 6H0v5h.01L0 20c0 1.1.9 2 2 2h18v-2H2V6zm20-2h-8l-2-2H6c-1.1 0-1.99.9-1.99 2L4 16c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zM7 15l4.5-6l3.5 4.51l2.5-3.01L21 15H7z"/></svg>
						</div>
							<span><?php esc_html_e( 'Media Settings', 'blog-designer-pro' ); ?></span>
						</li>
						<li data-show="bdpsingleeddsetting" class=<?php echo esc_attr( $bdpsingleeddsetting_class ); ?>>
							<div class="bdp_tab_icon">
								<svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" fill="none"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path fill="#9c9c9c" fill-rule="evenodd" d="M11 2a1 1 0 10-2 0v7.74L5.173 6.26a1 1 0 10-1.346 1.48l5.5 5a1 1 0 001.346 0l5.5-5a1 1 0 00-1.346-1.48L11 9.74V2zm-7.895 9.204A1 1 0 001.5 12v3.867a2.018 2.018 0 002.227 2.002c1.424-.147 3.96-.369 6.273-.369 2.386 0 5.248.236 6.795.383a2.013 2.013 0 002.205-2V12a1 1 0 10-2 0v3.884l-13.895-4.68zm0 0L2.5 11l.605.204zm0 0l13.892 4.683a.019.019 0 01-.007.005h-.006c-1.558-.148-4.499-.392-6.984-.392-2.416 0-5.034.23-6.478.38h-.009a.026.026 0 01-.013-.011V12a.998.998 0 00-.394-.796z"></path> </g></svg>
							</div>
							<span><?php esc_html_e( 'Easy Digital Downloads Settings', 'blog-designer-pro' ); ?></span>
						</li>
						<li data-show="bdpsinglepostnavigation" class=<?php echo esc_attr( $bdpsinglepostnavigation_class ); ?>>
							<div class="bdp_tab_icon">
								<svg xmlns="http://www.w3.org/2000/svg" height="16" width="17" viewBox="0 0 448 512"><path fill="#9c9c9c" d="M438.6 150.6c12.5-12.5 12.5-32.8 0-45.3l-96-96c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L338.7 96 32 96C14.3 96 0 110.3 0 128s14.3 32 32 32l306.7 0-41.4 41.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0l96-96zm-333.3 352c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L109.3 416 416 416c17.7 0 32-14.3 32-32s-14.3-32-32-32l-306.7 0 41.4-41.4c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-96 96c-12.5 12.5-12.5 32.8 0 45.3l96 96z"/></svg>
							</div>
							<span><?php esc_html_e( 'Downloads Navigation (Next/Previous) Settings', 'blog-designer-pro' ); ?></span>
						</li>
						<li data-show="bdpsinglepostauthor" class=<?php echo esc_attr( $bdpauthor_class ); ?>>
						<div class="bdp_tab_icon">
							<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"><path fill="currentColor" d="m21.7 13.35l-1 1l-2.05-2.05l1-1a.55.55 0 0 1 .77 0l1.28 1.28c.21.21.21.56 0 .77M12 18.94l6.06-6.06l2.05 2.05L14.06 21H12v-2.06M12 14c-4.42 0-8 1.79-8 4v2h6v-1.89l4-4c-.66-.08-1.33-.11-2-.11m0-10a4 4 0 0 0-4 4a4 4 0 0 0 4 4a4 4 0 0 0 4-4a4 4 0 0 0-4-4Z"/></svg>
						</div>
							<span><?php esc_html_e( 'Author Settings', 'blog-designer-pro' ); ?></span>
						</li>
						<li data-show="bdpsinglerelated" class=<?php echo esc_attr( $bdprelatd_class ); ?>>
							<div class="bdp_tab_icon">
								<svg fill="#9c9c9c" height="20" width="20" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 369.817 369.817" xml:space="preserve"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g> <path d="M369.312,260.117c-1.937-6.626-8.879-10.428-15.505-8.491l-187.427,54.788c-6.744-9.617-17.7-16.073-30.171-16.652 L63.818,36.715c-0.912-3.188-3.053-5.882-5.951-7.491c-2.898-1.609-6.317-2.001-9.505-1.089L9.065,39.377 c-6.637,1.899-10.479,8.819-8.58,15.456c1.899,6.637,8.819,10.476,15.456,8.58l27.279-7.804l68.957,241.047 c-10.191,7.06-16.883,18.829-16.883,32.138c0,21.548,17.53,39.078,39.078,39.078c21.007,0,38.188-16.664,39.037-37.466 l187.412-54.783C367.447,273.685,371.249,266.744,369.312,260.117z M134.372,342.871c-7.763,0-14.078-6.315-14.078-14.078 s6.315-14.078,14.078-14.078c7.763,0,14.078,6.315,14.078,14.078S142.135,342.871,134.372,342.871z"></path> <path d="M111.649,154.75c0.913,3.211,3.876,5.454,7.208,5.454c0.69,0,1.381-0.097,2.055-0.288l153.908-43.733 c1.927-0.548,3.526-1.813,4.501-3.563c0.975-1.75,1.21-3.775,0.663-5.702L251.706,7.4c-0.913-3.211-3.876-5.454-7.207-5.454 c-0.69,0-1.382,0.097-2.056,0.289L88.535,45.969c-1.927,0.547-3.526,1.813-4.502,3.563c-0.975,1.75-1.211,3.774-0.663,5.702 L111.649,154.75z M150.245,66.393L199.42,52.42c0.675-0.193,1.368-0.29,2.058-0.29c3.33,0,6.293,2.243,7.206,5.454 c1.13,3.978-1.186,8.134-5.164,9.265l-49.174,13.974c-0.674,0.192-1.366,0.289-2.056,0.289c-3.332,0-6.295-2.242-7.208-5.453 C143.951,71.68,146.267,67.524,150.245,66.393z"></path> <path d="M277.928,127.12l-153.907,43.733c-1.927,0.548-3.526,1.813-4.501,3.562c-0.976,1.75-1.212,3.775-0.664,5.702l28.279,99.52 c0.913,3.211,3.877,5.453,7.208,5.453c0.69,0,1.381-0.097,2.055-0.289l153.909-43.734c1.927-0.548,3.526-1.813,4.501-3.562 c0.976-1.751,1.212-3.775,0.664-5.702l-28.279-99.52c-0.912-3.21-3.876-5.453-7.208-5.453 C279.295,126.831,278.603,126.929,277.928,127.12z M243.505,188.171c-0.975,1.75-2.574,3.015-4.501,3.563l-49.174,13.973 c-0.673,0.191-1.365,0.289-2.055,0.289c-3.331,0-6.295-2.243-7.208-5.454c-0.548-1.927-0.312-3.952,0.664-5.702 c0.975-1.75,2.574-3.015,4.501-3.563l49.173-13.973c0.675-0.192,1.367-0.289,2.057-0.289c3.331,0,6.295,2.243,7.207,5.454 C244.717,184.396,244.481,186.421,243.505,188.171z"></path> </g> </g></svg>
							</div>
							<span><?php esc_html_e( 'Related Products Settings', 'blog-designer-pro' ); ?></span>
						</li>
						<?php
						if ( is_plugin_active( 'advanced-custom-fields/acf.php' ) ) {
							$groups = acf_get_field_groups();
							if ( ! empty( $groups ) ) {
								?>
								<li data-show="bdpacffieldssetting" class=<?php echo esc_attr( $bdpacffieldssetting_class ); ?>>
									<div class="bdp_tab_icon">
										<svg fill="#9c9c9c" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20" height="20" viewBox="0 0 32.181 32.18" xml:space="preserve"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g> <path d="M30.037,29.924c-0.67-0.611-1.525-2.094-2.567-4.447L16.539,0.625h-0.424L5.276,24.805 c-1.028,2.311-1.893,3.834-2.593,4.57c-0.7,0.738-1.595,1.189-2.683,1.352v0.828h10.079v-0.828 C8.5,30.608,7.511,30.43,7.107,30.192c-0.687-0.401-1.029-1.026-1.029-1.877c0-0.641,0.209-1.453,0.627-2.438l1.273-2.949h10.705 l1.608,3.777c0.416,0.98,0.642,1.541,0.67,1.676c0.091,0.283,0.135,0.559,0.135,0.826c0,0.447-0.163,0.791-0.49,1.029 c-0.479,0.328-1.305,0.488-2.48,0.488h-0.604v0.828h14.659v-0.828C31.261,30.653,30.545,30.385,30.037,29.924z M8.764,21.274 l4.647-10.436l4.516,10.436H8.764z"></path> </g> </g></svg>
									</div>
									<span><?php esc_html_e( 'ACF Field Settings', 'blog-designer-pro' ); ?></span>
								</li>
								<?php
							}
						}
						?>
						<li data-show="bdpsinglesocial" class=<?php echo esc_attr( $bdpsocial_class ); ?>>
						<div class="bdp_tab_icon">
							<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"><path fill="currentColor" d="M18 22q-1.25 0-2.125-.875T15 19q0-.175.025-.363t.075-.337l-7.05-4.1q-.425.375-.95.588T6 15q-1.25 0-2.125-.875T3 12q0-1.25.875-2.125T6 9q.575 0 1.1.213t.95.587l7.05-4.1q-.05-.15-.075-.337T15 5q0-1.25.875-2.125T18 2q1.25 0 2.125.875T21 5q0 1.25-.875 2.125T18 8q-.575 0-1.1-.212t-.95-.588L8.9 11.3q.05.15.075.338T9 12q0 .175-.025.363T8.9 12.7l7.05 4.1q.425-.375.95-.587T18 16q1.25 0 2.125.875T21 19q0 1.25-.875 2.125T18 22Z"/>
							</svg>
						</div>
							<span><?php esc_html_e( 'Social Share Settings', 'blog-designer-pro' ); ?></span>
						</li>
					</ul>
				</div>
				<div id="bdpsinglegeneral" class="postbox postbox-with-fw-options" style=<?php echo esc_attr( $bdpgeneral_class_show ); ?>>
					<div class="inside">
						<ul class="bdp-settings bdp-lineheight">
							<h3 class="bdp-table-title"><?php esc_html_e( 'Select Single Product Layout', 'blog-designer-pro' ); ?></h3>
							<li>
								<div class="bdp-left">
									<p class="bdp-margin-bottom-50"><?php esc_html_e( 'Select your favorite layout from 45+ powerful single download product layouts.', 'blog-designer-pro' ); ?> </p>
								</div>
								<div class="bdp-right">
									<?php
										$tempate_list = Bdp_Template::single_blog_template_list();
									?>
									<select class="chosen template_name" name="template_name" id="template_name">
										<?php
										foreach ( $tempate_list as $templates ) {
											$tname = substr( $templates['image_name'], 0, strrpos( $templates['image_name'], '.' ) );
											?>
											<option value="<?php echo esc_attr( $tname ); ?>" 
												<?php
												if ( $template_name == $tname ) {
													echo "selected='selected'"; }
												?>
											>
												<?php echo esc_html( $templates['template_name'] ); ?>
											</option>
											<?php } ?>
									</select>
									<div class="select_button_upper_div">
										<div class="bdp_selected_template_image">
											<div 
											<?php
											if ( isset( $template_name ) && empty( $template_name ) ) {
												echo ' class="bdp_no_template_found"';
											}
											?>
											>
											<?php
											if ( isset( $template_name ) && ! empty( $template_name ) ) {
												$image_name = $template_name . '.jpg';
												?>
													<img src="<?php echo esc_url( BLOGDESIGNERPRO_URL ) . '/admin/images/single/' . esc_attr( $image_name ); ?>" alt="
																		<?php
																		if ( isset( $template_name ) ) {
																			echo esc_attr( $tempate_list[ $template_name ]['template_name'] ); }
																		?>
													" title="
												<?php
												if ( isset( $template_name ) ) {
														echo esc_attr( $tempate_list[ $template_name ]['template_name'] ); }
												?>
" />
													<?php
											} else {
												esc_html_e( 'No template exist for selection', 'blog-designer-pro' );
											}
											?>
											</div>
										</div>
										<div class="bdp_reset_btn">
											<?php if ( isset( $_GET['action'] ) && 'edit' === $_GET['action'] && isset( $_GET['id'] ) ) { ?>
											<input type="submit" class="bdp-reset-data" name="resetdata" value="<?php esc_attr_e( 'Reset Layout Settings', 'blog-designer-pro' ); ?>" />
												<?php
											}
											?>
										</div>
									</div>
								</div>
							</li>

							<li class="bdp_single_template_color">
								<div class="bdp-left">
									<span class="bdp-key-title">
										<?php esc_html_e( 'Template Color Preset', 'blog-designer-pro' ); ?>
									</span>
								</div>
								<div class="bdp-right bdp-preset-position">
									<?php
									$bdp_color_preset      = isset( $bdp_settings['bdp_color_preset'] ) ? $bdp_settings['bdp_color_preset'] : $template_name . '_default';
									$template_color_preset = array(
										'boxy'             => array(
											'boxy_default' => array(
												'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#E84159,template_ftcolor:#555555,template_fthovercolor:#E84159,template_titlecolor:#E21130,related_title_color:#E21130,template_contentcolor:#999999,firstletter_contentcolor:#999999,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#E21130,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#E84159',
												'display_value' => '#E21130,#E84159,#555555,#999999',
											),
											'boxy_mckenzie' => array(
												'preset_name' => esc_html__( 'McKenzie', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#A2855B,template_ftcolor:#555555,template_fthovercolor:#A2855B,template_titlecolor:#8B6632,related_title_color:#8B6632,template_contentcolor:#999999,firstletter_contentcolor:#999999,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#8B6632,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#A2855B',
												'display_value' => '#8B6632,#A2855B,#555555,#999999',
											),
											'boxy_black_pearl' => array(
												'preset_name' => esc_html__( 'Black Pearl', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#44545D,template_ftcolor:#555555,template_fthovercolor:#44545D,template_titlecolor:#152934,related_title_color:#152934,template_contentcolor:#999999,firstletter_contentcolor:#999999,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#152934,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#44545D',
												'display_value' => '#152934,#44545D,#555555,#999999',
											),
											'boxy_fun_green' => array(
												'preset_name' => esc_html__( 'Fun Green', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#3E8563,template_ftcolor:#555555,template_fthovercolor:#3E8563,template_titlecolor:#0E663C,related_title_color:#0E663C,template_contentcolor:#999999,firstletter_contentcolor:#999999,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#0E663C,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#3E8563',
												'display_value' => '#0E663C,#3E8563,#555555,#999999',
											),
											'boxy_peru_tan' => array(
												'preset_name' => esc_html__( 'Peru Tan', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#916748,template_ftcolor:#555555,template_fthovercolor:#916748,template_titlecolor:#75411A,related_title_color:#75411A,template_contentcolor:#999999,firstletter_contentcolor:#999999,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#75411A,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#916748',
												'display_value' => '#75411A,#916748,#555555,#999999',
											),
											'boxy_blackberry' => array(
												'preset_name' => esc_html__( 'Blackberry', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#6D4657,template_ftcolor:#555555,template_fthovercolor:#6D4657,template_titlecolor:#49182D,related_title_color:#49182D,template_contentcolor:#999999,firstletter_contentcolor:#999999,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#49182D,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#6D4657',
												'display_value' => '#49182D,#6D4657,#555555,#999999',
											),
										),
										'boxy-clean'       => array(
											'boxy-clean_default' => array(
												'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_color:#3E91AD,template_ftcolor:#555555,template_fthovercolor:#3E91AD,template_titlecolor:#0E7699,related_title_color:#0E7699,template_contentcolor:#888888,firstletter_contentcolor:#888888,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#0E7699,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#3E91AD',
												'display_value' => '#0E7699,#3E91AD,#555555,#888888',
											),
											'boxy-clean_mandalay' => array(
												'preset_name' => esc_html__( 'Mandalay', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_color:#C18F55,template_ftcolor:#555555,template_fthovercolor:#C18F55,template_titlecolor:#B1732A,related_title_color:#B1732A,template_contentcolor:#888888,firstletter_contentcolor:#888888,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#B1732A,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#C18F55',
												'display_value' => '#B1732A,#C18F55,#555555,#888888',
											),
											'boxy-clean_alizarin' => array(
												'preset_name' => esc_html__( 'Alizarin', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_color:#ED4961,template_ftcolor:#555555,template_fthovercolor:#ED4961,template_titlecolor:#E81B3A,related_title_color:#E81B3A,template_contentcolor:#888888,firstletter_contentcolor:#888888,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#E81B3A,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#ED4961',
												'display_value' => '#E81B3A,#ED4961,#555555,#888888',
											),
											'boxy-clean_mckenzie' => array(
												'preset_name' => esc_html__( 'McKenzie', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_color:#A2855B,template_ftcolor:#555555,template_fthovercolor:#A2855B,template_titlecolor:#8B6632,related_title_color:#8B6632,template_contentcolor:#888888,firstletter_contentcolor:#888888,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#8B6632,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#A2855B',
												'display_value' => '#8B6632,#A2855B,#555555,#888888',
											),
											'boxy-clean_blackberry' => array(
												'preset_name' => esc_html__( 'Blackberry', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_color:#6D4657,template_ftcolor:#555555,template_fthovercolor:#6D4657,template_titlecolor:#49182D,related_title_color:#49182D,template_contentcolor:#888888,firstletter_contentcolor:#888888,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#8B6632,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#6D4657',
												'display_value' => '#49182D,#6D4657,#555555,#888888',
											),
											'boxy-clean_regal_blue' => array(
												'preset_name' => esc_html__( 'Regal Blue', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_color:#435F7F,template_ftcolor:#555555,template_fthovercolor:#435F7F,template_titlecolor:#14375F,related_title_color:#14375F,template_contentcolor:#888888,firstletter_contentcolor:#888888,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#14375F,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#435F7F',
												'display_value' => '#14375F,#435F7F,#555555,#888888',
											),
										),
										'brit_co'          => array(
											'brit_co_default' => array(
												'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#3E91AD,template_fthovercolor:#555555,template_titlecolor:#0E7699,related_title_color:#0E7699,template_contentcolor:#666666,firstletter_contentcolor:#666666,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#3E91AD,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#0E7699',
												'display_value' => '#0E7699,#3E91AD,#555555,#666666',
											),
											'brit_co_pompadour' => array(
												'preset_name' => esc_html__( 'Pompadour', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#974772,template_fthovercolor:#555555,template_titlecolor:#7D194F,related_title_color:#7D194F,template_contentcolor:#666666,firstletter_contentcolor:#666666,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#7D194F,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#974772',
												'display_value' => '#7D194F,#974772,#555555,#666666',
											),
											'brit_co_bronzetone' => array(
												'preset_name' => esc_html__( 'Bronzetone', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#67704E,template_fthovercolor:#555555,template_titlecolor:#414C22,related_title_color:#414C22,template_contentcolor:#666666,firstletter_contentcolor:#666666,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#414C22,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#67704E',
												'display_value' => '#414C22,#67704E,#555555,#666666',
											),
											'brit_co_west_side' => array(
												'preset_name' => esc_html__( 'West Side', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#FDF6F1,template_ftcolor:#E99955,template_fthovercolor:#555555,template_titlecolor:#E4802A,related_title_color:#E4802A,template_contentcolor:#666666,firstletter_contentcolor:#666666,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#E4802A,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#E99955',
												'display_value' => '#E4802A,#E99955,#555555,#666666',
											),
											'brit_co_regal_blue' => array(
												'preset_name' => esc_html__( 'Regal Blue', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#EEF1F4,template_ftcolor:#435F7F,template_fthovercolor:#555555,template_titlecolor:#14375F,related_title_color:#14375F,template_contentcolor:#666666,firstletter_contentcolor:#666666,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#14375F,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#435F7F',
												'display_value' => '#14375F,#435F7F,#555555,#666666',
											),
											'brit_co_fun_green' => array(
												'preset_name' => esc_html__( 'Fun Green', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#EEF4F1,template_ftcolor:#3E8563,template_fthovercolor:#555555,template_titlecolor:#0E663C,related_title_color:#0E663C,template_contentcolor:#666666,firstletter_contentcolor:#666666,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#0E663C,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#3E8563',
												'display_value' => '#0E663C,#3E8563,#555555,#666666',
											),
										),
										'brite'            => array(
											'brite_default' => array(
												'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
												'preset_value' => 'template_ftcolor:#0E7699,template_fthovercolor:#555555,winter_category_color:#3E91AD,template_titlecolor:#0E7699,related_title_color:#0E7699,template_contentcolor:#999999,firstletter_contentcolor:#999999,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#0E7699,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#3E91AD',
												'display_value' => '#0E7699,#3E91AD,#555555,#999999',
											),
											'brite_mandalay' => array(
												'preset_name' => esc_html__( 'Mandalay', 'blog-designer-pro' ),
												'preset_value' => 'template_ftcolor:#C18F55,template_fthovercolor:#555555,winter_category_color:#C18F55,template_titlecolor:#B1732A,related_title_color:#B1732A,template_contentcolor:#999999,firstletter_contentcolor:#999999,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#B1732A,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#C18F55',
												'display_value' => '#B1732A,#C18F55,#555555,#999999',
											),
											'brite_bronzetone' => array(
												'preset_name' => esc_html__( 'Bronzetone', 'blog-designer-pro' ),
												'preset_value' => 'template_ftcolor:#67704E,template_fthovercolor:#555555,winter_category_color:#67704E,template_titlecolor:#414C22,related_title_color:#414C22,template_contentcolor:#999999,firstletter_contentcolor:#999999,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#414C22,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#67704E',
												'display_value' => '#414C22,#67704E,#555555,#999999',
											),
											'brite_red_violet' => array(
												'preset_name' => esc_html__( 'Red Violet', 'blog-designer-pro' ),
												'preset_value' => 'template_ftcolor:#C44C91,template_fthovercolor:#555555,winter_category_color:#C44C91,template_titlecolor:#B51F76,related_title_color:#B51F76,template_contentcolor:#999999,firstletter_contentcolor:#999999,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#B51F76,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#C44C91',
												'display_value' => '#B51F76,#C44C91,#555555,#999999',
											),
											'brite_peru_tan' => array(
												'preset_name' => esc_html__( 'Peru Tan', 'blog-designer-pro' ),
												'preset_value' => 'template_ftcolor:#916748,template_fthovercolor:#555555,winter_category_color:#916748,template_titlecolor:#75411A,related_title_color:#75411A,template_contentcolor:#999999,firstletter_contentcolor:#999999,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#75411A,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#916748',
												'display_value' => '#75411A,#916748,#555555,#999999',
											),
											'brite_earls_green' => array(
												'preset_name' => esc_html__( 'Earls Green', 'blog-designer-pro' ),
												'preset_value' => 'template_ftcolor:#CEBF59,template_fthovercolor:#555555,winter_category_color:#CEBF59,template_titlecolor:#C2AF2F,related_title_color:#C2AF2F,template_contentcolor:#999999,firstletter_contentcolor:#999999,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#C2AF2F,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#CEBF59',
												'display_value' => '#C2AF2F,#CEBF59,#555555,#999999',
											),
										),
										'chapter'          => array(
											'chapter_default' => array(
												'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#555555,template_fthovercolor:#E84159,template_titlecolor:#E21130,related_title_color:#E21130,template_contentcolor:#888888,firstletter_contentcolor:#888888,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#E21130,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#E84159',
												'display_value' => '#E21130,#E84159,#555555,#888888',
											),
											'chapter_earls_green' => array(
												'preset_name' => esc_html__( 'Earls Green', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#555555,template_fthovercolor:#CEBF59,template_titlecolor:#C2AF2F,related_title_color:#C2AF2F,template_contentcolor:#888888,firstletter_contentcolor:#888888,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#C2AF2F,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#CEBF59',
												'display_value' => '#C2AF2F,#CEBF59,#555555,#888888',
											),
											'chapter_cerulean' => array(
												'preset_name' => esc_html__( 'Cerulean', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#555555,template_fthovercolor:#3E91AD,template_titlecolor:#0E7699,related_title_color:#0E7699,template_contentcolor:#888888,firstletter_contentcolor:#888888,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#0E7699,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#3E91AD',
												'display_value' => '#0E7699,#3E91AD,#555555,#888888',
											),
											'chapter_bronzetone' => array(
												'preset_name' => esc_html__( 'Bronzetone', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#555555,template_fthovercolor:#67704E,template_titlecolor:#414C22,related_title_color:#414C22,template_contentcolor:#888888,firstletter_contentcolor:#888888,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#414C22,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#67704E',
												'display_value' => '#414C22,#67704E,#555555,#888888',
											),
											'chapter_ce_soir' => array(
												'preset_name' => esc_html__( 'Ce Soir', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#555555,template_fthovercolor:#A381BB,template_titlecolor:#8C62AA,related_title_color:#8C62AA,template_contentcolor:#888888,firstletter_contentcolor:#888888,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#8C62AA,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#A381BB',
												'display_value' => '#8C62AA,#A381BB,#555555,#888888',
											),
											'chapter_yonder' => array(
												'preset_name' => esc_html__( 'Yonder', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#555555,template_fthovercolor:#8BA3CE,template_titlecolor:#6E8CC2,related_title_color:#6E8CC2,template_contentcolor:#888888,firstletter_contentcolor:#888888,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#6E8CC2,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#8BA3CE',
												'display_value' => '#6E8CC2,#8BA3CE,#555555,#888888',
											),
										),
										'classical'        => array(
											'classical_default' => array(
												'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_color:#3E91AD,template_ftcolor:#3E91AD,template_fthovercolor:#555555,template_titlecolor:#0E7699,related_title_color:#0E7699,template_contentcolor:#777777,firstletter_contentcolor:#777777,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#0E7699,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#3E91AD',
												'display_value' => '#0E7699,#3E91AD,#555555,#777777',
											),
											'classical_rich_gold' => array(
												'preset_name' => esc_html__( 'Rich Gold', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_color:#BA7850,template_ftcolor:#BA7850,template_fthovercolor:#555555,template_titlecolor:#A95624,related_title_color:#A95624,template_contentcolor:#777777,firstletter_contentcolor:#777777,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#A95624,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#BA7850',
												'display_value' => '#A95624,#BA7850,#555555,#777777',
											),
											'classical_bronzetone' => array(
												'preset_name' => esc_html__( 'Bronzetone', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_color:#67704E,template_ftcolor:#67704E,template_fthovercolor:#555555,template_titlecolor:#414C22,related_title_color:#414C22,template_contentcolor:#777777,firstletter_contentcolor:#777777,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#414C22,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#67704E',
												'display_value' => '#414C22,#67704E,#555555,#777777',
											),
											'classical_terracotta' => array(
												'preset_name' => esc_html__( 'Terracotta', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_color:#B06F6D,template_ftcolor:#B06F6D,template_fthovercolor:#555555,template_titlecolor:#9C4B48,related_title_color:#9C4B48,template_contentcolor:#777777,firstletter_contentcolor:#777777,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#9C4B48,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#B06F6D',
												'display_value' => '#9C4B48,#B06F6D,#555555,#777777',
											),
											'classical_buttercup' => array(
												'preset_name' => esc_html__( 'Buttercup', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#FDF7F1,template_color:#E5A452,template_ftcolor:#E5A452,template_fthovercolor:#555555,template_titlecolor:#DF8D27,related_title_color:#DF8D27,template_contentcolor:#777777,firstletter_contentcolor:#777777,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#DF8D27,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#E5A452',
												'display_value' => '#DF8D27,#E5A452,#555555,#777777',
											),
											'classical_wasabi' => array(
												'preset_name' => esc_html__( 'Wasabi', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#F6F7F1,template_color:#93A564,template_ftcolor:#93A564,template_fthovercolor:#555555,template_titlecolor:#788F3D,related_title_color:#788F3D,template_contentcolor:#777777,firstletter_contentcolor:#777777,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#788F3D,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#93A564',
												'display_value' => '#788F3D,#93A564,#555555,#777777',
											),
										),
										'cool_horizontal'  => array(
											'cool_horizontal_default' => array(
												'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#e21130,template_ftcolor:#e21130,template_fthovercolor:#333333,template_titlecolor:#e21130,related_title_color:#e21130,template_contentcolor:#444444,firstletter_contentcolor:#444444,bdp_edd_price_color:#666666,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#e21130,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#666666',
												'display_value' => '#e21130,#666666,#e21130,#444444',
											),
											'cool_horizontal_pink' => array(
												'preset_name' => esc_html__( 'Pink', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#D33683,template_ftcolor:#D33683,template_fthovercolor:#333333,template_titlecolor:#D33683,related_title_color:#D33683,template_contentcolor:#444444,firstletter_contentcolor:#444444,bdp_edd_price_color:#666666,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#D33683,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#666666',
												'display_value' => '#D33683,#666666,#D33683,#444444',
											),
											'cool_horizontal_blue' => array(
												'preset_name' => esc_html__( 'Chetwode Blue', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#6C71C3,template_ftcolor:#6C71C3,template_fthovercolor:#333333,template_titlecolor:#6C71C3,related_title_color:#6C71C3,template_contentcolor:#444444,firstletter_contentcolor:#444444,bdp_edd_price_color:#666666,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#6C71C3,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#666666',
												'display_value' => '#6C71C3,#666666,#6C71C3,#444444',
											),
											'cool_horizontal_java' => array(
												'preset_name' => esc_html__( 'Java', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#29A198,template_ftcolor:#29A198,template_fthovercolor:#333333,template_titlecolor:#29A198,related_title_color:#29A198,template_contentcolor:#444444,firstletter_contentcolor:#444444,bdp_edd_price_color:#666666,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#29A198,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#666666',
												'display_value' => '#29A198,#666666,#29A198,#444444',
											),
											'cool_horizontal_curious_blue' => array(
												'preset_name' => esc_html__( 'Curious Blue', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#268BD3,template_ftcolor:#268BD3,template_fthovercolor:#333333,template_titlecolor:#268BD3,related_title_color:#268BD3,template_contentcolor:#444444,firstletter_contentcolor:#444444,bdp_edd_price_color:#666666,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#268BD3,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#666666',
												'display_value' => '#268BD3,#666666,#268BD3,#444444',
											),
											'cool_horizontal_olive' => array(
												'preset_name' => esc_html__( 'Olive', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#869901,template_ftcolor:#869901,template_fthovercolor:#333333,template_titlecolor:#869901,related_title_color:#869901,template_contentcolor:#444444,firstletter_contentcolor:#444444,bdp_edd_price_color:#666666,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#869901,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#666666',
												'display_value' => '#869901,#666666,#869901,#444444',
											),
										),
										'deport'           => array(
											'deport_default' => array(
												'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_color:#3E91AD,template_ftcolor:#3E91AD,template_fthovercolor:#555555,deport_dashcolor:#3E91AD,template_titlecolor:#0E7699,related_title_color:#0E7699,template_contentcolor:#888888,firstletter_contentcolor:#888888,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#0E7699,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#3E91AD',
												'display_value' => '#0E7699,#3E91AD,#555555,#888888',
											),
											'deport_west_side' => array(
												'preset_name' => esc_html__( 'West Side', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_color:#E99955,template_ftcolor:#E99955,template_fthovercolor:#555555,deport_dashcolor:#E99955,template_titlecolor:#E4802A,related_title_color:#E4802A,template_contentcolor:#888888,firstletter_contentcolor:#888888,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#E4802A,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#E99955',
												'display_value' => '#E4802A,#E99955,#555555,#888888',
											),
											'deport_lemon_ginger' => array(
												'preset_name' => esc_html__( 'Lemon Ginger', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_color:#CEBF59,template_ftcolor:#CEBF59,template_fthovercolor:#555555,deport_dashcolor:#CEBF59,template_titlecolor:#C2AF2F,related_title_color:#C2AF2F,template_contentcolor:#888888,firstletter_contentcolor:#888888,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#C2AF2F,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#CEBF59',
												'display_value' => '#C2AF2F,#CEBF59,#555555,#888888',
											),
											'deport_alizarin' => array(
												'preset_name' => esc_html__( 'Alizarin', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_color:#ED4961,template_ftcolor:#ED4961,template_fthovercolor:#555555,deport_dashcolor:#ED4961,template_titlecolor:#E81B3A,related_title_color:#E81B3A,template_contentcolor:#888888,firstletter_contentcolor:#888888,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#E81B3A,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#ED4961',
												'display_value' => '#E81B3A,#ED4961,#555555,#888888',
											),
											'deport_ce_soir' => array(
												'preset_name' => esc_html__( 'Ce Soir', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_color:#A381BB,template_ftcolor:#A381BB,template_fthovercolor:#555555,deport_dashcolor:#A381BB,template_titlecolor:#8C62AA,related_title_color:#8C62AA,template_contentcolor:#888888,firstletter_contentcolor:#888888,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#8C62AA,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#A381BB',
												'display_value' => '#8C62AA,#A381BB,#555555,#888888',
											),
											'deport_yonder' => array(
												'preset_name' => esc_html__( 'Yonder', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_color:#8BA3CE,template_ftcolor:#8BA3CE,template_fthovercolor:#555555,deport_dashcolor:#8BA3CE,template_titlecolor:#6E8CC2,related_title_color:#6E8CC2,template_contentcolor:#888888,firstletter_contentcolor:#888888,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#6E8CC2,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#8BA3CE',
												'display_value' => '#6E8CC2,#8BA3CE,#555555,#888888',
											),
										),
										'easy_timeline'    => array(
											'easy_timeline_default' => array(
												'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#bfbfbf,template_ftcolor:#E21130,template_fthovercolor:#444444,template_titlecolor:#444444,related_title_color:#444444,template_contentcolor:#666666,firstletter_contentcolor:#666666,bdp_edd_price_color:#444444,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#E21130,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#444444',
												'display_value' => '#E21130,#999999,#444444,#666666',
											),
											'easy_timeline_dim_gray' => array(
												'preset_name' => esc_html__( 'Dim Gray', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#bfbfbf,template_ftcolor:#666666,template_fthovercolor:#f1f1f1,template_titlecolor:#444444,related_title_color:#444444,template_contentcolor:#444444,firstletter_contentcolor:#444444,bdp_edd_price_color:#444444,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#666666,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#444444',
												'display_value' => '#666666,#999999,#444444,#444444',
											),
											'easy_timeline_flamenco' => array(
												'preset_name' => esc_html__( 'Flamenco', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#bfbfbf,template_ftcolor:#E18942,template_fthovercolor:#999999,template_titlecolor:#444444,related_title_color:#444444,template_contentcolor:#666666,firstletter_contentcolor:#666666,bdp_edd_price_color:#444444,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#E18942,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#444444',
												'display_value' => '#E18942,#999999,#444444,#666666',
											),
											'easy_timeline_jagger' => array(
												'preset_name' => esc_html__( 'Jagger', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#bfbfbf,template_ftcolor:#3D3242,template_fthovercolor:#999999,template_titlecolor:#444444,related_title_color:#444444,template_contentcolor:#666666,firstletter_contentcolor:#666666,bdp_edd_price_color:#444444,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#3D3242,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#444444',
												'display_value' => '#3D3242,#999999,#444444,#666666',
											),
											'easy_timeline_camelot' => array(
												'preset_name' => esc_html__( 'Camelot', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#bfbfbf,template_ftcolor:#7A3E48,template_fthovercolor:#999999,template_titlecolor:#444444,related_title_color:#444444,template_contentcolor:#666666,firstletter_contentcolor:#666666,bdp_edd_price_color:#444444,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#7A3E48,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#444444',
												'display_value' => '#7A3E48,#999999,#444444,#666666',
											),
											'easy_timeline_sundance' => array(
												'preset_name' => esc_html__( 'Sundance', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#bfbfbf,template_ftcolor:#C59F4A,template_fthovercolor:#999999,template_titlecolor:#444444,related_title_color:#444444,template_contentcolor:#666666,firstletter_contentcolor:#666666,bdp_edd_price_color:#444444,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#C59F4A,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#444444',
												'display_value' => '#C59F4A,#999999,#444444,#666666',
											),
										),
										'elina'            => array(
											'elina_default' => array(
												'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#E84159,template_fthovercolor:#333333,template_titlecolor:#333333,related_title_color:#333333,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#E21130,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#E84159',
												'display_value' => '#E21130,#E84159,#333333,#555555',
											),
											'elina_madras' => array(
												'preset_name' => esc_html__( 'Madras', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#6D6145,template_fthovercolor:#333333,template_titlecolor:#493917,related_title_color:#493917,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#493917,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#6D6145',
												'display_value' => '#493917,#6D6145,#333333,#555555',
											),
											'elina_cerulean' => array(
												'preset_name' => esc_html__( 'Cerulean', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#3E91AD,template_fthovercolor:#333333,template_titlecolor:#0E7699,related_title_color:#0E7699,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#0E7699,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#3E91AD',
												'display_value' => '#0E7699,#3E91AD,#333333,#555555',
											),
											'elina_bronzetone' => array(
												'preset_name' => esc_html__( 'Bronzetone', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#67704E,template_fthovercolor:#333333,template_titlecolor:#414C22,related_title_color:#414C22,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#414C22,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#67704E',
												'display_value' => '#414C22,#67704E,#333333,#555555',
											),
											'elina_buttercup' => array(
												'preset_name' => esc_html__( 'Buttercup', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#E5A452,template_fthovercolor:#333333,template_titlecolor:#DF8D27,related_title_color:#DF8D27,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#DF8D27,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#E5A452',
												'display_value' => '#DF8D27,#E5A452,#333333,#555555',
											),
											'elina_yonder' => array(
												'preset_name' => esc_html__( 'Yonder', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#8BA3CE,template_fthovercolor:#333333,template_titlecolor:#6E8CC2,related_title_color:#6E8CC2,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#6E8CC2,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#8BA3CE',
												'display_value' => '#6E8CC2,#8BA3CE,#333333,#555555',
											),
										),
										'evolution'        => array(
											'evolution_default' => array(
												'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#3E91AD,template_fthovercolor:#333333,template_titlecolor:#0E7699,related_title_color:#0E7699,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#0E7699,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#3E91AD',
												'display_value' => '#0E7699,#3E91AD,#333333,#555555',
											),
											'evolution_rich_gold' => array(
												'preset_name' => esc_html__( 'Rich Gold', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#F4E9E1,template_ftcolor:#BA7850,template_fthovercolor:#333333,template_titlecolor:#A95624,related_title_color:#A95624,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#A95624,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#BA7850',
												'display_value' => '#A95624,#BA7850,#333333,#555555',
											),
											'evolution_alizarin' => array(
												'preset_name' => esc_html__( 'Alizarin', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#FCE1E4,template_ftcolor:#ED4961,template_fthovercolor:#333333,template_titlecolor:#E81B3A,related_title_color:#E81B3A,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#E81B3A,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#ED4961',
												'display_value' => '#E81B3A,#ED4961,#333333,#555555',
											),
											'evolution_west_side' => array(
												'preset_name' => esc_html__( 'West Side', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#FBEDE2,template_ftcolor:#E99955,template_fthovercolor:#333333,template_titlecolor:#E4802A,related_title_color:#E4802A,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#E4802A,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#E99955',
												'display_value' => '#E4802A,#E99955,#333333,#555555',
											),
											'evolution_fun_green' => array(
												'preset_name' => esc_html__( 'Fun Green', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#DFEAE5,template_ftcolor:#3E8563,template_fthovercolor:#333333,template_titlecolor:#0E663C,related_title_color:#0E663C,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#0E663C,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#3E8563',
												'display_value' => '#0E663C,#3E8563,#333333,#555555',
											),
											'evolution_yonder' => array(
												'preset_name' => esc_html__( 'Yonder', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ECF0F7,template_ftcolor:#8BA3CE,template_fthovercolor:#333333,template_titlecolor:#6E8CC2,related_title_color:#6E8CC2,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#6E8CC2,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#8BA3CE',
												'display_value' => '#6E8CC2,#8BA3CE,#333333,#555555',
											),
										),
										'explore'          => array(
											'explore_default' => array(
												'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#44545D,template_fthovercolor:#333333,template_titlecolor:#152934,related_title_color:#152934,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#152934,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#44545D',
												'display_value' => '#152934,#44545D,#333333,#555555',
											),
											'explore_lemon_ginger' => array(
												'preset_name' => esc_html__( 'Lemon Ginger', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#A39D5A,template_fthovercolor:#333333,template_titlecolor:#8C8431,related_title_color:#8C8431,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#8C8431,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#A39D5A',
												'display_value' => '#8C8431,#A39D5A,#333333,#555555',
											),
											'explore_rich_gold' => array(
												'preset_name' => esc_html__( 'Rich Gold', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#BA7850,template_fthovercolor:#333333,template_titlecolor:#A95624,related_title_color:#A95624,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#A95624,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#BA7850',
												'display_value' => '#A95624,#BA7850,#333333,#555555',
											),
											'explore_catalina_blue' => array(
												'preset_name' => esc_html__( 'Catalina Blue', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#495F85,template_fthovercolor:#333333,template_titlecolor:#1B3766,related_title_color:#1B3766,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#1B3766,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#495F85',
												'display_value' => '#1B3766,#495F85,#333333,#555555',
											),
											'explore_red_violet' => array(
												'preset_name' => esc_html__( 'Red Violet', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#C44C91,template_fthovercolor:#333333,template_titlecolor:#B51F76,related_title_color:#B51F76,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#B51F76,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#C44C91',
												'display_value' => '#B51F76,#C44C91,#333333,#555555',
											),
											'explore_blackberry' => array(
												'preset_name' => esc_html__( 'Blackberry', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#6D4657,template_fthovercolor:#333333,template_titlecolor:#49182D,related_title_color:#49182D,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#49182D,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#6D4657',
												'display_value' => '#49182D,#6D4657,#333333,#555555',
											),
										),
										'glossary'         => array(
											'glossary_default' => array(
												'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
												'preset_value' => 'template_ftcolor:#ea4335,template_fthovercolor:#555555,template_titlecolor:#222222,related_title_color:#222222,template_contentcolor:#888888,firstletter_contentcolor:#888888,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#ea4335,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#222222',
												'display_value' => '#ea4335,#222222,#555555,#888888',
											),
											'glossary_madras' => array(
												'preset_name' => esc_html__( 'Madras', 'blog-designer-pro' ),
												'preset_value' => 'template_ftcolor:#493917,template_fthovercolor:#555555,template_titlecolor:#493917,related_title_color:#493917,template_contentcolor:#888888,firstletter_contentcolor:#888888,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#493917,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#6D6145',
												'display_value' => '#493917,#6D6145,#555555,#888888',
											),
											'glossary_catalina_blue' => array(
												'preset_name' => esc_html__( 'Catalina Blue', 'blog-designer-pro' ),
												'preset_value' => 'template_ftcolor:#555555,template_fthovercolor:#495F85,template_titlecolor:#1B3766,related_title_color:#1B3766,template_contentcolor:#888888,firstletter_contentcolor:#888888,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#1B3766,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#495F85',
												'display_value' => '#1B3766,#495F85,#555555,#888888',
											),
											'glossary_pompadour' => array(
												'preset_name' => esc_html__( 'Pompadour', 'blog-designer-pro' ),
												'preset_value' => 'template_ftcolor:#495F85,template_fthovercolor:#974772,template_titlecolor:#495F85,related_title_color:#495F85,template_contentcolor:#888888,firstletter_contentcolor:#888888,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#495F85,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#974772',
												'display_value' => '#495F85,#974772,#555555,#888888',
											),
											'glossary_bronzetone' => array(
												'preset_name' => esc_html__( 'Bronzetone', 'blog-designer-pro' ),
												'preset_value' => 'template_ftcolor:#414C22,template_fthovercolor:#67704E,template_titlecolor:#414C22,related_title_color:#414C22,template_contentcolor:#888888,firstletter_contentcolor:#888888,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#414C22,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#67704E',
												'display_value' => '#414C22,#67704E,#555555,#888888',
											),
											'glossary_peru-tan' => array(
												'preset_name' => esc_html__( 'Peru Tan', 'blog-designer-pro' ),
												'preset_value' => 'template_ftcolor:#75411A,template_fthovercolor:#916748,template_titlecolor:#75411A,related_title_color:#75411A,template_contentcolor:#888888,firstletter_contentcolor:#888888,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#75411A,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#916748',
												'display_value' => '#75411A,#916748,#555555,#888888',
											),
										),
										'hub'              => array(
											'hub_default' => array(
												'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
												'preset_value' => 'template_ftcolor:#0E7699,template_fthovercolor:#555555,template_titlecolor:#0E7699,related_title_color:#222222,template_contentcolor:#888888,firstletter_contentcolor:#888888,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#0E7699,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#222222',
												'display_value' => '#0E7699,#222222,#555555,#888888',
											),
											'hub_crimson' => array(
												'preset_name' => esc_html__( 'Crimson', 'blog-designer-pro' ),
												'preset_value' => 'template_ftcolor:#E21130,template_fthovercolor:#E84159,template_titlecolor:#E21130,related_title_color:#E21130,template_contentcolor:#888888,firstletter_contentcolor:#888888,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#E21130,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#E84159',
												'display_value' => '#E21130,#E84159,#555555,#888888',
											),
											'hub_bronzetone' => array(
												'preset_name' => esc_html__( 'Bronzetone', 'blog-designer-pro' ),
												'preset_value' => 'template_ftcolor:#414C22,template_fthovercolor:#67704E,template_titlecolor:#414C22,related_title_color:#414C22,template_contentcolor:#888888,firstletter_contentcolor:#888888,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#414C22,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#67704E',
												'display_value' => '#414C22,#67704E,#555555,#888888',
											),
											'hub_west_side' => array(
												'preset_name' => esc_html__( 'West Side', 'blog-designer-pro' ),
												'preset_value' => 'template_ftcolor:#E4802A,template_fthovercolor:#E99955,template_titlecolor:#E4802A,related_title_color:#E4802A,template_contentcolor:#888888,firstletter_contentcolor:#888888,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#E4802A,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#E99955',
												'display_value' => '#E4802A,#E99955,#555555,#888888',
											),
											'hub_wasabi'  => array(
												'preset_name' => esc_html__( 'Wasabi', 'blog-designer-pro' ),
												'preset_value' => 'template_ftcolor:#788F3D,template_fthovercolor:#93A564,template_titlecolor:#788F3D,related_title_color:#788F3D,template_contentcolor:#888888,firstletter_contentcolor:#888888,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#788F3D,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#93A564',
												'display_value' => '#788F3D,#93A564,#555555,#888888',
											),
											'hub_yonder'  => array(
												'preset_name' => esc_html__( 'Yonder', 'blog-designer-pro' ),
												'preset_value' => 'template_ftcolor:#6E8CC2,template_fthovercolor:#8BA3CE,template_titlecolor:#6E8CC2,related_title_color:#6E8CC2,template_contentcolor:#888888,firstletter_contentcolor:#888888,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#6E8CC2,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#8BA3CE',
												'display_value' => '#6E8CC2,#8BA3CE,#555555,#888888',
											),
										),
										'invert-grid'      => array(
											'invert-grid_default' => array(
												'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#47c2dc,template_ftcolor:#d35400,template_fthovercolor:#555555,template_titlecolor:#333333,related_title_color:#333333,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#47c2dc,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#d35400',
												'display_value' => '#47c2dc,#d35400,#333333,#555555',
											),
											'invert-grid_mckenzie' => array(
												'preset_name' => esc_html__( 'McKenzie', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#8B6632,template_ftcolor:#A2855B,template_fthovercolor:#333333,template_titlecolor:#333333,related_title_color:#333333,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#8B6632,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#A2855B',
												'display_value' => '#8B6632,#A2855B,#333333,#555555',
											),
											'invert-grid_fun_green' => array(
												'preset_name' => esc_html__( 'Fun Green', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#3E8563,template_ftcolor:#333333,template_fthovercolor:#3E8563,template_titlecolor:#0E663C,related_title_color:#0E663C,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#0E663C,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#3E8563',
												'display_value' => '#0E663C,#3E8563,#333333,#555555',
											),
											'invert-grid_blackberry' => array(
												'preset_name' => esc_html__( 'Blackberry', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#6D4657,template_ftcolor:#333333,template_fthovercolor:#6D4657,template_titlecolor:#49182D,related_title_color:#49182D,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#49182D,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#6D4657',
												'display_value' => '#49182D,#6D4657,#333333,#555555',
											),
											'invert-grid_buttercup' => array(
												'preset_name' => esc_html__( 'Buttercup', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#E5A452,template_ftcolor:#333333,template_fthovercolor:#E5A452,template_titlecolor:#DF8D27,related_title_color:#DF8D27,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#DF8D27,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#E5A452',
												'display_value' => '#DF8D27,#E5A452,#333333,#555555',
											),
											'invert-grid_alizarin' => array(
												'preset_name' => esc_html__( 'Alizarin', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#ED4961,template_ftcolor:#333333,template_fthovercolor:#ED4961,template_titlecolor:#E81B3A,related_title_color:#E81B3A,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#E81B3A,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#ED4961',
												'display_value' => '#E81B3A,#ED4961,#333333,#555555',
											),
										),
										'lightbreeze'      => array(
											'lightbreeze_default' => array(
												'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#f5ab35,template_fthovercolor:#4c4c4c,template_titlecolor:#4c4c4c,related_title_color:#4c4c4c,template_contentcolor:#808080,firstletter_contentcolor:#808080,bdp_edd_price_color:#4c4c4c,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#f5ab35,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#4c4c4c',
												'display_value' => '#f5ab35,#ffffff,#4c4c4c,#808080',
											),
											'lightbreeze_pink' => array(
												'preset_name' => esc_html__( 'Pink', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#FEF5F8,template_ftcolor:#e21130,template_fthovercolor:#4c4c4c,template_titlecolor:#4c4c4c,related_title_color:#4c4c4c,template_contentcolor:#808080,firstletter_contentcolor:#808080,bdp_edd_price_color:#e21130,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#e21130,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#e21130',
												'display_value' => '#e21130,#FEF5F8,#e21130,#808080',
											),
											'lightbreeze_solitude' => array(
												'preset_name' => esc_html__( 'Solitude', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#FFF3E5,template_ftcolor:#FF8A00,template_fthovercolor:#4c4c4c,template_titlecolor:#4c4c4c,related_title_color:#4c4c4c,template_contentcolor:#808080,firstletter_contentcolor:#808080,bdp_edd_price_color:#4c4c4c,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#FF8A00,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#4c4c4c',
												'display_value' => '#FF8A00,#FFF3E5,#4c4c4c,#808080',
											),
										),
										'masonry_timeline' => array(
											'masonry_timeline_default' => array(
												'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#E21130,template_fthovercolor:#333333,template_titlecolor:#222222,related_title_color:#222222,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#E21130,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#222222',
												'display_value' => '#E21130,#222222,#333333,#555555',
											),
											'masonry_timeline_lemon_ginger' => array(
												'preset_name' => esc_html__( 'Lemon Ginger', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#8C8431,template_fthovercolor:#333333,template_titlecolor:#222222,related_title_color:#222222,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#8C8431,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#222222',
												'display_value' => '#8C8431,#222222,#333333,#555555',
											),
											'masonry_timeline_cerulean' => array(
												'preset_name' => esc_html__( 'Cerulean', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#3E91AD,template_fthovercolor:#333333,template_titlecolor:#0E7699,related_title_color:#0E7699,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#0E7699,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#3E91AD',
												'display_value' => '#0E7699,#3E91AD,#333333,#555555',
											),
											'masonry_timeline_yonder' => array(
												'preset_name' => esc_html__( 'Yonder', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#8BA3CE,template_fthovercolor:#333333,template_titlecolor:#6E8CC2,related_title_color:#6E8CC2,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#6E8CC2,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#8BA3CE',
												'display_value' => '#6E8CC2,#8BA3CE,#333333,#555555',
											),
											'masonry_timeline_peru_tan' => array(
												'preset_name' => esc_html__( 'Peru Tan', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#916748,template_fthovercolor:#333333,template_titlecolor:#75411A,related_title_color:#75411A,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#75411A,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#916748',
												'display_value' => '#75411A,#916748,#333333,#555555',
											),
											'masonry_timeline_fun_green' => array(
												'preset_name' => esc_html__( 'Fun Green', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#3E8563,template_fthovercolor:#333333,template_titlecolor:#0E663C,related_title_color:#0E663C,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#0E663C,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#3E8563',
												'display_value' => '#0E663C,#3E8563,#333333,#555555',
											),
										),
										'media-grid'       => array(
											'media-grid_default' => array(
												'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#3E91AD,template_ftcolor:#3E91AD,template_fthovercolor:#333333,template_titlecolor:#0E7699,related_title_color:#0E7699,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#0E7699,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#3E91AD',
												'display_value' => '#0E7699,#3E91AD,#333333,#555555',
											),
											'media-grid_rich_gold' => array(
												'preset_name' => esc_html__( 'Rich Gold', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#BA7850,template_ftcolor:#A95624,template_fthovercolor:#BA7850,template_titlecolor:#A95624,related_title_color:#A95624,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#A95624,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#BA7850',
												'display_value' => '#A95624,#BA7850,#333333,#555555',
											),
											'media-grid_pompadour' => array(
												'preset_name' => esc_html__( 'Pompadour', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#974772,template_ftcolor:#7D194F,template_fthovercolor:#974772,template_titlecolor:#7D194F,related_title_color:#7D194F,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#7D194F,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#974772',
												'display_value' => '#7D194F,#974772,#333333,#555555',
											),
											'media-grid_bronzetone' => array(
												'preset_name' => esc_html__( 'Bronzetone', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#67704E,template_ftcolor:#414C22,template_fthovercolor:#67704E,template_titlecolor:#414C22,related_title_color:#414C22,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#414C22,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#67704E',
												'display_value' => '#414C22,#67704E,#333333,#555555',
											),
											'media-grid_ce_soir' => array(
												'preset_name' => esc_html__( 'Ce Soir', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#A381BB,template_ftcolor:#8C62AA,template_fthovercolor:#A381BB,template_titlecolor:#8C62AA,related_title_color:#8C62AA,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#8C62AA,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#A381BB',
												'display_value' => '#8C62AA,#A381BB,#333333,#555555',
											),
											'media-grid_yonder' => array(
												'preset_name' => esc_html__( 'Yonder', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#8BA3CE,template_ftcolor:#6E8CC2,template_fthovercolor:#8BA3CE,template_titlecolor:#6E8CC2,related_title_color:#6E8CC2,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#6E8CC2,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#8BA3CE',
												'display_value' => '#6E8CC2,#8BA3CE,#333333,#555555',
											),
										),
										'my_diary'         => array(
											'my_diary_default' => array(
												'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#E21130,template_fthovercolor:#E84159,template_titlecolor:#E21130,related_title_color:#E21130,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#E21130,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#E84159',
												'display_value' => '#E21130,#E84159,#333333,#555555',
											),
											'my_diary_lemon_ginger' => array(
												'preset_name' => esc_html__( 'Lemon Ginger', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#8C8431,template_fthovercolor:#A39D5A,template_titlecolor:#8C8431,related_title_color:#8C8431,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#8C8431,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#A39D5A',
												'display_value' => '#8C8431,#A39D5A,#333333,#555555',
											),
											'my_diary_cerulean' => array(
												'preset_name' => esc_html__( 'Cerulean', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#0E7699,template_fthovercolor:#3E91AD,template_titlecolor:#0E7699,related_title_color:#0E7699,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#0E7699,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#3E91AD',
												'display_value' => '#0E7699,#3E91AD,#333333,#555555',
											),
											'my_diary_mandalay' => array(
												'preset_name' => esc_html__( 'Mandalay', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#A95624,template_fthovercolor:#BA7850,template_titlecolor:#A95624,related_title_color:#A95624,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#A95624,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#BA7850',
												'display_value' => '#A95624,#BA7850,#333333,#555555',
											),
											'my_diary_regal_blue' => array(
												'preset_name' => esc_html__( 'Regal Blue', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#14375F,template_fthovercolor:#435F7F,template_titlecolor:#14375F,related_title_color:#14375F,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#14375F,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#435F7F',
												'display_value' => '#14375F,#435F7F,#333333,#555555',
											),
											'my_diary_yonder' => array(
												'preset_name' => esc_html__( 'Yonder', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#6E8CC2,template_fthovercolor:#8BA3CE,template_titlecolor:#6E8CC2,related_title_color:#6E8CC2,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#6E8CC2,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#8BA3CE',
												'display_value' => '#6E8CC2,#8BA3CE,#333333,#555555',
											),
										),
										'navia'            => array(
											'navia_default' => array(
												'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
												'preset_value' => 'template_ftcolor:#0E7699,template_fthovercolor:#555555,template_titlecolor:#0E7699,related_title_color:#222222,template_contentcolor:#999999,firstletter_contentcolor:#999999,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#0E7699,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#222222',
												'display_value' => '#0E7699,#222222,#555555,#999999',
											),
											'navia_toddy'  => array(
												'preset_name' => esc_html__( 'Toddy', 'blog-designer-pro' ),
												'preset_value' => 'template_ftcolor:#AE742A,template_fthovercolor:#BE9055,template_titlecolor:#AE742A,related_title_color:#AE742A,template_contentcolor:#999999,firstletter_contentcolor:#999999,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#AE742A,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#BE9055',
												'display_value' => '#AE742A,#BE9055,#555555,#999999',
											),
											'navia_bronzetone' => array(
												'preset_name' => esc_html__( 'Bronzetone', 'blog-designer-pro' ),
												'preset_value' => 'template_ftcolor:#414C22,template_fthovercolor:#67704E,template_titlecolor:#414C22,related_title_color:#414C22,template_contentcolor:#999999,firstletter_contentcolor:#999999,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#414C22,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#67704E',
												'display_value' => '#414C22,#67704E,#555555,#999999',
											),
											'navia_regal_blue' => array(
												'preset_name' => esc_html__( 'Regal Blue', 'blog-designer-pro' ),
												'preset_value' => 'template_ftcolor:#14375F,template_fthovercolor:#435F7F,template_titlecolor:#14375F,related_title_color:#14375F,template_contentcolor:#999999,firstletter_contentcolor:#999999,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#14375F,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#435F7F',
												'display_value' => '#14375F,#435F7F,#555555,#999999',
											),
											'navia_claret' => array(
												'preset_name' => esc_html__( 'Claret', 'blog-designer-pro' ),
												'preset_value' => 'template_ftcolor:#781335,template_fthovercolor:#93425D,template_titlecolor:#781335,related_title_color:#781335,template_contentcolor:#999999,firstletter_contentcolor:#999999,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#781335,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#93425D',
												'display_value' => '#781335,#93425D,#555555,#999999',
											),
											'navia_earls_green' => array(
												'preset_name' => esc_html__( 'Earls Green', 'blog-designer-pro' ),
												'preset_value' => 'template_ftcolor:#C2AF2F,template_fthovercolor:#CEBF59,template_titlecolor:#C2AF2F,related_title_color:#C2AF2F,template_contentcolor:#999999,firstletter_contentcolor:#999999,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#C2AF2F,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#CEBF59',
												'display_value' => '#C2AF2F,#CEBF59,#555555,#999999',
											),
										),
										'news'             => array(
											'news_default' => array(
												'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
												'preset_value' => 'template_ftcolor:#A95624,template_fthovercolor:#555555,template_titlecolor:#A95624,related_title_color:#222222,template_contentcolor:#999999,firstletter_contentcolor:#999999,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#A95624,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#222222',
												'display_value' => '#A95624,#222222,#555555,#999999',
											),
											'news_cerulean' => array(
												'preset_name' => esc_html__( 'Cerulean', 'blog-designer-pro' ),
												'preset_value' => 'template_ftcolor:#0E7699,template_fthovercolor:#3E91AD,template_titlecolor:#0E7699,related_title_color:#0E7699,template_contentcolor:#999999,firstletter_contentcolor:#999999,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#0E7699,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#3E91AD',
												'display_value' => '#0E7699,#3E91AD,#555555,#999999',
											),
											'news_pompadour' => array(
												'preset_name' => esc_html__( 'Pompadour', 'blog-designer-pro' ),
												'preset_value' => 'template_ftcolor:#7D194F,template_fthovercolor:#974772,template_titlecolor:#7D194F,related_title_color:#7D194F,template_contentcolor:#999999,firstletter_contentcolor:#999999,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#7D194F,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#974772',
												'display_value' => '#7D194F,#974772,#555555,#999999',
											),
											'news_alizarin' => array(
												'preset_name' => esc_html__( 'Alizarin', 'blog-designer-pro' ),
												'preset_value' => 'template_ftcolor:#E81B3A,template_fthovercolor:#ED4961,template_titlecolor:#E81B3A,related_title_color:#E81B3A,template_contentcolor:#999999,firstletter_contentcolor:#999999,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#E81B3A,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#ED4961',
												'display_value' => '#E81B3A,#ED4961,#555555,#999999',
											),
											'news_wasabi'  => array(
												'preset_name' => esc_html__( 'Wasabi', 'blog-designer-pro' ),
												'preset_value' => 'template_ftcolor:#788F3D,template_fthovercolor:#93A564,template_titlecolor:#788F3D,related_title_color:#788F3D,template_contentcolor:#999999,firstletter_contentcolor:#999999,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#788F3D,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#93A564',
												'display_value' => '#788F3D,#93A564,#555555,#999999',
											),
											'news_earls_green' => array(
												'preset_name' => esc_html__( 'Earls Green', 'blog-designer-pro' ),
												'preset_value' => 'template_ftcolor:#C2AF2F,template_fthovercolor:#CEBF59,template_titlecolor:#C2AF2F,related_title_color:#C2AF2F,template_contentcolor:#999999,firstletter_contentcolor:#999999,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#C2AF2F,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#CEBF59',
												'display_value' => '#C2AF2F,#CEBF59,#555555,#999999',
											),
										),
										'nicy'             => array(
											'nicy_default' => array(
												'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_color:#3E91AD,template_ftcolor:#3E91AD,template_fthovercolor:#555555,template_titlecolor:#0E7699,related_title_color:#0E7699,template_contentcolor:#777777,firstletter_contentcolor:#777777,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#0E7699,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#3E91AD',
												'display_value' => '#0E7699,#3E91AD,#555555,#777777',
											),
											'nicy_rich_gold' => array(
												'preset_name' => esc_html__( 'Rich Gold', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_color:#BA7850,template_ftcolor:#BA7850,template_fthovercolor:#555555,template_titlecolor:#A95624,related_title_color:#A95624,template_contentcolor:#777777,firstletter_contentcolor:#777777,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#A95624,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#BA7850',
												'display_value' => '#A95624,#BA7850,#555555,#777777',
											),
											'nicy_bronzetone' => array(
												'preset_name' => esc_html__( 'Bronzetone', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_color:#67704E,template_ftcolor:#67704E,template_fthovercolor:#555555,template_titlecolor:#414C22,related_title_color:#414C22,template_contentcolor:#777777,firstletter_contentcolor:#777777,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#414C22,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#67704E',
												'display_value' => '#414C22,#67704E,#555555,#777777',
											),
											'nicy_terracotta' => array(
												'preset_name' => esc_html__( 'Terracotta', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_color:#B06F6D,template_ftcolor:#B06F6D,template_fthovercolor:#555555,template_titlecolor:#9C4B48,related_title_color:#9C4B48,template_contentcolor:#777777,firstletter_contentcolor:#777777,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#9C4B48,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#6F6DAD',
												'display_value' => '#9C4B48,#B06F6D,#555555,#777777',
											),
											'nicy_buttercup' => array(
												'preset_name' => esc_html__( 'Buttercup', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#FDF7F1,template_color:#E5A452,template_ftcolor:#E5A452,template_fthovercolor:#555555,template_titlecolor:#DF8D27,related_title_color:#DF8D27,template_contentcolor:#777777,firstletter_contentcolor:#777777,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#DF8D27,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#E5A452',
												'display_value' => '#DF8D27,#E5A452,#555555,#777777',
											),
											'nicy_wasabi'  => array(
												'preset_name' => esc_html__( 'Wasabi', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#F6F7F1,template_color:#93A564,template_ftcolor:#93A564,template_fthovercolor:#555555,template_titlecolor:#788F3D,related_title_color:#788F3D,template_contentcolor:#777777,firstletter_contentcolor:#777777,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#788F3D,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#93A564',
												'display_value' => '#788F3D,#93A564,#555555,#777777',
											),
										),
										'offer_blog'       => array(
											'offer_blog_default' => array(
												'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#0E7699,template_fthovercolor:#333333,template_titlecolor:#0E7699,related_title_color:#222222,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#0E7699,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#222222',
												'display_value' => '#0E7699,#222222,#333333,#555555',
											),
											'offer_blog_earls_green' => array(
												'preset_name' => esc_html__( 'Earls Green', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#C2AF2F,template_fthovercolor:#CEBF59,template_titlecolor:#C2AF2F,related_title_color:#C2AF2F,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#C2AF2F,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#CEBF59',
												'display_value' => '#C2AF2F,#CEBF59,#333333,#555555',
											),
											'offer_blog_pompadour' => array(
												'preset_name' => esc_html__( 'Pompadour', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#7D194F,template_fthovercolor:#974772,template_titlecolor:#7D194F,related_title_color:#7D194F,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#7D194F,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#974772',
												'display_value' => '#7D194F,#974772,#333333,#555555',
											),
											'offer_blog_bronzetone' => array(
												'preset_name' => esc_html__( 'Bronzetone', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#414C22,template_fthovercolor:#67704E,template_titlecolor:#414C22,related_title_color:#414C22,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#414C22,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#67704E',
												'display_value' => '#414C22,#67704E,#333333,#555555',
											),
											'offer_blog_west-side' => array(
												'preset_name' => esc_html__( 'West Side', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#FDF6F1,template_ftcolor:#E4802A,template_fthovercolor:#E99955,template_titlecolor:#E4802A,related_title_color:#E4802A,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#E4802A,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#E99955',
												'display_value' => '#E4802A,#E99955,#333333,#555555',
											),
											'offer_blog_yonder' => array(
												'preset_name' => esc_html__( 'Yonder', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#F5F7FB,template_ftcolor:#6E8CC2,template_fthovercolor:#8BA3CE,template_titlecolor:#6E8CC2,related_title_color:#6E8CC2,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#6E8CC2,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#8BA3CE',
												'display_value' => '#6E8CC2,#8BA3CE,#333333,#555555',
											),
										),
										'overlay_horizontal' => array(
											'overlay_horizontal_default' => array(
												'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#000000,template_ftcolor:#e2112f,template_fthovercolor:#ffffff,template_titlecolor:#ffffff,related_title_color:#ffffff,template_contentcolor:#333333,firstletter_contentcolor:#333333,bdp_edd_price_color:#ffffff,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#000000,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#e2112f',
												'display_value' => '#000000,#e2112f,#ffffff,#333333',
											),
											'overlay_horizontal_persian_red' => array(
												'preset_name' => esc_html__( 'Persian Red', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#CC3333,template_ftcolor:#33cccc,template_fthovercolor:#ffffff,template_titlecolor:#3333cc,related_title_color:#ffffff,template_contentcolor:#ffffff,firstletter_contentcolor:#ffffff,bdp_edd_price_color:#3333cc,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#33cccc,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#3333cc',
												'display_value' => '#CC3333,#33cccc,#3333cc,#ffffff',
											),
											'overlay_horizontal_dark_goldenrod' => array(
												'preset_name' => esc_html__( 'Dark Goldenrod', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#b8860b,template_ftcolor:#0bb886,template_fthovercolor:#94b80b,template_titlecolor:#0b3db8,related_title_color:#0b3db8,template_contentcolor:#b8300b,firstletter_contentcolor:#b8300b,bdp_edd_price_color:#0b3db8,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#b8860b,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#b8300b',
												'display_value' => '#b8860b,#0bb886,#0b3db8,#b8300b',
											),
											'overlay_horizontal_deep_cerise' => array(
												'preset_name' => esc_html__( 'Deep Cerise', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#da3287,template_ftcolor:#87da32,template_fthovercolor:#3287da,template_titlecolor:#3287da,related_title_color:#3287da,template_contentcolor:#32da85,firstletter_contentcolor:#32da85,bdp_edd_price_color:#3287da,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#da3287,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#32da85',
												'display_value' => '#da3287,#87da32,#3287da,#32da85',
											),
											'overlay_horizontal_rust' => array(
												'preset_name' => esc_html__( 'Rust', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#a55d35,template_ftcolor:#35a55d,template_fthovercolor:#5d35a5,template_titlecolor:#333333,related_title_color:#333333,template_contentcolor:#ffffff,firstletter_contentcolor:#ffffff,bdp_edd_price_color:#333333,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#a55d35,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#333333',
												'display_value' => '#a55d35,#35a55d,#333333,#ffffff',
											),
											'overlay_horizontal_blue' => array(
												'preset_name' => esc_html__( 'Chetwode Blue', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#666fb4,template_ftcolor:#6fb466,template_fthovercolor:#ffffff,template_titlecolor:#b4ab66,related_title_color:#b4ab66,template_contentcolor:#333333,firstletter_contentcolor:#333333,bdp_edd_price_color:#333333,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#666fb4,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#6fb466',
												'display_value' => '#666fb4,#6fb466,#b4ab66,#333333',
											),
										),
										'pretty'           => array(
											'pretty_default' => array(
												'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_color:#ff93a3,template_ftcolor:#999999,template_fthovercolor:#859f88,template_titlecolor:#859f88,related_title_color:#859f88,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#859f88,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#999999',
												'display_value' => '#ff93a3,#859f88,#555555,#999999',
											),
											'pretty_sky_blue' => array(
												'preset_name' => esc_html__( 'Sky Blue', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_color:#DAEBFF,template_ftcolor:#888888,template_fthovercolor:#00809D,template_titlecolor:#00809D,related_title_color:#00809D,template_contentcolor:#484848,firstletter_contentcolor:#484848,bdp_edd_price_color:#888888,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#00809D,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#484848',
												'display_value' => '#DAEBFF,#00809D,#484848,#888888',
											),
											'pretty_lite_green' => array(
												'preset_name' => esc_html__( 'Lite Green', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_color:#C3F3DD,template_ftcolor:#888888,template_fthovercolor:#0ef58d,template_titlecolor:#0ef58d,related_title_color:#0ef58d,template_contentcolor:#484848,firstletter_contentcolor:#484848,bdp_edd_price_color:#888888,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#0ef58d,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#484848',
												'display_value' => '#C3F3DD,#0ef58d,#484848,#888888',
											),
										),
										'region'           => array(
											'region_default' => array(
												'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#0E7699,template_fthovercolor:#333333,template_titlecolor:#0E7699,related_title_color:#222222,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#0E7699,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#222222',
												'display_value' => '#0E7699,#222222,#333333,#555555',
											),
											'region_regal-blue' => array(
												'preset_name' => esc_html__( 'Regal Blue', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#14375F,template_fthovercolor:#435F7F,template_titlecolor:#14375F,related_title_color:#14375F,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#14375F,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#435F7F',
												'display_value' => '#14375F,#435F7F,#333333,#555555',
											),
											'region_alizarin' => array(
												'preset_name' => esc_html__( 'Alizarin', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#E81B3A,template_fthovercolor:#ED4961,template_titlecolor:#E81B3A,related_title_color:#E81B3A,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#E81B3A,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#ED4961',
												'display_value' => '#E81B3A,#ED4961,#333333,#555555',
											),
											'region_lemon_ginger' => array(
												'preset_name' => esc_html__( 'Lemon Ginger', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#8C8431,template_fthovercolor:#A39D5A,template_titlecolor:#8C8431,related_title_color:#8C8431,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#8C8431,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#A39D5A',
												'display_value' => '#8C8431,#A39D5A,#333333,#555555',
											),
											'region_fun_green' => array(
												'preset_name' => esc_html__( 'Fun Green', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#0E663C,template_fthovercolor:#3E8563,template_titlecolor:#0E663C,related_title_color:#0E663C,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#0E663C,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#3E8563',
												'display_value' => '#0E663C,#3E8563,#333333,#555555',
											),
											'region_toddy' => array(
												'preset_name' => esc_html__( 'Toddy', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#AE742A,template_fthovercolor:#BE9055,template_titlecolor:#AE742A,related_title_color:#AE742A,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#AE742A,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#BE9055',
												'display_value' => '#AE742A,#BE9055,#333333,#555555',
											),
										),
										'roctangle'        => array(
											'roctangle_default' => array(
												'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_color:#f18293,template_ftcolor:#f18293,template_fthovercolor:#444444,template_titlecolor:#f18293,template_contentcolor:#444444,firstletter_contentcolor:#444444,bdp_edd_price_color:#666666,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#f18293,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#222222',
												'display_value' => '#f18293,#222222,#444444,#666666',
											),
											'roctangle_sky_blue' => array(
												'preset_name' => esc_html__( 'Sky Blue', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_color:#92E2FD,template_ftcolor:#92E2FD,template_fthovercolor:#444444,template_titlecolor:#92E2FD,template_contentcolor:#444444,firstletter_contentcolor:#444444,bdp_edd_price_color:#666666,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#92E2FD,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#222222',
												'display_value' => '#92E2FD,#222222,#444444,#666666',
											),
											'roctangle_lite_green' => array(
												'preset_name' => esc_html__( 'Lite Green', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_color:#0ef58d,template_ftcolor:#0ef58d,template_fthovercolor:#444444,template_titlecolor:#0ef58d,template_contentcolor:#444444,firstletter_contentcolor:#444444,bdp_edd_price_color:#666666,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#0ef58d,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#222222',
												'display_value' => '#0ef58d,#222222,#444444,#666666',
											),
										),
										'sharpen'          => array(
											'sharpen_default' => array(
												'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#f5ab35,template_fthovercolor:#4c4c4c,template_titlecolor:#4c4c4c,related_title_color:#4c4c4c,template_contentcolor:#808080,firstletter_contentcolor:#808080,bdp_edd_price_color:#4c4c4c,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#0E7699,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#4c4c4c',
												'display_value' => '#f5ab35,#ffffff,#4c4c4c,#808080',
											),
											'sharpen_pink' => array(
												'preset_name' => esc_html__( 'Pink', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#FEF5F8,template_ftcolor:#e21130,template_fthovercolor:#4c4c4c,template_titlecolor:#4c4c4c,related_title_color:#4c4c4c,template_contentcolor:#808080,firstletter_contentcolor:#808080,bdp_edd_price_color:#4c4c4c,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#e21130,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#4c4c4c',
												'display_value' => '#e21130,#FEF5F8,#4c4c4c,#808080',
											),
											'sharpen_solitude' => array(
												'preset_name' => esc_html__( 'Solitude', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#FFF3E5,template_ftcolor:#FF8A00,template_fthovercolor:#4c4c4c,template_titlecolor:#4c4c4c,related_title_color:#4c4c4c,template_contentcolor:#808080,firstletter_contentcolor:#808080,bdp_edd_price_color:#4c4c4c,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#FF8A00,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#4c4c4c',
												'display_value' => '#FF8A00,#FFF3E5,#4c4c4c,#808080',
											),
										),
										'spektrum'         => array(
											'spektrum_default' => array(
												'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
												'preset_value' => 'template_ftcolor:#0E7699,template_fthovercolor:#555555,template_titlecolor:#222222,related_title_color:#222222,template_contentcolor:#888888,firstletter_contentcolor:#888888,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#0E7699,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#222222',
												'display_value' => '#0E7699,#222222,#555555,#888888',
											),
											'spektrum_crimson' => array(
												'preset_name' => esc_html__( 'Crimson', 'blog-designer-pro' ),
												'preset_value' => 'template_ftcolor:#555555,template_fthovercolor:#E84159,template_titlecolor:#E21130,related_title_color:#E21130,template_contentcolor:#888888,firstletter_contentcolor:#888888,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#E21130,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#E84159',
												'display_value' => '#E21130,#E84159,#555555,#888888',
											),
											'spektrum_bronzetone' => array(
												'preset_name' => esc_html__( 'Bronzetone', 'blog-designer-pro' ),
												'preset_value' => 'template_ftcolor:#555555,template_fthovercolor:#67704E,template_titlecolor:#414C22,related_title_color:#414C22,template_contentcolor:#888888,firstletter_contentcolor:#888888,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#414C22,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#555555',
												'display_value' => '#414C22,#67704E,#555555,#888888',
											),
											'spektrum_west_side' => array(
												'preset_name' => esc_html__( 'West Side', 'blog-designer-pro' ),
												'preset_value' => 'template_ftcolor:#555555,template_fthovercolor:#E99955,template_titlecolor:#E4802A,related_title_color:#E4802A,template_contentcolor:#888888,firstletter_contentcolor:#888888,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#E4802A,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#E99955',
												'display_value' => '#E4802A,#E99955,#555555,#888888',
											),
											'spektrum_wasabi' => array(
												'preset_name' => esc_html__( 'Wasabi', 'blog-designer-pro' ),
												'preset_value' => 'template_ftcolor:#555555,template_fthovercolor:#93A564,template_titlecolor:#788F3D,related_title_color:#788F3D,template_contentcolor:#888888,firstletter_contentcolor:#888888,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#788F3D,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#93A564',
												'display_value' => '#788F3D,#93A564,#555555,#888888',
											),
											'spektrum_yonder' => array(
												'preset_name' => esc_html__( 'Yonder', 'blog-designer-pro' ),
												'preset_value' => 'template_ftcolor:#555555,template_fthovercolor:#8BA3CE,template_titlecolor:#6E8CC2,related_title_color:#6E8CC2,template_contentcolor:#888888,firstletter_contentcolor:#888888,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#6E8CC2,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#8BA3CE',
												'display_value' => '#6E8CC2,#8BA3CE,#555555,#888888',
											),
										),
										'story'            => array(
											'story_default' => array(
												'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#4458c9,template_color:#b00dd8,template_alternative_color:#b00dd8,story_startup_border_color:#ffffff,story_startup_background:#85e71c,story_startup_text_color:#333333,story_ending_background:#ade175,story_ending_text_color:#333333,template_ftcolor:#4458c9,template_fthovercolor:#2b2b2b,template_titlecolor:#707070,related_title_color:#707070,template_contentcolor:#666666,firstletter_contentcolor:#666666,bdp_edd_price_color:#333333,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#4458c9,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#333333',
												'display_value' => '#4458c9,#b00dd8,#85e71c,#333333',
											),
											'story_goldenrod' => array(
												'preset_name' => esc_html__( 'Goldenrod', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#daa520,template_color:#2055da,template_alternative_color:#d23582,story_startup_border_color:#da4820,story_startup_background:#da4820,story_startup_text_color:#ffffff,story_ending_background:#da4820,story_ending_text_color:#ffffff,template_ftcolor:#da4820,template_fthovercolor:#2b2b2b,template_titlecolor:#707070,related_title_color:#707070,template_contentcolor:#666666,firstletter_contentcolor:#666666,bdp_edd_price_color:#333333,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#daa520,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#da4820',
												'display_value' => '#daa520,#2055da,#da4820,#333333',
											),
											'story_radical_red' => array(
												'preset_name' => esc_html__( 'Radical Red', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ff355e,template_color:#355eff,template_alternative_color:#f18547,story_startup_border_color:#5d8a99,story_startup_background:#5d8a99,story_startup_text_color:#ffffff,story_ending_background:#5d8a99,story_ending_text_color:#ffffff,template_ftcolor:#5d8a99,template_fthovercolor:#2b2b2b,template_titlecolor:#707070,related_title_color:#707070,template_contentcolor:#666666,firstletter_contentcolor:#666666,bdp_edd_price_color:#333333,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#ff355e,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#5d8a99',
												'display_value' => '#ff355e,#355eff,#5d8a99,#333333',
											),
										),
										'tagly'            => array(
											'tagly_default' => array(
												'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#d4c6a8,template_ftcolor:#b79a5e,template_fthovercolor:#d4c6a8,template_titlecolor:#b79a5e,related_title_color:#333333,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#b79a5e,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#333333',
												'display_value' => '#b79a5e,#d4c6a8,#333333,#555555',
											),
											'tagly_crimson' => array(
												'preset_name' => esc_html__( 'Crimson', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#E84159,template_ftcolor:#E21130,template_fthovercolor:#E84159,template_titlecolor:#E21130,related_title_color:#E21130,template_contentcolor:#888888,firstletter_contentcolor:#888888,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#E21130,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#E84159',
												'display_value' => '#E21130,#E84159,#555555,#888888',
											),
											'tagly_cerulean' => array(
												'preset_name' => esc_html__( 'Cerulean', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#3E91AD,template_ftcolor:#0E7699,template_fthovercolor:#3E91AD,template_titlecolor:#0E7699,related_title_color:#0E7699,template_contentcolor:#888888,firstletter_contentcolor:#888888,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#0E7699,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#3E91AD',
												'display_value' => '#0E7699,#3E91AD,#555555,#888888',
											),
											'tagly_wasabi' => array(
												'preset_name' => esc_html__( 'Wasabi', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#93A564,template_ftcolor:#788F3D,template_fthovercolor:#93A564,template_titlecolor:#788F3D,related_title_color:#788F3D,template_contentcolor:#888888,firstletter_contentcolor:#888888,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#788F3D,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#93A564',
												'display_value' => '#788F3D,#93A564,#555555,#888888',
											),
											'tagly_ce_soir' => array(
												'preset_name' => esc_html__( 'Ce Soir', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#A381BB,template_ftcolor:#8C62AA,template_fthovercolor:#A381BB,template_titlecolor:#8C62AA,related_title_color:#8C62AA,template_contentcolor:#888888,firstletter_contentcolor:#888888,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#8C62AA,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#A381BB',
												'display_value' => '#8C62AA,#A381BB,#555555,#888888',
											),
											'tagly_earls-green' => array(
												'preset_name' => esc_html__( 'Earls Green', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#CEBF59,template_ftcolor:#C2AF2F,template_fthovercolor:#CEBF59,template_titlecolor:#C2AF2F,related_title_color:#C2AF2F,template_contentcolor:#888888,firstletter_contentcolor:#888888,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#C2AF2F,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#888888',
												'display_value' => '#C2AF2F,#CEBF59,#555555,#888888',
											),
										),
										'timeline'         => array(
											'timeline_default' => array(
												'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
												'preset_value' => 'displaydate_backcolor:#0099CB,template_color:#0099CB,template_ftcolor:#0099CB,template_fthovercolor:#333333,template_titlecolor:#0099CB,related_title_color:#222222,template_titlebackcolor:#E6F5FA,template_contentcolor:#333333,firstletter_contentcolor:#333333,bdp_edd_price_color:#333333,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#0099CB,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#005B79',
												'display_value' => '#0099CB,#005B79,#222222,#333333',
											),
											'timeline_venetian_red' => array(
												'preset_name' => esc_html__( 'Venetian Red', 'blog-designer-pro' ),
												'preset_value' => 'displaydate_backcolor:#414a54,template_color:#CC0001,template_ftcolor:#f15f74,template_fthovercolor:#444444,template_titlecolor:#f15f74,related_title_color:#f15f74,template_titlebackcolor:#ffffff,template_contentcolor:#333333,firstletter_contentcolor:#333333,bdp_edd_price_color:#333333,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#CC0001,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#444444',
												'display_value' => '#CC0001,#444444,#f15f74,#333333',
											),
											'timeline_pink' => array(
												'preset_name' => esc_html__( 'Dark Sea Green', 'blog-designer-pro' ),
												'preset_value' => 'displaydate_backcolor:#f15f74,template_color:#8FBC8F,template_ftcolor:#f15f74,template_fthovercolor:#444444,template_titlecolor:#475E47,related_title_color:#475E47,template_titlebackcolor:#F6F8F5,template_contentcolor:#333333,firstletter_contentcolor:#333333,bdp_edd_price_color:#333333,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#8FBC8F,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#475E47',
												'display_value' => '#8FBC8F,#475E47,#f15f74,#333333',
											),
											'timeline_dark_orchid' => array(
												'preset_name' => esc_html__( 'Dark Orchid', 'blog-designer-pro' ),
												'preset_value' => 'displaydate_backcolor:#9A33CC,template_color:#9A33CC,template_ftcolor:#CC9932,template_fthovercolor:#444444,template_titlecolor:#5B1E7A,related_title_color:#5B1E7A,template_titlebackcolor:#F5EAFA,template_contentcolor:#333333,firstletter_contentcolor:#333333,bdp_edd_price_color:#333333,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#9A33CC,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#5B1E7A',
												'display_value' => '#9A33CC,#5B1E7A,#CC9932,#333333',
											),
											'timeline_dark_orange' => array(
												'preset_name' => esc_html__( 'Dark Orange', 'blog-designer-pro' ),
												'preset_value' => 'displaydate_backcolor:#FF8A00,template_color:#FF8A00,template_ftcolor:#0073FF,template_fthovercolor:#444444,template_titlecolor:#7F4600,related_title_color:#7F4600,template_titlebackcolor:#FFF3E5,template_contentcolor:#333333,firstletter_contentcolor:#333333,bdp_edd_price_color:#333333,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#FF8A00,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#7F4600',
												'display_value' => '#FF8A00,#7F4600,#0073FF,#333333',
											),
											'timeline_venetian_red' => array(
												'preset_name' => esc_html__( 'Venetian Red', 'blog-designer-pro' ),
												'preset_value' => 'displaydate_backcolor:#CC0001,template_color:#C80815,template_ftcolor:#08C8BB,template_fthovercolor:#444444,template_titlecolor:#78040C,related_title_color:#78040C,template_titlebackcolor:#FAE4E6,template_contentcolor:#333333,firstletter_contentcolor:#333333,bdp_edd_price_color:#333333,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#C80815,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#78040C',
												'display_value' => '#C80815,#78040C,#08C8BB,#333333',
											),
										),
										'winter'           => array(
											'winter_default' => array(
												'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
												'preset_value' => 'winter_category_color:#e7492f,template_ftcolor:#37aece,template_fthovercolor:#444444,template_titlecolor:#37aece,related_title_color:#444444,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#e7492f,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#37aece',
												'display_value' => '#e7492f,#37aece,#444444,#555555',
											),
											'winter_wasabi' => array(
												'preset_name' => esc_html__( 'Wasabi', 'blog-designer-pro' ),
												'preset_value' => 'winter_category_color:#93A564,template_ftcolor:#93A564,template_fthovercolor:#555555,template_titlecolor:#788F3D,related_title_color:#788F3D,template_contentcolor:#999999,firstletter_contentcolor:#999999,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#788F3D,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#788F3D',
												'display_value' => '#788F3D,#788F3D,#555555,#999999',
											),
											'winter_yonder' => array(
												'preset_name' => esc_html__( 'Yonder', 'blog-designer-pro' ),
												'preset_value' => 'winter_category_color:#8BA3CE,template_ftcolor:#8BA3CE,template_fthovercolor:#555555,template_titlecolor:#6E8CC2,related_title_color:#6E8CC2,template_contentcolor:#999999,firstletter_contentcolor:#999999,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#6E8CC2,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#8BA3CE',
												'display_value' => '#6E8CC2,#8BA3CE,#555555,#999999',
											),
											'winter_regal_blue' => array(
												'preset_name' => esc_html__( 'Regal Blue', 'blog-designer-pro' ),
												'preset_value' => 'winter_category_color:#435F7F,template_ftcolor:#435F7F,template_fthovercolor:#555555,template_titlecolor:#14375F,related_title_color:#14375F,template_contentcolor:#999999,firstletter_contentcolor:#999999,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#14375F,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#435F7F',
												'display_value' => '#14375F,#435F7F,#555555,#999999',
											),
											'winter_buttercup' => array(
												'preset_name' => esc_html__( 'Buttercup', 'blog-designer-pro' ),
												'preset_value' => 'winter_category_color:#E5A452,template_ftcolor:#E5A452,template_fthovercolor:#555555,template_titlecolor:#DF8D27,related_title_color:#DF8D27,template_contentcolor:#999999,firstletter_contentcolor:#999999,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#DF8D27,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#E5A452',
												'display_value' => '#DF8D27,#E5A452,#555555,#999999',
											),
											'winter_alizarin' => array(
												'preset_name' => esc_html__( 'Alizarin', 'blog-designer-pro' ),
												'preset_value' => 'winter_category_color:#EE4861,template_ftcolor:#EE4861,template_fthovercolor:#555555,template_titlecolor:#EA1A3A,related_title_color:#EA1A3A,template_contentcolor:#999999,firstletter_contentcolor:#999999,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#EA1A3A,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#EE4861',
												'display_value' => '#EA1A3A,#EE4861,#555555,#999999',
											),
										),
										'minimal'          => array(
											'minimal_default' => array(
												'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#e84c89,template_fthovercolor:#e84c89,winter_category_color:#e84c89,template_titlecolor:#e84c89,related_title_color:#000000,template_contentcolor:#333333,firstletter_contentcolor:#444444,bdp_edd_price_color:#444444,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#e84c89,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#444444',
												'display_value' => '#ffffff,#e84c89,#444444,#000000',
											),
											'minimal_caribbean' => array(
												'preset_name' => esc_html__( 'Caribbean', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#0EE08B,template_fthovercolor:#0EE08B,winter_category_color:#0EE08B,template_titlecolor:#0EE08B,related_title_color:#043A25,template_contentcolor:#333333,firstletter_contentcolor:#065B39,bdp_edd_price_color:#065B39,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#0EE08B,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#065B39',
												'display_value' => '#DFFBF0,#0EE08B,#065B39,#043A25',
											),
											'minimal_cerulean' => array(
												'preset_name' => esc_html__( 'Cerulean', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#0E5476,template_fthovercolor:#0E5476,winter_category_color:#0E5476,template_titlecolor:#0E5476,related_title_color:#04161E,template_contentcolor:#333333,firstletter_contentcolor:#062230,bdp_edd_price_color:#062230,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#0E5476,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#062230',
												'display_value' => '#DFE8ED,#0E5476,#062230,#04161E',
											),
											'minimal_purple' => array(
												'preset_name' => esc_html__( 'Purple', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#8261DA,template_fthovercolor:#8261DA,winter_category_color:#8261DA,template_titlecolor:#8261DA,related_title_color:#221A39,template_contentcolor:#333333,firstletter_contentcolor:#352859,bdp_edd_price_color:#352859,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#8261DA,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#352859',
												'display_value' => '#EEE9FA,#8261DA,#352859,#221A39',
											),
											'minimal_harvest' => array(
												'preset_name' => esc_html__( 'Harvest', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#E6B571,template_fthovercolor:#E6B571,winter_category_color:#E6B571,template_titlecolor:#E6B571,related_title_color:#3C2F1E,template_contentcolor:#333333,firstletter_contentcolor:#5E4A2E,bdp_edd_price_color:#5E4A2E,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#E6B571,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#5E4A2E',
												'display_value' => '#FCF5EC,#E6B571,#5E4A2E,#3C2F1E',
											),
											'minimal_scarlet' => array(
												'preset_name' => esc_html__( 'Scarlet', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#F02F18,template_fthovercolor:#F02F18,winter_category_color:#F02F18,template_titlecolor:#F02F18,related_title_color:#3E0C06,template_contentcolor:#333333,firstletter_contentcolor:#62130A,bdp_edd_price_color:#62130A,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#F02F18,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#62130A',
												'display_value' => '#FDE4E1,#F02F18,#62130A,#3E0C06',
											),
										),
										'glamour'          => array(
											'glamour_default' => array(
												'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#f5c034,template_fthovercolor:#444444,template_titlecolor:#f5c034,related_title_color:#000000,template_contentcolor:#444444,firstletter_contentcolor:#444444,bdp_edd_price_color:#444444,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#f5c034,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#444444',
												'display_value' => '#ffffff,#f5c034,#444444,#000000',
											),
											'glamour_aqua' => array(
												'preset_name' => esc_html__( 'Aqua', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#00FFE9,template_fthovercolor:#003531,template_titlecolor:#00FFE9,related_title_color:#00221F,template_contentcolor:#003531,firstletter_contentcolor:#003531,bdp_edd_price_color:#003531,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#00FFE9,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#003531',
												'display_value' => '#DDFFFC,#00FFE9,#003531,#00221F',
											),
											'glamour_jade' => array(
												'preset_name' => esc_html__( 'Jade', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#00B97B,template_fthovercolor:#00261A,template_titlecolor:#00B97B,related_title_color:#001811,template_contentcolor:#00261A,firstletter_contentcolor:#00261A,bdp_edd_price_color:#00261A,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#00B97B,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#00261A',
												'display_value' => '#DDF6ED,#00B97B,#00261A,#001811',
											),
											'glamour_malibu' => array(
												'preset_name' => esc_html__( 'Malibu', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#60C1E8,template_fthovercolor:#152831,template_titlecolor:#60C1E8,related_title_color:#0E1A1F,template_contentcolor:#152831,firstletter_contentcolor:#152831,bdp_edd_price_color:#152831,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#60C1E8,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#152831',
												'display_value' => '#E9F6FC,#60C1E8,#152831,#0E1A1F',
											),
											'glamour_bourbon' => array(
												'preset_name' => esc_html__( 'Bourbon', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#AD6F49,template_fthovercolor:#2D1E13,template_titlecolor:#AD6F49,related_title_color:#170F0A,template_contentcolor:#2D1E13,firstletter_contentcolor:#2D1E13,bdp_edd_price_color:#2D1E13,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#AD6F49,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#2D1E13',
												'display_value' => '#F4ECE7,#AD6F49,#2D1E13,#170F0A',
											),
											'glamour_raven' => array(
												'preset_name' => esc_html__( 'Raven', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#696F7A,template_fthovercolor:#222528,template_titlecolor:#696F7A,related_title_color:#0E0F11,template_contentcolor:#222528,firstletter_contentcolor:#222528,bdp_edd_price_color:#222528,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#696F7A,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#222528',
												'display_value' => '#EAECED,#696F7A,#222528,#0E0F11',
											),
										),
										'famous'           => array(
											'famous_default' => array(
												'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
												'preset_value' => 'template_ftcolor:#f20075,template_fthovercolor:#333333,template_titlecolor:#f20075,related_title_color:#333333,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#f20075,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#555555',
												'display_value' => '#f20075,#a5a5a5,#555555,#333333',
											),
											'famous_vivid_gamboge' => array(
												'preset_name' => esc_html__( 'Vivid Gamboge', 'blog-designer-pro' ),
												'preset_value' => 'template_ftcolor:#F99900,template_fthovercolor:#333333,template_titlecolor:#F99900,related_title_color:#333333,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#F99900,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#555555',
												'display_value' => '#F99900,#a5a5a5,#555555,#333333',
											),
											'famous_timber_green' => array(
												'preset_name' => esc_html__( 'Timber Green', 'blog-designer-pro' ),
												'preset_value' => 'template_ftcolor:#3D3242,template_fthovercolor:#333333,template_titlecolor:#3D3242,related_title_color:#333333,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#3D3242,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#555555',
												'display_value' => '#3D3242,#a5a5a5,#555555,#333333',
											),
											'famous_jagger' => array(
												'preset_name' => esc_html__( 'Jagger', 'blog-designer-pro' ),
												'preset_value' => 'template_ftcolor:#374232,template_fthovercolor:#333333,template_titlecolor:#374232,related_title_color:#333333,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#374232,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#555555',
												'display_value' => '#374232,#a5a5a5,#555555,#333333',
											),
											'famous_barossa' => array(
												'preset_name' => esc_html__( 'Barossa', 'blog-designer-pro' ),
												'preset_value' => 'template_ftcolor:#423237,template_fthovercolor:#333333,template_titlecolor:#423237,related_title_color:#333333,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#423237,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#555555',
												'display_value' => '#423237,#a5a5a5,#555555,#333333',
											),
											'famous_blumine' => array(
												'preset_name' => esc_html__( 'Blumine', 'blog-designer-pro' ),
												'preset_value' => 'template_ftcolor:#2A5B66,template_fthovercolor:#333333,template_titlecolor:#2A5B66,related_title_color:#333333,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#2A5B66,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#555555',
												'display_value' => '#2A5B66,#a5a5a5,#555555,#333333',
											),
										),
										'fairy'            => array(
											'fairy_default' => array(
												'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#b6b6b6,template_fthovercolor:#0089bb,template_titlecolor:#0089bb,related_title_color:#000000,template_contentcolor:#5b5b5b,firstletter_contentcolor:#5b5b5b,bdp_edd_price_color:#5b5b5b,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#0089bb,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#5b5b5b',
												'display_value' => '#ffffff ,#0089bb,#5b5b5b,#000000',
											),
											'fairy_gorse'  => array(
												'preset_name' => esc_html__( 'Gorse', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#7E7415,template_fthovercolor:#F7E229,template_titlecolor:#F7E229,related_title_color:#221E06,template_contentcolor:#342F09,firstletter_contentcolor:#342F09,bdp_edd_price_color:#342F09,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#F7E229,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#342F09',
												'display_value' => '#FDFBE2 ,#F7E229,#342F09,#221E06',
											),
											'fairy_scampi' => array(
												'preset_name' => esc_html__( 'Scampi', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#393848,template_fthovercolor:#6F6E8D,template_titlecolor:#6F6E8D,related_title_color:#0F0E13,template_contentcolor:#18171E,firstletter_contentcolor:#18171E,bdp_edd_price_color:#18171E,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#6F6E8D,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#18171E',
												'display_value' => '#ECECF0 ,#6F6E8D,#18171E,#0F0E13',
											),
											'fairy_crusoe' => array(
												'preset_name' => esc_html__( 'Crusoe', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#083511,template_fthovercolor:#0F6620,template_titlecolor:#0F6620,related_title_color:#020E05,template_contentcolor:#041B09,firstletter_contentcolor:#041B09,bdp_edd_price_color:#041B09,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#0F6620,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#041B09',
												'display_value' => '#DFEAE1 ,#0F6620,#041B09,#020E05',
											),
											'fairy_seagull' => array(
												'preset_name' => esc_html__( 'Seagull', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#3E5866,template_fthovercolor:#7BADC8,template_titlecolor:#7BADC8,related_title_color:#11171B,template_contentcolor:#202D35,firstletter_contentcolor:#202D35,bdp_edd_price_color:#202D35,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#7BADC8,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#202D35',
												'display_value' => '#EDF4F8 ,#7BADC8,#202D35,#11171B',
											),
											'fairy_persimmon' => array(
												'preset_name' => esc_html__( 'Persimmon', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#722A05,template_fthovercolor:#722A05,template_titlecolor:#DF5309,related_title_color:#1E0B02,template_contentcolor:#2E1202,firstletter_contentcolor:#2E1202,bdp_edd_price_color:#2E1202,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#DF5309,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#2E1202',
												'display_value' => '#FAE8DD ,#DF5309,#2E1202,#1E0B02',
											),
										),
										'clicky'           => array(
											'clicky_default' => array(
												'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#a7a7a7,template_fthovercolor:#586f8c,winter_category_color:#586f8c,template_titlecolor:#586f8c,related_title_color:#586f8c,template_contentcolor:#686868,firstletter_contentcolor:#686868,bdp_edd_price_color:#686868,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#686868,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#586f8c',
												'display_value' => '#ffffff ,#a7a7a7,#686868,#586f8c',
											),
											'clicky_portage' => array(
												'preset_name' => esc_html__( 'Portage', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#9DA5E4,template_fthovercolor:#2A2B3C,winter_category_color:#2A2B3C,template_titlecolor:#9DA5E4,related_title_color:#2A2B3C,template_contentcolor:#2A2B3C,firstletter_contentcolor:#9DA5E4,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#2A2B3C,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#2A2B3C',
												'display_value' => '#ffffff ,#C1C5ED,#9DA5E4,#2A2B3C',
											),
											'clicky_emerald' => array(
												'preset_name' => esc_html__( 'Emerald', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#229541,template_fthovercolor:#0B3116,winter_category_color:#0B3116,template_titlecolor:#0B3116,related_title_color:#0B3116,template_contentcolor:#686868,firstletter_contentcolor:#165F2A,bdp_edd_price_color:#0B3116,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#686868,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#0B3116',
												'display_value' => '#E4FCEA ,#229541,#165F2A,#0B3116',
											),
										),
										'cover'            => array(
											'cover_default' => array(
												'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#b6b6b6,template_fthovercolor:#b6b6b6,template_titlecolor:#272727,related_title_color:#272727,template_contentcolor:#696969,firstletter_contentcolor:#696969,bdp_edd_price_color:#696969,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#696969,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#272727',
												'display_value' => '#ffffff ,#b6b6b6,#696969,#272727',
											),
											'cover_rust'  => array(
												'preset_name' => esc_html__( 'Rust', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#FCF6F5,template_ftcolor:#D1856D,template_fthovercolor:#B6401A,template_titlecolor:#301107,related_title_color:#301107,template_contentcolor:#4B1A0B,firstletter_contentcolor:#4B1A0B,bdp_edd_price_color:#4B1A0B,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#D1856D,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#4B1A0B',
												'display_value' => '#FCF6F5 ,#D1856D,#4B1A0B,#301107',
											),
											'cover_mulberry' => array(
												'preset_name' => esc_html__( 'Mulberry', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#FCF6FA,template_ftcolor:#DD92C7,template_fthovercolor:#CA55A7,template_titlecolor:#35162C,related_title_color:#35162C,template_contentcolor:#532245,firstletter_contentcolor:#532245,bdp_edd_price_color:#532245,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#532245,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#DD92C7',
												'display_value' => '#FCF6FA ,#DD92C7,#532245,#35162C',
											),
											'cover_green' => array(
												'preset_name' => esc_html__( 'Green', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ECF2E9,template_ftcolor:#4E8835,template_fthovercolor:#226A03,template_titlecolor:#0B2202,related_title_color:#0B2202,template_contentcolor:#123602,firstletter_contentcolor:#123602,bdp_edd_price_color:#123602,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#123602,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#4E8835',
												'display_value' => '#ECF2E9 ,#4E8835,#123602,#0B2202',
											),
											'cover_curious' => array(
												'preset_name' => esc_html__( 'Curious', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#F4F9FD,template_ftcolor:#81BCE0,template_fthovercolor:#3996CE,template_titlecolor:#0F2836,related_title_color:#0F2836,template_contentcolor:#183E55,firstletter_contentcolor:#183E55,bdp_edd_price_color:#183E55,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#81BCE0,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#183E55',
												'display_value' => '#F4F9FD ,#81BCE0,#183E55,#0F2836',
											),
											'cover_highball' => array(
												'preset_name' => esc_html__( 'Highball', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#FAFAF7,template_ftcolor:#969547,template_fthovercolor:#605F2E,template_titlecolor:#282713,related_title_color:#282713,template_contentcolor:#3E3D1E,firstletter_contentcolor:#3E3D1E,bdp_edd_price_color:#3E3D1E,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#3E3D1E,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#3E91AD',
												'display_value' => '#FAFAF7 ,#969547,#3E3D1E,#282713',
											),
										),
										'steps'            => array(
											'steps_default' => array(
												'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_color:#cbcbcb,template_ftcolor:#b7b7b7,template_fthovercolor:#f8c04e,template_titlecolor:#363636,related_title_color:#363636,template_contentcolor:#666666,firstletter_contentcolor:#666666,bdp_edd_price_color:#666666,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#f8c04e,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#363636',
												'display_value' => '#ffffff ,#cbcbcb,#f8c04e,#363636',
											),
											'steps_russett' => array(
												'preset_name' => esc_html__( 'Russett', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#F8F6F6,template_color:#DDD7D4,template_ftcolor:#AE9D96,template_fthovercolor:#81675B,template_titlecolor:#221B18,related_title_color:#221B18,template_contentcolor:#42352E,firstletter_contentcolor:#42352E,bdp_edd_price_color:#42352E,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#81675B,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#221B18',
												'display_value' => '#F8F6F6 ,#DDD7D4,#81675B,#221B18',
											),
											'steps_neon_blue' => array(
												'preset_name' => esc_html__( 'Neon Blue', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#F9F8FD,template_color:#D9D7FD,template_ftcolor:#8B84F7,template_fthovercolor:#4A3EF2,template_titlecolor:#0F0E32,related_title_color:#0F0E32,template_contentcolor:#1E1A63,firstletter_contentcolor:#42352E,bdp_edd_price_color:#42352E,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#4A3EF2,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#0F0E32',
												'display_value' => '#F9F8FD ,#D9D7FD,#4A3EF2,#0F0E32',
											),
										),
										'miracle'          => array(
											'miracle_default' => array(
												'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#e84c89,template_fthovercolor:#686868,template_titlecolor:#e84c89,related_title_color:#353535,template_contentcolor:#252525,firstletter_contentcolor:#252525,bdp_edd_price_color:#252525,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#62bf7c,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#353535',
												'display_value' => '#ffffff ,#62bf7c,#353535,#252525',
											),
											'miracle_lochmara' => array(
												'preset_name' => esc_html__( 'Lochmara', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#F7FAFC,template_ftcolor:#227CAD,template_fthovercolor:#227CAD,template_titlecolor:#051117,related_title_color:#051117,template_contentcolor:#09202D,firstletter_contentcolor:#09202D,bdp_edd_price_color:#09202D,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#227CAD,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#051117',
												'display_value' => '#F7FAFC ,#227CAD,#051117,#09202D',
											),
											'miracle_burgundy' => array(
												'preset_name' => esc_html__( 'Burgundy', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#FAF6F7,template_ftcolor:#6C0124,template_fthovercolor:#2C010E,template_titlecolor:#0E0105,related_title_color:#0E0105,template_contentcolor:#1C0109,firstletter_contentcolor:#1C0109,bdp_edd_price_color:#1C0109,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#6C0124,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#0E0105',
												'display_value' => '#FAF6F7 ,#6C0124,#0E0105,#1C0109',
											),
											'miracle_hillary' => array(
												'preset_name' => esc_html__( 'Hillary', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#FCFBFA,template_ftcolor:#A49E77,template_fthovercolor:#434131,template_titlecolor:#161610,related_title_color:#161610,template_contentcolor:#2B2A1F,firstletter_contentcolor:#2B2A1F,bdp_edd_price_color:#2B2A1F,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#A49E77,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#161610',
												'display_value' => '#FCFBFA ,#A49E77,#161610,#2B2A1F',
											),
											'miracle_amaranth' => array(
												'preset_name' => esc_html__( 'Amaranth', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#FDF9FA,template_ftcolor:#DE364A,template_fthovercolor:#491218,template_titlecolor:#1E070A,related_title_color:#1E070A,template_contentcolor:#2E0B0F,firstletter_contentcolor:#2E0B0F,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#DE364A,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#1E070A',
												'display_value' => '#FDF9FA ,#DE364A,#1E070A,#2E0B0F',
											),
											'miracle_manatee' => array(
												'preset_name' => esc_html__( 'Manatee', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#FCFCFD,template_ftcolor:#9699A7,template_fthovercolor:#3E3E45,template_titlecolor:#151516,related_title_color:#151516,template_contentcolor:#28282C,firstletter_contentcolor:#28282C,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#555555,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#151516',
												'display_value' => '#FCFCFD ,#9699A7,#151516,#28282C',
											),
										),
										'foodbox'          => array(
											'foodbox_default' => array(
												'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#6e3d30,template_bgcolor:#f7f4ef,template_ftcolor:#6e3d30,template_fthovercolor:#000000,template_titlecolor:#312725,template_titlehovercolor:#000000,template_contentcolor:#444444,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#312725,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#6e3d30',
												'display_value' => '#312725,#000000,#6e3d30,#444444',
											),
											'foodbox_radical' => array(
												'preset_name' => esc_html__( 'Radical', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#4F171C,template_bgcolor:#FDE7E9,template_ftcolor:#F34656,template_fthovercolor:#7C242C,template_titlecolor:#F34656,template_titlehovercolor:#4F171C,template_titlebackcolor:#FDE7E9,template_contentcolor:#444444,bdp_edd_price_color:#7C242C,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#F34656,bdp_edd_addtocart_text_hover_color:#FDE7E9,bdp_edd_addtocart_hover_backgroundcolor:#7C242C',
												'display_value' => '#FDE7E9,#F34656,#7C242C,#4F171C',
											),
											'Foodbox_tangerine' => array(
												'preset_name' => esc_html__( 'Tangerine', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#efb828,template_bgcolor:#fdf7e7,template_ftcolor:#473505,template_fthovercolor:#473505,template_titlecolor:#473505,template_titlehovercolor:#efb828,template_titlebackcolor:#fdf7e7,template_contentcolor:#444444,bdp_edd_price_color:#171101,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#171101,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#473505',
												'display_value' => '#f8df9f,#efb828,#473505,#171101',
											),
											'foodbox_rich-gold' => array(
												'preset_name' => esc_html__( 'Rich Gold', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#A95624,template_bgcolor:#ffd1b6,template_ftcolor:#A95624,template_fthovercolor:#444444,template_titlecolor:#A95624,template_titlehovercolor:#BA7850,template_titlebackcolor:#ffd1b6,template_contentcolor:#444444,bdp_edd_price_color:#BA7850,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#A95624,bdp_edd_addtocart_text_hover_color:#f1f1f1,bdp_edd_addtocart_hover_backgroundcolor:#444444',
												'display_value' => '#BA7850,#A95624,#555555,#444444',
											),
										),
										'neaty_block'      => array(
											'neaty_block_default' => array(
												'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#c4e2f7,template_ftcolor:#444444,template_fthovercolor:#000000,template_titlecolor:#444444,template_contentcolor:#444444,bdp_edd_price_color:#444444,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#444444,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#000000',
												'display_value' => '#444444,#000000,#444444,#000000',
											),
											'neaty_block_roof_terracotta' => array(
												'preset_name' => esc_html__( 'Roof Terracotta', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#B06F6D,template_bgcolor:#ffffff,template_bghovercolor:#F1E7E7,template_ftcolor:#B06F6D,template_fthovercolor:#555555,template_titlecolor:#9C4B48,template_titlehovercolor:#B06F6D,template_contentcolor:#999999,bdp_edd_price_color:#999999,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#B06F6D,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#555555',
												'display_value' => '#9C4B48,#B06F6D,#555555,#999999',
											),
											'neaty_block_bronzetone' => array(
												'preset_name' => esc_html__( 'Bronzetone', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#67704E,template_fthovercolor:#555555,template_titlecolor:#414C22,template_titlehovercolor:#67704E,template_titlebackcolor:#ffffff,template_contentcolor:#999999,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#414C22,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#999999',
												'display_value' => '#414C22,#67704E,#555555,#999999',
											),
											'offer_yonder' => array(
												'preset_name' => esc_html__( 'Yonder', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#F3F5FA,template_ftcolor:#8BA3CE,template_fthovercolor:#555555,template_titlecolor:#6E8CC2,template_titlehovercolor:#8BA3CE,template_titlebackcolor:#F3F5FA,template_contentcolor:#666666,bdp_edd_price_color:#555555,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#6E8CC2,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#666666',
												'display_value' => '#6E8CC2,#8BA3CE,#555555,#666666',
											),
										),
										'wise_block'       => array(
											'wise_block_default' => array(
												'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#444444,template_bgcolor:#c4e2f7,template_ftcolor:#444444,template_fthovercolor:#000000,template_titlecolor:#444444,template_titlehovercolor:#000000,template_contentcolor:#444444,bdp_edd_price_color:#444444,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#444444,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#000000',
												'display_value' => '#444444,#000000,#444444,#000000',
											),
											'wise_block_goldenrod' => array(
												'preset_name' => esc_html__( 'Goldenrod', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#F4EDDD,template_ftcolor:#3A2A02,template_fthovercolor:#5B4204,template_titlecolor:#5B4204,template_titlehovercolor:#3A2A02,template_titlebackcolor:#ffffff,template_contentcolor:#5B4204,bdp_edd_price_color:#F4EDDD, bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#B28007,bdp_edd_addtocart_text_hover_color:#F4EDDD,bdp_edd_addtocart_hover_backgroundcolor:#5B4204',
												'display_value' => '#F4EDDD,#B28007,#5B4204,#3A2A02',
											),
											'wise_block_salem' => array(
												'preset_name' => esc_html__( 'Salem', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#e8f3ed,template_bgcolor:#e8f3ed,template_ftcolor:#198c4b,template_fthovercolor:#666666,template_titlecolor:#333333,template_titlehovercolor:#198c4b,template_titlebackcolor:,template_contentcolor:#666666,bdp_edd_price_color:#333333,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#333333,bdp_edd_addtocart_text_hover_color:#e8f3ed,bdp_edd_addtocart_hover_backgroundcolor:#198c4b',
												'display_value' => '#e8f3ed,#198c4b,#333333,#666666',
											),
											'wise_block_prussian' => array(
												'preset_name' => esc_html__( 'Prussian', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#99a8ba,template_bgcolor:#e5e9ed,template_ftcolor:#000308,template_fthovercolor:#000b19,template_titlecolor:#193b65,template_titlehovercolor:#000b19,template_titlebackcolor:,template_contentcolor:#000308,bdp_edd_price_color:#000308,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#171101,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#000308',
												'display_value' => '#99a8ba,#193b65,#000b19,#000308',
											),
											'wise_block_pompadour' => array(
												'preset_name' => esc_html__( 'Pompadour', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#EAE1E7,template_ftcolor:#35122A,template_fthovercolor:#220B1B,template_titlecolor:#35122A,template_titlehovercolor:#662451,template_contentcolor:#EAE1E7,template_contentcolor:#35122A,bdp_edd_price_color:#35122A,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#662451,bdp_edd_addtocart_text_hover_color:#EAE1E7,bdp_edd_addtocart_hover_backgroundcolor:#220B1B',
												'display_value' => '#EAE1E7,#662451,#220B1B,#35122A',
											),
											'wise_block_go_ben' => array(
												'preset_name' => esc_html__( 'Go Ben', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#EDECE7,template_ftcolor:#3E3B25,template_fthovercolor:#282618,template_titlecolor:#3E3B25,template_titlehovercolor:#787449,template_contentcolor:#EDECE7,template_contentcolor:#3E3B25,bdp_edd_price_color:#3E3B25,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#787449,bdp_edd_addtocart_text_hover_color:#EDECE7,bdp_edd_addtocart_hover_backgroundcolor:#282618',
												'display_value' => '#EDECE7,#787449,#282618,#3E3B25',
											),
										),
										'soft_block'       => array(
											'soft_block_default' => array(
												'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#444444,template_bgcolor:#4fbfc1,template_ftcolor:#444444,template_fthovercolor:#444444,template_titlecolor:#444444,template_titlehovercolor:#ece0e0,template_contentcolor:#444444,template_bgcolor1:#4fbfc1,template_bgcolor2:#508FC4,template_bgcolor3:#F47882,template_bgcolor4:#F0CF80,template_bgcolor5:#75C77D,template_bgcolor6:#76ABD5,bdp_edd_price_color:#999999,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#444444,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#000000',
												'display_value' => '#444444,#000000,#ece0e0,#ffffff',
											),
											'soft_block_lochmara' => array(
												'preset_name' => esc_html__( 'Lochmara', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#4fbfc1,template_ftcolor:#227CAD,template_fthovercolor:#0E3246,template_titlecolor:#227CAD,template_titlehovercolor:#227CAD,template_contentcolor:#F7FAFC,bdp_edd_price_color:#051117,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#227CAD,bdp_edd_addtocart_text_hover_color:#F7FAFC,bdp_edd_addtocart_hover_backgroundcolor:#051117',
												'display_value' => '#F7FAFC,#227CAD,#051117,#09202D',
											),
											'soft_block_burgundy' => array(
												'preset_name' => esc_html__( 'Burgundy', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#4fbfc1,template_ftcolor:#6C0124,template_fthovercolor:#2C010E,template_titlecolor:#6C0124,template_titlehovercolor:#6C0124,template_contentcolor:#FAF6F7,bdp_edd_price_color:#0E0105,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#6C0124,bdp_edd_addtocart_text_hover_color:#FAF6F7,bdp_edd_addtocart_hover_backgroundcolor:#0E0105',
												'display_value' => '#FAF6F7,#6C0124,#0E0105,#1C0109',
											),
											'soft_block_hillary' => array(
												'preset_name' => esc_html__( 'Hillary', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#4fbfc1,template_ftcolor:#A49E77,template_fthovercolor:#434131,template_titlecolor:#A49E77,template_titlehovercolor:#A49E77,template_contentcolor:#FCFBFA,bdp_edd_price_color:#161610,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#A49E77,bdp_edd_addtocart_text_hover_color:#FCFBFA,bdp_edd_addtocart_hover_backgroundcolor:#161610',
												'display_value' => '#FCFBFA,#A49E77,#161610,#2B2A1F',
											),
											'soft_block_amaranth' => array(
												'preset_name' => esc_html__( 'Amaranth', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#4fbfc1,template_ftcolor:#DE364A,template_fthovercolor:#491218,template_titlecolor:#DE364A,template_titlehovercolor:#DE364A,template_contentcolor:#FDF9FA,bdp_edd_price_color:#1E070A,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#DE364A,bdp_edd_addtocart_text_hover_color:#FDF9FA,bdp_edd_addtocart_hover_backgroundcolor:#1E070A',
												'display_value' => '#FDF9FA,#DE364A,#1E070A,#2E0B0F',
											),
											'soft_block_manatee' => array(
												'preset_name' => esc_html__( 'Manatee', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#4fbfc1,template_ftcolor:#9699A7,template_fthovercolor:#3E3E45,template_titlecolor:#9699A7,template_titlehovercolor:#9699A7,template_titlebackcolor:#FCFCFD,template_contentcolor:#FCFCFD,bdp_edd_price_color:#151516,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#9699A7,bdp_edd_addtocart_text_hover_color:#FCFCFD,bdp_edd_addtocart_hover_backgroundcolor:#151516',
												'display_value' => '#FCFCFD,#9699A7,#151516,#28282C',
											),
										),
										'schedule'         => array(
											'schedule_default' => array(
												'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#e21130,template_bgcolor:#ffffff,template_ftcolor:#a5a5a5,template_fthovercolor:#123123,template_titlecolor:#444444,,template_titlebackcolor:#ffffff,template_titlehovercolor:#123123,,template_contentcolor:#333333,winter_category_color:#a5a5a5',
												'display_value' => '#444444,#000000,#333333,#ffffff',
											),
											'schedule_radical' => array(
												'preset_name' => esc_html__( 'Radical', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#7C242C,template_bgcolor:#ffffff,template_ftcolor:#F9A1A9,template_fthovercolor:#F34656,template_titlecolor:#F34656,template_titlehovercolor:#4F171C,template_titlebackcolor:#FDE7E9,template_contentcolor:#7C242C,bdp_edd_price_color:#FDE7E9,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#F34656,bdp_edd_addtocart_text_hover_color:#FDE7E9,bdp_edd_addtocart_hover_backgroundcolor:#7C242C,
                                                    winter_category_color:#7C242C',
												'display_value' => '#FDE7E9,#F34656,#7C242C,#4F171C',
											),
											'schedule_mariner' => array(
												'preset_name' => esc_html__( 'Mariner', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#465BAC,template_bgcolor:#ffffff,template_ftcolor:#465BAC,template_fthovercolor:#EAEDF6,template_titlecolor:#465BAC,template_titlehovercolor:#484848,template_titlebackcolor:#eaedf6,template_contentcolor:#7b7b7b,bdp_edd_price_color:#484848,bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#465BAC,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_hover_backgroundcolor:#484848,winter_category_color:#465BAC',
												'display_value' => '#465BAC,#EAEDF6,#465BAC,#484848',
											),
											'schedule_flamenco' => array(
												'preset_name' => esc_html__( 'Flamenco', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#683C6F,template_bgcolor:#ffffff,template_ftcolor:#683C6F,template_fthovercolor:#FFF3ED,template_titlecolor:#683C6F,template_titlehovercolor:#484848,template_titlebackcolor:#FFF3ED,template_contentcolor:#7b7b7bfffff,,bdp_edd_price_color:#484848
                                                    bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#683C6F,bdp_edd_addtocart_text_hover_color:#683C6F,bdp_edd_addtocart_hover_backgroundcolor:#FFF3ED,
                                                    winter_category_color:#683C6F',
												'display_value' => '#683C6F,#FFF3ED,#683C6F,#484848',
											),
											'schedule_finch' => array(
												'preset_name' => esc_html__( 'Finch', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#75815B,template_bgcolor:#ffffff,template_ftcolor:#75815B,template_fthovercolor:#EFF0EB,template_titlecolor:#75815B,template_titlehovercolor:#484848,template_titlebackcolor:#EFF0EB,template_contentcolor:#7b7b7b,bdp_edd_price_color:#484848,
                                                    bdp_edd_addtocart_textcolor:#ffffff,bdp_edd_addtocart_backgroundcolor:#75815B,bdp_edd_addtocart_text_hover_color:#75815B,bdp_edd_addtocart_hover_backgroundcolor:#EFF0EB,winter_category_color:#75815B',
												'display_value' => '#75815B,#EFF0EB,#75815B,#484848',
											),
										),
										'pedal'            => array(
											'pedal_default' => array(
												'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#eeeeee,template_ftcolor:#d35400,template_fthovercolor:#555555,template_titlecolor:#d35400,related_title_color:#333333,template_contentcolor:#555555,firstletter_contentcolor:#555555',
												'display_value' => '#eeeeee,#d35400,#333333,#555555',
											),
											'pedal_mckenzie' => array(
												'preset_name' => esc_html__( 'McKenzie', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#eeeeee,template_ftcolor:#A2855B,template_fthovercolor:#333333,template_titlecolor:#A2855B,related_title_color:#333333,template_contentcolor:#555555,firstletter_contentcolor:#555555',
												'display_value' => '#eeeeee,#A2855B,#333333,#555555',
											),
											'pedal_fun_green' => array(
												'preset_name' => esc_html__( 'Fun Green', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#3E8563,template_ftcolor:#0E663C,template_fthovercolor:#3E8563,template_titlecolor:#0E663C,related_title_color:#0E663C,template_contentcolor:#555555,firstletter_contentcolor:#555555',
												'display_value' => '#0E663C,#3E8563,#333333,#555555',
											),
											'pedal_blackberry' => array(
												'preset_name' => esc_html__( 'Blackberry', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#eeeeee,template_ftcolor:#49182D,template_fthovercolor:#6D4657,template_titlecolor:#49182D,related_title_color:#49182D,template_contentcolor:#555555,firstletter_contentcolor:#555555',
												'display_value' => '#49182D,#6D4657,#333333,#555555',
											),
											'pedal_buttercup' => array(
												'preset_name' => esc_html__( 'Buttercup', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#eeeeee,template_ftcolor:#DF8D27,template_fthovercolor:#E5A452,template_titlecolor:#DF8D27,related_title_color:#DF8D27,template_contentcolor:#555555,firstletter_contentcolor:#555555',
												'display_value' => '#DF8D27,#E5A452,#333333,#555555',
											),
											'pedal_alizarin' => array(
												'preset_name' => esc_html__( 'Alizarin', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#eeeeee,template_ftcolor:#E81B3A,template_fthovercolor:#ED4961,template_titlecolor:#E81B3A,related_title_color:#E81B3A,template_contentcolor:#555555,firstletter_contentcolor:#555555',
												'display_value' => '#E81B3A,#ED4961,#333333,#555555',
											),
										),
										'quci'             => array(
											'quci_default' => array(
												'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#974772,template_ftcolor:#974772,template_fthovercolor:#d35400,template_titlecolor:#974772,related_title_color:#333333,template_contentcolor:#555555,firstletter_contentcolor:#555555',
												'display_value' => '#974772,#d35400,#333333,#555555',
											),
											'quci_mckenzie' => array(
												'preset_name' => esc_html__( 'McKenzie', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#eeeeee,template_ftcolor:#A2855B,template_fthovercolor:#333333,template_titlecolor:#A2855B,related_title_color:#333333,template_contentcolor:#555555,firstletter_contentcolor:#555555',
												'display_value' => '#eeeeee,#A2855B,#333333,#555555',
											),
											'quci_fun_green' => array(
												'preset_name' => esc_html__( 'Fun Green', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#3E8563,template_ftcolor:#0E663C,template_fthovercolor:#3E8563,template_titlecolor:#0E663C,related_title_color:#0E663C,template_contentcolor:#555555,firstletter_contentcolor:#555555',
												'display_value' => '#0E663C,#3E8563,#333333,#555555',
											),
											'quci_blackberry' => array(
												'preset_name' => esc_html__( 'Blackberry', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#eeeeee,template_ftcolor:#49182D,template_fthovercolor:#6D4657,template_titlecolor:#49182D,related_title_color:#49182D,template_contentcolor:#555555,firstletter_contentcolor:#555555',
												'display_value' => '#49182D,#6D4657,#333333,#555555',
											),
											'quci_buttercup' => array(
												'preset_name' => esc_html__( 'Buttercup', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#eeeeee,template_ftcolor:#DF8D27,template_fthovercolor:#E5A452,template_titlecolor:#DF8D27,related_title_color:#DF8D27,template_contentcolor:#555555,firstletter_contentcolor:#555555',
												'display_value' => '#DF8D27,#E5A452,#333333,#555555',
											),
											'quci_alizarin' => array(
												'preset_name' => esc_html__( 'Alizarin', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#eeeeee,template_ftcolor:#E81B3A,template_fthovercolor:#ED4961,template_titlecolor:#E81B3A,related_title_color:#E81B3A,template_contentcolor:#555555,firstletter_contentcolor:#555555',
												'display_value' => '#E81B3A,#ED4961,#333333,#555555',
											),
										),
									);
									foreach ( $template_color_preset as $key => $single_template ) {
										?>
										<div class="controls_preset <?php echo esc_attr( $key ); ?>" style="display:none;">
											<?php foreach ( $single_template as $name => $value ) { ?>
												<div class="color-option preset
												<?php
												if ( $bdp_color_preset == $name ) {
													echo ' color_preset_selected';
												}
												?>
												" data-value="<?php echo esc_attr( $value['preset_value'] ); ?>">
													<label>
														<input class="of-radio-color" type="radio" name="bdp_color_preset" value="<?php echo esc_attr( $name ); ?>" <?php checked( $bdp_color_preset, $name ); ?>>
													</label>
													<?php Bdp_Utility::admin_color_preset( $value['display_value'] ); ?>
												</div>
												<?php
											}
											?>
										</div>
										<?php
									}
									?>
								</div>
							</li>

							<div class="bdp_grid_col_3">
								<li>
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Single Layout Name', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<input type="text" name="single_layout_name" id="single_layout_name" value="<?php echo esc_attr( $single_template_name ); ?>" placeholder="<?php esc_attr_e( 'Enter single layout name', 'blog-designer-pro' ); ?>">
									</div>
								</li>
								<li>
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Override Single Product Design', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<?php $override_single = isset( $bdp_settings['override_single'] ) ? $bdp_settings['override_single'] : '0'; ?>
											<fieldset class="buttonset buttonset-hide" data-hide='1'>
												<input id="override_single_1" name="override_single" type="radio" value="1" <?php checked( 1, $override_single ); ?> />
												<label id="override_single" for="override_single_1" <?php checked( 1, $override_single ); ?>><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
												<input id="override_single_0" name="override_single" type="radio" value="0" <?php checked( 0, $override_single ); ?> />
												<label id="override_single" for="override_single_0" <?php checked( 0, $override_single ); ?>><?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
											</fieldset>
									</div>
								</li>
								<li class="override-single-design-li">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Select Single Product Override Type', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<?php
										$bdp_single_type = '';
										if ( isset( $bdp_settings['bdp_single_type'] ) ) {
											$bdp_single_type = $bdp_settings['bdp_single_type'];
										}
										if ( 'all' === $custom_single_type ) {
											$all_setting = '';
										} else {
											$all_setting = Bdp_Template::get_all_single_download_template_settings();
										}
										?>
										<select id="bdp_single_type" name="bdp_single_type">
											<option value="all" 
											<?php
											if ( $all_setting ) {
												echo "disabled='disabled'";
											}
											?>
											<?php echo selected( 'all', $bdp_single_type ); ?>><?php esc_html_e( 'All Downloads', 'blog-designer-pro' ); ?></option>
											<option value="category" <?php echo selected( 'category', $bdp_single_type ); ?>><?php esc_html_e( 'Category Wise', 'blog-designer-pro' ); ?></option>
											<option value="tag" <?php echo selected( 'tag', $bdp_single_type ); ?>><?php esc_html_e( 'Tag Wise', 'blog-designer-pro' ); ?></option>
										</select>
										<div class="bdp-setting-description bdp-note">
											<b class="note"><?php esc_html_e( 'Note', 'blog-designer-pro' ); ?>:</b>
											<?php esc_html_e( 'If you select category/tag product override type, you must have to select category/tag type to show product.', 'blog-designer-pro' ); ?>
										</div>
									</div>
								</li>
								<li class="override-single-design-li single_category_list_tr">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Select Product Categories', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right single_download_category_list_tr">
										<?php
										$template_category = isset( $bdp_settings['template_category'] ) ? $bdp_settings['template_category'] : array();
										$categories        = get_categories(
											array(
												'child_of' => '',
												'hide_empty' => 1,
												'taxonomy' => 'download_category',
											)
										);
										$db_categories     = $wpdb->get_results( 'SELECT sub_categories FROM ' . $wpdb->prefix . 'bdp_single_ed_download WHERE single_download_template = "category"' );
										$db_category_list  = array();
										if ( $db_categories ) {
											foreach ( $db_categories as $db_category ) {
												$sub_list = $db_category->sub_categories;
												if ( $sub_list ) {
													$db_category_ids = explode( ',', $sub_list );
													foreach ( $db_category_ids as $db_category_id ) {
														$db_category_list[] = $db_category_id;
													}
												}
											}
										}
										$final_cat = array();
										if ( is_array( $template_category ) && ! empty( $template_category ) ) {
											$final_cat = array_diff( $db_category_list, $template_category );
										}

										?>
										<select data-placeholder="<?php esc_attr_e( 'Choose Product Categories', 'blog-designer-pro' ); ?>" class="chosen-select" multiple style="width:220px;" name="template_category[]" id="template_category"><?php foreach ( $categories as $category_obj ) : ?>
										<option value="<?php echo esc_attr( $category_obj->term_id ); ?>" 
											<?php
											if ( @in_array( $category_obj->term_id, $template_category ) ) {
												echo 'selected="selected"';
											}
											if ( in_array( $category_obj->term_id, $final_cat ) ) {
												echo 'disabled="disabled"';
											}
											?>
										><?php echo esc_html( $category_obj->name ); ?></option><?php endforeach; ?>
										</select>
									</div>
								</li>
								<li class="override-single-design-li single_tag_list_tr">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Select Product Tags', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<?php
										$template_tags = isset( $bdp_settings['template_tags'] ) ? $bdp_settings['template_tags'] : array();
										$tags          = get_terms( 'download_tag' );
										$db_tags       = $wpdb->get_results( 'SELECT sub_categories FROM ' . $wpdb->prefix . 'bdp_single_ed_download WHERE single_download_template = "tag"' );
										$db_tag_list   = array();
										if ( $db_tags ) {
											foreach ( $db_tags as $db_tag ) {
												$sub_list = $db_tag->sub_categories;
												if ( $sub_list ) {
													$db_tag_ids = explode( ',', $sub_list );
													foreach ( $db_tag_ids as $db_tag_id ) {
														$db_tag_list[] = $db_tag_id;
													}
												}
											}
										}
										$final_tag = array();
										if ( is_array( $template_tags ) && ! empty( $template_tags ) ) {
											$final_tag = array_diff( $db_tag_list, $template_tags );
										}

										?>
										<select data-placeholder="<?php esc_attr_e( 'Choose Product Tags', 'blog-designer-pro' ); ?>" class="chosen-select" multiple style="width:220px;" name="template_tags[]" id="template_tags">
											<?php foreach ( $tags as $tag ) : ?>
												<option value="<?php echo esc_attr( $tag->term_id ); ?>" 
													<?php
													if ( @in_array( $tag->term_id, $template_tags ) ) {
														echo 'selected="selected"';
													}
													if ( in_array( $tag->term_id, $final_tag ) ) {
														echo 'disabled="disabled"';
													}
													?>
												><?php echo esc_html( $tag->name ); ?></option>
													<?php endforeach; ?>
										</select>
									</div>
								</li>
								<li class="override-single-design-li single_all_post_tr">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Select Products', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right single_all_download_tr">
										<?php
										$template_posts = isset( $bdp_settings['template_posts'] ) ? $bdp_settings['template_posts'] : array();
										$db_posts       = $wpdb->get_results( 'SELECT single_download_id FROM ' . $wpdb->prefix . 'bdp_single_ed_download' );
										$db_posts_list  = array();
										if ( $db_posts ) {
											foreach ( $db_posts as $db_post ) {
												$sub_list = $db_post->single_download_id;
												if ( $sub_list ) {
													$db_post_ids = explode( ',', $sub_list );
													foreach ( $db_post_ids as $db_post_id ) {
														$db_posts_list[] = $db_post_id;
													}
												}
											}
										}
										$final_posts = array();
										if ( is_array( $template_posts ) && ! empty( $template_posts ) ) {
											$final_posts = array_diff( $db_posts_list, $template_posts );
										}
										if ( 'tag' === $bdp_single_type ) {
											$tag_ids = isset( $bdp_settings['template_tags'] ) ? $bdp_settings['template_tags'] : array();
											if ( ! empty( $tag_ids ) ) {
												$args              = array(
													'cache_results' => false,
													'no_found_rows' => true,
													'fields' => 'ids',
													'showposts' => '-1',
													'post_status' => 'publish',
													'post_type' => 'download',
												);
												$args['tax_query'] = array(
													array(
														'taxonomy' => 'download_tag',
														'field'    => 'term_id',
														'terms'    => $tag_ids,
													),
												);
											} else {
												$args = array(
													'cache_results' => 'false',
													'fields'  => 'ids',
													'posts_per_page' => -1,
													'post_type' => 'download',
													'orderby' => 'date',
													'order'   => 'desc',
												);
											}
										} elseif ( 'category' === $bdp_single_type ) {
											$cat_ids = isset( $bdp_settings['template_category'] ) ? $bdp_settings['template_category'] : array();
											if ( ! empty( $cat_ids ) ) {
												$args              = array(
													'cache_results' => false,
													'no_found_rows' => true,
													'fields' => 'ids',
													'showposts' => '-1',
													'post_status' => 'publish',
													'post_type' => 'download',
												);
												$args['tax_query'] = array(
													array(
														'taxonomy' => 'download_category',
														'field'    => 'term_id',
														'terms'    => $cat_ids,
													),
												);
											} else {
												$args = array(
													'cache_results' => 'false',
													'fields'  => 'ids',
													'posts_per_page' => -1,
													'post_type' => 'download',
													'orderby' => 'date',
													'order'   => 'desc',
												);
											}
										} else {
											$args = array(
												'cache_results' => 'false',
												'no_found_rows' => true,
												'fields'  => 'ids',
												'posts_per_page' => -1,
												'post_type' => 'download',
												'orderby' => 'date',
												'order'   => 'desc',
											);
										}
										$allposts = get_posts( $args );
										if ( $allposts ) {
											$bdp_single_post_hidden = '';
											if ( ! empty( $template_posts ) ) {
												$bdp_single_post_hidden = implode( ',', $template_posts );
											}
											?>
											<input type="hidden" value="download" name="bdp_single_post_type" id="bdp_single_post_type">
											<input type="hidden" value="<?php echo esc_attr( $bdp_single_post_hidden ); ?>" name="bdp_single_post_hidden" id="bdp_single_post_hidden">
											<select data-placeholder="<?php esc_attr_e( 'Choose Products', 'blog-designer-pro' ); ?>" class="chosen-select" multiple style="width:220px;" name="template_posts[]" id="template_posts">
												<?php
												foreach ( $allposts as $single_download_id ) {
													$single_post = get_post( $single_download_id );
													setup_postdata( $single_post );
													// if ( in_array( $single_post->ID, $final_posts ) ) {.
													?>
													<option value="<?php echo esc_attr( $single_post->ID ); ?>"
														<?php
														if ( ! empty( $bdp_settings['template_posts'] ) && is_array( $bdp_settings['template_posts'] ) && in_array( $single_post->ID, $bdp_settings['template_posts'] ) ) {
															echo 'selected="selected"';
														}
														if ( ! empty( $final_posts ) && is_array( $final_posts ) && in_array( $single_post->ID, $final_posts ) ) {
															echo 'disabled="disabled"';
														}
														?>
													><?php echo esc_html( $single_post->post_title ); ?>
													</option>
														<?php
														// }
												}
												wp_reset_postdata();
												?>
											</select>
											<div class="bdp-setting-description bdp-note">
												<b class="note"><?php esc_html_e( 'Note', 'blog-designer-pro' ); ?>:</b>
												<?php esc_html_e( 'Default All Products Selected', 'blog-designer-pro' ); ?>
											</div>
											<?php
										} else {
											esc_html_e( 'No Products found', 'blog-designer-pro' );
										}
										?>
									</div>
								</li>
							</div>
							<h3 class="bdp-table-title override-single-design-li bdp-display-settings"><?php esc_html_e( 'Display Settings', 'blog-designer-pro' ); ?></h3>
							<li class="override-single-design-li bdp-display-settings">
								<div class="bdp-typography-wrapper bdp-button-settings ">
									<div class="bdp-typography-cover">
										<div class="bdp-typography-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Product Title', 'blog-designer-pro' ); ?>
											</span>
										</div>
										<div class="bdp-typography-content">
											<?php $display_title = isset( $bdp_settings['display_title'] ) ? $bdp_settings['display_title'] : 1; ?>
											<fieldset class="bdp-social-options bdp-display_title buttonset">
												<input id="display_title_1" name="display_title" type="radio" value="1" <?php echo checked( 1, $display_title ); ?> />
												<label for="display_title_1"><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
												<input id="display_title_0" name="display_title" type="radio" value="0" <?php echo checked( 0, $display_title ); ?> />
												<label for="display_title_0"><?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
											</fieldset>
										</div>
									</div>
									<?php
										$taxonomy_names = get_object_taxonomies( 'download', 'objects' );
										$taxonomy_names = apply_filters( 'bdp_hide_taxonomies', $taxonomy_names );
									if ( ! empty( $taxonomy_names ) ) {
										foreach ( $taxonomy_names as $taxonomy_name ) {
											if ( ! empty( $taxonomy_name ) ) {
												?>
													<div class="bdp-typography-cover">
														<div class="bdp-typography-label">
															<span class="bdp-key-title">
															<?php echo esc_html( $taxonomy_name->label ); ?>
															</span>
														</div>
														<div class="bdp-typography-content">
														<?php
														$_name              = 'display_taxonomy_' . $taxonomy_name->name;
														$display_custom_tax = isset( $bdp_settings[ $_name ] ) ? $bdp_settings[ $_name ] : 0;
														?>
															<fieldset class="bdp-social-options bdp-display_tax buttonset">
																<input id="<?php echo esc_attr( $_name ) . '_1'; ?>" name="<?php echo esc_attr( $_name ); ?>" type="radio" value="1" <?php echo checked( 1, $display_custom_tax ); ?>/>
																<label for="<?php echo esc_attr( $_name ) . '_1'; ?>"><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
																<input id="<?php echo esc_attr( $_name ) . '_0'; ?>" name="<?php echo esc_attr( $_name ); ?>" type="radio" value="0" <?php echo checked( 0, $display_custom_tax ); ?> />
																<label for="<?php echo esc_attr( $_name ) . '_0'; ?>"><?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
															</fieldset>
															</label><label class="disable_link">
																<input id="disable_link_taxonomy_<?php echo esc_attr( $taxonomy_name->name ); ?>" name="disable_link_taxonomy_<?php echo esc_attr( $taxonomy_name->name ); ?>" type="checkbox" value="1" 
																<?php
																if ( isset( $bdp_settings[ 'disable_link_taxonomy_' . $taxonomy_name->name ] ) ) {
																	checked( 1, $bdp_settings[ 'disable_link_taxonomy_' . $taxonomy_name->name ] );
																}
																?>
																/> <?php esc_html_e( 'Disable Link', 'blog-designer-pro' ); ?>
															</label>
														</div>
													</div>
													<?php
											}
										}
									}
									?>
									<div class="bdp-typography-cover">
										<div class="bdp-typography-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Product Author', 'blog-designer-pro' ); ?>
											</span>
										</div>
										<div class="bdp-typography-content">
											<?php $display_author = isset( $bdp_settings['display_author'] ) ? $bdp_settings['display_author'] : 1; ?>
											<fieldset class="bdp-social-options bdp-display_author buttonset">
												<input id="display_author_1" name="display_author" type="radio" value="1"  <?php checked( 1, $display_author ); ?> />
												<label for="display_author_1"><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
												<input id="display_author_0" name="display_author" type="radio" value="0" <?php checked( 0, $display_author ); ?> />
												<label for="display_author_0"><?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
											</fieldset>
										</div>
									</div>

									<div class="bdp-typography-cover">
										<div class="bdp-typography-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Product Date', 'blog-designer-pro' ); ?>
											</span>
										</div>
										<div class="bdp-typography-content">
											<?php $display_date = isset( $bdp_settings['display_date'] ) ? $bdp_settings['display_date'] : 1; ?>
											<fieldset class="bdp-social-options bdp-display_date buttonset buttonset-hide ui-buttonset" data-hide="1">
												<input id="display_date_1" name="display_date" type="radio" value="1" <?php checked( 1, $display_date ); ?> />
												<label for="display_date_1" <?php checked( 1, $display_date ); ?>><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
												<input id="display_date_0" name="display_date" type="radio" value="0" <?php checked( 0, $display_date ); ?> />
												<label for="display_date_0" <?php checked( 0, $display_date ); ?>><?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
											</fieldset>
										</div>
									</div>
									<div class="bdp-typography-cover display-postlike">
										<div class="bdp-typography-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Product Like', 'blog-designer-pro' ); ?>
											</span>
										</div>
										<div class="bdp-typography-content">
											<?php $display_postlike = isset( $bdp_settings['display_postlike'] ) ? $bdp_settings['display_postlike'] : '0'; ?>
											<fieldset class="buttonset">
												<input id="display_postlike_1" name="display_postlike" type="radio" value="1" <?php echo checked( 1, $display_postlike ); ?> />
												<label for="display_postlike_1"><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
												<input id="display_postlike_0" name="display_postlike" type="radio" value="0" <?php echo checked( 0, $display_postlike ); ?> />
												<label for="display_postlike_0"><?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
											</fieldset>
										</div>
									</div>

									<div class="bdp-typography-cover bdp_single_post_published_year">
										<div class="bdp-typography-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Product Published Year', 'blog-designer-pro' ); ?>
											</span>
										</div>
										<div class="bdp-typography-content">
											<?php
											$display_single_story_year = 1;
											if ( isset( $bdp_settings['display_single_story_year'] ) && '' != $bdp_settings['display_single_story_year'] ) {
												$display_single_story_year = $bdp_settings['display_single_story_year'];
											}
											?>
											<fieldset class="bdp-social-options bdp-display_author buttonset">
												<input id="display_single_story_year_1" name="display_single_story_year" type="radio" value="1"  <?php checked( 1, $display_single_story_year ); ?> />
												<label for="display_single_story_year_1"><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
												<input id="display_single_story_year_0" name="display_single_story_year" type="radio" value="0" <?php checked( 0, $display_single_story_year ); ?> />
												<label for="display_single_story_year_0"><?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
											</fieldset>
										</div>
									</div>

								</div>
							</li>
							<h3 class="bdp-table-title override-single-design-li bdp-display-settings bdp-display-date-settings"><?php esc_html_e( 'Display Date Settings', 'blog-designer-pro' ); ?></h3>
							<li class="override-single-design-li bdp-display-settings bdp-display-date-settings">
								<div class="bdp-typography-wrapper bdp-button-settings">
									<div class="bdp-typography-cover post_date_from_tr">
										<div class="bdp-typography-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Date', 'blog-designer-pro' ); ?>
											</span>
										</div>
										<div class="bdp-typography-content">
											<?php $dsiplay_date_from = isset( $bdp_settings['dsiplay_date_from'] ) ? $bdp_settings['dsiplay_date_from'] : 'publish'; ?>
											<select name="dsiplay_date_from" id="dsiplay_date_from">
												<option value="publish"  <?php echo selected( 'publish', $dsiplay_date_from ); ?>><?php esc_html_e( 'Publish Date', 'blog-designer-pro' ); ?></option>
												<option value="modify"  <?php echo selected( 'modify', $dsiplay_date_from ); ?>><?php esc_html_e( 'Last Modify Date', 'blog-designer-pro' ); ?></option>
											</select>
										</div>
									</div>
									<div class="bdp-typography-cover post_date_format_tr">
										<div class="bdp-typography-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Date Format', 'blog-designer-pro' ); ?>
											</span>
										</div>
										<div class="bdp-typography-content">
											<?php $post_date_format = isset( $bdp_settings['post_date_format'] ) ? $bdp_settings['post_date_format'] : 'default'; ?>
											<select name="post_date_format" id="post_date_format">
												<option value="default"  <?php echo selected( 'default', $post_date_format ); ?>><?php esc_html_e( 'Default', 'blog-designer-pro' ); ?></option>
												<option value="F j, Y g:i a"  <?php echo selected( 'F j, Y g:i a', $post_date_format ); ?>><?php echo esc_attr( gmdate( 'F j, Y g:i a', true ) ); ?></option>
												<option value="F j, Y"  <?php echo selected( 'F j, Y', $post_date_format ); ?>><?php echo esc_attr( gmdate( 'F j, Y', true ) ); ?></option>
												<option value="F, Y"  <?php echo selected( 'F, Y', $post_date_format ); ?>><?php echo esc_attr( gmdate( 'F, Y', true ) ); ?></option>
												<option value="j F  Y"  <?php echo selected( 'j F  Y', $post_date_format ); ?>><?php echo esc_attr( gmdate( 'j F  Y', true ) ); ?></option>
												<option value="g:i a"  <?php echo selected( 'g:i a', $post_date_format ); ?>><?php echo esc_attr( gmdate( 'g:i a', true ) ); ?></option>
												<option value="g:i:s a"  <?php echo selected( 'g:i:s a', $post_date_format ); ?>><?php echo esc_attr( gmdate( 'g:i:s a', true ) ); ?></option>
												<option value="l, F jS, Y"  <?php echo selected( 'l, F jS, Y', $post_date_format ); ?>><?php echo esc_attr( gmdate( 'l, F jS, Y', true ) ); ?></option>
												<option value="M j, Y @ G:i"  <?php echo selected( 'M j, Y @ G:i', $post_date_format ); ?>><?php echo esc_attr( gmdate( 'M j, Y @ G:i', true ) ); ?></option>
												<option value="Y/m/d g:i:s A"  <?php echo selected( 'Y/m/d g:i:s A', $post_date_format ); ?>><?php echo esc_attr( gmdate( 'Y/m/d g:i:s A', true ) ); ?></option>
												<option value="Y/m/d"  <?php echo selected( 'Y/m/d', $post_date_format ); ?>><?php echo esc_attr( gmdate( 'Y/m/d', true ) ); ?></option>
												<option value="d.m.Y"  <?php echo selected( 'd.m.Y', $post_date_format ); ?>><?php echo esc_attr( gmdate( 'd.m.Y', true ) ); ?></option>
												<option value="d-m-Y"  <?php echo selected( 'd-m-Y', $post_date_format ); ?>><?php echo esc_attr( gmdate( 'd-m-Y', true ) ); ?></option>
											</select>
										</div>
									</div>
								</div>
							</li>
							<li class="override-single-design-li">
								<div class="bdp-left">
									<span class="bdp-key-title">
										<?php esc_html_e( 'Product Views', 'blog-designer-pro' ); ?>
									</span>
								</div>
								<div class="bdp-right">
									<?php
									$display_post_views = 0;
									if ( isset( $bdp_settings['display_post_views'] ) ) {
										$display_post_views = $bdp_settings['display_post_views'];
									}
									?>
									<fieldset class="bdp-social-size buttonset buttonset-hide green" data-hide='1'>
										<input id="display_post_views_1" name="display_post_views" type="radio" value="1" <?php checked( 1, $display_post_views ); ?> />
										<label id="bdp-options-button" for="display_post_views_1" <?php checked( 1, $display_post_views ); ?>><?php esc_html_e( "Show Today's View", 'blog-designer-pro' ); ?></label>
										<input id="display_post_views_2" name="display_post_views" type="radio" value="2" <?php checked( 2, $display_post_views ); ?> />
										<label id="bdp-options-button" for="display_post_views_2" <?php checked( 2, $display_post_views ); ?>><?php esc_html_e( 'Show All Views', 'blog-designer-pro' ); ?></label>
										<input id="display_post_views_0" name="display_post_views" type="radio" value="0" <?php checked( 0, $display_post_views ); ?> />
										<label id="bdp-options-button" for="display_post_views_0" <?php checked( 0, $display_post_views ); ?>><?php esc_html_e( 'Hide', 'blog-designer-pro' ); ?></label>
									</fieldset>
								</div>
							</li>
							<li class="override-single-design-li">
								<div class="bdp-left">
									<span class="bdp-key-title">
										<?php esc_html_e( 'Custom CSS', 'blog-designer-pro' ); ?>
									</span>
								</div>
								<div class="bdp-right">
									<?php
									echo '<textarea class="widefat textarea" name="custom_css" id="custom_css" placeholder=".class_name{ color:#ffffff }">';
									if ( isset( $bdp_settings['custom_css'] ) ) {
										echo esc_textarea( wp_unslash( $bdp_settings['custom_css'] ) );
									}
									echo '</textarea>';
									?>
									<div class="bdp-setting-description bdp-note">
										<b class=""><?php esc_html_e( 'Example', 'blog-designer-pro' ); ?>:</b>
										<?php echo '.class_name{ color:#ffffff }'; ?>
									</div>
								</div>
							</li>
						</ul>
					</div>
				</div>
				<div id="bdpsinglestandard" class="postbox postbox-with-fw-options" style=<?php echo esc_attr( $bdpstandard_class_show ); ?>>
					<div class="inside">
						<ul class="bdp-settings bdp-lineheight">
							<li>
								<div class="bdp-left">
									<span class="bdp-key-title">
										<?php esc_html_e( 'Main Container Class Name', 'blog-designer-pro' ); ?>
									</span>
								</div>
								<div class="bdp-right">
									<?php $main_container_class = ( isset( $bdp_settings['main_container_class'] ) && '' != $bdp_settings['main_container_class'] ) ? $bdp_settings['main_container_class'] : ''; ?>
									<input type="text" name="main_container_class" id="main_container_class" value="<?php echo esc_attr( $main_container_class ); ?>" placeholder="<?php esc_attr_e( 'Enter main container class name', 'blog-designer-pro' ); ?>">
								</div>
							</li>
							<div class="bdp_grid_col_3">
								<li class="single-background-tr">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Background Color for Single Products', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<input type="text" name="template_bgcolor" id="template_bgcolor" value="<?php echo isset( $bdp_settings['template_bgcolor'] ) ? esc_attr( $bdp_settings['template_bgcolor'] ) : '#fff'; ?>"/>
									</div>
								</li>
								<li class="blog-templatecolor-tr">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Single Product Template Color', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<input type="text" name="template_color" id="template_color" value="<?php echo isset( $bdp_settings['template_color'] ) ? esc_attr( $bdp_settings['template_color'] ) : '#000'; ?>"/>
									</div>
								</li>
								<li class="story-startup-tr">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Story Startup Text', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<input type="text" name="story_startup_text" id="story_startup_text" value="<?php echo isset( $bdp_settings['story_startup_text'] ) ? esc_attr( $bdp_settings['story_startup_text'] ) : esc_html__( 'STARTUP', 'blog-designer-pro' ); ?>"/>
									</div>
								</li>
								<li class="display_bgimage_tr">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Select Background Image', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<span class="bdp_default_image_holder bdp_bg_image">
											<?php
											if ( isset( $bdp_settings['bdp_bg_image_src'] ) && '' != $bdp_settings['bdp_bg_image_src'] ) {
												echo '<img src="' . esc_url( $bdp_settings['bdp_bg_image_src'] ) . '"/>';
											}
											?>
										</span>
										<?php if ( isset( $bdp_settings['bdp_bg_image_src'] ) && '' != $bdp_settings['bdp_bg_image_src'] ) { ?>
											<input id="bdp-image-action-button" class="button bdp-remove_image_button bdp_bg_image" type="button" value="<?php esc_attr_e( 'Remove Image', 'blog-designer-pro' ); ?>">
										<?php } else { ?>
											<input class="button bdp-upload_image_button bdp_bg_image" type="button" value="<?php esc_attr_e( 'Upload Image', 'blog-designer-pro' ); ?>">
										<?php } ?>
										<input type="hidden" value="<?php echo isset( $bdp_settings['bdp_bg_image_id'] ) ? esc_attr( $bdp_settings['bdp_bg_image_id'] ) : ''; ?>" name="bdp_bg_image_id" id="bdp_bg_image_id">
										<input type="hidden" value="<?php echo isset( $bdp_settings['bdp_bg_image_src'] ) ? esc_attr( $bdp_settings['bdp_bg_image_src'] ) : ''; ?>" name="bdp_bg_image_src" id="bdp_bg_image_src">
										</div>
								</li>
								<li class="story-startup-tr">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Story Startup Background Color', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<input type="text" name="story_startup_background" id="story_startup_background" value="<?php echo isset( $bdp_settings['story_startup_background'] ) ? esc_attr( $bdp_settings['story_startup_background'] ) : '#ade175'; ?>" data-default-color="<?php echo isset( $bdp_settings['story_startup_background'] ) ? esc_attr( $bdp_settings['story_startup_background'] ) : '#ade175'; ?>"/>
									</div>
								</li>
								<li class="story-startup-tr">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Story Startup Text Color', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<input type="text" name="story_startup_text_color" id="story_startup_text_color" value="<?php echo isset( $bdp_settings['story_startup_text_color'] ) ? esc_attr( $bdp_settings['story_startup_text_color'] ) : '#333'; ?>" data-default-color="<?php echo isset( $bdp_settings['story_startup_text_color'] ) ? esc_attr( $bdp_settings['story_startup_text_color'] ) : '#333'; ?>"/>
									</div>
								</li>
								<li>
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Choose Link Color', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<input type="text" name="template_ftcolor" id="template_ftcolor" value="<?php echo isset( $bdp_settings['template_ftcolor'] ) ? esc_attr( $bdp_settings['template_ftcolor'] ) : ''; ?>" data-default-color="<?php echo isset( $bdp_settings['template_ftcolor'] ) ? esc_attr( $bdp_settings['template_ftcolor'] ) : ''; ?>"/>
									</div>
								</li>
								<li>
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Choose Link Hover Color', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<input type="text" name="template_fthovercolor" id="template_fthovercolor" value="
											<?php
											if ( isset( $bdp_settings['template_fthovercolor'] ) ) {
												echo esc_attr( $bdp_settings['template_fthovercolor'] );
											}
											?>
											" data-default-color="
											<?php
											if ( isset( $bdp_settings['template_fthovercolor'] ) ) {
												echo esc_attr( $bdp_settings['template_fthovercolor'] );
											}
											?>
											"/>
									</div>
								</li>
								<li class="winter-category-back-color" style="display: none;">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php echo esc_html( $winter_category_txt ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<input type="text" name="winter_category_color" id="winter_category_color" value="
											<?php
											if ( isset( $bdp_settings['winter_category_color'] ) ) {
												echo esc_attr( $bdp_settings['winter_category_color'] );
											}
											?>
											" data-default-color="
											<?php
											if ( isset( $bdp_settings['winter_category_color'] ) ) {
												echo esc_attr( $bdp_settings['winter_category_color'] );
											}
											?>
											"/>
									</div>
								</li>
							</div>
						</ul>
					</div>
				</div>
				<div id="bdpsingletitle" class="postbox postbox-with-fw-options" style=<?php echo esc_attr( $bdptitle_class_show ); ?>>
					<div class="inside">
						<ul class="bdp-settings bdp-lineheight">
							<li>
								<div class="bdp-left">
									<span class="bdp-key-title">
										<?php esc_html_e( 'Product Title Color', 'blog-designer-pro' ); ?>
									</span>
								</div>
								<div class="bdp-right">
									<input type="text" name="template_titlecolor" id="template_titlecolor" value="<?php echo isset( $bdp_settings['template_titlecolor'] ) ? esc_attr( $bdp_settings['template_titlecolor'] ) : ''; ?>"/>
								</div>
							</li>
							<h3 class="bdp-table-title"><?php esc_html_e( 'Box Shadow Settings', 'blog-designer-pro' ); ?></h3>
							<li class="edd_addtocart_button_box_shadow_setting">
								<div class="bdp-boxshadow-wrapper bdp-boxshadow-wrapper1">
									<div class="bdp-boxshadow-cover">
										<div class="bdp-boxshadow-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'H-offset', 'blog-designer-pro' ); ?>
											</span>
											<span class="bdp-px-prefix"><?php echo ' (px)'; ?></span>
										</div>
										<div class="bdp-boxshadow-content">
											<?php $bdp_title_top_box_shadow = isset( $bdp_settings['bdp_title_top_box_shadow'] ) ? $bdp_settings['bdp_title_top_box_shadow'] : '0'; ?>
											<input type="number" id="bdp_title_top_box_shadow" name="bdp_title_top_box_shadow" step="1" min="0" value="<?php echo esc_attr( $bdp_title_top_box_shadow ); ?>"  onkeypress="return isNumberKey(event)">
										</div>
									</div>
									<div class="bdp-boxshadow-cover">
										<div class="bdp-boxshadow-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'V-offset', 'blog-designer-pro' ); ?>
											</span>
											<span class="bdp-px-prefix"><?php echo ' (px)'; ?></span>
										</div>
										<div class="bdp-boxshadow-content">
											<?php $bdp_title_right_box_shadow = isset( $bdp_settings['bdp_title_right_box_shadow'] ) ? $bdp_settings['bdp_title_right_box_shadow'] : '0'; ?>
											<input type="number" id="bdp_title_right_box_shadow" name="bdp_title_right_box_shadow" step="1" min="0" value="<?php echo esc_attr( $bdp_title_right_box_shadow ); ?>"  onkeypress="return isNumberKey(event)">
										</div>
									</div>
									<div class="bdp-boxshadow-cover">
										<div class="bdp-boxshadow-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Blur', 'blog-designer-pro' ); ?>
											</span>
											<span class="bdp-px-prefix"><?php echo ' (px)'; ?></span>
										</div>
										<div class="bdp-boxshadow-content">
											<?php $bdp_title_bottom_box_shadow = isset( $bdp_settings['bdp_title_bottom_box_shadow'] ) ? $bdp_settings['bdp_title_bottom_box_shadow'] : '0'; ?>
											<input type="number" id="bdp_title_bottom_box_shadow" name="bdp_title_bottom_box_shadow" step="1" min="0" value="<?php echo esc_attr( $bdp_title_bottom_box_shadow ); ?>"  onkeypress="return isNumberKey(event)">
										</div>
									</div>
									<div class="bdp-boxshadow-cover bdp-boxshadow-color">
										<div class="bdp-boxshadow-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Color', 'blog-designer-pro' ); ?>
											</span>
										</div>
										<div class="bdp-boxshadow-content">
												<?php $bdp_title_box_shadow_color = isset( $bdp_settings['bdp_title_box_shadow_color'] ) ? $bdp_settings['bdp_title_box_shadow_color'] : ''; ?>
												<input type="text" name="bdp_title_box_shadow_color" id="bdp_title_box_shadow_color" value="<?php echo esc_attr( $bdp_title_box_shadow_color ); ?>"/>
										</div>
									</div>
								</div>
							</li>
							<h3 class="bdp-table-title"><?php esc_html_e( 'Typography Settings', 'blog-designer-pro' ); ?></h3>
							<li>
								<div class="bdp-typography-wrapper bdp-typography-wrapper1 bdp_grid_col_4">
									<div class="bdp-typography-cover">
										<div class="bdp-typography-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Font Family', 'blog-designer-pro' ); ?>
											</span>
										</div>
										<div class="bdp-typography-content">
											<input type="hidden" name="template_titlefontface_font_type" id="template_titlefontface_font_type" value="<?php echo isset( $bdp_settings['template_titlefontface_font_type'] ) ? esc_attr( $bdp_settings['template_titlefontface_font_type'] ) : 'Serif Fonts'; ?>">
											<div class="select-cover">
												<select name="template_titlefontface" id="template_titlefontface">
													<option value=""><?php esc_html_e( 'Select Font Family', 'blog-designer-pro' ); ?></option>
													<?php
													$template_titlefontface = ( isset( $bdp_settings['template_titlefontface'] ) && '' != $bdp_settings['template_titlefontface'] ) ? $bdp_settings['template_titlefontface'] : '';
													$old_version            = '';
													$cnt                    = 0;
													foreach ( $font_family as $key => $value ) {
														if ( $value['version'] != $old_version ) {
															if ( $cnt > 0 ) {
																echo '</optgroup>';
															}
															echo '<optgroup label="' . esc_attr( $value['version'] ) . '">';
															$old_version = $value['version'];
														}
														echo "<option value='" . esc_attr( str_replace( '"', '', $value['label'] ) ) . "'";

														if ( '' != $template_titlefontface && ( str_replace( '"', '', $template_titlefontface ) == str_replace( '"', '', $value['label'] ) ) ) {
															echo ' selected';
														}
														echo '>' . esc_attr( $value['label'] ) . '</option>';
														++$cnt;
													}
													if ( count( $font_family ) == $cnt ) {
														echo '</optgroup>';
													}
													?>
												</select>
											</div>
										</div>
									</div>

									<div class="bdp-typography-cover">
										<div class="bdp-typography-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Font Size', 'blog-designer-pro' ); ?>
											</span>
											<span class="bdp-px-prefix"><?php echo ' (px)'; ?></span>
										</div>
										<div class="bdp-typography-content">
											<?php $template_titlefontsize = ( isset( $bdp_settings['template_titlefontsize'] ) ) ? $bdp_settings['template_titlefontsize'] : 16; ?>
											<input type="number" name="template_titlefontsize" id="template_titlefontsize" value="<?php echo esc_attr( $template_titlefontsize ); ?>" onkeypress="return isNumberKey(event)" />
										</div>
									</div>

									<div class="bdp-typography-cover">
										<div class="bdp-typography-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Font Weight', 'blog-designer-pro' ); ?>
											</span>
										</div>
										<div class="bdp-typography-content">
											<?php $template_title_font_weight = isset( $bdp_settings['template_title_font_weight'] ) ? $bdp_settings['template_title_font_weight'] : 'normal'; ?>
											<div class="select-cover">
												<select name="template_title_font_weight" id="template_title_font_weight">
													<option value="100" <?php selected( $template_title_font_weight, 100 ); ?>>100</option>
													<option value="200" <?php selected( $template_title_font_weight, 200 ); ?>>200</option>
													<option value="300" <?php selected( $template_title_font_weight, 300 ); ?>>300</option>
													<option value="400" <?php selected( $template_title_font_weight, 400 ); ?>>400</option>
													<option value="500" <?php selected( $template_title_font_weight, 500 ); ?>>500</option>
													<option value="600" <?php selected( $template_title_font_weight, 600 ); ?>>600</option>
													<option value="700" <?php selected( $template_title_font_weight, 700 ); ?>>700</option>
													<option value="800" <?php selected( $template_title_font_weight, 800 ); ?>>800</option>
													<option value="900" <?php selected( $template_title_font_weight, 900 ); ?>>900</option>
													<option value="bold" <?php selected( $template_title_font_weight, 'bold' ); ?> ><?php esc_html_e( 'Bold', 'blog-designer-pro' ); ?></option>
													<option value="normal" <?php selected( $template_title_font_weight, 'normal' ); ?>><?php esc_html_e( 'Normal', 'blog-designer-pro' ); ?></option>
												</select>
											</div>
										</div>
									</div>

									<div class="bdp-typography-cover">
										<div class="bdp-typography-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Line Height', 'blog-designer-pro' ); ?>
											</span>
										</div>
										<div class="bdp-typography-content">
											<input type="number" name="template_title_font_line_height" id="template_title_font_line_height" step="0.1" min="0" value="<?php echo isset( $bdp_settings['template_title_font_line_height'] ) ? esc_attr( $bdp_settings['template_title_font_line_height'] ) : '1.5'; ?>" onkeypress="return isNumberKey(event)">
										</div>
									</div>

									<div class="bdp-typography-cover">
										<div class="bdp-typography-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Italic Font Style', 'blog-designer-pro' ); ?>
											</span>
										</div>
										<div class="bdp-typography-content">
											<?php $template_title_font_italic = isset( $bdp_settings['template_title_font_italic'] ) ? $bdp_settings['template_title_font_italic'] : '0'; ?>
											<fieldset class="bdp-social-options bdp-display_author buttonset">
												<input id="template_title_font_italic_1" name="template_title_font_italic" type="radio" value="1"  <?php checked( 1, $template_title_font_italic ); ?> />
												<label for="template_title_font_italic_1"><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
												<input id="template_title_font_italic_0" name="template_title_font_italic" type="radio" value="0" <?php checked( 0, $template_title_font_italic ); ?> />
												<label for="template_title_font_italic_0"><?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
											</fieldset>
										</div>
									</div>
									<div class="bdp-typography-cover">
										<div class="bdp-typography-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Text Transform', 'blog-designer-pro' ); ?>
											</span>
										</div>
										<div class="bdp-typography-content">
											<?php $template_title_font_text_transform = isset( $bdp_settings['template_title_font_text_transform'] ) ? $bdp_settings['template_title_font_text_transform'] : 'none'; ?>
											<div class="select-cover">
												<select name="template_title_font_text_transform" id="template_title_font_text_transform">
													<option <?php selected( $template_title_font_text_transform, 'none' ); ?> value="none"><?php esc_html_e( 'None', 'blog-designer-pro' ); ?></option>
													<option <?php selected( $template_title_font_text_transform, 'capitalize' ); ?> value="capitalize"><?php esc_html_e( 'Capitalize', 'blog-designer-pro' ); ?></option>
													<option <?php selected( $template_title_font_text_transform, 'uppercase' ); ?> value="uppercase"><?php esc_html_e( 'Uppercase', 'blog-designer-pro' ); ?></option>
													<option <?php selected( $template_title_font_text_transform, 'lowercase' ); ?> value="lowercase"><?php esc_html_e( 'Lowercase', 'blog-designer-pro' ); ?></option>
													<option <?php selected( $template_title_font_text_transform, 'full-width' ); ?> value="full-width"><?php esc_html_e( 'Full Width', 'blog-designer-pro' ); ?></option>
												</select>
											</div>
										</div>
									</div>

									<div class="bdp-typography-cover">
										<div class="bdp-typography-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Text Decoration', 'blog-designer-pro' ); ?>
											</span>
										</div>
										<div class="bdp-typography-content">
											<?php $template_title_font_text_decoration = isset( $bdp_settings['template_title_font_text_decoration'] ) ? $bdp_settings['template_title_font_text_decoration'] : 'none'; ?>
											<div class="select-cover">
												<select name="template_title_font_text_decoration" id="template_title_font_text_decoration">
													<option <?php selected( $template_title_font_text_decoration, 'none' ); ?> value="none"><?php esc_html_e( 'None', 'blog-designer-pro' ); ?></option>
													<option <?php selected( $template_title_font_text_decoration, 'underline' ); ?> value="underline"><?php esc_html_e( 'Underline', 'blog-designer-pro' ); ?></option>
													<option <?php selected( $template_title_font_text_decoration, 'overline' ); ?> value="overline"><?php esc_html_e( 'Overline', 'blog-designer-pro' ); ?></option>
													<option <?php selected( $template_title_font_text_decoration, 'line-through' ); ?> value="line-through"><?php esc_html_e( 'Line Through', 'blog-designer-pro' ); ?></option>
												</select>
											</div>
										</div>
									</div>

									<div class="bdp-typography-cover">
										<div class="bdp-typography-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Letter Spacing', 'blog-designer-pro' ); ?>
											</span>
											<span class="bdp-px-prefix"><?php echo ' (px)'; ?></span>
										</div>
										<div class="bdp-typography-content">
											<input type="number" name="template_title_font_letter_spacing" id="template_title_font_letter_spacing" step="1" min="0" value="<?php echo isset( $bdp_settings['template_title_font_letter_spacing'] ) ? esc_attr( $bdp_settings['template_title_font_letter_spacing'] ) : '0'; ?>" onkeypress="return isNumberKey(event)">
										</div>
									</div>
								</div>
							</li>
							<h3 class="download_single_post_title_wrap bdp-table-title post_title_border_setting"><?php esc_html_e( 'Download Single Post Title Margin', 'blog-designer-pro' ); ?></h3>
							<li class="download_single_post_title_wrap edd_addtocart_button_box_shadow_setting post_title_border_setting">
								<div class="bdp-boxshadow-wrapper bdp-boxshadow-wrapper1">
									<div class="bdp-boxshadow-cover">
										<div class="bdp-boxshadow-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Left', 'blog-designer-pro' ); ?>
											</span>
											<span class="bdp-px-prefix"><?php echo ' (px)'; ?></span>
										</div>
										<div class="bdp-boxshadow-content">
											<?php $download_single_post_title_marginleft = isset( $bdp_settings['download_single_post_title_marginleft'] ) ? $bdp_settings['download_single_post_title_marginleft'] : 0; ?>
											<input type="number" id="download_single_post_title_marginleft" name="download_single_post_title_marginleft" step="1" value="<?php echo esc_attr( $download_single_post_title_marginleft ); ?>" onkeypress="return isNumberKey(event)">
										</div>
									</div>
									<div class="bdp-boxshadow-cover">
										<div class="bdp-boxshadow-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Right', 'blog-designer-pro' ); ?>
											</span>
											<span class="bdp-px-prefix"><?php echo ' (px)'; ?></span>
										</div>
										<div class="bdp-boxshadow-content">
											<?php $download_single_post_title_marginright = isset( $bdp_settings['download_single_post_title_marginright'] ) ? $bdp_settings['download_single_post_title_marginright'] : 0; ?>
											<input type="number" id="download_single_post_title_marginright" name="download_single_post_title_marginright" step="1" value="<?php echo esc_attr( $download_single_post_title_marginright ); ?>" onkeypress="return isNumberKey(event)">
										</div>
									</div>
									<div class="bdp-boxshadow-cover">
										<div class="bdp-boxshadow-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Top', 'blog-designer-pro' ); ?>
											</span>
											<span class="bdp-px-prefix"><?php echo ' (px)'; ?></span>
										</div>
										<div class="bdp-boxshadow-content">
											<?php $download_single_post_title_margintop = isset( $bdp_settings['download_single_post_title_margintop'] ) ? $bdp_settings['download_single_post_title_margintop'] : 0; ?>
											<input type="number" id="download_single_post_title_margintop" name="download_single_post_title_margintop" step="1" value="<?php echo esc_attr( $download_single_post_title_margintop ); ?>"  onkeypress="return isNumberKey(event)">
										</div>
									</div>
									<div class="bdp-boxshadow-cover">
										<div class="bdp-boxshadow-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Bottom', 'blog-designer-pro' ); ?>
											</span>
											<span class="bdp-px-prefix"><?php echo ' (px)'; ?></span>
										</div>
										<div class="bdp-boxshadow-content">
											<?php $download_single_post_title_marginbottom = isset( $bdp_settings['download_single_post_title_marginbottom'] ) ? $bdp_settings['download_single_post_title_marginbottom'] : 0; ?>
											<input type="number" id="download_single_post_title_marginbottom" name="download_single_post_title_marginbottom" step="1" value="<?php echo esc_attr( $download_single_post_title_marginbottom ); ?>" onkeypress="return isNumberKey(event)">
										</div>
									</div>
								</div>
							</li>
							<h3 class="download_post_title_padding_wrap bdp-table-title post_content_border_setting"><?php esc_html_e( 'Download Single Post Title Padding', 'blog-designer-pro' ); ?></h3>
							<li class="download_single_post_title_padding_wrap edd_addtocart_button_box_shadow_setting post_content_border_setting">
								<div class="bdp-boxshadow-wrapper bdp-boxshadow-wrapper1">
									<div class="bdp-boxshadow-cover">
										<div class="bdp-boxshadow-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Left', 'blog-designer-pro' ); ?>
											</span>
											<span class="bdp-px-prefix"><?php echo ' (px)'; ?></span>
										</div>
										<div class="bdp-boxshadow-content">
											<?php $download_single_post_title_paddingleft = isset( $bdp_settings['download_single_post_title_paddingleft'] ) ? $bdp_settings['download_single_post_title_paddingleft'] : 0; ?>
											<input type="number" id="download_single_post_title_paddingleft" name="download_single_post_title_paddingleft" step="1" value="<?php echo esc_attr( $download_single_post_title_paddingleft ); ?>" onkeypress="return isNumberKey(event)">
										</div>
									</div>
									<div class="bdp-boxshadow-cover">
										<div class="bdp-boxshadow-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Right', 'blog-designer-pro' ); ?>
											</span>
											<span class="bdp-px-prefix"><?php echo ' (px)'; ?></span>
										</div>
										<div class="bdp-boxshadow-content">
											<?php $download_single_post_title_paddingright = isset( $bdp_settings['download_single_post_title_paddingright'] ) ? $bdp_settings['download_single_post_title_paddingright'] : 0; ?>
											<input type="number" id="download_single_post_title_paddingright" name="download_single_post_title_paddingright" step="1" value="<?php echo esc_attr( $download_single_post_title_paddingright ); ?>" onkeypress="return isNumberKey(event)">
										</div>
									</div>
									<div class="bdp-boxshadow-cover">
										<div class="bdp-boxshadow-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Top', 'blog-designer-pro' ); ?>
											</span>
											<span class="bdp-px-prefix"><?php echo ' (px)'; ?></span>
										</div>
										<div class="bdp-boxshadow-content">
											<?php $download_single_post_title_paddingtop = isset( $bdp_settings['download_single_post_title_paddingtop'] ) ? $bdp_settings['download_single_post_title_paddingtop'] : 0; ?>
											<input type="number" id="download_single_post_title_paddingtop" name="download_single_post_title_paddingtop" step="1" value="<?php echo esc_attr( $download_single_post_title_paddingtop ); ?>"  onkeypress="return isNumberKey(event)">
										</div>
									</div>
									<div class="bdp-boxshadow-cover">
										<div class="bdp-boxshadow-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Bottom', 'blog-designer-pro' ); ?>
											</span>
											<span class="bdp-px-prefix"><?php echo ' (px)'; ?></span>
										</div>
										<div class="bdp-boxshadow-content">
											<?php $download_single_post_title_paddingbottom = isset( $bdp_settings['download_single_post_title_paddingbottom'] ) ? $bdp_settings['download_single_post_title_paddingbottom'] : 0; ?>
											<input type="number" id="download_single_post_title_paddingbottom" name="download_single_post_title_paddingbottom" step="1" value="<?php echo esc_attr( $download_single_post_title_paddingbottom ); ?>" onkeypress="return isNumberKey(event)">
										</div>
									</div>
								</div>
							</li>
						</ul>
					</div>
				</div>
				<div id="bdpsingleconent" class="postbox postbox-with-fw-options bdp-content-setting1" style=<?php echo esc_attr( $bdpcontent_class_show ); ?>>
					<div class="inside">
						<ul class="bdp-settings bdp-lineheight">
							<div class="bdp_grid_col_3">
								<li class="content-firstletter-tr">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'First letter of product content as Dropcap', 'blog-designer-pro' ); ?>
										</span>

									</div>
									<div class="bdp-right">
										<?php $firstletter_big = ( isset( $bdp_settings['firstletter_big'] ) ) ? $bdp_settings['firstletter_big'] : 0; ?>
										<fieldset class="buttonset firstletter_big">
											<input id="firstletter_big_1" name="firstletter_big" type="radio" value="1" <?php checked( 1, $firstletter_big ); ?> />
											<label for="firstletter_big_1" <?php checked( 1, $firstletter_big ); ?>><?php esc_html_e( 'Enable', 'blog-designer-pro' ); ?></label>
											<input id="firstletter_big_0" name="firstletter_big" type="radio" value="0" <?php checked( 0, $firstletter_big ); ?> />
											<label for="firstletter_big_0" <?php checked( 0, $firstletter_big ); ?>><?php esc_html_e( 'Disable', 'blog-designer-pro' ); ?></label>
										</fieldset>
									</div>
								</li>
								<li class="firstletter-setting">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'First letter of Product Content Font Size', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<?php $firstletter_fontsize = ( isset( $bdp_settings['firstletter_fontsize'] ) && '' != $bdp_settings['firstletter_fontsize'] ) ? $bdp_settings['firstletter_fontsize'] : '35'; ?>
										<input type="number" name="firstletter_fontsize" id="firstletter_fontsize" value="<?php echo esc_attr( $firstletter_fontsize ); ?>" onkeypress="return isNumberKey(event)" />
									</div>
								</li>
								<li class="firstletter-setting">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'First letter of Product Content Font Family', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<?php $firstletter_font_family = ( isset( $bdp_settings['firstletter_font_family'] ) && '' != $bdp_settings['firstletter_font_family'] ) ? $bdp_settings['firstletter_font_family'] : ''; ?>
										<div class="typo-field">
											<input type="hidden" id="firstletter_font_family_font_type" name="firstletter_font_family_font_type" value="<?php echo isset( $bdp_settings['firstletter_font_family_font_type'] ) ? esc_attr( $bdp_settings['firstletter_font_family_font_type'] ) : ''; ?>">
											<select name="firstletter_font_family" id="firstletter_font_family">
												<option value=""><?php esc_html_e( 'Select Font Family', 'blog-designer-pro' ); ?></option>
												<?php
												$old_version = '';
												$cnt         = 0;
												foreach ( $font_family as $key => $value ) {
													if ( $value['version'] != $old_version ) {
														if ( $cnt > 0 ) {
															echo '</optgroup>';
														}
														echo '<optgroup label="' . esc_attr( $value['version'] ) . '">';
														$old_version = $value['version'];
													}
													echo "<option value='" . esc_attr( str_replace( '"', '', $value['label'] ) ) . "'";

													if ( '' != $firstletter_font_family && ( str_replace( '"', '', $firstletter_font_family ) == str_replace( '"', '', $value['label'] ) ) ) {
														echo ' selected';
													}
													echo '>' . esc_attr( $value['label'] ) . '</option>';
													++$cnt;
												}
												if ( count( $font_family ) == $cnt ) {
													echo '</optgroup>';
												}
												?>
											</select>
										</div>
									</div>
								</li>
							</div>
							<div class="bdp_grid_col_2">
								<li class="firstletter-setting">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'First letter of Product Content Color', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<?php $firstletter_contentcolor = ( isset( $bdp_settings['firstletter_contentcolor'] ) ) ? $bdp_settings['firstletter_contentcolor'] : ''; ?>
										<input type="text" name="firstletter_contentcolor" id="firstletter_contentcolor" value="<?php echo esc_attr( $firstletter_contentcolor ); ?>"/>
									</div>
								</li>

								<li>
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Product Content Color', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<input type="text" name="template_contentcolor" id="template_contentcolor" value="<?php echo isset( $bdp_settings['template_contentcolor'] ) ? esc_attr( $bdp_settings['template_contentcolor'] ) : ''; ?>"/>
									</div>
								</li>
							</div>
							<h3 class="bdp-table-title"><?php esc_html_e( 'Box Shadow Settings', 'blog-designer-pro' ); ?></h3>
							<li class="edd_addtocart_button_box_shadow_setting">
								<div class="bdp-boxshadow-wrapper bdp-boxshadow-wrapper1">
									<div class="bdp-boxshadow-cover">
										<div class="bdp-boxshadow-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'H-offset', 'blog-designer-pro' ); ?>
											</span>
											<span class="bdp-px-prefix"><?php echo ' (px)'; ?></span>
										</div>
										<div class="bdp-boxshadow-content">
											<?php $bdp_content_top_box_shadow = isset( $bdp_settings['bdp_content_top_box_shadow'] ) ? $bdp_settings['bdp_content_top_box_shadow'] : '0'; ?>
											<input type="number" id="bdp_content_top_box_shadow" name="bdp_content_top_box_shadow" step="1" min="0" value="<?php echo esc_attr( $bdp_content_top_box_shadow ); ?>"  onkeypress="return isNumberKey(event)">
										</div>
									</div>
									<div class="bdp-boxshadow-cover">
										<div class="bdp-boxshadow-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'V-offset', 'blog-designer-pro' ); ?>
											</span>
											<span class="bdp-px-prefix"><?php echo ' (px)'; ?></span>
										</div>
										<div class="bdp-boxshadow-content">
											<?php $bdp_content_right_box_shadow = isset( $bdp_settings['bdp_content_right_box_shadow'] ) ? $bdp_settings['bdp_content_right_box_shadow'] : '0'; ?>
											<input type="number" id="bdp_content_right_box_shadow" name="bdp_content_right_box_shadow" step="1" min="0" value="<?php echo esc_attr( $bdp_content_right_box_shadow ); ?>"  onkeypress="return isNumberKey(event)">
										</div>
									</div>
									<div class="bdp-boxshadow-cover">
										<div class="bdp-boxshadow-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Blur', 'blog-designer-pro' ); ?>
											</span>
											<span class="bdp-px-prefix"><?php echo ' (px)'; ?></span>
										</div>
										<div class="bdp-boxshadow-content">
											<?php $bdp_content_bottom_box_shadow = isset( $bdp_settings['bdp_content_bottom_box_shadow'] ) ? $bdp_settings['bdp_content_bottom_box_shadow'] : '0'; ?>
											<input type="number" id="bdp_content_bottom_box_shadow" name="bdp_content_bottom_box_shadow" step="1" min="0" value="<?php echo esc_attr( $bdp_content_bottom_box_shadow ); ?>"  onkeypress="return isNumberKey(event)">
										</div>
									</div>
									<div class="bdp-boxshadow-cover bdp-boxshadow-color">
										<div class="bdp-boxshadow-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Color', 'blog-designer-pro' ); ?>
											</span>
										</div>
										<div class="bdp-boxshadow-content">
												<?php $bdp_content_box_shadow_color = isset( $bdp_settings['bdp_content_box_shadow_color'] ) ? $bdp_settings['bdp_content_box_shadow_color'] : ''; ?>
												<input type="text" name="bdp_content_box_shadow_color" id="bdp_content_box_shadow_color" value="<?php echo esc_attr( $bdp_content_box_shadow_color ); ?>"/>
										</div>
									</div>
								</div>
							</li>
							<h3 class="bdp-table-title"><?php esc_html_e( 'Typography Settings', 'blog-designer-pro' ); ?></h3>
							<li>
								<div class="bdp-typography-wrapper bdp-typography-wrapper1 bdp_grid_col_4">
									<div class="bdp-typography-cover">
										<div class="bdp-typography-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Font Family', 'blog-designer-pro' ); ?>
											</span>
										</div>
										<div class="bdp-typography-content">
											<?php
											$template_contentfontface = '';
											if ( isset( $bdp_settings['template_contentfontface'] ) ) {
												$template_contentfontface = $bdp_settings['template_contentfontface'];
											}
											?>
											<div class="typo-field">
												<input type="hidden" name="template_contentfontface_font_type" id="template_contentfontface_font_type" value="<?php echo isset( $bdp_settings['template_contentfontface_font_type'] ) ? esc_attr( $bdp_settings['template_contentfontface_font_type'] ) : 'Serif Fonts'; ?>">
												<div class="select-cover">
													<select name="template_contentfontface" id="template_contentfontface">
														<option value=""><?php esc_html_e( 'Select Font Family', 'blog-designer-pro' ); ?></option>
														<?php
														$old_version = '';
														$cnt         = 0;
														foreach ( $font_family as $key => $value ) {
															if ( $value['version'] != $old_version ) {
																if ( $cnt > 0 ) {
																	echo '</optgroup>';
																}
																echo '<optgroup label="' . esc_attr( $value['version'] ) . '">';
																$old_version = $value['version'];
															}
															echo "<option value='" . esc_attr( str_replace( '"', '', $value['label'] ) ) . "'";

															if ( '' != $template_contentfontface && ( str_replace( '"', '', $template_contentfontface ) == str_replace( '"', '', $value['label'] ) ) ) {
																echo ' selected';
															}
															echo '>' . esc_attr( $value['label'] ) . '</option>';
															++$cnt;
														}
														if ( count( $font_family ) == $cnt ) {
															echo '</optgroup>';
														}
														?>
													</select>
												</div>
											</div>
										</div>
									</div>

									<div class="bdp-typography-cover">
										<div class="bdp-typography-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Font Size', 'blog-designer-pro' ); ?>
											</span>
											<span class="bdp-px-prefix"><?php echo ' (px)'; ?></span>
										</div>
										<div class="bdp-typography-content">
											<?php $content_fontsize = ( isset( $bdp_settings['content_fontsize'] ) ) ? $bdp_settings['content_fontsize'] : 15; ?>
											<input type="number" name="content_fontsize" id="content_fontsize" value="<?php echo esc_attr( $content_fontsize ); ?>" onkeypress="return isNumberKey(event)" />
										</div>
									</div>

									<div class="bdp-typography-cover">
										<div class="bdp-typography-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Font Weight', 'blog-designer-pro' ); ?>
											</span>
										</div>
										<div class="bdp-typography-content">
											<?php $template_content_font_weight = isset( $bdp_settings['template_content_font_weight'] ) ? $bdp_settings['template_content_font_weight'] : 'normal'; ?>
											<div class="select-cover">
												<select name="template_content_font_weight" id="template_content_font_weight">
													<option value="100" <?php selected( $template_content_font_weight, 100 ); ?>>100</option>
													<option value="200" <?php selected( $template_content_font_weight, 200 ); ?>>200</option>
													<option value="300" <?php selected( $template_content_font_weight, 300 ); ?>>300</option>
													<option value="400" <?php selected( $template_content_font_weight, 400 ); ?>>400</option>
													<option value="500" <?php selected( $template_content_font_weight, 500 ); ?>>500</option>
													<option value="600" <?php selected( $template_content_font_weight, 600 ); ?>>600</option>
													<option value="700" <?php selected( $template_content_font_weight, 700 ); ?>>700</option>
													<option value="800" <?php selected( $template_content_font_weight, 800 ); ?>>800</option>
													<option value="900" <?php selected( $template_content_font_weight, 900 ); ?>>900</option>
													<option value="bold" <?php selected( $template_content_font_weight, 'bold' ); ?> ><?php esc_html_e( 'Bold', 'blog-designer-pro' ); ?></option>
													<option value="normal" <?php selected( $template_content_font_weight, 'normal' ); ?>><?php esc_html_e( 'Normal', 'blog-designer-pro' ); ?></option>
												</select>
											</div>
										</div>
									</div>

									<div class="bdp-typography-cover">
										<div class="bdp-typography-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Line Height', 'blog-designer-pro' ); ?>
											</span>
										</div>
										<div class="bdp-typography-content">
											<input type="number" name="template_content_font_line_height" id="template_content_font_line_height" step="0.1" min="0" value="<?php echo isset( $bdp_settings['template_content_font_line_height'] ) ? esc_attr( $bdp_settings['template_content_font_line_height'] ) : '1.5'; ?>" onkeypress="return isNumberKey(event)">
										</div>
									</div>

									<div class="bdp-typography-cover">
										<div class="bdp-typography-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Italic Font Style', 'blog-designer-pro' ); ?>
											</span>
										</div>
										<div class="bdp-typography-content">
											<?php $template_content_font_italic = isset( $bdp_settings['template_content_font_italic'] ) ? $bdp_settings['template_content_font_italic'] : '0'; ?>
											<fieldset class="bdp-social-options bdp-display_author buttonset">
												<input id="template_content_font_italic_1" name="template_content_font_italic" type="radio" value="1"  <?php checked( 1, $template_content_font_italic ); ?> />
												<label for="template_content_font_italic_1"><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
												<input id="template_content_font_italic_0" name="template_content_font_italic" type="radio" value="0" <?php checked( 0, $template_content_font_italic ); ?> />
												<label for="template_content_font_italic_0"><?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
											</fieldset>
										</div>
									</div>

									<div class="bdp-typography-cover">
										<div class="bdp-typography-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Text Transform', 'blog-designer-pro' ); ?>
											</span>
										</div>
										<div class="bdp-typography-content">
											<?php $template_content_font_text_transform = isset( $bdp_settings['template_content_font_text_transform'] ) ? $bdp_settings['template_content_font_text_transform'] : 'none'; ?>
											<div class="select-cover">
												<select name="template_content_font_text_transform" id="template_content_font_text_transform">
													<option <?php selected( $template_content_font_text_transform, 'none' ); ?> value="none"><?php esc_html_e( 'None', 'blog-designer-pro' ); ?></option>
													<option <?php selected( $template_content_font_text_transform, 'capitalize' ); ?> value="capitalize"><?php esc_html_e( 'Capitalize', 'blog-designer-pro' ); ?></option>
													<option <?php selected( $template_content_font_text_transform, 'uppercase' ); ?> value="uppercase"><?php esc_html_e( 'Uppercase', 'blog-designer-pro' ); ?></option>
													<option <?php selected( $template_content_font_text_transform, 'lowercase' ); ?> value="lowercase"><?php esc_html_e( 'Lowercase', 'blog-designer-pro' ); ?></option>
													<option <?php selected( $template_content_font_text_transform, 'full-width' ); ?> value="full-width"><?php esc_html_e( 'Full Width', 'blog-designer-pro' ); ?></option>
												</select>
											</div>
										</div>
									</div>

									<div class="bdp-typography-cover">
										<div class="bdp-typography-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Text Decoration', 'blog-designer-pro' ); ?>
											</span>
										</div>
										<div class="bdp-typography-content">
											<?php $template_content_font_text_decoration = isset( $bdp_settings['template_content_font_text_decoration'] ) ? $bdp_settings['template_content_font_text_decoration'] : 'none'; ?>
											<div class="select-cover">
												<select name="template_content_font_text_decoration" id="template_content_font_text_decoration">
													<option <?php selected( $template_content_font_text_decoration, 'none' ); ?> value="none"><?php esc_html_e( 'None', 'blog-designer-pro' ); ?></option>
													<option <?php selected( $template_content_font_text_decoration, 'underline' ); ?> value="underline"><?php esc_html_e( 'Underline', 'blog-designer-pro' ); ?></option>
													<option <?php selected( $template_content_font_text_decoration, 'overline' ); ?> value="overline"><?php esc_html_e( 'Overline', 'blog-designer-pro' ); ?></option>
													<option <?php selected( $template_content_font_text_decoration, 'line-through' ); ?> value="line-through"><?php esc_html_e( 'Line Through', 'blog-designer-pro' ); ?></option>
												</select>
											</div>
										</div>
									</div>

									<div class="bdp-typography-cover">
										<div class="bdp-typography-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Letter Spacing', 'blog-designer-pro' ); ?>
											</span>
											<span class="bdp-px-prefix"><?php echo ' (px)'; ?></span>
										</div>
										<div class="bdp-typography-content">
											<input type="number" name="template_content_font_letter_spacing" id="template_content_font_letter_spacing" step="1" min="0" value="<?php echo isset( $bdp_settings['template_content_font_letter_spacing'] ) ? esc_attr( $bdp_settings['template_content_font_letter_spacing'] ) : '0'; ?>" onkeypress="return isNumberKey(event)">
										</div>
									</div>
								</div>
							</li>
							<h3 class="download_single_post_content_wrap bdp-table-title post_content_border_setting"><?php esc_html_e( 'Download Single Post Content Margin', 'blog-designer-pro' ); ?></h3>
							<li class="download_single_post_content_wrap edd_addtocart_button_box_shadow_setting post_content_border_setting">
								<div class="bdp-boxshadow-wrapper bdp-boxshadow-wrapper1">
									<div class="bdp-boxshadow-cover">
										<div class="bdp-boxshadow-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Left', 'blog-designer-pro' ); ?>
											</span>
											<span class="bdp-px-prefix"><?php echo ' (px)'; ?></span>
										</div>
										<div class="bdp-boxshadow-content">
											<?php $download_single_post_content_marginleft = isset( $bdp_settings['download_single_post_content_marginleft'] ) ? $bdp_settings['download_single_post_content_marginleft'] : 0; ?>
											<input type="number" id="download_single_post_content_marginleft" name="download_single_post_content_marginleft" step="1" value="<?php echo esc_attr( $download_single_post_content_marginleft ); ?>" onkeypress="return isNumberKey(event)">
										</div>
									</div>
									<div class="bdp-boxshadow-cover">
										<div class="bdp-boxshadow-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Right', 'blog-designer-pro' ); ?>
											</span>
											<span class="bdp-px-prefix"><?php echo ' (px)'; ?></span>
										</div>
										<div class="bdp-boxshadow-content">
											<?php $download_single_post_content_marginright = isset( $bdp_settings['download_single_post_content_marginright'] ) ? $bdp_settings['download_single_post_content_marginright'] : 0; ?>
											<input type="number" id="download_single_post_content_marginright" name="download_single_post_content_marginright" step="1" value="<?php echo esc_attr( $download_single_post_content_marginright ); ?>" onkeypress="return isNumberKey(event)">
										</div>
									</div>
									<div class="bdp-boxshadow-cover">
										<div class="bdp-boxshadow-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Top', 'blog-designer-pro' ); ?>
											</span>
											<span class="bdp-px-prefix"><?php echo ' (px)'; ?></span>
										</div>
										<div class="bdp-boxshadow-content">
											<?php $download_single_post_content_margintop = isset( $bdp_settings['download_single_post_content_margintop'] ) ? $bdp_settings['download_single_post_content_margintop'] : 0; ?>
											<input type="number" id="download_single_post_content_margintop" name="download_single_post_content_margintop" step="1" value="<?php echo esc_attr( $download_single_post_content_margintop ); ?>"  onkeypress="return isNumberKey(event)">
										</div>
									</div>
									<div class="bdp-boxshadow-cover">
										<div class="bdp-boxshadow-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Bottom', 'blog-designer-pro' ); ?>
											</span>
											<span class="bdp-px-prefix"><?php echo ' (px)'; ?></span>
										</div>
										<div class="bdp-boxshadow-content">
											<?php $download_single_post_content_marginbottom = isset( $bdp_settings['download_single_post_content_marginbottom'] ) ? $bdp_settings['download_single_post_content_marginbottom'] : 0; ?>
											<input type="number" id="download_single_post_content_marginbottom" name="download_single_post_content_marginbottom" step="1" value="<?php echo esc_attr( $download_single_post_content_marginbottom ); ?>" onkeypress="return isNumberKey(event)">
										</div>
									</div>
								</div>
							</li>
							<h3 class="download_single_post_content_padding_wrap bdp-table-title post_content_border_setting"><?php esc_html_e( 'Download Single Post Content Padding', 'blog-designer-pro' ); ?></h3>
							<li class="download_single_post_content_padding_wrap edd_addtocart_button_box_shadow_setting post_content_border_setting">
								<div class="bdp-boxshadow-wrapper bdp-boxshadow-wrapper1">
									<div class="bdp-boxshadow-cover">
										<div class="bdp-boxshadow-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Left', 'blog-designer-pro' ); ?>
											</span>
											<span class="bdp-px-prefix"><?php echo ' (px)'; ?></span>
										</div>
										<div class="bdp-boxshadow-content">
											<?php $download_single_post_content_paddingleft = isset( $bdp_settings['download_single_post_content_paddingleft'] ) ? $bdp_settings['download_single_post_content_paddingleft'] : 0; ?>
											<input type="number" id="download_single_post_content_paddingleft" name="download_single_post_content_paddingleft" step="1" value="<?php echo esc_attr( $download_single_post_content_paddingleft ); ?>" onkeypress="return isNumberKey(event)">
										</div>
									</div>
									<div class="bdp-boxshadow-cover">
										<div class="bdp-boxshadow-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Right', 'blog-designer-pro' ); ?>
											</span>
											<span class="bdp-px-prefix"><?php echo ' (px)'; ?></span>
										</div>
										<div class="bdp-boxshadow-content">
											<?php $download_single_post_content_paddingright = isset( $bdp_settings['download_single_post_content_paddingright'] ) ? $bdp_settings['download_single_post_content_paddingright'] : 0; ?>
											<input type="number" id="download_single_post_content_paddingright" name="download_single_post_content_paddingright" step="1" value="<?php echo esc_attr( $download_single_post_content_paddingright ); ?>" onkeypress="return isNumberKey(event)">
										</div>
									</div>
									<div class="bdp-boxshadow-cover">
										<div class="bdp-boxshadow-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Top', 'blog-designer-pro' ); ?>
											</span>
											<span class="bdp-px-prefix"><?php echo ' (px)'; ?></span>
										</div>
										<div class="bdp-boxshadow-content">
											<?php $download_single_post_content_paddingtop = isset( $bdp_settings['download_single_post_content_paddingtop'] ) ? $bdp_settings['download_single_post_content_paddingtop'] : 0; ?>
											<input type="number" id="download_single_post_content_paddingtop" name="download_single_post_content_paddingtop" step="1" value="<?php echo esc_attr( $download_single_post_content_paddingtop ); ?>"  onkeypress="return isNumberKey(event)">
										</div>
									</div>
									<div class="bdp-boxshadow-cover">
										<div class="bdp-boxshadow-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Bottom', 'blog-designer-pro' ); ?>
											</span>
											<span class="bdp-px-prefix"><?php echo ' (px)'; ?></span>
										</div>
										<div class="bdp-boxshadow-content">
											<?php $download_single_post_content_paddingbottom = isset( $bdp_settings['download_single_post_content_paddingbottom'] ) ? $bdp_settings['download_single_post_content_paddingbottom'] : 0; ?>
											<input type="number" id="download_single_post_content_paddingbottom" name="download_single_post_content_paddingbottom" step="1" value="<?php echo esc_attr( $download_single_post_content_paddingbottom ); ?>" onkeypress="return isNumberKey(event)">
										</div>
									</div>
								</div>
							</li>
						</ul>
					</div>
				</div>
				<div id="bdpsinglemedia" class="postbox postbox-with-fw-options bdp-content-setting1" style=<?php echo esc_attr( $bdpmedia_class_show ); ?>>
					<div class="inside">
						<ul class="bdp-settings bdp-lineheight">
							<div class="bdp_grid_col_3">
								<li class="bdp_single_custom_media_selection">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Display Product Featured Image', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<?php $display_thumbnail = isset( $bdp_settings['display_thumbnail'] ) ? $bdp_settings['display_thumbnail'] : 1; ?>
										<fieldset class="bdp-social-options bdp-display_comment buttonset buttonset-hide ui-buttonset" data-hide="1">
											<input id="display_thumbnail_1" name="display_thumbnail" type="radio" value="1" <?php checked( 1, $display_thumbnail ); ?> />
											<label for="display_thumbnail_1" <?php checked( 1, $display_thumbnail ); ?>><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
											<input id="display_thumbnail_0" name="display_thumbnail" type="radio" value="0" <?php checked( 0, $display_thumbnail ); ?> />
											<label for="display_thumbnail_0" <?php checked( 0, $display_thumbnail ); ?>><?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
										</fieldset>
									</div>
								</li>
								<li class="bdp_single_custom_media_selection bdp_media_size_tr">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Select Product Media Size', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<select id="bdp_media_size" name="bdp_media_size">
											<option value="full" <?php echo ( isset( $bdp_settings['bdp_media_size'] ) && 'full' === $bdp_settings['bdp_media_size'] ) ? 'selected="selected"' : ''; ?> ><?php esc_html_e( 'Original Resolution', 'blog-designer-pro' ); ?></option>
											<?php
											global $_wp_additional_image_sizes;
											$thumb_sizes = array();
											$image_size  = get_intermediate_image_sizes();
											foreach ( $image_size as $s ) {
												$thumb_sizes [ $s ] = array( 0, 0 );
												if ( in_array( $s, array( 'thumbnail', 'medium', 'large' ) ) ) {
													?>
													<option value="<?php echo esc_attr( $s ); ?>" <?php echo ( isset( $bdp_settings['bdp_media_size'] ) && $bdp_settings['bdp_media_size'] == $s ) ? 'selected="selected"' : ''; ?>> <?php echo esc_html( $s ) . ' (' . esc_html( get_option( $s . '_size_w' ) ) . 'x' . esc_html( get_option( $s . '_size_h' ) ) . ')'; ?> </option>
													<?php
												} elseif ( isset( $_wp_additional_image_sizes ) && isset( $_wp_additional_image_sizes[ $s ] ) ) {
													?>
														<option value="<?php echo esc_attr( $s ); ?>" <?php echo ( isset( $bdp_settings['bdp_media_size'] ) && $bdp_settings['bdp_media_size'] == $s ) ? 'selected="selected"' : ''; ?>> <?php echo esc_html( $s ) . ' (' . esc_html( $_wp_additional_image_sizes[ $s ]['width'] ) . 'x' . esc_html( $_wp_additional_image_sizes[ $s ]['height'] ) . ')'; ?> </option>
														<?php

												}
											}
											?>
											<option value="custom" <?php echo ( isset( $bdp_settings['bdp_media_size'] ) && 'custom' === $bdp_settings['bdp_media_size'] ) ? 'selected="selected"' : ''; ?>><?php esc_html_e( 'Custom Size', 'blog-designer-pro' ); ?></option>
										</select>
									</div>
								</li>
								<li class="bdp_media_custom_size_tr">
								<div class="bdp-left">
									<span class="bdp-key-title">
										<?php esc_html_e( 'Add Custom Size', 'blog-designer-pro' ); ?>
									</span>
								</div>
								<div class="bdp-right">
									<div class="bdp_media_custom_size_tbl">
										<p> <span class="bdp_custom_media_size_title"><?php esc_html_e( 'Width', 'blog-designer-pro' ); ?> </span><span class="bdp-px-prefix"><?php echo ' (px)'; ?></span> <input type="number" min="1" name="media_custom_width" class="media_custom_width" id="media_custom_width" value="<?php echo ( isset( $bdp_settings['media_custom_width'] ) && '' != $bdp_settings['media_custom_width'] ) ? esc_attr( $bdp_settings['media_custom_width'] ) : ''; ?>" /> </p>
										<p> <span class="bdp_custom_media_size_title"><?php esc_html_e( 'Height', 'blog-designer-pro' ); ?> </span><span class="bdp-px-prefix"><?php echo ' (px)'; ?></span> <input type="number" min="1" name="media_custom_height" class="media_custom_height" id="media_custom_height" value="<?php echo ( isset( $bdp_settings['media_custom_height'] ) && '' != $bdp_settings['media_custom_height'] ) ? esc_attr( $bdp_settings['media_custom_height'] ) : ''; ?>"/></p>
									</div>
								</div>
								</li>

								<li class="enable_lazy_load">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Enable Lazy Load?', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<?php
										$enable_lazy_load = 0;
										$enable_lazy_load = isset( $bdp_settings['enable_lazy_load'] ) ? $bdp_settings['enable_lazy_load'] : '1';
										?>
										<fieldset class="buttonset buttonset-hide" data-hide='1'>
											<input id="enable_lazy_load_1" name="enable_lazy_load" type="radio" value="1" <?php checked( 1, $enable_lazy_load ); ?> />
											<label id="bdp-options-button" for="enable_lazy_load_1" <?php checked( 1, $enable_lazy_load ); ?>><?php esc_html_e( 'Enable', 'blog-designer-pro' ); ?></label>
											<input id="enable_lazy_load_0" name="enable_lazy_load" type="radio" value="0" <?php checked( 0, $enable_lazy_load ); ?> />
											<label id="bdp-options-button" for="enable_lazy_load_0" <?php checked( 0, $enable_lazy_load ); ?>><?php esc_html_e( 'Disable', 'blog-designer-pro' ); ?></label>
										</fieldset>
									</div>
								</li>
								<li class="enable_lazy_load_setting">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Enable Lazy Load Blurred Image?', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<?php
										$enable_lazy_load_blur_image = 0;
										$enable_lazy_load_blur_image = isset( $bdp_settings['enable_lazy_load_blur_image'] ) ? $bdp_settings['enable_lazy_load_blur_image'] : '1';
										?>
										<fieldset class="buttonset buttonset-hide" data-hide='1'>
											<input id="enable_lazy_load_blur_image_1" name="enable_lazy_load_blur_image" type="radio" value="1" <?php checked( 1, $enable_lazy_load_blur_image ); ?> />
											<label id="bdp-options-button" for="enable_lazy_load_blur_image_1" <?php checked( 1, $enable_lazy_load_blur_image ); ?>><?php esc_html_e( 'Enable', 'blog-designer-pro' ); ?></label>
											<input id="enable_lazy_load_blur_image_0" name="enable_lazy_load_blur_image" type="radio" value="0" <?php checked( 0, $enable_lazy_load_blur_image ); ?> />
											<label id="bdp-options-button" for="enable_lazy_load_blur_image_0" <?php checked( 0, $enable_lazy_load_blur_image ); ?>><?php esc_html_e( 'Disable', 'blog-designer-pro' ); ?></label>
										</fieldset>
									</div>
								</li>
								<li class="enable_lazy_load_setting">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Lazy Load Color', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<input type="text" name="template_lazy_load_color" id="template_lazy_load_color" value="<?php echo isset( $bdp_settings['template_lazy_load_color'] ) ? esc_attr( $bdp_settings['template_lazy_load_color'] ) : '#000000'; ?>"/>
									</div>
								</li>
							</div>
						</ul>
					</div>
				</div>
				<div id="bdpsingleeddsetting" class="postbox postbox-with-fw-options" style=<?php echo esc_attr( $bdpsingleeddsetting_class_show ); ?>>
					<div class="inside">
						<ul class="bdp-settings bdp-lineheight">
							<li>
								<div class="bdp-left">
									<span class="bdp-key-title">
										<?php esc_html_e( 'Display Price', 'blog-designer-pro' ); ?>
									</span>
								</div>
								<div class="bdp-right">
									<?php
									$display_download_price = '1';
									if ( isset( $bdp_settings['display_download_price'] ) ) {
										$display_download_price = $bdp_settings['display_download_price'];
									}
									?>
									<fieldset class="bdp-social-style buttonset buttonset-hide" data-hide='1'>
										<input id="display_download_price_1" name="display_download_price" type="radio" value="1" <?php checked( 1, $display_download_price ); ?> />
										<label id="bdp-options-button" for="display_download_price_1" <?php checked( 1, $display_download_price ); ?>><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
										<input id="display_download_price_0" name="display_download_price" type="radio" value="0" <?php checked( 0, $display_download_price ); ?> />
										<label id="bdp-options-button" for="display_download_price_0" <?php checked( 1, $display_download_price ); ?>><?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
									</fieldset>
								</div>
							</li>
							<h3 class="bdp-table-title bdp_edd_price_setting">
								<?php esc_html_e( 'Product Price Settings', 'blog-designer-pro' ); ?>
							</h3>
							<li class="bdp_edd_price_setting">
								<ul>
									<div class="bdp_grid_col_2">
										<li class="bdp_pricetext_tr">
											<div class="bdp-left">
												<span class="bdp-key-title">
													<?php esc_html_e( 'Text Color', 'blog-designer-pro' ); ?>
												</span>
											</div>
											<div class="bdp-right">
												<?php

												$bdp_edd_price_color = ( isset( $bdp_settings['bdp_edd_price_color'] ) && '' != $bdp_settings['bdp_edd_price_color'] ) ? $bdp_settings['bdp_edd_price_color'] : '#444444';
												?>
												<input type="text" name="bdp_edd_price_color" id="bdp_edd_price_color" value="<?php echo esc_attr( $bdp_edd_price_color ); ?>"/>
											</div>
										</li>
										<li class="bdp_edd_pricetext_tr bdp_edd_pricetext_alignment_tr">
											<div class="bdp-left">
												<span class="bdp-key-title">
													<?php esc_html_e( 'Alignment', 'blog-designer-pro' ); ?>
												</span>
											</div>
											<div class="bdp-right">
												<?php
												$bdp_edd_price_alignment = 'left';
												if ( isset( $bdp_settings['bdp_edd_price_alignment'] ) ) {
													$bdp_edd_price_alignment = $bdp_settings['bdp_edd_price_alignment'];
												}
												?>
												<fieldset class="buttonset green" data-hide='1'>
													<input id="bdp_edd_price_alignment_left" name="bdp_edd_price_alignment" type="radio" value="left" <?php checked( 'left', $bdp_edd_price_alignment ); ?> />
													<label id="bdp-options-button" for="bdp_edd_price_alignment_left"><?php esc_html_e( 'Align-Left', 'blog-designer-pro' ); ?><span class="bdp-aligment-icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M4 17q-.425 0-.713-.288T3 16q0-.425.288-.713T4 15h10q.425 0 .713.288T15 16q0 .425-.288.713T14 17H4Zm0-8q-.425 0-.713-.288T3 8q0-.425.288-.713T4 7h10q.425 0 .713.288T15 8q0 .425-.288.713T14 9H4Zm0 4q-.425 0-.713-.288T3 12q0-.425.288-.713T4 11h16q.425 0 .713.288T21 12q0 .425-.288.713T20 13H4Zm0 8q-.425 0-.713-.288T3 20q0-.425.288-.713T4 19h16q.425 0 .713.288T21 20q0 .425-.288.713T20 21H4ZM4 5q-.425 0-.713-.288T3 4q0-.425.288-.713T4 3h16q.425 0 .713.288T21 4q0 .425-.288.713T20 5H4Z"/></svg></span></label>
													<input id="bdp_edd_price_alignment_center" name="bdp_edd_price_alignment" type="radio" value="center" <?php checked( 'center', $bdp_edd_price_alignment ); ?> />
													<label id="bdp-options-button" for="bdp_edd_price_alignment_center" class="bdp_edd_price_alignment_center"><?php esc_html_e( 'Align-Center', 'blog-designer-pro' ); ?><span class="bdp-aligment-icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M4 21q-.425 0-.713-.288T3 20q0-.425.288-.713T4 19h16q.425 0 .713.288T21 20q0 .425-.288.713T20 21H4Zm4-4q-.425 0-.713-.288T7 16q0-.425.288-.713T8 15h8q.425 0 .713.288T17 16q0 .425-.288.713T16 17H8Zm-4-4q-.425 0-.713-.288T3 12q0-.425.288-.713T4 11h16q.425 0 .713.288T21 12q0 .425-.288.713T20 13H4Zm4-4q-.425 0-.713-.288T7 8q0-.425.288-.713T8 7h8q.425 0 .713.288T17 8q0 .425-.288.713T16 9H8ZM4 5q-.425 0-.713-.288T3 4q0-.425.288-.713T4 3h16q.425 0 .713.288T21 4q0 .425-.288.713T20 5H4Z"/></svg></span></label>
													<input id="bdp_edd_price_alignment_right" name="bdp_edd_price_alignment" type="radio" value="right" <?php checked( 'right', $bdp_edd_price_alignment ); ?> />
													<label id="bdp-options-button" for="bdp_edd_price_alignment_right"><?php esc_html_e( 'Align-Right', 'blog-designer-pro' ); ?><span class="bdp-aligment-icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M4 21q-.425 0-.713-.288T3 20q0-.425.288-.713T4 19h16q.425 0 .713.288T21 20q0 .425-.288.713T20 21H4Zm6-4q-.425 0-.713-.288T9 16q0-.425.288-.713T10 15h10q.425 0 .713.288T21 16q0 .425-.288.713T20 17H10Zm-6-4q-.425 0-.713-.288T3 12q0-.425.288-.713T4 11h16q.425 0 .713.288T21 12q0 .425-.288.713T20 13H4Zm6-4q-.425 0-.713-.288T9 8q0-.425.288-.713T10 7h10q.425 0 .713.288T21 8q0 .425-.288.713T20 9H10ZM4 5q-.425 0-.713-.288T3 4q0-.425.288-.713T4 3h16q.425 0 .713.288T21 4q0 .425-.288.713T20 5H4Z"/></svg></span></label>
												</fieldset>
											</div>
										</li>
									</div>
									<h3 class="bdp-table-title bdp_edd_price_setting">
										<?php esc_html_e( 'Padding', 'blog-designer-pro' ); ?>
									</h3>
									<li class="bdp_edd_pricetext_tr bdp_edd_pricetext_padding_setting_tr">
										<div class="bdp-padding-wrapper bdp-padding-wrapper1 bdp-border-wrap">
											<div class="bdp-padding-cover">
												<div class="bdp-padding-label">
													<span class="bdp-key-title">
														<?php esc_html_e( 'Left', 'blog-designer-pro' ); ?>
													</span>
													<span class="bdp-px-prefix"><?php echo ' (px)'; ?></span>
												</div>
												<div class="bdp-padding-content">
													<?php $bdp_edd_price_paddingleft = isset( $bdp_settings['bdp_edd_price_paddingleft'] ) ? $bdp_settings['bdp_edd_price_paddingleft'] : '10'; ?>
													<input type="number" id="bdp_edd_price_paddingleft" name="bdp_edd_price_paddingleft" step="1" min="0" value="<?php echo esc_attr( $bdp_edd_price_paddingleft ); ?>"  onkeypress="return isNumberKey(event)">
												</div>
											</div>
											<div class="bdp-padding-cover">
												<div class="bdp-padding-label">
													<span class="bdp-key-title">
														<?php esc_html_e( 'Right', 'blog-designer-pro' ); ?>
													</span>
													<span class="bdp-px-prefix"><?php echo ' (px)'; ?></span>
												</div>
												<div class="bdp-padding-content">
													<?php $bdp_edd_price_paddingright = isset( $bdp_settings['bdp_edd_price_paddingright'] ) ? $bdp_settings['bdp_edd_price_paddingright'] : '10'; ?>
													<input type="number" id="bdp_edd_price_paddingright" name="bdp_edd_price_paddingright" step="1" min="0" value="<?php echo esc_attr( $bdp_edd_price_paddingright ); ?>" onkeypress="return isNumberKey(event)">
												</div>
											</div>
											<div class="bdp-padding-cover">
												<div class="bdp-padding-label">
													<span class="bdp-key-title">
														<?php esc_html_e( 'Top', 'blog-designer-pro' ); ?>
													</span>
													<span class="bdp-px-prefix"><?php echo ' (px)'; ?></span>
												</div>
												<div class="bdp-padding-content">
													<?php $bdp_edd_price_paddingtop = isset( $bdp_settings['bdp_edd_price_paddingtop'] ) ? $bdp_settings['bdp_edd_price_paddingtop'] : '10'; ?>
													<input type="number" id="bdp_edd_price_paddingtop" name="bdp_edd_price_paddingtop" step="1" min="0" value="<?php echo esc_attr( $bdp_edd_price_paddingtop ); ?>"  onkeypress="return isNumberKey(event)">
												</div>
											</div>
											<div class="bdp-padding-cover">
												<div class="bdp-padding-label">
													<span class="bdp-key-title">
														<?php esc_html_e( 'Bottom', 'blog-designer-pro' ); ?>
													</span>
													<span class="bdp-px-prefix"><?php echo ' (px)'; ?></span>
												</div>
												<div class="bdp-padding-content">
													<?php $bdp_edd_price_paddingbottom = isset( $bdp_settings['bdp_edd_price_paddingbottom'] ) ? $bdp_settings['bdp_edd_price_paddingbottom'] : '10'; ?>
													<input type="number" id="bdp_edd_price_paddingbottom" name="bdp_edd_price_paddingbottom" step="1" min="0" value="<?php echo esc_attr( $bdp_edd_price_paddingbottom ); ?>" onkeypress="return isNumberKey(event)">
												</div>
											</div>
										</div>
									</li>
									<li class="bdp_edd_pricetext_tr bdp_edd_pricetext_typography_setting_tr">
										<h3 class="bdp-table-title"><?php esc_html_e( 'Typography Settings', 'blog-designer-pro' ); ?></h3>
										<div class="bdp-typography-wrapper bdp-typography-wrapper1 bdp_grid_col_4">
											<div class="bdp-typography-cover">
												<div class="bdp-typography-label">
													<span class="bdp-key-title">
														<?php esc_html_e( 'Font Family', 'blog-designer-pro' ); ?>
													</span>
												</div>
												<div class="bdp-typography-content">
													<?php
													if ( isset( $bdp_settings['bdp_edd_pricefontface'] ) && '' != $bdp_settings['bdp_edd_pricefontface'] ) {
														$bdp_edd_pricefontface = $bdp_settings['bdp_edd_pricefontface'];
													} else {
														$bdp_edd_pricefontface = '';
													}
													?>
													<div class="typo-field">
														<input type="hidden" id="bdp_edd_pricefontface_font_type" name="bdp_edd_pricefontface_font_type" value="<?php echo isset( $bdp_settings['bdp_edd_pricefontface_font_type'] ) ? esc_attr( $bdp_settings['bdp_edd_pricefontface_font_type'] ) : ''; ?>">
														<div class="select-cover">
															<select name="bdp_edd_pricefontface" id="bdp_edd_pricefontface">
																<option value=""><?php esc_html_e( 'Select Font Family', 'blog-designer-pro' ); ?></option>
																<?php
																$old_version = '';
																$cnt         = 0;
																foreach ( $font_family as $key => $value ) {
																	if ( $value['version'] != $old_version ) {
																		if ( $cnt > 0 ) {
																			echo '</optgroup>';
																		}
																		echo '<optgroup label="' . esc_attr( $value['version'] ) . '">';
																		$old_version = $value['version'];
																	}
																	echo "<option value='" . esc_attr( str_replace( '"', '', $value['label'] ) ) . "'";

																	if ( '' != $bdp_edd_pricefontface && ( str_replace( '"', '', $bdp_edd_pricefontface ) == str_replace( '"', '', $value['label'] ) ) ) {
																		echo ' selected';
																	}
																	echo '>' . esc_attr( $value['label'] ) . '</option>';
																	++$cnt;
																}
																if ( count( $font_family ) == $cnt ) {
																	echo '</optgroup>';
																}
																?>
															</select>
														</div>
													</div>
												</div>
											</div>

											<div class="bdp-typography-cover">
												<div class="bdp-typography-label">
													<span class="bdp-key-title">
														<?php esc_html_e( 'Font Size', 'blog-designer-pro' ); ?>
													</span>
													<span class="bdp-px-prefix"><?php echo ' (px)'; ?></span>
												</div>
												<div class="bdp-typography-content">
													<?php
													if ( isset( $bdp_settings['bdp_edd_pricefontsize'] ) && '' != $bdp_settings['bdp_edd_pricefontsize'] ) {
														$bdp_edd_pricefontsize = $bdp_settings['bdp_edd_pricefontsize'];
													} else {
														$bdp_edd_pricefontsize = 14;
													}
													?>
													<input type="number" name="bdp_edd_pricefontsize" id="bdp_edd_pricefontsize" value="<?php echo esc_attr( $bdp_edd_pricefontsize ); ?>" onkeypress="return isNumberKey(event)" />
												</div>
											</div>

											<div class="bdp-typography-cover">
												<div class="bdp-typography-label">
													<span class="bdp-key-title">
														<?php esc_html_e( 'Font Weight', 'blog-designer-pro' ); ?>
													</span>
												</div>
												<div class="bdp-typography-content">
													<?php $bdp_edd_price_font_weight = isset( $bdp_settings['bdp_edd_price_font_weight'] ) ? $bdp_settings['bdp_edd_price_font_weight'] : 'normal'; ?>
													<div class="select-cover">
														<select name="bdp_edd_price_font_weight" id="bdp_edd_price_font_weight">
															<option value="100" <?php selected( $bdp_edd_price_font_weight, 100 ); ?>>100</option>
															<option value="200" <?php selected( $bdp_edd_price_font_weight, 200 ); ?>>200</option>
															<option value="300" <?php selected( $bdp_edd_price_font_weight, 300 ); ?>>300</option>
															<option value="400" <?php selected( $bdp_edd_price_font_weight, 400 ); ?>>400</option>
															<option value="500" <?php selected( $bdp_edd_price_font_weight, 500 ); ?>>500</option>
															<option value="600" <?php selected( $bdp_edd_price_font_weight, 600 ); ?>>600</option>
															<option value="700" <?php selected( $bdp_edd_price_font_weight, 700 ); ?>>700</option>
															<option value="800" <?php selected( $bdp_edd_price_font_weight, 800 ); ?>>800</option>
															<option value="900" <?php selected( $bdp_edd_price_font_weight, 900 ); ?>>900</option>
															<option value="bold" <?php selected( $bdp_edd_price_font_weight, 'bold' ); ?> ><?php esc_html_e( 'Bold', 'blog-designer-pro' ); ?></option>
															<option value="normal" <?php selected( $bdp_edd_price_font_weight, 'normal' ); ?>><?php esc_html_e( 'Normal', 'blog-designer-pro' ); ?></option>
														</select>
													</div>
												</div>
											</div>

											<div class="bdp-typography-cover">
												<div class="bdp-typography-label">
													<span class="bdp-key-title">
														<?php esc_html_e( 'Line Height', 'blog-designer-pro' ); ?>
													</span>
												</div>
												<div class="bdp-typography-content">
													<input type="number" name="bdp_edd_price_font_line_height" id="bdp_edd_price_font_line_height" step="0.1" min="0" value="<?php echo isset( $bdp_settings['bdp_edd_price_font_line_height'] ) ? esc_attr( $bdp_settings['bdp_edd_price_font_line_height'] ) : '1.5'; ?>" onkeypress="return isNumberKey(event)" >
												</div>
											</div>
											<div class="bdp-typography-cover">
											<div class="bdp-typography-label">
												<span class="bdp-key-title">
													<?php esc_html_e( 'Italic Font Style', 'blog-designer-pro' ); ?>
												</span><?php $bdp_edd_price_font_italic = isset( $bdp_settings['bdp_edd_price_font_italic'] ) ? $bdp_settings['bdp_edd_price_font_italic'] : '0'; ?>
											</div>
											<div class="bdp-typography-content">
												<fieldset class="bdp-social-options bdp-display_author buttonset">
													<input id="bdp_edd_price_font_italic_1" name="bdp_edd_price_font_italic" type="radio" value="1"  <?php checked( 1, $bdp_edd_price_font_italic ); ?> />
													<label for="bdp_edd_price_font_italic_1"><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
													<input id="bdp_edd_price_font_italic_0" name="bdp_edd_price_font_italic" type="radio" value="0" <?php checked( 0, $bdp_edd_price_font_italic ); ?> />
													<label for="bdp_edd_price_font_italic_0"><?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
												</fieldset>
											</div>
										</div>

										<div class="bdp-typography-cover">
											<div class="bdp-typography-label">
												<span class="bdp-key-title">
													<?php esc_html_e( 'Text Transform', 'blog-designer-pro' ); ?>
												</span>
											</div>
											<div class="bdp-typography-content">
												<?php $template_edd_product_font_text_transform = isset( $bdp_settings['template_edd_product_font_text_transform'] ) ? $bdp_settings['template_edd_product_font_text_transform'] : 'none'; ?>
												<div class="select-cover">
													<select name="template_edd_product_font_text_transform" id="template_edd_product_font_text_transform">
														<option <?php selected( $template_edd_product_font_text_transform, 'none' ); ?> value="none"><?php esc_html_e( 'None', 'blog-designer-pro' ); ?></option>
														<option <?php selected( $template_edd_product_font_text_transform, 'capitalize' ); ?> value="capitalize"><?php esc_html_e( 'Capitalize', 'blog-designer-pro' ); ?></option>
														<option <?php selected( $template_edd_product_font_text_transform, 'uppercase' ); ?> value="uppercase"><?php esc_html_e( 'Uppercase', 'blog-designer-pro' ); ?></option>
														<option <?php selected( $template_edd_product_font_text_transform, 'lowercase' ); ?> value="lowercase"><?php esc_html_e( 'Lowercase', 'blog-designer-pro' ); ?></option>
														<option <?php selected( $template_edd_product_font_text_transform, 'full-width' ); ?> value="full-width"><?php esc_html_e( 'Full Width', 'blog-designer-pro' ); ?></option>
													</select>
												</div>
											</div>
										</div>
										<div class="bdp-typography-cover">
											<div class="bdp-typography-label">
												<span class="bdp-key-title">
												<?php esc_html_e( 'Text Decoration', 'blog-designer-pro' ); ?>
												</span>
											</div>
											<div class="bdp-typography-content">
												<?php $bdp_edd_price_font_text_decoration = isset( $bdp_settings['bdp_edd_price_font_text_decoration'] ) ? $bdp_settings['bdp_edd_price_font_text_decoration'] : 'none'; ?>
												<div class="select-cover">
													<select name="bdp_edd_price_font_text_decoration" id="bdp_edd_price_font_text_decoration">
														<option <?php selected( $bdp_edd_price_font_text_decoration, 'none' ); ?> value="none"><?php esc_html_e( 'None', 'blog-designer-pro' ); ?></option>
														<option <?php selected( $bdp_edd_price_font_text_decoration, 'underline' ); ?> value="underline"><?php esc_html_e( 'Underline', 'blog-designer-pro' ); ?></option>
														<option <?php selected( $bdp_edd_price_font_text_decoration, 'overline' ); ?> value="overline"><?php esc_html_e( 'Overline', 'blog-designer-pro' ); ?></option>
														<option <?php selected( $bdp_edd_price_font_text_decoration, 'line-through' ); ?> value="line-through"><?php esc_html_e( 'Line Through', 'blog-designer-pro' ); ?></option>
													</select>
												</div>
											</div>
										</div>
											<div class="bdp-typography-cover">
												<div class="bdp-typography-label">
													<span class="bdp-key-title">
														<?php esc_html_e( 'Letter Spacing', 'blog-designer-pro' ); ?>
													</span>
													<span class="bdp-px-prefix"><?php echo ' (px)'; ?></span>
												</div>
												<div class="bdp-typography-content">
													<input type="number" name="bdp_edd_price_font_letter_spacing" id="bdp_edd_price_font_letter_spacing" step="1" min="0" value="<?php echo isset( $bdp_settings['bdp_edd_price_font_letter_spacing'] ) ? esc_attr( $bdp_settings['bdp_edd_price_font_letter_spacing'] ) : '0'; ?>" onkeypress="return isNumberKey(event)">
												</div>
											</div>
										</div>
									</li>
								</ul>
							</li>
							<li>
								<div class="bdp-left">
									<span class="bdp-key-title">
										<?php esc_html_e( 'Display Purchase Button', 'blog-designer-pro' ); ?>
									</span>
								</div>
								<div class="bdp-right">
									<?php
									$display_edd_addtocart_button = '1';
									if ( isset( $bdp_settings['display_edd_addtocart_button'] ) ) {
										$display_edd_addtocart_button = $bdp_settings['display_edd_addtocart_button'];
									}
									?>
									<fieldset class="bdp-social-style buttonset buttonset-hide" data-hide='1'>
										<input id="display_edd_addtocart_button_1" name="display_edd_addtocart_button" type="radio" value="1" <?php checked( 1, $display_edd_addtocart_button ); ?> />
										<label id="bdp-options-button" for="display_edd_addtocart_button_1" <?php checked( 1, $display_edd_addtocart_button ); ?>><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
										<input id="display_edd_addtocart_button_0" name="display_edd_addtocart_button" type="radio" value="0" <?php checked( 0, $display_edd_addtocart_button ); ?> />
										<label id="bdp-options-button" for="display_edd_addtocart_button_0" <?php checked( 0, $display_edd_addtocart_button ); ?>><?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
									</fieldset>
								</div>
							</li>
							<h3 class="bdp-table-title bdp_edd_cart_button_setting">
									<?php esc_html_e( 'Purchase Button Settings', 'blog-designer-pro' ); ?>
								</h3>
								<li class="bdp_edd_cart_button_setting">
									<ul>
										<li class="bdp_edd_add_to_cart_tr edd_cart_button_alignment">
											<div class="bdp-left">
												<span class="bdp-key-title">
													<?php esc_html_e( 'Alignment', 'blog-designer-pro' ); ?>
												</span>
											</div>
											<div class="bdp-right">
												<?php
												$bdp_edd_addtocartbutton_alignment = 'left';
												if ( isset( $bdp_settings['bdp_edd_addtocartbutton_alignment'] ) ) {
													$bdp_edd_addtocartbutton_alignment = $bdp_settings['bdp_edd_addtocartbutton_alignment'];
												}
												?>
												<fieldset class="buttonset green" data-hide='1'>
													<input id="bdp_edd_addtocartbutton_alignment_left" name="bdp_edd_addtocartbutton_alignment" type="radio" value="left" <?php checked( 'left', $bdp_edd_addtocartbutton_alignment ); ?> />
													<label id="bdp-options-button" for="bdp_edd_addtocartbutton_alignment_left"><?php esc_html_e( 'Align-Left', 'blog-designer-pro' ); ?><span class="bdp-aligment-icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M4 17q-.425 0-.713-.288T3 16q0-.425.288-.713T4 15h10q.425 0 .713.288T15 16q0 .425-.288.713T14 17H4Zm0-8q-.425 0-.713-.288T3 8q0-.425.288-.713T4 7h10q.425 0 .713.288T15 8q0 .425-.288.713T14 9H4Zm0 4q-.425 0-.713-.288T3 12q0-.425.288-.713T4 11h16q.425 0 .713.288T21 12q0 .425-.288.713T20 13H4Zm0 8q-.425 0-.713-.288T3 20q0-.425.288-.713T4 19h16q.425 0 .713.288T21 20q0 .425-.288.713T20 21H4ZM4 5q-.425 0-.713-.288T3 4q0-.425.288-.713T4 3h16q.425 0 .713.288T21 4q0 .425-.288.713T20 5H4Z"/></svg></span></label>
													<input id="bdp_edd_addtocartbutton_alignment_center" name="bdp_edd_addtocartbutton_alignment" type="radio" value="center" <?php checked( 'center', $bdp_edd_addtocartbutton_alignment ); ?> />
													<label id="bdp-options-button" for="bdp_edd_addtocartbutton_alignment_center" class="bdp_edd_addtocartbutton_alignment_center"><?php esc_html_e( 'Align-Center', 'blog-designer-pro' ); ?><span class="bdp-aligment-icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M4 21q-.425 0-.713-.288T3 20q0-.425.288-.713T4 19h16q.425 0 .713.288T21 20q0 .425-.288.713T20 21H4Zm4-4q-.425 0-.713-.288T7 16q0-.425.288-.713T8 15h8q.425 0 .713.288T17 16q0 .425-.288.713T16 17H8Zm-4-4q-.425 0-.713-.288T3 12q0-.425.288-.713T4 11h16q.425 0 .713.288T21 12q0 .425-.288.713T20 13H4Zm4-4q-.425 0-.713-.288T7 8q0-.425.288-.713T8 7h8q.425 0 .713.288T17 8q0 .425-.288.713T16 9H8ZM4 5q-.425 0-.713-.288T3 4q0-.425.288-.713T4 3h16q.425 0 .713.288T21 4q0 .425-.288.713T20 5H4Z"/></svg></span></label>
													<input id="bdp_edd_addtocartbutton_alignment_right" name="bdp_edd_addtocartbutton_alignment" type="radio" value="right" <?php checked( 'right', $bdp_edd_addtocartbutton_alignment ); ?> />
													<label id="bdp-options-button" for="bdp_edd_addtocartbutton_alignment_right"><?php esc_html_e( 'Align-Right', 'blog-designer-pro' ); ?><span class="bdp-aligment-icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M4 21q-.425 0-.713-.288T3 20q0-.425.288-.713T4 19h16q.425 0 .713.288T21 20q0 .425-.288.713T20 21H4Zm6-4q-.425 0-.713-.288T9 16q0-.425.288-.713T10 15h10q.425 0 .713.288T21 16q0 .425-.288.713T20 17H10Zm-6-4q-.425 0-.713-.288T3 12q0-.425.288-.713T4 11h16q.425 0 .713.288T21 12q0 .425-.288.713T20 13H4Zm6-4q-.425 0-.713-.288T9 8q0-.425.288-.713T10 7h10q.425 0 .713.288T21 8q0 .425-.288.713T20 9H10ZM4 5q-.425 0-.713-.288T3 4q0-.425.288-.713T4 3h16q.425 0 .713.288T21 4q0 .425-.288.713T20 5H4Z"/></svg></span></label>
												</fieldset>
											</div>
										</li>
										<li class="bdp_edd_add_to_cart_tr bdp_padding_0">
											<div class="bdp-typography-wrapper bdp-typography-wrapper1 bdp_grid_col_2">
												<div class="bdp-typography-cover">
													<h3 class="bdp-table-title"><?php esc_html_e( 'Normal Settings', 'blog-designer-pro' ); ?></h3>
													<div class="bdp-typography-sub-cover bdp_edd_add_to_cart_tr">
														<div class="bdp-typography-label">
															<span class="bdp-key-title">
															<?php esc_html_e( 'Text Color', 'blog-designer-pro' ); ?>
															</span>
														</div>
														<div class="bdp-typography-content">
															<?php $bdp_edd_addtocart_textcolor = isset( $bdp_settings['bdp_edd_addtocart_textcolor'] ) ? $bdp_settings['bdp_edd_addtocart_textcolor'] : ''; ?>
															<input type="text" name="bdp_edd_addtocart_textcolor" id="bdp_edd_addtocart_textcolor" value="<?php echo esc_attr( $bdp_edd_addtocart_textcolor ); ?>"/>
														</div>
													</div>
													<div class="bdp-typography-sub-cover bdp_edd_add_to_cart_tr">
														<div class="bdp-typography-label">
															<span class="bdp-key-title">
															<?php esc_html_e( 'Background Color', 'blog-designer-pro' ); ?>
															</span>
														</div>
														<div class="bdp-typography-content">
															<?php $bdp_edd_addtocart_backgroundcolor = isset( $bdp_settings['bdp_edd_addtocart_backgroundcolor'] ) ? $bdp_settings['bdp_edd_addtocart_backgroundcolor'] : ''; ?>
															<input type="text" name="bdp_edd_addtocart_backgroundcolor" id="bdp_edd_addtocart_backgroundcolor" value="<?php echo esc_attr( $bdp_edd_addtocart_backgroundcolor ); ?>"/>
														</div>
													</div>
													<div class="bdp-typography-sub-cover bdp_edd_add_to_cart_tr bdp_border_radius_tr">
														<div class="bdp-typography-label">
															<span class="bdp-key-title">
															<?php esc_html_e( 'Border Radius', 'blog-designer-pro' ); ?>
															</span>
															<span class="bdp-px-prefix"><?php echo ' (px)'; ?></span>
														</div>
														<div class="bdp-typography-content">
															<?php
															$display_edd_addtocart_button_border_radius = '0';
															if ( isset( $bdp_settings['display_edd_addtocart_button_border_radius'] ) ) {
																$display_edd_addtocart_button_border_radius = $bdp_settings['display_edd_addtocart_button_border_radius'];
															}
															?>
															<input type="number" id="display_edd_addtocart_button_border_radius" name="display_edd_addtocart_button_border_radius" step="1" min="0" value="<?php echo esc_attr( $display_edd_addtocart_button_border_radius ); ?>" onkeypress="return isNumberKey(event)">
														</div>
													</div>
													<div class="bdp-typography-sub-cover full-width bdp_edd_add_to_cart_tr">
														<div class="bdp-typography-label">
															<span class="bdp-key-title">
																<?php esc_html_e( 'Border', 'blog-designer-pro' ); ?>
															</span>
														</div>
														<div class="bdp-typography-content">
															<div class="bdp-border-wrap">
																<div class="bdp-border-wrapper bdp-border-wrapper1 bdp_grid_col_2">
																	<div class="bdp-rmb-inner">
																		<div class="bdp-border-cover bdp-border-label">
																				<span class="bdp-key-title">
																					<?php esc_html_e( 'Left ', 'blog-designer-pro' ); ?>
																					<?php echo '<span class="bdp-media-div">(<i>' . esc_html__( 'px', 'blog-designer-pro' ) . '</i>)</span>'; ?>
																				</span>
																			</div>
																		<div class="bdp-border-cover">
																			<div class="bdp-border-content">
																				<?php $bdp_edd_addtocartbutton_borderleft = isset( $bdp_settings['bdp_edd_addtocartbutton_borderleft'] ) ? $bdp_settings['bdp_edd_addtocartbutton_borderleft'] : '0'; ?>
																				<input type="number" id="bdp_edd_addtocartbutton_borderleft" name="bdp_edd_addtocartbutton_borderleft" step="1" min="0" value="<?php echo esc_attr( $bdp_edd_addtocartbutton_borderleft ); ?>"  onkeypress="return isNumberKey(event)">
																			</div>
																		</div>
																		<div class="bdp-border-cover bdp-border-label">
																			<span class="bdp-key-title">
																				<?php esc_html_e( 'Right ', 'blog-designer-pro' ); ?>
																				<?php echo '<span class="bdp-media-div">(<i>' . esc_html__( 'px', 'blog-designer-pro' ) . '</i>)</span>'; ?>
																			</span>
																		</div>
																		<div class="bdp-border-cover">
																			<div class="bdp-border-content">
																				<?php $bdp_edd_addtocartbutton_borderright = isset( $bdp_settings['bdp_edd_addtocartbutton_borderright'] ) ? $bdp_settings['bdp_edd_addtocartbutton_borderright'] : '0'; ?>
																				<input type="number" id="bdp_edd_addtocartbutton_borderright" name="bdp_edd_addtocartbutton_borderright" step="1" min="0" value="<?php echo esc_attr( $bdp_edd_addtocartbutton_borderright ); ?>" onkeypress="return isNumberKey(event)">
																			</div>
																		</div>
																		<div class="bdp-border-cover bdp-border-label">
																			<span class="bdp-key-title">
																				<?php esc_html_e( 'Top ', 'blog-designer-pro' ); ?>
																				<?php echo '<span class="bdp-media-div">(<i>' . esc_html__( 'px', 'blog-designer-pro' ) . '</i>)</span>'; ?>
																			</span>
																		</div>
																		<div class="bdp-border-cover">
																			<div class="bdp-border-content">
																				<?php $bdp_edd_addtocartbutton_bordertop = isset( $bdp_settings['bdp_edd_addtocartbutton_bordertop'] ) ? $bdp_settings['bdp_edd_addtocartbutton_bordertop'] : '0'; ?>
																				<input type="number" id="bdp_edd_addtocartbutton_bordertop" name="bdp_edd_addtocartbutton_bordertop" step="1" min="0" value="<?php echo esc_attr( $bdp_edd_addtocartbutton_bordertop ); ?>" onkeypress="return isNumberKey(event)">
																			</div>
																		</div>
																		<div class="bdp-border-cover bdp-border-label">
																			<span class="bdp-key-title">
																				<?php esc_html_e( 'Bottom', 'blog-designer-pro' ); ?>
																				<?php echo '<span class="bdp-media-div">(<i>' . esc_html__( 'px', 'blog-designer-pro' ) . '</i>)</span>'; ?>
																			</span>
																		</div>
																		<div class="bdp-border-cover">
																			<div class="bdp-border-content">
																				<?php $bdp_edd_addtocartbutton_borderbottom = isset( $bdp_settings['bdp_edd_addtocartbutton_borderbottom'] ) ? $bdp_settings['bdp_edd_addtocartbutton_borderbottom'] : '0'; ?>
																				<input type="number" id="bdp_edd_addtocartbutton_borderbottom" name="bdp_edd_addtocartbutton_borderbottom" step="1" min="0" value="<?php echo esc_attr( $bdp_edd_addtocartbutton_borderbottom ); ?>" onkeypress="return isNumberKey(event)">
																			</div>
																		</div>
																	</div>
																	<div class="bdp-rmb-inner">
																		<div class="bdp-border-cover bdp-border-label">
																			<span class="bdp-key-title">
																				<?php esc_html_e( 'Color', 'blog-designer-pro' ); ?>
																			</span>
																		</div>
																		<div class="bdp-border-cover">
																			<div class="bdp-border-content">
																				<?php $bdp_edd_addtocartbutton_borderleftcolor = isset( $bdp_settings['bdp_edd_addtocartbutton_borderleftcolor'] ) ? $bdp_settings['bdp_edd_addtocartbutton_borderleftcolor'] : ''; ?>
																				<input type="text" name="bdp_edd_addtocartbutton_borderleftcolor" id="bdp_edd_addtocartbutton_borderleftcolor" value="<?php echo esc_attr( $bdp_edd_addtocartbutton_borderleftcolor ); ?>"/>
																			</div>
																		</div>
																		<div class="bdp-border-cover bdp-border-label">
																			<span class="bdp-key-title">
																				<?php esc_html_e( 'Color', 'blog-designer-pro' ); ?>
																			</span>
																		</div>
																		<div class="bdp-border-cover">
																			<div class="bdp-border-content">
																				<?php $bdp_edd_addtocartbutton_borderrightcolor = isset( $bdp_settings['bdp_edd_addtocartbutton_borderrightcolor'] ) ? $bdp_settings['bdp_edd_addtocartbutton_borderrightcolor'] : ''; ?>
																				<input type="text" name="bdp_edd_addtocartbutton_borderrightcolor" id="bdp_edd_addtocartbutton_borderrightcolor" value="<?php echo esc_attr( $bdp_edd_addtocartbutton_borderrightcolor ); ?>"/>
																			</div>
																		</div>
																		<div class="bdp-border-cover bdp-border-label">
																			<span class="bdp-key-title">
																				<?php esc_html_e( 'Color', 'blog-designer-pro' ); ?>
																			</span>
																		</div>
																		<div class="bdp-border-cover">
																			<div class="bdp-border-content">
																				<?php $bdp_edd_addtocartbutton_bordertopcolor = isset( $bdp_settings['bdp_edd_addtocartbutton_bordertopcolor'] ) ? $bdp_settings['bdp_edd_addtocartbutton_bordertopcolor'] : ''; ?>
																				<input type="text" name="bdp_edd_addtocartbutton_bordertopcolor" id="bdp_edd_addtocartbutton_bordertopcolor" value="<?php echo esc_attr( $bdp_edd_addtocartbutton_bordertopcolor ); ?>"/>
																			</div>
																		</div>
																		<div class="bdp-border-cover bdp-border-label">
																			<span class="bdp-key-title">
																				<?php esc_html_e( 'Color', 'blog-designer-pro' ); ?>
																			</span>
																		</div>
																		<div class="bdp-border-cover">
																			<div class="bdp-border-content">
																			<?php $bdp_edd_addtocartbutton_borderbottomcolor = isset( $bdp_settings['bdp_edd_addtocartbutton_borderbottomcolor'] ) ? $bdp_settings['bdp_edd_addtocartbutton_borderbottomcolor'] : ''; ?>
																			<input type="text" name="bdp_edd_addtocartbutton_borderbottomcolor" id="bdp_edd_addtocartbutton_borderbottomcolor" value="<?php echo esc_attr( $bdp_edd_addtocartbutton_borderbottomcolor ); ?>"/>
																			</div>
																		</div>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
												<div class="bdp-typography-cover">
													<h3 class="bdp-table-title"><?php esc_html_e( 'Hover Settings', 'blog-designer-pro' ); ?></h3>
													<div class="bdp-typography-sub-cover bdp_edd_add_to_cart_tr">
														<div class="bdp-typography-label">
															<span class="bdp-key-title">
															<?php esc_html_e( 'Text Color', 'blog-designer-pro' ); ?>
															</span>
														</div>
														<div class="bdp-typography-content">
															<?php $bdp_edd_addtocart_text_hover_color = isset( $bdp_settings['bdp_edd_addtocart_text_hover_color'] ) ? $bdp_settings['bdp_edd_addtocart_text_hover_color'] : ''; ?>
															<input type="text" name="bdp_edd_addtocart_text_hover_color" id="bdp_edd_addtocart_text_hover_color" value="<?php echo esc_attr( $bdp_edd_addtocart_text_hover_color ); ?>"/>
														</div>
													</div>
													<div class="bdp-typography-sub-cover bdp_edd_add_to_cart_tr">
														<div class="bdp-typography-label">
															<span class="bdp-key-title">
															<?php esc_html_e( 'Background Color', 'blog-designer-pro' ); ?>
															</span>
														</div>
														<div class="bdp-typography-content">
															<?php $bdp_edd_addtocart_hover_backgroundcolor = isset( $bdp_settings['bdp_edd_addtocart_hover_backgroundcolor'] ) ? $bdp_settings['bdp_edd_addtocart_hover_backgroundcolor'] : ''; ?>
															<input type="text" name="bdp_edd_addtocart_hover_backgroundcolor" id="bdp_edd_addtocart_hover_backgroundcolor" value="<?php echo esc_attr( $bdp_edd_addtocart_hover_backgroundcolor ); ?>"/>
														</div>
													</div>
													<div class="bdp-typography-sub-cover bdp_edd_add_to_cart_tr bdp_border_radius_tr">
														<div class="bdp-typography-label">
															<span class="bdp-key-title">
															<?php esc_html_e( 'Border Radius', 'blog-designer-pro' ); ?>
															</span>
															<span class="bdp-px-prefix"><?php echo ' (px)'; ?></span>
														</div>
														<div class="bdp-typography-content">
															<?php
															$display_edd_addtocart_button_border_hover_radius = '0';
															if ( isset( $bdp_settings['display_edd_addtocart_button_border_hover_radius'] ) ) {
																$display_edd_addtocart_button_border_hover_radius = $bdp_settings['display_edd_addtocart_button_border_hover_radius'];
															}
															?>
															<input type="number" id="display_edd_addtocart_button_border_hover_radius" name="display_edd_addtocart_button_border_hover_radius" step="1" min="0" value="<?php echo esc_attr( $display_edd_addtocart_button_border_hover_radius ); ?>" onkeypress="return isNumberKey(event)">
														</div>
													</div>
													<div class="bdp-typography-sub-cover full-width bdp_edd_add_to_cart_tr">
														<div class="bdp-typography-label">
															<span class="bdp-key-title">
																<?php esc_html_e( 'Border', 'blog-designer-pro' ); ?>
															</span>
														</div>
														<div class="bdp-typography-content">
															<div class="bdp-border-wrap">
																<div class="bdp-border-wrapper bdp-border-wrapper1 bdp_grid_col_2">
																	<div class="bdp-rmb-inner">
																		<div class="bdp-border-cover bdp-border-label">
																				<span class="bdp-key-title">
																					<?php esc_html_e( 'Left ', 'blog-designer-pro' ); ?>
																					<?php echo '<span class="bdp-media-div">(<i>' . esc_html__( 'px', 'blog-designer-pro' ) . '</i>)</span>'; ?>
																				</span>
																			</div>
																		<div class="bdp-border-cover">
																			<div class="bdp-border-content">
																				<?php $bdp_edd_addtocartbutton_hover_borderleft = isset( $bdp_settings['bdp_edd_addtocartbutton_hover_borderleft'] ) ? $bdp_settings['bdp_edd_addtocartbutton_hover_borderleft'] : '0'; ?>
																				<input type="number" id="bdp_edd_addtocartbutton_hover_borderleft" name="bdp_edd_addtocartbutton_hover_borderleft" step="1" min="0" value="<?php echo esc_attr( $bdp_edd_addtocartbutton_hover_borderleft ); ?>"  onkeypress="return isNumberKey(event)">
																			</div>
																		</div>
																		<div class="bdp-border-cover bdp-border-label">
																			<span class="bdp-key-title">
																				<?php esc_html_e( 'Right ', 'blog-designer-pro' ); ?>
																				<?php echo '<span class="bdp-media-div">(<i>' . esc_html__( 'px', 'blog-designer-pro' ) . '</i>)</span>'; ?>
																			</span>
																		</div>
																		<div class="bdp-border-cover">
																			<div class="bdp-border-content">
																				<?php $bdp_edd_addtocartbutton_hover_borderright = isset( $bdp_settings['bdp_edd_addtocartbutton_hover_borderright'] ) ? $bdp_settings['bdp_edd_addtocartbutton_hover_borderright'] : '0'; ?>
																				<input type="number" id="bdp_edd_addtocartbutton_hover_borderright" name="bdp_edd_addtocartbutton_hover_borderright" step="1" min="0" value="<?php echo esc_attr( $bdp_edd_addtocartbutton_hover_borderright ); ?>" onkeypress="return isNumberKey(event)">
																			</div>
																		</div>
																		<div class="bdp-border-cover bdp-border-label">
																			<span class="bdp-key-title">
																				<?php esc_html_e( 'Top ', 'blog-designer-pro' ); ?>
																				<?php echo '<span class="bdp-media-div">(<i>' . esc_html__( 'px', 'blog-designer-pro' ) . '</i>)</span>'; ?>
																			</span>
																		</div>
																		<div class="bdp-border-cover">
																			<div class="bdp-border-content">
																				<?php $bdp_edd_addtocartbutton_hover_bordertop = isset( $bdp_settings['bdp_edd_addtocartbutton_hover_bordertop'] ) ? $bdp_settings['bdp_edd_addtocartbutton_hover_bordertop'] : '0'; ?>
																				<input type="number" id="bdp_edd_addtocartbutton_hover_bordertop" name="bdp_edd_addtocartbutton_hover_bordertop" step="1" min="0" value="<?php echo esc_attr( $bdp_edd_addtocartbutton_hover_bordertop ); ?>" onkeypress="return isNumberKey(event)">
																			</div>
																		</div>
																		<div class="bdp-border-cover bdp-border-label">
																			<span class="bdp-key-title">
																				<?php esc_html_e( 'Bottom', 'blog-designer-pro' ); ?>
																				<?php echo '<span class="bdp-media-div">(<i>' . esc_html__( 'px', 'blog-designer-pro' ) . '</i>)</span>'; ?>
																			</span>
																		</div>
																		<div class="bdp-border-cover">
																			<div class="bdp-border-content">
																				<?php $bdp_edd_addtocartbutton_hover_borderbottom = isset( $bdp_settings['bdp_edd_addtocartbutton_hover_borderbottom'] ) ? $bdp_settings['bdp_edd_addtocartbutton_hover_borderbottom'] : '0'; ?>
																				<input type="number" id="bdp_edd_addtocartbutton_hover_borderbottom" name="bdp_edd_addtocartbutton_hover_borderbottom" step="1" min="0" value="<?php echo esc_attr( $bdp_edd_addtocartbutton_hover_borderbottom ); ?>" onkeypress="return isNumberKey(event)">
																			</div>
																		</div>
																	</div>
																	<div class="bdp-rmb-inner">
																		<div class="bdp-border-cover bdp-border-label">
																			<span class="bdp-key-title">
																				<?php esc_html_e( 'Color', 'blog-designer-pro' ); ?>
																			</span>
																		</div>
																		<div class="bdp-border-cover">
																			<div class="bdp-border-content">
																				<?php $bdp_edd_addtocartbutton_hover_borderleftcolor = isset( $bdp_settings['bdp_edd_addtocartbutton_hover_borderleftcolor'] ) ? $bdp_settings['bdp_edd_addtocartbutton_hover_borderleftcolor'] : ''; ?>
																				<input type="text" name="bdp_edd_addtocartbutton_hover_borderleftcolor" id="bdp_edd_addtocartbutton_hover_borderleftcolor" value="<?php echo esc_attr( $bdp_edd_addtocartbutton_hover_borderleftcolor ); ?>"/>
																			</div>
																		</div>
																		<div class="bdp-border-cover bdp-border-label">
																			<span class="bdp-key-title">
																				<?php esc_html_e( 'Color', 'blog-designer-pro' ); ?>
																			</span>
																		</div>
																		<div class="bdp-border-cover">
																			<div class="bdp-border-content">
																				<?php $bdp_edd_addtocartbutton_hover_borderrightcolor = isset( $bdp_settings['bdp_edd_addtocartbutton_hover_borderrightcolor'] ) ? $bdp_settings['bdp_edd_addtocartbutton_hover_borderrightcolor'] : ''; ?>
																				<input type="text" name="bdp_edd_addtocartbutton_hover_borderrightcolor" id="bdp_edd_addtocartbutton_hover_borderrightcolor" value="<?php echo esc_attr( $bdp_edd_addtocartbutton_hover_borderrightcolor ); ?>"/>
																			</div>
																		</div>
																		<div class="bdp-border-cover bdp-border-label">
																			<span class="bdp-key-title">
																				<?php esc_html_e( 'Color', 'blog-designer-pro' ); ?>
																			</span>
																		</div>
																		<div class="bdp-border-cover">
																			<div class="bdp-border-content">
																				<?php $bdp_edd_addtocartbutton_hover_bordertopcolor = isset( $bdp_settings['bdp_edd_addtocartbutton_hover_bordertopcolor'] ) ? $bdp_settings['bdp_edd_addtocartbutton_hover_bordertopcolor'] : ''; ?>
																				<input type="text" name="bdp_edd_addtocartbutton_hover_bordertopcolor" id="bdp_edd_addtocartbutton_hover_bordertopcolor" value="<?php echo esc_attr( $bdp_edd_addtocartbutton_hover_bordertopcolor ); ?>"/>
																			</div>
																		</div>
																		<div class="bdp-border-cover bdp-border-label">
																			<span class="bdp-key-title">
																				<?php esc_html_e( 'Color', 'blog-designer-pro' ); ?>
																			</span>
																		</div>
																		<div class="bdp-border-cover">
																			<div class="bdp-border-content">
																			<?php $bdp_edd_addtocartbutton_hover_borderbottomcolor = isset( $bdp_settings['bdp_edd_addtocartbutton_hover_borderbottomcolor'] ) ? $bdp_settings['bdp_edd_addtocartbutton_hover_borderbottomcolor'] : ''; ?>
																			<input type="text" name="bdp_edd_addtocartbutton_hover_borderbottomcolor" id="bdp_edd_addtocartbutton_hover_borderbottomcolor" value="<?php echo esc_attr( $bdp_edd_addtocartbutton_hover_borderbottomcolor ); ?>"/>
																			</div>
																		</div>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</li>
										<li class="bdp_edd_add_to_cart_tr">
											<h3 class="bdp-table-title"><?php esc_html_e( 'Padding', 'blog-designer-pro' ); ?></h3>
											<div class="bdp-border-cover">
												<div class="bdp-padding-wrapper bdp-padding-wrapper1 bdp-border-wrap">
													<div class="bdp-padding-cover">
														<div class="bdp-padding-wrapper bdp-padding-wrapper1 bdp-border-wrap">
															<div class="bdp-left">
																<span class="bdp-key-title">
																	<?php esc_html_e( 'Left-Right ', 'blog-designer-pro' ); ?>
																</span>
																<?php echo '<span class="bdp-media-div">(<i>' . esc_html__( 'px', 'blog-designer-pro' ) . '</i>)</span>'; ?>
															</div>
															<div class="bdp-right">
																<div class="bdp-padding-content">
																	<?php $bdp_edd_addtocartbutton_padding_leftright = isset( $bdp_settings['bdp_edd_addtocartbutton_padding_leftright'] ) ? $bdp_settings['bdp_edd_addtocartbutton_padding_leftright'] : '0'; ?>
																	<input type="number" id="bdp_edd_addtocartbutton_padding_leftright" name="bdp_edd_addtocartbutton_padding_leftright" step="1" min="0" value="<?php echo esc_attr( $bdp_edd_addtocartbutton_padding_leftright ); ?>" onkeypress="return isNumberKey(event)">
																</div>
															</div>
															<div class="bdp-left">
																<span class="bdp-key-title">
																	<?php esc_html_e( 'Top-Bottom', 'blog-designer-pro' ); ?>
																</span>
																<?php echo '<span class="bdp-media-div">(<i>' . esc_html__( 'px', 'blog-designer-pro' ) . '</i>)</span>'; ?>
															</div>
															<div class="bdp-right">
																<div class="bdp-padding-content">
																	<?php $bdp_edd_addtocartbutton_padding_topbottom = isset( $bdp_settings['bdp_edd_addtocartbutton_padding_topbottom'] ) ? $bdp_settings['bdp_edd_addtocartbutton_padding_topbottom'] : '0'; ?>
																	<input type="number" id="bdp_edd_addtocartbutton_padding_topbottom" name="bdp_edd_addtocartbutton_padding_topbottom" step="1" min="0" value="<?php echo esc_attr( $bdp_edd_addtocartbutton_padding_topbottom ); ?>"  onkeypress="return isNumberKey(event)">
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</li>
										<li class="bdp_edd_add_to_cart_tr">
											<h3 class="bdp-table-title"><?php esc_html_e( 'Margin', 'blog-designer-pro' ); ?></h3>
											<div class="bdp-border-cover">
												<div class="bdp-padding-wrapper bdp-padding-wrapper1 bdp-border-wrap">
													<div class="bdp-left">
														<span class="bdp-key-title">
															<?php esc_html_e( 'Left-Right ', 'blog-designer-pro' ); ?>
														</span>
														<?php echo '<span class="bdp-media-div">(<i>' . esc_html__( 'px', 'blog-designer-pro' ) . '</i>)</span>'; ?>
													</div>
													<div class="bdp-right">
														<div class="bdp-padding-content">
															<?php $bdp_edd_addtocartbutton_margin_leftright = isset( $bdp_settings['bdp_edd_addtocartbutton_margin_leftright'] ) ? $bdp_settings['bdp_edd_addtocartbutton_margin_leftright'] : '0'; ?>
															<input type="number" id="bdp_edd_addtocartbutton_margin_leftright" name="bdp_edd_addtocartbutton_margin_leftright" step="1" value="<?php echo esc_attr( $bdp_edd_addtocartbutton_margin_leftright ); ?>"  onkeypress="return isNumberKey(event)">
														</div>
													</div>
													<div class="bdp-left">
														<span class="bdp-key-title">
															<?php esc_html_e( 'Top-Bottom', 'blog-designer-pro' ); ?>
														</span>
														<?php echo '<span class="bdp-media-div">(<i>' . esc_html__( 'px', 'blog-designer-pro' ) . '</i>)</span>'; ?>
													</div>
													<div class="bdp-right">
														<div class="bdp-padding-content">
															<?php $bdp_edd_addtocartbutton_margin_topbottom = isset( $bdp_settings['bdp_edd_addtocartbutton_margin_topbottom'] ) ? $bdp_settings['bdp_edd_addtocartbutton_margin_topbottom'] : '0'; ?>
															<input type="number" id="bdp_edd_addtocartbutton_margin_topbottom" name="bdp_edd_addtocartbutton_margin_topbottom" step="1" value="<?php echo esc_attr( $bdp_edd_addtocartbutton_margin_topbottom ); ?>"  onkeypress="return isNumberKey(event)">
														</div>
													</div>
												</div>
											</div>
										</li>
										<li class="bdp_edd_add_to_cart_tr edd_addtocart_button_box_shadow_setting">
											<h3 class="bdp-table-title"><?php esc_html_e( 'Box Shadow Settings', 'blog-designer-pro' ); ?></h3>
											<div class="bdp-boxshadow-wrapper bdp-boxshadow-wrapper1">
												<div class="bdp-boxshadow-cover">
													<div class="bdp-boxshadow-label">
														<span class="bdp-key-title">
															<?php esc_html_e( 'H-offset', 'blog-designer-pro' ); ?>
														</span>
														<span class="bdp-px-prefix"><?php echo ' (px)'; ?></span>
													</div>
													<div class="bdp-boxshadow-content">
														<?php $bdp_edd_addtocart_button_top_box_shadow = isset( $bdp_settings['bdp_edd_addtocart_button_top_box_shadow'] ) ? $bdp_settings['bdp_edd_addtocart_button_top_box_shadow'] : '0'; ?>
														<input type="number" id="bdp_edd_addtocart_button_top_box_shadow" name="bdp_edd_addtocart_button_top_box_shadow" step="1" min="0" value="<?php echo esc_attr( $bdp_edd_addtocart_button_top_box_shadow ); ?>"  onkeypress="return isNumberKey(event)">
													</div>
												</div>
												<div class="bdp-boxshadow-cover">
													<div class="bdp-boxshadow-label">
														<span class="bdp-key-title">
															<?php esc_html_e( 'V-offset', 'blog-designer-pro' ); ?>
														</span>
														<span class="bdp-px-prefix"><?php echo ' (px)'; ?></span>
													</div>
													<div class="bdp-boxshadow-content">
														<?php $bdp_edd_addtocart_button_right_box_shadow = isset( $bdp_settings['bdp_edd_addtocart_button_right_box_shadow'] ) ? $bdp_settings['bdp_edd_addtocart_button_right_box_shadow'] : '0'; ?>
														<input type="number" id="bdp_edd_addtocart_button_right_box_shadow" name="bdp_edd_addtocart_button_right_box_shadow" step="1" min="0" value="<?php echo esc_attr( $bdp_edd_addtocart_button_right_box_shadow ); ?>"  onkeypress="return isNumberKey(event)">
													</div>
												</div>
												<div class="bdp-boxshadow-cover">
													<div class="bdp-boxshadow-label">
														<span class="bdp-key-title">
															<?php esc_html_e( 'Blur', 'blog-designer-pro' ); ?>
														</span>
														<span class="bdp-px-prefix"><?php echo ' (px)'; ?></span>
													</div>
													<div class="bdp-boxshadow-content">
														<?php $bdp_edd_addtocart_button_bottom_box_shadow = isset( $bdp_settings['bdp_edd_addtocart_button_bottom_box_shadow'] ) ? $bdp_settings['bdp_edd_addtocart_button_bottom_box_shadow'] : '0'; ?>
														<input type="number" id="bdp_edd_addtocart_button_bottom_box_shadow" name="bdp_edd_addtocart_button_bottom_box_shadow" step="1" min="0" value="<?php echo esc_attr( $bdp_edd_addtocart_button_bottom_box_shadow ); ?>"  onkeypress="return isNumberKey(event)">
													</div>
												</div>
												<div class="bdp-boxshadow-cover">
													<div class="bdp-boxshadow-label">
														<span class="bdp-key-title">
															<?php esc_html_e( 'Spread', 'blog-designer-pro' ); ?>
														</span>
														<span class="bdp-px-prefix"><?php echo ' (px)'; ?></span>
													</div>
													<div class="bdp-boxshadow-content">
														<?php $bdp_edd_addtocart_button_left_box_shadow = isset( $bdp_settings['bdp_edd_addtocart_button_left_box_shadow'] ) ? $bdp_settings['bdp_edd_addtocart_button_left_box_shadow'] : '0'; ?>
														<input type="number" id="bdp_edd_addtocart_button_left_box_shadow" name="bdp_edd_addtocart_button_left_box_shadow" step="1" min="0" value="<?php echo esc_attr( $bdp_edd_addtocart_button_left_box_shadow ); ?>"  onkeypress="return isNumberKey(event)">
													</div>
												</div>
												<div class="bdp-boxshadow-cover bdp-boxshadow-color">
													<div class="bdp-boxshadow-label">
														<span class="bdp-key-title">
															<?php esc_html_e( 'Color', 'blog-designer-pro' ); ?>
														</span>
													</div>
													<div class="bdp-boxshadow-content">
															<?php $bdp_edd_addtocart_button_box_shadow_color = isset( $bdp_settings['bdp_edd_addtocart_button_box_shadow_color'] ) ? $bdp_settings['bdp_edd_addtocart_button_box_shadow_color'] : ''; ?>
															<input type="text" name="bdp_edd_addtocart_button_box_shadow_color" id="bdp_edd_addtocart_button_box_shadow_color" value="<?php echo esc_attr( $bdp_edd_addtocart_button_box_shadow_color ); ?>"/>
													</div>
												</div>
											</div>
										</li>
										<li class="bdp_edd_add_to_cart_tr edd_addtocart_button_hover_box_shadow_setting">
											<h3 class="bdp-table-title"><?php esc_html_e( 'Hover Box Shadow Settings', 'blog-designer-pro' ); ?></h3>
											<div class="bdp-boxshadow-wrapper bdp-boxshadow-wrapper1">
												<div class="bdp-boxshadow-cover">
													<div class="bdp-boxshadow-label">
														<span class="bdp-key-title">
															<?php esc_html_e( 'H-offset', 'blog-designer-pro' ); ?>
														</span>
														<span class="bdp-px-prefix"><?php echo ' (px)'; ?></span>
													</div>
													<div class="bdp-boxshadow-content">
														<?php $bdp_edd_addtocart_button_hover_top_box_shadow = isset( $bdp_settings['bdp_edd_addtocart_button_hover_top_box_shadow'] ) ? $bdp_settings['bdp_edd_addtocart_button_hover_top_box_shadow'] : '0'; ?>
														<input type="number" id="bdp_edd_addtocart_button_hover_top_box_shadow" name="bdp_edd_addtocart_button_hover_top_box_shadow" step="1" min="0" value="<?php echo esc_attr( $bdp_edd_addtocart_button_hover_top_box_shadow ); ?>"  onkeypress="return isNumberKey(event)">
													</div>
												</div>
												<div class="bdp-boxshadow-cover">
													<div class="bdp-boxshadow-label">
														<span class="bdp-key-title">
															<?php esc_html_e( 'V-offset', 'blog-designer-pro' ); ?>
														</span>
														<span class="bdp-px-prefix"><?php echo ' (px)'; ?></span>
													</div>
													<div class="bdp-boxshadow-content">
														<?php $bdp_edd_addtocart_button_hover_right_box_shadow = isset( $bdp_settings['bdp_edd_addtocart_button_hover_right_box_shadow'] ) ? $bdp_settings['bdp_edd_addtocart_button_hover_right_box_shadow'] : '0'; ?>
														<input type="number" id="bdp_edd_addtocart_button_hover_right_box_shadow" name="bdp_edd_addtocart_button_hover_right_box_shadow" step="1" min="0" value="<?php echo esc_attr( $bdp_edd_addtocart_button_hover_right_box_shadow ); ?>"  onkeypress="return isNumberKey(event)">
													</div>
												</div>
												<div class="bdp-boxshadow-cover">
													<div class="bdp-boxshadow-label">
														<span class="bdp-key-title">
															<?php esc_html_e( 'Blur', 'blog-designer-pro' ); ?>
														</span>
														<span class="bdp-px-prefix"><?php echo ' (px)'; ?></span>
													</div>
													<div class="bdp-boxshadow-content">
														<?php $bdp_edd_addtocart_button_hover_bottom_box_shadow = isset( $bdp_settings['bdp_edd_addtocart_button_hover_bottom_box_shadow'] ) ? $bdp_settings['bdp_edd_addtocart_button_hover_bottom_box_shadow'] : '0'; ?>
														<input type="number" id="bdp_edd_addtocart_button_hover_bottom_box_shadow" name="bdp_edd_addtocart_button_hover_bottom_box_shadow" step="1" min="0" value="<?php echo esc_attr( $bdp_edd_addtocart_button_hover_bottom_box_shadow ); ?>"  onkeypress="return isNumberKey(event)">
													</div>
												</div>
												<div class="bdp-boxshadow-cover">
													<div class="bdp-boxshadow-label">
														<span class="bdp-key-title">
															<?php esc_html_e( 'Spread', 'blog-designer-pro' ); ?>
														</span>
														<span class="bdp-px-prefix"><?php echo ' (px)'; ?></span>
													</div>
													<div class="bdp-boxshadow-content">
														<?php $bdp_edd_addtocart_button_hover_left_box_shadow = isset( $bdp_settings['bdp_edd_addtocart_button_hover_left_box_shadow'] ) ? $bdp_settings['bdp_edd_addtocart_button_hover_left_box_shadow'] : '0'; ?>
														<input type="number" id="bdp_edd_addtocart_button_hover_left_box_shadow" name="bdp_edd_addtocart_button_hover_left_box_shadow" step="1" min="0" value="<?php echo esc_attr( $bdp_edd_addtocart_button_hover_left_box_shadow ); ?>"  onkeypress="return isNumberKey(event)">
													</div>
												</div>
												<div class="bdp-boxshadow-cover bdp-boxshadow-color">
													<div class="bdp-boxshadow-label">
														<span class="bdp-key-title">
															<?php esc_html_e( 'Color', 'blog-designer-pro' ); ?>
														</span>
													</div>
													<div class="bdp-boxshadow-content">
														<?php $bdp_edd_addtocart_button_hover_box_shadow_color = isset( $bdp_settings['bdp_edd_addtocart_button_hover_box_shadow_color'] ) ? $bdp_settings['bdp_edd_addtocart_button_hover_box_shadow_color'] : ''; ?>
														<input type="text" name="bdp_edd_addtocart_button_hover_box_shadow_color" id="bdp_edd_addtocart_button_hover_box_shadow_color" value="<?php echo esc_attr( $bdp_edd_addtocart_button_hover_box_shadow_color ); ?>"/>
													</div>
												</div>
											</div>
										</li>
										<li class="bdp_edd_add_to_cart_tr">
											<h3 class="bdp-table-title"><?php esc_html_e( 'Typography Settings', 'blog-designer-pro' ); ?></h3>
											<div class="bdp-typography-wrapper bdp-typography-wrapper1 bdp_grid_col_4">
												<div class="bdp-typography-cover">
													<div class="bdp-typography-label">
														<span class="bdp-key-title">
															<?php esc_html_e( 'Font Family', 'blog-designer-pro' ); ?>
														</span>
													</div>
													<div class="bdp-typography-content">
														<?php
														if ( isset( $bdp_settings['bdp_edd_addtocart_button_fontface'] ) && '' != $bdp_settings['bdp_edd_addtocart_button_fontface'] ) {
															$bdp_edd_addtocart_button_fontface = $bdp_settings['bdp_edd_addtocart_button_fontface'];
														} else {
															$bdp_edd_addtocart_button_fontface = '';
														}
														?>
														<div class="typo-field">
															<input type="hidden" id="bdp_edd_addtocart_button_fontface_font_type" name="bdp_edd_addtocart_button_fontface_font_type" value="<?php echo isset( $bdp_settings['bdp_edd_addtocart_button_fontface_font_type'] ) ? esc_attr( $bdp_settings['bdp_edd_addtocart_button_fontface_font_type'] ) : ''; ?>">
															<div class="select-cover">
																<select name="bdp_edd_addtocart_button_fontface" id="bdp_edd_addtocart_button_fontface">
																	<option value=""><?php esc_html_e( 'Select Font Family', 'blog-designer-pro' ); ?></option>
																	<?php
																	$old_version = '';
																	$cnt         = 0;
																	foreach ( $font_family as $key => $value ) {
																		if ( $value['version'] != $old_version ) {
																			if ( $cnt > 0 ) {
																				echo '</optgroup>';
																			}
																			echo '<optgroup label="' . esc_attr( $value['version'] ) . '">';
																			$old_version = $value['version'];
																		}
																		echo "<option value='" . esc_attr( str_replace( '"', '', $value['label'] ) ) . "'";
																		if ( '' != $bdp_edd_addtocart_button_fontface && ( str_replace( '"', '', $bdp_edd_addtocart_button_fontface ) == str_replace( '"', '', $value['label'] ) ) ) {
																			echo ' selected';
																		}
																		echo '>' . esc_html( $value['label'] ) . '</option>';
																		++$cnt;
																	}
																	if ( count( $font_family ) == $cnt ) {
																		echo '</optgroup>';
																	}
																	?>
																</select>
															</div>
														</div>
													</div>
												</div>
												<div class="bdp-typography-cover">
													<div class="bdp-typography-label">
														<span class="bdp-key-title">
															<?php esc_html_e( 'Font Size', 'blog-designer-pro' ); ?>
														</span>
														<span class="bdp-px-prefix"><?php echo ' (px)'; ?></span>
													</div>
													<div class="bdp-typography-content">
														<?php
														if ( isset( $bdp_settings['bdp_edd_addtocart_button_fontsize'] ) && '' != $bdp_settings['bdp_edd_addtocart_button_fontsize'] ) {
															$bdp_edd_addtocart_button_fontsize = $bdp_settings['bdp_edd_addtocart_button_fontsize'];
														} else {
															$bdp_edd_addtocart_button_fontsize = 14;
														}
														?>
														<input type="number" name="bdp_edd_addtocart_button_fontsize" id="bdp_edd_addtocart_button_fontsize" value="<?php echo esc_attr( $bdp_edd_addtocart_button_fontsize ); ?>" onkeypress="return isNumberKey(event)" />
													</div>
												</div>

												<div class="bdp-typography-cover">
													<div class="bdp-typography-label">
														<span class="bdp-key-title">
															<?php esc_html_e( 'Font Weight', 'blog-designer-pro' ); ?>
														</span>
													</div>
													<div class="bdp-typography-content">
														<?php $bdp_edd_addtocart_button_font_weight = isset( $bdp_settings['bdp_edd_addtocart_button_font_weight'] ) ? $bdp_settings['bdp_edd_addtocart_button_font_weight'] : 'normal'; ?>
														<div class="select-cover">
															<select name="bdp_edd_addtocart_button_font_weight" id="bdp_edd_addtocart_button_font_weight">
																<option value="100" <?php selected( $bdp_edd_addtocart_button_font_weight, 100 ); ?>>100</option>
																<option value="200" <?php selected( $bdp_edd_addtocart_button_font_weight, 200 ); ?>>200</option>
																<option value="300" <?php selected( $bdp_edd_addtocart_button_font_weight, 300 ); ?>>300</option>
																<option value="400" <?php selected( $bdp_edd_addtocart_button_font_weight, 400 ); ?>>400</option>
																<option value="500" <?php selected( $bdp_edd_addtocart_button_font_weight, 500 ); ?>>500</option>
																<option value="600" <?php selected( $bdp_edd_addtocart_button_font_weight, 600 ); ?>>600</option>
																<option value="700" <?php selected( $bdp_edd_addtocart_button_font_weight, 700 ); ?>>700</option>
																<option value="800" <?php selected( $bdp_edd_addtocart_button_font_weight, 800 ); ?>>800</option>
																<option value="900" <?php selected( $bdp_edd_addtocart_button_font_weight, 900 ); ?>>900</option>
																<option value="bold" <?php selected( $bdp_edd_addtocart_button_font_weight, 'bold' ); ?> ><?php esc_html_e( 'Bold', 'blog-designer-pro' ); ?></option>
																<option value="normal" <?php selected( $bdp_edd_addtocart_button_font_weight, 'normal' ); ?>><?php esc_html_e( 'Normal', 'blog-designer-pro' ); ?></option>
															</select>
														</div>
													</div>
												</div>

												<div class="bdp-typography-cover">
													<div class="bdp-typography-label">
														<span class="bdp-key-title">
															<?php esc_html_e( 'Line Height', 'blog-designer-pro' ); ?>
														</span>
													</div>
													<div class="bdp-typography-content">
														<?php
															$display_edd_addtocart_button_line_height = '1.5';
														if ( isset( $bdp_settings['display_edd_addtocart_button_line_height'] ) ) {
															$display_edd_addtocart_button_line_height = $bdp_settings['display_edd_addtocart_button_line_height'];
														}
														?>
														<input type="number" id="display_edd_addtocart_button_line_height" name="display_edd_addtocart_button_line_height" step="1" min="1.5" value="<?php echo esc_attr( $display_edd_addtocart_button_line_height ); ?>" placeholder="<?php esc_attr_e( 'Enter line height', 'blog-designer-pro' ); ?>" onkeypress="return isNumberKey(event)">
													</div>
												</div>
												<div class="bdp-typography-cover">
												<div class="bdp-typography-label">
													<span class="bdp-key-title">
														<?php esc_html_e( 'Italic Font Style', 'blog-designer-pro' ); ?>
													</span><?php $bdp_edd_addtocart_button_font_italic = isset( $bdp_settings['bdp_edd_addtocart_button_font_italic'] ) ? $bdp_settings['bdp_edd_addtocart_button_font_italic'] : '0'; ?>
												</div>
												<div class="bdp-typography-content">
													<fieldset class="bdp-social-options bdp-display_author buttonset">
														<input id="bdp_edd_addtocart_button_font_italic_1" name="bdp_edd_addtocart_button_font_italic" type="radio" value="1"  <?php checked( 1, $bdp_edd_addtocart_button_font_italic ); ?> />
														<label for="bdp_edd_addtocart_button_font_italic_1"><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
														<input id="bdp_edd_addtocart_button_font_italic_0" name="bdp_edd_addtocart_button_font_italic" type="radio" value="0" <?php checked( 0, $bdp_edd_addtocart_button_font_italic ); ?> />
														<label for="bdp_edd_addtocart_button_font_italic_0"><?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
													</fieldset>
												</div>
											</div>
											<div class="bdp-typography-cover">
												<div class="bdp-typography-label">
													<span class="bdp-key-title">
														<?php esc_html_e( 'Text Transform', 'blog-designer-pro' ); ?>
													</span>
												</div>
												<div class="bdp-typography-content">
													<?php $bdp_edd_addtocart_button_font_text_transform = isset( $bdp_settings['bdp_edd_addtocart_button_font_text_transform'] ) ? $bdp_settings['bdp_edd_addtocart_button_font_text_transform'] : 'none'; ?>
													<div class="select-cover">
														<select name="bdp_edd_addtocart_button_font_text_transform" id="bdp_edd_addtocart_button_font_text_transform">
															<option <?php selected( $bdp_edd_addtocart_button_font_text_transform, 'none' ); ?> value="none"><?php esc_html_e( 'None', 'blog-designer-pro' ); ?></option>
															<option <?php selected( $bdp_edd_addtocart_button_font_text_transform, 'capitalize' ); ?> value="capitalize"><?php esc_html_e( 'Capitalize', 'blog-designer-pro' ); ?></option>
															<option <?php selected( $bdp_edd_addtocart_button_font_text_transform, 'uppercase' ); ?> value="uppercase"><?php esc_html_e( 'Uppercase', 'blog-designer-pro' ); ?></option>
															<option <?php selected( $bdp_edd_addtocart_button_font_text_transform, 'lowercase' ); ?> value="lowercase"><?php esc_html_e( 'Lowercase', 'blog-designer-pro' ); ?></option>
															<option <?php selected( $bdp_edd_addtocart_button_font_text_transform, 'full-width' ); ?> value="full-width"><?php esc_html_e( 'Full Width', 'blog-designer-pro' ); ?></option>
														</select>
													</div>
												</div>
											</div>
											<div class="bdp-typography-cover">
												<div class="bdp-typography-label">
													<span class="bdp-key-title">
														<?php esc_html_e( 'Text Decoration', 'blog-designer-pro' ); ?>
													</span>
												</div>
												<div class="bdp-typography-content">
													<?php $bdp_edd_addtocart_button_font_text_decoration = isset( $bdp_settings['bdp_edd_addtocart_button_font_text_decoration'] ) ? esc_attr( $bdp_settings['bdp_edd_addtocart_button_font_text_decoration'] ) : 'none'; ?>
													<div class="select-cover">
														<select name="bdp_edd_addtocart_button_font_text_decoration" id="bdp_edd_addtocart_button_font_text_decoration">
															<option <?php selected( $bdp_edd_addtocart_button_font_text_decoration, 'none' ); ?> value="none"><?php esc_html_e( 'None', 'blog-designer-pro' ); ?></option>
															<option <?php selected( $bdp_edd_addtocart_button_font_text_decoration, 'underline' ); ?> value="underline"><?php esc_html_e( 'Underline', 'blog-designer-pro' ); ?></option>
															<option <?php selected( $bdp_edd_addtocart_button_font_text_decoration, 'overline' ); ?> value="overline"><?php esc_html_e( 'Overline', 'blog-designer-pro' ); ?></option>
															<option <?php selected( $bdp_edd_addtocart_button_font_text_decoration, 'line-through' ); ?> value="line-through"><?php esc_html_e( 'Line Through', 'blog-designer-pro' ); ?></option>
														</select>
													</div>
												</div>
											</div>
												<div class="bdp-typography-cover">
													<div class="bdp-typography-label">
														<span class="bdp-key-title">
															<?php esc_html_e( 'Letter Spacing', 'blog-designer-pro' ); ?>
														</span>
														<span class="bdp-px-prefix"><?php echo ' (px)'; ?></span>
													</div>
													<div class="bdp-typography-content">
														<input type="number" name="bdp_edd_addtocart_button_letter_spacing" id="bdp_edd_addtocart_button_letter_spacing" step="1" min="0" value="<?php echo isset( $bdp_settings['bdp_edd_addtocart_button_letter_spacing'] ) ? esc_attr( $bdp_settings['bdp_edd_addtocart_button_letter_spacing'] ) : '0'; ?>" onkeypress="return isNumberKey(event)">
													</div>
												</div>
											</div>
										</li>
									</ul>
								</li>
						</ul>
					</div>
				</div>
				<div id="bdpsinglepostnavigation" class="postbox postbox-with-fw-options bdp-post-navigation-setting" style=<?php echo esc_attr( $bdpsinglepostnavigation_class_show ); ?>>
					<div class="inside">
						<ul class="bdp-settings bdp-lineheight">
							<div class="bdp_grid_col_2">
								<li>
									<div class="bdp-left">
										<span class="bdp-key-title" style="line-height:1.5">
											<?php esc_html_e( 'Display Product Next/Previous Navigation', 'blog-designer-pro' ); ?>&nbsp;
										</span>
									</div>
									<div class="bdp-right">
										<?php $display_navigation = isset( $bdp_settings['display_navigation'] ) ? $bdp_settings['display_navigation'] : 0; ?>
										<fieldset class="bdp-social-options bdp-display_comment buttonset buttonset-hide ui-buttonset" data-hide="1">
											<input id="display_navigation_1" name="display_navigation" type="radio" value="1" <?php checked( 1, $display_navigation ); ?> />
											<label for="display_navigation_1" <?php checked( 1, $display_navigation ); ?>><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
											<input id="display_navigation_0" name="display_navigation" type="radio" value="0" <?php checked( 0, $display_navigation ); ?> />
											<label for="display_navigation_0" <?php checked( 0, $display_navigation ); ?>><?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
										</fieldset>
									</div>
								</li>
								<li class="post-navigation-blocks">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Apply Filter on Product Navigation', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<?php $bdp_post_navigation_filter = isset( $bdp_settings['bdp_post_navigation_filter'] ) ? $bdp_settings['bdp_post_navigation_filter'] : ''; ?>
										<select name="bdp_post_navigation_filter" id="bdp_post_navigation_filter">
											<option value=""><?php esc_html_e( 'Default', 'blog-designer-pro' ); ?></option>
											<option <?php selected( $bdp_post_navigation_filter, 'download_category' ); ?> value="download_category"><?php esc_html_e( 'Category', 'blog-designer-pro' ); ?></option>
											<option <?php selected( $bdp_post_navigation_filter, 'download_tag' ); ?> value="download_tag"><?php esc_html_e( 'Tag', 'blog-designer-pro' ); ?></option>
										</select>
									</div>
								</li>
							</div>
							<div class="bdp_grid_col_3">
								<li class="post-navigation-blocks">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Display Product Title', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<?php $display_pn_title = ( isset( $bdp_settings['display_pn_title'] ) ) ? $bdp_settings['display_pn_title'] : 1; ?>
										<fieldset class="buttonset buttonset-hide ui-buttonset" data-hide="1">
											<input id="display_pn_title_1" name="display_pn_title" type="radio" value="1" <?php checked( 1, $display_pn_title ); ?> />
											<label for="display_pn_title_1" <?php checked( 1, $display_pn_title ); ?>><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
											<input id="display_pn_title_0" name="display_pn_title" type="radio" value="0" <?php checked( 0, $display_pn_title ); ?> />
											<label for="display_pn_title_0" <?php checked( 0, $display_pn_title ); ?>><?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
										</fieldset>
										<div class="bdp-setting-description bdp-note">
											<b class="note"><?php esc_html_e( 'Note', 'blog-designer-pro' ); ?>:</b>
											<?php esc_html_e( 'Show Product title when option is "Yes" otherwise it will display "Previous Product" and "Next Product".', 'blog-designer-pro' ); ?>
										</div>
									</div>
								</li>
								<li class="post-navigation-blocks">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Display Product Feature Image', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<?php $display_pn_image = ( isset( $bdp_settings['display_pn_image'] ) ) ? $bdp_settings['display_pn_image'] : 1; ?>
										<fieldset class="buttonset buttonset-hide ui-buttonset" data-hide="1">
											<input id="display_pn_image_1" name="display_pn_image" type="radio" value="1" <?php checked( 1, $display_pn_image ); ?> />
											<label for="display_pn_image_1" <?php checked( 1, $display_pn_image ); ?>><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
											<input id="display_pn_image_0" name="display_pn_image" type="radio" value="0" <?php checked( 0, $display_pn_image ); ?> />
											<label for="display_pn_image_0" <?php checked( 0, $display_pn_image ); ?>><?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
										</fieldset>
									</div>
								</li>
								<li class="post-navigation-blocks">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Display Product Date', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<?php $display_pn_date = ( isset( $bdp_settings['display_pn_date'] ) ) ? $bdp_settings['display_pn_date'] : 1; ?>
										<fieldset class="buttonset buttonset-hide ui-buttonset" data-hide="1">
											<input id="display_pn_date_1" name="display_pn_date" type="radio" value="1" <?php checked( 1, $display_pn_date ); ?> />
											<label for="display_pn_date_1" <?php checked( 1, $display_pn_date ); ?>><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
											<input id="display_pn_date_0" name="display_pn_date" type="radio" value="0" <?php checked( 0, $display_pn_date ); ?> />
											<label for="display_pn_date_0" <?php checked( 0, $display_pn_date ); ?>><?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
										</fieldset>
									</div>
								</li>
							</div>
						</ul>
					</div>
				</div>
				<div id="bdpsinglepostauthor" class="postbox postbox-with-fw-options bdp-post-navigation-setting" style=<?php echo esc_attr( $bdpauthor_class_show ); ?>>
					<div class="inside">
						<ul class="bdp-settings bdp-lineheight">
							<div class="bdp_grid_col_2">
								<li>
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Display Author Data', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<?php
										if ( isset( $bdp_settings['display_author_data'] ) ) {
											$display_author_data = $bdp_settings['display_author_data'];
										} else {
											$display_author_data = 1;
										}
										?>
										<fieldset class="bdp-social-options bdp-display_author_data buttonset">
											<input id="display_author_data_1" name="display_author_data" type="radio" value="1"  <?php checked( 1, $display_author_data ); ?> />
											<label for="display_author_data_1"><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
											<input id="display_author_data_0" name="display_author_data" type="radio" value="0" <?php checked( 0, $display_author_data ); ?> />
											<label for="display_author_data_0"><?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
										</fieldset>
									</div>
								</li>
								<li class="display_author_biography_div">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Display Author Biography', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<?php
										if ( isset( $bdp_settings['display_author_biography'] ) ) {
											$display_author_biography = $bdp_settings['display_author_biography'];
										} else {
											$display_author_biography = 1;
										}
										?>
										<fieldset class="bdp-social-options bdp-display_author buttonset">
											<input id="display_author_biography_1" name="display_author_biography" type="radio" value="1"  <?php checked( 1, $display_author_biography ); ?> />
											<label for="display_author_biography_1"><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
											<input id="display_author_biography_0" name="display_author_biography" type="radio" value="0" <?php checked( 0, $display_author_biography ); ?> />
											<label for="display_author_biography_0"><?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
										</fieldset>
									</div>
								</li>
							</div>
							<div class="bdp_grid_col_3">
								<li class="display_author_biography_div">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Display Author Title', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<input type="text" id="txtAuthorTitle" name="txtAuthorTitle" value="<?php echo isset( $bdp_settings['txtAuthorTitle'] ) ? esc_attr( $bdp_settings['txtAuthorTitle'] ) : esc_html__( 'About', 'blog-designer-pro' ) . ' [author]'; ?>" placeholder="
										<?php
										esc_html_e( 'About', 'blog-designer-pro' );
										echo ' [author]';
										?>
										">
										<label class="disable_link bdp-link-disable">
											<input id="disable_link_author_div" name="disable_link_author_div" type="checkbox" value="1" 
											<?php
											if ( isset( $bdp_settings['disable_link_author_div'] ) ) {
												checked( 1, $bdp_settings['disable_link_author_div'] );
											}
											?>
											/>
											<?php esc_html_e( 'Disable Link for Author', 'blog-designer-pro' ); ?>
										</label>
										<div class="bdp-setting-description bdp-note">
											<b class="note"><?php esc_html_e( 'Note', 'blog-designer-pro' ); ?>: </b>
											<?php
											esc_html_e( 'Use', 'blog-designer-pro' );
											echo ' [author] ';
											esc_html_e( 'to display author name with link dynamically.', 'blog-designer-pro' );
											?>
										</div>
									</div>
								</li>
								<li class="display_author_biography_div">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Author Title Font Size', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<?php
										if ( isset( $bdp_settings['author_title_fontsize'] ) ) {
											$author_title_fontsize = $bdp_settings['author_title_fontsize'];
										} else {
											$author_title_fontsize = 16;
										}
										?>
										<input type="number" name="author_title_fontsize" id="author_title_fontsize" value="<?php echo esc_attr( $author_title_fontsize ); ?>" onkeypress="return isNumberKey(event)" />
									</div>
								</li>
								<li class="display_author_biography_div">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Author Title Font Family', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<div class="typo-field">
											<input type="hidden" name="author_title_fontface_font_type" id="author_title_fontface_font_type" value="<?php echo isset( $bdp_settings['author_title_fontface_font_type'] ) ? esc_attr( $bdp_settings['author_title_fontface_font_type'] ) : 'Serif Fonts'; ?>">
											<?php
											$author_title_fontface = '';
											if ( isset( $bdp_settings['author_title_fontface'] ) ) {
												$author_title_fontface = $bdp_settings['author_title_fontface'];
											}
											?>
											<select name="author_title_fontface" id="author_title_fontface">
												<option value=""><?php esc_html_e( 'Select Font Family', 'blog-designer-pro' ); ?></option>
												<?php
												$old_version = '';
												$cnt         = 0;
												foreach ( $font_family as $key => $value ) {
													if ( $value['version'] != $old_version ) {
														if ( $cnt > 0 ) {
															echo '</optgroup>';
														}
														echo '<optgroup label="' . esc_attr( $value['version'] ) . '">';
														$old_version = $value['version'];
													}
													echo "<option value='" . esc_attr( str_replace( '"', '', $value['label'] ) ) . "'";

													if ( '' != $author_title_fontface && ( str_replace( '"', '', $author_title_fontface ) == str_replace( '"', '', $value['label'] ) ) ) {
														echo ' selected';
													}
													echo '>' . esc_attr( $value['label'] ) . '</option>';
													++$cnt;
												}
												if ( count( $font_family ) == $cnt ) {
													echo '</optgroup>';
												}
												?>
											</select>
										</div>
									</div>
								</li>
							</div>
						</ul>
					</div>
				</div>
				<div id="bdpsinglerelated" class="postbox postbox-with-fw-options bdp-content-setting1" style=<?php echo esc_attr( $bdprelated_class_show ); ?>>
					<div class="inside">
						<ul class="bdp-settings bdp-lineheight">
							<div class="bdp_grid_col_2">
								<li class="bdp-related-lineheight">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Display Related Product On Single Page', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<?php $display_related_post = isset( $bdp_settings['display_related_post'] ) ? $bdp_settings['display_related_post'] : '0'; ?>
											<fieldset class="buttonset buttonset-hide" data-hide='1'>
												<input id="display_related_post_1" name="display_related_post" type="radio" value="1" <?php checked( 1, $display_related_post ); ?> />
												<label id="display_related_post" for="display_related_post_1" <?php checked( 1, $display_related_post ); ?>><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
												<input id="display_related_post_0" name="display_related_post" type="radio" value="0" <?php checked( 0, $display_related_post ); ?> />
												<label id="display_related_post" for="display_related_post_0" <?php checked( 0, $display_related_post ); ?>><?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
											</fieldset>
									</div>
								</li>
								<li class="related-post-positions">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Related Product Position', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<?php
										$bdp_display_related_post = 'bottom';
										if ( isset( $bdp_settings['bdp_display_related_post'] ) ) {
											$bdp_display_related_post = $bdp_settings['bdp_display_related_post'];
										}
										?>
										<select id="bdp_display_related_post" name="bdp_display_related_post">
											<option value="bottom" <?php echo selected( 'bottom', $bdp_display_related_post ); ?>><?php esc_html_e( 'Bottom Side', 'blog-designer-pro' ); ?></option>
											<option value="left" <?php echo selected( 'left', $bdp_display_related_post ); ?>><?php esc_html_e( 'Left Side', 'blog-designer-pro' ); ?></option>
											<option value="right" <?php echo selected( 'right', $bdp_display_related_post ); ?>><?php esc_html_e( 'Right Side', 'blog-designer-pro' ); ?></option>
										</select>
									</div>
								</li>
								<li>
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Display Number Of Column Related Posts', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<?php
										if ( isset( $bdp_settings['related_post_column'] ) ) {
											$bdp_settings['related_post_column'] = $bdp_settings['related_post_column'];
										} else {
											$bdp_settings['related_post_column'] = 3;
										}
										?>
										<select name="related_post_column" id="related_post_column">
											<option value="1" 
											<?php
											if ( '1' == $bdp_settings['related_post_column'] ) {
												?>
												selected="selected"<?php } ?>>1</option>
											<option value="2" 
											<?php
											if ( '2' === $bdp_settings['related_post_column'] ) {
												?>
												selected="selected"<?php } ?>>2</option>
											<option value="3" 
											<?php
											if ( '3' === $bdp_settings['related_post_column'] ) {
												?>
												selected="selected"<?php } ?>>3</option>
											<option value="4" 
											<?php
											if ( '4' === $bdp_settings['related_post_column'] ) {
												?>
												selected="selected"<?php } ?>>4</option>
										</select>
									</div>
								</li>
								<li class="bdp_single_custom_media_selection_related_post">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Select Product Media Size', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<select id="bdp_related_post_media_size" name="bdp_related_post_media_size">
											<option value="full" <?php echo ( isset( $bdp_settings['bdp_related_post_media_size'] ) && 'full' === $bdp_settings['bdp_related_post_media_size'] ) ? 'selected="selected"' : ''; ?> ><?php esc_html_e( 'Original Resolution', 'blog-designer-pro' ); ?></option>
											<?php
											global $_wp_additional_image_sizes;
											$thumb_sizes = array();
											$image_size  = get_intermediate_image_sizes();
											foreach ( $image_size as $s ) {
												$thumb_sizes [ $s ] = array( 0, 0 );
												if ( in_array( $s, array( 'thumbnail', 'medium', 'large' ) ) ) {
													?>
													<option value="<?php echo esc_attr( $s ); ?>" <?php echo ( isset( $bdp_settings['bdp_related_post_media_size'] ) && $bdp_settings['bdp_related_post_media_size'] == $s ) ? 'selected="selected"' : ''; ?>> <?php echo esc_html( $s ) . ' (' . esc_html( get_option( $s . '_size_w' ) ) . 'x' . esc_html( get_option( $s . '_size_h' ) ) . ')'; ?> </option>
													<?php
												} elseif ( isset( $_wp_additional_image_sizes ) && isset( $_wp_additional_image_sizes[ $s ] ) ) {
													?>
														<option value="<?php echo esc_attr( $s ); ?>" <?php echo ( isset( $bdp_settings['bdp_related_post_media_size'] ) && $bdp_settings['bdp_related_post_media_size'] == $s ) ? 'selected="selected"' : ''; ?>> <?php echo esc_html( $s ) . ' (' . esc_html( $_wp_additional_image_sizes[ $s ]['width'] ) . 'x' . esc_html( $_wp_additional_image_sizes[ $s ]['height'] ) . ')'; ?> </option>
														<?php

												}
											}
											?>
											<option value="custom" <?php echo ( isset( $bdp_settings['bdp_related_post_media_size'] ) && 'custom' === $bdp_settings['bdp_related_post_media_size'] ) ? 'selected="selected"' : ''; ?>><?php esc_html_e( 'Custom Size', 'blog-designer-pro' ); ?></option>
										</select>
									</div>
								</li>
								<li class="bdp_related_post_media_custom_size_tr">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Add Custom Size', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<div class="bdp_media_custom_size_tbl">
											<p> <span class="bdp_custom_media_size_title"><?php esc_html_e( 'Width', 'blog-designer-pro' ); ?> </span><span class="bdp-px-prefix"><?php echo ' (px)'; ?></span> <input type="number" onkeypress="return isNumberKey(event)" min="0" name="related_post_media_custom_width" class="media_custom_width" id="related_post_media_custom_width" value="<?php echo ( isset( $bdp_settings['related_post_media_custom_width'] ) && '' != $bdp_settings['related_post_media_custom_width'] ) ? esc_attr( $bdp_settings['related_post_media_custom_width'] ) : ''; ?>" /></p>
											<p> <span class="bdp_custom_media_size_title"><?php esc_html_e( 'Height', 'blog-designer-pro' ); ?> </span> <span class="bdp-px-prefix"><?php echo ' (px)'; ?></span><input type="number" onkeypress="return isNumberKey(event)" min="0" name="related_post_media_custom_height" class="media_custom_height" id="related_post_media_custom_height" value="<?php echo ( isset( $bdp_settings['related_post_media_custom_height'] ) && '' != $bdp_settings['related_post_media_custom_height'] ) ? esc_attr( $bdp_settings['related_post_media_custom_height'] ) : ''; ?>"/></p>
										</div>
									</div>
								</li>
								<li class="related_post_text">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Related Product Title', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<input type="text" name="related_post_title" id="related_post_title" value="
										<?php
										if ( isset( $bdp_settings['related_post_title'] ) ) {
											echo esc_attr( $bdp_settings['related_post_title'] ); }
										?>
										" placeholder="<?php esc_html_e( 'Enter Related Post Title', 'blog-designer-pro' ); ?>">
									</div>
								</li>
								<li class="bdp_related_font_size">
									<div class="bdp-left"><span class="bdp-key-title"><?php esc_html_e( 'Related Product Title Font Size', 'blog-designer-pro' ); ?></span><span class="bdp-px-prefix"><?php echo ' (px)'; ?></span></div>
									<div class="bdp-right">
										<?php
										if ( isset( $bdp_settings['related_title_fontsize'] ) ) {
											$related_title_fontsize = $bdp_settings['related_title_fontsize'];
										} else {
											$related_title_fontsize = 25;
										}
										?>
										<input type="number" name="related_title_fontsize" id="related_title_fontsize" value="<?php echo esc_attr( $related_title_fontsize ); ?>" onkeypress="return isNumberKey(event)" />
									</div>
								</li>
								<li>
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Related Product Title Font Family', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<div class="typo-field">
											<input type="hidden" name="related_title_fontface_font_type" id="related_title_fontface_font_type" value="<?php echo isset( $bdp_settings['related_title_fontface_font_type'] ) ? esc_attr( $bdp_settings['related_title_fontface_font_type'] ) : 'Serif Fonts'; ?>">
											<?php
											if ( isset( $bdp_settings['related_title_fontface'] ) ) {
												$related_title_fontface = $bdp_settings['related_title_fontface'];
											} else {
												$related_title_fontface = 'Georgia, serif';
											}
											?>
											<select name="related_title_fontface" id="related_title_fontface">
												<option value=""><?php esc_html_e( 'Select Font Family', 'blog-designer-pro' ); ?></option>
												<?php
												$old_version = '';
												$cnt         = 0;
												foreach ( $font_family as $key => $value ) {
													if ( $value['version'] != $old_version ) {
														if ( $cnt > 0 ) {
															echo '</optgroup>';
														}
														echo '<optgroup label="' . esc_attr( $value['version'] ) . '">';
														$old_version = $value['version'];
													}
													echo "<option value='" . esc_attr( str_replace( '"', '', $value['label'] ) ) . "'";

													if ( '' != $related_title_fontface && ( str_replace( '"', '', $related_title_fontface ) == str_replace( '"', '', $value['label'] ) ) ) {
														echo ' selected';
													}
													echo '>' . esc_attr( $value['label'] ) . '</option>';
													++$cnt;
												}
												if ( count( $font_family ) == $cnt ) {
													echo '</optgroup>';
												}
												?>
											</select>
										</div>
									</div>
								</li>
								<li>
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Related Product Title Color', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<input type="text" name="related_title_color" id="related_title_color" value="<?php echo isset( $bdp_settings['related_title_color'] ) ? esc_attr( $bdp_settings['related_title_color'] ) : '#333333'; ?>"/>
									</div>
								</li>
								<li>
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Show Related Products By', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<?php
										$related_post_by = isset( $bdp_settings['related_post_by'] ) ? $bdp_settings['related_post_by'] : '';
										?>
										<select name="related_post_by" id="related_post_by">
											<option selected="" value="category" 
											<?php
											if ( 'category' === $related_post_by ) {
												?>
												selected="selected"<?php } ?>>
												<?php esc_html_e( 'Category', 'blog-designer-pro' ); ?>
											</option>
											<option value="tag" 
											<?php
											if ( 'tag' === $related_post_by ) {
												?>
												selected="selected"<?php } ?>>
												<?php esc_html_e( 'Tag', 'blog-designer-pro' ); ?>
											</option>
										</select>
									</div>
								</li>
								<li>
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Number Of Related Products', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<?php
										if ( isset( $bdp_settings['related_post_number'] ) ) {
											$bdp_settings['related_post_number'] = $bdp_settings['related_post_number'];
										} else {
											$bdp_settings['related_post_number'] = 3;
										}
										?>
										<select name="related_post_number" id="related_post_number">
											<option selected="" value="2" 
											<?php
											if ( '2' === $bdp_settings['related_post_number'] ) {
												?>
												selected="selected"<?php } ?>>2</option>
											<option value="3" 
											<?php
											if ( '3' === $bdp_settings['related_post_number'] ) {
												?>
												selected="selected"<?php } ?>>3</option>
											<option value="4" 
											<?php
											if ( '4' === $bdp_settings['related_post_number'] ) {
												?>
												selected="selected"<?php } ?>>4</option>
										</select>
									</div>
								</li>
							</div>
							<div class="bdp_grid_col_3">
								<li>
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Related Products Order By', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<?php
										$related_orderby = '';
										if ( isset( $bdp_settings['bdp_related_post_order_by'] ) ) {
											$related_orderby = $bdp_settings['bdp_related_post_order_by'];
										}
										?>
										<select id="bdp_related_post_order_by" name="bdp_related_post_order_by">
											<option value="" <?php echo selected( '', $related_orderby ); ?>><?php esc_html_e( 'Default Sorting', 'blog-designer-pro' ); ?></option>
											<option value="rand" <?php echo selected( 'rand', $related_orderby ); ?>><?php esc_html_e( 'Random', 'blog-designer-pro' ); ?></option>
											<option value="ID" <?php echo selected( 'ID', $related_orderby ); ?>><?php esc_html_e( 'Product ID', 'blog-designer-pro' ); ?></option>
											<option value="author" <?php echo selected( 'author', $related_orderby ); ?>><?php esc_html_e( 'Author', 'blog-designer-pro' ); ?></option>
											<option value="title" <?php echo selected( 'title', $related_orderby ); ?>><?php esc_html_e( 'Product Title', 'blog-designer-pro' ); ?></option>
											<option value="name" <?php echo selected( 'name', $related_orderby ); ?>><?php esc_html_e( 'Product Slug', 'blog-designer-pro' ); ?></option>
											<option value="date" <?php echo selected( 'date', $related_orderby ); ?>><?php esc_html_e( 'Publish Date', 'blog-designer-pro' ); ?></option>
											<option value="modified" <?php echo selected( 'modified', $related_orderby ); ?>><?php esc_html_e( 'Modified Date', 'blog-designer-pro' ); ?></option>
											<option value="meta_value_num" <?php echo selected( 'meta_value_num', $related_orderby ); ?>><?php esc_html_e( 'Product Likes', 'blog-designer-pro' ); ?></option>
										</select>
											<div class="blg_order">
											<?php
											$related_post_order = 'DESC';
											if ( isset( $bdp_settings['bdp_related_post_order'] ) ) {
												$related_post_order = $bdp_settings['bdp_related_post_order'];
											}
											?>
											<fieldset class="buttonset green" data-hide='1'>
												<input id="bdp_related_post_asc" name="bdp_related_post_order" type="radio" value="ASC" <?php checked( 'ASC', $related_post_order ); ?> />
												<label id="bdp-options-button" for="bdp_related_post_asc"><?php esc_html_e( 'Ascending', 'blog-designer-pro' ); ?></label>
												<input id="bdp_related_post_desc" name="bdp_related_post_order" type="radio" value="DESC" <?php checked( 'DESC', $related_post_order ); ?> />
												<label id="bdp-options-button" for="bdp_related_post_desc"><?php esc_html_e( 'Descending', 'blog-designer-pro' ); ?></label>
											</fieldset>
										</div>
									</div>
								</li>
								<li>
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Show Content From', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<?php $template_post_content_from = isset( $bdp_settings['related_post_content_from'] ) ? $bdp_settings['related_post_content_from'] : 'from_content'; ?>
										<select name="related_post_content_from" id="related_post_content_from">
											<option value="from_content" <?php selected( $template_post_content_from, 'from_content' ); ?> ><?php esc_html_e( 'Product Content', 'blog-designer-pro' ); ?></option>
											<option value="from_description" <?php selected( $template_post_content_from, 'from_description' ); ?>><?php esc_html_e( 'Product Description', 'blog-designer-pro' ); ?></option>
										</select>
										<div class="bdp-setting-description bdp-note">
											<b class="note"><?php esc_html_e( 'Note', 'blog-designer-pro' ); ?>:</b> &nbsp;
											<?php esc_html_e( 'If Product Description is empty then Content will get automatically from Product Content.', 'blog-designer-pro' ); ?>
										</div>
									</div>
								</li>
								<li class="bdp_related_font_size">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Product Content Length (words)', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<input class="bdp-content-lenth" type="number" id="related_post_content_length" name="related_post_content_length" step="1" min="0" value="<?php echo isset( $bdp_settings['related_post_content_length'] ) ? esc_attr( $bdp_settings['related_post_content_length'] ) : ''; ?>" placeholder="<?php esc_html_e( 'Enter Content length', 'blog-designer-pro' ); ?>" onkeypress="return isNumberKey(event)">
										<div class="bdp-setting-description bdp-note">
											<b class="note"><?php esc_html_e( 'Note', 'blog-designer-pro' ); ?>: </b>
											<?php esc_html_e( 'Leave it blank if you want to hide content in related product.', 'blog-designer-pro' ); ?>
										</div>
									</div>
								</li>
								<li class="">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Display Product Price', 'blog-designer-pro' ); ?>
										</span>

									</div>
									<div class="bdp-right">
										<?php $related_download_price = ( isset( $bdp_settings['related_download_price'] ) ) ? $bdp_settings['related_download_price'] : 0; ?>
										<fieldset class="buttonset related_download_price">
											<input id="related_download_price_1" name="related_download_price" type="radio" value="1" <?php checked( 1, $related_download_price ); ?> />
											<label for="related_download_price_1" <?php checked( 1, $related_download_price ); ?>><?php esc_html_e( 'Enable', 'blog-designer-pro' ); ?></label>
											<input id="related_download_price_0" name="related_download_price" type="radio" value="0" <?php checked( 0, $related_download_price ); ?> />
											<label for="related_download_price_0" <?php checked( 0, $related_download_price ); ?>><?php esc_html_e( 'Disable', 'blog-designer-pro' ); ?></label>
										</fieldset>
									</div>
								</li>
								<li class="content-firstletter-tr">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Display Purchase Button', 'blog-designer-pro' ); ?>
										</span>

									</div>
									<div class="bdp-right">
										<?php $related_download_cart_button = ( isset( $bdp_settings['related_download_cart_button'] ) ) ? $bdp_settings['related_download_cart_button'] : 0; ?>
										<fieldset class="buttonset related_download_cart_button">
											<input id="related_download_cart_button_1" name="related_download_cart_button" type="radio" value="1" <?php checked( 1, $related_download_cart_button ); ?> />
											<label for="related_download_cart_button_1" <?php checked( 1, $related_download_cart_button ); ?>><?php esc_html_e( 'Enable', 'blog-designer-pro' ); ?></label>
											<input id="related_download_cart_button_0" name="related_download_cart_button" type="radio" value="0" <?php checked( 0, $related_download_cart_button ); ?> />
											<label for="related_download_cart_button_0" <?php checked( 0, $related_download_cart_button ); ?>><?php esc_html_e( 'Disable', 'blog-designer-pro' ); ?></label>
										</fieldset>
									</div>
								</li>
								<li>
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Display Related Product Content', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<?php
										$display_related_postcontent = 'bottom';
										if ( isset( $bdp_settings['bdp_display_related_postcontent'] ) ) {
											$display_related_postcontent = $bdp_settings['bdp_display_related_postcontent'];
										}
										?>
										<select id="bdp_display_related_postcontent" name="bdp_display_related_postcontent">
											<option value="bottom" <?php echo selected( 'bottom', $display_related_postcontent ); ?>><?php esc_html_e( 'Bottom Side', 'blog-designer-pro' ); ?></option>
											<option value="left" <?php echo selected( 'left', $display_related_postcontent ); ?>><?php esc_html_e( 'Left Side', 'blog-designer-pro' ); ?></option>
											<option value="right" <?php echo selected( 'right', $display_related_postcontent ); ?>><?php esc_html_e( 'Right Side', 'blog-designer-pro' ); ?></option>
											<option value="top" <?php echo selected( 'top', $display_related_postcontent ); ?>><?php esc_html_e( 'Top Side', 'blog-designer-pro' ); ?></option>
										</select>
									</div>
								</li>
								<li>
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Display Related Post Post Date', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<?php
										if ( isset( $bdp_settings['display_related_post_date'] ) ) {
											$display_related_post_date = $bdp_settings['display_related_post_date'];
										} else {
											$display_related_post_date = 0;
										}
										?>
										<fieldset class="bdp-social-options bdp-display_author buttonset">
											<input id="display_related_post_date_1" name="display_related_post_date" type="radio" value="1"  <?php checked( 1, $display_related_post_date ); ?> />
											<label for="display_related_post_date_1"><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
											<input id="display_related_post_date_0" name="display_related_post_date" type="radio" value="0" <?php checked( 0, $display_related_post_date ); ?> />
											<label for="display_related_post_date_0"><?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
										</fieldset>
									</div>
								</li>
							</div>
						</ul>
					</div>
				</div>
				<?php if ( is_plugin_active( 'advanced-custom-fields/acf.php' ) ) { ?>
				<div id="bdpacffieldssetting" class="postbox postbox-with-fw-options" style=<?php echo esc_attr( $bdpacffieldssetting_class_show ); ?>>
					<div class="inside">
						<ul class="bdp-settings bdp-lineheight">
							<div class="bdp_grid_col_2">
								<li>
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Display ACF Field', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<?php
										$display_acf_field = '0';
										if ( isset( $bdp_settings['display_acf_field'] ) ) {
											$display_acf_field = $bdp_settings['display_acf_field'];
										}
										?>
										<fieldset class="bdp-social-style buttonset buttonset-hide" data-hide='1'>
											<input id="display_acf_field_1" name="display_acf_field" type="radio" value="1" <?php checked( 1, $display_acf_field ); ?> />
											<label id="bdp-options-button" for="display_acf_field_1" <?php checked( 1, $display_acf_field ); ?>><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
											<input id="display_acf_field_0" name="display_acf_field" type="radio" value="0" <?php checked( 0, $display_acf_field ); ?> />
											<label id="bdp-options-button" for="display_acf_field_0" <?php checked( 1, $display_acf_field ); ?>><?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
										</fieldset>
									</div>
								</li>
								<li class="bdp_setting_acf_field">
									<?php
									$post_id = get_posts(
										array(
											'fields' => 'ids',
											'posts_per_page' => -1,
										)
									);
									$groups  = acf_get_field_groups(
										array(
											'post_id'   => $post_id,
											'post_type' => 'download',
										)
									);
									if ( ! empty( $groups ) ) {
										?>
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Select ACF Field', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<?php
										$bdp_acf_field = isset( $bdp_settings['bdp_acf_field'] ) ? $bdp_settings['bdp_acf_field'] : array();

										?>
										<select data-placeholder="<?php esc_attr_e( 'Choose acf field', 'blog-designer-pro' ); ?>" class="chosen-select" multiple style="width:220px;" name="bdp_acf_field[]" id="bdp_acf_field">
											<?php
											foreach ( $groups as $group ) {
												$group_id                                 = $group['ID'];
												$group_title                              = $group['title'];
												$all_acf_data[ $group_id ]                = array();
												$all_acf_data[ $group_id ]['group_id']    = $group_id;
												$all_acf_data[ $group_id ]['group_title'] = $group_title;
												$fields                                   = acf_get_fields( $group_id );
												if ( $fields ) {
													$all_acf_data[ $group_id ]['fields'] = array();
													$val_fields                          = 0;
													foreach ( $fields as $field ) {
														$field_id    = $field['ID'];
														$field_label = $field['label'];
														$field_key   = $field['key'];
														?>
														<option value="<?php echo esc_attr( $field_id ); ?>" 
															<?php
															if ( @is_array( $bdp_acf_field ) && in_array( $field_id, $bdp_acf_field ) ) {
																echo 'selected="selected"';
															}
															?>
														><?php echo esc_html( $field_label ); ?></option>
														<?php
													}
												}
											}
											?>
										</select>
									</div>
									<?php } ?>
								</li>
							</div>
						</ul>
					</div>
				</div>
				<?php } ?>
				<div id="bdpsinglesocial" class="postbox postbox-with-fw-options" style=<?php echo esc_attr( $bdpsocial_class_show ); ?>>
					<div class="inside">
						<ul class="bdp-settings bdp-lineheight">
							<div class="bdp_grid_col_2">
								<li>
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Social Share', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<?php $social_share = isset( $bdp_settings['social_share'] ) ? $bdp_settings['social_share'] : 1; ?>
										<fieldset class="bdp-social-options buttonset buttonset-hide" data-hide='1'>
											<input id="social_share_1" name="social_share" type="radio" value="1" <?php checked( 1, $social_share ); ?> />
											<label id="" for="social_share_1" <?php checked( 1, $social_share ); ?>><?php esc_html_e( 'Enable', 'blog-designer-pro' ); ?></label>
											<input id="social_share_0" name="social_share" type="radio" value="0" <?php checked( 0, $social_share ); ?> />
											<label id="" for="social_share_0" <?php checked( 0, $social_share ); ?>> <?php esc_html_e( 'Disable', 'blog-designer-pro' ); ?></label>
										</fieldset>
									</div>
								</li>

								<li class ="social_share_options">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Social Share Style', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<?php
										$social_style = '1';
										if ( isset( $bdp_settings['social_style'] ) ) {
											$social_style = $bdp_settings['social_style'];
										}
										?>
										<fieldset class="bdp-social-style buttonset buttonset-hide green" data-hide='1'>
											<input id="social_style_0" name="social_style" type="radio" value="0" <?php checked( 0, $social_style ); ?> />
											<label id="bdp-options-button" for="social_style_0" <?php checked( 0, $social_style ); ?>><?php esc_html_e( 'Default', 'blog-designer-pro' ); ?></label>
											<input id="social_style_1" name="social_style" type="radio" value="1" <?php checked( 1, $social_style ); ?> />
											<label id="bdp-options-button" for="social_style_1" <?php checked( 1, $social_style ); ?>><?php esc_html_e( 'Custom', 'blog-designer-pro' ); ?></label>
										</fieldset>
									</div>
								</li>
								<li class ="social_share_options shape_social_icon">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Shape of Social Icon', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<?php
										$social_icon_style = isset( $bdp_settings['social_icon_style'] ) ? $bdp_settings['social_icon_style'] : 1;
										?>
										<fieldset class="bdp-social-shape buttonset buttonset-hide green" data-hide='1'>
											<input id="social_icon_style_0" name="social_icon_style" type="radio" value="0" nhp-opts-button-hide-below <?php checked( 0, $social_icon_style ); ?> />
											<label id="bdp-options-button" for="social_icon_style_0" <?php checked( 0, $social_icon_style ); ?>><?php esc_html_e( 'Circle', 'blog-designer-pro' ); ?></label>
											<input id="social_icon_style_1" name="social_icon_style" type="radio" value="1" nhp-opts-button-hide-below <?php checked( 1, $social_icon_style ); ?> />
											<label id="bdp-options-button" for="social_icon_style_1" <?php checked( 1, $social_icon_style ); ?>><?php esc_html_e( 'Square', 'blog-designer-pro' ); ?></label>
										</fieldset>
									</div>
								</li>
								<li class ="social_share_options size_social_icon">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Size of Social Icon', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<?php
										$social_icon_size = isset( $bdp_settings['social_icon_size'] ) ? $bdp_settings['social_icon_size'] : '0';
										?>
										<fieldset class="bdp-social-size buttonset buttonset-hide green bdp-social-icon-size" data-hide='1'>
											<input id="social_icon_size_2" name="social_icon_size" type="radio" value="2" <?php checked( 2, $social_icon_size ); ?> />
											<label id="bdp-options-button" for="social_icon_size_2" <?php checked( 2, $social_icon_size ); ?>><?php esc_html_e( 'Extra Small', 'blog-designer-pro' ); ?></label>
											<input id="social_icon_size_1" name="social_icon_size" type="radio" value="1" <?php checked( 1, $social_icon_size ); ?> />
											<label id="bdp-options-button" for="social_icon_size_1" <?php checked( 1, $social_icon_size ); ?>><?php esc_html_e( 'Small', 'blog-designer-pro' ); ?></label>
											<input id="social_icon_size_0" name="social_icon_size" type="radio" value="0" <?php checked( 0, $social_icon_size ); ?> />
											<label id="bdp-options-button" for="social_icon_size_0" <?php checked( 0, $social_icon_size ); ?>><?php esc_html_e( 'Large', 'blog-designer-pro' ); ?></label>
										</fieldset>
									</div>
								</li>
							</div>
							<li class ="social_share_options default_icon_layouts">
								<div class="bdp-left">
									<span class="bdp-key-title">
										<?php esc_html_e( 'Available Icon Themes', 'blog-designer-pro' ); ?>
									</span>
								</div>
								<div class="bdp-right">
									<?php
									$default_icon_theme = 1;
									if ( isset( $bdp_settings['default_icon_theme'] ) ) {
										$default_icon_theme = $bdp_settings['default_icon_theme'];
									}
									?>
									<div class="social-share-theme">
										<?php
										for ( $i = 1; $i <= 10; $i++ ) {
											?>
											<div class="social-cover social_share_theme_<?php echo esc_attr( $i ); ?>">
												<label><input type="radio" id="default_icon_theme_<?php echo esc_attr( $i ); ?>" value="<?php echo esc_attr( $i ); ?>" name="default_icon_theme" <?php checked( $i, $default_icon_theme ); ?> />
													<span class="bdp-social-icons facebook-icon bdp_theme_wrapper"></span>
													<span class="bdp-social-icons twitter-icon bdp_theme_wrapper"></span>
													<span class="bdp-social-icons linkdin-icon bdp_theme_wrapper"></span>
													<span class="bdp-social-icons pin-icon bdp_theme_wrapper"></span>
													<span class="bdp-social-icons whatsup-icon bdp_theme_wrapper"></span>
													<span class="bdp-social-icons telegram-icon bdp_theme_wrapper"></span>
													<span class="bdp-social-icons pocket-icon bdp_theme_wrapper"></span>
													<span class="bdp-social-icons mail-icon bdp_theme_wrapper"></span>
													<span class="bdp-social-icons reddit-icon bdp_theme_wrapper"></span>
													<span class="bdp-social-icons digg-icon bdp_theme_wrapper"></span>
													<span class="bdp-social-icons tumblr-icon bdp_theme_wrapper"></span>
													<span class="bdp-social-icons skype-icon bdp_theme_wrapper"></span>
													<span class="bdp-social-icons wordpress-icon bdp_theme_wrapper"></span>
												</label>
											</div>
											<?php
										}
										?>
									</div>
								</div>
							</li>
							<h3 class="bdp-table-title social_share_options bdp-display-settings bdp-social-share-options">Social Share Links Settings</h3>
							<li class ="social_share_options bdp-display-settings bdp-social-share-options">
								<div class="bdp-typography-wrapper bdp-social-share-link bdp_grid_col_4">
									<div class="bdp-typography-cover">
										<div class="bdp-typography-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Facebook Share Link', 'blog-designer-pro' ); ?>
											</span>
										</div>
										<div class="bdp-typography-content">
											<?php
											$facebook_link = isset( $bdp_settings['facebook_link'] ) ? $bdp_settings['facebook_link'] : 1;
											?>
											<fieldset class="bdp-social-options bdp-facebook_link buttonset buttonset-hide" data-hide='1'>
												<input id="facebook_link_1" name="facebook_link" type="radio" value="1" <?php checked( 1, $facebook_link ); ?> />
												<label id=""for="facebook_link_1" <?php checked( 1, $facebook_link ); ?>><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
												<input id="facebook_link_0" name="facebook_link" type="radio" value="0" <?php checked( 0, $facebook_link ); ?> />
												<label id="" for="facebook_link_0" <?php checked( 0, $facebook_link ); ?>> <?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
											</fieldset>
											<label class="social_link_with_count">
												<input id="facebook_link_with_count" name="facebook_link_with_count" type="checkbox" value="1" 
												<?php
												if ( isset( $bdp_settings['facebook_link_with_count'] ) ) {
													checked( 1, $bdp_settings['facebook_link_with_count'] );
												}
												?>
												/>
												<?php esc_html_e( 'Hide Facebook Share Count', 'blog-designer-pro' ); ?>
											</label>
										</div>
									</div>
									<div class="bdp-typography-cover">
										<div class="bdp-typography-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Twitter Share Link', 'blog-designer-pro' ); ?>
											</span>
										</div>
										<div class="bdp-typography-content">
											<?php
											$twitter_link = isset( $bdp_settings['twitter_link'] ) ? $bdp_settings['twitter_link'] : 1;
											?>
											<fieldset class="bdp-social-options bdp-twitter_link buttonset buttonset-hide" data-hide='1'>
												<input id="twitter_link_1" name="twitter_link" type="radio" value="1" <?php checked( 1, $twitter_link ); ?> />
												<label for="twitter_link_1" <?php checked( 1, $twitter_link ); ?>><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
												<input id="twitter_link_0" name="twitter_link" type="radio" value="0" <?php checked( 0, $twitter_link ); ?> />
												<label for="twitter_link_0" <?php checked( 0, $twitter_link ); ?>><?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
											</fieldset>
										</div>
									</div>
									<div class="bdp-typography-cover">
										<div class="bdp-typography-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Linkedin Share Link', 'blog-designer-pro' ); ?>
											</span>
										</div>
										<div class="bdp-typography-content">
											<?php
											$linkedin_link = isset( $bdp_settings['linkedin_link'] ) ? $bdp_settings['linkedin_link'] : 1;
											?>
											<fieldset class="bdp-social-options bdp-linkedin_link buttonset buttonset-hide" data-hide='1'>
												<input id="linkedin_link_1" name="linkedin_link" type="radio" value="1" <?php checked( 1, $linkedin_link ); ?> />
												<label for="linkedin_link_1" <?php checked( 1, $linkedin_link ); ?>><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
												<input id="linkedin_link_0" name="linkedin_link" type="radio" value="0" <?php checked( 0, $linkedin_link ); ?> />
												<label for="linkedin_link_0" <?php checked( 0, $linkedin_link ); ?>><?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
											</fieldset>
										</div>
									</div>
									<div class="bdp-typography-cover">
										<div class="bdp-typography-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Pinterest Share link', 'blog-designer-pro' ); ?>
											</span>
										</div>
										<div class="bdp-typography-content">
											<?php $pinterest_link = isset( $bdp_settings['pinterest_link'] ) ? $bdp_settings['pinterest_link'] : 1; ?>
											<fieldset class="bdp-social-options bdp-linkedin_link buttonset buttonset-hide" data-hide='1'>
												<input id="pinterest_link_1" name="pinterest_link" type="radio" value="1" <?php checked( 1, $pinterest_link ); ?> />
												<label for="pinterest_link_1" <?php checked( 1, $pinterest_link ); ?>><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
												<input id="pinterest_link_0" name="pinterest_link" type="radio" value="0" <?php checked( 0, $pinterest_link ); ?> />
												<label for="pinterest_link_0" <?php checked( 0, $pinterest_link ); ?>><?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
											</fieldset>
											<label class="social_link_with_count">
												<input id="pinterest_link_with_count" name="pinterest_link_with_count" type="checkbox" value="1" 
												<?php
												if ( isset( $bdp_settings['pinterest_link_with_count'] ) ) {
													checked( 1, $bdp_settings['pinterest_link_with_count'] );
												}
												?>
												/>
												<?php esc_html_e( 'Hide Pinterest Share Count', 'blog-designer-pro' ); ?>
											</label>
										</div>
									</div>
									<div class="bdp-typography-cover">
										<div class="bdp-typography-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Show Pinterest on Featured Image', 'blog-designer-pro' ); ?>
											</span>
										</div>
										<div class="bdp-typography-content">
											<label>
												<?php $pinterest_image_share = isset( $bdp_settings['pinterest_image_share'] ) ? $bdp_settings['pinterest_image_share'] : 1; ?>
												<fieldset class="bdp-social-options bdp-linkedin_link buttonset buttonset-hide" data-hide='1'>
													<input id="pinterest_image_share_1" name="pinterest_image_share" type="radio" value="1" <?php checked( 1, $pinterest_image_share ); ?> />
													<label for="pinterest_image_share_1" <?php checked( 1, $pinterest_image_share ); ?>><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
													<input id="pinterest_image_share_0" name="pinterest_image_share" type="radio" value="0" <?php checked( 0, $pinterest_image_share ); ?> />
													<label for="pinterest_image_share_0" <?php checked( 0, $pinterest_image_share ); ?>><?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
												</fieldset>
											</label>
										</div>
									</div>
									<div class="bdp-typography-cover">
										<div class="bdp-typography-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Skype Share Link', 'blog-designer-pro' ); ?>
											</span>
										</div>
										<div class="bdp-typography-content">
											<?php $skype_link = isset( $bdp_settings['skype_link'] ) ? $bdp_settings['skype_link'] : '0'; ?>
											<fieldset class="bdp-social-options bdp-twitter_link buttonset buttonset-hide" data-hide='1'>
												<input id="skype_link_1" name="skype_link" type="radio" value="1" <?php checked( 1, $skype_link ); ?> />
												<label for="skype_link_1" <?php checked( 1, $skype_link ); ?>><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
												<input id="skype_link_0" name="skype_link" type="radio" value="0" <?php checked( 0, $skype_link ); ?> />
												<label for="skype_link_0" <?php checked( 0, $skype_link ); ?>><?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
											</fieldset>
										</div>
									</div>

									<div class="bdp-typography-cover">
										<div class="bdp-typography-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Pocket Share Link', 'blog-designer-pro' ); ?>
											</span>
										</div>
										<?php $pocket_link = isset( $bdp_settings['pocket_link'] ) ? $bdp_settings['pocket_link'] : '0'; ?>
										<div class="bdp-typography-content">
											<fieldset class="bdp-social-options bdp-pocket_link buttonset buttonset-hide" data-hide='1'>
												<input id="pocket_link_1" name="pocket_link" type="radio" value="1" <?php checked( 1, $pocket_link ); ?> />
												<label for="pocket_link_1" <?php checked( 1, $pocket_link ); ?>><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
												<input id="pocket_link_0" name="pocket_link" type="radio" value="0" <?php checked( 0, $pocket_link ); ?> />
												<label for="pocket_link_0" <?php checked( 0, $pocket_link ); ?>><?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
											</fieldset>
										</div>
									</div>
									<div class="bdp-typography-cover">
										<div class="bdp-typography-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Telegram Share Link', 'blog-designer-pro' ); ?>
											</span>
										</div>
										<?php $telegram_link = isset( $bdp_settings['telegram_link'] ) ? $bdp_settings['telegram_link'] : '0'; ?>
										<div class="bdp-typography-content">
											<fieldset class="bdp-social-options bdp-telegram_link buttonset buttonset-hide" data-hide='1'>
												<input id="telegram_link_1" name="telegram_link" type="radio" value="1" <?php checked( 1, $telegram_link ); ?> />
												<label for="telegram_link_1" <?php checked( 1, $telegram_link ); ?>><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
												<input id="telegram_link_0" name="telegram_link" type="radio" value="0" <?php checked( 0, $telegram_link ); ?> />
												<label for="telegram_link_0" <?php checked( 0, $telegram_link ); ?>><?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
											</fieldset>
										</div>
									</div>
									<div class="bdp-typography-cover">
										<div class="bdp-typography-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Reddit Share Link', 'blog-designer-pro' ); ?>
											</span>
										</div>
										<?php $reddit_link = isset( $bdp_settings['reddit_link'] ) ? $bdp_settings['reddit_link'] : '0'; ?>
										<div class="bdp-typography-content">
											<fieldset class="bdp-social-options bdp-reddit_link buttonset buttonset-hide" data-hide='1'>
												<input id="reddit_link_1" name="reddit_link" type="radio" value="1" <?php checked( 1, $reddit_link ); ?> />
												<label for="reddit_link_1" <?php checked( 1, $reddit_link ); ?>><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
												<input id="reddit_link_0" name="reddit_link" type="radio" value="0" <?php checked( 0, $reddit_link ); ?> />
												<label for="reddit_link_0" <?php checked( 0, $reddit_link ); ?>><?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
											</fieldset>
										</div>
									</div>
									<div class="bdp-typography-cover">
										<div class="bdp-typography-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Digg Share Link', 'blog-designer-pro' ); ?>
											</span>
										</div>
										<?php $digg_link = isset( $bdp_settings['digg_link'] ) ? $bdp_settings['digg_link'] : '0'; ?>
										<div class="bdp-typography-content">
											<fieldset class="bdp-social-options bdp-reddit_link buttonset buttonset-hide" data-hide='1'>
												<input id="digg_link_1" name="digg_link" type="radio" value="1" <?php checked( 1, $digg_link ); ?> />
												<label for="digg_link_1" <?php checked( 1, $digg_link ); ?>><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
												<input id="digg_link_0" name="digg_link" type="radio" value="0" <?php checked( 0, $digg_link ); ?> />
												<label for="digg_link_0" <?php checked( 0, $digg_link ); ?>><?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
											</fieldset>
										</div>
									</div>
									<div class="bdp-typography-cover">
										<div class="bdp-typography-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Tumblr Share Link', 'blog-designer-pro' ); ?>
											</span>
										</div>
										<?php $tumblr_link = isset( $bdp_settings['tumblr_link'] ) ? $bdp_settings['tumblr_link'] : '0'; ?>
										<div class="bdp-typography-content">
											<fieldset class="bdp-social-options bdp-tumblr_link buttonset buttonset-hide" data-hide='1'>
												<input id="tumblr_link_1" name="tumblr_link" type="radio" value="1" <?php checked( 1, $tumblr_link ); ?> />
												<label for="tumblr_link_1" <?php checked( 1, $tumblr_link ); ?>><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
												<input id="tumblr_link_0" name="tumblr_link" type="radio" value="0" <?php checked( 0, $tumblr_link ); ?> />
												<label for="tumblr_link_0" <?php checked( 0, $tumblr_link ); ?>><?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
											</fieldset>
										</div>
									</div>
									<div class="bdp-typography-cover">
										<div class="bdp-typography-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'WordPress Share Link', 'blog-designer-pro' ); ?>
											</span>
										</div>
										<?php $wordpress_link = isset( $bdp_settings['wordpress_link'] ) ? $bdp_settings['wordpress_link'] : '0'; ?>
										<div class="bdp-typography-content">
											<fieldset class="bdp-social-options bdp-wordpress_link buttonset buttonset-hide" data-hide='1'>
												<input id="wordpress_link_1" name="wordpress_link" type="radio" value="1" <?php checked( 1, $wordpress_link ); ?> />
												<label for="wordpress_link_1" <?php checked( 1, $wordpress_link ); ?>><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
												<input id="wordpress_link_0" name="wordpress_link" type="radio" value="0" <?php checked( 0, $wordpress_link ); ?> />
												<label for="wordpress_link_0" <?php checked( 0, $wordpress_link ); ?>><?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
											</fieldset>
										</div>
									</div>
									<div class="bdp-typography-cover">
										<div class="bdp-typography-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Share via Mail', 'blog-designer-pro' ); ?>
											</span>
										</div>
										<div class="bdp-typography-content">
											<?php $email_link = isset( $bdp_settings['email_link'] ) ? $bdp_settings['email_link'] : 0; ?>
											<fieldset class="bdp-social-options bdp-linkedin_link buttonset">
												<input id="email_link_1" class="bdp-opts-button" name="email_link" type="radio" value="1" <?php checked( 1, $email_link ); ?> />
												<label id="bdp-opts-button" for="email_link_1"><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
												<input id="email_link_0" class="bdp-opts-button" name="email_link" type="radio" value="0" <?php checked( 0, $email_link ); ?> />
												<label id="bdp-opts-button" for="email_link_0"><?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
											</fieldset>
										</div>
									</div>
									<div class="bdp-typography-cover">
										<div class="bdp-typography-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'WhatsApp Share Link', 'blog-designer-pro' ); ?>
											</span>
										</div>
										<div class="bdp-typography-content">
											<?php $whatsapp_link = isset( $bdp_settings['whatsapp_link'] ) ? $bdp_settings['whatsapp_link'] : '0'; ?>
											<fieldset class="bdp-social-options bdp-whatsapp_link buttonset">
												<input id="whatsapp_link_1" class="bdp-opts-button" name="whatsapp_link" type="radio" value="1" <?php checked( 1, $whatsapp_link ); ?> />
												<label id="bdp-opts-button" for="whatsapp_link_1"><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
												<input id="whatsapp_link_0" class="bdp-opts-button" name="whatsapp_link" type="radio" value="0" <?php checked( 0, $whatsapp_link ); ?> />
												<label id="bdp-opts-button" for="whatsapp_link_0"><?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
											</fieldset>
										</div>
									</div>

								</div>
							</li>
							<li class="social_share_options fb_access_token_div">
								<div class="bdp-left">
									<span class="bdp-key-title">
										<?php esc_html_e( 'Facebook Access Token', 'blog-designer-pro' ); ?>
									</span>
								</div>
								<div class="bdp-right">
									<?php
									$facebook_token = '';
									if ( isset( $bdp_settings['facebook_token'] ) ) {
										$facebook_token = $bdp_settings['facebook_token'];
									}
									?>
									<input type="text" name="facebook_token" id="facebook_token" value="<?php echo esc_attr( $facebook_token ); ?>" >
								</div>
							</li>
							<li class ="social_share_options mail_share_content">
								<div class="bdp-left">
									<span class="bdp-key-title"><?php esc_html_e( 'Mail Share Content', 'blog-designer-pro' ); ?></span>
								</div>
								<div class="bdp-right">
									<?php $mail_subject = ( isset( $bdp_settings['mail_subject'] ) && '' != $bdp_settings['mail_subject'] ) ? $bdp_settings['mail_subject'] : '[post_title]'; ?>
									<input type="text" name="mail_subject" id="mail_subject" value="<?php echo esc_attr( $mail_subject ); ?>" placeholder="<?php esc_attr_e( 'Enter Mail Subject', 'blog-designer-pro' ); ?>">
									<?php
									$settings = array(
										'wpautop'       => true,
										'media_buttons' => true,
										'textarea_name' => 'mail_content',
										'textarea_rows' => 10,
										'tabindex'      => '',
										'editor_css'    => '',
										'editor_class'  => '',
										'teeny'         => false,
										'dfw'           => false,
										'tinymce'       => true,
										'quicktags'     => true,
									);
									if ( isset( $bdp_settings['mail_content'] ) && '' != $bdp_settings['mail_content'] ) {
										$contents = $bdp_settings['mail_content'];
									} else {
										$contents = esc_html__( 'My Dear friends', 'blog-designer-pro' ) . '<br/><br/>' . esc_html__( 'I read one good blog link and I would like to share that same link for you. That might useful for you', 'blog-designer-pro' ) . '<br/><br/>[post_link]<br/><br/>' . esc_html__( 'Best Regards', 'blog-designer-pro' ) . ',<br/>' . esc_html__( 'Blog Designer', 'blog-designer-pro' );
									}

									wp_editor( html_entity_decode( $contents ), 'mail_content', $settings );
									?>
									<div class="div-pre">
										<p> [post_title] => <?php esc_html_e( 'Product Title', 'blog-designer-pro' ); ?> </p>
										<p> [post_link] => <?php esc_html_e( 'Product Link', 'blog-designer-pro' ); ?> </p>
										<p> [post_thumbnail] => <?php esc_html_e( 'Product Featured Image', 'blog-designer-pro' ); ?> </p>
										<p> [sender_name] => <?php esc_html_e( 'Mail Sender Name', 'blog-designer-pro' ); ?> </p>
										<p> [sender_email] => <?php esc_html_e( 'Mail Sender Email Address', 'blog-designer-pro' ); ?> </p>
									</div>
								</div>
							</li>
							<div class="bdp_grid_col_3">
								<li class="social_share_options mail_share_content">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Remove Reply to email', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<?php
										$reply_to_mail = isset( $bdp_settings['reply_to_mail'] ) ? $bdp_settings['reply_to_mail'] : 0;
										?>
										<fieldset class="bdp-social-options buttonset buttonset-hide" data-hide='1'>
											<input id="reply_to_mail_1" name="reply_to_mail" type="radio" value="1" <?php checked( 1, $reply_to_mail ); ?> />
											<label id="" for="reply_to_mail_1" <?php checked( 1, $reply_to_mail ); ?>><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
											<input id="reply_to_mail_0" name="reply_to_mail" type="radio" value="0" <?php checked( 0, $reply_to_mail ); ?> />
											<label id="" for="reply_to_mail_0" <?php checked( 0, $reply_to_mail ); ?>> <?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
										</fieldset>
									</div>
								</li>
								<li class ="social_share_options">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Social Share Count Position', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<?php
										$social_count_position = 'bottom';
										if ( isset( $bdp_settings['social_count_position'] ) ) {
											$social_count_position = $bdp_settings['social_count_position'];
										}
										?>
										<div class="typo-field">
											<select name="social_count_position" id="social_sharecount">
												<option value="bottom" <?php echo selected( 'bottom', $social_count_position ); ?>><?php esc_html_e( 'Bottom', 'blog-designer-pro' ); ?></option>
												<option value="right" <?php echo selected( 'right', $social_count_position ); ?>><?php esc_html_e( 'Right', 'blog-designer-pro' ); ?></option>
												<option value="top" <?php echo selected( 'top', $social_count_position ); ?>><?php esc_html_e( 'Top', 'blog-designer-pro' ); ?></option>
											</select>
										</div>
									</div>
								</li>
								<li class="social_share_options social_share_position_option">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Social Share Position', 'blog-designer-pro' ); ?>
										</span>
									</div>
										<?php
										$social_share_position = 'left';
										if ( isset( $bdp_settings['social_share_position'] ) ) {
											$social_share_position = $bdp_settings['social_share_position'];
										}
										?>
										<div class="typo-field">
											<select name="social_share_position" id="social_share_position">
												<option value="left" <?php echo selected( 'left', $social_share_position ); ?>><?php esc_html_e( 'Left', 'blog-designer-pro' ); ?></option>
												<option value="center" <?php echo selected( 'center', $social_share_position ); ?>><?php esc_html_e( 'Center', 'blog-designer-pro' ); ?></option>
												<option value="right" <?php echo selected( 'right', $social_share_position ); ?>><?php esc_html_e( 'Right', 'blog-designer-pro' ); ?></option>
											</select>
										</div>
									</div>
								</li>
							</div>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</form>
	<div id="popupdiv-single" class="bdp-template-popupdiv" style="display: none;">
		<?php
		foreach ( $tempate_list as $key => $value ) {
			$classes = explode( ' ', $value['class'] );
			foreach ( $classes as $class ) {
				$all_class[] = $class;
			}
		}
		$count = array_count_values( $all_class );
		?>
		<ul class="bdp_template_tab">
			<li class="current_tab">
				<a href="#all"><?php esc_html_e( 'All', 'blog-designer-pro' ); ?></a>
			</li>
			<li class=""><a href="#full-width"><?php echo esc_html__( 'Full Width', 'blog-designer-pro' ) . ' (' . esc_html( $count['full-width'] ) . ')'; ?></a></li>
			<li><a href="#grid"><?php echo esc_html__( 'Grid', 'blog-designer-pro' ) . ' (' . esc_html( $count['grid'] ) . ')'; ?></a></li>
			<li><a href="#masonry"><?php echo esc_html__( 'Masonry', 'blog-designer-pro' ) . ' (' . esc_html( $count['masonry'] ) . ')'; ?></a></li>
			<li><a href="#magazine"><?php echo esc_html__( 'Magazine', 'blog-designer-pro' ) . ' (' . esc_html( $count['magazine'] ) . ')'; ?></a></li>
			<li><a href="#timeline"><?php echo esc_html__( 'Timeline', 'blog-designer-pro' ) . ' (' . esc_html( $count['timeline'] ) . ')'; ?></a></li>
			<li><a href="#slider"><?php echo esc_html__( 'Slider', 'blog-designer-pro' ) . ' (' . esc_html( $count['slider'] ) . ')'; ?></a></li>
			<div class="bdp-single-blog-template-search-cover">
				<input type="text" class="bdp-template-search" id="bdp-template-search" placeholder="<?php esc_html_e( 'Search Template', 'blog-designer-pro' ); ?>" />
				<span class="bdp-template-search-clear"></span>
			</div>
		</ul>
		<?php
		echo '<div class="bdp-blog-template-cover">';
		foreach ( $tempate_list as $key => $value ) {
			?>
			<div class="template-thumbnail <?php echo esc_attr( $value['class'] ); ?>" <?php echo ( isset( $value['data'] ) && '' != $value['data'] ) ? 'data-value="' . esc_attr( $value['data'] ) . '"' : ''; ?>>
				<div class="template-thumbnail-inner">
					<img src="<?php echo esc_attr( BLOGDESIGNERPRO_URL ) . '/admin/images/single/' . esc_attr( $value['image_name'] ); ?>" data-value="<?php echo esc_attr( $key ); ?>" alt="<?php echo esc_attr( $value['template_name'] ); ?>" title="<?php echo esc_attr( $value['template_name'] ); ?>">
					<div class="hover_overlay">
						<div class="popup-template-name">
							<div class="popup-select"><a href="#"><?php esc_html_e( 'Select Template', 'blog-designer-pro' ); ?></a></div>
							<div class="popup-view"><a href="<?php echo esc_attr( $value['demo_link'] ); ?>" target="_blank"><?php esc_html_e( 'Live Demo', 'blog-designer-pro' ); ?></a></div>
						</div>
					</div>
				</div>
				<span class="bdp-span-template-name"><?php echo esc_html( $value['template_name'] ); ?></span>
			</div>
			<?php
		}
		echo '</div>';
		echo '<h3 class="no-template" style="display: none;">' . esc_html__( 'No template found. Please try again', 'blog-designer-pro' ) . '</h3>';
		?>
	</div>
</div>

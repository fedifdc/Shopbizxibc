<?php
/**
 * Add/Edit Archive Layout setting page
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

global $wpdb, $bdp_errors, $bdp_success, $bdp_settings;
if ( isset( $_GET['page'] ) && 'bdp_add_archive_layout' === $_GET['page'] ) {
	$page                = esc_attr( sanitize_text_field( wp_unslash( $_GET['page'] ) ) );
	$bdp_settings        = array();
	$custom_archive_type = '';
	if ( isset( $_GET['id'] ) && ! empty( $_GET['id'] ) ) {
		$archive_id = intval( $_GET['id'] );
		$table_name = $wpdb->prefix . 'bdp_archives';
		if ( is_numeric( $archive_id ) ) {
			$get_allsettings = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}bdp_archives WHERE ID = %d", $archive_id ), ARRAY_A );
		}
		if ( ! isset( $get_allsettings[0]['settings'] ) ) {
			echo '<div class="updated notice">';
			wp_die( esc_html__( 'You attempted to edit an item that doesn’t exist. Perhaps it was deleted?', 'blog-designer-pro' ) );
			echo '</div>';
		}
		$allsettings = $get_allsettings[0]['settings'];
		if ( is_serialized( $allsettings ) ) {
			$bdp_settings        = maybe_unserialize( $allsettings );
			$custom_archive_type = $get_allsettings[0]['archive_template'];
		}
	}
}
$font_family                    = Bdp_Utility::default_recognized_font_faces();
$template_name                  = isset( $bdp_settings['template_name'] ) ? $bdp_settings['template_name'] : 'classical';
$archive_name                   = isset( $bdp_settings['archive_name'] ) ? $bdp_settings['archive_name'] : '';
$posts_per_page                 = isset( $bdp_settings['posts_per_page'] ) ? $bdp_settings['posts_per_page'] : 5;
$pagination_type                = isset( $bdp_settings['pagination_type'] ) ? $bdp_settings['pagination_type'] : 'paged';
$template_category              = isset( $bdp_settings['template_category'] ) ? $bdp_settings['template_category'] : array();
$template_tags                  = isset( $bdp_settings['template_tags'] ) ? $bdp_settings['template_tags'] : array();
$template_author                = isset( $bdp_settings['template_author'] ) ? $bdp_settings['template_author'] : array();
$display_feature_image          = isset( $bdp_settings['display_feature_image'] ) ? $bdp_settings['display_feature_image'] : '1';
$display_category               = isset( $bdp_settings['display_category'] ) ? $bdp_settings['display_category'] : '1';
$display_postlike               = isset( $bdp_settings['display_postlike'] ) ? $bdp_settings['display_postlike'] : '0';
$display_tag                    = isset( $bdp_settings['display_tag'] ) ? $bdp_settings['display_tag'] : '1';
$display_author                 = isset( $bdp_settings['display_author'] ) ? $bdp_settings['display_author'] : '1';
$display_date                   = isset( $bdp_settings['display_date'] ) ? $bdp_settings['display_date'] : '1';
$display_story_year             = isset( $bdp_settings['display_story_year'] ) ? $bdp_settings['display_story_year'] : '1';
$display_comment_count          = isset( $bdp_settings['display_comment_count'] ) ? $bdp_settings['display_comment_count'] : '1';
$template_columns               = isset( $bdp_settings['template_columns'] ) ? $bdp_settings['template_columns'] : 2;
$template_alternativebackground = isset( $bdp_settings['template_alternativebackground'] ) ? $bdp_settings['template_alternativebackground'] : '0';
$template_titlefontface         = isset( $bdp_settings['template_titlefontface'] ) ? $bdp_settings['template_titlefontface'] : 'Georgia, serif';
$template_titlefontsize         = isset( $bdp_settings['template_titlefontsize'] ) ? $bdp_settings['template_titlefontsize'] : '25';
$rss_use_excerpt                = isset( $bdp_settings['rss_use_excerpt'] ) ? $bdp_settings['rss_use_excerpt'] : '1';
$txt_excerptlength              = isset( $bdp_settings['txtExcerptlength'] ) ? $bdp_settings['txtExcerptlength'] : '50';
$content_fontsize               = isset( $bdp_settings['content_fontsize'] ) ? $bdp_settings['content_fontsize'] : '14';
$template_contentcolor          = isset( $bdp_settings['template_contentcolor'] ) ? $bdp_settings['template_contentcolor'] : '#666';
$template_content_hovercolor    = isset( $bdp_settings['template_content_hovercolor'] ) ? $bdp_settings['template_content_hovercolor'] : '#f5f5f5';
$txt_readmoretext               = isset( $bdp_settings['txtReadmoretext'] ) ? $bdp_settings['txtReadmoretext'] : __( 'Read More', 'blog-designer-pro' );
$template_readmorecolor         = isset( $bdp_settings['template_readmorecolor'] ) ? $bdp_settings['template_readmorecolor'] : '#f1f1f1';
$template_readmorebackcolor     = isset( $bdp_settings['template_readmorebackcolor'] ) ? $bdp_settings['template_readmorebackcolor'] : '#999';
$display_author_biography       = isset( $bdp_settings['display_author_biography'] ) ? $bdp_settings['display_author_biography'] : '1';
$display_author_social          = isset( $bdp_settings['display_author_social'] ) ? $bdp_settings['display_author_social'] : '1';
$bdp_timeline_layout            = isset( $bdp_settings['bdp_timeline_layout'] ) ? $bdp_settings['bdp_timeline_layout'] : '';
$easy_timeline_effect           = isset( $bdp_settings['easy_timeline_effect'] ) ? $bdp_settings['easy_timeline_effect'] : 'default-effect';
$tempate_list                   = Bdp_Template::blog_template_list();
$loaders                        = array(
	'circularG'               => '<div class="bdp-circularG-wrapper"><div class="bdp-circularG bdp-circularG_1"></div><div class="bdp-circularG bdp-circularG_2"></div><div class="bdp-circularG bdp-circularG_3"></div><div class="bdp-circularG bdp-circularG_4"></div><div class="bdp-circularG bdp-circularG_5"></div><div class="bdp-circularG bdp-circularG_6"></div><div class="bdp-circularG bdp-circularG_7"></div><div class="bdp-circularG bdp-circularG_8"></div></div>',
	'floatingCirclesG'        => '<div class="bdp-floatingCirclesG"><div class="bdp-f_circleG bdp-frotateG_01"></div><div class="bdp-f_circleG bdp-frotateG_02"></div><div class="bdp-f_circleG bdp-frotateG_03"></div><div class="bdp-f_circleG bdp-frotateG_04"></div><div class="bdp-f_circleG bdp-frotateG_05"></div><div class="bdp-f_circleG bdp-frotateG_06"></div><div class="bdp-frotateG_07 bdp-f_circleG"></div><div class="bdp-frotateG_08 bdp-f_circleG"></div></div>',
	'spinloader'              => '<div class="bdp-spinloader"></div>',
	'doublecircle'            => '<div class="bdp-doublec-container"><ul class="bdp-doublec-flex-container"><li><span class="bdp-doublec-loading"></span></li></ul></div>',
	'wBall'                   => '<div class="bdp-windows8"><div class="bdp-wBall bdp-wBall_1"><div class="bdp-wInnerBall"></div></div><div class="bdp-wBall bdp-wBall_2"><div class="bdp-wInnerBall"></div></div><div class="bdp-wBall bdp-wBall_3"><div class="bdp-wInnerBall"></div></div><div class="bdp-wBall bdp-wBall_4"><div class="bdp-wInnerBall"></div></div><div class="bdp-wBall_5 bdp-wBall"><div class="bdp-wInnerBall"></div></div></div>',
	'cssanim'                 => '<div class="bdp-cssload-aim"></div>',
	'thecube'                 => '<div class="bdp-cssload-thecube"><div class="bdp-cssload-cube bdp-cssload-c1"></div><div class="bdp-cssload-cube bdp-cssload-c2"></div><div class="bdp-cssload-cube bdp-cssload-c4"></div><div class="bdp-cssload-cube bdp-cssload-c3"></div></div>',
	'ballloader'              => '<div class="bdp-ballloader"><div class="bdp-loader-inner bdp-ball-grid-pulse"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div></div>',
	'squareloader'            => '<div class="bdp-squareloader"><div class="bdp-square"></div><div class="bdp-square"></div><div class="bdp-square last"></div><div class="bdp-square clear"></div><div class="bdp-square"></div><div class="bdp-square last"></div><div class="bdp-square clear"></div><div class="bdp-square"></div><div class="bdp-square last"></div></div>',
	'loadFacebookG'           => '<div class="bdp-loadFacebookG"><div class="bdp-blockG_1 bdp-facebook_blockG"></div><div class="bdp-blockG_2 bdp-facebook_blockG"></div><div class="bdp-facebook_blockG bdp-blockG_3"></div></div>',
	'floatBarsG'              => '<div class="bdp-floatBarsG-wrapper"><div class="bdp-floatBarsG_1 bdp-floatBarsG"></div><div class="bdp-floatBarsG_2 bdp-floatBarsG"></div><div class="bdp-floatBarsG_3 bdp-floatBarsG"></div><div class="bdp-floatBarsG_4 bdp-floatBarsG"></div><div class="bdp-floatBarsG_5 bdp-floatBarsG"></div><div class="bdp-floatBarsG_6 bdp-floatBarsG"></div><div class="bdp-floatBarsG_7 bdp-floatBarsG"></div><div class="bdp-floatBarsG_8 bdp-floatBarsG"></div></div>',
	'movingBallG'             => '<div class="bdp-movingBallG-wrapper"><div class="bdp-movingBallLineG"></div><div class="bdp-movingBallG_1 bdp-movingBallG"></div></div>',
	'ballsWaveG'              => '<div class="bdp-ballsWaveG-wrapper"><div class="bdp-ballsWaveG_1 bdp-ballsWaveG"></div><div class="bdp-ballsWaveG_2 bdp-ballsWaveG"></div><div class="bdp-ballsWaveG_3 bdp-ballsWaveG"></div><div class="bdp-ballsWaveG_4 bdp-ballsWaveG"></div><div class="bdp-ballsWaveG_5 bdp-ballsWaveG"></div><div class="bdp-ballsWaveG_6 bdp-ballsWaveG"></div><div class="bdp-ballsWaveG_7 bdp-ballsWaveG"></div><div class="bdp-ballsWaveG_8 bdp-ballsWaveG"></div></div>',
	'fountainG'               => '<div class="fountainG-wrapper"><div class="bdp-fountainG_1 bdp-fountainG"></div><div class="bdp-fountainG_2 bdp-fountainG"></div><div class="bdp-fountainG_3 bdp-fountainG"></div><div class="bdp-fountainG_4 bdp-fountainG"></div><div class="bdp-fountainG_5 bdp-fountainG"></div><div class="bdp-fountainG_6 bdp-fountainG"></div><div class="bdp-fountainG_7 bdp-fountainG"></div><div class="bdp-fountainG_8 bdp-fountainG"></div></div>',
	'audio_wave'              => '<div class="bdp-audio_wave"><span></span><span></span><span></span><span></span><span></span></div>',
	'warningGradientBarLineG' => '<div class="bdp-warningGradientOuterBarG"><div class="bdp-warningGradientFrontBarG bdp-warningGradientAnimationG"><div class="bdp-warningGradientBarLineG"></div><div class="bdp-warningGradientBarLineG"></div><div class="bdp-warningGradientBarLineG"></div><div class="bdp-warningGradientBarLineG"></div><div class="bdp-warningGradientBarLineG"></div><div class="bdp-warningGradientBarLineG"></div></div></div>',
	'floatingBarsG'           => '<div class="bdp-floatingBarsG"><div class="bdp-rotateG_01 bdp-blockG"></div><div class="bdp-rotateG_02 bdp-blockG"></div><div class="bdp-rotateG_03 bdp-blockG"></div><div class="bdp-rotateG_04 bdp-blockG"></div><div class="bdp-rotateG_05 bdp-blockG"></div><div class="bdp-rotateG_06 bdp-blockG"></div><div class="bdp-rotateG_07 bdp-blockG"></div><div class="bdp-rotateG_08 bdp-blockG"></div></div>',
	'rotatecircle'            => '<div class="bdp-cssload-loader"><div class="bdp-cssload-inner bdp-cssload-one"></div><div class="bdp-cssload-inner bdp-cssload-two"></div><div class="bdp-cssload-inner bdp-cssload-three"></div></div>',
	'overlay-loader'          => '<div class="bdp-overlay-loader"><div class="bdp-loader"><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div></div>',
	'circlewave'              => '<div class="bdp-circlewave"></div>',
	'cssload-ball'            => '<div class="bdp-cssload-ball"></div>',
	'cssheart'                => '<div class="bdp-cssload-main"><div class="bdp-cssload-heart"><span class="bdp-cssload-heartL"></span><span class="bdp-cssload-heartR"></span><span class="bdp-cssload-square"></span></div><div class="bdp-cssload-shadow"></div></div>',
	'spinload'                => '<div class="bdp-spinload-loading"><i></i><i></i><i></i></div>',
	'bigball'                 => '<div class="bdp-bigball-container"><div class="bdp-bigball-loading"><i></i><i></i><i></i></div></div>',
	'bubblec'                 => '<div class="bdp-bubble-container"><div class="bdp-bubble-loading"><i></i><i></i></div></div>',
	'csball'                  => '<div class="bdp-csball-container"><div class="bdp-csball-loading"><i></i><i></i><i></i><i></i></div></div>',
	'ccball'                  => '<div class="bdp-ccball-container"><div class="bdp-ccball-loading"><i></i><i></i></div></div>',
	'circulardot'             => '<div class="bdp-cssload-wrap"><div class="bdp-circulardot-container"><span class="bdp-cssload-dots"></span><span class="bdp-cssload-dots"></span><span class="bdp-cssload-dots"></span><span class="bdp-cssload-dots"></span><span class="bdp-cssload-dots"></span><span class="bdp-cssload-dots"></span><span class="bdp-cssload-dots"></span><span class="bdp-cssload-dots"></span><span class="bdp-cssload-dots"></span><span class="bdp-cssload-dots"></span></div></div>',
);
if ( 'cool_horizontal' === $template_name || 'overlay_horizontal' === $template_name ) {
	$pagination_type = 'no_pagination';
}
if ( 'brite' === $template_name ) {
	$winter_category_txt = esc_html__( 'Choose Tags Background Color', 'blog-designer-pro' );
} else {
	$winter_category_txt = esc_html__( 'Choose Category Background Color', 'blog-designer-pro' );
}
?>
<div class="wrap">
	<?php if ( isset( $_GET['message'] ) && 'archive_added_msg' === $_GET['message'] ) { ?>
		<div class="updated notice"><p><?php esc_html_e( 'Archive layout added successfully.', 'blog-designer-pro' ); ?></p></div>
		<?php
	}
	if ( isset( $_GET['message'] ) && 'shortcode_duplicate_msg' === $_GET['message'] ) {
		?>
		<div class="updated notice"><p><?php esc_html_e( 'Archive layout has been duplicated successfully. Please Select Archive Type.', 'blog-designer-pro' ); ?></p></div>
		<?php
	}
	if ( isset( $bdp_errors ) ) {
		if ( is_wp_error( $bdp_errors ) ) {
			?>
			<div class="error notice"><p><?php echo esc_html( $bdp_errors->get_error_message() ); ?></p></div>
			<?php
		}
	}
	if ( isset( $bdp_success ) ) {
		?>
		<div class="updated notice"><p><?php echo wp_kses( $bdp_success, Bdp_Admin_Functions::args_kses() ); ?></p></div>
		<?php
	}
	?>
	<h1 class="bdp-shortcode-div">
		<div class= "pull-left bdp_edit_layout">
			<?php
			if ( isset( $_GET['action'] ) && 'edit' === $_GET['action'] && isset( $_GET['id'] ) ) {
				esc_html_e( 'Edit Archive Layout', 'blog-designer-pro' );
			} else {
				esc_html_e( 'Add Archive Layout', 'blog-designer-pro' );
			}
			?>
		</div>
		<?php if ( isset( $_GET['action'] ) && 'edit' === $_GET['action'] && isset( $_GET['id'] ) ) { ?>
			<div class="pull-right"><a class="page-title-action bdp_create_new_layout" href="?page=bdp_add_archive_layout"><?php esc_html_e( 'Create New Archive Layout', 'blog-designer-pro' ); ?></a></div>
		<?php } ?>
	</h1>
	<div class="splash-screen"></div>
	<div class="bdp_spinner_wrapper">
		<div class="bdp_loader_inner">
			<svg viewBox="0 0 102 102" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path class="bdp-big-circle" d="M101 51C101 78.6142 78.6142 101 51 101C23.3858 101 1 78.6142 1 51" stroke="#e21130" stroke-width="2"/>
				<path class="bdp-small-circle" d="M91 51C91 28.9086 73.0914 11 51 11C28.9086 11 11 28.9086 11 51" stroke="#e21130" stroke-width="2"/>
			</svg>
		</div>
	</div>
	<form method="post" action="" id="edit_archive_layout_form" class="bd-form-class bdp-archive-page">
		<?php
		wp_nonce_field( 'bdp-archive-page-submit', 'bdp-archive-nonce' );
		$page = '';
		if ( isset( $_GET['page'] ) && '' != $_GET['page'] ) {
			$page = esc_attr( sanitize_text_field( wp_unslash( $_GET['page'] ) ) );
			?>
			<input type="hidden" name="originalpage" class="bdporiginalpage" value="<?php echo esc_attr( $page ); ?>">
		<?php } ?>
		<div id="poststuff">
			<div class="postbox-container bd-settings-wrappers bd_poststuff">
				<div class="bdp-preview-box" id="bdp-preview-box"></div>
				<div class="bd-header-wrapper">
					<div class="bd-logo-wrapper pull-left">
						<h3><?php esc_html_e( 'Blog designer settings', 'blog-designer-pro' ); ?></h3>
					</div>
					<div class="pull-right">
						<?php
						if ( isset( $_GET['id'] ) ) {
							$archive_id = intval( $_GET['id'] );
							?>
						<a id="bdp-btn-show-preview" class="button button-hero archive_show_preview" href="#" data-post-id="<?php echo esc_attr( $archive_id ); ?>">
							<span><i class="fas fa-eye"></i>&nbsp;&nbsp;<?php esc_html_e( 'Preview', 'blog-designer-pro' ); ?></span>
						</a>
						<?php } ?>
						<input type="submit" class="button-primary archive_savebtn button" name="savedata" value="<?php esc_attr_e( 'Save Changes', 'blog-designer-pro' ); ?>" />
					</div>
				</div>
				<div class="bd-menu-setting">
					<?php
					$bdpgeneral_class               = '';
					$dbptimeline_class              = '';
					$bdpstandard_class              = '';
					$bdptitle_class                 = '';
					$bdpauthor_class                = '';
					$bdpcontent_class               = '';
					$bdpmedia_class                 = '';
					$bdpslider_class                = '';
					$bdpsocial_class                = '';
					$bdpfilter_class                = '';
					$bdppagination_class            = '';
					$bdpacffieldssetting_class      = '';
					$bdpads_class                   = '';
					$bdpgeneral_class_show          = '';
					$dbptimeline_class_show         = '';
					$bdpstandard_class_show         = '';
					$bdptitle_class_show            = '';
					$bdpauthor_class_show           = '';
					$bdpcontent_class_show          = '';
					$bdpmedia_class_show            = '';
					$bdpslider_class_show           = '';
					$bdpsocial_class_show           = '';
					$bdpfilter_class_show           = '';
					$bdppagination_class            = '';
					$bdppagination_class_show       = '';
					$bdpacffieldssetting_class_show = '';
					$bdpads_class_show              = '';
					if ( Bdp_Posts::postbox_classes( 'bdpgeneral', $page ) ) {
						$bdpgeneral_class      = 'bd-active-tab';
						$bdpgeneral_class_show = 'display:block';
					} elseif ( Bdp_Posts::postbox_classes( 'bdpstandard', $page ) ) {
						$bdpstandard_class      = 'bd-active-tab';
						$bdpstandard_class_show = 'display:block';
					} elseif ( Bdp_Posts::postbox_classes( 'bdptitle', $page ) ) {
						$bdptitle_class      = 'bd-active-tab';
						$bdptitle_class_show = 'display:block';
					} elseif ( Bdp_Posts::postbox_classes( 'bdpsinglepostauthor', $page ) ) {
						$bdpauthor_class      = 'bd-active-tab';
						$bdpauthor_class_show = 'display:block';
					} elseif ( Bdp_Posts::postbox_classes( 'bdpcontent', $page ) ) {
						$bdpcontent_class      = 'bd-active-tab';
						$bdpcontent_class_show = 'display:block';
					} elseif ( Bdp_Posts::postbox_classes( 'bdpmedia', $page ) ) {
						$bdpmedia_class      = 'bd-active-tab';
						$bdpmedia_class_show = 'display:block';
					} elseif ( Bdp_Posts::postbox_classes( 'dbptimeline', $page ) ) {
						$dbptimeline_class      = 'bd-active-tab';
						$dbptimeline_class_show = 'display:block';
					} elseif ( Bdp_Posts::postbox_classes( 'bdpslider', $page ) ) {
						$bdpslider_class      = 'bd-active-tab';
						$bdpslider_class_show = 'display:block';
					} elseif ( Bdp_Posts::postbox_classes( 'bdpfilter', $page ) ) {
						$bdpfilter_class      = 'bd-active-tab';
						$bdpfilter_class_show = 'display:block';
					} elseif ( Bdp_Posts::postbox_classes( 'bdppagination', $page ) ) {
						$bdppagination_class      = 'bd-active-tab';
						$bdppagination_class_show = 'display:block';
					} elseif ( Bdp_Posts::postbox_classes( 'bdpacffield', $page ) ) {
						$bdpsocial_class      = 'bd-active-tab';
						$bdpsocial_class_show = 'display:block';
					} elseif ( Bdp_Posts::postbox_classes( 'bdpsocial', $page ) ) {
						$bdpsocial_class      = 'bd-active-tab';
						$bdpsocial_class_show = 'display:block';
					} elseif ( Bdp_Posts::postbox_classes( 'bdpads', $page ) ) {
						$bdpads_class      = 'bd-active-tab';
						$bdpads_class_show = 'display:block';
					} else {
						$bdpgeneral_class      = 'bd-active-tab';
						$bdpgeneral_class_show = 'display:block';
					}
					?>
					<ul class="bd-setting-handle">
						<li data-show="bdpgeneral" class=<?php echo esc_attr( $bdpgeneral_class ); ?>>
						<div class="bdp_tab_icon">
						<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"><path fill="currentColor" d="M19.14 12.94c.04-.3.06-.61.06-.94c0-.32-.02-.64-.07-.94l2.03-1.58a.49.49 0 0 0 .12-.61l-1.92-3.32a.488.488 0 0 0-.59-.22l-2.39.96c-.5-.38-1.03-.7-1.62-.94l-.36-2.54a.484.484 0 0 0-.48-.41h-3.84c-.24 0-.43.17-.47.41l-.36 2.54c-.59.24-1.13.57-1.62.94l-2.39-.96c-.22-.08-.47 0-.59.22L2.74 8.87c-.12.21-.08.47.12.61l2.03 1.58c-.05.3-.09.63-.09.94s.02.64.07.94l-2.03 1.58a.49.49 0 0 0-.12.61l1.92 3.32c.12.22.37.29.59.22l2.39-.96c.5.38 1.03.7 1.62.94l.36 2.54c.05.24.24.41.48.41h3.84c.24 0 .44-.17.47-.41l.36-2.54c.59-.24 1.13-.56 1.62-.94l2.39.96c.22.08.47 0 .59-.22l1.92-3.32c.12-.22.07-.47-.12-.61l-2.01-1.58zM12 15.6c-1.98 0-3.6-1.62-3.6-3.6s1.62-3.6 3.6-3.6s3.6 1.62 3.6 3.6s-1.62 3.6-3.6 3.6z"/></svg>
						</div><span><?php esc_html_e( 'General Settings', 'blog-designer-pro' ); ?></span>
						</li>
						<li data-show="bdpstandard" class=<?php echo esc_attr( $bdpstandard_class ); ?>>
						<div class="bdp_tab_icon">
						<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"><path fill="currentColor" d="M10.788 3.102c.495-1.003 1.926-1.003 2.421 0l2.358 4.778l5.273.766c1.107.16 1.549 1.522.748 2.303l-.905.882a6.5 6.5 0 0 0-9.441 7.43l-3.96 2.081c-.99.52-2.147-.32-1.958-1.423l.9-5.251l-3.815-3.72c-.801-.78-.359-2.141.748-2.302L8.43 7.88l2.358-4.778Zm3.49 10.873a2 2 0 0 1-1.441 2.496l-.584.145a5.729 5.729 0 0 0 .006 1.807l.54.13a2 2 0 0 1 1.45 2.51l-.187.631c.44.386.94.7 1.484.922l.494-.519a2 2 0 0 1 2.899 0l.498.525a5.276 5.276 0 0 0 1.483-.912l-.198-.686a2 2 0 0 1 1.441-2.497l.584-.144a5.718 5.718 0 0 0-.006-1.807l-.54-.13a2 2 0 0 1-1.45-2.51l.187-.631a5.278 5.278 0 0 0-1.484-.922l-.493.518a2 2 0 0 1-2.9 0l-.498-.525c-.544.22-1.044.53-1.483.913l.198.686Zm3.222 5.024c-.8 0-1.45-.671-1.45-1.5c0-.828.65-1.5 1.45-1.5c.8 0 1.45.672 1.45 1.5c0 .829-.65 1.5-1.45 1.5Z"/></svg>
						</div><span><?php esc_html_e( 'Standard Settings', 'blog-designer-pro' ); ?></span>
						</li>
						<li data-show="bdpsinglepostauthor" class=<?php echo esc_attr( $bdpauthor_class ); ?>>
						<div class="bdp_tab_icon">
							<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"><path fill="currentColor" d="m21.7 13.35l-1 1l-2.05-2.05l1-1a.55.55 0 0 1 .77 0l1.28 1.28c.21.21.21.56 0 .77M12 18.94l6.06-6.06l2.05 2.05L14.06 21H12v-2.06M12 14c-4.42 0-8 1.79-8 4v2h6v-1.89l4-4c-.66-.08-1.33-.11-2-.11m0-10a4 4 0 0 0-4 4a4 4 0 0 0 4 4a4 4 0 0 0 4-4a4 4 0 0 0-4-4Z"/></svg>
						</div>
							<span><?php esc_html_e( 'Author Settings', 'blog-designer-pro' ); ?></span>
						</li>
						<li data-show="bdptitle" class=<?php echo esc_attr( $bdptitle_class ); ?>>
						<div class="bdp_tab_icon">
							<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16"><path fill="currentColor" fill-rule="evenodd" d="M.75 2a.75.75 0 0 0-.75.75v1.5a.75.75 0 0 0 1.5 0V3.5h2.75v9h-.5a.75.75 0 0 0 0 1.5h2.5a.75.75 0 0 0 0-1.5h-.5v-9H8.5v.75a.75.75 0 0 0 1.5 0v-1.5A.75.75 0 0 0 9.25 2H.75ZM8 7.75A.75.75 0 0 1 8.75 7h6.5a.75.75 0 0 1 0 1.5h-6.5A.75.75 0 0 1 8 7.75Zm0 3.5a.75.75 0 0 1 .75-.75h6.5a.75.75 0 0 1 0 1.5h-6.5a.75.75 0 0 1-.75-.75Z" clip-rule="evenodd"/></svg>
						</div>
							<span><?php esc_html_e( 'Title Settings', 'blog-designer-pro' ); ?></span>
						</li>
						<li data-show="bdpcontent" class=<?php echo esc_attr( $bdpcontent_class ); ?>>
						<div class="bdp_tab_icon">
							<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"><path fill="currentColor" d="M14 2H6c-1.11 0-2 .89-2 2v16c0 1.11.89 2 2 2h7.81c-.53-.91-.81-1.95-.81-3c0-.33.03-.67.08-1H6v-2h7.81c.46-.8 1.1-1.5 1.87-2H6v-2h12v1.08c.33-.05.67-.08 1-.08s.67.03 1 .08V8l-6-6m-1 7V3.5L18.5 9H13m5 6v3h-3v2h3v3h2v-3h3v-2h-3v-3h-2Z"/></svg>
						</div>
							<span><?php esc_html_e( 'Content Settings', 'blog-designer-pro' ); ?></span>
						</li>
						<li data-show="bdpmedia" class=<?php echo esc_attr( $bdpmedia_class ); ?>>
						<div class="bdp_tab_icon">
							<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"><path fill="currentColor" d="M2 6H0v5h.01L0 20c0 1.1.9 2 2 2h18v-2H2V6zm20-2h-8l-2-2H6c-1.1 0-1.99.9-1.99 2L4 16c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zM7 15l4.5-6l3.5 4.51l2.5-3.01L21 15H7z"/></svg>
						</div>
							<span><?php esc_html_e( 'Media Settings', 'blog-designer-pro' ); ?></span>
						</li>
						<li data-show="dbptimeline" class=<?php echo esc_attr( $dbptimeline_class ); ?>>
							<div class="bdp_tab_icon">
								<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 256 256"><path fill="currentColor" d="M208 138h-74v-20h50a14 14 0 0 0 14-14V64a14 14 0 0 0-14-14h-50V32a6 6 0 0 0-12 0v18H72a14 14 0 0 0-14 14v40a14 14 0 0 0 14 14h50v20H48a14 14 0 0 0-14 14v40a14 14 0 0 0 14 14h74v18a6 6 0 0 0 12 0v-18h74a14 14 0 0 0 14-14v-40a14 14 0 0 0-14-14ZM70 104V64a2 2 0 0 1 2-2h112a2 2 0 0 1 2 2v40a2 2 0 0 1-2 2H72a2 2 0 0 1-2-2Zm140 88a2 2 0 0 1-2 2H48a2 2 0 0 1-2-2v-40a2 2 0 0 1 2-2h160a2 2 0 0 1 2 2Z"/></svg>
							</div>
							<span><?php esc_html_e( 'Horizontal Timeline Settings', 'blog-designer-pro' ); ?></span>
						</li>
						<li data-show="bdpslider" class=<?php echo esc_attr( $bdpslider_class ); ?>>
						<div class="bdp_tab_icon">
							<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"><path fill="currentColor" d="M13 5h9v2h-9zM2 7h7v2h2V3H9v2H2zm7 10h13v2H9zm10-6h3v2h-3zm-2 4V9.012h-2V11H2v2h13v2zM7 21v-6H5v2H2v2h3v2z"/></svg>
						</div>
							<span><?php esc_html_e( 'Slider Settings', 'blog-designer-pro' ); ?></span>
						</li>
						<li data-show="bdpfilter" class=<?php echo esc_attr( $bdpfilter_class ); ?>>
						<div class="bdp_tab_icon">
							<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"><path fill="currentColor" d="M11 18q-.425 0-.713-.288T10 17q0-.425.288-.713T11 16h2q.425 0 .713.288T14 17q0 .425-.288.713T13 18h-2ZM4 8q-.425 0-.713-.288T3 7q0-.425.288-.713T4 6h16q.425 0 .713.288T21 7q0 .425-.288.713T20 8H4Zm3 5q-.425 0-.713-.288T6 12q0-.425.288-.713T7 11h10q.425 0 .713.288T18 12q0 .425-.288.713T17 13H7Z"/></svg>
						</div>
							<span><?php esc_html_e( 'Filter Settings', 'blog-designer-pro' ); ?></span>
						</li>
						<li data-show="bdppagination" class=<?php echo esc_attr( $bdppagination_class ); ?>>
						<div class="bdp_tab_icon">
							<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"><path fill="currentColor" d="M16 16h-5.5V4H16a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2ZM4 4h5.5v12H4a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2Zm8.5 10a.5.5 0 1 0 0-1a.5.5 0 0 0 0 1Zm2.5-.5a.5.5 0 1 0-1 0a.5.5 0 0 0 1 0Zm1.5.5a.5.5 0 1 0 0-1a.5.5 0 0 0 0 1Z"/></svg>
						</div>
							<span><?php esc_html_e( 'Pagination Settings', 'blog-designer-pro' ); ?></span>
						</li>
						<?php
						if ( is_plugin_active( 'advanced-custom-fields/acf.php' ) ) {
							$groups = acf_get_field_groups();
							if ( ! empty( $groups ) ) {
								?>
								<li data-show="bdpacffieldssetting" class=<?php echo esc_attr( $bdpacffieldssetting_class ); ?>>
								<div class="bdp_tab_icon">
									<svg fill="#9c9c9c" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20" height="20" viewBox="0 0 32.181 32.18" xml:space="preserve"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g> <path d="M30.037,29.924c-0.67-0.611-1.525-2.094-2.567-4.447L16.539,0.625h-0.424L5.276,24.805 c-1.028,2.311-1.893,3.834-2.593,4.57c-0.7,0.738-1.595,1.189-2.683,1.352v0.828h10.079v-0.828 C8.5,30.608,7.511,30.43,7.107,30.192c-0.687-0.401-1.029-1.026-1.029-1.877c0-0.641,0.209-1.453,0.627-2.438l1.273-2.949h10.705 l1.608,3.777c0.416,0.98,0.642,1.541,0.67,1.676c0.091,0.283,0.135,0.559,0.135,0.826c0,0.447-0.163,0.791-0.49,1.029 c-0.479,0.328-1.305,0.488-2.48,0.488h-0.604v0.828h14.659v-0.828C31.261,30.653,30.545,30.385,30.037,29.924z M8.764,21.274 l4.647-10.436l4.516,10.436H8.764z"></path> </g> </g></svg>
								</div><span><?php esc_html_e( 'ACF Field Settings', 'blog-designer-pro' ); ?></span>
								</li>
								<?php
							}
						}
						?>
						<li data-show="bdpsocial" class=<?php echo esc_attr( $bdpsocial_class ); ?>>
						<div class="bdp_tab_icon">
							<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"><path fill="currentColor" d="M18 22q-1.25 0-2.125-.875T15 19q0-.175.025-.363t.075-.337l-7.05-4.1q-.425.375-.95.588T6 15q-1.25 0-2.125-.875T3 12q0-1.25.875-2.125T6 9q.575 0 1.1.213t.95.587l7.05-4.1q-.05-.15-.075-.337T15 5q0-1.25.875-2.125T18 2q1.25 0 2.125.875T21 5q0 1.25-.875 2.125T18 8q-.575 0-1.1-.212t-.95-.588L8.9 11.3q.05.15.075.338T9 12q0 .175-.025.363T8.9 12.7l7.05 4.1q.425-.375.95-.587T18 16q1.25 0 2.125.875T21 19q0 1.25-.875 2.125T18 22Z"/>
							</svg>
						</div>
							<span><?php esc_html_e( 'Social Share Settings', 'blog-designer-pro' ); ?></span>
						</li>
						<?php if ( is_plugin_active( 'blog-designer-ads/blog-designer-ads.php' ) ) { ?>
							<?php do_action( 'bdads_do_blog_settings', 'tab' ); ?>
							<?php
						} else {
							?>
							<li data-show="bdpads" class=<?php echo esc_attr( $bdpads_class ); ?>>
								<div class="bdp_tab_icon">
									<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" style="width: 20px;height: 20px;"><path d="M64 32C28.7 32 0 60.7 0 96V416c0 35.3 28.7 64 64 64H512c35.3 0 64-28.7 64-64V96c0-35.3-28.7-64-64-64H64zM229.5 173.3l72 144c5.9 11.9 1.1 26.3-10.7 32.2s-26.3 1.1-32.2-10.7L253.2 328H162.8l-5.4 10.7c-5.9 11.9-20.3 16.7-32.2 10.7s-16.7-20.3-10.7-32.2l72-144c4.1-8.1 12.4-13.3 21.5-13.3s17.4 5.1 21.5 13.3zM208 237.7L186.8 280h42.3L208 237.7zM392 256a24 24 0 1 0 0 48 24 24 0 1 0 0-48zm24-43.9V184c0-13.3 10.7-24 24-24s24 10.7 24 24v96 48c0 13.3-10.7 24-24 24c-6.6 0-12.6-2.7-17-7c-9.4 4.5-19.9 7-31 7c-39.8 0-72-32.2-72-72s32.2-72 72-72c8.4 0 16.5 1.4 24 4.1z" style="fill: #9c9c9c;"></path></svg>
								</div><span><?php esc_html_e( 'Ads Settings', 'blog-designer-pro' ); ?></span>
							</li>
							<?php
						}
						?>
					</ul>
				</div>
				<div id="bdpgeneral" class="postbox postbox-with-fw-options" style=<?php echo esc_attr( $bdpgeneral_class_show ); ?>>
					<div class="inside">
						<ul class="bdp-settings bdp-lineheight">
							<h3 class="bdp-table-title"><?php esc_html_e( 'Select Archive Layout', 'blog-designer-pro' ); ?></h3>
							<li>
								<div class="bdp-left">
									<span class="bdp-key-title">
										<?php esc_html_e( 'Select your favourite layout from 61 powerful archive layouts', 'blog-designer-pro' ); ?>
									</span>
								</div>
								<div class="bdp-right">
								<?php
									$tempate_list = Bdp_Template::blog_template_list();
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
													<img src="<?php echo esc_url( BLOGDESIGNERPRO_URL ) . '/admin/images/layouts/' . esc_attr( $image_name ); ?>" alt="
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
							<li>
								<div class="bdp-left"><span class="bdp-key-title"><?php esc_html_e( 'Template Color Preset', 'blog-designer-pro' ); ?></span></div>
								<div class="bdp-right bdp-preset-position">
									<?php
										$color_template        = isset( $bdp_settings['template_name'] ) ? $bdp_settings['template_name'] : 'classical';
										$bdp_color_preset      = isset( $bdp_settings['bdp_color_preset'] ) ? $bdp_settings['bdp_color_preset'] : $color_template . '_default';
										$template_color_preset = array(
											'accordion'    => array(
												'accordion_default' => array(
													'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#239190,template_icon_bgcolor:transparent,template_fthovercolor:#ffffff,template_icon_active_header_color:#239190,template_titlecolor:#FFFFFF,template_titlehovercolor:#333333,template_titlebackcolor:#239190,template_contentcolor:#555555,template_readmorecolor:#000000,template_readmorebackcolor:#239190,template_readmorehovercolor:#ffffff,template_readmore_hover_backcolor:#239190,bdp_sale_tagbgcolor:#239190,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#239190,bdp_pricetextcolor:#239190,bdp_edd_price_color:#239190,bdp_wishlist_textcolor:#239190,bdp_wishlist_backgroundcolor:#239190,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#239190,bdp_addtocart_textcolor:#ffffff,bdp_edd_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#239190,bdp_edd_addtocart_backgroundcolor:#239190,bdp_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#239190,bdp_edd_addtocart_hover_backgroundcolor:#239190',
													'display_value' => '#239190,#ffffff,#239190,#555555',
												),
												'accordion_persian-red' => array(
													'preset_name' => esc_html__( 'Persian Red', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#dd3333,template_fthovercolor:#ffffff,template_icon_active_header_color:#dd3333,template_titlecolor:#FFFFFF,template_titlehovercolor:#333333,template_titlebackcolor:#dd3333,template_contentcolor:#7b7b7b,template_readmorecolor:#dd3333,template_readmorebackcolor:#535353,template_readmorehovercolor:#ffffff,template_readmore_hover_backcolor:#484848,bdp_sale_tagbgcolor:#484848,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#7b7b7b,bdp_star_rating_bg_color:#dd3333,bdp_pricetextcolor:#555555,bdp_edd_price_color:#555555,bdp_wishlist_textcolor:#484848,bdp_wishlist_backgroundcolor:#f2f2f2,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#484848,bdp_addtocart_textcolor:#ffffff,bdp_edd_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#535353,bdp_edd_addtocart_backgroundcolor:#484848,bdp_addtocart_text_hover_color:#484848,bdp_edd_addtocart_text_hover_color:#484848,bdp_addtocart_hover_backgroundcolor:#f2f2f2,bdp_edd_addtocart_hover_backgroundcolor:#f2f2f2',
													'display_value' => '#dd3333,#ffffff,#dd3333,#484848',
												),
												'accordion_mariner' => array(
													'preset_name' => esc_html__( 'Mariner', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#EAEDF6,template_ftcolor:#465BAC,template_fthovercolor:#ffffff,template_icon_active_header_color:#465BAC,template_titlecolor:#FFFFFF,template_titlehovercolor:#484848,template_titlebackcolor:#465BAC,template_contentcolor:#7b7b7b,template_readmorecolor:#465BAC,template_readmorebackcolor:#EAEDF6,template_readmorehovercolor:#ffffff,template_readmore_hover_backcolor:#465BAC,bdp_sale_tagbgcolor:#465BAC,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#7b7b7b,bdp_star_rating_bg_color:#465BAC,bdp_pricetextcolor:#484848,bdp_edd_price_color:#484848,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#484848,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#465BAC,bdp_addtocart_textcolor:#ffffff,bdp_edd_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#EAEDF6,bdp_edd_addtocart_backgroundcolor:#465BAC,bdp_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#484848,bdp_edd_addtocart_hover_backgroundcolor:#484848',
													'display_value' => '#465BAC,#EAEDF6,#465BAC,#484848',
												),
												'accordion_radical_red' => array(
													'preset_name' => esc_html__( 'Radical Red', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#FFEAF1,template_ftcolor:#FA336C,template_fthovercolor:#ffffff,template_icon_active_header_color:#FA336C,template_titlecolor:#FFFFFF,template_titlehovercolor:#484848,template_titlebackcolor:#FA336C,template_contentcolor:#7b7b7b,template_readmorecolor:#FA336C,template_readmorebackcolor:#FFEAF1,template_readmorehovercolor:#ffffff,template_readmore_hover_backcolor:#FA336C,bdp_sale_tagbgcolor:#FA336C,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#7b7b7b,bdp_star_rating_bg_color:#FA336C,bdp_pricetextcolor:#484848,bdp_edd_price_color:#484848,bdp_wishlist_textcolor:#FA336C,bdp_wishlist_backgroundcolor:#ffeaf1,bdp_wishlist_text_hover_color:#ffeaf1,bdp_wishlist_hover_backgroundcolor:#FA336C,bdp_addtocart_textcolor:#ffffff,bdp_edd_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#FFEAF1,bdp_edd_addtocart_backgroundcolor:#FA336C,bdp_addtocart_text_hover_color:#FA336C,bdp_edd_addtocart_text_hover_color:#FA336C,bdp_addtocart_hover_backgroundcolor:#ffeaf1,bdp_edd_addtocart_hover_backgroundcolor:#ffeaf1',
													'display_value' => '#FA336C,#FFEAF1,#FA336C,#484848',
												),
												'accordion_flamenco' => array(
													'preset_name' => esc_html__( 'Flamenco', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#FFF3ED,template_ftcolor:#683C6F,template_fthovercolor:#ffffff,template_icon_active_header_color:#683C6F,template_titlecolor:#FFFFFF,template_titlehovercolor:#484848,template_titlebackcolor:#683C6F,template_contentcolor:#7b7b7b,template_readmorecolor:#683C6F,template_readmorebackcolor:#FFF3ED,template_readmorehovercolor:#ffffff,template_readmore_hover_backcolor:#683C6F,bdp_sale_tagbgcolor:#683C6F,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#7b7b7b,bdp_star_rating_bg_color:#683C6F,bdp_pricetextcolor:#484848,bdp_edd_price_color:#484848,bdp_wishlist_textcolor:#683C6F,bdp_wishlist_backgroundcolor:#FFF3ED,bdp_wishlist_text_hover_color:#FFF3ED,bdp_wishlist_hover_backgroundcolor:#683C6F,bdp_addtocart_textcolor:#ffffff,bdp_edd_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#FFF3ED,bdp_edd_addtocart_backgroundcolor:#683C6F,bdp_addtocart_text_hover_color:#683C6F,bdp_edd_addtocart_text_hover_color:#683C6F,bdp_addtocart_hover_backgroundcolor:#FFF3ED,bdp_edd_addtocart_hover_backgroundcolor:#FFF3ED',
													'display_value' => '#683C6F,#FFF3ED,#683C6F,#484848',
												),
												'accordion_finch'   => array(
													'preset_name' => esc_html__( 'Finch', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#EFF0EB,template_ftcolor:#75815B,template_fthovercolor:#ffffff,template_icon_active_header_color:#75815B,template_titlecolor:#FFFFFF,template_titlehovercolor:#484848,template_titlebackcolor:#75815B,template_contentcolor:#7b7b7b,template_readmorecolor:#75815B,template_readmorebackcolor:#EFF0EB,template_readmorehovercolor:#ffffff,template_readmore_hover_backcolor:#75815B,bdp_sale_tagbgcolor:#75815B,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#7b7b7b,bdp_star_rating_bg_color:#75815B,bdp_pricetextcolor:#484848,bdp_edd_price_color:#484848,bdp_wishlist_textcolor:#75815B,bdp_wishlist_backgroundcolor:#EFF0EB,bdp_wishlist_text_hover_color:#EFF0EB,bdp_wishlist_hover_backgroundcolor:#75815B,bdp_addtocart_textcolor:#ffffff,bdp_edd_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#EFF0EB,bdp_edd_addtocart_backgroundcolor:#75815B,bdp_addtocart_text_hover_color:#75815B,bdp_edd_addtocart_text_hover_color:#75815B,bdp_addtocart_hover_backgroundcolor:#EFF0EB,bdp_edd_addtocart_hover_backgroundcolor:#EFF0EB',
													'display_value' => '#75815B,#EFF0EB,#75815B,#484848',
												),
											),
											'tabbed'       => array(
												'tabbed_default' => array(
													'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#8d6fcf,template_bgcolor:#ffffff,template_ftcolor:#A9A9A9,template_fthovercolor:#A9A9A9,template_titlecolor:#000000,,template_titlebackcolor:#ffffff,template_titlehovercolor:#123123,template_readmorecolor:#ffffff,template_contentcolor:#000000,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#A9A9A9,beforeloop_readmorehovercolor:#A9A9A9,beforeloop_readmorehoverbackcolor:#000000,bdp_sale_tagbgcolor:#000000,bdp_sale_tagtextcolor:#FDE7E9,bdp_star_rating_color:#333333,bdp_star_rating_bg_color:#F34656,bdp_pricetextcolor:#A9A9A9,bdp_edd_price_color:#A9A9A9,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#000000,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#333333,bdp_addtocart_textcolor:#ffffff,bdp_edd_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#F34656,bdp_edd_addtocart_backgroundcolor:#F34656,bdp_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#333333,bdp_edd_addtocart_hover_backgroundcolor:#333333',
													'display_value' => '#8d6fcf,#A9A9A9,#000000,#ffffff',
												),
												'tabbed_cyan' => array(
													'preset_name' => esc_html__( 'Cyan', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#dd9323,template_bgcolor:#DDEEF1,template_ftcolor:#dd9323,template_fthovercolor:#002226,template_titlecolor:#dd9323,template_titlehovercolor:#000000,template_contentcolor:#000000,template_readmorecolor:#DDEEF1,template_readmorebackcolor:#3f3f3f,template_readmore_hover_backcolor:#000000,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#dd9323,beforeloop_readmorehovercolor:#000000,beforeloop_readmorehoverbackcolor:#000000,bdp_sale_tagbgcolor:#ffffff,bdp_sale_tagtextcolor:#3f3f3f,bdp_star_rating_color:#dd9323,bdp_star_rating_bg_color:#002226,bdp_pricetextcolor:#000000,bdp_edd_price_color:#00363B,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#3f3f3f,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#000000,bdp_addtocart_textcolor:#ffffff,bdp_edd_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#3f3f3f,bdp_edd_addtocart_backgroundcolor:#3f3f3f,bdp_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#000000,bdp_edd_addtocart_hover_backgroundcolor:#000000',
													'display_value' => '#ffffff,#dd9323,#3f3f3f,#000000',
												),
												'tabbed_pompadour' => array(
													'preset_name' => esc_html__( 'Pompadour', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#f4291a,template_bgcolor:#ffffff,template_ftcolor:#494949,template_fthovercolor:#f4291a,template_titlecolor:#f4291a,template_titlehovercolor:#323e72,template_contentcolor:#000000,template_readmorecolor:#ffffff,template_readmorebackcolor:#f4291a,template_readmore_hover_backcolor:#000000,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#f4291a,beforeloop_readmorehovercolor:#f4291a,beforeloop_readmorehoverbackcolor:#000000,bdp_sale_tagbgcolor:#662451,bdp_sale_tagtextcolor:#f4291a,bdp_star_rating_color:#ffffff,bdp_star_rating_bg_color:#323e72,bdp_pricetextcolor:#000000,bdp_edd_price_color:#000000,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#f4291a,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#662451,bdp_addtocart_textcolor:#ffffff,bdp_edd_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#662451,bdp_edd_addtocart_backgroundcolor:#662451,bdp_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#323e72,bdp_edd_addtocart_hover_backgroundcolor:#323e72',
													'display_value' => '#ffffff,#f4291a,#323e72,#000000',
												),
											),
											'blog_grid_box' => array(
												'blog_grid_box_default' => array(
													'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#3E91AD,template_bgcolor:#ffffff,template_ftcolor:#ffffff,template_fthovercolor:#555555,template_titlecolor:#ffffff,template_titlehovercolor:#3E91AD,template_readmorecolor:#3E91AD,template_readmorebackcolor:#666666,template_contentcolor:#666666,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#3E91AD,beforeloop_readmorehovercolor:#3E91AD,beforeloop_readmorehoverbackcolor:#ffffff,bdp_sale_tagbgcolor:#3E91AD,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#666666,bdp_pricetextcolor:#222222,bdp_edd_price_color:#222222,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#666666,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#555555,bdp_addtocart_textcolor:#ffffff,bdp_edd_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#3E91AD,bdp_edd_addtocart_backgroundcolor:#3E91AD,bdp_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#222222,bdp_edd_addtocart_hover_backgroundcolor:#222222',
													'display_value' => '#3E91AD,#222222,#555555,#666666',
												),
												'blog_grid_box_blackberry' => array(
													'preset_name' => esc_html__( 'Blackberry', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#49182D,template_bgcolor:#F3F0F1,template_ftcolor:#ffffff,template_fthovercolor:#6D4657,template_titlecolor:#ffffff,template_titlehovercolor:#6D4657,template_readmorecolor:#6D4657,template_readmorebackcolor:#666666,template_contentcolor:#666666,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#6D4657,beforeloop_readmorehovercolor:#6D4657,beforeloop_readmorehoverbackcolor:#ffffff,bdp_sale_tagbgcolor:#49182D,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#666666,bdp_pricetextcolor:#49182D,bdp_edd_price_color:#49182D,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#666666,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#49182D,bdp_addtocart_textcolor:#ffffff,bdp_edd_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#6D4657,bdp_edd_addtocart_backgroundcolor:#6D4657,bdp_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#555555,bdp_edd_addtocart_hover_backgroundcolor:#555555',
													'display_value' => '#49182D,#6D4657,#555555,#666666',
												),
												'blog_grid_box_prussian' => array(
													'preset_name' => esc_html__( 'Prussian', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#99a8ba,template_bgcolor:#e5e9ed,template_ftcolor:#ffffff,template_fthovercolor:#000b19,template_titlecolor:#ffffff,template_titlehovercolor:#99a8ba,template_titlebackcolor:,template_contentcolor:#000308,template_readmorecolor:#e5e9ed,template_readmorebackcolor:#000308,template_readmore_hover_backcolor:#193b65,beforeloop_readmorecolor:#e5e9ed,beforeloop_readmorebackcolor:#000308,beforeloop_readmorehovercolor:#e5e9ed,beforeloop_readmorehoverbackcolor:#193b65,bdp_sale_tagbgcolor:#171101,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#171101,bdp_star_rating_bg_color:#193b65,bdp_pricetextcolor:#000308,bdp_edd_price_color:#000308,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#000b19,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#193b65,bdp_addtocart_textcolor:#ffffff,bdp_edd_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#171101,bdp_edd_addtocart_backgroundcolor:#171101,bdp_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#000308,bdp_edd_addtocart_hover_backgroundcolor:#000308',
													'display_value' => '#99a8ba,#193b65,#000b19,#000308',
												),
												'blog_grid_box_tangerine'   => array(
													'preset_name' => esc_html__( 'Tangerine', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#f8df9f,template_bgcolor:#fdf7e7,template_ftcolor:#ffffff,template_fthovercolor:#473505,template_titlecolor:#ffffff,template_titlehovercolor:#f8df9f,template_titlebackcolor:,template_contentcolor:#171101,template_readmorecolor:#fdf7e7,template_readmorebackcolor:#171101,template_readmore_hover_backcolor:#efb828,beforeloop_readmorecolor:#fdf7e7,beforeloop_readmorebackcolor:#171101,beforeloop_readmorehovercolor:#fdf7e7,beforeloop_readmorehoverbackcolor:#efb828,bdp_sale_tagbgcolor:#171101,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#171101,bdp_star_rating_bg_color:#473505,bdp_pricetextcolor:#171101,bdp_edd_price_color:#171101,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#888888,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#473505,bdp_addtocart_textcolor:#ffffff,bdp_edd_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#171101,bdp_edd_addtocart_backgroundcolor:#171101,bdp_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#473505,bdp_edd_addtocart_hover_backgroundcolor:#473505',
													'display_value' => '#f8df9f,#efb828,#473505,#171101',
												),
											),
											'blog_carousel' => array(
												'blog_carousel_default' => array(
													'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#15506F,template_bgcolor:#ffffff,template_bghovercolor:#DFEDF1,template_ftcolor:#FFFFFF,template_fthovercolor:#555555,template_titlecolor:#FFFFFF,template_titlehovercolor:#DFEDF1,template_contentcolor:#FFFFFF,template_readmorecolor:#FFFFFF,template_readmorebackcolor:#555555,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#15506F,beforeloop_readmorehovercolor:#15506F,beforeloop_readmorehoverbackcolor:#ffffff,bdp_sale_tagbgcolor:#15506F,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#999999,bdp_star_rating_bg_color:#15506F,bdp_pricetextcolor:#999999,bdp_edd_price_color:#999999,bdp_wishlist_textcolor:#15506F,bdp_wishlist_backgroundcolor:#555555,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#15506F,bdp_addtocart_textcolor:#ffffff,bdp_edd_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#15506F,bdp_edd_addtocart_backgroundcolor:#15506F,bdp_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#555555,bdp_edd_addtocart_hover_backgroundcolor:#555555',
													'display_value' => '#15506F,#DFEDF1,#555555,#999999',
												),
												'blog_carousel_pompadour' => array(
													'preset_name' => esc_html__( 'Pompadour', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#974772,template_bgcolor:#ffffff,template_bghovercolor:#EDE1E7,template_ftcolor:#555555,template_fthovercolor:#974772,template_titlecolor:#FFFFFF,template_titlehovercolor:#974772,template_contentcolor:#FFFFFF,template_readmorecolor:#ffffff,template_readmorebackcolor:#555555,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#974772,beforeloop_readmorehovercolor:#974772,beforeloop_readmorehoverbackcolor:#ffffff,bdp_sale_tagbgcolor:#974772,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#999999,bdp_star_rating_bg_color:#974772,bdp_pricetextcolor:#999999,bdp_edd_price_color:#999999,bdp_wishlist_textcolor:#974772,bdp_wishlist_backgroundcolor:#555555,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#974772,bdp_addtocart_textcolor:#ffffff,bdp_edd_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#974772,bdp_edd_addtocart_backgroundcolor:#974772,bdp_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#555555,bdp_edd_addtocart_hover_backgroundcolor:#555555',
													'display_value' => '#7D194F,#974772,#555555,#999999',
												),
												'blog_carousel_mariner' => array(
													'preset_name' => esc_html__( 'Mariner', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#465BAC,grid_hoverback_color:#465BAC,template_ftcolor:#e0e0e0,template_fthovercolor:#ffffff,template_titlecolor:#FFFFFF,template_titlehovercolor:#ffffff,beforeloop_readmorecolor:#465BAC,beforeloop_readmorebackcolor:#e0e0e0,beforeloop_readmorehovercolor:#ffffff,beforeloop_readmorehoverbackcolor:#465BAC,bdp_sale_tagbgcolor:#465BAC,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#e0e0e0,bdp_star_rating_bg_color:#465BAC,bdp_pricetextcolor:#e0e0e0,bdp_edd_price_color:#e0e0e0,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#465BAC,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#465BAC,bdp_addtocart_textcolor:#ffffff,bdp_edd_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#465BAC,bdp_edd_addtocart_backgroundcolor:#465BAC,bdp_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#465BAC,bdp_edd_addtocart_hover_backgroundcolor:#465BAC,template_contentcolor:#FFFFFF,template_readmorecolor:#ffffff,template_readmorebackcolor:#555555',
													'display_value' => '#465BAC,#e0e0e0,#ffffff,#e0e0e0',
												),
												'blog_carousel_finch'   => array(
													'preset_name' => esc_html__( 'Finch', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#93A564,template_bgcolor:#ffffff,template_bghovercolor:#EDEDE9,template_ftcolor:#555555,template_fthovercolor:#93A564,template_titlecolor:#FFFFFF,template_titlehovercolor:#93A564,template_contentcolor:#FFFFFF,template_readmorecolor:#ffffff,template_readmorebackcolor:#555555,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#93A564,beforeloop_readmorehovercolor:#93A564,beforeloop_readmorehoverbackcolor:#ffffff,bdp_sale_tagbgcolor:#93A564,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#999999,bdp_star_rating_bg_color:#93A564,bdp_pricetextcolor:#999999,bdp_edd_price_color:#999999,bdp_wishlist_textcolor:#93A564,bdp_wishlist_backgroundcolor:#EDEDE9,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#93A564,bdp_addtocart_textcolor:#ffffff,bdp_edd_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#93A564,bdp_edd_addtocart_backgroundcolor:#93A564,bdp_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#555555,bdp_edd_addtocart_hover_backgroundcolor:#555555',
													'display_value' => '#788F3D,#93A564,#555555,#999999',
												),
											),
											'boxy'         => array(
												'boxy_default' => array(
													'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#239190,template_fthovercolor:#ffffff,template_titlecolor:#239190,template_titlehovercolor:#333333,template_titlebackcolor:#ffffff,template_contentcolor:#555555,template_readmorecolor:#ffffff,template_readmorebackcolor:#239190,template_readmorehovercolor:#000000,template_readmore_hover_backcolor:#239190',
													'display_value' => '#239190,#ffffff,#239190,#555555',
												),
												'boxy_persian-red' => array(
													'preset_name' => esc_html__( 'Persian Red', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#dd3333,template_fthovercolor:#ffffff,template_titlecolor:#dd3333,template_titlehovercolor:#484848,template_titlebackcolor:#ffffff,template_contentcolor:#7b7b7b,template_readmorecolor:#ffffff,template_readmorebackcolor:#dd3333,template_readmorehovercolor:#000000,template_readmore_hover_backcolor:#dd3333',
													'display_value' => '#dd3333,#ffffff,#dd3333,#484848',
												),
												'boxy_mariner' => array(
													'preset_name' => esc_html__( 'Mariner', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#EAEDF6,template_ftcolor:#465BAC,template_fthovercolor:#ffffff,template_titlecolor:#465BAC,template_titlehovercolor:#484848,template_titlebackcolor:#eaedf6,template_contentcolor:#7b7b7b,template_readmorecolor:#ffffff,template_readmorebackcolor:#465BAC,template_readmorehovercolor:#000000,template_readmore_hover_backcolor:#465BAC',
													'display_value' => '#465BAC,#EAEDF6,#465BAC,#484848',
												),
												'boxy_radical_red' => array(
													'preset_name' => esc_html__( 'Radical Red', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#FFEAF1,template_ftcolor:#FA336C,template_fthovercolor:#ffffff,template_titlecolor:#FA336C,template_titlehovercolor:#484848,template_titlebackcolor:#FFEAF1,template_contentcolor:#7b7b7b,template_readmorecolor:#ffffff,template_readmorebackcolor:#FA336C,template_readmorehovercolor:#000000,template_readmore_hover_backcolor:#FA336C',
													'display_value' => '#FA336C,#FFEAF1,#FA336C,#484848',
												),
												'boxy_flamenco' => array(
													'preset_name' => esc_html__( 'Flamenco', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#FFF3ED,template_ftcolor:#683C6F,template_fthovercolor:#ffffff,template_titlecolor:#683C6F,template_titlehovercolor:#484848,template_titlebackcolor:#FFF3ED,template_contentcolor:#7b7b7b,template_readmorecolor:#ffffff,template_readmorebackcolor:#683C6F,template_readmorehovercolor:#000000,template_readmore_hover_backcolor:#683C6F',
													'display_value' => '#683C6F,#FFF3ED,#683C6F,#484848',
												),
												'boxy_finch' => array(
													'preset_name' => esc_html__( 'Finch', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#EFF0EB,template_ftcolor:#75815B,template_fthovercolor:#ffffff,template_titlecolor:#75815B,template_titlehovercolor:#484848,template_titlebackcolor:#EFF0EB,template_contentcolor:#7b7b7b,template_readmorecolor:#ffffff,template_readmorebackcolor:#75815B,template_readmorehovercolor:#000000,template_readmore_hover_backcolor:#75815B',
													'display_value' => '#75815B,#EFF0EB,#75815B,#484848',
												),
											),
											'boxy-clean'   => array(
												'boxy-clean_default' => array(
													'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#15506F,template_bgcolor:#ffffff,template_bghovercolor:#DFEDF1,template_ftcolor:#15506F,template_fthovercolor:#555555,template_titlecolor:#15506F,template_titlehovercolor:#DFEDF1,template_contentcolor:#999999,template_readmorecolor:#ffffff,template_readmorebackcolor:#15506F,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#15506F,beforeloop_readmorehovercolor:#15506F,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#15506F,#DFEDF1,#555555,#999999',
												),
												'boxy-clean_pompadour' => array(
													'preset_name' => esc_html__( 'Pompadour', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#974772,template_bgcolor:#ffffff,template_bghovercolor:#EDE1E7,template_ftcolor:#555555,template_fthovercolor:#974772,template_titlecolor:#7D194F,template_titlehovercolor:#974772,template_contentcolor:#999999,template_readmorecolor:#ffffff,template_readmorebackcolor:#974772,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#974772,beforeloop_readmorehovercolor:#974772,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#7D194F,#974772,#555555,#999999',
												),
												'boxy-clean_roof_terracotta' => array(
													'preset_name' => esc_html__( 'Roof Terracotta', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#B06F6D,template_bgcolor:#ffffff,template_bghovercolor:#F1E7E7,template_ftcolor:#555555,template_fthovercolor:#B06F6D,template_titlecolor:#9C4B48,template_titlehovercolor:#B06F6D,template_contentcolor:#999999,template_readmorecolor:#ffffff,template_readmorebackcolor:#B06F6D,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#B06F6D,beforeloop_readmorehovercolor:#B06F6D,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#9C4B48,#B06F6D,#555555,#999999',
												),
												'boxy-clean_lemon-ginger' => array(
													'preset_name' => esc_html__( 'Lemon Ginger', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#CEBF59,template_bgcolor:#ffffff,template_bghovercolor:#F0EEE4,template_ftcolor:#555555,template_fthovercolor:#CEBF59,template_titlecolor:#C2AF2F,template_titlehovercolor:#CEBF59,template_contentcolor:#999999,template_readmorecolor:#ffffff,template_readmorebackcolor:#CEBF59,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#CEBF59,beforeloop_readmorehovercolor:#CEBF59,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#C2AF2F,#CEBF59,#555555,#999999',
												),
												'boxy-clean_finch' => array(
													'preset_name' => esc_html__( 'Finch', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#93A564,template_bgcolor:#ffffff,template_bghovercolor:#EDEDE9,template_ftcolor:#555555,template_fthovercolor:#93A564,template_titlecolor:#788F3D,template_titlehovercolor:#93A564,template_contentcolor:#999999,template_readmorecolor:#ffffff,template_readmorebackcolor:#93A564,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#93A564,beforeloop_readmorehovercolor:#93A564,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#788F3D,#93A564,#555555,#999999',
												),
												'boxy-clean_blackberry' => array(
													'preset_name' => esc_html__( 'Blackberry', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#6D4657,template_bgcolor:#ffffff,template_bghovercolor:#E7E1E3,template_ftcolor:#555555,template_fthovercolor:#6D4657,template_titlecolor:#49182D,template_titlehovercolor:#6D4657,template_contentcolor:#999999,template_readmorecolor:#ffffff,template_readmorebackcolor:#6D4657,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#6D4657,beforeloop_readmorehovercolor:#6D4657,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#49182D,#6D4657,#555555,#999999',
												),
											),
											'brit_co'      => array(
												'brit_co_default' => array(
													'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
													'preset_value' => 'template_ftcolor:#555555,template_fthovercolor:#3E91AD,template_titlecolor:#222222,template_titlehovercolor:#3E91AD,template_titlebackcolor:#ffffff,template_readmorecolor:#ffffff,template_readmorebackcolor:#3E91AD,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#3E91AD,beforeloop_readmorehovercolor:#3E91AD,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#3E91AD,beforeloop_readmorehovercolor:#3E91AD,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#222222,#3E91AD,#555555,#999999',
												),
												'brit_co_yonder' => array(
													'preset_name' => esc_html__( 'Yonder', 'blog-designer-pro' ),
													'preset_value' => 'template_ftcolor:#555555,template_fthovercolor:#8BA3CE,template_titlecolor:#6E8CC2,template_titlehovercolor:#8BA3CE,template_titlebackcolor:#ffffff,template_readmorecolor:#ffffff,template_readmorebackcolor:#8BA3CE,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#F18547,beforeloop_readmorehovercolor:#8BA3CE,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#8BA3CE,beforeloop_readmorehovercolor:#8BA3CE,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#6E8CC2,#8BA3CE,#555555,#999999',
												),
												'brit_co_bronzetone' => array(
													'preset_name' => esc_html__( 'Bronzetone', 'blog-designer-pro' ),
													'preset_value' => 'template_ftcolor:#555555,template_fthovercolor:#67704E,template_titlecolor:#414C22,template_titlehovercolor:#67704E,template_titlebackcolor:#ffffff,template_readmorecolor:#ffffff,template_readmorebackcolor:#67704E,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#67704E,beforeloop_readmorehovercolor:#67704E,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#67704E,beforeloop_readmorehovercolor:#67704E,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#414C22,#67704E,#555555,#999999',
												),
												'brit_co_lemon_ginger' => array(
													'preset_name' => esc_html__( 'Lemon Ginger', 'blog-designer-pro' ),
													'preset_value' => 'template_ftcolor:#555555,template_fthovercolor:#A39D5A,template_titlecolor:#8C8431,template_titlehovercolor:#A39D5A,template_titlebackcolor:#ffffff,template_readmorecolor:#ffffff,template_readmorebackcolor:#A39D5A,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#A39D5A,beforeloop_readmorehovercolor:#A39D5A,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#A39D5A,beforeloop_readmorehovercolor:#A39D5A,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#8C8431,#A39D5A,#555555,#999999',
												),
												'brit_co_alizarin' => array(
													'preset_name' => esc_html__( 'Alizarin', 'blog-designer-pro' ),
													'preset_value' => 'template_ftcolor:#555555,template_fthovercolor:#ED4961,template_titlecolor:#E81B3A,template_titlehovercolor:#ED4961,template_titlebackcolor:#ffffff,template_readmorecolor:#ffffff,template_readmorebackcolor:#ED4961,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#ED4961,beforeloop_readmorehovercolor:#ED4961,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#ED4961,beforeloop_readmorehovercolor:#ED4961,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#E81B3A,#ED4961,#555555,#999999',
												),
												'brit_co_toddy' => array(
													'preset_name' => esc_html__( 'Toddy', 'blog-designer-pro' ),
													'preset_value' => 'template_ftcolor:#555555,template_fthovercolor:#BE9055,template_titlecolor:#AE742A,template_titlehovercolor:#BE9055,template_titlebackcolor:#ffffff,template_readmorecolor:#ffffff,template_readmorebackcolor:#BE9055,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#BE9055,beforeloop_readmorehovercolor:#BE9055,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#BE9055,beforeloop_readmorehovercolor:#BE9055,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#AE742A,#BE9055,#555555,#999999',
												),
											),
											'brite'        => array(
												'brite_default' => array(
													'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#ffffff;template_ftcolor:#555555,template_fthovercolor:#0e83cd,winter_category_color:#0e83cd,template_titlecolor:#222222,template_titlehovercolor:#0e83cd,template_contentcolor:#545454,template_readmorecolor:#ffffff,template_readmorebackcolor:#0e83cd,
													template_readmore_hover_backcolor:#0e83cd,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#0e83cd,beforeloop_readmorehovercolor:#0e83cd,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#0e83cd,#222222,#555555,#545454',
												),
												'brite_mountain_meadow' => array(
													'preset_name' => esc_html__( 'Mountain Meadow', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#ffffff;template_ftcolor:#545454,template_fthovercolor:#5dbabc,winter_category_color:#21be85,template_titlecolor:#222222,template_titlehovercolor:#21be85,template_contentcolor:#545454,template_readmorecolor:#ffffff,template_readmorebackcolor:#21be85,
													template_readmore_hover_backcolor:#5dbabc,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#21be85,beforeloop_readmorehovercolor:#21be85,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#21be85,#5dbabc,#222222,#545454',
												),
												'brite_brandy_punch' => array(
													'preset_name' => esc_html__( 'Brandy Punch', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#ffffff;template_ftcolor:#545454,template_fthovercolor:#c27938,winter_category_color:#c27938,template_titlecolor:#c27938,template_titlehovercolor:#222222,template_contentcolor:#545454,template_readmorecolor:#ffffff,template_readmorebackcolor:#c27938,
													template_readmore_hover_backcolor:#c27938,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#c27938,beforeloop_readmorehovercolor:#c27938,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#c27938,#222222,#555555,#545454',
												),
											),
											'chapter'      => array(
												'chapter_default' => array(
													'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#e9181d,template_bgcolor:#000000,template_ftcolor:#ffffff,template_fthovercolor:#ffffff,template_titlecolor:#ffffff,template_titlehovercolor:#ffffff,template_contentcolor:#ffffff,template_readmorecolor:#ffffff,template_readmorebackcolor:#000000,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#e9181d,beforeloop_readmorehovercolor:#e9181d,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#e9181d,#000000,#ffffff,#ffffff',
												),
												'chapter_curious_blue' => array(
													'preset_name' => esc_html__( 'Curious Blue', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#257fb4,template_bgcolor:#000000,template_ftcolor:#ffffff,template_fthovercolor:#ffffff,template_titlecolor:#e1edf5,template_titlehovercolor:#ffffff,template_contentcolor:#e1edf5,template_readmorecolor:#ffffff,template_readmorebackcolor:#000000,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#257fb4,beforeloop_readmorehovercolor:#257fb4,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#257fb4,#000000,#e1edf5,#ffffff',
												),
												'chapter_buddha_gold' => array(
													'preset_name' => esc_html__( 'Buddha Gold', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#c4a618,template_bgcolor:#342b06,template_ftcolor:#f7f3e1,template_fthovercolor:#f7f3e1,template_titlecolor:#f0e7c3,template_titlehovercolor:#f7f3e1,template_contentcolor:#f7f3e1,template_readmorecolor:#f7f3e1,template_readmorebackcolor:#000000,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#c4a618,beforeloop_readmorehovercolor:#c4a618,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#c4a618,#342b06,#f0e7c3,#f7f3e1',
												),
												'chapter_sea_green' => array(
													'preset_name' => esc_html__( 'Sea Green', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#1ba3ab,template_bgcolor:#051b1d,template_ftcolor:#e1f3f4,template_fthovercolor:#e1f3f4,template_titlecolor:#c3e7e9,template_titlehovercolor:#e1f3f4,template_contentcolor:#e1f3f4,template_readmorecolor:#e1f3f4,template_readmorebackcolor:#000000,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#1ba3ab,beforeloop_readmorehovercolor:#1ba3ab,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#1ba3ab,#051b1d,#c3e7e9,#e1f3f4',
												),
												'chapter_fun_green' => array(
													'preset_name' => esc_html__( 'Fun Green', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#0E663C,template_bgcolor:#000000,template_ftcolor:#ffffff,template_fthovercolor:#ffffff,template_titlecolor:#ffffff,template_titlehovercolor:#ffffff,template_contentcolor:#ffffff,template_readmorecolor:#ffffff,template_readmorebackcolor:#000000,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#0E663C,beforeloop_readmorehovercolor:#0E663C,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#0E663C,#000000,#ffffff,#ffffff',
												),
												'chapter_madras' => array(
													'preset_name' => esc_html__( 'Madras', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#493917,template_bgcolor:#000000,template_ftcolor:#ffffff,template_fthovercolor:#ffffff,template_titlecolor:#ffffff,template_titlehovercolor:#ffffff,template_contentcolor:#ffffff,template_readmorecolor:#ffffff,template_readmorebackcolor:#000000,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#493917,beforeloop_readmorehovercolor:#493917,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#493917,#000000,#ffffff,#ffffff',
												),
											),
											'cover'        => array(
												'cover_default' => array(
													'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#f2f2f2,template_bgcolor:#f9f9f9,template_ftcolor:#fb6262,template_fthovercolor:#666666,template_titlecolor:#333333,template_titlehovercolor:#fb6262,template_titlebackcolor:,template_contentcolor:#666666,template_readmorecolor:#ffffff,template_readmorebackcolor:#666666,template_readmore_hover_backcolor:#fb6262,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#666666,beforeloop_readmorehovercolor:#ffffff,beforeloop_readmorehoverbackcolor:#fb6262',
													'display_value' => '#f2f2f2,#fb6262,#333333,#666666',
												),
												'cover_dodger_blue' => array(
													'preset_name' => esc_html__( 'Dodger Blue', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#E2F1FC,template_bgcolor:#f9f9f9,template_ftcolor:#2A97EA,template_fthovercolor:#666666,template_titlecolor:#333333,template_titlehovercolor:#2A97EA,template_titlebackcolor:,template_contentcolor:#666666,template_readmorecolor:#ffffff,template_readmorebackcolor:#666666,template_readmore_hover_backcolor:#2A97EA,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#666666,beforeloop_readmorehovercolor:#ffffff,beforeloop_readmorehoverbackcolor:#2A97EA',
													'display_value' => '#E2F1FC,#2A97EA,#333333,#666666',
												),
												'cover_west_side' => array(
													'preset_name' => esc_html__( 'West Side', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#fcf2e9,template_bgcolor:#f9f9f9,template_ftcolor:#EA7D2A,template_fthovercolor:#666666,template_titlecolor:#333333,template_titlehovercolor:#EA7D2A,template_titlebackcolor:,template_contentcolor:#666666,template_readmorecolor:#ffffff,template_readmorebackcolor:#666666,template_readmore_hover_backcolor:#EA7D2A,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#666666,beforeloop_readmorehovercolor:#ffffff,beforeloop_readmorehoverbackcolor:#EA7D2A',
													'display_value' => '#fcf2e9,#EA7D2A,#333333,#666666',
												),
												'cover_salem' => array(
													'preset_name' => esc_html__( 'Salem', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#e8f3ed,template_bgcolor:#f9f9f9,template_ftcolor:#198c4b,template_fthovercolor:#666666,template_titlecolor:#333333,template_titlehovercolor:#198c4b,template_titlebackcolor:,template_contentcolor:#666666,template_readmorecolor:#ffffff,template_readmorebackcolor:#666666,template_readmore_hover_backcolor:#198c4b,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#666666,beforeloop_readmorehovercolor:#ffffff,beforeloop_readmorehoverbackcolor:#198c4b',
													'display_value' => '#e8f3ed,#198c4b,#333333,#666666',
												),
												'cover_flame_red' => array(
													'preset_name' => esc_html__( 'Flame Red', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#f3e8e8,template_bgcolor:#f9f9f9,template_ftcolor:#8c1920,template_fthovercolor:#666666,template_titlecolor:#333333,template_titlehovercolor:#8c1920,template_titlebackcolor:,template_contentcolor:#666666,template_readmorecolor:#ffffff,template_readmorebackcolor:#666666,template_readmore_hover_backcolor:#8c1920,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#666666,beforeloop_readmorehovercolor:#ffffff,beforeloop_readmorehoverbackcolor:#8c1920',
													'display_value' => '#f3e8e8,#8c1920,#333333,#666666',
												),
												'cover_mulled_wine' => array(
													'preset_name' => esc_html__( 'Mulled Wine', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#f3e8e8,template_bgcolor:#f9f9f9,template_ftcolor:#544c6a,template_fthovercolor:#666666,template_titlecolor:#333333,template_titlehovercolor:#544c6a,template_titlebackcolor:,template_contentcolor:#666666,template_readmorecolor:#ffffff,template_readmorebackcolor:#666666,template_readmore_hover_backcolor:#544c6a,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#666666,beforeloop_readmorehovercolor:#ffffff,beforeloop_readmorehoverbackcolor:#544c6a',
													'display_value' => '#ededf0,#544c6a,#333333,#666666',
												),
											),
											'classical'    => array(
												'classical_default' => array(
													'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#ffffff,template_ftcolor:#2a97ea,template_fthovercolor:#555555,template_titlecolor:#222222,template_titlehovercolor:#2a97ea,template_titlebackcolor:#ffffff,template_contentcolor:#999999,template_readmorecolor:#f1f1f1,template_readmorebackcolor:#2a97ea,beforeloop_readmorecolor:#2a97ea,beforeloop_readmorebackcolor:#f1f1f1,beforeloop_readmorehovercolor:#f1f1f1,beforeloop_readmorehoverbackcolor:#2a97ea,template_readmore_hover_backcolor:#2a97ea',
													'display_value' => '#2a97ea,#222222,#555555,#999999',
												),
												'classical_cerulean' => array(
													'preset_name' => esc_html__( 'Cerulean', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#ffffff,template_ftcolor:#0E7699,template_fthovercolor:#3E91AD,template_titlecolor:#555555,template_titlehovercolor:#3E91AD,template_titlebackcolor:#ffffff,template_contentcolor:#999999,template_readmorecolor:#3E91AD,template_readmorebackcolor:#f1f1f1,beforeloop_readmorecolor:#3E91AD,beforeloop_readmorebackcolor:#f1f1f1,beforeloop_readmorehovercolor:#f1f1f1,beforeloop_readmorehoverbackcolor:#3E91AD,template_readmore_hover_backcolor:#0E7699',
													'display_value' => '#0E7699,#3E91AD,#555555,#999999',
												),
												'classical_fun_green' => array(
													'preset_name' => esc_html__( 'Fun Green', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#ffffff,template_ftcolor:#0E663C,template_fthovercolor:#3E8563,template_titlecolor:#555555,template_titlehovercolor:#3E8563,template_titlebackcolor:#ffffff,template_contentcolor:#999999,template_readmorecolor:#3E8563,template_readmorebackcolor:#f1f1f1,beforeloop_readmorecolor:#3E8563,beforeloop_readmorebackcolor:#f1f1f1,beforeloop_readmorehovercolor:#f1f1f1,beforeloop_readmorehoverbackcolor:#3E8563,template_readmore_hover_backcolor:#0E663C',
													'display_value' => '#0E663C,#3E8563,#555555,#999999',
												),
												'classical_blackberry' => array(
													'preset_name' => esc_html__( 'Blackberry', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#ffffff,template_ftcolor:#49182D,template_fthovercolor:#6D4657,template_titlecolor:#555555,template_titlehovercolor:#6D4657,template_titlebackcolor:#ffffff,template_contentcolor:#999999,template_readmorecolor:#6D4657,template_readmorebackcolor:#f1f1f1,beforeloop_readmorecolor:#6D4657,beforeloop_readmorebackcolor:#f1f1f1,beforeloop_readmorehovercolor:#f1f1f1,beforeloop_readmorehoverbackcolor:#6D4657,template_readmore_hover_backcolor:#49182D',
													'display_value' => '#49182D,#6D4657,#555555,#999999',
												),
												'classical_earls_green' => array(
													'preset_name' => esc_html__( 'Earls Green', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#ffffff,template_ftcolor:#C2AF2F,template_fthovercolor:#CEBF59,template_titlecolor:#555555,template_titlehovercolor:#CEBF59,template_titlebackcolor:#ffffff,template_contentcolor:#999999,template_readmorecolor:#CEBF59,template_readmorebackcolor:#f1f1f1,beforeloop_readmorecolor:#CEBF59,beforeloop_readmorebackcolor:#f1f1f1,beforeloop_readmorehovercolor:#f1f1f1,beforeloop_readmorehoverbackcolor:#CEBF59,template_readmore_hover_backcolor:#C2AF2F',
													'display_value' => '#C2AF2F,#CEBF59,#555555,#999999',
												),
												'classical_toddy' => array(
													'preset_name' => esc_html__( 'Toddy', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#ffffff,template_ftcolor:#AE742A,template_fthovercolor:#BE9055,template_titlecolor:#555555,template_titlehovercolor:#BE9055,template_titlebackcolor:#ffffff,template_contentcolor:#999999,template_readmorecolor:#BE9055,template_readmorebackcolor:#f1f1f1,beforeloop_readmorecolor:#BE9055,beforeloop_readmorebackcolor:#f1f1f1,beforeloop_readmorehovercolor:#f1f1f1,beforeloop_readmorehoverbackcolor:#BE9055,template_readmore_hover_backcolor:#AE742A',
													'display_value' => '#AE742A,#BE9055,#555555,#999999',
												),
											),
											'clicky'       => array(
												'clicky_default' => array(
													'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#adadad,template_fthovercolor:#444444,template_titlecolor:#5b7090,template_titlehovercolor:#000000,template_titlebackcolor:#ffffff,template_contentcolor:#444444,template_readmorecolor:#5b7090,template_readmorebackcolor:#ffffff,template_readmore_hover_backcolor:#e7e7e7,beforeloop_readmorecolor:#5b7090,beforeloop_readmorebackcolor:#ffffff,beforeloop_readmorehovercolor:#5b7090,beforeloop_readmorehoverbackcolor:#e7e7e7',
													'display_value' => '#ffffff,#5b7090,#444444,#000000',
												),
												'clicky_limeade' => array(
													'preset_name' => esc_html__( 'Limeade', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#AECA91,template_fthovercolor:#324E15,template_titlecolor:#629928,template_titlehovercolor:#20320E,template_titlebackcolor:#ffffff,template_contentcolor:#324E15,template_readmorecolor:#629928,template_readmorebackcolor:#E9F1E2,template_readmore_hover_backcolor:#AECA91,beforeloop_readmorecolor:#629928,beforeloop_readmorebackcolor:#E9F1E2,beforeloop_readmorehovercolor:#629928,beforeloop_readmorehoverbackcolor:#AECA91',
													'display_value' => '#E9F1E2,#629928,#324E15,#20320E',
												),
												'clicky_violet' => array(
													'preset_name' => esc_html__( 'Violet', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#CA9CFD,template_fthovercolor:#4E1F81,template_titlecolor:#983DFB,template_titlehovercolor:#321452,template_titlebackcolor:#ffffff,template_contentcolor:#4E1F81,template_readmorecolor:#983DFB,template_readmorebackcolor:#F1E5FD,template_readmore_hover_backcolor:#CA9CFD,beforeloop_readmorecolor:#983DFB,beforeloop_readmorebackcolor:#F1E5FD,beforeloop_readmorehovercolor:#983DFB,beforeloop_readmorehoverbackcolor:#CA9CFD',
													'display_value' => '#F1E5FD,#983DFB,#4E1F81,#321452',
												),
												'clicky_punga' => array(
													'preset_name' => esc_html__( 'Punga', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#A6A195,template_fthovercolor:#2A2518,template_titlecolor:#51492F,template_titlehovercolor:#1B180F,template_titlebackcolor:#ffffff,template_contentcolor:#2A2518,template_readmorecolor:#51492F,template_readmorebackcolor:#E7E7E4,template_readmore_hover_backcolor:#A6A195,beforeloop_readmorecolor:#51492F,beforeloop_readmorebackcolor:#E7E7E4,beforeloop_readmorehovercolor:#51492F,beforeloop_readmorehoverbackcolor:#A6A195',
													'display_value' => '#E7E7E4,#51492F,#2A2518,#1B180F',
												),
												'clicky_radical' => array(
													'preset_name' => esc_html__( 'Radical', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#F9A1A9,template_fthovercolor:#7C242C,template_titlecolor:#F34656,template_titlehovercolor:#4F171C,template_titlebackcolor:#ffffff,template_contentcolor:#7C242C,template_readmorecolor:#F34656,template_readmorebackcolor:#FDE7E9,template_readmore_hover_backcolor:#F9A1A9,beforeloop_readmorecolor:#F34656,beforeloop_readmorebackcolor:#FDE7E9,beforeloop_readmorehovercolor:#F34656,beforeloop_readmorehoverbackcolor:#F9A1A9',
													'display_value' => '#FDE7E9,#F34656,#7C242C,#4F171C',
												),
												'clicky_goldenrod' => array(
													'preset_name' => esc_html__( 'Goldenrod', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#D7BD81,template_fthovercolor:#5B4204,template_titlecolor:#B28007,template_titlehovercolor:#3A2A02,template_titlebackcolor:#ffffff,template_contentcolor:#5B4204,template_readmorecolor:#B28007,template_readmorebackcolor:#F4EDDD,template_readmore_hover_backcolor:#D7BD81,beforeloop_readmorecolor:#B28007,beforeloop_readmorebackcolor:#F4EDDD,beforeloop_readmorehovercolor:#B28007,beforeloop_readmorehoverbackcolor:#D7BD81',
													'display_value' => '#F4EDDD,#B28007,#5B4204,#3A2A02',
												),
											),
											'cool_horizontal' => array(
												'cool_horizontal_default' => array(
													'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
													'preset_value' => 'template_ftcolor:#555555,template_fthovercolor:#F16C20,template_titlecolor:#F16C20,template_titlehovercolor:#333333,template_readmorecolor:#F16C20,template_readmorebackcolor:#ffffff,template_contentcolor:#999999,beforeloop_readmorecolor:#F16C20,beforeloop_readmorebackcolor:#555555,beforeloop_readmorehovercolor:#F16C20,beforeloop_readmorehoverbackcolor:#555555',
													'display_value' => '#F16C20,#333333,#555555,#999999',
												),
												'cool_horizontal_crimson' => array(
													'preset_name' => esc_html__( 'Crimson', 'blog-designer-pro' ),
													'preset_value' => 'template_ftcolor:#666666,template_fthovercolor:#333333,template_titlecolor:#e21130,template_titlehovercolor:#666666,template_readmorecolor:#444444,template_readmorebackcolor:#ffffff,template_contentcolor:#444444,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#444444,beforeloop_readmorehovercolor:#ffffff,beforeloop_readmorehoverbackcolor:#999999',
													'display_value' => '#e21130,#333333,#666666,#444444',
												),
												'cool_horizontal_blue' => array(
													'preset_name' => esc_html__( 'Chetwode Blue', 'blog-designer-pro' ),
													'preset_value' => 'template_ftcolor:#666666,template_fthovercolor:#333333,template_titlecolor:#6C71C3,template_titlehovercolor:#666666,template_readmorecolor:#444444,template_readmorebackcolor:#ffffff,template_contentcolor:#444444,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#444444,beforeloop_readmorehovercolor:#ffffff,beforeloop_readmorehoverbackcolor:#999999',
													'display_value' => '#6C71C3,#333333,#666666,#444444',
												),
												'cool_horizontal_java' => array(
													'preset_name' => esc_html__( 'Java', 'blog-designer-pro' ),
													'preset_value' => 'template_ftcolor:#666666,template_fthovercolor:#333333,template_titlecolor:#29A198,template_titlehovercolor:#666666,template_readmorecolor:#444444,template_readmorebackcolor:#ffffff,template_contentcolor:#444444,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#444444,beforeloop_readmorehovercolor:#ffffff,beforeloop_readmorehoverbackcolor:#999999',
													'display_value' => '#29A198,#333333,#666666,#444444',
												),
												'cool_horizontal_curious_blue' => array(
													'preset_name' => esc_html__( 'Curious Blue', 'blog-designer-pro' ),
													'preset_value' => 'template_ftcolor:#666666,template_fthovercolor:#333333,template_titlecolor:#268BD3,template_titlehovercolor:#666666,template_readmorecolor:#444444,template_readmorebackcolor:#ffffff,template_contentcolor:#444444,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#444444,beforeloop_readmorehovercolor:#ffffff,beforeloop_readmorehoverbackcolor:#999999',
													'display_value' => '#268BD3,#333333,#666666,#444444',
												),
												'cool_horizontal_olive' => array(
													'preset_name' => esc_html__( 'Olive', 'blog-designer-pro' ),
													'preset_value' => 'template_ftcolor:#666666,template_fthovercolor:#333333,template_titlecolor:#869901,template_titlehovercolor:#666666,template_readmorecolor:#444444,template_readmorebackcolor:#ffffff,template_contentcolor:#444444,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#444444,beforeloop_readmorehovercolor:#ffffff,beforeloop_readmorehoverbackcolor:#999999',
													'display_value' => '#869901,#333333,#666666,#444444',
												),
											),
											'crayon_slider' => array(
												'crayon_slider_default' => array(
													'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#000000,template_ftcolor:#F5C034,template_fthovercolor:#ffffff,winter_category_color:#F5C034,template_titlecolor:#ffffff,template_titlehovercolor:#F5C034,template_contentcolor:#ffffff,template_readmorecolor:#ffffff,template_readmorebackcolor:#F5C034,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#F5C034,beforeloop_readmorehovercolor:#F5C034,beforeloop_readmorehoverbackcolor:#ffffff,template_readmore_hover_backcolor:#F5C034',
													'display_value' => '#000000,#ffffff,#F5C034,#ffffff',
												),
												'crayon_slider_cerise' => array(
													'preset_name' => esc_html__( 'Cerise', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#000000,template_ftcolor:#ffffff,template_fthovercolor:#ff00ae,winter_category_color:#ff00ae,template_titlecolor:#ffffff,template_titlehovercolor:#ff00ae,template_contentcolor:#ffffff,template_readmorecolor:#ffffff,template_readmorebackcolor:#ff00ae,template_readmorebackcolor:#ff00ae,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#ff00ae,beforeloop_readmorehovercolor:#ff00ae,beforeloop_readmorehoverbackcolor:#ffffff,template_readmore_hover_backcolor:#ff00ae',
													'display_value' => '#000000,#ffffff,#ff00ae,#ffffff',
												),
												'crayon_slider_radical_red' => array(
													'preset_name' => esc_html__( 'Radical Red', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#000000,template_ftcolor:#ffffff,template_fthovercolor:#FA336C,winter_category_color:#FA336C,template_titlecolor:#ffffff,template_titlehovercolor:#FA336C,,template_contentcolor:#ffffff,template_readmorecolor:#ffffff,template_readmorebackcolor:#FA336C,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#FA336C,beforeloop_readmorehovercolor:#FA336C,beforeloop_readmorehoverbackcolor:#ffffff,template_readmore_hover_backcolor:#FA336C',
													'display_value' => '#000000,#ffffff,#FA336C,#ffffff',
												),
												'crayon_slider_eminence' => array(
													'preset_name' => esc_html__( 'Eminence', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#000000,template_ftcolor:#ffffff,template_fthovercolor:#683C6F,winter_category_color:#683C6F,template_titlecolor:#ffffff,template_titlehovercolor:#683C6F,template_contentcolor:#ffffff,template_readmorecolor:#ffffff,template_readmorebackcolor:#683C6F,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#683C6F,beforeloop_readmorehovercolor:#683C6F,beforeloop_readmorehoverbackcolor:#ffffff,template_readmore_hover_backcolor:#683C6F',
													'display_value' => '#000000,#ffffff,#683C6F,#ffffff',
												),
												'crayon_slider_persian_red' => array(
													'preset_name' => esc_html__( 'Persian Red', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#000000,template_ftcolor:#ffffff,template_fthovercolor:#DC3330,winter_category_color:#DC3330,template_titlecolor:#ffffff,template_titlehovercolor:#DC3330,template_contentcolor:#ffffff,template_readmorecolor:#ffffff,template_readmorebackcolor:#DC3330,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#DC3330,beforeloop_readmorehovercolor:#DC3330,beforeloop_readmorehoverbackcolor:#ffffff,template_readmore_hover_backcolor:#DC3330',
													'display_value' => '#000000,#ffffff,#DC3330,#ffffff',
												),
												'crayon_slider_fun-green' => array(
													'preset_name' => esc_html__( 'Fun Green', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#000000,template_ftcolor:#ffffff,template_fthovercolor:#0E663C,winter_category_color:#0E663C,template_titlecolor:#ffffff,template_titlehovercolor:#0E663C,template_contentcolor:#ffffff,template_readmorecolor:#ffffff,template_readmorebackcolor:#0E663C,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#0E663C,beforeloop_readmorehovercolor:#0E663C,beforeloop_readmorehoverbackcolor:#ffffff,template_readmore_hover_backcolor:#0E663C',
													'display_value' => '#000000,#ffffff,#0E663C,#ffffff',
												),
											),
											'deport'       => array(
												'deport_default' => array(
													'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
													'preset_value' => 'template_ftcolor:#92A660,template_fthovercolor:#555555,deport_dashcolor:#92A660,template_titlecolor:#222222,template_titlehovercolor:#92A660,template_readmorecolor:#ffffff,template_readmorebackcolor:#92A660,template_contentcolor:#777777,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#92A660,beforeloop_readmorehovercolor:#92A660,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#92A660,#222222,#555555,#777777',
												),
												'deport_catalina_blue' => array(
													'preset_name' => esc_html__( 'Catalina Blue', 'blog-designer-pro' ),
													'preset_value' => 'template_ftcolor:#999999,template_fthovercolor:#495F85,deport_dashcolor:#495F85,template_titlecolor:#1B3766,template_titlehovercolor:#495F85,template_readmorecolor:#ffffff,template_readmorebackcolor:#495F85,template_contentcolor:#999999,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#495F85,beforeloop_readmorehovercolor:#495F85,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#1B3766,#495F85,#555555,#999999',
												),
												'deport_fun-green' => array(
													'preset_name' => esc_html__( 'Fun Green', 'blog-designer-pro' ),
													'preset_value' => 'template_ftcolor:#3E8563,template_fthovercolor:#999999,deport_dashcolor:#3E8563,template_titlecolor:#0E663C,template_titlehovercolor:#3E8563,template_readmorecolor:#ffffff,template_readmorebackcolor:#3E8563,template_contentcolor:#999999,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#3E8563,beforeloop_readmorehovercolor:#3E8563,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#0E663C,#3E8563,#555555,#999999',
												),
												'deport_alizarin' => array(
													'preset_name' => esc_html__( 'Alizarin', 'blog-designer-pro' ),
													'preset_value' => 'template_ftcolor:#999999,template_fthovercolor:#ED4961,deport_dashcolor:#ED4961,template_titlecolor:#E81B3A,template_titlehovercolor:#ED4961,template_readmorecolor:#ffffff,template_readmorebackcolor:#ED4961,template_contentcolor:#999999,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#ED4961,beforeloop_readmorehovercolor:#ED4961,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#E81B3A,#ED4961,#555555,#999999',
												),
												'deport_mckenzie' => array(
													'preset_name' => esc_html__( 'McKenzie', 'blog-designer-pro' ),
													'preset_value' => 'template_ftcolor:#999999,template_fthovercolor:#A2855B,deport_dashcolor:#A2855B,template_titlecolor:#8B6632,template_titlehovercolor:#A2855B,template_readmorecolor:#ffffff,template_readmorebackcolor:#A2855B,template_contentcolor:#999999,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#A2855B,beforeloop_readmorehovercolor:#A2855B,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#8B6632,#A2855B,#555555,#999999',
												),
												'deport_blackberry' => array(
													'preset_name' => esc_html__( 'Blackberry', 'blog-designer-pro' ),
													'preset_value' => 'template_ftcolor:#999999,template_fthovercolor:#6D4657,deport_dashcolor:#6D4657,template_titlecolor:#49182D,template_titlehovercolor:#6D4657,template_readmorecolor:#ffffff,template_readmorebackcolor:#6D4657,template_contentcolor:#999999,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#6D4657,beforeloop_readmorehovercolor:#6D4657,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#49182D,#6D4657,#555555,#999999',
												),
											),
											'easy_timeline' => array(
												'easy_timeline_default' => array(
													'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#ffffff,template_ftcolor:#C58A3C,template_fthovercolor:#555555,template_titlecolor:#222222,template_titlehovercolor:#C58A3C,template_readmorecolor:#C58A3C,template_readmorebackcolor:#ffffff,template_contentcolor:#555555',
													'display_value' => '#ffffff,#C58A3C,#222222,#555555',
												),
												'easy_timeline_crimson' => array(
													'preset_name' => esc_html__( 'Crimson', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#E21130,template_ftcolor:#ffffff,template_fthovercolor:#f1f1f1,template_titlecolor:#ffffff,template_titlehovercolor:#f1f1f1,template_readmorecolor:#E21130,template_readmorebackcolor:#ffffff,template_contentcolor:#ffffff',
													'display_value' => '#E21130,#ffffff,#f1f1f1,#E21130',
												),
												'easy_timeline_flamenco' => array(
													'preset_name' => esc_html__( 'Flamenco', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#E18942,template_ftcolor:#ffffff,template_fthovercolor:#f1f1f1,template_titlecolor:#ffffff,template_titlehovercolor:#f1f1f1,template_readmorecolor:#E18942,template_readmorebackcolor:#ffffff,template_contentcolor:#ffffff',
													'display_value' => '#E18942,#ffffff,#f1f1f1,#E18942',
												),
												'easy_timeline_jagger' => array(
													'preset_name' => esc_html__( 'Jagger', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#3D3242,template_ftcolor:#ffffff,template_fthovercolor:#f1f1f1,template_titlecolor:#ffffff,template_titlehovercolor:#f1f1f1,template_readmorecolor:#3D3242,template_readmorebackcolor:#ffffff,template_contentcolor:#ffffff',
													'display_value' => '#3D3242,#ffffff,#f1f1f1,#3D3242',
												),
												'easy_timeline_camelot' => array(
													'preset_name' => esc_html__( 'Camelot', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#7A3E48,template_ftcolor:#ffffff,template_fthovercolor:#f1f1f1,template_titlecolor:#ffffff,template_titlehovercolor:#f1f1f1,template_readmorecolor:#7A3E48,template_readmorebackcolor:#ffffff,template_contentcolor:#ffffff',
													'display_value' => '#7A3E48,#ffffff,#f1f1f1,#7A3E48',
												),
												'easy_timeline_sundance' => array(
													'preset_name' => esc_html__( 'Sundance', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#C59F4A,template_ftcolor:#ffffff,template_fthovercolor:#f1f1f1,template_titlecolor:#ffffff,template_titlehovercolor:#f1f1f1,template_readmorecolor:#C59F4A,template_readmorebackcolor:#ffffff,template_contentcolor:#ffffff',
													'display_value' => '#C59F4A,#ffffff,#f1f1f1,#C59F4A',
												),
											),
											'elina'        => array(
												'elina_default' => array(
													'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#3E91AD,template_fthovercolor:#555555,template_titlecolor:#222222,template_titlehovercolor:#3E91AD,template_readmorecolor:#3E91AD,template_readmorebackcolor:#666666,template_contentcolor:#666666,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#3E91AD,beforeloop_readmorehovercolor:#3E91AD,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#3E91AD,#222222,#555555,#666666',
												),
												'elina_crimson' => array(
													'preset_name' => esc_html__( 'Crimson', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#FDEEF1,template_ftcolor:#555555,template_fthovercolor:#E84159,template_titlecolor:#E21130,template_titlehovercolor:#E84159,template_readmorecolor:#E84159,template_readmorebackcolor:#666666,template_contentcolor:#666666,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#E84159,beforeloop_readmorehovercolor:#E84159,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#E21130,#E84159,#555555,#666666',
												),
												'elina_bronzetone' => array(
													'preset_name' => esc_html__( 'Bronzetone', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#F1F3F0,template_ftcolor:#555555,template_fthovercolor:#67704E,template_titlecolor:#414C22,template_titlehovercolor:#67704E,template_readmorecolor:#67704E,template_readmorebackcolor:#666666,template_contentcolor:#666666,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#67704E,beforeloop_readmorehovercolor:#67704E,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#414C22,#67704E,#555555,#666666',
												),
												'elina_toddy' => array(
													'preset_name' => esc_html__( 'Toddy', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#F9F5F1,template_ftcolor:#555555,template_fthovercolor:#BE9055,template_titlecolor:#AE742A,template_titlehovercolor:#BE9055,template_readmorecolor:#BE9055,template_readmorebackcolor:#666666,template_contentcolor:#666666,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#BE9055,beforeloop_readmorehovercolor:#BE9055,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#AE742A,#BE9055,#555555,#666666',
												),
												'elina_blackberry' => array(
													'preset_name' => esc_html__( 'Blackberry', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#F3F0F1,template_ftcolor:#555555,template_fthovercolor:#6D4657,template_titlecolor:#49182D,template_titlehovercolor:#6D4657,template_readmorecolor:#6D4657,template_readmorebackcolor:#666666,template_contentcolor:#666666,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#6D4657,beforeloop_readmorehovercolor:#6D4657,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#49182D,#6D4657,#555555,#666666',
												),
												'elina_ce_soir' => array(
													'preset_name' => esc_html__( 'Ce Soir', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#F0E9F4,template_ftcolor:#555555,template_fthovercolor:#A381BB,template_titlecolor:#1B3766,template_titlehovercolor:#A381BB,template_readmorecolor:#A381BB,template_readmorebackcolor:#666666,template_contentcolor:#666666,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#A381BB,beforeloop_readmorehovercolor:#A381BB,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#1B3766,#A381BB,#555555,#666666',
												),
											),
											'evolution'    => array(
												'evolution_default' => array(
													'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#ffffff,template_alterbgcolor:#ffffff,template_ftcolor:#2E6480,template_fthovercolor:#555555,template_titlecolor:#222222,template_titlehovercolor:#2E6480,template_titlebackcolor:#ffffff,template_readmorecolor:#ffffff,template_readmorebackcolor:#2E6480,template_contentcolor:#333333,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#2E6480,beforeloop_readmorehovercolor:#2E6480,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#2E6480,#222222,#555555,#333333',
												),
												'evolution_pompadour' => array(
													'preset_name' => esc_html__( 'Pompadour', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#ffffff,template_alterbgcolor:#FFFFFF,template_ftcolor:#555555,template_fthovercolor:#974772,template_titlecolor:#7D194F,template_titlehovercolor:#974772,template_titlebackcolor:#ffffff,template_readmorecolor:#ffffff,template_readmorebackcolor:#974772,template_contentcolor:#333333,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#974772,beforeloop_readmorehovercolor:#974772,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#7D194F,#974772,#555555,#333333',
												),
												'evolution_bronzetone' => array(
													'preset_name' => esc_html__( 'Bronzetone', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#ffffff,template_alterbgcolor:#FFFFFF,template_ftcolor:#555555,template_fthovercolor:#67704E,template_titlecolor:#414C22,template_titlehovercolor:#67704E,template_titlebackcolor:#ffffff,template_readmorecolor:#ffffff,template_readmorebackcolor:#67704E,template_contentcolor:#333333,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#67704E,beforeloop_readmorehovercolor:#67704E,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#414C22,#67704E,#555555,#333333',
												),
												'evolution_alizarin' => array(
													'preset_name' => esc_html__( 'Alizarin', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#ffffff,template_alterbgcolor:#FFFFFF,template_ftcolor:#555555,template_fthovercolor:#EE4861,template_titlecolor:#EA1A3A,template_titlehovercolor:#EE4861,template_titlebackcolor:#ffffff,template_readmorecolor:#ffffff,template_readmorebackcolor:#EE4861,template_contentcolor:#333333,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#EE4861,beforeloop_readmorehovercolor:#EE4861,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#EA1A3A,#EE4861,#555555,#333333',
												),
												'evolution_buttercup' => array(
													'preset_name' => esc_html__( 'Buttercup', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#ffffff,template_alterbgcolor:#FFFFFF,template_ftcolor:#555555,template_fthovercolor:#E5A452,template_titlecolor:#DF8D27,template_titlehovercolor:#EE4861,template_titlebackcolor:#ffffff,template_readmorecolor:#ffffff,template_readmorebackcolor:#E5A452,template_contentcolor:#333333,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#E5A452,beforeloop_readmorehovercolor:#E5A452,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#DF8D27,#E5A452,#555555,#333333',
												),
												'evolution_ce_soir' => array(
													'preset_name' => esc_html__( 'Ce Soir', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#ffffff,template_alterbgcolor:#FFFFFF,template_ftcolor:#555555,template_fthovercolor:#A381BB,template_titlecolor:#8C62AA,template_titlehovercolor:#A381BB,template_titlebackcolor:#ffffff,template_readmorecolor:#ffffff,template_readmorebackcolor:#A381BB,template_contentcolor:#333333,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#A381BB,beforeloop_readmorehovercolor:#A381BB,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#8C62AA,#A381BB,#555555,#333333',
												),
											),
											'explore'      => array(
												'explore_default' => array(
													'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
													'preset_value' => 'grid_hoverback_color:#000000,template_ftcolor:#e0e0e0,template_fthovercolor:#ffffff,template_titlecolor:#e0e0e0,template_titlehovercolor:#ffffff,beforeloop_readmorecolor:#e0e0e0,beforeloop_readmorebackcolor:#000000,beforeloop_readmorehovercolor:#ffffff,beforeloop_readmorehoverbackcolor:#000000',
													'display_value' => '#000000,#e0e0e0,#ffffff,#e0e0e0',
												),
												'explore_mariner' => array(
													'preset_name' => esc_html__( 'Mariner', 'blog-designer-pro' ),
													'preset_value' => 'grid_hoverback_color:#465BAC,template_ftcolor:#e0e0e0,template_fthovercolor:#ffffff,template_titlecolor:#e0e0e0,template_titlehovercolor:#ffffff,beforeloop_readmorecolor:#465BAC,beforeloop_readmorebackcolor:#e0e0e0,beforeloop_readmorehovercolor:#ffffff,beforeloop_readmorehoverbackcolor:#465BAC',
													'display_value' => '#465BAC,#e0e0e0,#ffffff,#e0e0e0',
												),
												'explore_radical_red' => array(
													'preset_name' => esc_html__( 'Radical Red', 'blog-designer-pro' ),
													'preset_value' => 'grid_hoverback_color:#FA336C,template_ftcolor:#e0e0e0,template_fthovercolor:#ffffff,template_titlecolor:#e0e0e0,template_titlehovercolor:#ffffff,beforeloop_readmorecolor:#FA336C,beforeloop_readmorebackcolor:#e0e0e0,beforeloop_readmorehovercolor:#ffffff,beforeloop_readmorehoverbackcolor:#FA336C',
													'display_value' => '#FA336C,#e0e0e0,#ffffff,#e0e0e0',
												),
												'explore_finch' => array(
													'preset_name' => esc_html__( 'Finch', 'blog-designer-pro' ),
													'preset_value' => 'grid_hoverback_color:#75815B,template_ftcolor:#e0e0e0,template_fthovercolor:#ffffff,template_titlecolor:#e0e0e0,template_titlehovercolor:#ffffff,beforeloop_readmorecolor:#75815B,beforeloop_readmorebackcolor:#e0e0e0,beforeloop_readmorehovercolor:#ffffff,beforeloop_readmorehoverbackcolor:#75815B',
													'display_value' => '#75815B,#e0e0e0,#ffffff,#e0e0e0',
												),
												'explore_vivid_gamboge' => array(
													'preset_name' => esc_html__( 'Vivid Gamboge', 'blog-designer-pro' ),
													'preset_value' => 'grid_hoverback_color:#f99900,template_ftcolor:#e0e0e0,template_fthovercolor:#ffffff,template_titlecolor:#e0e0e0,template_titlehovercolor:#ffffff,beforeloop_readmorecolor:#f99900,beforeloop_readmorebackcolor:#e0e0e0,beforeloop_readmorehovercolor:#ffffff,beforeloop_readmorehoverbackcolor:#f999000',
													'display_value' => '#f99900,#e0e0e0,#ffffff,#e0e0e0',
												),
												'explore_moderate-orange' => array(
													'preset_name' => esc_html__( 'Moderate Orange', 'blog-designer-pro' ),
													'preset_value' => 'grid_hoverback_color:#8B6632,template_ftcolor:#e0e0e0,template_fthovercolor:#ffffff,template_titlecolor:#e0e0e0,template_titlehovercolor:#ffffff,beforeloop_readmorecolor:#8B6632,beforeloop_readmorebackcolor:#e0e0e0,beforeloop_readmorehovercolor:#ffffff,beforeloop_readmorehoverbackcolor:#8B6632',
													'display_value' => '#8B6632,#e0e0e0,#ffffff,#e0e0e0',
												),
											),
											'famous'       => array(
												'famous_default' => array(
													'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#f42887,template_fthovercolor:#333333,template_titlecolor:#777777,template_titlehovercolor:#333333,template_contentcolor:#333333,template_readmorecolor:#ffffff,template_readmorebackcolor:#f42887,template_readmore_hover_backcolor:#333333,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#f42887,beforeloop_readmorehovercolor:#ffffff,beforeloop_readmorehoverbackcolor:#f42887',
													'display_value' => '#ffffff,#f42887,#333333,#777777',
												),
												'famous_vivid_gamboge' => array(
													'preset_name' => esc_html__( 'Vivid Gamboge', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#fef4e5,template_ftcolor:#f99900,template_fthovercolor:#333333,template_titlecolor:#777777,template_titlehovercolor:#333333,template_contentcolor:#333333,template_readmorecolor:#ffffff,template_readmorebackcolor:#f99900,template_readmore_hover_backcolor:#333333,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#f99900,beforeloop_readmorehovercolor:#ffffff,beforeloop_readmorehoverbackcolor:#f99900',
													'display_value' => '#fef4e5,#f99900,#333333,#777777',
												),
												'famous_jagger' => array(
													'preset_name' => esc_html__( 'Jagger', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#ebeaec,template_ftcolor:#3d3242,template_fthovercolor:#333333,template_titlecolor:#777777,template_titlehovercolor:#333333,template_contentcolor:#333333,template_readmorecolor:#ffffff,template_readmorebackcolor:#3d3242,template_readmore_hover_backcolor:#333333,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#3d3242,beforeloop_readmorehovercolor:#ffffff,beforeloop_readmorehoverbackcolor:#3d3242',
													'display_value' => '#ebeaec,#3d3242,#333333,#777777',
												),
												'famous_timber_green' => array(
													'preset_name' => esc_html__( 'Timber Green', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#ebeaec,template_ftcolor:#374232,template_fthovercolor:#333333,template_titlecolor:#777777,template_titlehovercolor:#333333,template_contentcolor:#333333,template_readmorecolor:#ffffff,template_readmorebackcolor:#374232,template_readmore_hover_backcolor:#333333,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#374232,beforeloop_readmorehovercolor:#ffffff,beforeloop_readmorehoverbackcolor:#374232',
													'display_value' => '#ebecea,#374232,#333333,#777777',
												),
												'famous_barossa' => array(
													'preset_name' => esc_html__( 'Barossa', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#ebeaec,template_ftcolor:#423237,template_fthovercolor:#333333,template_titlecolor:#777777,template_titlehovercolor:#333333,template_contentcolor:#333333,template_readmorecolor:#ffffff,template_readmorebackcolor:#423237,template_readmore_hover_backcolor:#333333,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#423237,beforeloop_readmorehovercolor:#ffffff,beforeloop_readmorehoverbackcolor:#423237',
													'display_value' => '#eceaeb,#423237,#333333,#777777',
												),
												'famous_blumine' => array(
													'preset_name' => esc_html__( 'Blumine', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#e9eeef,template_ftcolor:#2a5b66,template_fthovercolor:#333333,template_titlecolor:#777777,template_titlehovercolor:#333333,template_contentcolor:#333333,template_readmorecolor:#ffffff,template_readmorebackcolor:#2a5b66,template_readmore_hover_backcolor:#333333,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#2a5b66,beforeloop_readmorehovercolor:#ffffff,beforeloop_readmorehoverbackcolor:#2a5b66',
													'display_value' => '#e9eeef,#2a5b66,#333333,#777777',
												),
											),
											'fairy'        => array(
												'fairy_default' => array(
													'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#f7f7f7,template_bgcolor:#ffffff,template_ftcolor:#333333,template_fthovercolor:#888888,template_titlecolor:#ffffff,template_titlehovercolor:#e5d3d3,template_titlebackcolor:,template_contentcolor:#333333,template_readmorecolor:#ffffff,template_readmorebackcolor:#333333,template_readmore_hover_backcolor:#888888,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#333333,beforeloop_readmorehovercolor:#ffffff,beforeloop_readmorehoverbackcolor:#888888',
													'display_value' => '#f7f7f7,#888888,#333333,#000000',
												),
												'fairy_tangerine' => array(
													'preset_name' => esc_html__( 'Tangerine', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#f8df9f,template_bgcolor:#fdf7e7,template_ftcolor:#171101,template_fthovercolor:#473505,template_titlecolor:#fdf7e7,template_titlehovercolor:#f8df9f,template_titlebackcolor:,template_contentcolor:#171101,template_readmorecolor:#fdf7e7,template_readmorebackcolor:#171101,template_readmore_hover_backcolor:#efb828,beforeloop_readmorecolor:#fdf7e7,beforeloop_readmorebackcolor:#171101,beforeloop_readmorehovercolor:#fdf7e7,beforeloop_readmorehoverbackcolor:#efb828',
													'display_value' => '#f8df9f,#efb828,#473505,#171101',
												),
												'fairy_prussian' => array(
													'preset_name' => esc_html__( 'Prussian', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#99a8ba,template_bgcolor:#e5e9ed,template_ftcolor:#000308,template_fthovercolor:#000b19,template_titlecolor:#e5e9ed,template_titlehovercolor:#99a8ba,template_titlebackcolor:,template_contentcolor:#000308,template_readmorecolor:#e5e9ed,template_readmorebackcolor:#000308,template_readmore_hover_backcolor:#193b65,beforeloop_readmorecolor:#e5e9ed,beforeloop_readmorebackcolor:#000308,beforeloop_readmorehovercolor:#e5e9ed,beforeloop_readmorehoverbackcolor:#193b65',
													'display_value' => '#99a8ba,#193b65,#000b19,#000308',
												),
											),
											'glamour'      => array(
												'glamour_default' => array(
													'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#000000,template_ftcolor:#f5c034,template_fthovercolor:#ffffff,template_titlecolor:#ffffff,template_titlehovercolor:#f5c034,template_titlebackcolor:,template_contentcolor:#ffffff,template_readmorecolor:#f5c034,template_readmorebackcolor:#2d2d2d,template_readmore_hover_backcolor:#000000,beforeloop_readmorecolor:#2d2d2d,beforeloop_readmorebackcolor:#000000,beforeloop_readmorehovercolor:#2d2d2d,beforeloop_readmorehoverbackcolor:#000000',
													'display_value' => '#f5c034,#333333,#2d2d2d,#000000',
												),
												'glamour_aqua' => array(
													'preset_name' => esc_html__( 'Aqua', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#000000,template_ftcolor:#00FFE9,template_fthovercolor:#ffffff,template_titlecolor:#ffffff,template_titlehovercolor:#00FFE9,template_titlebackcolor:,template_contentcolor:#ffffff,template_readmorecolor:#00FFE9,template_readmorebackcolor:#2d2d2d,template_readmore_hover_backcolor:#000000,beforeloop_readmorecolor:#2d2d2d,beforeloop_readmorebackcolor:#000000,beforeloop_readmorehovercolor:#2d2d2d,beforeloop_readmorehoverbackcolor:#000000',
													'display_value' => '#00FFE9,#333333,#2d2d2d,#000000',
												),
												'glamour_harlequin' => array(
													'preset_name' => esc_html__( 'Harlequin', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#000000,template_ftcolor:#43FF00,template_fthovercolor:#ffffff,template_titlecolor:#ffffff,template_titlehovercolor:#43FF00,template_titlebackcolor:,template_contentcolor:#ffffff,template_readmorecolor:#43FF00,template_readmorebackcolor:#2d2d2d,template_readmore_hover_backcolor:#000000,beforeloop_readmorecolor:#2d2d2d,beforeloop_readmorebackcolor:#000000,beforeloop_readmorehovercolor:#2d2d2d,beforeloop_readmorehoverbackcolor:#000000',
													'display_value' => '#43FF00,#333333,#2d2d2d,#000000',
												),
												'glamour_red' => array(
													'preset_name' => esc_html__( 'Red', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#000000,template_ftcolor:#FF0000,template_fthovercolor:#ffffff,template_titlecolor:#ffffff,template_titlehovercolor:#FF0000,template_titlebackcolor:,template_contentcolor:#ffffff,template_readmorecolor:#FF0000,template_readmorebackcolor:#2d2d2d,template_readmore_hover_backcolor:#000000,beforeloop_readmorecolor:#2d2d2d,beforeloop_readmorebackcolor:#000000,beforeloop_readmorehovercolor:#2d2d2d,beforeloop_readmorehoverbackcolor:#000000',
													'display_value' => '#FF0000,#333333,#2d2d2d,#000000',
												),
												'glamour_spring_green' => array(
													'preset_name' => esc_html__( 'Spring Green', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#000000,template_ftcolor:#00FF80,template_fthovercolor:#ffffff,template_titlecolor:#ffffff,template_titlehovercolor:#00FF80,template_titlebackcolor:,template_contentcolor:#ffffff,template_readmorecolor:#00FF80,template_readmorebackcolor:#2d2d2d,template_readmore_hover_backcolor:#000000,beforeloop_readmorecolor:#2d2d2d,beforeloop_readmorebackcolor:#000000,beforeloop_readmorehovercolor:#2d2d2d,beforeloop_readmorehoverbackcolor:#000000',
													'display_value' => '#00FF80,#333333,#2d2d2d,#000000',
												),
												'glamour_pale_turquoise' => array(
													'preset_name' => esc_html__( 'Pale Turquoise', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#000000,template_ftcolor:#ACFEFF,template_fthovercolor:#ffffff,template_titlecolor:#ffffff,template_titlehovercolor:#ACFEFF,template_titlebackcolor:,template_contentcolor:#ffffff,template_readmorecolor:#ACFEFF,template_readmorebackcolor:#2d2d2d,template_readmore_hover_backcolor:#000000,beforeloop_readmorecolor:#2d2d2d,beforeloop_readmorebackcolor:#000000,beforeloop_readmorehovercolor:#2d2d2d,beforeloop_readmorehoverbackcolor:#000000',
													'display_value' => '#ACFEFF,#333333,#2d2d2d,#000000',
												),
											),
											'glossary'     => array(
												'glossary_default' => array(
													'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#E84159,template_fthovercolor:#555555,template_titlecolor:#222222,template_titlehovercolor:#E84159,template_titlebackcolor:#ffffff,template_contentcolor:#999999,template_content_hovercolor:#E84159,template_readmorecolor:#ffffff,template_readmorebackcolor:#E84159,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#E84159,beforeloop_readmorehovercolor:#E84159,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#E84159,#222222,#555555,#999999',
												),
												'glossary_madras' => array(
													'preset_name' => esc_html__( 'Madras', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#555555,template_fthovercolor:#6D6145,template_titlecolor:#493917,template_titlehovercolor:#6D6145,template_titlebackcolor:#ffffff,template_contentcolor:#999999,template_content_hovercolor:#6D6145,template_readmorecolor:#ffffff,template_readmorebackcolor:#6D6145,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#6D6145,beforeloop_readmorehovercolor:#6D6145,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#493917,#6D6145,#555555,#999999',
												),
												'glossary_pompadour' => array(
													'preset_name' => esc_html__( 'Pompadour', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#555555,template_fthovercolor:#974772,template_titlecolor:#7D194F,template_titlehovercolor:#974772,template_titlebackcolor:#ffffff,template_contentcolor:#999999,template_content_hovercolor:#974772,template_readmorecolor:#ffffff,template_readmorebackcolor:#974772,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#974772,beforeloop_readmorehovercolor:#974772,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#7D194F,#974772,#555555,#999999',
												),
												'glossary_bronzetone' => array(
													'preset_name' => esc_html__( 'Bronzetone', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#E5E7E1,template_ftcolor:#555555,template_fthovercolor:#67704E,template_titlecolor:#414C22,template_titlehovercolor:#6D6145,template_titlebackcolor:#E5E7E1,template_contentcolor:#999999,template_content_hovercolor:#67704E,template_readmorecolor:#ffffff,template_readmorebackcolor:#67704E,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#67704E,beforeloop_readmorehovercolor:#67704E,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#414C22,#67704E,#555555,#999999',
												),
												'glossary_peru_tan' => array(
													'preset_name' => esc_html__( 'Peru Tan', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#EDE5E1,template_ftcolor:#555555,template_fthovercolor:#916748,template_titlecolor:#75411A,template_titlehovercolor:#916748,template_titlebackcolor:#EDE5E1,template_contentcolor:#999999,template_content_hovercolor:#916748,template_readmorecolor:#ffffff,template_readmorebackcolor:#916748,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#916748,beforeloop_readmorehovercolor:#916748,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#75411A,#916748,#555555,#999999',
												),
												'glossary_buttercup' => array(
													'preset_name' => esc_html__( 'Buttercup', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#FAF0E2,template_ftcolor:#555555,template_fthovercolor:#E5A452,template_titlecolor:#DF8D27,template_titlehovercolor:#E5A452,template_titlebackcolor:#FAF0E2,template_contentcolor:#999999,template_content_hovercolor:#E5A452,template_readmorecolor:#ffffff,template_readmorebackcolor:#E5A452,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#E5A452,beforeloop_readmorehovercolor:#E5A452,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#DF8D27,#E5A452,#555555,#999999',
												),
											),
											'hoverbic'     => array(
												'hoverbic_default' => array(
													'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
													'preset_value' => 'grid_hoverback_color:#000000,template_ftcolor:#ff9600,template_fthovercolor:#ffffff,template_titlecolor:#e0e0e0,template_titlehovercolor:#ff9600,beforeloop_readmorecolor:#e0e0e0,beforeloop_readmorebackcolor:#000000,beforeloop_readmorehovercolor:#ffffff,beforeloop_readmorehoverbackcolor:#000000',
													'display_value' => '#000000,#ff9600,#ffffff,#e0e0e0',
												),
												'hoverbic_mariner' => array(
													'preset_name' => esc_html__( 'Mariner', 'blog-designer-pro' ),
													'preset_value' => 'grid_hoverback_color:#465BAC,template_ftcolor:#e0e0e0,template_fthovercolor:#ffffff,template_titlecolor:#e0e0e0,template_titlehovercolor:#ffffff,beforeloop_readmorecolor:#465BAC,beforeloop_readmorebackcolor:#e0e0e0,beforeloop_readmorehovercolor:#ffffff,beforeloop_readmorehoverbackcolor:#465BAC',
													'display_value' => '#465BAC,#e0e0e0,#ffffff,#e0e0e0',
												),
												'hoverbic_radical_red' => array(
													'preset_name' => esc_html__( 'Radical Red', 'blog-designer-pro' ),
													'preset_value' => 'grid_hoverback_color:#FA336C,template_ftcolor:#e0e0e0,template_fthovercolor:#ffffff,template_titlecolor:#e0e0e0,template_titlehovercolor:#ffffff,beforeloop_readmorecolor:#FA336C,beforeloop_readmorebackcolor:#e0e0e0,beforeloop_readmorehovercolor:#ffffff,beforeloop_readmorehoverbackcolor:#FA336C',
													'display_value' => '#FA336C,#e0e0e0,#ffffff,#e0e0e0',
												),
												'hoverbic_finch' => array(
													'preset_name' => esc_html__( 'Finch', 'blog-designer-pro' ),
													'preset_value' => 'grid_hoverback_color:#75815B,template_ftcolor:#e0e0e0,template_fthovercolor:#ffffff,template_titlecolor:#e0e0e0,template_titlehovercolor:#ffffff,beforeloop_readmorecolor:#75815B,beforeloop_readmorebackcolor:#e0e0e0,beforeloop_readmorehovercolor:#ffffff,beforeloop_readmorehoverbackcolor:#75815B',
													'display_value' => '#75815B,#e0e0e0,#ffffff,#e0e0e0',
												),
												'hoverbic_vivid_gamboge' => array(
													'preset_name' => esc_html__( 'Vivid Gamboge', 'blog-designer-pro' ),
													'preset_value' => 'grid_hoverback_color:#f99900,template_ftcolor:#e0e0e0,template_fthovercolor:#ffffff,template_titlecolor:#e0e0e0,template_titlehovercolor:#ffffff,beforeloop_readmorecolor:#f99900,beforeloop_readmorebackcolor:#e0e0e0,beforeloop_readmorehovercolor:#ffffff,beforeloop_readmorehoverbackcolor:#f999000',
													'display_value' => '#f99900,#e0e0e0,#ffffff,#e0e0e0',
												),
												'hoverbic_moderate-orange' => array(
													'preset_name' => esc_html__( 'Moderate Orange', 'blog-designer-pro' ),
													'preset_value' => 'grid_hoverback_color:#8B6632,template_ftcolor:#e0e0e0,template_fthovercolor:#ffffff,template_titlecolor:#e0e0e0,template_titlehovercolor:#ffffff,beforeloop_readmorecolor:#8B6632,beforeloop_readmorebackcolor:#e0e0e0,beforeloop_readmorehovercolor:#ffffff,beforeloop_readmorehoverbackcolor:#8B6632',
													'display_value' => '#8B6632,#e0e0e0,#ffffff,#e0e0e0',
												),
											),
											'hub'          => array(
												'hub_default' => array(
													'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#495F85,template_fthovercolor:#555555,template_titlecolor:#222222,template_titlehovercolor:#495F85,template_titlebackcolor:#ffffff,template_contentcolor:#333333,template_readmorecolor:#ffffff,template_readmorebackcolor:#495F85,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#495F85,beforeloop_readmorehovercolor:#495F85,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#495F85,#222222,#555555,#333333',
												),
												'hub_torea_bay' => array(
													'preset_name' => esc_html__( 'Torea Bay', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#555555,template_fthovercolor:#3E91AD,template_titlecolor:#0E7699,template_titlehovercolor:#3E91AD,template_titlebackcolor:#ffffff,template_contentcolor:#999999,template_readmorecolor:#ffffff,template_readmorebackcolor:#3E91AD,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#3E91AD,beforeloop_readmorehovercolor:#3E91AD,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#0E7699,#3E91AD,#555555,#999999',
												),
												'hub_alizarin' => array(
													'preset_name' => esc_html__( 'Alizarin', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#FCE1E4,template_ftcolor:#555555,template_fthovercolor:#EE4861,template_titlecolor:#EA1A3A,template_titlehovercolor:#EE4861,template_titlebackcolor:#FCE1E4,template_contentcolor:#333333,template_readmorecolor:#ffffff,template_readmorebackcolor:#EE4861,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#EE4861,beforeloop_readmorehovercolor:#EE4861,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#EA1A3A,#EE4861,#555555,#333333',
												),
												'hub_buttercup' => array(
													'preset_name' => esc_html__( 'Buttercup', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#FAF0E2,template_ftcolor:#555555,template_fthovercolor:#E5A452,template_titlecolor:#DF8D27,template_titlehovercolor:#E5A452,template_titlebackcolor:#FAF0E2,template_contentcolor:#333333,template_readmorecolor:#ffffff,template_readmorebackcolor:#E5A452,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#E5A452,beforeloop_readmorehovercolor:#E5A452,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#DF8D27,#E5A452,#555555,#333333',
												),
												'hub_ce_soir' => array(
													'preset_name' => esc_html__( 'Ce Soir', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#F0E9F4,template_ftcolor:#555555,template_fthovercolor:#A381BB,template_titlecolor:#8C62AA,template_titlehovercolor:#A381BB,template_titlebackcolor:#F0E9F4,template_contentcolor:#333333,template_readmorecolor:#ffffff,template_readmorebackcolor:#A381BB,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#A381BB,beforeloop_readmorehovercolor:#A381BB,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#8C62AA,#A381BB,#555555,#333333',
												),
												'hub_wild_yonder' => array(
													'preset_name' => esc_html__( 'Wild Yonder', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#ECF0F7,template_ftcolor:#555555,template_fthovercolor:#8BA3CE,template_titlecolor:#6E8CC2,template_titlehovercolor:#8BA3CE,template_titlebackcolor:#ECF0F7,template_contentcolor:#333333,template_readmorecolor:#ffffff,template_readmorebackcolor:#8BA3CE,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#8BA3CE,beforeloop_readmorehovercolor:#8BA3CE,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#6E8CC2,#8BA3CE,#555555,#333333',
												),
											),
											'invert-grid'  => array(
												'invert-grid_default' => array(
													'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
													'preset_value' => 'template_ftcolor:#CC0001,template_fthovercolor:#2b2b2b,template_titlecolor:#2b2b2b,template_titlehovercolor:#CC0001,template_titlebackcolor:#ffffff,template_contentcolor:#4c3e37,template_readmorecolor:#ffffff,template_readmorebackcolor:#CC0001,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#CC0001,beforeloop_readmorehovercolor:#CC0001,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#CC0001,#2b2b2b,#CC0001,#4c3e37',
												),
												'invert-grid_tawny' => array(
													'preset_name' => esc_html__( 'Tawny', 'blog-designer-pro' ),
													'preset_value' => 'template_ftcolor:#d35400,template_fthovercolor:#2b2b2b,template_titlecolor:#2b2b2b,template_titlehovercolor:#d35400,template_titlebackcolor:#ffffff,template_contentcolor:#4c3e37,template_readmorecolor:#ffffff,template_readmorebackcolor:#d35400,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#d35400,beforeloop_readmorehovercolor:#d35400,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#d35400,#2b2b2b,#d35400,#4c3e37',
												),
												'invert-grid_pacific_blue' => array(
													'preset_name' => esc_html__( 'Pacific Blue', 'blog-designer-pro' ),
													'preset_value' => 'template_ftcolor:#0099CB,template_fthovercolor:#2b2b2b,template_titlecolor:#2b2b2b,template_titlehovercolor:#0099CB,template_titlebackcolor:#ffffff,template_contentcolor:#4c3e37,template_readmorecolor:#ffffff,template_readmorebackcolor:#0099CB,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#0099CB,beforeloop_readmorehovercolor:#0099CB,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#0099CB,#2b2b2b,#0099CB,#4c3e37',
												),
												'invert-grid_pacific_dark_orchid' => array(
													'preset_name' => esc_html__( 'Dark Orchid', 'blog-designer-pro' ),
													'preset_value' => 'template_ftcolor:#9A33CC,template_fthovercolor:#2b2b2b,template_titlecolor:#2b2b2b,template_titlehovercolor:#9A33CC,template_titlebackcolor:#ffffff,template_contentcolor:#4c3e37,template_readmorecolor:#ffffff,template_readmorebackcolor:#9A33CC,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#9A33CC,beforeloop_readmorehovercolor:#9A33CC,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#9A33CC,#2b2b2b,#9A33CC,#4c3e37',
												),
												'invert-grid_pacific_dark_orange' => array(
													'preset_name' => esc_html__( 'Dark Orange', 'blog-designer-pro' ),
													'preset_value' => 'template_ftcolor:#FF8A00,template_fthovercolor:#2b2b2b,template_titlecolor:#2b2b2b,template_titlehovercolor:#FF8A00,template_titlebackcolor:#ffffff,template_contentcolor:#4c3e37,template_readmorecolor:#ffffff,template_readmorebackcolor:#FF8A00,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#FF8A00,beforeloop_readmorehovercolor:#FF8A00,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#FF8A00,#2b2b2b,#FF8A00,#4c3e37',
												),
												'invert-grid_bronzetone' => array(
													'preset_name' => esc_html__( 'Bronzetone', 'blog-designer-pro' ),
													'preset_value' => 'template_ftcolor:#414C22,template_fthovercolor:#2b2b2b,template_titlecolor:#2b2b2b,template_titlehovercolor:#414C22,template_titlebackcolor:#ffffff,template_contentcolor:#4c3e37,template_readmorecolor:#ffffff,template_readmorebackcolor:#414C22,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#414C22,beforeloop_readmorehovercolor:#414C22,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#414C22,#2b2b2b,#414C22,#4c3e37',
												),
											),
											'lightbreeze'  => array(
												'lightbreeze_default' => array(
													'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#1eafa6,template_fthovercolor:#555555,template_titlecolor:#222222,template_titlehovercolor:#1eafa6,template_titlebackcolor:#ffffff,template_contentcolor:#999999,template_readmorecolor:#f1f1f1,template_readmorebackcolor:#1eafa6,beforeloop_readmorecolor:#f1f1f1,beforeloop_readmorebackcolor:#1eafa6,beforeloop_readmorehovercolor:#1eafa6,beforeloop_readmorehoverbackcolor:#f1f1f1',
													'display_value' => '#1eafa6,#222222,#555555,#999999',
												),
												'lightbreeze_bronzetone' => array(
													'preset_name' => esc_html__( 'Bronzetone', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#555555,template_fthovercolor:#67704E,template_titlecolor:#414C22,template_titlehovercolor:#67704E,template_titlebackcolor:#ffffff,template_contentcolor:#999999,template_readmorecolor:#f1f1f1,template_readmorebackcolor:#67704E,beforeloop_readmorecolor:#f1f1f1,beforeloop_readmorebackcolor:#67704E,beforeloop_readmorehovercolor:#67704E,beforeloop_readmorehoverbackcolor:#f1f1f1',
													'display_value' => '#414C22,#67704E,#555555,#999999',
												),
												'lightbreeze_red_violet' => array(
													'preset_name' => esc_html__( 'Red Violet', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#555555,template_fthovercolor:#C44C91,template_titlecolor:#B51F76,template_titlehovercolor:#C44C91,template_titlebackcolor:#ffffff,template_contentcolor:#999999,template_readmorecolor:#f1f1f1,template_readmorebackcolor:#C44C91,beforeloop_readmorecolor:#f1f1f1,beforeloop_readmorebackcolor:#C44C91,beforeloop_readmorehovercolor:#C44C91,beforeloop_readmorehoverbackcolor:#f1f1f1',
													'display_value' => '#B51F76,#C44C91,#555555,#999999',
												),
											),
											'leest'         => array(
												'leest_default' => array(
													'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#eb3d34,template_bgcolor:#eb3d34,template_lazy_load_color:#FF0000,template_bghovercolor:#DFEDF1,template_ftcolor:#FFFFFF,template_fthovercolor:#555555,template_titlecolor:#FFFFFF,template_titlehovercolor:#DFEDF1,template_contentcolor:#FFFFFF,template_readmorecolor:#FFFFFF,template_readmorebackcolor:#555555,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#eb3d34,beforeloop_readmorehovercolor:#eb3d34,beforeloop_readmorehoverbackcolor:#ffffff,bdp_sale_tagbgcolor:#eb3d34,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#999999,bdp_star_rating_bg_color:transparent,bdp_pricetextcolor:#999999,bdp_edd_price_color:#999999,bdp_wishlist_textcolor:#eb3d34,bdp_wishlist_backgroundcolor:#555555,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#eb3d34,bdp_addtocart_textcolor:#ffffff,bdp_edd_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#eb3d34,bdp_edd_addtocart_backgroundcolor:#eb3d34,bdp_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#555555,bdp_edd_addtocart_hover_backgroundcolor:#555555',
													'display_value' => '#eb3d34,#DFEDF1,#555555,#999999',
												),
												'leest_wild_watermelon' => array(
													'preset_name' => esc_html__( 'Wild Watermelon', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#FF5C77,template_bgcolor:#FF5C77,template_lazy_load_color:#FF0000,template_bghovercolor:#DFEDF1,template_ftcolor:#FFFFFF,template_fthovercolor:#555555,template_titlecolor:#FFFFFF,template_titlehovercolor:#DFEDF1,template_contentcolor:#FFCED6,template_readmorecolor:#FFD871,template_readmorebackcolor:#555555,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#FF5C77,beforeloop_readmorehovercolor:#FF5C77,beforeloop_readmorehoverbackcolor:#ffffff,bdp_sale_tagbgcolor:#FF5C77,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#999999,bdp_star_rating_bg_color:transparent,bdp_pricetextcolor:#999999,bdp_edd_price_color:#999999,bdp_wishlist_textcolor:#FF5C77,bdp_wishlist_backgroundcolor:#555555,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#FF5C77,bdp_addtocart_textcolor:#ffffff,bdp_edd_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#FF5C77,bdp_edd_addtocart_backgroundcolor:#FF5C77,bdp_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#555555,bdp_edd_addtocart_hover_backgroundcolor:#555555',
													'display_value' => '#FF5C77,#DFEDF1,#555555,#999999',
												),
												'leest_hsl' => array(
													'preset_name' => esc_html__( 'HSL', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#9D1E62,template_bgcolor:#9D1E62,template_lazy_load_color:#FF0000,template_bghovercolor:#DFEDF1,template_ftcolor:#FFFFFF,template_fthovercolor:#555555,template_titlecolor:#FFFFFF,template_titlehovercolor:#DFEDF1,template_contentcolor:#EBD2E0,template_readmorecolor:#FFD9A2,template_readmorebackcolor:#555555,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#9D1E62,beforeloop_readmorehovercolor:#9D1E62,beforeloop_readmorehoverbackcolor:#ffffff,bdp_sale_tagbgcolor:#9D1E62,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#999999,bdp_star_rating_bg_color:transparent,bdp_pricetextcolor:#999999,bdp_edd_price_color:#999999,bdp_wishlist_textcolor:#9D1E62,bdp_wishlist_backgroundcolor:#555555,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#9D1E62,bdp_addtocart_textcolor:#ffffff,bdp_edd_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#9D1E62,bdp_edd_addtocart_backgroundcolor:#9D1E62,bdp_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#555555,bdp_edd_addtocart_hover_backgroundcolor:#555555',
													'display_value' => '#9D1E62,#DFEDF1,#555555,#999999',
												),
											),
											'masonry_timeline' => array(
												'masonry_timeline_default' => array(
													'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#A39D5A,template_fthovercolor:#555555,template_titlecolor:#222222,template_titlehovercolor:#A39D5A,template_contentcolor:#666666,template_readmorecolor:#666666,template_readmorebackcolor:#A39D5A,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#A39D5A,beforeloop_readmorehovercolor:#A39D5A,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#A39D5A,#222222,#555555,#666666',
												),
												'masonry_timeline_crimson' => array(
													'preset_name' => esc_html__( 'Crimson', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#555555,template_fthovercolor:#E84159,template_titlecolor:#E21130,template_titlehovercolor:#E84159,template_contentcolor:#666666,template_readmorecolor:#666666,template_readmorebackcolor:#E21130,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#E21130,beforeloop_readmorehovercolor:#E21130,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#E21130,#E84159,#555555,#666666',
												),
												'masonry_timeline_bronzetone' => array(
													'preset_name' => esc_html__( 'Bronzetone', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#F1F3F0,template_ftcolor:#555555,template_fthovercolor:#67704E,template_titlecolor:#414C22,template_titlehovercolor:#67704E,template_contentcolor:#666666,template_readmorecolor:#666666,template_readmorebackcolor:#67704E,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#67704E,beforeloop_readmorehovercolor:#67704E,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#414C22,#67704E,#555555,#666666',
												),
												'masonry_timeline_cerulean' => array(
													'preset_name' => esc_html__( 'Cerulean', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#EEF6F8,template_ftcolor:#555555,template_fthovercolor:#EEF6F8,template_titlecolor:#0E7699,template_titlehovercolor:#EEF6F8,template_contentcolor:#666666,template_readmorecolor:#666666,template_readmorebackcolor:#0E7699,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#EEF6F8,beforeloop_readmorehovercolor:#EEF6F8,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#0E7699,#3E91AD,#555555,#666666',
												),
												'masonry_timeline_buttercup' => array(
													'preset_name' => esc_html__( 'Buttercup', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#FDF7F1,template_ftcolor:#555555,template_fthovercolor:#E5A452,template_titlecolor:#DF8D27,template_titlehovercolor:#E5A452,template_contentcolor:#666666,template_readmorecolor:#666666,template_readmorebackcolor:#E5A452,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#E5A452,beforeloop_readmorehovercolor:#E5A452,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#DF8D27,#E5A452,#555555,#666666',
												),
												'masonry_timeline_red_violet' => array(
													'preset_name' => esc_html__( 'Red Violet', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#FAF0F6,template_ftcolor:#555555,template_fthovercolor:#C44C91,template_titlecolor:#B51F76,template_titlehovercolor:#C44C91,template_contentcolor:#666666,template_readmorecolor:#666666,template_readmorebackcolor:#C44C91,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#C44C91,beforeloop_readmorehovercolor:#C44C91,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#B51F76,#C44C91,#555555,#666666',
												),
											),
											'media-grid'   => array(
												'media-grid_default' => array(
													'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
													'preset_value' => 'template_ftcolor:#A49538,template_fthovercolor:#2b2b2b,template_titlecolor:#000000,template_titlehovercolor:#A49538,template_contentcolor:#7b6b79,template_readmorecolor:#dddddd,template_readmorebackcolor:#A49538,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#A49538,beforeloop_readmorehovercolor:#A49538,beforeloop_readmorehoverbackcolor:#ffffff,template_readmore_hover_backcolor:#A49538',
													'display_value' => '#A49538,#ffffff,#000000,#7b6b79',
												),
												'media-grid_salt-box' => array(
													'preset_name' => esc_html__( 'Salt Box', 'blog-designer-pro' ),
													'preset_value' => 'template_ftcolor:#60505e,template_fthovercolor:#333333,template_titlecolor:#60505e,template_titlehovercolor:#2b2b2b,template_contentcolor:#7b6b79,template_readmorecolor:#dddddd,template_readmorebackcolor:#60505e,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#60505e,beforeloop_readmorehovercolor:#60505e,beforeloop_readmorehoverbackcolor:#ffffff,template_readmore_hover_backcolor:#60505e',
													'display_value' => '#60505e,#ffffff,#2b2b2b,#7b6b79',
												),
												'media-grid_pacific_blue' => array(
													'preset_name' => esc_html__( 'Pacific Blue', 'blog-designer-pro' ),
													'preset_value' => 'template_ftcolor:#0099CB,template_fthovercolor:#2b2b2b,template_titlecolor:#0099CB,template_titlehovercolor:#2b2b2b,template_contentcolor:#7b6b79,template_readmorecolor:#dddddd,template_readmorebackcolor:#0099CB,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#0099CB,beforeloop_readmorehovercolor:#0099CB,beforeloop_readmorehoverbackcolor:#ffffff,template_readmore_hover_backcolor:#0099CB',
													'display_value' => '#0099CB,#ffffff,#2b2b2b,#7b6b79',
												),
												'media-grid_pacific_dark_orchid' => array(
													'preset_name' => esc_html__( 'Dark Orchid', 'blog-designer-pro' ),
													'preset_value' => 'template_ftcolor:#9A33CC,template_fthovercolor:#2b2b2b,template_titlecolor:#9A33CC,template_titlehovercolor:#2b2b2b,template_contentcolor:#7b6b79,template_readmorecolor:#dddddd,template_readmorebackcolor:#9A33CC,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#9A33CC,beforeloop_readmorehovercolor:#9A33CC,beforeloop_readmorehoverbackcolor:#ffffff,template_readmore_hover_backcolor:#9A33CC',
													'display_value' => '#9A33CC,#ffffff,#2b2b2b,#7b6b79',
												),
												'media-grid_pacific_dark_orange' => array(
													'preset_name' => esc_html__( 'Dark Orange', 'blog-designer-pro' ),
													'preset_value' => 'template_ftcolor:#FF8A00,template_fthovercolor:#2b2b2b,template_titlecolor:#FF8A00,template_titlehovercolor:#2b2b2b,template_contentcolor:#7b6b79,template_readmorecolor:#dddddd,template_readmorebackcolor:#FF8A00,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#FF8A00,beforeloop_readmorehovercolor:#FF8A00,beforeloop_readmorehoverbackcolor:#ffffff,template_readmore_hover_backcolor:#FF8A00',
													'display_value' => '#FF8A00,#ffffff,#2b2b2b,#7b6b79',
												),
												'media-grid_pacific_venetian_red' => array(
													'preset_name' => esc_html__( 'Venetian Red', 'blog-designer-pro' ),
													'preset_value' => 'template_ftcolor:#CC0001,template_fthovercolor:#2b2b2b,template_titlecolor:#CC0001,template_titlehovercolor:#2b2b2b,template_contentcolor:#7b6b79,template_readmorecolor:#dddddd,template_readmorebackcolor:#CC0001,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#CC0001,beforeloop_readmorehovercolor:#CC0001,beforeloop_readmorehoverbackcolor:#ffffff,template_readmore_hover_backcolor:#CC0001',
													'display_value' => '#CC0001,#ffffff,#2b2b2b,#7b6b79',
												),
											),
											'minimal'      => array(
												'minimal_default' => array(
													'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#444444,template_fthovercolor:#2b2b2b,template_titlecolor:#444444,template_titlehovercolor:#e84c89,template_contentcolor:#ffffff,template_contentcolor:#444444,template_readmorecolor:#ffffff,template_readmorebackcolor:#e84c89,template_readmore_hover_backcolor:#444444,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#e84c89,beforeloop_readmorehovercolor:#A49538,beforeloop_readmorehoverbackcolor:#444444',
													'display_value' => '#ffffff,#e84c89,#2b2b2b,#444444',
												),
												'minimal_cyan' => array(
													'preset_name' => esc_html__( 'Cyan', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#DDEEF1,template_ftcolor:#00363B,template_fthovercolor:#002226,template_titlecolor:#00363B,template_titlehovercolor:#008491,template_contentcolor:#DDEEF1,template_contentcolor:#00363B,template_readmorecolor:#DDEEF1,template_readmorebackcolor:#008491,template_readmore_hover_backcolor:#00363B,beforeloop_readmorecolor:#DDEEF1,beforeloop_readmorebackcolor:#008491,beforeloop_readmorehovercolor:#A49538,beforeloop_readmorehoverbackcolor:#00363B',
													'display_value' => '#DDEEF1,#008491,#002226,#00363B',
												),
												'minimal_purple_heart' => array(
													'preset_name' => esc_html__( 'Purple Heart', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#E9E4F6,template_ftcolor:#26164E,template_fthovercolor:#180E32,template_titlecolor:#26164E,template_titlehovercolor:#5C37BF,template_contentcolor:#E9E4F6,template_contentcolor:#26164E,template_readmorecolor:#E9E4F6,template_readmorebackcolor:#5C37BF,template_readmore_hover_backcolor:#26164E,beforeloop_readmorecolor:#E9E4F6,beforeloop_readmorebackcolor:#5C37BF,beforeloop_readmorehovercolor:#A49538,beforeloop_readmorehoverbackcolor:#26164E',
													'display_value' => '#E9E4F6,#5C37BF,#180E32,#26164E',
												),
												'minimal_go_ben' => array(
													'preset_name' => esc_html__( 'Go Ben', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#EDECE7,template_ftcolor:#3E3B25,template_fthovercolor:#282618,template_titlecolor:#3E3B25,template_titlehovercolor:#787449,template_contentcolor:#EDECE7,template_contentcolor:#3E3B25,template_readmorecolor:#EDECE7,template_readmorebackcolor:#787449,template_readmore_hover_backcolor:#3E3B25,beforeloop_readmorecolor:#EDECE7,beforeloop_readmorebackcolor:#787449,beforeloop_readmorehovercolor:#A49538,beforeloop_readmorehoverbackcolor:#3E3B25',
													'display_value' => '#EDECE7,#787449,#282618,#3E3B25',
												),
												'minimal_pompadour' => array(
													'preset_name' => esc_html__( 'Pompadour', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#EAE1E7,template_ftcolor:#35122A,template_fthovercolor:#220B1B,template_titlecolor:#35122A,template_titlehovercolor:#662451,template_contentcolor:#EAE1E7,template_contentcolor:#35122A,template_readmorecolor:#EAE1E7,template_readmorebackcolor:#662451,template_readmore_hover_backcolor:#35122A,beforeloop_readmorecolor:#EAE1E7,beforeloop_readmorebackcolor:#662451,beforeloop_readmorehovercolor:#A49538,beforeloop_readmorehoverbackcolor:#35122A',
													'display_value' => '#EAE1E7,#662451,#220B1B,#35122A',
												),
												'minimal_royal_blue' => array(
													'preset_name' => esc_html__( 'Royal Blue', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#E4EAF9,template_ftcolor:#1B326A,template_fthovercolor:#122044,template_titlecolor:#1B326A,template_titlehovercolor:#3463D0,template_contentcolor:#E4EAF9,template_contentcolor:#1B326A,template_readmorecolor:#E4EAF9,template_readmorebackcolor:#3463D0,template_readmore_hover_backcolor:#1B326A,beforeloop_readmorecolor:#E4EAF9,beforeloop_readmorebackcolor:#3463D0,beforeloop_readmorehovercolor:#A49538,beforeloop_readmorehoverbackcolor:#1B326A',
													'display_value' => '#E4EAF9,#3463D0,#122044,#1B326A',
												),
											),
											'miracle'      => array(
												'miracle_default' => array(
													'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#62bf7c,template_fthovercolor:#686868,template_titlecolor:#353535,template_titlehovercolor:#62bf7c,template_titlebackcolor:#ffffff,template_contentcolor:#252525,template_readmorecolor:#ffffff,template_readmorebackcolor:#62bf7c,template_readmore_hover_backcolor:#686868,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#62bf7c,beforeloop_readmorehovercolor:#ffffff,beforeloop_readmorehoverbackcolor:#686868,author_bgcolor:#ffffff,author_titlecolor:#353535,author_content_color:#25255',
													'display_value' => '#ffffff,#62bf7c,#353535,#252525',
												),
												'miracle_lochmara' => array(
													'preset_name' => esc_html__( 'Lochmara', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#F7FAFC,template_ftcolor:#227CAD,template_fthovercolor:#0E3246,template_titlecolor:#051117,template_titlehovercolor:#227CAD,template_titlebackcolor:#F7FAFC,template_contentcolor:#09202D,template_readmorecolor:#F7FAFC,template_readmorebackcolor:#227CAD,template_readmore_hover_backcolor:#0E3246,beforeloop_readmorecolor:#F7FAFC,beforeloop_readmorebackcolor:#227CAD,beforeloop_readmorehovercolor:#F7FAFC,beforeloop_readmorehoverbackcolor:#0E3246,author_bgcolor:#F7FAFC,author_titlecolor:#051117,author_content_color:#09202D',
													'display_value' => '#F7FAFC,#227CAD,#051117,#09202D',
												),
												'miracle_burgundy' => array(
													'preset_name' => esc_html__( 'Burgundy', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#FAF6F7,template_ftcolor:#6C0124,template_fthovercolor:#2C010E,template_titlecolor:#0E0105,template_titlehovercolor:#6C0124,template_titlebackcolor:#FAF6F7,template_contentcolor:#1C0109,template_readmorecolor:#FAF6F7,template_readmorebackcolor:#6C0124,template_readmore_hover_backcolor:#2C010E,beforeloop_readmorecolor:#FAF6F7,beforeloop_readmorebackcolor:#6C0124,beforeloop_readmorehovercolor:#FAF6F7,beforeloop_readmorehoverbackcolor:#2C010E,author_bgcolor:#FAF6F7,author_titlecolor:#0E0105,author_content_color:#1C0109',
													'display_value' => '#FAF6F7,#6C0124,#0E0105,#1C0109',
												),
												'miracle_hillary' => array(
													'preset_name' => esc_html__( 'Hillary', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#FCFBFA,template_ftcolor:#A49E77,template_fthovercolor:#434131,template_titlecolor:#161610,template_titlehovercolor:#A49E77,template_titlebackcolor:#FCFBFA,template_contentcolor:#2B2A1F,template_readmorecolor:#FCFBFA,template_readmorebackcolor:#A49E77,template_readmore_hover_backcolor:#434131,beforeloop_readmorecolor:#FCFBFA,beforeloop_readmorebackcolor:#A49E77,beforeloop_readmorehovercolor:#FCFBFA,beforeloop_readmorehoverbackcolor:#434131,author_bgcolor:#FCFBFA,author_titlecolor:#161610,author_content_color:#2B2A1F',
													'display_value' => '#FCFBFA,#A49E77,#161610,#2B2A1F',
												),
												'miracle_amaranth' => array(
													'preset_name' => esc_html__( 'Amaranth', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#FDF9FA,template_ftcolor:#DE364A,template_fthovercolor:#491218,template_titlecolor:#1E070A,template_titlehovercolor:#DE364A,template_titlebackcolor:#FDF9FA,template_contentcolor:#2E0B0F,template_readmorecolor:#FDF9FA,template_readmorebackcolor:#DE364A,template_readmore_hover_backcolor:#491218,beforeloop_readmorecolor:#FDF9FA,beforeloop_readmorebackcolor:#DE364A,beforeloop_readmorehovercolor:#FDF9FA,beforeloop_readmorehoverbackcolor:#491218,author_bgcolor:#FDF9FA,author_titlecolor:#1E070A,author_content_color:#2E0B0F',
													'display_value' => '#FDF9FA,#DE364A,#1E070A,#2E0B0F',
												),
												'miracle_manatee' => array(
													'preset_name' => esc_html__( 'Manatee', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#FCFCFD,template_ftcolor:#9699A7,template_fthovercolor:#3E3E45,template_titlecolor:#151516,template_titlehovercolor:#9699A7,template_titlebackcolor:#FCFCFD,template_contentcolor:#28282C,template_readmorecolor:#FCFCFD,template_readmorebackcolor:#9699A7,template_readmore_hover_backcolor:#3E3E45,beforeloop_readmorecolor:#FCFCFD,beforeloop_readmorebackcolor:#9699A7,beforeloop_readmorehovercolor:#FCFCFD,beforeloop_readmorehoverbackcolor:#3E3E45,author_bgcolor:#FCFCFD,author_titlecolor:#151516,author_content_color:#28282C',
													'display_value' => '#FCFCFD,#9699A7,#151516,#28282C',
												),
											),
											'my_diary'     => array(
												'my_diary_default' => array(
													'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#313131,template_ftcolor:#128775,template_fthovercolor:#000000,template_titlecolor:#128775,template_titlehovercolor:#000000,template_contentcolor:#333333,template_readmorecolor:#ffffff,template_readmorebackcolor:#128775,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#128775,beforeloop_readmorehovercolor:#128775,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#128775,#313131,#ffffff,#333333',
												),
												'my_diary_crimson' => array(
													'preset_name' => esc_html__( 'Crimson', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#000000,template_ftcolor:#e21130,template_fthovercolor:#000000,template_titlecolor:#e21130,template_titlehovercolor:#000000,template_contentcolor:#333333,template_readmorecolor:#ffffff,template_readmorebackcolor:#e21130,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#e21130,beforeloop_readmorehovercolor:#e21130,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#e21130,#000000,#ffffff,#333333',
												),
												'my_diary_eastern_blue' => array(
													'preset_name' => esc_html__( 'Eastern Blue', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#DAEBFF,template_ftcolor:#00809D,template_fthovercolor:#000000,template_titlecolor:#00809D,template_titlehovercolor:#000000,template_contentcolor:#333333,template_readmorecolor:#ffffff,template_readmorebackcolor:#00809D,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#00809D,beforeloop_readmorehovercolor:#00809D,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#00809D,#DAEBFF,#ffffff,#000000',
												),
												'my_diary_eastern_mint_tulip' => array(
													'preset_name' => esc_html__( 'Mint Tulip', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#313131,template_ftcolor:#E18942,template_fthovercolor:#000000,template_titlecolor:#E18942,template_titlehovercolor:#000000,template_contentcolor:#333333,template_readmorecolor:#ffffff,template_readmorebackcolor:#E18942,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#E18942,beforeloop_readmorehovercolor:#E18942,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#E18942,#313131,#ffffff,#333333',
												),
												'my_diary_camelot' => array(
													'preset_name' => esc_html__( 'Camelot', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#313131,template_ftcolor:#7A3E48,template_fthovercolor:#000000,template_titlecolor:#7A3E48,template_titlehovercolor:#000000,template_contentcolor:#333333,template_readmorecolor:#ffffff,template_readmorebackcolor:#7A3E48,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#00809D,beforeloop_readmorehovercolor:#00809D,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#7A3E48,#313131,#ffffff,#333333',
												),
												'my_diary_sundance' => array(
													'preset_name' => esc_html__( 'Sundance', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#313131,template_ftcolor:#C59F4A,template_fthovercolor:#000000,template_titlecolor:#C59F4A,template_titlehovercolor:#000000,template_contentcolor:#333333,template_readmorecolor:#ffffff,template_readmorebackcolor:#C59F4A,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#00809D,beforeloop_readmorehovercolor:#00809D,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#C59F4A,#313131,#ffffff,#333333',
												),
											),
											'navia'        => array(
												'navia_default' => array(
													'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#222222,template_fthovercolor:#555555,template_titlecolor:#D65D88,template_titlehovercolor:#D65D88,template_titlebackcolor:#ffffff,template_contentcolor:#999999,template_readmorecolor:#ffffff,template_readmorebackcolor:#D65D88,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#D65D88,beforeloop_readmorehovercolor:#D65D88,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#D65D88,#222222,#555555,#999999',
												),
												'navia_toddy' => array(
													'preset_name' => esc_html__( 'Toddy', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#555555,template_fthovercolor:#BE9055,template_titlecolor:#AE742A,template_titlehovercolor:#BE9055,template_titlebackcolor:#ffffff,template_contentcolor:#999999,template_readmorecolor:#ffffff,template_readmorebackcolor:#BE9055,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#BE9055,beforeloop_readmorehovercolor:#BE9055,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#AE742A,#BE9055,#555555,#999999',
												),
												'navia_fun_green' => array(
													'preset_name' => esc_html__( 'Fun Green', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#555555,template_fthovercolor:#3E8563,template_titlecolor:#0E663C,template_titlehovercolor:#3E8563,template_titlebackcolor:#ffffff,template_contentcolor:#999999,template_readmorecolor:#ffffff,template_readmorebackcolor:#3E8563,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#3E8563,beforeloop_readmorehovercolor:#3E8563,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#0E663C,#3E8563,#555555,#999999',
												),
												'navia_ce_soir' => array(
													'preset_name' => esc_html__( 'Ce Soir', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#555555,template_fthovercolor:#A381BB,template_titlecolor:#8C62AA,template_titlehovercolor:#A381BB,template_titlebackcolor:#ffffff,template_contentcolor:#999999,template_readmorecolor:#ffffff,template_readmorebackcolor:#A381BB,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#A381BB,beforeloop_readmorehovercolor:#A381BB,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#8C62AA,#A381BB,#555555,#999999',
												),
												'navia_buttercup' => array(
													'preset_name' => esc_html__( 'Buttercup', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#555555,template_fthovercolor:#E5A452,template_titlecolor:#DF8D27,template_titlehovercolor:#E5A452,template_titlebackcolor:#FAF0E2,template_contentcolor:#999999,template_readmorecolor:#ffffff,template_readmorebackcolor:#E5A452,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#E5A452,beforeloop_readmorehovercolor:#E5A452,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#DF8D27,#E5A452,#555555,#999999',
												),
												'navia_alizarin' => array(
													'preset_name' => esc_html__( 'Alizarin', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#555555,template_fthovercolor:#ED4961,template_titlecolor:#E81B3A,template_titlehovercolor:#ED4961,template_titlebackcolor:#FCE1E4,template_contentcolor:#999999,template_readmorecolor:#ffffff,template_readmorebackcolor:#ED4961,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#ED4961,beforeloop_readmorehovercolor:#ED4961,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#E81B3A,#ED4961,#555555,#999999',
												),
											),
											'news'         => array(
												'news_default' => array(
													'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#AF583D,template_fthovercolor:#555555,template_titlecolor:#222222,template_titlehovercolor:#AF583D,template_titlebackcolor:#ffffff,template_contentcolor:#444444,template_readmorecolor:#AF583D,template_readmorebackcolor:#f1f1f1,beforeloop_readmorecolor:#f1f1f1,beforeloop_readmorebackcolor:#AF583D,beforeloop_readmorehovercolor:#AF583D,beforeloop_readmorehoverbackcolor:#f1f1f1',
													'display_value' => '#AF583D,#222222,#555555,#444444',
												),
												'news_toddy' => array(
													'preset_name' => esc_html__( 'Toddy', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#F8F3ED,template_ftcolor:#555555,template_fthovercolor:#BE9055,template_titlecolor:#AE742A,template_titlehovercolor:#BE9055,template_titlebackcolor:#F8F3ED,template_contentcolor:#444444,template_readmorecolor:#683C6F,template_readmorebackcolor:#f1f1f1,beforeloop_readmorecolor:#f1f1f1,beforeloop_readmorebackcolor:#BE9055,beforeloop_readmorehovercolor:#BE9055,beforeloop_readmorehoverbackcolor:#f1f1f1',
													'display_value' => '#AE742A,#BE9055,#555555,#444444',
												),
												'news_cerulean' => array(
													'preset_name' => esc_html__( 'Cerulean', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#EEF6F8,template_ftcolor:#555555,template_fthovercolor:#3E91AD,template_titlecolor:#0E7699,template_titlehovercolor:#3E91AD,template_titlebackcolor:#EEF6F8,template_contentcolor:#444444,template_readmorecolor:#3E91AD,template_readmorebackcolor:#f1f1f1,beforeloop_readmorecolor:#f1f1f1,beforeloop_readmorebackcolor:#3E91AD,beforeloop_readmorehovercolor:#3E91AD,beforeloop_readmorehoverbackcolor:#f1f1f1',
													'display_value' => '#0E7699,#3E91AD,#555555,#444444',
												),
												'news_rich-gold' => array(
													'preset_name' => esc_html__( 'Rich Gold', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#F9F4F0,template_ftcolor:#555555,template_fthovercolor:#BA7850,template_titlecolor:#A95624,template_titlehovercolor:#BA7850,template_titlebackcolor:#F9F4F0,template_contentcolor:#444444,template_readmorecolor:#BA7850,template_readmorebackcolor:#f1f1f1,beforeloop_readmorecolor:#f1f1f1,beforeloop_readmorebackcolor:#BA7850,beforeloop_readmorehovercolor:#BA7850,beforeloop_readmorehoverbackcolor:#f1f1f1',
													'display_value' => '#A95624,#BA7850,#555555,#444444',
												),
												'news_bronzetone' => array(
													'preset_name' => esc_html__( 'Bronzetone', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#F1F3F0,template_ftcolor:#555555,template_fthovercolor:#67704E,template_titlecolor:#414C22,template_titlehovercolor:#67704E,template_titlebackcolor:#F1F3F0,template_contentcolor:#444444,template_readmorecolor:#67704E,template_readmorebackcolor:#f1f1f1,beforeloop_readmorecolor:#f1f1f1,beforeloop_readmorebackcolor:#67704E,beforeloop_readmorehovercolor:#67704E,beforeloop_readmorehoverbackcolor:#f1f1f1',
													'display_value' => '#414C22,#67704E,#555555,#444444',
												),
												'news_regal_blue' => array(
													'preset_name' => esc_html__( 'Regal Blue', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#EEF1F4,template_ftcolor:#555555,template_fthovercolor:#435F7F,template_titlecolor:#14375F,template_titlehovercolor:#435F7F,template_titlebackcolor:#EEF1F4,template_contentcolor:#444444,template_readmorecolor:#435F7F,template_readmorebackcolor:#f1f1f1,beforeloop_readmorecolor:#f1f1f1,beforeloop_readmorebackcolor:#435F7F,beforeloop_readmorehovercolor:#435F7F,beforeloop_readmorehoverbackcolor:#f1f1f1',
													'display_value' => '#14375F,#435F7F,#555555,#444444',
												),
											),
											'offer_blog'   => array(
												'offer_blog_default' => array(
													'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#BE6A67,template_fthovercolor:#555555,template_titlecolor:#333333,template_titlehovercolor:#BE6A67,template_titlebackcolor:#ffffff,template_contentcolor:#666666,template_readmorecolor:#ffffff,template_readmorebackcolor:#BE6A67,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#BE6A67,beforeloop_readmorehovercolor:#BE6A67,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#BE6A67,#333333,#555555,#666666',
												),
												'offer_bronzetone' => array(
													'preset_name' => esc_html__( 'Bronzetone', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#F1F3F0,template_ftcolor:#555555,template_fthovercolor:#67704E,template_titlecolor:#414C22,template_titlehovercolor:#67704E,template_titlebackcolor:#F1F3F0,template_contentcolor:#666666,template_readmorecolor:#ffffff,template_readmorebackcolor:#67704E,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#67704E,beforeloop_readmorehovercolor:#67704E,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#414C22,#67704E,#555555,#666666',
												),
												'offer_toddy' => array(
													'preset_name' => esc_html__( 'Toddy', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#F9F5F1,template_ftcolor:#555555,template_fthovercolor:#BE9055,template_titlecolor:#AE742A,template_titlehovercolor:#BE9055,template_titlebackcolor:#F9F5F1,template_contentcolor:#666666,template_readmorecolor:#ffffff,template_readmorebackcolor:#BE9055,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#BE9055,beforeloop_readmorehovercolor:#BE9055,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#AE742A,#BE9055,#555555,#666666',
												),
												'offer_regal_blue' => array(
													'preset_name' => esc_html__( 'Regal Blue', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#EEF1F4,template_ftcolor:#555555,template_fthovercolor:#435F7F,template_titlecolor:#14375F,template_titlehovercolor:#435F7F,template_titlebackcolor:#EEF1F4,template_contentcolor:#666666,template_readmorecolor:#ffffff,template_readmorebackcolor:#435F7F,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#435F7F,beforeloop_readmorehovercolor:#435F7F,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#14375F,#435F7F,#555555,#666666',
												),
												'offer_shiraz' => array(
													'preset_name' => esc_html__( 'Shiraz', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#EEE2E4,template_ftcolor:#555555,template_fthovercolor:#9E555D,template_titlecolor:#862A35,template_titlehovercolor:#9E555D,template_titlebackcolor:#EEE2E4,template_contentcolor:#666666,template_readmorecolor:#ffffff,template_readmorebackcolor:#9E555D,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#9E555D,beforeloop_readmorehovercolor:#9E555D,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#862A35,#9E555D,#555555,#666666',
												),
												'offer_yonder' => array(
													'preset_name' => esc_html__( 'Yonder', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#F3F5FA,template_ftcolor:#555555,template_fthovercolor:#8BA3CE,template_titlecolor:#6E8CC2,template_titlehovercolor:#8BA3CE,template_titlebackcolor:#F3F5FA,template_contentcolor:#666666,template_readmorecolor:#ffffff,template_readmorebackcolor:#8BA3CE,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#8BA3CE,beforeloop_readmorehovercolor:#8BA3CE,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#6E8CC2,#8BA3CE,#555555,#666666',
												),
											),
											'overlay_horizontal' => array(
												'overlay_horizontal_default' => array(
													'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#dd5555,template_ftcolor:#ffffff,template_fthovercolor:#aaaaaa,template_titlecolor:#ffffff,template_titlehovercolor:#aaaaaa,template_contentcolor:#ffffff,template_readmorecolor:#ffffff,template_readmorebackcolor:#aaaaaa,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#999999,beforeloop_readmorehovercolor:#999999,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#dd5555,#ffffff,#aaaaaa,#ffffff',
												),
												'overlay_horizontal_persian_red' => array(
													'preset_name' => esc_html__( 'Persian Red', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#DC3330,template_ftcolor:#ffffff,template_fthovercolor:#aaaaaa,template_titlecolor:#DC3330,template_titlehovercolor:#aaaaaa,template_contentcolor:#ffffff,template_readmorecolor:#ffffff,template_readmorebackcolor:#aaaaaa,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#999999,beforeloop_readmorehovercolor:#999999,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#DC3330,#ffffff,#aaaaaa,#ffffff',
												),
												'overlay_horizontal_dark_goldenrod' => array(
													'preset_name' => esc_html__( 'Dark Goldenrod', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#B48900,template_ftcolor:#ffffff,template_fthovercolor:#aaaaaa,template_titlecolor:#B48900,template_titlehovercolor:#aaaaaa,template_contentcolor:#ffffff,template_readmorecolor:#ffffff,template_readmorebackcolor:#aaaaaa,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#999999,beforeloop_readmorehovercolor:#999999,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#B48900,#ffffff,#aaaaaa,#ffffff',
												),
												'overlay_horizontal_deep_cerise' => array(
													'preset_name' => esc_html__( 'Deep Cerise', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#D23582,template_ftcolor:#ffffff,template_fthovercolor:#aaaaaa,template_titlecolor:#D23582,template_titlehovercolor:#aaaaaa,template_contentcolor:#ffffff,template_readmorecolor:#ffffff,template_readmorebackcolor:#aaaaaa,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#999999,beforeloop_readmorehovercolor:#999999,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#D23582,#ffffff,#aaaaaa,#ffffff',
												),
												'overlay_horizontal_rust' => array(
													'preset_name' => esc_html__( 'Rust', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#CA4B16,template_ftcolor:#ffffff,template_fthovercolor:#aaaaaa,template_titlecolor:#CA4B16,template_titlehovercolor:#aaaaaa,template_contentcolor:#ffffff,template_readmorecolor:#ffffff,template_readmorebackcolor:#aaaaaa,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#999999,beforeloop_readmorehovercolor:#999999,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#CA4B16,#ffffff,#aaaaaa,#ffffff,',
												),
												'overlay_horizontal_blue' => array(
													'preset_name' => esc_html__( 'Chetwode Blue', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#6C71C3,template_ftcolor:#ffffff,template_fthovercolor:#aaaaaa,template_titlecolor:#6C71C3,template_titlehovercolor:#aaaaaa,template_contentcolor:#ffffff,template_readmorecolor:#ffffff,template_readmorebackcolor:#aaaaaa,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#999999,beforeloop_readmorehovercolor:#999999,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#6C71C3,#ffffff,#aaaaaa,#ffffff',
												),
											),
											'nicy'         => array(
												'nicy_default' => array(
													'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#ffffff,template_ftcolor:#ED4961,template_fthovercolor:#555555,template_titlecolor:#222222,template_titlehovercolor:#ED4961,template_titlebackcolor:#ffffff,template_contentcolor:#999999,template_readmorecolor:#f1f1f1,template_readmorebackcolor:#ED4961,beforeloop_readmorecolor:#ED4961,beforeloop_readmorebackcolor:#f1f1f1,beforeloop_readmorehovercolor:#f1f1f1,beforeloop_readmorehoverbackcolor:#ED4961',
													'display_value' => '#ED4961,#222222,#555555,#999999',
												),
												'nicy_cerulean' => array(
													'preset_name' => esc_html__( 'Cerulean', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#ffffff,template_ftcolor:#555555,template_fthovercolor:#3E91AD,template_titlecolor:#0E7699,template_titlehovercolor:#3E91AD,template_titlebackcolor:#ffffff,template_contentcolor:#999999,template_readmorecolor:#3E91AD,template_readmorebackcolor:#f1f1f1,beforeloop_readmorecolor:#3E91AD,beforeloop_readmorebackcolor:#f1f1f1,beforeloop_readmorehovercolor:#f1f1f1,beforeloop_readmorehoverbackcolor:#3E91AD',
													'display_value' => '#0E7699,#3E91AD,#555555,#999999',
												),
												'nicy_fun_green' => array(
													'preset_name' => esc_html__( 'Fun Green', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#ffffff,template_ftcolor:#555555,template_fthovercolor:#3E8563,template_titlecolor:#0E663C,template_titlehovercolor:#3E8563,template_titlebackcolor:#ffffff,template_contentcolor:#999999,template_readmorecolor:#3E8563,template_readmorebackcolor:#f1f1f1,beforeloop_readmorecolor:#3E8563,beforeloop_readmorebackcolor:#f1f1f1,beforeloop_readmorehovercolor:#f1f1f1,beforeloop_readmorehoverbackcolor:#3E8563',
													'display_value' => '#0E663C,#3E8563,#555555,#999999',
												),
												'nicy_blackberry' => array(
													'preset_name' => esc_html__( 'Blackberry', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#ffffff,template_ftcolor:#555555,template_fthovercolor:#6D4657,template_titlecolor:#49182D,template_titlehovercolor:#6D4657,template_titlebackcolor:#ffffff,template_contentcolor:#999999,template_readmorecolor:#6D4657,template_readmorebackcolor:#f1f1f1,beforeloop_readmorecolor:#6D4657,beforeloop_readmorebackcolor:#f1f1f1,beforeloop_readmorehovercolor:#f1f1f1,beforeloop_readmorehoverbackcolor:#6D4657',
													'display_value' => '#49182D,#6D4657,#555555,#999999',
												),
												'nicy_earls_green' => array(
													'preset_name' => esc_html__( 'Earls Green', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#ffffff,template_ftcolor:#555555,template_fthovercolor:#CEBF59,template_titlecolor:#C2AF2F,template_titlehovercolor:#CEBF59,template_titlebackcolor:#ffffff,template_contentcolor:#999999,template_readmorecolor:#CEBF59,template_readmorebackcolor:#f1f1f1,beforeloop_readmorecolor:#CEBF59,beforeloop_readmorebackcolor:#f1f1f1,beforeloop_readmorehovercolor:#f1f1f1,beforeloop_readmorehoverbackcolor:#CEBF59',
													'display_value' => '#C2AF2F,#CEBF59,#555555,#999999',
												),
												'nicy_toddy' => array(
													'preset_name' => esc_html__( 'Toddy', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#ffffff,template_ftcolor:#555555,template_fthovercolor:#BE9055,template_titlecolor:#AE742A,template_titlehovercolor:#BE9055,template_titlebackcolor:#ffffff,template_contentcolor:#999999,template_readmorecolor:#BE9055,template_readmorebackcolor:#f1f1f1,beforeloop_readmorecolor:#BE9055,beforeloop_readmorebackcolor:#f1f1f1,beforeloop_readmorehovercolor:#f1f1f1,beforeloop_readmorehoverbackcolor:#BE9055',
													'display_value' => '#AE742A,#BE9055,#555555,#999999',
												),
											),
											'region'       => array(
												'region_default' => array(
													'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#AC619B,template_fthovercolor:#555555,template_titlecolor:#222222,template_titlehovercolor:#AC619B,template_titlebackcolor:#ffffff,template_contentcolor:#333333,template_readmorecolor:#f1f1f1,template_readmorebackcolor:#AC619B,beforeloop_readmorecolor:#f1f1f1,beforeloop_readmorebackcolor:#AC619B,beforeloop_readmorehovercolor:#AC619B,beforeloop_readmorehoverbackcolor:#f1f1f1',
													'display_value' => '#AC619B,#222222,#555555,#333333',
												),
												'region_red_violet' => array(
													'preset_name' => esc_html__( 'Red Violet', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#F5E1ED,template_ftcolor:#555555,template_fthovercolor:#C44C91,template_titlecolor:#B51F76,template_titlehovercolor:#C44C91,template_titlebackcolor:#F5E1ED,template_contentcolor:#333333,template_readmorecolor:#f1f1f1,template_readmorebackcolor:#C44C91,beforeloop_readmorecolor:#f1f1f1,beforeloop_readmorebackcolor:#C44C91,beforeloop_readmorehovercolor:#C44C91,beforeloop_readmorehoverbackcolor:#f1f1f1',
													'display_value' => '#B51F76,#C44C91,#555555,#333333',
												),
												'region_alizarin' => array(
													'preset_name' => esc_html__( 'Alizarin', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#FCE1E4,template_ftcolor:#555555,template_fthovercolor:#ED4961,template_titlecolor:#E81B3A,template_titlehovercolor:#ED4961,template_titlebackcolor:#FCE1E4,template_contentcolor:#333333,template_readmorecolor:#f1f1f1,template_readmorebackcolor:#ED4961,beforeloop_readmorecolor:#f1f1f1,beforeloop_readmorebackcolor:#ED4961,beforeloop_readmorehovercolor:#ED4961,beforeloop_readmorehoverbackcolor:#f1f1f1',
													'display_value' => '#E81B3A,#ED4961,#555555,#333333',
												),
												'region_earls_green' => array(
													'preset_name' => esc_html__( 'Earls Green', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#F7F4E4,template_ftcolor:#55555,template_fthovercolor:#CEBF59,template_titlecolor:#C2AF2F,template_titlehovercolor:#CEBF59,template_titlebackcolor:#F7F4E4,template_contentcolor:#333333,template_readmorecolor:#f1f1f1,template_readmorebackcolor:#CEBF59,beforeloop_readmorecolor:#f1f1f1,beforeloop_readmorebackcolor:#CEBF59,beforeloop_readmorehovercolor:#CEBF59,beforeloop_readmorehoverbackcolor:#f1f1f1',
													'display_value' => '#C2AF2F,#CEBF59,#555555,#333333',
												),
												'region_bronzetone' => array(
													'preset_name' => esc_html__( 'Bronzetone', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#E5E7E1,template_ftcolor:#555555,template_fthovercolor:#67704E,template_titlecolor:#414C22,template_titlehovercolor:#67704E,template_titlebackcolor:#E5E7E1,template_contentcolor:#333333,template_readmorecolor:#f1f1f1,template_readmorebackcolor:#67704E,beforeloop_readmorecolor:#f1f1f1,beforeloop_readmorebackcolor:#67704E,beforeloop_readmorehovercolor:#67704E,beforeloop_readmorehoverbackcolor:#f1f1f1',
													'display_value' => '#414C22,#67704E,#555555,#333333',
												),
												'region_ce_soir' => array(
													'preset_name' => esc_html__( 'Ce Soir', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#F0E9F4,template_ftcolor:#555555,template_fthovercolor:#A381BB,template_titlecolor:#8C62AA,template_titlehovercolor:#A381BB,template_titlebackcolor:#F0E9F4,template_contentcolor:#333333,template_readmorecolor:#f1f1f1,template_readmorebackcolor:#A381BB,beforeloop_readmorecolor:#f1f1f1,beforeloop_readmorebackcolor:#A381BB,beforeloop_readmorehovercolor:#A381BB,beforeloop_readmorehoverbackcolor:#f1f1f1',
													'display_value' => '#8C62AA,#A381BB,#555555,#333333',
												),
											),
											'roctangle'    => array(
												'roctangle_default' => array(
													'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#ffffff,template_color:#f18293,template_ftcolor:#666666,template_fthovercolor:#444444,template_titlecolor:#222222,template_titlehovercolor:#f18293,template_readmorecolor:#222222,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#f18293,beforeloop_readmorehovercolor:#ffffff,beforeloop_readmorehoverbackcolor:#222222,template_contentcolor:#444444',
													'display_value' => '#f18293,#222222,#444444,#666666',
												),
												'roctangle_sky_blue' => array(
													'preset_name' => esc_html__( 'Sky Blue', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#ffffff,template_color:#92E2FD,template_ftcolor:#666666,template_fthovercolor:#444444,template_titlecolor:#222222,template_titlehovercolor:#92E2FD,template_readmorecolor:#222222,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#92E2FD,beforeloop_readmorehovercolor:#ffffff,beforeloop_readmorehoverbackcolor:#222222,template_contentcolor:#444444',
													'display_value' => '#92E2FD,#222222,#444444,#666666',
												),
												'roctangle_lite_green' => array(
													'preset_name' => esc_html__( 'Lite Green', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#ffffff,template_color:#0ef58d,template_ftcolor:#666666,template_fthovercolor:#444444,template_titlecolor:#222222,template_titlehovercolor:#0ef58d,template_readmorecolor:#222222,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#0ef58d,beforeloop_readmorehovercolor:#ffffff,beforeloop_readmorehoverbackcolor:#222222,template_contentcolor:#444444',
													'display_value' => '#0ef58d,#222222,#444444,#666666',
												),
											),
											'sharpen'      => array(
												'sharpen_default' => array(
													'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#2E6480,template_fthovercolor:#555555,template_titlecolor:#222222,template_titlehovercolor:#2E6480,template_titlebackcolor:#ffffff,template_contentcolor:#999999,template_readmorecolor:#f1f1f1,template_readmorebackcolor:#2E6480,beforeloop_readmorecolor:#f1f1f1,beforeloop_readmorebackcolor:#2E6480,beforeloop_readmorehovercolor:#2E6480,beforeloop_readmorehoverbackcolor:#f1f1f1',
													'display_value' => '#2E6480,#222222,#555555,#999999',
												),
												'sharpen_bronzetone' => array(
													'preset_name' => esc_html__( 'Bronzetone', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#555555,template_fthovercolor:#67704E,template_titlecolor:#414C22,template_titlehovercolor:#67704E,template_titlebackcolor:#ffffff,template_contentcolor:#999999,template_readmorecolor:#f1f1f1,template_readmorebackcolor:#67704E,beforeloop_readmorecolor:#f1f1f1,beforeloop_readmorebackcolor:#67704E,beforeloop_readmorehovercolor:#67704E,beforeloop_readmorehoverbackcolor:#f1f1f1',
													'display_value' => '#414C22,#67704E,#555555,#999999',
												),
												'sharpen_red_violet' => array(
													'preset_name' => esc_html__( 'Red Violet', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#555555,template_fthovercolor:#C44C91,template_titlecolor:#B51F76,template_titlehovercolor:#C44C91,template_titlebackcolor:#ffffff,template_contentcolor:#999999,template_readmorecolor:#f1f1f1,template_readmorebackcolor:#C44C91,beforeloop_readmorecolor:#f1f1f1,beforeloop_readmorebackcolor:#C44C91,beforeloop_readmorehovercolor:#C44C91,beforeloop_readmorehoverbackcolor:#f1f1f1',
													'display_value' => '#B51F76,#C44C91,#555555,#999999',
												),
											),
											'spektrum'     => array(
												'spektrum_default' => array(
													'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#2d7fc1,template_fthovercolor:#555555,template_titlecolor:#2d7fc1,template_titlehovercolor:#2d7fc1,template_titlebackcolor:#ffffff,template_contentcolor:#333333,template_readmorecolor:#ffffff,template_readmorebackcolor:#2d7fc1,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#2d7fc1,beforeloop_readmorehovercolor:#2d7fc1,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#2d7fc1,#222222,#555555,#333333',
												),
												'spektrum_torea_bay' => array(
													'preset_name' => esc_html__( 'Torea Bay', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#0E7699,template_fthovercolor:#3E91AD,template_titlecolor:#0E7699,template_titlehovercolor:#3E91AD,template_titlebackcolor:#ffffff,template_contentcolor:#999999,template_readmorecolor:#ffffff,template_readmorebackcolor:#3E91AD,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#3E91AD,beforeloop_readmorehovercolor:#3E91AD,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#0E7699,#3E91AD,#555555,#999999',
												),
												'spektrum_alizarin' => array(
													'preset_name' => esc_html__( 'Alizarin', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#EA1A3A,template_fthovercolor:#EE4861,template_titlecolor:#EA1A3A,template_titlehovercolor:#EE4861,template_titlebackcolor:#ffffff,template_contentcolor:#333333,template_readmorecolor:#ffffff,template_readmorebackcolor:#EE4861,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#EE4861,beforeloop_readmorehovercolor:#EE4861,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#EA1A3A,#EE4861,#555555,#333333',
												),
												'spektrum_buttercup' => array(
													'preset_name' => esc_html__( 'Buttercup', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#DF8D27,template_fthovercolor:#E5A452,template_titlecolor:#DF8D27,template_titlehovercolor:#E5A452,template_titlebackcolor:#ffffff,template_contentcolor:#333333,template_readmorecolor:#ffffff,template_readmorebackcolor:#E5A452,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#E5A452,beforeloop_readmorehovercolor:#E5A452,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#DF8D27,#E5A452,#555555,#333333',
												),
												'spektrum_ce_soir' => array(
													'preset_name' => esc_html__( 'Ce Soir', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#8C62AA,template_fthovercolor:#A381BB,template_titlecolor:#8C62AA,template_titlehovercolor:#A381BB,template_titlebackcolor:#ffffff,template_contentcolor:#333333,template_readmorecolor:#ffffff,template_readmorebackcolor:#A381BB,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#A381BB,beforeloop_readmorehovercolor:#A381BB,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#8C62AA,#A381BB,#555555,#333333',
												),
												'spektrum_wild_yonder' => array(
													'preset_name' => esc_html__( 'Wild Yonder', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#6E8CC2,template_fthovercolor:#8BA3CE,template_titlecolor:#6E8CC2,template_titlehovercolor:#8BA3CE,template_titlebackcolor:#ffffff,template_contentcolor:#333333,template_readmorecolor:#ffffff,template_readmorebackcolor:#8BA3CE,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#8BA3CE,beforeloop_readmorehovercolor:#8BA3CE,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#6E8CC2,#8BA3CE,#555555,#333333',
												),
											),
											'steps'        => array(
												'steps_default' => array(
													'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#d1d1d1,template_bgcolor:#ffffff,template_ftcolor:#b7b7b7,template_fthovercolor:#2a2727,template_titlecolor:#2a2727,template_titlehovercolor:#e21130,template_titlebackcolor:#ffffff,template_contentcolor:#504d4d,template_readmorecolor:#e21130,beforeloop_readmorecolor:#e21130,beforeloop_readmorebackcolor:#ffffff,beforeloop_readmorehovercolor:#504d4d,beforeloop_readmorehoverbackcolor:#ffffff,template_readmore_hover_backcolor:#e21130',
													'display_value' => '#d1d1d1,#b7b7b7,#2a2727,#e21130',
												),
												'steps_tan' => array(
													'preset_name' => esc_html__( 'Tan', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#E7DDC5,template_bgcolor:#F9F6F0,template_ftcolor:#D9CAA5,template_fthovercolor:#6A6149,template_titlecolor:#6A6149,template_titlehovercolor:#CFBD8F,template_titlebackcolor:#F9F6F0,template_contentcolor:#6A6149,template_readmorecolor:#CFBD8F,beforeloop_readmorecolor:#CFBD8F,beforeloop_readmorebackcolor:#F9F6F0,beforeloop_readmorehovercolor:#6A6149,beforeloop_readmorehoverbackcolor:#F9F6F0,template_readmore_hover_backcolor:#CFBD8F',
													'display_value' => '#E7DDC5,#D9CAA5,#6A6149,#CFBD8F',
												),
												'steps_nordic' => array(
													'preset_name' => esc_html__( 'Nordic', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#849697,template_bgcolor:#DFE4E4,template_ftcolor:#3F5B5D,template_fthovercolor:#081A1B,template_titlecolor:#081A1B,template_titlehovercolor:#0F3235,template_titlebackcolor:#DFE4E4,template_contentcolor:#081A1B,template_readmorecolor:#0F3235,beforeloop_readmorecolor:#0F3235,beforeloop_readmorebackcolor:#DFE4E4,beforeloop_readmorehovercolor:#081A1B,beforeloop_readmorehoverbackcolor:#DFE4E4,template_readmore_hover_backcolor:#0F3235',
													'display_value' => '#849697,#3F5B5D,#081A1B,#0F3235',
												),
											),
											'story'        => array(
												'story_default' => array(
													'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#0c555e,template_alternative_color:#ff6861,story_startup_border_color:#ffffff,story_startup_background:#71a405,story_startup_text_color:#ffffff,story_ending_background:#71a405,story_ending_text_color:#ffffff,template_ftcolor:#0c555e,template_fthovercolor:#2b2b2b,template_titlecolor:#333333,template_titlehovercolor:#ade175,template_contentcolor:#666666,template_readmorecolor:#d6d6d6,template_readmorebackcolor:#333333',
													'display_value' => '#0c555e,#ff6861,#0c555e,#333333',
												),
												'story_goldenrod' => array(
													'preset_name' => esc_html__( 'Goldenrod', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#b48900,template_alternative_color:#d23582,story_startup_border_color:#e21130,story_startup_background:#e21130,story_startup_text_color:#ffffff,story_ending_background:#e21130,story_ending_text_color:#ffffff,template_ftcolor:#e21130,template_fthovercolor:#2b2b2b,template_titlecolor:#707070,template_titlehovercolor:#e21130,template_contentcolor:#666666,template_readmorecolor:#d6d6d6,template_readmorebackcolor:#e21130',
													'display_value' => '#b48900,#d23582,#e21130,#333333',
												),
												'story_radical_red' => array(
													'preset_name' => esc_html__( 'Radical Red', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#fa336c,template_alternative_color:#f18547,story_startup_border_color:#75815b,story_startup_background:#75815b,story_startup_text_color:#ffffff,story_ending_background:#75815b,story_ending_text_color:#ffffff,template_ftcolor:#75815b,template_fthovercolor:#2b2b2b,template_titlecolor:#707070,template_titlehovercolor:#75815b,template_contentcolor:#666666,template_readmorecolor:#d6d6d6,template_readmorebackcolor:#75815b',
													'display_value' => '#fa336c,#f18547,#75815b,#333333',
												),
											),
											'timeline'     => array(
												'timeline_default' => array(
													'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
													'preset_value' => 'displaydate_backcolor:#414a54,template_color:#E0254D,template_bgcolor:#ffffff,template_ftcolor:#E0254D,template_fthovercolor:#444444,template_titlecolor:#E0254D,template_titlehovercolor:#444444,template_titlebackcolor:#ffffff,template_contentcolor:#333333,template_readmorecolor:#ffffff,template_readmorebackcolor:#E0254D,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#E0254D,beforeloop_readmorehovercolor:#E0254D,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#E0254D,#414a54,#ffffff,#333333',
												),
												'timeline_pink' => array(
													'preset_name' => esc_html__( 'Dark Sea Green', 'blog-designer-pro' ),
													'preset_value' => 'displaydate_backcolor:#9AB999,template_color:#9AB999,template_bgcolor:#F6F8F5,template_ftcolor:#9AB999,template_fthovercolor:#444444,template_titlecolor:#9AB999,template_titlehovercolor:#444444,template_titlebackcolor:#F6F8F5,template_contentcolor:#333333,template_readmorecolor:#ffffff,template_readmorebackcolor:#9AB999,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#9AB999,beforeloop_readmorehovercolor:#9AB999,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#9AB999,#F6F8F5,#9AB999,#333333',
												),
												'timeline_pacific_blue' => array(
													'preset_name' => esc_html__( 'Pacific Blue', 'blog-designer-pro' ),
													'preset_value' => 'displaydate_backcolor:#0099CB,template_color:#0099CB,template_bgcolor:#E6F5FA,template_ftcolor:#0099CB,template_fthovercolor:#444444,template_titlecolor:#0099CB,template_titlehovercolor:#444444,template_titlebackcolor:#E6F5FA,template_contentcolor:#333333,template_readmorecolor:#ffffff,template_readmorebackcolor:#0099CB,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#0099CB,beforeloop_readmorehovercolor:#0099CB,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#0099CB,#E6F5FA,#0099CB,#333333',
												),
												'timeline_dark_orchid' => array(
													'preset_name' => esc_html__( 'Dark Orchid', 'blog-designer-pro' ),
													'preset_value' => 'displaydate_backcolor:#9A33CC,template_color:#9A33CC,template_bgcolor:#F5EAFA,template_ftcolor:#9A33CC,template_fthovercolor:#444444,template_titlecolor:#9A33CC,template_titlehovercolor:#444444,template_titlebackcolor:#F5EAFA,template_contentcolor:#333333,template_readmorecolor:#ffffff,template_readmorebackcolor:#9A33CC,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#9A33CC,beforeloop_readmorehovercolor:#9A33CC,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#9A33CC,#F5EAFA,#9A33CC,#333333',
												),
												'timeline_dark_orange' => array(
													'preset_name' => esc_html__( 'Dark Orange', 'blog-designer-pro' ),
													'preset_value' => 'displaydate_backcolor:#FF8A00,template_color:#FF8A00,template_bgcolor:#FFF3E5,template_ftcolor:#FF8A00,template_fthovercolor:#444444,template_titlecolor:#FF8A00,template_titlehovercolor:#444444,template_titlebackcolor:#FFF3E5,template_contentcolor:#333333,template_readmorecolor:#ffffff,template_readmorebackcolor:#FF8A00,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#FF8A00,beforeloop_readmorehovercolor:#FF8A00,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#FF8A00,#FFF3E5,#FF8A00,#333333',
												),
												'timeline_venetian_red' => array(
													'preset_name' => esc_html__( 'Venetian Red', 'blog-designer-pro' ),
													'preset_value' => 'displaydate_backcolor:#CC0001,template_color:#CC0001,template_bgcolor:#FAE4E6,template_ftcolor:#CC0001,template_fthovercolor:#444444,template_titlecolor:#CC0001,template_titlehovercolor:#444444,template_titlebackcolor:#FAE4E6,template_contentcolor:#333333,template_readmorecolor:#ffffff,template_readmorebackcolor:#CC0001,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#CC0001,beforeloop_readmorehovercolor:#CC0001,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#CC0001,#FAE4E6,#CC0001,#333333',
												),
											),
											'winter'       => array(
												'winter_default' => array(
													'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
													'preset_value' => 'winter_category_color:#E7492F,template_bgcolor:#ffffff,template_ftcolor:#E7492F,template_fthovercolor:#555555,template_titlecolor:#222222,template_titlehovercolor:#ED4961,template_titlebackcolor:#ffffff,template_contentcolor:#666666,template_readmorecolor:#ffffff,template_readmorebackcolor:#ED4961,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#ED4961,beforeloop_readmorehovercolor:#ED4961,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#E7492F,#222222,#555555,#666666',
												),
												'winter_toddy' => array(
													'preset_name' => esc_html__( 'Toddy', 'blog-designer-pro' ),
													'preset_value' => 'winter_category_color:#BE9055,template_bgcolor:#F9F5F1,template_ftcolor:#555555,template_fthovercolor:#BE9055,template_titlecolor:#AE742A,template_titlehovercolor:#BE9055,template_titlebackcolor:#F9F5F1,template_contentcolor:#666666,template_readmorecolor:#F9F5F1,template_readmorebackcolor:#BE9055,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#BE9055,beforeloop_readmorehovercolor:#BE9055,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#AE742A,#BE9055,#555555,#666666',
												),
												'winter_bronzetone' => array(
													'preset_name' => esc_html__( 'Bronzetone', 'blog-designer-pro' ),
													'preset_value' => 'winter_category_color:#67704E,template_bgcolor:#E5E7E1,template_ftcolor:#555555,template_fthovercolor:#67704E,template_titlecolor:#414C22,template_titlehovercolor:#E5E7E1,template_titlebackcolor:#E5E7E1,template_contentcolor:#666666,template_readmorecolor:#E5E7E1,template_readmorebackcolor:#67704E,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#67704E,beforeloop_readmorehovercolor:#67704E,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#414C22,#67704E,#555555,#666666',
												),
												'winter_cerulean' => array(
													'preset_name' => esc_html__( 'Cerulean', 'blog-designer-pro' ),
													'preset_value' => 'winter_category_color:#3E91AD,template_bgcolor:#EEF6F8,template_ftcolor:#555555,template_fthovercolor:#3E91AD,template_titlecolor:#0E7699,template_titlehovercolor:#3E91AD,template_titlebackcolor:#EEF6F8,template_contentcolor:#666666,template_readmorecolor:#EEF6F8,template_readmorebackcolor:#3E91AD,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#3E91AD,beforeloop_readmorehovercolor:#3E91AD,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#0E7699,#3E91AD,#555555,#666666',
												),
												'winter_ce_soir' => array(
													'preset_name' => esc_html__( 'Ce Soir', 'blog-designer-pro' ),
													'preset_value' => 'winter_category_color:#A381BB,template_bgcolor:#F7F4F9,template_ftcolor:#555555,template_fthovercolor:#A381BB,template_titlecolor:#8C62AA,template_titlehovercolor:#3E91AD,template_titlebackcolor:#F7F4F9,template_contentcolor:#666666,template_readmorecolor:#F7F4F9,template_readmorebackcolor:#8C62AA,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#8C62AA,beforeloop_readmorehovercolor:#8C62AA,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#8C62AA,#A381BB,#555555,#666666',
												),
												'winter_yonder' => array(
													'preset_name' => esc_html__( 'Yonder', 'blog-designer-pro' ),
													'preset_value' => 'winter_category_color:#8BA3CE,template_bgcolor:#F5F7FB,template_ftcolor:#555555,template_fthovercolor:#8BA3CE,template_titlecolor:#6E8CC2,template_titlehovercolor:#8BA3CE,template_titlebackcolor:#F5F7FB,template_contentcolor:#666666,template_readmorecolor:#F5F7FB,template_readmorebackcolor:#8BA3CE,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#8BA3CE,beforeloop_readmorehovercolor:#8BA3CE,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#6E8CC2,#8BA3CE,#555555,#666666',
												),
											),
											'sallet_slider' => array(
												'sallet_slider_default' => array(
													'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#ffffff,template_ftcolor:#555555,template_fthovercolor:#3E8563,winter_category_color:#3E8563,template_titlecolor:#0E663C,template_titlehovercolor:#3E8563,template_contentcolor:#333333,template_readmorecolor:#ffffff,template_readmorebackcolor:#3E8563,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#3E8563,beforeloop_readmorehovercolor:#3E8563,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#0E663C,#3E8563,#555555,#333333',
												),
												'sallet_slider_red_violet' => array(
													'preset_name' => esc_html__( 'Red Violet', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#FBF3F8,template_ftcolor:#555555,template_fthovercolor:#C44C91,winter_category_color:#C44C91,template_titlecolor:#B51F76,template_titlehovercolor:#C44C91,template_contentcolor:#333333,template_readmorecolor:#ffffff,template_readmorebackcolor:#C44C91,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#C44C91,beforeloop_readmorehovercolor:#C44C91,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#B51F76,#C44C91,#555555,#333333',
												),
												'sallet_slider_lemon_ginger' => array(
													'preset_name' => esc_html__( 'Lemon Ginger', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#F7F6F1,template_ftcolor:#555555,template_fthovercolor:#A39D5A,winter_category_color:#A39D5A,template_titlecolor:#8C8431,template_titlehovercolor:#A39D5A,template_contentcolor:#333333,template_readmorecolor:#ffffff,template_readmorebackcolor:#A39D5A,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#A39D5A,beforeloop_readmorehovercolor:#A39D5A,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#8C8431,#A39D5A,#555555,#333333',
												),
												'sallet_slider_cerulean' => array(
													'preset_name' => esc_html__( 'Cerulean', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#EEF6F8,template_ftcolor:#555555,template_fthovercolor:#3E91AD,winter_category_color:#3E91AD,template_titlecolor:#0E7699,template_titlehovercolor:#3E91AD,template_contentcolor:#333333,template_readmorecolor:#ffffff,template_readmorebackcolor:#3E91AD,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#3E91AD,beforeloop_readmorehovercolor:#3E91AD,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#0E7699,#3E91AD,#555555,#333333',
												),
												'sallet_slider_buttercup' => array(
													'preset_name' => esc_html__( 'Buttercup', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#FDF7F1,template_ftcolor:#555555,template_fthovercolor:#E5A452,winter_category_color:#E5A452,template_titlecolor:#DF8D27,template_titlehovercolor:#E5A452,template_contentcolor:#333333,template_readmorecolor:#ffffff,template_readmorebackcolor:#E5A452,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#E5A452,beforeloop_readmorehovercolor:#E5A452,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#DF8D27,#E5A452,#555555,#333333',
												),
												'sallet_slider_wasabi' => array(
													'preset_name' => esc_html__( 'Wasabi', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#F6F7F1,template_ftcolor:#555555,template_fthovercolor:#93A564,winter_category_color:#93A564,template_titlecolor:#788F3D,template_titlehovercolor:#93A564,template_contentcolor:#333333,template_readmorecolor:#ffffff,template_readmorebackcolor:#93A564,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#93A564,beforeloop_readmorehovercolor:#93A564,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#788F3D,#93A564,#555555,#333333',
												),
											),
											'colorful_sliding' => array(
												'colorful_sliding_default' => array(
													'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#ffffff,template_ftcolor:#555555,template_fthovercolor:#3E8563,winter_category_color:#3E8563,template_titlecolor:#0E663C,template_titlehovercolor:#3E8563,template_contentcolor:#333333,template_readmorecolor:#ffffff,template_readmorebackcolor:#3E8563,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#3E8563,beforeloop_readmorehovercolor:#3E8563,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#0E663C,#3E8563,#555555,#333333',
												),
												'colorful_sliding_red_violet' => array(
													'preset_name' => esc_html__( 'Red Violet', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#FBF3F8,template_ftcolor:#555555,template_fthovercolor:#C44C91,winter_category_color:#C44C91,template_titlecolor:#B51F76,template_titlehovercolor:#C44C91,template_contentcolor:#333333,template_readmorecolor:#ffffff,template_readmorebackcolor:#C44C91,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#C44C91,beforeloop_readmorehovercolor:#C44C91,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#B51F76,#C44C91,#555555,#333333',
												),
												'colorful_sliding_lemon_ginger' => array(
													'preset_name' => esc_html__( 'Lemon Ginger', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#F7F6F1,template_ftcolor:#555555,template_fthovercolor:#A39D5A,winter_category_color:#A39D5A,template_titlecolor:#8C8431,template_titlehovercolor:#A39D5A,template_contentcolor:#333333,template_readmorecolor:#ffffff,template_readmorebackcolor:#A39D5A,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#A39D5A,beforeloop_readmorehovercolor:#A39D5A,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#8C8431,#A39D5A,#555555,#333333',
												),
												'colorful_sliding_cerulean' => array(
													'preset_name' => esc_html__( 'Cerulean', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#EEF6F8,template_ftcolor:#555555,template_fthovercolor:#3E91AD,winter_category_color:#3E91AD,template_titlecolor:#0E7699,template_titlehovercolor:#3E91AD,template_contentcolor:#333333,template_readmorecolor:#ffffff,template_readmorebackcolor:#3E91AD,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#3E91AD,beforeloop_readmorehovercolor:#3E91AD,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#0E7699,#3E91AD,#555555,#333333',
												),
												'colorful_sliding_buttercup' => array(
													'preset_name' => esc_html__( 'Buttercup', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#FDF7F1,template_ftcolor:#555555,template_fthovercolor:#E5A452,winter_category_color:#E5A452,template_titlecolor:#DF8D27,template_titlehovercolor:#E5A452,template_contentcolor:#333333,template_readmorecolor:#ffffff,template_readmorebackcolor:#E5A452,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#E5A452,beforeloop_readmorehovercolor:#E5A452,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#DF8D27,#E5A452,#555555,#333333',
												),
												'colorful_sliding_wasabi' => array(
													'preset_name' => esc_html__( 'Wasabi', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#F6F7F1,template_ftcolor:#555555,template_fthovercolor:#93A564,winter_category_color:#93A564,template_titlecolor:#788F3D,template_titlehovercolor:#93A564,template_contentcolor:#333333,template_readmorecolor:#ffffff,template_readmorebackcolor:#93A564,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#93A564,beforeloop_readmorehovercolor:#93A564,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#788F3D,#93A564,#555555,#333333',
												),
											),
											'sunshiny_slider' => array(
												'sunshiny_slider_default' => array(
													'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#000000,template_ftcolor:#ffffff,template_fthovercolor:#ff00ae,winter_category_color:#ff00ae,template_titlecolor:#ffffff,template_titlehovercolor:#ff00ae,template_contentcolor:#333333,template_readmorecolor:#ffffff,template_readmorebackcolor:#ff00ae',
													'display_value' => '#ffffff,#ff00ae,#ffffff,#333333',
												),
												'sunshiny_slider_radical_red' => array(
													'preset_name' => esc_html__( 'Radical Red', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#330a12,template_ftcolor:#ffeaee,template_fthovercolor:#ff355e,winter_category_color:#ff355e,template_titlecolor:#ffd6de,template_titlehovercolor:#FA336C,template_contentcolor:#ffeaee,template_readmorecolor:#ffffff,template_readmorebackcolor:#FA336C',
													'display_value' => '#330a12,#ff355e,#ffd6de,#ffeaee',
												),
												'sunshiny_slider_eminence' => array(
													'preset_name' => esc_html__( 'Eminence', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#2b1334,template_ftcolor:#e1d5e6,template_fthovercolor:#2b1334,winter_category_color:#6c3082,template_titlecolor:#e1d5e6,template_titlehovercolor:#2b1334,template_contentcolor:#f0eaf2,template_readmorecolor:#ffffff,template_readmorebackcolor:#683C6F',
													'display_value' => '#2b1334,#6c3082,#e1d5e6,#f0eaf2',
												),
												'sunshiny_slider_dark_orange' => array(
													'preset_name' => esc_html__( 'Dark Orange', 'blog-designer-pro' ),
													'preset_value' => 'winter_category_color:#FF8A00,template_color:#FFF3E5,template_ftcolor:#FF8A00,template_fthovercolor:#2b2b2b,template_titlecolor:#FF8A00,template_titlehovercolor:#2b2b2b,template_contentcolor:#2b2b2b,template_readmorecolor:#ffffff,template_readmorebackcolor:#FF8A00',
													'display_value' => '#FF8A00,#FFF3E5,#ffffff,#2b2b2b',
												),
												'sunshiny_slider_persian_red' => array(
													'preset_name' => esc_html__( 'Persian Red', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#3d0f0f,template_ftcolor:#ffffff,template_fthovercolor:#3d0f0f,winter_category_color:#cc3333,template_titlecolor:#f4d6d6,template_titlehovercolor:#3d0f0f,template_contentcolor:#f9eaea,template_readmorecolor:#ffffff,template_readmorebackcolor:#DC3330',
													'display_value' => '#3d0f0f,#cc3333,#f4d6d6,#f9eaea',
												),
												'sunshiny_slider_venetian_red' => array(
													'preset_name' => esc_html__( 'Venetian Red', 'blog-designer-pro' ),
													'preset_value' => 'winter_category_color:#CC0001,template_color:#FAE4E6,template_ftcolor:#ffffff,template_fthovercolor:#2b2b2b,template_titlecolor:#ffffff,template_titlehovercolor:#2b2b2b,template_contentcolor:#ffffff,template_readmorecolor:#ffffff,template_readmorebackcolor:#CC0001',
													'display_value' => '#FAE4E6,#CC0001,#ffffff,#2b2b2b',
												),
											),
											'pretty'       => array(
												'pretty_default' => array(
													'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#ff93a3,template_bgcolor:#ffffff,template_ftcolor:#b7b7b7,template_fthovercolor:#859f88,template_titlecolor:#859f88,template_titlehovercolor:#ff93a3,template_titlebackcolor:#ffffff,template_readmorecolor:#f7fbfc,template_readmorebackcolor:#859f88,template_contentcolor:#484848,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#859f88,beforeloop_readmorehovercolor:#859f88,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#ff93a3,#ffffff,#484848,#859f88',
												),
												'pretty_sky_blue' => array(
													'preset_name' => esc_html__( 'Sky Blue', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#00809D,template_bgcolor:#DAEBFF,template_ftcolor:#888888,template_fthovercolor:#00809D,template_titlecolor:#00809D,template_titlehovercolor:#888888,template_titlebackcolor:#DAEBFF,template_readmorecolor:#f7fbfc,template_readmorebackcolor:#00809D,template_contentcolor:#484848,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#DAEBFF,beforeloop_readmorehovercolor:#DAEBFF,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#00809D,#DAEBFF,#484848,#888888',
												),
												'pretty_lite_green' => array(
													'preset_name' => esc_html__( 'Lite Green', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#0ef58d,template_bgcolor:#C3F3DD,template_ftcolor:#888888,template_fthovercolor:#E21130,template_titlecolor:#e21130,template_titlehovercolor:#0ef58d,template_titlebackcolor:#C3F3DD,template_readmorecolor:#f7fbfc,template_readmorebackcolor:#E21130,template_contentcolor:#484848,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#C3F3DD,beforeloop_readmorehovercolor:#C3F3DD,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#0ef58d,#C3F3DD,#484848,#E21130',
												),
											),
											'tagly'        => array(
												'tagly_default' => array(
													'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#b79a5e,template_bgcolor:#ffffff,template_ftcolor:#555555,template_fthovercolor:#b79a5e,template_titlecolor:#333333,template_titlehovercolor:#b79a5e,template_titlebackcolor:#ffffff,template_readmorecolor:#ffffff,template_readmorebackcolor:#b79a5e,template_contentcolor:#999999,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#b79a5e,beforeloop_readmorehovercolor:#b79a5e,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#b79a5e,#333333,#555555,#999999',
												),
												'tagly_earls_green' => array(
													'preset_name' => esc_html__( 'Earls Green', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#CEBF59,template_bgcolor:#ffffff,template_ftcolor:#555555,template_fthovercolor:#CEBF59,template_titlecolor:#C2AF2F,template_titlehovercolor:#CEBF59,template_titlebackcolor:#ffffff,template_readmorecolor:#ffffff,template_readmorebackcolor:#CEBF59,template_contentcolor:#999999,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#CEBF59,beforeloop_readmorehovercolor:#CEBF59,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#C2AF2F,#CEBF59,#555555,#999999',
												),
												'tagly_cerulean' => array(
													'preset_name' => esc_html__( 'Cerulean', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#3E91AD,template_bgcolor:#ffffff,template_ftcolor:#555555,template_fthovercolor:#3E91AD,template_titlecolor:#0E7699,template_titlehovercolor:#3E91AD,template_titlebackcolor:#ffffff,template_readmorecolor:#ffffff,template_readmorebackcolor:#3E91AD,template_contentcolor:#999999,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#3E91AD,beforeloop_readmorehovercolor:#3E91AD,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#0E7699,#3E91AD,#555555,#999999',
												),
												'tagly_pompadour' => array(
													'preset_name' => esc_html__( 'Pompadour', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#974772,template_bgcolor:#ffffff,template_ftcolor:#555555,template_fthovercolor:#974772,template_titlecolor:#7D194F,template_titlehovercolor:#974772,template_titlebackcolor:#ffffff,template_readmorecolor:#ffffff,template_readmorebackcolor:#974772,template_contentcolor:#999999,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#974772,beforeloop_readmorehovercolor:#974772,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#7D194F,#974772,#555555,#999999',
												),
												'tagly_alizarin' => array(
													'preset_name' => esc_html__( 'Alizarin', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#ED4961,template_bgcolor:#FDF0F1,template_ftcolor:#555555,template_fthovercolor:#ED4961,template_titlecolor:#E81B3A,template_titlehovercolor:#ED4961,template_titlebackcolor:#FDF0F1,template_readmorecolor:#ffffff,template_readmorebackcolor:#ED4961,template_contentcolor:#999999,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#ED4961,beforeloop_readmorehovercolor:#ED4961,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#E81B3A,#ED4961,#555555,#999999',
												),
												'tagly_yonder' => array(
													'preset_name' => esc_html__( 'Yonder', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#8BA3CE,template_bgcolor:#F5F7FB,template_ftcolor:#555555,template_fthovercolor:#8BA3CE,template_titlecolor:#6E8CC2,template_titlehovercolor:#8BA3CE,template_titlebackcolor:#F5F7FB,template_readmorecolor:#ffffff,template_readmorebackcolor:#8BA3CE,template_contentcolor:#999999,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#8BA3CE,beforeloop_readmorehovercolor:#8BA3CE,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#6E8CC2,#8BA3CE,#555555,#999999',
												),
											),
											'foodbox'      => array(
												'foodbox_default' => array(
													'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#6e3d30,template_bgcolor:#f7f4ef,template_ftcolor:#444444,template_fthovercolor:#000000,template_titlecolor:#312725,template_titlehovercolor:#000000,template_readmorecolor:#ffffff,template_readmorebackcolor:#6e3d30,template_contentcolor:#444444,
                                                    template_readmore_hover_backcolor:#000000,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#6e3d30,beforeloop_readmorehovercolor:#ffffff,beforeloop_readmorehoverbackcolor:#000000',
													'display_value' => '#312725,#000000,#6e3d30,#444444',
												),
												'foodbox_radical' => array(
													'preset_name' => esc_html__( 'Radical', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#4F171C,template_bgcolor:#FDE7E9,template_ftcolor:#4F171C,template_fthovercolor:#7C242C,template_titlecolor:#F34656,template_titlehovercolor:#4F171C,template_titlebackcolor:#FDE7E9,template_contentcolor:#7C242C,template_readmorecolor:#f1f1f1,template_readmorebackcolor:#F34656,template_readmore_hover_backcolor:#F9A1A9,beforeloop_readmorecolor:#F34656,beforeloop_readmorebackcolor:#FDE7E9,beforeloop_readmorehovercolor:#F34656,beforeloop_readmorehoverbackcolor:#F9A1A9,bdp_sale_tagbgcolor:#4F171C,bdp_sale_tagtextcolor:#FDE7E9,bdp_star_rating_color:#7C242C,bdp_star_rating_bg_color:#F34656,bdp_pricetextcolor:#FDE7E9,bdp_wishlist_textcolor:#FDE7E9,bdp_wishlist_backgroundcolor:#4F171C,bdp_wishlist_text_hover_color:#FDE7E9,bdp_wishlist_hover_backgroundcolor:#7C242C,bdp_addtocart_textcolor:#FDE7E9,bdp_addtocart_backgroundcolor:#F34656,bdp_addtocart_text_hover_color:#FDE7E9,bdp_addtocart_hover_backgroundcolor:#7C242C',
													'display_value' => '#FDE7E9,#F34656,#7C242C,#4F171C',
												),
												'Foodbox_tangerine' => array(
													'preset_name' => esc_html__( 'Tangerine', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#efb828,template_bgcolor:#fdf7e7,template_ftcolor:#171101,template_fthovercolor:#473505,template_titlecolor:#473505,template_titlehovercolor:#efb828,template_titlebackcolor:#fdf7e7,template_contentcolor:#171101,template_readmorecolor:#fdf7e7,template_readmorebackcolor:#171101,template_readmore_hover_backcolor:#efb828,beforeloop_readmorecolor:#fdf7e7,beforeloop_readmorebackcolor:#171101,beforeloop_readmorehovercolor:#fdf7e7,beforeloop_readmorehoverbackcolor:#efb828,bdp_sale_tagbgcolor:#171101,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#171101,bdp_star_rating_bg_color:#473505,bdp_pricetextcolor:#171101,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#888888,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#473505,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#171101,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#473505',
													'display_value' => '#f8df9f,#efb828,#473505,#171101',
												),
												'foodbox_rich-gold' => array(
													'preset_name' => esc_html__( 'Rich Gold', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#A95624,template_bgcolor:#ffd1b6,template_ftcolor:#444444,template_fthovercolor:#A95624,template_titlecolor:#A95624,template_titlehovercolor:#BA7850,template_titlebackcolor:#ffd1b6,template_contentcolor:#444444,template_readmorecolor:#BA7850,template_readmorebackcolor:#ffd1b6,beforeloop_readmorecolor:#f1f1f1,beforeloop_readmorebackcolor:#BA7850,beforeloop_readmorehovercolor:#BA7850,beforeloop_readmorehoverbackcolor:#f1f1f1,bdp_sale_tagbgcolor:#A95624,bdp_sale_tagtextcolor:#f1f1f1,bdp_star_rating_color:#444444,bdp_star_rating_bg_color:#555555,bdp_pricetextcolor:#BA7850,bdp_wishlist_textcolor:#f1f1f1,bdp_wishlist_backgroundcolor:#555555,bdp_wishlist_text_hover_color:#f1f1f1,bdp_wishlist_hover_backgroundcolor:#BA7850,bdp_addtocart_textcolor:#f1f1f1,bdp_addtocart_backgroundcolor:#A95624,bdp_addtocart_text_hover_color:#f1f1f1,bdp_addtocart_hover_backgroundcolor:#444444',
													'display_value' => '#BA7850,#A95624,#555555,#444444',
												),
											),
											'neaty_block'  => array(
												'neaty_block_default' => array(
													'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#c4e2f7,template_ftcolor:#444444,template_fthovercolor:#000000,template_titlecolor:#444444,template_titlehovercolor:#000000,template_readmorecolor:#ffffff,template_readmorebackcolor:#444444,template_contentcolor:#444444,
                                                    template_readmore_hover_backcolor:#000000,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#444444,beforeloop_readmorehovercolor:#444444,beforeloop_readmorehoverbackcolor:#000000',
													'display_value' => '#444444,#000000,#444444,#000000',
												),
												'neaty_block_roof_terracotta' => array(
													'preset_name' => esc_html__( 'Roof Terracotta', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#B06F6D,template_bgcolor:#ffffff,template_bghovercolor:#F1E7E7,template_ftcolor:#555555,template_fthovercolor:#B06F6D,template_titlecolor:#9C4B48,template_titlehovercolor:#B06F6D,template_contentcolor:#999999,template_readmorecolor:#ffffff,template_readmorebackcolor:#B06F6D,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#B06F6D,beforeloop_readmorehovercolor:#B06F6D,beforeloop_readmorehoverbackcolor:#ffffff,bdp_sale_tagbgcolor:#B06F6D,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#999999,bdp_star_rating_bg_color:#B06F6D,bdp_pricetextcolor:#999999,bdp_wishlist_textcolor:#B06F6D,bdp_wishlist_backgroundcolor:#555555,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#B06F6D,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#B06F6D,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#555555',
													'display_value' => '#9C4B48,#B06F6D,#555555,#999999',
												),
												'neaty_block_bronzetone' => array(
													'preset_name' => esc_html__( 'Bronzetone', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#555555,template_fthovercolor:#67704E,template_titlecolor:#414C22,template_titlehovercolor:#67704E,template_titlebackcolor:#ffffff,template_contentcolor:#999999,template_readmorecolor:#f1f1f1,template_readmorebackcolor:#67704E,beforeloop_readmorecolor:#f1f1f1,beforeloop_readmorebackcolor:#67704E,beforeloop_readmorehovercolor:#67704E,beforeloop_readmorehoverbackcolor:#f1f1f1,bdp_sale_tagbgcolor:#414C22,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#999999,bdp_star_rating_bg_color:#555555,bdp_pricetextcolor:#67704E,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#67704E,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#555555,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#414C22,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#999999',
													'display_value' => '#414C22,#67704E,#555555,#999999',
												),
												'offer_yonder' => array(
													'preset_name' => esc_html__( 'Yonder', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#F3F5FA,template_ftcolor:#555555,template_fthovercolor:#8BA3CE,template_titlecolor:#6E8CC2,template_titlehovercolor:#8BA3CE,template_titlebackcolor:#F3F5FA,template_contentcolor:#666666,template_readmorecolor:#ffffff,template_readmorebackcolor:#8BA3CE,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#8BA3CE,beforeloop_readmorehovercolor:#8BA3CE,beforeloop_readmorehoverbackcolor:#ffffff,bdp_sale_tagbgcolor:#6E8CC2,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#666666,bdp_star_rating_bg_color:#555555,bdp_pricetextcolor:#6E8CC2,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#555555,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#8BA3CE,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#6E8CC2,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#666666',
													'display_value' => '#6E8CC2,#8BA3CE,#555555,#666666',
												),
											),
											'wise_block'   => array(
												'wise_block_default' => array(
													'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#444444,template_bgcolor:#c4e2f7,template_ftcolor:#444444,template_fthovercolor:#000000,template_titlecolor:#444444,template_titlehovercolor:#000000,template_readmorecolor:#ffffff,template_readmorebackcolor:#444444,template_contentcolor:#444444,
                                                    template_readmore_hover_backcolor:#000000,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#444444,beforeloop_readmorehovercolor:#444444,beforeloop_readmorehoverbackcolor:#000000',
													'display_value' => '#444444,#000000,#444444,#000000',
												),
												'wise_block_goldenrod' => array(
													'preset_name' => esc_html__( 'Goldenrod', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#F4EDDD,template_ftcolor:#3A2A02,template_fthovercolor:#5B4204,template_titlecolor:#5B4204,template_titlehovercolor:#3A2A02,template_titlebackcolor:#ffffff,template_contentcolor:#5B4204,template_readmorecolor:#B28007,template_readmorebackcolor:#F4EDDD,template_readmore_hover_backcolor:#D7BD81,beforeloop_readmorecolor:#B28007,beforeloop_readmorebackcolor:#F4EDDD,beforeloop_readmorehovercolor:#B28007,beforeloop_readmorehoverbackcolor:#D7BD81,bdp_sale_tagbgcolor:#3A2A02,bdp_sale_tagtextcolor:#F4EDDD,bdp_star_rating_color:#5B4204,bdp_star_rating_bg_color:#B28007,bdp_pricetextcolor:#F4EDDD,bdp_wishlist_textcolor:#F4EDDD,bdp_wishlist_backgroundcolor:#3A2A02,bdp_wishlist_text_hover_color:#F4EDDD, bdp_wishlist_hover_backgroundcolor:#5B4204,bdp_addtocart_textcolor:#F4EDDD,bdp_addtocart_backgroundcolor:#B28007,bdp_addtocart_text_hover_color:#F4EDDD,bdp_addtocart_hover_backgroundcolor:#5B4204',
													'display_value' => '#F4EDDD,#B28007,#5B4204,#3A2A02',
												),
												'wise_block_salem' => array(
													'preset_name' => esc_html__( 'Salem', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#e8f3ed,template_bgcolor:#e8f3ed,template_ftcolor:#198c4b,template_fthovercolor:#666666,template_titlecolor:#333333,template_titlehovercolor:#198c4b,template_titlebackcolor:,template_contentcolor:#666666,template_readmorecolor:#ffffff,template_readmorebackcolor:#666666,template_readmore_hover_backcolor:#198c4b,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#666666,beforeloop_readmorehovercolor:#ffffff,beforeloop_readmorehoverbackcolor:#198c4b,bdp_sale_tagbgcolor:#198c4b,bdp_sale_tagtextcolor:#e8f3ed,bdp_star_rating_color:#666666,bdp_star_rating_bg_color:#198c4b,bdp_pricetextcolor:#333333,bdp_wishlist_textcolor:#e8f3ed,bdp_wishlist_backgroundcolor:#198c4b,bdp_wishlist_text_hover_color:#e8f3ed,bdp_wishlist_hover_backgroundcolor:#666666,bdp_addtocart_textcolor:#e8f3ed,bdp_addtocart_backgroundcolor:#333333,bdp_addtocart_text_hover_color:#e8f3ed,bdp_addtocart_hover_backgroundcolor:#198c4b',
													'display_value' => '#e8f3ed,#198c4b,#333333,#666666',
												),
												'wise_block_prussian' => array(
													'preset_name' => esc_html__( 'Prussian', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#99a8ba,template_bgcolor:#e5e9ed,template_ftcolor:#000308,template_fthovercolor:#000b19,template_titlecolor:#193b65,template_titlehovercolor:#000b19,template_titlebackcolor:,template_contentcolor:#000308,template_readmorecolor:#e5e9ed,template_readmorebackcolor:#000308,template_readmore_hover_backcolor:#193b65,beforeloop_readmorecolor:#e5e9ed,beforeloop_readmorebackcolor:#000308,beforeloop_readmorehovercolor:#e5e9ed,beforeloop_readmorehoverbackcolor:#193b65,bdp_sale_tagbgcolor:#171101,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#171101,bdp_star_rating_bg_color:#193b65,bdp_pricetextcolor:#000308,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#000b19,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#193b65,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#171101,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#000308',
													'display_value' => '#99a8ba,#193b65,#000b19,#000308',
												),
												'wise_block_pompadour' => array(
													'preset_name' => esc_html__( 'Pompadour', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#EAE1E7,template_ftcolor:#35122A,template_fthovercolor:#220B1B,template_titlecolor:#35122A,template_titlehovercolor:#662451,template_contentcolor:#EAE1E7,template_contentcolor:#35122A,template_readmorecolor:#EAE1E7,template_readmorebackcolor:#662451,template_readmore_hover_backcolor:#35122A,beforeloop_readmorecolor:#EAE1E7,beforeloop_readmorebackcolor:#662451,beforeloop_readmorehovercolor:#A49538,beforeloop_readmorehoverbackcolor:#35122A,bdp_sale_tagbgcolor:#662451,bdp_sale_tagtextcolor:#EAE1E7,bdp_star_rating_color:#35122A,bdp_star_rating_bg_color:#220B1B,bdp_pricetextcolor:#35122A,bdp_wishlist_textcolor:#EAE1E7,bdp_wishlist_backgroundcolor:#35122A,bdp_wishlist_text_hover_color:#EAE1E7,bdp_wishlist_hover_backgroundcolor:#662451,bdp_addtocart_textcolor:#EAE1E7,bdp_addtocart_backgroundcolor:#662451,bdp_addtocart_text_hover_color:#EAE1E7,bdp_addtocart_hover_backgroundcolor:#220B1B',
													'display_value' => '#EAE1E7,#662451,#220B1B,#35122A',
												),
												'wise_block_go_ben' => array(
													'preset_name' => esc_html__( 'Go Ben', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#EDECE7,template_ftcolor:#3E3B25,template_fthovercolor:#282618,template_titlecolor:#3E3B25,template_titlehovercolor:#787449,template_contentcolor:#EDECE7,template_contentcolor:#3E3B25,template_readmorecolor:#EDECE7,template_readmorebackcolor:#787449,template_readmore_hover_backcolor:#3E3B25,beforeloop_readmorecolor:#EDECE7,beforeloop_readmorebackcolor:#787449,beforeloop_readmorehovercolor:#A49538,beforeloop_readmorehoverbackcolor:#3E3B25,bdp_sale_tagbgcolor:#787449,bdp_sale_tagtextcolor:#EDECE7,bdp_star_rating_color:#3E3B25,bdp_star_rating_bg_color:#282618,bdp_pricetextcolor:#3E3B25,bdp_wishlist_textcolor:#EDECE7,bdp_wishlist_backgroundcolor:#3E3B25,bdp_wishlist_text_hover_color:#EDECE7,bdp_wishlist_hover_backgroundcolor:#787449,bdp_addtocart_textcolor:#EDECE7,bdp_addtocart_backgroundcolor:#787449,bdp_addtocart_text_hover_color:#EDECE7,bdp_addtocart_hover_backgroundcolor:#282618',
													'display_value' => '#EDECE7,#787449,#282618,#3E3B25',
												),
											),
											'soft_block'   => array(
												'soft_block_default' => array(
													'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#444444,template_bgcolor:#f7f4ef,template_ftcolor:#444444,template_fthovercolor:#444444,template_titlecolor:#444444,template_titlehovercolor:#ece0e0,template_readmorecolor:#ffffff,template_contentcolor:#444444,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#444444,beforeloop_readmorehovercolor:#444444,beforeloop_readmorehoverbackcolor:#000000,template_bgcolor1:#4fbfc1,template_bgcolor2:#508FC4,template_bgcolor3:#F47882,template_bgcolor4:#F0CF80,template_bgcolor5:#75C77D,template_bgcolor6:#76ABD5',
													'display_value' => '#444444,#000000,#ece0e0,#ffffff',
												),
												'soft_block_lochmara' => array(
													'preset_name' => esc_html__( 'Lochmara', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#F7FAFC,template_ftcolor:#227CAD,template_fthovercolor:#0E3246,template_titlecolor:#F7FAFC,template_titlehovercolor:#227CAD,template_contentcolor:#F7FAFC,template_readmorecolor:#F7FAFC,template_readmorebackcolor:#227CAD,template_readmore_hover_backcolor:#0E3246,beforeloop_readmorecolor:#F7FAFC,beforeloop_readmorebackcolor:#227CAD,beforeloop_readmorehovercolor:#F7FAFC,beforeloop_readmorehoverbackcolor:#0E3246,bdp_sale_tagbgcolor:#227CAD,bdp_sale_tagtextcolor:#F7FAFC,bdp_star_rating_color:#09202D,bdp_star_rating_bg_color:#051117,bdp_pricetextcolor:#051117,bdp_wishlist_textcolor:#F7FAFC,bdp_wishlist_backgroundcolor:#051117,bdp_wishlist_text_hover_color:#F7FAFC,bdp_wishlist_hover_backgroundcolor:#227CAD,bdp_addtocart_textcolor:#F7FAFC,bdp_addtocart_backgroundcolor:#227CAD,bdp_addtocart_text_hover_color:#F7FAFC,bdp_addtocart_hover_backgroundcolor:#051117',
													'display_value' => '#F7FAFC,#227CAD,#051117,#09202D',
												),
												'soft_block_burgundy' => array(
													'preset_name' => esc_html__( 'Burgundy', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#FAF6F7,template_ftcolor:#6C0124,template_fthovercolor:#2C010E,template_titlecolor:#FAF6F7,template_titlehovercolor:#6C0124,template_contentcolor:#FAF6F7,template_readmorecolor:#FAF6F7,template_readmorebackcolor:#6C0124,template_readmore_hover_backcolor:#2C010E,beforeloop_readmorecolor:#FAF6F7,beforeloop_readmorebackcolor:#6C0124,beforeloop_readmorehovercolor:#FAF6F7,beforeloop_readmorehoverbackcolor:#2C010E,bdp_sale_tagbgcolor:#6C0124,bdp_sale_tagtextcolor:#FAF6F7,bdp_star_rating_color:#1C0109,bdp_star_rating_bg_color:#0E0105,bdp_pricetextcolor:#0E0105,bdp_wishlist_textcolor:#FAF6F7,bdp_wishlist_backgroundcolor:#0E0105,bdp_wishlist_text_hover_color:#FAF6F7,bdp_wishlist_hover_backgroundcolor:#6C0124,bdp_addtocart_textcolor:#FAF6F7,bdp_addtocart_backgroundcolor:#6C0124,bdp_addtocart_text_hover_color:#FAF6F7,bdp_addtocart_hover_backgroundcolor:#0E0105',
													'display_value' => '#FAF6F7,#6C0124,#0E0105,#1C0109',
												),
												'soft_block_hillary' => array(
													'preset_name' => esc_html__( 'Hillary', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#FCFBFA,template_ftcolor:#A49E77,template_fthovercolor:#434131,template_titlecolor:#FCFBFA,template_titlehovercolor:#A49E77,template_contentcolor:#FCFBFA,template_readmorecolor:#FCFBFA,template_readmorebackcolor:#A49E77,template_readmore_hover_backcolor:#434131,beforeloop_readmorecolor:#FCFBFA,beforeloop_readmorebackcolor:#A49E77,beforeloop_readmorehovercolor:#FCFBFA,beforeloop_readmorehoverbackcolor:#434131,bdp_sale_tagbgcolor:#A49E77,bdp_sale_tagtextcolor:#FCFBFA,bdp_star_rating_color:#2B2A1F,bdp_star_rating_bg_color:#161610,bdp_pricetextcolor:#161610,bdp_wishlist_textcolor:#FCFBFA,bdp_wishlist_backgroundcolor:#161610,bdp_wishlist_text_hover_color:#FCFBFA,bdp_wishlist_hover_backgroundcolor:#A49E77,bdp_addtocart_textcolor:#FCFBFA,bdp_addtocart_backgroundcolor:#A49E77,bdp_addtocart_text_hover_color:#FCFBFA,bdp_addtocart_hover_backgroundcolor:#161610',
													'display_value' => '#FCFBFA,#A49E77,#161610,#2B2A1F',
												),
												'soft_block_amaranth' => array(
													'preset_name' => esc_html__( 'Amaranth', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#FDF9FA,template_ftcolor:#DE364A,template_fthovercolor:#491218,template_titlecolor:#FDF9FA,template_titlehovercolor:#DE364A,template_contentcolor:#FDF9FA,template_readmorecolor:#FDF9FA,template_readmorebackcolor:#DE364A,template_readmore_hover_backcolor:#491218,beforeloop_readmorecolor:#FDF9FA,beforeloop_readmorebackcolor:#DE364A,beforeloop_readmorehovercolor:#FDF9FA,beforeloop_readmorehoverbackcolor:#491218,bdp_sale_tagbgcolor:#DE364A,bdp_sale_tagtextcolor:#FDF9FA,bdp_star_rating_color:#2E0B0F,bdp_star_rating_bg_color:#1E070A,bdp_pricetextcolor:#1E070A,bdp_wishlist_textcolor:#FDF9FA,bdp_wishlist_backgroundcolor:#1E070A,bdp_wishlist_text_hover_color:#FDF9FA,bdp_wishlist_hover_backgroundcolor:#DE364A,bdp_addtocart_textcolor:#FDF9FA,bdp_addtocart_backgroundcolor:#DE364A,bdp_addtocart_text_hover_color:#FDF9FA,bdp_addtocart_hover_backgroundcolor:#1E070A',
													'display_value' => '#FDF9FA,#DE364A,#1E070A,#2E0B0F',
												),
												'soft_block_manatee' => array(
													'preset_name' => esc_html__( 'Manatee', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#FCFCFD,template_ftcolor:#9699A7,template_fthovercolor:#3E3E45,template_titlecolor:#FCFCFD,template_titlehovercolor:#9699A7,template_contentcolor:#FCFCFD,template_readmorecolor:#FCFCFD,template_readmorebackcolor:#9699A7,template_readmore_hover_backcolor:#3E3E45,beforeloop_readmorecolor:#FCFCFD,beforeloop_readmorebackcolor:#9699A7,beforeloop_readmorehovercolor:#FCFCFD,beforeloop_readmorehoverbackcolor:#3E3E45,bdp_sale_tagbgcolor:#DE364A,bdp_sale_tagtextcolor:#FCFCFD,bdp_star_rating_color:#9699A7,bdp_star_rating_bg_color:#151516,bdp_pricetextcolor:#151516,bdp_wishlist_textcolor:#FCFCFD,bdp_wishlist_backgroundcolor:#151516,bdp_wishlist_text_hover_color:#FCFCFD,bdp_wishlist_hover_backgroundcolor:#9699A7,bdp_addtocart_textcolor:#FCFCFD,bdp_addtocart_backgroundcolor:#9699A7,bdp_addtocart_text_hover_color:#FCFCFD,bdp_addtocart_hover_backgroundcolor:#151516',
													'display_value' => '#FCFCFD,#9699A7,#151516,#28282C',
												),
											),
											'schedule'     => array(
												'schedule_default' => array(
													'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#e21130,template_bgcolor:#ffffff,template_ftcolor:#a5a5a5,template_fthovercolor:#123123,template_titlecolor:#444444,,template_titlebackcolor:#ffffff,template_titlehovercolor:#123123,template_readmorecolor:#ffffff,template_contentcolor:#333333,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#444444,beforeloop_readmorehovercolor:#444444,beforeloop_readmorehoverbackcolor:#000000,winter_category_color:#a5a5a5',
													'display_value' => '#444444,#e21130,#333333,#ffffff',
												),
												'schedule_radical' => array(
													'preset_name' => esc_html__( 'Radical', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#7C242C,template_bgcolor:#FDE7E9,template_ftcolor:#F9A1A9,template_fthovercolor:#7C242C,template_titlecolor:#F34656,template_titlehovercolor:#4F171C,template_titlebackcolor:#FDE7E9,template_contentcolor:#7C242C,template_readmorecolor:#F34656,template_readmorebackcolor:#FDE7E9,template_readmore_hover_backcolor:#F9A1A9,beforeloop_readmorecolor:#F34656,beforeloop_readmorebackcolor:#FDE7E9,beforeloop_readmorehovercolor:#F34656,beforeloop_readmorehoverbackcolor:#F9A1A9,bdp_sale_tagbgcolor:#4F171C,bdp_sale_tagtextcolor:#FDE7E9,bdp_star_rating_color:#7C242C,bdp_star_rating_bg_color:#F34656,bdp_pricetextcolor:#FDE7E9,bdp_wishlist_textcolor:#FDE7E9,bdp_wishlist_backgroundcolor:#4F171C,bdp_wishlist_text_hover_color:#FDE7E9,bdp_wishlist_hover_backgroundcolor:#7C242C,bdp_addtocart_textcolor:#FDE7E9,bdp_addtocart_backgroundcolor:#F34656,bdp_addtocart_text_hover_color:#FDE7E9,bdp_addtocart_hover_backgroundcolor:#7C242C,
                                                        winter_category_color:#7C242C',
													'display_value' => '#FDE7E9,#F34656,#7C242C,#4F171C',
												),
												'schedule_mariner' => array(
													'preset_name' => esc_html__( 'Mariner', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#465BAC,template_bgcolor:#EAEDF6,template_ftcolor:#465BAC,template_fthovercolor:#ffffff,template_titlecolor:#465BAC,template_titlehovercolor:#484848,template_titlebackcolor:#eaedf6,template_contentcolor:#7b7b7b,template_readmorecolor:#465BAC,template_readmorebackcolor:#EAEDF6,bdp_sale_tagbgcolor:#465BAC,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#7b7b7b,bdp_star_rating_bg_color:#465BAC,bdp_pricetextcolor:#484848,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#484848,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#465BAC,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#465BAC,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#484848,winter_category_color:#465BAC',
													'display_value' => '#465BAC,#EAEDF6,#465BAC,#484848',
												),
												'schedule_flamenco' => array(
													'preset_name' => esc_html__( 'Flamenco', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#683C6F,template_bgcolor:#FFF3ED,template_ftcolor:#683C6F,template_fthovercolor:#ffffff,template_titlecolor:#683C6F,template_titlehovercolor:#484848,template_titlebackcolor:#FFF3ED,template_contentcolor:#7b7b7b,template_readmorecolor:#683C6F,template_readmorebackcolor:#FFF3ED,bdp_sale_tagbgcolor:#683C6F,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#7b7b7b,bdp_star_rating_bg_color:#683C6F,
                                                        bdp_wishlist_textcolor:#683C6F,bdp_wishlist_backgroundcolor:#FFF3ED,bdp_wishlist_text_hover_color:#FFF3ED,bdp_wishlist_hover_backgroundcolor:#683C6F,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#683C6F,bdp_addtocart_text_hover_color:#683C6F,bdp_addtocart_hover_backgroundcolor:#FFF3ED,
                                                        winter_category_color:#683C6F',
													'display_value' => '#683C6F,#FFF3ED,#683C6F,#484848',
												),
												'schedule_finch' => array(
													'preset_name' => esc_html__( 'Finch', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#75815B,template_bgcolor:#EFF0EB,template_ftcolor:#75815B,template_fthovercolor:#ffffff,template_titlecolor:#75815B,template_titlehovercolor:#484848,template_titlebackcolor:#EFF0EB,template_contentcolor:#7b7b7b,template_readmorecolor:#75815B,template_readmorebackcolor:#EFF0EB,bdp_sale_tagbgcolor:#75815B,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#7b7b7b,bdp_star_rating_bg_color:#75815B,
                                                        bdp_wishlist_textcolor:#75815B,bdp_wishlist_backgroundcolor:#EFF0EB,bdp_wishlist_text_hover_color:#EFF0EB,bdp_wishlist_hover_backgroundcolor:#75815B,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#75815B,bdp_addtocart_text_hover_color:#75815B,bdp_addtocart_hover_backgroundcolor:#EFF0EB,winter_category_color:#75815B',
													'display_value' => '#75815B,#EFF0EB,#75815B,#484848',
												),
											),
											'quci'         => array(
												'quci_default' => array(
													'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#3fd39d,template_bgcolor:#ffffff,template_ftcolor:#3fd39d,template_fthovercolor:#3fd39d,template_titlecolor:#222222,template_titlehovercolor:#3fd39d,template_titlebackcolor:#ffffff,template_contentcolor:#555555,template_content_hovercolor:#E84159,template_readmorecolor:#3fd39d,template_readmorebackcolor:#FFFFFF,beforeloop_readmorecolor:#3fd39d,beforeloop_readmorebackcolor:#ffffff,beforeloop_readmorehovercolor:#3fd39d,beforeloop_readmorehoverbackcolor:#ffffff,bdp_sale_tagbgcolor:#E84159,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#999999,bdp_star_rating_bg_color:#555555,bdp_pricetextcolor:#222222,bdp_edd_price_color:#222222,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#222222,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#555555,bdp_addtocart_textcolor:#ffffff,bdp_edd_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#E84159,bdp_edd_addtocart_backgroundcolor:#E84159,bdp_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#999999,bdp_edd_addtocart_hover_backgroundcolor:#999999',
													'display_value' => '#3fd39d,#222222,#555555,#999999',
												),
												'quci_madras' => array(
													'preset_name' => esc_html__( 'Madras', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#6D6145,template_bgcolor:#ffffff,template_ftcolor:#6D6145,template_fthovercolor:#6D6145,template_titlecolor:#493917,template_titlehovercolor:#6D6145,template_titlebackcolor:#ffffff,template_contentcolor:#999999,template_content_hovercolor:#6D6145,template_readmorecolor:#ffffff,template_readmorebackcolor:#6D6145,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#6D6145,beforeloop_readmorehovercolor:#6D6145,beforeloop_readmorehoverbackcolor:#ffffff,bdp_sale_tagbgcolor:#493917,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#999999,bdp_star_rating_bg_color:#555555,bdp_pricetextcolor:#6D6145,bdp_edd_price_color:#6D6145,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#6D6145,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#555555,bdp_addtocart_textcolor:#ffffff,bdp_edd_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#493917,bdp_edd_addtocart_backgroundcolor:#493917,bdp_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#999999,bdp_edd_addtocart_hover_backgroundcolor:#999999',
													'display_value' => '#493917,#6D6145,#555555,#999999',
												),
												'quci_pompadour' => array(
													'preset_name' => esc_html__( 'Pompadour', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#974772,template_bgcolor:#ffffff,template_ftcolor:#974772,template_fthovercolor:#974772,template_titlecolor:#7D194F,template_titlehovercolor:#974772,template_titlebackcolor:#ffffff,template_contentcolor:#999999,template_content_hovercolor:#974772,template_readmorecolor:#ffffff,template_readmorebackcolor:#974772,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#974772,beforeloop_readmorehovercolor:#974772,beforeloop_readmorehoverbackcolor:#ffffff,bdp_sale_tagbgcolor:#7D194F,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#999999,bdp_star_rating_bg_color:#555555,bdp_pricetextcolor:#974772,bdp_edd_price_color:#974772,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#974772,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#555555,bdp_addtocart_textcolor:#ffffff,bdp_edd_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#7D194F,bdp_edd_addtocart_backgroundcolor:#7D194F,bdp_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#999999,bdp_edd_addtocart_hover_backgroundcolor:#999999',
													'display_value' => '#7D194F,#974772,#555555,#999999',
												),
												'quci_bronzetone' => array(
													'preset_name' => esc_html__( 'Bronzetone', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#67704E,template_bgcolor:#E5E7E1,template_ftcolor:#67704E,template_fthovercolor:#67704E,template_titlecolor:#414C22,template_titlehovercolor:#6D6145,template_titlebackcolor:#E5E7E1,template_contentcolor:#999999,template_content_hovercolor:#67704E,template_readmorecolor:#ffffff,template_readmorebackcolor:#67704E,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#67704E,beforeloop_readmorehovercolor:#67704E,beforeloop_readmorehoverbackcolor:#ffffff,bdp_sale_tagbgcolor:#414C22,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#999999,bdp_star_rating_bg_color:#555555,bdp_pricetextcolor:#67704E,bdp_edd_price_color:#67704E,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#67704E,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#555555,bdp_addtocart_textcolor:#ffffff,bdp_edd_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#414C22,bdp_edd_addtocart_backgroundcolor:#414C22,bdp_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#999999,bdp_edd_addtocart_hover_backgroundcolor:#999999',
													'display_value' => '#414C22,#67704E,#555555,#999999',
												),
												'quci_peru_tan' => array(
													'preset_name' => esc_html__( 'Peru Tan', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#916748,template_bgcolor:#EDE5E1,template_ftcolor:#916748,template_fthovercolor:#916748,template_titlecolor:#75411A,template_titlehovercolor:#916748,template_titlebackcolor:#EDE5E1,template_contentcolor:#999999,template_content_hovercolor:#916748,template_readmorecolor:#ffffff,template_readmorebackcolor:#916748,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#916748,beforeloop_readmorehovercolor:#916748,beforeloop_readmorehoverbackcolor:#ffffff,bdp_sale_tagbgcolor:#75411A,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#999999,bdp_star_rating_bg_color:#555555,bdp_pricetextcolor:#916748,bdp_edd_price_color:#916748,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#916748,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#555555,bdp_addtocart_textcolor:#ffffff,bdp_edd_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#75411A,bdp_edd_addtocart_backgroundcolor:#75411A,bdp_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#999999,bdp_edd_addtocart_hover_backgroundcolor:#999999',
													'display_value' => '#75411A,#916748,#555555,#999999',
												),
												'quci_buttercup' => array(
													'preset_name' => esc_html__( 'Buttercup', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#E5A452,template_bgcolor:#FAF0E2,template_ftcolor:#E5A452,template_fthovercolor:#E5A452,template_titlecolor:#DF8D27,template_titlehovercolor:#E5A452,template_titlebackcolor:#FAF0E2,template_contentcolor:#999999,template_content_hovercolor:#E5A452,template_readmorecolor:#ffffff,template_readmorebackcolor:#E5A452,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#E5A452,beforeloop_readmorehovercolor:#E5A452,beforeloop_readmorehoverbackcolor:#ffffff,bdp_sale_tagbgcolor:#DF8D27,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#999999,bdp_star_rating_bg_color:#555555,bdp_pricetextcolor:#E5A452,bdp_edd_price_color:#E5A452,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#E5A452,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#555555,bdp_addtocart_textcolor:#ffffff,bdp_edd_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#DF8D27,bdp_edd_addtocart_backgroundcolor:#DF8D27,bdp_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#999999,bdp_edd_addtocart_hover_backgroundcolor:#999999',
													'display_value' => '#DF8D27,#E5A452,#555555,#999999',
												),
											),
											'pedal'        => array(
												'pedal_default' => array(
													'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#333333,template_fthovercolor:#555555,template_titlecolor:#222222,template_titlehovercolor:#333333,template_titlebackcolor:#ffffff,template_contentcolor:#333333,template_readmorecolor:#ffffff,template_readmorebackcolor:#33cb7d,template_readmore_hover_backcolor:#33cb7d,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#62bf7c,beforeloop_readmorehovercolor:#ffffff,beforeloop_readmorehoverbackcolor:#686868,bdp_sale_tagbgcolor:#62bf7c,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#252525,bdp_star_rating_bg_color:#353535,bdp_pricetextcolor:#353535,bdp_edd_price_color:#353535,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#353535,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#62bf7c,bdp_addtocart_textcolor:#ffffff,bdp_edd_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#62bf7c,bdp_edd_addtocart_backgroundcolor:#62bf7c,bdp_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#353535,bdp_edd_addtocart_hover_backgroundcolor:#353535',
													'display_value' => '#ffffff,#33cb7d,#222222,#333333',
												),
												'pedal_lochmara' => array(
													'preset_name' => esc_html__( 'Lochmara', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#F7FAFC,template_ftcolor:#227CAD,template_fthovercolor:#0E3246,template_titlecolor:#051117,template_titlehovercolor:#227CAD,template_titlebackcolor:#F7FAFC,template_contentcolor:#09202D,template_readmorecolor:#F7FAFC,template_readmorebackcolor:#227CAD,template_readmore_hover_backcolor:#0E3246,beforeloop_readmorecolor:#F7FAFC,beforeloop_readmorebackcolor:#227CAD,beforeloop_readmorehovercolor:#F7FAFC,beforeloop_readmorehoverbackcolor:#0E3246,bdp_sale_tagbgcolor:#227CAD,bdp_sale_tagtextcolor:#F7FAFC,bdp_star_rating_color:#09202D,bdp_star_rating_bg_color:#051117,bdp_pricetextcolor:#051117,bdp_edd_price_color:#051117,bdp_wishlist_textcolor:#F7FAFC,bdp_wishlist_backgroundcolor:#051117,bdp_wishlist_text_hover_color:#F7FAFC,bdp_wishlist_hover_backgroundcolor:#227CAD,bdp_addtocart_textcolor:#F7FAFC,bdp_edd_addtocart_textcolor:#F7FAFC,bdp_addtocart_backgroundcolor:#227CAD,bdp_edd_addtocart_backgroundcolor:#227CAD,bdp_addtocart_text_hover_color:#F7FAFC,bdp_edd_addtocart_text_hover_color:#F7FAFC,bdp_addtocart_hover_backgroundcolor:#051117,bdp_edd_addtocart_hover_backgroundcolor:#051117',
													'display_value' => '#F7FAFC,#227CAD,#051117,#09202D',
												),
												'pedal_burgundy' => array(
													'preset_name' => esc_html__( 'Burgundy', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#FAF6F7,template_ftcolor:#6C0124,template_fthovercolor:#2C010E,template_titlecolor:#0E0105,template_titlehovercolor:#6C0124,template_titlebackcolor:#FAF6F7,template_contentcolor:#1C0109,template_readmorecolor:#FAF6F7,template_readmorebackcolor:#6C0124,template_readmore_hover_backcolor:#2C010E,beforeloop_readmorecolor:#FAF6F7,beforeloop_readmorebackcolor:#6C0124,beforeloop_readmorehovercolor:#FAF6F7,beforeloop_readmorehoverbackcolor:#2C010E,bdp_sale_tagbgcolor:#6C0124,bdp_sale_tagtextcolor:#FAF6F7,bdp_star_rating_color:#1C0109,bdp_star_rating_bg_color:#0E0105,bdp_pricetextcolor:#0E0105,bdp_edd_price_color:#0E0105,bdp_wishlist_textcolor:#FAF6F7,bdp_wishlist_backgroundcolor:#0E0105,bdp_wishlist_text_hover_color:#FAF6F7,bdp_wishlist_hover_backgroundcolor:#6C0124,bdp_addtocart_textcolor:#FAF6F7,bdp_edd_addtocart_textcolor:#FAF6F7,bdp_addtocart_backgroundcolor:#6C0124,bdp_edd_addtocart_backgroundcolor:#6C0124,bdp_addtocart_text_hover_color:#FAF6F7,bdp_edd_addtocart_text_hover_color:#FAF6F7,bdp_addtocart_hover_backgroundcolor:#0E0105,bdp_edd_addtocart_hover_backgroundcolor:#0E0105',
													'display_value' => '#FAF6F7,#6C0124,#0E0105,#1C0109',
												),
												'pedal_hillary' => array(
													'preset_name' => esc_html__( 'Hillary', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#FCFBFA,template_ftcolor:#A49E77,template_fthovercolor:#434131,template_titlecolor:#161610,template_titlehovercolor:#A49E77,template_titlebackcolor:#FCFBFA,template_contentcolor:#2B2A1F,template_readmorecolor:#FCFBFA,template_readmorebackcolor:#A49E77,template_readmore_hover_backcolor:#434131,beforeloop_readmorecolor:#FCFBFA,beforeloop_readmorebackcolor:#A49E77,beforeloop_readmorehovercolor:#FCFBFA,beforeloop_readmorehoverbackcolor:#434131,bdp_sale_tagbgcolor:#A49E77,bdp_sale_tagtextcolor:#FCFBFA,bdp_star_rating_color:#2B2A1F,bdp_star_rating_bg_color:#161610,bdp_pricetextcolor:#161610,bdp_edd_price_color:#161610,bdp_wishlist_textcolor:#FCFBFA,bdp_wishlist_backgroundcolor:#161610,bdp_wishlist_text_hover_color:#FCFBFA,bdp_wishlist_hover_backgroundcolor:#A49E77,bdp_addtocart_textcolor:#FCFBFA,bdp_edd_addtocart_textcolor:#FCFBFA,bdp_addtocart_backgroundcolor:#A49E77,bdp_edd_addtocart_backgroundcolor:#A49E77,bdp_addtocart_text_hover_color:#FCFBFA,bdp_edd_addtocart_text_hover_color:#FCFBFA,bdp_addtocart_hover_backgroundcolor:#161610,bdp_edd_addtocart_hover_backgroundcolor:#161610',
													'display_value' => '#FCFBFA,#A49E77,#161610,#2B2A1F',
												),
												'pedal_amaranth' => array(
													'preset_name' => esc_html__( 'Amaranth', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#FDF9FA,template_ftcolor:#DE364A,template_fthovercolor:#491218,template_titlecolor:#1E070A,template_titlehovercolor:#DE364A,template_titlebackcolor:#FDF9FA,template_contentcolor:#2E0B0F,template_readmorecolor:#FDF9FA,template_readmorebackcolor:#DE364A,template_readmore_hover_backcolor:#491218,beforeloop_readmorecolor:#FDF9FA,beforeloop_readmorebackcolor:#DE364A,beforeloop_readmorehovercolor:#FDF9FA,beforeloop_readmorehoverbackcolor:#491218,bdp_sale_tagbgcolor:#DE364A,bdp_sale_tagtextcolor:#FDF9FA,bdp_star_rating_color:#2E0B0F,bdp_star_rating_bg_color:#1E070A,bdp_pricetextcolor:#1E070A,bdp_edd_price_color:#1E070A,bdp_wishlist_textcolor:#FDF9FA,bdp_wishlist_backgroundcolor:#1E070A,bdp_wishlist_text_hover_color:#FDF9FA,bdp_wishlist_hover_backgroundcolor:#DE364A,bdp_addtocart_textcolor:#FDF9FA,bdp_edd_addtocart_textcolor:#FDF9FA,bdp_addtocart_backgroundcolor:#DE364A,bdp_edd_addtocart_backgroundcolor:#DE364A,bdp_addtocart_text_hover_color:#FDF9FA,bdp_edd_addtocart_text_hover_color:#FDF9FA,bdp_addtocart_hover_backgroundcolor:#1E070A,bdp_edd_addtocart_hover_backgroundcolor:#1E070A',
													'display_value' => '#FDF9FA,#DE364A,#1E070A,#2E0B0F',
												),
												'pedal_manatee' => array(
													'preset_name' => esc_html__( 'Manatee', 'blog-designer-pro' ),
													'preset_value' => 'template_bgcolor:#FCFCFD,template_ftcolor:#9699A7,template_fthovercolor:#3E3E45,template_titlecolor:#151516,template_titlehovercolor:#9699A7,template_titlebackcolor:#FCFCFD,template_contentcolor:#28282C,template_readmorecolor:#FCFCFD,template_readmorebackcolor:#9699A7,template_readmore_hover_backcolor:#3E3E45,beforeloop_readmorecolor:#FCFCFD,beforeloop_readmorebackcolor:#9699A7,beforeloop_readmorehovercolor:#FCFCFD,beforeloop_readmorehoverbackcolor:#3E3E45,bdp_sale_tagbgcolor:#DE364A,bdp_sale_tagtextcolor:#FCFCFD,bdp_star_rating_color:#9699A7,bdp_star_rating_bg_color:#151516,bdp_pricetextcolor:#151516,bdp_edd_price_color:#151516,bdp_wishlist_textcolor:#FCFCFD,bdp_wishlist_backgroundcolor:#151516,bdp_wishlist_text_hover_color:#FCFCFD,bdp_wishlist_hover_backgroundcolor:#9699A7,bdp_addtocart_textcolor:#FCFCFD,bdp_edd_addtocart_textcolor:#FCFCFD,bdp_addtocart_backgroundcolor:#9699A7,bdp_edd_addtocart_backgroundcolor:#9699A7,bdp_addtocart_text_hover_color:#FCFCFD,bdp_edd_addtocart_text_hover_color:#FCFCFD,bdp_addtocart_hover_backgroundcolor:#151516,bdp_edd_addtocart_hover_backgroundcolor:#151516',
													'display_value' => '#FCFCFD,#9699A7,#151516,#28282C',
												),
											),
											'threed_carousel' => array(
												'threed_carousel_default' => array(
													'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#ffffff,template_bgcolor:#ffffff,template_bghovercolor:#DFEDF1,template_ftcolor:#15506F,template_fthovercolor:#555555,template_titlecolor:#15506F,template_titlehovercolor:#DFEDF1,template_contentcolor:#999999,template_readmorecolor:#ffffff,template_readmorebackcolor:#15506F,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#15506F,beforeloop_readmorehovercolor:#15506F,beforeloop_readmorehoverbackcolor:#ffffff,bdp_sale_tagbgcolor:#15506F,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#999999,bdp_star_rating_bg_color:#15506F,bdp_pricetextcolor:#999999,bdp_edd_price_color:#999999,bdp_wishlist_textcolor:#15506F,bdp_wishlist_backgroundcolor:#555555,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#15506F,bdp_addtocart_textcolor:#ffffff,bdp_edd_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#15506F,bdp_edd_addtocart_backgroundcolor:#15506F,bdp_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#555555,bdp_edd_addtocart_hover_backgroundcolor:#555555',
													'display_value' => '#15506F,#DFEDF1,#555555,#999999',
												),
												'threed_carousel_pompadour' => array(
													'preset_name' => esc_html__( 'Pompadour', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#ffffff,template_bgcolor:#ffffff,template_bghovercolor:#EDE1E7,template_ftcolor:#555555,template_fthovercolor:#974772,template_titlecolor:#7D194F,template_titlehovercolor:#974772,template_contentcolor:#999999,template_readmorecolor:#ffffff,template_readmorebackcolor:#974772,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#974772,beforeloop_readmorehovercolor:#974772,beforeloop_readmorehoverbackcolor:#ffffff,bdp_sale_tagbgcolor:#974772,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#999999,bdp_star_rating_bg_color:#974772,bdp_pricetextcolor:#999999,bdp_edd_price_color:#999999,bdp_wishlist_textcolor:#974772,bdp_wishlist_backgroundcolor:#555555,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#974772,bdp_addtocart_textcolor:#ffffff,bdp_edd_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#974772,bdp_edd_addtocart_backgroundcolor:#974772,bdp_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#555555,bdp_edd_addtocart_hover_backgroundcolor:#555555',
													'display_value' => '#7D194F,#974772,#555555,#999999',
												),
												'threed_carousel_mariner' => array(
													'preset_name' => esc_html__( 'Mariner', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#ffffff,grid_hoverback_color:#465BAC,template_ftcolor:#465BAC,template_fthovercolor:#ffffff,template_titlecolor:#465BAC,template_titlehovercolor:#ffffff,template_readmorebackcolor:#465BAC,beforeloop_readmorecolor:#465BAC,beforeloop_readmorebackcolor:#465BAC,beforeloop_readmorehovercolor:#ffffff,beforeloop_readmorehoverbackcolor:#465BAC,bdp_sale_tagbgcolor:#465BAC,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#465BAC,bdp_star_rating_bg_color:#465BAC,bdp_pricetextcolor:#465BAC,bdp_edd_price_color:#465BAC,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#465BAC,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#465BAC,bdp_addtocart_textcolor:#ffffff,bdp_edd_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#465BAC,bdp_edd_addtocart_backgroundcolor:#465BAC,bdp_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#465BAC,bdp_edd_addtocart_hover_backgroundcolor:#465BAC',
													'display_value' => '#465BAC,#e0e0e0,#ffffff,#e0e0e0',
												),
												'threed_carousel_finch'   => array(
													'preset_name' => esc_html__( 'Finch', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#ffffff,template_bgcolor:#ffffff,template_bghovercolor:#EDEDE9,template_ftcolor:#555555,template_fthovercolor:#93A564,template_titlecolor:#788F3D,template_titlehovercolor:#93A564,template_contentcolor:#999999,template_readmorecolor:#ffffff,template_readmorebackcolor:#93A564,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#93A564,beforeloop_readmorehovercolor:#93A564,beforeloop_readmorehoverbackcolor:#ffffff,bdp_sale_tagbgcolor:#93A564,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#999999,bdp_star_rating_bg_color:#93A564,bdp_pricetextcolor:#999999,bdp_edd_price_color:#999999,bdp_wishlist_textcolor:#93A564,bdp_wishlist_backgroundcolor:#EDEDE9,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#93A564,bdp_addtocart_textcolor:#ffffff,bdp_edd_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#93A564,bdp_edd_addtocart_backgroundcolor:#93A564,bdp_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#555555,bdp_edd_addtocart_hover_backgroundcolor:#555555',
													'display_value' => '#788F3D,#93A564,#555555,#999999',
												),
											),
											'flip_book_3d' => array(
												'flip_book_3d_default' => array(
													'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
													'preset_value' => 'template_ftcolor:#A49538,template_fthovercolor:#2b2b2b,template_titlecolor:#000000,template_titlehovercolor:#A49538,template_contentcolor:#7b6b79,template_readmorecolor:#dddddd,template_readmorebackcolor:#A49538,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#A49538,beforeloop_readmorehovercolor:#A49538,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#A49538,#ffffff,#000000,#7b6b79',
												),
												'flip_book_3d_salt-box' => array(
													'preset_name' => esc_html__( 'Salt Box', 'blog-designer-pro' ),
													'preset_value' => 'template_ftcolor:#60505e,template_fthovercolor:#333333,template_titlecolor:#60505e,template_titlehovercolor:#2b2b2b,template_contentcolor:#7b6b79,template_readmorecolor:#dddddd,template_readmorebackcolor:#60505e,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#60505e,beforeloop_readmorehovercolor:#60505e,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#60505e,#ffffff,#2b2b2b,#7b6b79',
												),
												'flip_book_3d_pacific_blue' => array(
													'preset_name' => esc_html__( 'Pacific Blue', 'blog-designer-pro' ),
													'preset_value' => 'template_ftcolor:#0099CB,template_fthovercolor:#2b2b2b,template_titlecolor:#0099CB,template_titlehovercolor:#2b2b2b,template_contentcolor:#7b6b79,template_readmorecolor:#dddddd,template_readmorebackcolor:#0099CB,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#0099CB,beforeloop_readmorehovercolor:#0099CB,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#0099CB,#ffffff,#2b2b2b,#7b6b79',
												),
												'flip_book_3d_pacific_dark_orchid' => array(
													'preset_name' => esc_html__( 'Dark Orchid', 'blog-designer-pro' ),
													'preset_value' => 'template_ftcolor:#ED0086,template_fthovercolor:#2b2b2b,template_titlecolor:#ED0086,template_titlehovercolor:#2b2b2b,template_contentcolor:#7b6b79,template_readmorecolor:#dddddd,template_readmorebackcolor:#ED0086,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#ED0086,beforeloop_readmorehovercolor:#ED0086,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#ED0086,#ffffff,#2b2b2b,#7b6b79',
												),
												'flip_book_3d_pacific_dark_orange' => array(
													'preset_name' => esc_html__( 'Dark Orange', 'blog-designer-pro' ),
													'preset_value' => 'template_ftcolor:#FF8A00,template_fthovercolor:#2b2b2b,template_titlecolor:#FF8A00,template_titlehovercolor:#2b2b2b,template_contentcolor:#7b6b79,template_readmorecolor:#dddddd,template_readmorebackcolor:#FF8A00,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#FF8A00,beforeloop_readmorehovercolor:#FF8A00,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#FF8A00,#ffffff,#2b2b2b,#7b6b79',
												),
												'flip_book_3d_pacific_venetian_red' => array(
													'preset_name' => esc_html__( 'Venetian Red', 'blog-designer-pro' ),
													'preset_value' => 'template_ftcolor:#CC0001,template_fthovercolor:#2b2b2b,template_titlecolor:#CC0001,template_titlehovercolor:#2b2b2b,template_contentcolor:#7b6b79,template_readmorecolor:#dddddd,template_readmorebackcolor:#CC0001,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#CC0001,beforeloop_readmorehovercolor:#CC0001,beforeloop_readmorehoverbackcolor:#ffffff',
													'display_value' => '#CC0001,#ffffff,#2b2b2b,#7b6b79',
												),
											),
											'banner'       => array(
												'banner_default' => array(
													'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#102950,template_titlebackcolor:#102950,template_lazy_load_color:#FF0000,template_ftcolor:#102950,template_fthovercolor:#555555,template_titlecolor:#FFFFFF,template_titlehovercolor:#102950,template_readmorecolor:#FFFFFF,template_readmorebackcolor:#283f62,template_contentcolor:#FFFFFF,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#102950,beforeloop_readmorehovercolor:#102950,beforeloop_readmorehoverbackcolor:#ffffff,bdp_sale_tagbgcolor:#102950,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#666666,bdp_pricetextcolor:#222222,bdp_edd_price_color:#222222,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#666666,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#555555,bdp_addtocart_textcolor:#ffffff,bdp_edd_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#102950,bdp_edd_addtocart_backgroundcolor:#102950,bdp_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#222222,bdp_edd_addtocart_hover_backgroundcolor:#222222',
													'display_value' => '#102950,#222222,#555555,#666666',
												),
												'banner_blackberry' => array(
													'preset_name' => esc_html__( 'Blackberry', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#6D4657,template_titlebackcolor:#6D4657,template_lazy_load_color:#FF0000,template_ftcolor:#FFFFFF,template_fthovercolor:#FFFFFF,template_titlecolor:#49182D,template_titlehovercolor:#6D4657,template_readmorecolor:#FFFFFF,template_readmorebackcolor:#666666,template_contentcolor:#FFFFFF,beforeloop_readmorecolor:#ffffff,beforeloop_readmorebackcolor:#6D4657,beforeloop_readmorehovercolor:#6D4657,beforeloop_readmorehoverbackcolor:#ffffff,bdp_sale_tagbgcolor:#49182D,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#FFFFFF,bdp_star_rating_bg_color:#666666,bdp_pricetextcolor:#49182D,bdp_edd_price_color:#49182D,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#666666,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#49182D,bdp_addtocart_textcolor:#ffffff,bdp_edd_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#6D4657,bdp_edd_addtocart_backgroundcolor:#6D4657,bdp_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#555555,bdp_edd_addtocart_hover_backgroundcolor:#555555',
													'display_value' => '#49182D,#6D4657,#555555,#666666',
												),
												'banner_prussian' => array(
													'preset_name' => esc_html__( 'Prussian', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#99a8ba,template_titlebackcolor:#99a8ba,template_lazy_load_color:#FF0000,template_ftcolor:#000308,template_fthovercolor:#000b19,template_titlecolor:#e5e9ed,template_titlehovercolor:#99a8ba,template_contentcolor:#000308,template_readmorecolor:#e5e9ed,template_readmorebackcolor:#000308,template_readmore_hover_backcolor:#193b65,beforeloop_readmorecolor:#e5e9ed,beforeloop_readmorebackcolor:#000308,beforeloop_readmorehovercolor:#e5e9ed,beforeloop_readmorehoverbackcolor:#193b65,bdp_sale_tagbgcolor:#171101,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#171101,bdp_star_rating_bg_color:#193b65,bdp_pricetextcolor:#000308,bdp_edd_price_color:#000308,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#000b19,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#193b65,bdp_addtocart_textcolor:#ffffff,bdp_edd_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#171101,bdp_edd_addtocart_backgroundcolor:#171101,bdp_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#000308,bdp_edd_addtocart_hover_backgroundcolor:#000308',
													'display_value' => '#99a8ba,#193b65,#000b19,#000308',
												),
												'banner_tangerine'   => array(
													'preset_name' => esc_html__( 'Tangerine', 'blog-designer-pro' ),
													'preset_value' => 'template_color:#f8df9f,template_titlebackcolor:#f8df9f,template_lazy_load_color:#FF0000,template_ftcolor:#171101,template_fthovercolor:#473505,template_titlecolor:#fdf7e7,template_titlehovercolor:#f8df9f,template_contentcolor:#171101,template_readmorecolor:#fdf7e7,template_readmorebackcolor:#171101,template_readmore_hover_backcolor:#efb828,beforeloop_readmorecolor:#fdf7e7,beforeloop_readmorebackcolor:#171101,beforeloop_readmorehovercolor:#fdf7e7,beforeloop_readmorehoverbackcolor:#efb828,bdp_sale_tagbgcolor:#171101,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#171101,bdp_star_rating_bg_color:#473505,bdp_pricetextcolor:#171101,bdp_edd_price_color:#171101,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#888888,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#473505,bdp_addtocart_textcolor:#ffffff,bdp_edd_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#171101,bdp_edd_addtocart_backgroundcolor:#171101,bdp_addtocart_text_hover_color:#ffffff,bdp_edd_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#473505,bdp_edd_addtocart_hover_backgroundcolor:#473505',
													'display_value' => '#f8df9f,#efb828,#473505,#171101',
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
														<?php
														Bdp_Utility::admin_color_preset( $value['display_value'] );
														?>
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
							<li class="bdp-caution">
								<svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" viewBox="0 0 24 24"><path fill="#e9ab30" d="M13 14h-2V9h2m0 9h-2v-2h2M1 21h22L12 2L1 21Z"/></svg>
								<div class="bdp-setting-description">
									<?php
									esc_html_e( "If you're selecting the same category or tag as previous Archive layouts for the New Archive layout, only the first created Archive layout will be allowed. Please ensure that you choose a different Post Categories or Post tag.", 'blog-designer-pro' );
									?>
								</div>
							</li>
							<div class="bdp_grid_col_3">
							<li>
								<div class="bdp-left">
									<span class="bdp-key-title">
										<?php esc_html_e( 'Archive Layout Name', 'blog-designer-pro' ); ?>
									</span>
								</div>
								<div class="bdp-right">
									<input type="text" name="archive_name" id="archive_name" value="<?php echo esc_attr( stripslashes( $archive_name ) ); ?>" placeholder="<?php esc_attr_e( 'Enter archive layout name', 'blog-designer-pro' ); ?>">
								</div>
							</li>

							<li class="display-layout_type">
								<div class="bdp-left">
									<span class="bdp-key-title">
										<?php esc_html_e( 'Display Timeline Layout', 'blog-designer-pro' ); ?>
									</span>
								</div>
								<div class="bdp-right">
									<?php
									$bdp_timeline_layout = '';
									if ( isset( $bdp_settings['bdp_timeline_layout'] ) ) {
										$bdp_timeline_layout = $bdp_settings['bdp_timeline_layout'];
									}
									?>
									<select id="bdp_timeline_layout" name="bdp_timeline_layout">
										<option value="" <?php echo esc_attr( selected( '', $bdp_timeline_layout ) ); ?>><?php esc_html_e( 'Default Layout', 'blog-designer-pro' ); ?></option>
										<option value="left_side" <?php echo esc_attr( selected( 'left_side', $bdp_timeline_layout ) ); ?>><?php esc_html_e( 'Left Side', 'blog-designer-pro' ); ?></option>
										<option value="right_side" <?php echo esc_attr( selected( 'right_side', $bdp_timeline_layout ) ); ?>><?php esc_html_e( 'Right Side', 'blog-designer-pro' ); ?></option>
										<option value="center" <?php echo esc_attr( selected( 'center', $bdp_timeline_layout ) ); ?>><?php esc_html_e( 'Center', 'blog-designer-pro' ); ?></option>
									</select>
								</div>
							</li>

							<li>
								<div class="bdp-left">
									<span class="bdp-key-title">
										<?php esc_html_e( 'Select Archive Type', 'blog-designer-pro' ); ?>
									</span>
								</div>
								<div class="bdp-right">
									<select name="custom_archive_type" id="custom_archive_type" >
										<?php
										$db_date   = '';
										$db_author = '';
										$db_search = '';
										if ( 'date_template' !== $custom_archive_type ) {
											$db_date = Bdp_Template::get_date_template_settings();
										}
										if ( 'search_template' !== $custom_archive_type ) {
											$db_search = Bdp_Template::get_search_template_settings();
										}
										if ( ! isset( $_GET['id'] ) && Bdp_Template::get_date_template_settings() ) {
											$custom_archive_type = 'author_template';
										}
										?>
										<option value="date_template" 
										<?php
										if ( 'date_template' === $custom_archive_type ) {
											echo 'selected=selected';
										} if ( $db_date ) {
											echo ' disabled=disabled';
										}
										?>
										><?php esc_html_e( 'Date', 'blog-designer-pro' ); ?></option>
										<option value="author_template" 
										<?php
										if ( 'author_template' === $custom_archive_type ) {
											echo 'selected=selected';
										}
										?>
										><?php esc_html_e( 'Author', 'blog-designer-pro' ); ?></option>
										<option value="search_template" 
										<?php
										if ( 'search_template' === $custom_archive_type ) {
											echo 'selected=selected';
										} if ( $db_search ) {
											echo ' disabled=disabled';
										}
										?>
										><?php esc_html_e( 'Search', 'blog-designer-pro' ); ?></option>
										<option value="category_template" 
										<?php
										if ( 'category_template' === $custom_archive_type ) {
											echo 'selected=selected';
										}
										?>
										><?php esc_html_e( 'Category', 'blog-designer-pro' ); ?></option>
										<option value="tag_template" 
										<?php
										if ( 'tag_template' === $custom_archive_type ) {
											echo 'selected=selected';
										}
										?>
										><?php esc_html_e( 'Tag', 'blog-designer-pro' ); ?></option>
									</select>

									<div class="bdp-setting-description bdp-note">
										<b class="note"><?php esc_html_e( 'Note', 'blog-designer-pro' ); ?>: </b>
										<?php esc_html_e( 'Date and Search are allow to select only once', 'blog-designer-pro' ); ?>
									</div>
								</div>
							</li>
							<li class="post-category">
								<div class="bdp-left"><span class="bdp-key-title"><?php esc_html_e( 'Select Post Categories', 'blog-designer-pro' ); ?></span></div>
								<div class="bdp-right">
									<?php
										$categories       = get_categories(
											array(
												'child_of' => '',
												'hide_empty' => 1,
											)
										);
										$db_categories    = $wpdb->get_results( 'SELECT sub_categories FROM ' . $wpdb->prefix . 'bdp_archives WHERE archive_template = "category_template"' );
										$db_category_list = array();
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
										<select data-placeholder="<?php esc_attr_e( 'Choose Post Categories', 'blog-designer-pro' ); ?>" class="chosen-select" multiple style="width:220px;" name="template_category[]" id="template_category">
											<?php foreach ( $categories as $category_obj ) : ?>
												<option value="<?php echo esc_attr( $category_obj->term_id ); ?>" 
													<?php
													if ( in_array( $category_obj->term_id, $template_category ) ) {
														echo 'selected="selected"';
													} if ( in_array( $category_obj->term_id, $final_cat ) ) {
														echo 'disabled="disabled"';
													}
													?>
												><?php echo esc_html( $category_obj->name ); ?></option>
											<?php endforeach; ?>
										</select>
										<div class="bdp-setting-description bdp-note">
											<b class="note"><?php esc_html_e( 'Note', 'blog-designer-pro' ); ?>: </b>
											<?php esc_html_e( 'Select atleast one Category to display on Category archive page!', 'blog-designer-pro' ); ?>
										</div>
								</div>
							</li>

							<li class="post-tag">
								<div class="bdp-left">
									<span class="bdp-key-title">
										<?php esc_html_e( 'Select Post Tags', 'blog-designer-pro' ); ?>
									</span>
								</div>
								<div class="bdp-right">
									<?php
									$tags        = get_terms( array( 'post_tag' ) );
									$db_tags     = $wpdb->get_results( 'SELECT sub_categories FROM ' . $wpdb->prefix . 'bdp_archives WHERE archive_template = "tag_template"' );
									$db_tag_list = array();
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
									<select data-placeholder="<?php esc_attr_e( 'Choose Post Tags', 'blog-designer-pro' ); ?>" class="chosen-select" multiple style="width:220px;" name="template_tags[]" id="template_tags">
										<?php foreach ( $tags as $tag ) : ?>
											<option value="<?php echo esc_attr( $tag->term_id ); ?>" 
												<?php
												if ( in_array( $tag->term_id, $template_tags ) ) {
													echo 'selected="selected"';
												} if ( in_array( $tag->term_id, $final_tag ) ) {
													echo 'disabled="disabled"';
												}
												?>
											><?php echo esc_html( $tag->name ); ?></option>
										<?php endforeach; ?>
									</select>
									<div class="bdp-setting-description bdp-note">
										<b class="note"><?php esc_html_e( 'Note', 'blog-designer-pro' ); ?>: </b>
										<?php esc_html_e( 'Select atleast one Tag to display on Tag archive page.', 'blog-designer-pro' ); ?>
									</div>
								</div>
							</li>

							<li class="post-author">
								<div class="bdp-left">
									<span class="bdp-key-title">
										<?php esc_html_e( 'Select Post Authors', 'blog-designer-pro' ); ?>
									</span>
								</div>
								<div class="bdp-right">
									<?php
									$blogusers      = get_users( 'orderby=nicename&order=asc' );
									$db_authors     = $wpdb->get_results( 'SELECT sub_categories FROM ' . $wpdb->prefix . 'bdp_archives WHERE archive_template = "author_template"' );
									$db_author_list = array();
									if ( $db_authors ) {
										foreach ( $db_authors as $db_author ) {
											$sub_list = $db_author->sub_categories;
											if ( $sub_list ) {
												$db_author_ids = explode( ',', $sub_list );
												foreach ( $db_author_ids as $db_author_id ) {
													$db_author_list[] = $db_author_id;
												}
											}
										}
									}
									$final_author = array();
									if ( is_array( $template_author ) && ! empty( $template_author ) ) {
										$final_author = array_diff( $db_author_list, $template_author );
									}

									$template_authors = '';
									if ( ! empty( $template_author ) ) {
										$template_authors = implode( ',', $template_author );
									}
									$final_authors = '';
									if ( ! empty( $final_author ) ) {
										$final_authors = implode( ',', $final_author );
									}
									?>
									<input type="hidden" value="<?php echo esc_attr( $final_authors ); ?>" name="template_final_author_hidden" id="template_final_author_hidden">
									<input type="hidden" value="<?php echo esc_attr( $template_authors ); ?>" name="template_author_hidden" id="template_author_hidden">
									<?php
									$bdp_multi_author_selection = get_option( 'bdp_multi_author_selection', 1 );
									if ( 1 == $bdp_multi_author_selection ) {
										?>
										<select data-placeholder="<?php esc_attr_e( 'Choose Post Author', 'blog-designer-pro' ); ?>" class="chosen-select" multiple style="width:220px;" name="template_author[]" id="template_author">
											<?php foreach ( $blogusers as $user ) : ?>
												<option value="<?php echo esc_attr( $user->ID ); ?>" selected="selected"><?php echo esc_html( $user->display_name ); ?></option>
											<?php endforeach; ?>
										</select>
										<?php
									} else {
										?>
										<select data-placeholder="<?php esc_attr_e( 'Choose Post Author', 'blog-designer-pro' ); ?>" class="chosen-select" multiple style="width:220px;" name="template_author[]" id="template_author">
											<?php
											foreach ( $blogusers as $user ) {
												if ( in_array( $user->ID, $template_author ) ) {
													?>
												<option value="<?php echo esc_attr( $user->ID ); ?>" 
													<?php
													if ( @in_array( $user->ID, $template_author ) ) {
														echo 'selected="selected"';
													}
													if ( in_array( $user->ID, $final_author ) ) {
														echo 'disabled="disabled"';
													}
													?>
												><?php echo esc_html( $user->display_name ); ?></option>
													<?php
												}
											}
											?>
										</select>
										<?php
									}
									?>
								</div>
							</li>

							<li class="archive_blog_page_show">
								<div class="bdp-left">
									<span class="bdp-key-title">
										<?php esc_html_e( 'Number of Posts to Display', 'blog-designer-pro' ); ?>
									</span>
								</div>
								<div class="bdp-right">
									<input name="posts_per_page" type="number" step="1" min="1" id="posts_per_page"
										value="<?php echo esc_attr( $posts_per_page ); ?>" onkeypress="return isNumberKey(event)" />
								</div>
							</li>
							<li class="bdp_display_tabbed_options">
								<div class="bdp-left">
									<span class="bdp-key-title">
										<?php esc_html_e( 'Display Tabbed Layout', 'blog-designer-pro' ); ?>
									</span>
								</div>
								<div class="bdp-right">
									<?php
									$bdp_tabbed_layout = 'left_side';
									if ( isset( $bdp_settings['bdp_tabbed_layout'] ) ) {
										$bdp_tabbed_layout = $bdp_settings['bdp_tabbed_layout'];
									}
									?>
									<select id="bdp_tabbed_layout" name="bdp_tabbed_layout">
										<option value="left_side" <?php echo selected( 'left_side', $bdp_tabbed_layout ); ?>><?php esc_html_e( 'Left Post And Right Posts List', 'blog-designer-pro' ); ?></option>
										<option value="right_side" <?php echo selected( 'right_side', $bdp_tabbed_layout ); ?>><?php esc_html_e( 'Right Post And Left Posts List', 'blog-designer-pro' ); ?></option>
									</select>
								</div>
							</li>
							<li class="bdp_display_tabbed_filter">
								<div class="bdp-left">
									<span class="bdp-key-title">
										<?php esc_html_e( 'Display Tabbed layout Filter by', 'blog-designer-pro' ); ?>
									</span>
								</div>
								<div class="bdp-right">
									<select id="archive_display_tabbed_filter_by" name="archive_display_tabbed_filter_by">
										<?php
										$display_tabbed_filter_by = 'category';
										if ( isset( $bdp_settings['archive_display_tabbed_filter_by'] ) ) {
											$display_tabbed_filter_by = $bdp_settings['archive_display_tabbed_filter_by'];
										}
										$custom_posttype = 'post';
										$taxonomy_names  = get_object_taxonomies( $custom_posttype, 'objects' );
										$taxonomy_names  = apply_filters( 'bdp_hide_taxonomies', $taxonomy_names );
										if ( ! empty( $taxonomy_names ) ) {
											foreach ( $taxonomy_names as $taxonomy_name ) {
												$terms = get_terms( $taxonomy_name->name, array( 'hide_empty' => false ) );
												if ( ! empty( $terms ) ) {
													?>
													<option value="<?php echo esc_attr( $taxonomy_name->name ); ?>" <?php echo selected( $taxonomy_name->name, $display_tabbed_filter_by ); ?>><?php echo esc_html( $taxonomy_name->label ); ?></option>
													<?php
												}
											}
										}
										?>
									</select>
								</div>
							</li> 
							<?php
							$custom_posttype = 'post';
							$terms           = get_terms( $display_tabbed_filter_by, array( 'hide_empty' => false ) );
							if ( ! empty( $terms ) ) {
								foreach ( $taxonomy_names as $taxonomy_name ) {
									if ( $taxonomy_name->name == $display_tabbed_filter_by ) {
										?>
										<li class="bdp_display_tabbed_filter_html">
											<div class="bdp-left">
												<span class="bdp-key-title">
													<?php echo esc_html__( 'Select tabbed layout', 'blog-designer-pro' ) . ' ' . esc_html( $taxonomy_name->label ); ?>
												</span>
											</div>
											<div class="bdp-right">
												<select data-placeholder="Choose <?php echo esc_attr( $taxonomy_name->label ); ?>" multiple style="width:220px;" class="chosen-select custom_post_term" name="<?php echo esc_attr( $taxonomy_name->name ); ?>_tabbed_terms[]" id="terms_tabbed_<?php echo esc_attr( $taxonomy_name->name ); ?>" data-name="<?php echo esc_attr( $taxonomy_name->name ); ?>">
												<?php
												if ( isset( $bdp_settings[ $taxonomy_name->name . '_tabbed_terms' ] ) ) {
													$tax_count = count( $bdp_settings[ $taxonomy_name->name . '_tabbed_terms' ] );
												}
												foreach ( $terms as $term ) {
													?>
													<option 
													<?php
													if ( isset( $bdp_settings[ $taxonomy_name->name . '_tabbed_terms' ] ) ) {
														for ( $i = 0; $i < $tax_count; $i++ ) {
															if ( $term->name == $bdp_settings[ $taxonomy_name->name . '_tabbed_terms' ][ $i ] ) {
																echo 'selected=selected';
															}
														}
													}
													?>
													value="<?php echo esc_attr( $term->name ); ?>"><?php echo esc_html( $term->name ); ?></option>
												<?php } ?>
												</select>
											</div>
										</li>
										<?php
									}
								}
							}
							?>
							</div>
							<h3 class="bdp-table-title bdp-display-settings"><?php esc_html_e( 'Display Settings', 'blog-designer-pro' ); ?></h3>
							<li class="bdp-display-settings post_metadatas">
								<div class="bdp-typography-wrapper bdp-button-settings">
									<div class="bdp-typography-cover">
										<div class="bdp-typography-label">
											<span class="bd-key-title">
												<?php esc_html_e( 'Post Category', 'blog-designer-pro' ); ?>
											</span>
										</div>
										<div class="bdp-typography-content">
											<fieldset class="buttonset">
												<input id="display_category_1" name="display_category" type="radio" value="1" <?php echo checked( 1, $display_category ); ?> />
												<label for="display_category_1"><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
												<input id="display_category_0" name="display_category" type="radio" value="0" <?php echo checked( 0, $display_category ); ?> />
												<label for="display_category_0"><?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
											</fieldset>
											<label class="disable_link">
												<input id="disable_link_category" name="disable_link_category" type="checkbox" value="1" 
												<?php
												if ( isset( $bdp_settings['disable_link_category'] ) ) {
													checked( 1, $bdp_settings['disable_link_category'] );
												}
												?>
												/> <?php esc_html_e( 'Disable Link', 'blog-designer-pro' ); ?>
											</label>
										</div>
									</div>
									<div class="bdp-typography-cover">
										<div class="bdp-typography-label">
											<span class="bd-key-title">
												<?php esc_html_e( 'Post Tags', 'blog-designer-pro' ); ?>
											</span>
										</div>
										<div class="bdp-typography-content">
											<fieldset class="buttonset">
												<input id="display_tag_1" name="display_tag" type="radio" value="1" <?php checked( 1, $display_tag ); ?> />
												<label for="display_tag_1"><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
												<input id="display_tag_0" name="display_tag" type="radio" value="0" <?php checked( 0, $display_tag ); ?> />
												<label for="display_tag_0"><?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
											</fieldset>
											<label class="disable_link">
												<input id="disable_link_tag" name="disable_link_tag" type="checkbox" value="1" 
												<?php
												if ( isset( $bdp_settings['disable_link_tag'] ) ) {
													checked( 1, $bdp_settings['disable_link_tag'] );
												}
												?>
												/> <?php esc_html_e( 'Disable Link', 'blog-designer-pro' ); ?>
											</label>
										</div>
									</div>

									<div class="bdp-typography-cover">
										<div class="bdp-typography-label">
											<span class="bd-key-title"><?php esc_html_e( 'Post Author', 'blog-designer-pro' ); ?></span>
										</div>
										<div class="bdp-typography-content">
											<fieldset class="buttonset">
												<input id="display_author_1" name="display_author" type="radio" value="1"  <?php checked( 1, $display_author ); ?>/>
												<label for="display_author_1"><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
												<input id="display_author_0" name="display_author" type="radio" value="0" <?php checked( 0, $display_author ); ?> />
												<label for="display_author_0"><?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
											</fieldset>
											<label class="disable_link">
												<input id="disable_link_author" name="disable_link_author" type="checkbox" value="1" 
												<?php
												if ( isset( $bdp_settings['disable_link_author'] ) ) {
													checked( 1, $bdp_settings['disable_link_author'] );
												}
												?>
												/> <?php esc_html_e( 'Disable Link for Author', 'blog-designer-pro' ); ?>
											</label>
										</div>
									</div>
									<div class="bdp-typography-cover">
										<div class="bdp-typography-label">
											<span class="bd-key-title">
												<?php esc_html_e( 'Post Publish Date', 'blog-designer-pro' ); ?>
											</span>
										</div>
										<div class="bdp-typography-content">
											<fieldset class="bdp-social-options bdp-display_date buttonset buttonset-hide ui-buttonset" data-hide="1">
												<input id="display_date_1" name="display_date" type="radio" value="1" <?php checked( 1, $display_date ); ?> />
												<label for="display_date_1" <?php checked( 1, $display_date ); ?>><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
												<input id="display_date_0" name="display_date" type="radio" value="0" <?php checked( 0, $display_date ); ?> />
												<label for="display_date_0" <?php checked( 0, $display_date ); ?>><?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
											</fieldset>
											<label class="disable_link">
												<input id="disable_link_date" name="disable_link_date" type="checkbox" value="1" 
												<?php
												if ( isset( $bdp_settings['disable_link_date'] ) ) {
													checked( 1, $bdp_settings['disable_link_date'] );
												}
												?>
												/> <?php esc_html_e( 'Disable Link for Publish Date', 'blog-designer-pro' ); ?>
											</label>
										</div>
									</div>

									<div class="bdp-typography-cover display_comment">
										<div class="bdp-typography-label">
											<span class="bd-key-title">
												<?php esc_html_e( 'Post Comment Count', 'blog-designer-pro' ); ?>
											</span>
										</div>
										<div class="bdp-typography-content">
											<fieldset class="bdp-social-options bdp-display_comment_count buttonset buttonset-hide ui-buttonset" data-hide="1">
												<input id="display_comment_count_1" name="display_comment_count" type="radio" value="1" <?php checked( 1, $display_comment_count ); ?> />
												<label for="display_comment_count_1" <?php checked( 1, $display_comment_count ); ?>><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
												<input id="display_comment_count_0" name="display_comment_count" type="radio" value="0" <?php checked( 0, $display_comment_count ); ?> />
												<label for="display_comment_count_0" <?php checked( 0, $display_comment_count ); ?>><?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
											</fieldset>
											<label class="disable_link">
												<input id="disable_link_comment" name="disable_link_comment" type="checkbox" value="1" 
												<?php
												if ( isset( $bdp_settings['disable_link_comment'] ) ) {
													checked( 1, $bdp_settings['disable_link_comment'] );
												}
												?>
												/> <?php esc_html_e( 'Disable Link', 'blog-designer-pro' ); ?>
											</label>
										</div>
									</div>

									<div class="bdp-typography-cover display-postlike">
										<div class="bdp-typography-label">
											<span class="bd-key-title">
												<?php esc_html_e( 'Post Like', 'blog-designer-pro' ); ?>
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

									<div class="bdp-typography-cover display_year">
										<div class="bdp-typography-label">
											<span class="bd-key-title">
												<?php esc_html_e( 'Post Published Year', 'blog-designer-pro' ); ?>
											</span>
										</div>
										<div class="bdp-typography-content">
											<fieldset class="bdp-social-options bdp-display_year buttonset buttonset-hide ui-buttonset" data-hide="1">
												<input id="display_story_year_1" name="display_story_year" type="radio" value="1" <?php checked( 1, $display_story_year ); ?> />
												<label for="display_story_year_1" <?php checked( 1, $display_story_year ); ?>><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
												<input id="display_story_year_0" name="display_story_year" type="radio" value="0" <?php checked( 0, $display_story_year ); ?> />
												<label for="display_story_year_0" <?php checked( 0, $display_story_year ); ?>><?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
											</fieldset>
										</div>
									</div>
								</div>
							</li>

							<li>
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
										<b><?php esc_html_e( 'Example', 'blog-designer-pro' ); ?>:</b>
										<?php echo '.class_name{ color:#ffffff }'; ?>
									</div>
								</div>
							</li>
						</ul>
					</div>
				</div>
				<div id="dbptimeline" class="postbox postbox-with-fw-options" style=<?php echo esc_attr( $dbptimeline_class_show ); ?>>
					<div class="inside">
						<ul class="bdp-settings">
							<div class="bdp_grid_col_3">
								<li>
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Display Timeline Bar', 'blog-designer-pro' ); ?>&nbsp;
										</span>
									</div>
									<div class="bdp-right">
										<?php $display_timeline_bar = isset( $bdp_settings['display_timeline_bar'] ) ? $bdp_settings['display_timeline_bar'] : ''; ?>
										<fieldset class="bdp-social-options bdp-display_timeline_bar buttonset buttonset-hide ui-buttonset" data-hide="1">
											<input id="display_timeline_bar_0" name="display_timeline_bar" class="display_timeline_bar" type="radio" value="0" <?php checked( 0, $display_timeline_bar ); ?> />
											<label for="display_timeline_bar_0" <?php checked( 0, $display_timeline_bar ); ?>><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
											<input id="display_timeline_bar_1" name="display_timeline_bar" class="display_timeline_bar" type="radio" value="1" <?php checked( 1, $display_timeline_bar ); ?> />
											<label for="display_timeline_bar_1" <?php checked( 1, $display_timeline_bar ); ?>><?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
										</fieldset>
									</div>
								</li>

								<li class="timeline_start_from">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Active Post', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right active_post_list">
										<?php
										$timeline_start_from = '';
										if ( isset( $bdp_settings['timeline_start_from'] ) && '' != $bdp_settings['timeline_start_from'] ) {
											$timeline_start_from = $bdp_settings['timeline_start_from'];
										}
										if ( 'category_template' == $custom_archive_type ) {
											$taxonomy    = 'category';
											$template_id = isset( $bdp_settings['template_category'] ) ? $bdp_settings['template_category'] : '';
										} elseif ( 'tag_template' == $custom_archive_type ) {
											$taxonomy    = 'post_tag';
											$template_id = isset( $bdp_settings['template_tags'] ) ? $bdp_settings['template_tags'] : '';
										}
										global $post;
										$args = array(
											'cache_results' => false,
											'no_found_rows' => true,
											'fields'      => 'ids',
											'showposts'   => '-1',
											'post_status' => 'publish',
											'post_type'   => 'post',
										);
										if ( 'category_template' === $custom_archive_type || 'tag_template' === $custom_archive_type ) {
											$args['tax_query'] = array(
												array(
													'taxonomy' => $taxonomy,
													'field'    => 'term_id',
													'terms'    => $template_id,
												),
											);
										}
										$bdp_timeline_the_query = get_posts( $args );
										if ( $bdp_timeline_the_query ) {
											echo '<select name="timeline_start_from" id="timeline_start_from">';
											foreach ( $bdp_timeline_the_query as $post ) {
												$post__id = get_the_ID();
												?>
												<option value="<?php echo esc_attr( get_the_date( 'd/m/Y', $post__id ) ); ?>" <?php echo ( get_the_date( 'd/m/Y', $post__id ) == $timeline_start_from ) ? 'selected="selected"' : ''; ?>><?php echo esc_attr( get_the_title( $post__id ) ); ?></option>
												<?php
											}
											wp_reset_postdata();
											echo '</select>';
										} else {
											echo '<p>' . esc_html__( 'No Post Found', 'blog-designer-pro' ) . '</p>';
										}
										?>
									</div>
								</li>

								<li>
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Posts Effect', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<?php $template_easing = isset( $bdp_settings['template_easing'] ) ? $bdp_settings['template_easing'] : 'easeInQuad'; ?>
										<select name="template_easing" id="template_easing">
											<option value="easeInQuad" <?php echo 'easeInQuad' === $template_easing ? 'selected="selected"' : ''; ?>><?php esc_html_e( 'easeInQuad', 'blog-designer-pro' ); ?></option>
											<option value="easeOutQuad" <?php echo 'easeOutQuad' === $template_easing ? 'selected="selected"' : ''; ?>><?php esc_html_e( 'easeOutQuad', 'blog-designer-pro' ); ?></option>
											<option value="easeInOutQuad" <?php echo 'easeInOutQuad' === $template_easing ? 'selected="selected"' : ''; ?>><?php esc_html_e( 'easeInOutQuad', 'blog-designer-pro' ); ?></option>
											<option value="easeInCubic" <?php echo 'easeInCubic' === $template_easing ? 'selected="selected"' : ''; ?>><?php esc_html_e( 'easeInCubic', 'blog-designer-pro' ); ?></option>
											<option value="easeOutCubic" <?php echo 'easeInCubic' === $template_easing ? 'selected="selected"' : ''; ?>><?php esc_html_e( 'easeInCubic', 'blog-designer-pro' ); ?></option>
											<option value="easeInOutCubic" <?php echo 'easeInOutCubic' === $template_easing ? 'selected="selected"' : ''; ?>><?php esc_html_e( 'easeInOutCubic', 'blog-designer-pro' ); ?></option>
											<option value="easeInQuart" <?php echo 'easeInQuart' === $template_easing ? 'selected="selected"' : ''; ?>><?php esc_html_e( 'easeInQuart', 'blog-designer-pro' ); ?></option>
											<option value="easeOutQuart" <?php echo 'easeOutQuart' === $template_easing ? 'selected="selected"' : ''; ?>><?php esc_html_e( 'easeOutQuart', 'blog-designer-pro' ); ?></option>
											<option value="easeInOutQuart" <?php echo 'easeInOutQuart' === $template_easing ? 'selected="selected"' : ''; ?>><?php esc_html_e( 'easeInOutQuart', 'blog-designer-pro' ); ?></option>
											<option value="easeInQuint" <?php echo 'easeInQuint' === $template_easing ? 'selected="selected"' : ''; ?>><?php esc_html_e( 'easeInQuint', 'blog-designer-pro' ); ?></option>
											<option value="easeOutQuint" <?php echo 'easeOutQuint' === $template_easing ? 'selected="selected"' : ''; ?>><?php esc_html_e( 'easeOutQuint', 'blog-designer-pro' ); ?></option>
											<option value="easeInOutQuint" <?php echo 'easeInOutQuint' === $template_easing ? 'selected="selected"' : ''; ?>><?php esc_html_e( 'easeInOutQuint', 'blog-designer-pro' ); ?></option>
											<option value="easeInSine" <?php echo 'easeInSine' === $template_easing ? 'selected="selected"' : ''; ?>><?php esc_html_e( 'easeInSine', 'blog-designer-pro' ); ?></option>
											<option value="easeOutSine" <?php echo 'easeOutSine' === $template_easing ? 'selected="selected"' : ''; ?>><?php esc_html_e( 'easeOutSine', 'blog-designer-pro' ); ?></option>
											<option value="easeInOutSine" <?php echo 'easeInOutSine' === $template_easing ? 'selected="selected"' : ''; ?>><?php esc_html_e( 'easeInOutSine', 'blog-designer-pro' ); ?></option>
											<option value="easeInExpo" <?php echo 'easeInExpo' === $template_easing ? 'selected="selected"' : ''; ?>><?php esc_html_e( 'easeInExpo', 'blog-designer-pro' ); ?></option>
											<option value="easeOutExpo" <?php echo 'easeOutExpo' === $template_easing ? 'selected="selected"' : ''; ?>><?php esc_html_e( 'easeOutExpo', 'blog-designer-pro' ); ?></option>
											<option value="easeInOutExpo" <?php echo 'easeInOutExpo' === $template_easing ? 'selected="selected"' : ''; ?>><?php esc_html_e( 'easeInOutExpo', 'blog-designer-pro' ); ?></option>
											<option value="easeInCirc" <?php echo 'easeInCirc' === $template_easing ? 'selected="selected"' : ''; ?>><?php esc_html_e( 'easeInCirc', 'blog-designer-pro' ); ?></option>
											<option value="easeOutCirc" <?php echo 'easeOutCirc' === $template_easing ? 'selected="selected"' : ''; ?>><?php esc_html_e( 'easeOutCirc', 'blog-designer-pro' ); ?></option>
											<option value="easeInOutCirc" <?php echo 'easeInOutCirc' === $template_easing ? 'selected="selected"' : ''; ?>><?php esc_html_e( 'easeInOutCirc', 'blog-designer-pro' ); ?></option>
											<option value="easeOutCirc" <?php echo 'easeOutCirc' === $template_easing ? 'selected="selected"' : ''; ?>><?php esc_html_e( 'easeOutCirc', 'blog-designer-pro' ); ?></option>
											<option value="easeInOutCirc" <?php echo 'easeInOutCirc' === $template_easing ? 'selected="selected"' : ''; ?>><?php esc_html_e( 'easeInOutCirc', 'blog-designer-pro' ); ?></option>
											<option value="easeInElastic" <?php echo 'easeInElastic' === $template_easing ? 'selected="selected"' : ''; ?>><?php esc_html_e( 'easeInElastic', 'blog-designer-pro' ); ?></option>
											<option value="easeOutElastic" <?php echo 'easeOutElastic' === $template_easing ? 'selected="selected"' : ''; ?>><?php esc_html_e( 'easeOutElastic', 'blog-designer-pro' ); ?></option>
											<option value="easeInOutElastic" <?php echo 'easeInOutElastic' === $template_easing ? 'selected="selected"' : ''; ?>><?php esc_html_e( 'easeInOutElastic', 'blog-designer-pro' ); ?></option>
											<option value="easeInBack" <?php echo 'easeInBack' === $template_easing ? 'selected="selected"' : ''; ?>><?php esc_html_e( 'easeInBack', 'blog-designer-pro' ); ?></option>
											<option value="easeOutBack" <?php echo 'easeOutBack' === $template_easing ? 'selected="selected"' : ''; ?>><?php esc_html_e( 'easeOutBack', 'blog-designer-pro' ); ?></option>
											<option value="easeInOutBack" <?php echo 'easeInOutBack' === $template_easing ? 'selected="selected"' : ''; ?>><?php esc_html_e( 'easeInOutBack', 'blog-designer-pro' ); ?></option>
											<option value="easeInBounce" <?php echo 'easeInBounce' === $template_easing ? 'selected="selected"' : ''; ?>><?php esc_html_e( 'easeInBounce', 'blog-designer-pro' ); ?></option>
											<option value="easeOutBounce" <?php echo 'easeOutBounce' === $template_easing ? 'selected="selected"' : ''; ?>><?php esc_html_e( 'easeOutBounce', 'blog-designer-pro' ); ?></option>
											<option value="easeInOutBounce" <?php echo 'easeInOutBounce' === $template_easing ? 'selected="selected"' : ''; ?>><?php esc_html_e( 'easeInOutBounce', 'blog-designer-pro' ); ?></option>
										</select>
									</div>
								</li>
								<li>
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Post Width', 'blog-designer-pro' ); ?>
										</span><span class="bdp-px-prefix"><?php esc_html_e( '(px)', 'blog-designer-pro' ); ?></span>
									</div>
									<div class="bdp-right">
										<input type="number" name="item_width" id="item_width" min="100" max="1100" step="1" onblur="if (this.value <= 100) (this.value = 100)" value="<?php echo ( isset( $bdp_settings['item_width'] ) ? esc_attr( $bdp_settings['item_width'] ) : 400 ); ?>">
									</div>
								</li>

								<li>
									<div class="bdp-left">
										<span class="bdp-key-title"><?php esc_html_e( 'Item Height', 'blog-designer-pro' ); ?></span>
										<span class="bdp-px-prefix"><?php esc_html_e( '(px)', 'blog-designer-pro' ); ?></span>
									</div>
									<div class="bdp-right">
										<input type="number" name="item_height" id="item_height" min="100" max="1100" step="1" onblur="if (this.value <= 100) (this.value = 100)" value="<?php echo ( isset( $bdp_settings['item_height'] ) ? esc_attr( $bdp_settings['item_height'] ) : 570 ); ?>">
									</div>
								</li>
								<li>
									<div class="bdp-left">
										<span class="bdp-key-title"><?php esc_html_e( 'Margin between Blog Post', 'blog-designer-pro' ); ?></span>
										<span class="bdp-px-prefix"><?php esc_html_e( '(px)', 'blog-designer-pro' ); ?></span>
									</div>
									<div class="bdp-right">
										<input type="number" class="grid_col_space_val" name="template_post_margin" id="template_post_margin" value="<?php echo ( isset( $bdp_settings['template_post_margin'] ) ? esc_attr( $bdp_settings['template_post_margin'] ) : 28 ); ?>" />
									</div>
								</li>

								<li>
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Enable Autoslide', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<?php $enable_autoslide = ( ( isset( $bdp_settings['enable_autoslide'] ) && '' != $bdp_settings['enable_autoslide'] ) ) ? $bdp_settings['enable_autoslide'] : 1; ?>
										<fieldset class="bdp-social-options bdp-enable_autoslide buttonset buttonset-hide ui-buttonset" data-hide="1">
											<input id="enable_autoslide_1" name="enable_autoslide" class="enable_autoslide" type="radio" value="1" <?php checked( 1, $enable_autoslide ); ?> />
											<label for="enable_autoslide_1" <?php checked( 1, $enable_autoslide ); ?>><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
											<input id="enable_autoslide_0" name="enable_autoslide" class="enable_autoslide" type="radio" value="0" <?php checked( 0, $enable_autoslide ); ?> />
											<label for="enable_autoslide_0" <?php checked( 0, $enable_autoslide ); ?>><?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
										</fieldset>
									</div>
								</li>

								<li class="scroll_speed_tr">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Scrolling Speed', 'blog-designer-pro' ); ?>
										</span><span class="bdp-px-prefix"><?php esc_html_e( '(ms)', 'blog-designer-pro' ); ?></span>
									</div>
									<div class="bdp-right">
										<input type="number" name="scroll_speed" id="scroll_speed" min="500" step="100" onblur="if (this.value <= 500) (this.value = 500)" value="<?php echo ( isset( $bdp_settings['scroll_speed'] ) ? esc_html( $bdp_settings['scroll_speed'] ) : 1000 ); ?>">
									</div>
								</li>
							</div>
						</ul>
					</div>
				</div>
				<div id="bdpstandard" class="postbox postbox-with-fw-options" style=<?php echo esc_attr( $bdpstandard_class_show ); ?>>
					<div class="inside">
						<ul class="bdp-settings bdp-lineheight">
							<li class="bdp-main-container-li">
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
							<div class="bdp_grid_col_2">
								<li class="column_setting_tr">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Set Column', 'blog-designer-pro' ); ?>
											<?php echo '<span class="bdp-media-div">(<i>' . esc_html__( 'Desktop - Above', 'blog-designer-pro' ) . ' 980px</i>)</span>'; ?>
										</span>
									</div>
									<div class="bdp-right">
										<?php
										$template_name  = ( isset( $bdp_settings['template_name'] ) && '' != $bdp_settings['template_name'] ) ? $bdp_settings['template_name'] : '';
										$column         = ( 'chapter' === $template_name ) ? 3 : 2;
										$column_setting = ( isset( $bdp_settings['column_setting'] ) && '' != $bdp_settings['column_setting'] ) ? $bdp_settings['column_setting'] : $column;
										?>
										<select id="column_setting" name="column_setting">
											<option value="1" <?php echo ( 1 == $column_setting ) ? 'selected="selected"' : ''; ?> > <?php esc_html_e( '1 Column', 'blog-designer-pro' ); ?> </option>
											<option value="2" <?php echo ( 2 == $column_setting ) ? 'selected="selected"' : ''; ?>> <?php esc_html_e( '2 Columns', 'blog-designer-pro' ); ?> </option>
											<option value="3" <?php echo ( 3 == $column_setting ) ? 'selected="selected"' : ''; ?>> <?php esc_html_e( '3 Columns', 'blog-designer-pro' ); ?> </option>
											<option value="4" <?php echo ( 4 == $column_setting ) ? 'selected="selected"' : ''; ?>> <?php esc_html_e( '4 Columns', 'blog-designer-pro' ); ?> </option>
										</select>
									</div>
								</li>

								<li class="column_setting_tr">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Set Column', 'blog-designer-pro' ); ?>
											<?php echo '<span class="bdp-media-div">(<i>' . esc_html__( 'iPad', 'blog-designer-pro' ) . ' - 720px - 980px</i>)</span>'; ?>
										</span>
									</div>
									<div class="bdp-right">
										<?php
										$template_name  = ( isset( $bdp_settings['template_name'] ) && '' != $bdp_settings['template_name'] ) ? $bdp_settings['template_name'] : '';
										$column         = ( 'chapter' === $template_name ) ? 3 : 2;
										$column_setting = ( isset( $bdp_settings['column_setting_ipad'] ) && '' != $bdp_settings['column_setting_ipad'] ) ? $bdp_settings['column_setting_ipad'] : $column;
										?>
										<select id="column_setting_ipad" name="column_setting_ipad">
											<option value="1" <?php echo ( 1 == $column_setting ) ? 'selected="selected"' : ''; ?> > <?php esc_html_e( '1 Column', 'blog-designer-pro' ); ?> </option>
											<option value="2" <?php echo ( 2 == $column_setting ) ? 'selected="selected"' : ''; ?>> <?php esc_html_e( '2 Columns', 'blog-designer-pro' ); ?> </option>
											<option value="3" <?php echo ( 3 == $column_setting ) ? 'selected="selected"' : ''; ?>> <?php esc_html_e( '3 Columns', 'blog-designer-pro' ); ?> </option>
											<option value="4" <?php echo ( 4 == $column_setting ) ? 'selected="selected"' : ''; ?>> <?php esc_html_e( '4 Columns', 'blog-designer-pro' ); ?> </option>
										</select>
									</div>
								</li>

								<li class="column_setting_tr">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Set Column', 'blog-designer-pro' ); ?>
											<?php echo '<span class="bdp-media-div">(<i>' . esc_html__( 'Tablet', 'blog-designer-pro' ) . ' - 480px - 720px</i>)</span>'; ?>
										</span>
									</div>
									<div class="bdp-right">
										<?php
										$template_name  = ( isset( $bdp_settings['template_name'] ) && '' != $bdp_settings['template_name'] ) ? $bdp_settings['template_name'] : '';
										$column         = ( 'chapter' === $template_name ) ? 2 : 1;
										$column_setting = ( isset( $bdp_settings['column_setting_tablet'] ) && '' != $bdp_settings['column_setting_tablet'] ) ? $bdp_settings['column_setting_tablet'] : $column;
										?>
										<select id="column_setting_tablet" name="column_setting_tablet">
											<option value="1" <?php echo ( 1 == $column_setting ) ? 'selected="selected"' : ''; ?> > <?php esc_html_e( '1 Column', 'blog-designer-pro' ); ?> </option>
											<option value="2" <?php echo ( 2 == $column_setting ) ? 'selected="selected"' : ''; ?>> <?php esc_html_e( '2 Columns', 'blog-designer-pro' ); ?> </option>
											<option value="3" <?php echo ( 3 == $column_setting ) ? 'selected="selected"' : ''; ?>> <?php esc_html_e( '3 Columns', 'blog-designer-pro' ); ?> </option>
											<option value="4" <?php echo ( 4 == $column_setting ) ? 'selected="selected"' : ''; ?>> <?php esc_html_e( '4 Columns', 'blog-designer-pro' ); ?> </option>
										</select>
									</div>
								</li>
								<li class="column_setting_tr">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Set Column', 'blog-designer-pro' ); ?>
											<?php echo '<span class="bdp-media-div">(<i>' . esc_html__( 'Mobile - Smaller Than', 'blog-designer-pro' ) . ' 480px </i>)</span>'; ?>
										</span>
									</div>
									<div class="bdp-right">
										<?php $column_setting = ( isset( $bdp_settings['column_setting_mobile'] ) && '' != $bdp_settings['column_setting_mobile'] ) ? $bdp_settings['column_setting_mobile'] : 1; ?>
										<select id="column_setting_mobile" name="column_setting_mobile">
											<option value="1" <?php echo ( 1 == $column_setting ) ? 'selected="selected"' : ''; ?> > <?php esc_html_e( '1 Column', 'blog-designer-pro' ); ?> </option>
											<option value="2" <?php echo ( 2 == $column_setting ) ? 'selected="selected"' : ''; ?>> <?php esc_html_e( '2 Columns', 'blog-designer-pro' ); ?> </option>
											<option value="3" <?php echo ( 3 == $column_setting ) ? 'selected="selected"' : ''; ?>> <?php esc_html_e( '3 Columns', 'blog-designer-pro' ); ?> </option>
											<option value="4" <?php echo ( 4 == $column_setting ) ? 'selected="selected"' : ''; ?>> <?php esc_html_e( '4 Columns', 'blog-designer-pro' ); ?> </option>
										</select>
									</div>
								</li>
							</div>

							<div class="bdp_grid_col_3 bdp_col_ash">
								<li class="apply_same_height_section">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Apply same height for posts block?', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<?php $apply_same_height = isset( $bdp_settings['apply_same_height'] ) ? $bdp_settings['apply_same_height'] : '0'; ?>
										<fieldset class="bdp-social-options bdp-apply_same_height buttonset buttonset-hide ui-buttonset">
											<input id="apply_same_height_1" name="apply_same_height" type="radio" value="1" <?php checked( 1, $apply_same_height ); ?> />
											<label for="apply_same_height_1" <?php checked( 1, $apply_same_height ); ?>><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
											<input id="apply_same_height_0" name="apply_same_height" type="radio" value="0" <?php checked( 0, $apply_same_height ); ?> />
											<label for="apply_same_height_0" <?php checked( 0, $apply_same_height ); ?>><?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
										</fieldset>
									</div>
								</li>
								<li class="blog_unique_design_tr">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Blog Unique Design', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
									<?php $blog_unique_design = isset( $bdp_settings['blog_unique_design'] ) ? $bdp_settings['blog_unique_design'] : '0'; ?>
											<fieldset class="bdp-social-options bdp-blog_unique_design buttonset buttonset-hide ui-buttonset">
												<input id="blog_unique_design_1" name="blog_unique_design" type="radio" value="1" <?php checked( 1, $blog_unique_design ); ?> />
												<label for="blog_unique_design_1" <?php checked( 1, $blog_unique_design ); ?>><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
												<input id="blog_unique_design_0" name="blog_unique_design" type="radio" value="0" <?php checked( 0, $blog_unique_design ); ?> />
												<label for="blog_unique_design_0" <?php checked( 0, $blog_unique_design ); ?>><?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
											</fieldset>
									</div>
								</li>
								<li class="blog-unique-tr">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Select Unique Design for first post or Featured posts', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<?php $bdp_settings['unique_design_option'] = isset( $bdp_settings['unique_design_option'] ) ? $bdp_settings['unique_design_option'] : 'first_post'; ?>
										<select name="unique_design_option" id="unique_design_option">
											<option value="first_post" 
											<?php
											if ( 'first_post' === $bdp_settings['unique_design_option'] ) {
												?>
												selected="selected"<?php } ?>>
												<?php esc_html_e( 'First Post', 'blog-designer-pro' ); ?>
											</option>
											<option value="featured_posts" 
											<?php
											if ( 'featured_posts' === $bdp_settings['unique_design_option'] ) {
												?>
												selected="selected"<?php } ?>>
												<?php esc_html_e( 'Featured Posts', 'blog-designer-pro' ); ?>
											</option>
										</select>
										<div class="bdp-setting-description bdp-note">
											<b class="note"><?php esc_html_e( 'Note', 'blog-designer-pro' ); ?>:</b>
											<?php esc_html_e( 'If you selected featured post option, then unique design apply on sticky post.', 'blog-designer-pro' ); ?>
										</div>
									</div>
								</li>
							</div>

							<div class="bdp_grid_col_2">
								<li class="blog-columns-tr">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php echo esc_html_e( 'Blog Grid Columns', 'blog-designer-pro' ); ?>
											<?php echo '<span class="bdp-media-div">(<i>' . esc_html__( 'Desktop - Above', 'blog-designer-pro' ) . ' 980px</i>)</span>'; ?>
										</span>
									</div>
									<div class="bdp-right">
										<?php $bdp_settings['template_columns'] = isset( $bdp_settings['template_columns'] ) ? $bdp_settings['template_columns'] : 2; ?>
										<select name="template_columns" id="template_columns">
											<option value="1" 
											<?php
											if ( '1' == $bdp_settings['template_columns'] ) {
												?>
												selected="selected"<?php } ?>>
												<?php esc_html_e( '1 Column', 'blog-designer-pro' ); ?>
											</option>
											<option value="2" 
											<?php
											if ( '2' === $bdp_settings['template_columns'] ) {
												?>
												selected="selected"<?php } ?>>
												<?php esc_html_e( '2 Columns', 'blog-designer-pro' ); ?>
											</option>
											<option value="3" 
											<?php
											if ( '3' === $bdp_settings['template_columns'] ) {
												?>
												selected="selected"<?php } ?>>
												<?php esc_html_e( '3 Columns', 'blog-designer-pro' ); ?>
											</option>
											<option value="4" 
											<?php
											if ( '4' === $bdp_settings['template_columns'] ) {
												?>
												selected="selected"<?php } ?>>
												<?php esc_html_e( '4 Columns', 'blog-designer-pro' ); ?>
											</option>
										</select>
									</div>
								</li>

								<li class="blog-columns-tr">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php echo esc_html_e( 'Blog Grid Columns', 'blog-designer-pro' ); ?>
											<?php echo '<span class="bdp-media-div">(<i>' . esc_html__( 'iPad', 'blog-designer-pro' ) . ' - 720px - 980px</i>)</span>'; ?>
										</span>
									</div>
									<div class="bdp-right">
										<?php $bdp_settings['template_columns_ipad'] = isset( $bdp_settings['template_columns_ipad'] ) ? $bdp_settings['template_columns_ipad'] : 1; ?>
										<select name="template_columns_ipad" id="template_columns_ipad">
											<option value="1" 
											<?php
											if ( '1' == $bdp_settings['template_columns_ipad'] ) {
												?>
												selected="selected"<?php } ?>>
												<?php esc_html_e( '1 Column', 'blog-designer-pro' ); ?>
											</option>
											<option value="2" 
											<?php
											if ( '2' === $bdp_settings['template_columns_ipad'] ) {
												?>
												selected="selected"<?php } ?>>
												<?php esc_html_e( '2 Columns', 'blog-designer-pro' ); ?>
											</option>
											<option value="3" 
											<?php
											if ( '3' === $bdp_settings['template_columns_ipad'] ) {
												?>
												selected="selected"<?php } ?>>
												<?php esc_html_e( '3 Columns', 'blog-designer-pro' ); ?>
											</option>
											<option value="4" 
											<?php
											if ( '4' === $bdp_settings['template_columns_ipad'] ) {
												?>
												selected="selected"<?php } ?>>
												<?php esc_html_e( '4 Columns', 'blog-designer-pro' ); ?>
											</option>
										</select>
									</div>
								</li>
								<li class="blog-columns-tr">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php echo esc_html_e( 'Blog Grid Columns', 'blog-designer-pro' ); ?>
											<?php echo '<span class="bdp-media-div">(<i>' . esc_html__( 'Tablet', 'blog-designer-pro' ) . ' - 480px - 720px</i>)</span>'; ?>
										</span>
									</div>
									<div class="bdp-right">
										<?php $bdp_settings['template_columns_tablet'] = isset( $bdp_settings['template_columns_tablet'] ) ? $bdp_settings['template_columns_tablet'] : 1; ?>
										<select name="template_columns_tablet" id="template_columns_tablet">
											<option value="1" 
											<?php
											if ( '1' == $bdp_settings['template_columns_tablet'] ) {
												?>
												selected="selected"<?php } ?>>
												<?php esc_html_e( '1 Column', 'blog-designer-pro' ); ?>
											</option>
											<option value="2" 
											<?php
											if ( '2' === $bdp_settings['template_columns_tablet'] ) {
												?>
												selected="selected"<?php } ?>>
												<?php esc_html_e( '2 Columns', 'blog-designer-pro' ); ?>
											</option>
											<option value="3" 
											<?php
											if ( '3' === $bdp_settings['template_columns_tablet'] ) {
												?>
												selected="selected"<?php } ?>>
												<?php esc_html_e( '3 Columns', 'blog-designer-pro' ); ?>
											</option>
											<option value="4" 
											<?php
											if ( '4' === $bdp_settings['template_columns_tablet'] ) {
												?>
												selected="selected"<?php } ?>>
												<?php esc_html_e( '4 Columns', 'blog-designer-pro' ); ?>
											</option>
										</select>
									</div>
								</li>
								<li class="blog-columns-tr">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php echo esc_html_e( 'Blog Grid Columns', 'blog-designer-pro' ); ?>
											<?php echo '<span class="bdp-media-div">(<i>' . esc_html__( 'Mobile - Smaller Than', 'blog-designer-pro' ) . ' 480px </i>)</span>'; ?>
										</span>
									</div>
									<div class="bdp-right">
										<?php $bdp_settings['template_columns_mobile'] = isset( $bdp_settings['template_columns_mobile'] ) ? $bdp_settings['template_columns_mobile'] : 1; ?>
										<select name="template_columns_mobile" id="template_columns_mobile">
											<option value="1" 
											<?php
											if ( '1' == $bdp_settings['template_columns_mobile'] ) {
												?>
												selected="selected"<?php } ?>>
												<?php esc_html_e( '1 Column', 'blog-designer-pro' ); ?>
											</option>
											<option value="2" 
											<?php
											if ( '2' === $bdp_settings['template_columns_mobile'] ) {
												?>
												selected="selected"<?php } ?>>
												<?php esc_html_e( '2 Columns', 'blog-designer-pro' ); ?>
											</option>
											<option value="3" 
											<?php
											if ( '3' === $bdp_settings['template_columns_mobile'] ) {
												?>
												selected="selected"<?php } ?>>
												<?php esc_html_e( '3 Columns', 'blog-designer-pro' ); ?>
											</option>
											<option value="4" 
											<?php
											if ( '4' === $bdp_settings['template_columns_mobile'] ) {
												?>
												selected="selected"<?php } ?>>
												<?php esc_html_e( '4 Columns', 'blog-designer-pro' ); ?>
											</option>
										</select>
									</div>
								</li>
							</div>
							<h3 class="bdp-table-title bdp-table-sub-title bdp-bg-title-wrap"><?php esc_html_e( 'Post Color Settings', 'blog-designer-pro' ); ?></h3>
							<div class="bdp_grid_col_2">
								<li class="blog-templatecolor-tr blog-templatecolor-tr">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Blog Posts Template Color', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<input type="text" name="template_color" id="template_color" value="<?php echo isset( $bdp_settings['template_color'] ) ? esc_attr( $bdp_settings['template_color'] ) : '#000000'; ?>"/>
									</div>
								</li>
							</div>
							<div class="bdp_grid_col_2  preset">
								<li class="blog-templatecolor-tr1 blog-templatecolor-tr">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Repetitive Blog Posts Template Color 1', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<input type="text" name="template_color1" id="template_color1" value="<?php echo isset( $bdp_settings['template_color1'] ) ? esc_attr( $bdp_settings['template_color1'] ) : '#72616e'; ?>"/>
									</div>
								</li>
								<li class="blog-templatecolor-tr2 blog-templatecolor-tr">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Repetitive Blog Posts Template Color 2', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<input type="text" name="template_color2" id="template_color2" value="<?php echo isset( $bdp_settings['template_color2'] ) ? esc_attr( $bdp_settings['template_color2'] ) : '#e8846b'; ?>"/>
									</div>
								</li>
								<li class="blog-templatecolor-tr3 blog-templatecolor-tr">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Repetitive Blog Posts Template Color 3', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<input type="text" name="template_color3" id="template_color3" value="<?php echo isset( $bdp_settings['template_color3'] ) ? esc_attr( $bdp_settings['template_color3'] ) : '#16528e'; ?>"/>
									</div>
								</li>
								<li class="blog-templatecolor-tr4 blog-templatecolor-tr">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Repetitive Blog Posts Template Color 4', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<input type="text" name="template_color4" id="template_color4" value="<?php echo isset( $bdp_settings['template_color4'] ) ? esc_attr( $bdp_settings['template_color4'] ) : '#e54b4b'; ?>"/>
									</div>
								</li>
								<li class="blog-templatecolor-tr5 blog-templatecolor-tr">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Repetitive Blog Posts Template Color 5', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<input type="text" name="template_color5" id="template_color5" value="<?php echo isset( $bdp_settings['template_color5'] ) ? esc_attr( $bdp_settings['template_color5'] ) : '#a2c5bf'; ?>"/>
									</div>
								</li>
								<li class="blog-templatecolor-tr6 blog-templatecolor-tr">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Repetitive Blog Posts Template Color 6', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<input type="text" name="template_color6" id="template_color6" value="<?php echo isset( $bdp_settings['template_color6'] ) ? esc_attr( $bdp_settings['template_color6'] ) : '#167c80'; ?>"/>
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
								<li class="story-ending-tr">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Story Ending Text', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<input type="text" name="story_ending_text" id="story_ending_text" value="<?php echo isset( $bdp_settings['story_ending_text'] ) ? esc_attr( $bdp_settings['story_ending_text'] ) : esc_html__( 'Ending', 'blog-designer-pro' ); ?>"/>
									</div>
								</li>
								<li class="template_alternative_color blog-templatecolor-tr">
								<div class="bdp-left">
									<span class="bdp-key-title">
										<?php esc_html_e( 'Blog Posts Alternative Template Color', 'blog-designer-pro' ); ?>
									</span>
								</div>
								<div class="bdp-right">
									<input type="text" name="template_alternative_color" id="template_alternative_color" value="<?php echo isset( $bdp_settings['template_alternative_color'] ) ? esc_attr( $bdp_settings['template_alternative_color'] ) : '#c34376'; ?>"/>
								</div>
								</li>

								<li class="story-startup-tr blog-templatecolor-tr">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Story Border Color', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<input type="text" name="story_startup_border_color" id="story_startup_border_color" value="<?php echo isset( $bdp_settings['story_startup_border_color'] ) ? esc_attr( $bdp_settings['story_startup_border_color'] ) : '#ffffff'; ?>"/>
									</div>
								</li>
								<li class="story-startup-tr blog-templatecolor-tr">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Story Startup Background Color', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<input type="text" name="story_startup_background" id="story_startup_background" value="<?php echo isset( $bdp_settings['story_startup_background'] ) ? esc_attr( $bdp_settings['story_startup_background'] ) : '#ade175'; ?>" data-default-color="<?php echo isset( $bdp_settings['story_startup_background'] ) ? esc_attr( $bdp_settings['story_startup_background'] ) : '#ade175'; ?>"/>
									</div>
								</li>
								<li class="story-startup-tr blog-templatecolor-tr">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Story Startup Text Color', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<input type="text" name="story_startup_text_color" id="story_startup_text_color"
											value="<?php echo isset( $bdp_settings['story_startup_text_color'] ) ? esc_attr( $bdp_settings['story_startup_text_color'] ) : '#333'; ?>"
											data-default-color="<?php echo isset( $bdp_settings['story_startup_text_color'] ) ? esc_attr( $bdp_settings['story_startup_text_color'] ) : '#333'; ?>"/>
									</div>
								</li>
								<li class="story-ending-tr">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Story Post Loop Alignment', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<?php $post_loop_alignment = isset( $bdp_settings['post_loop_alignment'] ) ? $bdp_settings['post_loop_alignment'] : 'default'; ?>
										<select name="post_loop_alignment" id="story_loop_alignment">
											<option value="default" <?php echo selected( 'default', $post_loop_alignment ); ?>>
												<?php esc_html_e( 'Default', 'blog-designer-pro' ); ?>
											</option>
											<option value="left" <?php echo selected( 'left', $post_loop_alignment ); ?>>
												<?php esc_html_e( 'Left', 'blog-designer-pro' ); ?>
											</option>
											<option value="right" <?php echo selected( 'right', $post_loop_alignment ); ?>>
												<?php esc_html_e( 'Right', 'blog-designer-pro' ); ?>
											</option>
										</select>
									</div>
								</li>

								<li class="ticker-effect-option">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Ticker Effect', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
									<?php $ticker_effect = isset( $bdp_settings['ticker_effect'] ) ? $bdp_settings['ticker_effect'] : 'none'; ?>
										<div class="select-cover">
											<select name="ticker_effect" id="ticker_effect">
												<option <?php selected( $ticker_effect, 'fade' ); ?> value="fade"><?php esc_html_e( 'Fade', 'blog-designer-pro' ); ?></option>
												<option <?php selected( $ticker_effect, 'typography' ); ?> value="typography"><?php esc_html_e( 'Type', 'blog-designer-pro' ); ?></option>
												<option <?php selected( $ticker_effect, 'scroll' ); ?> value="scroll"><?php esc_html_e( 'Scroll', 'blog-designer-pro' ); ?></option>
												<option <?php selected( $ticker_effect, 'slide-down' ); ?> value="slide-down"><?php esc_html_e( 'Scroll Down', 'blog-designer-pro' ); ?></option>
												<option <?php selected( $ticker_effect, 'slide-up' ); ?> value="slide-up"><?php esc_html_e( 'Scroll Up', 'blog-designer-pro' ); ?></option>
												<option <?php selected( $ticker_effect, 'slide-right' ); ?> value="slide-right"><?php esc_html_e( 'Scroll Right', 'blog-designer-pro' ); ?></option>
												<option <?php selected( $ticker_effect, 'slide-left' ); ?> value="slide-left"><?php esc_html_e( 'Scroll Left', 'blog-designer-pro' ); ?></option>
											</select>
										</div>
									</div>
								</li>

								<li class="ticker-label-text">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Ticker Label Text', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<?php $ticker_label_text = ( isset( $bdp_settings['ticker_label_text'] ) && '' != $bdp_settings['ticker_label_text'] ) ? $bdp_settings['ticker_label_text'] : ''; ?>
										<input type="text" name="ticker_label_text" id="ticker_label_text" value="<?php echo esc_attr( $ticker_label_text ); ?>" placeholder="<?php esc_attr_e( 'Enter text for Ticker label', 'blog-designer-pro' ); ?>">
									</div>
								</li>

								<li class="ticker_autoplay_tr">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Ticker Autoplay', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<?php $ticker_autoplay = isset( $bdp_settings['ticker_autoplay'] ) ? $bdp_settings['ticker_autoplay'] : '1'; ?>
										<fieldset class="bdp-social-options bdp-ticker_autoplay buttonset buttonset-hide ui-buttonset">
											<input id="ticker_autoplay_1" name="ticker_autoplay" type="radio" value="1" <?php checked( 1, $ticker_autoplay ); ?> />
											<label for="ticker_autoplay_1" <?php checked( 1, $ticker_autoplay ); ?>><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
											<input id="ticker_autoplay_0" name="ticker_autoplay" type="radio" value="0" <?php checked( 0, $ticker_autoplay ); ?> />
											<label for="ticker_autoplay_0" <?php checked( 0, $ticker_autoplay ); ?>><?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
										</fieldset>
									</div>
								</li>

								<li class="blog-sallet-slider-tr">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php echo esc_html_e( 'Display Blog Content in ', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
											<?php $bdp_settings['template_slider_content'] = isset( $bdp_settings['template_slider_content'] ) ? $bdp_settings['template_slider_content'] : 'center'; ?>
										<select name="template_slider_content" id="template_slider_content">
											<option value="center" 
											<?php
											if ( 'center' === $bdp_settings['template_slider_content'] ) {
												?>
												selected="selected"<?php } ?>>
												<?php esc_html_e( 'Center', 'blog-designer-pro' ); ?>
											</option>
											<option value="right" 
											<?php
											if ( 'right' === $bdp_settings['template_slider_content'] ) {
												?>
												selected="selected"<?php } ?>>
												<?php esc_html_e( 'Right', 'blog-designer-pro' ); ?>
											</option>
										</select>
									</div>
								</li>

								<li class="story-ending-tr">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Story Ending Link', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<input type="text" name="story_ending_link" id="story_ending_link" value="<?php echo isset( $bdp_settings['story_ending_link'] ) ? esc_attr( $bdp_settings['story_ending_link'] ) : ''; ?>"/>
									</div>
								</li>
								<li class="story-ending-tr blog-templatecolor-tr">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Story Ending Background Color', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<input type="text" name="story_ending_background" id="story_ending_background" value="<?php echo isset( $bdp_settings['story_ending_background'] ) ? esc_attr( $bdp_settings['story_ending_background'] ) : '#ade175'; ?>" data-default-color="<?php echo isset( $bdp_settings['story_ending_background'] ) ? esc_attr( $bdp_settings['story_ending_background'] ) : '#ade175'; ?>"/>
									</div>
								</li>

								<li class="story-ending-tr blog-templatecolor-tr">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Story Ending Text Color', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<input type="text" name="story_ending_text_color" id="story_ending_text_color"
											value="<?php echo isset( $bdp_settings['story_ending_text_color'] ) ? esc_attr( $bdp_settings['story_ending_text_color'] ) : '#333'; ?>"
											data-default-color="<?php echo isset( $bdp_settings['story_ending_text_color'] ) ? esc_attr( $bdp_settings['story_ending_text_color'] ) : '#333'; ?>"/>
									</div>
								</li>
								<li class="deport-dash-div blog-templatecolor-tr" style="display: none;">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Choose Dash Color', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<input type="text" name="deport_dashcolor" id="deport_dashcolor"
											value="
											<?php
											if ( isset( $bdp_settings['deport_dashcolor'] ) ) {
												echo esc_attr( $bdp_settings['deport_dashcolor'] );
											}
											?>
											"
											data-default-color="
											<?php
											if ( isset( $bdp_settings['deport_dashcolor'] ) ) {
												echo esc_attr( $bdp_settings['deport_dashcolor'] );
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
										<input type="text" name="winter_category_color" id="winter_category_color"
											value="
											<?php
											if ( isset( $bdp_settings['winter_category_color'] ) ) {
												echo esc_attr( $bdp_settings['winter_category_color'] );
											}
											?>
											"
											data-default-color="
											<?php
											if ( isset( $bdp_settings['winter_category_color'] ) ) {
												echo esc_attr( $bdp_settings['winter_category_color'] );
											}
											?>
											"/>
									</div>
								</li>
								<li class="lightbreeze-image-corner" style="display:none">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Image Corner Selection', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<?php $image_corner_selection = isset( $bdp_settings['image_corner_selection'] ) ? $bdp_settings['image_corner_selection'] : 0; ?>
										<fieldset class="bdp-social-size buttonset buttonset-hide green" data-hide='1'>
											<input id="image_corner_selection_0" name="image_corner_selection" type="radio" value="0" <?php checked( 0, $image_corner_selection ); ?> />
											<label id="bdp-options-button" for="image_corner_selection_0" <?php checked( 0, $image_corner_selection ); ?>><?php esc_html_e( 'Triangle', 'blog-designer-pro' ); ?></label>
											<input id="image_corner_selection_1" name="image_corner_selection" type="radio" value="1" <?php checked( 1, $image_corner_selection ); ?> />
											<label id="bdp-options-button" for="image_corner_selection_1" <?php checked( 1, $image_corner_selection ); ?>><?php esc_html_e( 'Square', 'blog-designer-pro' ); ?></label>
											<input id="image_corner_selection_2" name="image_corner_selection" type="radio" value="2" <?php checked( 2, $image_corner_selection ); ?> />
											<label id="bdp-options-button" for="image_corner_selection_2" <?php checked( 2, $image_corner_selection ); ?>><?php esc_html_e( 'Round', 'blog-designer-pro' ); ?></label>
										</fieldset>
									</div>
								</li>
							</div>

							<div class="bdp_grid_col_3">
								<li class="ticker_autoplay_interval">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Enter ticker autoplay intervals(ms)', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<?php $ticker_autoplay_interval = isset( $bdp_settings['ticker_autoplay_interval'] ) ? $bdp_settings['ticker_autoplay_interval'] : '1'; ?>
										<input type="number" id="ticker_autoplay_interval" name="ticker_autoplay_interval" step="1" min="0" value="<?php echo isset( $bdp_settings['ticker_autoplay_interval'] ) ? esc_attr( $bdp_settings['ticker_autoplay_interval'] ) : '3000'; ?>" placeholder="<?php esc_attr_e( 'Enter ticker intervals', 'blog-designer-pro' ); ?>" onkeypress="return isNumberKey(event)">
									</div>
								</li>

								<li class="crayon-slider-design">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Choose Design', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
									<?php $cd_design_type = ( isset( $bdp_settings['cd_design_type'] ) && ! empty( $bdp_settings['cd_design_type'] ) ) ? $bdp_settings['cd_design_type'] : '0'; ?>
										<div class="select-cover">
											<select name="cd_design_type" id="cd_design_type">
												<option <?php selected( $cd_design_type, 'design1' ); ?> value="design1"><?php esc_html_e( 'Design 1', 'blog-designer-pro' ); ?></option>
												<option <?php selected( $cd_design_type, 'design2' ); ?> value="design2"><?php esc_html_e( 'Design 2', 'blog-designer-pro' ); ?></option>
											</select>
										</div>
									</div>
								</li>

								<li class="timeline-layout-design">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Choose Design', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
									<?php $timeline_design = ( isset( $bdp_settings['timeline_design'] ) && ! empty( $bdp_settings['timeline_design'] ) ) ? $bdp_settings['timeline_design'] : '0'; ?>
										<div class="select-cover">
											<select name="timeline_design" id="timeline_design">
												<option <?php selected( $timeline_design, 'design1' ); ?> value="design1"><?php esc_html_e( 'Design 1', 'blog-designer-pro' ); ?></option>
												<option <?php selected( $timeline_design, 'design2' ); ?> value="design2"><?php esc_html_e( 'Design 2', 'blog-designer-pro' ); ?></option>
											</select>
										</div>
									</div>
								</li>
								<li class="leest-layout-design">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Choose Design', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
									<?php $leest_design = ( isset( $bdp_settings['leest_design'] ) && ! empty( $bdp_settings['leest_design'] ) ) ? $bdp_settings['leest_design'] : '0'; ?>
										<div class="select-cover">
											<select name="leest_design" id="timeline_design">
												<option <?php selected( $leest_design, 'top-bottom' ); ?> value="top-bottom"><?php esc_html_e( 'Top - Bottom', 'blog-designer-pro' ); ?></option>
												<option <?php selected( $leest_design, 'top' ); ?> value="top"><?php esc_html_e( 'Top', 'blog-designer-pro' ); ?></option>
												<option <?php selected( $leest_design, 'bottom' ); ?> value="bottom"><?php esc_html_e( 'Bottom', 'blog-designer-pro' ); ?></option>
											</select>
										</div>
									</div>
								</li>
							</div>

							<h3 class="bdp-table-title bdp-table-sub-title bdp-bg-title-wrap bg_list_setting"><?php esc_html_e( 'Background Settings', 'blog-designer-pro' ); ?></h3>
							<div class="bdp_grid_col_2">
								<li class="blog-template-tr bdp-back-color-blog-wrap blog-templatecolor-tr-inner">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Background Color for Blog Post Wrap', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<?php $template_bgcolor_wrap = ( isset( $bdp_settings['template_bgcolor_wrap'] ) && ! empty( $bdp_settings['template_bgcolor_wrap'] ) ) ? $bdp_settings['template_bgcolor_wrap'] : ''; ?>
										<input type="text" name="template_bgcolor_wrap" id="template_bgcolor_wrap" value="<?php echo esc_attr( $template_bgcolor_wrap ); ?>"/>
									</div>
								</li>
								<li class="blog-template-tr bdp-back-color-blog blog-templatecolor-tr-inner">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Background Color for Blog Posts', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<?php $template_bgcolor = ( isset( $bdp_settings['template_bgcolor'] ) && ! empty( $bdp_settings['template_bgcolor'] ) ) ? $bdp_settings['template_bgcolor'] : ''; ?>
										<input type="text" name="template_bgcolor" id="template_bgcolor" value="<?php echo esc_attr( $template_bgcolor ); ?>"/>
									</div>
								</li>
								<li class="display_bgimage_tr blog-templatecolor-tr-inner">
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
								<li class="hoverbic-hoverbackcolor-tr blog-templatecolor-tr-inner">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Blog Posts hover Background Color', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<input type="text" name="grid_hoverback_color" id="grid_hoverback_color" value="<?php echo isset( $bdp_settings['grid_hoverback_color'] ) ? esc_attr( $bdp_settings['grid_hoverback_color'] ) : '#000000'; ?>"/>
									</div>
								</li>
								<li class="bdp-back-color-soft-block blog-templatecolor-tr-inner">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Repetitive Blog Background Color 1', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<?php $template_bgcolor1 = ( isset( $bdp_settings['template_bgcolor1'] ) && ! empty( $bdp_settings['template_bgcolor1'] ) ) ? $bdp_settings['template_bgcolor1'] : ''; ?>
										<input type="text" name="template_bgcolor1" id="template_bgcolor1" value="<?php echo esc_attr( $template_bgcolor1 ); ?>"/>
									</div>
								</li>
								<li class="bdp-back-color-soft-block blog-templatecolor-tr-inner">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Repetitive Blog Background Color 2', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<?php $template_bgcolor2 = ( isset( $bdp_settings['template_bgcolor2'] ) && ! empty( $bdp_settings['template_bgcolor2'] ) ) ? $bdp_settings['template_bgcolor2'] : ''; ?>
										<input type="text" name="template_bgcolor2" id="template_bgcolor2" value="<?php echo esc_attr( $template_bgcolor2 ); ?>"/>
									</div>
								</li>
								<li class="bdp-back-color-soft-block blog-templatecolor-tr-inner">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Repetitive Blog Background Color 3', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<?php $template_bgcolor3 = ( isset( $bdp_settings['template_bgcolor3'] ) && ! empty( $bdp_settings['template_bgcolor3'] ) ) ? $bdp_settings['template_bgcolor3'] : ''; ?>
										<input type="text" name="template_bgcolor3" id="template_bgcolor3" value="<?php echo esc_attr( $template_bgcolor3 ); ?>"/>
									</div>
								</li>
								<li class="bdp-back-color-soft-block blog-templatecolor-tr-inner">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Repetitive Blog Background Color 4', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<?php $template_bgcolor4 = ( isset( $bdp_settings['template_bgcolor4'] ) && ! empty( $bdp_settings['template_bgcolor4'] ) ) ? $bdp_settings['template_bgcolor4'] : ''; ?>
										<input type="text" name="template_bgcolor4" id="template_bgcolor4" value="<?php echo esc_attr( $template_bgcolor4 ); ?>"/>
									</div>
								</li>
								<li class="bdp-back-color-soft-block blog-templatecolor-tr-inner">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Repetitive Blog Background Color 5', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<?php $template_bgcolor5 = ( isset( $bdp_settings['template_bgcolor5'] ) && ! empty( $bdp_settings['template_bgcolor5'] ) ) ? $bdp_settings['template_bgcolor5'] : ''; ?>
										<input type="text" name="template_bgcolor5" id="template_bgcolor5" value="<?php echo esc_attr( $template_bgcolor5 ); ?>"/>
									</div>
								</li>
								<li class="bdp-back-color-soft-block blog-templatecolor-tr-inner">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Repetitive Blog Background Color 6', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<?php $template_bgcolor6 = ( isset( $bdp_settings['template_bgcolor6'] ) && ! empty( $bdp_settings['template_bgcolor6'] ) ) ? $bdp_settings['template_bgcolor6'] : ''; ?>
										<input type="text" name="template_bgcolor6" id="template_bgcolor6" value="<?php echo esc_attr( $template_bgcolor6 ); ?>"/>
									</div>
								</li>
							</div>

							<div class="bdp_grid_col_3">
								<li class="blog_background_image_tr">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Blog Post Feature Image set as Background Image', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
									<?php $blog_background_image = isset( $bdp_settings['blog_background_image'] ) ? $bdp_settings['blog_background_image'] : 1; ?>
										<fieldset class="bdp-blog_background_image buttonset buttonset-hide green" data-hide='1'>
											<input id="blog_background_image_1" name="blog_background_image" type="radio" value="1" <?php checked( 0, $blog_background_image ); ?> />
											<label id="bdp-options-button" for="blog_background_image_1" <?php checked( 1, $blog_background_image ); ?>><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
											<input id="blog_background_image_0" name="blog_background_image" type="radio" value="0" <?php checked( 1, $blog_background_image ); ?> />
											<label id="bdp-options-button" for="blog_background_image_0" <?php checked( 0, $blog_background_image ); ?>><?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
										</fieldset>
									</div>
								</li>
								<li class="blog_background_image_tr blog_background_image_style_tr">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Blog Post Background Image Style', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<?php $blog_background_image_style = isset( $bdp_settings['blog_background_image_style'] ) ? $bdp_settings['blog_background_image_style'] : 1; ?>
										<fieldset class="bdp-blog_background_image_style buttonset buttonset-hide green" data-hide='1'>
											<input id="blog_background_image_style_0" name="blog_background_image_style" type="radio" value="0" <?php checked( 0, $blog_background_image_style ); ?> />
											<label id="bdp-options-button" for="blog_background_image_style_0" <?php checked( 0, $blog_background_image_style ); ?>><?php esc_html_e( 'Normal', 'blog-designer-pro' ); ?></label>
											<input id="blog_background_image_style_1" name="blog_background_image_style" type="radio" value="1" <?php checked( 1, $blog_background_image_style ); ?> />
											<label id="bdp-options-button" for="blog_background_image_style_1" <?php checked( 1, $blog_background_image_style ); ?>><?php esc_html_e( 'Parallax', 'blog-designer-pro' ); ?></label>
										</fieldset>
									</div>
								</li>

								<li class="blog-template-tr">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Alternative Background Color', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<?php $template_alternativebackground = isset( $bdp_settings['template_alternativebackground'] ) ? $bdp_settings['template_alternativebackground'] : 0; ?>
										<fieldset class="bdp-social-options bdp-alternative_color buttonset buttonset-hide" data-hide='1'>
											<input id="template_alternativebackground_1" type="radio" value="1" name="template_alternativebackground" <?php checked( 1, $template_alternativebackground ); ?> />
											<label for="template_alternativebackground_1" <?php checked( 1, $template_alternativebackground ); ?>><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
											<input id="template_alternativebackground_0" type="radio" value="0" name="template_alternativebackground" <?php checked( 0, $template_alternativebackground ); ?> />
											<label for="template_alternativebackground_0" <?php checked( 0, $template_alternativebackground ); ?>><?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
										</fieldset>
									</div>
								</li>
							</div>

							<div class="bdp_grid_col_2">
								<li class="bdp-back-hover-color-blog blog-templatecolor-tr-inner">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Background Hover Color for Blog Posts', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<input type="text" name="template_bghovercolor" id="template_bghovercolor" value="<?php echo isset( $bdp_settings['template_bghovercolor'] ) ? esc_attr( $bdp_settings['template_bghovercolor'] ) : '#eeeeee'; ?>"/>
									</div>
								</li>

								<li class="alternative-color-tr blog-templatecolor-tr-inner">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Choose Alternative Background Color', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<?php $template_alterbgcolor = ( isset( $bdp_settings['template_alterbgcolor'] ) && ! empty( $bdp_settings['template_alterbgcolor'] ) ) ? $bdp_settings['template_alterbgcolor'] : ''; ?>
										<input type="text" name="template_alterbgcolor" id="template_alterbgcolor" value="<?php echo esc_attr( $template_alterbgcolor ); ?>"/>
									</div>
								</li>
							</div>
							<h3 class="bdp-table-title bdp-table-sub-title bdp_accordion_template"><?php esc_html_e( 'Accordion Layout Settings', 'blog-designer-pro' ); ?></h3>
							<li class="bdp_accordion_template">
								<div class="bdp-left">
									<span class="bdp-key-title">
										<?php esc_html_e( 'Select Accordion Template', 'blog-designer-pro' ); ?>
									</span>
								</div>
								<div class="bdp-right">
									<?php $accordion_template = isset( $bdp_settings['accordion_template'] ) ? $bdp_settings['accordion_template'] : 'accordion-template-1'; ?>
									<select name="accordion_template" id="accordion_template">
										<option value="accordion-template-1" <?php echo selected( 'accordion-template-1', $accordion_template ); ?>>
											<?php esc_html_e( 'Accordion Template 1', 'blog-designer-pro' ); ?>
										</option>
										<option value="accordion-template-2" <?php echo selected( 'accordion-template-2', $accordion_template ); ?>>
											<?php esc_html_e( 'Accordion Template 2', 'blog-designer-pro' ); ?>
										</option>
										<option value="accordion-template-3" <?php echo selected( 'accordion-template-3', $accordion_template ); ?>>
											<?php esc_html_e( 'Accordion Template 3', 'blog-designer-pro' ); ?>
										</option>
										<option value="accordion-template-4" <?php echo selected( 'accordion-template-4', $accordion_template ); ?>>
											<?php esc_html_e( 'Accordion Template 4', 'blog-designer-pro' ); ?>
										</option>
										<option value="accordion-template-5" <?php echo selected( 'accordion-template-5', $accordion_template ); ?>>
											<?php esc_html_e( 'Accordion Template 5', 'blog-designer-pro' ); ?>
										</option>
										<option value="accordion-template-6" <?php echo selected( 'accordion-template-6', $accordion_template ); ?>>
											<?php esc_html_e( 'Accordion Template 6', 'blog-designer-pro' ); ?>
										</option>
										<option value="accordion-template-7" <?php echo selected( 'accordion-template-7', $accordion_template ); ?>>
											<?php esc_html_e( 'Accordion Template 7', 'blog-designer-pro' ); ?>
										</option>
										<option value="accordion-template-8" <?php echo selected( 'accordion-template-8', $accordion_template ); ?>>
											<?php esc_html_e( 'Accordion Template 8', 'blog-designer-pro' ); ?>
										</option>
									</select>
									<div class="bdp-setting-description bdp-setting-accordion">
										<img class="accordion_template_images"src="<?php echo esc_attr( BLOGDESIGNERPRO_URL ) . '/admin/images/accordion/' . esc_attr( $accordion_template ) . '.png'; ?>">
									</div>
								</div>
							</li>
							<div class="bdp_grid_col_2">
								<li class="post_content_border_radius_setting">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Set Content Border Radius', 'blog-designer-pro' ); ?>
										</span><span class="bdp-px-prefix"><?php echo ' (px)'; ?></span>
									</div>
									<div class="bdp-right">
										<?php $content_button_border_radius = isset( $bdp_settings['content_button_border_radius'] ) ? $bdp_settings['content_button_border_radius'] : '0'; ?>
										<input type="number" id="content_button_border_radius" name="content_button_border_radius" step="1" min="0" value="<?php echo esc_attr( $content_button_border_radius ); ?>" onkeypress="return isNumberKey(event)">
									</div>
								</li>
								<li class="bdp_display_icon_tr">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Choose Icon', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<?php
										$bdp_dynamic_icon = 'plus_minus';
										if ( isset( $bdp_settings['bdp_dynamic_icon'] ) ) {
											$bdp_dynamic_icon = $bdp_settings['bdp_dynamic_icon'];
										}
										?>
										<div class="typo-field">
											<select name="bdp_dynamic_icon" id="bdp_dynamic_icon">
												<option value="plus_minus" <?php echo selected( 'plus_minus', $bdp_dynamic_icon ); ?>><?php esc_html_e( 'Plus - Minus Arrows', 'blog-designer-pro' ); ?>
												</option>
												<option value="single_chevron" <?php echo selected( 'single_chevron', $bdp_dynamic_icon ); ?>><?php esc_html_e( 'Single Arrows', 'blog-designer-pro' ); ?>
												</option>
												<option value="double_chevron" <?php echo selected( 'double_chevron', $bdp_dynamic_icon ); ?>><?php esc_html_e( 'Double Arrows', 'blog-designer-pro' ); ?>
												</option>
												<option value="hand_point" <?php echo selected( 'hand_point', $bdp_dynamic_icon ); ?>><?php esc_html_e( 'Hands Arrows', 'blog-designer-pro' ); ?>
												<option value="solid_arrow" <?php echo selected( 'solid_arrow', $bdp_dynamic_icon ); ?>><?php esc_html_e( 'Solid Arrows', 'blog-designer-pro' ); ?>

											</select>
										</div>
										<div class="bdp-setting-description bdp-icon-wrap">
											<?php
												echo '<div class="bdp-inside-icon-wrap plus_minus" ><span class="sol-acc-icon-left"><svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" viewBox="0 0 24 24"><path fill="currentColor" d="M18 12.998h-5v5a1 1 0 0 1-2 0v-5H6a1 1 0 0 1 0-2h5v-5a1 1 0 0 1 2 0v5h5a1 1 0 0 1 0 2z"/></svg></span><span class="sol-acc-icon-right"><svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" viewBox="0 0 24 24"><path fill="currentColor" d="M18 12.998H6a1 1 0 0 1 0-2h12a1 1 0 0 1 0 2z"/></svg><span></div>';
												echo '<div class="bdp-inside-icon-wrap single_chevron" ><span class="sol-acc-icon-left"><i class="fas fa-chevron-down"></i></span><span class="sol-acc-icon-right"><i class="fas fa-chevron-up"></i><span></div>';
												echo '<div class="bdp-inside-icon-wrap double_chevron" ><span class="sol-acc-icon-left"><i class="fas fa-angle-double-down"></i></span><span class="sol-acc-icon-right"><i class="fas fa-angle-double-up"></i><span></div>';
												echo '<div class="bdp-inside-icon-wrap hand_point" ><span class="sol-acc-icon-left"><i class="fas fa-hand-point-down"></i></span><span class="sol-acc-icon-right"><i class="fas fa-hand-point-up"></i><span></div>';
												echo '<div class="bdp-inside-icon-wrap solid_arrow" ><span class="sol-acc-icon-left"><i class="fas fa-arrow-down"></i></span><span class="sol-acc-icon-right"><i class="fas fa-arrow-up"></i><span></div>';

											?>
										</div>
									</div>
								</li>
								<li class="bdp_icon_color_tr blog-templatecolor-tr-inner">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Choose Icon Color', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<?php $template_icon_color = isset( $bdp_settings['template_icon_color'] ) ? $bdp_settings['template_icon_color'] : '#000000'; ?>
										<input type="text" name="template_icon_color" id="template_icon_color"
											value="<?php echo esc_attr( $template_icon_color ); ?>"
											data-default-color="<?php echo esc_attr( $template_icon_color ); ?>"/>
									</div>
								</li>
								<li class="bdp_icon_hover_color_tr blog-templatecolor-tr-inner">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Choose Icon Hover Color', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<?php $template_icon_hover_color = isset( $bdp_settings['template_icon_hover_color'] ) ? $bdp_settings['template_icon_hover_color'] : '#000000'; ?>
										<input type="text" name="template_icon_hover_color" id="template_icon_hover_color"
											value="<?php echo esc_attr( $template_icon_hover_color ); ?>"
											data-default-color="<?php echo esc_attr( $template_icon_hover_color ); ?>"/>
									</div>
								</li>

								<li class="bdp_icon_active_header_color_tr blog-templatecolor-tr-inner">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Choose Active Header Color', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<?php $template_icon_active_header_color = isset( $bdp_settings['template_icon_active_header_color'] ) ? $bdp_settings['template_icon_active_header_color'] : '#000000'; ?>
										<input type="text" name="template_icon_active_header_color" id="template_icon_active_header_color"
											value="<?php echo esc_attr( $template_icon_active_header_color ); ?>"
											data-default-color="<?php echo esc_attr( $template_icon_active_header_color ); ?>"/>
									</div>
								</li>

								<li class="bdp_icon_bg_color_tr blog-templatecolor-tr-inner">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Choose Icon Background Color', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<?php $template_icon_bgcolor = isset( $bdp_settings['template_icon_bgcolor'] ) ? $bdp_settings['template_icon_bgcolor'] : 'transparent'; ?>
										<input type="text" name="template_icon_bgcolor" id="template_icon_bgcolor"
											value="<?php echo esc_attr( $template_icon_bgcolor ); ?>"
											data-default-color="<?php echo esc_attr( $template_icon_bgcolor ); ?>"/>
									</div>
								</li>

								<li class="bdp_rep_icon_bg_color_1 blog-templatecolor-tr-inner">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Repetitive Icon Color 1', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<?php $repetative_icon_color1 = ( isset( $bdp_settings['repetative_icon_color1'] ) && ! empty( $bdp_settings['repetative_icon_color1'] ) ) ? $bdp_settings['repetative_icon_color1'] : ''; ?>
										<input type="text" name="repetative_icon_color1" id="repetative_icon_color1" value="<?php echo esc_attr( $repetative_icon_color1 ); ?>"/>
									</div>
								</li>

								<li class="bdp_rep_icon_bg_color_2 blog-templatecolor-tr-inner">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Repetitive Icon Color 2', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<?php $repetative_icon_color2 = ( isset( $bdp_settings['repetative_icon_color2'] ) && ! empty( $bdp_settings['repetative_icon_color2'] ) ) ? $bdp_settings['repetative_icon_color2'] : ''; ?>
										<input type="text" name="repetative_icon_color2" id="repetative_icon_color2" value="<?php echo esc_attr( $repetative_icon_color2 ); ?>"/>
									</div>
								</li>

								<li class="bdp_rep_icon_bg_color_3 blog-templatecolor-tr-inner">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Repetitive Icon Color 3', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<?php $repetative_icon_color3 = ( isset( $bdp_settings['repetative_icon_color3'] ) && ! empty( $bdp_settings['repetative_icon_color3'] ) ) ? $bdp_settings['repetative_icon_color3'] : ''; ?>
										<input type="text" name="repetative_icon_color3" id="repetative_icon_color3" value="<?php echo esc_attr( $repetative_icon_color3 ); ?>"/>
									</div>
								</li>
							</div>
							<div class="bdp_grid_col_3">
								<li class="bdp_icon_alignment_tr">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Choose Icon Alignment', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<?php
										$template_icon_alignment = 'icon-left';
										if ( isset( $bdp_settings['template_icon_alignment'] ) ) {
											$template_icon_alignment = $bdp_settings['template_icon_alignment'];
										}
										?>
										<fieldset class="buttonset green" data-hide='1'>
											<input id="template_icon_alignment_left" name="template_icon_alignment" type="radio" value="icon-left" <?php checked( 'icon-left', $template_icon_alignment ); ?> />
											<label id="bdp-options-button" for="template_icon_alignment_left"><?php esc_html_e( 'Left', 'blog-designer-pro' ); ?></label>
											<input id="template_icon_alignment_right" name="template_icon_alignment" type="radio" value="icon-right" <?php checked( 'icon-right', $template_icon_alignment ); ?> />
											<label id="bdp-options-button" for="template_icon_alignment_right"><?php esc_html_e( 'Right', 'blog-designer-pro' ); ?></label>
										</fieldset>
									</div>
								</li>

								<li class="icon_border_radius_setting">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Set Icon Border Radius', 'blog-designer-pro' ); ?>
										</span><span class="bdp-px-prefix"><?php echo '(px)'; ?></span>
									</div>
									<div class="bdp-right">
										<?php $icon_button_border_radius = isset( $bdp_settings['icon_button_border_radius'] ) ? $bdp_settings['icon_button_border_radius'] : '0'; ?>
										<input type="number" id="icon_button_border_radius" name="icon_button_border_radius" step="1" min="0" value="<?php echo esc_attr( $icon_button_border_radius ); ?>" onkeypress="return isNumberKey(event)">
									</div>
								</li>

								<li class="icon_font_size_setting">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Set Icon Font Size', 'blog-designer-pro' ); ?>
										</span><span class="bdp-px-prefix"><?php echo '(px)'; ?></span>
									</div>
									<div class="bdp-right">
										<?php $icon_fontsize = isset( $bdp_settings['icon_fontsize'] ) ? $bdp_settings['icon_fontsize'] : '0'; ?>
										<?php
										if ( isset( $bdp_settings['icon_fontsize'] ) && '' != $bdp_settings['icon_fontsize'] ) {
											$icon_fontsize = $bdp_settings['icon_fontsize'];
										} else {
											$icon_fontsize = 14;
										}
										?>
											<input type="number" class="grid_col_space_val" name="icon_fontsize" id="icon_fontsize" value="<?php echo esc_attr( $icon_fontsize ); ?>" onkeypress="return isNumberKey(event)" />
									</div>
								</li>
							</div>
							<li class="icon_padding_setting">
								<div class="bdp-full-width bdp-border-cover">
									<div class="bdp-padding-wrapper bdp-padding-wrapper1 bdp-border-wrap">
										<div class="bdp-padding-cover">
											<div class="bdp-padding-label">
												<span class="bdp-key-title">
													<?php esc_html_e( 'Left', 'blog-designer-pro' ); ?>
												</span><span class="bdp-px-prefix"><?php echo '(px)'; ?></span>
											</div>
											<div class="bdp-padding-content">
												<?php $icon_paddingleft = isset( $bdp_settings['icon_paddingleft'] ) ? $bdp_settings['icon_paddingleft'] : '0'; ?>
												<input type="number" id="icon_paddingleft" name="icon_paddingleft" step="1" min="0" value="<?php echo esc_attr( $icon_paddingleft ); ?>" onkeypress="return isNumberKey(event)">
											</div>
										</div>
										<div class="bdp-padding-cover">
											<div class="bdp-padding-label">
												<span class="bdp-key-title">
													<?php esc_html_e( 'Right', 'blog-designer-pro' ); ?>
												</span><span class="bdp-px-prefix"><?php echo '(px)'; ?></span>
											</div>
											<div class="bdp-padding-content">
												<?php $icon_paddingright = isset( $bdp_settings['icon_paddingright'] ) ? $bdp_settings['icon_paddingright'] : '0'; ?>
												<input type="number" id="icon_paddingright" name="icon_paddingright" step="1" min="0" value="<?php echo esc_attr( $icon_paddingright ); ?>" onkeypress="return isNumberKey(event)">
											</div>
										</div>
										<div class="bdp-padding-cover">
											<div class="bdp-padding-label">
												<span class="bdp-key-title">
													<?php esc_html_e( 'Top', 'blog-designer-pro' ); ?>
												</span><span class="bdp-px-prefix"><?php echo '(px)'; ?></span>
											</div>
											<div class="bdp-padding-content">
												<?php $icon_paddingtop = isset( $bdp_settings['icon_paddingtop'] ) ? $bdp_settings['icon_paddingtop'] : '0'; ?>
												<input type="number" id="icon_paddingtop" name="icon_paddingtop" step="1" min="0" value="<?php echo esc_attr( $icon_paddingtop ); ?>"  onkeypress="return isNumberKey(event)">
											</div>
										</div>
										<div class="bdp-padding-cover">
											<div class="bdp-padding-label">
												<span class="bdp-key-title">
													<?php esc_html_e( 'Bottom', 'blog-designer-pro' ); ?>
												</span><span class="bdp-px-prefix"><?php echo '(px)'; ?></span>
											</div>
											<div class="bdp-padding-content">
												<?php $icon_paddingbottom = isset( $bdp_settings['icon_paddingbottom'] ) ? $bdp_settings['icon_paddingbottom'] : '0'; ?>
												<input type="number" id="icon_paddingbottom" name="icon_paddingbottom" step="1" min="0" value="<?php echo esc_attr( $icon_paddingbottom ); ?>" onkeypress="return isNumberKey(event)">
											</div>
										</div>
									</div>
								</div>								
							</li>
							<h3 class="bdp-table-title bdp-table-sub-title"><?php esc_html_e( 'Normal Settings', 'blog-designer-pro' ); ?></h3>
							<li class="blog-templatecolor-tr-inner">
								<div class="bdp-left">
									<span class="bdp-key-title">
										<?php esc_html_e( 'Choose Link Color', 'blog-designer-pro' ); ?>
									</span>
								</div>
								<div class="bdp-right">
									<?php $template_ftcolor = isset( $bdp_settings['template_ftcolor'] ) ? $bdp_settings['template_ftcolor'] : ''; ?>
									<input type="text" name="template_ftcolor" id="template_ftcolor"
										value="<?php echo esc_attr( $template_ftcolor ); ?>"
										data-default-color="<?php echo esc_attr( $template_ftcolor ); ?>"/>
								</div>
							</li>
							<h3 class="bdp-table-title bdp-table-sub-title bdp-hover-setting"><?php esc_html_e( 'Hover Settings', 'blog-designer-pro' ); ?></h3>
							<div class="bdp_grid_col_1">
								<li class="link-hover-color-tr blog-templatecolor-tr-inner">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Choose Link Hover Color', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<?php $fthover = isset( $bdp_settings['template_fthovercolor'] ) ? $bdp_settings['template_fthovercolor'] : ''; ?>
										<input type="text" name="template_fthovercolor" id="template_fthovercolor"
											value="<?php echo esc_attr( $fthover ); ?>"
											data-default-color="<?php echo esc_attr( $fthover ); ?>"/>
									</div>
								</li>
								<li class="bdp-post-container-hover">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Hide Hover Effect on Blog post', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<?php $bdp_hide_hover_post = isset( $bdp_settings['bdp_hide_hover_post'] ) ? $bdp_settings['bdp_hide_hover_post'] : '0'; ?>
										<fieldset class="buttonset buttonset-hide" data-hide='1'>
											<input id="bdp_hide_hover_post_1" name="bdp_hide_hover_post" type="radio" value="1" <?php checked( 1, $bdp_hide_hover_post ); ?> />
											<label id="bdp-options-button" for="bdp_hide_hover_post_1" <?php checked( 1, $bdp_hide_hover_post ); ?>><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
											<input id="bdp_hide_hover_post_0" name="bdp_hide_hover_post" type="radio" value="0" <?php checked( 0, $bdp_hide_hover_post ); ?> />
											<label id="bdp-options-button" for="bdp_hide_hover_post_0" <?php checked( 0, $bdp_hide_hover_post ); ?>><?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
										</fieldset>
									</div>
								</li>
							</div>
							<h3 class="bdp-table-title bdp-table-sub-title bdp_print_page"><?php esc_html_e( 'Print page Settings', 'blog-designer-pro' ); ?></h3>
							<div class="bdp_grid_col_2">
								<li class="enable_print_page">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Enable Print Page?', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<?php

										$enable_print_page = isset( $bdp_settings['enable_print_page'] ) ? $bdp_settings['enable_print_page'] : '1';
										?>
										<fieldset class="buttonset buttonset-hide" data-hide='1'>
											<input id="enable_print_page_1" name="enable_print_page" type="radio" value="1" <?php checked( 1, $enable_print_page ); ?> />
											<label id="bdp-options-button" for="enable_print_page_1" <?php checked( 1, $enable_print_page ); ?>><?php esc_html_e( 'Enable', 'blog-designer-pro' ); ?></label>
											<input id="enable_print_page_0" name="enable_print_page" type="radio" value="0" <?php checked( 0, $enable_print_page ); ?> />
											<label id="bdp-options-button" for="enable_print_page_0" <?php checked( 0, $enable_print_page ); ?>><?php esc_html_e( 'Disable', 'blog-designer-pro' ); ?></label>
										</fieldset>
									</div>
								</li>
								<li class="bdp-print-page">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Print Page Display Name', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<?php $txt_print_page = ( isset( $bdp_settings['txt_print_page'] ) && '' != $bdp_settings['txt_print_page'] ) ? $bdp_settings['txt_print_page'] : ''; ?>
										<input type="text" name="txt_print_page" id="txt_print_page" value="<?php echo esc_attr( $txt_print_page ); ?>" placeholder="<?php esc_attr_e( 'Enter Print Page name', 'blog-designer-pro' ); ?>">
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
											<?php esc_html_e( 'Author Box Background Color', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<input type="text" name="author_bgcolor" id="author_bgcolor" value="<?php echo isset( $bdp_settings['author_bgcolor'] ) ? esc_attr( $bdp_settings['author_bgcolor'] ) : ''; ?>"/>
									</div>
								</li>
								<li class="display_author_biography_div">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Display Author Title', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<input type="text" id="txtAuthorTitle" name="txtAuthorTitle" value="<?php echo isset( $bdp_settings['txtAuthorTitle'] ) ? esc_html( $bdp_settings['txtAuthorTitle'] ) : esc_html__( 'About', 'blog-designer-pro' ) . ' [author]'; ?>" placeholder="<?php esc_html_e( 'About', 'blog-designer-pro' ); ?> [author] ">
										<div class="bdp-setting-description bdp-note">
											<b class="note"><?php esc_html_e( 'Note', 'blog-designer-pro' ); ?>: </b>
											<?php
											esc_html_e( 'Use', 'blog-designer-pro' );
											echo ' [author] ';
											esc_html_e( 'to display author name dynamically', 'blog-designer-pro' );
											?>
									</div>
									</div>
								</li>
								<li class="display_author_biography_div">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Author Title Color', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<input type="text" name="author_titlecolor" id="author_titlecolor" value="<?php echo isset( $bdp_settings['author_titlecolor'] ) ? esc_attr( $bdp_settings['author_titlecolor'] ) : ''; ?>"/>
									</div>
								</li>
							</div>
							<h3 class="bdp-table-title display_author_biography_div"><?php esc_html_e( 'Typography Settings for Author Title', 'blog-designer-pro' ); ?></h3>
							<li>
								<div class="bdp-typography-wrapper bdp-typography-wrapper1 display_author_biography_div bdp_grid_col_4">
									<div class="bdp-typography-cover">
										<div class="bdp-typography-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Font Family', 'blog-designer-pro' ); ?>
											</span>
										</div>
										<div class="bdp-typography-content">
											<?php $author_title_fontface = isset( $bdp_settings['author_title_fontface'] ) ? $bdp_settings['author_title_fontface'] : 'Georgia, serif'; ?>

											<div class="typo-field">
												<input type="hidden" name="author_title_fontface_font_type" id="author_title_fontface_font_type" value="<?php echo isset( $bdp_settings['author_title_fontface_font_type'] ) ? esc_attr( $bdp_settings['author_title_fontface_font_type'] ) : ''; ?>">
												<div class="select-cover">
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
											</span><span class="bdp-px-prefix"><?php esc_html_e( '(px)', 'blog-designer-pro' ); ?></span>
										</div>
										<div class="bdp-typography-content">
											<?php
											if ( isset( $bdp_settings['author_title_fontsize'] ) ) {
												$author_title_fontsize = $bdp_settings['author_title_fontsize'];
											} else {
												$author_title_fontsize = 16;
											}
											?>
											<input type="number" class="grid_col_space_val" name="author_title_fontsize" id="author_title_fontsize" value="<?php echo esc_attr( $author_title_fontsize ); ?>" onkeypress="return isNumberKey(event)" />
										</div>
									</div>

									<div class="bdp-typography-cover">
										<div class="bdp-typography-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Font Weight', 'blog-designer-pro' ); ?>
											</span>
										</div>
										<div class="bdp-typography-content">
											<?php $author_title_font_weight = isset( $bdp_settings['author_title_font_weight'] ) ? $bdp_settings['author_title_font_weight'] : 'normal'; ?>
											<div class="select-cover">
												<select name="author_title_font_weight" id="author_title_font_weight">
													<option value="100" <?php selected( $author_title_font_weight, 100 ); ?>>100</option>
													<option value="200" <?php selected( $author_title_font_weight, 200 ); ?>>200</option>
													<option value="300" <?php selected( $author_title_font_weight, 300 ); ?>>300</option>
													<option value="400" <?php selected( $author_title_font_weight, 400 ); ?>>400</option>
													<option value="500" <?php selected( $author_title_font_weight, 500 ); ?>>500</option>
													<option value="600" <?php selected( $author_title_font_weight, 600 ); ?>>600</option>
													<option value="700" <?php selected( $author_title_font_weight, 700 ); ?>>700</option>
													<option value="800" <?php selected( $author_title_font_weight, 800 ); ?>>800</option>
													<option value="900" <?php selected( $author_title_font_weight, 900 ); ?>>900</option>
													<option value="bold" <?php selected( $author_title_font_weight, 'bold' ); ?> ><?php esc_html_e( 'Bold', 'blog-designer-pro' ); ?></option>
													<option value="normal" <?php selected( $author_title_font_weight, 'normal' ); ?>><?php esc_html_e( 'Normal', 'blog-designer-pro' ); ?></option>
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
												<input type="number" name="author_title_font_line_height" id="author_title_font_line_height" step="0.1" min="0" value="<?php echo isset( $bdp_settings['author_title_font_line_height'] ) ? esc_attr( $bdp_settings['author_title_font_line_height'] ) : '1.5'; ?>" onkeypress="return isNumberKey(event)">
										</div>
									</div>

									<div class="bdp-typography-cover">
										<div class="bdp-typography-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Text Transform', 'blog-designer-pro' ); ?>
											</span>
										</div>
										<div class="bdp-typography-content">
											<?php $author_title_font_text_transform = isset( $bdp_settings['author_title_font_text_transform'] ) ? $bdp_settings['author_title_font_text_transform'] : 'none'; ?>
											<div class="select-cover">
												<select name="author_title_font_text_transform" id="author_title_font_text_transform">
													<option <?php selected( $author_title_font_text_transform, 'none' ); ?> value="none"><?php esc_html_e( 'None', 'blog-designer-pro' ); ?></option>
													<option <?php selected( $author_title_font_text_transform, 'capitalize' ); ?> value="capitalize"><?php esc_html_e( 'Capitalize', 'blog-designer-pro' ); ?></option>
													<option <?php selected( $author_title_font_text_transform, 'uppercase' ); ?> value="uppercase"><?php esc_html_e( 'Uppercase', 'blog-designer-pro' ); ?></option>
													<option <?php selected( $author_title_font_text_transform, 'lowercase' ); ?> value="lowercase"><?php esc_html_e( 'Lowercase', 'blog-designer-pro' ); ?></option>
													<option <?php selected( $author_title_font_text_transform, 'full-width' ); ?> value="full-width"><?php esc_html_e( 'Full Width', 'blog-designer-pro' ); ?></option>
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
											<?php $author_title_font_text_decoration = isset( $bdp_settings['author_title_font_text_decoration'] ) ? $bdp_settings['author_title_font_text_decoration'] : 'none'; ?>
											<div class="select-cover">
												<select name="author_title_font_text_decoration" id="author_title_font_text_decoration">
													<option <?php selected( $author_title_font_text_decoration, 'none' ); ?> value="none"><?php esc_html_e( 'None', 'blog-designer-pro' ); ?></option>
													<option <?php selected( $author_title_font_text_decoration, 'underline' ); ?> value="underline"><?php esc_html_e( 'Underline', 'blog-designer-pro' ); ?></option>
													<option <?php selected( $author_title_font_text_decoration, 'overline' ); ?> value="overline"><?php esc_html_e( 'Overline', 'blog-designer-pro' ); ?></option>
													<option <?php selected( $author_title_font_text_decoration, 'line-through' ); ?> value="line-through"><?php esc_html_e( 'Line Through', 'blog-designer-pro' ); ?></option>
												</select>
											</div>
										</div>
									</div>

									<div class="bdp-typography-cover">
										<div class="bdp-typography-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Letter Spacing', 'blog-designer-pro' ); ?>
											</span><span class="bdp-px-prefix"><?php esc_html_e( '(px)', 'blog-designer-pro' ); ?></span>
										</div>
										<div class="bdp-typography-content">
												<input type="number" name="auhtor_title_font_letter_spacing" id="auhtor_title_font_letter_spacing" step="1" min="0" value="<?php echo isset( $bdp_settings['auhtor_title_font_letter_spacing'] ) ? esc_attr( $bdp_settings['auhtor_title_font_letter_spacing'] ) : '0'; ?>" onkeypress="return isNumberKey(event)">
										</div>
									</div>
									<div class="bdp-typography-cover">
										<div class="bdp-typography-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Italic Font Style', 'blog-designer-pro' ); ?>
											</span>
										</div>
										<div class="bdp-typography-content">
											<?php $auhtor_title_font_italic = isset( $bdp_settings['auhtor_title_font_italic'] ) ? $bdp_settings['auhtor_title_font_italic'] : '0'; ?>
											<fieldset class="bdp-social-options bdp-display_author buttonset">
												<input id="auhtor_title_font_italic_1" name="auhtor_title_font_italic" type="radio" value="1"  <?php checked( 1, $auhtor_title_font_italic ); ?> />
												<label for="auhtor_title_font_italic_1"><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
												<input id="auhtor_title_font_italic_0" name="auhtor_title_font_italic" type="radio" value="0" <?php checked( 0, $auhtor_title_font_italic ); ?> />
												<label for="auhtor_title_font_italic_0"><?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
											</fieldset>
										</div>
									</div>
								</div>
							</li>
							<div class="bdp_grid_col_2">
								<li class="display_author_biography_div">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Display Author Biography', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<fieldset class="bdp-social-options bdp-display_author buttonset">
											<input id="display_author_biography_1" name="display_author_biography" type="radio" value="1"  <?php checked( 1, $display_author_biography ); ?> />
											<label for="display_author_biography_1"><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
											<input id="display_author_biography_0" name="display_author_biography" type="radio" value="0" <?php checked( 0, $display_author_biography ); ?> />
											<label for="display_author_biography_0"><?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
										</fieldset>
									</div>
								</li>

								<li class="display_author_biography_div author_biography_div">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Author Content Color', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<input type="text" name="author_content_color" id="author_content_color" value="<?php echo isset( $bdp_settings['author_content_color'] ) ? esc_attr( $bdp_settings['author_content_color'] ) : ''; ?>"/>
									</div>
								</li>
							</div>
							<h3 class="bdp-table-title display_author_biography_div author_biography_div"><?php esc_html_e( 'Typography Settings for Author Content', 'blog-designer-pro' ); ?></h3>
							<li class="display_author_biography_div author_biography_div">
								<div class="bdp-typography-wrapper bdp-typography-wrapper1 display_author_biography_div author_biography_div bdp_grid_col_4">
									<div class="bdp-typography-cover">
										<div class="bdp-typography-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Font Family', 'blog-designer-pro' ); ?>
											</span>
										</div>
										<div class="bdp-typography-content">
											<?php $author_content_fontface = isset( $bdp_settings['author_content_fontface'] ) ? $bdp_settings['author_content_fontface'] : 'Georgia, serif'; ?>
											<div class="typo-field">
												<input type="hidden" name="author_content_fontface_font_type" id="author_content_fontface_font_type" value="<?php echo isset( $bdp_settings['author_content_fontface_font_type'] ) ? esc_attr( $bdp_settings['author_content_fontface_font_type'] ) : ''; ?>">
												<div class="select-cover">
													<select name="author_content_fontface" id="author_content_fontface">
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

															if ( '' != $author_content_fontface && ( str_replace( '"', '', $author_content_fontface ) == str_replace( '"', '', $value['label'] ) ) ) {
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
											<span class="bdp-key-title"><?php esc_html_e( 'Font Size', 'blog-designer-pro' ); ?></span>
											<span class="bdp-px-prefix"><?php esc_html_e( '(px)', 'blog-designer-pro' ); ?></span>
										</div>
										<div class="bdp-typography-content">
											<?php
											if ( isset( $bdp_settings['author_content_fontsize'] ) ) {
												$author_content_fontsize = $bdp_settings['author_content_fontsize'];
											} else {
												$author_content_fontsize = 16;
											}
											?>
											<input type="number" class="grid_col_space_val" name="author_content_fontsize" id="author_content_fontsize" value="<?php echo esc_attr( $author_content_fontsize ); ?>" onkeypress="return isNumberKey(event)" />
										</div>
									</div>

									<div class="bdp-typography-cover">
										<div class="bdp-typography-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Font Weight', 'blog-designer-pro' ); ?>
											</span>
										</div>
										<div class="bdp-typography-content">
											<?php $author_content_font_weight = isset( $bdp_settings['author_content_font_weight'] ) ? $bdp_settings['author_content_font_weight'] : 'normal'; ?>
											<div class="select-cover">
												<select name="author_content_font_weight" id="author_content_font_weight">
													<option value="100" <?php selected( $author_content_font_weight, 100 ); ?>>100</option>
													<option value="200" <?php selected( $author_content_font_weight, 200 ); ?>>200</option>
													<option value="300" <?php selected( $author_content_font_weight, 300 ); ?>>300</option>
													<option value="400" <?php selected( $author_content_font_weight, 400 ); ?>>400</option>
													<option value="500" <?php selected( $author_content_font_weight, 500 ); ?>>500</option>
													<option value="600" <?php selected( $author_content_font_weight, 600 ); ?>>600</option>
													<option value="700" <?php selected( $author_content_font_weight, 700 ); ?>>700</option>
													<option value="800" <?php selected( $author_content_font_weight, 800 ); ?>>800</option>
													<option value="900" <?php selected( $author_content_font_weight, 900 ); ?>>900</option>
													<option value="bold" <?php selected( $author_content_font_weight, 'bold' ); ?> ><?php esc_html_e( 'Bold', 'blog-designer-pro' ); ?></option>
													<option value="normal" <?php selected( $author_content_font_weight, 'normal' ); ?>><?php esc_html_e( 'Normal', 'blog-designer-pro' ); ?></option>
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
											<input type="number" name="author_content_font_line_height" id="author_content_font_line_height" step="0.1" min="0" value="<?php echo isset( $bdp_settings['author_content_font_line_height'] ) ? esc_attr( $bdp_settings['author_content_font_line_height'] ) : '1.5'; ?>" onkeypress="return isNumberKey(event)">
										</div>
									</div>

									<div class="bdp-typography-cover">
										<div class="bdp-typography-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Italic Font Style', 'blog-designer-pro' ); ?>
											</span>
										</div>
										<div class="bdp-typography-content">
											<?php $auhtor_content_font_italic = isset( $bdp_settings['auhtor_content_font_italic'] ) ? $bdp_settings['auhtor_content_font_italic'] : '0'; ?>
											<fieldset class="bdp-social-options bdp-display_author buttonset">
												<input id="auhtor_content_font_italic_1" name="auhtor_content_font_italic" type="radio" value="1"  <?php checked( 1, $auhtor_content_font_italic ); ?> />
												<label for="auhtor_content_font_italic_1"><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
												<input id="auhtor_content_font_italic_0" name="auhtor_content_font_italic" type="radio" value="0" <?php checked( 0, $auhtor_content_font_italic ); ?> />
												<label for="auhtor_content_font_italic_0"><?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
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
											<?php $author_content_font_text_transform = isset( $bdp_settings['author_content_font_text_transform'] ) ? $bdp_settings['author_content_font_text_transform'] : 'none'; ?>
											<div class="select-cover">
												<select name="author_content_font_text_transform" id="author_content_font_text_transform">
													<option <?php selected( $author_content_font_text_transform, 'none' ); ?> value="none"><?php esc_html_e( 'None', 'blog-designer-pro' ); ?></option>
													<option <?php selected( $author_content_font_text_transform, 'capitalize' ); ?> value="capitalize"><?php esc_html_e( 'Capitalize', 'blog-designer-pro' ); ?></option>
													<option <?php selected( $author_content_font_text_transform, 'uppercase' ); ?> value="uppercase"><?php esc_html_e( 'Uppercase', 'blog-designer-pro' ); ?></option>
													<option <?php selected( $author_content_font_text_transform, 'lowercase' ); ?> value="lowercase"><?php esc_html_e( 'Lowercase', 'blog-designer-pro' ); ?></option>
													<option <?php selected( $author_content_font_text_transform, 'full-width' ); ?> value="full-width"><?php esc_html_e( 'Full Width', 'blog-designer-pro' ); ?></option>
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
											<?php $author_content_font_text_decoration = isset( $bdp_settings['author_content_font_text_decoration'] ) ? $bdp_settings['author_content_font_text_decoration'] : 'none'; ?>
											<div class="select-cover">
												<select name="author_content_font_text_decoration" id="author_content_font_text_decoration">
													<option <?php selected( $author_content_font_text_decoration, 'none' ); ?> value="none"><?php esc_html_e( 'None', 'blog-designer-pro' ); ?></option>
													<option <?php selected( $author_content_font_text_decoration, 'underline' ); ?> value="underline"><?php esc_html_e( 'Underline', 'blog-designer-pro' ); ?></option>
													<option <?php selected( $author_content_font_text_decoration, 'overline' ); ?> value="overline"><?php esc_html_e( 'Overline', 'blog-designer-pro' ); ?></option>
													<option <?php selected( $author_content_font_text_decoration, 'line-through' ); ?> value="line-through"><?php esc_html_e( 'Line Through', 'blog-designer-pro' ); ?></option>
												</select>
											</div>
										</div>
									</div>

									<div class="bdp-typography-cover">
										<div class="bdp-typography-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Letter Spacing (px)', 'blog-designer-pro' ); ?>
											</span>
										</div>
										<div class="bdp-typography-content">
											<input type="number" name="auhtor_content_font_letter_spacing" id="auhtor_content_font_letter_spacing" step="1" min="0" value="<?php echo isset( $bdp_settings['auhtor_content_font_letter_spacing'] ) ? esc_attr( $bdp_settings['auhtor_content_font_letter_spacing'] ) : '0'; ?>" onkeypress="return isNumberKey(event)">
										</div>
									</div>
								</div>
							</li>

							<li class="display_author_biography_div">
								<div class="bdp-left">
									<span class="bdp-key-title">
										<?php esc_html_e( 'Display Author Social Links', 'blog-designer-pro' ); ?>
									</span>
								</div>
								<div class="bdp-right">
									<fieldset class="bdp-social-options bdp-display_author buttonset">
										<input id="display_author_social_1" name="display_author_social" type="radio" value="1"  <?php checked( 1, $display_author_social ); ?> />
										<label for="display_author_social_1"><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
										<input id="display_author_social_0" name="display_author_social" type="radio" value="0" <?php checked( 0, $display_author_social ); ?> />
										<label for="display_author_social_0"><?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
									</fieldset>
									<div class="bdp-setting-description bdp-note">
										<b class="note"><?php esc_html_e( 'Note', 'blog-designer-pro' ); ?>:</b>
										<?php esc_html_e( 'You can set social links from', 'blog-designer-pro' ); ?>&nbsp; <a href="<?php echo esc_attr( get_edit_user_link() ); ?>" target="_blank" ><?php esc_html_e( 'author profile page', 'blog-designer-pro' ); ?>.</a>
									</div>

								</div>
							</li>
							<li class="display_author_biography_div display_author_social_div bdp-display-settings bdp-social-share-options">
								<div class="bdp-typography-wrapper bdp_grid_col_3">
									<div class="bdp-typography-cover">
										<div class="bdp-typography-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Email Link', 'blog-designer-pro' ); ?>
											</span>
										</div>
										<div class="bdp-typography-content">
											<?php $author_email_link = isset( $bdp_settings['author_email_link'] ) ? $bdp_settings['author_email_link'] : 0; ?>
											<fieldset class="bdp-social-options bdp-author_email_link buttonset buttonset-hide" data-hide='1'>
												<input id="author_email_link_1" name="author_email_link" type="radio" value="1" <?php checked( 1, $author_email_link ); ?> />
												<label id=""for="author_email_link_1" <?php checked( 1, $author_email_link ); ?>><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
												<input id="author_email_link_0" name="author_email_link" type="radio" value="0" <?php checked( 0, $author_email_link ); ?> />
												<label id="" for="author_email_link_0" <?php checked( 0, $author_email_link ); ?>> <?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
											</fieldset>
										</div>
									</div>

									<div class="bdp-typography-cover">
										<div class="bdp-typography-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Website Link', 'blog-designer-pro' ); ?>
											</span>
										</div>
										<div class="bdp-typography-content">
											<?php $author_website_link = isset( $bdp_settings['author_website_link'] ) ? $bdp_settings['author_website_link'] : 1; ?>
											<fieldset class="bdp-social-options bdp-author_website_link buttonset buttonset-hide" data-hide='1'>
												<input id="author_website_link_1" name="author_website_link" type="radio" value="1" <?php checked( 1, $author_website_link ); ?> />
												<label id=""for="author_website_link_1" <?php checked( 1, $author_website_link ); ?>><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
												<input id="author_website_link_0" name="author_website_link" type="radio" value="0" <?php checked( 0, $author_website_link ); ?> />
												<label id="" for="author_website_link_0" <?php checked( 0, $author_website_link ); ?>> <?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
											</fieldset>
										</div>
									</div>

									<div class="bdp-typography-cover">
										<div class="bdp-typography-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Facebook Link', 'blog-designer-pro' ); ?>
											</span>
										</div>
										<div class="bdp-typography-content">
											<?php $author_facebook_link = isset( $bdp_settings['author_facebook_link'] ) ? $bdp_settings['author_facebook_link'] : 1; ?>
											<fieldset class="bdp-social-options bdp-author_facebook_link buttonset buttonset-hide" data-hide='1'>
												<input id="author_facebook_link_1" name="author_facebook_link" type="radio" value="1" <?php checked( 1, $author_facebook_link ); ?> />
												<label id=""for="author_facebook_link_1" <?php checked( 1, $author_facebook_link ); ?>><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
												<input id="author_facebook_link_0" name="author_facebook_link" type="radio" value="0" <?php checked( 0, $author_facebook_link ); ?> />
												<label id="" for="author_facebook_link_0" <?php checked( 0, $author_facebook_link ); ?>> <?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
											</fieldset>
										</div>
									</div>

									<div class="bdp-typography-cover">
										<div class="bdp-typography-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Twitter Link', 'blog-designer-pro' ); ?>
											</span>
										</div>
										<div class="bdp-typography-content">
											<?php $author_twitter_link = isset( $bdp_settings['author_twitter_link'] ) ? $bdp_settings['author_twitter_link'] : 1; ?>
											<fieldset class="bdp-social-options bdp-author_twitter_link buttonset buttonset-hide" data-hide='1'>
												<input id="author_twitter_link_1" name="author_twitter_link" type="radio" value="1" <?php checked( 1, $author_twitter_link ); ?> />
												<label id=""for="author_twitter_link_1" <?php checked( 1, $author_twitter_link ); ?>><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
												<input id="author_twitter_link_0" name="author_twitter_link" type="radio" value="0" <?php checked( 0, $author_twitter_link ); ?> />
												<label id="" for="author_twitter_link_0" <?php checked( 0, $author_twitter_link ); ?>> <?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
											</fieldset>
										</div>
									</div>

									<div class="bdp-typography-cover">
										<div class="bdp-typography-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'LinkedIn Link', 'blog-designer-pro' ); ?>
											</span>
										</div>
										<div class="bdp-typography-content">
											<?php $author_linkedin_link = isset( $bdp_settings['author_linkedin_link'] ) ? $bdp_settings['author_linkedin_link'] : 1; ?>
											<fieldset class="bdp-social-options bdp-author_linkedin_link buttonset buttonset-hide" data-hide='1'>
												<input id="author_linkedin_link_1" name="author_linkedin_link" type="radio" value="1" <?php checked( 1, $author_linkedin_link ); ?> />
												<label id=""for="author_linkedin_link_1" <?php checked( 1, $author_linkedin_link ); ?>><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
												<input id="author_linkedin_link_0" name="author_linkedin_link" type="radio" value="0" <?php checked( 0, $author_linkedin_link ); ?> />
												<label id="" for="author_linkedin_link_0" <?php checked( 0, $author_linkedin_link ); ?>> <?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
											</fieldset>
										</div>
									</div>

									<div class="bdp-typography-cover">
										<div class="bdp-typography-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'YouTube Link', 'blog-designer-pro' ); ?>
											</span>
										</div>
										<div class="bdp-typography-content">
											<?php $author_youtube_link = isset( $bdp_settings['author_youtube_link'] ) ? $bdp_settings['author_youtube_link'] : 1; ?>
											<fieldset class="bdp-social-options bdp-author_youtube_link buttonset buttonset-hide" data-hide='1'>
												<input id="author_youtube_link_1" name="author_youtube_link" type="radio" value="1" <?php checked( 1, $author_youtube_link ); ?> />
												<label id=""for="author_youtube_link_1" <?php checked( 1, $author_youtube_link ); ?>><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
												<input id="author_youtube_link_0" name="author_youtube_link" type="radio" value="0" <?php checked( 0, $author_youtube_link ); ?> />
												<label id="" for="author_youtube_link_0" <?php checked( 0, $author_youtube_link ); ?>> <?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
											</fieldset>
										</div>
									</div>

									<div class="bdp-typography-cover">
										<div class="bdp-typography-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Pinterest Link', 'blog-designer-pro' ); ?>
											</span>
										</div>
										<div class="bdp-typography-content">
											<?php $author_pinterest_link = isset( $bdp_settings['author_pinterest_link'] ) ? $bdp_settings['author_pinterest_link'] : 1; ?>
											<fieldset class="bdp-social-options bdp-author_pinterest_link buttonset buttonset-hide" data-hide='1'>
												<input id="author_pinterest_link_1" name="author_pinterest_link" type="radio" value="1" <?php checked( 1, $author_pinterest_link ); ?> />
												<label id=""for="author_pinterest_link_1" <?php checked( 1, $author_pinterest_link ); ?>><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
												<input id="author_pinterest_link_0" name="author_pinterest_link" type="radio" value="0" <?php checked( 0, $author_pinterest_link ); ?> />
												<label id="" for="author_pinterest_link_0" <?php checked( 0, $author_pinterest_link ); ?>> <?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
											</fieldset>
										</div>
									</div>

									<div class="bdp-typography-cover">
										<div class="bdp-typography-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Instagram Link', 'blog-designer-pro' ); ?>
											</span>
										</div>
										<div class="bdp-typography-content">
											<?php $author_instagram_link = isset( $bdp_settings['author_instagram_link'] ) ? $bdp_settings['author_instagram_link'] : 1; ?>
											<fieldset class="bdp-social-options bdp-author_instagram_link buttonset buttonset-hide" data-hide='1'>
												<input id="author_instagram_link_1" name="author_instagram_link" type="radio" value="1" <?php checked( 1, $author_instagram_link ); ?> />
												<label id=""for="author_instagram_link_1" <?php checked( 1, $author_instagram_link ); ?>><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
												<input id="author_instagram_link_0" name="author_instagram_link" type="radio" value="0" <?php checked( 0, $author_instagram_link ); ?> />
												<label id="" for="author_instagram_link_0" <?php checked( 0, $author_instagram_link ); ?>> <?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
											</fieldset>
										</div>
									</div>

									<div class="bdp-typography-cover">
										<div class="bdp-typography-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Reddit Link', 'blog-designer-pro' ); ?>
											</span>
										</div>
										<div class="bdp-typography-content">
											<?php $author_reddit_link = isset( $bdp_settings['author_reddit_link'] ) ? $bdp_settings['author_reddit_link'] : 0; ?>
											<fieldset class="bdp-social-options bdp-author_reddit_link buttonset buttonset-hide" data-hide='1'>
												<input id="author_reddit_link_1" name="author_reddit_link" type="radio" value="1" <?php checked( 1, $author_reddit_link ); ?> />
												<label id=""for="author_reddit_link_1" <?php checked( 1, $author_reddit_link ); ?>><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
												<input id="author_reddit_link_0" name="author_reddit_link" type="radio" value="0" <?php checked( 0, $author_reddit_link ); ?> />
												<label id="" for="author_reddit_link_0" <?php checked( 0, $author_reddit_link ); ?>> <?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
											</fieldset>
										</div>
									</div>

									<div class="bdp-typography-cover">
										<div class="bdp-typography-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Pocket Link', 'blog-designer-pro' ); ?>
											</span>
										</div>
										<div class="bdp-typography-content">
											<?php $author_pocket_link = isset( $bdp_settings['author_pocket_link'] ) ? $bdp_settings['author_pocket_link'] : 0; ?>
											<fieldset class="bdp-social-options bdp-author_pocket_link buttonset buttonset-hide" data-hide='1'>
												<input id="author_pocket_link_1" name="author_pocket_link" type="radio" value="1" <?php checked( 1, $author_pocket_link ); ?> />
												<label id=""for="author_pocket_link_1" <?php checked( 1, $author_pocket_link ); ?>><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
												<input id="author_pocket_link_0" name="author_pocket_link" type="radio" value="0" <?php checked( 0, $author_pocket_link ); ?> />
												<label id="" for="author_pocket_link_0" <?php checked( 0, $author_pocket_link ); ?>> <?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
											</fieldset>
										</div>
									</div>

									<div class="bdp-typography-cover">
										<div class="bdp-typography-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Skype Link', 'blog-designer-pro' ); ?>
											</span>
										</div>
										<div class="bdp-typography-content">
											<?php $author_skype_link = isset( $bdp_settings['author_skype_link'] ) ? $bdp_settings['author_skype_link'] : 0; ?>
											<fieldset class="bdp-social-options bdp-author_skype_link buttonset buttonset-hide" data-hide='1'>
												<input id="author_skype_link_1" name="author_skype_link" type="radio" value="1" <?php checked( 1, $author_skype_link ); ?> />
												<label id=""for="author_skype_link_1" <?php checked( 1, $author_skype_link ); ?>><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
												<input id="author_skype_link_0" name="author_skype_link" type="radio" value="0" <?php checked( 0, $author_skype_link ); ?> />
												<label id="" for="author_skype_link_0" <?php checked( 0, $author_skype_link ); ?>> <?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
											</fieldset>
										</div>
									</div>

									<div class="bdp-typography-cover">
										<div class="bdp-typography-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'WordPress Link', 'blog-designer-pro' ); ?>
											</span>
										</div>
										<div class="bdp-typography-content">
											<?php $author_wordpress_link = isset( $bdp_settings['author_wordpress_link'] ) ? $bdp_settings['author_wordpress_link'] : 0; ?>
											<fieldset class="bdp-social-options bdp-author_wordpress_link buttonset buttonset-hide" data-hide='1'>
												<input id="author_wordpress_link_1" name="author_wordpress_link" type="radio" value="1" <?php checked( 1, $author_wordpress_link ); ?> />
												<label id=""for="author_wordpress_link_1" <?php checked( 1, $author_wordpress_link ); ?>><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
												<input id="author_wordpress_link_0" name="author_wordpress_link" type="radio" value="0" <?php checked( 0, $author_wordpress_link ); ?> />
												<label id="" for="author_wordpress_link_0" <?php checked( 0, $author_wordpress_link ); ?>> <?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
											</fieldset>
										</div>
									</div>
									<div class="bdp-typography-cover">
										<div class="bdp-typography-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Vine Link', 'blog-designer-pro' ); ?>
											</span>
										</div>
										<div class="bdp-typography-content">
											<?php $author_vine_link = isset( $bdp_settings['author_vine_link'] ) ? $bdp_settings['author_vine_link'] : 0; ?>
											<fieldset class="bdp-social-options bdp-author_vine_link buttonset buttonset-hide" data-hide='1'>
												<input id="author_vine_link_1" name="author_vine_link" type="radio" value="1" <?php checked( 1, $author_vine_link ); ?> />
												<label id=""for="author_vine_link_1" <?php checked( 1, $author_vine_link ); ?>><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
												<input id="author_vine_link_0" name="author_vine_link" type="radio" value="0" <?php checked( 0, $author_vine_link ); ?> />
												<label id="" for="author_vine_link_0" <?php checked( 0, $author_vine_link ); ?>> <?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
											</fieldset>
										</div>
									</div>

									<div class="bdp-typography-cover">
										<div class="bdp-typography-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Tumblr Link', 'blog-designer-pro' ); ?>
											</span>
										</div>
										<div class="bdp-typography-content">
											<?php $author_tumblr_link = isset( $bdp_settings['author_tumblr_link'] ) ? $bdp_settings['author_tumblr_link'] : 0; ?>
											<fieldset class="bdp-social-options bdp-author_tumblr_link buttonset buttonset-hide" data-hide='1'>
												<input id="author_tumblr_link_1" name="author_tumblr_link" type="radio" value="1" <?php checked( 1, $author_tumblr_link ); ?> />
												<label id=""for="author_tumblr_link_1" <?php checked( 1, $author_tumblr_link ); ?>><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
												<input id="author_tumblr_link_0" name="author_tumblr_link" type="radio" value="0" <?php checked( 0, $author_tumblr_link ); ?> />
												<label id="" for="author_tumblr_link_0" <?php checked( 0, $author_tumblr_link ); ?>> <?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
											</fieldset>
										</div>
									</div>

								</div>
							</li>
						</ul>
					</div>
				</div>
				<div id="bdptitle" class="postbox postbox-with-fw-options" style=<?php echo esc_attr( $bdptitle_class_show ); ?>>
					<div class="inside">
						<ul class="bdp-settings bdp-lineheight">
							<div class="bdp_grid_col_2">
								<li class="posts_title_links">
									<div class="bdp-left"><span class="bdp-key-title"><?php esc_html_e( 'Post Title Link', 'blog-designer-pro' ); ?></span></div>
									<div class="bdp-right">
										<?php $bdp_post_title_link = isset( $bdp_settings['bdp_post_title_link'] ) ? $bdp_settings['bdp_post_title_link'] : '1'; ?>
										<fieldset class="buttonset buttonset-hide" data-hide='1'>
											<input id="bdp_post_title_link_1" name="bdp_post_title_link" type="radio" value="1" <?php checked( 1, $bdp_post_title_link ); ?> />
											<label id="bdp-options-button" for="bdp_post_title_link_1" <?php checked( 1, $bdp_post_title_link ); ?>><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
											<input id="bdp_post_title_link_0" name="bdp_post_title_link" type="radio" value="0" <?php checked( 0, $bdp_post_title_link ); ?> />
											<label id="bdp-options-button" for="bdp_post_title_link_0" <?php checked( 0, $bdp_post_title_link ); ?>><?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
										</fieldset>
									</div>
								</li>
								<!-- Post Title setting -->
									<li class="post_length_setting">
										<div class="bdp-left">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Post Tile Maximum Line', 'blog-designer' ); ?>
											</span>
										</div>
										<div class="bdp-right">
											<fieldset class="buttonset">
												<?php $post_length_setting = isset( $bdp_settings['post_length_setting'] ) ? $bdp_settings['post_length_setting'] : '0'; ?>
												<input id="post_length_setting_1" name="post_length_setting" type="radio" value="1" <?php checked( '1', $post_length_setting ); ?>/>
												<label for="post_length_setting_1"><?php esc_html_e( 'Yes', 'blog-designer' ); ?></label>

												<input id="post_length_setting_0" name="post_length_setting" type="radio" value="0" <?php checked( '0', $post_length_setting ); ?>/>
												<label for="post_length_setting_0"><?php esc_html_e( 'No', 'blog-designer' ); ?></label>
											</fieldset>			
										</div>
									</li>
									<li class="post_length_line">
										<div class="bdp-left">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Display Maximum Number Of Line', 'blog-designer-pro' ); ?>
											</span>
										</div>
										<div class="bdp-right input_number_of_line">
											<?php $total_noofline = isset( $bdp_settings['total_noofline'] ) ? $bdp_settings['total_noofline'] : '2'; ?>
											<input type="number" id="total_noofline" name="total_noofline" step="1" min="0" value="<?php echo isset( $bdp_settings['total_noofline'] ) ? esc_attr( $bdp_settings['total_noofline'] ) : '2'; ?>" placeholder="<?php esc_attr_e( 'Enter total number of Line', 'blog-designer' ); ?>">
										</div>
									</li>
									<li class="post_content_wrap">
										<div class="bdp-left">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Post Title Break Words', 'blog-designer' ); ?>
											</span>
										</div>
										<div class="bdp-right">
											<?php
												$template_post_content_wrap_from = get_option( 'template_post_content_wrap_from' );
												$template_post_content_wrap_from = ( isset( $bdp_settings['template_post_content_wrap_from'] ) && '' != $bdp_settings['template_post_content_wrap_from'] ) ? $bdp_settings['template_post_content_wrap_from'] : 'normal';
											?>
											<fieldset class="buttonset three-buttomset">
												<input id="template_post_content_wrap_from_default" name="template_post_content_wrap_from" type="radio" value="normal" <?php checked( 'normal', $template_post_content_wrap_from ); ?>/>
												<label id="bdp-options-button" for="template_post_content_wrap_from_default" <?php checked( 'normal', $template_post_content_wrap_from ); ?>><?php esc_html_e( 'Default', 'blog-designer' ); ?></label>

												<input id="template_post_content_wrap_from_break_word" name="template_post_content_wrap_from" type="radio" value="break-word" <?php checked( 'break-word', $template_post_content_wrap_from ); ?> />
												<label id="bdp-options-button" for="template_post_content_wrap_from_break_word" <?php checked( 'break-word', $template_post_content_wrap_from ); ?>><?php esc_html_e( 'Break word', 'blog-designer' ); ?></label>

												<input id="template_post_content_wrap_from_break_all" name="template_post_content_wrap_from" type="radio" value="break-all" <?php checked( 'break-all', $template_post_content_wrap_from ); ?> />
												<label id="bdp-options-button" for="template_post_content_wrap_from_break_all" <?php checked( 'break-all', $template_post_content_wrap_from ); ?>><?php esc_html_e( 'Break all', 'blog-designer' ); ?></label>
											</fieldset>
								
										</div>
									</li>
								<!-- Post Title setting -->
								<li class="posts_title_links_alignment">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Post Title Alignment', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<?php
										$template_title_alignment = 'left';
										if ( isset( $bdp_settings['template_title_alignment'] ) ) {
											$template_title_alignment = $bdp_settings['template_title_alignment'];
										}
										?>
										<fieldset class="buttonset green" data-hide='1'>
											<input id="template_title_alignment_left" name="template_title_alignment" type="radio" value="left" <?php checked( 'left', $template_title_alignment ); ?> />
											<label id="bdp-options-button" for="template_title_alignment_left"><?php esc_html_e( 'Align-Left', 'blog-designer-pro' ); ?><span class="bdp-aligment-icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M4 17q-.425 0-.713-.288T3 16q0-.425.288-.713T4 15h10q.425 0 .713.288T15 16q0 .425-.288.713T14 17H4Zm0-8q-.425 0-.713-.288T3 8q0-.425.288-.713T4 7h10q.425 0 .713.288T15 8q0 .425-.288.713T14 9H4Zm0 4q-.425 0-.713-.288T3 12q0-.425.288-.713T4 11h16q.425 0 .713.288T21 12q0 .425-.288.713T20 13H4Zm0 8q-.425 0-.713-.288T3 20q0-.425.288-.713T4 19h16q.425 0 .713.288T21 20q0 .425-.288.713T20 21H4ZM4 5q-.425 0-.713-.288T3 4q0-.425.288-.713T4 3h16q.425 0 .713.288T21 4q0 .425-.288.713T20 5H4Z"/></svg></span></label>
											<input id="template_title_alignment_center" name="template_title_alignment" type="radio" value="center" <?php checked( 'center', $template_title_alignment ); ?> />
											<label id="bdp-options-button" for="template_title_alignment_center" class="template_title_alignment_center"><?php esc_html_e( 'Align-Center', 'blog-designer-pro' ); ?><span class="bdp-aligment-icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M4 21q-.425 0-.713-.288T3 20q0-.425.288-.713T4 19h16q.425 0 .713.288T21 20q0 .425-.288.713T20 21H4Zm4-4q-.425 0-.713-.288T7 16q0-.425.288-.713T8 15h8q.425 0 .713.288T17 16q0 .425-.288.713T16 17H8Zm-4-4q-.425 0-.713-.288T3 12q0-.425.288-.713T4 11h16q.425 0 .713.288T21 12q0 .425-.288.713T20 13H4Zm4-4q-.425 0-.713-.288T7 8q0-.425.288-.713T8 7h8q.425 0 .713.288T17 8q0 .425-.288.713T16 9H8ZM4 5q-.425 0-.713-.288T3 4q0-.425.288-.713T4 3h16q.425 0 .713.288T21 4q0 .425-.288.713T20 5H4Z"/></svg></span></label>
											<input id="template_title_alignment_right" name="template_title_alignment" type="radio" value="right" <?php checked( 'right', $template_title_alignment ); ?> />
											<label id="bdp-options-button" for="template_title_alignment_right"><?php esc_html_e( 'Align-Right', 'blog-designer-pro' ); ?><span class="bdp-aligment-icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M4 21q-.425 0-.713-.288T3 20q0-.425.288-.713T4 19h16q.425 0 .713.288T21 20q0 .425-.288.713T20 21H4Zm6-4q-.425 0-.713-.288T9 16q0-.425.288-.713T10 15h10q.425 0 .713.288T21 16q0 .425-.288.713T20 17H10Zm-6-4q-.425 0-.713-.288T3 12q0-.425.288-.713T4 11h16q.425 0 .713.288T21 12q0 .425-.288.713T20 13H4Zm6-4q-.425 0-.713-.288T9 8q0-.425.288-.713T10 7h10q.425 0 .713.288T21 8q0 .425-.288.713T20 9H10ZM4 5q-.425 0-.713-.288T3 4q0-.425.288-.713T4 3h16q.425 0 .713.288T21 4q0 .425-.288.713T20 5H4Z"/></svg></span></label>
										</fieldset>
									</div>
								</li>
							</div>
							<li class="bdp_padding_0 bdp_title_color_settings">
								<div class="bdp-typography-wrapper bdp-typography-wrapper1 bdp_grid_col_2">
									<div class="bdp-typography-cover bdp-typography-title-normal">
										<h3 class="bdp-table-title"><?php esc_html_e( 'Normal Settings', 'blog-designer-pro' ); ?></h3>
										<div class="bdp-typography-sub-cover title-link-color-tr">
											<div class="bdp-typography-label">
												<span class="bdp-key-title">
												<?php esc_html_e( 'Title Color', 'blog-designer-pro' ); ?>
												</span>
											</div>
											<div class="bdp-typography-content">
												<?php $template_titlecolor = isset( $bdp_settings['template_titlecolor'] ) ? $bdp_settings['template_titlecolor'] : ''; ?>
												<input type="text" name="template_titlecolor" id="template_titlecolor" value="<?php echo esc_attr( $template_titlecolor ); ?>"/>
											</div>
										</div>
										<div class="bdp-typography-sub-cover titlebackcolor_tr">
											<div class="bdp-typography-label">
												<span class="bdp-key-title">
												<?php esc_html_e( 'Background Color', 'blog-designer-pro' ); ?>
												</span>
											</div>
											<div class="bdp-typography-content">
												<?php $template_titlebackcolor = isset( $bdp_settings['template_titlebackcolor'] ) ? $bdp_settings['template_titlebackcolor'] : ''; ?>
												<input type="text" name="template_titlebackcolor" id="template_titlebackcolor" value="<?php echo esc_attr( $template_titlebackcolor ); ?>"/>
											</div>
										</div>
									</div>
									<div class="bdp-typography-cover title-link-hover-color-tr">
										<h3 class="bdp-table-title"><?php esc_html_e( 'Hover Settings', 'blog-designer-pro' ); ?></h3>
										<div class="bdp-typography-sub-cover title-link-hover-color-tr">
											<div class="bdp-typography-label">
												<span class="bdp-key-title">
												<?php esc_html_e( 'Title Link Color', 'blog-designer-pro' ); ?>
												</span>
											</div>
											<div class="bdp-typography-content">
												<?php $template_titlehovercolor = isset( $bdp_settings['template_titlehovercolor'] ) ? $bdp_settings['template_titlehovercolor'] : '#444'; ?>
												<input type="text" name="template_titlehovercolor" id="template_titlehovercolor" value="<?php echo esc_attr( $template_titlehovercolor ); ?>"/>
											</div>
										</div>
									</div>
								</div>
							</li>
							<h3 class="bdp-table-title box_shadow"><?php esc_html_e( 'Box Shadow Settings', 'blog-designer-pro' ); ?></h3>
							<li class="edd_addtocart_button_box_shadow_setting box_shadow_setting">
								<div class="bdp-boxshadow-wrapper bdp-boxshadow-wrapper1 bdp_grid_col_4">
									<div class="bdp-boxshadow-cover">
										<div class="bdp-boxshadow-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'H-offset', 'blog-designer-pro' ); ?>
											</span><span class="bdp-px-prefix"><?php esc_html_e( '(px)', 'blog-designer-pro' ); ?></span>
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
											</span><span class="bdp-px-prefix"><?php esc_html_e( '(px)', 'blog-designer-pro' ); ?></span>
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
											</span><span class="bdp-px-prefix"><?php esc_html_e( '(px)', 'blog-designer-pro' ); ?></span>
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
							<li class="bdp-typography-settings-li">
								<div class="bdp-typography-wrapper bdp-typography-wrapper1 bdp_grid_col_4">
									<div class="bdp-typography-cover">
										<div class="bdp-typography-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Font Family', 'blog-designer-pro' ); ?>
											</span>
										</div>
										<div class="bdp-typography-content">
											<?php
											if ( isset( $bdp_settings['template_titlefontface'] ) && '' != $bdp_settings['template_titlefontface'] ) {
												$template_titlefontface = $bdp_settings['template_titlefontface'];
											} else {
												$template_titlefontface = '';
											}
											?>
											<div class="typo-field">
												<input type="hidden" name="template_titlefontface_font_type" id="template_titlefontface_font_type" value="<?php echo isset( $bdp_settings['template_titlefontface_font_type'] ) ? esc_attr( $bdp_settings['template_titlefontface_font_type'] ) : ''; ?>">
												<div class="select-cover">
													<select name="template_titlefontface" id="template_titlefontface">
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
															if ( '' != $template_titlefontface && ( str_replace( '"', '', $template_titlefontface ) == str_replace( '"', '', $value['label'] ) ) ) {
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
											<span class="bdp-key-title"><?php esc_html_e( 'Font Size', 'blog-designer-pro' ); ?></span>
											<span class="bdp-px-prefix"><?php esc_html_e( '(px)', 'blog-designer-pro' ); ?></span>
										</div>
										<div class="bdp-typography-content">
											<?php $template_titlefontsize = ( isset( $bdp_settings['template_titlefontsize'] ) && '' != $bdp_settings['template_titlefontsize'] ) ? $bdp_settings['template_titlefontsize'] : 14; ?>
											<input type="number" class="grid_col_space_val" name="template_titlefontsize" id="template_titlefontsize" value="<?php echo esc_attr( $template_titlefontsize ); ?>" onkeypress="return isNumberKey(event)" />
										</div>
									</div>
									<div class="bdp-typography-cover">
										<div class="bdp-typography-label">
											<span class="bdp-key-title"><?php esc_html_e( 'Font Weight', 'blog-designer-pro' ); ?></span>
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
											<input type="number" name="template_title_font_line_height" id="template_title_font_line_height" step="0.1" min="0" value="<?php echo isset( $bdp_settings['template_title_font_line_height'] ) ? esc_attr( $bdp_settings['template_title_font_line_height'] ) : '1.5'; ?>" onkeypress="return isNumberKey(event)" >
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
											<div class="select-cover">
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
									</div>

									<div class="bdp-typography-cover">
										<div class="bdp-typography-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Letter Spacing', 'blog-designer-pro' ); ?>
											</span><span class="bdp-px-prefix"><?php esc_html_e( '(px)', 'blog-designer-pro' ); ?></span>
										</div>
										<div class="bdp-typography-content">
											<input type="number" name="template_title_font_letter_spacing" id="template_title_font_letter_spacing" step="1" min="0" value="<?php echo isset( $bdp_settings['template_title_font_letter_spacing'] ) ? esc_attr( $bdp_settings['template_title_font_letter_spacing'] ) : '0'; ?>" onkeypress="return isNumberKey(event)">
										</div>
									</div>

								</div>
							</li>
							<h3 class="post_title_wrap bdp-table-title post_title_border_setting"><?php esc_html_e( 'Post Title Margin', 'blog-designer-pro' ); ?></h3>
							<li class="post_title_wrap edd_addtocart_button_box_shadow_setting post_title_border_setting">
								<div class="bdp-boxshadow-wrapper bdp-boxshadow-wrapper1">
									<div class="bdp-boxshadow-cover">
										<div class="bdp-boxshadow-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Left', 'blog-designer-pro' ); ?>
											</span><span class="bdp-px-prefix"><?php esc_html_e( '(px)', 'blog-designer-pro' ); ?></span>
										</div>
										<div class="bdp-boxshadow-content">
											<?php $post_title_marginleft = isset( $bdp_settings['post_title_marginleft'] ) ? $bdp_settings['post_title_marginleft'] : 0; ?>
											<input type="number" id="post_title_marginleft" name="post_title_marginleft" step="1" value="<?php echo esc_attr( $post_title_marginleft ); ?>" onkeypress="return isNumberKey(event)">
										</div>
									</div>
									<div class="bdp-boxshadow-cover">
										<div class="bdp-boxshadow-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Right', 'blog-designer-pro' ); ?>
											</span><span class="bdp-px-prefix"><?php esc_html_e( '(px)', 'blog-designer-pro' ); ?></span>
										</div>
										<div class="bdp-boxshadow-content">
											<?php $post_title_marginright = isset( $bdp_settings['post_title_marginright'] ) ? $bdp_settings['post_title_marginright'] : 0; ?>
											<input type="number" id="post_title_marginright" name="post_title_marginright" step="1" value="<?php echo esc_attr( $post_title_marginright ); ?>" onkeypress="return isNumberKey(event)">
										</div>
									</div>
									<div class="bdp-boxshadow-cover">
										<div class="bdp-boxshadow-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Top', 'blog-designer-pro' ); ?>
											</span><span class="bdp-px-prefix"><?php esc_html_e( '(px)', 'blog-designer-pro' ); ?></span>
										</div>
										<div class="bdp-boxshadow-content">
											<?php $post_title_margintop = isset( $bdp_settings['post_title_margintop'] ) ? $bdp_settings['post_title_margintop'] : 0; ?>
											<input type="number" id="post_title_margintop" name="post_title_margintop" step="1" value="<?php echo esc_attr( $post_title_margintop ); ?>"  onkeypress="return isNumberKey(event)">
										</div>
									</div>
									<div class="bdp-boxshadow-cover">
										<div class="bdp-boxshadow-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Bottom', 'blog-designer-pro' ); ?>
											</span><span class="bdp-px-prefix"><?php esc_html_e( '(px)', 'blog-designer-pro' ); ?></span>
										</div>
										<div class="bdp-boxshadow-content">
											<?php $post_title_marginbottom = isset( $bdp_settings['post_title_marginbottom'] ) ? $bdp_settings['post_title_marginbottom'] : 0; ?>
											<input type="number" id="post_title_marginbottom" name="post_title_marginbottom" step="1" value="<?php echo esc_attr( $post_title_marginbottom ); ?>" onkeypress="return isNumberKey(event)">
										</div>
									</div>
								</div>
							</li>
							<h3 class="post_title_padding_wrap bdp-table-title post_title_border_setting"><?php esc_html_e( 'Post Title Padding', 'blog-designer-pro' ); ?></h3>
							<li class="post_title_padding_wrap edd_addtocart_button_box_shadow_setting post_title_border_setting">
								<div class="bdp-boxshadow-wrapper bdp-boxshadow-wrapper1">
									<div class="bdp-boxshadow-cover">
										<div class="bdp-boxshadow-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Left', 'blog-designer-pro' ); ?>
											</span><span class="bdp-px-prefix"><?php esc_html_e( '(px)', 'blog-designer-pro' ); ?></span>
										</div>
										<div class="bdp-boxshadow-content">
											<?php $post_title_paddingleft = isset( $bdp_settings['post_title_paddingleft'] ) ? $bdp_settings['post_title_paddingleft'] : 0; ?>
											<input type="number" id="post_title_paddingleft" name="post_title_paddingleft" step="1" value="<?php echo esc_attr( $post_title_paddingleft ); ?>" onkeypress="return isNumberKey(event)">
										</div>
									</div>
									<div class="bdp-boxshadow-cover">
										<div class="bdp-boxshadow-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Right', 'blog-designer-pro' ); ?>
											</span><span class="bdp-px-prefix"><?php esc_html_e( '(px)', 'blog-designer-pro' ); ?></span>
										</div>
										<div class="bdp-boxshadow-content">
											<?php $post_title_paddingright = isset( $bdp_settings['post_title_paddingright'] ) ? $bdp_settings['post_title_paddingright'] : 0; ?>
											<input type="number" id="post_title_paddingright" name="post_title_paddingright" step="1" value="<?php echo esc_attr( $post_title_paddingright ); ?>" onkeypress="return isNumberKey(event)">
										</div>
									</div>
									<div class="bdp-boxshadow-cover">
										<div class="bdp-boxshadow-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Top', 'blog-designer-pro' ); ?>
											</span><span class="bdp-px-prefix"><?php esc_html_e( '(px)', 'blog-designer-pro' ); ?></span>
										</div>
										<div class="bdp-boxshadow-content">
											<?php $post_title_paddingtop = isset( $bdp_settings['post_title_paddingtop'] ) ? $bdp_settings['post_title_paddingtop'] : 0; ?>
											<input type="number" id="post_title_paddingtop" name="post_title_paddingtop" step="1" value="<?php echo esc_attr( $post_title_paddingtop ); ?>"  onkeypress="return isNumberKey(event)">
										</div>
									</div>
									<div class="bdp-boxshadow-cover">
										<div class="bdp-boxshadow-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Bottom', 'blog-designer-pro' ); ?>
											</span><span class="bdp-px-prefix"><?php esc_html_e( '(px)', 'blog-designer-pro' ); ?></span>
										</div>
										<div class="bdp-boxshadow-content">
											<?php $post_title_paddingbottom = isset( $bdp_settings['post_title_paddingbottom'] ) ? $bdp_settings['post_title_paddingbottom'] : 0; ?>
											<input type="number" id="post_title_paddingbottom" name="post_title_paddingbottom" step="1" value="<?php echo esc_attr( $post_title_paddingbottom ); ?>" onkeypress="return isNumberKey(event)">
										</div>
									</div>
								</div>
							</li>
						</ul>
					</div>
				</div>
				<div id="bdpcontent" class="postbox postbox-with-fw-options bdp-content-setting1" style=<?php echo esc_attr( $bdpcontent_class_show ); ?>>
					<div class="inside">
						<ul class="bdp-settings bdp-lineheight">
							<div class="bdp_grid_col_2">
								<li class="post_content_from">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Show Content From', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<?php $template_post_content_from = isset( $bdp_settings['template_post_content_from'] ) ? $bdp_settings['template_post_content_from'] : 'from_content'; ?>
										<select name="template_post_content_from" id="template_post_content_from">
											<option value="from_content" <?php selected( $template_post_content_from, 'from_content' ); ?> ><?php esc_html_e( 'Post Content', 'blog-designer-pro' ); ?></option>
											<option value="from_excerpt" <?php selected( $template_post_content_from, 'from_excerpt' ); ?>><?php esc_html_e( 'Post Excerpt', 'blog-designer-pro' ); ?></option>
										</select>
										<div class="bdp-setting-description bdp-note">
											<b class="note"><?php esc_html_e( 'Note', 'blog-designer-pro' ); ?>:</b>
											<?php esc_html_e( 'If Post Excerpt is empty then Content will get automatically from Post Content.', 'blog-designer-pro' ); ?>
										</div>
									</div>
								</li>
								<li class="feed_excert">
									<div class="bdp-left"><span class="bdp-key-title"><?php esc_html_e( 'For each Article in a Feed, Show ', 'blog-designer-pro' ); ?></span></div>
									<div class="bdp-right rss_use_excerpt">
										<fieldset class="buttonset buttonset-hide green" data-hide='1'>
											<input id="rss_use_excerpt_0" name="rss_use_excerpt" type="radio" value="0" <?php checked( 0, $rss_use_excerpt ); ?> />
											<label id="bdp-options-button" for="rss_use_excerpt_0" <?php checked( 0, $rss_use_excerpt ); ?>><?php esc_html_e( 'Full Text', 'blog-designer-pro' ); ?></label>
											<input id="rss_use_excerpt_1" name="rss_use_excerpt" type="radio" value="1" <?php checked( 1, $rss_use_excerpt ); ?> />
											<label id="bdp-options-button" for="rss_use_excerpt_1" <?php checked( 1, $rss_use_excerpt ); ?>><?php esc_html_e( 'Summary', 'blog-designer-pro' ); ?></label>
										</fieldset>
									</div>
								</li>
								<li class="display_html_tags_tr">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Display HTML tags with Summary', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<?php $display_html_tags = ( isset( $bdp_settings['display_html_tags'] ) ) ? $bdp_settings['display_html_tags'] : 0; ?>
										<fieldset class="buttonset display_html_tags">
											<input id="display_html_tags_1" name="display_html_tags" type="radio" value="1" <?php checked( 1, $display_html_tags ); ?> />
											<label for="display_html_tags_1" <?php checked( 1, $display_html_tags ); ?>><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
											<input id="display_html_tags_0" name="display_html_tags" type="radio" value="0" <?php checked( 0, $display_html_tags ); ?> />
											<label for="display_html_tags_0" <?php checked( 0, $display_html_tags ); ?>><?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
										</fieldset>
									</div>
								</li>
								<li class="content-firstletter-tr">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'First letter as Dropcap', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<?php $firstletter_big = ( isset( $bdp_settings['firstletter_big'] ) ) ? $bdp_settings['firstletter_big'] : 0; ?>
										<fieldset class="buttonset firstletter_big">
											<input id="firstletter_big_1" name="firstletter_big" type="radio" value="1" <?php checked( 1, $firstletter_big ); ?> />
											<label for="firstletter_big_1" <?php checked( 1, $firstletter_big ); ?>><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
											<input id="firstletter_big_0" name="firstletter_big" type="radio" value="0" <?php checked( 0, $firstletter_big ); ?> />
											<label for="firstletter_big_0" <?php checked( 0, $firstletter_big ); ?>><?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
										</fieldset>
									</div>
								</li>
							</div>
							<h3 class="bdp-table-title firstletter-setting bdp-dropcap-settings"><?php esc_html_e( 'Dropcap Settings', 'blog-designer-pro' ); ?></h3>
							<li class="firstletter-setting bdp-dropcap-settings">
								<div class="bdp-typography-wrapper bdp-typography-wrapper1 bdp_grid_col_3">
									<div class="bdp-typography-cover">
										<div class="bdp-typography-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Dropcap Font Family', 'blog-designer-pro' ); ?>
											</span>
										</div>
										<div class="bdp-typography-content">
											<?php $firstletter_font_family = ( isset( $bdp_settings['firstletter_font_family'] ) && '' != $bdp_settings['firstletter_font_family'] ) ? esc_attr( $bdp_settings['firstletter_font_family'] ) : ''; ?>
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
									<div class="bdp-typography-cover">
										<div class="bdp-typography-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Dropcap Font Size', 'blog-designer-pro' ); ?>
											</span><span class="bdp-px-prefix"><?php esc_html_e( '(px)', 'blog-designer-pro' ); ?></span>
										</div>
										<div class="bdp-typography-content">
											<?php $firstletter_fontsize = ( isset( $bdp_settings['firstletter_fontsize'] ) && '' != $bdp_settings['firstletter_fontsize'] ) ? $bdp_settings['firstletter_fontsize'] : '35'; ?>
											<input type="number" class="grid_col_space_val" name="firstletter_fontsize" id="firstletter_fontsize" value="<?php echo esc_attr( $firstletter_fontsize ); ?>" onkeypress="return isNumberKey(event)" />
										</div>
									</div>
									<div class="bdp-typography-cover">
										<div class="bdp-typography-label">
											<span class="bdp-key-title"><?php esc_html_e( 'Dropcap Color', 'blog-designer-pro' ); ?></span>
										</div>
										<div class="bdp-typography-content">
											<?php
											if ( isset( $bdp_settings['firstletter_contentcolor'] ) ) {
												$firstletter_contentcolor = $bdp_settings['firstletter_contentcolor'];
											} else {
												$firstletter_contentcolor = '#000000';
											}
											?>
											<input type="text" name="firstletter_contentcolor" id="firstletter_contentcolor" value="<?php echo esc_html( $firstletter_contentcolor ); ?>"/>
										</div>
									</div>
								</div>
							</li>
							<div class="bdp_grid_col_3">
								<li class="excerpt_length">
									<div class="bdp-left"><span class="bdp-key-title"><?php esc_html_e( 'Enter post content length (words)', 'blog-designer-pro' ); ?></span></div>
									<div class="bdp-right">
										<input type="number" id="txtExcerptlength" name="txtExcerptlength" step="1" min="0" value="<?php echo esc_attr( $txt_excerptlength ); ?>" placeholder="Enter excerpt length" onkeypress="return isNumberKey(event)">
									</div>
								</li>
								<li class="advance_contents_tr">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Advance Post Contents', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<?php $advance_contents = ( isset( $bdp_settings['advance_contents'] ) ) ? $bdp_settings['advance_contents'] : 0; ?>
										<fieldset class="buttonset advance_contents">
											<input id="advance_contents_1" name="advance_contents" type="radio" value="1" <?php checked( 1, $advance_contents ); ?> />
											<label for="advance_contents_1" <?php checked( 1, $advance_contents ); ?>><?php esc_html_e( 'Enable', 'blog-designer-pro' ); ?></label>
											<input id="advance_contents_0" name="advance_contents" type="radio" value="0" <?php checked( 0, $advance_contents ); ?> />
											<label for="advance_contents_0" <?php checked( 0, $advance_contents ); ?>><?php esc_html_e( 'Disable', 'blog-designer-pro' ); ?></label>
										</fieldset>
									</div>
								</li>
								<li class="advance_contents_tr advance_contents_settings">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Stoppage From', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<?php $contents_stopage_from = isset( $bdp_settings['contents_stopage_from'] ) ? $bdp_settings['contents_stopage_from'] : 'paragraph'; ?>
										<select name="contents_stopage_from" id="contents_stopage_from">
											<option value="paragraph" <?php selected( $contents_stopage_from, 'paragraph' ); ?> ><?php esc_html_e( 'Last Paragraph', 'blog-designer-pro' ); ?></option>
											<option value="character" <?php selected( $contents_stopage_from, 'character' ); ?>><?php esc_html_e( 'Characters', 'blog-designer-pro' ); ?></option>
										</select>
										<div class="bdp-setting-description bdp-note">
											<b class="note"><?php esc_html_e( 'Note', 'blog-designer-pro' ); ?>:</b> &nbsp;
											<?php
											esc_html_e( 'If "Display HTML tags with Summary" is Enable then Stoppage From Characters option is disable.', 'blog-designer-pro' );
											?>
										</div>
									</div>
								</li>
								<li class="advance_contents_tr advance_contents_settings advance_contents_settings_character">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Stoppage Characters', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<?php $contents_stopage_character = isset( $bdp_settings['contents_stopage_character'] ) ? $bdp_settings['contents_stopage_character'] : array( '.' ); ?>
										<select data-placeholder="<?php esc_attr_e( 'Choose stoppage characters', 'blog-designer-pro' ); ?>" class="chosen-select" multiple style="width:220px;" name="contents_stopage_character[]" id="contents_stopage_character">
											<option value="." <?php echo ( in_array( '.', $contents_stopage_character ) ) ? 'selected="selected"' : ''; ?>> . </option>
											<option value="?" <?php echo ( in_array( '?', $contents_stopage_character ) ) ? 'selected="selected"' : ''; ?>> ? </option>
											<option value="," <?php echo ( in_array( ',', $contents_stopage_character ) ) ? 'selected="selected"' : ''; ?>> , </option>
											<option value=";" <?php echo ( in_array( ';', $contents_stopage_character ) ) ? 'selected="selected"' : ''; ?>> ; </option>
											<option value=":" <?php echo ( in_array( ':', $contents_stopage_character ) ) ? 'selected="selected"' : ''; ?>> : </option>
										</select>
									</div>
								</li>
								<li class="contentcolor_tr">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Post Content Color', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<input type="text" name="template_contentcolor" id="template_contentcolor" value="<?php echo esc_attr( $template_contentcolor ); ?>" />
									</div>
								</li>
								<li class="post_content_hover">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Post Content Section Hover Background Color', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<input type="text" name="template_content_hovercolor" id="template_content_hovercolor" value="<?php echo esc_attr( $template_content_hovercolor ); ?>"/>
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
											</span><span class="bdp-px-prefix"><?php esc_html_e( '(px)', 'blog-designer-pro' ); ?></span>
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
											</span><span class="bdp-px-prefix"><?php esc_html_e( '(px)', 'blog-designer-pro' ); ?></span>
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
											</span><span class="bdp-px-prefix"><?php esc_html_e( '(px)', 'blog-designer-pro' ); ?></span>
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
							<h3 class="bdp-table-title"><?php esc_html_e( 'Post Content Typography Settings', 'blog-designer-pro' ); ?></h3>
							<li class="bdp-wrapper-cover-label-li">
								<div class="bdp-typography-wrapper bdp-typography-wrapper1 bdp_grid_col_4">
									<div class="bdp-typography-cover">
										<div class="bdp-typography-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Font Family', 'blog-designer-pro' ); ?>
											</span>
										</div>
										<div class="bdp-typography-content">
											<?php
											if ( isset( $bdp_settings['content_font_family'] ) && '' != $bdp_settings['content_font_family'] ) {
												$content_font_family = $bdp_settings['content_font_family'];
											} else {
												$content_font_family = '';
											}
											?>
											<div class="typo-field">
												<input type="hidden" id="content_font_family_font_type" name="content_font_family_font_type" value="<?php echo isset( $bdp_settings['content_font_family_font_type'] ) ? esc_attr( $bdp_settings['content_font_family_font_type'] ) : ''; ?>">
												<div class="select-cover">
													<select name="content_font_family" id="content_font_family">
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
															if ( '' != $content_font_family && ( str_replace( '"', '', $content_font_family ) == str_replace( '"', '', $value['label'] ) ) ) {
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
											</span><span class="bdp-px-prefix"><?php esc_html_e( '(px)', 'blog-designer-pro' ); ?></span>
										</div>
										<div class="bdp-typography-content">
											<input type="number" class="grid_col_space_val" name="content_fontsize" id="content_fontsize" value="<?php echo esc_attr( $content_fontsize ); ?>" onkeypress="return isNumberKey(event)" />
										</div>
									</div>

									<div class="bdp-typography-cover">
										<div class="bdp-typography-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Font Weight', 'blog-designer-pro' ); ?>
											</span>
										</div>
										<div class="bdp-typography-content">
											<?php $content_font_weight = isset( $bdp_settings['content_font_weight'] ) ? $bdp_settings['content_font_weight'] : 'normal'; ?>
											<div class="select-cover">
												<select name="content_font_weight" id="content_font_weight">
													<option value="100" <?php selected( $content_font_weight, 100 ); ?>>100</option>
													<option value="200" <?php selected( $content_font_weight, 200 ); ?>>200</option>
													<option value="300" <?php selected( $content_font_weight, 300 ); ?>>300</option>
													<option value="400" <?php selected( $content_font_weight, 400 ); ?>>400</option>
													<option value="500" <?php selected( $content_font_weight, 500 ); ?>>500</option>
													<option value="600" <?php selected( $content_font_weight, 600 ); ?>>600</option>
													<option value="700" <?php selected( $content_font_weight, 700 ); ?>>700</option>
													<option value="800" <?php selected( $content_font_weight, 800 ); ?>>800</option>
													<option value="900" <?php selected( $content_font_weight, 900 ); ?>>900</option>
													<option value="bold" <?php selected( $content_font_weight, 'bold' ); ?> ><?php esc_html_e( 'Bold', 'blog-designer-pro' ); ?></option>
													<option value="normal" <?php selected( $content_font_weight, 'normal' ); ?>><?php esc_html_e( 'Normal', 'blog-designer-pro' ); ?></option>
												</select>
											</div>
										</div>
									</div>
									<div class="bdp-typography-cover">
										<div class="bdp-typography-label">
											<span class="bdp-key-title"><?php esc_html_e( 'Line Height', 'blog-designer-pro' ); ?></span>
										</div>
										<div class="bdp-typography-content">
											<input type="number" name="content_font_line_height" id="content_font_line_height" step="0.1" min="0" value="<?php echo isset( $bdp_settings['content_font_line_height'] ) ? esc_attr( $bdp_settings['content_font_line_height'] ) : '1.5'; ?>" onkeypress="return isNumberKey(event)">
										</div>
									</div>
									<div class="bdp-typography-cover">
										<div class="bdp-typography-label">
											<span class="bdp-key-title"><?php esc_html_e( 'Italic Font Style', 'blog-designer-pro' ); ?></span>
										</div>
										<div class="bdp-typography-content">
											<?php $content_font_italic = isset( $bdp_settings['content_font_italic'] ) ? $bdp_settings['content_font_italic'] : '0'; ?>
											<fieldset class="bdp-social-options bdp-display_author buttonset">
												<input id="content_font_italic_1" name="content_font_italic" type="radio" value="1"  <?php checked( 1, $content_font_italic ); ?> />
												<label for="content_font_italic_1"><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
												<input id="content_font_italic_0" name="content_font_italic" type="radio" value="0" <?php checked( 0, $content_font_italic ); ?> />
												<label for="content_font_italic_0"><?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
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
											<?php $content_font_text_transform = isset( $bdp_settings['content_font_text_transform'] ) ? $bdp_settings['content_font_text_transform'] : 'none'; ?>
											<div class="select-cover">
												<select name="content_font_text_transform" id="content_font_text_transform">
													<option <?php selected( $content_font_text_transform, 'none' ); ?> value="none"><?php esc_html_e( 'None', 'blog-designer-pro' ); ?></option>
													<option <?php selected( $content_font_text_transform, 'capitalize' ); ?> value="capitalize"><?php esc_html_e( 'Capitalize', 'blog-designer-pro' ); ?></option>
													<option <?php selected( $content_font_text_transform, 'uppercase' ); ?> value="uppercase"><?php esc_html_e( 'Uppercase', 'blog-designer-pro' ); ?></option>
													<option <?php selected( $content_font_text_transform, 'lowercase' ); ?> value="lowercase"><?php esc_html_e( 'Lowercase', 'blog-designer-pro' ); ?></option>
													<option <?php selected( $content_font_text_transform, 'full-width' ); ?> value="full-width"><?php esc_html_e( 'Full Width', 'blog-designer-pro' ); ?></option>
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
											<?php $content_font_text_decoration = isset( $bdp_settings['content_font_text_decoration'] ) ? $bdp_settings['content_font_text_decoration'] : 'none'; ?>
											<div class="select-cover">
												<select name="content_font_text_decoration" id="content_font_text_decoration">
													<option <?php selected( $content_font_text_decoration, 'none' ); ?> value="none"><?php esc_html_e( 'None', 'blog-designer-pro' ); ?></option>
													<option <?php selected( $content_font_text_decoration, 'underline' ); ?> value="underline"><?php esc_html_e( 'Underline', 'blog-designer-pro' ); ?></option>
													<option <?php selected( $content_font_text_decoration, 'overline' ); ?> value="overline"><?php esc_html_e( 'Overline', 'blog-designer-pro' ); ?></option>
													<option <?php selected( $content_font_text_decoration, 'line-through' ); ?> value="line-through"><?php esc_html_e( 'Line Through', 'blog-designer-pro' ); ?></option>
												</select>
											</div>
										</div>
									</div>

									<div class="bdp-typography-cover">
										<div class="bdp-typography-label">
											<span class="bdp-key-title"><?php esc_html_e( 'Letter Spacing', 'blog-designer-pro' ); ?></span>
											<span class="bdp-px-prefix"><?php esc_html_e( '(px)', 'blog-designer-pro' ); ?></span>
										</div>
										<div class="bdp-typography-content">
											<input type="number" name="content_font_letter_spacing" id="content_font_letter_spacing" step="1" min="0" value="<?php echo isset( $bdp_settings['content_font_letter_spacing'] ) ? esc_attr( $bdp_settings['content_font_letter_spacing'] ) : '0'; ?>" onkeypress="return isNumberKey(event)">
										</div>
									</div>
								</div>
							</li>
							<h3 class="post_content_wrap bdp-table-title post_content_border_setting"><?php esc_html_e( 'Post Content Margin', 'blog-designer-pro' ); ?></h3>
							<li class="post_content_wrap edd_addtocart_button_box_shadow_setting post_content_border_setting">
								<div class="bdp-boxshadow-wrapper bdp-boxshadow-wrapper1">
									<div class="bdp-boxshadow-cover">
										<div class="bdp-boxshadow-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Left', 'blog-designer-pro' ); ?>
											</span><span class="bdp-px-prefix"><?php esc_html_e( '(px)', 'blog-designer-pro' ); ?></span>
										</div>
										<div class="bdp-boxshadow-content">
											<?php $post_content_marginleft = isset( $bdp_settings['post_content_marginleft'] ) ? $bdp_settings['post_content_marginleft'] : 0; ?>
											<input type="number" id="post_content_marginleft" name="post_content_marginleft" step="1" value="<?php echo esc_attr( $post_content_marginleft ); ?>" onkeypress="return isNumberKey(event)">
										</div>
									</div>
									<div class="bdp-boxshadow-cover">
										<div class="bdp-boxshadow-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Right', 'blog-designer-pro' ); ?>
											</span><span class="bdp-px-prefix"><?php esc_html_e( '(px)', 'blog-designer-pro' ); ?></span>
										</div>
										<div class="bdp-boxshadow-content">
											<?php $post_content_marginright = isset( $bdp_settings['post_content_marginright'] ) ? $bdp_settings['post_content_marginright'] : 0; ?>
											<input type="number" id="post_content_marginright" name="post_content_marginright" step="1" value="<?php echo esc_attr( $post_content_marginright ); ?>" onkeypress="return isNumberKey(event)">
										</div>
									</div>
									<div class="bdp-boxshadow-cover">
										<div class="bdp-boxshadow-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Top', 'blog-designer-pro' ); ?>
											</span><span class="bdp-px-prefix"><?php esc_html_e( '(px)', 'blog-designer-pro' ); ?></span>
										</div>
										<div class="bdp-boxshadow-content">
											<?php $post_content_margintop = isset( $bdp_settings['post_content_margintop'] ) ? $bdp_settings['post_content_margintop'] : 0; ?>
											<input type="number" id="post_content_margintop" name="post_content_margintop" step="1" value="<?php echo esc_attr( $post_content_margintop ); ?>"  onkeypress="return isNumberKey(event)">
										</div>
									</div>
									<div class="bdp-boxshadow-cover">
										<div class="bdp-boxshadow-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Bottom', 'blog-designer-pro' ); ?>
											</span><span class="bdp-px-prefix"><?php esc_html_e( '(px)', 'blog-designer-pro' ); ?></span>
										</div>
										<div class="bdp-boxshadow-content">
											<?php $post_content_marginbottom = isset( $bdp_settings['post_content_marginbottom'] ) ? $bdp_settings['post_content_marginbottom'] : 0; ?>
											<input type="number" id="post_content_marginbottom" name="post_content_marginbottom" step="1" value="<?php echo esc_attr( $post_content_marginbottom ); ?>" onkeypress="return isNumberKey(event)">
										</div>
									</div>
								</div>
							</li>
							<h3 class="post_content_padding_wrap bdp-table-title post_content_border_setting"><?php esc_html_e( 'Post Content Padding', 'blog-designer-pro' ); ?></h3>
							<li class="post_content_padding_wrap edd_addtocart_button_box_shadow_setting post_content_border_setting">
								<div class="bdp-boxshadow-wrapper bdp-boxshadow-wrapper1">
									<div class="bdp-boxshadow-cover">
										<div class="bdp-boxshadow-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Left', 'blog-designer-pro' ); ?>
											</span><span class="bdp-px-prefix"><?php esc_html_e( '(px)', 'blog-designer-pro' ); ?></span>
										</div>
										<div class="bdp-boxshadow-content">
											<?php $post_content_paddingleft = isset( $bdp_settings['post_content_paddingleft'] ) ? $bdp_settings['post_content_paddingleft'] : 0; ?>
											<input type="number" id="post_content_paddingleft" name="post_content_paddingleft" step="1" value="<?php echo esc_attr( $post_content_paddingleft ); ?>" onkeypress="return isNumberKey(event)">
										</div>
									</div>
									<div class="bdp-boxshadow-cover">
										<div class="bdp-boxshadow-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Right', 'blog-designer-pro' ); ?>
											</span><span class="bdp-px-prefix"><?php esc_html_e( '(px)', 'blog-designer-pro' ); ?></span>
										</div>
										<div class="bdp-boxshadow-content">
											<?php $post_content_paddingright = isset( $bdp_settings['post_content_paddingright'] ) ? $bdp_settings['post_content_paddingright'] : 0; ?>
											<input type="number" id="post_content_paddingright" name="post_content_paddingright" step="1" value="<?php echo esc_attr( $post_content_paddingright ); ?>" onkeypress="return isNumberKey(event)">
										</div>
									</div>
									<div class="bdp-boxshadow-cover">
										<div class="bdp-boxshadow-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Top', 'blog-designer-pro' ); ?>
											</span><span class="bdp-px-prefix"><?php esc_html_e( '(px)', 'blog-designer-pro' ); ?></span>
										</div>
										<div class="bdp-boxshadow-content">
											<?php $post_content_paddingtop = isset( $bdp_settings['post_content_paddingtop'] ) ? $bdp_settings['post_content_paddingtop'] : 0; ?>
											<input type="number" id="post_content_paddingtop" name="post_content_paddingtop" step="1" value="<?php echo esc_attr( $post_content_paddingtop ); ?>"  onkeypress="return isNumberKey(event)">
										</div>
									</div>
									<div class="bdp-boxshadow-cover">
										<div class="bdp-boxshadow-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Bottom', 'blog-designer-pro' ); ?>
											</span><span class="bdp-px-prefix"><?php esc_html_e( '(px)', 'blog-designer-pro' ); ?></span>
										</div>
										<div class="bdp-boxshadow-content">
											<?php $post_content_paddingbottom = isset( $bdp_settings['post_content_paddingbottom'] ) ? $bdp_settings['post_content_paddingbottom'] : 0; ?>
											<input type="number" id="post_content_paddingbottom" name="post_content_paddingbottom" step="1" value="<?php echo esc_attr( $post_content_paddingbottom ); ?>" onkeypress="return isNumberKey(event)">
										</div>
									</div>
								</div>
							</li>
							<h3 class="bdp-table-title bdp-read-more-title"><?php esc_html_e( 'Read More Settings', 'blog-designer-pro' ); ?></h3>
							<div class="bdp_grid_col_3">
								<li class="display_read_more_link">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Display Read More Link', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<?php $read_more_link = isset( $bdp_settings['read_more_link'] ) ? $bdp_settings['read_more_link'] : '1'; ?>
										<fieldset class="bdp-social-options bdp-read_more_link buttonset buttonset-hide ui-buttonset">
											<input id="read_more_link_1" name="read_more_link" type="radio" value="1" <?php checked( 1, $read_more_link ); ?> />
											<label for="read_more_link_1" <?php checked( 1, $read_more_link ); ?>><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
											<input id="read_more_link_0" name="read_more_link" type="radio" value="0" <?php checked( 0, $read_more_link ); ?> />
											<label for="read_more_link_0" <?php checked( 0, $read_more_link ); ?>><?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
										</fieldset>
									</div>
								</li>

								<li class="display_read_more_on read_more_wrap">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Display Read More On', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<?php $read_more_on = isset( $bdp_settings['read_more_on'] ) ? $bdp_settings['read_more_on'] : '2'; ?>
										<fieldset class="bdp-social-options bdp-read_more_on buttonset buttonset-hide ui-buttonset green">
											<input id="read_more_on_1" name="read_more_on" type="radio" value="1" <?php checked( 1, $read_more_on ); ?> />
											<label id="bdp-options-button" for="read_more_on_1" <?php checked( 1, $read_more_on ); ?>><?php esc_html_e( 'Same Line', 'blog-designer-pro' ); ?></label>
											<input id="read_more_on_2" name="read_more_on" type="radio" value="2" <?php checked( 2, $read_more_on ); ?> />
											<label id="bdp-options-button" for="read_more_on_2" <?php checked( 2, $read_more_on ); ?>><?php esc_html_e( 'Next Line', 'blog-designer-pro' ); ?></label>
										</fieldset>
									</div>
								</li>
								<li class="read_more_text read_more_wrap">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Read More Text', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<input type="text" name="txtReadmoretext" id="txtReadmoretext" value="<?php echo esc_attr( $txt_readmoretext ); ?>" placeholder="Enter read more text">
									</div>
								</li>

								<li class="read_more_wrap">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Read More Link', 'blog-designer-pro' ); ?>&nbsp;
										</span>
									</div>
									<div class="bdp-right">
										<?php $post_link_type = isset( $bdp_settings['post_link_type'] ) ? $bdp_settings['post_link_type'] : '0'; ?>
										<fieldset class="bdp-post_link_type buttonset buttonset-hide green" data-hide='1'>
											<input id="post_link_type_0" name="post_link_type" type="radio" value="0" <?php checked( 0, $post_link_type ); ?> />
											<label id="bdp-options-button" for="post_link_type_0" <?php checked( 0, $post_link_type ); ?>><?php esc_html_e( 'Post Link', 'blog-designer-pro' ); ?></label>
											<input id="post_link_type_1" name="post_link_type" type="radio" value="1" <?php checked( 1, $post_link_type ); ?> />
											<label id="bdp-options-button" for="post_link_type_1" <?php checked( 1, $post_link_type ); ?>><?php esc_html_e( 'Custom Link', 'blog-designer-pro' ); ?></label>
										</fieldset>
									</div>
								</li>
								<li class="read_more_wrap link-behaviour-option">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Link Behaviour', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
									<?php $link_behaviour = isset( $bdp_settings['link_behaviour'] ) ? $bdp_settings['link_behaviour'] : ''; ?>
										<div class="select-cover">
											<select name="link_behaviour" id="link_behaviour">
												<option <?php selected( $link_behaviour, 'self' ); ?> value="self"><?php esc_html_e( 'Self', 'blog-designer-pro' ); ?></option>
												<option <?php selected( $link_behaviour, 'new' ); ?> value="new"><?php esc_html_e( 'New', 'blog-designer-pro' ); ?></option>
											</select>
										</div>
									</div>
								</li>
								<li class="read_more_wrap read_more_button_alignment_setting">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Read More Button Alignment', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<?php
										$readmore_button_alignment = 'left';
										if ( isset( $bdp_settings['readmore_button_alignment'] ) ) {
											$readmore_button_alignment = $bdp_settings['readmore_button_alignment'];
										}
										?>
										<fieldset class="buttonset green" data-hide='1'>
												<input id="readmore_button_alignment_left" name="readmore_button_alignment" type="radio" value="left" <?php checked( 'left', $readmore_button_alignment ); ?> />
												<label id="bdp-options-button" for="readmore_button_alignment_left"><?php esc_html_e( 'Left', 'blog-designer-pro' ); ?><span class="bdp-aligment-icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M4 17q-.425 0-.713-.288T3 16q0-.425.288-.713T4 15h10q.425 0 .713.288T15 16q0 .425-.288.713T14 17H4Zm0-8q-.425 0-.713-.288T3 8q0-.425.288-.713T4 7h10q.425 0 .713.288T15 8q0 .425-.288.713T14 9H4Zm0 4q-.425 0-.713-.288T3 12q0-.425.288-.713T4 11h16q.425 0 .713.288T21 12q0 .425-.288.713T20 13H4Zm0 8q-.425 0-.713-.288T3 20q0-.425.288-.713T4 19h16q.425 0 .713.288T21 20q0 .425-.288.713T20 21H4ZM4 5q-.425 0-.713-.288T3 4q0-.425.288-.713T4 3h16q.425 0 .713.288T21 4q0 .425-.288.713T20 5H4Z"/></svg></span></label>
												<input id="readmore_button_alignment_center" name="readmore_button_alignment" type="radio" value="center" <?php checked( 'center', $readmore_button_alignment ); ?> />
												<label id="bdp-options-button" for="readmore_button_alignment_center" class="readmore_button_alignment_center"><?php esc_html_e( 'Center', 'blog-designer-pro' ); ?><span class="bdp-aligment-icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M4 21q-.425 0-.713-.288T3 20q0-.425.288-.713T4 19h16q.425 0 .713.288T21 20q0 .425-.288.713T20 21H4Zm4-4q-.425 0-.713-.288T7 16q0-.425.288-.713T8 15h8q.425 0 .713.288T17 16q0 .425-.288.713T16 17H8Zm-4-4q-.425 0-.713-.288T3 12q0-.425.288-.713T4 11h16q.425 0 .713.288T21 12q0 .425-.288.713T20 13H4Zm4-4q-.425 0-.713-.288T7 8q0-.425.288-.713T8 7h8q.425 0 .713.288T17 8q0 .425-.288.713T16 9H8ZM4 5q-.425 0-.713-.288T3 4q0-.425.288-.713T4 3h16q.425 0 .713.288T21 4q0 .425-.288.713T20 5H4Z"/></svg></span></label>
												<input id="readmore_button_alignment_right" name="readmore_button_alignment" type="radio" value="right" <?php checked( 'right', $readmore_button_alignment ); ?> />
												<label id="bdp-options-button" for="readmore_button_alignment_right"><?php esc_html_e( 'Right', 'blog-designer-pro' ); ?><span class="bdp-aligment-icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M4 21q-.425 0-.713-.288T3 20q0-.425.288-.713T4 19h16q.425 0 .713.288T21 20q0 .425-.288.713T20 21H4Zm6-4q-.425 0-.713-.288T9 16q0-.425.288-.713T10 15h10q.425 0 .713.288T21 16q0 .425-.288.713T20 17H10Zm-6-4q-.425 0-.713-.288T3 12q0-.425.288-.713T4 11h16q.425 0 .713.288T21 12q0 .425-.288.713T20 13H4Zm6-4q-.425 0-.713-.288T9 8q0-.425.288-.713T10 7h10q.425 0 .713.288T21 8q0 .425-.288.713T20 9H10ZM4 5q-.425 0-.713-.288T3 4q0-.425.288-.713T4 3h16q.425 0 .713.288T21 4q0 .425-.288.713T20 5H4Z"/></svg></span></label>
										</fieldset>
									</div>
								</li>
								<li class="read_more_wrap custom_link_url">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Custom Link URL', 'blog-designer-pro' ); ?>&nbsp;
										</span>
									</div>
									<div class="bdp-right">
										<?php $custom_link_url = isset( $bdp_settings['custom_link_url'] ) ? $bdp_settings['custom_link_url'] : ''; ?>
											<input type="text" name="custom_link_url" id="custom_link_url" value="<?php echo esc_attr( $custom_link_url ); ?>" placeholder="<?php echo esc_html__( 'eg.', 'blog-designer-pro' ) . ' ' . esc_url( get_site_url() ); ?>" />
									</div>
								</li>
							</div>
							<li class="read_more_wrap bdp_padding_0">
								<div class="bdp-typography-wrapper bdp-typography-wrapper1 bdp_grid_col_2">
									<div class="bdp-typography-cover">
										<h3 class="bdp-table-title"><?php esc_html_e( 'Normal Settings', 'blog-designer-pro' ); ?></h3>
										<div class="bdp-typography-sub-cover read_more_text_color">
											<div class="bdp-typography-label">
												<span class="bdp-key-title">
												<?php esc_html_e( 'Text Color', 'blog-designer-pro' ); ?>
												</span>
											</div>
											<div class="bdp-typography-content">
												<?php $template_readmorecolor = isset( $bdp_settings['template_readmorecolor'] ) ? $bdp_settings['template_readmorecolor'] : ''; ?>
												<input type="text" name="template_readmorecolor" id="template_readmorecolor" value="<?php echo esc_attr( $template_readmorecolor ); ?>"/>
											</div>
										</div>
										<div class="bdp-typography-sub-cover read_more_text_background">
											<div class="bdp-typography-label">
												<span class="bdp-key-title">
												<?php esc_html_e( 'Background Color', 'blog-designer-pro' ); ?>
												</span>
											</div>
											<div class="bdp-typography-content">
												<?php $template_readmorebackcolor = isset( $bdp_settings['template_readmorebackcolor'] ) ? $bdp_settings['template_readmorebackcolor'] : ''; ?>
												<input type="text" name="template_readmorebackcolor" id="template_readmorebackcolor" value="<?php echo esc_attr( $template_readmorebackcolor ); ?>"/>
											</div>
										</div>
										<div class="bdp-typography-sub-cover read_more_button_border_radius_setting">
											<div class="bdp-typography-label">
												<span class="bdp-key-title">
												<?php esc_html_e( 'Border Radius', 'blog-designer-pro' ); ?>
												</span><span class="bdp-px-prefix"><?php esc_html_e( '(px)', 'blog-designer-pro' ); ?></span>
											</div>
											<div class="bdp-typography-content">
												<?php $readmore_button_border_radius = isset( $bdp_settings['readmore_button_border_radius'] ) ? $bdp_settings['readmore_button_border_radius'] : '0'; ?>
												<input type="number" id="readmore_button_border_radius" name="readmore_button_border_radius" step="1" min="0" value="<?php echo esc_attr( $readmore_button_border_radius ); ?>" onkeypress="return isNumberKey(event)">
											</div>
										</div>
										<div class="bdp-typography-sub-cover read_more_button_border_setting">
											<div class="bdp-typography-label">
												<span class="bdp-key-title">
												<?php esc_html_e( 'Border Style', 'blog-designer-pro' ); ?>
												</span>
											</div>
											<div class="bdp-typography-content">
												<?php $read_more_button_border_style = isset( $bdp_settings['read_more_button_border_style'] ) ? $bdp_settings['read_more_button_border_style'] : 'solid'; ?>
												<select name="read_more_button_border_style" id="read_more_button_border_style">
													<option value="none" <?php selected( $read_more_button_border_style, 'none' ); ?>><?php esc_html_e( 'None', 'blog-designer-pro' ); ?></option>
													<option value="dotted" <?php selected( $read_more_button_border_style, 'dotted' ); ?>><?php esc_html_e( 'Dotted', 'blog-designer-pro' ); ?></option>
													<option value="dashed" <?php selected( $read_more_button_border_style, 'dashed' ); ?>><?php esc_html_e( 'Dashed', 'blog-designer-pro' ); ?></option>
													<option value="solid" <?php selected( $read_more_button_border_style, 'solid' ); ?>><?php esc_html_e( 'Solid', 'blog-designer-pro' ); ?></option>
													<option value="double" <?php selected( $read_more_button_border_style, 'double' ); ?>><?php esc_html_e( 'Double', 'blog-designer-pro' ); ?></option>
													<option value="groove" <?php selected( $read_more_button_border_style, 'groove' ); ?>><?php esc_html_e( 'Groove', 'blog-designer-pro' ); ?></option>
													<option value="ridge" <?php selected( $read_more_button_border_style, 'ridge' ); ?>><?php esc_html_e( 'Ridge', 'blog-designer-pro' ); ?></option>
													<option value="inset" <?php selected( $read_more_button_border_style, 'inset' ); ?>><?php esc_html_e( 'Inset', 'blog-designer-pro' ); ?></option>
													<option value="outset" <?php selected( $read_more_button_border_style, 'outset' ); ?> ><?php esc_html_e( 'Outset', 'blog-designer-pro' ); ?></option>
												</select>
											</div>
										</div>
										<div class="bdp-typography-sub-cover full-width read_more_button_border_setting">
											<div class="bdp-typography-label bdp-rmbb-main">
												<span class="bdp-key-title">
													<?php esc_html_e( 'Read More Button Border', 'blog-designer-pro' ); ?>
												</span>
											</div>
											<div class="bdp-typography-content">
												<div class="bdp-border-wrap">
													<div class="bdp-border-wrapper bdp-border-wrapper1 bdp_grid_col_2">
														<div class="bdp-rmb-inner">
															<div class="bdp-border-cover bdp-border-label">
																<span class="bdp-key-title">
																	<?php esc_html_e( 'Left', 'blog-designer-pro' ); ?>
																</span><span class="bdp-px-prefix"><?php esc_html_e( '(px)', 'blog-designer-pro' ); ?></span>
															</div>
															<div class="bdp-border-cover">
																<div class="bdp-border-content">
																	<?php $bdp_readmore_button_borderleft = isset( $bdp_settings['bdp_readmore_button_borderleft'] ) ? $bdp_settings['bdp_readmore_button_borderleft'] : '0'; ?>
																	<input type="number" id="bdp_readmore_button_borderleft" name="bdp_readmore_button_borderleft" step="1" min="0" value="<?php echo esc_attr( $bdp_readmore_button_borderleft ); ?>"  onkeypress="return isNumberKey(event)">
																</div>
															</div>
															<div class="bdp-border-cover bdp-border-label">
																	<span class="bdp-key-title">
																		<?php esc_html_e( 'Right', 'blog-designer-pro' ); ?>
																	</span><span class="bdp-px-prefix"><?php esc_html_e( '(px)', 'blog-designer-pro' ); ?></span>
																</div>
															<div class="bdp-border-cover">
																<div class="bdp-border-content">
																	<?php $bdp_readmore_button_borderright = isset( $bdp_settings['bdp_readmore_button_borderright'] ) ? $bdp_settings['bdp_readmore_button_borderright'] : '0'; ?>
																	<input type="number" id="bdp_readmore_button_borderright" name="bdp_readmore_button_borderright" step="1" min="0" value="<?php echo esc_attr( $bdp_readmore_button_borderright ); ?>" onkeypress="return isNumberKey(event)">
																</div>
															</div>
															<div class="bdp-border-cover bdp-border-label">
																	<span class="bdp-key-title">
																		<?php esc_html_e( 'Top', 'blog-designer-pro' ); ?>
																	</span><span class="bdp-px-prefix"><?php esc_html_e( '(px)', 'blog-designer-pro' ); ?></span>
																</div>
															<div class="bdp-border-cover">
																<div class="bdp-border-content">
																	<?php $bdp_readmore_button_bordertop = isset( $bdp_settings['bdp_readmore_button_bordertop'] ) ? $bdp_settings['bdp_readmore_button_bordertop'] : '0'; ?>
																	<input type="number" id="bdp_readmore_button_bordertop" name="bdp_readmore_button_bordertop" step="1" min="0" value="<?php echo esc_attr( $bdp_readmore_button_bordertop ); ?>" onkeypress="return isNumberKey(event)">
																</div>
															</div>
															<div class="bdp-border-cover bdp-border-label">
																<span class="bdp-key-title">
																	<?php esc_html_e( 'Bottom', 'blog-designer-pro' ); ?>
																</span><span class="bdp-px-prefix"><?php esc_html_e( '(px)', 'blog-designer-pro' ); ?></span>
															</div>
															<div class="bdp-border-cover">
																<div class="bdp-border-content">
																	<?php $bdp_readmore_button_borderbottom = isset( $bdp_settings['bdp_readmore_button_borderbottom'] ) ? $bdp_settings['bdp_readmore_button_borderbottom'] : '0'; ?>
																	<input type="number" id="bdp_readmore_button_borderbottom" name="bdp_readmore_button_borderbottom" step="1" min="0" value="<?php echo esc_attr( $bdp_readmore_button_borderbottom ); ?>" onkeypress="return isNumberKey(event)">
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
																	<?php $bdp_readmore_button_borderleftcolor = isset( $bdp_settings['bdp_readmore_button_borderleftcolor'] ) ? $bdp_settings['bdp_readmore_button_borderleftcolor'] : ''; ?>
																	<input type="text" name="bdp_readmore_button_borderleftcolor" id="bdp_readmore_button_borderleftcolor" value="<?php echo esc_attr( $bdp_readmore_button_borderleftcolor ); ?>"/>
																</div>
															</div>
															<div class="bdp-border-cover bdp-border-label">
																<span class="bdp-key-title">
																	<?php esc_html_e( 'Color', 'blog-designer-pro' ); ?>
																</span>
															</div>
															<div class="bdp-border-cover">
																<div class="bdp-border-content">
																	<?php $bdp_readmore_button_borderrightcolor = isset( $bdp_settings['bdp_readmore_button_borderrightcolor'] ) ? $bdp_settings['bdp_readmore_button_borderrightcolor'] : ''; ?>
																	<input type="text" name="bdp_readmore_button_borderrightcolor" id="bdp_readmore_button_borderrightcolor" value="<?php echo esc_attr( $bdp_readmore_button_borderrightcolor ); ?>"/>
																</div>
															</div>
															<div class="bdp-border-cover bdp-border-label">
																<span class="bdp-key-title">
																	<?php esc_html_e( 'Color', 'blog-designer-pro' ); ?>
																</span>
															</div>
															<div class="bdp-border-cover">
																<div class="bdp-border-content">
																	<?php $bdp_readmore_button_bordertopcolor = isset( $bdp_settings['bdp_readmore_button_bordertopcolor'] ) ? $bdp_settings['bdp_readmore_button_bordertopcolor'] : ''; ?>
																	<input type="text" name="bdp_readmore_button_bordertopcolor" id="bdp_readmore_button_bordertopcolor" value="<?php echo esc_attr( $bdp_readmore_button_bordertopcolor ); ?>"/>
																</div>
															</div>
															<div class="bdp-border-cover bdp-border-label">
																<span class="bdp-key-title">
																	<?php esc_html_e( 'Color', 'blog-designer-pro' ); ?>
																</span>
															</div>
															<div class="bdp-border-cover">
																<div class="bdp-border-content">
																<?php $bdp_readmore_button_borderbottomcolor = isset( $bdp_settings['bdp_readmore_button_borderbottomcolor'] ) ? $bdp_settings['bdp_readmore_button_borderbottomcolor'] : ''; ?>
																<input type="text" name="bdp_readmore_button_borderbottomcolor" id="bdp_readmore_button_borderbottomcolor" value="<?php echo esc_attr( $bdp_readmore_button_borderbottomcolor ); ?>"/>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="bdp-typography-cover bdp-typography-title-normal">
										<h3 class="bdp-table-title"><?php esc_html_e( 'Hover Settings', 'blog-designer-pro' ); ?></h3>
										<div class="bdp-typography-sub-cover read_more_hover_text_color">
											<div class="bdp-typography-label">
												<span class="bdp-key-title">
												<?php esc_html_e( 'Text Color', 'blog-designer-pro' ); ?>
												</span>
											</div>
											<div class="bdp-typography-content">
												<?php $template_readmorehovercolor = isset( $bdp_settings['template_readmorehovercolor'] ) ? $bdp_settings['template_readmorehovercolor'] : ''; ?>
												<input type="text" name="template_readmorehovercolor" id="template_readmorehovercolor" value="<?php echo esc_attr( $template_readmorehovercolor ); ?>"/>
											</div>
										</div>
										<div class="bdp-typography-sub-cover read_more_text_hover_background">
											<div class="bdp-typography-label">
												<span class="bdp-key-title">
												<?php esc_html_e( 'Background Color', 'blog-designer-pro' ); ?>
												</span>
											</div>
											<div class="bdp-typography-content">
												<input type="text" name="template_readmore_hover_backcolor" id="template_readmore_hover_backcolor" value="<?php echo ( isset( $bdp_settings['template_readmore_hover_backcolor'] ) && '' != $bdp_settings['template_readmore_hover_backcolor'] ) ? esc_attr( $bdp_settings['template_readmore_hover_backcolor'] ) : ''; ?>"/>
											</div>
										</div>
										<div class="bdp-typography-sub-cover read_more_button_border_radius_setting">
											<div class="bdp-typography-label">
												<span class="bdp-key-title">
												<?php esc_html_e( 'Border Radius(px)', 'blog-designer-pro' ); ?>
												</span>
											</div>
											<div class="bdp-typography-content">
												<?php $readmore_button_hover_border_radius = isset( $bdp_settings['readmore_button_hover_border_radius'] ) ? $bdp_settings['readmore_button_hover_border_radius'] : '0'; ?>
												<input type="number" id="readmore_button_hover_border_radius" name="readmore_button_hover_border_radius" step="1" min="0" value="<?php echo esc_attr( $readmore_button_hover_border_radius ); ?>" onkeypress="return isNumberKey(event)">
											</div>
										</div>
										<div class="bdp-typography-sub-cover read_more_button_border_setting">
											<div class="bdp-typography-label">
												<span class="bdp-key-title">
												<?php esc_html_e( 'Border Style', 'blog-designer-pro' ); ?>
												</span>
											</div>
											<div class="bdp-typography-content">
												<?php $read_more_button_hover_border_style = isset( $bdp_settings['read_more_button_hover_border_style'] ) ? $bdp_settings['read_more_button_hover_border_style'] : 'solid'; ?>
												<select name="read_more_button_hover_border_style" id="read_more_button_hover_border_style">
													<option value="none" <?php selected( $read_more_button_hover_border_style, 'none' ); ?>><?php esc_html_e( 'None', 'blog-designer-pro' ); ?></option>
													<option value="dotted" <?php selected( $read_more_button_hover_border_style, 'dotted' ); ?>><?php esc_html_e( 'Dotted', 'blog-designer-pro' ); ?></option>
													<option value="dashed" <?php selected( $read_more_button_hover_border_style, 'dashed' ); ?>><?php esc_html_e( 'Dashed', 'blog-designer-pro' ); ?></option>
													<option value="solid" <?php selected( $read_more_button_hover_border_style, 'solid' ); ?>><?php esc_html_e( 'Solid', 'blog-designer-pro' ); ?></option>
													<option value="double" <?php selected( $read_more_button_hover_border_style, 'double' ); ?>><?php esc_html_e( 'Double', 'blog-designer-pro' ); ?></option>
													<option value="groove" <?php selected( $read_more_button_hover_border_style, 'groove' ); ?>><?php esc_html_e( 'Groove', 'blog-designer-pro' ); ?></option>
													<option value="ridge" <?php selected( $read_more_button_hover_border_style, 'ridge' ); ?>><?php esc_html_e( 'Ridge', 'blog-designer-pro' ); ?></option>
													<option value="inset" <?php selected( $read_more_button_hover_border_style, 'inset' ); ?>><?php esc_html_e( 'Inset', 'blog-designer-pro' ); ?></option>
													<option value="outset" <?php selected( $read_more_button_hover_border_style, 'outset' ); ?> ><?php esc_html_e( 'Outset', 'blog-designer-pro' ); ?></option>
												</select>
											</div>
										</div>
										<div class="bdp-typography-sub-cover full-width read_more_button_border_setting">
											<div class="bdp-typography-label bdp-rmbb-main">
												<span class="bdp-key-title">
													<?php esc_html_e( 'Read More Button Hover Border', 'blog-designer-pro' ); ?>
												</span>
											</div>
											<div class="bdp-typography-content">
												<div class="bdp-border-wrap">
													<div class="bdp-border-wrapper bdp-border-wrapper1 bdp_grid_col_2">
														<div class="bdp-rmb-border-inner">
															<div class="bdp-border-cover bdp-border-label">
																	<span class="bdp-key-title">
																		<?php esc_html_e( 'Left', 'blog-designer-pro' ); ?>
																	</span><span class="bdp-px-prefix"><?php esc_html_e( '(px)', 'blog-designer-pro' ); ?></span>
															</div>
															<div class="bdp-border-cover">
																<div class="bdp-border-content">
																	<?php $bdp_readmore_button_hover_borderleft = isset( $bdp_settings['bdp_readmore_button_hover_borderleft'] ) ? $bdp_settings['bdp_readmore_button_hover_borderleft'] : '0'; ?>
																	<input type="number" id="bdp_readmore_button_hover_borderleft" name="bdp_readmore_button_hover_borderleft" step="1" min="0" value="<?php echo esc_attr( $bdp_readmore_button_hover_borderleft ); ?>"  onkeypress="return isNumberKey(event)">
																</div>
															</div>
															<div class="bdp-border-cover bdp-border-label">
																<span class="bdp-key-title">
																	<?php esc_html_e( 'Right', 'blog-designer-pro' ); ?>
																</span><span class="bdp-px-prefix"><?php esc_html_e( '(px)', 'blog-designer-pro' ); ?></span>
															</div>
															<div class="bdp-border-cover">
																<div class="bdp-border-content">
																	<?php $bdp_readmore_button_hover_borderright = isset( $bdp_settings['bdp_readmore_button_hover_borderright'] ) ? $bdp_settings['bdp_readmore_button_hover_borderright'] : '0'; ?>
																	<input type="number" id="bdp_readmore_button_hover_borderright" name="bdp_readmore_button_hover_borderright" step="1" min="0" value="<?php echo esc_attr( $bdp_readmore_button_hover_borderright ); ?>" onkeypress="return isNumberKey(event)">
																</div>
															</div>
															<div class="bdp-border-cover bdp-border-label">
																<span class="bdp-key-title">
																	<?php esc_html_e( 'Top', 'blog-designer-pro' ); ?>
																</span><span class="bdp-px-prefix"><?php esc_html_e( '(px)', 'blog-designer-pro' ); ?></span>
															</div>
															<div class="bdp-border-cover">
																<div class="bdp-border-content">
																	<?php $bdp_readmore_button_hover_bordertop = isset( $bdp_settings['bdp_readmore_button_hover_bordertop'] ) ? $bdp_settings['bdp_readmore_button_hover_bordertop'] : '0'; ?>
																	<input type="number" id="bdp_readmore_button_hover_bordertop" name="bdp_readmore_button_hover_bordertop" step="1" min="0" value="<?php echo esc_attr( $bdp_readmore_button_hover_bordertop ); ?>" onkeypress="return isNumberKey(event)">
																</div>
															</div>
															<div class="bdp-border-cover bdp-border-label">
																<span class="bdp-key-title">
																	<?php esc_html_e( 'Bottom', 'blog-designer-pro' ); ?>
																</span><span class="bdp-px-prefix"><?php esc_html_e( '(px)', 'blog-designer-pro' ); ?></span>
															</div>
															<div class="bdp-border-cover">
																<div class="bdp-border-content">
																	<?php $bdp_readmore_button_hover_borderbottom = isset( $bdp_settings['bdp_readmore_button_hover_borderbottom'] ) ? $bdp_settings['bdp_readmore_button_hover_borderbottom'] : '0'; ?>
																	<input type="number" id="bdp_readmore_button_hover_borderbottom" name="bdp_readmore_button_hover_borderbottom" step="1" min="0" value="<?php echo esc_attr( $bdp_readmore_button_hover_borderbottom ); ?>" onkeypress="return isNumberKey(event)">
																</div>
															</div>
														</div>
														<div class="bdp-rmb-border-inner">
															<div class="bdp-border-cover bdp-border-label">
																<span class="bdp-key-title">
																	<?php esc_html_e( 'Color', 'blog-designer-pro' ); ?>
																</span>
															</div>
															<div class="bdp-border-cover">
																<div class="bdp-border-content">
																	<?php $bdp_readmore_button_hover_borderleftcolor = isset( $bdp_settings['bdp_readmore_button_hover_borderleftcolor'] ) ? $bdp_settings['bdp_readmore_button_hover_borderleftcolor'] : ''; ?>
																	<input type="text" name="bdp_readmore_button_hover_borderleftcolor" id="bdp_readmore_button_hover_borderleftcolor" value="<?php echo esc_attr( $bdp_readmore_button_hover_borderleftcolor ); ?>"/>
																</div>
															</div>
															<div class="bdp-border-cover bdp-border-label">
																<span class="bdp-key-title">
																	<?php esc_html_e( 'Color', 'blog-designer-pro' ); ?>
																</span>
															</div>
															<div class="bdp-border-cover">
																<div class="bdp-border-content">
																<?php $bdp_readmore_button_hover_borderrightcolor = isset( $bdp_settings['bdp_readmore_button_hover_borderrightcolor'] ) ? $bdp_settings['bdp_readmore_button_hover_borderrightcolor'] : ''; ?>
																	<input type="text" name="bdp_readmore_button_hover_borderrightcolor" id="bdp_readmore_button_hover_borderrightcolor" value="<?php echo esc_attr( $bdp_readmore_button_hover_borderrightcolor ); ?>"/>
																</div>
															</div>
															<div class="bdp-border-cover bdp-border-label">
																<span class="bdp-key-title">
																	<?php esc_html_e( 'Color', 'blog-designer-pro' ); ?>
																</span>
															</div>
															<div class="bdp-border-cover">
																<div class="bdp-border-content">
																	<?php $bdp_readmore_button_hover_bordertopcolor = isset( $bdp_settings['bdp_readmore_button_hover_bordertopcolor'] ) ? $bdp_settings['bdp_readmore_button_hover_bordertopcolor'] : ''; ?>
																	<input type="text" name="bdp_readmore_button_hover_bordertopcolor" id="bdp_readmore_button_hover_bordertopcolor" value="<?php echo esc_attr( $bdp_readmore_button_hover_bordertopcolor ); ?>"/>
																</div>
															</div>
															<div class="bdp-border-cover bdp-border-label">
																<span class="bdp-key-title">
																	<?php esc_html_e( 'Color', 'blog-designer-pro' ); ?>
																</span>
															</div>
															<div class="bdp-border-cover">
																<div class="bdp-border-content">
																<?php $bdp_readmore_button_hover_borderbottomcolor = isset( $bdp_settings['bdp_readmore_button_hover_borderbottomcolor'] ) ? $bdp_settings['bdp_readmore_button_hover_borderbottomcolor'] : ''; ?>
																<input type="text" name="bdp_readmore_button_hover_borderbottomcolor" id="bdp_readmore_button_hover_borderbottomcolor" value="<?php echo esc_attr( $bdp_readmore_button_hover_borderbottomcolor ); ?>"/>
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
							<h3 class="read_more_wrap bdp-table-title read_more_button_border_setting"><?php esc_html_e( 'Read More Button Padding', 'blog-designer-pro' ); ?></h3>
							<li class="read_more_wrap edd_addtocart_button_box_shadow_setting read_more_button_border_setting">
								<div class="bdp-boxshadow-wrapper bdp-boxshadow-wrapper1">
									<div class="bdp-boxshadow-cover">
										<div class="bdp-boxshadow-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Left', 'blog-designer-pro' ); ?>
											</span><span class="bdp-px-prefix"><?php echo '(px)'; ?></span>
										</div>
										<div class="bdp-boxshadow-content">
											<?php $readmore_button_paddingleft = isset( $bdp_settings['readmore_button_paddingleft'] ) ? $bdp_settings['readmore_button_paddingleft'] : '10'; ?>
											<input type="number" id="readmore_button_paddingleft" name="readmore_button_paddingleft" step="1" min="0" value="<?php echo esc_attr( $readmore_button_paddingleft ); ?>" onkeypress="return isNumberKey(event)">
										</div>
									</div>
									<div class="bdp-boxshadow-cover">
										<div class="bdp-boxshadow-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Right', 'blog-designer-pro' ); ?>
											</span><span class="bdp-px-prefix"><?php echo '(px)'; ?></span>
										</div>
										<div class="bdp-boxshadow-content">
											<?php $readmore_button_paddingright = isset( $bdp_settings['readmore_button_paddingright'] ) ? $bdp_settings['readmore_button_paddingright'] : '10'; ?>
											<input type="number" id="readmore_button_paddingright" name="readmore_button_paddingright" step="1" min="0" value="<?php echo esc_attr( $readmore_button_paddingright ); ?>" onkeypress="return isNumberKey(event)">
										</div>
									</div>
									<div class="bdp-boxshadow-cover">
										<div class="bdp-boxshadow-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Top', 'blog-designer-pro' ); ?>
											</span><span class="bdp-px-prefix"><?php echo '(px)'; ?></span>
										</div>
										<div class="bdp-boxshadow-content">
											<?php $readmore_button_paddingtop = isset( $bdp_settings['readmore_button_paddingtop'] ) ? $bdp_settings['readmore_button_paddingtop'] : '10'; ?>
											<input type="number" id="readmore_button_paddingtop" name="readmore_button_paddingtop" step="1" min="0" value="<?php echo esc_attr( $readmore_button_paddingtop ); ?>"  onkeypress="return isNumberKey(event)">
										</div>
									</div>
									<div class="bdp-boxshadow-cover">
										<div class="bdp-boxshadow-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Bottom', 'blog-designer-pro' ); ?>
											</span><span class="bdp-px-prefix"><?php echo '(px)'; ?></span>
										</div>
										<div class="bdp-boxshadow-content">
											<?php $readmore_button_paddingbottom = isset( $bdp_settings['readmore_button_paddingbottom'] ) ? $bdp_settings['readmore_button_paddingbottom'] : '10'; ?>
											<input type="number" id="readmore_button_paddingbottom" name="readmore_button_paddingbottom" step="1" min="0" value="<?php echo esc_attr( $readmore_button_paddingbottom ); ?>" onkeypress="return isNumberKey(event)">
										</div>
									</div>
								</div>
							</li>
							<h3 class="read_more_wrap bdp-table-title read_more_button_border_setting"><?php esc_html_e( 'Read More Button Margin', 'blog-designer-pro' ); ?></h3>
							<li class="read_more_wrap edd_addtocart_button_box_shadow_setting read_more_button_border_setting">
								<div class="bdp-boxshadow-wrapper bdp-boxshadow-wrapper1">
									<div class="bdp-boxshadow-cover">
										<div class="bdp-boxshadow-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Left', 'blog-designer-pro' ); ?>
											</span><span class="bdp-px-prefix"><?php echo '(px)'; ?></span>
										</div>
										<div class="bdp-boxshadow-content">
											<?php $readmore_button_marginleft = isset( $bdp_settings['readmore_button_marginleft'] ) ? $bdp_settings['readmore_button_marginleft'] : 0; ?>
											<input type="number" id="readmore_button_marginleft" name="readmore_button_marginleft" step="1" value="<?php echo esc_attr( $readmore_button_marginleft ); ?>" onkeypress="return isNumberKey(event)">
										</div>
									</div>
									<div class="bdp-boxshadow-cover">
										<div class="bdp-boxshadow-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Right', 'blog-designer-pro' ); ?>
											</span><span class="bdp-px-prefix"><?php echo '(px)'; ?></span>
										</div>
										<div class="bdp-boxshadow-content">
											<?php $readmore_button_marginright = isset( $bdp_settings['readmore_button_marginright'] ) ? $bdp_settings['readmore_button_marginright'] : 0; ?>
											<input type="number" id="readmore_button_marginright" name="readmore_button_marginright" step="1" value="<?php echo esc_attr( $readmore_button_marginright ); ?>" onkeypress="return isNumberKey(event)">
										</div>
									</div>
									<div class="bdp-boxshadow-cover">
										<div class="bdp-boxshadow-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Top', 'blog-designer-pro' ); ?>
											</span><span class="bdp-px-prefix"><?php echo '(px)'; ?></span>
										</div>
										<div class="bdp-boxshadow-content">
											<?php $readmore_button_margintop = isset( $bdp_settings['readmore_button_margintop'] ) ? $bdp_settings['readmore_button_margintop'] : 0; ?>
											<input type="number" id="readmore_button_margintop" name="readmore_button_margintop" step="1" value="<?php echo esc_attr( $readmore_button_margintop ); ?>"  onkeypress="return isNumberKey(event)">
										</div>
									</div>
									<div class="bdp-boxshadow-cover">
										<div class="bdp-boxshadow-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Bottom', 'blog-designer-pro' ); ?>
											</span><span class="bdp-px-prefix"><?php echo '(px)'; ?></span>
										</div>
										<div class="bdp-boxshadow-content">
											<?php $readmore_button_marginbottom = isset( $bdp_settings['readmore_button_marginbottom'] ) ? $bdp_settings['readmore_button_marginbottom'] : 0; ?>
											<input type="number" id="readmore_button_marginbottom" name="readmore_button_marginbottom" step="1" value="<?php echo esc_attr( $readmore_button_marginbottom ); ?>" onkeypress="return isNumberKey(event)">
										</div>
									</div>
								</div>
							</li>
							<h3 class="read_more_wrap bdp-table-title read_more_text_typography_setting"><?php esc_html_e( 'Read More Typography Settings', 'blog-designer-pro' ); ?></h3>
							<li class="read_more_wrap read_more_text_typography_setting">
								<div class="bdp-typography-wrapper bdp-typography-wrapper1 bdp_grid_col_4">
									<div class="bdp-typography-cover">
										<div class="bdp-typography-label">
											<span class="bdp-key-title">
											<?php esc_html_e( 'Font Family', 'blog-designer-pro' ); ?>
											</span>
										</div>
										<div class="bdp-typography-content">
											<?php
											if ( isset( $bdp_settings['readmore_font_family'] ) && '' != $bdp_settings['readmore_font_family'] ) {
												$readmore_font_family = $bdp_settings['readmore_font_family'];
											} else {
												$readmore_font_family = '';
											}
											?>
											<div class="typo-field">
												<input type="hidden" id="readmore_font_family_font_type" name="readmore_font_family_font_type" value="<?php echo isset( $bdp_settings['readmore_font_family_font_type'] ) ? esc_attr( $bdp_settings['readmore_font_family_font_type'] ) : ''; ?>">
												<div class="select-cover">
													<select name="readmore_font_family" id="readmore_font_family">
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
															if ( '' != $readmore_font_family && ( str_replace( '"', '', $readmore_font_family ) == str_replace( '"', '', $value['label'] ) ) ) {
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
											</span><span class="bdp-px-prefix"><?php esc_html_e( '(px)', 'blog-designer-pro' ); ?></span>
										</div>
										<div class="bdp-typography-content">
										<?php $readmore_fontsize = isset( $bdp_settings['readmore_fontsize'] ) ? $bdp_settings['readmore_fontsize'] : '14'; ?>
											<input type="number" class="grid_col_space_val" name="readmore_fontsize" id="readmore_fontsize" value="<?php echo esc_attr( $readmore_fontsize ); ?>" onkeypress="return isNumberKey(event)" />
										</div>
									</div>

									<div class="bdp-typography-cover">
										<div class="bdp-typography-label">
											<span class="bdp-key-title">
											<?php esc_html_e( 'Font Weight', 'blog-designer-pro' ); ?>
											</span>
										</div>
										<div class="bdp-typography-content">
											<?php $readmore_font_weight = isset( $bdp_settings['readmore_font_weight'] ) ? $bdp_settings['readmore_font_weight'] : 'normal'; ?>
											<div class="select-cover">
												<select name="readmore_font_weight" id="readmore_font_weight">
													<option value="100" <?php selected( $readmore_font_weight, 100 ); ?>>100</option>
													<option value="200" <?php selected( $readmore_font_weight, 200 ); ?>>200</option>
													<option value="300" <?php selected( $readmore_font_weight, 300 ); ?>>300</option>
													<option value="400" <?php selected( $readmore_font_weight, 400 ); ?>>400</option>
													<option value="500" <?php selected( $readmore_font_weight, 500 ); ?>>500</option>
													<option value="600" <?php selected( $readmore_font_weight, 600 ); ?>>600</option>
													<option value="700" <?php selected( $readmore_font_weight, 700 ); ?>>700</option>
													<option value="800" <?php selected( $readmore_font_weight, 800 ); ?>>800</option>
													<option value="900" <?php selected( $readmore_font_weight, 900 ); ?>>900</option>
													<option value="bold" <?php selected( $readmore_font_weight, 'bold' ); ?> ><?php esc_html_e( 'Bold', 'blog-designer-pro' ); ?></option>
													<option value="normal" <?php selected( $readmore_font_weight, 'normal' ); ?>><?php esc_html_e( 'Normal', 'blog-designer-pro' ); ?></option>
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
												<input type="number" name="readmore_font_line_height" id="readmore_font_line_height" step="0.1" min="0" value="<?php echo isset( $bdp_settings['readmore_font_line_height'] ) ? esc_attr( $bdp_settings['readmore_font_line_height'] ) : '1.5'; ?>" onkeypress="return isNumberKey(event)">
										</div>
									</div>

									<div class="bdp-typography-cover">
										<div class="bdp-typography-label">
											<span class="bdp-key-title">
											<?php esc_html_e( 'Text Transform', 'blog-designer-pro' ); ?>
											</span>
										</div>
										<div class="bdp-typography-content">
											<?php $readmore_font_text_transform = isset( $bdp_settings['readmore_font_text_transform'] ) ? $bdp_settings['readmore_font_text_transform'] : 'none'; ?>
												<div class="select-cover">
													<select name="readmore_font_text_transform" id="readmore_font_text_transform">
														<option <?php selected( $readmore_font_text_transform, 'none' ); ?> value="none"><?php esc_html_e( 'None', 'blog-designer-pro' ); ?></option>
														<option <?php selected( $readmore_font_text_transform, 'capitalize' ); ?> value="capitalize"><?php esc_html_e( 'Capitalize', 'blog-designer-pro' ); ?></option>
														<option <?php selected( $readmore_font_text_transform, 'uppercase' ); ?> value="uppercase"><?php esc_html_e( 'Uppercase', 'blog-designer-pro' ); ?></option>
														<option <?php selected( $readmore_font_text_transform, 'lowercase' ); ?> value="lowercase"><?php esc_html_e( 'Lowercase', 'blog-designer-pro' ); ?></option>
														<option <?php selected( $readmore_font_text_transform, 'full-width' ); ?> value="full-width"><?php esc_html_e( 'Full Width', 'blog-designer-pro' ); ?></option>
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
											<?php $readmore_font_text_decoration = isset( $bdp_settings['readmore_font_text_decoration'] ) ? $bdp_settings['readmore_font_text_decoration'] : 'none'; ?>
											<div class="select-cover">
												<select name="readmore_font_text_decoration" id="readmore_font_text_decoration">
													<option <?php selected( $readmore_font_text_decoration, 'none' ); ?> value="none"><?php esc_html_e( 'None', 'blog-designer-pro' ); ?></option>
													<option <?php selected( $readmore_font_text_decoration, 'underline' ); ?> value="underline"><?php esc_html_e( 'Underline', 'blog-designer-pro' ); ?></option>
													<option <?php selected( $readmore_font_text_decoration, 'overline' ); ?> value="overline"><?php esc_html_e( 'Overline', 'blog-designer-pro' ); ?></option>
													<option <?php selected( $readmore_font_text_decoration, 'line-through' ); ?> value="line-through"><?php esc_html_e( 'Line Through', 'blog-designer-pro' ); ?></option>
												</select>
											</div>
										</div>
									</div>

									<div class="bdp-typography-cover">
										<div class="bdp-typography-label">
											<span class="bdp-key-title">
											<?php esc_html_e( 'Letter Spacing', 'blog-designer-pro' ); ?>
											</span><span class="bdp-px-prefix"><?php esc_html_e( '(px)', 'blog-designer-pro' ); ?></span>
										</div>
										<div class="bdp-typography-content">
											<input type="number" name="readmore_font_letter_spacing" id="readmore_font_letter_spacing" step="1" min="0" value="<?php echo isset( $bdp_settings['readmore_font_letter_spacing'] ) ? esc_attr( $bdp_settings['readmore_font_letter_spacing'] ) : '0'; ?>" onkeypress="return isNumberKey(event)">
										</div>
									</div>
									<div class="bdp-typography-cover">
										<div class="bdp-typography-label">
											<span class="bdp-key-title">
											<?php esc_html_e( 'Italic Font Style', 'blog-designer-pro' ); ?>
											</span>
										</div>
										<div class="bdp-typography-content">
											<?php $readmore_font_italic = isset( $bdp_settings['readmore_font_italic'] ) ? $bdp_settings['readmore_font_italic'] : '0'; ?>
											<fieldset class="bdp-social-options bdp-display_author buttonset">
												<input id="readmore_font_italic_1" name="readmore_font_italic" type="radio" value="1"  <?php checked( 1, $readmore_font_italic ); ?> />
												<label for="readmore_font_italic_1"><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
												<input id="readmore_font_italic_0" name="readmore_font_italic" type="radio" value="0" <?php checked( 0, $readmore_font_italic ); ?> />
												<label for="readmore_font_italic_0"><?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
											</fieldset>
										</div>
									</div>
								</div>
							</li>
							<li class="sprecrum_date_color">
								<div class="bdp-left">
									<span class="bdp-key-title">
										<?php esc_html_e( 'Use Readmore Color Selection on Date', 'blog-designer-pro' ); ?>
									</span>
								</div>
								<div class="bdp-right">
								<?php $date_color_of_readmore = isset( $bdp_settings['date_color_of_readmore'] ) ? $bdp_settings['date_color_of_readmore'] : '0'; ?>
									<fieldset class="bdp-social-options bdp-display_author buttonset">
										<input id="date_color_of_readmore_1" name="date_color_of_readmore" type="radio" value="1"  <?php checked( 1, $date_color_of_readmore ); ?> />
										<label for="date_color_of_readmore_1"><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
										<input id="date_color_of_readmore_0" name="date_color_of_readmore" type="radio" value="0" <?php checked( 0, $date_color_of_readmore ); ?> />
										<label for="date_color_of_readmore_0"><?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
									</fieldset>
								</div>
							</li>
						</ul>
					</div>
				</div>
				<div id="bdpmedia" class="postbox postbox-with-fw-options" style=<?php echo esc_attr( $bdpmedia_class_show ); ?>>
					<div class="inside">
						<ul class="bdp-settings bdp-lineheight">
							<div class="bdp_grid_col_3">
								<li class="display_feature_image_tr">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Display Post Feature Image', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<?php $display_feature_image = isset( $bdp_settings['display_feature_image'] ) ? $bdp_settings['display_feature_image'] : '1'; ?>
										<fieldset class="bdp-social-options bdp-display_feature_image buttonset">
											<input id="display_feature_image_1" name="display_feature_image" type="radio" value="1" <?php echo checked( 1, $display_feature_image ); ?> />
											<label for="display_feature_image_1"><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
											<input id="display_feature_image_0" name="display_feature_image" type="radio" value="0" <?php echo checked( 0, $display_feature_image ); ?> />
											<label for="display_feature_image_0"><?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
										</fieldset>
									</div>
								</li>

								<li class="thumbnail_skin_tr display_image_tr">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Post Thumbnail Skin', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<?php $thumbnail_skin = ( isset( $bdp_settings['thumbnail_skin'] ) ) ? $bdp_settings['thumbnail_skin'] : 1; ?>
										<fieldset class="bdp-thumbnail_skin buttonset buttonset-hide green" data-hide='1'>
											<input id="thumbnail_skin_0" name="thumbnail_skin" type="radio" value="0" <?php checked( 0, $thumbnail_skin ); ?> />
											<label id="bdp-options-button" for="thumbnail_skin_0" <?php checked( 0, $thumbnail_skin ); ?>><?php esc_html_e( 'Square', 'blog-designer-pro' ); ?></label>
											<input id="thumbnail_skin_1" name="thumbnail_skin" type="radio" value="1" <?php checked( 1, $thumbnail_skin ); ?> />
											<label id="bdp-options-button" for="thumbnail_skin_1" <?php checked( 1, $thumbnail_skin ); ?>><?php esc_html_e( 'Circle', 'blog-designer-pro' ); ?></label>
										</fieldset>
									</div>
								</li>

								<li class="blog-grid-height-tr">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Blog Grid Height', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<?php $blog_grid_height = ( isset( $bdp_settings['blog_grid_height'] ) ) ? $bdp_settings['blog_grid_height'] : 1; ?>
										<fieldset class="buttonset blog_grid_height green">
											<input id="blog_grid_height_0" name="blog_grid_height" type="radio" value="0" <?php checked( 0, $blog_grid_height ); ?> />
											<label id="bdp-options-button" for="blog_grid_height_0" <?php checked( 0, $blog_grid_height ); ?>><?php esc_html_e( 'Full', 'blog-designer-pro' ); ?></label>
											<input id="blog_grid_height_1" name="blog_grid_height" type="radio" value="1" <?php checked( 1, $blog_grid_height ); ?> />
											<label id="bdp-options-button" for="blog_grid_height_1" <?php checked( 1, $blog_grid_height ); ?>><?php esc_html_e( 'Fixed', 'blog-designer-pro' ); ?></label>
										</fieldset>
									</div>
								</li>

								<li class="easy_timeline_effect_tr display_image_tr">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Posts Effect', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<?php $easy_timeline_effect = isset( $bdp_settings['easy_timeline_effect'] ) ? $bdp_settings['easy_timeline_effect'] : 'default-effect'; ?>
										<select name="easy_timeline_effect" id="easy_timeline_effect">
											<option value="default-effect" <?php echo ( 'default-effect' === $easy_timeline_effect ) ? 'selected="selected"' : ''; ?>><?php esc_html_e( 'Default', 'blog-designer-pro' ); ?></option>
											<option value="slide-down-up-effect" <?php echo ( 'slide-down-up-effect' === $easy_timeline_effect ) ? 'selected="selected"' : ''; ?>><?php esc_html_e( 'Slide Down Up', 'blog-designer-pro' ); ?></option>
											<option value="slide-up-down-effect" <?php echo ( 'slide-up-down-effect' === $easy_timeline_effect ) ? 'selected="selected"' : ''; ?>><?php esc_html_e( 'Slide Up Down', 'blog-designer-pro' ); ?></option>
											<option value="slide-right-left-effect" <?php echo ( 'slide-right-left-effect' === $easy_timeline_effect ) ? 'selected="selected"' : ''; ?>><?php esc_html_e( 'Slide Right Left', 'blog-designer-pro' ); ?></option>
											<option value="slide-left-right-effect" <?php echo ( 'slide-left-right-effect' === $easy_timeline_effect ) ? 'selected="selected"' : ''; ?>><?php esc_html_e( 'Slide Left Right', 'blog-designer-pro' ); ?></option>
											<option value="flip-effect" <?php echo ( 'flip-effect' === $easy_timeline_effect ) ? 'selected="selected"' : ''; ?>><?php esc_html_e( 'Flip Effect', 'blog-designer-pro' ); ?></option>
											<option value="transformation-effect" <?php echo ( 'transformation-effect' === $easy_timeline_effect ) ? 'selected="selected"' : ''; ?>><?php esc_html_e( 'Transformation Eeffect', 'blog-designer-pro' ); ?></option>
										</select>
									</div>
								</li>

								<li class="blog-post-grid-height-tr">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Blog Grid Height', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<?php $template_grid_height = ( isset( $bdp_settings['template_grid_height'] ) ) ? $bdp_settings['template_grid_height'] : 300; ?>
										<input type="number" name="template_grid_height" id="template_grid_height" value="<?php echo esc_attr( $template_grid_height ); ?>"/>
									</div>
								</li>

								<li class="blog-gridskin-tr">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Blog Grid Skin', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<?php $bdp_settings['template_grid_skin'] = ( isset( $bdp_settings['template_grid_skin'] ) && '' != $bdp_settings['template_grid_skin'] ) ? $bdp_settings['template_grid_skin'] : 'default'; ?>
										<select name="template_grid_skin" id="template_grid_skin">
											<option value="default" 
											<?php
											if ( 'default' === $bdp_settings['template_grid_skin'] ) {
												?>
												selected="selected"<?php } ?>>
												<?php esc_html_e( 'Default Skin', 'blog-designer-pro' ); ?>
											</option>
											<option value="repeat" 
											<?php
											if ( 'repeat' === $bdp_settings['template_grid_skin'] ) {
												?>
												selected="selected"<?php } ?>>
												<?php esc_html_e( 'Repeat Skin', 'blog-designer-pro' ); ?>
											</option>
											<option class="only_explore" value="reverse" 
											<?php
											if ( 'reverse' === $bdp_settings['template_grid_skin'] ) {
												?>
												selected="selected"<?php } ?>>
												<?php esc_html_e( 'Reverse Skin', 'blog-designer-pro' ); ?>
											</option>
										</select>
									</div>
								</li>
								<li class="gridcol_space_tr">
									<div class="bdp-left"><span class="bdp-key-title"><?php esc_html_e( 'Blog Grid column space', 'blog-designer-pro' ); ?></span><span class="bdp-px-prefix"><?php esc_html_e( '(px)', 'blog-designer-pro' ); ?></span></div>
									<div class="bdp-right">
										<?php $grid_col_space = ( isset( $bdp_settings['grid_col_space'] ) && '' != $bdp_settings['grid_col_space'] ) ? $bdp_settings['grid_col_space'] : 10; ?>
											<input type="number" class="grid_col_space_val" name="grid_col_space" id="grid_col_spaceOutputId" value="<?php echo esc_attr( $grid_col_space ); ?>" />
									</div>
								</li>
								<li class="display_image_tr">
									<div class="bdp-left"><span class="bdp-key-title"><?php esc_html_e( 'Post Image Link', 'blog-designer-pro' ); ?></span></div>
									<div class="bdp-right">
										<?php $bdp_post_image_link = isset( $bdp_settings['bdp_post_image_link'] ) ? $bdp_settings['bdp_post_image_link'] : '1'; ?>
										<fieldset class="buttonset buttonset-hide" data-hide='1'>
											<input id="bdp_post_image_link_1" name="bdp_post_image_link" type="radio" value="1" <?php checked( 1, $bdp_post_image_link ); ?> />
											<label id="bdp-options-button" for="bdp_post_image_link_1" <?php checked( 1, $bdp_post_image_link ); ?>><?php esc_html_e( 'Enable', 'blog-designer-pro' ); ?></label>
											<input id="bdp_post_image_link_0" name="bdp_post_image_link" type="radio" value="0" <?php checked( 0, $bdp_post_image_link ); ?> />
											<label id="bdp-options-button" for="bdp_post_image_link_0" <?php checked( 0, $bdp_post_image_link ); ?>><?php esc_html_e( 'Disable', 'blog-designer-pro' ); ?></label>
										</fieldset>
									</div>
								</li>
								<li class="bdp-image-hover-effect display_image_tr">
									<div class="bdp-left"><span class="bdp-key-title"><?php esc_html_e( 'Post Image Hover Effect', 'blog-designer-pro' ); ?></span></div>
									<div class="bdp-right">
										<?php $bdp_image_hover_effect = isset( $bdp_settings['bdp_image_hover_effect'] ) ? $bdp_settings['bdp_image_hover_effect'] : '0'; ?>
										<fieldset class="buttonset buttonset-hide" data-hide='1'>
											<input id="bdp_image_hover_effect_1" name="bdp_image_hover_effect" type="radio" value="1" <?php checked( 1, $bdp_image_hover_effect ); ?> />
											<label id="bdp-options-button" for="bdp_image_hover_effect_1" <?php checked( 1, $bdp_image_hover_effect ); ?>><?php esc_html_e( 'Enable', 'blog-designer-pro' ); ?></label>
											<input id="bdp_image_hover_effect_0" name="bdp_image_hover_effect" type="radio" value="0" <?php checked( 0, $bdp_image_hover_effect ); ?> />
											<label id="bdp-options-button" for="bdp_image_hover_effect_0" <?php checked( 0, $bdp_image_hover_effect ); ?>><?php esc_html_e( 'Disable', 'blog-designer-pro' ); ?></label>
										</fieldset>
									</div>
								</li>
								<li class="bdp-image-hover-effect-tr bdp-image-hover-effect display_image_tr">
									<div class="bdp-left"><span class="bdp-key-title"><?php esc_html_e( 'Select Post Image Hover Effect', 'blog-designer-pro' ); ?></span></div>
									<div class="bdp-right">
										<?php $bdp_image_hover_effect_type = isset( $bdp_settings['bdp_image_hover_effect_type'] ) ? $bdp_settings['bdp_image_hover_effect_type'] : 'zoom_in'; ?>
										<select name="bdp_image_hover_effect_type" id="bdp_image_hover_effect_type">
											<option value="blur" <?php echo ( 'blur' === $bdp_image_hover_effect_type ) ? 'selected="selected"' : ''; ?>><?php esc_html_e( 'Blur', 'blog-designer-pro' ); ?></option>
											<option value="flashing" <?php echo ( 'flashing' === $bdp_image_hover_effect_type ) ? 'selected="selected"' : ''; ?>><?php esc_html_e( 'Flashing', 'blog-designer-pro' ); ?></option>
											<option value="gray_scale" <?php echo ( 'gray_scale' === $bdp_image_hover_effect_type ) ? 'selected="selected"' : ''; ?>><?php esc_html_e( 'Gray Scale', 'blog-designer-pro' ); ?></option>
											<option value="opacity" <?php echo ( 'opacity' === $bdp_image_hover_effect_type ) ? 'selected="selected"' : ''; ?>><?php esc_html_e( 'Opacity', 'blog-designer-pro' ); ?></option>
											<option value="sepia" <?php echo ( 'sepia' === $bdp_image_hover_effect_type ) ? 'selected="selected"' : ''; ?>><?php esc_html_e( 'Sepia', 'blog-designer-pro' ); ?></option>
											<option value="slide" <?php echo ( 'slide' === $bdp_image_hover_effect_type ) ? 'selected="selected"' : ''; ?>><?php esc_html_e( 'Slide', 'blog-designer-pro' ); ?></option>
											<option value="shine" <?php echo ( 'shine' === $bdp_image_hover_effect_type ) ? 'selected="selected"' : ''; ?>><?php esc_html_e( 'Shine', 'blog-designer-pro' ); ?></option>
											<option value="shine_circle" <?php echo ( 'shine_circle' === $bdp_image_hover_effect_type ) ? 'selected="selected"' : ''; ?>><?php esc_html_e( 'Shine Circle', 'blog-designer-pro' ); ?></option>
											<option value="zoom_in" <?php echo ( 'zoom_in' === $bdp_image_hover_effect_type ) ? 'selected="selected"' : ''; ?>><?php esc_html_e( 'Zoom In', 'blog-designer-pro' ); ?></option>
											<option value="zoom_out" <?php echo ( 'zoom_out' === $bdp_image_hover_effect_type ) ? 'selected="selected"' : ''; ?>><?php esc_html_e( 'Zoom Out', 'blog-designer-pro' ); ?></option>
										</select>
									</div>
								</li>
								<li class="bdp_media_size_tr display_image_tr">
									<div class="bdp-left"><span class="bdp-key-title"><?php esc_html_e( 'Select Post Media Size', 'blog-designer-pro' ); ?></span></div>
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
													<option value="<?php echo esc_attr( $s ); ?>" <?php echo ( isset( $bdp_settings['bdp_media_size'] ) && $bdp_settings['bdp_media_size'] == $s ) ? 'selected="selected"' : ''; ?>> <?php echo esc_attr( $s ) . ' (' . esc_html( get_option( $s . '_size_w' ) ) . 'x' . esc_html( get_option( $s . '_size_h' ) ) . ')'; ?> </option>
													<?php
												} elseif ( isset( $_wp_additional_image_sizes ) && isset( $_wp_additional_image_sizes[ $s ] ) ) {
													?>
														<option value="<?php echo esc_attr( $s ); ?>" <?php echo ( isset( $bdp_settings['bdp_media_size'] ) && $bdp_settings['bdp_media_size'] == $s ) ? 'selected="selected"' : ''; ?>> <?php echo esc_attr( $s ) . ' (' . esc_html( $_wp_additional_image_sizes[ $s ]['width'] ) . 'x' . esc_html( $_wp_additional_image_sizes[ $s ]['height'] ) . ')'; ?> </option>
														<?php

												}
											}
											?>
											<option value="custom" <?php echo ( isset( $bdp_settings['bdp_media_size'] ) && 'custom' === $bdp_settings['bdp_media_size'] ) ? 'selected="selected"' : ''; ?>><?php esc_html_e( 'Custom Size', 'blog-designer-pro' ); ?></option>
										</select>
									</div>
								</li>
								<li class="display_image_tr bdp_media_upload_tab">
									<div class="bdp-left"><span class="bdp-key-title"><?php esc_html_e( 'Select Post Default Image', 'blog-designer-pro' ); ?></span></div>
									<div class="bdp-right">
										<span class="bdp_default_image_holder">
											<?php
											if ( isset( $bdp_settings['bdp_default_image_src'] ) && '' != $bdp_settings['bdp_default_image_src'] ) {
												echo '<img src="' . esc_attr( $bdp_settings['bdp_default_image_src'] ) . '"/>';
											}
											?>
										</span>
										<?php if ( isset( $bdp_settings['bdp_default_image_src'] ) && '' != $bdp_settings['bdp_default_image_src'] ) { ?>
											<input id="bdp-image-action-button" class="button bdp-remove_image_button" type="button" value="<?php esc_attr_e( 'Remove Image', 'blog-designer-pro' ); ?>">
										<?php } else { ?>
											<input class="button bdp-upload_image_button" type="button" value="<?php esc_attr_e( 'Upload Image', 'blog-designer-pro' ); ?>">
										<?php } ?>
										<input type="hidden" value="<?php echo isset( $bdp_settings['bdp_default_image_id'] ) ? esc_attr( $bdp_settings['bdp_default_image_id'] ) : ''; ?>" name="bdp_default_image_id" id="bdp_default_image_id">
										<input type="hidden" value="<?php echo isset( $bdp_settings['bdp_default_image_src'] ) ? esc_attr( $bdp_settings['bdp_default_image_src'] ) : ''; ?>" name="bdp_default_image_src" id="bdp_default_image_src">
									</div>
								</li>
							</div>
							<li class="bdp_media_custom_size_tr display_image_tr">
								<div class="bdp-left"><span class="bdp-key-title"><?php esc_html_e( 'Add Custom Size', 'blog-designer-pro' ); ?></span></div>
								<div class="bdp-right">
									<div class="bdp_media_custom_size_tbl">
										<p> <span class="bdp_custom_media_size_title"><?php esc_html_e( 'Width (px)', 'blog-designer-pro' ); ?> </span> <input type="number" min="1" name="media_custom_width" class="media_custom_width" id="media_custom_width" value="<?php echo ( isset( $bdp_settings['media_custom_width'] ) && '' != $bdp_settings['media_custom_width'] ) ? esc_attr( $bdp_settings['media_custom_width'] ) : ''; ?>" /> </p>
										<p> <span class="bdp_custom_media_size_title"><?php esc_html_e( 'Height (px)', 'blog-designer-pro' ); ?> </span> <input type="number" min="1" name="media_custom_height" class="media_custom_height" id="media_custom_height" value="<?php echo ( isset( $bdp_settings['media_custom_height'] ) && '' != $bdp_settings['media_custom_height'] ) ? esc_attr( $bdp_settings['media_custom_height'] ) : ''; ?>"/> </p>
									</div>
								</div>
							</li>

							<div class="bdp_grid_col_3">
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
				<div id="bdpslider" class="postbox postbox-with-fw-options" style=<?php echo esc_attr( $bdpslider_class_show ); ?>>
					<div class="inside">
						<ul class="bdp-settings bdp-lineheight">
							<div class="bdp_grid_col_2">
								<li class="bdp-slider-effect">
									<div class="bdp-left"><span class="bdp-key-title bdp-key-title2"><?php esc_html_e( 'Slider Effect', 'blog-designer-pro' ); ?></span></div>
									<div class="bdp-right">
										<?php $bdp_settings['template_slider_effect'] = ( isset( $bdp_settings['template_slider_effect'] ) ) ? $bdp_settings['template_slider_effect'] : ''; ?>
										<select name="template_slider_effect" id="template_slider_effect">
											<option value="slide" 
											<?php
											if ( 'slide' === $bdp_settings['template_slider_effect'] ) {
												?>
												selected="selected"<?php } ?>>
												<?php esc_html_e( 'Slide', 'blog-designer-pro' ); ?>
											</option>
											<option value="fade" 
											<?php
											if ( 'fade' === $bdp_settings['template_slider_effect'] ) {
												?>
												selected="selected"<?php } ?>>
												<?php esc_html_e( 'Fade', 'blog-designer-pro' ); ?>
											</option>
										</select>
									</div>
								</li>

								<li class="slider_columns_tr">
									<div class="bdp-left">
										<span class="bdp-key-title bdp-key-title2">
											<?php esc_html_e( 'Slider Columns', 'blog-designer-pro' ); ?>
											<?php echo '<span class="bdp-media-div">(<i>' . esc_html__( 'Desktop - Above', 'blog-designer-pro' ) . ' 980px</i>)</span>'; ?>
										</span>
									</div>
									<div class="bdp-right">
										<?php $bdp_settings['template_slider_columns'] = ( isset( $bdp_settings['template_slider_columns'] ) ) ? $bdp_settings['template_slider_columns'] : 2; ?>
										<select name="template_slider_columns" id="template_slider_columns">
											<option value="1" 
											<?php
											if ( '1' == $bdp_settings['template_slider_columns'] ) {
												?>
												selected="selected"<?php } ?>>
												<?php esc_html_e( '1 Column', 'blog-designer-pro' ); ?>
											</option>
											<option value="2" 
											<?php
											if ( '2' === $bdp_settings['template_slider_columns'] ) {
												?>
												selected="selected"<?php } ?>>
												<?php esc_html_e( '2 Columns', 'blog-designer-pro' ); ?>
											</option>
											<option value="3" 
											<?php
											if ( '3' === $bdp_settings['template_slider_columns'] ) {
												?>
												selected="selected"<?php } ?>>
												<?php esc_html_e( '3 Columns', 'blog-designer-pro' ); ?>
											</option>
											<option value="4" 
											<?php
											if ( '4' === $bdp_settings['template_slider_columns'] ) {
												?>
												selected="selected"<?php } ?>>
												<?php esc_html_e( '4 Columns', 'blog-designer-pro' ); ?>
											</option>
											<option value="5" 
											<?php
											if ( '5' === $bdp_settings['template_slider_columns'] ) {
												?>
												selected="selected"<?php } ?>>
												<?php esc_html_e( '5 Columns', 'blog-designer-pro' ); ?>
											</option>
											<option value="6" 
											<?php
											if ( '6' === $bdp_settings['template_slider_columns'] ) {
												?>
												selected="selected"<?php } ?>>
												<?php esc_html_e( '6 Columns', 'blog-designer-pro' ); ?>
											</option>
										</select>
									</div>
								</li>
								<li class="slider_columns_tr">
									<div class="bdp-left">
										<span class="bdp-key-title bdp-key-title2">
											<?php esc_html_e( 'Slider Columns', 'blog-designer-pro' ); ?>
											<?php echo '<span class="bdp-media-div">(<i>' . esc_html__( 'iPad', 'blog-designer-pro' ) . ' - 720px - 980px</i>)</span>'; ?>
										</span>
									</div>
									<div class="bdp-right">
										<?php $bdp_settings['template_slider_columns_ipad'] = ( isset( $bdp_settings['template_slider_columns_ipad'] ) ) ? $bdp_settings['template_slider_columns_ipad'] : 2; ?>
										<select name="template_slider_columns_ipad" id="template_slider_columns_ipad">
											<option value="1" 
											<?php
											if ( '1' == $bdp_settings['template_slider_columns_ipad'] ) {
												?>
												selected="selected"<?php } ?>>
												<?php esc_html_e( '1 Column', 'blog-designer-pro' ); ?>
											</option>
											<option value="2" 
											<?php
											if ( '2' === $bdp_settings['template_slider_columns_ipad'] ) {
												?>
												selected="selected"<?php } ?>>
												<?php esc_html_e( '2 Columns', 'blog-designer-pro' ); ?>
											</option>
											<option value="3" 
											<?php
											if ( '3' === $bdp_settings['template_slider_columns_ipad'] ) {
												?>
												selected="selected"<?php } ?>>
												<?php esc_html_e( '3 Columns', 'blog-designer-pro' ); ?>
											</option>
											<option value="4" 
											<?php
											if ( '4' === $bdp_settings['template_slider_columns_ipad'] ) {
												?>
												selected="selected"<?php } ?>>
												<?php esc_html_e( '4 Columns', 'blog-designer-pro' ); ?>
											</option>
											<option value="5" 
											<?php
											if ( '5' === $bdp_settings['template_slider_columns_ipad'] ) {
												?>
												selected="selected"<?php } ?>>
												<?php esc_html_e( '5 Columns', 'blog-designer-pro' ); ?>
											</option>
											<option value="6" 
											<?php
											if ( '6' === $bdp_settings['template_slider_columns_ipad'] ) {
												?>
												selected="selected"<?php } ?>>
												<?php esc_html_e( '6 Columns', 'blog-designer-pro' ); ?>
											</option>
										</select>
									</div>
								</li>

								<li class="slider_columns_tr">
									<div class="bdp-left">
										<span class="bdp-key-title bdp-key-title2">
											<?php esc_html_e( 'Slider Columns', 'blog-designer-pro' ); ?>
											<?php echo '<span class="bdp-media-div">(<i>' . esc_html__( 'Tablet', 'blog-designer-pro' ) . ' - 480px - 720px</i>)</span>'; ?>
										</span>
									</div>
									<div class="bdp-right">
										<?php $bdp_settings['template_slider_columns_tablet'] = ( isset( $bdp_settings['template_slider_columns_tablet'] ) ) ? $bdp_settings['template_slider_columns_tablet'] : 2; ?>
										<select name="template_slider_columns_tablet" id="template_slider_columns_tablet">
											<option value="1" 
											<?php
											if ( '1' == $bdp_settings['template_slider_columns_tablet'] ) {
												?>
												selected="selected"<?php } ?>>
												<?php esc_html_e( '1 Column', 'blog-designer-pro' ); ?>
											</option>
											<option value="2" 
											<?php
											if ( '2' === $bdp_settings['template_slider_columns_tablet'] ) {
												?>
												selected="selected"<?php } ?>>
												<?php esc_html_e( '2 Columns', 'blog-designer-pro' ); ?>
											</option>
											<option value="3" 
											<?php
											if ( '3' === $bdp_settings['template_slider_columns_tablet'] ) {
												?>
												selected="selected"<?php } ?>>
												<?php esc_html_e( '3 Columns', 'blog-designer-pro' ); ?>
											</option>
											<option value="4" 
											<?php
											if ( '4' === $bdp_settings['template_slider_columns_tablet'] ) {
												?>
												selected="selected"<?php } ?>>
												<?php esc_html_e( '4 Columns', 'blog-designer-pro' ); ?>
											</option>
											<option value="5" 
											<?php
											if ( '5' === $bdp_settings['template_slider_columns_tablet'] ) {
												?>
												selected="selected"<?php } ?>>
												<?php esc_html_e( '5 Columns', 'blog-designer-pro' ); ?>
											</option>
											<option value="6" 
											<?php
											if ( '6' === $bdp_settings['template_slider_columns_tablet'] ) {
												?>
												selected="selected"<?php } ?>>
												<?php esc_html_e( '6 Columns', 'blog-designer-pro' ); ?>
											</option>
										</select>
									</div>
								</li>

								<li class="slider_columns_tr">
									<div class="bdp-left">
										<span class="bdp-key-title bdp-key-title2">
											<?php esc_html_e( 'Slider Columns', 'blog-designer-pro' ); ?>
											<?php echo '<span class="bdp-media-div">(<i>' . esc_html__( 'Mobile - Smaller Than', 'blog-designer-pro' ) . ' 480px </i>)</span>'; ?>
										</span>
									</div>
									<div class="bdp-right">
										<?php $bdp_settings['template_slider_columns_mobile'] = ( isset( $bdp_settings['template_slider_columns_mobile'] ) ) ? $bdp_settings['template_slider_columns_mobile'] : 1; ?>
										<select name="template_slider_columns_mobile" id="template_slider_columns_mobile">
											<option value="1" 
											<?php
											if ( '1' == $bdp_settings['template_slider_columns_mobile'] ) {
												?>
												selected="selected"<?php } ?>>
												<?php esc_html_e( '1 Column', 'blog-designer-pro' ); ?>
											</option>
											<option value="2" 
											<?php
											if ( '2' === $bdp_settings['template_slider_columns_mobile'] ) {
												?>
												selected="selected"<?php } ?>>
												<?php esc_html_e( '2 Columns', 'blog-designer-pro' ); ?>
											</option>
											<option value="3" 
											<?php
											if ( '3' === $bdp_settings['template_slider_columns_mobile'] ) {
												?>
												selected="selected"<?php } ?>>
												<?php esc_html_e( '3 Columns', 'blog-designer-pro' ); ?>
											</option>
											<option value="4" 
											<?php
											if ( '4' === $bdp_settings['template_slider_columns_mobile'] ) {
												?>
												selected="selected"<?php } ?>>
												<?php esc_html_e( '4 Columns', 'blog-designer-pro' ); ?>
											</option>
											<option value="5" 
											<?php
											if ( '5' === $bdp_settings['template_slider_columns_mobile'] ) {
												?>
												selected="selected"<?php } ?>>
												<?php esc_html_e( '5 Columns', 'blog-designer-pro' ); ?>
											</option>
											<option value="6" 
											<?php
											if ( '6' === $bdp_settings['template_slider_columns_mobile'] ) {
												?>
												selected="selected"<?php } ?>>
												<?php esc_html_e( '6 Columns', 'blog-designer-pro' ); ?>
											</option>
										</select>
									</div>
								</li>
								<li class="slider_scroll_tr">
									<div class="bdp-left"><span class="bdp-key-title bdp-key-title2"><?php esc_html_e( 'Slide to Scroll', 'blog-designer-pro' ); ?></span></div>
									<div class="bdp-right">
										<?php $template_slider_scroll = isset( $bdp_settings['template_slider_scroll'] ) ? $bdp_settings['template_slider_scroll'] : '1'; ?>
										<select name="template_slider_scroll" id="template_slider_scroll">
											<option value="1" 
											<?php
											if ( '1' == $template_slider_scroll ) {
												?>
												selected="selected"<?php } ?>>1</option>
											<option value="2" 
											<?php
											if ( '2' === $template_slider_scroll ) {
												?>
												selected="selected"<?php } ?>>2</option>
											<option value="3" 
											<?php
											if ( '3' === $template_slider_scroll ) {
												?>
												selected="selected"<?php } ?>>3</option>
										</select>
									</div>
								</li>
							</div>
							<div class="bdp_grid_col_3">
								<li class="bdp_slider_navigation">
									<div class="bdp-left"><span class="bdp-key-title"><?php esc_html_e( 'Display Slider Navigation', 'blog-designer-pro' ); ?></span></div>
									<div class="bdp-right">
										<?php $display_slider_navigation = isset( $bdp_settings['display_slider_navigation'] ) ? $bdp_settings['display_slider_navigation'] : '1'; ?>
										<fieldset class="bdp-social-options bdp-display_slider_navigation buttonset buttonset-hide ui-buttonset">
											<input id="display_slider_navigation_1" name="display_slider_navigation" type="radio" value="1" <?php checked( 1, $display_slider_navigation ); ?> />
											<label for="display_slider_navigation_1" <?php checked( 1, $display_slider_navigation ); ?>><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
											<input id="display_slider_navigation_0" name="display_slider_navigation" type="radio" value="0" <?php checked( 0, $display_slider_navigation ); ?> />
											<label for="display_slider_navigation_0" <?php checked( 0, $display_slider_navigation ); ?>><?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
										</fieldset>
									</div>
								</li>
								<li class="select_slider_navigation_tr">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Slider Navigation Icon', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<?php $slider_navigation = isset( $bdp_settings['navigation_style_hidden'] ) ? $bdp_settings['navigation_style_hidden'] : 'navigation3'; ?>
										<div class="select_button_upper_div ">
											<div class="bdp_select_template_button_div">
												<input type="button" class="button bdp_select_navigation" value="<?php esc_attr_e( 'Select Navigation', 'blog-designer-pro' ); ?>">
												<input style="visibility: hidden;" type="hidden" id="navigation_style_hidden" class="navigation_style_hidden" name="navigation_style_hidden" value="<?php echo esc_attr( $slider_navigation ); ?>" />
											</div>
											<div class="bdp_selected_navigation_image">
												<div class="bdp-dialog-navigation-style slider_controls" >
													<div class="bdp_navigation_image_holder navigation_hidden" ><img src="<?php echo esc_attr( BLOGDESIGNERPRO_URL ) . '/public/images/navigation/' . esc_attr( $slider_navigation ) . '.png'; ?>"></div>
												</div>
											</div>
										</div>
									</div>
								</li>
								<li class="post-slider-arrow">
									<div class="bdp-left"><span class="bdp-key-title"><?php esc_html_e( 'Display Slider Controls', 'blog-designer-pro' ); ?></span></div>
									<div class="bdp-right">
										<?php $display_slider_controls = isset( $bdp_settings['display_slider_controls'] ) ? $bdp_settings['display_slider_controls'] : '1'; ?>
										<fieldset class="bdp-social-options bdp-display_slider_controls buttonset buttonset-hide ui-buttonset">
											<input id="display_slider_controls_1" name="display_slider_controls" type="radio" value="1" <?php checked( 1, $display_slider_controls ); ?> />
											<label for="display_slider_controls_1" <?php checked( 1, $display_slider_controls ); ?>><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
											<input id="display_slider_controls_0" name="display_slider_controls" type="radio" value="0" <?php checked( 0, $display_slider_controls ); ?> />
											<label for="display_slider_controls_0" <?php checked( 0, $display_slider_controls ); ?>><?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
										</fieldset>
									</div>
								</li>
								<li class="select_slider_controls_tr">
									<div class="bdp-left"><span class="bdp-key-title"><?php esc_html_e( 'Select Slider Arrow', 'blog-designer-pro' ); ?></span></div>
									<div class="bdp-right">
										<?php $slider_arrow = isset( $bdp_settings['arrow_style_hidden'] ) ? $bdp_settings['arrow_style_hidden'] : 'arrow1'; ?>
										<div class="select_button_upper_div ">
											<div class="bdp_select_template_button_div">
												<input type="button" class="button bdp_select_arrow" value="<?php esc_attr_e( 'Select Arrow', 'blog-designer-pro' ); ?>">
												<input style="visibility: hidden;" type="hidden" id="arrow_style_hidden" class="arrow_style_hidden" name="arrow_style_hidden" value="<?php echo esc_attr( $slider_arrow ); ?>" />
											</div>
											<div class="bdp_selected_arrow_image"><div class="bdp-dialog-arrow-style slider_controls"><div class="bdp_arrow_image_holder arrow_hidden"><img src="<?php echo esc_attr( BLOGDESIGNERPRO_URL ) . '/public/images/arrow/' . esc_attr( $slider_arrow ) . '.png'; ?>"></div></div></div>
										</div>
									</div>
								</li>
								<li class="post-slider-thumb">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Post Slider with Thumbnail', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<?php $post_slider_thumb = isset( $bdp_settings['post_slider_thumb'] ) ? $bdp_settings['post_slider_thumb'] : '1'; ?>
										<fieldset class="bdp-social-options bdp-post_slider_thumb buttonset buttonset-hide ui-buttonset">
											<input id="post_slider_thumb_1" name="post_slider_thumb" type="radio" value="1" <?php checked( 1, $post_slider_thumb ); ?> />
											<label for="post_slider_thumb_1" <?php checked( 1, $post_slider_thumb ); ?>><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
											<input id="post_slider_thumb_0" name="post_slider_thumb" type="radio" value="0" <?php checked( 0, $post_slider_thumb ); ?> />
											<label for="post_slider_thumb_0" <?php checked( 0, $post_slider_thumb ); ?>><?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
										</fieldset>
									</div>
								</li>
								<li class="bdp-slider-autoplay" >
									<div class="bdp-left"><span class="bdp-key-title"><?php esc_html_e( 'Slider Autoplay', 'blog-designer-pro' ); ?></span></div>
									<div class="bdp-right">
										<?php $slider_autoplay = isset( $bdp_settings['slider_autoplay'] ) ? $bdp_settings['slider_autoplay'] : '1'; ?>
										<fieldset class="bdp-social-options bdp-slider_autoplay buttonset buttonset-hide ui-buttonset">
											<input id="slider_autoplay_1" name="slider_autoplay" type="radio" value="1" <?php checked( 1, $slider_autoplay ); ?> />
											<label for="slider_autoplay_1" <?php checked( 1, $slider_autoplay ); ?>><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
											<input id="slider_autoplay_0" name="slider_autoplay" type="radio" value="0" <?php checked( 0, $slider_autoplay ); ?> />
											<label for="slider_autoplay_0" <?php checked( 0, $slider_autoplay ); ?>><?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
										</fieldset>
									</div>
								</li>
								<li class="slider_autoplay_tr">
									<div class="bdp-left">
										<span class="bdp-key-title"><?php esc_html_e( 'Enter slider autoplay intervals(ms)', 'blog-designer-pro' ); ?></span>
									</div>
									<div class="bdp-right">
										<?php $slider_autoplay_intervals = isset( $bdp_settings['slider_autoplay_intervals'] ) ? $bdp_settings['slider_autoplay_intervals'] : '1'; ?>
										<input type="number" id="slider_autoplay_intervals" name="slider_autoplay_intervals" step="1" min="0" value="<?php echo isset( $bdp_settings['slider_autoplay_intervals'] ) ? esc_attr( $bdp_settings['slider_autoplay_intervals'] ) : '3000'; ?>" placeholder="<?php esc_attr_e( 'Enter slider intervals', 'blog-designer-pro' ); ?>" onkeypress="return isNumberKey(event)">
									</div>
								</li>
								<li class="slider_autoplay_tr">
									<div class="bdp-left"><span class="bdp-key-title"><?php esc_html_e( 'Slider Speed', 'blog-designer-pro' ); ?></span>
									<span class="bdp-px-prefix"><?php esc_html_e( '(ms)', 'blog-designer-pro' ); ?></span>
									</div>
									<div class="bdp-right">
										<?php $slider_speed = isset( $bdp_settings['slider_speed'] ) ? $bdp_settings['slider_speed'] : '300'; ?>
										<input type="number" id="slider_speed" name="slider_speed" step="1" min="0" value="<?php echo isset( $bdp_settings['slider_speed'] ) ? esc_attr( $bdp_settings['slider_speed'] ) : '300'; ?>" placeholder="<?php esc_attr_e( 'Enter slider intervals', 'blog-designer-pro' ); ?>" onkeypress="return isNumberKey(event)">
									</div>
								</li>
								<li class="bdp_3d_number_tr">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Display Number of Posts in slider', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<?php $number_post = isset( $bdp_settings['bdp_number_post'] ) ? $bdp_settings['bdp_number_post'] : '5'; ?>
										<input type="number" id="bdp_number_post" name="bdp_number_post" step="1" min="0" value="<?php echo isset( $bdp_settings['bdp_number_post'] ) ? esc_attr( $bdp_settings['bdp_number_post'] ) : '5'; ?>" placeholder="<?php esc_attr_e( 'Enter slider distance', 'blog-designer-pro' ); ?>" onkeypress="return isNumberKey(event)">
										<p>
											<strong>Note: </strong>If Template  is "Threed-Carousel" then set only odd number ex. 3,5,7,...
										</p>
									</div>
								</li>
								<li class="bdp_3d_slider_distance_tr">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Distance Between Two Slider', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<?php $slider_distance = isset( $bdp_settings['slider_distance'] ) ? $bdp_settings['slider_distance'] : '120'; ?>
										<input type="number" id="slider_distance" name="slider_distance" step="1" min="0" value="<?php echo isset( $bdp_settings['slider_distance'] ) ? esc_attr( $bdp_settings['slider_distance'] ) : '120'; ?>">
									</div>
								</li>
							</div>

							<li class="bdp_3d_image_slider_tr">
								<div class="bdp-left">
									<span class="bdp-key-title">
										<?php esc_html_e( 'Add Custom Size for slider', 'blog-designer-pro' ); ?>
									</span>
								</div>
								<div class="bdp-right">
									<div class="bdp_3d_slider_custom_size_tbl">
										<p> <span class="bdp_custom_3d_slider_size_title">
											<?php
											esc_html_e( 'Width', 'blog-designer-pro' );
											echo ' (px)';
											?>
											</span> <input type="number" min="1" name="slider_custom_width" class="slider_custom_width" id="slider_custom_width" value="<?php echo ( isset( $bdp_settings['slider_custom_width'] ) && '' != $bdp_settings['slider_custom_width'] ) ? esc_attr( $bdp_settings['slider_custom_width'] ) : ''; ?>" /></p>
											<p> <span class="bdp_custom_3d_slider_size_title">
											<?php
											esc_html_e( 'Height', 'blog-designer-pro' );
											echo ' (px)';
											?>
										</span> <input type="number" min="1" name="slider_custom_height" class="slider_custom_height" id="slider_custom_height" value="<?php echo ( isset( $bdp_settings['slider_custom_height'] ) && '' != $bdp_settings['slider_custom_height'] ) ? esc_attr( $bdp_settings['slider_custom_height'] ) : ''; ?>"/> </p>
									</div>
								</div>
							</li>
							<li class="bdp_3d_slider_distance_tr">
								<div class="bdp-left">
									<span class="bdp-key-title">
										<?php esc_html_e( 'Distance Between Two Slider', 'blog-designer-pro' ); ?>
									</span>
								</div>
								<div class="bdp-right">
									<?php $slider_distance = isset( $bdp_settings['slider_distance'] ) ? $bdp_settings['slider_distance'] : '120'; ?>
									<input type="number" id="slider_distance" name="slider_distance" step="1" min="0" value="<?php echo isset( $bdp_settings['slider_distance'] ) ? esc_attr( $bdp_settings['slider_distance'] ) : '120'; ?>" placeholder="<?php esc_attr_e( 'Enter slider distance', 'blog-designer-pro' ); ?>" onkeypress="return isNumberKey(event)">
								</div>
							</li>
						</ul>
					</div>
				</div>
				<div id="bdpfilter" class="postbox postbox-with-fw-options" style=<?php echo esc_attr( $bdpfilter_class_show ); ?>>
					<div class="inside">
						<ul class="bdp-settings bdp-lineheight">
							<div class="bdp_grid_col_3">
								<li class="date_from_tr">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Display Date', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<?php
										$dsiplay_date_from = isset( $bdp_settings['dsiplay_date_from'] ) ? $bdp_settings['dsiplay_date_from'] : 'publish';
										?>

										<select name="dsiplay_date_from" id="dsiplay_date_from">
											<option value="publish"  <?php echo selected( 'publish', $dsiplay_date_from ); ?>><?php esc_html_e( 'Publish Date', 'blog-designer-pro' ); ?></option>
											<option value="modify"  <?php echo selected( 'modify', $dsiplay_date_from ); ?>><?php esc_html_e( 'Last Modify Date', 'blog-designer-pro' ); ?></option>
										</select>
									</div>
								</li>
								<li class="post_date_format_tr">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Date Format', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<?php $post_date_format = isset( $bdp_settings['post_date_format'] ) ? $bdp_settings['post_date_format'] : 'default'; ?>

										<select name="post_date_format" id="post_date_format">
											<option value="default"  <?php echo selected( 'default', $post_date_format ); ?>><?php esc_html_e( 'Default', 'blog-designer-pro' ); ?></option>
											<option value="F j, Y g:i a"  <?php echo selected( 'F j, Y g:i a', $post_date_format ); ?>><?php echo esc_attr( get_the_time( 'F j, Y g:i a' ) ); ?></option>
											<option value="F j, Y"  <?php echo selected( 'F j, Y', $post_date_format ); ?>><?php echo esc_attr( get_the_time( 'F j, Y' ) ); ?></option>
											<option value="F, Y"  <?php echo selected( 'F, Y', $post_date_format ); ?>><?php echo esc_attr( get_the_time( 'F, Y' ) ); ?></option>
											<option value="j F  Y"  <?php echo selected( 'j F  Y', $post_date_format ); ?>><?php echo esc_attr( get_the_time( 'j F  Y' ) ); ?></option>
											<option value="g:i a"  <?php echo selected( 'g:i a', $post_date_format ); ?>><?php echo esc_attr( get_the_time( 'g:i a' ) ); ?></option>
											<option value="g:i:s a"  <?php echo selected( 'g:i:s a', $post_date_format ); ?>><?php echo esc_attr( get_the_time( 'g:i:s a' ) ); ?></option>
											<option value="l, F jS, Y"  <?php echo selected( 'l, F jS, Y', $post_date_format ); ?>><?php echo esc_attr( get_the_time( 'l, F jS, Y' ) ); ?></option>
											<option value="M j, Y @ G:i"  <?php echo selected( 'M j, Y @ G:i', $post_date_format ); ?>><?php echo esc_attr( get_the_time( 'M j, Y @ G:i' ) ); ?></option>
											<option value="Y/m/d g:i:s A"  <?php echo selected( 'Y/m/d g:i:s A', $post_date_format ); ?>><?php echo esc_attr( get_the_time( 'Y/m/d g:i:s A' ) ); ?></option>
											<option value="Y/m/d"  <?php echo selected( 'Y/m/d', $post_date_format ); ?>><?php echo esc_attr( get_the_time( 'Y/m/d' ) ); ?></option>
											<option value="d.m.Y"  <?php echo selected( 'd.m.Y', $post_date_format ); ?>><?php echo esc_attr( get_the_time( 'd.m.Y' ) ); ?></option>
											<option value="d-m-Y"  <?php echo selected( 'd-m-Y', $post_date_format ); ?>><?php echo esc_attr( get_the_time( 'd-m-Y' ) ); ?></option>
										</select>
									</div>
								</li>
								<li class="archive_blog_order_by">
									<div class="bdp-left"><span class="bdp-key-title"><?php esc_html_e( 'Blog Order by', 'blog-designer-pro' ); ?></span></div>
									<div class="bdp-right">
										<?php
										$orderby = '';
										if ( isset( $bdp_settings['bdp_blog_order_by'] ) ) {
											$orderby = $bdp_settings['bdp_blog_order_by'];
										}
										?>
										<select id="bdp_blog_order_by" name="bdp_blog_order_by">
											<option value="" <?php echo selected( '', $orderby ); ?>><?php esc_html_e( 'Default Sorting', 'blog-designer-pro' ); ?></option>
											<option value="rand" <?php echo selected( 'rand', $orderby ); ?>><?php esc_html_e( 'Random', 'blog-designer-pro' ); ?></option>
											<option value="ID" <?php echo selected( 'ID', $orderby ); ?>><?php esc_html_e( 'Post ID', 'blog-designer-pro' ); ?></option>
											<option value="author" <?php echo selected( 'author', $orderby ); ?>><?php esc_html_e( 'Author', 'blog-designer-pro' ); ?></option>
											<option value="title" <?php echo selected( 'title', $orderby ); ?>><?php esc_html_e( 'Post Title', 'blog-designer-pro' ); ?></option>
											<option value="name" <?php echo selected( 'name', $orderby ); ?>><?php esc_html_e( 'Post Slug', 'blog-designer-pro' ); ?></option>
											<option value="date" <?php echo selected( 'date', $orderby ); ?>><?php esc_html_e( 'Publish Date', 'blog-designer-pro' ); ?></option>
											<option value="modified" <?php echo selected( 'modified', $orderby ); ?>><?php esc_html_e( 'Modified Date', 'blog-designer-pro' ); ?></option>
											<option value="meta_value_num" <?php echo selected( 'meta_value_num', $orderby ); ?>><?php esc_html_e( 'Post Likes', 'blog-designer-pro' ); ?></option>
										</select>
										<div class="blg_order">
											<?php
											$order = 'DESC';
											if ( isset( $bdp_settings['bdp_blog_order'] ) ) {
												$order = $bdp_settings['bdp_blog_order'];
											}
											?>
											<fieldset class="buttonset green" data-hide='1'>
												<input id="bdp_blog_order_asc" name="bdp_blog_order" type="radio" value="ASC" <?php checked( 'ASC', $order ); ?> />
												<label id="bdp-options-button" for="bdp_blog_order_asc"><?php esc_html_e( 'Ascending', 'blog-designer-pro' ); ?></label>
												<input id="bdp_blog_order_desc" name="bdp_blog_order" type="radio" value="DESC" <?php checked( 'DESC', $order ); ?> />
												<label id="bdp-options-button" for="bdp_blog_order_desc"><?php esc_html_e( 'Descending', 'blog-designer-pro' ); ?></label>
											</fieldset>
										</div>
									</div>
								</li>
								<li class="orderby_date_display">
									<div class="bdp-left"><span class="bdp-key-title"><?php esc_html_e( 'Post Display Year Or Months Wise', 'blog-designer-pro' ); ?></span></div>
									<div class="bdp-right">
										<?php
										$timeline_display_option = '';
										if ( isset( $bdp_settings['timeline_display_option'] ) ) {
											$timeline_display_option = $bdp_settings['timeline_display_option'];
										}
										?>
										<select name="timeline_display_option" id="timeline_display_option">
											<option value="" <?php echo esc_attr( selected( '', $timeline_display_option ) ); ?>>
												<?php esc_html_e( 'Select Option', 'blog-designer-pro' ); ?>
											</option>
											<option value="display_year" <?php echo esc_attr( selected( 'display_year', $timeline_display_option ) ); ?>>
												<?php esc_html_e( 'Display Years', 'blog-designer-pro' ); ?>
											</option>
											<option value="display_month" <?php echo esc_attr( selected( 'display_month', $timeline_display_option ) ); ?>>
												<?php esc_html_e( 'Display Months', 'blog-designer-pro' ); ?>
											</option>
										</select>
									</div>
								</li>
								<li class="bdp_post_status">
									<div class="bdp-left"><span class="bdp-key-title"><?php esc_html_e( 'Post Status', 'blog-designer-pro' ); ?></span></div>
									<div class="bdp-right">
										<?php $post_status = isset( $bdp_settings['bdp_post_status'] ) ? $bdp_settings['bdp_post_status'] : array( 'publish' ); ?>
										<select id="bdp_post_status" name="bdp_post_status[]" data-placeholder="<?php esc_attr_e( 'Select post status', 'blog-designer-pro' ); ?>" class="chosen-select" multiple style="width:220px;" >
											<option value="publish" 
											<?php
											if ( @in_array( 'publish', $post_status ) ) {
												echo 'selected="selected"'; }
											?>
											><?php esc_html_e( 'Publish', 'blog-designer-pro' ); ?></option>
											<option value="pending" 
											<?php
											if ( @in_array( 'pending', $post_status ) ) {
												echo 'selected="selected"'; }
											?>
											><?php esc_html_e( 'Pending', 'blog-designer-pro' ); ?></option>
											<option value="draft" 
											<?php
											if ( @in_array( 'draft', $post_status ) ) {
												echo 'selected="selected"'; }
											?>
											><?php esc_html_e( 'Draft', 'blog-designer-pro' ); ?></option>
											<option value="auto-draft" 
											<?php
											if ( @in_array( 'auto-draft', $post_status ) ) {
												echo 'selected="selected"'; }
											?>
											><?php esc_html_e( 'Auto Draft', 'blog-designer-pro' ); ?></option>
											<option value="future" 
											<?php
											if ( @in_array( 'future', $post_status ) ) {
												echo 'selected="selected"'; }
											?>
											><?php esc_html_e( 'Future', 'blog-designer-pro' ); ?></option>
											<option value="private" 
											<?php
											if ( @in_array( 'private', $post_status ) ) {
												echo 'selected="selected"'; }
											?>
											><?php esc_html_e( 'Private', 'blog-designer-pro' ); ?></option>
											<option value="inherit" 
											<?php
											if ( @in_array( 'inherit', $post_status ) ) {
												echo 'selected="selected"'; }
											?>
											><?php esc_html_e( 'Inherit', 'blog-designer-pro' ); ?></option>
											<option value="trash" 
											<?php
											if ( @in_array( 'trash', $post_status ) ) {
												echo 'selected="selected"'; }
											?>
											><?php esc_html_e( 'Trash', 'blog-designer-pro' ); ?></option>
										</select>
									</div>
								</li>
								<li class="displayorder_backcolor">
									<div class="bdp-left"><span class="bdp-key-title"><?php esc_html_e( 'Year Or Month Display BackGround Color', 'blog-designer-pro' ); ?></span></div>
									<div class="bdp-right">
										<input type="text" name="displaydate_backcolor" id="displaydate_backcolor" value="<?php echo isset( $bdp_settings['displaydate_backcolor'] ) ? esc_attr( $bdp_settings['displaydate_backcolor'] ) : '#414a54'; ?>"/>
									</div>
								</li>
							</div>
							<div class="bdp_grid_col_2">
								<li class="show_sticky_post">
									<div class="bdp-left"><span class="bdp-key-title"><?php esc_html_e( 'Show Sticky Post', 'blog-designer-pro' ); ?></span></div>
									<div class="bdp-right">
										<?php
										$display_sticky = 0;
										if ( isset( $bdp_settings['display_sticky'] ) ) {
											$display_sticky = $bdp_settings['display_sticky'];
										}
										?>
										<fieldset class="bdp-social-options bdp-display_sticky buttonset">
											<input id="display_sticky_1" name="display_sticky" type="radio" value="1" <?php echo checked( 1, $display_sticky ); ?> />
											<label for="display_sticky_1"><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
											<input id="display_sticky_0" name="display_sticky" type="radio" value="0" <?php echo checked( 0, $display_sticky ); ?> />
											<label for="display_sticky_0"><?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
										</fieldset>
										<div class="bdp-setting-description bdp-note">
											<b class="note"><?php esc_html_e( 'Note', 'blog-designer-pro' ); ?>:</b>
											<?php
											esc_html_e( 'Sticky Post not count in the number of post to be displayed in blog layout page.', 'blog-designer-pro' );
											?>
										</div>
									</div>
								</li>
								<li class="label_featured_post">
									<div class="bdp-left"><span class="bdp-key-title"><?php esc_html_e( 'Label for featured posts', 'blog-designer-pro' ); ?></span></div>
									<div class="bdp-right">
										<input type="text" name="label_featured_post" id="label_featured_post" value="<?php echo ( isset( $bdp_settings['label_featured_post'] ) ? esc_attr( $bdp_settings['label_featured_post'] ) : '' ); ?>" placeholder="<?php esc_attr_e( 'Enter Label Text', 'blog-designer-pro' ); ?>">
										<div class="bdp-setting-description bdp-note">
											<b class="note"><?php esc_html_e( 'Note', 'blog-designer-pro' ); ?>:</b>
											<?php
											esc_html_e( 'Leave blank to disable label', 'blog-designer-pro' );
											?>
										</div>
									</div>
								</li>
							</div>
						</ul>
					</div>
				</div>
				<div id="bdppagination" class="postbox postbox-with-fw-options" style=<?php echo esc_attr( $bdppagination_class_show ); ?>>
					<div class="inside">
						<ul class="bdp-settings bdp-lineheight">
							<div class="bdp_grid_col_2">
								<li class="archive_pagination_type">
									<div class="bdp-left"><span class="bdp-key-title"><?php esc_html_e( 'Pagination Type', 'blog-designer-pro' ); ?></span></div>
									<div class="bdp-right">
										<select name="pagination_type" id="pagination_type">
											<option value="no_pagination" <?php echo esc_attr( selected( 'no_pagination', $pagination_type ) ); ?>>
												<?php esc_html_e( 'No Pagination', 'blog-designer-pro' ); ?>
											</option>
											<option value="paged" <?php echo esc_attr( selected( 'paged', $pagination_type ) ); ?>>
												<?php esc_html_e( 'Paged', 'blog-designer-pro' ); ?>
											</option>
											<option value="load_more_btn" <?php echo esc_attr( selected( 'load_more_btn', $pagination_type ) ); ?>>
												<?php esc_html_e( 'Load More Button', 'blog-designer-pro' ); ?>
											</option>
											<option value="load_onscroll_btn" <?php echo esc_attr( selected( 'load_onscroll_btn', $pagination_type ) ); ?>>
												<?php esc_html_e( 'Load On Page Scroll', 'blog-designer-pro' ); ?>
											</option>
										</select>
									</div>
								</li>
								<li class="archive_pagination_template">
									<div class="bdp-left"><span class="bdp-key-title"><?php esc_html_e( 'Pagination Template', 'blog-designer-pro' ); ?></span>
									<?php $pagination_template = isset( $bdp_settings['pagination_template'] ) ? $bdp_settings['pagination_template'] : 'template-1'; ?>
										<select name="pagination_template" id="pagination_template">
											<option value="template-1" <?php echo esc_attr( selected( 'template-1', $pagination_template ) ); ?>>
												<?php esc_html_e( 'Template 1', 'blog-designer-pro' ); ?>
											</option>
											<option value="template-2" <?php echo esc_attr( selected( 'template-2', $pagination_template ) ); ?>>
												<?php esc_html_e( 'Template 2', 'blog-designer-pro' ); ?>
											</option>
											<option value="template-3" <?php echo esc_attr( selected( 'template-3', $pagination_template ) ); ?>>
												<?php esc_html_e( 'Template 3', 'blog-designer-pro' ); ?>
											</option>
											<option value="template-4" <?php echo esc_attr( selected( 'template-4', $pagination_template ) ); ?>>
												<?php esc_html_e( 'Template 4', 'blog-designer-pro' ); ?>
											</option>
										</select>
									</div>
									<div class="bdp-right">
										<div class="bdp-setting-description bdp-setting-pagination"><img class="pagination_template_images"src="<?php echo esc_attr( BLOGDESIGNERPRO_URL ) . '/public/images/pagination/' . esc_attr( $pagination_template ) . '.png'; ?>"></div>
									</div>
								</li>
								<li class="next-button-text">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Next Button Text', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<?php $next_button_text = ( isset( $bdp_settings['next_button_text'] ) && '' != $bdp_settings['next_button_text'] ) ? $bdp_settings['next_button_text'] : ''; ?>
										<input type="text" name="next_button_text" id="next_button_text" value="<?php echo esc_attr( $next_button_text ); ?>" placeholder="<?php esc_attr_e( 'Enter text for Next button', 'blog-designer-pro' ); ?>">
									</div>
								</li>
								<li class="previous-button-text">
									<div class="bdp-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Previous Button Text', 'blog-designer-pro' ); ?>
										</span>
									</div>
									<div class="bdp-right">
										<?php $previous_button_text = ( isset( $bdp_settings['previous_button_text'] ) && '' != $bdp_settings['previous_button_text'] ) ? $bdp_settings['previous_button_text'] : ''; ?>
										<input type="text" name="previous_button_text" id="previous_button_text" value="<?php echo esc_attr( $previous_button_text ); ?>" placeholder="<?php esc_attr_e( 'Enter text for Previous button', 'blog-designer-pro' ); ?>">
									</div>
								</li>
							</div>
							<h3 class="bdp-table-title archive_pagination_template"><?php esc_html_e( 'Pagination Color Settings', 'blog-designer-pro' ); ?></h3>
							<li class="archive_pagination_template">
								<div class="bdp-pagination-wrapper bdp-pagination-wrapper1 bdp_grid_col_3">
									<div class="bdp-pagination-cover">
										<div class="bdp-pagination-label">
											<span class="bdp-key-title"><?php esc_html_e( 'Text Color', 'blog-designer-pro' ); ?></span>
										</div>
										<div class="bdp-pagination-content">
											<?php $pagination_text_color = isset( $bdp_settings['pagination_text_color'] ) ? $bdp_settings['pagination_text_color'] : '#ffffff'; ?>
											<input type="text" name="pagination_text_color" id="pagination_text_color" value="<?php echo esc_attr( $pagination_text_color ); ?>" data-default-color="<?php echo esc_attr( $pagination_text_color ); ?>"/>
										</div>
									</div>
									<div class="bdp-pagination-cover bdp-pagination-background-color">
										<div class="bdp-pagination-label">
											<span class="bdp-key-title"><?php esc_html_e( 'Background Color', 'blog-designer-pro' ); ?></span>
										</div>
										<div class="bdp-pagination-content">
											<?php $pagination_background_color = isset( $bdp_settings['pagination_background_color'] ) ? $bdp_settings['pagination_background_color'] : '#777'; ?>
											<input type="text" name="pagination_background_color" id="pagination_background_color" value="<?php echo esc_attr( $pagination_background_color ); ?>" data-default-color="<?php echo esc_attr( $pagination_background_color ); ?>"/>
										</div>
									</div>
									<div class="bdp-pagination-cover">
										<div class="bdp-pagination-label">
											<span class="bdp-key-title"><?php esc_html_e( 'Text Hover Color', 'blog-designer-pro' ); ?></span>
										</div>
										<div class="bdp-pagination-content">
											<?php $pagination_text_hover_color = isset( $bdp_settings['pagination_text_hover_color'] ) ? $bdp_settings['pagination_text_hover_color'] : ''; ?>
											<input type="text" name="pagination_text_hover_color" id="pagination_text_hover_color" value="<?php echo esc_attr( $pagination_text_hover_color ); ?>" data-default-color="<?php echo esc_attr( $pagination_text_hover_color ); ?>"/>
										</div>
									</div>
									<div class="bdp-pagination-cover bdp-pagination-hover-background-color">
										<div class="bdp-pagination-label">
											<span class="bdp-key-title"><?php esc_html_e( 'Hover Background Color', 'blog-designer-pro' ); ?></span>
										</div>
										<div class="bdp-pagination-content">
											<?php $pagination_background_hover_color = isset( $bdp_settings['pagination_background_hover_color'] ) ? $bdp_settings['pagination_background_hover_color'] : ''; ?>
											<input type="text" name="pagination_background_hover_color" id="pagination_background_hover_color" value="<?php echo esc_attr( $pagination_background_hover_color ); ?>" data-default-color="<?php echo esc_attr( $pagination_background_hover_color ); ?>"/>
										</div>
									</div>
									<div class="bdp-pagination-cover">
										<div class="bdp-pagination-label">
											<span class="bdp-key-title"><?php esc_html_e( 'Active Text Color', 'blog-designer-pro' ); ?></span>
										</div>
										<div class="bdp-pagination-content">
											<?php $pagination_text_active_color = isset( $bdp_settings['pagination_text_active_color'] ) ? $bdp_settings['pagination_text_active_color'] : ''; ?>
											<input type="text" name="pagination_text_active_color" id="pagination_text_active_color" value="<?php echo esc_attr( $pagination_text_active_color ); ?>" data-default-color="<?php echo esc_attr( $pagination_text_active_color ); ?>"/>
										</div>
									</div>
									<div class="bdp-pagination-cover">
										<div class="bdp-pagination-label">
											<span class="bdp-key-title"><?php esc_html_e( 'Active Background Color', 'blog-designer-pro' ); ?></span>
										</div>
										<div class="bdp-pagination-content">
											<?php $pagination_active_background_color = isset( $bdp_settings['pagination_active_background_color'] ) ? $bdp_settings['pagination_active_background_color'] : ''; ?>
											<input type="text" name="pagination_active_background_color" id="pagination_active_background_color" value="<?php echo esc_attr( $pagination_active_background_color ); ?>" data-default-color="<?php echo esc_attr( $pagination_active_background_color ); ?>"/>
										</div>
									</div>
									<div class="bdp-pagination-cover bdp-pagination-border-wrap ">
										<div class="bdp-pagination-label">
											<span class="bdp-key-title"><?php esc_html_e( 'Border Color', 'blog-designer-pro' ); ?></span>
										</div>
										<div class="bdp-pagination-content">
											<?php $pagination_border_color = isset( $bdp_settings['pagination_border_color'] ) ? $bdp_settings['pagination_border_color'] : '#b2b2b2'; ?>
											<input type="text" name="pagination_border_color" id="pagination_border_color" value="<?php echo esc_attr( $pagination_border_color ); ?>" data-default-color="<?php echo esc_attr( $pagination_border_color ); ?>"/>
										</div>
									</div>
									<div class="bdp-pagination-cover bdp-pagination-active-border-wrap">
										<div class="bdp-pagination-label">
											<span class="bdp-key-title"><?php esc_html_e( 'Active Border Color', 'blog-designer-pro' ); ?></span>
										</div>
										<div class="bdp-pagination-content">
											<?php $pagination_active_border_color = isset( $bdp_settings['pagination_active_border_color'] ) ? $bdp_settings['pagination_active_border_color'] : '#007acc'; ?>
											<input type="text" name="pagination_active_border_color" id="pagination_active_border_color" value="<?php echo esc_attr( $pagination_active_border_color ); ?>" data-default-color="<?php echo esc_attr( $pagination_active_border_color ); ?>"/>
										</div>
									</div>
								</div>
							</li>
							<li class="loadmore_btn_option archive_loadmore_btn_template">
								<div class="bdp-left">
									<span class="bdp-key-title"><?php esc_html_e( 'Button Template', 'blog-designer-pro' ); ?></span>
									<?php $load_more_button_template = isset( $bdp_settings['load_more_button_template'] ) ? $bdp_settings['load_more_button_template'] : 'template-1'; ?>
									<select name="load_more_button_template" id="load_more_button_template">
										<option value="template-1" <?php echo esc_attr( selected( 'template-1', $load_more_button_template ) ); ?>>
											<?php esc_html_e( 'Template 1', 'blog-designer-pro' ); ?>
										</option>
										<option value="template-2" <?php echo esc_attr( selected( 'template-2', $load_more_button_template ) ); ?>>
											<?php esc_html_e( 'Template 2', 'blog-designer-pro' ); ?>
										</option>
										<option value="template-3" <?php echo esc_attr( selected( 'template-3', $load_more_button_template ) ); ?>>
											<?php esc_html_e( 'Template 3', 'blog-designer-pro' ); ?>
										</option>
									</select>
								</div>
								<div class="bdp-right">
									<div class="bdp-setting-description button-loadmore"><img class="load_more_button_template_images"src="<?php echo esc_attr( BLOGDESIGNERPRO_URL ) . '/public/images/buttons/' . esc_attr( $load_more_button_template ) . '.png'; ?>"></div>
								</div>
							</li>
							<div class="bdp_grid_col_2">
								<li class="loadmore_btn_option">
									<div class="bdp-left"><span class="bdp-key-title"><?php esc_html_e( 'Load More Button Text', 'blog-designer-pro' ); ?></span></div>
									<div class="bdp-right">
										<?php $loadmore_button_text = ( isset( $bdp_settings['loadmore_button_text'] ) && '' != $bdp_settings['loadmore_button_text'] ) ? $bdp_settings['loadmore_button_text'] : esc_html__( 'Load More', 'blog-designer-pro' ); ?>
										<input type="text" name="loadmore_button_text" id="loadmore_button_text" value="<?php echo esc_attr( $loadmore_button_text ); ?>" placeholder="<?php esc_attr_e( 'Enter load more button text', 'blog-designer-pro' ); ?>">
									</div>
								</li>
								<li class="loadmore_btn_option">
									<div class="bdp-left">
										<span class="bdp-key-title"><?php esc_html_e( 'Load More Text Color', 'blog-designer-pro' ); ?></span>
									</div>
									<div class="bdp-right">
										<?php $loadmore_button_color = ( isset( $bdp_settings['loadmore_button_color'] ) && '' != $bdp_settings['loadmore_button_color'] ) ? $bdp_settings['loadmore_button_color'] : '#ffffff'; ?>
										<input type="text" name="loadmore_button_color" id="loadmore_button_color" value="<?php echo esc_attr( $loadmore_button_color ); ?>"/>
									</div>
								</li>
								<li class="loadmore_btn_option">
									<div class="bdp-left"><span class="bdp-key-title"><?php esc_html_e( 'Load More Button Background Color', 'blog-designer-pro' ); ?></span></div>
									<div class="bdp-right">
										<?php $loadmore_button_bg_color = ( isset( $bdp_settings['loadmore_button_bg_color'] ) && '' != $bdp_settings['loadmore_button_bg_color'] ) ? $bdp_settings['loadmore_button_bg_color'] : '#444444'; ?>
										<input type="text" name="loadmore_button_bg_color" id="loadmore_button_bg_color" value="<?php echo esc_attr( $loadmore_button_bg_color ); ?>"/>
									</div>
								</li>
								<li class="archive_loader_template">
									<div class="bdp-left"><span class="bdp-key-title"><?php esc_html_e( 'Loader Type', 'blog-designer-pro' ); ?></span></div>
									<div class="bdp-right">
										<?php $loader_type = isset( $bdp_settings['loader_type'] ) ? $bdp_settings['loader_type'] : 0; ?>
										<select name="loader_type" id="pagination_template">
											<option value="0" <?php echo esc_attr( selected( '0', $loader_type ) ); ?>>
												<?php esc_html_e( 'Select Default Loader', 'blog-designer-pro' ); ?>
											</option>
											<option value="1" <?php echo esc_attr( selected( '1', $loader_type ) ); ?>>
												<?php esc_html_e( 'Upload New Loader Image', 'blog-designer-pro' ); ?>
											</option>
										</select>
									</div>
								</li>
							</div>
							<div class="bdp_grid_col_3">
								<li class="archive_loader_template default_loader">
									<div class="bdp-left"><span class="bdp-key-title"><?php esc_html_e( 'Loader Icon', 'blog-designer-pro' ); ?></span></div>
									<div class="bdp-right">
										<?php $loader_style_hidden = isset( $bdp_settings['loader_style_hidden'] ) ? $bdp_settings['loader_style_hidden'] : 'circularG'; ?>
										<div class="select_button_upper_div ">
											<div class="bdp_select_template_button_div">
												<input type="button" class="button bdp_select_loader" value="<?php esc_attr_e( 'Select Loader Icon', 'blog-designer-pro' ); ?>">
												<input style="visibility:hidden" type="hidden" id="loader_style_hidden" class="loader_style_hidden" name="loader_style_hidden" value="<?php echo esc_attr( $loader_style_hidden ); ?>" />
											</div>
											<div class="bdp_selected_loader_image"><div class='bdp-dialog-loader-style'><span class="bdp_loader_image_holder loader_hidden"><?php echo wp_kses( $loaders[ $loader_style_hidden ], Bdp_Admin_Functions::args_kses() ); ?></span></div></div>
										</div>
									</div>
								</li>
								<li class="archive_loader_template upload_loader">
									<div class="bdp-left"><span class="bdp-key-title"><?php esc_html_e( 'Loader Image', 'blog-designer-pro' ); ?></span></div>
									<div class="bdp-right">
										<div class="select_button_upper_div ">
											<div class="bdp_select_template_button_div">
												<?php $loader_image_src = ( isset( $bdp_settings['bdp_loader_image_src'] ) && '' != $bdp_settings['bdp_loader_image_src'] ) ? $bdp_settings['bdp_loader_image_src'] : BLOGDESIGNERPRO_URL . '/public/images/loading.gif'; ?>
												<?php if ( '' != $loader_image_src ) { ?>
													<input class="button bdp-remove_upload_image_button" type="button" value="<?php esc_attr_e( 'Remove Image', 'blog-designer-pro' ); ?>">
												<?php } else { ?>
													<input class="button bdp-loader_upload_image_button " type="button" value="<?php esc_attr_e( 'Upload Image', 'blog-designer-pro' ); ?>">
												<?php } ?>
												<input type="hidden" value="<?php echo isset( $bdp_settings['bdp_loader_image_id'] ) ? esc_attr( $bdp_settings['bdp_loader_image_id'] ) : ''; ?>" name="bdp_loader_image_id" id="bdp_loader_image_id">
												<input type="hidden" value="<?php echo esc_attr( $loader_image_src ); ?>" name="bdp_loader_image_src" id="bdp_loader_image_src">
											</div>
											<div class="bdp_selected_loader_image">
												<span class="bdp_loader_image_holder">
													<?php
													if ( '' != $loader_image_src ) {
														echo '<img src="' . esc_url( $loader_image_src ) . '"/>';
													}
													?>
												</span>
											</div>
										</div>
									</div>
								</li>
								<li class="archive_loader_template default_loader">
									<div class="bdp-left"><span class="bdp-key-title"><?php esc_html_e( 'Choose Loader Icon Color', 'blog-designer-pro' ); ?></span></div>
									<div class="bdp-right">
										<?php $loader_color = isset( $bdp_settings['loader_color'] ) ? $bdp_settings['loader_color'] : ''; ?>
										<input type="text" name="loader_color" id="loader_color" value="<?php echo esc_attr( $loader_color ); ?>" data-default-color="<?php echo esc_attr( $loader_color ); ?>"/>
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
									<div class="bdp-left"><span class="bdp-key-title"><?php esc_html_e( 'Display ACF Field', 'blog-designer-pro' ); ?></span></div>
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
								<li class="bdp_setting_acf_field bdp_setting_acf_field_blog">
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
											'post_type' => 'post',
										)
									);
									if ( ! empty( $groups ) ) {
										?>
										<div class="bdp-left"><span class="bdp-key-title"><?php esc_html_e( 'Select ACF Field', 'blog-designer-pro' ); ?></span></div>
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
																><?php echo esc_html( $field_label ); ?>
															</option>
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
				<div id="bdpsocial" class="postbox postbox-with-fw-options" style=<?php echo esc_attr( $bdpsocial_class_show ); ?>>
					<div class="inside">
						<ul class="bdp-settings bdp-lineheight">
							<div class="bdp_grid_col_2">
								<li>
									<div class="bdp-left"><span class="bdp-key-title"><?php esc_html_e( 'Social Share', 'blog-designer-pro' ); ?></span></div>
									<div class="bdp-right">
										<?php
										$social_share = isset( $bdp_settings['social_share'] ) ? $bdp_settings['social_share'] : 1;
										?>
										<fieldset class="bdp-social-options buttonset buttonset-hide" data-hide='1'>
											<input id="social_share_1" name="social_share" type="radio" value="1" <?php checked( 1, $social_share ); ?> />
											<label id="" for="social_share_1" <?php checked( 1, $social_share ); ?>><?php esc_html_e( 'Enable', 'blog-designer-pro' ); ?></label>
											<input id="social_share_0" name="social_share" type="radio" value="0" <?php checked( 0, $social_share ); ?> />
											<label id="" for="social_share_0" <?php checked( 0, $social_share ); ?>> <?php esc_html_e( 'Disable', 'blog-designer-pro' ); ?></label>
										</fieldset>
									</div>
								</li>
								<li class="social_share_options">
									<div class="bdp-left"><span class="bdp-key-title"><?php esc_html_e( 'Social Share Style', 'blog-designer-pro' ); ?></span></div>
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
								<li class="social_share_options shape_social_icon">
									<div class="bdp-left"><span class="bdp-key-title"><?php esc_html_e( 'Shape of Social Icon', 'blog-designer-pro' ); ?></span></div>
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
								<li class="social_share_options size_social_icon">
									<div class="bdp-left"><span class="bdp-key-title"><?php esc_html_e( 'Size of Social Icon', 'blog-designer-pro' ); ?></span></div>
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
							<li class="social_share_options default_icon_layouts">
								<div class="bdp-left"><span class="bdp-key-title"><?php esc_html_e( 'Available Icon Themes', 'blog-designer-pro' ); ?></span></div>
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
							<h3 class="bdp-table-title"><?php esc_html_e( 'Social Share Links Settings', 'blog-designer-pro' ); ?></h3>
							<li class="social_share_options bdp-display-settings bdp-social-share-options">
								<div class="bdp-typography-wrapper bdp_grid_col_4">
									<div class="bdp-typography-cover">
										<div class="bdp-typography-label">
											<span class="bdp-key-title"><?php esc_html_e( 'Facebook Share Link', 'blog-designer-pro' ); ?></span>
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
											<span class="bdp-key-title"><?php esc_html_e( 'Twitter Share Link', 'blog-designer-pro' ); ?></span>
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
											<span class="bdp-key-title"><?php esc_html_e( 'Linkedin Share Link', 'blog-designer-pro' ); ?></span>
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
											<span class="bdp-key-title"><?php esc_html_e( 'Pinterest Share link', 'blog-designer-pro' ); ?></span>
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
											<span class="bdp-key-title"><?php esc_html_e( 'Show Pinterest on Featured Image', 'blog-designer-pro' ); ?></span>
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
											<span class="bdp-key-title"><?php esc_html_e( 'Skype Share Link', 'blog-designer-pro' ); ?></span>
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
											<span class="bdp-key-title"><?php esc_html_e( 'Pocket Share Link', 'blog-designer-pro' ); ?></span>
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
											<span class="bdp-key-title"><?php esc_html_e( 'Telegram Share Link', 'blog-designer-pro' ); ?></span>
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
											<span class="bdp-key-title"><?php esc_html_e( 'Reddit Share Link', 'blog-designer-pro' ); ?></span>
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
											<span class="bdp-key-title"><?php esc_html_e( 'Digg Share Link', 'blog-designer-pro' ); ?></span>
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
											<span class="bdp-key-title"><?php esc_html_e( 'Tumblr Share Link', 'blog-designer-pro' ); ?></span>
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
											<span class="bdp-key-title"><?php esc_html_e( 'WordPress Share Link', 'blog-designer-pro' ); ?></span>
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
											<span class="bdp-key-title"><?php esc_html_e( 'Share via Mail', 'blog-designer-pro' ); ?></span>
										</div>
										<div class="bdp-typography-content">
											<?php $email_link = isset( $bdp_settings['email_link'] ) ? $bdp_settings['email_link'] : 0; ?>
											<fieldset class="bdp-social-options bdp-linkedin_link buttonset">
												<input id="email_link_1" class="bdp-opts-button" name="email_link" type="radio" value="1" <?php checked( 1, $email_link ); ?> />
												<label id="bdp-opts-button" for="email_link_1"><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
												<label id="bdp-opts-button" for="email_link_0"><?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
												<input id="email_link_0" class="bdp-opts-button" name="email_link" type="radio" value="0" <?php checked( 0, $email_link ); ?> />
											</fieldset>
										</div>
									</div>
									<div class="bdp-typography-cover">
										<div class="bdp-typography-label">
											<span class="bdp-key-title"><?php esc_html_e( 'WhatsApp Share Link', 'blog-designer-pro' ); ?></span>
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
								<div class="bdp-left"><span class="bdp-key-title"><?php esc_html_e( 'Facebook Access Token', 'blog-designer-pro' ); ?></span></div>
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
							<li class="social_share_options mail_share_content">
								<div class="bdp-left"><span class="bdp-key-title"><?php esc_html_e( 'Mail Share Content', 'blog-designer-pro' ); ?></span></div>
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
										<p> [post_title] => <?php esc_html_e( 'Post Title', 'blog-designer-pro' ); ?> </p>
										<p> [post_link] => <?php esc_html_e( 'Post Link', 'blog-designer-pro' ); ?> </p>
										<p> [post_thumbnail] => <?php esc_html_e( 'Post Featured Image', 'blog-designer-pro' ); ?> </p>
										<p> [sender_name] => <?php esc_html_e( 'Mail Sender Name', 'blog-designer-pro' ); ?> </p>
										<p> [sender_email] => <?php esc_html_e( 'Mail Sender Email Address', 'blog-designer-pro' ); ?> </p>
									</div>
								</div>
							</li>
							<div class="bdp_grid_col_3">
								<li class="social_share_options mail_share_content">
									<div class="bdp-left"><span class="bdp-key-title"><?php esc_html_e( 'Remove Reply to email', 'blog-designer-pro' ); ?></span></div>
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
									<div class="bdp-left"><span class="bdp-key-title"><?php esc_html_e( 'Social Share Count Position', 'blog-designer-pro' ); ?></span></div>
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
									<div class="bdp-left"><span class="bdp-key-title"><?php esc_html_e( 'Social Share Position', 'blog-designer-pro' ); ?></span></div>
									<div class="bdp-right">
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
				<?php
				if ( is_plugin_active( 'blog-designer-ads/blog-designer-ads.php' ) ) {
					do_action( 'bdads_do_blog_settings', 'tab_content' );
				} else {
					?>
					<div id="bdpads" class="postbox postbox-with-fw-options" style=<?php echo esc_attr( $bdpads_class_show ); ?>>
						<div class="inside">
							<ul class="bdp-settings bdp-lineheight">
								<div class="bdp_grid_col_2">
									<li>
										<div class="ads-pro-feature">
											<div class="bdp-left"><?php esc_html_e( 'Ads', 'blog-designer-pro' ); ?></div>
											<div class="bdp-right">
												<?php
												$bdads_ads_set = isset( $bdp_settings['bdads_ads_set'] ) ? $bdp_settings['bdads_ads_set'] : '0';
												?>
												<fieldset class="buttonset green buttonset-hide" data-hide='1'>
													<input id="bdads_ads_set_1" name="bdads_ads_set" type="radio" value="1" <?php checked( 1, esc_html( $bdads_ads_set ) ); ?> /><label id="bdp-options-button" for="bdads_ads_set_1" <?php checked( 1, esc_html( $bdads_ads_set ) ); ?>><?php esc_html_e( 'Repeated', 'blog-designer-pro' ); ?></label>
													<input id="bdads_ads_set_2" name="bdads_ads_set" type="radio" value="2" <?php checked( 2, esc_html( $bdads_ads_set ) ); ?> /><label id="bdp-options-button bdp-ads-set" for="bdads_ads_set_2" <?php checked( 2, esc_html( $bdads_ads_set ) ); ?>><?php esc_html_e( 'Normal', 'blog-designer-pro' ); ?></label>
													<input id="bdads_ads_set_0" name="bdads_ads_set" type="radio" value="0" <?php checked( 0, esc_html( $bdads_ads_set ) ); ?> /><label id="bdp-options-button bdp-ads-set" for="bdads_ads_set_0" <?php checked( 0, esc_html( $bdads_ads_set ) ); ?>><?php esc_html_e( 'Disable', 'blog-designer-pro' ); ?></label>
												</fieldset>
											</div>
										</div>
									</li>
									<li class="bdads_is_random_ads_col">
										<div class="ads-pro-feature">
											<div class="bdp-left"><?php esc_html_e( 'Random Ads', 'blog-designer-pro' ); ?></div>
											<div class="bdp-right">
												<?php $bdads_is_random = isset( $bdp_settings['bdads_is_random'] ) ? $bdp_settings['bdads_is_random'] : '0'; ?>
												<fieldset class="buttonset buttonset-hide" data-hide='1'>
													<input id="bdads_is_random_1" name="bdads_is_random" type="radio" value="1" <?php checked( 1, $bdads_is_random ); ?> /><label id="bdp-options-button" for="bdads_is_random_1" <?php checked( 1, $bdads_is_random ); ?>><?php esc_html_e( 'Enable', 'blog-designer-pro' ); ?></label>
													<input id="bdads_is_random_0" name="bdads_is_random" type="radio" value="0" <?php checked( 0, $bdads_is_random ); ?> /><label id="bdp-options-button" for="bdads_is_random_0" <?php checked( 0, $bdads_is_random ); ?>><?php esc_html_e( 'Disable', 'blog-designer-pro' ); ?></label>
												</fieldset>
											</div>
										</div>
									</li>
									<li class="bdads_repeated_ads_col">
										<?php $repeated_ad_post_number = isset( $bdp_settings['repeatedAdPostNumber'] ) ? $bdp_settings['repeatedAdPostNumber'] : '1'; ?>
										<div class="ads-pro-feature">
											<div class="bdp-left"><?php esc_html_e( 'Show Ad after', 'blog-designer-pro' ); ?></div>
											<div class="bdp-right">
												<input type="number" id="repeatedAdPostNumber" name="repeatedAdPostNumber" step="1" min="0" value="<?php echo esc_html( $repeated_ad_post_number ); ?>" onkeypress="return isNumberKey(event)">
											</div>
										</div>
									</li>
								</div>
							</ul>
						</div>
					</div>
					<?php
				}
				?>
			</div>
		</div>
	</form>
	<div id="popupdiv" class="bdp-template-popupdiv bdp-archive-popupdiv" style="display: none;">
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
			<li class="current_tab"><a href="#all"><?php esc_html_e( 'All', 'blog-designer-pro' ); ?></a></li>
			<li><a href="#full-width"><?php echo esc_html__( 'Full Width', 'blog-designer-pro' ) . ' (' . esc_html( $count['full-width'] ) . ')'; ?></a></li>
			<li><a href="#grid"><?php echo esc_html__( 'Grid', 'blog-designer-pro' ) . ' (' . esc_html( $count['grid'] ) . ')'; ?></a></li>
			<li><a href="#masonry"><?php echo esc_html__( 'Masonry', 'blog-designer-pro' ) . ' (' . esc_html( $count['masonry'] ) . ')'; ?></a></li>
			<li><a href="#magazine"><?php echo esc_html__( 'Magazine', 'blog-designer-pro' ) . ' (' . esc_html( $count['magazine'] ) . ')'; ?></a></li>
			<li><a href="#timeline"><?php echo esc_html__( 'Timeline', 'blog-designer-pro' ) . ' (' . esc_html( $count['timeline'] ) . ')'; ?></a></li>
			<li><a href="#slider"><?php echo esc_html__( 'Slider', 'blog-designer-pro' ) . ' (' . esc_html( $count['slider'] ) . ')'; ?></a></li>
			<div class="bdp-blog-template-search-cover">
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
					<img src="<?php echo esc_attr( BLOGDESIGNERPRO_URL ) . '/admin/images/layouts/' . esc_attr( $value['image_name'] ); ?>" alt="<?php echo esc_attr( $value['template_name'] ); ?>" title="<?php echo esc_attr( $value['template_name'] ); ?>" data-value="<?php echo esc_attr( $key ); ?>">
					<div class="hover_overlay">
						<div class="popup-template-name">
							<div class="popup-select"><a href="#"><?php esc_html_e( 'Select Template', 'blog-designer-pro' ); ?></a></div>
							<div class="popup-view"><a href="<?php echo esc_url( $value['demo_link'] ); ?>" target="_blank"><?php esc_html_e( 'Live Demo', 'blog-designer-pro' ); ?></a></div>
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
	<div id="bdp-advertisement-popup">
		<div class="bdp-advertisement-cover">
			<a class="bdp-advertisement-link" target="_blank" href="<?php echo esc_url( 'https://codecanyon.net/item/blog-designer-ads/26605381' ); ?>">
				<img src="<?php echo esc_attr( BLOGDESIGNERPRO_URL ) . '/public/images/ads-wordpress-plugin.jpg'; ?>" />
			</a>
		</div>
	</div>
	<div id="popuploaderdiv" class="bdp-loader-popupdiv bdp-wrapper" style="display: none;">
		<div class="bdp-loader-style-box">
			<?php
			$total_bullets = count( $loaders );
			if ( $total_bullets > 0 ) {
				foreach ( $loaders as $key => $loader_html ) {
					?>
					<div class="bdp-dialog-loader-style <?php echo esc_attr( $key ); ?>">
						<input type="hidden" class="bdp-loader-style-hidden" value="<?php echo esc_attr( $key ); ?>" />
						<div class="bdp-loader-style-html">
							<?php echo wp_kses( $loader_html, Bdp_Admin_Functions::args_kses() ); ?>
						</div>
					</div>
					<?php
				}
			}
			?>
		</div>
	</div>
	<div id="popupnavifationdiv" class="bdp-navigation-popupdiv bdp-wrapper" style="display: none;">
		<div class="bdp-navigation-style-box">
			<?php
			for ( $i = 1; $i <= 9; $i++ ) {
				?>
				<div class="bdp-navigation-cover navigation<?php echo esc_attr( $i ); ?>">
					<input type="hidden" class="bdp-navigation-style-hidden" value="navigation<?php echo esc_attr( $i ); ?>" />
					<img src="<?php echo esc_url( BLOGDESIGNERPRO_URL ) . '/public/images/navigation/navigation' . esc_attr( $i ) . '.png'; ?>">
				</div>
				<?php
			}
			?>
		</div>
	</div>

	<div id="popuparrowdiv" class="bdp-arrow-popupdiv bdp-wrapper" style="display: none;">
		<div class="bdp-arrow-style-box">
			<?php
			for ( $i = 1; $i <= 6; $i++ ) {
				?>
				<div class="bdp-arrow-cover arrow<?php echo esc_attr( $i ); ?>">
					<input type="hidden" class="bdp-arrow-style-hidden" value="arrow<?php echo esc_attr( $i ); ?>" />
					<img src="<?php echo esc_url( BLOGDESIGNERPRO_URL ) . '/public/images/arrow/arrow' . esc_attr( $i ) . '.png'; ?>">
				</div>
				<?php
			}
			?>
		</div>
	</div>
</div>
<?php

<?php
/**
 * BDP Getting Started
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

$active_tab         = ( isset( $_GET['tab'] ) && '' != $_GET['tab'] ) ? esc_attr( sanitize_text_field( wp_unslash( $_GET['tab'] ) ) ) : 'help_file';
$bdp_wp_auto_update = new Bdp_Wp_Auto_Update();
$plugin_version     = get_option( 'bdp_version' );

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
if ( isset( $_GET['page'] ) && 'bdp_getting_started' === $_GET['page'] ) {
	$allsettings = get_option( 'bdp_single_file_template' );
	if ( $allsettings && is_serialized( $allsettings ) ) {
		$bdp_settings = maybe_unserialize( $allsettings );
	}
	$msg = '&msg=updated';
}

Bdp_Posts::admin_singlefile_actions();
?>
<div class="wrap getting-started-wrap">
	<h2 style="display: none;"></h2>
	<div class="intro">
		<div class="intro-content">
			<h3> <?php esc_html_e( 'Getting Started', 'blog-designer-pro' ); ?> </h3>
			<h4> <?php echo esc_html__( 'You will find everything you need to get started here. You are now equipped with arguably the most powerful WordPress', 'blog-designer-pro' ) . ' <b>' . esc_html__( 'Blog Layouts Builder', 'blog-designer-pro' ) . '</b>. ' . esc_html__( 'To enjoy the full experience, we strongly recommend to read the help file first and register your product with codecanyon license key.', 'blog-designer-pro' ); ?> </h4>
		</div>
		<div class="intro-logo">
			<div class="intro-logo-cover">
				<img src="<?php echo esc_attr( BLOGDESIGNERPRO_URL ) . '/admin/images/bdp-logo.png'; ?>" alt="<?php esc_html_e( 'Blog Designer PRO', 'blog-designer-pro' ); ?>" />
				<span class="bdp-version"><?php echo esc_html__( 'Version', 'blog-designer-pro' ) . ' ' . esc_html( $plugin_version ); ?></span>
			</div>
		</div>
	</div>
	<div class="blog-designer-panel">
		<ul class="blog-designer-panel-list">
			<li class="panel-item <?php echo ( 'help_file' === $active_tab ) ? 'active' : ''; ?>">
				<a href="<?php echo esc_url( admin_url( 'admin.php?page=bdp_getting_started&tab=help_file' ) ); ?>"><?php esc_html_e( 'Read This First', 'blog-designer-pro' ); ?></a>
			</li>
			<li class="panel-item <?php echo ( 'register_product' === $active_tab ) ? 'active' : ''; ?> <?php echo ( 'correct' === $bdp_wp_auto_update->get_remote_license() ) ? 'bdp-reg' : 'bdp-not-reg'; ?>">
				<a href="<?php echo esc_url( admin_url( 'admin.php?page=bdp_getting_started&tab=register_product' ) ); ?>"><?php esc_html_e( 'Register Product', 'blog-designer-pro' ); ?></a>
			</li>
			<li class="panel-item <?php echo ( 'plugin_settings' === $active_tab ) ? 'active' : ''; ?>">
				<a href="<?php echo esc_url( admin_url( 'admin.php?page=bdp_getting_started&tab=plugin_settings' ) ); ?>"><?php esc_html_e( 'Plugin Settings', 'blog-designer-pro' ); ?></a>
			</li>
			<li class="panel-item <?php echo ( 'support' === $active_tab ) ? 'active' : ''; ?>">
				<a href="<?php echo esc_url( admin_url( 'admin.php?page=bdp_getting_started&tab=support' ) ); ?>"><?php esc_html_e( 'Support', 'blog-designer-pro' ); ?></a>
			</li>
			<li class="panel-item <?php echo ( 'tools' === $active_tab ) ? 'active' : ''; ?>">
				<a href="<?php echo esc_url( admin_url( 'admin.php?page=bdp_getting_started&tab=tools' ) ); ?>"><?php esc_html_e( 'Tools', 'blog-designer-pro' ); ?></a>
			</li>
			<li class="panel-item <?php echo ( 'recomended_plugins' === $active_tab ) ? 'active' : ''; ?>">
				<a href="<?php echo esc_url( admin_url( 'admin.php?page=bdp_getting_started&tab=recomended_plugins' ) ); ?>"><?php esc_html_e( 'Recommended Plugins', 'blog-designer-pro' ); ?></a>
			</li>
			<li class="panel-item <?php echo ( 'system_status' === $active_tab ) ? 'active' : ''; ?>">
				<a href="<?php echo esc_url( admin_url( 'admin.php?page=bdp_getting_started&tab=system_status' ) ); ?>"><?php esc_html_e( 'System Status', 'blog-designer-pro' ); ?></a>
			</li>
		</ul>

		<div class="blog-designer-panel-wrap">
			<?php if ( 'help_file' === $active_tab ) : ?>
			<div id="bdp-help-file" class="bdp-help-file">
				<div class="bdp-panel-left">
					<div class="bdp-notification">
						<h2>
							<?php echo esc_html__( 'Success, The Blog Designer PRO is now activated!', 'blog-designer-pro' ) . ' &#x1F60A'; ?>
						</h2>
						<h4 class="do-create-test-page">
							<?php
							$blog_designer_setting                   = get_option( 'wp_blog_designer_settings' );
							$create_layout_from_blog_designer_notice = get_option( 'bdp_admin_notice_create_layout_from_blog_designer_dismiss', false );
							$sample_layout_notice                    = get_option( 'bdp_admin_notice_pro_layouts_dismiss', false );
							if ( '' != $blog_designer_setting && false == $create_layout_from_blog_designer_notice ) {
								esc_html_e( 'Would you like to create one sample blog page using Blog Designer (free plugin) Data?', 'blog-designer-pro' );
								?>
								<br/><br/>
								<a class="bdp-create-layout-using-blog-designer" href="<?php echo esc_url( add_query_arg( 'create-layout-using-blog-designer', 'new', admin_url( 'admin.php?page=layouts' ) ) ); ?>"><?php esc_html_e( 'Yes, Please do it', 'blog-designer-pro' ); ?></a> | <a href="<?php echo esc_url( 'https://www.solwininfotech.com/documents/wordpress/blog-designer-pro/#quick_guide' ); ?>" target="_blank"> <?php esc_html_e( 'No, I will configure my self (Give me steps)', 'blog-designer-pro' ); ?> </a> 
								<?php
							} elseif ( false == $sample_layout_notice ) {
								esc_html_e( 'Would you like to create one sample blog page to check usage of Blog Designer PRO plugin?', 'blog-designer-pro' );
								?>
								<br/><br/>
								<a class="bdp-create-layout" href="<?php echo esc_url( add_query_arg( 'sample-blog-layout', 'new', admin_url( 'admin.php?page=layouts' ) ) ); ?>"><?php esc_html_e( 'Yes, Please do it', 'blog-designer-pro' ); ?></a> | <a href="<?php echo esc_url( 'https://www.solwininfotech.com/documents/wordpress/blog-designer-pro/#quick_guide' ); ?>" target="_blank"> <?php esc_html_e( 'No, I will configure my self (Give me steps)', 'blog-designer-pro' ); ?> </a>
								<?php
							}
							?>

						</h4>
						<p><?php echo esc_html__( 'To customize your blog layouts,', 'blog-designer-pro' ) . ' <a href="admin.php?page=layouts" target="_blank">' . esc_html__( 'Go to Layouts', 'blog-designer-pro' ) . '</a>. ' . esc_html__( 'In case of doubt,', 'blog-designer-pro' ) . ' <a href="https://solwininfotech.com/documents/wordpress/blog-designer-pro/" target="_blank"> ' . esc_html__( 'Read Documentation', 'blog-designer-pro' ) . ' </a> ' . esc_html__( 'or create a support ticket on our', 'blog-designer-pro' ) . ' <a href="http://support.solwininfotech.com/" target="_blank">' . esc_html__( 'support portal', 'blog-designer-pro' ) . '</a>.'; ?> </p>
					</div>

					<h3>
						<?php esc_html_e( 'Getting Started', 'blog-designer-pro' ); ?> <span>(<?php esc_html_e( 'Must Read', 'blog-designer-pro' ); ?>)</span>
					</h3>
					<p><?php esc_html_e( 'Once you’ve activated your plugin, you’ll be redirected to a Getting Started page (Blog Designer > Getting Started). Here, you can view the required and helpful steps to use plugin.', 'blog-designer-pro' ); ?></p>

					<hr id="#bdp-important-things">
					<h3>
						<?php esc_html_e( 'Important things', 'blog-designer-pro' ); ?> <span>(<?php esc_html_e( 'Required', 'blog-designer-pro' ); ?>)</span> <a href="#bdp-important-things">#</a>
						<a class="back-to-top" href="#bdp-help-file"><?php esc_html_e( 'Back to Top', 'blog-designer-pro' ); ?></a>
					</h3>
					<p><?php esc_html_e( 'To use Blog Designer, follow the below steps for initial setup - Correct the Reading Settings.', 'blog-designer-pro' ); ?></p>
					<ul>
						<li><?php echo esc_html__( 'To check the reading settings, click', 'blog-designer-pro' ) . ' <b><a href="options-reading.php" target="_blank">' . esc_html__( 'Settings > Reading', 'blog-designer-pro' ) . '</a></b> ' . esc_html__( 'in the WordPress admin menu.', 'blog-designer-pro' ); ?></li>
						<li><?php echo esc_html__( 'If your', 'blog-designer-pro' ) . '<b> ' . esc_html__( 'Posts page', 'blog-designer-pro' ) . ' </b> ' . esc_html__( ' is selected with the same page selection as the page you selected under Blog Designer Settings, then change that selection from the dropdown to the default', 'blog-designer-pro' ) . '</b> ( <b>" — ' . esc_html__( 'Select', 'blog-designer-pro' ) . ' — "</b> )'; ?></li>
					</ul>

					<hr id="bdp-shortcode-usage">
					<h3>
						<?php esc_html_e( 'How to use Blog Designer Shortcode?', 'blog-designer-pro' ); ?> <span>(<?php esc_html_e( 'Optional', 'blog-designer-pro' ); ?>)</span> <a href="#bdp-shortcode-usage">#</a>
						<a class="back-to-top" href="#bdp-help-file"><?php esc_html_e( 'Back to Top', 'blog-designer-pro' ); ?></a>
					</h3>
					<p><?php esc_html_e( 'Blog Designer is flexible to be used with any page builders like WPBakery Page Builder, Elementor, Beaver Builder, SiteOrigin, Tailor, etc.', 'blog-designer-pro' ); ?></p>
					<ul>
						<li><?php echo esc_html__( 'Use shortcode', 'blog-designer-pro' ) . ' <b>[wp_blog_designer id="xx"]</b> ' . esc_html__( 'in any WordPress post or page.', 'blog-designer-pro' ); ?></li>
						<li><?php echo esc_html__( 'Use', 'blog-designer-pro' ) . '<b> &lt;&quest;php echo do_shortcode("[wp_blog_designer id="xx"]"); &nbsp;&quest;&gt; </b>' . esc_html__( 'into a template file within your theme files.', 'blog-designer-pro' ); ?></li>
					</ul>

					<hr id="bdp-dummy-posts">
					<h3>
						<?php esc_html_e( 'Import Dummy Post', 'blog-designer-pro' ); ?> <span>(<?php esc_html_e( 'Optional', 'blog-designer-pro' ); ?>)</span> <a href="#bdp-dummy-posts">#</a>
						<a class="back-to-top" href="#bdp-help-file"><?php esc_html_e( 'Back to Top', 'blog-designer-pro' ); ?></a>
					</h3>
					<p><?php esc_html_e( 'We have created a dummy set of posts for you to get started with Blog Designer PRO.', 'blog-designer-pro' ); ?></p>
					<p><?php esc_html_e( 'To import the dummy posts, follow the below process:', 'blog-designer-pro' ); ?></p>
					<ul>
						<li><?php echo esc_html__( 'Go to', 'blog-designer-pro' ) . ' <b>' . esc_html__( 'Tools > Import', 'blog-designer-pro' ) . '</b> ' . esc_html__( 'in WordPress Admin panel.', 'blog-designer-pro' ); ?></li>
						<li><?php echo esc_html__( 'Run', 'blog-designer-pro' ) . '<b> ' . esc_html__( 'WordPress Importer', 'blog-designer-pro' ) . '</b> ' . esc_html__( 'at the end of the presentated list.', 'blog-designer-pro' ); ?></li>
						<li><?php echo esc_html__( 'You will be redirected on', 'blog-designer-pro' ) . ' <b>' . esc_html__( 'Import WordPress', 'blog-designer-pro' ) . '</b> ' . esc_html__( 'where we need to select actual sample posts XML file.', 'blog-designer-pro' ); ?></li>
						<li><?php echo esc_html__( 'Select', 'blog-designer-pro' ) . ' <b> import-sample_posts.xml </b> ' . esc_html__( 'from', 'blog-designer-pro' ) . ' <b>' . esc_html__( 'blog-designer-pro > admin > dummy-data', 'blog-designer-pro' ) . '</b> ' . esc_html__( 'folder.', 'blog-designer-pro' ); ?></li>
						<li><?php echo esc_html__( 'Click on', 'blog-designer-pro' ) . ' <b>' . esc_html__( 'Upload file and import', 'blog-designer-pro' ) . '</b> ' . esc_html__( 'and with next step please select', 'blog-designer-pro' ) . ' <b>' . esc_html__( 'Download and import file attachments', 'blog-designer-pro' ) . '</b> ' . esc_html__( 'checkbox.', 'blog-designer-pro' ); ?></li>
						<li><?php esc_html_e( 'All done! Your website is ready with sample blog posts.', 'blog-designer-pro' ); ?></li>
					</ul>
				</div>
				<div class="bd-panel-right">
					<h3><?php esc_html_e( 'Blog Designer Ads', 'blog-designer-pro' ); ?></h3>
					<div class="panel-aside">
						<a href="<?php echo esc_url( 'https://1.envato.market/NM3AO' ); ?>" target="_blank">
							<img src="https://solwincdn-79e1.kxcdn.com/wp-content/uploads/2020/05/Blog-Designer-Ads-WordPress-Plugin.jpg" alt="<?php esc_html_e( 'Blog designer ads', 'blog-designer-pro' ); ?>" />
						</a>
						<div class="panel-club-inside">
							<p><?php echo '<b>' . esc_html__( 'Blog Designer Ads', 'blog-designer-pro' ) . '</b> ' . esc_html__( 'is an add-on WordPress plugin for Blog Designer Pro and Blog Designer plugin which enables you to earn money with advertising and branding. Blog Designer Ads supports 3rd party ads such as Google AdSense, Amazone Ads, Yahoo and Bing ads so you you can earn more money with your blog, also supports custom ads with many customization features such as html ads, image ads, slider etc so that you can add branding / 3rd party advertising banners and earn more from it. There are customization settings such as font color, background, margin, padding, border etc to display your ads beautifully.', 'blog-designer-pro' ); ?></p>
							<a href="<?php echo esc_url( 'https://1.envato.market/NM3AO' ); ?>" target="_blank"><?php esc_html_e( 'Read More', 'blog-designer-pro' ); ?></a>
						</div>
					</div>
					<h3><?php esc_html_e( 'Other Premium Plugins', 'blog-designer-pro' ); ?></h3>
					<div class="panel-aside">
						<a href="<?php echo esc_url( 'https://www.solwininfotech.com/product/wordpress-plugins/avartan-slider/' ); ?>" target="_blank">
							<img src="https://solwincdn-79e1.kxcdn.com/wp-content/uploads/2015/10/avartan-responsive-slider.png" alt="<?php esc_html_e( 'Avartan Slider', 'blog-designer-pro' ); ?>" />
						</a>
						<div class="panel-club-inside">
							<p><?php echo '<b>' . esc_html__( 'Avartan Slider', 'blog-designer-pro' ) . '</b> ' . esc_html__( 'is a responsive WordPress plugin to create stunning image slider and video slider for your WordPress website. It has unique features like drag and drop visual slider builder, multi-media content, etc.', 'blog-designer-pro' ); ?></p>
							<a href="<?php echo esc_url( 'https://www.solwininfotech.com/product/wordpress-plugins/avartan-slider/' ); ?>" target="_blank"><?php esc_html_e( 'Read More', 'blog-designer-pro' ); ?></a>
						</div>
					</div>
					<div class="panel-aside">
						<a href="<?php echo esc_url( 'https://www.solwininfotech.com/product/wordpress-plugins/portfolio-designer/' ); ?>" target="_blank">
							<img src="https://solwincdn-79e1.kxcdn.com/wp-content/uploads/2017/02/Portfolio-Designer-WordPress-Plugin.png" alt="<?php esc_html_e( 'Portfolio Designer', 'blog-designer-pro' ); ?>" />
						</a>
						<div class="panel-club-inside">
							<p><?php echo '<b>' . esc_html__( 'Portfolio Designer', 'blog-designer-pro' ) . '</b> ' . esc_html__( 'is a WordPress plugin used to build portfolio in any desired layout. This plugin is user friendly, So no matter if you are a beginner, WordPress user, Designer or a Developer, no additional coding knowledge is required in creating portfolio layouts.', 'blog-designer-pro' ); ?></p>
							<a href="<?php echo esc_url( 'https://www.solwininfotech.com/product/wordpress-plugins/portfolio-designer/' ); ?>" target="_blank"><?php esc_html_e( 'Read More', 'blog-designer-pro' ); ?></a>
						</div>
					</div>

					<h3><?php esc_html_e( 'Other Premium Themes', 'blog-designer-pro' ); ?></h3>
					<div class="panel-aside">
						<a href="<?php echo esc_url( 'https://www.solwininfotech.com/product/wordpress-themes/kosmic/' ); ?>" target="_blank">
							<img src="https://solwincdn-79e1.kxcdn.com/wp-content/uploads/2018/09/Kosmic-Multipurpose-Responsive-WordPress-Theme.png" alt="<?php esc_html_e( 'Kosmic', 'blog-designer-pro' ); ?>" />
						</a>
						<div class="panel-club-inside">
							<p><?php echo '<b>' . esc_html__( 'Kosmic', 'blog-designer-pro' ) . '</b> ' . esc_html__( 'is a multipurpose WordPress theme which is suitable for almost all types of websites. This eCommerce theme is very simple, clean and professional. It comes with an extensive theme options panel to customize your site easily as per your requirements.', 'blog-designer-pro' ); ?></p>
							<a href="<?php echo esc_url( 'https://www.solwininfotech.com/product/wordpress-themes/kosmic/' ); ?>" target="_blank"><?php esc_html_e( 'Read More', 'blog-designer-pro' ); ?></a>
						</div>
					</div>
					<div class="panel-aside">
						<a href="<?php echo esc_url( 'https://www.solwininfotech.com/product/wordpress-themes/foodfork/' ); ?>" target="_blank">
							<img src="https://solwincdn-79e1.kxcdn.com/wp-content/uploads/2016/06/FoodFork-Restaturant-WordPress-Theme.jpg" alt="<?php esc_html_e( 'FoodFork', 'blog-designer-pro' ); ?>" />
						</a>
						<div class="panel-club-inside">
							<p><?php echo '<b>' . esc_html__( 'FoodFork', 'blog-designer-pro' ) . '</b> ' . esc_html__( 'is a premium WordPress theme for Restaurants and food business websites. You can use this theme for your business websites like restaurant, cafe, coffee shop, fast food or pizza store.', 'blog-designer-pro' ); ?></p>
							<a href="<?php echo esc_url( 'https://www.solwininfotech.com/product/wordpress-themes/foodfork/' ); ?>" target="_blank"><?php esc_html_e( 'Read More', 'blog-designer-pro' ); ?></a>
						</div>
					</div>
					<div class="panel-aside">
						<a href="<?php echo esc_url( 'https://www.solwininfotech.com/product/wordpress-themes/jewelux/' ); ?>" target="_blank">
							<img src="https://solwincdn-79e1.kxcdn.com/wp-content/uploads/2016/02/JewelUX-WordPress-Premium-Theme.jpg" alt="<?php esc_html_e( 'JewelUX', 'blog-designer-pro' ); ?>" />
						</a>
						<div class="panel-club-inside">
							<p><?php echo '<b>' . esc_html__( 'JewelUX', 'blog-designer-pro' ) . '</b> ' . esc_html__( 'is a clean and modern jewelry WordPress theme designed for any online jewelry website. It’s a WooCommerce theme with responsive layout, fully widgetized and animated home page.', 'blog-designer-pro' ); ?></p>
							<a href="<?php echo esc_url( 'https://www.solwininfotech.com/product/wordpress-themes/jewelux/' ); ?>" target="_blank"><?php esc_html_e( 'Read More', 'blog-designer-pro' ); ?></a>
						</div>
					</div>
				</div>
			</div>
			<?php endif; ?>

			<?php
			if ( 'register_product' === $active_tab ) :
				global $wp_version, $wpdb, $bdp_errors, $bdp_success, $bdp_settings;
				$return = '';
				if ( isset( $_POST['bdp_sbt_purchasecode'] ) ) {
					$sol_username  = isset( $_POST['sol_username'] ) ? sanitize_text_field( wp_unslash( $_POST['sol_username'] ) ) : '';
					$purchase_code = isset( $_POST['sol_purchase_code'] ) ? sanitize_text_field( wp_unslash( $_POST['sol_purchase_code'] ) ) : '';
					$return        = $bdp_wp_auto_update->update_license( trim( $sol_username ), trim( $purchase_code ) );
				}
				if ( isset( $_POST['bdp_deregister_purchasecode'] ) ) {
					$sol_username  = isset( $_POST['sol_username'] ) ? sanitize_text_field( wp_unslash( $_POST['sol_username'] ) ) : '';
					$purchase_code = isset( $_POST['sol_purchase_code'] ) ? sanitize_text_field( wp_unslash( $_POST['sol_purchase_code'] ) ) : '';
					$return        = $bdp_wp_auto_update->deregister_site( trim( $sol_username ), trim( $purchase_code ) );
				}
				$sol_username    = get_option( 'bdp_username' );
				$purchase_code   = get_option( 'bdp_purchase_code' );
				$bdp_information = $bdp_wp_auto_update->get_remote_information();
				?>
				<div id="bdp-register-product" class="bdp-register-product" >
					<?php if ( 'correct' === $return ) { ?>
						<div class="bdp-updated bdp-notice">
							<p><?php esc_html_e( 'License updated successfully.', 'blog-designer-pro' ); ?></p>
						</div>
						<?php
					} elseif ( 'used' === $return ) {
						?>
						<div class="bdp-error bdp-notice">
							<p><?php esc_html_e( 'License Key already used.', 'blog-designer-pro' ); ?></p>
						</div>
						<?php
					} elseif ( 'incorrect' === $return ) {
						?>
						<div class="bdp-error bdp-notice">
							<p><?php esc_html_e( 'Wrong license key.', 'blog-designer-pro' ); ?></p>
						</div>
						<?php
					} elseif ( 'unsuccess' === $return ) {
						?>
						<div class="bdp-error bdp-notice">
							<p><?php esc_html_e( 'Site is not registered with this license key.', 'blog-designer-pro' ); ?></p>
						</div>
						<?php
					} elseif ( 'success' === $return ) {
						?>
						<div class="bdp-updated bdp-notice">
							<p><?php esc_html_e( 'Site has been De-Registered successfully.', 'blog-designer-pro' ); ?></p>
						</div>
						<?php
					}
					?>
					<h3>
						<?php esc_html_e( 'Register your plugin copy', 'blog-designer-pro' ); ?>
					</h3>
					<p><?php esc_html_e( 'Verify your codecanyon item purchase key to get automatic updates, notifications on your WordPress dashboard.', 'blog-designer-pro' ); ?></p>

					<form method="post" name="bdp_frm_purchasecode" id="bdp_frm_purchasecode">
						<p>
							<span class="bdp-lable"> <b><?php esc_html_e( 'Username', 'blog-designer-pro' ); ?> : </b> </span>
							<input value="<?php echo esc_attr( $sol_username ); ?>" required="" type="text" name="sol_username" id="sol_username" />
							<i><small><?php esc_html_e( 'Username will be your registered username with', 'blog-designer-pro' ); ?>&nbsp;<a target="blank" href="https://codecanyon.net/"><?php esc_html_e( 'codecanyon', 'blog-designer-pro' ); ?></a></small></i>
						</p>
						<p>
							<span class="bdp-lable"> <b><?php esc_html_e( 'License Key', 'blog-designer-pro' ); ?> : </b> </span>
							<input value="<?php echo esc_attr( $purchase_code ); ?>" required="" type="password" name="sol_purchase_code" id="sol_purchase_code" />
							<i><small><a target="blank" href="<?php echo esc_url( 'https://help.market.envato.com/hc/en-us/articles/202822600-Where-Is-My-Purchase-Code' ); ?>"> <?php esc_html_e( 'Click here', 'blog-designer-pro' ); ?> </a> <?php esc_html_e( 'to know how to get item purchase code.', 'blog-designer-pro' ); ?></small></i>
						</p>
						<p>
							<input class="bdp-btn-success" type="submit" value="<?php echo esc_attr( '' != $sol_username ) ? esc_html__( 'Update License', 'blog-designer-pro' ) : esc_html__( 'Verify License', 'blog-designer-pro' ); ?>" name="bdp_sbt_purchasecode" />
							<?php if ( '' != $sol_username ) { ?>
							<input class="bdp-btn-error" type="submit" value="<?php echo esc_html__( 'De-Register Site', 'blog-designer-pro' ); ?>" name="bdp_deregister_purchasecode" />
						<?php } ?>
						</p>
						<i> Note: If plugin was packed with your theme then it will update by your theme provider. </i>
					</form>
				</div>

				<?php
			endif;
			?>
			<?php
			if ( 'plugin_settings' === $active_tab ) :
				$msg       = '';
				$msg_class = '';
				if ( isset( $_POST['submit_display'] ) && isset( $_POST['_wp_bdp_settings_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['_wp_bdp_settings_nonce'] ) ), '_wp_bdp_settings_action' ) ) {
					$msg_class = 'updated';
					if ( isset( $_POST['bdp_delete_data'] ) ) {
						update_option( 'bdp_delete_data', '1' );
					} else {
						update_option( 'bdp_delete_data', '0' );
					}
					if ( isset( $_POST['bdp_disable_fa_css'] ) ) {
						update_option( 'bdp_disable_fa_css', '1' );
					} else {
						update_option( 'bdp_disable_fa_css', '0' );
					}
					$msg = esc_html__( 'Blog Designer PRO setting has been updated successfully.', 'blog-designer-pro' );
				}
				if ( isset( $_POST['reset_count'] ) && isset( $_POST['_wp_bdp_settings_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['_wp_bdp_settings_nonce'] ) ), '_wp_bdp_settings_action' ) ) {
					$count_val = 0;
					if ( Bdp_Woocommerce::is_woocommerce_plugin() && is_plugin_active( 'easy-digital-downloads/easy-digital-downloads.php' ) ) {
						$arg_posts = array(
							'post_type'      => array( 'post', 'product', 'download' ),
							'posts_per_page' => -1,
						);
					} elseif ( Bdp_Woocommerce::is_woocommerce_plugin() && ! is_plugin_active( 'easy-digital-downloads/easy-digital-downloads.php' ) ) {
						$arg_posts = array(
							'post_type'      => array( 'post', 'product' ),
							'posts_per_page' => -1,
						);
					} elseif ( ! Bdp_Woocommerce::is_woocommerce_plugin() && is_plugin_active( 'easy-digital-downloads/easy-digital-downloads.php' ) ) {
						$arg_posts = array(
							'post_type'      => array( 'post', 'download' ),
							'posts_per_page' => -1,
						);
					} else {
						$arg_posts = array(
							'post_type'      => 'post',
							'posts_per_page' => -1,
						);
					}
					$loop = new WP_Query( $arg_posts );
					while ( $loop->have_posts() ) :
						$loop->the_post();
						$post_id         = $loop->post->ID;
						$count_key       = '_bdp_post_views_count';
						$daily_count_key = '_bdp_post_daily_count';
						delete_post_meta_by_key( $count_key );
						delete_post_meta_by_key( $daily_count_key );
					endwhile;
					$msg = esc_html__( 'Reset Post Count(s) has been updated successfully.', 'blog-designer-pro' );
				}
				if ( '' != $msg ) {
					?>
					<div class="bdp-updated bdp-notice updated">
						<p><?php echo esc_html( $msg ); ?></p>
					</div>
					<?php
				}
				$bdp_delete_data    = get_option( 'bdp_delete_data', 0 );
				$bdp_disable_fa_css = get_option( 'bdp_disable_fa_css', 0 );
				?>
				<div class="bdp-plugin-settings">
					<div class="bdp-ub-header">
						<h3><?php esc_html_e( 'Blog Designer PRO plugin Settings', 'blog-designer-pro' ); ?> </h3>
					</div>
					<form id="frmbdpsetting" name="frmbdpsetting" method="post" action="">
						<div class="bdp-ub-right">
							<input id="bdp_disable_fa_css" type="checkbox" value="1" <?php checked( '1', $bdp_disable_fa_css ); ?> name="bdp_disable_fa_css">&nbsp;<label for="bdp_disable_fa_css"><?php esc_html_e( 'Do you want to disable Font Awesome CSS?', 'blog-designer-pro' ); ?></label>
						</div>
						<div class="bdp-ub-right">
							<input id="bdp_delete_data" type="checkbox" value="1" <?php checked( '1', $bdp_delete_data ); ?> name="bdp_delete_data">&nbsp;<label for="bdp_delete_data"><?php esc_html_e( 'Delete data on deletion of plugin.', 'blog-designer-pro' ); ?></label>
						</div>
						<p class="submit">
							<input id="submit" class="button button-primary" type="submit" value="<?php esc_attr_e( 'Save Changes', 'blog-designer-pro' ); ?>" name="submit_display">
						</p>
						<div class="bdp-ub-right">
							<?php esc_html_e( 'Do you want to reset the post count? ', 'blog-designer-pro' ); ?> <input id="reset_submit" class="button button-secondary bdp-button" type="submit" value="<?php esc_attr_e( 'Reset Post Count', 'blog-designer-pro' ); ?>" name="reset_count">
						</div>
						<?php wp_nonce_field( '_wp_bdp_settings_action', '_wp_bdp_settings_nonce' ); ?>
					</form>
				</div>
			<?php endif; ?>
			<?php if ( 'support' === $active_tab ) : ?>
				<div id="bdp-support" class="bdp-support">
					<div class="bdp-line-cover">
						<div class="bdp-line-content">
							<h3>
								<a href="<?php echo esc_url( 'https://www.solwininfotech.com/documents/wordpress/blog-designer-pro/' ); ?>" target="_blank"><?php esc_html_e( 'Documentation', 'blog-designer-pro' ); ?> </a>
							</h3>
							<p><?php esc_html_e( 'Read helpful resources regarding how to use The Blog Designer Plugin more efficiently.', 'blog-designer-pro' ); ?></p>
						</div>
						<div class="bdp-line-button">
							<p> <a class="button button-primary bdp-button" href="<?php echo esc_url( 'https://www.solwininfotech.com/documents/wordpress/blog-designer-pro/' ); ?>" target="_blank"><?php esc_html_e( 'Read Documentation', 'blog-designer-pro' ); ?></a> </p>
						</div>
					</div>
					<hr/>
					<div class="bdp-line-cover">
						<div class="bdp-line-content">
							<h3>
								<a href="<?php echo esc_url( 'https://www.solwininfotech.com/documents/wordpress/blog-designer-pro/#faq' ); ?>" target="_blank"><?php esc_html_e( 'FAQ', 'blog-designer-pro' ); ?> </a>
							</h3>
							<p><?php esc_html_e( 'The most frequently asked questions are answered here.', 'blog-designer-pro' ); ?></p>
						</div>
						<div class="bdp-line-button">
							<p> <a class="button button-primary bdp-button" href="<?php echo esc_url( 'https://www.solwininfotech.com/documents/wordpress/blog-designer-pro/#faq' ); ?>" target="_blank"><?php esc_html_e( 'Read FAQ', 'blog-designer-pro' ); ?></a> </p>
						</div>
					</div>

					<hr/>
					<div class="bdp-line-cover">
						<div class="bdp-line-content">
							<h3>
								<a href="<?php echo esc_url( 'http://support.solwininfotech.com/' ); ?>" target="_blank"><?php esc_html_e( 'Ask our experts', 'blog-designer-pro' ); ?> </a>
							</h3>
							<p><?php esc_html_e( 'Any question that is not addressed on documentation? Ask it from Solwin Infotech experts. Note that you need to share your codecanyon license key to be able to get premium support.', 'blog-designer-pro' ); ?></p>
						</div>
						<div class="bdp-line-button">
							<p> <a class="button button-primary bdp-button" href="<?php echo esc_url( 'http://support.solwininfotech.com/' ); ?>" target="_blank" ><?php esc_html_e( 'Submit a Ticket', 'blog-designer-pro' ); ?></a> </p>
						</div>
					</div>
					<hr/>
					<div class="bdp-line-cover">
						<div class="bdp-line-content">
							<h3>
								<a href="<?php echo esc_url( 'https://www.solwininfotech.com/contact-us/' ); ?>" target="_blank"><?php esc_html_e( 'Customize The Plugin', 'blog-designer-pro' ); ?> </a>
							</h3>
							<p><?php esc_html_e( 'Have some more customization beyond what The Blog Designer PRO offers? Solwin Infotech experts are here to help.', 'blog-designer-pro' ); ?></p>
						</div>
						<div class="bdp-line-button">
							<p> <a class="button button-primary bdp-button" href="<?php echo esc_url( 'https://www.solwininfotech.com/contact-us/' ); ?>" target="_blank"><?php esc_html_e( 'Hire Us', 'blog-designer-pro' ); ?></a> </p>
						</div>
					</div>
					<hr/>
				</div>
			<?php endif; ?>
			<?php if ( 'tools' === $active_tab ) : ?>
				<div id="bdp-tools" class="bdp-tools">
					<div  class="bdp-tools-cover">
						<?php
						$blog_designer_setting = get_option( 'wp_blog_designer_settings' );
						if ( '' != $blog_designer_setting ) {
							?>
							<div class="bdp-line-cover">
								<div class="bdp-line-content">
									<h3>
										<?php echo esc_html__( 'Create Blog Layout using Blog Designer (free plugin) Data', 'blog-designer-pro' ); ?>
									</h3>
									<p> <?php echo esc_html__( 'Create your first Blog layout after switching from free to PRO version plugin. This action will use your Blog Designer (free version) Plugin data and create PRO version layout. This is a', 'blog-designer-pro' ) . ' <b>' . esc_html__( 'recommended', 'blog-designer-pro' ) . '</b> ' . esc_html__( 'action if you have upgraded your plugin from lite to PRO.', 'blog-designer-pro' ); ?> </p>
								</div>
								<div class="bdp-line-button">
									<p> <a class="bdp-create-layout-using-blog-designer button button-secondary bdp-button" href="<?php echo esc_url( add_query_arg( 'create-layout-using-blog-designer', 'new', admin_url( 'admin.php?page=layouts' ) ) ); ?>"> <?php echo esc_html__( 'Create Layout', 'blog-designer-pro' ); ?></a> </p>
								</div>
							</div>
							<hr/>
							<?php
						}
						?>


						<div class="bdp-line-cover">
							<div class="bdp-line-content">
								<h3>
									<?php echo esc_html__( 'Create New Sample Blog layout', 'blog-designer-pro' ); ?>
								</h3>
								<p> <?php echo esc_html__( 'If you are newbie with Blog Designer PRO plugin and do not know from where to start and create your first blog layout with the site then this automatic stuff will help you to create your first blog layout via with', 'blog-designer-pro' ) . ' <b>' . esc_html__( 'One Click', 'blog-designer-pro' ) . '</b> ' . esc_html__( 'action.', 'blog-designer-pro' ); ?> </p>
							</div>
							<div class="bdp-line-button">
								<p> <a class="bdp-create-layout button button-secondary bdp-button" href="<?php echo esc_url( add_query_arg( 'sample-blog-layout', 'new', admin_url( 'admin.php?page=layouts' ) ) ); ?>"> <?php echo esc_html__( 'Create Layout', 'blog-designer-pro' ); ?> </a> </p>
							</div>
						</div>
						<hr/>
						<div class="bdp-line-cover single_file_override">
							<?php

							$template_name = BLOGDESIGNERPRO_DIR . 'bdp_templates/single/single.php';
							$template      = 'single/single.php';
							$template_file = apply_filters( 'bdp_locate_core_template', $template_name, $template );
							$local_file    = Bdp_Template::get_theme_template_file( $template );
							$template_dir  = apply_filters( 'bdp_template_directory', 'bdp_templates', $template );

							?>
							<div class="bdp-line-content">
								<h3>
									<?php esc_html_e( 'Single file Override', 'blog-designer-pro' ); ?>&nbsp;
								</h3>
								<p>
									<?php
									if ( file_exists( $local_file ) ) {
										esc_html_e( 'This template has been overridden by your theme and can be found in', 'blog-designer-pro' );
										echo ': <code>' . esc_html( trailingslashit( basename( get_stylesheet_directory() ) ) ) . esc_html( $template_dir ) . '/' . esc_html( $template ) . '</code>.';
									} elseif ( file_exists( $template_name ) ) {
										esc_html_e( 'To override or edit single template file according to your active theme requirements then please copy this file', 'blog-designer-pro' );
										echo ' "<code>' . esc_html( plugin_basename( $template_name ) ) . '</code>" ';
										esc_html_e( 'and paste into your active theme folder with this location', 'blog-designer-pro' );
										echo ' <code>' . esc_html( trailingslashit( basename( get_stylesheet_directory() ) ) ) . esc_html( $template_dir ) . '/' . esc_html( $template ) . '</code>.';
									}
									?>
								</p>
							</div>
							<div class="bdp-line-button">
								<p>
									<a class="button single_toggle_editor bdp-button" href="#"><?php esc_html_e( 'Hide template', 'blog-designer-pro' ); ?></a>
								</p>
								<?php
								if ( file_exists( $local_file ) ) {
									if ( is_writable( $local_file ) ) {
										?>
										<p>
											<a href="<?php echo esc_url( wp_nonce_url( remove_query_arg( array( 'move_template', 'saved' ), add_query_arg( 'delete_template', $template ) ), 'bdp_single_template_nonce', '_bdp_single_nonce' ) ); ?>" class="delete_template button bdp-button"><?php esc_html_e( 'Delete Template File', 'blog-designer-pro' ); ?></a>
										</p>
										<?php
									}
								} elseif ( file_exists( $template_name ) ) {
									if ( ( is_dir( get_stylesheet_directory() . '/' . $template_dir . '/' ) && is_writable( get_stylesheet_directory() . '/' . $template_dir . '/' ) ) || is_writable( get_stylesheet_directory() ) ) {
										?>
										<p>
											<a href="<?php echo esc_url( wp_nonce_url( remove_query_arg( array( 'delete_template', 'saved' ), add_query_arg( 'move_template', $template ) ), 'bdp_single_template_nonce', '_bdp_single_nonce' ) ); ?>" class="button bdp-button"><?php esc_html_e( 'Copy File To Theme', 'blog-designer-pro' ); ?></a>
										</p>
										<?php
									}
								}
								?>

							</div>
							<div class="single_file_editor">
								<form id="" method="post">
								<?php wp_nonce_field( 'bdp-singlefile-form-submit', 'bdp-singlefile-submit-nonce' ); ?>
								<?php
								if ( file_exists( $local_file ) ) {
									?>
									<p>
										<textarea class="code" cols="25" rows="20" 
										<?php
										if ( ! is_writable( $local_file ) ) :
											?>
											readonly="readonly" disabled="disabled"
											<?php
										else :
											?>
											name="singlefile_html"<?php endif; ?>><?php echo esc_html( file_get_contents( $local_file ) ); ?></textarea>
									</p>
									<p>
										<input type="submit" style="" class="button-primary single_file_savebtn" name="savedata" value="<?php esc_attr_e( 'Save Changes', 'blog-designer-pro' ); ?>" />
									</p>
										<?php
								} else {
									?>
									<p>
										<textarea class="code" readonly="readonly" disabled="disabled" cols="25" rows="20"><?php echo esc_html( file_get_contents( $template_name ) ); ?></textarea>
									</p>
									<?php
								}
								?>
								</form>
							</div>
						</div>

						<?php

						$bdp_single_override_template_dir  = get_stylesheet_directory() . '/bdp_templates/single/';
						$bdp_archive_override_template_dir = get_stylesheet_directory() . '/bdp_templates/archive/';
						$bdp_blog_override_template_dir    = get_stylesheet_directory() . '/bdp_templates/blog/';
						if ( is_dir( $bdp_blog_override_template_dir ) || is_dir( $bdp_archive_override_template_dir ) || is_dir( $bdp_single_override_template_dir ) ) {
							$bdp_single_override_templates_layouts  = ( is_dir( $bdp_single_override_template_dir ) ) ? scandir( $bdp_single_override_template_dir ) : 1;
							$bdp_archive_override_templates_layouts = ( is_dir( $bdp_archive_override_template_dir ) ) ? scandir( $bdp_archive_override_template_dir ) : 1;
							$bdp_blog_override_templates_layouts    = ( is_dir( $bdp_blog_override_template_dir ) ) ? scandir( $bdp_blog_override_template_dir ) : 1;
							if ( ( is_array( $bdp_single_override_templates_layouts ) && ! empty( $bdp_single_override_templates_layouts ) && count( $bdp_single_override_templates_layouts ) > 2 ) || ( is_array( $bdp_archive_override_templates_layouts ) && ! empty( $bdp_archive_override_templates_layouts ) && count( $bdp_archive_override_templates_layouts ) > 2 ) || ( is_array( $bdp_blog_override_templates_layouts ) && ! empty( $bdp_blog_override_templates_layouts ) && count( $bdp_blog_override_templates_layouts ) > 2 ) ) {
								?>
								<hr/>
								<div class="bdp-line-cover">
									<div class="bdp-line-content">
										<h3>
											<?php esc_html_e( 'Your Theme is not compatible or contains outdated copies of some Blog Designer template files', 'blog-designer-pro' ); ?>.
										</h3>
										<p><?php echo esc_html__( 'These files may require to design your "Post Layouts" with the current version of Blog Designer PRO plugin. You can see which files are required or outdated from the theme.', 'blog-designer-pro' ); ?></p>
									</div>
									<div class="bdp-line-button">
										<p> <a class="button button-secondary bdp-button" href="<?php echo esc_url( admin_url( 'admin.php?page=bdp_getting_started&tab=system_status#bdp_templates_status' ) ); ?>" ><?php esc_html_e( 'Check Here', 'blog-designer-pro' ); ?></a> </p>
									</div>
								</div>
								<?php
							}
						}
						?>
					</div>
				</div>
			<?php endif; ?>

			<?php if ( 'recomended_plugins' === $active_tab ) : ?>
				<div id="bdp-recomended-plugins" class="bdp-recomended-plugins">
					<?php
					include_once 'class-blog-designer-pro-plugin-installer.php';
					if ( class_exists( 'Blog_Designer_PRO_Plugin_Installer' ) ) {
						$free_plugins = array(
							array(
								'slug' => 'regenerate-thumbnails',
							),
							array(
								'slug' => 'wordpress-seo',
							),
						);
						echo '<div class="bdp-plugin-status-cover">';
						Blog_Designer_PRO_Plugin_Installer::init( $free_plugins );
						echo '</div>';
					}
					?>
				</div>
			<?php endif; ?>

			<?php
			if ( 'system_status' === $active_tab ) :
				global $wpdb;
				?>
			<div  class="bdp-system-status-cover bdp-admin">
				<div id="bdp_wp_status_report">
					<span class="bdp-get-system-status">
						<span class="bdp-system-report-msg"><?php esc_html_e( 'Click the button to produce a report, then copy and paste into your support ticket.', 'blog-designer-pro' ); ?></span>
						<a href="#" class="button-primary bdp-debug-status-report bdp-button"><?php esc_html_e( 'Get System Report', 'blog-designer-pro' ); ?></a>
					</span>
					<div id="bdp-debug-report">
						<textarea id="bdp-copy-text" readonly="readonly"></textarea>
						<p class="submit">
							<button id="bdp-copy-for-support" class="button button-primary bdp-button" data-clipboard-target="#bdp-copy-text" href="#" data-tip="<?php esc_html_e( 'Copied!', 'blog-designer-pro' ); ?>"><?php esc_html_e( 'Copy for Support', 'blog-designer-pro' ); ?></button>
						</p>
					</div>
				</div>
				<br/> <hr/> <br/>

				<div class="bdp-status-cover" id="bdp_wp_status">
					<div class="bdp-status-head" data-export-label="WordPress Environment">
						<?php esc_html_e( 'WordPress Environment', 'blog-designer-pro' ); ?>
					</div>
					<div class="bdp-status-contents">
						<p>
							<span class="bdp-staus-lable" data-export-label="Home URL">
								<?php esc_html_e( 'Home URL', 'blog-designer-pro' ); ?>:
							</span>
							<span class="bdp-status-value">
								<?php form_option( 'home' ); ?>
							</span>
						</p>
						<p>
							<span class="bdp-staus-lable" data-export-label="Site URL">
								<?php esc_html_e( 'Site URL', 'blog-designer-pro' ); ?>:
							</span>
							<span class="bdp-status-value">
								<?php form_option( 'siteurl' ); ?>
							</span>
						</p>
						<p>
							<span class="bdp-staus-lable" data-export-label="WP Version">
								<?php esc_html_e( 'WP Version', 'blog-designer-pro' ); ?>:
							</span>
							<span class="bdp-status-value">
								<?php bloginfo( 'version' ); ?>
							</span>
						</p>
						<p>
							<span class="bdp-staus-lable" data-export-label="WP Memory Limit">
								<?php esc_html_e( 'WP Memory Limit', 'blog-designer-pro' ); ?>:
							</span>
							<span class="bdp-status-value">
								<?php
								$memory = Bdp_Utility::let_to_num( WP_MEMORY_LIMIT );

								if ( function_exists( 'memory_get_usage' ) ) {
									$system_memory = Bdp_Utility::let_to_num( @ini_get( 'memory_limit' ) );
									$memory        = max( $memory, $system_memory );
								}
								echo '<mark class="yes">' . esc_html( size_format( $memory ) ) . '</mark>';
								?>
							</span>
						</p>
						<p>
							<span class="bdp-staus-lable" data-export-label="WP Multisite">
								<?php esc_html_e( 'WP Multisite', 'blog-designer-pro' ); ?>:
							</span>
							<span class="bdp-status-value" data-export-label="<?php echo ( is_multisite() ) ? 'YES' : 'NO'; ?>">
								<?php echo ( is_multisite() ) ? '<mark class="yes"><span class="dashicons dashicons-yes"></span></mark>' : '<mark class="error"> <span class="dashicons dashicons-no-alt"></span></mark>'; ?>
							</span>
						</p>
						<p>
							<span class="bdp-staus-lable" data-export-label="Language">
								<?php esc_html_e( 'Language', 'blog-designer-pro' ); ?>
							</span>
							<span class="bdp-status-value" >
								<?php echo esc_html( get_locale() ); ?>
							</span>
						</p>
					</div>
				</div>

				<div class="bdp-status-cover" id="bdp_server_status">
					<div class="bdp-status-head" data-export-label="Server Environment">
						<?php esc_html_e( 'Server Environment', 'blog-designer-pro' ); ?>
					</div>
					<div class="bdp-status-contents">
						<p>
							<span class="bdp-staus-lable" data-export-label="Server info">
								<?php esc_html_e( 'Server info', 'blog-designer-pro' ); ?>:
							</span>
							<span class="bdp-status-value" >
								<?php
								$server_software = isset( $_SERVER['SERVER_SOFTWARE'] ) ? sanitize_text_field( wp_unslash( $_SERVER['SERVER_SOFTWARE'] ) ) : '';
								echo esc_html( $server_software );
								?>
							</span>
						</p>
						<p>
							<span class="bdp-staus-lable" data-export-label="PHP Version">
								<?php esc_html_e( 'PHP Version', 'blog-designer-pro' ); ?>:
							</span>
							<span class="bdp-status-value" >
								<?php
								if ( function_exists( 'phpversion' ) ) {
									$php_version = phpversion();
									if ( version_compare( $php_version, '5.6', '<' ) ) {
										echo '<mark class="error"> <span class="dashicons dashicons-warning"></span>' . esc_html( $php_version ) . '</mark>' . esc_html__( 'We recommend a minimum PHP version of 5.6.', 'blog-designer-pro' );
									} else {
										echo '<mark class="yes">' . esc_html( $php_version ) . '</mark>';
									}
								} else {
									esc_html_e( "Couldn't determine PHP version because", 'blog-designer-pro' );
									echo ' phpversion() ';
									esc_html_e( "doesn't exist.", 'blog-designer-pro' );
								}
								?>
							</span>
						</p>
						<?php
						if ( $wpdb->use_mysqli ) {
							$wpdb_ver = mysqli_get_server_info( $wpdb->dbh );// ignore in phpcs.
						} else {
							$wpdb_ver = mysql_get_server_info();// ignore in phpcs.
						}
						if ( ! empty( $wpdb->is_mysql ) && ! stristr( $wpdb_ver, 'MariaDB' ) ) :
							?>
							<p>
								<span class="bdp-staus-lable" data-export-label="MySQL Version">
									<?php esc_html_e( 'MySQL Version', 'blog-designer-pro' ); ?>:
								</span>
								<span class="bdp-status-value" >
									<?php
									$mysql_version = $wpdb->db_version();
									if ( version_compare( $mysql_version, '5.5', '<' ) ) {
										echo '<mark class="error"> <span class="dashicons dashicons-warning"></span>' . esc_html( $mysql_version ) . '</mark>' . esc_html__( 'We recommend a minimum MySQL version of 5.5.', 'blog-designer-pro' );
									} else {
										echo '<mark class="yes">' . esc_html( $mysql_version ) . '</mark>';
									}
									?>
								</span>
							</p>
							<?php
						endif;

						if ( function_exists( 'ini_get' ) ) :
							?>
							<p>
								<span class="bdp-staus-lable" data-export-label="PHP Post Max Size">
									<?php esc_html_e( 'PHP Post Max Size', 'blog-designer-pro' ); ?>:
								</span>
								<span class="bdp-status-value" >
									<?php echo esc_html( size_format( Bdp_Utility::let_to_num( ini_get( 'post_max_size' ) ) ) ); ?>
								</span>
							</p>
							<p>
								<span class="bdp-staus-lable" data-export-label="PHP Time Limit">
									<?php esc_html_e( 'PHP Time Limit', 'blog-designer-pro' ); ?>:
								</span>
								<span class="bdp-status-value" >
									<?php echo esc_html( ini_get( 'max_execution_time' ) ); ?>
								</span>
							</p>
							<p>
								<span class="bdp-staus-lable" data-export-label="PHP Max Input Vars">
									<?php esc_html_e( 'PHP Max Input Vars', 'blog-designer-pro' ); ?>:
								</span>
								<span class="bdp-status-value" >
									<?php echo esc_html( ini_get( 'max_input_vars' ) ); ?>
								</span>
							</p>
							<?php
						endif;
						?>
						<p>
							<span class="bdp-staus-lable" data-export-label="Max Upload Size">
								<?php esc_html_e( 'Max Upload Size', 'blog-designer-pro' ); ?>:
							</span>
							<span class="bdp-status-value" >
								<?php echo esc_html( size_format( wp_max_upload_size() ) ); ?>
							</span>
						</p>
					</div>
				</div>

				<?php $active_plugins_data = Bdp_Utility::get_active_plugins(); ?>
				<div class="bdp-status-cover" id="bdp_plugins_status">
					<div class="bdp-status-head" data-export-label="Active Plugins">
						<?php echo esc_html__( 'Active Plugins', 'blog-designer-pro' ) . ' (' . count( $active_plugins_data ) . ')'; ?>
					</div>
					<div class="bdp-status-contents">
						<?php
						foreach ( $active_plugins_data as $active_plugin ) {
							if ( ! empty( $active_plugin['name'] ) ) {
								$plugin_name = esc_html( $active_plugin['name'] );
								if ( ! empty( $active_plugin['url'] ) ) {
									$plugin_name = '<a href="' . esc_url( $active_plugin['url'] ) . '" aria-label="' . esc_attr__( 'Visit plugin homepage', 'blog-designer-pro' ) . '" target="_blank">' . $plugin_name . '</a>';
								}
								$author_name = $active_plugin['author_name'];
								if ( ! empty( $active_plugin['author_url'] ) ) {
									$author_name = '<a href="' . esc_url( $active_plugin['author_url'] ) . '" aria-label="' . esc_attr__( 'Visit plugin Author Page', 'blog-designer-pro' ) . '" target="_blank">' . $author_name . '</a>';
								}
								?>
								<p>
									<span class="bdp-staus-lable" data-export-label="<?php echo esc_attr( $active_plugin['name'] ); ?>">
										<?php echo wp_kses( $plugin_name, Bdp_Admin_Functions::args_kses() ); ?>
									</span>
									<span class="bdp-status-value" >
										<?php echo esc_html__( 'by', 'blog-designer-pro' ) . ' ' . wp_kses( $author_name, Bdp_Admin_Functions::args_kses() ) . ' - ' . esc_html( $active_plugin['version'] ); ?>
									</span>
								</p>
								<?php
							}
						}
						?>
					</div>
				</div>

				<?php $bdp_theme = Bdp_Utility::get_theme_info(); ?>
				<div class="bdp-status-cover" id="bdp_theme_status">
					<div class="bdp-status-head" data-export-label="Theme Info">
						<?php esc_html_e( 'Theme Info', 'blog-designer-pro' ); ?>
					</div>
					<div class="bdp-status-contents">
						<p>
							<span class="bdp-staus-lable" data-export-label="Name">
								<?php esc_html_e( 'Name', 'blog-designer-pro' ); ?>:
							</span>
							<span class="bdp-status-value" >
								<?php echo esc_html( $bdp_theme['name'] ); ?>
							</span>
						</p>
						<p>
							<span class="bdp-staus-lable" data-export-label="Version">
								<?php esc_html_e( 'Version', 'blog-designer-pro' ); ?>:
							</span>
							<span class="bdp-status-value" >
								<?php echo esc_html( $bdp_theme['version'] ); ?>
							</span>
						</p>
						<p>
							<span class="bdp-staus-lable" data-export-label="Author URL">
								<?php esc_html_e( 'Author URL', 'blog-designer-pro' ); ?>:
							</span>
							<span class="bdp-status-value" >
								<?php echo esc_url( $bdp_theme['author_url'] ); ?>
							</span>
						</p>
						<p>
							<span class="bdp-staus-lable" data-export-label="Child theme">
								<?php esc_html_e( 'Child theme', 'blog-designer-pro' ); ?>:
							</span>
							<span class="bdp-status-value" data-export-label="<?php echo ( $bdp_theme['is_child_theme'] ) ? 'YES' : 'NO'; ?>">
								<?php
								if ( $bdp_theme['is_child_theme'] ) {
									echo '<mark class="yes"><span class="dashicons dashicons-yes"></span></mark>';
								} else {
									echo '<mark class="error"> <span class="dashicons dashicons-no-alt"></span></mark> - ';
									echo esc_html__( 'If you are modifying Blog Designer PRO on a parent theme that you did not build personally we recommend using a child theme.', 'blog-designer-pro' ) . '<br/>';
									echo esc_html__( 'See', 'blog-designer-pro' ) . ':<a href="' . esc_url( 'https://developer.wordpress.org/themes/advanced-topics/child-themes/' ) . '" target="_blank"> ' . esc_html__( 'How to create a child theme', 'blog-designer-pro' ) . '</a>';
								}
								?>
							</span>
						</p>
						<?php if ( $bdp_theme['is_child_theme'] ) : ?>
							<p>
								<span class="bdp-staus-lable" data-export-label="Parent Theme Name">
									<?php esc_html_e( 'Parent Theme Name', 'blog-designer-pro' ); ?>:
								</span>
								<span class="bdp-status-value" >
									<?php echo esc_html( $bdp_theme['parent_name'] ); ?>
								</span>
							</p>
							<p>
								<span class="bdp-staus-lable" data-export-label="Parent Theme Version">
									<?php esc_html_e( 'Parent Theme Version', 'blog-designer-pro' ); ?>:
								</span>
								<span class="bdp-status-value" >
									<?php echo esc_html( $bdp_theme['parent_version'] ); ?>
								</span>
							</p>
							<p>
								<span class="bdp-staus-lable" data-export-label="Parent Theme Author URL">
									<?php esc_html_e( 'Parent Theme Author URL', 'blog-designer-pro' ); ?>:
								</span>
								<span class="bdp-status-value" >
									<?php echo esc_url( $bdp_theme['parent_author_url'] ); ?>
								</span>
							</p>
						<?php endif ?>
					</div>
				</div>

				<div class="bdp-status-cover" id="bdp_templates_status">
					<div class="bdp-status-head" data-export-label="Templates Status">
						<?php esc_html_e( 'Templates Status', 'blog-designer-pro' ); ?>
					</div>
					<div class="bdp-status-contents">
						<p>
							<span class="bdp-staus-lable" data-export-label="Blog Template">
								<?php esc_html_e( 'Blog Template', 'blog-designer-pro' ); ?>:
							</span>
							<span class="bdp-status-value" >
								<?php
								$bdp_core_template_dir     = BLOGDESIGNERPRO_DIR . 'bdp_templates/blog/';
								$bdp_override_template_dir = get_stylesheet_directory() . '/bdp_templates/blog/';
								if ( is_dir( $bdp_override_template_dir ) ) {
									$bdp_override_templates_layouts = scandir( $bdp_override_template_dir );
									if ( count( $bdp_override_templates_layouts ) > 2 ) {
										foreach ( $bdp_override_templates_layouts as $key => $value ) {
											if ( '.' !== $value && '..' !== $value ) {
												$bdp_core_template = $bdp_core_template_dir . $value;
												if ( ! file_exists( $bdp_core_template ) ) {
													$bdp_outdated = false;
													continue;
												}
												$core_version          = Bdp_Utility::check_file_version( $bdp_core_template_dir . $value );
												$bdp_override_template = $bdp_override_template_dir . $value;
												$templates_path        = str_replace( WP_CONTENT_DIR . '/themes/', '', $bdp_override_template );
												$template_version      = Bdp_Utility::check_file_version( $bdp_override_template );
												if ( $core_version > $template_version ) {
													$template_version = '<mark class="error">' . Bdp_Utility::check_file_version( $bdp_override_template ) . '</mark>';
													echo '~ <mark class="outdated">' . esc_html( $templates_path ) . '</mark> ' . esc_html__( 'version', 'blog-designer-pro' ) . ' ' . esc_html( $template_version ) . ' ' . esc_html__( 'is out of date. The core version is', 'blog-designer-pro' ) . ' ' . esc_html( $core_version ) . ' <br/>';
												} else {
													echo esc_html__( 'Theme patch apply sccessfully', 'blog-designer-pro' );
													echo '~ bdp_templates/' . esc_html( $value ) . '&nbsp;<span class="theme_patch_status_success">' . esc_html__( 'Theme patch applied successfully!', 'blog-designer-pro' ) . '</span>';
												}
											}
										}
									} else {
										echo esc_html__( 'No any theme patch found in your', 'blog-designer-pro' ) . ' ' . esc_html( $bdp_theme['name'] ) . ' ' . esc_html__( 'theme.', 'blog-designer-pro' );
									}
								} else {
									echo esc_html__( 'No any theme patch found in your', 'blog-designer-pro' ) . ' ' . esc_html( $bdp_theme['name'] ) . ' ' . esc_html__( 'theme.', 'blog-designer-pro' );
								}
								?>
							</span>
						</p>
						<p>
							<span class="bdp-staus-lable" data-export-label="Archive Template">
								<?php esc_html_e( 'Archive Template', 'blog-designer-pro' ); ?>:
							</span>
							<span class="bdp-status-value" >
								<?php
								$bdp_core_template_dir     = BLOGDESIGNERPRO_DIR . 'bdp_templates/archive/';
								$bdp_override_template_dir = get_stylesheet_directory() . '/bdp_templates/archive/';
								if ( is_dir( $bdp_override_template_dir ) ) {
									$bdp_override_templates_layouts = scandir( $bdp_override_template_dir );
									if ( count( $bdp_override_templates_layouts ) > 2 ) {
										foreach ( $bdp_override_templates_layouts as $key => $value ) {
											if ( '.' !== $value && '..' !== $value ) {
												$bdp_core_template = $bdp_core_template_dir . $value;
												if ( ! file_exists( $bdp_core_template ) ) {
													$bdp_outdated = false;
													continue;
												}
												$core_version          = Bdp_Utility::check_file_version( $bdp_core_template_dir . $value );
												$bdp_override_template = $bdp_override_template_dir . $value;
												$template_version      = Bdp_Utility::check_file_version( $bdp_override_template );
												$templates_path        = str_replace( WP_CONTENT_DIR . '/themes/', '', $bdp_override_template );
												if ( $core_version > $template_version ) {
													$template_version = '<mark class="error">' . Bdp_Utility::check_file_version( $bdp_override_template ) . '</mark>';
													echo '~ <mark class="outdated">' . esc_html( $templates_path ) . '</mark> ' . esc_html__( 'version', 'blog-designer-pro' ) . ' ' . esc_html( $template_version ) . ' ' . esc_html__( 'is out of date. The core version is', 'blog-designer-pro' ) . ' ' . esc_html( $core_version ) . ' <br/>';
												} else {
													echo '~ ' . esc_html( $templates_path ) . '&nbsp;<span class="theme_patch_status_success">' . esc_html__( 'Theme patch applied successfully!', 'blog-designer-pro' ) . '</span>';
												}
											}
										}
									} else {
										echo esc_html__( 'No any theme patch found in your', 'blog-designer-pro' ) . ' ' . esc_html( $bdp_theme['name'] ) . ' ' . esc_html__( 'theme.', 'blog-designer-pro' );
									}
								} else {
									echo esc_html__( 'No any theme patch found in your', 'blog-designer-pro' ) . ' ' . esc_html( $bdp_theme['name'] ) . ' ' . esc_html__( 'theme.', 'blog-designer-pro' );
								}
								?>
							</span>
						</p>
						<?php if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) { ?>
						<p>
							<span class="bdp-staus-lable" data-export-label="Archive Product Template">
								<?php esc_html_e( 'Archive Product Template', 'blog-designer-pro' ); ?>:
							</span>
							<span class="bdp-status-value" >
								<?php
								$bdp_core_template_dir     = BLOGDESIGNERPRO_DIR . 'bdp_templates/woocommerce/archive/';
								$bdp_override_template_dir = get_stylesheet_directory() . '/bdp_templates/woocommerce/archive/';
								if ( is_dir( $bdp_override_template_dir ) ) {
									$bdp_override_templates_layouts = scandir( $bdp_override_template_dir );
									if ( count( $bdp_override_templates_layouts ) > 2 ) {
										foreach ( $bdp_override_templates_layouts as $key => $value ) {
											if ( '.' !== $value && '..' !== $value ) {
												$bdp_core_template = $bdp_core_template_dir . $value;
												if ( ! file_exists( $bdp_core_template ) ) {
													$bdp_outdated = false;
													continue;
												}
												$core_version          = Bdp_Utility::check_file_version( $bdp_core_template_dir . $value );
												$bdp_override_template = $bdp_override_template_dir . $value;
												$template_version      = Bdp_Utility::check_file_version( $bdp_override_template );
												$templates_path        = str_replace( WP_CONTENT_DIR . '/themes/', '', $bdp_override_template );
												if ( $core_version > $template_version ) {
													$template_version = '<mark class="error">' . Bdp_Utility::check_file_version( $bdp_override_template ) . '</mark>';
													echo '~ <mark class="outdated">' . esc_html( $templates_path ) . '</mark> ' . esc_html__( 'version', 'blog-designer-pro' ) . ' ' . esc_html( $template_version ) . ' ' . esc_html__( 'is out of date. The core version is', 'blog-designer-pro' ) . ' ' . esc_html( $core_version ) . ' <br/>';
												} else {
													echo '~ ' . esc_html( $templates_path ) . '&nbsp;<span class="theme_patch_status_success">' . esc_html__( 'Theme patch applied successfully!', 'blog-designer-pro' ) . '</span>';
												}
											}
										}
									} else {
										echo esc_html__( 'No any theme patch found in your', 'blog-designer-pro' ) . ' ' . esc_html( $bdp_theme['name'] ) . ' ' . esc_html__( 'theme.', 'blog-designer-pro' );
									}
								} else {
									echo esc_html__( 'No any theme patch found in your', 'blog-designer-pro' ) . ' ' . esc_html( $bdp_theme['name'] ) . ' ' . esc_html__( 'theme.', 'blog-designer-pro' );
								}
								?>
							</span>
						</p>
						<p>
							<span class="bdp-staus-lable" data-export-label="Single Product Template">
								<?php esc_html_e( 'Single Product Template', 'blog-designer-pro' ); ?>:
							</span>
							<span class="bdp-status-value" >
								<?php
								$bdp_core_template_dir     = BLOGDESIGNERPRO_DIR . 'bdp_templates/woocommerce/single/';
								$bdp_override_template_dir = get_stylesheet_directory() . '/bdp_templates/woocommerce/single/';
								if ( is_dir( $bdp_override_template_dir ) ) {
									$bdp_override_templates_layouts = scandir( $bdp_override_template_dir );
									if ( count( $bdp_override_templates_layouts ) > 2 ) {
										foreach ( $bdp_override_templates_layouts as $key => $value ) {
											if ( '.' !== $value && '..' !== $value ) {
												$bdp_core_template = $bdp_core_template_dir . $value;
												if ( ! file_exists( $bdp_core_template ) ) {
													$bdp_outdated = false;
													continue;
												}
												$core_version          = Bdp_Utility::check_file_version( $bdp_core_template_dir . $value );
												$bdp_override_template = $bdp_override_template_dir . $value;
												$template_version      = Bdp_Utility::check_file_version( $bdp_override_template );
												$templates_path        = str_replace( WP_CONTENT_DIR . '/themes/', '', $bdp_override_template );
												if ( $core_version > $template_version ) {
													$template_version = '<mark class="error">' . Bdp_Utility::check_file_version( $bdp_override_template ) . '</mark>';
													echo '~ <mark class="outdated">' . esc_html( $templates_path ) . '</mark> ' . esc_html__( 'version', 'blog-designer-pro' ) . ' ' . esc_html( $template_version ) . ' ' . esc_html__( 'is out of date. The core version is', 'blog-designer-pro' ) . ' ' . esc_html( $core_version ) . ' <br/>';
												} else {
													echo '~ ' . esc_html( $templates_path ) . '&nbsp;<span class="theme_patch_status_success">' . esc_html__( 'Theme patch applied successfully!', 'blog-designer-pro' ) . '</span>';
												}
											}
										}
									} else {
										echo esc_html__( 'No any theme patch found in your', 'blog-designer-pro' ) . ' ' . esc_html( $bdp_theme['name'] ) . ' ' . esc_html__( 'theme.', 'blog-designer-pro' );
									}
								} else {
									echo esc_html__( 'No any theme patch found in your', 'blog-designer-pro' ) . ' ' . esc_html( $bdp_theme['name'] ) . ' ' . esc_html__( 'theme.', 'blog-designer-pro' );
								}
								?>
							</span>
						</p>
						<?php } ?>
						<p>
							<span class="bdp-staus-lable" data-export-label="Single Template">
								<?php esc_html_e( 'Single Template', 'blog-designer-pro' ); ?>:
							</span>
							<span class="bdp-status-value" >
								<?php
								$bdp_core_template_dir     = BLOGDESIGNERPRO_DIR . 'bdp_templates/single/';
								$bdp_override_template_dir = get_stylesheet_directory() . '/bdp_templates/single/';
								if ( is_dir( $bdp_override_template_dir ) ) {
									$bdp_override_templates_layouts = scandir( $bdp_override_template_dir );
									if ( count( $bdp_override_templates_layouts ) > 2 ) {
										foreach ( $bdp_override_templates_layouts as $key => $value ) {
											if ( '.' !== $value && '..' !== $value ) {
												$bdp_core_template = $bdp_core_template_dir . $value;
												if ( ! file_exists( $bdp_core_template ) ) {
													$bdp_outdated = false;
													continue;
												}
												$core_version          = Bdp_Utility::check_file_version( $bdp_core_template_dir . $value );
												$bdp_override_template = $bdp_override_template_dir . $value;
												$template_version      = Bdp_Utility::check_file_version( $bdp_override_template );
												$templates_path        = str_replace( WP_CONTENT_DIR . '/themes/', '', $bdp_override_template );
												if ( $core_version > $template_version ) {
													$template_version = '<mark class="error">' . Bdp_Utility::check_file_version( $bdp_override_template ) . '</mark>';
													echo '~ <mark class="outdated">' . esc_html( $templates_path ) . '</mark> ' . esc_html__( 'version', 'blog-designer-pro' ) . ' ' . esc_html( $template_version ) . ' ' . esc_html__( 'is out of date. The core version is', 'blog-designer-pro' ) . ' ' . esc_html( $core_version ) . ' <br/>';
												} else {
													echo '~ ' . esc_html( $templates_path ) . '&nbsp;<span class="theme_patch_status_success">' . esc_html__( 'Theme patch applied successfully!', 'blog-designer-pro' ) . '</span>';
												}
											}
										}
									} else {
										echo esc_html__( 'No any theme patch found in your', 'blog-designer-pro' ) . ' ' . esc_html( $bdp_theme['name'] ) . ' ' . esc_html__( 'theme.', 'blog-designer-pro' );
									}
								} else {
									echo esc_html__( 'No any theme patch found in your', 'blog-designer-pro' ) . ' ' . esc_html( $bdp_theme['name'] ) . ' ' . esc_html__( 'theme.', 'blog-designer-pro' );
								}
								?>
							</span>
						</p>
					</div>
				</div>
			</div>
			<?php endif; ?>

		</div>
	</div>
	<?php if ( 'register_product' === $active_tab ) : ?>
		<div class="bdp-updatestory">
			<div class="bdp-info-heading bdp-panel-header">
				<h3><span class="dashicons dashicons-image-rotate"> </span> <?php esc_html_e( 'Update History', 'blog-designer-pro' ); ?> </h3>
			</div>
			<div class="bdp-panel-body">
				<div class="changelog-cover">
					<?php echo wp_kses( $bdp_information->sections['changelog'], Bdp_Admin_Functions::args_kses() ); ?>
				</div>
			</div>
		</div>
	<?php endif; ?>
</div>

<style type="text/css">
	
</style>

<div class="wrap">
	<?php 
	/**
	 * saved notification
	 */
	if(isset($_REQUEST['settings-updated']) && $_REQUEST['settings-updated']==true) {
		echo "<div id='message' class='updated'><p><strong>Settings saved</strong></p></div>";
	} 
	?>

	<h2><?php _e("Plugin Resi",PLUGIN_RESI)  ?></h2>

	<div style="margin: 8px 0 10px;">
		<?php _e("Created by",PLUGIN_RESI) ?> 
		<a href='https://tonjoostudio.com' target="_blank">Tonjoo Studio</a> ~
		<a href='https://forum.tonjoostudio.com/thread-category/plugin-resi/' target="_blank"><?php _e("Forum",PLUGIN_RESI) ?></a> |
		<a href='https://tonjoostudio.com/addons/plugin-resi/#faq' target="_blank"><?php _e("FAQ",PLUGIN_RESI) ?></a> 
	</div>	

	<?php require_once( plugin_dir_path( __FILE__ ) . 'plugin-default-option.php'); ?>

	<div class="metabox-holder columns-2" style="margin-right: 300px;">

		<form method="post" action="options.php" id="main-plugin-form"> 
			<?php settings_fields('plugin_resi_options'); ?>

			<!-- GENERAL OPTIONS -->
			<div id='opt-general' class="postbox-container group" style="width: 100%;min-width: 463px;float: left; ">
				<div class="meta-box-sortables ui-sortable">
					<div id="adminform" class="postbox">
						<h3 class="hndle"><span><?php _e("Additional Couriers",PLUGIN_RESI) ?></span></h3>
						<div class="inside" style="z-index:1;">
							<table class="form-table">
								<?php require_once( plugin_dir_path( __FILE__ ) . 'plugin-option-page-general.php'); ?>
							</table>
						</div>
					</div>
				</div>
			</div>

			<!-- SIDEBAR -->
			<div class="postbox-container" style="float: right;margin-right: -300px;width: 280px;">
				<div class="metabox-holder" style="padding-top:0px;">	
					<div class="meta-box-sortables ui-sortable">
						<div id="email-signup" class="postbox">
							<h3 class="hndle"><span><?php _e('Save Options',PLUGIN_RESI) ?></span></h3>
							<div class="inside" style="padding-top:10px;">
								<?php _e('Save your changes to apply the options',PLUGIN_RESI) ?>
								<br>
								<br>
								<input type="submit" class="button-primary" value="<?php _e('Save Options',PLUGIN_RESI) ?>" />

							</div>
						</div>
					</div>
				</div>
			</div>
			
		</form>

	</div> <!-- metabox-holder -->
</div>
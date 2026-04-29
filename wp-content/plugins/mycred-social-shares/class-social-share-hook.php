<?php
add_filter('mycred_setup_hooks', 'register_social_share_hook', 10);

function register_social_share_hook($installed) {
    $installed['social_share'] = array(
        'title' => __('Points for Social Share', 'mycred_social_share'),
        'description' => __('Points for Social Share', 'mycred_social_share'),
        'callback' => array('myCRED_Hook_Social_Share')
    );
	 $installed['social_follow'] = array(
        'title' => __('Points for Social Follow', 'mycred_social_share'),
        'description' => __('Points for Social follow', 'mycred_social_share'),
        'callback' => array('myCRED_Hook_Social_follow')
    );
    return $installed;
}



if (!class_exists('myCRED_Hook_Social_follow') && class_exists('myCRED_Hook')) {

    class myCRED_Hook_Social_follow extends myCRED_Hook {
		
        /**
         * Construct
         */
        function __construct($hook_prefs, $type) {
			
			
            $defaults = array(
                'twitter' => array(
                    'creds' => 1,
                    'log' => '%plural% for twitter follow',
                ),
				'facebook' => array(
                    'creds' => 1,
                    'log' => '%plural% for facebook follow',
                ),
				'youtube' => array(
                    'creds' => 1,
                    'log' => '%plural% for youtube follow',
                ),
				'twitch' => array(
                    'creds' => 1,
                    'log' => '%plural% for twitch follow',
                ),
                'instagram' => array(
                    'creds' => 1,
                    'log' => '%plural% for instagram follow',
                ),
                'limit_by' => 'none',
            );
			 

			parent::__construct(
			array('id' => 'social_follow',
			'defaults' => $defaults
			), $hook_prefs, $type);
			 
			 
        }
		
		
		 /**
         * Add Settings
         */
        public function preferences() {
            $prefs = $this->prefs;
			//var_dump($prefs);
            ?>
          <div class="hook-instance">
                <h3><?php _e('Twitter', 'mycred'); ?></h3>
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="<?php echo $this->field_id(array('twitter' => 'creds')); ?>"><?php echo $this->core->plural(); ?></label>
                            <input type="text" name="<?php echo $this->field_name(array('twitter' => 'creds')); ?>" id="<?php echo $this->field_id(array('twitter' => 'creds')); ?>" value="<?php echo $this->core->number($prefs['twitter']['creds']); ?>" class="form-control" />
                        </div>
                    </div>
                    <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="<?php echo $this->field_id(array('twitter' => 'log')); ?>"><?php _e('Log Template', 'mycred'); ?></label>
                            <input type="text" name="<?php echo $this->field_name(array('twitter' => 'log')); ?>" id="<?php echo $this->field_id(array('twitter' => 'log')); ?>" placeholder="<?php _e('required', 'mycred'); ?>" value="<?php echo esc_attr($prefs['twitter']['log']); ?>" class="form-control" />
                            <span class="description"><?php echo $this->available_template_tags(array('general', 'user')); ?></span>
                        </div>
                    </div>
                </div>
            </div>
			<div class="hook-instance">
                <h3><?php _e('Facebook', 'mycred'); ?></h3>
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="<?php echo $this->field_id(array('facebook' => 'creds')); ?>"><?php echo $this->core->plural(); ?></label>
                            <input type="text" name="<?php echo $this->field_name(array('facebook' => 'creds')); ?>" id="<?php echo $this->field_id(array('facebook' => 'creds')); ?>" value="<?php echo $this->core->number($prefs['facebook']['creds']); ?>" class="form-control" />
                        </div>
                    </div>
                    <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="<?php echo $this->field_id(array('facebook' => 'log')); ?>"><?php _e('Log Template', 'mycred'); ?></label>
                            <input type="text" name="<?php echo $this->field_name(array('facebook' => 'log')); ?>" id="<?php echo $this->field_id(array('facebook' => 'log')); ?>" placeholder="<?php _e('required', 'mycred'); ?>" value="<?php echo esc_attr($prefs['facebook']['log']); ?>" class="form-control" />
                            <span class="description"><?php echo $this->available_template_tags(array('general', 'user')); ?></span>
                        </div>
                    </div>
                </div>
            </div>
			<div class="hook-instance">
                <h3><?php _e('Youtube', 'mycred'); ?></h3>
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="<?php echo $this->field_id(array('youtube' => 'creds')); ?>"><?php echo $this->core->plural(); ?></label>
                            <input type="text" name="<?php echo $this->field_name(array('youtube' => 'creds')); ?>" id="<?php echo $this->field_id(array('youtube' => 'creds')); ?>" value="<?php echo $this->core->number($prefs['youtube']['creds']); ?>" class="form-control" />
                        </div>
                    </div>
                    <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="<?php echo $this->field_id(array('youtube' => 'log')); ?>"><?php _e('Log Template', 'mycred'); ?></label>
                            <input type="text" name="<?php echo $this->field_name(array('youtube' => 'log')); ?>" id="<?php echo $this->field_id(array('youtube' => 'log')); ?>" placeholder="<?php _e('required', 'mycred'); ?>" value="<?php echo esc_attr($prefs['youtube']['log']); ?>" class="form-control" />
                            <span class="description"><?php echo $this->available_template_tags(array('general', 'user')); ?></span>
                        </div>
                    </div>
                </div>
            </div>
			<div class="hook-instance">
                <h3><?php _e('Twitch', 'mycred'); ?></h3>
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="<?php echo $this->field_id(array('twitch' => 'creds')); ?>"><?php echo $this->core->plural(); ?></label>
                            <input type="text" name="<?php echo $this->field_name(array('twitch' => 'creds')); ?>" id="<?php echo $this->field_id(array('twitch' => 'creds')); ?>" value="<?php echo $this->core->number($prefs['twitch']['creds']); ?>" class="form-control" />
                        </div>
                    </div>
                    <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="<?php echo $this->field_id(array('twitch' => 'log')); ?>"><?php _e('Log Template', 'mycred'); ?></label>
                            <input type="text" name="<?php echo $this->field_name(array('twitch' => 'log')); ?>" id="<?php echo $this->field_id(array('twitch' => 'log')); ?>" placeholder="<?php _e('required', 'mycred'); ?>" value="<?php echo esc_attr($prefs['twitch']['log']); ?>" class="form-control" />
                            <span class="description"><?php echo $this->available_template_tags(array('general', 'user')); ?></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="hook-instance">
                <h3><?php _e('Instagram', 'mycred'); ?></h3>
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="<?php echo $this->field_id(array('instagram' => 'creds')); ?>"><?php echo $this->core->plural(); ?></label>
                            <input type="text" name="<?php echo $this->field_name(array('instagram' => 'creds')); ?>" id="<?php echo $this->field_id(array('instagram' => 'creds')); ?>" value="<?php echo $this->core->number($prefs['instagram']['creds']); ?>" class="form-control" />
                        </div>
                    </div>
                    <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="<?php echo $this->field_id(array('instagram' => 'log')); ?>"><?php _e('Log Template', 'mycred'); ?></label>
                            <input type="text" name="<?php echo $this->field_name(array('instagram' => 'log')); ?>" id="<?php echo $this->field_id(array('instagram' => 'log')); ?>" placeholder="<?php _e('required', 'mycred'); ?>" value="<?php echo esc_attr($prefs['instagram']['log']); ?>" class="form-control" />
                            <span class="description"><?php echo $this->available_template_tags(array('general', 'user')); ?></span>
                        </div>
                    </div>
                </div>
            </div>
			<?php
        }
	public function run() {
	   
	if (!is_user_logged_in()) {
	return;
	}
	add_action('mycred_register_assets', array($this, 'register_script'));
	add_action('mycred_front_enqueue_footer', array($this, 'enqueue_footer'));
	
	add_action( 'wp_ajax_mycred-socialfollow-link-points',array($this, 'ajax_call_social_follow_link_points') );
	add_action( 'wp_ajax_nopriv_mycred-socialfollow-link-points',array($this, 'ajax_call_social_follow_link_points')  );

	// if (isset($_POST['action']) && $_POST['action'] == 'mycred-socialfollow-link-points' && isset($_POST['token']) && wp_verify_nonce($_POST['token'], 'mycred-socialfollow-link-points')) {
	
	// $this->ajax_call_social_follow_link_points();
		// // wp_die();
	// }
	}
		
	public function ajax_call_social_follow_link_points() {
		
			// We must be logged in
            if (!is_user_logged_in())
                return;
            // Security
            check_ajax_referer('mycred-socialfollow-link-points', 'token');

            $user_id = get_current_user_id();
            // Current User
            $amount = $this->prefs[$_POST['socialtype']]['creds'];
            if ($amount == 0 || $amount == $this->core->zero()) {
                return false;
            }

            $data = array(
                'ref_type' => 'social_link',
                'link_url' => esc_url_raw($_POST['url']),
                'socialtype' => $_POST['socialtype'],
            );
			// Limits
            // if ($this->prefs['limit_by'] == 'url') {
                // if ($this->has_clicked($user_id, 'link_url', $data['link_url'])) {
                    // return false;
                // }
            // }
			
 
			$args = array('ref'  => 'social_follow','user_id' => $user_id);
			$rows = new myCRED_Query_Log( $args );
			foreach ( $rows->results as $row ) {
			  $socialtype = maybe_unserialize($row->data)['socialtype'];
			  if($_POST["socialtype"] == $socialtype)
				{
				return false;	
				}
			
			}
			$this->core->add_creds(
                    'social_follow', $user_id, $amount, $this->prefs[$_POST['socialtype']]['log'], '', $data, $this->mycred_type
            );
			wp_die();
        }
	public function enqueue_footer() {
            global $post;

            
			wp_localize_script(
				'mycred-socialfollow-link-points', 'myCRED_follow_social', array(
				'ajaxurl' =>  admin_url( 'admin-ajax.php' ),
				'token' => wp_create_nonce('mycred-socialfollow-link-points'),
                    )
            );
            wp_enqueue_script('mycred-socialfollow-link-points');
            wp_enqueue_script('mycred-socialshare-hide');
        }
		 public function register_script() {

            global $mycred_link_points;

            $mycred_link_points = false;

            wp_register_script(
                    'mycred-socialfollow-link-points','', array('jquery'), '', true
            );
             
        }
	 public function has_clicked($user_id = NULL, $by = '', $check = '') {

            global $wpdb;

            $rows = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$this->core->log_table} WHERE ref = %s AND user_id = %d AND ctype = %s", 'social_share', $user_id, $this->mycred_type));

            if (count($rows) == 0)
                return false;

            $reply = false;
            foreach ($rows as $row) {

                $data = maybe_unserialize($row->data);
                if (!is_array($data) || !isset($data[$by]))
                    continue;

                if ($data[$by] == $check) {
                    $reply = true;
                    break;
                }
            }
            return $reply;
        }

        public function custom_limit() {
            return array(
                'none' => __('No limit', 'mycred_social_share'),
                'url' => __('Once for each unique URL', 'mycred_social_share'),
            );
        }	
		
		
	}
	}



if (!class_exists('myCRED_Hook_Social_Share') && class_exists('myCRED_Hook')) {

    class myCRED_Hook_Social_Share extends myCRED_Hook {

        /**
         * Construct
         */
        function __construct($hook_prefs, $type) {
		
            $defaults = array(
                'facebook' => array(
                    'creds' => 1,
                    'log' => '%plural% for facebook sharing',
                ),
                'twitter' => array(
                    'creds' => 1,
                    'log' => '%plural% for twitter sharing',
                ),
				'pinterest' => array(
                    'creds' => 1,
                    'log' => '%plural% for pinterest sharing'
                ),
                'linkedin' => array(
                    'creds' => 1,
                    'log' => '%plural% for linkedin sharing'
                ),
				
                'limit_by' => 'none',
            );
			parent::__construct(
			array
			(
			'id' => 'social_share',
			'defaults' => $defaults
			)
			, $hook_prefs, $type);
			 
			 
        }

        /**
         * Hook into WordPress
         */
        public function run() {
			
			
            // if (!is_user_logged_in()) {
                // return;
            // }
			 
			$social_frontend = new MyCred_Social_Shares_Frontend();
		 	$social_frontend->addButtonsInContent();
			
			add_action('mycred_register_assets', array($this, 'register_script'));
			add_action('mycred_front_enqueue_footer', array($this, 'enqueue_footer'));
			add_action('wp_ajax_mycred-socialshare-link-points',array($this, 'ajax_call_sociallink_points') );
			add_action('wp_ajax_nopriv_mycred-socialshare-link-points',array($this, 'ajax_call_sociallink_points')  );
 
        }

        public function register_script() {

            global $mycred_link_points;

            $mycred_link_points = false;

            wp_register_script(
                    'mycred-socialshare-link-points', plugin_dir_url(__FILE__) . 'assets/js/share-links.js', array('jquery'), '', true
            );
            wp_register_script(
                    'mycred-socialshare-hide', plugin_dir_url(__FILE__) . 'assets/js/more-button.js', array('jquery'), '', true
            );
        }

        public function enqueue_footer() {
            global $post;
 
            wp_localize_script(
                    'mycred-socialshare-link-points', 'myCREDsocial', array(
                'ajaxurl' => admin_url( 'admin-ajax.php' ),
                'token' => wp_create_nonce('mycred-socialshare-link-points'),
                    )
            );
			 
            wp_enqueue_script('mycred-socialshare-link-points');
            wp_enqueue_script('mycred-socialshare-hide');
        }

        public function ajax_call_sociallink_points() {
            // We must be logged in
			
			 if (!is_user_logged_in())
                return;
            // Security
            check_ajax_referer('mycred-socialshare-link-points', 'token');
		
            $user_id = get_current_user_id();
            // Current User
            $amount = $this->prefs[$_POST['socialtype']]['creds'];
            if ($amount == 0 || $amount == $this->core->zero()) {
                return false;
            }
				 
            $data = array(
                'ref_type' => 'social_link',
                'link_url' => esc_url_raw($_POST['url']),
            );
           // Limits
            if ($this->prefs['limit_by'] == 'url') {
                if ($this->has_clicked($user_id, 'link_url', $data['link_url'])) {
                    return false;
                }
            }
			 
			$this->core->add_creds(
                      'social_share', $user_id, $amount, $this->prefs[$_POST['socialtype']]['log'], '', $data, $this->mycred_type
              );
			  
		die();	  
        }

        /**
         * Add Settings
         */
        public function preferences() {
            $prefs = $this->prefs;
			?><div class="hook-instance">
                <h3><?php _e('Twitter', 'mycred'); ?></h3>
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="<?php echo $this->field_id(array('twitter' => 'creds')); ?>"><?php echo $this->core->plural(); ?></label>
                            <input type="text" name="<?php echo $this->field_name(array('twitter' => 'creds')); ?>" id="<?php echo $this->field_id(array('twitter' => 'creds')); ?>" value="<?php echo $this->core->number($prefs['twitter']['creds']); ?>" class="form-control" />
                        </div>
                    </div>
                    <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="<?php echo $this->field_id(array('twitter' => 'log')); ?>"><?php _e('Log Template', 'mycred'); ?></label>
                            <input type="text" name="<?php echo $this->field_name(array('twitter' => 'log')); ?>" id="<?php echo $this->field_id(array('twitter' => 'log')); ?>" placeholder="<?php _e('required', 'mycred'); ?>" value="<?php echo esc_attr($prefs['twitter']['log']); ?>" class="form-control" />
                            <span class="description"><?php echo $this->available_template_tags(array('general', 'user')); ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="hook-instance">
                <h3><?php _e('Facebook', 'mycred'); ?></h3>
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="<?php echo $this->field_id(array('facebook' => 'creds')); ?>"><?php echo $this->core->plural(); ?></label>
                            <input type="text" name="<?php echo $this->field_name(array('facebook' => 'creds')); ?>" id="<?php echo $this->field_id(array('facebook' => 'creds')); ?>" value="<?php echo $this->core->number($prefs['facebook']['creds']); ?>" class="form-control" />
                        </div>
                    </div>
                    <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="<?php echo $this->field_id(array('facebook' => 'log')); ?>"><?php _e('Log Template', 'mycred'); ?></label>
                            <input type="text" name="<?php echo $this->field_name(array('facebook' => 'log')); ?>" id="<?php echo $this->field_id(array('facebook' => 'log')); ?>" placeholder="<?php _e('required', 'mycred'); ?>" value="<?php echo esc_attr($prefs['facebook']['log']); ?>" class="form-control" />
                            <span class="description"><?php echo $this->available_template_tags(array('general', 'user')); ?></span>
                        </div>
                    </div>
                </div>
            </div>
			<div class="hook-instance">
                <h3><?php _e('Linkedin', 'mycred'); ?></h3>
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="<?php echo $this->field_id(array('linkedin' => 'creds')); ?>"><?php echo $this->core->plural(); ?></label>
                            <input type="text" name="<?php echo $this->field_name(array('linkedin' => 'creds')); ?>" id="<?php echo $this->field_id(array('linkedin' => 'creds')); ?>" value="<?php echo $this->core->number($prefs['linkedin']['creds']); ?>" class="form-control" />
                        </div>
                    </div>
                    <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="<?php echo $this->field_id(array('linkedin' => 'log')); ?>"><?php _e('Log Template', 'mycred'); ?></label>
                            <input type="text" name="<?php echo $this->field_name(array('linkedin' => 'log')); ?>" id="<?php echo $this->field_id(array('linkedin' => 'log')); ?>" placeholder="<?php _e('required', 'mycred'); ?>" value="<?php echo esc_attr($prefs['linkedin']['log']); ?>" class="form-control" />
                            <span class="description"><?php echo $this->available_template_tags(array('general', 'user')); ?></span>
                        </div>
                    </div>
                </div>
            </div>


            <div class="hook-instance">
                <h3><?php _e('Pinterest', 'mycred'); ?></h3>
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="<?php echo $this->field_id(array('pinterest' => 'creds')); ?>"><?php echo $this->core->plural(); ?></label>
                            <input type="text" name="<?php echo $this->field_name(array('pinterest' => 'creds')); ?>" id="<?php echo $this->field_id(array('pinterest' => 'creds')); ?>" value="<?php echo $this->core->number($prefs['pinterest']['creds']); ?>" class="form-control" />
                        </div>
                    </div>
                    <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="<?php echo $this->field_id(array('pinterest' => 'log')); ?>"><?php _e('Log Template', 'mycred'); ?></label>
                            <input type="text" name="<?php echo $this->field_name(array('pinterest' => 'log')); ?>" id="<?php echo $this->field_id(array('pinterest' => 'log')); ?>" placeholder="<?php _e('required', 'mycred'); ?>" value="<?php echo esc_attr($prefs['pinterest']['log']); ?>" class="form-control" />
                            <span class="description"><?php echo $this->available_template_tags(array('general', 'user')); ?></span>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label for=""><?php _e('Limits', 'mycred'); ?></label>
                        <?php
                        add_filter('mycred_hook_impose_limits', array($this, 'custom_limit'));
                        $this->impose_limits_dropdown('limit_by', false);
                        ?>
                    </div>
                </div>

            </div>


            <?php
        }

        /**
         * Has Clicked
         * Checks if a user has received points for a link based on either
         * an ID or URL.
         * @since 1.3.3.1
         * @version 1.0
         */
        public function has_clicked($user_id = NULL, $by = '', $check = '') {

            global $wpdb;

            $rows = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$this->core->log_table} WHERE ref = %s AND user_id = %d AND ctype = %s", 'social_share', $user_id, $this->mycred_type));

            if (count($rows) == 0)
                return false;

            $reply = false;
            foreach ($rows as $row) {

                $data = maybe_unserialize($row->data);
                if (!is_array($data) || !isset($data[$by]))
                    continue;

                if ($data[$by] == $check) {
                    $reply = true;
                    break;
                }
            }
            return $reply;
        }

        public function custom_limit() {
            return array(
                'none' => __('No limit', 'mycred_social_share'),
                'url' => __('Once for each unique URL', 'mycred_social_share'),
            );
        }

    }

}

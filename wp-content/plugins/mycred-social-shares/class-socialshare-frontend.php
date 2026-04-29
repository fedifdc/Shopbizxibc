<?php 
class MyCred_Social_Shares_Frontend {
    public $fields_social_order = array();
    public $socialshare = array();
    public function __construct() { 
        $this->fields_social_order = array(
            0 => array(
                'id' => '0',
                'name' => 'facebook',
                'slug' => 'facebook_share',
            ),
            1 => array(
                'id' => '1',
                'name' => 'twitter',
                'slug' => 'twitter_share',
            ),
            2 => array(
                'id' => '2',
                'name' => 'pinterest',
                'slug' => 'pinterest_share',
            ),
            3 => array(
                'id' => '3',
                'name' => 'linkedin',
                'slug' => 'linkedin_share',
            )
        );
        $this->socialshare = array(
            'facebook_share' => array(
                'class' => 'mycred-social-icons mycred-social-icon-facebook',
                'text_class' => 'facebook social-text'
            ),
            'twitter_share' => array(
                'class' => 'mycred-social-icons mycred-social-icon-twitter',
                'text_class' => 'twitter social-text'
            ),
            'pinterest_share' => array(
                'class' => 'mycred-social-icons mycred-social-icon-pinterest',
                'text_class' => 'pinterest social-text'
            ),
            'linkedin_share' => array(
                'class' => 'mycred-social-icons mycred-social-icon-linkedin',
                'text_class' => 'linkedin social-text'
            )
        );
    }
    public function addButtonsInContent() {
		
        add_filter('the_content', array($this, 'mycred_social_sharing_buttons'));
    }
    public function mycred_social_sharing_buttons($content) {
		
		$output = '';
		
		if (!is_user_logged_in() && ('none'!=get_option('social_position')) ) {
			
			if ( 'yes' == get_option('none_login_user_show_icons') ){
				 
				ob_start(); //Start output buffer
				$this->social_share_html();
				$output = ob_get_contents(); //Grab output
				ob_end_clean(); //Discard output buffer
			}
			
            if ( 'yes' == get_option('txtCheckNotification') ) {
                $output .= "<div class='mycred_socialshare_notice muf'>" . __( 'If you want to share and earn points please', 'mycred_social_share' ) . " <a href='".wp_login_url( get_permalink())."'> " . __('login', 'mycred_social_share') . " </a> " . __('first', 'mycred_social_share') . "</div>";
				return social_position($content,$output);
			} else {
				
				return social_position($content,$output);
			}
		}
        global $post;
        $social_post_type = get_option('show_post_type');
		$posts_ids = get_option('social_share_exclude_settings');
        if ( $social_post_type != "" && in_array( $post->post_type, $social_post_type ) ) {
			if ( $posts_ids != "" && in_array( $post->ID, $posts_ids ) ) {
				return $content;
			}
		if ( class_exists( 'WooCommerce' ) ) {
			if(is_cart() || is_account_page() || is_checkout() || is_wc_endpoint_url()
				|| is_wc_endpoint_url( 'order-pay' ) || is_wc_endpoint_url( 'order-received' )
				||	is_wc_endpoint_url( 'view-order' ) || is_wc_endpoint_url( 'edit-account' )
				|| is_wc_endpoint_url( 'edit-address' ) || is_wc_endpoint_url( 'lost-password' )
				|| is_wc_endpoint_url( 'customer-logout' ) || is_wc_endpoint_url( 'add-payment-method' ))
			{
				return $content;
			}
			}
		 
			switch (get_option('social_position')) {
                case 'after' :
                    ob_start(); //Start output buffer
                    echo $content;
                    $this->social_share_html();
                    $output = ob_get_contents(); //Grab output
                    ob_end_clean(); //Discard output buffer
                    break;
                case 'before' :
                    ob_start(); //Start output buffer
                    $this->social_share_html();
                    echo $content;
                    $output = ob_get_contents(); //Grab output
                    ob_end_clean(); //Discard output buffer
                    break;
                case 'both' :
                    ob_start(); //Start output buffer
                    $this->social_share_html();
                    echo $content;
                    $this->social_share_html();
                    $output = ob_get_contents(); //Grab output
                    ob_end_clean(); //Discard output buffer
                    break;
                default :

                    $output = $content;
                    break;
            }
            return $output;
        }
        return $content;
    }
    public function social_share_html() {
	 
	echo '<div id="mycred-social-wrapper" class="social-wrapper mycred-social-wrapper mycred_socialshare_box">';
?>
<script>

	window.mycred_fb_sdk_loaded = false;

	function mycred_social_share_add_points( socialtype ) {

		jQuery.ajax({
	        type: "POST",
	        data: {
	            action: 'mycred-socialshare-link-points',
				url: "<?php echo get_permalink();?>",
	            token: myCREDsocial.token,
	            socialtype: socialtype
	        },
	        dataType: "JSON",
	        url: myCREDsocial.ajaxurl,
	        	success: function (response) {
	        }
	    });
	
	}

	function mycred_facebook_share( socialtype ) {

		<?php if ( ! is_user_logged_in() ) echo 'alert( "If you want to share and earn points please login first." ); return;';?>

		if ( ! window.mycred_fb_sdk_loaded ) 
			mycred_init_fb_sdk();

		//check user session and refresh it
		FB.getLoginStatus( function (response) {

			if ( response.status === 'connected' ) {
				
				//user is authorized
				FB.ui({
					method: 'share',
					display: 'popup',
					href: '<?php echo get_the_permalink(); ?>',
				},
				// callback
				function( response ) {

					if ( response && ! response.error_message )
						mycred_social_share_add_points( socialtype );
					else 
						alert( 'Error while posting.' );

				});

			} 
			else {
				
				//user is unauthorized
				FB.login( function ( response ) {

					if ( response.status === 'connected' ) {

						FB.ui({
							method: 'share',
							display: 'popup',
							href: '<?php echo get_the_permalink(); ?>',
						},
						// callback
						function(response) {

							if ( response && ! response.error_message )
								alert('Posting completed.');
							else
								alert('Error while posting.');
							
						});
					}

				},
				{
					scope: 'email,public_profile',
					return_scopes: true
				});

			}
		});

	}

	window.fbAsyncInit = function () {

		mycred_init_fb_sdk();
	
	};

	function mycred_init_fb_sdk() {
		
		window.mycred_fb_sdk_loaded = true;

		//SDK loaded, initialize it
		FB.init({
			appId   : '<?php echo get_option( 'facebook_app_id' ); ?>',
			xfbml   : true,
			version : 'v2.2'
		});
		//check user session and refresh it

	}

	//load the JavaScript SDK
	(function(d, s, id) {
	  	var js, fjs = d.getElementsByTagName(s)[0];
	  	if (d.getElementById(id)) return;
	  	js = d.createElement(s); js.id = id;
	  	js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.0";
	  	fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));

	function mycred_open_window(href_link,socialtype) { 
		
		<?php if ( ! is_user_logged_in() ) echo 'alert( "If you want to share and earn points please login first." ); return;';?>

		popup = window.open( href_link, "socialtype", "width=600,height=600" );

	 	var popupTick = setInterval(function() {
      	
      		if (popup.closed) {
        		clearInterval(popupTick);
        		console.log('window closed!');
				mycred_social_share_add_points(socialtype);
      		}

    	}, 500);

	    return false;
	
	}
</script>

<?php
		$imageurlshar = get_the_post_thumbnail_url(get_the_ID(),'full');
		$httpcode = $this->curl_facebook_status_app_id();
		if($httpcode==200){
		$facebookURL = "javascript:void(0)";
		}else{
		$facebookURL = "http://www.facebook.com/sharer.php?u=" . get_the_permalink() . "&amp;p[images[0]=".$imageurlshar."";
       	}
		$pinterestURL = "https://pinterest.com/pin/create/bookmarklet/?media=" .$imageurlshar. "&amp;url=" . get_the_permalink() . "";
		$twitter = "https://twitter.com/share?url=" . get_the_permalink() . "&text=" . get_the_title() . "";
		$linkedin = "http://www.linkedin.com/shareArticle?url=" . get_the_permalink() . "&title=" . get_the_title() . "";
		$this->socialshare['facebook_share']['url'] = $facebookURL;
		$this->socialshare['twitter_share']['url'] = $twitter;
		$this->socialshare['pinterest_share']['url'] = $pinterestURL;
		$this->socialshare['linkedin_share']['url'] = $linkedin;
        $filter_fields_order = get_option('social_fields_sort', $this->fields_social_order);
        $display_all = get_option('display_all');
        $num_show = false;
        if (!$display_all) {
            $num_show = get_option('no_show_button');
        }
		$count = 0;
		$selected = get_option('social_fields_check');
		?><style><?php
		 foreach ($filter_fields_order as $k => $value) {
		if (isset($value['name'])) {
			$name = $value['name'];
		}
		if (isset($value['id'])) {
			$id = $value['id'];
		}
		if ($name == "facebook")
		{
			?>
			.mycred_socialshare_box  a.mycred-social-icon-facebook.i-text-admin:hover:after {
			content: "<?php echo  $this->mycred_follow_buttton_text_check($id,$name);?>";
			}
			<?php
		}
		elseif ($name == "twitter")
		{
			?>
			.mycred_socialshare_box a.mycred-social-icon-twitter.i-text-admin:hover:after {
			content: "<?php echo  $this->mycred_follow_buttton_text_check($id,$name);?>";
			}
			<?php
		}
		elseif ($name == "linkedin")
		{
			?>
			.mycred_socialshare_box a.mycred-social-icon-linkedin.i-text-admin:hover:after {
			content: "<?php echo  $this->mycred_follow_buttton_text_check($id,$name);?>";
			}
			<?php
		}
		elseif ($name == "pinterest")
		{
			?>
			.mycred_socialshare_box a.mycred-social-icon-pinterest.i-text-admin:hover:after {
			content: "<?php echo  $this->mycred_follow_buttton_text_check($id,$name);?>";
			}
			<?php
		}
			}
		 ?></style>
		 <?php
        foreach ($filter_fields_order as $k => $value) {
            if (isset($value['id'])) {
                $id = $value['id'];
            }
            if (isset($value['name'])) {
                $name = $value['name'];
            }
            if (isset($value['slug'])) {
                $slug = $value['slug'];
            }
            ?>
            <?php
            if (!empty($selected)) {
            foreach ( $selected as $key => $value) {
            if ($key == (int)$id) {
            if (get_option('social_fields_check')[$id] == 'true'):
                if (get_option('button_style')) {
                    $this->social_button_style($this->socialshare[$slug]['url'], $name, get_option('social_fields_name')[$id] ? get_option('social_fields_name')[$id] : $name,$httpcode);
                }
                if (get_option('text_style')) {
                    $this->social_text_style($this->socialshare[$slug]['url'], $name, get_option('social_fields_name')[$id] ? get_option('social_fields_name')[$id] : $name ,$httpcode);
                }
                if (get_option('icon_style')) {
                    $this->social_icon_style($this->socialshare[$slug]['url'], $name,$httpcode);
                }
                if (get_option('icon_style_hover')) {
                    $this->social_icon_hover_style($this->socialshare[$slug]['url'], $name, get_option('social_fields_name')[$id] ? get_option('social_fields_name')[$id] : $name ,$httpcode);
                }
                ?>
                <?php
            endif;
        }
        }
    }
    }
        ?>
        <?php if ($num_show): ?>
            <?php if ($num_show < $count): ?>
                <button class="show-more" data-num="<?php echo $num_show ?>"> + </button>
            <?php endif; ?>
        <?php endif; ?>
        <?php
        echo '</div>';
    }
    public function social_button_style($url, $name, $title,$httpcode) {
        switch (true) {
            case ($name=='facebook' || $name=='Facebook'):
            if($httpcode==200){?>
			<a data-social="facebook" class="social-share test" href="<?php echo $url; ?>" onclick="mycred_facebook_share('facebook')"><button class="<?php echo $this->socialshare['facebook_share']["class"]?>" ><?php echo $title; ?></button></a><?php
			}else{
			?><a data-social="facebook" class="social-share" href="<?php echo $url; ?>" onclick="mycred_open_window(this.href, 'facebook', 'left=20,top=20,width=600,height=300,toolbar=0,resizable=1');
				return false;"><button  class="<?php echo $this->socialshare['facebook_share']["class"]?>"><?php echo $title; ?></button></a><?php
				}
                break;
            case ($name=='twitter' || $name=='Twitter'):
                ?><a data-social="twitter" class="social-share" href="<?php echo $url; ?>" onclick="mycred_open_window(this.href, 'twitter', 'left=20,top=20,width=600,height=300,toolbar=0,resizable=1');
                        return false;"><button  class="<?php echo $this->socialshare['twitter_share']["class"]?>"><?php echo $title; ?></button></a><?php
                    break;
                case ($name=='linkedin' || $name=='Linkedin'):
                    ?><a data-social="linkedin" class="social-share" href="<?php echo $url; ?>" onclick="mycred_open_window(this.href, 'linkedin', 'left=20,top=20,width=600,height=700,toolbar=0,resizable=1');
                        return false;" data-social="linkedin"><button class="<?php echo $this->socialshare['linkedin_share']["class"]?>"><?php echo $title; ?></button></a><?php
                break;
            case ($name=='pinterest' || $name=='Pinterest'):
                ?><a data-social="pinterest"  class="social-share" href="<?php echo $url; ?>" onclick="mycred_open_window(this.href, 'pinterest', 'left=20,top=20,width=600,height=700,toolbar=0,resizable=1');
                        return false;"> <button  class="<?php echo $this->socialshare['pinterest_share']["class"]?>"><?php echo $title; ?></button></a><?php
                    break;
                default:
                    break;
            }
            ?>
            <?php
        }
        public function social_icon_style($url, $name,$httpcode) {
            switch (true) {
                case ($name=='facebook' || $name=='Facebook'):
			if($httpcode==200){?>
			 <a href="<?php echo $url; ?>"  onclick="mycred_facebook_share('facebook')" class="<?php echo $this->socialshare['facebook_share']["class"]?> social-share" data-social="facebook">
				</a>
			<?php }else{
						 ?>
			<a href="<?php echo $url; ?>"  onclick="mycred_open_window(this.href, 'facebook', 'left=20,top=20,width=600,height=300,toolbar=0,resizable=1');
			return false;" class="<?php echo $this->socialshare['facebook_share']["class"]?> social-share" data-social="facebook">
			</a>
 <?php
						}
				break;
               case ($name=='twitter' || $name=='Twitter'):
                   ?><a href="<?php echo $url; ?>" onclick="mycred_open_window(this.href, 'twitter', 'left=20,top=20,width=600,height=300,toolbar=0,resizable=1');
                        return false;"  class="<?php echo $this->socialshare['twitter_share']["class"]?> social-share" data-social="twitter"></a><?php
                   break;
               case ($name=='linkedin' || $name=='Linkedin'):
                   ?><a href="<?php echo $url; ?>"  class="<?php echo $this->socialshare['linkedin_share']["class"]?> social-share"  onclick="mycred_open_window(this.href, 'linkedinwindow', 'left=20,top=20,width=600,height=700,toolbar=0,resizable=1');
                        return false;" data-social="linkedin"></a><?php
                   break;
               case ($name=='pinterest' || $name=='Pinterest'):
                   ?><a href="<?php echo $url; ?>" class="<?php echo $this->socialshare['pinterest_share']["class"]?> social-share" data-social="pinterest" onclick="mycred_open_window(this.href, 'pinterestwindow', 'left=20,top=20,width=600,height=700,toolbar=0,resizable=1');
                        return false;"></a><?php
                   break;
               
               default:
                   break;
           }
           ?>
           <?php
       }
       public function social_text_style($url, $name, $title,$httpcode) {
           switch (true) {
               case ($name=='facebook' || $name=='Facebook'):
			  if($httpcode==200){?><a data-social="facebook" class="social-share" href="<?php echo $url; ?>"  onclick="mycred_facebook_share('facebook')" >	<button class="facebook social-text"><?php echo $title; ?></button></a>
			<?php }else{ ?><a data-social="facebook" class="social-share" href="<?php echo $url; ?>"  onclick="mycred_open_window(this.href, 'facebook', 'left=20,top=20,width=600,height=300,toolbar=0,resizable=1');
			return false;" ><button class="facebook social-text"><?php echo $title; ?></button></a><?php
						}
			   break;
                case ($name=='twitter' || $name=='Twitter'):
                    ?><a data-social="twitter" class="social-share" href="<?php echo $url; ?>" onclick="mycred_open_window(this.href, 'twitter', 'left=20,top=20,width=600,height=300,toolbar=0,resizable=1');
                        return false;"><button class="twitter social-text"><?php echo $title; ?></button></a><?php
                    break;
                case ($name=='linkedin' || $name=='Linkedin'):
                    ?><a data-social="linkedin" class="social-share" href="<?php echo $url; ?>" onclick="mycred_open_window(this.href, 'linkedinwindow', 'left=20,top=20,width=600,height=700,toolbar=0,resizable=1');
                        return false;"><button class="linkedin social-text"><?php echo $title; ?></button></a><?php
                    break;
                case ($name=='pinterest' || $name=='Pinterest'):
                    ?>
                <a data-social="pinterest" class="social-share" href="<?php echo $url; ?>" onclick="mycred_open_window(this.href, 'pinterestwindow', 'left=20,top=20,width=600,height=700,toolbar=0,resizable=1');
                        return false;"><button class="pinterest social-text"><?php echo $title; ?></button></a>
                    <?php
                    break;
                default:
                    break;
            }
        }
        public function social_icon_hover_style($url, $name, $title,$httpcode) {
            switch (true) {
                case ($name=='facebook' || $name=='Facebook'):
if($httpcode==200){?><a href="<?php echo $url; ?>"  onclick="mycred_facebook_share('facebook')" class="social-share i-text-admin <?php echo $this->socialshare['facebook_share']["class"]?>" data-social="facebook"></a><?php }else{
						 ?><a href="<?php echo $url; ?>"  onclick="mycred_open_window(this.href, 'facebook', 'left=20,top=20,width=600,height=300,toolbar=0,resizable=1');
			return false;" class="social-share i-text-admin <?php echo $this->socialshare['facebook_share']["class"]?>" data-social="facebook"></a><?php
						}
			break;
            case ($name=='twitter' || $name=='Twitter'):
                ?><a href="<?php echo $url; ?>" onclick="mycred_open_window(this.href, 'twitter', 'left=20,top=20,width=600,height=300,toolbar=0,resizable=1');
                        return false;" class="social-share i-text-admin <?php echo $this->socialshare['twitter_share']["class"]?>" data-social="twitter"></a><?php
                break;
            case ($name=='linkedin' || $name=='Linkedin'):
                ?><a href="<?php echo $url; ?>" onclick="mycred_open_window(this.href, 'linkedinwindow', 'left=20,top=20,width=600,height=700,toolbar=0,resizable=1');
					return false;" class="social-share i-text-admin <?php echo $this->socialshare['linkedin_share']["class"]?>" data-social="linkedin"></a><?php
                break;
            case ($name=='pinterest' || $name=='Pinterest'):
                ?><a href="<?php echo $url; ?>" class="social-share i-text-admin fab <?php echo $this->socialshare['pinterest_share']["class"]?>" data-social="pinterest" onclick="mycred_open_window(this.href, 'pinterestwindow', 'left=20,top=20,width=600,height=700,toolbar=0,resizable=1');
                        return false;"></a><?php
                break;
            default:
                break;
        }
    }
    public function mycred_socialshare_facebook() {
        ?><div id="fb-root"></div><?php
    }
	public function mycred_follow_buttton_text_check($id,$name)
	{
		if ( !empty( get_option('social_fields_name')[$id] ) ) {
			return $follow_custom_title =   get_option('social_fields_name')[$id];
		}
		else {
			if ($name == "facebook" || $name == "Facebook") {
				return $follow_custom_title = 'Follow';
			}
			elseif ($name == "twitter" || $name == "Twitter") {
				return	$follow_custom_title = 'Follow';
			}
			elseif ($name == "youtube" || $name == "Youtube") {
				return	$follow_custom_title = 'Subscribe';
			}
			elseif ($name == "twitch" || $name == "Twitch") {
				return	$follow_custom_title = 'Follow';
			}elseif ($name == "instagram" || $name == "Instagram") {
				return	$follow_custom_title = 'Follow';
			}
		}
	}
	public function curl_facebook_status_app_id()
	{
		if( get_option('facebook_app_id') ) {
			$urlfacebook_app_id = 'https://graph.facebook.com/'.get_option('facebook_app_id');
			$ch = curl_init($urlfacebook_app_id);
			curl_setopt($ch, CURLOPT_HEADER, true);    // we want headers
			curl_setopt($ch, CURLOPT_NOBODY, true);    // we don't need body
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
			curl_setopt($ch, CURLOPT_TIMEOUT,10);
			$output = curl_exec($ch);
			$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			curl_close($ch);
			return $httpcode;
		}
	}
}

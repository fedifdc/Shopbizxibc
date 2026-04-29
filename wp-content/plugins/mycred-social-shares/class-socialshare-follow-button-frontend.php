<?php

class MyCred_Social_Follow_Button_Frontend {
	
    public $fields_social_order = array();
    public $socialshare = array();

    public function __construct() {

       

        $this->fields_social_order = array(
			0 => array(
				'id' => '0',
				'name' => 'facebook',
				'slug' => 'facebook_follow_button_share'
			),
			1 => array(
				'id' => '1',
				'name' => 'twitter',
				'slug' => 'twitter_follow_button_share'
			),
			2 => array(
				'id' => '2',
				'name' => 'youtube',
				'slug' => 'youtube_follow_button_share'
			),
			3 => array(
				'id' => '3',
				'name' => 'twitch',
				'slug' => 'twitch_follow_button_share'
            ),
            4 => array(
				'id' => '4',
				'name' => 'instagram',
				'slug' => 'instagram_follow_button_share'
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
                'class' => 'fmycred-social-icons mycred-social-icon-pinterest',
                'text_class' => 'pinterest social-text'
            ),
            'linkedin_share' => array(
                'class' => 'mycred-social-icons mycred-social-icon-linkedin',
                'text_class' => 'linkedin social-text'
            )
        );
    }

   
    public function social_share_follow_button_html() {

        echo '<div class="social-wrapper mycred-social-wrapper mycred_socialfollow_box">';

?>
<script>
function mycred_social_follow_add_points(socialtype)
{  
	jQuery.ajax({ 
        type: "POST", 
        data: {
            action: 'mycred-socialfollow-link-points',
			url: "<?php echo get_permalink();?>",
            token: myCRED_follow_social.token,
            socialtype: socialtype
        },
        dataType: "JSON",
        url: myCRED_follow_social.ajaxurl,
        success: function (response) {
        console.log('get points');   
        }
    });
}
function mycred_open_follow_window_new_tab (href_link,socialtype)
{  
	<?php
	if ( !is_user_logged_in() ) {
		echo 'alert( "If you want to share and earn points please login first." ); return;';
	}  
	?>
	mycred_social_follow_add_points(socialtype);
}
</script>
<?php 
		$youtubeURL = "https://www.youtube.com/channel/";
		$facebookURL = "https://www.facebook.com/";
		$twitter = "https://twitter.com/";
        $twitchURL = "https://www.twitch.tv/";
        $instagramURL = "https://www.instagram.com/";
 
        $this->socialshare['facebook_follow_button_share']['url'] = $facebookURL;
        $this->socialshare['twitter_follow_button_share']['url'] = $twitter;
        $this->socialshare['youtube_follow_button_share']['url'] = $youtubeURL;
        $this->socialshare['twitch_follow_button_share']['url'] = $twitchURL;
        $this->socialshare['instagram_follow_button_share']['url'] = $instagramURL;

        $filter_fields_order = get_option('social_follow_button_fields_sort', $this->fields_social_order);

 
        $display_all = get_option('display_all');
        $num_show = false;
        if (!$display_all) {
            $num_show = get_option('no_show_button');
        }
		
        $count = 0;
         $selected = get_option('social_follow_button_fields_check');
 
 ?>
		 <style>
         <?php 
         
		    foreach ($filter_fields_order as $k => $value) {
                if (isset($value['name'])) {
                    $name = $value['name'];
                }
                if (isset($value['id'])) {
                    $id = $value['id'];
                }
                if ($name == "facebook" || $name == "Facebook") {
                ?>
                    .mycred_socialfollow_box  a.mycred-social-icon-facebook.i-text-admin:hover:after {
                    content: "<?php echo  $this->mycred_follow_buttton_text_check($id,$name);?>";
                    }
                <?php 
                } 
                elseif ($name == "twitter" || $name == "Twitter") {
                ?>
                    .mycred_socialfollow_box a.mycred-social-icon-twitter.i-text-admin:hover:after {
                        content: "<?php echo  $this->mycred_follow_buttton_text_check($id,$name);?>";
                    }
            <?php
                } 
                elseif ($name == "youtube" || $name == "Youtube") {
            ?>
                    .mycred_socialfollow_box a.mycred-social-icon-youtube.i-text-admin:hover:after {
                        content: "<?php  echo $this->mycred_follow_buttton_text_check($id,$name);?>";
                    }
            <?php 
                } 
                elseif ($name == "twitch" || $name == "Twitch"){
            ?>
                    .mycred_socialfollow_box a.mycred-social-icon-twitch.i-text-admin:hover:after {
                        content: "<?php echo  $this->mycred_follow_buttton_text_check($id,$name);?>";
                    }
            <?php 
                }
                elseif ($name == "instagram" || $name == "Instagram") {
            ?>
                    .mycred_socialfollow_box a.mycred-social-icon-instagram.i-text-admin:hover:after {
                        content: "<?php echo  $this->mycred_follow_buttton_text_check($id,$name);?>";
                    }
            <?php 
                }
                elseif ($name == "linkedin" || $name == "Linkedin") {
            ?>
                    .mycred_socialfollow_box a.mycred-social-icon-linkedin.i-text-admin:hover:after {
                        content: "<?php echo  $this->mycred_follow_buttton_text_check($id,$name);?>";
                    }
            <?php 
                }
                elseif ($name == "pinterest" || $name == "Pinterest") {
            ?>
                    .mycred_socialfollow_box a.mycred-social-icon-pinterest.i-text-admin:hover:after {
                        content: "<?php echo  $this->mycred_follow_buttton_text_check($id,$name);?>";
                    }
            <?php 
                }
		 
		    }
		 ?>
		 </style>
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

                        if (get_option('social_follow_button_fields_check')[$id] == 'true') {

            			    if (get_option('follow_button_style')) {
								
                                $this->social_button_style($this->social_follow_url($this->socialshare[$slug]['url'].get_option('social_follow_button_fields_name')[$id]),$name,get_option('social_follow_button_fields_name')[$id] ? get_option('social_follow_button_fields_name')[$id] : $name,$this->mycred_follow_buttton_text_check($id,$name));
                            }

                            if (get_option('follow_text_style')) {
                                $this->social_text_style($this->social_follow_url($this->socialshare[$slug]['url'].get_option('social_follow_button_fields_name')[$id]), $name, get_option('social_follow_button_fields_name')[$id] ? get_option('social_follow_button_fields_name')[$id] : $name ,$this->mycred_follow_buttton_text_check($id,$name));
                            }

                            if (get_option('follow_icon_style')) {
                                $this->social_icon_style($this->social_follow_url($this->socialshare[$slug]['url'].get_option('social_follow_button_fields_name')[$id]), $name);
                            }

                            if (get_option('follow_icon_style_hover')) {
                                $this->social_icon_hover_style($this->social_follow_url($this->socialshare[$slug]['url'].get_option('social_follow_button_fields_name')[$id]), $name, get_option('social_follow_button_fields_name')[$id] ? get_option('social_follow_button_fields_name')[$id] : $name ,$this->mycred_follow_buttton_text_check($id,$name) );
                            }

                        }
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
	public function social_follow_url($url) {
		
		if ( !is_user_logged_in() ) {
			return 'javascript:void(0);';
		}else {
			return $url;
		}  
		
	}
	public function target_link() {
		
		if ( is_user_logged_in() ) {
			return 'target="_blank"';
		}else {
			return '';
		}  
		
	}
	
    public function social_button_style($url, $name, $title , $follow_custom_title) {

         switch (true) {
            case ($name=='Facebook' || $name=='facebook'):
                ?>
                <a data-social="facebook" class="social-share" href="<?php echo $url; ?>" <?php echo $this->target_link();?>  onclick="mycred_open_follow_window_new_tab(this.href, 'facebook', 'left=20,top=20,width=600,height=300,toolbar=0,resizable=1');"><button class="mycred-social-icons mycred-social-icon-facebook" ><?php echo $follow_custom_title; ?></button></a>
			   <?php
                break;
				case ($name=='Twitter' || $name=='twitter'):
                ?>
                <a data-social="twitter" class="social-share" href="<?php echo $url; ?>" <?php echo $this->target_link();?> onclick="mycred_open_follow_window_new_tab(this.href, 'twitter', 'left=20,top=20,width=600,height=300,toolbar=0,resizable=1');"><button  class="mycred-social-icons mycred-social-icon-twitter"><?php echo $follow_custom_title; ?></button></a>
                    <?php
                    break;
                 case ($name=='Youtube' || $name=='youtube'):
                    ?>
					<a data-social="youtube"  class="social-share"  <?php echo $this->target_link();?>  href="<?php echo $url."?sub_confirmation=1"; ?>" onclick="mycred_open_follow_window_new_tab(this.href, 'youtube', 'left=20,top=20,width=600,height=700,toolbar=0,resizable=1');
                        "><button  class="mycred-social-icons mycred-social-icon-youtube"><?php echo $follow_custom_title; ?></button></a>	
					<?php
                     break;
					 case ($name=='Twitch' || $name=='twitch'):
                    ?>
					<a data-social="twitch"  class="social-share"  <?php echo $this->target_link();?>  href="<?php echo $url; ?>" onclick="mycred_open_follow_window_new_tab(this.href, 'twitch', 'left=20,top=20,width=600,height=700,toolbar=0,resizable=1');
                        "><button  class="mycred-social-icons mycred-social-icon-twitch"><?php echo $follow_custom_title; ?></button></a>	
					<?php
                     break;
                     case ($name=='Instagram' || $name=='instagram'):
                        ?>
                        <a data-social="instagram"  class="social-share"  <?php echo $this->target_link();?>  href="<?php echo $url; ?>" onclick="mycred_open_follow_window_new_tab(this.href, 'instagram', 'left=20,top=20,width=600,height=700,toolbar=0,resizable=1');
                            "><button  class="mycred-social-icons mycred-social-icon-instagram"><?php echo $follow_custom_title; ?></button></a>	
                        <?php
                         break;
                default:
                    break;
            }
            ?>
			<?php
        }
 
        public function social_icon_style($url, $name) {
            switch (true) {
                case ($name=='Facebook' || $name=='facebook'):
                    ?>
                <a  <?php echo $this->target_link();?>  href="<?php echo $url; ?>"  onclick="mycred_open_follow_window_new_tab(this.href, 'facebook', 'left=20,top=20,width=600,height=300,toolbar=0,resizable=1');"class="mycred-social-icons mycred-social-icon-facebook social-share" data-social="facebook">
				</a>
                   <?php
                   break;
               case ($name=='Twitter' || $name=='twitter'):
                   ?>
                <a  <?php echo $this->target_link();?>  href="<?php echo $url; ?>" onclick="mycred_open_follow_window_new_tab(this.href, 'twitter', 'left=20,top=20,width=600,height=300,toolbar=0,resizable=1');
                        "  class="mycred-social-icons mycred-social-icon-twitter social-share" data-social="twitter"></a>
                   <?php
                   break;
               case ($name=='Youtube' || $name=='youtube'):
                   ?>
                <a  <?php echo $this->target_link();?>  href="<?php echo $url."?sub_confirmation=1"; ?>"  class="mycred-social-icons mycred-social-icon-youtube social-share"  onclick="mycred_open_follow_window_new_tab(this.href, 'youtube', 'left=20,top=20,width=600,height=700,toolbar=0,resizable=1');
                        " data-social="youtube"></a>
                   <?php
                   break;

               case ($name=='Twitch' || $name=='twitch'):
                   ?>
                <a  <?php echo $this->target_link();?>  href="<?php echo $url; ?>" class="mycred-social-icons mycred-social-icon-twitch social-share" data-social="twitch" onclick="mycred_open_follow_window_new_tab(this.href, 'twitch', 'left=20,top=20,width=600,height=700,toolbar=0,resizable=1');
                        "></a>
                   <?php
                   break;

                case ($name=='Instagram' || $name=='instagram'):
                    ?>
                 <a  <?php echo $this->target_link();?>  href="<?php echo $url; ?>" class="mycred-social-icons mycred-social-icon-instagram social-share" data-social="instagram" onclick="mycred_open_follow_window_new_tab(this.href, 'instagram', 'left=20,top=20,width=600,height=700,toolbar=0,resizable=1');
                         "></a>
                    <?php
                    break;
               default:
                   break;
           }
           ?>
           <?php
       }

       public function social_text_style($url, $name, $title ,$follow_custom_title) {
           switch (true) {
               case ($name=='Facebook' || $name=='facebook'):
                   ?>
                <a data-social="facebook" class="social-share"  <?php echo $this->target_link();?>  href="<?php echo $url; ?>"  onclick="mycred_open_follow_window_new_tab(this.href, 'facebook', 'left=20,top=20,width=600,height=300,toolbar=0,resizable=1');"><button class="facebook social-text"><?php echo  $follow_custom_title; ?></button></a>
                    <?php
                    break;
                case ($name=='Twitter' || $name=='twitter'):
                    ?>
               <a data-social="twitter" class="social-share"  <?php echo $this->target_link();?>  href="<?php echo $url; ?>" onclick="mycred_open_follow_window_new_tab(this.href, 'twitter', 'left=20,top=20,width=600,height=300,toolbar=0,resizable=1');
                        "> <button class="twitter social-text"><?php echo $follow_custom_title; ?></button></a>
                    <?php
                    break;
                case ($name=='Youtube' || $name=='youtube'):
                    ?>
                <a data-social="youtube" class="social-share"  <?php echo $this->target_link();?>  href="<?php echo $url."?sub_confirmation=1"; ?>" onclick="mycred_open_follow_window_new_tab(this.href, 'youtube', 'left=20,top=20,width=600,height=700,toolbar=0,resizable=1');
                        "><button class="youtube social-text"><?php echo  $follow_custom_title; ?></button></a>
                    <?php
                    break;
                case ($name=='Twitch' || $name=='twitch'):
                    ?>
                <a data-social="twitch" class="social-share"  <?php echo $this->target_link();?>  href="<?php echo $url; ?>" onclick="mycred_open_follow_window_new_tab(this.href, 'twitch', 'left=20,top=20,width=600,height=700,toolbar=0,resizable=1');
                        "><button class="twitch social-text"><?php echo  $follow_custom_title; ?></button></a>
                    <?php
                    break;
                case ($name=='Instagram' || $name=='instagram'):
                    ?>
                <a data-social="instagram" class="social-share"  <?php echo $this->target_link();?>  href="<?php echo $url; ?>" onclick="mycred_open_follow_window_new_tab(this.href, 'instagram', 'left=20,top=20,width=600,height=700,toolbar=0,resizable=1');
                        "><button class="instagram social-text"><?php echo  $follow_custom_title; ?></button></a>
                    <?php
                    break;
                default:
                    break;
            }
        }

        public function social_icon_hover_style($url, $name, $title) {
            switch (true) {
                case ($name=='Facebook' || $name=='facebook'):
                    ?>
                <a  <?php echo $this->target_link();?>  href="<?php echo $url; ?>"  onclick="mycred_open_follow_window_new_tab(this.href, 'facebook', 'left=20,top=20,width=600,height=300,toolbar=0,resizable=1');" class="social-share i-text-admin mycred-social-icons mycred-social-icon-facebook" data-social="facebook">
                </a>
             <?php
                break;
            case ($name=='Twitter' || $name=='twitter'):
                ?>
                 <a  <?php echo $this->target_link();?>  href="<?php echo $url; ?>" onclick="mycred_open_follow_window_new_tab(this.href, 'twitter', 'left=20,top=20,width=600,height=300,toolbar=0,resizable=1');
                        " class="social-share i-text-admin mycred-social-icons mycred-social-icon-twitter" data-social="twitter">
                </a> 
                <?php
                break;
            case ($name=='Youtube' || $name=='youtube'):
                ?>
                <a  <?php echo $this->target_link();?>  href="<?php echo $url."?sub_confirmation=1"; ?>" class="social-share i-text-admin mycred-social-icons mycred-social-icon-youtube"  onclick="mycred_open_follow_window_new_tab(this.href, 'youtube', 'left=20,top=20,width=600,height=700,toolbar=0,resizable=1');
                        " data-social="youtube">
                 </a>
            
                <?php
                break;

            case ($name=='Twitch' || $name=='twitch'):
                ?> 
                 <a  <?php echo $this->target_link();?>  href="<?php echo $url; ?>" class="social-share i-text-admin mycred-social-icons mycred-social-icon-twitch" data-social="twitch" onclick="mycred_open_follow_window_new_tab(this.href, 'twitch', 'left=20,top=20,width=600,height=700,toolbar=0,resizable=1');
                  "> 
                </a>
				<?php
                break;

            case ($name=='Instagram' || $name=='instagram'):
                ?> 
                    <a  <?php echo $this->target_link();?>  href="<?php echo $url; ?>" class="social-share i-text-admin mycred-social-icons mycred-social-icon-instagram" data-social="instagram" onclick="mycred_open_follow_window_new_tab(this.href, 'instagram', 'left=20,top=20,width=600,height=700,toolbar=0,resizable=1');
                    "> 
                </a>
                <?php
                break;
            default:
                break;
        }
    }

	public function mycred_follow_buttton_text_check($id,$name)
	{
		if (!empty(get_option('social_follow_button_custom_filed_text')[$id])) {
		  return $follow_custom_title =   get_option('social_follow_button_custom_filed_text')[$id];
		}
		else
		{
			
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
            }
            elseif ($name == "instagram" || $name == "Instagram") {
                return	$follow_custom_title = 'Follow';
            }
				 
		}
	}

}
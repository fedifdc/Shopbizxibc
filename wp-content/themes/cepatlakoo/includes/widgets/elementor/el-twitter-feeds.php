<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function cepatlakoo_get_recent_tweets(
   $consumer_key = '',
   $consumer_secret = '',
   $access_token = '',
   $access_token_secret = '',
   $username = '',
   $tweets_count = '' ) {

   $settings = array(
       'oauth_access_token' => esc_html( $access_token ),
       'oauth_access_token_secret' => esc_html( $access_token_secret ),
       'consumer_key' => esc_html( $consumer_key ),
       'consumer_secret' => esc_html( $consumer_secret )
   );

   require_once( get_template_directory() . '/includes/TwitterAPIWordPress.php');

   $url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
   $get_field = '?screen_name='. esc_html( $username ) .'&count='. esc_html( $tweets_count );
   $request_method = 'GET';

   $instance = new Twitter_API_WordPress( $settings );
   $update = $instance
      ->set_get_field( $get_field )
      ->build_oauth( $url, $request_method )
      ->process_request();

   $twitter_data = json_decode($update);

   return $twitter_data;
}

function twitter_links($tweet_text) {
   $tweet_text = preg_replace("#(^|[\n ])([\w]+?://[\w]+[^ \"\n\r\t< ]*)#", "\\1<a href=\"\\2\" target=\"_blank\">\\2</a>", $tweet_text);
   $tweet_text = preg_replace("#(^|[\n ])((www|ftp)\.[^ \"\t\n\r< ]*)#", "\\1<a href=\"http://\\2\" target=\"_blank\">\\2</a>", $tweet_text);
   $tweet_text = preg_replace("/@(\w+)/", "<a href=\"http://twitter.com/\\1\" target=\"_blank\">@\\1</a>", $tweet_text);
   $tweet_text = preg_replace("/#(\w+)/", "<a href=\"http://twitter.com/search?q=\\1\" target=\"_blank\">#\\1</a>", $tweet_text);
   return $tweet_text;
}

class Widget_Twitter_Feeds extends Widget_Base {

   public function get_id() {
      return 'cepatlakoo-twitter-feeds';
   }

   public function get_name() {
      return 'cepatlakoo-twitter-feeds';
   }

   public function get_title() {
      return esc_html__( 'CL - Twitter Feeds', 'cepatlakoo' );
   }

   public function get_icon() {
      return 'eicon-twitter';
   }

   protected function register_controls() {

		$this->start_controls_section(
         'section_twitter_feeds',
         [
            'label' => esc_html__( 'CL - Twitter Feeds', 'cepatlakoo' ),
         ]
      );

		$this->add_control(
			'cepatlakoo_info',
			[
				'label' => esc_html__( 'Note', 'cepatlakoo' ),
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'raw' => sprintf(__( 'You can get the values for Consumer Key, Consumer Secret, Access Token and Access Token Secret from %s.', 'cepatlakoo' ), '<a href="https://developer.twitter.com/" target="_blank">https://developer.twitter.com/</a>' ),
				'content_classes' => 'clel-notice',
			]
		);
		
		$this->add_control(
			'hr',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

      $this->add_control(
         'cepatlakoo_consumer_key',
         [
            'label' => esc_html__( 'Consumer Key', 'cepatlakoo' ),
            'type' => Controls_Manager::TEXT,
            'default' => '',
            'title' => esc_html__( 'Enter some text', 'cepatlakoo' ),
         ]
      );

      $this->add_control(
         'cepatlakoo_consumer_secret',
         [
            'label' => esc_html__( 'Consumer Secret', 'cepatlakoo' ),
            'type' => Controls_Manager::TEXT,
            'default' => '',
            'title' => esc_html__( 'Enter some text', 'cepatlakoo' ),
         ]
      );

      $this->add_control(
         'cepatlakoo_access_token',
         [
            'label' => esc_html__( 'Access Token', 'cepatlakoo' ),
            'type' => Controls_Manager::TEXT,
            'default' => '',
            'title' => esc_html__( 'Enter some text', 'cepatlakoo' ),
         ]
      );

      $this->add_control(
         'cepatlakoo_access_token_secret',
         [
            'label' => esc_html__( 'Access Token Secret', 'cepatlakoo' ),
            'type' => Controls_Manager::TEXT,
            'default' => '',
            'title' => esc_html__( 'Enter some text', 'cepatlakoo' ),
         ]
      );

      $this->add_control(
         'cepatlakoo_twitter_username',
         [
            'label' => esc_html__( 'Twitter Username', 'cepatlakoo' ),
            'type' => Controls_Manager::TEXT,
            'default' => '',
            'title' => esc_html__( 'Enter some text', 'cepatlakoo' ),
         ]
      );

      $this->add_control(
         'cepatlakoo_number_of_tweets',
         [
            'label' => esc_html__( 'Number Of Tweets', 'cepatlakoo' ),
            'type' => Controls_Manager::TEXT,
            'default' => '7',
            'title' => esc_html__( 'Enter some text', 'cepatlakoo' ),
         ]
      );
   }

   protected function render() {
      $settings = $this->get_settings();

      if ( !empty( $settings['cepatlakoo_consumer_key'] )  && !empty( $settings['cepatlakoo_consumer_secret'] ) ) :
         $tweets_cepatlakoo =
         cepatlakoo_get_recent_tweets(
            esc_attr( $settings['cepatlakoo_consumer_key'] ),
            esc_attr( $settings['cepatlakoo_consumer_secret'] ),
            esc_attr( $settings['cepatlakoo_access_token'] ),
            esc_attr( $settings['cepatlakoo_access_token_secret'] ),
            esc_attr( $settings['cepatlakoo_twitter_username'] ),
            absint( $settings['cepatlakoo_number_of_tweets'] )
         );
?>
         <section id="twitter-widget">
            <div class="container clearfix">
               <div class="twitter-feeds single-slider">
               <?php
               if( is_array($tweets_cepatlakoo) && count($tweets_cepatlakoo) > 0 ) :
                  foreach ( $tweets_cepatlakoo as $tweet_cepatlakoo ) :
                     $tweet_time = date('U', strtotime( $tweet_cepatlakoo->created_at ));
               ?>
                     <div class="twitter-feed">
                        <p><?php echo twitter_links($tweet_cepatlakoo->text) ?></p>
                        <div class="entry-meta">
                           <span><i class="eicon-clock"></i>
                           <?php
                              echo sprintf( wp_kses( __( '<a href="%s" target="_blank">%s ago</a>', 'cepatlakoo' ), array(  'a' => array( 'href' => array() , 'target' => array() ), 'img' => array( 'src' => array() ) ) ),
                                 'https://twitter.com/'. esc_attr( $tweet_cepatlakoo->user->screen_name ).'/status/'. $tweet_cepatlakoo->id_str,
                                 human_time_diff( $tweet_time, current_time('timestamp') )  );
                           ?>
                           </span>
                        </div>
                     </div>
               <?php
                  endforeach;
               endif;
               ?>
               </div>
            </div>
         </section>
<?php
         endif;
   }

   protected function content_template() {}

   public function render_plain_content() {}

}

Plugin::instance()->widgets_manager->register( new Widget_Twitter_Feeds() );
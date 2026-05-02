<?php
/**
 * The admin-facing functionality of the plugin.
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

/**
 * Main Blog Designer PRO Backend Functions Class.
 *
 * @class   Bdp_Like
 * @version 1.0.0
 */
class Bdp_Like {
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_shortcode( 'likebtn_shortcode', array( $this, 'likebtn_shortcode' ) );
	}
	/**
	 * Utility to test if the post is already liked
	 *
	 * @param int $post_id post id.
	 * @return boolean true|false
	 */
	public static function already_liked( $post_id ) {
		$post_users = null;
		$user_id    = null;
		if ( is_user_logged_in() ) {
			$user_id         = get_current_user_id();
			$post_meta_users = get_post_meta( $post_id, 'like_users' );
			if ( 0 != count( $post_meta_users ) ) {
				$post_users = $post_meta_users[0];
			}
		} else {
			$user_id         = Bdp_Utility::get_ip();
			$post_meta_users = get_post_meta( $post_id, 'like_ipaddresses' );
			/* meta exists, set up values. */
			if ( 0 != count( $post_meta_users ) ) {
				$post_users = $post_meta_users[0];
			}
		}
		if ( is_array( $post_users ) && in_array( $user_id, $post_users, true ) ) {
			return true;
		} else {
			return false;
		}
	}
	/**
	 * Output the like button
	 *
	 * @param int $post_id post id.
	 * @return $output
	 */
	public function get_simple_likes_button( $post_id ) {
		$output        = '';
		$nonce         = wp_create_nonce( 'bdp-simple-likes-nonce' );
		$post_id_class = esc_attr( ' bdp-button-' . $post_id );
		$comment_class = esc_attr( '' );
		$like_count    = get_post_meta( $post_id, '_post_like_count', true );
		$like_count    = ( isset( $like_count ) && is_numeric( $like_count ) ) ? $like_count : 0;
		$count         = self::get_like_count( $like_count );
		$icon_empty    = self::get_unliked_icon();
		$icon_full     = self::get_liked_icon();
		$loader        = '<span id="bdp-loader"></span>';
		if ( self::already_liked( $post_id ) ) {
			$class = esc_attr( ' liked' );
			$title = esc_html__( 'Unlike', 'blog-designer-pro' );
			$icon  = $icon_full;
		} else {
			$class = '';
			$title = esc_html__( 'Like', 'blog-designer-pro' );
			$icon  = $icon_empty;
		}
		$output = '<span class="bdp-wrapper-like"><a href="javascript:void(0)" class="bdp-like-button' . $post_id_class . $class . '" data-nonce="' . $nonce . '" data-post-id="' . $post_id . '" title="' . $title . '">' . $icon . $count . '</a>' . $loader . '</span>';
		return $output;
	}
	/**
	 * Utility retrieves count plus count options,
	 * returns appropriate format based on options
	 *
	 * @param int $like_count like count.
	 * @return int $count
	 */
	public static function get_like_count( $like_count ) {
		$like_text = esc_html__( 'Like', 'blog-designer-pro' );
		if ( is_numeric( $like_count ) && $like_count > 0 ) {
			$number = Bdp_Utility::format_count( $like_count );
		} else {
			$number = $like_text;
		}
		$count = '<span class="bdp-count">' . $number . '</span>';
		return $count;
	}
	/**
	 * Utility returns the button icon for "like" action
	 */
	public static function get_liked_icon() {
		$icon = '<i class="fas fa-heart"></i>';
		return $icon;
	}
	/**
	 * Utility returns the button icon for "unlike" action
	 *
	 * @return html
	 */
	public static function get_unliked_icon() {
		return '<i class="far fa-heart"></i>';
	}
	/**
	 * Processes shortcode to manually add the button to posts
	 *
	 * @return html
	 */
	public function likebtn_shortcode() {
		return self::get_simple_likes_button( get_the_ID(), 0 );
	}
	/**
	 * Utility retrieves post meta user likes (user id array),
	 * then adds new user id to retrieved array
	 *
	 * @param int $user_id user id.
	 * @param int $post_id post id.
	 * @return array $post_users
	 */
	public static function post_user_likes( $user_id, $post_id ) {
		$post_users      = '';
		$post_meta_users = get_post_meta( $post_id, 'like_users' );
		if ( 0 != count( $post_meta_users ) ) {
			$post_users = $post_meta_users[0];
		}
		if ( ! is_array( $post_users ) ) {
			$post_users = array();
		}
		if ( ! in_array( $user_id, $post_users, true ) ) {
			$post_users[ 'user-' . $user_id ] = $user_id;
		}
		return $post_users;
	}
	/**
	 * Utility retrieves post meta ip likes (ip array),
	 * then adds new ip to retrieved array
	 *
	 * @param int $user_ip user ip address.
	 * @param int $post_id post id.
	 * @return array $post_users
	 */
	public static function post_ip_likes( $user_ip, $post_id ) {
		$post_users      = '';
		$post_meta_users = get_post_meta( $post_id, 'like_ipaddresses' );
		// Retrieve post information.
		if ( 0 != count( $post_meta_users ) ) {
			$post_users = $post_meta_users[0];
		}
		if ( ! is_array( $post_users ) ) {
			$post_users = array();
		}
		if ( ! in_array( $user_ip, $post_users, true ) ) {
			$post_users[ 'ip-' . $user_ip ] = $user_ip;
		}
		return $post_users;
	}
}
new Bdp_Like();

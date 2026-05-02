<?php
/**
 * The template for displaying all archive posts
 * This template can be overridden by copying it to yourtheme/bdp_templates/archive/ticker.php.
 *
 * @link       https://www.solwininfotech.com/
 * @since      2.3
 *
 * @package    Blog_Designer_PRO
 * @subpackage Blog_Designer_PRO/admin
 * @author     Solwin Infotech <info@solwininfotech.com>
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'bd_archive_design_format_function', 'bdp_archive_ticker_template', 10, 1 );

if ( ! function_exists( 'bdp_archive_ticker_template' ) ) {

	/**
	 * Add html for ticker template
	 *
	 * @param array $bdp_settings settings.
	 * @global object $post
	 * @return void
	 */
	function bdp_archive_ticker_template( $bdp_settings ) {
		global $post;
		?>
		<li>
			<?php
				$bdp_post_title_link = isset( $bdp_settings['bdp_post_title_link'] ) ? $bdp_settings['bdp_post_title_link'] : 1;
			if ( 1 == $bdp_post_title_link ) {
				echo '<a class="blog-ticker-anchor" href="' . esc_url( get_the_permalink() ) . '" title="' . esc_html( get_the_title() ) . '">';
			}
			$total_noofline = isset( $bdp_settings['total_noofline'] ) ? $bdp_settings['total_noofline'] : '2';
			echo ( $total_noofline ) ? '<span>' : '';
			echo wp_kses( get_the_title(), Bdp_Admin_Functions::args_kses() );
			echo ( $total_noofline ) ? '</span>' : '';
			if ( 1 == $bdp_post_title_link ) {
				echo '</a>';
			}
			?>
		</li>
		<?php
		do_action( 'bdp_archive_separator_after_post' );
	}
}

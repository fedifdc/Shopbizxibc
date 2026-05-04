<?php 
/**
 * The template for displaying comments
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @since CepatLakoo 1.0.0
 */
?>

<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<?php
if ( 'comments.php' == basename( $_SERVER['SCRIPT_FILENAME'] ) )
    die ( esc_html_e( 'Please do not load this page directly. Thanks!', 'cepatlakoo' ) );
    if ( ! empty( $post->post_password ) ) {
        if ( !empty($_COOKIE['wp-postpass_' . COOKIEHASH]) != $post->post_password ) { ?>
            <p class="nocomments"><?php esc_html_e( 'This post is password protected. Enter the password to view comments.', 'cepatlakoo' ) ; ?></p> <?php
        return;
        }
    }
?>

<?php if ( have_comments() ) : ?>
    <div class="widget article-widget">
        <div class="comment-widget">
            <h4 class="widget-title"><?php comments_number( esc_html__( 'No Comments', 'cepatlakoo' ), esc_html__( '1 Comment', 'cepatlakoo' ), esc_html__( '% Comments', 'cepatlakoo' ) ); ?></h4>
            <div class="comments">
                <ul>
                    <?php wp_list_comments( 'callback=cepatlakoo_comment_list' ); ?>
                </ul>
                <?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
                    <div class="navigation clearfix">
                        <span class="prev"><?php previous_comments_link(esc_html__( '&larr; Previous', 'cepatlakoo' ), 0); ?></span>
                        <span class="next"><?php next_comments_link(esc_html__( 'Next &rarr;', 'cepatlakoo' ), 0); ?></span>
                    </div>  
                <?php endif; ?>
            </div>
        </div>  
    </div>    
<?php endif; ?> 

<?php if ( comments_open() ) : ?>
    <div class="article-widget">
        <div class="comment-form">
        <?php 
            comment_form( array(
                'title_reply'           =>  '<h4 class="widget-title">'. esc_html__( 'Leave a response','cepatlakoo' ) .'</h4>',
                'comment_notes_before'  =>  '',
                'comment_notes_after'   =>  '',
                'cancel_reply_link'     =>  esc_html__( 'Cancel Reply', 'cepatlakoo' ),
                'logged_in_as'          => '<p class="logged-user">' . sprintf( wp_kses( __( 'You are logged in as <a href="%1$s">%2$s</a> &#8212; <a href="%3$s">Logout &raquo;</a>', 'cepatlakoo' ), array(  'a' => array( 'href' => array() ) ) ), admin_url( 'profile.php' ), $user_identity, wp_logout_url( apply_filters( 'the_permalink', get_permalink() ) ) ) . '</p>',
                'fields'                => array(
                'author'                =>  '<div class="input-group"><label><input type="text" name="author" id="fullname" class="input-s" value="" placeholder="'. esc_html__('Full Name', 'cepatlakoo') .'" /></label></div>',
                'email'                 =>  '<div class="input-group column"><label><input type="text" name="email" id="email" class="input-s" value=""  placeholder="'. esc_html__('Email Address', 'cepatlakoo') .'" /></label></div>',
                'url'                   =>  '<div class="input-group column"><label><input type="text" name="url" id="weburl" class="input-s" value="" placeholder="'. esc_html__('Web URL', 'cepatlakoo') .'" /></label></div>'
                                        ),
                'comment_field'         =>  '<div class="input-group"><label><textarea name="comment" id="message" class="input textarea" placeholder="'. esc_html__('Comment', 'cepatlakoo') .'" /></textarea></label></div>',
                'label_submit'          => esc_html__('Submit','cepatlakoo')
            ));
        ?>
            <div class="clearfix"></div>    
        </div>
    </div>
<?php endif; ?>
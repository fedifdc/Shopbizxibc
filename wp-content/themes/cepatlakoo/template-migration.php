<?php
/**
 * The template for displaying override page
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

<?php if ( ! is_admin() ) : ?>
<!-- START : NEW HTML REPLACMENT -->
<!DOCTYPE html>
<html>
    <head>
        <title><?php esc_html_e( 'Maintainance Mode', 'cepatlakoo' ); ?></title>
        <style type="text/css">
            body {
                background-color: #fafafa;
                font: 14px/160% Arial, Helvetican, sans-serif;
                color: #555;
                margin: 0 auto;
            }

            #wrap {
                background-color: #fff;
                max-width: 480px;
                text-align: center;
                margin: 10% auto 0 auto;
                padding: 30px;
                box-shadow: 0 1px 5px 0 #ddd;
            }

            h3 {
                font-size: 24px;
                color: #ff0000;
            }

            .note {
                font-size: 18px;
                line-height: 170%;
            }
        </style>
    </head>

    <body class="maintenance">
        <div id="wrap">
            <p class="note"><?php esc_html_e( 'Mohon maaf, kami sedang dalam proses maintainance. Silahkan cek kembali secara berkala.', 'cepatlakoo' ); ?></p>
            
            <?php if( current_user_can('administrator') && is_user_logged_in() ) :  ?>
                <h3><?php _e( 'Update Theme Cepatlakoo', 'cepatlakoo' ); ?></h3>
                <p><?php esc_html_e( 'Hai Admin, theme Cepatlakoo mengalami perubahan cukup besar pada versi ini. Kami mengubah theme options dari menggunakan Titan Framework menjadi menggunakan plugin Redux Framework', 'cepatlakoo') ?></p>
                <p><?php esc_html_e( 'Klik link di bawah untuk melakukan proses migrasi theme options.', 'cepatlakoo' ); ?></p>
                <a href="<?php echo get_admin_url(); ?>"><?php esc_html_e( 'Lakukan Migrasi Sekarang', 'cepatlakoo' ); ?></a>
            <?php endif; ?>
         </div>
    </body>
</html>
<!-- END : HTML REPLACE -->
<?php endif; ?>


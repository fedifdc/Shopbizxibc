<?php

class Komship_Settings {

    public static function add_settings_page() {
        add_menu_page(
            __( 'Komship Integration', 'komship-integration' ),
            __( 'Komship Settings', 'komship-integration' ),
            'manage_options',
            'komship-settings',
            array( 'Komship_Settings', 'settings_page_html' ),
            'dashicons-car'
        );

    }

    public static function settings_page_html() {
        ?>
        <div class="wrap">
            <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
            <form method="POST" action="options.php">
                <?php
                    settings_fields( 'komship_integration_options' );
                    do_settings_sections( 'komship-settings' );
                ?>
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="komship_base_url"><?php _e( 'Base URL', 'komship-integration' ); ?></label>
                        </th>
                        <td>
                            <input name="komship_base_url" id="komship_base_url" type="text" value="<?php echo esc_attr( get_option( 'komship_base_url' ) ); ?>" class="regular-text">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="komship_api_key"><?php _e( 'API Key', 'komship-integration' ); ?></label>
                        </th>
                        <td>
                            <input name="komship_api_key" id="komship_api_key" type="text" value="<?php echo esc_attr( get_option( 'komship_api_key' ) ); ?>" class="regular-text">
                        </td>
                    </tr>
                </table>
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }
    

    public static function register_settings() {
        register_setting( 'komship_integration_options', 'komship_base_url' );
        register_setting( 'komship_integration_options', 'komship_api_key' );
    }
}

add_action( 'admin_init', array( 'Komship_Settings', 'register_settings' ) );

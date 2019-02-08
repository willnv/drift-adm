<?php

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'DriftADM_Settings' ) ) {

    class DriftADM_Settings {
        
        /**
         * Where all options are stored
         *
         * @var array - array of arrays
         */
        private static
            $settings;

        /**
         * @var string $settings_page_slug
         * @var string $settings_slug
         * @var array $themes
         */
        static
            $settings_page_slug, 
            $settings_slug, 
            $themes;


        public function __construct() {
            
            self::$themes = array(
                'Light',
                'Dark'
            );

            self::$settings_page_slug = 'drift-adm-settings';
            self::$settings_slug = 'main-settings';
            self::$settings = self::driftadm_get_settings();

            #register_activation_hook( __FILE__, array( __CLASS__, 'set_defaults' ) );
        }

        
        /**
         * Sets the default options when
         * loading DriftADM for the first time.
         */
        public static function set_defaults() {

            if ( ! get_option( 'driftadm_loaded' ) ) {

                update_option( 'menu_title', get_bloginfo( 'name' ) );

                # Set loaded
                updade_option( 'driftadm_loaded', 1 );
            }
        }


        /**
         * Get all existing options in Drift ADM.
         * Use this function to add more settings as well.
         *
         * @return array $settings
         */
        public static function driftadm_get_settings() {

            $settings['menu_title'] = array(
                'id'          => 'menu_title',
                'name'        => __( 'Menu Title', 'drift-adm'),
                'description' => __( 'Shown above the main Wordpress Menu.', 'drift-adm' ),
                'type'        => 'string',
                'input_type'  => 'text',
                'section'     => 'main_settings'
            );
            
            $settings['adm_theme'] = array(
                'id'            => 'adm_theme',
                'name'          => __( 'Theme', 'drift-adm' ),
                'description'   => __( 'Dark theme may cause layout errors on 3rd party plugin screens.', 'drift-adm' ),
                'type'          => 'boolean',
                'input_type'    => 'radio',
                'radio_choices' => 2,
                'choices'       => self::$themes,
                'section'       => 'main_settings'
            );

            $settings['disable_wptoolbar'] = array(
                'id'            => 'disable_wptoolbar',
                'name'          => __( 'WP toolbar', 'drift-adm' ),
                'description'   => __( 'Disables default toolbar on front-end.', 'drift-adm' ),
                'type'          => 'boolean',
                'input_type'    => 'bool',
                'section'       => 'main_settings'
            );

            $settings['disable_wpfooter'] = array(
                'id'            => 'disable_wpfooter',
                'name'          => __( 'WP footer', 'drift-adm' ),
                'description'   => __( 'Disables the version and welcome message from footer.', 'drift-adm' ),
                'type'          => 'boolean',
                'input_type'    => 'bool',
                'section'       => 'main_settings'
            );
            
            $settings['login_bg_type'] = array(
                'id'          => 'login_bg_type',
                'name'        => __( 'Login Background', 'drift-adm' ),
                'description' => __( 'Dimensions for the image to appear entirely are 400x400.', 'drift-adm' ),
                'type'        => 'string',
                'input_type'  => 'upload_img',
                'section'     => 'login_settings'
            );
            
            $settings['login_bg_img'] = array(
                'id'          => 'login_bg_img',
                'name'        => '',
                'type'        => 'string',
                //'input_type'  => 'hidden',
                'section'     => 'login_settings'
            );
            
            $settings['login_bg_color'] = array(
                'id'          => 'login_bg_color',
                'name'        => '',
                'type'        => 'string',
                //'input_type'  => 'hidden',
                'section'     => 'login_settings'
            );

            $settings['login_logo'] = array(
                'id'          => 'login_logo',
                'name'        => __( 'Login logo', 'drift-adm' ),
                'description' => __( 'A logo to display on top of your login background.', 'drift-adm' ),
                'type'        => 'string',
                'input_type'  => 'login_logo',
                'section'     => 'login_settings'
            );

            return $settings;
        }


        /**
         * Given the option id, retrieves the option value.
         * 
         * @param string $setting
         * @return string|array $settings
         */
        public static function driftadm_get_setting( $setting = '' ) {
            if ( array_key_exists( $setting, self::$settings ) ) {
                return get_option( self::$settings[$setting]['id'] );
            }

            return false;
        }
        

        public static function register_section_settings() {

            foreach( self::driftadm_get_settings() as $setting ) {

                $sanitize = null;

                if ( array_key_exists( 'sanitize_callback', $setting ) )
                    $sanitize = $setting['sanitize_callback'];

                register_setting(
                    self::$settings_slug, // group
                    $setting['id'],
                    $sanitize
                );

                $args = array_merge( $setting, array(
                    'label_for'   => 'hide',
                    'description' => $setting['description'],
                    'input_type'  => $setting['input_type']
                ) );

                if ( $setting['input_type'] === 'radio' ) {
                    $args['radio_choices'] = $setting['radio_choices'];
                }

                add_settings_field(
                    $setting['id'],
                    $setting['name'],
                    array( 'DriftADM_Settings', 'output_fields' ),
                    self::$settings_page_slug,
                    $setting['section'],
                    $args
                );
            }
        }


        /**
         * This is where all the field output logic happens
         * in a loop structure based on driftadm_get_settings()
         *
         * @param array $args - brings all the info required for the field to be displayed.
         */
        public static function output_fields( $args = array() ) {
            $input_type = $args['input_type'];
            $option_value = get_option( $args['id'] );

            $is_inline = $input_type === 'bool' ? 'inline-option' : '';

            if ( !$input_type )
                return;
            
            if ( $input_type === 'hidden' ){
                echo "<input id='{$args['id']}' type='hidden' value='{$option_value}' name='{$args['id']}'>";

                return;
            }

            echo "<div class='option {$is_inline}'>";

                echo '<div class="option-title">'.
                "<h3>{$args['name']}</h3>".
                "<p class='option-desc'>{$args['description']}</p>".
                '</div>';

            if ( $input_type === 'radio' ) {
                $radio_id = $args['id'];

                foreach ( $args['choices'] as $choice ) {
                    $choice_value = strtolower( $choice );
                    $checked = checked( $option_value, $choice_value, false );
                    echo "<div class='radio-option'><input value='{$choice_value}' name='{$radio_id}' id='{$radio_id}' type='radio' {$checked}>"
                    . "<label for='{$radio_id}'>{$choice}</label></div>";
                }
            } else if ( $input_type === 'text' ) {
                echo "<input value='{$option_value}' type='text' name='{$args['id']}'>";
            } else if( $input_type === 'bool' ) {
                $checked = checked( $option_value, 1, false );
                echo "<input class='checkbox-bool' name='{$args['id']}' {$checked} value='1' type='checkbox'>";
            } else if ( $input_type === 'upload_img' ) {
                self::output_login_bg( $args );
            } else if ( $input_type === 'login_logo' ) {
                self::output_login_logo( $args );
            }
            echo '</div>';
        }


        /**
         * Login background option template
         * 
         * @param array $args
         */
        private static function output_login_bg( $args ) { 
            
            $option_value = get_option( $args['id'] ); 
            $login_bg_type = get_option( 'login_bg_type' ); ?>

            <input type='hidden' name="<?= $args['id']; ?>" value="<?= $option_value; ?>">
            <div class="login-bg-container">

                <div class="choice-container media-upload-wrapper">
                    <div class="media-preview">
                        <img class="img-preview" src="<?= get_option( 'login_bg_img' ); ?>" width="145" height="100">
                    </div>
                    <input id="login_bg_img" class="media-hidden-value" type="hidden" value="<?= get_option( 'login_bg_img' ); ?>" name="login_bg_img">
                    <button class="driftadm-btn driftadm-upload-media-button" type="button"><?php _e( 'Upload image', 'drift-adm' ); ?></button>
                </div>

                <div class="hidden choice-container color-picker-wrapper">
                    <input autocomplete="off" value="<?= get_option( 'login_color' ); ?>" type="text" name="login_bg_color" id="login_bg_color_preview">
                    <input id="login_bg_color" type="hidden" value="<?= get_option( 'login_bg_color' ); ?>" name="login_bg_color">
                    <div id="color-preview"></div>
                </div>

                <div class="login-bg-choices">
                    <div class="radio-choice">
                        <input value='img' <?php checked( $login_bg_type, 'img', true ); ?> name="login_bg_type" type="radio" data-choice="media-upload-wrapper">
                        <label for="bg_type_image"><?php _e( 'Image', 'drift-adm' ); ?></label>
                    </div>

                    <div class="radio-choice">
                        <input value='color' <?php checked( $login_bg_type, 'color', true ); ?> name="login_bg_type" type="radio" data-choice="color-picker-wrapper">
                        <label for="bg_type_solid_color"><?php _e( 'Solid color', 'drift-adm' ); ?></label>
                    </div>
                </div>
            </div>
        <?php 
        }


        /**
         * Login logo option template
         * 
         * @param array args
         */
        private static function output_login_logo( $args ) {
            
            $empty_img_dir = DRIFTADM_ROOT_PATH . '/assets/img/empty_img.png'; ?>

            <div class="media-upload-wrapper">
                <input type="hidden" class="media-hidden-value" value="<?= get_option( $args['id'] ); ?>" name="<?= $args['id']; ?>" id="<?= $args['id']; ?>">
                <div class="media-preview">
                    <img onerror="this.src='<?= $empty_img_dir; ?>'" class="img-preview" src="<?= get_option( 'login_logo' ); ?>" width="145" height="100">
                </div>
                <button class="driftadm-btn driftadm-upload-media-button" type="button"><?php _e( 'Upload image', 'drift-adm' ); ?></button>
            </div>
        <?php
        }


        /**
         * Add the main settings page,
         * no need for changes here.
         */
        public static function create_settings_page() {
            add_options_page(
                __( 'Drift ADM - Settings', 'drift-adm' ),
                'Drift ADM',
                'manage_options',
                self::$settings_page_slug,
                array( 'DriftADM_Settings', 'output_settings_page' )
            );
        }


        /**
         * Main settings page view.
         */
        public static function output_settings_page() {
            include_once( dirname( __FILE__ ) . '/../views/driftadm-settings-screen.php' );
        }
    }
}
$e = new DriftADM_Settings;
//$set_defaults = $e->set_defaults();

<?php

defined( 'ABSPATH' ) || exit;

# require_once( dirname( __DIR__ )  . '/helpers/Validator.php' );
require_once( dirname( __FILE__ ) . '/DriftADM_Settings.php' );
require_once( dirname( __FILE__ ) . '/DriftADM_Login.php' );
require_once( dirname( __FILE__ ) . '/DriftADM_Admin.php' );


if ( ! class_exists( 'DriftADM_Loader' ) ) {

    class DriftADM_Loader extends DriftADM_Settings {

        public function __construct() {
            
            add_action( 'admin_init', array( __CLASS__, 'dequeue_wp_default_styles' ) );
            add_action( 'admin_enqueue_scripts', array( __CLASS__, 'spawn_assets' ) );
            add_action( 'admin_enqueue_scripts', array( __CLASS__, 'init_media_library' ) );
            add_action( 'plugins_loaded', array( __CLASS__, 'spawn_classes' ) );
            add_action( 'admin_menu', array( __CLASS__, 'create_settings_page' ) );
            add_action( 'plugins_loaded', array( $this, 'load_translations' ) );
            
            add_action( 'admin_init', array( __CLASS__, 'register_section_settings' ) );

            # instance classes based on page
            if ( is_admin() )
                new DriftADM_Admin;
            else if ( $GLOBALS['pagenow'] === 'wp-login.php' )
                new DriftADM_Login;
            
        }

        public static function dequeue_wp_default_styles() {

            $to_dequeue = array(
                'wp-admin',
                'buttons'
            );

            wp_deregister_style( $to_dequeue );
        }

        public static function init_media_library() {
            wp_enqueue_media();
            wp_enqueue_script( 'media-upload-js', DRIFTADM_ROOT_PATH . 'assets/js/media-selector.js', array( 'jquery' ), false, true );
            wp_localize_script( 'media-upload-js', 'buttons_text', array(
                'title' => __( 'Select a image to upload', 'drift-adm' ),
                'upload_btn' => __( 'Use this image', 'drift-adm' )
            ) );
        }

        public function spawn_classes() {
            require_once( dirname( __FILE__ ) . '/DriftADM_Settings.php' );
        }

        public function load_translations() {
            $path = basename( dirname( __DIR__ ) ) . '/languages';

            load_plugin_textdomain( 'drift-adm', false, $path );
        }

        public function spawn_assets() {
            /**
             * load these assets 
             * only on the settings page.
             */
            $screen = get_current_screen();
            if ( $screen->id === 'settings_page_drift-adm-settings' ) {

                # https://github.com/vdw/Tabslet
                wp_enqueue_script( 'jquery-tabslet', DRIFTADM_ROOT_PATH . 'vendor/jquery.tabslet.min.js' );

                # https://github.com/tovic/color-picker
                wp_enqueue_script( 'color-picker-js', DRIFTADM_ROOT_PATH . 'vendor/color-picker/color-picker.min.js' );
                wp_enqueue_style( 'color-picker-css', DRIFTADM_ROOT_PATH . 'vendor/color-picker/color-picker.min.css' );
                
                wp_enqueue_script( 'driftadm-settings-js', DRIFTADM_ROOT_PATH . 'assets/js/settings-page.js' );
                wp_enqueue_style( 'driftadm-settings-css', DRIFTADM_ROOT_PATH . 'assets/css/driftadm-settings.css' );
            }

            wp_enqueue_style( 'driftadm-main-css', DRIFTADM_ROOT_PATH . 'assets/css/driftadm-main.css' );
            wp_enqueue_script( 'driftadm-main-js', DRIFTADM_ROOT_PATH . 'assets/js/driftadm-main.js' );
        }
    }
}

<?php

defined( 'ABSPATH' ) || exit;

class DriftADM_Admin extends DriftADM_Settings {


    public function __construct() {

        add_action( 'show_admin_bar', array( $this, 'disable_wp_toolbar' ) );
        add_action( 'adminmenu', array( $this, 'display_custom_top_menu' ) );

        # footer
        add_filter( 'admin_footer_text', array( $this, 'disable_wp_footer' ), 99 );
        add_filter( 'update_footer', array( $this, 'disable_wp_footer' ), 99 );
    }
    

    /**
     * Disables the default WP toolbar from front-end
     */
    public function disable_wp_toolbar() {
        if ( DriftADM_Settings::driftadm_get_setting( 'disable_wptoolbar' ) )
            return false;

        return true;
    }

    /**
     * Displays the menu title on top
     * of the main wp menu.
     */
    public function display_menu_title() {
        $title = DriftADM_Settings::driftadm_get_setting( 'menu_title' );

        if ( $title ) {
            echo $title;
        } else {
           echo get_bloginfo( 'name' );
        }
    }

    /**
     * Adds a logout link button
     * to the wordpress menu
     */
    public function display_custom_top_menu() { ?>
        <div class="menu-custom-top">
            <div class="title">
                <h2><?= DriftADM_Settings::driftadm_get_setting( 'menu_title' ); ?></h2>
                <a href="<?= wp_logout_url(); ?>"><?php _e( 'Logout', 'drift-adm' ); ?></a>
            </div>

            <li class="wp-not-current-submenu menu-top menu-icon-comments menu-top-last" id="menu-comments">
                <a target="_blank" href="<?= get_site_url(); ?>" class="wp-not-current-submenu menu-top menu-icon-comments menu-top-last">
                    <div class="wp-menu-image dashicons-before dashicons-admin-site"></div>
                    <div class="wp-menu-name"><?php _e( 'Visit site' ); ?></div>
                </a>
            </li>
        </div>
    <?php 
    }

    /**
     * Disbles the welcome message and version
     * from the footer.
     * 
     * @param string $default
     */
    public function disable_wp_footer( $default ) {
        if ( DriftADM_Settings::driftadm_get_setting( 'disable_wpfooter' ) ) {
            return false;
        }

        return $default;
    }
}

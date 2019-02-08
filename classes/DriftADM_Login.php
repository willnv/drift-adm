<?php

class DriftADM_Login {
    
    private
        $login_bg_type,
        $login_bg_img,
        $login_bg_color;

    public function __construct() {
        $this->login_bg_type = get_option( 'login_bg_type' );
        $this->login_bg_img = get_option( 'login_bg_img' );
        $this->login_bg_color = get_option( 'login_bg_color' );

        add_action( 'login_headertitle', array( $this, 'login_template' ) );
        add_action( 'login_enqueue_scripts', array( $this, 'enqueue_login_scripts' ) );
        add_action( 'login_form', array( $this, 'display_login_title' ) );
    }

    public function display_login_title() { ?>
        <h2 class="form-title"><?php _e( 'Login', 'drift-adm' ); ?></h2>
    <?php
    }

    /**
     * Enqueue and localize scripts
     */
    public function enqueue_login_scripts() {
        wp_enqueue_style( 'adm-login-css', DRIFTADM_ROOT_PATH . 'assets/css/driftadm-login.css' );

        wp_enqueue_script( 'adm-login-js', DRIFTADM_ROOT_PATH . 'assets/js/login.js', '', false, true );
        wp_localize_script( 'adm-login-js', 'login_placeholders', array(
            'username' => __( 'Username or e-mail', 'drift-adm' ),
            'password' => __( 'Password', 'drift-adm' )
        ) );
    }

    /**
     * Displays the login image box aside
     * the login form on wp-login
     */
    public function login_template() {
        
        $bg_type_style = $this->login_bg_type === 'img' ? 
        "background-image:url('{$this->login_bg_img}')" : 
        "background-color:{$this->login_bg_color}"; ?>

        <div style="<?= $bg_type_style; ?>" class="login-bg-container">
            <?php if ( get_option( 'login_logo' ) ) { ?>
                <img src="<?= get_option( 'login_logo' ); ?>">
            <?php } ?>
        </div>
    <?php
    }
}

<div class="wrap">
    <h1><?php esc_html_e( get_admin_page_title() ); ?></h1>
        
    <form method="POST" id="driftadm-settings-form" action="options.php">

        <div class="tabs">
            <ul>
                <li><a href="#tab-1"><?php _e( 'Admin area', 'drift-adm' ); ?></a></li>
                <li><a href="#tab-2"><?php _e( 'Login page', 'drift-adm' ); ?></a></li>
            </ul>
            <div class="settings-content">
                <div class="tabs-container">

                    <div id="tab-1" class="tab-admin-area">
                        <?php do_settings_fields( 'drift-adm-settings', 'main_settings' ); ?>
                        <?php settings_fields( 'main-settings' ); ?>
                    </div>
                    
                    <div id="tab-2" class="tab-login-page">
                        <?php do_settings_fields( 'drift-adm-settings', 'login_settings' ); ?>         
                    </div>
                    
                </div>
                <div class="driftadm-settings-footer">
                    <?php submit_button( null, 'primary driftadm-submit-button', 'submit', false ); ?>
                </div>
            </div>
        </div>
    </form>
</div>


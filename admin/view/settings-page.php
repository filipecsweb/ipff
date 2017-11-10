<?php
if ( ! defined( 'WPINC' ) ) {
	die;
} ?>

<div class="wrap">
    <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

    <div class="notice-wrapper"></div>

    <h2 class="nav-tab-wrapper">
        <a href="#tab-general" class="nav-tab nav-tab-active"><?php _e( "General", 's-s' ); ?></a>
        <a href="#tab-advanced" class="nav-tab"><?php _e( "Advanced", 's-s' ); ?></a>
    </h2>

    <div id="poststuff">
        <div id="post-body" class="wp-clearfix metabox-holder columns-2">
            <div id="post-body-content">
                <form action="options.php" method="post">

					<?php settings_fields( 'ipff_settings' ); ?>

                    <div class="postbox">
                        <div id="tab-general" class="tabs-panel tabs-panel-active">
                            <section class="inside">
								<table class="form-table">
									<?php do_settings_fields( 'ipff_settings_page', 'user_section-first' ); ?>
                                </table>
                            </section>
                        </div>

                        <div id="tab-advanced" class="tabs-panel">
                            <section class="inside">

                            </section>
                        </div>
                    </div>

					<?php submit_button( null, 'primary', 'submit', false ); ?>
                </form>

            </div>
            <div id="postbox-container-1">
                <div class="postbox">
                    <section class="inside">

                    </section>
                </div>
            </div>
        </div>
    </div>
</div>
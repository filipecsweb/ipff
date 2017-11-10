<?php
/**
 * Class responsible for build plugin view page
 */

class Ipff_Settings {

	public function __construct() {

		$this->set_fields();

	}

	public function set_fields() {

		$fields = array(
			array(
				'args'     => array(),
				'callback' => array( $this, 'the_field__instagram_user' ),
				'id'       => 'instagram_user',
				'page'     => 'ipff_settings_page',
				'section'  => 'user_section-first',
				'title'    => __( 'Instagram Users', 'ipff' )
			)
		);

		foreach ( $fields as $field ) {

			add_settings_field(
				$field['id'],
				$field['title'],
				$field['callback'],
				$field['page'],
				$field['section'],
				array_merge(
					array(
						'id' => $field['id']
					),
					$field['args']
				)
			);

		}

	}

	/**
	 * @param   array $args Array of arguments:
	 *                          option_group
	 *                          option_name
	 *                          setting_args
	 *
	 * @see Ipff_Admin::init_settings_page()
	 */
	public function register( $args ) {

		register_setting( $args['option_group'], $args['option_name'], $args['setting_args'] );

	}

	public function the_field__instagram_user( $args ) {

		$id = $args['id'];

		$request_args = array(
			'back_to'        => Ipff_Admin::$instagram['back_to'],
			'parent_element' => 'instagram-user-0'
		);

		$request_args = json_encode( $request_args );
		$request_args = base64_encode( $request_args );

		$redirect_uri = Ipff_Admin::$instagram['redirect_uri'];
		$redirect_uri .= "?request_args=$request_args";

		$auth_url = Ipff_Admin::$instagram['auth_url'];
		$auth_url .= "&redirect_uri=$redirect_uri"; ?>

        <div id="instagram-user-0" class="instagram-user">
            <div class="username-wrapper">
                <label for="username-user-0">
                    <span><?php _e( 'Username', 'ipff' ); ?></span>
                    <input class="large-text" type="text"
                           id="username-user-0"
                           readonly
                           name="<?php echo "ipff_settings[$id][user-0][username]"; ?>"
                           value="<?php echo Ipff_Admin::get_option_value( array(
						       'key'        => 'ipff_settings',
						       'subindexes' => "$id,user-0,username"
					       ) ); ?>"/>
                </label>
            </div>
            <div class="token-wrapper">
                <label for="token-user-0">
                    <span>Token</span>
                    <input class="large-text" type="text"
                           id="token-user-0"
                           readonly
                           name="<?php echo "ipff_settings[$id][user-0][access_token]"; ?>"
                           value="<?php echo Ipff_Admin::get_option_value( array(
						       'key'        => 'ipff_settings',
						       'subindexes' => "$id,user-0,access_token"
					       ) ); ?>"/>
                </label>
            </div>
            <div class="btn-get-token-wrapper textright">
                <a href="<?php echo $auth_url; ?>" class="button-secondary btn-get-token">
                    <span><?php _e( 'Add user', 'ipff' ); ?> &raquo;</span>
                </a>
            </div>
        </div>

	<?php }

	public function sanitize__ipff_settings( $input ) {

		foreach ( $input as $k => $value ) {

			if ( is_array( $value ) ) {

				foreach ( $value as $i => $sec_value ) {

					if ( is_array( $sec_value ) ) {
						foreach ( $sec_value as $j => $third_value ) {
							$new_input[ $k ][ $i ][ $j ] = trim( $third_value );
						}
					} else {
						$new_input[ $k ][ $i ] = trim( $sec_value );
					}

				}

			} else {
				$new_input[ $k ] = trim( $value );
			}

		}

		return $new_input;

	}

}

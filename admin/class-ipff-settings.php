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
				'id'       => 'instagram_users',
				'args'     => array(),
				'callback' => array( $this, 'the_field__instagram_users' ),
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

	public function the_field__instagram_users( $args ) {

		$request_args = array(
			'back_to' => Ipff_Admin::$instagram['back_to'],
		);

		$request_args = json_encode( $request_args );
		$request_args = base64_encode( $request_args );

		$redirect_uri = Ipff_Admin::$instagram['redirect_uri'];
		$redirect_uri .= "?request_args=$request_args";

		$auth_url = Ipff_Admin::$instagram['auth_url'];
		$auth_url .= "&redirect_uri=$redirect_uri";

		require IPFF_PATH . "/admin/view/fields/instagram_users.php";

	}

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

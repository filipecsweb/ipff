<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       //seusobrinho.com.br
 * @since      1.0.0
 *
 * @package    Ipff
 * @subpackage Ipff/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package     Ipff
 * @since       1.0.0
 * @subpackage  Ipff/Admin
 * @author      Filipe Seabra <filipe@seusobrinho.com.br>
 */
class Ipff_Admin {

	/**
	 * @var     array $instagram Keeps data for user authentication purposes.
	 */
	public static $instagram;

	/**
	 * @var     array $options
	 */
	public static $options;

	public function __construct() {

		if ( ! is_admin() ) {
			return;
		}

		$this->set_instagram();
		$this->set_options();
		$this->set_admin_dependencies();

	}

	private function set_instagram() {

		$back_to = empty( $_SERVER['HTTPS'] ) ? 'http://' : 'https://';
		$back_to .= $_SERVER['HTTP_HOST'];
		$back_to .= strtok( $_SERVER['REQUEST_URI'], '&' );

		$redirect_uri = "https://seusobrinho.com.br/api/instagram/oauth.php";

		$auth_url = "https://api.instagram.com/oauth/authorize/?client_id=cd2a18ef4a3a4f9a91a81a7919183b31&response_type=code";

		self::$instagram = array(
			'auth_url'     => $auth_url,
			'back_to'      => $back_to,
			'redirect_uri' => $redirect_uri
		);

	}

	/**
	 * @access  private
	 */
	private function set_options() {
		self::$options = array(
			'ipff_settings' => get_option( 'ipff_settings' )
		);
	}

	/**
	 * @access  private
	 */
	private function set_admin_dependencies() {

		require IPFF_PATH . '/admin/class-ipff-menu.php';
		require IPFF_PATH . '/admin/class-ipff-settings.php';

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @access  public
	 * @see     Ipff::set_admin_actions()
	 * @since   1.0.0
	 */
	public function enqueue_admin_styles( $hook ) {

		if ( strpos( $hook, 'ipff_settings_page' ) === false ) {
			return;
		}

		wp_enqueue_style(
			'admin-' . IPFF_SLUG,
			IPFF_URL . 'admin/css/ipff-admin.css',
			array(),
			IPFF_VERSION,
			'all'
		);

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @access  public
	 * @see     Ipff::set_admin_actions()
	 * @since   1.0.0
	 */
	public function enqueue_admin_scripts( $hook ) {

		if ( strpos( $hook, 'ipff_settings_page' ) === false ) {
			return;
		}

		$handle = 'admin_' . IPFF_SLUG;

		wp_register_script(
			$handle,
			IPFF_URL . 'admin/js/ipff-admin.js',
			array( 'jquery' ),
			IPFF_VERSION,
			true
		);

		$l10n = array(
			'user_denied_app' => __( 'You did not authorize the app. Try again and be sure to authorize the app.',
				'ipff' )
		);

		wp_localize_script( $handle, "localized_$handle", $l10n );

		wp_enqueue_script( $handle );

	}

	/**
	 * @access  public
	 * @see     Ipff::set_admin_actions()
	 * @since   1.0.0
	 */
	public function set_admin_menu_items() {

		$admin_menu = new Ipff_Menu();

		$admin_menu->add(
			array(
				'menu_slug' => 'ipff_settings_page'
			)
		);

	}

	/**
	 * @access  public
	 * @see     Ipff::set_admin_actions()
	 * @since   1.0.0
	 */
	public function init_settings_page() {

		$settings = new Ipff_Settings();

		$settings->register(
			array(
				'option_group' => 'ipff_settings',
				'option_name'  => 'ipff_settings',
				'setting_args' => array(
					'sanitize_callback' => array( $settings, 'sanitize__ipff_settings' )
				)
			)
		);

	}

	/**
	 * This function retrieves a value given a key and subindexes separated by comma.
	 *
	 * @param   array $args Array of argumetns:
	 *                          key
	 *                          subindexes
	 *
	 * @return  mixed|string
	 */
	public static function get_option_value( $args ) {
		$defaults = array(
			'key'        => 'ipff_settings',
			'subindexes' => ''
		);

		$args = array_merge(
			$defaults,
			$args
		);

		$options = self::$options['ipff_settings'];

		$value = $options;

		if ( ! empty( $args['subindexes'] ) ) {
			$subindexes = explode( ',', $args['subindexes'] );

			foreach ( $subindexes as $subindex ) {
				if ( ! empty( $previous_value ) ) {
					$value = $previous_value[ $subindex ];
				} else {
					$value = $value[ $subindex ];
				}

				$previous_value = $value;
			}
		}

		return $value ? $value : '';
	}

}

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

	/**
	 * @since    1.0.0
	 */
	public function __construct() {

		if ( ! is_admin() ) {
			return;
		}

		$this->set_instagram();
		$this->set_options();
		$this->set_dependencies();

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
	private function set_dependencies() {

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

		$handles = array(
			array(
				'handle' => 'ss-tooltip',
				'src'    => IPFF_URL . 'admin/css/ss-tooltip.css',
				'deps'   => array(),
				'ver'    => '',
				'media'  => 'all'
			),
			array(
				'handle' => 'admin-' . IPFF_SLUG,
				'src'    => IPFF_URL . 'admin/css/ipff-admin.css',
				'deps'   => array(),
				'ver'    => IPFF_VERSION,
				'media'  => 'all'
			)
		);

		foreach ( $handles as $handle ) {
			wp_enqueue_style(
				$handle['handle'],
				$handle['src'],
				$handle['deps'],
				$handle['ver'],
				$handle['media']
			);
		}

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

		$l10n = array(
			0 => array(
				'denied'        => __( "You did not authorize the plugin. Retry and authorize it.", 'ipff' ),
				'reauthorized'  => __( "The Instagram user %s is here already. If you are trying to add another user, log out from Instagram.", 'ipff' ),
				'authorized'    => __( "The Instagram user %s was added. Please, save changes now.", 'ipff' ),
				'ipff_settings' => Ipff_Admin::$options['ipff_settings']
			)
		);

		$handles = array(
			array(
				'handle'    => 'ss-tooltip',
				'src'       => IPFF_URL . 'admin/js/ss-tooltip.js',
				'deps'      => array( 'jquery' ),
				'ver'       => '',
				'in_footer' => true
			),
			array(
				'handle'    => 'admin_' . IPFF_SLUG,
				'src'       => IPFF_URL . 'admin/js/ipff-admin.js',
				'deps'      => array( 'ss-tooltip' ),
				'ver'       => IPFF_VERSION,
				'in_footer' => true,
				'l10n'      => $l10n[0]
			),
		);

		foreach ( $handles as $handle ) {
			wp_register_script(
				$handle['handle'],
				$handle['src'],
				$handle['deps'],
				$handle['ver'],
				$handle['in_footer']
			);
		}

		foreach ( $handles as $handle ) {
			if ( ! empty( $handle['l10n'] ) ) {
				wp_localize_script( $handle['handle'], "localized_{$handle['handle']}", $handle['l10n'] );
			}
		}

		foreach ( $handles as $handle ) {
			wp_enqueue_script( $handle['handle'] );
		}

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

		$value = is_array( $options ) ? array_filter( $options ) : $options;

		if ( ! empty( $args['subindexes'] ) && $value ) {
			$subindexes = explode( ',', $args['subindexes'] );

			foreach ( $subindexes as $subindex ) {
				if ( empty( $value[ $subindex ] ) ) {
					continue;
				}

				$value = $value[ $subindex ];
			}
		}

		return is_array( $value ) || ! $value ? '' : $value;
	}

}

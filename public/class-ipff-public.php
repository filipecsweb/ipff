<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @package    Ipff
 * @subpackage Ipff/public
 * @author     Filipe Seabra <filipe@seusobrinho.com.br>
 */
class Ipff_Public {

	/**
	 * @var     array $options
	 */
	public static $options;

	/**
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->set_options();
		$this->set_dependencies();

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
	 * @since   1.0.0
	 */
	private function set_dependencies() {

		require IPFF_PATH . '/public/shortcodes/class-ipff-shortcode.php';

	}

	/**
	 * Register the stylesheets for the public area.
	 *
	 * @access  public
	 * @see     Ipff::set_public_actions()
	 * @since   1.0.0
	 */
	public function enqueue_public_styles() {

		wp_enqueue_style(
			'public-' . IPFF_SLUG,
			IPFF_URL . 'public/css/ipff-public.css',
			array(),
			IPFF_VERSION,
			'all'
		);

	}

	/**
	 * Register the JavaScript for the public area.
	 *
	 * @access  public
	 * @see     Ipff::set_public_actions()
	 * @since   1.0.0
	 */
	public function enqueue_public_scripts() {

//		$handle = 'public_' . IPFF_SLUG;
//
//		wp_register_script(
//			$handle,
//			IPFF_URL . 'public/js/ipff-public.js',
//			array( 'jquery' ),
//			IPFF_VERSION,
//			true
//		);
//
//		wp_enqueue_script( $handle );

	}

	/**
	 * @access  public
	 * @see     Ipff::set_public_actions()
	 */
	public function init_shortcodes() {
		$shortcodes = new Ipff_Shortcodes();

		$shortcodes->add();
	}

}

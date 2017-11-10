<?php

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * @since       1.0.0
 * @version     1.0.0
 * @package     Ipff
 * @subpackage  Ipff/Includes
 * @author      Filipe Seabra <filipe@seusobrinho.com.br>
 */
class Ipff {

	/**
	 * @var     object $instance
	 */
	protected static $instance = null;

	public function __construct() {

		$this->set_dependencies();
		$this->set_locale();
		$this->set_admin_actions();

	}

	public static function run() {

		if ( null == self::$instance ) {
			self::$instance = new self();
		}

	}

	public static function my_var_dump() {
		$args = func_get_args();

		ob_start();

		foreach ( $args as $arg ) {
			echo "<pre>";
			var_dump( $arg );
			echo "</pre>";
		}

		echo ob_get_clean();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_dependencies() {

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once IPFF_PATH . '/includes/class-ipff-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once IPFF_PATH . '/admin/class-ipff-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once IPFF_PATH . '/public/class-ipff-public.php';

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Ipff_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Ipff_i18n();

		add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	private function set_admin_actions() {

		$ipff_admin = new Ipff_Admin();

		/**
		 * @link    https://codex.wordpress.org/Plugin_API/Action_Reference/admin_enqueue_scripts
		 * @since   1.0.0
		 */
		add_action( 'admin_enqueue_scripts', array( $ipff_admin, 'enqueue_admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $ipff_admin, 'enqueue_admin_scripts' ) );

		/**
		 * the admin_menu action is called before admin_init.
		 *
		 * @link    https://codex.wordpress.org/Plugin_API/Action_Reference/admin_menu
		 * @since   1.0.0
		 */
		add_action( 'admin_menu', array( $ipff_admin, 'set_admin_menu_items' ) );

		/**
		 * @link    https://codex.wordpress.org/Plugin_API/Action_Reference/admin_init
		 * @since   1.0.0
		 */
		add_action( 'admin_init', array( $ipff_admin, 'init_settings_page' ) );

	}

}

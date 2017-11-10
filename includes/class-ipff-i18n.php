<?php

/**
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Ipff
 * @subpackage Ipff/Includes
 * @author     Filipe Seabra <filipe@seusobrinho.com.br>
 */
class Ipff_i18n {

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'ipff',
			false,
			IPFF_PATH . '/languages/'
		);

	}

}

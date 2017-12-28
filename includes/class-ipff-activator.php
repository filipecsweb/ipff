<?php

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Ipff
 * @subpackage Ipff/Includes
 * @author     Filipe Seabra <filipe@seusobrinho.com.br>
 */
class Ipff_Activator {

	/**
	 * @since    1.0.0
	 */
	public static function activate() {

		$ipff_settings = get_option( 'ipff_setting' );

		if ( ! $ipff_settings ) {
			add_option( 'ipff_settings', '' );
		}

	}

}

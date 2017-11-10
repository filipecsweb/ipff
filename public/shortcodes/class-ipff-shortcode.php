<?php
/**
 * Class responsible for creating all shortcodes for this plugin.
 *
 * @author      Filipe Seabra <filipe@seusobrinho.com.br>
 * @package     Ipff
 * @subpackage  Ipff/Public/Shortcodes
 * @since       1.0.0
 */

class Ipff_Shortcodes {

	/**
	 * @acess   public
	 * @since   1.0.0
	 */
	public function add() {
		$path = dirname( __FILE__ );

		$files = array_diff( scandir( $path ), array( '.', '..', basename( __FILE__ ) ) );

		foreach ( $files as $f ) {
			require_once $path . "/$f";

			$slug = substr( $f, 0, - 4 );

			add_shortcode( $slug, $slug );
		}
	}

}

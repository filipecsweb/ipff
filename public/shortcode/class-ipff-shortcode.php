<?php
/**
 * This file creates the responsible class for creating all plugin shortcodes
 *
 * @author      Filipe Seabra <filipe@seusobrinho.com.br>
 * @package     Ipff
 * @subpackage  Ipff/public/shortcode
 * @since       1.0.0
 */

class Ipff_Shortcode {

	/**
	 * @var     string $plugin_name
	 */
	public $plugin_name;

	/**
	 * @var     array $options The array of options coming from the plugin view page
	 */
	public $options;

	public function __construct( $plugin_name ) {

		$this->plugin_name = $plugin_name;

		$this->options = get_option( $this->plugin_name . '_options' );

		$tags = array(

			'instagram_feed' => 'instagram_feed',

		);

		foreach ( $tags as $function => $tag ) {

			$tag      = apply_filters( $tag . '_shortcode_tag', $tag );
			$function = $tag;

			add_shortcode( $tag, array( $this, $function ) );

		}

	}

	public function instagram_feed( $atts ) {

		$atts = shortcode_atts(
			array(
				'user'    => 1,
				'layout'  => '1',
				'qty'     => 10,
				'columns' => 5
			),
			$atts
		);

		extract( $atts );


		$user         = $this->options["ipff_user-$user"];
		$access_token = $user['access_token'];

		$feed = file_get_contents( "https://api.instagram.com/v1/users/self/media/recent/?access_token=$access_token" );
		$feed = json_decode( $feed );

//		echo "<pre>"; var_dump($feed); echo "</pre>";

		$class = "ipff-feed-wrapper ipff-clearfix _$layout-layout _$columns-columns ";

		$out = "<div class='$class' data-qty='$qty'>";

		foreach ( $feed->data as $k => $post ) {

			if ( $k >= $qty ) {
				break;
			}

//			$low = $post->images->low_resolution->url;
			$standard = $post->images->standard_resolution->url;
			$link     = $post->link;

//			$out .= "<div class='item' style='background-image: url($low);'><a class='link' href='$standard'></a></div>";
//			$out .= "<img class='item' src='$low' />";
			$out .= "<div class='item item-$k' style='background-image: url($standard);'>";
			$out .= "<a class='link' href='$link' target='_blank'></a>";
			$out .= "</div>";

		}

		$out .= "</div>";

		return $out;

	}

}
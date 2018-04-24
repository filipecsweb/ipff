<?php
/**
 * instagram_feed shortcode functions.
 *
 * @param   array $atts
 *
 * @return  string  $html   Feed.
 */
function instagram_feed( $atts ) {
	$atts = shortcode_atts(
		array(
			'id'     => 1,
			'layout' => '1',
			'count'  => 10,
			'cols'   => 5
		),
		$atts
	);

	return ipff_get_recent_media( $atts );
}

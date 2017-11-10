<?php
/**
 * instagram_feed shortcode funtion.
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

	extract( $atts );

	$access_token = Ipff_Public::$options['ipff_settings']['instagram_user']["user-$id"]['access_token'];

	$feed = file_get_contents( "https://api.instagram.com/v1/users/self/media/recent/?access_token=$access_token" );
	$feed = json_decode( $feed );

	$class = "instagram-feed-wrapper layout-$layout cols-$cols";

	ob_start(); ?>

    <div class='<?php echo $class ?>' data-count='<?php echo $count; ?>'>

		<div class="instagram-feed">
			<?php
			foreach ( $feed->data as $k => $post ) {

				if ( $k >= $count ) {
					break;
				}

				$standard = $post->images->standard_resolution->url;
				//        $low = $post->images->low_resolution->url;

				$link = $post->link; ?>

                <div class="item bg-wrapper">
                    <div class="bg-carrier" style="background-image: url('<?php echo $standard; ?>');"></div>
                    <a class="link-carrier" href="<?php echo $link; ?>" target="_blank"></a>
                </div>

			<?php } ?>
        </div>

    </div>

	<?php
    $html = ob_get_clean();

	return $html;

}

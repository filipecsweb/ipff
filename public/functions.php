<?php
/**
 * Public functions.
 *
 * @package    Ipff
 * @subpackage Public
 * @author     Filipe Seabra
 */

/**
 * Creates the cache file which stores the Instagram Recent Media HTML.
 * If the file does not exist it is created, otherwise it is overwritten.
 *
 * @link    http://php.net/manual/en/function.file-put-contents.php
 *
 * @param string $html
 * @param string $file The path for the cache file in which the HTML should be stored.
 */
function ipff_cache_recent_media( $html, $file = '' ) {
	if ( $file === '' ) {
		$file = plugin_dir_path( __FILE__ ) . 'cache/recent_media.cache';
	}

	$html = preg_replace( "/[\n\r]|\s\s+/", '', $html );

	if ( ! file_exists( dirname( $file ) ) ) {
		mkdir( dirname( $file ), 0755, true );
	}

	file_put_contents( $file, $html );
}

function ipff_get_recent_media( $args ) {
	$args = array_merge(
		array(
			'id'     => 1,
			'cols'   => 4,
			'count'  => 8,
			'layout' => 1,
		),
		$args
	);

	$id     = $args['id'];
	$cols   = $args['cols'];
	$count  = $args['count'];
	$layout = $args['layout'];

	$access_token = Ipff_Public::$options['ipff_settings']['instagram_users']["user-$id"]['token'];

	if ( empty( $access_token ) ) {
		return sprintf( __( "Nenhum token encontrado para o usuario %s.", 'ipff' ), $id );
	}

	$new_request = true;

	$recent_media_cache_file = plugin_dir_path( __FILE__ ) . 'cache/recent_media.cache';

	if ( file_exists( $recent_media_cache_file ) ) {
		$modified_time = (int) filemtime( $recent_media_cache_file );
		$current_time  = (int) time();

		$diff = $current_time - $modified_time;

		// Checks if the cache file has less than 15 minutes of existence.
		if ( $diff < 901 ) {
			$new_request = false;
		}
	}

	if ( $new_request ) {
		$recent_media = file_get_contents( "https://api.instagram.com/v1/users/self/media/recent/?access_token=$access_token" );
		$recent_media = json_decode( $recent_media );

		$wrapper_class = "instagram-feed-wrapper layout-$layout cols-$cols";

		$recent_media_data = $recent_media->data;

		ob_start(); ?>
        <div class='<?php echo $wrapper_class ?>' data-count='<?php echo $count; ?>'>
            <div class="instagram-feed">
				<?php
				foreach ( $recent_media_data as $k => $_post ) {
					if ( $k >= $count ) {
						break;
					}

					$standard_img_url = $_post->images->standard_resolution->url;
//					$low              = $_post->images->low_resolution->url;
					$post_link = $_post->link; ?>
                    <div class="item bg-wrapper">
                        <div class="bg-carrier" style="background-image: url('<?php echo $standard_img_url; ?>');"></div>
                        <a class="link-carrier" href="<?php echo $post_link; ?>" target="_blank" rel="nofollow"></a>
                    </div>
				<?php } ?>
            </div>
        </div>
		<?php
		$html = trim( ob_get_clean() );

		ipff_cache_recent_media( $html, $recent_media_cache_file );
	} else {
		$html = file_get_contents( $recent_media_cache_file );
	}

	return $html;
}

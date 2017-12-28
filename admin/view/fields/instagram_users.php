<?php
/**
 * @author      Filipe Seabra <filipe@seusobrinho.com.br>
 * @package     Ipff
 * @subpackage  Ipff/Admin/View/Fields
 * @since       1.0.0
 * @version     1.0.0
 */

/**
 * Prints the Auth Button.
 *
 * @param $args
 */
function ipff_the_auth_user_button( $args ) {
	$defaults = array(
		'text' => __( "Add your first user", 'ipff' ),
		'tag'  => 'a',
		'attr' => array()
	);

	$args = array_merge(
		$defaults,
		$args
	);

	$attr = array();

	foreach ( $args['attr'] as $key => $value ) {
		$attr[] = "$key='" . esc_attr( $value ) . "'";
	} ?>

    <div class="btn-auth-user-wrapper">
		<?php
		if ( $args['tag'] == 'a' ) { ?>
            <a <?php echo implode( ' ', $attr ); ?>>
                <span><?php echo $args['text']; ?> &raquo;</span>
            </a>
		<?php } else { ?>
            <button <?php echo implode( ' ', $attr ); ?>>
                <span><?php echo $args['text']; ?> &raquo;</span>
            </button>
		<?php } ?>
    </div>

<?php }

/**
 * Retrieves the Instagram User form field.
 *
 * @param   array $args
 *
 * @return  string  $html
 */
function ipff_get_instagram_user_field( $args ) {
	$defaults = array(
		'user_count' => '',
		'is_hidden'  => false
	);

	$args = array_merge(
		$defaults,
		$args
	);

	$user_count = $args['user_count'];

	$value__username = Ipff_Admin::get_option_value( array(
		'key'        => 'ipff_settings',
		'subindexes' => "instagram_users,user-$user_count,username"
	) );

	$value__id = Ipff_Admin::get_option_value( array(
		'key'        => 'ipff_settings',
		'subindexes' => "instagram_users,user-$user_count,id"
	) );

	$value__profile_picture = Ipff_Admin::get_option_value( array(
		'key'        => 'ipff_settings',
		'subindexes' => "instagram_users,user-$user_count,profile_picture"
	) );

	$value__token = Ipff_Admin::get_option_value( array(
		'key'        => 'ipff_settings',
		'subindexes' => "instagram_users,user-$user_count,token"
	) );

	$wrapper_class = "instagram-user";
	$wrapper_class .= $args['is_hidden'] ? " hidden" : "";

	ob_start(); ?>

    <div class="<?php echo $wrapper_class; ?>" data-user-count="<?php echo $user_count; ?>">
        <div class="profile-picture-wrapper">
            <img class="profile-picture" src="<?php echo $value__profile_picture; ?>"/>
        </div>
        <div class="username-wrapper">
            <input class="large-text" type="text"
                   readonly
                   name="<?php echo "ipff_settings[instagram_users][user-$user_count][username]"; ?>"
                   value="<?php echo $value__username; ?>"/>
        </div>
        <input type="hidden"
               name="<?php echo "ipff_settings[instagram_users][user-$user_count][id]"; ?>"
               value="<?php echo $value__id; ?>"/>
        <input type="hidden"
               name="<?php echo "ipff_settings[instagram_users][user-$user_count][profile_picture]"; ?>"
               value="<?php echo $value__profile_picture; ?>"/>
        <input type="hidden"
               name="<?php echo "ipff_settings[instagram_users][user-$user_count][token]"; ?>"
               value="<?php echo $value__token; ?>"/>
    </div>

	<?php
	$html = ob_get_clean();

	return $html;

}

$instagram_users = array();

if ( ! empty( Ipff_Admin::$options['ipff_settings']['instagram_users'] ) ) {
	$instagram_users = (array) Ipff_Admin::$options['ipff_settings']['instagram_users'];
}

$form_fields = array();

$user_count = 1;

foreach ( $instagram_users as $instagram_user ) {
	$form_fields[] = ipff_get_instagram_user_field( array(
		'user_count' => $user_count
	) );

	$user_count ++;
}

$btn_auth_user_args = array(
	'attr' => array(
		'href'  => $auth_url,
		'class' => 'button-secondary btn-auth-user'
	)
);

if ( $form_fields ) {
	$btn_auth_user_args['text'] = __( "Add another user", 'ipff' );
} else {
	$form_fields[] = ipff_get_instagram_user_field( array(
		'user_count' => 1,
		'is_hidden'  => true
	) );
} ?>

    <div class="instagram-users-wrapper">
		<?php foreach ( $form_fields as $form_field ) {
			echo $form_field;
		} ?>
    </div>
    <hr />

<?php
ipff_the_auth_user_button( $btn_auth_user_args );

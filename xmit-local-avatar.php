<?php
/**
 * Plugin Name:       Xmit Use Local Avatar
 * Plugin URI:        http://transmitstudio.com/
 * Description:       Overrides Gravatar with local avatar uploaded to media library.
 * Version:           1.0
 * Author:            Dave Kuhar
 * Author URI:        http://davekuhar.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       xmit-avatar
 * Domain Path:       /languages
 */
 
/** Based on
 * Use ACF image field as avatar
 * @author Mike Hemberger
 * @link http://thestizmedia.com/acf-pro-simple-local-avatars/
 * @uses ACF Pro image field (tested return value set as Array )
 */

add_filter('get_avatar', 'xbk5_acf_profile_avatar', 10, 5);
function xbk5_acf_profile_avatar( $avatar, $id_or_email, $size, $default, $alt ) {
    $user = '';
    
    // Get user by id or email
    if ( is_numeric( $id_or_email ) ) {
        $id   = (int) $id_or_email;
        $user = get_user_by( 'id' , $id );
    } elseif ( is_object( $id_or_email ) ) {
        if ( ! empty( $id_or_email->user_id ) ) {
            $id   = (int) $id_or_email->user_id;
            $user = get_user_by( 'id' , $id );
        }
    } else {
        $user = get_user_by( 'email', $id_or_email );
    }
    if ( ! $user ) {
        return $avatar;
    }
    // Get the user id
    $user_id = $user->ID;
    // Get the file id
    $image_id = get_user_meta($user_id, 'xbk5_local_avatar', true);
    // Bail if we don't have a local avatar
    if ( ! $image_id ) {
        return $avatar;
    }
    // Get the file size
    $image_url  = wp_get_attachment_image_src( $image_id, 'thumbnail' ); // Set image size by name
    // Get the file url
    $avatar_url = $image_url[0];
    // Get the img markup
    $avatar = '<img alt="' . $alt . '" src="' . $avatar_url . '" class="avatar avatar-' . $size . '" height="' . $size . '" width="' . $size . '"/>';
    // Return our new avatar
    return $avatar;
}
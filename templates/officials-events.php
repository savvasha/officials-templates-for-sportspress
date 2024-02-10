<?php
/**
 * Official Events.
 *
 * @author      savvasha
 * @package     OTFS/Templates
 * @version     1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
if ( get_option( 'sportspress_officials_show_events', 'yes' ) === 'no' ) {
	return;
}

if ( ! isset( $id ) ) {
	$id = get_the_ID();
}

$args = array(
    'post_type' => 'sp_event',
    'posts_per_page' => -1, // Retrieve all posts.
    'meta_query' => array(
        array(
            'key' => 'sp_officials',
            'value' => 'i:' . $id . ';', // Format the search string to match the serialized array.
            'compare' => 'REGEXP',
        ),
    ),
);
$events = get_posts($args);
//$events = new WP_Query($args);
var_dump($events);
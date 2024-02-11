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

//$official = new OTFS_Officials( $id );
//$events = $official->data();

$format = get_option( 'otfs_officials_events_format', 'list' );
if ( 'calendar' === $format ) {
	sp_get_template( 'event-calendar.php', array( 'official_id' => $id ), '', OTFS_PLUGIN_DIR . 'templates/' );
} elseif ( 'list' === $format ) {
	$args = array(
		'official_id'       => $id,
		'title_format' => 'homeaway',
		'time_format'  => 'separate',
		'columns'      => array( 'event', 'time', 'results' ),
		'order'        => 'DESC',
	);
	$args = apply_filters( 'otfs_official_events_list_args', $args );
	sp_get_template( 'event-list.php', $args, '', OTFS_PLUGIN_DIR . 'templates/' );
} else {
	sp_get_template( 'event-fixtures-results.php', array( 'official_id' => $id ), '', OTFS_PLUGIN_DIR . 'templates/' );
}
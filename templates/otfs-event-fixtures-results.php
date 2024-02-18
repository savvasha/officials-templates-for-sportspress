<?php
/**
 * Event Blocks
 *
 * @author      ThemeBoy
 * @package     SportsPress/Templates
 * @version     2.7.14
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$defaults = array(
	'official_id' => null,
	'number'      => -1,
	'link_teams'  => get_option( 'sportspress_link_teams', 'no' ) == 'yes' ? true : false,
	'link_events' => get_option( 'sportspress_link_events', 'yes' ) == 'yes' ? true : false,
	'paginated'   => get_option( 'sportspress_event_blocks_paginated', 'yes' ) == 'yes' ? true : false,
	'rows'        => get_option( 'sportspress_event_blocks_rows', 5 ),
	'show_league' => get_option( 'sportspress_event_blocks_show_league', 'no' ) == 'yes' ? true : false,
	'show_season' => get_option( 'sportspress_event_blocks_show_season', 'no' ) == 'yes' ? true : false,
	'show_venue'  => get_option( 'sportspress_event_blocks_show_venue', 'no' ) == 'yes' ? true : false,
);

extract( $defaults, EXTR_SKIP );

$args = array(
	'title'                => esc_attr__( 'Fixtures', 'sportspress' ),
	'status'               => 'future',
	'official_id'          => $official_id,
	'number'               => $number,
	'link_teams'           => $link_teams,
	'link_events'          => $link_events,
	'paginated'            => $paginated,
	'rows'                 => $rows,
	'order'                => 'ASC',
	'show_all_events_link' => false,
	'show_title'           => true,
	'show_league'          => $show_league,
	'show_season'          => $show_season,
	'show_venue'           => $show_venue,
	'hide_if_empty'        => true,
);

echo '<div class="sp-fixtures-results">';
ob_start();
sp_get_template( 'otfs-event-blocks.php', $args, '', OTFS_PLUGIN_DIR . 'templates/' );
$fixtures = ob_get_clean();

$args['title']  = esc_attr__( 'Results', 'sportspress' );
$args['status'] = 'publish';
$args['order']  = 'DESC';

ob_start();
sp_get_template( 'otfs-event-blocks.php', $args, '', OTFS_PLUGIN_DIR . 'templates/' );
$results = ob_get_clean();

if ( false == $fixtures || false == $results ) {

	echo $fixtures; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	echo $results; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

} else {

	echo '<div class="sp-widget-align-left">';
	echo $fixtures; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	echo '</div>';

	echo '<div class="sp-widget-align-right">';
	echo $results; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	echo '</div>';
}

echo '</div>';

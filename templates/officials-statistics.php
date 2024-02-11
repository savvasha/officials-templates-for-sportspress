<?php
/**
 * Official Statistics.
 *
 * @author      savvasha
 * @package     OTFS/Templates
 * @version     1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
if ( get_option( 'sportspress_officials_show_statistics', 'yes' ) === 'no' ) {
	return;
}

if ( ! isset( $id ) ) {
	$id = get_the_ID();
}

$official = new OTFS_Officials( $id );
//var_dump($official->stats());

$scrollable         = get_option( 'sportspress_enable_scrollable_tables', 'yes' ) == 'yes' ? true : false;
//$show_career_totals = 'yes' === get_option( 'sportspress_player_show_career_total', 'no' ) ? true : false;
$show_career_totals = false;
//$sections           = get_option( 'sportspress_player_performance_sections', -1 );
$leagues            = array_filter( (array) get_the_terms( $id, 'sp_league' ) );

// Sort Leagues by User Defined Order (PHP5.2 supported)
foreach ( $leagues as $key => $league ) {
	$leagues[ $key ]->sp_order = get_term_meta( $league->term_id, 'sp_order', true );
}
if ( ! function_exists( 'sortByOrder' ) ) {
	function sortByOrder( $a, $b ) {
		return (int) $a->sp_order - (int) $b->sp_order;
	}
}
usort( $leagues, 'sortByOrder' );

$duties       = $official->duties();
$player_sections = array();

// Determine order of sections
$section_order = array( -1 => null );

// Loop through statistics for each league
if ( is_array( $leagues ) ) :
	foreach ( $section_order as $section_id => $section_label ) {
		if ( -1 !== $section_id && ! empty( $player_sections ) && ! in_array( $section_id, $player_sections ) ) {
			continue;
		}

		if ( sizeof( $leagues ) > 1 ) {
			printf( '<h3 class="sp-post-caption sp-player-statistics-section">%s</h3>', wp_kses_post( $section_label ) );
		}

		foreach ( $leagues as $league ) :
			$caption = $league->name;

			if ( null !== $section_label ) {
				if ( sizeof( $leagues ) === 1 ) {
					$caption = $section_label;
				}
			}

			$args = array(
				'data'       => $official->stats( $league->term_id, false, $section_id ),
				'caption'    => $caption,
				'scrollable' => $scrollable,
				'league_id'  => $league->term_id,
				'hide_teams' => true,
			);
			sp_get_template( 'player-statistics-league.php', $args );
		endforeach;

		if ( $show_career_totals ) {
			sp_get_template(
				'player-statistics-league.php',
				array(
					'data'       => $official->stats( 0, false, $section_id ),
					'caption'    => esc_attr__( 'Career Total', 'sportspress' ),
					'scrollable' => $scrollable,
					'hide_teams' => true,
				)
			);
		}
	}
endif;

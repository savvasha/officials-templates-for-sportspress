<?php
/**
 * Event Calendar
 *
 * @author      ThemeBoy
 * @package     SportsPress/Templates
 * @version   2.7.9
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $wpdb, $m, $monthnum, $year, $wp_locale;

$official_id          = null;
$initial              = true;
$caption_tag          = 'caption';
$override_global_date = false;
$today                = current_time( 'mysql' );

$official = new OTFS_Officials( $official_id );

if ( $override_global_date ) {
	$year     = gmdate( 'Y', strtotime( $today ) );
	$monthnum = gmdate( 'm', strtotime( $today ) );
}
$events = $official->events();

if ( empty( $events ) ) {
	$in = 'AND 1 = 0'; // False logic to prevent SQL error
} else {
	$event_ids = wp_list_pluck( $events, 'ID' );
	$in        = 'AND ID IN (' . implode( ', ', $event_ids ) . ')';
}

// week_begins = 0 stands for Sunday
$week_begins = intval( get_option( 'start_of_week' ) );

// Get year and month from query vars
$year     = isset( $_GET['sp_year'] ) ? sanitize_text_field( wp_unslash( $_GET['sp_year'] ) ) : $year;
$monthnum = isset( $_GET['sp_month'] ) ? sanitize_text_field( wp_unslash( $_GET['sp_month'] ) ) : $monthnum;

// Let's figure out when we are
if ( ! empty( $monthnum ) && ! empty( $year ) ) {
	$thismonth = '' . zeroise( intval( $monthnum ), 2 );
	$thisyear  = '' . intval( $year );
} elseif ( ! empty( $w ) ) {
	// We need to get the month from MySQL
	$thisyear  = '' . intval( substr( $m, 0, 4 ) );
	$d         = ( ( $w - 1 ) * 7 ) + 6; // it seems MySQL's weeks disagree with PHP's
	$thismonth = $wpdb->get_var( "SELECT DATE_FORMAT((DATE_ADD('{$thisyear}0101', INTERVAL $d DAY) ), '%m')" );
} elseif ( ! empty( $m ) ) {
	$thisyear = '' . intval( substr( $m, 0, 4 ) );
	if ( strlen( $m ) < 6 ) {
			$thismonth = '01';
	} else {
		$thismonth = '' . zeroise( intval( substr( $m, 4, 2 ) ), 2 );
	}
} else {
	$thisyear  = gmdate( 'Y', strtotime( $today ) );
	$thismonth = gmdate( 'm', strtotime( $today ) );
}

$unixmonth = mktime( 0, 0, 0, $thismonth, 1, $thisyear );
$last_day  = gmdate( 't', $unixmonth );

// Get the next and previous month and year with at least one post
$previous = $wpdb->get_row(
	"SELECT MONTH(post_date) AS month, YEAR(post_date) AS year
	FROM $wpdb->posts
	WHERE post_date < '$thisyear-$thismonth-01'
	AND post_type = 'sp_event' AND ( post_status = 'publish' OR post_status = 'future' )
	$in
		ORDER BY post_date DESC
		LIMIT 1"
);
$next     = $wpdb->get_row(
	"SELECT MONTH(post_date) AS month, YEAR(post_date) AS year
	FROM $wpdb->posts
	WHERE post_date > '$thisyear-$thismonth-{$last_day} 23:59:59'
	AND post_type = 'sp_event' AND ( post_status = 'publish' OR post_status = 'future' )
	$in
		ORDER BY post_date ASC
		LIMIT 1"
);

/* translators: Calendar caption: 1: month name, 2: 4-digit year */
$calendar_caption = _x( '%1$s %2$s', 'calendar caption', 'sportspress' );
$calendar_output  = '
<div class="sp-calendar-wrapper">
<table id="wp-calendar" class="sp-calendar sp-event-calendar sp-data-table">
<caption class="sp-table-caption">' . ( 'caption' === $caption_tag ? '' : '<' . $caption_tag . '>' ) . sprintf( $calendar_caption, $wp_locale->get_month( $thismonth ), gmdate( 'Y', $unixmonth ) ) . ( 'caption' === $caption_tag ? '' : '</' . $caption_tag . '>' ) . '</caption>
<thead>
<tr>';

$myweek = array();

for ( $wdcount = 0; $wdcount <= 6; $wdcount++ ) {
	$myweek[] = $wp_locale->get_weekday( ( $wdcount + $week_begins ) % 7 );
}

foreach ( $myweek as $wd ) {
	$day_name         = ( true === $initial ) ? $wp_locale->get_weekday_initial( $wd ) : $wp_locale->get_weekday_abbrev( $wd );
	$wd               = esc_attr( $wd );
	$calendar_output .= "\n\t\t<th scope=\"col\" title=\"$wd\">$day_name</th>";
}

$calendar_output .= '
</tr>
</thead>

<tfoot>
<tr>';

if ( $previous ) {
	$calendar_output .= "\n\t\t" . '<td colspan="3" id="prev" class="sp-previous-month"><a data-tooltip data-options="disable_for_touch:true" class="has-tooltip tip-right" href="' . add_query_arg(
		array(
			'sp_year'  => $previous->year,
			'sp_month' => $previous->month,
		)
	) . '" title="' . esc_attr( sprintf( _x( '%1$s %2$s', 'calendar caption', 'sportspress' ), $wp_locale->get_month( $previous->month ), gmdate( 'Y', mktime( 0, 0, 0, $previous->month, 1, $previous->year ) ) ) ) . '">&laquo; ' . $wp_locale->get_month_abbrev( $wp_locale->get_month( $previous->month ) ) . '</a></td>';
} else {
	$calendar_output .= "\n\t\t" . '<td colspan="3" id="prev" class="pad">&nbsp;</td>';
}

$calendar_output .= "\n\t\t" . '<td class="pad">&nbsp;</td>';

if ( $next ) {
	$calendar_output .= "\n\t\t" . '<td colspan="3" id="next" class="sp-next-month"><a data-tooltip data-options="disable_for_touch:true" class="has-tooltip tip-left" href="' . add_query_arg(
		array(
			'sp_year'  => $next->year,
			'sp_month' => $next->month,
		)
	) . '" title="' . esc_attr( sprintf( _x( '%1$s %2$s', 'calendar caption', 'sportspress' ), $wp_locale->get_month( $next->month ), gmdate( 'Y', mktime( 0, 0, 0, $next->month, 1, $next->year ) ) ) ) . '">' . $wp_locale->get_month_abbrev( $wp_locale->get_month( $next->month ) ) . ' &raquo;</a></td>';
} else {
	$calendar_output .= "\n\t\t" . '<td colspan="3" id="next" class="pad">&nbsp;</td>';
}

$calendar_output .= '
</tr>
</tfoot>

<tbody>
<tr>';

// Get days with posts
$dayswithposts = $wpdb->get_results(
	"SELECT DAYOFMONTH(post_date), ID
	FROM $wpdb->posts WHERE post_date >= '{$thisyear}-{$thismonth}-01 00:00:00'
	AND post_type = 'sp_event' AND ( post_status = 'publish' OR post_status = 'future' )
	$in
	AND post_date <= '{$thisyear}-{$thismonth}-{$last_day} 23:59:59'",
	ARRAY_N
);
if ( $dayswithposts ) {
	foreach ( (array) $dayswithposts as $daywith ) {
		$daywithpost[ $daywith[0] ][] = $daywith[1];
	}
} else {
	$daywithpost = array();
}

if ( array_key_exists( 'HTTP_USER_AGENT', $_SERVER ) && preg_match( '/(MSIE|camino|safari)/', wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) ) ) { // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
	$ak_title_separator = "\n";
} else {
	$ak_title_separator = ', ';
}

$ak_titles_for_day = array();
$ak_post_titles    = $wpdb->get_results(
	'SELECT ID, post_title, post_date, DAYOFMONTH(post_date) as dom '
	. "FROM $wpdb->posts "
	. "WHERE post_date >= '{$thisyear}-{$thismonth}-01 00:00:00' "
	. "AND post_date <= '{$thisyear}-{$thismonth}-{$last_day} 23:59:59' "
	. "AND post_type = 'sp_event' AND ( post_status = 'publish' OR post_status = 'future' ) "
	. "$in"
);
if ( $ak_post_titles ) {
	foreach ( (array) $ak_post_titles as $ak_post_title ) {

			/** This filter is documented in wp-includes/post-template.php */
			$post_title = esc_attr( apply_filters( 'the_title', $ak_post_title->post_title, $ak_post_title->ID ) . ' @ ' . apply_filters( 'sportspress_event_time', date_i18n( get_option( 'time_format' ), strtotime( $ak_post_title->post_date ) ), $ak_post_title->ID ) );

		if ( empty( $ak_titles_for_day[ 'day_' . $ak_post_title->dom ] ) ) {
			$ak_titles_for_day[ 'day_' . $ak_post_title->dom ] = '';
		}
		if ( empty( $ak_titles_for_day[ "$ak_post_title->dom" ] ) ) { // first one
			$ak_titles_for_day[ "$ak_post_title->dom" ] = $post_title;
		} else {
			$ak_titles_for_day[ "$ak_post_title->dom" ] .= $ak_title_separator . $post_title;
		}
	}
}

// See how much we should pad in the beginning
$pad = calendar_week_mod( gmdate( 'w', $unixmonth ) - $week_begins );
if ( 0 !== $pad ) {
	$calendar_output .= "\n\t\t" . '<td colspan="' . esc_attr( $pad ) . '" class="pad">&nbsp;</td>';
}

$daysinmonth = intval( gmdate( 't', $unixmonth ) );
for ( $day = 1; $day <= $daysinmonth; ++$day ) {
	if ( isset( $newrow ) && $newrow ) {
		$calendar_output .= "\n\t</tr>\n\t<tr>\n\t\t";
	}
	$newrow = false;

	$day_has_posts = array_key_exists( $day, $daywithpost );
	$td_properties = '';

	if ( gmdate( 'j', strtotime( $today ) ) === $day && gmdate( 'm', strtotime( $today ) ) === $thismonth && gmdate( 'Y', strtotime( $today ) ) === $thisyear ) {
		$td_properties .= ' id="today" class="sp-highlight"';
	}

	if ( $day_has_posts ) {
		$td_properties .= ' itemscope itemtype="http://schema.org/SportsEvent"';
	}

	$calendar_output .= '<td' . $td_properties . '>';

	if ( $day_has_posts ) { // any posts today?
		$calendar_output .= '<a data-tooltip data-options="disable_for_touch:true" class="has-tip" href="' . ( sizeof( $daywithpost[ $day ] ) > 1 ? add_query_arg( array( 'post_type' => 'sp_event' ), get_day_link( $thisyear, $thismonth, $day ) ) . '" title="' . sprintf( esc_attr__( '%s events', 'sportspress' ), ( sizeof( $daywithpost[ $day ] ) ) ) : get_post_permalink( $daywithpost[ $day ][0], false, true ) . '" title="' . esc_attr( $ak_titles_for_day[ $day ] ) ) . "\" itemprop=\"url\">$day</a>";
	} else {
		$calendar_output .= $day;
	}
	$calendar_output .= '</td>';

	if ( 6 === (int) calendar_week_mod( gmdate( 'w', mktime( 0, 0, 0, $thismonth, $day, $thisyear ) ) - $week_begins ) ) {
		$newrow = true;
	}
}

$pad = 7 - calendar_week_mod( gmdate( 'w', mktime( 0, 0, 0, $thismonth, $day, $thisyear ) ) - $week_begins );
if ( 0 !== $pad && 7 !== $pad ) {
	$calendar_output .= "\n\t\t" . '<td class="pad" colspan="' . esc_attr( $pad ) . '">&nbsp;</td>';
}

$calendar_output .= "\n\t</tr>\n\t</tbody>\n\t</table>\n\t</div>";
?>
<div class="sp-template sp-template-event-calendar">
	<?php echo wp_kses_post( $calendar_output ); ?>
</div>

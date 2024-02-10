<?php
/**
 * Official Details
 *
 * @author      savvasha
 * @package     OTFS/Templates
 * @version     1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
if ( get_option( 'sportspress_officials_show_details', 'yes' ) === 'no' ) {
	return;
}

if ( ! isset( $id ) ) {
	$id = get_the_ID();
}

$defaults = array(
	'show_name'              => get_option( 'sportspress_officials_show_name', 'no' ) == 'yes' ? true : false,
	'show_duties'         => get_option( 'sportspress_officials_show_duties', 'yes' ) == 'yes' ? true : false,
	'show_nationality'       => get_option( 'sportspress_officials_show_nationality', 'yes' ) == 'yes' ? true : false,
	'show_nationality_flags' => get_option( 'sportspress_officials_show_flags', 'yes' ) == 'yes' ? true : false,
	'show_birthday' => get_option( 'sportspress_officials_show_birthday', 'yes' ) == 'yes' ? true : false,
	'show_age' => get_option( 'sportspress_officials_show_age', 'yes' ) == 'yes' ? true : false,
);

extract( $defaults, EXTR_SKIP );

$official = new OTFS_Templates( $id );
$nationalities = $official->nationalities( $id );

$data = array();
$common = array();

if ( $show_name ) :
	$data[ esc_attr__( 'Name', 'sportspress' ) ] = get_the_title( $id );
endif;

if ( $show_nationality && $nationalities && is_array( $nationalities ) ) :
	$countries = SP()->countries->countries;
	$values = array();
	foreach ( $nationalities as $nationality ) :
		if ( 2 == strlen( $nationality ) ) :
			$legacy      = SP()->countries->legacy;
			$nationality = strtolower( $nationality );
			$nationality = sp_array_value( $legacy, $nationality, null );
		endif;
		$country_name = sp_array_value( $countries, $nationality, null );
		$values[]     = $country_name ? ( $show_nationality_flags ? sp_flags( $nationality ) : '' ) . $country_name : '&mdash;';
	endforeach;
	$data[ esc_attr__( 'Nationality', 'sportspress' ) ] = implode( '<br>', $values );
endif;

if ( $show_duties ) :
	$duties = get_the_terms( $id, 'sp_duty' );
		if ( $duties ) {
			usort( $duties, 'sp_sort_terms' );
		}
	if ( $duties && is_array( $duties ) ) :
		$duty_names = array();
		foreach ( $duties as $duty ) :
			$duty_names[] = $duty->name;
		endforeach;
		$data[ esc_attr__( 'Duty', 'sportspress' ) ] = implode( ', ', $duty_names );
	endif;
endif;

if ( $show_birthday ) {
	$data[ esc_attr__( 'Birthday', 'sportspress' ) ] = get_the_date( get_option( 'date_format' ), $id );
}
			
if ( $show_age ) {
	$sp_birthday_functions = new SportsPress_Birthdays();
	$data[ esc_attr__( 'Age', 'sportspress' ) ] = $sp_birthday_functions->get_age( get_the_date( 'm-d-Y', $id ) );
}

$data = apply_filters( 'sportspress_officials_details', $data, $id );

if ( empty( $data ) ) {
	return;
}

$output = '<div class="sp-list-wrapper">' .
	'<dl class="sp-official-details">';

foreach ( $data as $label => $value ) :

	$output .= '<dt>' . $label . '</dt><dd>' . $value . '</dd>';

endforeach;

$output .= '</dl></div>';
?>
<div class="sp-template sp-template-official-details sp-template-details">
	<?php echo wp_kses_post( $output ); ?>
</div>

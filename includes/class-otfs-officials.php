<?php
/**
 * Officials Class
 *
 * The OTFS officials class handles individual official data.
 *
 * @class       OTFS_Officials
 * @version     1.0.0
 * @package     OTFS/Classes
 * @category    Class
 * @author      SavvasHa
 */
class OTFS_Officials extends SP_Custom_Post {

	/**
	 * Returns duties sorted by `sp_order`.
	 *
	 * @access public
	 * @return array
	 */
	public function duties() {
		return $this->get_terms_sorted_by_sp_order( 'sp_duty' );
	}

	/**
	 * Returns nationalities
	 *
	 * @access public
	 * @return array
	 */
	public function nationalities() {
		$nationalities = get_post_meta( $this->ID, 'sp_nationality', true );
		if ( empty( $nationalities ) ) {
			return array();
		}
		foreach ( $nationalities as $nationality ) :
			if ( 2 == strlen( $nationality ) ) :
				$legacy      = SP()->countries->legacy;
				$nationality = strtolower( $nationality );
				$nationality = sp_array_value( $legacy, $nationality, null );
			endif;
		endforeach;
		return $nationalities;
	}

	/**
	 * Returns formatted data
	 *
	 * @access public
	 * @return array
	 */
	public function data() {
	}

}

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

	/** @var string The events status. */
	public $status = 'any';

	/** @var string The events ordering. */
	public $order = 'DESC';

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
	 * Returns formatted events
	 *
	 * @access public
	 * @return array
	 */
	public function events() {
		$args = array(
				    'post_type' => 'sp_event',
				    'posts_per_page' => -1, // Retrieve all posts.
				    'post_status' => array( 'publish', 'future' ),
				    'order' => $this->order,
				    'meta_query' => array(
				        array(
				            'key' => 'sp_officials',
				            'value' => 'i:' . $this->ID . ';', // Format the search string to match the serialized array.
				            'compare' => 'REGEXP',
				        ),
				    ),
				);
		if ( 'publish' == $this->status ) {
			$args['post_status']    = 'publish';
		}
		if ( 'future' == $this->status ) {
			$args['post_status']    = 'future';
		}
		$events = get_posts( $args );

		return $events;
	}

}

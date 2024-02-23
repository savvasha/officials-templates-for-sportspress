<?php
/**
 * Official Content
 *
 * @author      savvasha
 * @package     OTFS/Templates
 * @version     1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
if ( get_option( 'sportspress_officials_show_content', 'yes' ) === 'no' ) {
	return;
}

if ( ! isset( $id ) ) {
	$id = get_the_ID();
}

$post    = get_post( $id );
$content = $post->post_content;
if ( $content ) {
	?>
	<div class="sp-post-content">
		<?php echo wp_kses_post( apply_filters( 'the_content', $content ) ); ?>
	</div>
	<?php
}

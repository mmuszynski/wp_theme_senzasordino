<?php
/**
 * Lays out the current query as a newspaper front: a lead story with a rail of
 * top stories beside it, followed by a multi-column grid of the remaining posts.
 *
 * @package WordPress
 * @subpackage senzasordino
 * @since senzasordino 1.1
 */

global $wp_query;

$all_posts  = $wp_query->posts;
$rail_size  = 3;
$lead_posts = array();
$rail_posts = array();

if ( ! is_paged() && count( $all_posts ) > 1 ) {
	$lead_posts = array_slice( $all_posts, 0, 1 );
	$rail_posts = array_slice( $all_posts, 1, $rail_size );
	$all_posts  = array_slice( $all_posts, 1 + $rail_size );
}

$senzasordino_render = function ( $posts, $variant ) {
	global $post;
	foreach ( $posts as $post ) { // phpcs:ignore WordPress.WP.GlobalVariablesOverride
		setup_postdata( $post );
		get_template_part( 'template-parts/content/content', 'card', array( 'variant' => $variant ) );
	}
	wp_reset_postdata();
};
?>

<?php if ( $lead_posts ) : ?>
	<section class="news-top">
		<div class="news-lead">
			<?php $senzasordino_render( $lead_posts, 'lead' ); ?>
		</div>
		<?php if ( $rail_posts ) : ?>
			<aside class="news-rail" aria-label="<?php esc_attr_e( 'Top stories', 'senzasordino' ); ?>">
				<h2 class="section-heading"><?php esc_html_e( 'Top Stories', 'senzasordino' ); ?></h2>
				<?php $senzasordino_render( $rail_posts, 'rail' ); ?>
			</aside>
		<?php endif; ?>
	</section>
<?php endif; ?>

<?php if ( $all_posts ) : ?>
	<section class="news-more">
		<?php if ( $lead_posts ) : ?>
			<h2 class="section-heading"><?php esc_html_e( 'More Stories', 'senzasordino' ); ?></h2>
		<?php endif; ?>
		<div class="news-grid">
			<?php $senzasordino_render( $all_posts, 'standard' ); ?>
		</div>
	</section>
<?php endif; ?>

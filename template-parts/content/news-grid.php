<?php
/**
 * Lays out the current query as a newspaper front: a lead story with a rail of
 * top stories beside it, followed by a multi-column grid of the remaining posts.
 *
 * Accepts $args['query'] (WP_Query); defaults to the main query.
 *
 * @package WordPress
 * @subpackage senzasordino
 * @since senzasordino 1.1
 */

$grid_query = isset( $args['query'] ) ? $args['query'] : $GLOBALS['wp_query'];
$rail_size  = 3;
$lead_count = 0;
$rail_count = 0;

if ( ! is_paged() && $grid_query->post_count > 1 ) {
	$lead_count = 1;
	$rail_count = min( $rail_size, $grid_query->post_count - 1 );
}
$more_count = $grid_query->post_count - $lead_count - $rail_count;

$senzasordino_render = function ( $count, $variant ) use ( $grid_query ) {
	for ( $i = 0; $i < $count && $grid_query->have_posts(); $i++ ) {
		$grid_query->the_post();
		get_template_part( 'template-parts/content/content', 'card', array( 'variant' => $variant ) );
	}
};
?>

<?php if ( $lead_count ) : ?>
	<section class="news-top">
		<div class="news-lead">
			<?php $senzasordino_render( $lead_count, 'lead' ); ?>
		</div>
		<?php if ( $rail_count ) : ?>
			<aside class="news-rail" aria-label="<?php esc_attr_e( 'More stories', 'senzasordino' ); ?>">
				<h2 class="section-heading"><?php esc_html_e( 'More Stories', 'senzasordino' ); ?></h2>
				<?php $senzasordino_render( $rail_count, 'rail' ); ?>
			</aside>
		<?php endif; ?>
	</section>
<?php endif; ?>

<?php if ( $more_count > 0 ) : ?>
	<section class="news-more">
		<div class="news-grid">
			<?php $senzasordino_render( $more_count, 'standard' ); ?>
		</div>
	</section>
<?php endif; ?>

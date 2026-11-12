<?php
/**
 * Story card used on the front page and archive grids.
 *
 * Accepts $args['variant']: 'lead', 'rail' or 'standard'.
 *
 * @package WordPress
 * @subpackage senzasordino
 * @since senzasordino 1.1
 */

$variant    = isset( $args['variant'] ) ? $args['variant'] : 'standard';
$deck_words = array(
	'lead'     => 60,
	'rail'     => 18,
	'standard' => 32,
);
$show_image = twenty_twenty_one_can_show_post_thumbnail() && 'standard' !== $variant;
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'story story-' . $variant ); ?>>
	<?php if ( $show_image && 'lead' === $variant ) : ?>
		<a class="story-image" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
			<?php the_post_thumbnail( 'large' ); ?>
		</a>
	<?php endif; ?>

	<?php senzasordino_the_kicker(); ?>

	<h2 class="story-headline"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>

	<?php if ( $show_image && 'rail' === $variant ) : ?>
		<a class="story-thumb" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
			<?php the_post_thumbnail( 'thumbnail' ); ?>
		</a>
	<?php endif; ?>

	<p class="story-deck"><?php echo esc_html( senzasordino_get_deck( $deck_words[ $variant ] ) ); ?></p>

	<?php if ( 'lead' === $variant ) : ?>
		<p class="story-byline"><?php senzasordino_the_compact_byline(); ?></p>
		<a class="story-more" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Continue reading', 'senzasordino' ); ?> &rarr;</a>
	<?php endif; ?>
</article>

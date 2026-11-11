<?php
/**
 * Template part for displaying a single article.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage senzasordino
 * @since senzasordino 1.0
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'single-story' ); ?>>

	<header class="entry-header story-header">

		<div class="post-breadcrumbs">
			<?php senzasordino_get_post_breadcrumbs(); ?>
		</div>

		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

		<?php if ( has_excerpt() ) : ?>
			<p class="story-standfirst"><?php echo esc_html( get_the_excerpt() ); ?></p>
		<?php endif; ?>

		<?php get_template_part( 'template-parts/post/author', 'byline' ); ?>
	</header><!-- .entry-header -->

	<?php if ( has_post_thumbnail() ) : ?>
		<figure class="story-figure">
			<?php the_post_thumbnail( 'large', array( 'loading' => false ) ); ?>
			<?php if ( wp_get_attachment_caption( get_post_thumbnail_id() ) ) : ?>
				<figcaption><?php echo wp_kses_post( wp_get_attachment_caption( get_post_thumbnail_id() ) ); ?></figcaption>
			<?php endif; ?>
		</figure>
	<?php endif; ?>

	<div class="entry-content">
		<?php
		the_content();

		wp_link_pages(
			array(
				'before'   => '<nav class="page-links" aria-label="' . esc_attr__( 'Page', 'twentytwentyone' ) . '">',
				'after'    => '</nav>',
				/* translators: %: Page number. */
				'pagelink' => esc_html__( 'Page %', 'twentytwentyone' ),
			)
		);
		?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<?php senzasordino_get_post_tags(); ?>
	</footer><!-- .entry-footer -->

	<?php if ( ! is_singular( 'attachment' ) ) : ?>
		<?php get_template_part( 'template-parts/post/author-bio' ); ?>
	<?php endif; ?>

</article><!-- #post-<?php the_ID(); ?> -->

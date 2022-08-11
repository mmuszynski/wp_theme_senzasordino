<?php
/**
 * The template for displaying author info below posts.
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_One
 * @since Twenty Twenty-One 1.0
 */

?>
<?php if ( (bool) get_the_author_meta( 'description' ) && post_type_supports( get_post_type(), 'author' ) ) : ?>
<hr>
	<div class="author-bio">
		<div class="author-bio-content">
			<p class="author-description"> <?php the_author_meta( 'description' ); ?></p><!-- .author-description -->
			<?php
			printf(
				'<a class="author-link" href="%1$s" rel="author">%2$s</a>',
				esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
				sprintf(
					/* translators: %s: Author name. */
					esc_html__( 'View all of %s\'s posts.', 'twentytwentyone' ),
					get_the_author()
				)
			);
			?>
		</div><!-- .author-bio-content -->
	</div><!-- .author-bio -->
<?php endif; ?>

<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_One
 * @since Twenty Twenty-One 1.0
 */

function category_has_parent($catid){
    $category = get_category($catid);
    if ($category->category_parent > 0){
        return true;
    }
    return false;
}

function senzasordino_get_post_breadcrumbs() {
    $category = get_the_category()[0];
    $category_links = substr(get_category_parents($category->term_id, true, " > "), 0, -3);
    
    echo "Senza Sordino > $category_links";
}

/// Gets the tags and outputs them in a div capable of styling
function senzasordino_get_post_tags() {
    $tags = get_the_tags();
    
    if ( !empty($tags) ) {

        echo "<div class=\"article-tags\">";
        
        foreach ($tags as &$tag) {
            $link = get_tag_link($tag);
            echo "<a class=\"article-tag-capsule\" href=\"$link\">$tag->name</a>";
        }
        
        echo "</div>";
        
    }
}

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<header class="entry-header alignwide">

        <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

        <div class="post-breadcrumbs">
            <? senzasordino_get_post_breadcrumbs(); ?>
        </div>

        <div class="author-byline">
            <?php get_template_part( 'template-parts/post/author', 'byline' ); ?>
        </div>

        <?php senzasordino_get_post_tags(); ?>
	</header><!-- .entry-header -->

    <hr>

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

	<footer class="entry-footer default-max-width">
		<?php twenty_twenty_one_entry_meta_footer(); ?>
	</footer><!-- .entry-footer -->

	<?php if ( ! is_singular( 'attachment' ) ) : ?>
		<?php get_template_part( 'template-parts/post/author-bio' ); ?>
	<?php endif; ?>

</article><!-- #post-<?php the_ID(); ?> -->

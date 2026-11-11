<?php
/**
 * Newspaper-style template helpers.
 *
 * @package WordPress
 * @subpackage senzasordino
 * @since senzasordino 1.1
 */

/**
 * Returns the URL of an image bundled with the theme.
 *
 * @param string $name File name inside assets/images.
 * @return string
 */
function senzasordino_image_uri( $name ) {
	return get_template_directory_uri() . '/assets/images/' . $name;
}

/**
 * Prints the section navigation: the primary menu if assigned, otherwise top-level categories.
 *
 * @return void
 */
function senzasordino_section_nav() {
	if ( has_nav_menu( 'primary' ) ) {
		remove_filter( 'walker_nav_menu_start_el', 'twenty_twenty_one_add_sub_menu_toggle', 10 );
		wp_nav_menu(
			array(
				'theme_location' => 'primary',
				'container'      => false,
				'menu_class'     => 'section-nav-list',
				'depth'          => 1,
				'fallback_cb'    => false,
			)
		);
		add_filter( 'walker_nav_menu_start_el', 'twenty_twenty_one_add_sub_menu_toggle', 10, 4 );
		return;
	}

	echo '<ul class="section-nav-list">';
	printf(
		'<li class="%1$s"><a href="%2$s">%3$s</a></li>',
		( is_front_page() || is_home() ) ? 'current-cat' : '',
		esc_url( home_url( '/' ) ),
		esc_html__( 'Home', 'senzasordino' )
	);
	wp_list_categories(
		array(
			'title_li'   => '',
			'depth'      => 1,
			'parent'     => 0,
			'hide_empty' => true,
			'orderby'    => 'name',
		)
	);
	echo '</ul>';
}

/**
 * Prints the "kicker" label (the post's primary category) shown above headlines.
 *
 * @return void
 */
function senzasordino_the_kicker() {
	$categories = get_the_category();
	if ( empty( $categories ) ) {
		return;
	}
	printf(
		'<a class="story-kicker" href="%1$s">%2$s</a>',
		esc_url( get_category_link( $categories[0]->term_id ) ),
		esc_html( $categories[0]->name )
	);
}

/**
 * Prints a one-line byline: "By Author · Date".
 *
 * @return void
 */
function senzasordino_the_compact_byline() {
	printf(
		'<span class="story-author">%1$s</span><span class="story-sep" aria-hidden="true">&middot;</span><time class="story-date" datetime="%2$s">%3$s</time>',
		/* translators: %s: Author name. */
		esc_html( sprintf( __( 'By %s', 'senzasordino' ), get_the_author() ) ),
		esc_attr( get_the_date( DATE_W3C ) ),
		esc_html( get_the_date() )
	);
}

/**
 * Returns a plain-text summary of the current post for story cards.
 *
 * @param int $words Maximum number of words.
 * @return string
 */
function senzasordino_get_deck( $words = 30 ) {
	if ( post_password_required() ) {
		return __( 'This content is password protected.', 'senzasordino' );
	}
	if ( has_excerpt() ) {
		return wp_trim_words( get_the_excerpt(), $words, '&hellip;' );
	}
	$content = excerpt_remove_blocks( strip_shortcodes( get_post()->post_content ) );
	return wp_trim_words( $content, $words, '&hellip;' );
}

/**
 * Prints "Senza Sordino > Parent > Category" breadcrumbs for the current post.
 *
 * @return void
 */
function senzasordino_get_post_breadcrumbs() {
	$categories = get_the_category();
	if ( empty( $categories ) ) {
		return;
	}
	$category_links = substr( get_category_parents( $categories[0]->term_id, true, ' &rsaquo; ' ), 0, -10 );

	printf(
		'<a href="%1$s">%2$s</a> &rsaquo; %3$s',
		esc_url( home_url( '/' ) ),
		esc_html( get_bloginfo( 'name' ) ),
		$category_links // phpcs:ignore WordPress.Security.EscapeOutput
	);
}

/**
 * Gets the tags and outputs them in a div capable of styling.
 *
 * @return void
 */
function senzasordino_get_post_tags() {
	$tags = get_the_tags();
	if ( empty( $tags ) ) {
		return;
	}

	echo '<div class="article-tags">';
	foreach ( $tags as $tag ) {
		printf(
			'<a class="article-tag-capsule" href="%1$s">%2$s</a>',
			esc_url( get_tag_link( $tag ) ),
			esc_html( $tag->name )
		);
	}
	echo '</div>';
}

// Show "Issue Name" instead of "Category: Issue Name" on archive pages.
add_filter( 'get_the_archive_title_prefix', '__return_empty_string' );

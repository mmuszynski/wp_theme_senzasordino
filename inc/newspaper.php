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
 * Prints the section navigation from the menu assigned to the "primary" location.
 *
 * @return void
 */
function senzasordino_section_nav() {
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
}

/**
 * Returns the links shown in the ICSOM site bar, mirroring the navigation on icsom.org.
 *
 * Each item is a label mapped to either a path on icsom.org or an array of submenu labels and paths.
 *
 * @return array
 */
function senzasordino_icsom_nav_items() {
	$items = array(
		'Home'          => '/',
		'About'         => array(
			'About ICSOM'      => '/about/index.php',
			'BUTI Scholarship' => '/about/buti.php',
		),
		'News'          => '/news/index.php',
		'Board'         => array(
			'GB &amp; Counsel' => '/governance/',
		),
		'Conferences'   => array(
			'By Year' => '/conferences/',
		),
		'Orchestras'    => '/orchestras/',
		'Senza Sordino' => array(
			'Senza Online'   => '/senzasordino/',
			'Senza Archives' => '/senza/',
		),
		'Media'         => array(
			'ICSOM Profiles' => '/media/profiles.php',
			'Photo Gallery'  => '/gallery/',
			'Video Archive'  => '/media/index.php',
		),
		'Reference'     => array(
			'Links'                => '/links/laborLinks.php',
			'History'              => '/history/',
			'Conductor Evaluation' => '/conductor-eval/',
			'Forms &amp; Letters'  => '/forms/',
			'In Memoriam'          => '/in-memoriam/',
		),
		'For Members'   => array(
			'Reset Password'    => '/access/reset.php',
			'Join Delegate-L'   => '/contact/delegates.php',
			'Join Orchestra-L'  => '/contact/orch-L.php',
		),
	);

	/**
	 * Filters the ICSOM site bar links.
	 *
	 * @param array $items Label => path, or label => array of submenu label => path.
	 */
	return apply_filters( 'senzasordino_icsom_nav_items', $items );
}

/**
 * Prints the ICSOM site bar links as a Bootstrap navbar list.
 *
 * @return void
 */
function senzasordino_icsom_nav() {
	$base = 'https://www.icsom.org';
	$id   = 0;

	echo '<ul class="navbar-nav me-auto mb-2 mb-lg-0">';
	foreach ( senzasordino_icsom_nav_items() as $label => $target ) {
		if ( ! is_array( $target ) ) {
			printf(
				'<li class="nav-item"><a class="nav-link" href="%1$s">%2$s</a></li>',
				esc_url( $base . $target ),
				wp_kses_post( $label )
			);
			continue;
		}

		$id++;
		$toggle_id = 'icsom-nav-dropdown-' . $id;
		printf(
			'<li class="nav-item dropdown"><a class="nav-link dropdown-toggle%1$s" href="#" id="%2$s" role="button" data-bs-toggle="dropdown" aria-expanded="false">%3$s</a><ul class="dropdown-menu" aria-labelledby="%2$s">',
			'Senza Sordino' === $label ? ' active' : '',
			esc_attr( $toggle_id ),
			wp_kses_post( $label )
		);
		foreach ( $target as $sub_label => $path ) {
			printf(
				'<li><a class="dropdown-item" href="%1$s">%2$s</a></li>',
				esc_url( $base . $path ),
				wp_kses_post( $sub_label )
			);
		}
		echo '</ul></li>';
	}
	echo '</ul>';
}

/**
 * Returns the category for the current issue shown on the front page.
 *
 * Uses the category chosen in the Customizer; otherwise, of the latest post's categories, the one
 * whose first post is newest.
 *
 * @return WP_Term|null
 */
function senzasordino_get_current_issue() {
	static $current = false;
	if ( false === $current ) {
		$current = senzasordino_find_current_issue();
	}
	return $current;
}

/**
 * Looks up the current issue category; see senzasordino_get_current_issue().
 *
 * @return WP_Term|null
 */
function senzasordino_find_current_issue() {
	$issue_id = absint( get_theme_mod( 'senzasordino_current_issue', 0 ) );
	if ( $issue_id ) {
		$issue = get_category( $issue_id );
		if ( $issue instanceof WP_Term ) {
			return $issue;
		}
	}

	$latest = get_posts(
		array(
			'numberposts'      => 1,
			'fields'           => 'ids',
			'suppress_filters' => false,
		)
	);
	if ( ! $latest ) {
		return null;
	}

	$default    = (int) get_option( 'default_category' );
	$categories = array_filter(
		get_the_category( $latest[0] ),
		function ( $category ) use ( $default ) {
			return $category->term_id !== $default;
		}
	);
	if ( ! $categories ) {
		return null;
	}

	// The issue is the category that started most recently; topic categories have older first posts.
	$issue      = null;
	$issue_date = '';
	foreach ( $categories as $category ) {
		$first = get_posts(
			array(
				'numberposts' => 1,
				'cat'         => $category->term_id,
				'orderby'     => 'date',
				'order'       => 'ASC',
			)
		);
		if ( $first && $first[0]->post_date > $issue_date ) {
			$issue      = $category;
			$issue_date = $first[0]->post_date;
		}
	}
	return $issue;
}

/**
 * Adds the "Current issue" picker to the Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Customizer manager.
 * @return void
 */
function senzasordino_customize_current_issue( $wp_customize ) {
	$choices = array( 0 => __( 'Automatic (most recently started category)', 'senzasordino' ) );
	foreach ( get_categories( array( 'orderby' => 'term_id', 'order' => 'DESC', 'hide_empty' => false ) ) as $category ) {
		$choices[ $category->term_id ] = $category->name;
	}

	$wp_customize->add_section(
		'senzasordino_front_page',
		array(
			'title'    => __( 'Current Issue', 'senzasordino' ),
			'priority' => 120,
		)
	);
	$wp_customize->add_setting(
		'senzasordino_current_issue',
		array(
			'default'           => 0,
			'sanitize_callback' => 'absint',
		)
	);
	$wp_customize->add_control(
		'senzasordino_current_issue',
		array(
			'type'        => 'select',
			'section'     => 'senzasordino_front_page',
			'label'       => __( 'Issue shown on the front page', 'senzasordino' ),
			'choices'     => $choices,
		)
	);
}
add_action( 'customize_register', 'senzasordino_customize_current_issue' );

/**
 * Prints the "kicker" label (the post's primary category) shown above headlines.
 *
 * @return void
 */
function senzasordino_the_kicker() {
	$categories = get_the_category();
	$issue      = senzasordino_get_current_issue();
	if ( $issue && count( $categories ) > 1 ) {
		$categories = array_values( wp_list_filter( $categories, array( 'term_id' => $issue->term_id ), 'NOT' ) );
	}
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

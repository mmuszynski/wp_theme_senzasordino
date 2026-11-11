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
 * Volume and number of an issue category named "Month YYYY", e.g. "Volume 64, No. 3".
 *
 * The volume counts from 1962 and the number is the issue's position among that year's issue categories.
 *
 * @param WP_Term $issue Issue category.
 * @return string Empty if the category name is not "Month YYYY".
 */
function senzasordino_issue_volume( $issue ) {
	$parse = function ( $name ) {
		$time = strtotime( '1 ' . $name );
		return ( preg_match( '/^[A-Za-z]+ \d{4}$/', trim( $name ) ) && $time ) ? $time : false;
	};

	$label = '';
	$time  = $parse( $issue->name );
	if ( $time ) {
		$year   = (int) gmdate( 'Y', $time );
		$number = 1;
		$terms  = get_terms(
			array(
				'taxonomy'   => 'category',
				'hide_empty' => false,
				'search'     => (string) $year,
				'fields'     => 'names',
			)
		);
		foreach ( (array) $terms as $name ) {
			$other = $parse( $name );
			if ( $other && (int) gmdate( 'Y', $other ) === $year && $other < $time ) {
				$number++;
			}
		}
		/* translators: 1: volume number, 2: issue number within the volume. */
		$label = sprintf( __( 'Volume %1$d, No. %2$d', 'senzasordino' ), $year - 1962, $number );
	}

	/**
	 * Filters the volume/number label shown for an issue in the masthead.
	 *
	 * @param string  $label Label, e.g. "Volume 64, No. 3".
	 * @param WP_Term $issue Issue category.
	 */
	return apply_filters( 'senzasordino_issue_volume', $label, $issue );
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

/**
 * Whether a category name follows the "Month YYYY" issue naming used on the site.
 *
 * @param string $name Category name.
 * @return bool
 */
function senzasordino_is_issue_name( $name ) {
	return (bool) ( preg_match( '/^[A-Za-z]+ \d{4}$/', trim( $name ) ) && strtotime( '1 ' . trim( $name ) ) );
}

/**
 * Returns all "Month YYYY" issue categories, newest issue first.
 *
 * Each entry is an array with 'term' (WP_Term) and 'time' (Unix timestamp of the issue month).
 *
 * @return array
 */
function senzasordino_issue_categories() {
	$issues = array();
	foreach ( get_categories( array( 'hide_empty' => false ) ) as $term ) {
		if ( senzasordino_is_issue_name( $term->name ) ) {
			$issues[] = array(
				'term' => $term,
				'time' => strtotime( '1 ' . trim( $term->name ) ),
			);
		}
	}
	usort(
		$issues,
		function ( $a, $b ) {
			return $b['time'] <=> $a['time'];
		}
	);
	return $issues;
}

/**
 * URL of the Past Issues index, pretty or plain depending on permalink settings.
 *
 * @return string
 */
function senzasordino_past_issues_url() {
	return get_option( 'permalink_structure' )
		? home_url( user_trailingslashit( '/past-issues' ) )
		: home_url( '?senza_past_issues=1' );
}

/**
 * Registers the /past-issues/ rewrite and its query var.
 *
 * @return void
 */
function senzasordino_past_issues_rewrite() {
	add_rewrite_rule( '^past-issues/?$', 'index.php?senza_past_issues=1', 'top' );
}
add_action( 'init', 'senzasordino_past_issues_rewrite' );

/**
 * Adds the past-issues query var.
 *
 * @param array $vars Public query vars.
 * @return array
 */
function senzasordino_past_issues_query_var( $vars ) {
	$vars[] = 'senza_past_issues';
	return $vars;
}
add_filter( 'query_vars', 'senzasordino_past_issues_query_var' );

/**
 * Serves the Past Issues template for the /past-issues/ request.
 *
 * @param string $template Template path.
 * @return string
 */
function senzasordino_past_issues_template( $template ) {
	if ( get_query_var( 'senza_past_issues' ) ) {
		$found = locate_template( 'past-issues.php' );
		if ( $found ) {
			return $found;
		}
	}
	return $template;
}
add_filter( 'template_include', 'senzasordino_past_issues_template' );

// Refresh rewrite rules when the theme is activated so /past-issues/ works immediately.
add_action( 'after_switch_theme', 'flush_rewrite_rules' );

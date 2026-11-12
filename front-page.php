<?php
/**
 * Front page: the stories in the current issue, laid out as a newspaper front.
 *
 * @package WordPress
 * @subpackage senzasordino
 * @since senzasordino 1.2
 */

get_header();

$senzasordino_issue = senzasordino_get_current_issue();
$issue_query        = null;
if ( $senzasordino_issue ) {
	$issue_query = new WP_Query(
		array(
			'cat'                 => $senzasordino_issue->term_id,
			'posts_per_page'      => -1,
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
		)
	);
}

if ( $issue_query && $issue_query->have_posts() ) :
	get_template_part( 'template-parts/content/news-grid', null, array( 'query' => $issue_query ) );
	wp_reset_postdata();
else :
	get_template_part( 'template-parts/content/content-none' );
endif;

get_footer();

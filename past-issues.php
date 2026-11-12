<?php
/**
 * The Past Issues index: every "Month YYYY" issue category, newest first.
 *
 * Served for the /past-issues/ request registered in inc/newspaper.php.
 *
 * @package WordPress
 * @subpackage senzasordino
 * @since senzasordino 1.3
 */

get_header();

$senzasordino_issues = senzasordino_issue_categories();
?>

<article class="past-issues">
	<header class="page-header">
		<h1 class="page-title"><?php esc_html_e( 'Past Issues', 'senzasordino' ); ?></h1>
	</header>

	<?php if ( $senzasordino_issues ) : ?>
		<ul class="issue-list">
			<?php foreach ( $senzasordino_issues as $senzasordino_entry ) : ?>
				<?php $senzasordino_term = $senzasordino_entry['term']; ?>
				<li class="issue-list-item">
					<a class="issue-link" href="<?php echo esc_url( get_category_link( $senzasordino_term ) ); ?>">
						<?php echo esc_html( $senzasordino_term->name ); ?>
					</a>
					<span class="issue-meta">
						<?php
						$senzasordino_volume = senzasordino_issue_volume( $senzasordino_term );
						if ( $senzasordino_volume ) {
							echo esc_html( $senzasordino_volume ) . ' &middot; ';
						}
						printf(
							/* translators: %s: Number of articles in the issue. */
							esc_html( _n( '%s article', '%s articles', $senzasordino_term->count, 'senzasordino' ) ),
							esc_html( number_format_i18n( $senzasordino_term->count ) )
						);
						?>
					</span>
				</li>
			<?php endforeach; ?>
		</ul>
	<?php else : ?>
		<?php get_template_part( 'template-parts/content/content-none' ); ?>
	<?php endif; ?>
</article>

<?php
get_footer();

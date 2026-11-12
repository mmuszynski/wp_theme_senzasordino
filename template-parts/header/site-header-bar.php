<?php
/**
 * Displays the Senza Sordino masthead: nameplate, dateline with search, and section navigation.
 *
 * @package WordPress
 * @subpackage SenzaSordino
 * @since SenzaSordino 1.0
 */

$description   = get_bloginfo( 'description', 'display' );
$issue         = senzasordino_get_current_issue();
$nameplate_tag = ( is_front_page() || is_home() ) ? 'h1' : 'div';
?>

<header id="masthead" class="masthead" role="banner">
	<<?php echo $nameplate_tag; // phpcs:ignore WordPress.Security.EscapeOutput ?> class="masthead-nameplate">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
			<img src="<?php echo esc_url( senzasordino_image_uri( 'senzaLogo.svg' ) ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" />
		</a>
	</<?php echo $nameplate_tag; // phpcs:ignore WordPress.Security.EscapeOutput ?>>

	<?php if ( $description ) : ?>
		<p class="masthead-tagline"><?php echo esc_html( $description ); ?></p>
	<?php endif; ?>

	<div class="masthead-dateline">
		<span class="masthead-edition">
			<span class="masthead-date"><?php echo esc_html( $issue ? senzasordino_issue_volume( $issue ) : '' ); ?></span>
			<?php if ( $issue ) : ?>
				<details class="masthead-issue-nav">
					<summary class="masthead-issue"><?php echo esc_html( $issue->name ); ?></summary>
					<ul class="issue-menu">
						<li><a href="<?php echo esc_url( get_category_link( $issue ) ); ?>"><strong><?php echo esc_html( $issue->name ); ?></strong></a></li>
						<?php foreach ( senzasordino_issue_categories() as $senzasordino_entry ) : ?>
							<?php
							$senzasordino_term = $senzasordino_entry['term'];
							if ( $senzasordino_term->term_id === $issue->term_id ) {
								continue;
							}
							?>
							<li><a href="<?php echo esc_url( get_category_link( $senzasordino_term ) ); ?>"><?php echo esc_html( $senzasordino_term->name ); ?></a></li>
						<?php endforeach; ?>
						<li class="issue-menu-all"><a href="<?php echo esc_url( senzasordino_past_issues_url() ); ?>"><?php esc_html_e( 'All past issues', 'senzasordino' ); ?></a></li>
					</ul>
				</details>
			<?php endif; ?>
		</span>
		<?php if ( has_nav_menu( 'primary' ) ) : ?>
			<nav class="section-nav" aria-label="<?php esc_attr_e( 'Sections', 'senzasordino' ); ?>">
				<?php senzasordino_section_nav(); ?>
			</nav>
		<?php endif; ?>
		<form role="search" method="get" class="masthead-search" action="<?php echo esc_url( home_url( '/' ) ); ?>">
			<label class="screen-reader-text" for="masthead-search-field"><?php esc_html_e( 'Search', 'senzasordino' ); ?></label>
			<input type="search" id="masthead-search-field" name="s" placeholder="<?php esc_attr_e( 'Search Senza Sordino', 'senzasordino' ); ?>" value="<?php echo get_search_query(); ?>" />
			<button type="submit"><?php esc_html_e( 'Go', 'senzasordino' ); ?></button>
		</form>
	</div>
</header>

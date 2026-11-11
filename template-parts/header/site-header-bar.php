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

	<div class="masthead-dateline">
		<time class="masthead-date" datetime="<?php echo esc_attr( current_time( 'Y-m-d' ) ); ?>"><?php echo esc_html( date_i18n( 'l, F j, Y' ) ); ?></time>
		<?php if ( $description || $issue ) : ?>
			<span class="masthead-tagline">
				<?php echo esc_html( $description ); ?>
				<?php if ( $issue ) : ?>
					<a class="masthead-issue" href="<?php echo esc_url( get_category_link( $issue ) ); ?>"><?php echo esc_html( $issue->name ); ?></a>
				<?php endif; ?>
			</span>
		<?php endif; ?>
		<form role="search" method="get" class="masthead-search" action="<?php echo esc_url( home_url( '/' ) ); ?>">
			<label class="screen-reader-text" for="masthead-search-field"><?php esc_html_e( 'Search', 'senzasordino' ); ?></label>
			<input type="search" id="masthead-search-field" name="s" placeholder="<?php esc_attr_e( 'Search Senza Sordino', 'senzasordino' ); ?>" value="<?php echo get_search_query(); ?>" />
			<button type="submit"><?php esc_html_e( 'Go', 'senzasordino' ); ?></button>
		</form>
	</div>

	<?php if ( has_nav_menu( 'primary' ) ) : ?>
		<nav class="section-nav" aria-label="<?php esc_attr_e( 'Sections', 'senzasordino' ); ?>">
			<?php senzasordino_section_nav(); ?>
		</nav>
	<?php endif; ?>
</header>

<?php
/**
 * Displays the newspaper-style masthead: ears, nameplate, dateline and section navigation.
 *
 * @package WordPress
 * @subpackage SenzaSordino
 * @since SenzaSordino 1.0
 */

$description   = get_bloginfo( 'description', 'display' );
$nameplate_tag = ( is_front_page() || is_home() ) ? 'h1' : 'div';
?>

<header id="masthead" class="masthead" role="banner">
	<div class="masthead-main">
		<div class="masthead-ear masthead-ear-left">
			<a class="masthead-icsom" href="https://www.icsom.org/">
				<img src="<?php echo esc_url( senzasordino_image_uri( 'ICSOM4ColorNoText_200.png' ) ); ?>" alt="" width="48" height="48" />
				<span><?php esc_html_e( 'International Conference of Symphony and Opera Musicians', 'senzasordino' ); ?></span>
			</a>
		</div>

		<<?php echo $nameplate_tag; // phpcs:ignore WordPress.Security.EscapeOutput ?> class="masthead-nameplate">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
				<img src="<?php echo esc_url( senzasordino_image_uri( 'senzaLogo.svg' ) ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" />
			</a>
		</<?php echo $nameplate_tag; // phpcs:ignore WordPress.Security.EscapeOutput ?>>

		<div class="masthead-ear masthead-ear-right">
			<form role="search" method="get" class="masthead-search" action="<?php echo esc_url( home_url( '/' ) ); ?>">
				<label class="screen-reader-text" for="masthead-search-field"><?php esc_html_e( 'Search', 'senzasordino' ); ?></label>
				<input type="search" id="masthead-search-field" name="s" placeholder="<?php esc_attr_e( 'Search', 'senzasordino' ); ?>" value="<?php echo get_search_query(); ?>" />
				<button type="submit"><?php esc_html_e( 'Go', 'senzasordino' ); ?></button>
			</form>
		</div>
	</div>

	<div class="masthead-dateline">
		<time class="masthead-date" datetime="<?php echo esc_attr( current_time( 'Y-m-d' ) ); ?>"><?php echo esc_html( date_i18n( 'l, F j, Y' ) ); ?></time>
		<?php if ( $description ) : ?>
			<span class="masthead-tagline"><?php echo esc_html( $description ); ?></span>
		<?php endif; ?>
		<a class="masthead-home-link" href="https://www.icsom.org/">icsom.org</a>
	</div>

	<nav class="section-nav" aria-label="<?php esc_attr_e( 'Sections', 'senzasordino' ); ?>">
		<?php senzasordino_section_nav(); ?>
	</nav>
</header>

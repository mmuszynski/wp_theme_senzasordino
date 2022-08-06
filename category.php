<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_One
 * @since Twenty Twenty-One 1.0
 */

get_header();
$description = get_the_archive_description();
?>

<?php if( get_field('sub_heading') ): ?>
    <h2><?php the_field('sub_heading'); ?></h2>
<?php endif; ?>

<?php if ( have_posts() ) : ?>

	<header class="page-header alignwide">
		<?php the_archive_title( '<h1 class="page-title">', '</h1>' ); ?>
		<?php if ( $description ) : ?>
			<div class="archive-description"><?php echo wp_kses_post( wpautop( $description ) ); ?></div>
		<?php endif; ?>
	</header><!-- .page-header -->

	<?php while ( have_posts() ) : ?>
		<?php the_post(); ?>

<!-- Need to remove option in class-twenty-twenty-one-customize.php
$wp_customize->add_setting('display_excerpt_or_full_post' -->

        <?php get_template_part( 'template-parts/post/author', 'byline' ); ?>
		<?php get_template_part( 'template-parts/content/content', 'excerpt' ); ?>
	<?php endwhile; ?>

	<?php twenty_twenty_one_the_posts_navigation(); ?>

<?php else : ?>
	<?php get_template_part( 'template-parts/content/content-none' ); ?>
<?php endif; ?>

<?php get_footer(); ?>

<?php
/**
 * The template for displaying author byline information.
 *
 * @package WordPress
 * @subpackage senzasordino
 * @since senzasordino 1.0
 */

?>
    <div class="author-byline <?php echo get_option( 'show_avatars' ) ? 'show-avatars' : ''; ?>">
        <div class="author-byline-content">
            <div class="author-avatar">
                <?php echo get_avatar( get_the_author_meta( 'ID' ), '85' ); ?>
            </div>

            <div class="author-byline-information">
            <div class="author-byline">
                <?php
                printf(
                    /* translators: %s: Author name. */
                    esc_html__( 'By %s', 'twentytwentyone' ),
                    get_the_author()
                );
                ?>
            </div>
            <div class="author-byline-post-date">
                Jan 1, 1970
            </div>
            </div>
        </div><!-- .author-byline-content -->
    </div><!-- .author-byline -->
<hr>

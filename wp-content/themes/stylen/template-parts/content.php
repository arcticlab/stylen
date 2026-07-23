<?php
$has_thumbnail = has_post_thumbnail();
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

    <header class="entry-header">
        <?php
        if ( is_singular() ) {
            the_title( '<h1 class="entry-title">', '</h1>' );
        } else {
            the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
        }
        ?>

        <?php if ( 'post' === get_post_type() ) : ?>
            <div class="entry-meta">
                <time datetime="<?php echo get_the_date( 'c' ); ?>"><?php echo get_the_date(); ?></time>
                &mdash; <?php the_author(); ?>
                <?php if ( has_category() ) : ?>
                    &mdash; <?php the_category( ', ' ); ?>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </header>

    <?php if ( $has_thumbnail && ! is_singular() ) : ?>
        <div class="post-thumbnail">
            <a href="<?php the_permalink(); ?>">
                <?php the_post_thumbnail( 'medium_large' ); ?>
            </a>
        </div>
    <?php elseif ( $has_thumbnail ) : ?>
        <div class="post-thumbnail">
            <?php the_post_thumbnail( 'large' ); ?>
        </div>
    <?php endif; ?>

    <div class="entry-content">
        <?php
        if ( is_singular() ) {
            the_content();
            wp_link_pages( [
                'before' => '<div class="page-links">' . __( 'Pages:', 'stylen' ),
                'after'  => '</div>',
            ] );
        } else {
            the_excerpt();
        }
        ?>
    </div>

    <?php if ( ! is_singular() ) : ?>
        <footer class="entry-footer">
            <a href="<?php the_permalink(); ?>" class="btn"><?php _e( 'Read more', 'stylen' ); ?></a>
        </footer>
    <?php endif; ?>

</article>

<?php ?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

    <header class="entry-header">
        <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
        <div class="entry-meta">
            <time datetime="<?php echo get_the_date( 'c' ); ?>"><?php echo get_the_date(); ?></time>
            &mdash; <?php the_author(); ?>
            <?php if ( has_category() ) : ?>
                &mdash; <?php the_category( ', ' ); ?>
            <?php endif; ?>
        </div>
    </header>

    <?php if ( has_post_thumbnail() ) : ?>
        <div class="post-thumbnail">
            <?php the_post_thumbnail( 'large' ); ?>
        </div>
    <?php endif; ?>

    <div class="entry-content">
        <?php
        the_content();
        wp_link_pages( [
            'before' => '<div class="page-links">' . __( 'Pages:', 'stylen' ),
            'after'  => '</div>',
        ] );
        ?>
    </div>

    <footer class="entry-footer">
        <?php the_tags( '<div class="tags-links"><span>' . __( 'Tags:', 'stylen' ) . '</span> ', ', ', '</div>' ); ?>
    </footer>

</article>

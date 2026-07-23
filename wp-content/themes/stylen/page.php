<?php get_header(); ?>

        <div class="container content-layout">

            <main id="primary" class="content-area">

                <?php while ( have_posts() ) : the_post(); ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                        <header class="entry-header">
                            <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
                        </header>

                        <?php if ( has_post_thumbnail() ) : ?>
                            <div class="post-thumbnail">
                                <?php the_post_thumbnail( 'large' ); ?>
                            </div>
                        <?php endif; ?>

                        <div class="entry-content">
                            <?php the_content(); ?>
                            <?php
                            wp_link_pages( [
                                'before' => '<div class="page-links">' . __( 'Pages:', 'stylen' ),
                                'after'  => '</div>',
                            ] );
                            ?>
                        </div>
                    </article>
                <?php endwhile; ?>

            </main>

            <?php get_sidebar(); ?>

        </div><!-- .content-layout -->

<?php get_footer(); ?>

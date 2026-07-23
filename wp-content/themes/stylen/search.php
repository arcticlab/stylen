<?php get_header(); ?>

        <div class="container content-layout">

            <main id="primary" class="content-area">

                <?php if ( have_posts() ) : ?>

                    <header class="page-header">
                        <h1 class="page-title">
                            <?php printf( __( 'Search results for: %s', 'stylen' ), '<span>' . get_search_query() . '</span>' ); ?>
                        </h1>
                    </header>

                    <?php while ( have_posts() ) : the_post(); ?>
                        <?php get_template_part( 'template-parts/content', 'excerpt' ); ?>
                    <?php endwhile; ?>

                    <?php the_posts_navigation(); ?>

                <?php else : ?>
                    <?php get_template_part( 'template-parts/content', 'none' ); ?>
                <?php endif; ?>

            </main>

            <?php get_sidebar(); ?>

        </div><!-- .content-layout -->

<?php get_footer(); ?>

<?php
/**
 * Template Name: Блог
 *
 * A single, undivided stream of articles — no subcategories, no rubric filter,
 * no featured-first special-casing. Every post flows uniformly in one grid.
 *
 * @package Stylen
 */

$c = stylen_contacts();

$paged = max( 1, (int) get_query_var( 'paged' ), (int) get_query_var( 'page' ) );
$q = new WP_Query( [
    'post_type'           => 'post',
    'post_status'         => 'publish',
    'posts_per_page'      => 9,
    'paged'               => $paged,
    'ignore_sticky_posts' => true,
] );

get_header();
?>

<main id="primary">

    <!-- ============ HERO ============ -->
    <section class="page-hero">
        <div class="container">
            <div class="page-hero__inner page-hero__inner--wide">
                <?php stylen_breadcrumbs(); ?>
                <h1 class="page-hero__title"><?php echo wp_kses_post( get_field( 'bl_hero_title' ) ); ?></h1>
                <p class="page-hero__lead"><?php echo esc_html( get_field( 'bl_hero_lead' ) ); ?></p>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container">

            <?php if ( $q->have_posts() ) : ?>

                <div class="post-grid">
                    <?php while ( $q->have_posts() ) : $q->the_post(); ?>
                        <article class="post-card" data-reveal>
                            <a href="<?php the_permalink(); ?>" aria-label="<?php the_title_attribute(); ?>" tabindex="-1">
                                <?php stylen_post_cover( 'post-card' ); ?>
                            </a>
                            <div class="post-card__body">
                                <h2 class="post-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                                <p class="post-card__excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 22 ) ); ?></p>
                                <div class="post-card__foot"><?php echo esc_html( get_the_date() ); ?></div>
                            </div>
                        </article>
                    <?php endwhile; ?>
                </div>

                <?php
                echo paginate_links( [
                    'total'     => $q->max_num_pages,
                    'current'   => $paged,
                    'prev_text' => '←',
                    'next_text' => '→',
                ] );
                ?>

            <?php else : ?>
                <p class="pf-empty">Скоро здесь появятся статьи. А&nbsp;пока&nbsp;— <a href="#order">оставьте заявку</a>.</p>
            <?php endif; wp_reset_postdata(); ?>

        </div>
    </section>

    <?php if ( get_field( 'bl_cta_title' ) ) : ?>
        <!-- ============ CTA ============ -->
        <section class="section" style="padding-top:0">
            <div class="container">
                <div class="cta-band" data-reveal>
                    <div>
                        <h2 class="cta-band__title"><?php echo esc_html( get_field( 'bl_cta_title' ) ); ?></h2>
                        <p class="cta-band__text"><?php echo esc_html( get_field( 'bl_cta_text' ) ); ?></p>
                    </div>
                    <div class="cta-band__actions">
                        <a class="btn btn--gold btn--lg" href="#order"><?php echo esc_html( get_field( 'bl_cta_button' ) ); ?> <?php echo stylen_icon( 'arrow', 'icon btn__arrow' ); ?></a>
                        <a class="btn btn--ghost-light btn--lg" href="<?php echo esc_attr( $c['phone_href'] ); ?>"><?php echo esc_html( $c['phone'] ); ?></a>
                    </div>
                </div>
            </div>
        </section>
    <?php endif; ?>

</main>

<?php
get_footer();

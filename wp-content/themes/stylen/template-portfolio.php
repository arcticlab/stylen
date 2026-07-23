<?php
/**
 * Template Name: Портфолио
 *
 * Lists the real `portfolio` custom-post-type entries, grouped into
 * subcategories (taxonomy `portfolio_cat`) with a client-side filter.
 * Each work is an individually-addressable record (single-portfolio.php).
 *
 * @package Stylen
 */

$c = stylen_contacts();

$q = new WP_Query( [
    'post_type'      => 'portfolio',
    'post_status'    => 'publish',
    'posts_per_page' => -1,
    'orderby'        => [ 'menu_order' => 'ASC', 'date' => 'DESC' ],
] );

$pf_terms = get_terms( [ 'taxonomy' => 'portfolio_cat', 'hide_empty' => true ] );

get_header();
?>

<main id="primary">

    <!-- ============ HERO ============ -->
    <section class="page-hero">
        <div class="container">
            <div class="page-hero__inner page-hero__inner--wide">
                <?php stylen_breadcrumbs(); ?>
                <p class="page-hero__kicker">Наши работы</p>
                <h1 class="page-hero__title">Портфолио <span class="mark">Стиль-Н</span></h1>
                <p class="page-hero__lead">Более 12&nbsp;000&nbsp;реализованных заказов&nbsp;— от&nbsp;одной таблички до&nbsp;комплексного оформления зданий. Выберите направление, чтобы посмотреть работы.</p>
            </div>
        </div>
    </section>

    <!-- ============ GALLERY ============ -->
    <section class="section">
        <div class="container">

            <?php if ( ! is_wp_error( $pf_terms ) && ! empty( $pf_terms ) ) : ?>
                <nav class="pf-filter" aria-label="Подкатегории портфолио">
                    <a class="pf-chip is-active" href="<?php echo esc_url( home_url( '/portfolio/' ) ); ?>" aria-current="page">Все работы</a>
                    <?php foreach ( $pf_terms as $t ) : ?>
                        <a class="pf-chip" href="<?php echo esc_url( get_term_link( $t ) ); ?>"><?php echo esc_html( $t->name ); ?></a>
                    <?php endforeach; ?>
                </nav>
            <?php endif; ?>

            <div class="pf-grid">
                <?php
                if ( $q->have_posts() ) :
                    $i = 0;
                    while ( $q->have_posts() ) : $q->the_post();
                        echo stylen_pf_card( ( $i % 3 ) * 55 );
                        $i++;
                    endwhile;
                    wp_reset_postdata();
                else :
                    ?>
                    <p class="pf-empty">Скоро здесь появятся работы. А&nbsp;пока&nbsp;— <a href="<?php echo esc_url( home_url( '/kontakty/' ) ); ?>">обсудите свой проект</a>.</p>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- ============ ABOUT ============ -->
    <?php $pf_intro = stylen_portfolio_intro(); ?>
    <section class="section section--tint">
        <div class="container">
            <div class="pf-prose" data-reveal>
                <header class="section__head section__head--left">
                    <p class="section__kicker">О портфолио</p>
                    <h2 class="section__title"><?php echo esc_html( $pf_intro['title'] ); ?></h2>
                </header>
                <div class="article-body"><?php echo $pf_intro['body']; ?></div>
            </div>
        </div>
    </section>

    <!-- ============ CTA ============ -->
    <section class="section" style="padding-top:0">
        <div class="container">
            <div class="cta-band" data-reveal>
                <div>
                    <h2 class="cta-band__title">Хотите так&nbsp;же?</h2>
                    <p class="cta-band__text">Расскажите о&nbsp;задаче&nbsp;— предложим решение, посчитаем стоимость и&nbsp;бесплатно подготовим макет.</p>
                </div>
                <div class="cta-band__actions">
                    <a class="btn btn--gold btn--lg" href="<?php echo esc_url( home_url( '/kontakty/' ) ); ?>">Обсудить проект <?php echo stylen_icon( 'arrow', 'icon btn__arrow' ); ?></a>
                    <a class="btn btn--ghost-light btn--lg" href="<?php echo esc_attr( $c['phone_href'] ); ?>"><?php echo esc_html( $c['phone'] ); ?></a>
                </div>
            </div>
        </div>
    </section>

</main>

<?php
get_footer();

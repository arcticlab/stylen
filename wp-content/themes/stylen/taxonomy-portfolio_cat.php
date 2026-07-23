<?php
/**
 * Portfolio subcategory archive — /portfolio/{subcat}/
 *
 * @package Stylen
 */

$c        = stylen_contacts();
$term     = get_queried_object();
$pf_terms = get_terms( [ 'taxonomy' => 'portfolio_cat', 'hide_empty' => true ] );
$pf_meta  = stylen_pf_cat_meta( $term->slug );

// "Об услуге" description: the term's own (custom, admin-editable) description
// wins; otherwise the curated helper copy is the fallback.
$custom_desc = trim( (string) $term->description );
$about_body  = '' !== $custom_desc ? wpautop( wp_kses_post( $custom_desc ) ) : ( $pf_meta['body'] ?? '' );
$about_title = ! empty( $pf_meta['title'] ) ? $pf_meta['title'] : 'Подробнее о направлении';

get_header();
?>

<main id="primary">

    <!-- ============ HERO ============ -->
    <section class="page-hero">
        <div class="container">
            <div class="page-hero__inner page-hero__inner--wide">
                <?php stylen_breadcrumbs(); ?>
                <p class="page-hero__kicker">Портфолио</p>
                <h1 class="page-hero__title"><?php echo esc_html( $term->name ); ?></h1>
                <p class="page-hero__lead">
                    <?php
                    echo ! empty( $pf_meta['lead'] )
                        ? esc_html( $pf_meta['lead'] )
                        : 'Примеры выполненных работ в&nbsp;направлении «' . esc_html( $term->name ) . '».';
                    ?>
                </p>
            </div>
        </div>
    </section>

    <!-- ============ GALLERY ============ -->
    <section class="section">
        <div class="container">

            <?php if ( ! is_wp_error( $pf_terms ) && ! empty( $pf_terms ) ) : ?>
                <nav class="pf-filter" aria-label="Подкатегории портфолио">
                    <a class="pf-chip" href="<?php echo esc_url( home_url( '/portfolio/' ) ); ?>">Все работы</a>
                    <?php foreach ( $pf_terms as $t ) : ?>
                        <a class="pf-chip<?php echo $t->term_id === $term->term_id ? ' is-active' : ''; ?>"
                           href="<?php echo esc_url( get_term_link( $t ) ); ?>"
                           <?php echo $t->term_id === $term->term_id ? 'aria-current="page"' : ''; ?>><?php echo esc_html( $t->name ); ?></a>
                    <?php endforeach; ?>
                </nav>
            <?php endif; ?>

            <div class="pf-grid">
                <?php
                if ( have_posts() ) :
                    $i = 0;
                    while ( have_posts() ) : the_post();
                        echo stylen_pf_card( ( $i % 3 ) * 55 );
                        $i++;
                    endwhile;
                else :
                    ?>
                    <p class="pf-empty">В&nbsp;этой категории пока нет работ. <a href="<?php echo esc_url( home_url( '/kontakty/' ) ); ?>">Обсудите свой проект</a>.</p>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- ============ ABOUT SUBCATEGORY (custom term description → helper fallback) ============ -->
    <?php if ( $about_body ) : ?>
        <section class="section section--tint">
            <div class="container">
                <div class="pf-prose" data-reveal>
                    <header class="section__head section__head--left">
                        <p class="section__kicker">О направлении</p>
                        <h2 class="section__title"><?php echo esc_html( $about_title ); ?></h2>
                    </header>
                    <div class="article-body"><?php echo $about_body; ?></div>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <!-- ============ CTA ============ -->
    <section class="section" style="padding-top:0">
        <div class="container">
            <div class="cta-band" data-reveal>
                <div>
                    <h2 class="cta-band__title">Хотите так&nbsp;же?</h2>
                    <p class="cta-band__text">Расскажите о&nbsp;задаче&nbsp;— предложим решение, посчитаем стоимость и&nbsp;бесплатно подготовим макет.</p>
                </div>
                <div class="cta-band__actions">
                    <a class="btn btn--gold btn--lg" href="#order">Обсудить проект <?php echo stylen_icon( 'arrow', 'icon btn__arrow' ); ?></a>
                    <a class="btn btn--ghost-light btn--lg" href="<?php echo esc_attr( $c['phone_href'] ); ?>"><?php echo esc_html( $c['phone'] ); ?></a>
                </div>
            </div>
        </div>
    </section>

</main>

<?php
get_footer();

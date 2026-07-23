<?php
/**
 * Front page — Стиль-Н. v9 «Смета».
 * All copy is managed in the admin (ACF field group «Главная страница» on the
 * front page + the «Контент сайта» options page). This template only reads it.
 *
 * @package Stylen
 */

$c          = stylen_contacts();
$directions = stylen_get_directions();
$form_id    = get_option( 'stylen_order_form_id' );
$map_src    = 'https://yandex.ru/map-widget/v1/?text=' . rawurlencode( $c['zip'] . ', ' . $c['address'] ) . '&z=16';

$pid = get_queried_object_id();
$f   = function ( $k ) use ( $pid ) {
    return function_exists( 'get_field' ) ? get_field( $k, $pid ) : '';
};

/* Map each direction slug to the exact CF7 order-form option string. */
$cf7_map = [
    'wide-format'     => 'Широкоформатная и УФ-печать',
    'plaques'         => 'Таблички',
    'stands-safety'   => 'Стенды и безопасность',
    'displays'        => 'Рекламные конструкции',
    'stickers-labels' => 'Наклейки и этикетки',
    'backdrops'       => 'Фотофоны',
    'interior'        => 'Интерьерная продукция',
    'signs'           => 'Вывески',
];
$term_map = [
    'wide-format' => 'от 1 дня', 'plaques' => 'от 1 дня', 'stands-safety' => 'от 2 дней',
    'displays' => 'от 2 дней', 'stickers-labels' => 'от 1 дня', 'backdrops' => 'от 2 дней',
    'interior' => 'от 2 дней', 'signs' => 'от 3 дней',
];

$tiles = [];
foreach ( $directions as $d ) {
    $slug    = $d->post_name;
    $meta    = stylen_direction_meta( $slug );
    $tiles[] = [
        'id'    => $d->ID,
        'slug'  => $slug,
        'title' => $d->post_title,
        'cf7'   => $cf7_map[ $slug ] ?? $d->post_title,
        'term'  => $term_map[ $slug ] ?? 'от 1 дня',
        'desc'  => $meta['desc'],
        'icon'  => $meta['icon'],
        'url'   => get_permalink( $d->ID ),
        'bg'    => function_exists( 'get_field' ) ? get_field( 'direction_hero_bg', $d->ID ) : 0,
    ];
}
$init = $tiles[0] ?? null;

$faq = (array) $f( 'home_faq_items' );

get_header();
?>

<main id="primary" class="home">

    <!-- ============ HERO — dark configurator slider + live estimate ============ -->
    <section class="hero section--dark" data-hero-slider>

        <!-- background layers: one flat colour per direction, cross-faded -->
        <div class="hero__bg" aria-hidden="true">
            <?php foreach ( $tiles as $i => $t ) :
                $bg_url = $t['bg'] ? wp_get_attachment_image_url( $t['bg'], 'full' ) : '';
                if ( ! $bg_url ) { continue; }
                ?>
                <span class="hero__bg-layer<?php echo 0 === $i ? ' is-active' : ''; ?>"
                      data-bg-index="<?php echo (int) $i; ?>"
                      style="background-image:url('<?php echo esc_url( $bg_url ); ?>')"></span>
            <?php endforeach; ?>
        </div>

        <div class="container hero__grid">

            <div class="hero__lead">
                <span class="eyebrow"><?php echo esc_html( $f( 'home_hero_eyebrow' ) ); ?></span>
                <h1 class="hero__title"><?php echo wp_kses_post( $f( 'home_hero_title' ) ); ?></h1>

                <!-- swapped by the slider -->
                <p class="hero__slide-title" data-hero="title"><?php echo esc_html( $init ? $init['title'] : '' ); ?></p>
                <p class="hero__text" data-hero="desc"><?php echo esc_html( $init ? $init['desc'] : $f( 'home_hero_text' ) ); ?></p>

                <div class="chooser" role="group" aria-label="Что нужно изготовить">
                    <?php foreach ( $tiles as $i => $t ) : ?>
                        <button type="button"
                            class="chooser__tile<?php echo 0 === $i ? ' is-active' : ''; ?>"
                            role="radio" aria-checked="<?php echo 0 === $i ? 'true' : 'false'; ?>"
                            data-slug="<?php echo esc_attr( $t['slug'] ); ?>"
                            data-title="<?php echo esc_attr( $t['title'] ); ?>"
                            data-cf7="<?php echo esc_attr( $t['cf7'] ); ?>"
                            data-term="<?php echo esc_attr( $t['term'] ); ?>"
                            data-desc="<?php echo esc_attr( $t['desc'] ); ?>"
                            data-url="<?php echo esc_url( $t['url'] ); ?>">
                            <?php echo stylen_icon( $t['icon'], 'icon chooser__icon' ); ?>
                            <span class="chooser__label"><?php echo esc_html( $t['title'] ); ?></span>
                        </button>
                    <?php endforeach; ?>
                </div>
            </div>

            <?php if ( $init ) : ?>
            <aside class="estimate" id="estimate" aria-live="polite" aria-label="Предварительная смета">
                <div class="estimate__head">
                    <span class="estimate__tag"><?php echo esc_html( $f( 'home_est_tag' ) ); ?></span>
                    <span class="estimate__hint"><?php echo esc_html( $f( 'home_est_hint' ) ); ?></span>
                </div>
                <div class="estimate__rows">
                    <div class="estimate__row">
                        <span class="estimate__k"><?php echo esc_html( $f( 'home_est_k_dir' ) ); ?></span>
                        <span class="estimate__v" data-est="dir"><?php echo esc_html( $init['title'] ); ?></span>
                    </div>
                    <div class="estimate__row">
                        <span class="estimate__k"><?php echo esc_html( $f( 'home_est_k_term' ) ); ?></span>
                        <span class="estimate__v tnum" data-est="term"><?php echo esc_html( $init['term'] ); ?></span>
                    </div>
                    <div class="estimate__row">
                        <span class="estimate__k"><?php echo esc_html( $f( 'home_est_k_layout' ) ); ?></span>
                        <span class="estimate__v estimate__v--free"><?php echo esc_html( $f( 'home_est_free' ) ); ?></span>
                    </div>
                </div>
                <p class="estimate__desc" data-est="desc"><?php echo esc_html( $init['desc'] ); ?></p>
                <div class="estimate__foot">
                    <a class="btn btn--gold btn--block js-estimate-cta" data-cf7="<?php echo esc_attr( $init['cf7'] ); ?>" href="#order">
                        <?php echo esc_html( $f( 'home_est_cta' ) ); ?> <?php echo stylen_icon( 'arrow', 'icon btn__arrow' ); ?>
                    </a>
                    <p class="estimate__note"><?php echo stylen_icon( 'check', 'icon icon--sm' ); ?><?php echo esc_html( $f( 'home_est_note' ) ); ?></p>
                    <a class="estimate__phone" href="<?php echo esc_attr( $c['phone_href'] ); ?>"><?php echo stylen_icon( 'phone', 'icon icon--sm' ); ?><?php echo esc_html( $c['phone'] ); ?></a>
                </div>
            </aside>
            <?php endif; ?>

        </div>
    </section>

    <!-- ============ TRUST STRIP — spec bar ============ -->
    <section class="trust">
        <div class="container">
            <ul class="trust__row">
                <?php while ( have_rows( 'home_trust', $pid ) ) : the_row(); ?>
                    <li class="trust__item">
                        <span class="trust__icon"><?php echo stylen_icon( (string) get_sub_field( 't_icon' ), 'icon' ); ?></span>
                        <span class="trust__text"><b><?php echo esc_html( get_sub_field( 't_value' ) ); ?></b><?php echo esc_html( get_sub_field( 't_caption' ) ); ?></span>
                    </li>
                <?php endwhile; ?>
            </ul>
        </div>
    </section>

    <!-- ============ CATALOG — production directions ============ -->
    <section id="services" class="section">
        <div class="container">
            <header class="head" data-reveal>
                <div class="head__top">
                    <div>
                        <span class="eyebrow"><?php echo esc_html( $f( 'home_services_eyebrow' ) ); ?></span>
                        <h2 class="head__title"><?php echo esc_html( $f( 'home_services_title' ) ); ?></h2>
                    </div>
                    <p class="head__text"><?php echo esc_html( $f( 'home_services_text' ) ); ?></p>
                </div>
            </header>

            <div class="cat">
                <?php foreach ( $tiles as $i => $t ) : ?>
                    <a class="cat__card<?php echo has_post_thumbnail( $t['id'] ) ? ' cat__card--photo' : ''; ?>" href="<?php echo esc_url( $t['url'] ); ?>" data-reveal style="--d:<?php echo ( $i % 4 ) * 45; ?>ms">
                        <?php if ( has_post_thumbnail( $t['id'] ) ) : ?>
                            <span class="cat__cover"><?php echo get_the_post_thumbnail( $t['id'], 'medium', [ 'loading' => 'lazy', 'alt' => '' ] ); ?></span>
                        <?php else : ?>
                            <span class="cat__icon"><?php echo stylen_icon( $t['icon'], 'icon' ); ?></span>
                        <?php endif; ?>
                        <span class="cat__body">
                            <span class="cat__title"><?php echo esc_html( $t['title'] ); ?></span>
                            <span class="cat__desc"><?php echo esc_html( $t['desc'] ); ?></span>
                        </span>
                        <span class="cat__go"><?php echo stylen_icon( 'arrow', 'icon' ); ?></span>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- ============ WHY — advantages ============ -->
    <section class="section section--tint">
        <div class="container">
            <header class="head" data-reveal>
                <div class="head__top">
                    <div>
                        <span class="eyebrow"><?php echo esc_html( $f( 'home_adv_eyebrow' ) ); ?></span>
                        <h2 class="head__title"><?php echo wp_kses_post( $f( 'home_adv_title' ) ); ?></h2>
                    </div>
                    <p class="head__text"><?php echo esc_html( $f( 'home_adv_text' ) ); ?></p>
                </div>
            </header>

            <?php $adv_img = $f( 'home_adv_image' ); ?>
            <?php if ( $adv_img ) : ?>
                <figure class="prodshot" data-reveal>
                    <?php echo wp_get_attachment_image( $adv_img, 'large', false, [ 'class' => 'prodshot__img', 'loading' => 'lazy' ] ); ?>
                </figure>
            <?php endif; ?>

            <div class="feats">
                <?php $i = 0; while ( have_rows( 'home_adv_items', $pid ) ) : the_row(); ?>
                    <div class="feat" data-reveal style="--d:<?php echo ( $i % 2 ) * 60; ?>ms">
                        <span class="feat__icon"><?php echo stylen_icon( (string) get_sub_field( 'a_icon' ), 'icon' ); ?></span>
                        <h3 class="feat__title"><?php echo esc_html( get_sub_field( 'a_title' ) ); ?></h3>
                        <p class="feat__text"><?php echo esc_html( get_sub_field( 'a_text' ) ); ?></p>
                    </div>
                <?php $i++; endwhile; ?>
            </div>
        </div>
    </section>

    <!-- ============ STATS — production metrics panel (dark insert) ============ -->
    <section class="section stats-sec section--dark">
        <div class="container">
            <header class="stats-head" data-reveal>
                <span class="eyebrow"><?php echo esc_html( $f( 'home_stats_eyebrow' ) ); ?></span>
                <h2 class="stats-head__title"><?php echo esc_html( $f( 'home_stats_title' ) ); ?></h2>
            </header>
            <ul class="metrics" data-reveal>
                <?php while ( have_rows( 'home_stats_items', $pid ) ) : the_row();
                    $num = (string) get_sub_field( 's_number' );
                    $suf = (string) get_sub_field( 's_suffix' );
                    ?>
                    <li class="metric">
                        <span class="metric__icon"><?php echo stylen_icon( (string) get_sub_field( 's_icon' ), 'icon' ); ?></span>
                        <span class="metric__n tnum"><?php
                            if ( get_sub_field( 's_countup' ) ) {
                                echo '<span data-count="' . esc_attr( $num ) . '" data-suffix="">0</span>';
                            } else {
                                echo esc_html( $num );
                            }
                            if ( '' !== $suf ) {
                                echo '<i>' . esc_html( $suf ) . '</i>';
                            }
                            ?></span>
                        <span class="metric__l"><?php echo esc_html( get_sub_field( 's_label' ) ); ?></span>
                    </li>
                <?php endwhile; ?>
            </ul>
        </div>
    </section>

    <!-- ============ PROCESS — pipeline ============ -->
    <section class="section section--tint">
        <div class="container">
            <header class="head" data-reveal>
                <div class="head__top">
                    <div>
                        <span class="eyebrow"><?php echo esc_html( $f( 'home_process_eyebrow' ) ); ?></span>
                        <h2 class="head__title"><?php echo esc_html( $f( 'home_process_title' ) ); ?></h2>
                    </div>
                </div>
            </header>

            <ol class="pipeline">
                <?php $i = 0; while ( have_rows( 'home_process_items', $pid ) ) : the_row(); ?>
                    <li class="pipeline__step" data-reveal style="--d:<?php echo $i * 60; ?>ms">
                        <span class="pipeline__n tnum"><?php echo sprintf( '%02d', $i + 1 ); ?></span>
                        <h3 class="pipeline__title"><?php echo esc_html( get_sub_field( 'p_title' ) ); ?></h3>
                        <p class="pipeline__text"><?php echo esc_html( get_sub_field( 'p_text' ) ); ?></p>
                    </li>
                <?php $i++; endwhile; ?>
            </ol>
        </div>
    </section>

    <!-- ============ WORKS — gallery (dark insert) ============ -->
    <section id="works" class="section section--dark">
        <div class="container">
            <header class="head" data-reveal>
                <div class="head__top">
                    <div>
                        <span class="eyebrow"><?php echo esc_html( $f( 'home_works_eyebrow' ) ); ?></span>
                        <h2 class="head__title"><?php echo esc_html( $f( 'home_works_title' ) ); ?></h2>
                    </div>
                    <a class="btn btn--link" href="<?php echo esc_url( home_url( '/portfolio/' ) ); ?>"><?php echo esc_html( $f( 'home_works_link' ) ); ?> <?php echo stylen_icon( 'arrow', 'icon btn__arrow' ); ?></a>
                </div>
            </header>

            <div class="works">
                <?php
                // Latest 6 portfolio works flagged «Избранная работа»; fall back
                // to the latest works if none are flagged yet.
                $works_args = [
                    'post_type'      => 'portfolio',
                    'post_status'    => 'publish',
                    'posts_per_page' => 6,
                    'orderby'        => 'date',
                    'order'          => 'DESC',
                ];
                $works_q = new WP_Query( $works_args + [ 'meta_key' => 'pf_featured', 'meta_value' => '1' ] );
                if ( ! $works_q->have_posts() ) {
                    $works_q = new WP_Query( $works_args );
                }
                $i = 0;
                while ( $works_q->have_posts() ) : $works_q->the_post();
                    $terms   = get_the_terms( get_the_ID(), 'portfolio_cat' );
                    $tag     = ( $terms && ! is_wp_error( $terms ) ) ? $terms[0]->name : '';
                    $has_img = has_post_thumbnail();
                    $cover   = get_post_meta( get_the_ID(), 'pf_cover', true ) ?: 'a';
                    $icon    = get_post_meta( get_the_ID(), 'pf_icon', true ) ?: 'star';
                    ?>
                    <a class="work" href="<?php the_permalink(); ?>" data-reveal style="--d:<?php echo ( $i % 3 ) * 45; ?>ms">
                        <span class="work__cover<?php echo $has_img ? '' : ' pf-cover--' . esc_attr( $cover ); ?>">
                            <?php echo $has_img ? get_the_post_thumbnail( null, 'large', [ 'alt' => '' ] ) : stylen_icon( $icon, 'icon' ); ?>
                        </span>
                        <span class="work__meta">
                            <?php if ( $tag ) : ?><span class="work__tag"><?php echo esc_html( $tag ); ?></span><?php endif; ?>
                            <span class="work__title"><?php the_title(); ?></span>
                        </span>
                    </a>
                <?php $i++; endwhile; wp_reset_postdata(); ?>
            </div>
        </div>
    </section>

    <!-- ============ REVIEWS ============ -->
    <section id="reviews" class="section section--tint">
        <div class="container">
            <header class="head" data-reveal>
                <div class="head__top">
                    <div>
                        <span class="eyebrow"><?php echo esc_html( $f( 'home_reviews_eyebrow' ) ); ?></span>
                        <h2 class="head__title"><?php echo esc_html( $f( 'home_reviews_title' ) ); ?></h2>
                    </div>
                </div>
            </header>

            <div class="reviews">
                <?php $i = 0; while ( have_rows( 'home_reviews_items', $pid ) ) : the_row();
                    $rating = (int) get_sub_field( 'r_rating' );
                    $rating = ( $rating < 1 || $rating > 5 ) ? 5 : $rating;
                    ?>
                    <blockquote class="review" data-reveal style="--d:<?php echo ( $i % 3 ) * 45; ?>ms">
                        <span class="review__stars" role="img" aria-label="Оценка <?php echo $rating; ?> из 5">
                            <?php for ( $s = 1; $s <= 5; $s++ ) { echo stylen_icon( 'star', 'icon' . ( $s <= $rating ? ' is-on' : '' ) ); } ?>
                        </span>
                        <p class="review__text"><?php echo esc_html( get_sub_field( 'r_text' ) ); ?></p>
                        <footer class="review__by">
                            <span class="review__name"><?php echo esc_html( get_sub_field( 'r_name' ) ); ?></span>
                            <span class="review__role"><?php echo esc_html( get_sub_field( 'r_role' ) ); ?></span>
                        </footer>
                    </blockquote>
                <?php $i++; endwhile; ?>
            </div>
        </div>
    </section>

    <!-- ============ ORDER — the estimate, realised ============ -->
    <section id="order" class="order">
        <div class="container order__grid">
            <div class="order__intro" data-reveal>
                <span class="eyebrow eyebrow--light"><?php echo esc_html( $f( 'home_order_eyebrow' ) ); ?></span>
                <h2 class="order__title"><?php echo esc_html( $f( 'home_order_title' ) ); ?></h2>
                <p class="order__text"><?php echo esc_html( $f( 'home_order_text' ) ); ?></p>
                <ul class="order__list">
                    <?php while ( have_rows( 'home_order_list', $pid ) ) : the_row(); ?>
                        <li><?php echo stylen_icon( 'check', 'icon icon--sm' ); ?><?php echo esc_html( get_sub_field( 'o_item' ) ); ?></li>
                    <?php endwhile; ?>
                </ul>
                <div class="order__contacts">
                    <a href="<?php echo esc_attr( $c['phone_href'] ); ?>"><?php echo stylen_icon( 'phone' ); ?><?php echo esc_html( $c['phone'] ); ?></a>
                    <a href="mailto:<?php echo esc_attr( $c['email'] ); ?>"><?php echo stylen_icon( 'mail' ); ?><?php echo esc_html( $c['email'] ); ?></a>
                </div>
            </div>

            <div class="order__card" data-reveal>
                <?php
                if ( $form_id ) {
                    echo do_shortcode( '[contact-form-7 id="' . (int) $form_id . '" title="Заявка на расчёт"]' );
                } else {
                    echo '<p>Позвоните нам: <a href="' . esc_attr( $c['phone_href'] ) . '">' . esc_html( $c['phone'] ) . '</a>.</p>';
                }
                ?>
            </div>
        </div>
    </section>

    <!-- ============ MAP ============ -->
    <section id="map" class="section">
        <div class="container">
            <header class="head" data-reveal>
                <div class="head__top">
                    <div>
                        <span class="eyebrow"><?php echo esc_html( $f( 'home_map_eyebrow' ) ); ?></span>
                        <h2 class="head__title"><?php echo esc_html( $f( 'home_map_title' ) ); ?></h2>
                    </div>
                    <p class="head__text"><?php echo esc_html( $f( 'home_map_text' ) ); ?></p>
                </div>
            </header>
            <div class="mapblock" data-reveal>
                <div class="mapblock__card">
                    <h3 class="mapblock__title"><?php echo esc_html( $f( 'home_map_card_title' ) ); ?></h3>
                    <ul class="mapblock__list">
                        <li>
                            <span class="mapblock__ico"><?php echo stylen_icon( 'pin' ); ?></span>
                            <span class="mapblock__rows"><b>Адрес</b><span><?php echo esc_html( $c['zip'] . ', ' . $c['address'] ); ?></span></span>
                        </li>
                        <li>
                            <span class="mapblock__ico"><?php echo stylen_icon( 'phone' ); ?></span>
                            <span class="mapblock__rows"><b>Телефон</b><a href="<?php echo esc_attr( $c['phone_href'] ); ?>"><?php echo esc_html( $c['phone'] ); ?></a></span>
                        </li>
                        <li>
                            <span class="mapblock__ico"><?php echo stylen_icon( 'mail' ); ?></span>
                            <span class="mapblock__rows"><b>Почта</b><a href="mailto:<?php echo esc_attr( $c['email'] ); ?>"><?php echo esc_html( $c['email'] ); ?></a></span>
                        </li>
                        <li>
                            <span class="mapblock__ico"><?php echo stylen_icon( 'clock' ); ?></span>
                            <span class="mapblock__rows"><b>Часы работы</b><span><?php echo esc_html( $c['hours'] ); ?></span></span>
                        </li>
                    </ul>
                </div>
                <div class="mapblock__map">
                    <iframe src="<?php echo esc_url( $map_src ); ?>" width="100%" height="440" frameborder="0" loading="lazy" title="Стиль-Н на карте Воронежа" style="border:0;"></iframe>
                </div>
            </div>
        </div>
    </section>

    <!-- ============ FAQ ============ -->
    <section class="section section--tint faq">
        <div class="container faq__grid">
            <div class="faq__intro" data-reveal>
                <span class="eyebrow"><?php echo esc_html( $f( 'home_faq_eyebrow' ) ); ?></span>
                <h2 class="faq__title"><?php echo esc_html( $f( 'home_faq_title' ) ); ?></h2>
                <p class="faq__text"><?php echo esc_html( $f( 'home_faq_text' ) ); ?></p>
                <div class="faq__actions">
                    <a class="btn btn--primary" href="#order"><?php echo esc_html( $f( 'home_faq_cta' ) ); ?> <?php echo stylen_icon( 'arrow', 'icon btn__arrow' ); ?></a>
                    <a class="faq__phone" href="<?php echo esc_attr( $c['phone_href'] ); ?>"><?php echo stylen_icon( 'phone', 'icon icon--sm' ); ?><?php echo esc_html( $c['phone'] ); ?></a>
                </div>
            </div>
            <div class="faq__list" data-reveal>
                <?php foreach ( $faq as $n => $item ) : ?>
                    <div class="faq__item">
                        <h3 class="faq__heading">
                            <button type="button" id="faq-q-<?php echo $n; ?>" class="faq__q" aria-expanded="false" aria-controls="faq-<?php echo $n; ?>">
                                <span><?php echo esc_html( $item['f_q'] ); ?></span>
                                <?php echo stylen_icon( 'plus', 'icon faq__toggle' ); ?>
                            </button>
                        </h3>
                        <div class="faq__body" id="faq-<?php echo $n; ?>" role="region" aria-labelledby="faq-q-<?php echo $n; ?>">
                            <div class="faq__answer"><p><?php echo esc_html( $item['f_a'] ); ?></p></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

</main>

<?php
if ( ! empty( $faq ) ) {
    $faq_ld = [
        '@context'   => 'https://schema.org',
        '@type'      => 'FAQPage',
        'mainEntity' => array_map( function ( $i ) {
            return [ '@type' => 'Question', 'name' => $i['f_q'], 'acceptedAnswer' => [ '@type' => 'Answer', 'text' => $i['f_a'] ] ];
        }, $faq ),
    ];
    echo '<script type="application/ld+json">' . wp_json_encode( $faq_ld, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . '</script>' . "\n";
}

get_footer();

<?php
/**
 * Template Name: Контакты
 *
 * @package Stylen
 */

$c       = stylen_contacts();
$form_id = get_option( 'stylen_order_form_id' );
$map_src = 'https://yandex.ru/map-widget/v1/?text=' . rawurlencode( $c['zip'] . ', ' . $c['address'] ) . '&z=16';

$pid = get_queried_object_id();
$f   = function ( $k ) use ( $pid ) {
    return function_exists( 'get_field' ) ? get_field( $k, $pid ) : '';
};

get_header();
?>

<main id="primary">

    <!-- ============ HERO ============ -->
    <section class="page-hero">
        <div class="container">
            <div class="page-hero__inner">
                <?php stylen_breadcrumbs(); ?>
                <p class="page-hero__kicker"><?php echo esc_html( $f( 'ct_hero_kicker' ) ); ?></p>
                <h1 class="page-hero__title"><?php echo wp_kses_post( $f( 'ct_hero_title' ) ); ?></h1>
                <p class="page-hero__lead"><?php echo esc_html( $f( 'ct_hero_lead' ) ); ?></p>
                <div class="page-hero__actions">
                    <a class="btn btn--primary btn--lg" href="#order"><?php echo esc_html( $f( 'ct_hero_cta1' ) ); ?> <?php echo stylen_icon( 'arrow', 'icon btn__arrow' ); ?></a>
                    <a class="btn btn--outline btn--lg" href="<?php echo esc_attr( $c['phone_href'] ); ?>"><?php echo stylen_icon( 'phone', 'icon' ); ?> <?php echo esc_html( $f( 'ct_hero_cta2' ) ); ?></a>
                </div>
            </div>
        </div>
    </section>

    <!-- ============ CONTACT CARDS ============ -->
    <section class="section">
        <div class="container">
            <div class="contact-cards">
                <?php
                // Fixed card types (icon / value / link) filled from contacts;
                // the label + note come from the ACF repeater, by row order.
                $card_types = [
                    [ 'phone', $c['phone'],   $c['phone_href'],              false ],
                    [ 'mail',  $c['email'],   'mailto:' . $c['email'],       false ],
                    [ 'pin',   $c['address'], $c['map'],                     true ],
                    [ 'clock', $c['hours'],   '',                            false ],
                ];
                $ci = 0;
                while ( have_rows( 'ct_cards' ) ) : the_row();
                    $t = $card_types[ $ci ] ?? null;
                    if ( ! $t ) { $ci++; continue; }
                    $href  = $t[2];
                    $open  = $href
                        ? '<a class="contact-card" href="' . ( $t[3] ? esc_url( $href ) : esc_attr( $href ) ) . '"' . ( $t[3] ? ' target="_blank" rel="noopener"' : '' ) . ' data-reveal style="--d:' . ( $ci * 55 ) . 'ms">'
                        : '<div class="contact-card" data-reveal style="--d:' . ( $ci * 55 ) . 'ms">';
                    echo $open;
                    ?>
                        <span class="contact-card__icon"><?php echo stylen_icon( $t[0], 'icon' ); ?></span>
                        <span class="contact-card__label"><?php echo esc_html( get_sub_field( 'label' ) ); ?></span>
                        <span class="contact-card__value"><?php echo esc_html( $t[1] ); ?></span>
                        <p class="contact-card__note"><?php echo esc_html( get_sub_field( 'note' ) ); ?></p>
                    <?php echo $href ? '</a>' : '</div>'; ?>
                    <?php $ci++;
                endwhile;
                ?>
            </div>
        </div>
    </section>

    <!-- ============ DEPARTMENTS + MAP ============ -->
    <section class="section section--tint section--flush-top" style="padding-top:var(--section-y)">
        <div class="container">
            <header class="section__head section__head--left" data-reveal style="margin-bottom:var(--s-8)">
                <p class="section__kicker"><?php echo esc_html( $f( 'ct_dept_kicker' ) ); ?></p>
                <h2 class="section__title"><?php echo esc_html( $f( 'ct_dept_title' ) ); ?></h2>
            </header>
            <div class="contact-split">
                <div data-reveal>
                    <ul class="dept-list">
                        <?php while ( have_rows( 'ct_departments' ) ) : the_row();
                            $dep_href = (string) get_sub_field( 'dep_href' );
                            $external = (bool) preg_match( '#^https?://#i', $dep_href );
                            ?>
                            <li class="dept">
                                <span class="dept__icon"><?php echo stylen_icon( (string) get_sub_field( 'icon' ), 'icon icon--sm' ); ?></span>
                                <span>
                                    <span class="dept__name"><?php echo esc_html( get_sub_field( 'dep_name' ) ); ?></span><br>
                                    <span class="dept__role"><?php echo esc_html( get_sub_field( 'dep_role' ) ); ?></span>
                                </span>
                                <a class="dept__contact" href="<?php echo $external ? esc_url( $dep_href ) : esc_attr( $dep_href ); ?>"<?php echo $external ? ' target="_blank" rel="noopener"' : ''; ?>><?php echo esc_html( get_sub_field( 'dep_contact' ) ); ?></a>
                            </li>
                        <?php endwhile; ?>
                    </ul>

                    <div class="requisites">
                        <h3><?php echo esc_html( $f( 'ct_req_title' ) ); ?></h3>
                        <dl>
                            <?php while ( have_rows( 'ct_requisites' ) ) : the_row(); ?>
                                <dt><?php echo esc_html( get_sub_field( 'label' ) ); ?></dt><dd><?php echo esc_html( get_sub_field( 'value' ) ); ?></dd>
                            <?php endwhile; ?>
                        </dl>
                    </div>
                </div>

                <div class="contact-map" data-reveal style="--d:80ms">
                    <iframe src="<?php echo esc_url( $map_src ); ?>" width="100%" height="460" loading="lazy" title="Стиль-Н на карте Воронежа"></iframe>
                </div>
            </div>
        </div>
    </section>

    <!-- ============ MANAGERS ============ -->
    <?php if ( have_rows( 'ct_managers' ) ) : ?>
        <section class="section">
            <div class="container">
                <header class="section__head section__head--left" data-reveal>
                    <p class="section__kicker"><?php echo esc_html( $f( 'ct_mgr_kicker' ) ); ?></p>
                    <h2 class="section__title"><?php echo esc_html( $f( 'ct_mgr_title' ) ); ?></h2>
                </header>
                <div class="mgr-grid">
                    <?php $mi = 0; while ( have_rows( 'ct_managers' ) ) : the_row();
                        $photo = get_sub_field( 'm_photo' );
                        $phone = (string) get_sub_field( 'm_phone' );
                        $tel   = 'tel:+' . preg_replace( '/\D+/', '', $phone );
                        $email = (string) get_sub_field( 'm_email' );
                        ?>
                        <article class="mgr" data-reveal style="--d:<?php echo ( $mi % 4 ) * 55; ?>ms">
                            <div class="mgr__photo">
                                <?php
                                if ( $photo ) {
                                    echo wp_get_attachment_image( $photo, 'medium_large', false, [ 'loading' => 'lazy' ] );
                                }
                                ?>
                            </div>
                            <div class="mgr__body">
                                <h3 class="mgr__name"><?php echo esc_html( get_sub_field( 'm_name' ) ); ?></h3>
                                <p class="mgr__role"><?php echo esc_html( get_sub_field( 'm_role' ) ); ?></p>
                                <?php if ( $phone ) : ?>
                                    <a class="mgr__phone" href="<?php echo esc_attr( $tel ); ?>"><?php echo stylen_icon( 'phone', 'icon icon--sm' ); ?><?php echo esc_html( $phone ); ?></a>
                                <?php endif; ?>
                                <?php if ( $email ) : ?>
                                    <a class="mgr__mail" href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo stylen_icon( 'mail', 'icon icon--sm' ); ?><?php echo esc_html( $email ); ?></a>
                                <?php endif; ?>
                            </div>
                        </article>
                    <?php $mi++; endwhile; ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <!-- ============ ORDER FORM ============ -->
    <section id="order" class="section">
        <div class="container">
            <div class="order" data-reveal>
                <div class="order__info">
                    <p class="section__kicker"><?php echo esc_html( $f( 'ct_order_kicker' ) ); ?></p>
                    <h2 class="section__title"><?php echo esc_html( $f( 'ct_order_title' ) ); ?></h2>
                    <p class="order__text"><?php echo esc_html( $f( 'ct_order_text' ) ); ?></p>
                    <ul class="order__contacts">
                        <li><a href="<?php echo esc_attr( $c['phone_href'] ); ?>"><?php echo stylen_icon( 'phone' ); ?><?php echo esc_html( $c['phone'] ); ?></a></li>
                        <li><a href="mailto:<?php echo esc_attr( $c['email'] ); ?>"><?php echo stylen_icon( 'mail' ); ?><?php echo esc_html( $c['email'] ); ?></a></li>
                        <li><a href="<?php echo esc_url( $c['map'] ); ?>" target="_blank" rel="noopener"><?php echo stylen_icon( 'pin' ); ?><?php echo esc_html( $c['zip'] . ', ' . $c['address'] ); ?></a></li>
                    </ul>
                </div>
                <div class="order__form">
                    <?php
                    if ( $form_id ) {
                        echo do_shortcode( '[contact-form-7 id="' . (int) $form_id . '" title="Заявка на расчёт"]' );
                    } else {
                        echo '<p>Позвоните нам: <a href="' . esc_attr( $c['phone_href'] ) . '">' . esc_html( $c['phone'] ) . '</a>.</p>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </section>

</main>

<?php
get_footer();

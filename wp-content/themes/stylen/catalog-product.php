<?php
/**
 * Catalog product — a terminal page (no children).
 * Routed here via the template_include filter in functions.php.
 *
 * @package Stylen
 */

$c = stylen_contacts();

get_header();

while ( have_posts() ) : the_post();

    $post_obj    = get_queried_object();
    $pm          = stylen_product_meta( $post_obj->ID );
    $direction   = stylen_direction_of( $post_obj );
    $icon        = stylen_catalog_icon( $post_obj );
    $content     = trim( get_the_content() );
    $catalog_url = get_permalink( stylen_catalog_root() );
    $order_url   = home_url( '/kontakty/#order' );

    // Sibling products in the same direction (for "смотрите также").
    $siblings = array_filter( stylen_child_pages( $post_obj->post_parent ), function ( $p ) use ( $post_obj ) {
        return (int) $p->ID !== (int) $post_obj->ID;
    } );

    ?>

    <main id="primary">

        <!-- ============ HERO ============ -->
        <?php $has_bg = (bool) stylen_catalog_hero_bg( $post_obj ); ?>
        <section class="page-hero<?php echo $has_bg ? ' page-hero--media' : ''; ?>">
            <?php stylen_catalog_hero_backdrop( $post_obj ); ?>
            <div class="container">
                <div class="page-hero__inner page-hero__inner--wide">
                    <?php stylen_breadcrumbs(); ?>
                    <p class="page-hero__kicker"><?php echo esc_html( $direction ? $direction->post_title : 'Каталог' ); ?></p>
                    <h1 class="page-hero__title"><?php the_title(); ?></h1>
                    <?php if ( $pm['desc'] ) : ?>
                        <p class="page-hero__lead"><?php echo esc_html( $pm['desc'] ); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <!-- ============ PRODUCT ============ -->
        <section class="section">
            <div class="container">
                <div class="product">
                    <div>
                        <div class="product__media pf-cover--<?php echo esc_attr( stylen_catalog_cover_class( $post_obj->ID ) ); ?>" data-reveal>
                            <?php if ( has_post_thumbnail() ) : ?>
                                <?php the_post_thumbnail( 'large', [ 'alt' => '' ] ); ?>
                            <?php else : ?>
                                <?php echo stylen_icon( $icon, 'icon' ); ?>
                                <span class="product__media-note"><?php echo esc_html( $direction ? $direction->post_title : $c['company'] ); ?></span>
                            <?php endif; ?>
                        </div>

                        <?php $trust = (array) get_field( 'product_trust', 'option' ); ?>
                        <?php if ( $trust ) : ?>
                            <ul class="product__trust">
                                <?php foreach ( $trust as $tr ) : ?>
                                    <li class="ptrust">
                                        <span class="ptrust__icon"><?php echo stylen_icon( (string) ( $tr['icon'] ?: 'check' ), 'icon' ); ?></span>
                                        <span class="ptrust__body">
                                            <span class="ptrust__title"><?php echo esc_html( $tr['title'] ); ?></span>
                                            <?php if ( ! empty( $tr['text'] ) ) : ?>
                                                <span class="ptrust__text"><?php echo esc_html( $tr['text'] ); ?></span>
                                            <?php endif; ?>
                                        </span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>

                    <aside class="product__aside" data-reveal style="--d:80ms">
                        <?php if ( ! empty( $pm['price'] ) ) : ?>
                            <div class="product__price">
                                <span class="product__price-label">Стоимость</span>
                                <span class="product__price-value"><?php echo esc_html( $pm['price'] ); ?></span>
                            </div>
                        <?php endif; ?>

                        <?php if ( $pm['desc'] ) : ?>
                            <p class="product__desc"><?php echo esc_html( $pm['desc'] ); ?></p>
                        <?php endif; ?>

                        <?php if ( ! empty( $pm['specs'] ) ) : ?>
                            <ul class="spec-list">
                                <?php foreach ( $pm['specs'] as $k => $v ) : ?>
                                    <li><span class="spec__k"><?php echo esc_html( $k ); ?></span><span class="spec__v"><?php echo esc_html( $v ); ?></span></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>

                        <div class="product__actions">
                            <a class="btn btn--gold btn--lg" href="<?php echo esc_url( $order_url ); ?>">Оставить заявку <?php echo stylen_icon( 'arrow', 'icon btn__arrow' ); ?></a>
                            <a class="btn btn--outline" href="<?php echo esc_attr( $c['phone_href'] ); ?>"><?php echo stylen_icon( 'phone', 'icon' ); ?> <?php echo esc_html( $c['phone'] ); ?></a>
                        </div>
                        <p class="product__note"><?php echo stylen_icon( 'check', 'icon icon--sm' ); ?>Бесплатный расчёт и&nbsp;макет&nbsp;— ни&nbsp;к&nbsp;чему не&nbsp;обязывает</p>
                    </aside>
                </div>
            </div>
        </section>

        <?php $about = $pm['body']; ?>
        <?php if ( $about || $content ) : ?>
            <!-- ============ SERVICE DESCRIPTION ============ -->
            <section class="section section--tint">
                <div class="container">
                    <header class="section__head" data-reveal>
                        <p class="section__kicker"><?php echo esc_html( get_field( 'cat_product_about_kicker', 'option' ) ); ?></p>
                        <h2 class="section__title"><?php echo esc_html( get_the_title() ); ?></h2>
                    </header>
                    <div class="product-body" data-reveal>
                        <?php
                        if ( $about ) {
                            echo wp_kses_post( $about );
                        }
                        if ( $content ) {
                            echo '<div class="entry-content">';
                            the_content();
                            echo '</div>';
                        }
                        ?>
                    </div>
                </div>
            </section>
        <?php endif; ?>

        <!-- ============ SIBLINGS ============ -->
        <?php $desc_shown = ( $about || $content ); ?>
        <?php if ( ! empty( $siblings ) ) : ?>
            <section class="section<?php echo $desc_shown ? '' : ' section--tint'; ?>">
                <div class="container">
                    <header class="section__head" data-reveal>
                        <p class="section__kicker"><?php echo esc_html( get_field( 'cat_product_related_kicker', 'option' ) ); ?></p>
                        <h2 class="section__title"><?php echo esc_html( $direction ? $direction->post_title : 'Похожее' ); ?></h2>
                    </header>
                    <div class="cat-grid cat-grid--3">
                        <?php foreach ( array_slice( array_values( $siblings ), 0, 3 ) as $i => $sib ) :
                            $sm = stylen_product_meta( $sib->ID ); ?>
                            <a class="cat-card" href="<?php echo esc_url( get_permalink( $sib->ID ) ); ?>" data-reveal style="--d:<?php echo $i * 55; ?>ms">
                                <?php stylen_catalog_cover( $sib ); ?>
                                <span class="cat-card__body">
                                    <?php if ( ! empty( $sm['price'] ) ) : ?>
                                        <span class="cat-card__price" style="position:static;align-self:flex-start;margin-bottom:var(--s-1)"><?php echo esc_html( $sm['price'] ); ?></span>
                                    <?php endif; ?>
                                    <h3 class="cat-card__title"><?php echo esc_html( $sib->post_title ); ?></h3>
                                    <p class="cat-card__desc"><?php echo esc_html( $sm['desc'] ? wp_trim_words( $sm['desc'], 14 ) : '' ); ?></p>
                                    <span class="cat-card__link">Подробнее <?php echo stylen_icon( 'arrow', 'icon icon--sm' ); ?></span>
                                </span>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>
        <?php endif; ?>

        <!-- ============ CTA ============ -->
        <section class="section" style="padding-top:0">
            <div class="container">
                <div class="cta-band" data-reveal>
                    <div>
                        <h2 class="cta-band__title"><?php echo esc_html( get_field( 'cat_product_cta_title', 'option' ) ); ?></h2>
                        <p class="cta-band__text"><?php echo esc_html( sprintf( (string) get_field( 'cat_product_cta_text', 'option' ), get_the_title() ) ); ?></p>
                    </div>
                    <div class="cta-band__actions">
                        <a class="btn btn--gold btn--lg" href="<?php echo esc_url( $order_url ); ?>">Оставить заявку <?php echo stylen_icon( 'arrow', 'icon btn__arrow' ); ?></a>
                        <a class="btn btn--ghost-light btn--lg" href="<?php echo esc_attr( $c['phone_href'] ); ?>"><?php echo esc_html( $c['phone'] ); ?></a>
                    </div>
                </div>
            </div>
        </section>

    </main>

    <?php
endwhile;

get_footer();

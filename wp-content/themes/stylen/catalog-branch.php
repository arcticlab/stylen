<?php
/**
 * Catalog branch — a direction page (has child pages).
 * Routed here via the template_include filter in functions.php.
 *
 * @package Stylen
 */

$c = stylen_contacts();

get_header();

while ( have_posts() ) : the_post();

    $post_obj   = get_queried_object();
    $meta       = stylen_direction_meta( $post_obj->post_name );
    $children   = stylen_child_pages( $post_obj->ID );
    $directions = stylen_get_directions();
    $content    = trim( get_the_content() );
    $catalog_url = get_permalink( stylen_catalog_root() );

    // Section headings — editable per direction in the admin (ACF), with fallbacks.
    $about_title    = ( function_exists( 'get_field' ) && get_field( 'branch_about_title' ) ) ? get_field( 'branch_about_title' ) : 'Подробнее о направлении';
    $products_title = ( function_exists( 'get_field' ) && get_field( 'branch_products_title' ) ) ? get_field( 'branch_products_title' ) : 'Что входит в направление';
    ?>

    <main id="primary">

        <!-- ============ HERO ============ -->
        <section class="page-hero">
            <div class="container">
                <div class="page-hero__inner page-hero__inner--wide">
                    <?php stylen_breadcrumbs(); ?>
                    <p class="page-hero__kicker"><?php echo esc_html( get_field( 'cat_branch_kicker', 'option' ) ); ?></p>
                    <h1 class="page-hero__title"><?php the_title(); ?></h1>
                    <?php if ( $meta['desc'] ) : ?>
                        <p class="page-hero__lead"><?php echo esc_html( $meta['desc'] ); ?></p>
                    <?php endif; ?>
                    <?php $dir_price = function_exists( 'get_field' ) ? trim( (string) get_field( 'direction_price' ) ) : ''; ?>
                    <?php if ( $dir_price ) : ?>
                        <p class="price-from"><span class="price-from__label">Стоимость</span> <span class="price-from__value"><?php echo esc_html( $dir_price ); ?></span></p>
                    <?php endif; ?>
                    <div class="page-hero__actions">
                        <a class="btn btn--primary btn--lg" href="<?php echo esc_url( home_url( '/kontakty/#order' ) ); ?>">Рассчитать стоимость <?php echo stylen_icon( 'arrow', 'icon btn__arrow' ); ?></a>
                        <a class="btn btn--outline btn--lg" href="<?php echo esc_attr( $c['phone_href'] ); ?>"><?php echo stylen_icon( 'phone', 'icon' ); ?> <?php echo esc_html( $c['phone'] ); ?></a>
                    </div>
                </div>
            </div>
        </section>

        <?php $has_desc = ( $meta['body'] || $content ); ?>

        <!-- ============ SERVICE DESCRIPTION (moved above the products) ============ -->
        <?php if ( $has_desc ) : ?>
            <section class="section section--tint">
                <div class="container">
                    <header class="section__head" data-reveal>
                        <p class="section__kicker">Об услуге</p>
                        <h2 class="section__title"><?php echo esc_html( $about_title ); ?></h2>
                    </header>
                    <div class="cat-prose" data-reveal>
                        <?php
                        if ( $meta['body'] ) {
                            echo wp_kses_post( $meta['body'] );
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

        <!-- ============ PRODUCTS ============ -->
        <section class="section">
            <div class="container">
                <header class="section__head" data-reveal>
                    <p class="section__kicker">Виды и услуги</p>
                    <h2 class="section__title"><?php echo esc_html( $products_title ); ?></h2>
                </header>
                <div class="cat-grid cat-grid--3">
                    <?php foreach ( $children as $i => $child ) :
                        $pm = stylen_product_meta( $child->ID );
                        ?>
                        <a class="cat-card" href="<?php echo esc_url( get_permalink( $child->ID ) ); ?>" data-reveal style="--d:<?php echo ( $i % 3 ) * 55; ?>ms">
                            <?php stylen_catalog_cover( $child ); ?>
                            <span class="cat-card__body">
                                <?php if ( ! empty( $pm['price'] ) ) : ?>
                                    <span class="cat-card__price" style="position:static;align-self:flex-start;margin-bottom:var(--s-1)"><?php echo esc_html( $pm['price'] ); ?></span>
                                <?php endif; ?>
                                <h3 class="cat-card__title"><?php echo esc_html( $child->post_title ); ?></h3>
                                <p class="cat-card__desc"><?php echo esc_html( $pm['desc'] ? wp_trim_words( $pm['desc'], 16 ) : '' ); ?></p>
                                <span class="cat-card__link">Подробнее <?php echo stylen_icon( 'arrow', 'icon icon--sm' ); ?></span>
                            </span>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <!-- ============ CTA ============ -->
        <section class="section">
            <div class="container">
                <div class="cta-band" data-reveal>
                    <div>
                        <h2 class="cta-band__title"><?php echo esc_html( get_field( 'cat_branch_cta_title', 'option' ) ); ?></h2>
                        <p class="cta-band__text"><?php echo esc_html( get_field( 'cat_branch_cta_text', 'option' ) ); ?></p>
                    </div>
                    <div class="cta-band__actions">
                        <a class="btn btn--gold btn--lg" href="<?php echo esc_url( home_url( '/kontakty/#order' ) ); ?>">Оставить заявку <?php echo stylen_icon( 'arrow', 'icon btn__arrow' ); ?></a>
                        <a class="btn btn--ghost-light btn--lg" href="<?php echo esc_attr( $c['phone_href'] ); ?>"><?php echo esc_html( $c['phone'] ); ?></a>
                    </div>
                </div>
            </div>
        </section>

        <!-- ============ OTHER DIRECTIONS (moved to the very bottom) ============ -->
        <section class="section section--tint">
            <div class="container">
                <header class="section__head" data-reveal>
                    <p class="section__kicker">Каталог</p>
                    <h2 class="section__title">Другие направления</h2>
                </header>
                <nav class="cat-nav" aria-label="Направления каталога" style="margin-bottom:0">
                    <a href="<?php echo esc_url( $catalog_url ); ?>">Все направления</a>
                    <?php foreach ( $directions as $d ) :
                        $dm = stylen_direction_meta( $d->post_name );
                        $active = (int) $d->ID === (int) $post_obj->ID;
                        ?>
                        <a href="<?php echo esc_url( get_permalink( $d->ID ) ); ?>"<?php echo $active ? ' class="is-active" aria-current="page"' : ''; ?>>
                            <?php echo stylen_icon( $dm['icon'], 'icon' ); ?><?php echo esc_html( $d->post_title ); ?>
                        </a>
                    <?php endforeach; ?>
                </nav>
            </div>
        </section>

    </main>

    <?php
endwhile;

get_footer();

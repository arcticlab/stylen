<?php
/**
 * Catalog home — the id-10 root page. Lists all directions.
 * Routed here via the template_include filter in functions.php.
 *
 * @package Stylen
 */

$c          = stylen_contacts();
$directions = stylen_get_directions();

get_header();

while ( have_posts() ) : the_post();
    $content = trim( get_the_content() );
    ?>

    <main id="primary">

        <!-- ============ HERO ============ -->
        <section class="page-hero">
            <div class="container">
                <div class="page-hero__inner page-hero__inner--wide">
                    <?php stylen_breadcrumbs(); ?>
                    <p class="page-hero__kicker"><?php echo esc_html( get_field( 'ch_hero_kicker' ) ); ?></p>
                    <h1 class="page-hero__title"><?php echo wp_kses_post( get_field( 'ch_hero_title' ) ); ?></h1>
                    <p class="page-hero__lead"><?php echo esc_html( get_field( 'ch_hero_lead' ) ); ?></p>
                    <div class="page-hero__actions">
                        <a class="btn btn--primary btn--lg" href="<?php echo esc_url( home_url( '/kontakty/#order' ) ); ?>">Рассчитать стоимость <?php echo stylen_icon( 'arrow', 'icon btn__arrow' ); ?></a>
                        <a class="btn btn--outline btn--lg" href="<?php echo esc_attr( $c['phone_href'] ); ?>"><?php echo stylen_icon( 'phone', 'icon' ); ?> <?php echo esc_html( $c['phone'] ); ?></a>
                    </div>
                </div>
            </div>
        </section>

        <!-- ============ DIRECTIONS ============ -->
        <section class="section">
            <div class="container">
                <header class="section__head" data-reveal>
                    <p class="section__kicker"><?php echo esc_html( get_field( 'ch_dir_kicker' ) ); ?></p>
                    <h2 class="section__title"><?php echo wp_kses_post( get_field( 'ch_dir_title' ) ); ?></h2>
                    <p class="section__subtitle"><?php echo esc_html( get_field( 'ch_dir_subtitle' ) ); ?></p>
                </header>
                <div class="cat-grid">
                    <?php foreach ( $directions as $i => $d ) :
                        $meta  = stylen_direction_meta( $d->post_name );
                        $count = count( stylen_child_pages( $d->ID ) );
                        ?>
                        <a class="cat-card" href="<?php echo esc_url( get_permalink( $d->ID ) ); ?>" data-reveal style="--d:<?php echo ( $i % 4 ) * 55; ?>ms">
                            <span class="cat-card__cover pf-cover--<?php echo esc_attr( stylen_catalog_cover_class( $d->ID ) ); ?>">
                                <?php if ( $count ) : ?><span class="cat-card__badge"><?php echo (int) $count; ?>&nbsp;<?php echo esc_html( stylen_plural( $count, 'вид', 'вида', 'видов' ) ); ?></span><?php endif; ?>
                                <?php echo stylen_icon( $meta['icon'], 'icon' ); ?>
                            </span>
                            <span class="cat-card__body">
                                <h3 class="cat-card__title"><?php echo esc_html( $d->post_title ); ?></h3>
                                <p class="cat-card__desc"><?php echo esc_html( $meta['desc'] ); ?></p>
                                <span class="cat-card__link">Смотреть <?php echo stylen_icon( 'arrow', 'icon icon--sm' ); ?></span>
                            </span>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <?php if ( $content ) : ?>
            <section class="section section--tint">
                <div class="container">
                    <div class="cat-prose entry-content"><?php the_content(); ?></div>
                </div>
            </section>
        <?php endif; ?>

        <!-- ============ WHY US ============ -->
        <section class="section<?php echo $content ? '' : ' section--tint'; ?>">
            <div class="container">
                <header class="section__head" data-reveal>
                    <p class="section__kicker"><?php echo esc_html( get_field( 'ch_why_kicker' ) ); ?></p>
                    <h2 class="section__title"><?php echo esc_html( get_field( 'ch_why_title' ) ); ?></h2>
                </header>
                <div class="values">
                    <?php $i = 0; while ( have_rows( 'ch_why_items' ) ) : the_row(); ?>
                        <article class="value" data-reveal style="--d:<?php echo $i * 55; ?>ms">
                            <span class="value__icon"><?php echo stylen_icon( (string) get_sub_field( 'icon' ), 'icon' ); ?></span>
                            <h3 class="value__title"><?php echo esc_html( get_sub_field( 'title' ) ); ?></h3>
                            <p class="value__text"><?php echo esc_html( get_sub_field( 'text' ) ); ?></p>
                        </article>
                    <?php $i++; endwhile; ?>
                </div>
            </div>
        </section>

        <!-- ============ CTA ============ -->
        <section class="section" style="padding-top:0">
            <div class="container">
                <div class="cta-band" data-reveal>
                    <div>
                        <h2 class="cta-band__title"><?php echo esc_html( get_field( 'ch_cta_title' ) ); ?></h2>
                        <p class="cta-band__text"><?php echo esc_html( get_field( 'ch_cta_text' ) ); ?></p>
                    </div>
                    <div class="cta-band__actions">
                        <a class="btn btn--gold btn--lg" href="<?php echo esc_url( home_url( '/kontakty/#order' ) ); ?>">Оставить заявку <?php echo stylen_icon( 'arrow', 'icon btn__arrow' ); ?></a>
                        <a class="btn btn--ghost-light btn--lg" href="<?php echo esc_attr( $c['phone_href'] ); ?>"><?php echo esc_html( $c['phone'] ); ?></a>
                    </div>
                </div>
            </div>
        </section>

    </main>

    <?php
endwhile;

get_footer();

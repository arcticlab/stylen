<?php
/**
 * Single portfolio work.
 *
 * @package Stylen
 */

$c = stylen_contacts();
get_header();

while ( have_posts() ) : the_post();

    $terms = get_the_terms( get_the_ID(), 'portfolio_cat' );
    $term  = ( $terms && ! is_wp_error( $terms ) ) ? $terms[0] : null;
    $cover = get_post_meta( get_the_ID(), 'pf_cover', true ) ?: 'a';
    $icon  = get_post_meta( get_the_ID(), 'pf_icon', true ) ?: 'star';

    // Collect the work's photos (repeater) — they become the media of the work,
    // shown alongside its description as one block (lead photo + thumbnails).
    $gallery = [];
    if ( have_rows( 'pf_gallery' ) ) {
        while ( have_rows( 'pf_gallery' ) ) {
            the_row();
            $gid = (int) get_sub_field( 'pf_image' );
            if ( ! $gid ) {
                continue;
            }
            $gcap      = (string) get_sub_field( 'pf_caption' );
            $gallery[] = [
                'caption' => $gcap,
                'thumb'   => wp_get_attachment_image_url( $gid, 'large' ),
                'full'    => wp_get_attachment_image_url( $gid, 'full' ),
                'alt'     => get_post_meta( $gid, '_wp_attachment_image_alt', true ) ?: $gcap,
            ];
        }
    }
    $has_gallery = ! empty( $gallery );
    ?>

<main id="primary" class="pf-single">

    <section class="page-hero">
        <div class="container">
            <div class="page-hero__inner page-hero__inner--wide">
                <?php stylen_breadcrumbs(); ?>
                <?php if ( $term ) : ?>
                    <p class="page-hero__kicker"><?php echo esc_html( $term->name ); ?></p>
                <?php endif; ?>
                <h1 class="page-hero__title"><?php the_title(); ?></h1>
                <?php if ( has_excerpt() ) : ?>
                    <p class="page-hero__lead"><?php echo esc_html( get_the_excerpt() ); ?></p>
                <?php endif; ?>
                <div class="page-hero__actions">
                    <a class="btn btn--outline" href="<?php echo esc_url( home_url( '/portfolio/' ) ); ?>"><?php echo stylen_icon( 'arrow', 'icon btn__arrow btn__arrow--back' ); ?>Все работы</a>
                </div>
            </div>
        </div>
    </section>

    <!-- ============ WORK — photos + description as one block ============ -->
    <section class="section">
        <div class="container">
            <div class="pf-single__layout<?php echo $has_gallery ? ' pf-single__layout--gallery' : ''; ?>">
                <div class="pf-single__media">
                    <?php if ( $has_gallery ) : $lead = $gallery[0]; ?>
                        <figure class="pf-single__figure">
                            <button type="button" class="pf-single__lead" data-lightbox="pf-work"
                                data-full="<?php echo esc_url( $lead['full'] ); ?>"
                                data-caption="<?php echo esc_attr( $lead['caption'] ); ?>"
                                aria-label="<?php echo esc_attr( $lead['caption'] ? 'Открыть фото: ' . $lead['caption'] : 'Открыть фото' ); ?>">
                                <img src="<?php echo esc_url( $lead['thumb'] ); ?>" alt="<?php echo esc_attr( $lead['alt'] ?: get_the_title() ); ?>">
                                <span class="pf-gallery__zoom" aria-hidden="true"><?php echo stylen_icon( 'maximize', 'icon' ); ?></span>
                            </button>
                            <?php if ( count( $gallery ) > 1 ) : ?>
                                <div class="pf-single__thumbs">
                                    <?php foreach ( array_slice( $gallery, 1 ) as $g ) : ?>
                                        <button type="button" class="pf-single__thumb" data-lightbox="pf-work"
                                            data-full="<?php echo esc_url( $g['full'] ); ?>"
                                            data-caption="<?php echo esc_attr( $g['caption'] ); ?>"
                                            aria-label="<?php echo esc_attr( $g['caption'] ? 'Открыть фото: ' . $g['caption'] : 'Открыть фото' ); ?>">
                                            <img src="<?php echo esc_url( $g['thumb'] ); ?>" alt="<?php echo esc_attr( $g['alt'] ); ?>" loading="lazy">
                                        </button>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </figure>
                    <?php elseif ( has_post_thumbnail() ) : ?>
                        <?php the_post_thumbnail( 'large', [ 'alt' => '', 'class' => 'pf-single__img' ] ); ?>
                    <?php else : ?>
                        <span class="pf-single__cover pf-cover--<?php echo esc_attr( $cover ); ?>"><?php echo stylen_icon( $icon, 'icon' ); ?></span>
                    <?php endif; ?>
                </div>

                <?php if ( trim( get_the_content() ) ) : ?>
                    <div class="pf-single__body article-body">
                        <?php the_content(); ?>
                        <?php if ( $has_gallery ) : ?>
                            <p class="pf-single__hint"><?php echo stylen_icon( 'maximize', 'icon icon--sm' ); ?>Нажмите на&nbsp;фото, чтобы открыть галерею</p>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- ============ CTA ============ -->
    <section class="section">
        <div class="container">
            <div class="cta-band" data-reveal>
                <div>
                    <h2 class="cta-band__title">Нужна похожая работа?</h2>
                    <p class="cta-band__text">Расскажите о&nbsp;задаче&nbsp;— предложим решение, посчитаем стоимость и&nbsp;бесплатно подготовим макет.</p>
                </div>
                <div class="cta-band__actions">
                    <a class="btn btn--gold btn--lg" href="#order">Обсудить проект <?php echo stylen_icon( 'arrow', 'icon btn__arrow' ); ?></a>
                    <a class="btn btn--ghost-light btn--lg" href="<?php echo esc_attr( $c['phone_href'] ); ?>"><?php echo esc_html( $c['phone'] ); ?></a>
                </div>
            </div>
        </div>
    </section>

    <!-- ============ "Смотрите также" — other works in the same subcategory ============ -->
    <?php
    if ( $term ) :
        $rel = new WP_Query( [
            'post_type'      => 'portfolio',
            'post_status'    => 'publish',
            'posts_per_page' => 3,
            'post__not_in'   => [ get_the_ID() ],
            'orderby'        => 'rand',
            'tax_query'      => [ [ 'taxonomy' => 'portfolio_cat', 'field' => 'term_id', 'terms' => $term->term_id ] ],
        ] );
        if ( $rel->have_posts() ) : ?>
            <section class="section section--tint">
                <div class="container">
                    <header class="section__head section__head--left" data-reveal>
                        <p class="section__kicker">Смотрите также</p>
                        <h2 class="section__title"><?php echo esc_html( $term->name ); ?></h2>
                    </header>
                    <div class="pf-grid">
                        <?php
                        $i = 0;
                        while ( $rel->have_posts() ) : $rel->the_post();
                            echo stylen_pf_card( ( $i % 3 ) * 55 );
                            $i++;
                        endwhile;
                        ?>
                    </div>
                </div>
            </section>
            <?php
        endif;
        wp_reset_postdata();
    endif;
    ?>

</main>

<?php
endwhile;
get_footer();

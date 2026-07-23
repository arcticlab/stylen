<?php
/**
 * Single blog article — Стиль-Н.
 *
 * @package Stylen
 */

get_header();

while ( have_posts() ) :
    the_post();
    $cats     = get_the_category();
    $cat      = ! empty( $cats ) ? $cats[0] : null;
    $author   = get_the_author();
    $initials = mb_substr( $author, 0, 1 );
    $share_u  = rawurlencode( get_permalink() );
    $share_t  = rawurlencode( get_the_title() );
    ?>

    <div class="reading-progress" id="reading-progress" aria-hidden="true"></div>

    <main id="primary">

        <!-- ============ ARTICLE HERO ============ -->
        <section class="page-hero">
            <div class="container">
                <div class="page-hero__inner page-hero__inner--wide">
                    <?php stylen_breadcrumbs(); ?>
                    <?php if ( $cat ) : ?>
                        <p class="page-hero__kicker"><?php echo esc_html( $cat->name ); ?></p>
                    <?php endif; ?>
                    <h1 class="page-hero__title"><?php the_title(); ?></h1>
                    <div class="article__meta">
                        <span class="post-meta"><?php echo stylen_icon( 'clock', 'icon icon--sm' ); ?><?php echo get_the_date(); ?></span>
                    </div>
                </div>
            </div>
        </section>

        <!-- ============ ARTICLE BODY ============ -->
        <article <?php post_class( 'section' ); ?>>
            <div class="container">
                <div class="article">

                    <?php if ( has_post_thumbnail() ) : ?>
                        <div class="article__cover"><?php the_post_thumbnail( 'large', [ 'alt' => '' ] ); ?></div>
                    <?php endif; ?>

                    <div class="article-body">
                        <?php
                        the_content();
                        wp_link_pages( [
                            'before' => '<div class="page-links">' . esc_html__( 'Страницы:', 'stylen' ),
                            'after'  => '</div>',
                        ] );
                        ?>
                    </div>

                    <footer class="article-foot">
                        <?php the_tags( '<div class="tags-links">', '', '</div>' ); ?>
                        <div class="share">
                            <span class="share__label">Поделиться:</span>
                            <a href="https://t.me/share/url?url=<?php echo $share_u; ?>&text=<?php echo $share_t; ?>" target="_blank" rel="noopener" aria-label="Telegram">
                                <svg class="icon icon--sm" viewBox="0 0 24 24" width="20" height="20" aria-hidden="true"><path fill="currentColor" d="M21.9 4.3 18.7 19.4c-.2 1-.9 1.3-1.8.8l-4.9-3.6-2.4 2.3c-.3.3-.5.5-1 .5l.3-4.9 9-8.1c.4-.3-.1-.5-.6-.2L6.4 13.3l-4.7-1.5c-1-.3-1-.9.2-1.4l18.4-7.1c.9-.3 1.6.2 1.3 1z"/></svg>
                            </a>
                            <a href="https://vk.com/share.php?url=<?php echo $share_u; ?>&title=<?php echo $share_t; ?>" target="_blank" rel="noopener" aria-label="ВКонтакте">
                                <svg class="icon icon--sm" viewBox="0 0 24 24" width="20" height="20" aria-hidden="true"><path fill="currentColor" d="M12.8 16.5c-5.3 0-8.6-3.7-8.7-9.8h2.7c.1 4.5 2.1 6.3 3.6 6.7V6.7h2.5v3.8c1.5-.2 3-1.8 3.6-3.8h2.5c-.4 2.5-2 4.1-3.1 4.8 1.1.6 2.9 2 3.6 4.2h-2.8c-.5-1.6-1.9-2.9-3.4-3.1v3.1z"/></svg>
                            </a>
                            <a href="https://wa.me/?text=<?php echo $share_t; ?>%20<?php echo $share_u; ?>" target="_blank" rel="noopener" aria-label="WhatsApp">
                                <svg class="icon icon--sm" viewBox="0 0 24 24" width="20" height="20" aria-hidden="true"><path fill="currentColor" d="M12 2a10 10 0 0 0-8.6 15L2 22l5.2-1.4A10 10 0 1 0 12 2m0 2a8 8 0 0 1 0 16 8 8 0 0 1-4.1-1.1l-.3-.2-3 .8.8-2.9-.2-.3A8 8 0 0 1 12 4m-2.7 4c-.2 0-.5 0-.7.4-.2.4-.9.9-.9 2.1s.9 2.5 1 2.6c.1.2 1.7 2.8 4.3 3.8 2.1.8 2.6.7 3 .6.5-.1 1.4-.6 1.6-1.1.2-.6.2-1 .1-1.1l-.7-.3c-.3-.2-1.4-.7-1.6-.8-.2-.1-.4-.1-.5.1l-.7.9c-.1.2-.3.2-.5.1-.3-.1-1.1-.4-2-1.3-.8-.7-1.3-1.5-1.4-1.7-.1-.3 0-.4.1-.5l.4-.4.3-.5v-.4c0-.1-.5-1.3-.7-1.7-.2-.5-.4-.4-.5-.4z"/></svg>
                            </a>
                        </div>
                    </footer>

                </div>
            </div>
        </article>

        <!-- ============ CTA ============ -->
        <section class="section" style="padding-top:0">
            <div class="container">
                <div class="cta-band" data-reveal>
                    <div>
                        <h2 class="cta-band__title"><?php echo esc_html( get_field( 'modal_title', 'option' ) ); ?></h2>
                        <p class="cta-band__text"><?php echo esc_html( get_field( 'modal_text', 'option' ) ); ?></p>
                    </div>
                    <div class="cta-band__actions">
                        <a class="btn btn--gold btn--lg" href="#order"><?php echo esc_html( get_field( 'footer_cta_label', 'option' ) ); ?> <?php echo stylen_icon( 'arrow', 'icon btn__arrow' ); ?></a>
                        <a class="btn btn--ghost-light btn--lg" href="<?php echo esc_attr( stylen_contacts()['phone_href'] ); ?>"><?php echo esc_html( stylen_contacts()['phone'] ); ?></a>
                    </div>
                </div>
            </div>
        </section>

        <!-- ============ RELATED ============ -->
        <?php
        if ( $cat ) :
            $related = new WP_Query( [
                'post_type'           => 'post',
                'posts_per_page'      => 3,
                'post__not_in'        => [ get_the_ID() ],
                'category__in'        => [ $cat->term_id ],
                'ignore_sticky_posts' => true,
                'no_found_rows'       => true,
            ] );
            if ( $related->have_posts() ) :
                ?>
                <section class="section section--tint">
                    <div class="container">
                        <header class="section__head" data-reveal>
                            <p class="section__kicker">Читайте также</p>
                            <h2 class="section__title">Похожие статьи</h2>
                        </header>
                        <div class="post-grid">
                            <?php while ( $related->have_posts() ) : $related->the_post(); ?>
                                <article class="post-card" data-reveal>
                                    <a href="<?php the_permalink(); ?>" aria-label="<?php the_title_attribute(); ?>" tabindex="-1">
                                        <?php stylen_post_cover( 'post-card' ); ?>
                                    </a>
                                    <div class="post-card__body">
                                        <h3 class="post-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                        <div class="post-card__foot"><?php echo esc_html( get_the_date() ); ?></div>
                                    </div>
                                </article>
                            <?php endwhile; ?>
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

<?php
/**
 * Archive — Стиль-Н. The blog is a single category, so this template now
 * serves TAG archives (and any date archives). No rubric chips, no
 * reading-time, no category pill on cards. Tag descriptions (editable in
 * Записи → Метки → Изменить → Описание) render in the hero.
 *
 * @package Stylen
 */

get_header();

$is_tag = is_tag();
$title  = $is_tag ? single_tag_title( '', false ) : wp_strip_all_tags( get_the_archive_title() );
$desc   = get_the_archive_description();
?>

<main id="primary">

    <!-- ============ HERO ============ -->
    <section class="page-hero">
        <div class="container">
            <div class="page-hero__inner page-hero__inner--wide">
                <?php stylen_breadcrumbs(); ?>
                <p class="page-hero__kicker"><?php echo esc_html( $is_tag ? 'Блог · тег' : 'Блог' ); ?></p>
                <h1 class="page-hero__title"><?php echo esc_html( $title ); ?></h1>
                <?php if ( $desc ) : ?>
                    <div class="page-hero__lead"><?php echo wp_kses_post( wpautop( $desc ) ); ?></div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container">

            <?php if ( have_posts() ) : ?>

                <div class="post-grid">
                    <?php while ( have_posts() ) : the_post(); ?>
                        <article class="post-card" data-reveal>
                            <a href="<?php the_permalink(); ?>" aria-label="<?php the_title_attribute(); ?>" tabindex="-1">
                                <?php stylen_post_cover( 'post-card' ); ?>
                            </a>
                            <div class="post-card__body">
                                <h2 class="post-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                                <p class="post-card__excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 20 ) ); ?></p>
                                <div class="post-card__foot"><?php echo esc_html( get_the_date() ); ?></div>
                            </div>
                        </article>
                    <?php endwhile; ?>
                </div>

                <?php
                echo paginate_links( [
                    'prev_text' => '←',
                    'next_text' => '→',
                ] );
                ?>

            <?php else : ?>
                <p class="pf-empty">По&nbsp;этому тегу пока нет статей.</p>
            <?php endif; ?>

        </div>
    </section>

</main>

<?php
get_footer();

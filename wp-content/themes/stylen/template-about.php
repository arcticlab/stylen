<?php
/**
 * Template Name: О компании
 *
 * @package Stylen
 */

$c     = stylen_contacts();
$years = stylen_years_on_market();

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
            <div class="page-hero__inner page-hero__inner--wide">
                <?php stylen_breadcrumbs(); ?>
                <p class="page-hero__kicker"><?php echo esc_html( $f( 'ab_hero_kicker' ) ); ?></p>
                <h1 class="page-hero__title"><?php echo wp_kses_post( $f( 'ab_hero_title' ) ); ?></h1>
                <p class="page-hero__lead"><?php echo esc_html( $f( 'ab_hero_lead' ) ); ?></p>
            </div>
        </div>
    </section>

    <!-- ============ STATS ============ -->
    <section class="section">
        <div class="container">
            <div class="stats">
                <?php $i = 0; while ( have_rows( 'ab_stats' ) ) : the_row();
                    $num = (string) get_sub_field( 'number' );
                    $ds  = (string) get_sub_field( 'dsuffix' );
                    $ss  = (string) get_sub_field( 'ssuffix' );
                    ?>
                    <div class="stat" data-reveal style="--d:<?php echo $i * 55; ?>ms">
                        <div class="stat__num"><span data-count="<?php echo esc_attr( $num ); ?>"<?php if ( '' !== $ds ) { echo ' data-suffix="' . esc_attr( $ds ) . '"'; } ?>><?php echo esc_html( $num . $ds ); ?></span><?php if ( '' !== $ss ) { echo '<span class="suffix">' . esc_html( $ss ) . '</span>'; } ?></div>
                        <p class="stat__label"><?php echo esc_html( get_sub_field( 'label' ) ); ?></p>
                    </div>
                <?php $i++; endwhile; ?>
            </div>
        </div>
    </section>

    <!-- ============ STORY ============ -->
    <section class="section section--tint">
        <div class="container">
            <div class="about-split">
                <div data-reveal>
                    <header class="section__head section__head--left" style="margin-bottom:var(--s-6)">
                        <p class="section__kicker"><?php echo esc_html( $f( 'ab_story_kicker' ) ); ?></p>
                        <h2 class="section__title"><?php echo esc_html( $f( 'ab_story_title' ) ); ?></h2>
                    </header>
                    <div class="prose"><?php echo wp_kses_post( $f( 'ab_story_body' ) ); ?></div>
                    <div class="page-hero__actions" style="margin-top:var(--s-8)">
                        <a class="btn btn--primary" href="<?php echo esc_url( home_url( '/catalog/' ) ); ?>"><?php echo esc_html( $f( 'ab_story_cta_label' ) ); ?> <?php echo stylen_icon( 'arrow', 'icon btn__arrow' ); ?></a>
                    </div>
                </div>
                <div class="about-split__media" data-reveal style="--d:80ms">
                    <?php echo stylen_icon( 'factory', 'icon' ); ?>
                    <div class="about-split__badge"><span><?php echo esc_html( $f( 'ab_story_badge_label' ) ); ?></span><?php echo esc_html( $f( 'ab_story_badge_value' ) ); ?></div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============ TIMELINE ============ -->
    <section class="section">
        <div class="container">
            <header class="section__head" data-reveal>
                <p class="section__kicker"><?php echo esc_html( $f( 'ab_timeline_kicker' ) ); ?></p>
                <h2 class="section__title"><?php echo wp_kses_post( $f( 'ab_timeline_title' ) ); ?></h2>
                <p class="section__subtitle"><?php echo esc_html( $f( 'ab_timeline_subtitle' ) ); ?></p>
            </header>
            <div class="timeline" style="max-width:760px;margin:0 auto">
                <?php $i = 0; while ( have_rows( 'ab_timeline_items' ) ) : the_row(); ?>
                    <div class="tl" data-reveal style="--d:<?php echo $i * 55; ?>ms">
                        <div class="tl__year"><?php echo esc_html( get_sub_field( 'year' ) ); ?></div>
                        <h3 class="tl__title"><?php echo esc_html( get_sub_field( 'title' ) ); ?></h3>
                        <p class="tl__text"><?php echo esc_html( get_sub_field( 'text' ) ); ?></p>
                    </div>
                <?php $i++; endwhile; ?>
            </div>
        </div>
    </section>

    <!-- ============ VALUES ============ -->
    <section class="section section--tint">
        <div class="container">
            <header class="section__head" data-reveal>
                <p class="section__kicker"><?php echo esc_html( $f( 'ab_values_kicker' ) ); ?></p>
                <h2 class="section__title"><?php echo esc_html( $f( 'ab_values_title' ) ); ?></h2>
            </header>
            <div class="values">
                <?php $i = 0; while ( have_rows( 'ab_values_items' ) ) : the_row(); ?>
                    <article class="value" data-reveal style="--d:<?php echo $i * 50; ?>ms">
                        <span class="value__icon"><?php echo stylen_icon( (string) get_sub_field( 'icon' ), 'icon' ); ?></span>
                        <h3 class="value__title"><?php echo esc_html( get_sub_field( 'title' ) ); ?></h3>
                        <p class="value__text"><?php echo esc_html( get_sub_field( 'text' ) ); ?></p>
                    </article>
                <?php $i++; endwhile; ?>
            </div>
        </div>
    </section>

    <!-- ============ TEAM ============ -->
    <section class="section">
        <div class="container">
            <header class="section__head" data-reveal>
                <p class="section__kicker"><?php echo esc_html( $f( 'ab_team_kicker' ) ); ?></p>
                <h2 class="section__title"><?php echo esc_html( $f( 'ab_team_title' ) ); ?></h2>
                <p class="section__subtitle"><?php echo esc_html( $f( 'ab_team_subtitle' ) ); ?></p>
            </header>
            <div class="team">
                <?php $i = 0; while ( have_rows( 'ab_team_items' ) ) : the_row(); ?>
                    <div class="member" data-reveal style="--d:<?php echo $i * 55; ?>ms">
                        <div class="member__avatar"><?php echo esc_html( get_sub_field( 'initials' ) ); ?></div>
                        <p class="member__name"><?php echo esc_html( get_sub_field( 'tname' ) ); ?></p>
                        <p class="member__role"><?php echo esc_html( get_sub_field( 'role' ) ); ?></p>
                    </div>
                <?php $i++; endwhile; ?>
            </div>
        </div>
    </section>

    <!-- ============ CTA ============ -->
    <section class="section" style="padding-top:0">
        <div class="container">
            <div class="cta-band" data-reveal>
                <div>
                    <h2 class="cta-band__title"><?php echo esc_html( $f( 'ab_cta_title' ) ); ?></h2>
                    <p class="cta-band__text"><?php echo esc_html( $f( 'ab_cta_text' ) ); ?></p>
                </div>
                <div class="cta-band__actions">
                    <a class="btn btn--gold btn--lg" href="<?php echo esc_url( home_url( '/kontakty/' ) ); ?>">Оставить заявку <?php echo stylen_icon( 'arrow', 'icon btn__arrow' ); ?></a>
                    <a class="btn btn--ghost-light btn--lg" href="<?php echo esc_attr( $c['phone_href'] ); ?>"><?php echo esc_html( $c['phone'] ); ?></a>
                </div>
            </div>
        </div>
    </section>

</main>

<?php
get_footer();

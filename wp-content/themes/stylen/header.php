<?php
/**
 * Header
 *
 * @package Stylen
 */
$c = stylen_contacts();
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script>document.documentElement.classList.add('js');if(navigator.userAgent.indexOf('Gecko/')>-1||/firefox|fxios/i.test(navigator.userAgent))document.documentElement.classList.add('is-firefox');</script>
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Перейти к содержимому', 'stylen' ); ?></a>

<div id="page" class="site">

    <header id="masthead" class="site-header" data-sticky>
        <div class="container site-header__inner">

            <?php list( $brand_name, $brand_accent ) = stylen_brand_split(); ?>
            <a class="brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
                <span class="brand__name"><?php echo esc_html( $brand_name ); ?></span><span class="brand__accent"><?php echo esc_html( $brand_accent ); ?></span>
                <span class="brand__tag"><?php echo esc_html( get_field( 'brand_tag', 'option' ) ); ?></span>
            </a>

            <nav id="site-navigation" class="main-navigation" aria-label="<?php esc_attr_e( 'Основное меню', 'stylen' ); ?>">
                <?php
                wp_nav_menu( [
                    'theme_location' => 'primary',
                    'menu_id'        => 'primary-menu',
                    'container'      => false,
                    'depth'          => 2,
                    'fallback_cb'    => false,
                ] );
                ?>
                <ul class="nav-contacts">
                    <li><a href="<?php echo esc_attr( $c['phone_href'] ); ?>"><?php echo stylen_icon( 'phone', 'icon icon--sm' ); ?><?php echo esc_html( $c['phone'] ); ?></a></li>
                    <li><a href="mailto:<?php echo esc_attr( $c['email'] ); ?>"><?php echo stylen_icon( 'mail', 'icon icon--sm' ); ?><?php echo esc_html( $c['email'] ); ?></a></li>
                    <li><a href="<?php echo esc_url( $c['map'] ); ?>" target="_blank" rel="noopener"><?php echo stylen_icon( 'pin', 'icon icon--sm' ); ?><?php echo esc_html( $c['zip'] . ', ' . $c['address'] ); ?></a></li>
                    <li><span class="nav-contacts__hours"><?php echo stylen_icon( 'clock', 'icon icon--sm' ); ?><?php echo esc_html( $c['hours'] ); ?></span></li>
                </ul>
                <a class="btn btn--primary nav-cta" href="#order"><?php echo esc_html( get_field( 'header_cta_label', 'option' ) ); ?></a>
            </nav>

            <div class="site-header__actions">
                <a class="header-phone" href="<?php echo esc_attr( $c['phone_href'] ); ?>">
                    <span class="header-phone__label"><?php echo esc_html( $c['city_short'] ); ?></span>
                    <span class="header-phone__num"><?php echo esc_html( $c['phone'] ); ?></span>
                </a>
                <a class="btn btn--primary header-cta" href="#order"><?php echo esc_html( get_field( 'header_cta_label', 'option' ) ); ?></a>
                <button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false" aria-label="<?php esc_attr_e( 'Меню', 'stylen' ); ?>">
                    <span></span><span></span><span></span>
                </button>
            </div>

        </div>
    </header>
    <div class="nav-overlay"></div>

    <div id="content" class="site-content">

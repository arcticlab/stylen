<?php
/**
 * Footer
 *
 * @package Stylen
 */
$c = stylen_contacts();
$directions = stylen_get_directions();
?>
    </div><!-- #content -->

    <footer id="contacts" class="site-footer">
        <div class="container site-footer__grid">

            <?php list( $brand_name, $brand_accent ) = stylen_brand_split(); ?>
            <div class="footer-col footer-col--about">
                <a class="brand brand--footer" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
                    <span class="brand__name"><?php echo esc_html( $brand_name ); ?></span><span class="brand__accent"><?php echo esc_html( $brand_accent ); ?></span>
                </a>
                <p class="footer-about"><?php echo esc_html( get_field( 'footer_about', 'option' ) ); ?></p>
                <a class="btn btn--gold" href="#order"><?php echo esc_html( get_field( 'footer_cta_label', 'option' ) ); ?></a>
            </div>

            <div class="footer-col">
                <h2 class="footer-col__title"><?php esc_html_e( 'Каталог', 'stylen' ); ?></h2>
                <ul class="footer-menu">
                    <?php foreach ( $directions as $d ) : ?>
                        <li><a href="<?php echo esc_url( get_permalink( $d->ID ) ); ?>"><?php echo esc_html( $d->post_title ); ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="footer-col">
                <h2 class="footer-col__title"><?php esc_html_e( 'Контакты', 'stylen' ); ?></h2>
                <ul class="footer-contacts">
                    <li><?php echo stylen_icon( 'phone', 'icon icon--sm' ); ?><a href="<?php echo esc_attr( $c['phone_href'] ); ?>"><?php echo esc_html( $c['phone'] ); ?></a></li>
                    <li><?php echo stylen_icon( 'mail', 'icon icon--sm' ); ?><a href="mailto:<?php echo esc_attr( $c['email'] ); ?>"><?php echo esc_html( $c['email'] ); ?></a></li>
                    <li><?php echo stylen_icon( 'pin', 'icon icon--sm' ); ?><a href="<?php echo esc_url( $c['map'] ); ?>" target="_blank" rel="noopener"><?php echo esc_html( $c['zip'] . ', ' . $c['address'] ); ?></a></li>
                    <li><?php echo stylen_icon( 'clock', 'icon icon--sm' ); ?><span><?php echo esc_html( $c['hours'] ); ?></span></li>
                </ul>
            </div>

        </div>

        <div class="footer-bottom">
            <div class="container footer-bottom__inner">
                <p>&copy; <?php echo (int) $c['founded']; ?>&ndash;<?php echo esc_html( date( 'Y' ) ); ?> <?php echo esc_html( $c['company'] ); ?>. <?php esc_html_e( 'Все права защищены.', 'stylen' ); ?></p>
                <?php
                $privacy = get_privacy_policy_url();
                if ( $privacy ) :
                    ?>
                    <a href="<?php echo esc_url( $privacy ); ?>"><?php esc_html_e( 'Политика конфиденциальности', 'stylen' ); ?></a>
                <?php endif; ?>
            </div>
        </div>
    </footer>

</div><!-- #page -->

<?php $modal_form = get_option( 'stylen_order_form_id' ); ?>
<div class="modal" id="order-modal" aria-hidden="true">
    <div class="modal__overlay" data-modal-close></div>
    <div class="modal__dialog" role="dialog" aria-modal="true" aria-labelledby="order-modal-title">
        <button type="button" class="modal__close" data-modal-close aria-label="Закрыть окно">
            <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" aria-hidden="true"><path d="M6 6l12 12M18 6L6 18"/></svg>
        </button>
        <div class="modal__intro">
            <span class="eyebrow"><?php echo esc_html( get_field( 'modal_eyebrow', 'option' ) ); ?></span>
            <h2 class="modal__title" id="order-modal-title"><?php echo esc_html( get_field( 'modal_title', 'option' ) ); ?></h2>
            <p class="modal__text"><?php echo esc_html( get_field( 'modal_text', 'option' ) ); ?></p>
        </div>
        <div class="modal__form">
            <?php
            if ( $modal_form ) {
                echo do_shortcode( '[contact-form-7 id="' . (int) $modal_form . '" title="Заявка (модальное окно)"]' );
            } else {
                echo '<p>Позвоните нам: <a href="' . esc_attr( $c['phone_href'] ) . '">' . esc_html( $c['phone'] ) . '</a>.</p>';
            }
            ?>
        </div>
    </div>
</div>

<?php wp_footer(); ?>
</body>
</html>

<?php
/**
 * 404 — page not found. Стиль-Н.
 *
 * @package Stylen
 */

$c = stylen_contacts();

$quick = [
    [ 'wide-format', 'Каталог',   'Все направления печати и рекламы', home_url( '/catalog/' ) ],
    [ 'star',        'Портфолио', 'Примеры наших работ',              home_url( '/portfolio/' ) ],
    [ 'palette',     'Блог',      'Статьи о рекламе и печати',        home_url( '/blog/' ) ],
    [ 'phone',       'Контакты',  'Телефон, почта, адрес офиса',      home_url( '/kontakty/' ) ],
];

get_header();
?>

<main id="primary">

    <section class="error404-hero">
        <div class="container">
            <div class="error404__code" aria-hidden="true">
                <span>4</span><span class="zero">0</span><span>4</span>
            </div>
            <h1 class="error404__title">Такой страницы не&nbsp;нашлось</h1>
            <p class="error404__text">Возможно, ссылка устарела или&nbsp;в&nbsp;адресе опечатка. Но&nbsp;не&nbsp;беда&nbsp;— мы&nbsp;поможем найти нужное.</p>

            <form class="error404__search" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                <label class="screen-reader-text" for="s404">Поиск по&nbsp;сайту</label>
                <input type="search" id="s404" name="s" placeholder="Что вы искали?" value="<?php echo esc_attr( get_search_query() ); ?>">
                <button type="submit"><?php esc_html_e( 'Найти', 'stylen' ); ?></button>
            </form>

            <div class="error404__actions">
                <a class="btn btn--gold btn--lg" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo stylen_icon( 'arrow', 'icon' ); ?> На&nbsp;главную</a>
                <a class="btn btn--ghost-light btn--lg" href="<?php echo esc_attr( $c['phone_href'] ); ?>"><?php echo stylen_icon( 'phone', 'icon' ); ?> <?php echo esc_html( $c['phone'] ); ?></a>
            </div>
        </div>
    </section>

    <section class="error404-links">
        <div class="container">
            <header class="section__head" data-reveal>
                <p class="section__kicker">Куда дальше</p>
                <h2 class="section__title">Популярные разделы</h2>
            </header>
            <div class="quick-links">
                <?php foreach ( $quick as $i => $ql ) : ?>
                    <a class="quick-link" href="<?php echo esc_url( $ql[3] ); ?>" data-reveal style="--d:<?php echo $i * 55; ?>ms">
                        <span class="quick-link__icon"><?php echo stylen_icon( $ql[0], 'icon' ); ?></span>
                        <span class="quick-link__title"><?php echo esc_html( $ql[1] ); ?></span>
                        <p class="quick-link__text"><?php echo esc_html( $ql[2] ); ?></p>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

</main>

<?php
get_footer();

<?php

if ( ! defined( 'ABSPATH' ) ) exit;

function stylen_setup() {
    load_theme_textdomain( 'stylen', get_template_directory() . '/languages' );

    add_theme_support( 'automatic-feed-links' );
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', [ 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'script', 'style' ] );
    add_theme_support( 'customize-selective-refresh-widgets' );
    add_theme_support( 'wp-block-styles' );
    add_theme_support( 'responsive-embeds' );

    register_nav_menus( [
        'primary' => __( 'Primary Menu', 'stylen' ),
        'footer'  => __( 'Footer Menu', 'stylen' ),
    ] );

    set_post_thumbnail_size( 800, 450, true );
}
add_action( 'after_setup_theme', 'stylen_setup' );

function stylen_content_width() {
    $GLOBALS['content_width'] = 800;
}
add_action( 'after_setup_theme', 'stylen_content_width', 0 );

function stylen_widgets_init() {
    register_sidebar( [
        'name'          => __( 'Sidebar', 'stylen' ),
        'id'            => 'sidebar-1',
        'description'   => __( 'Add widgets here.', 'stylen' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ] );

    register_sidebar( [
        'name'          => __( 'Footer Widgets', 'stylen' ),
        'id'            => 'footer-1',
        'description'   => __( 'Add footer widgets here.', 'stylen' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ] );
}
add_action( 'widgets_init', 'stylen_widgets_init' );

define( 'STYLEN_VER', '9.5.0' );

function stylen_scripts() {
    $uri = get_template_directory_uri();

    // Typography (v9 «Смета» — engineered-tool voice):
    // One contemporary grotesque — Onest (full Cyrillic, tabular figures) —
    // for everything. A calm, precise, product-UI family. Deliberately not the
    // serif/Inter split of v8, nor Golos/Rubik/Manrope of earlier concepts:
    // a single, unused typeface is the clearest "different team" signal.
    wp_enqueue_style(
        'stylen-fonts',
        'https://fonts.googleapis.com/css2?family=Onest:wght@400;500;600;700;800&display=swap',
        [],
        null
    );

    wp_enqueue_style( 'stylen-style', get_stylesheet_uri(), [ 'stylen-fonts' ], STYLEN_VER );
    wp_enqueue_style( 'stylen-main', $uri . '/assets/css/main.css', [ 'stylen-style' ], STYLEN_VER );

    // Interior pages: custom page templates, blog listings, single posts, archives, 404, catalog.
    $interior_templates = [ 'template-contacts.php', 'template-about.php', 'template-portfolio.php', 'template-blog.php' ];
    $is_catalog = stylen_is_catalog();
    if ( is_page_template( $interior_templates ) || is_singular( 'post' ) || is_singular( 'portfolio' ) || is_home() || is_archive() || is_search() || is_404() || $is_catalog ) {
        wp_enqueue_style( 'stylen-pages', $uri . '/assets/css/pages.css', [ 'stylen-main' ], STYLEN_VER );
    }
    if ( $is_catalog ) {
        wp_enqueue_style( 'stylen-catalog', $uri . '/assets/css/catalog.css', [ 'stylen-pages' ], STYLEN_VER );
    }

    // Concept layer — shared component overrides.
    wp_enqueue_style( 'stylen-concept', $uri . '/assets/css/concept.css', [ 'stylen-main' ], STYLEN_VER );

    // Homepage layer — loads LAST so it is fully authoritative over the shared/concept styles.
    if ( is_front_page() ) {
        wp_enqueue_style( 'stylen-home', $uri . '/assets/css/home.css', [ 'stylen-concept' ], STYLEN_VER );
    }

    wp_enqueue_script( 'stylen-main', $uri . '/assets/js/main.js', [], STYLEN_VER, true );

    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }
}
add_action( 'wp_enqueue_scripts', 'stylen_scripts' );

/**
 * Preconnect to Google Fonts for faster first paint.
 */
add_filter( 'wp_resource_hints', function ( $hints, $relation ) {
    if ( 'preconnect' === $relation ) {
        $hints[] = 'https://fonts.gstatic.com';
    }
    return $hints;
}, 10, 2 );

/**
 * Favicon — served from the site root (downloaded from the live brand site).
 */
add_action( 'wp_head', function () {
    $ico = home_url( '/favicon.ico' );
    echo '<link rel="icon" href="' . esc_url( $ico ) . '" sizes="any">' . "\n";
    echo '<link rel="shortcut icon" href="' . esc_url( $ico ) . '">' . "\n";
}, 1 );

/**
 * ACF Local JSON — save & load field groups from the theme's acf-json folder,
 * so all field definitions are version-controlled with the theme.
 */
add_filter( 'acf/settings/save_json', function () {
    return get_stylesheet_directory() . '/acf-json';
} );

add_filter( 'acf/settings/load_json', function ( $paths ) {
    unset( $paths[0] );
    $paths[] = get_stylesheet_directory() . '/acf-json';
    return $paths;
} );

require get_template_directory() . '/inc/template-tags.php';
require get_template_directory() . '/inc/helpers.php';
require get_template_directory() . '/inc/acf.php';

/**
 * Portfolio — a real custom post type of individually-addressable works,
 * grouped into subcategories (taxonomy `portfolio_cat`). This is the deliberate
 * counterpart to the blog, which is intentionally a single undivided stream.
 * URL structure mirrors the catalogue:
 *   /portfolio/                       — listing (Page id 93, template-portfolio.php)
 *   /portfolio/{subcat}/              — subcategory (taxonomy-portfolio_cat.php)
 *   /portfolio/{subcat}/{work}/       — single work (single-portfolio.php)
 */
add_action( 'init', function () {
    register_post_type( 'portfolio', [
        'labels' => [
            'name'          => 'Портфолио',
            'singular_name' => 'Работа',
            'menu_name'     => 'Портфолио',
            'all_items'     => 'Все работы',
            'add_new_item'  => 'Добавить работу',
            'edit_item'     => 'Редактировать работу',
            'new_item'      => 'Новая работа',
            'view_item'     => 'Смотреть работу',
            'search_items'  => 'Искать работы',
        ],
        'public'        => true,
        'has_archive'   => false,
        'menu_icon'     => 'dashicons-portfolio',
        'menu_position' => 22,
        'supports'      => [ 'title', 'editor', 'excerpt', 'thumbnail', 'page-attributes' ],
        'rewrite'       => [ 'slug' => 'portfolio/%portfolio_cat%', 'with_front' => false ],
        'show_in_rest'  => true,
    ] );

    register_taxonomy( 'portfolio_cat', 'portfolio', [
        'labels' => [
            'name'          => 'Подкатегории работ',
            'singular_name' => 'Подкатегория',
            'menu_name'     => 'Подкатегории',
            'all_items'     => 'Все подкатегории',
            'edit_item'     => 'Редактировать подкатегорию',
            'add_new_item'  => 'Добавить подкатегорию',
            'search_items'  => 'Искать подкатегории',
        ],
        'public'            => true,
        'hierarchical'      => true,
        'show_admin_column' => true,
        'rewrite'           => [ 'slug' => 'portfolio', 'with_front' => false ],
        'show_in_rest'      => true,
    ] );

    // Make %portfolio_cat% resolvable inside the CPT single permalink.
    add_rewrite_tag( '%portfolio_cat%', '([^/]+)', 'portfolio_cat=' );
} );

/**
 * Fill the %portfolio_cat% placeholder in a portfolio work's permalink with its
 * (first) subcategory slug → /portfolio/{subcat}/{work}/.
 */
add_filter( 'post_type_link', function ( $link, $post ) {
    if ( 'portfolio' !== $post->post_type ) {
        return $link;
    }
    $terms = get_the_terms( $post, 'portfolio_cat' );
    $slug  = ( $terms && ! is_wp_error( $terms ) ) ? $terms[0]->slug : 'raboty';
    return str_replace( '%portfolio_cat%', $slug, $link );
}, 10, 2 );

/**
 * Insert the "Портфолио" listing level into the Yoast breadcrumb trail for
 * portfolio subcategory archives and single works, so the crumbs match the
 * /portfolio/{subcat}/{work}/ URL structure.
 */
add_filter( 'wpseo_breadcrumb_links', function ( $crumbs ) {
    if ( is_singular( 'portfolio' ) || is_tax( 'portfolio_cat' ) ) {
        array_splice( $crumbs, 1, 0, [ [
            'text' => 'Портфолио',
            'url'  => home_url( '/portfolio/' ),
        ] ] );
    }
    return $crumbs;
} );

/**
 * Cover for a portfolio item: featured image when set, otherwise a brand
 * placeholder (gradient class `pf_cover` + icon key `pf_icon` from post meta).
 */
function stylen_portfolio_cover( $post_id = null, $ctx = 'pf-item' ) {
    $post_id = $post_id ?: get_the_ID();
    if ( has_post_thumbnail( $post_id ) ) {
        return '<span class="' . esc_attr( $ctx ) . '__cover">' . get_the_post_thumbnail( $post_id, 'large', [ 'alt' => '' ] ) . '</span>';
    }
    $cover = get_post_meta( $post_id, 'pf_cover', true ) ?: 'a';
    $icon  = get_post_meta( $post_id, 'pf_icon', true ) ?: 'star';
    return '<span class="' . esc_attr( $ctx ) . '__cover pf-cover--' . esc_attr( $cover ) . '">' . stylen_icon( $icon, 'icon' ) . '</span>';
}

/**
 * Render one portfolio card (.pf-item) for the current loop post. Shared by the
 * portfolio listing and the single "смотрите также" grid so both stay in sync.
 */
function stylen_pf_card( $reveal_delay = 0 ) {
    $terms = get_the_terms( get_the_ID(), 'portfolio_cat' );
    $slugs = '';
    $name  = '';
    if ( $terms && ! is_wp_error( $terms ) ) {
        $slugs = implode( ' ', wp_list_pluck( $terms, 'slug' ) );
        $name  = $terms[0]->name;
    }
    $has_img   = has_post_thumbnail();
    $cover_cls = $has_img ? '' : ' pf-cover--' . esc_attr( get_post_meta( get_the_ID(), 'pf_cover', true ) ?: 'a' );

    ob_start(); ?>
    <article class="pf-item" data-cat="<?php echo esc_attr( $slugs ); ?>" data-reveal style="--d:<?php echo (int) $reveal_delay; ?>ms">
        <a class="pf-item__cover<?php echo $cover_cls; ?>" href="<?php the_permalink(); ?>" aria-label="<?php the_title_attribute(); ?>">
            <?php if ( $name ) : ?><span class="pf-item__tag"><?php echo esc_html( $name ); ?></span><?php endif; ?>
            <?php
            if ( $has_img ) {
                the_post_thumbnail( 'large', [ 'alt' => '' ] );
            } else {
                echo stylen_icon( get_post_meta( get_the_ID(), 'pf_icon', true ) ?: 'star', 'icon' );
            }
            ?>
        </a>
        <div class="pf-item__body">
            <h2 class="pf-item__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
            <?php if ( has_excerpt() ) : ?>
                <p class="pf-item__meta"><?php echo stylen_icon( 'check', 'icon icon--sm' ); ?><?php echo esc_html( get_the_excerpt() ); ?></p>
            <?php endif; ?>
        </div>
    </article>
    <?php
    return ob_get_clean();
}

/**
 * Route catalog pages (the id-10 subtree) to dedicated templates without
 * having to assign a Page Template to each of the 35 imported pages:
 *   - the root            → catalog-home.php
 *   - pages with children → catalog-branch.php  (a direction)
 *   - leaf pages          → catalog-product.php (a terminal product)
 * A page with an explicitly chosen template is left untouched.
 */
add_filter( 'template_include', function ( $template ) {
    if ( ! is_page() || ! stylen_is_catalog() ) {
        return $template;
    }
    $slug = get_page_template_slug();
    if ( $slug && 'default' !== $slug ) {
        return $template; // respect an explicitly assigned Page Template
    }
    $post = get_queried_object();
    if ( (int) $post->ID === stylen_catalog_root() ) {
        $choice = 'catalog-home.php';
    } elseif ( stylen_child_pages( $post->ID ) ) {
        $choice = 'catalog-branch.php';
    } else {
        $choice = 'catalog-product.php';
    }
    $located = locate_template( $choice );
    return $located ? $located : $template;
}, 99 );

/**
 * Add a hidden `page-url` field to every CF7 form so the submission carries the
 * address of the page it was sent from. Value is the current request URL
 * (JS also refreshes it live on submit — see main.js).
 */
add_filter( 'wpcf7_form_hidden_fields', function ( $fields ) {
    $scheme = is_ssl() ? 'https' : 'http';
    $host   = isset( $_SERVER['HTTP_HOST'] ) ? wp_unslash( $_SERVER['HTTP_HOST'] ) : (string) wp_parse_url( home_url(), PHP_URL_HOST );
    $uri    = isset( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : '/';
    $fields['page-url'] = esc_url_raw( $scheme . '://' . $host . $uri );
    return $fields;
} );

/**
 * Append the submitting page URL to the admin notification email.
 */
add_filter( 'wpcf7_mail_components', function ( $components, $contact_form, $mail = null ) {
    if ( is_object( $mail ) && method_exists( $mail, 'name' ) && 'mail' !== $mail->name() ) {
        return $components; // only the admin notification, not the auto-reply
    }
    if ( ! empty( $_POST['page-url'] ) ) {
        $url = esc_url_raw( wp_unslash( $_POST['page-url'] ) );
        if ( $url && ( empty( $components['body'] ) || false === strpos( $components['body'], $url ) ) ) {
            $components['body'] .= "\n\nОтправлено со страницы: " . $url;
        }
    }
    return $components;
}, 10, 3 );

<?php
/**
 * Theme helpers: business contacts, inline SVG icons, catalog direction meta.
 *
 * @package Stylen
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Central business contact data — single source of truth for header/footer/homepage.
 */
function stylen_contacts() {
    static $c = null;
    if ( null !== $c ) {
        return $c;
    }
    $g = function ( $key, $default = '' ) {
        $v = function_exists( 'get_field' ) ? get_field( $key, 'option' ) : '';
        return ( '' === $v || null === $v ) ? $default : $v;
    };
    $phone   = $g( 'c_phone' );
    $address = $g( 'c_address' );
    $c = [
        'company'    => $g( 'c_company' ),
        'legal'      => $g( 'c_legal' ),
        'phone'      => $phone,
        'phone_href' => 'tel:+' . preg_replace( '/\D+/', '', $phone ),
        'email'      => $g( 'c_email' ),
        'address'    => $address,
        'zip'        => $g( 'c_zip' ),
        'hours'      => $g( 'c_hours' ),
        'city_short' => $g( 'c_city_short' ),
        'founded'    => (int) $g( 'c_founded', 1995 ),
        'map'        => 'https://yandex.ru/maps/?text=' . rawurlencode( $address ),
    ];
    return $c;
}

/**
 * Split the brand name (from ACF `c_company`) into [name, accent] at the last
 * hyphen, so "Стиль-Н" → ["Стиль", "-Н"] for the two-tone logotype.
 */
function stylen_brand_split() {
    $c     = stylen_contacts();
    $brand = (string) $c['company'];
    $pos   = mb_strrpos( $brand, '-' );
    if ( false === $pos ) {
        return [ $brand, '' ];
    }
    return [ mb_substr( $brand, 0, $pos ), mb_substr( $brand, $pos ) ];
}

/**
 * Years the company has been on the market.
 */
function stylen_years_on_market() {
    $c = stylen_contacts();
    return max( 1, (int) date( 'Y' ) - (int) $c['founded'] );
}

/**
 * Inline SVG icon set (24×24, stroke = currentColor). Keeps the page fast (no icon font).
 */
function stylen_icon( $name, $class = 'icon' ) {
    $p = 'fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"';
    $paths = [
        // Catalog directions
        'wide-format'     => '<rect x="4" y="7" width="16" height="10" rx="1.5" '.$p.'/><path d="M7 7V4h10v3M7 17v3h10v-3" '.$p.'/><path d="M8 13h8" '.$p.'/>',
        'plaques'         => '<rect x="4" y="4" width="16" height="16" rx="2" '.$p.'/><path d="M8 9h8M8 13h5" '.$p.'/><circle cx="16" cy="13.5" r="0.6" fill="currentColor"/>',
        'stands-safety'   => '<path d="M12 3l7 3v5c0 4.2-2.9 6.8-7 8-4.1-1.2-7-3.8-7-8V6l7-3z" '.$p.'/><path d="M9.5 12l1.8 1.8L15 10" '.$p.'/>',
        'displays'        => '<rect x="5" y="4" width="14" height="10" rx="1.5" '.$p.'/><path d="M12 14v4M8 20l4-2 4 2" '.$p.'/>',
        'stickers-labels' => '<path d="M4 5.5A1.5 1.5 0 015.5 4H14l6 6v8.5A1.5 1.5 0 0118.5 20h-13A1.5 1.5 0 014 18.5z" '.$p.'/><path d="M14 4v5a1 1 0 001 1h5" '.$p.'/>',
        'backdrops'       => '<rect x="3" y="5" width="18" height="14" rx="2" '.$p.'/><circle cx="8.5" cy="10" r="1.5" '.$p.'/><path d="M4 17l5-4 4 3 3-2 4 3" '.$p.'/>',
        'interior'        => '<rect x="4" y="4" width="16" height="16" rx="1.5" '.$p.'/><rect x="7.5" y="7.5" width="9" height="9" rx="0.5" '.$p.'/>',
        'signs'           => '<path d="M5 5h14M5 5v14M19 5v14M5 19h14" '.$p.'/><path d="M9 15V9l3 4 3-4v6" '.$p.'/>',
        // UI
        'phone'   => '<path d="M6.5 4h3l1.2 4-2 1.3a11 11 0 005 5l1.3-2 4 1.2v3a2 2 0 01-2.2 2A16 16 0 014.5 6.2 2 2 0 016.5 4z" '.$p.'/>',
        'mail'    => '<rect x="3" y="5" width="18" height="14" rx="2" '.$p.'/><path d="M4 7l8 6 8-6" '.$p.'/>',
        'pin'     => '<path d="M12 21s7-5.6 7-11a7 7 0 10-14 0c0 5.4 7 11 7 11z" '.$p.'/><circle cx="12" cy="10" r="2.5" '.$p.'/>',
        'clock'   => '<circle cx="12" cy="12" r="8" '.$p.'/><path d="M12 8v4l3 2" '.$p.'/>',
        'check'   => '<circle cx="12" cy="12" r="8" '.$p.'/><path d="M8.5 12.2l2.3 2.3L16 9.5" '.$p.'/>',
        'arrow'   => '<path d="M5 12h14M13 6l6 6-6 6" '.$p.'/>',
        'plus'    => '<path d="M12 5v14M5 12h14" '.$p.'/>',
        'star'    => '<path d="M12 3l2.6 5.3 5.9.9-4.3 4.1 1 5.8L12 16.9 6.8 19.2l1-5.8L3.5 9.2l5.9-.9z" '.$p.'/>',
        'factory' => '<path d="M3 21V10l6 4V10l6 4V6l6 4v11z" '.$p.'/><path d="M3 21h18" '.$p.'/>',
        'bolt'    => '<path d="M13 3L5 13h6l-1 8 8-10h-6z" '.$p.'/>',
        'palette' => '<path d="M12 3a9 9 0 000 18c1.7 0 2-1.3 1.2-2.2-.8-.9-.3-2.3 1-2.3H17a4 4 0 004-4c0-4.4-4-9.5-9-9.5z" '.$p.'/><circle cx="8" cy="11" r="1" fill="currentColor"/><circle cx="12" cy="8" r="1" fill="currentColor"/><circle cx="16" cy="11" r="1" fill="currentColor"/>',
        'shield'  => '<path d="M12 3l7 3v5c0 4.2-2.9 6.8-7 8-4.1-1.2-7-3.8-7-8V6l7-3z" '.$p.'/>',
        'maximize'=> '<path d="M4 9V5a1 1 0 011-1h4M20 9V5a1 1 0 00-1-1h-4M4 15v4a1 1 0 001 1h4M20 15v4a1 1 0 01-1 1h-4" '.$p.'/>',
        'sun'     => '<circle cx="12" cy="12" r="4" '.$p.'/><path d="M12 2v2M12 20v2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M2 12h2M20 12h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4" '.$p.'/>',
        'scissors'=> '<circle cx="6" cy="6" r="2.4" '.$p.'/><circle cx="6" cy="18" r="2.4" '.$p.'/><path d="M8.1 7.6L20 18M8.1 16.4L20 6M14 12l-3.5 2.5" '.$p.'/>',
        'layers'  => '<path d="M12 3l9 5-9 5-9-5 9-5z" '.$p.'/><path d="M3 12l9 5 9-5M3 16l9 5 9-5" '.$p.'/>',
        // Social / messengers (filled glyphs)
        'vk'      => '<path fill="currentColor" d="M12.8 17.4c-5.6 0-9.1-3.9-9.2-10.4h2.9c.1 4.8 2.2 6.7 3.8 7.1V7h2.7v4c1.6-.2 3.2-1.9 3.8-4h2.7c-.5 2.6-2.1 4.4-3.3 5.1 1.2.6 3 2.1 3.8 4.4h-3c-.6-1.7-2-3.1-3.6-3.3v3.3z"/>',
        'ok'      => '<path fill="currentColor" d="M12 2.8a3.5 3.5 0 100 7 3.5 3.5 0 000-7zm0 2.2a1.3 1.3 0 110 2.6 1.3 1.3 0 010-2.6z"/><path fill="currentColor" d="M15.1 12.4a8 8 0 01-2.1.9l2 2a1.15 1.15 0 01-1.6 1.65L12 15.5l-1.4 1.45a1.15 1.15 0 11-1.6-1.65l2-2a8 8 0 01-2.1-.9 1.15 1.15 0 011.2-1.95 5.1 5.1 0 004.8 0 1.15 1.15 0 011.2 1.95z"/>',
        'max'     => '<path d="M4 5.5A1.5 1.5 0 015.5 4h13A1.5 1.5 0 0120 5.5v9a1.5 1.5 0 01-1.5 1.5H9.5L5 20v-3.5A1.5 1.5 0 014 15z" '.$p.'/><circle cx="9" cy="10" r="1" fill="currentColor"/><circle cx="12" cy="10" r="1" fill="currentColor"/><circle cx="15" cy="10" r="1" fill="currentColor"/>',
    ];
    $svg = isset( $paths[ $name ] ) ? $paths[ $name ] : '';
    return '<svg class="' . esc_attr( $class ) . '" viewBox="0 0 24 24" width="24" height="24" aria-hidden="true" focusable="false">' . $svg . '</svg>';
}

/**
 * Direction meta by slug: icon key (structural) + desc/body read from ACF on
 * the direction page (content migrated out of the theme, editable in admin).
 */
function stylen_direction_meta( $slug ) {
    $icons = [ 'wide-format', 'plaques', 'stands-safety', 'displays', 'stickers-labels', 'backdrops', 'interior', 'signs' ];

    static $ids = null;
    if ( null === $ids ) {
        $ids = [];
        foreach ( stylen_get_directions() as $d ) {
            $ids[ $d->post_name ] = $d->ID;
        }
    }
    $id  = $ids[ $slug ] ?? 0;
    $acf = $id && function_exists( 'get_field' );

    return [
        'icon' => in_array( $slug, $icons, true ) ? $slug : 'star',
        'desc' => $acf ? (string) get_field( 'direction_desc', $id ) : '',
        'body' => $acf ? (string) get_field( 'direction_body', $id ) : '',
    ];
}

/**
 * Intro copy for the portfolio landing page (/portfolio/).
 */
function stylen_portfolio_intro() {
    return [
        'title' => function_exists( 'get_field' ) ? (string) get_field( 'pf_intro_title', 'option' ) : '',
        'body'  => function_exists( 'get_field' ) ? (string) get_field( 'pf_intro_body', 'option' ) : '',
    ];
}

/**
 * Portfolio subcategory meta by term slug: lead + about-title from ACF on the
 * term. The rich body is the term's own description field (see the template).
 */
function stylen_pf_cat_meta( $slug ) {
    $term = get_term_by( 'slug', $slug, 'portfolio_cat' );
    if ( ! $term || is_wp_error( $term ) || ! function_exists( 'get_field' ) ) {
        return [ 'lead' => '', 'title' => '', 'body' => '' ];
    }
    return [
        'lead'  => (string) get_field( 'pf_cat_lead', $term ),
        'title' => (string) get_field( 'pf_cat_about_title', $term ),
        'body'  => '',
    ];
}

/**
 * Append a caret element to top-level menu items that have a submenu.
 */
add_filter( 'nav_menu_item_title', function ( $title, $item, $args, $depth ) {
    if ( isset( $args->theme_location ) && 'primary' === $args->theme_location
        && 0 === $depth && in_array( 'menu-item-has-children', (array) $item->classes, true ) ) {
        $title .= ' <span class="caret" aria-hidden="true"></span>';
    }
    return $title;
}, 10, 4 );

/**
 * Estimate reading time (minutes) for a post from its word count.
 */
function stylen_reading_time( $post_id = null ) {
    $content = get_post_field( 'post_content', $post_id ?: get_the_ID() );
    $words   = max( 1, str_word_count( wp_strip_all_tags( strip_shortcodes( $content ) ) ) );
    return max( 1, (int) ceil( $words / 180 ) );
}

/**
 * Map a blog category slug → icon key + gradient placeholder class.
 * Falls back to a hashed gradient so every post has a distinct cover.
 */
function stylen_post_visual( $post_id = null ) {
    $post_id = $post_id ?: get_the_ID();
    $map = [
        'naruzhnaya-reklama' => [ 'signs',       'a' ],
        'pechat'             => [ 'wide-format', 'b' ],
        'sovety'             => [ 'palette',     'c' ],
        'kejsy'              => [ 'star',        'd' ],
        'novosti'            => [ 'bolt',        'e' ],
    ];
    foreach ( get_the_category( $post_id ) as $cat ) {
        if ( isset( $map[ $cat->slug ] ) ) {
            return [ 'icon' => $map[ $cat->slug ][0], 'cover' => $map[ $cat->slug ][1] ];
        }
    }
    $covers = [ 'a', 'b', 'c', 'd', 'e' ];
    return [ 'icon' => 'palette', 'cover' => $covers[ $post_id % 5 ] ];
}

/**
 * Echo a post cover: featured image when present, else a branded gradient
 * placeholder with a category icon. $context is a BEM element class root.
 */
function stylen_post_cover( $context, $size = 'medium_large', $post_id = null ) {
    $post_id = $post_id ?: get_the_ID();
    $vis     = stylen_post_visual( $post_id );
    $class   = $context . '__cover';
    if ( has_post_thumbnail( $post_id ) ) {
        echo '<span class="' . esc_attr( $class ) . '">' . get_the_post_thumbnail( $post_id, $size, [ 'loading' => 'lazy', 'alt' => '' ] ) . '</span>';
    } else {
        echo '<span class="' . esc_attr( $class . ' pf-cover--' . $vis['cover'] ) . '">' . stylen_icon( $vis['icon'], 'icon' ) . '</span>';
    }
}

/**
 * Render interior-page breadcrumbs from Yoast SEO — the single source of truth,
 * so the on-page trail matches the BreadcrumbList schema Yoast emits. The trail
 * itself is shaped in Yoast (see the `wpseo_breadcrumb_links` filter in
 * functions.php for the portfolio hierarchy). Falls back to a bare "Главная"
 * link only if Yoast is unavailable.
 */
function stylen_breadcrumbs() {
    if ( function_exists( 'yoast_breadcrumb' ) ) {
        yoast_breadcrumb(
            '<nav class="breadcrumbs" aria-label="' . esc_attr__( 'Хлебные крошки', 'stylen' ) . '">',
            '</nav>'
        );
        return;
    }

    echo '<nav class="breadcrumbs" aria-label="' . esc_attr__( 'Хлебные крошки', 'stylen' ) . '">';
    echo '<a href="' . esc_url( home_url( '/' ) ) . '">Главная</a>';
    echo '</nav>';
}

/**
 * Russian plural form: stylen_plural(2, 'вид','вида','видов') → 'вида'.
 */
function stylen_plural( $n, $one, $few, $many ) {
    $n = abs( (int) $n ) % 100;
    $n1 = $n % 10;
    if ( $n > 10 && $n < 20 ) return $many;
    if ( $n1 > 1 && $n1 < 5 ) return $few;
    if ( 1 === $n1 ) return $one;
    return $many;
}

/**
 * Root page id of the catalog tree.
 */
function stylen_catalog_root() {
    return 10;
}

/**
 * Published child pages of a parent, ordered by menu_order.
 */
function stylen_child_pages( $parent_id ) {
    return get_posts( [
        'post_type'   => 'page',
        'post_parent' => (int) $parent_id,
        'numberposts' => -1,
        'orderby'     => 'menu_order',
        'order'       => 'ASC',
        'post_status' => 'publish',
    ] );
}

/**
 * Top-level catalog directions (children of the Catalog page, id 10), ordered.
 */
function stylen_get_directions() {
    return stylen_child_pages( stylen_catalog_root() );
}

/**
 * Is the given (or current) page the catalog root or one of its descendants?
 */
function stylen_is_catalog( $post = null ) {
    $post = get_post( $post );
    if ( ! $post || 'page' !== $post->post_type ) {
        return false;
    }
    $root = stylen_catalog_root();
    return (int) $post->ID === $root || in_array( $root, get_post_ancestors( $post->ID ), true );
}

/**
 * Catalog page level: '' (not catalog), 'root', 'branch' (direction), 'leaf'
 * (product). Drives the ACF location rule + the content migration.
 */
function stylen_catalog_page_level( $post = null ) {
    $post = get_post( $post );
    if ( ! $post || ! stylen_is_catalog( $post ) ) {
        return '';
    }
    if ( (int) $post->ID === stylen_catalog_root() ) {
        return 'root';
    }
    return stylen_child_pages( $post->ID ) ? 'branch' : 'leaf';
}

/**
 * The top-level catalog direction a page belongs to (its level-2 ancestor,
 * or itself if it already is a direction). Returns a WP_Post or null.
 */
function stylen_direction_of( $post = null ) {
    $post = get_post( $post );
    if ( ! $post ) {
        return null;
    }
    $root      = stylen_catalog_root();
    $ancestors = array_reverse( get_post_ancestors( $post->ID ) ); // root → parent
    // Build the chain root-first including self.
    $chain = array_merge( $ancestors, [ $post->ID ] );
    $ri    = array_search( $root, $chain, true );
    if ( false === $ri || ! isset( $chain[ $ri + 1 ] ) ) {
        // page IS a direction (child of root) or is the root itself
        return ( (int) $post->post_parent === $root ) ? $post : null;
    }
    return get_post( $chain[ $ri + 1 ] );
}

/**
 * Icon key for any catalog page — inherited from its top-level direction.
 */
function stylen_catalog_icon( $post = null ) {
    $dir = stylen_direction_of( $post );
    if ( $dir ) {
        return stylen_direction_meta( $dir->post_name )['icon'];
    }
    return 'layers';
}

/**
 * Resolve the page-hero background image (attachment ID) for a catalog page.
 * Products may set their own `product_hero_bg`; when empty they inherit the
 * parent direction's `direction_hero_bg`. Directions use their own field.
 * Returns an attachment ID or 0.
 */
function stylen_catalog_hero_bg( $post = null ) {
    if ( ! function_exists( 'get_field' ) ) {
        return 0;
    }
    $post  = get_post( $post );
    if ( ! $post ) {
        return 0;
    }
    $level = stylen_catalog_page_level( $post );

    if ( 'leaf' === $level ) {
        $own = (int) get_field( 'product_hero_bg', $post->ID );
        if ( $own ) {
            return $own;
        }
        $dir = stylen_direction_of( $post );
        return $dir ? (int) get_field( 'direction_hero_bg', $dir->ID ) : 0;
    }

    if ( 'branch' === $level ) {
        return (int) get_field( 'direction_hero_bg', $post->ID );
    }

    return 0;
}

/**
 * Render the media backdrop (image + readability scrim) for a catalog page-hero.
 * Echoes nothing when the page has no background, so the hero falls back to the
 * default light treatment. Call INSIDE <section class="page-hero …"> before the
 * .container. Returns true when a backdrop was printed (so the caller can add
 * the `page-hero--media` modifier + light-on-dark markup).
 */
function stylen_catalog_hero_backdrop( $post = null ) {
    $bg = stylen_catalog_hero_bg( $post );
    if ( ! $bg ) {
        return false;
    }
    $url = wp_get_attachment_image_url( $bg, 'full' );
    if ( ! $url ) {
        return false;
    }
    echo '<span class="page-hero__bg" aria-hidden="true" style="background-image:url(\'' . esc_url( $url ) . '\')"></span>';
    return true;
}

/**
 * Gradient placeholder cover class (a–e), stable per page id.
 */
function stylen_catalog_cover_class( $post_id ) {
    $covers = [ 'a', 'b', 'c', 'd', 'e' ];
    return $covers[ (int) $post_id % 5 ];
}

/**
 * Echo a catalog card cover: featured image, else a branded gradient + icon.
 */
function stylen_catalog_cover( $post, $context = 'cat-card' ) {
    $post  = get_post( $post );
    $class = $context . '__cover';
    if ( has_post_thumbnail( $post ) ) {
        echo '<span class="' . esc_attr( $class ) . '">' . get_the_post_thumbnail( $post, 'medium_large', [ 'loading' => 'lazy', 'alt' => '' ] ) . '</span>';
    } else {
        echo '<span class="' . esc_attr( $class . ' pf-cover--' . stylen_catalog_cover_class( $post->ID ) ) . '">'
            . stylen_icon( stylen_catalog_icon( $post ), 'icon' ) . '</span>';
    }
}


/**
 * Product meta read from ACF on the product page (content migrated out of the
 * theme, editable per product). The old slug-keyed arrays are gone.
 * Returns [ 'desc' => string, 'specs' => [ label => value ], 'price' => string, 'body' => html ].
 */
function stylen_product_meta( $post = null ) {
	$id  = get_post( $post ) ? get_post( $post )->ID : 0;
	$acf = $id && function_exists( 'get_field' );

	$specs = [];
	if ( $acf ) {
		while ( have_rows( 'product_specs', $id ) ) {
			the_row();
			$label = trim( (string) get_sub_field( 'label' ) );
			if ( '' !== $label ) {
				$specs[ $label ] = (string) get_sub_field( 'value' );
			}
		}
	}

	return [
		'desc'  => $acf ? (string) get_field( 'product_desc', $id ) : '',
		'specs' => $specs,
		'price' => $acf ? (string) get_field( 'product_price', $id ) : '',
		'body'  => $acf ? (string) get_field( 'product_body', $id ) : '',
	];
}

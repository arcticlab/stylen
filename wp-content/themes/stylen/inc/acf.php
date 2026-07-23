<?php
/**
 * ACF content architecture — all SEO/business copy is managed from the admin.
 *
 * - An Options page «Контент сайта» holds global copy (contacts, header/footer
 *   labels, order-modal text, portfolio intro).
 * - A field group bound to the front page holds every homepage section.
 *
 * Field definitions live here (compact PHP); the values live in the database.
 *
 * @package Stylen
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'acf_add_local_field_group' ) ) {
	return;
}

/* Options page. */
add_action( 'acf/init', function () {
	if ( function_exists( 'acf_add_options_page' ) ) {
		acf_add_options_page( [
			'page_title' => 'Контент сайта',
			'menu_title' => 'Контент сайта',
			'menu_slug'  => 'stylen-content',
			'capability' => 'edit_theme_options',
			'icon_url'   => 'dashicons-edit-large',
			'position'   => 3,
			'redirect'   => false,
		] );
	}
} );

/* Small helpers to keep field definitions terse. */
function stylen_acf_text( $key, $label, $name, $extra = [] ) {
	return array_merge( [ 'key' => $key, 'label' => $label, 'name' => $name, 'type' => 'text' ], $extra );
}
function stylen_acf_tab( $key, $label ) {
	return [ 'key' => $key, 'label' => $label, 'type' => 'tab', 'placement' => 'top' ];
}

add_action( 'acf/init', function () {

	/* ============================================================
	   GLOBAL — Options page «Контент сайта»
	   ============================================================ */
	acf_add_local_field_group( [
		'key'      => 'group_stylen_global',
		'title'    => 'Контент сайта',
		'location' => [ [ [ 'param' => 'options_page', 'operator' => '==', 'value' => 'stylen-content' ] ] ],
		'fields'   => [
			stylen_acf_tab( 'tab_contacts', 'Контакты' ),
			stylen_acf_text( 'field_c_company', 'Название компании', 'c_company' ),
			stylen_acf_text( 'field_c_legal', 'Юридическое описание', 'c_legal' ),
			stylen_acf_text( 'field_c_phone', 'Телефон', 'c_phone' ),
			stylen_acf_text( 'field_c_email', 'E-mail', 'c_email' ),
			stylen_acf_text( 'field_c_address', 'Адрес', 'c_address' ),
			stylen_acf_text( 'field_c_zip', 'Индекс', 'c_zip' ),
			stylen_acf_text( 'field_c_hours', 'Часы работы', 'c_hours' ),
			stylen_acf_text( 'field_c_city', 'Город (кратко)', 'c_city_short' ),
			[ 'key' => 'field_c_founded', 'label' => 'Год основания', 'name' => 'c_founded', 'type' => 'number' ],

			stylen_acf_tab( 'tab_chrome', 'Шапка и подвал' ),
			stylen_acf_text( 'field_header_cta', 'Кнопка в шапке', 'header_cta_label' ),
			stylen_acf_text( 'field_brand_tag', 'Подпись логотипа', 'brand_tag' ),
			[ 'key' => 'field_footer_about', 'label' => 'Текст о компании (подвал)', 'name' => 'footer_about', 'type' => 'textarea', 'rows' => 3, 'new_lines' => '' ],
			stylen_acf_text( 'field_footer_cta', 'Кнопка в подвале', 'footer_cta_label' ),

			stylen_acf_tab( 'tab_modal', 'Модальное окно заявки' ),
			stylen_acf_text( 'field_modal_eyebrow', 'Надзаголовок', 'modal_eyebrow' ),
			stylen_acf_text( 'field_modal_title', 'Заголовок', 'modal_title' ),
			[ 'key' => 'field_modal_text', 'label' => 'Описание', 'name' => 'modal_text', 'type' => 'textarea', 'rows' => 3, 'new_lines' => '' ],

			stylen_acf_tab( 'tab_pf_intro', 'Портфолио — вступление' ),
			stylen_acf_text( 'field_pf_intro_title', 'Заголовок блока', 'pf_intro_title' ),
			[ 'key' => 'field_pf_intro_body', 'label' => 'Текст', 'name' => 'pf_intro_body', 'type' => 'wysiwyg', 'tabs' => 'all', 'media_upload' => 0 ],
		],
	] );

	/* ============================================================
	   HOMEPAGE — bound to the front page
	   ============================================================ */
	acf_add_local_field_group( [
		'key'      => 'group_stylen_home',
		'title'    => 'Главная страница',
		'location' => [ [ [ 'param' => 'page_type', 'operator' => '==', 'value' => 'front_page' ] ] ],
		'fields'   => [

			stylen_acf_tab( 'tab_home_hero', 'Первый экран' ),
			stylen_acf_text( 'field_home_hero_eyebrow', 'Надзаголовок', 'home_hero_eyebrow' ),
			[ 'key' => 'field_home_hero_title', 'label' => 'Заголовок (можно <span class="em">…</span>)', 'name' => 'home_hero_title', 'type' => 'textarea', 'rows' => 2, 'new_lines' => '' ],
			[ 'key' => 'field_home_hero_text', 'label' => 'Описание', 'name' => 'home_hero_text', 'type' => 'textarea', 'rows' => 3, 'new_lines' => '' ],
			stylen_acf_text( 'field_home_hero_cta_calc', 'Кнопка «Рассчитать»', 'home_hero_cta_calc' ),
			stylen_acf_text( 'field_home_hero_cta_services', 'Кнопка «Услуги»', 'home_hero_cta_services' ),

			stylen_acf_tab( 'tab_home_shots', 'Витрина работ (фото под первым экраном)' ),
			[ 'key' => 'field_home_shots', 'label' => 'Фото работ', 'name' => 'home_shots', 'type' => 'repeater', 'layout' => 'block', 'max' => 4, 'button_label' => 'Добавить фото', 'sub_fields' => [
				[ 'key' => 'field_home_shot_img', 'label' => 'Фото', 'name' => 's_img', 'type' => 'image', 'return_format' => 'id', 'preview_size' => 'medium' ],
				stylen_acf_text( 'field_home_shot_cap', 'Подпись', 's_cap' ),
			] ],

			stylen_acf_tab( 'tab_home_est', 'Карточка-смета' ),
			stylen_acf_text( 'field_home_est_tag', 'Заголовок карточки', 'home_est_tag' ),
			stylen_acf_text( 'field_home_est_hint', 'Пометка', 'home_est_hint' ),
			stylen_acf_text( 'field_home_est_k_dir', 'Строка: направление', 'home_est_k_dir' ),
			stylen_acf_text( 'field_home_est_k_term', 'Строка: срок', 'home_est_k_term' ),
			stylen_acf_text( 'field_home_est_k_layout', 'Строка: макет', 'home_est_k_layout' ),
			stylen_acf_text( 'field_home_est_free', 'Значение «макет»', 'home_est_free' ),
			stylen_acf_text( 'field_home_est_cta', 'Кнопка', 'home_est_cta' ),
			stylen_acf_text( 'field_home_est_note', 'Примечание', 'home_est_note' ),

			stylen_acf_tab( 'tab_home_trust', 'Полоса доверия' ),
			[ 'key' => 'field_home_trust', 'label' => 'Пункты', 'name' => 'home_trust', 'type' => 'repeater', 'layout' => 'table', 'button_label' => 'Добавить пункт', 'sub_fields' => [
				stylen_acf_text( 'field_home_trust_icon', 'Иконка (ключ)', 't_icon' ),
				stylen_acf_text( 'field_home_trust_value', 'Значение', 't_value' ),
				stylen_acf_text( 'field_home_trust_caption', 'Подпись', 't_caption' ),
			] ],

			stylen_acf_tab( 'tab_home_services', 'Каталог' ),
			stylen_acf_text( 'field_home_services_eyebrow', 'Надзаголовок', 'home_services_eyebrow' ),
			stylen_acf_text( 'field_home_services_title', 'Заголовок', 'home_services_title' ),
			[ 'key' => 'field_home_services_text', 'label' => 'Описание', 'name' => 'home_services_text', 'type' => 'textarea', 'rows' => 2, 'new_lines' => '' ],

			stylen_acf_tab( 'tab_home_adv', 'Преимущества' ),
			stylen_acf_text( 'field_home_adv_eyebrow', 'Надзаголовок', 'home_adv_eyebrow' ),
			[ 'key' => 'field_home_adv_title', 'label' => 'Заголовок (можно <span class="em">…</span>)', 'name' => 'home_adv_title', 'type' => 'textarea', 'rows' => 2, 'new_lines' => '' ],
			[ 'key' => 'field_home_adv_text', 'label' => 'Описание', 'name' => 'home_adv_text', 'type' => 'textarea', 'rows' => 3, 'new_lines' => '' ],
			[ 'key' => 'field_home_adv_image', 'label' => 'Фото производства', 'name' => 'home_adv_image', 'type' => 'image', 'return_format' => 'id', 'preview_size' => 'medium' ],
			[ 'key' => 'field_home_adv_items', 'label' => 'Пункты', 'name' => 'home_adv_items', 'type' => 'repeater', 'layout' => 'block', 'button_label' => 'Добавить', 'sub_fields' => [
				stylen_acf_text( 'field_home_adv_icon', 'Иконка', 'a_icon' ),
				stylen_acf_text( 'field_home_adv_i_title', 'Заголовок', 'a_title' ),
				[ 'key' => 'field_home_adv_i_text', 'label' => 'Текст', 'name' => 'a_text', 'type' => 'textarea', 'rows' => 2, 'new_lines' => '' ],
			] ],

			stylen_acf_tab( 'tab_home_stats', 'Цифры' ),
			stylen_acf_text( 'field_home_stats_eyebrow', 'Надзаголовок', 'home_stats_eyebrow' ),
			stylen_acf_text( 'field_home_stats_title', 'Заголовок', 'home_stats_title' ),
			[ 'key' => 'field_home_stats_items', 'label' => 'Показатели', 'name' => 'home_stats_items', 'type' => 'repeater', 'layout' => 'block', 'button_label' => 'Добавить', 'sub_fields' => [
				stylen_acf_text( 'field_home_stats_icon', 'Иконка', 's_icon' ),
				stylen_acf_text( 'field_home_stats_number', 'Число', 's_number' ),
				stylen_acf_text( 'field_home_stats_suffix', 'Суффикс', 's_suffix' ),
				stylen_acf_text( 'field_home_stats_label', 'Подпись', 's_label' ),
				[ 'key' => 'field_home_stats_countup', 'label' => 'Анимация счётчика', 'name' => 's_countup', 'type' => 'true_false', 'ui' => 1 ],
			] ],

			stylen_acf_tab( 'tab_home_process', 'Как заказать' ),
			stylen_acf_text( 'field_home_process_eyebrow', 'Надзаголовок', 'home_process_eyebrow' ),
			stylen_acf_text( 'field_home_process_title', 'Заголовок', 'home_process_title' ),
			[ 'key' => 'field_home_process_items', 'label' => 'Шаги', 'name' => 'home_process_items', 'type' => 'repeater', 'layout' => 'block', 'button_label' => 'Добавить шаг', 'sub_fields' => [
				stylen_acf_text( 'field_home_process_i_title', 'Заголовок', 'p_title' ),
				[ 'key' => 'field_home_process_i_text', 'label' => 'Текст', 'name' => 'p_text', 'type' => 'textarea', 'rows' => 2, 'new_lines' => '' ],
			] ],

			stylen_acf_tab( 'tab_home_works', 'Работы' ),
			stylen_acf_text( 'field_home_works_eyebrow', 'Надзаголовок', 'home_works_eyebrow' ),
			stylen_acf_text( 'field_home_works_title', 'Заголовок', 'home_works_title' ),
			stylen_acf_text( 'field_home_works_link', 'Ссылка «Всё портфолио»', 'home_works_link' ),
			[ 'key' => 'field_home_works_items', 'label' => 'Работы', 'name' => 'home_works_items', 'type' => 'repeater', 'layout' => 'block', 'button_label' => 'Добавить работу', 'sub_fields' => [
				stylen_acf_text( 'field_home_works_i_title', 'Название', 'w_title' ),
				stylen_acf_text( 'field_home_works_i_tag', 'Категория', 'w_tag' ),
				stylen_acf_text( 'field_home_works_i_icon', 'Иконка', 'w_icon' ),
				stylen_acf_text( 'field_home_works_i_color', 'Цвет обложки (hex)', 'w_color' ),
			] ],

			stylen_acf_tab( 'tab_home_reviews', 'Отзывы' ),
			stylen_acf_text( 'field_home_reviews_eyebrow', 'Надзаголовок', 'home_reviews_eyebrow' ),
			stylen_acf_text( 'field_home_reviews_title', 'Заголовок', 'home_reviews_title' ),
			[ 'key' => 'field_home_reviews_items', 'label' => 'Отзывы', 'name' => 'home_reviews_items', 'type' => 'repeater', 'layout' => 'block', 'button_label' => 'Добавить отзыв', 'sub_fields' => [
				[ 'key' => 'field_home_reviews_i_text', 'label' => 'Текст', 'name' => 'r_text', 'type' => 'textarea', 'rows' => 3, 'new_lines' => '' ],
				stylen_acf_text( 'field_home_reviews_i_name', 'Имя', 'r_name' ),
				stylen_acf_text( 'field_home_reviews_i_role', 'Роль/компания', 'r_role' ),
				[ 'key' => 'field_home_reviews_i_rating', 'label' => 'Оценка (1–5)', 'name' => 'r_rating', 'type' => 'number', 'min' => 1, 'max' => 5, 'default_value' => 5 ],
			] ],

			stylen_acf_tab( 'tab_home_order', 'Заявка' ),
			stylen_acf_text( 'field_home_order_eyebrow', 'Надзаголовок', 'home_order_eyebrow' ),
			stylen_acf_text( 'field_home_order_title', 'Заголовок', 'home_order_title' ),
			[ 'key' => 'field_home_order_text', 'label' => 'Описание', 'name' => 'home_order_text', 'type' => 'textarea', 'rows' => 3, 'new_lines' => '' ],
			[ 'key' => 'field_home_order_list', 'label' => 'Список', 'name' => 'home_order_list', 'type' => 'repeater', 'layout' => 'table', 'button_label' => 'Добавить пункт', 'sub_fields' => [
				stylen_acf_text( 'field_home_order_list_item', 'Пункт', 'o_item' ),
			] ],

			stylen_acf_tab( 'tab_home_map', 'Контакты (карта)' ),
			stylen_acf_text( 'field_home_map_eyebrow', 'Надзаголовок', 'home_map_eyebrow' ),
			stylen_acf_text( 'field_home_map_title', 'Заголовок', 'home_map_title' ),
			[ 'key' => 'field_home_map_text', 'label' => 'Описание', 'name' => 'home_map_text', 'type' => 'textarea', 'rows' => 2, 'new_lines' => '' ],
			stylen_acf_text( 'field_home_map_card_title', 'Заголовок карточки', 'home_map_card_title' ),

			stylen_acf_tab( 'tab_home_faq', 'Вопросы' ),
			stylen_acf_text( 'field_home_faq_eyebrow', 'Надзаголовок', 'home_faq_eyebrow' ),
			stylen_acf_text( 'field_home_faq_title', 'Заголовок', 'home_faq_title' ),
			[ 'key' => 'field_home_faq_text', 'label' => 'Описание', 'name' => 'home_faq_text', 'type' => 'textarea', 'rows' => 3, 'new_lines' => '' ],
			stylen_acf_text( 'field_home_faq_cta', 'Кнопка', 'home_faq_cta' ),
			[ 'key' => 'field_home_faq_items', 'label' => 'Вопросы', 'name' => 'home_faq_items', 'type' => 'repeater', 'layout' => 'block', 'button_label' => 'Добавить вопрос', 'sub_fields' => [
				stylen_acf_text( 'field_home_faq_q', 'Вопрос', 'f_q' ),
				[ 'key' => 'field_home_faq_a', 'label' => 'Ответ', 'name' => 'f_a', 'type' => 'textarea', 'rows' => 3, 'new_lines' => '' ],
			] ],
		],
	] );
} );

/* ============================================================
   Custom location rule — catalog page level (root/branch/leaf)
   ============================================================ */
add_filter( 'acf/location/rule_types', function ( $groups ) {
	$groups['Стиль-Н']['stylen_catalog_level'] = 'Каталог: уровень страницы';
	return $groups;
} );
add_filter( 'acf/location/rule_values/stylen_catalog_level', function () {
	return [ 'root' => 'Корень каталога', 'branch' => 'Направление', 'leaf' => 'Товар' ];
} );
add_filter( 'acf/location/rule_match/stylen_catalog_level', function ( $match, $rule, $screen ) {
	$pid = isset( $screen['post_id'] ) ? $screen['post_id'] : 0;
	if ( ! $pid || ! is_numeric( $pid ) || ! function_exists( 'stylen_catalog_page_level' ) ) {
		return false;
	}
	$level = stylen_catalog_page_level( (int) $pid );
	return '==' === $rule['operator'] ? ( $level === $rule['value'] ) : ( $level !== $rule['value'] );
}, 10, 3 );

/* ============================================================
   PHASE 2 — catalog directions / products, portfolio subcats
   ============================================================ */
add_action( 'acf/init', function () {

	/* Direction (branch) content — shown on direction pages (children of root). */
	acf_add_local_field_group( [
		'key'      => 'group_catalog_direction',
		'title'    => 'Каталог — направление: описание',
		'location' => [ [ [ 'param' => 'page_parent', 'operator' => '==', 'value' => (string) stylen_catalog_root() ] ] ],
		'fields'   => [
			[ 'key' => 'field_direction_desc', 'label' => 'Короткое описание (лид/карточка)', 'name' => 'direction_desc', 'type' => 'textarea', 'rows' => 2, 'new_lines' => '' ],
			stylen_acf_text( 'field_direction_price', 'Цена «от» (показывается над кнопкой «Рассчитать стоимость»)', 'direction_price', [ 'placeholder' => 'от 450 ₽/м²' ] ),
			[ 'key' => 'field_direction_body', 'label' => 'Текст «Об услуге»', 'name' => 'direction_body', 'type' => 'wysiwyg', 'tabs' => 'all', 'media_upload' => 0 ],
		],
	] );

	/* Product (leaf) content. */
	acf_add_local_field_group( [
		'key'      => 'group_catalog_product',
		'title'    => 'Каталог — товар',
		'location' => [ [ [ 'param' => 'stylen_catalog_level', 'operator' => '==', 'value' => 'leaf' ] ] ],
		'fields'   => [
			[ 'key' => 'field_product_desc', 'label' => 'Короткое описание', 'name' => 'product_desc', 'type' => 'textarea', 'rows' => 3, 'new_lines' => '' ],
			stylen_acf_text( 'field_product_price', 'Цена', 'product_price' ),
			[ 'key' => 'field_product_specs', 'label' => 'Характеристики', 'name' => 'product_specs', 'type' => 'repeater', 'layout' => 'table', 'button_label' => 'Добавить строку', 'sub_fields' => [
				stylen_acf_text( 'field_product_spec_label', 'Параметр', 'label' ),
				stylen_acf_text( 'field_product_spec_value', 'Значение', 'value' ),
			] ],
			[ 'key' => 'field_product_body', 'label' => 'Текст «Об услуге»', 'name' => 'product_body', 'type' => 'wysiwyg', 'tabs' => 'all', 'media_upload' => 0 ],
		],
	] );

	/* Portfolio subcategory content (term). Body = the term description field. */
	acf_add_local_field_group( [
		'key'      => 'group_portfolio_cat',
		'title'    => 'Подкатегория портфолио',
		'location' => [ [ [ 'param' => 'taxonomy', 'operator' => '==', 'value' => 'portfolio_cat' ] ] ],
		'fields'   => [
			stylen_acf_text( 'field_pf_cat_lead', 'Лид (под заголовком)', 'pf_cat_lead' ),
			stylen_acf_text( 'field_pf_cat_about_title', 'Заголовок блока «О направлении»', 'pf_cat_about_title' ),
		],
	] );
} );

/* ============================================================
   PHASE 3 — single-instance page copy
   ============================================================ */
add_action( 'acf/init', function () {

	/* Shared catalog labels (options page). */
	acf_add_local_field_group( [
		'key'      => 'group_stylen_catalog_labels',
		'title'    => 'Каталог — общие подписи',
		'location' => [ [ [ 'param' => 'options_page', 'operator' => '==', 'value' => 'stylen-content' ] ] ],
		'fields'   => [
			stylen_acf_tab( 'tab_cat_labels', 'Каталог: подписи' ),
			stylen_acf_text( 'field_cat_branch_kicker', 'Направление: надзаголовок', 'cat_branch_kicker' ),
			stylen_acf_text( 'field_cat_branch_cta_title', 'Направление: заголовок CTA', 'cat_branch_cta_title' ),
			[ 'key' => 'field_cat_branch_cta_text', 'label' => 'Направление: текст CTA', 'name' => 'cat_branch_cta_text', 'type' => 'textarea', 'rows' => 2, 'new_lines' => '' ],
			stylen_acf_text( 'field_cat_product_about_kicker', 'Товар: надзаголовок «Об услуге»', 'cat_product_about_kicker' ),
			stylen_acf_text( 'field_cat_product_related_kicker', 'Товар: надзаголовок «Смотрите также»', 'cat_product_related_kicker' ),
			stylen_acf_text( 'field_cat_product_cta_title', 'Товар: заголовок CTA', 'cat_product_cta_title' ),
			[ 'key' => 'field_cat_product_cta_text', 'label' => 'Товар: текст CTA (%s — название товара)', 'name' => 'cat_product_cta_text', 'type' => 'textarea', 'rows' => 2, 'new_lines' => '' ],
			stylen_acf_tab( 'tab_product_trust', 'Товар: преимущества' ),
			[ 'key' => 'field_product_trust', 'label' => 'Пункты (одинаковы для всех товаров)', 'name' => 'product_trust', 'type' => 'repeater', 'layout' => 'table', 'button_label' => 'Добавить пункт', 'sub_fields' => [
				stylen_acf_text( 'field_pt_icon', 'Иконка (ключ)', 'icon' ),
				stylen_acf_text( 'field_pt_title', 'Заголовок', 'title' ),
				stylen_acf_text( 'field_pt_text', 'Подпись', 'text' ),
			] ],
		],
	] );

	/* Catalog home (root page). */
	acf_add_local_field_group( [
		'key'      => 'group_catalog_home',
		'title'    => 'Каталог — главная страница каталога',
		'location' => [ [ [ 'param' => 'stylen_catalog_level', 'operator' => '==', 'value' => 'root' ] ] ],
		'fields'   => [
			stylen_acf_tab( 'tab_ch_hero', 'Первый экран' ),
			stylen_acf_text( 'field_ch_hero_kicker', 'Надзаголовок', 'ch_hero_kicker' ),
			stylen_acf_text( 'field_ch_hero_title', 'Заголовок (можно <span class="mark">)', 'ch_hero_title' ),
			[ 'key' => 'field_ch_hero_lead', 'label' => 'Описание', 'name' => 'ch_hero_lead', 'type' => 'textarea', 'rows' => 3, 'new_lines' => '' ],
			stylen_acf_tab( 'tab_ch_dir', 'Секция направлений' ),
			stylen_acf_text( 'field_ch_dir_kicker', 'Надзаголовок', 'ch_dir_kicker' ),
			stylen_acf_text( 'field_ch_dir_title', 'Заголовок', 'ch_dir_title' ),
			[ 'key' => 'field_ch_dir_subtitle', 'label' => 'Подзаголовок', 'name' => 'ch_dir_subtitle', 'type' => 'textarea', 'rows' => 2, 'new_lines' => '' ],
			stylen_acf_tab( 'tab_ch_why', 'Почему мы' ),
			stylen_acf_text( 'field_ch_why_kicker', 'Надзаголовок', 'ch_why_kicker' ),
			stylen_acf_text( 'field_ch_why_title', 'Заголовок', 'ch_why_title' ),
			[ 'key' => 'field_ch_why_items', 'label' => 'Пункты', 'name' => 'ch_why_items', 'type' => 'repeater', 'layout' => 'block', 'button_label' => 'Добавить', 'sub_fields' => [
				stylen_acf_text( 'field_ch_why_icon', 'Иконка', 'icon' ),
				stylen_acf_text( 'field_ch_why_title2', 'Заголовок', 'title' ),
				[ 'key' => 'field_ch_why_text', 'label' => 'Текст', 'name' => 'text', 'type' => 'textarea', 'rows' => 2, 'new_lines' => '' ],
			] ],
			stylen_acf_tab( 'tab_ch_cta', 'CTA' ),
			stylen_acf_text( 'field_ch_cta_title', 'Заголовок', 'ch_cta_title' ),
			[ 'key' => 'field_ch_cta_text', 'label' => 'Текст', 'name' => 'ch_cta_text', 'type' => 'textarea', 'rows' => 2, 'new_lines' => '' ],
		],
	] );
} );

add_action( 'acf/init', function () {

	/* Blog page. */
	acf_add_local_field_group( [
		'key'      => 'group_blog',
		'title'    => 'Страница «Блог»',
		'location' => [ [ [ 'param' => 'page_template', 'operator' => '==', 'value' => 'template-blog.php' ] ] ],
		'fields'   => [
			stylen_acf_tab( 'tab_bl_hero', 'Первый экран' ),
			stylen_acf_text( 'field_bl_hero_kicker', 'Надзаголовок', 'bl_hero_kicker' ),
			stylen_acf_text( 'field_bl_hero_title', 'Заголовок (можно <span class="mark">)', 'bl_hero_title' ),
			[ 'key' => 'field_bl_hero_lead', 'label' => 'Описание', 'name' => 'bl_hero_lead', 'type' => 'textarea', 'rows' => 3, 'new_lines' => '' ],
			stylen_acf_tab( 'tab_bl_cta', 'CTA внизу страницы' ),
			stylen_acf_text( 'field_bl_cta_title', 'Заголовок', 'bl_cta_title' ),
			[ 'key' => 'field_bl_cta_text', 'label' => 'Текст', 'name' => 'bl_cta_text', 'type' => 'textarea', 'rows' => 2, 'new_lines' => '' ],
			stylen_acf_text( 'field_bl_cta_button', 'Кнопка', 'bl_cta_button' ),
		],
	] );

	/* Contacts page. */
	acf_add_local_field_group( [
		'key'      => 'group_contacts',
		'title'    => 'Страница «Контакты»',
		'location' => [ [ [ 'param' => 'page_template', 'operator' => '==', 'value' => 'template-contacts.php' ] ] ],
		'fields'   => [
			stylen_acf_tab( 'tab_ct_hero', 'Первый экран' ),
			stylen_acf_text( 'field_ct_hero_kicker', 'Надзаголовок', 'ct_hero_kicker' ),
			stylen_acf_text( 'field_ct_hero_title', 'Заголовок (можно <span class="mark">)', 'ct_hero_title' ),
			[ 'key' => 'field_ct_hero_lead', 'label' => 'Описание', 'name' => 'ct_hero_lead', 'type' => 'textarea', 'rows' => 3, 'new_lines' => '' ],
			stylen_acf_text( 'field_ct_hero_cta1', 'Кнопка 1', 'ct_hero_cta1' ),
			stylen_acf_text( 'field_ct_hero_cta2', 'Кнопка 2', 'ct_hero_cta2' ),

			stylen_acf_tab( 'tab_ct_cards', 'Карточки (тел/почта/адрес/часы)' ),
			[ 'key' => 'field_ct_cards', 'label' => 'Карточки — по порядку: телефон, почта, адрес, часы', 'name' => 'ct_cards', 'type' => 'repeater', 'layout' => 'table', 'max' => 4, 'button_label' => 'Добавить', 'sub_fields' => [
				stylen_acf_text( 'field_ct_card_label', 'Подпись', 'label' ),
				stylen_acf_text( 'field_ct_card_note', 'Примечание', 'note' ),
			] ],

			stylen_acf_tab( 'tab_ct_dept', 'Отделы' ),
			stylen_acf_text( 'field_ct_dept_kicker', 'Надзаголовок', 'ct_dept_kicker' ),
			stylen_acf_text( 'field_ct_dept_title', 'Заголовок', 'ct_dept_title' ),
			[ 'key' => 'field_ct_departments', 'label' => 'Отделы', 'name' => 'ct_departments', 'type' => 'repeater', 'layout' => 'block', 'button_label' => 'Добавить отдел', 'sub_fields' => [
				stylen_acf_text( 'field_ct_dep_icon', 'Иконка', 'icon' ),
				stylen_acf_text( 'field_ct_dep_name', 'Название', 'dep_name' ),
				stylen_acf_text( 'field_ct_dep_role', 'Описание', 'dep_role' ),
				stylen_acf_text( 'field_ct_dep_contact', 'Контакт (текст)', 'dep_contact' ),
				stylen_acf_text( 'field_ct_dep_href', 'Контакт (ссылка)', 'dep_href' ),
			] ],

			stylen_acf_tab( 'tab_ct_managers', 'Менеджеры' ),
			stylen_acf_text( 'field_ct_mgr_kicker', 'Надзаголовок', 'ct_mgr_kicker' ),
			stylen_acf_text( 'field_ct_mgr_title', 'Заголовок', 'ct_mgr_title' ),
			[ 'key' => 'field_ct_managers', 'label' => 'Менеджеры', 'name' => 'ct_managers', 'type' => 'repeater', 'layout' => 'block', 'button_label' => 'Добавить менеджера', 'sub_fields' => [
				[ 'key' => 'field_ct_mgr_photo', 'label' => 'Фото', 'name' => 'm_photo', 'type' => 'image', 'return_format' => 'id', 'preview_size' => 'medium' ],
				stylen_acf_text( 'field_ct_mgr_name', 'ФИО', 'm_name' ),
				stylen_acf_text( 'field_ct_mgr_role', 'Должность', 'm_role' ),
				stylen_acf_text( 'field_ct_mgr_phone', 'Телефон', 'm_phone' ),
				stylen_acf_text( 'field_ct_mgr_email', 'E-mail (необязательно)', 'm_email' ),
			] ],

			stylen_acf_tab( 'tab_ct_req', 'Реквизиты' ),
			stylen_acf_text( 'field_ct_req_title', 'Заголовок', 'ct_req_title' ),
			[ 'key' => 'field_ct_requisites', 'label' => 'Реквизиты', 'name' => 'ct_requisites', 'type' => 'repeater', 'layout' => 'table', 'button_label' => 'Добавить', 'sub_fields' => [
				stylen_acf_text( 'field_ct_req_label', 'Поле', 'label' ),
				stylen_acf_text( 'field_ct_req_value', 'Значение', 'value' ),
			] ],

			stylen_acf_tab( 'tab_ct_order', 'Форма заявки' ),
			stylen_acf_text( 'field_ct_order_kicker', 'Надзаголовок', 'ct_order_kicker' ),
			stylen_acf_text( 'field_ct_order_title', 'Заголовок', 'ct_order_title' ),
			[ 'key' => 'field_ct_order_text', 'label' => 'Описание', 'name' => 'ct_order_text', 'type' => 'textarea', 'rows' => 3, 'new_lines' => '' ],
		],
	] );
} );

add_action( 'acf/init', function () {
	acf_add_local_field_group( [
		'key'      => 'group_about',
		'title'    => 'Страница «О компании»',
		'location' => [ [ [ 'param' => 'page_template', 'operator' => '==', 'value' => 'template-about.php' ] ] ],
		'fields'   => [
			stylen_acf_tab( 'tab_ab_hero', 'Первый экран' ),
			stylen_acf_text( 'field_ab_hero_kicker', 'Надзаголовок', 'ab_hero_kicker' ),
			stylen_acf_text( 'field_ab_hero_title', 'Заголовок (можно <span class="mark">)', 'ab_hero_title' ),
			[ 'key' => 'field_ab_hero_lead', 'label' => 'Описание', 'name' => 'ab_hero_lead', 'type' => 'textarea', 'rows' => 3, 'new_lines' => '' ],

			stylen_acf_tab( 'tab_ab_stats', 'Цифры' ),
			[ 'key' => 'field_ab_stats', 'label' => 'Показатели', 'name' => 'ab_stats', 'type' => 'repeater', 'layout' => 'table', 'button_label' => 'Добавить', 'sub_fields' => [
				stylen_acf_text( 'field_ab_stat_number', 'Число', 'number' ),
				stylen_acf_text( 'field_ab_stat_dsuffix', 'Суффикс в счётчике', 'dsuffix' ),
				stylen_acf_text( 'field_ab_stat_ssuffix', 'Доп. знак', 'ssuffix' ),
				stylen_acf_text( 'field_ab_stat_label', 'Подпись', 'label' ),
			] ],

			stylen_acf_tab( 'tab_ab_story', 'О нас' ),
			stylen_acf_text( 'field_ab_story_kicker', 'Надзаголовок', 'ab_story_kicker' ),
			stylen_acf_text( 'field_ab_story_title', 'Заголовок', 'ab_story_title' ),
			[ 'key' => 'field_ab_story_body', 'label' => 'Текст', 'name' => 'ab_story_body', 'type' => 'wysiwyg', 'tabs' => 'all', 'media_upload' => 0 ],
			stylen_acf_text( 'field_ab_story_cta', 'Кнопка', 'ab_story_cta_label' ),
			stylen_acf_text( 'field_ab_story_badge_label', 'Плашка: подпись', 'ab_story_badge_label' ),
			stylen_acf_text( 'field_ab_story_badge_value', 'Плашка: значение', 'ab_story_badge_value' ),

			stylen_acf_tab( 'tab_ab_timeline', 'История' ),
			stylen_acf_text( 'field_ab_tl_kicker', 'Надзаголовок', 'ab_timeline_kicker' ),
			stylen_acf_text( 'field_ab_tl_title', 'Заголовок (можно <span class="mark">)', 'ab_timeline_title' ),
			[ 'key' => 'field_ab_tl_subtitle', 'label' => 'Подзаголовок', 'name' => 'ab_timeline_subtitle', 'type' => 'textarea', 'rows' => 2, 'new_lines' => '' ],
			[ 'key' => 'field_ab_tl_items', 'label' => 'Этапы', 'name' => 'ab_timeline_items', 'type' => 'repeater', 'layout' => 'block', 'button_label' => 'Добавить этап', 'sub_fields' => [
				stylen_acf_text( 'field_ab_tl_year', 'Год', 'year' ),
				stylen_acf_text( 'field_ab_tl_ititle', 'Заголовок', 'title' ),
				[ 'key' => 'field_ab_tl_itext', 'label' => 'Текст', 'name' => 'text', 'type' => 'textarea', 'rows' => 2, 'new_lines' => '' ],
			] ],

			stylen_acf_tab( 'tab_ab_values', 'Принципы' ),
			stylen_acf_text( 'field_ab_val_kicker', 'Надзаголовок', 'ab_values_kicker' ),
			stylen_acf_text( 'field_ab_val_title', 'Заголовок', 'ab_values_title' ),
			[ 'key' => 'field_ab_val_items', 'label' => 'Принципы', 'name' => 'ab_values_items', 'type' => 'repeater', 'layout' => 'block', 'button_label' => 'Добавить', 'sub_fields' => [
				stylen_acf_text( 'field_ab_val_icon', 'Иконка', 'icon' ),
				stylen_acf_text( 'field_ab_val_ititle', 'Заголовок', 'title' ),
				[ 'key' => 'field_ab_val_itext', 'label' => 'Текст', 'name' => 'text', 'type' => 'textarea', 'rows' => 2, 'new_lines' => '' ],
			] ],

			stylen_acf_tab( 'tab_ab_team', 'Команда' ),
			stylen_acf_text( 'field_ab_team_kicker', 'Надзаголовок', 'ab_team_kicker' ),
			stylen_acf_text( 'field_ab_team_title', 'Заголовок', 'ab_team_title' ),
			[ 'key' => 'field_ab_team_subtitle', 'label' => 'Подзаголовок', 'name' => 'ab_team_subtitle', 'type' => 'textarea', 'rows' => 2, 'new_lines' => '' ],
			[ 'key' => 'field_ab_team_items', 'label' => 'Сотрудники', 'name' => 'ab_team_items', 'type' => 'repeater', 'layout' => 'table', 'button_label' => 'Добавить', 'sub_fields' => [
				stylen_acf_text( 'field_ab_team_initials', 'Инициалы', 'initials' ),
				stylen_acf_text( 'field_ab_team_name', 'Имя', 'tname' ),
				stylen_acf_text( 'field_ab_team_role', 'Должность', 'role' ),
			] ],

			stylen_acf_tab( 'tab_ab_cta', 'CTA' ),
			stylen_acf_text( 'field_ab_cta_title', 'Заголовок', 'ab_cta_title' ),
			[ 'key' => 'field_ab_cta_text', 'label' => 'Текст', 'name' => 'ab_cta_text', 'type' => 'textarea', 'rows' => 2, 'new_lines' => '' ],
		],
	] );
} );

add_action( 'acf/init', function () {
	/* Portfolio: "Featured work" flag (shown on the homepage works block). */
	acf_add_local_field_group( [
		'key'      => 'group_pf_featured',
		'title'    => 'Работа',
		'location' => [ [ [ 'param' => 'post_type', 'operator' => '==', 'value' => 'portfolio' ] ] ],
		'position' => 'side',
		'menu_order' => 0,
		'fields'   => [
			[ 'key' => 'field_pf_featured', 'label' => 'Избранная работа', 'name' => 'pf_featured', 'type' => 'true_false', 'ui' => 1, 'instructions' => 'Выводить на главной в блоке «Избранные работы».' ],
		],
	] );
} );

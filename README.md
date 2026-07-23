# Стиль-Н — тема WordPress

Кастомная тема `stylen` для сайта рекламно-производственной компании
**«Стиль-Н»** (Воронеж, [stylen.ru](https://stylen.ru)) — широкоформатная
и интерьерная печать, вывески, таблички, стенды, наклейки, фотофоны.

## Что в репозитории

Отслеживается **только тема** — `wp-content/themes/stylen`.
Ядро WordPress, сторонние плагины, загрузки (`uploads`) и `wp-config.php`
намеренно исключены (см. `.gitignore`): ядро и плагины ставятся отдельно,
а `wp-config.php` содержит доступы к БД.

```
wp-content/themes/stylen/
├── functions.php              # STYLEN_VER, enqueue, регистрация, фильтры
├── front-page.php             # главная (конфигуратор «Смета» + витрина работ)
├── catalog-home|branch|product.php   # 3 уровня каталога (иерархия страниц)
├── template-about|blog|contacts|portfolio.php
├── single.php / archive.php / single-portfolio.php / taxonomy-portfolio_cat.php
├── inc/
│   ├── acf.php                # ВСЕ поля ACF (acf_add_local_field_group)
│   ├── helpers.php            # stylen_contacts(), stylen_icon(), meta-хелперы
│   └── template-tags.php
├── acf-json/                  # ACF Local JSON (синхронизация полей)
└── assets/css/{main,concept,pages,catalog,home}.css + assets/js/main.js
```

## Зависимости

| Плагин | Роль |
|---|---|
| **Advanced Custom Fields PRO** | весь редактируемый контент (обязателен) |
| Contact Form 7 | формы заявки (id формы — в опции `stylen_order_form_id`) |
| Yoast SEO | мета-теги и хлебные крошки (`yoast_breadcrumb`) |
| cyr2lat | транслитерация слагов |
| EPS 301 Redirects, HTML5 Slash Fixer | редиректы, валидность разметки |

## Контент

Весь бизнес- и SEO-текст вынесен в админку — в файлах темы хардкода нет.
Правится в **«Контент сайта»** (страница опций ACF) и в полях конкретных
страниц / записей / терминов. В коде остаются только вёрстка, ключи иконок
`stylen_icon()` и несколько служебных подписей интерфейса.

Структура каталога — иерархия **страниц** (3 уровня) под корнем `/catalog/`;
конечные страницы и есть товары.

## Разработка

> ⚠️ После правки любого CSS/JS **обязательно поднимите `STYLEN_VER`** в
> `functions.php` — константа используется как `?ver=` для сброса кэша,
> иначе браузер отдаёт старые файлы.

Локально проект работает на OSPanel; тема кладётся в
`wp-content/themes/stylen` и активируется в админке.

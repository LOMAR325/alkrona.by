<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <?php wp_head(); ?>
    <!-- Yandex.Metrika counter -->
    <script type="text/javascript">
        (function(m, e, t, r, i, k, a) {
            m[i] = m[i] || function() {
                (m[i].a = m[i].a || []).push(arguments)
            };
            m[i].l = 1 * new Date();
            for (var j = 0; j < document.scripts.length; j++) {
                if (document.scripts[j].src === r) {
                    return;
                }
            }
            k = e.createElement(t), a = e.getElementsByTagName(t)[0], k.async = 1, k.src = r, a.parentNode.insertBefore(k, a)
        })(window, document, 'script', 'https://mc.yandex.ru/metrika/tag.js?id=107180496', 'ym');

        ym(107180496, 'init', {
            ssr: true,
            webvisor: true,
            clickmap: true,
            ecommerce: "dataLayer",
            referrer: document.referrer,
            url: location.href,
            accurateTrackBounce: true,
            trackLinks: true
        });
    </script>
    <noscript>
        <div><img src="https://mc.yandex.ru/watch/107180496" style="position:absolute; left:-9999px;" alt="" /></div>
    </noscript>
    <!-- /Yandex.Metrika counter -->
</head>

<body <?php body_class('page'); ?>>
    <?php wp_body_open(); ?>
    <?php $header_catalog_categories = alkrona_product_categories(); ?>
    <style>
        .site-header__inner {
            overflow: visible;
        }

        .site-header__nav {
            flex-wrap: nowrap;
        }

        .site-header__nav--left {
            overflow: visible;
        }

        .site-header__nav--right {
            justify-content: flex-end;
            gap: 24px !important;
        }

        .site-header__catalog {
            position: relative;
            display: inline-block;
        }

        .site-header__catalog-link {
            display: inline-flex;
            align-items: center;
        }

        .site-header__catalog-menu {
            position: absolute !important;
            top: calc(100% + 14px) !important;
            left: 0 !important;
            display: none !important;
            min-width: 280px;
            padding: 10px;
            border: 1px solid #E6D9C9;
            border-radius: 14px;
            background: #FEFAEE;
            box-shadow: 0 18px 36px rgba(34, 84, 61, 0.16);
            z-index: 100;
        }

        .site-header__catalog-menu::before {
            content: "";
            position: absolute;
            left: 0;
            right: 0;
            top: -14px;
            height: 14px;
        }

        .site-header__catalog:hover .site-header__catalog-menu,
        .site-header__catalog:focus-within .site-header__catalog-menu {
            display: block !important;
        }

        .site-header__catalog-item {
            display: block !important;
            padding: 10px 12px;
            border-radius: 10px;
            font-size: 16px;
            line-height: 1.35;
            white-space: nowrap;
        }

        .site-header__catalog-item + .site-header__catalog-item {
            margin-top: 4px;
        }

        .site-header__catalog-item:hover,
        .site-header__catalog-item:focus-visible {
            background: #F7EBDE;
            color: #2F7A52;
        }

        @media (max-width: 1170px) {
            .site-header__nav {
                flex-wrap: wrap;
            }

            .site-header__nav--left {
                gap: 24px !important;
            }

            .site-header__catalog-menu {
                min-width: 240px;
            }
        }

        @media (max-width: 900px) {
            .site-header__catalog-menu {
                display: none !important;
            }
        }
    </style>
    <header class="site-header">
        <div class="container site-header__inner">
            <nav class="site-header__nav site-header__nav--left">
                <a class="site-header__link" href="<?php echo esc_url(alkrona_section_url('contacts')); ?>">Контакты</a>
                <a class="site-header__link" href="<?php echo esc_url(alkrona_about_url()); ?>">О нас</a>
                <div class="site-header__catalog">
                    <a class="site-header__link site-header__catalog-link" href="<?php echo esc_url(alkrona_catalog_url()); ?>" aria-haspopup="true">Каталог</a>
                    <?php if ($header_catalog_categories !== []) : ?>
                        <div class="site-header__catalog-menu" aria-label="Категории каталога">
                            <?php foreach ($header_catalog_categories as $category) : ?>
                                <a class="site-header__catalog-item" href="<?php echo esc_url(alkrona_catalog_category_url((string) $category->slug)); ?>">
                                    <?php echo esc_html($category->name); ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </nav>

            <a class="site-header__logo" href="<?php echo esc_url(home_url('/')); ?>" aria-label="Алькрона">
                <?php
                $logo_rendered = false;
                if (has_custom_logo()) {
                    $logo_id  = (int) get_theme_mod('custom_logo');
                    $logo_src = wp_get_attachment_image_src($logo_id, 'full');
                    if (!empty($logo_src[0])) {
                        echo '<img src="' . esc_url($logo_src[0]) . '" alt="' . esc_attr__('Логотип Алькрона', 'alkrona') . '" />';
                        $logo_rendered = true;
                    }
                }
                if (!$logo_rendered) { ?>
                    <img src="<?php echo esc_url(alkrona_theme_image_url('logo', 'logo.png')); ?>" alt="<?php esc_attr_e('Логотип Алькрона', 'alkrona'); ?>" />
                <?php } ?>
            </a>

            <button class="site-header__toggle" type="button" aria-label="Открыть меню" aria-expanded="false"
                aria-controls="site-menu">
                <span class="site-header__toggle-line"></span>
                <span class="site-header__toggle-line"></span>
                <span class="site-header__toggle-line"></span>
            </button>

            <nav class="site-header__nav site-header__nav--right">
                <a class="site-header__link site-header__link-delivery" href="<?php echo esc_url(alkrona_delivery_url()); ?>">Оплата и доставка</a>
                <a class="btn btn--ghost site-header__phone" href="tel:+375293191844">+375(29) 319 18 44</a>
            </nav>
        </div>
        <div class="site-header__drawer" id="site-menu">
            <div class="container site-header__drawer-inner">
                <a class="site-header__drawer-link" href="<?php echo esc_url(alkrona_section_url('contacts')); ?>">Контакты</a>
                <a class="site-header__drawer-link" href="<?php echo esc_url(alkrona_about_url()); ?>">О нас</a>
                <a class="site-header__drawer-link" href="<?php echo esc_url(alkrona_catalog_url()); ?>">Каталог</a>
                <a class="site-header__drawer-link" href="<?php echo esc_url(alkrona_delivery_url()); ?>">Оплата и доставка</a>
                <a class="btn btn--ghost site-header__drawer-phone" href="tel:+375293191844">+375(29) 319 18 44</a>
            </div>
        </div>

    </header>

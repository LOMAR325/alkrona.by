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
    <header class="site-header">
        <div class="container site-header__inner">
            <nav class="site-header__nav site-header__nav--left">
                <a class="site-header__link" href="<?php echo esc_url(alkrona_section_url('contacts')); ?>">Контакты</a>
                <a class="site-header__link" href="<?php echo esc_url(alkrona_about_url()); ?>">О нас</a>
                <a class="site-header__link" href="<?php echo esc_url(alkrona_catalog_url()); ?>">Каталог</a>
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
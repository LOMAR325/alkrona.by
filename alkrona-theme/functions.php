<?php
/**
 * Alkrona theme bootstrap.
 */

if (!defined('ALKRONA_THEME_VERSION')) {
    $theme = wp_get_theme();
    define('ALKRONA_THEME_VERSION', $theme->get('Version'));
}

function alkrona_is_woocommerce_active(): bool
{
    return class_exists('WooCommerce');
}

function alkrona_product_post_type(): string
{
    return 'product';
}

function alkrona_product_category_taxonomy(): string
{
    return 'product_cat';
}

function alkrona_default_product_category_id(): int
{
    $default_id = (int) get_option('default_product_cat');
    return $default_id > 0 ? $default_id : 0;
}

function alkrona_theme_image_fields(): array
{
    return [
        'logo'               => 'Логотип (резервный)',
        'hero_cta'           => 'Главный экран',
        'audience_land_icon' => 'Аудитория: иконка ландшафт',
        'audience_land_img'  => 'Аудитория: фото ландшафт',
        'audience_clients_icon' => 'Аудитория: иконка клиенты',
        'audience_clients_img'  => 'Аудитория: фото клиенты',
        'audience_dev_icon'     => 'Аудитория: иконка девелоперы',
        'audience_dev_img'      => 'Аудитория: фото девелоперы',
        'audience_centers_icon' => 'Аудитория: иконка садовые центры',
        'audience_centers_img'  => 'Аудитория: фото садовые центры',
        'category_conifers'  => 'Категория: хвойные',
        'category_shrubs'    => 'Категория: лиственные',
        'product_placeholder'=> 'Заглушка товара',
    ];
}

function alkrona_theme_image_url(string $key, string $fallback = ''): string
{
    $setting = get_theme_mod('alkrona_img_' . $key);

    if (is_numeric($setting) && (int) $setting > 0) {
        $url = wp_get_attachment_image_url((int) $setting, 'full');
        if (is_string($url) && $url !== '') {
            return $url;
        }
    }

    if (is_string($setting) && $setting !== '') {
        return $setting;
    }

    if ($fallback !== '') {
        return get_template_directory_uri() . '/assets/images/' . ltrim($fallback, '/');
    }

    return '';
}

add_action('customize_register', function ($wp_customize): void {
    $wp_customize->add_section('alkrona_images', [
        'title'       => 'Alkrona: Изображения',
        'description' => 'Изображения темы из медиатеки WordPress.',
        'priority'    => 35,
    ]);

    foreach (alkrona_theme_image_fields() as $key => $label) {
        $setting_id = 'alkrona_img_' . $key;
        $wp_customize->add_setting($setting_id, [
            'type'              => 'theme_mod',
            'sanitize_callback' => 'absint',
            'default'           => 0,
        ]);

        $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, $setting_id, [
            'label'      => $label,
            'section'    => 'alkrona_images',
            'mime_type'  => 'image',
            'settings'   => $setting_id,
        ]));
    }
});

add_action('after_setup_theme', function () {
    load_theme_textdomain('alkrona');
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('woocommerce');
    add_theme_support('html5', ['search-form', 'comment-list', 'comment-form', 'gallery', 'caption', 'style', 'script']);
    add_theme_support('custom-logo', [
        'height'      => 80,
        'width'       => 200,
        'flex-height' => true,
        'flex-width'  => true,
    ]);

    register_nav_menus([
        'header-left'  => __('Header Left', 'alkrona'),
        'header-right' => __('Header Right', 'alkrona'),
        'footer'       => __('Footer', 'alkrona'),
    ]);
});

add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style('alkrona-fonts', 'https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap', [], null);

    $css_file = get_template_directory() . '/css/style.css';
    $css_ver  = file_exists($css_file) ? filemtime($css_file) : ALKRONA_THEME_VERSION;
    wp_enqueue_style('alkrona-style', get_template_directory_uri() . '/css/style.css', ['alkrona-fonts'], $css_ver);

    $js_file = get_template_directory() . '/js/sctipt.js';
    $js_ver  = file_exists($js_file) ? filemtime($js_file) : ALKRONA_THEME_VERSION;
    wp_enqueue_script('alkrona-script', get_template_directory_uri() . '/js/sctipt.js', [], $js_ver, true);
});

add_filter('woocommerce_enqueue_styles', function () {
    return [];
});

add_filter('woocommerce_product_tabs', function () {
    return [];
}, 99);

add_filter('woocommerce_enable_reviews', '__return_false');

add_filter('comments_open', function ($open, $post_id) {
    if (get_post_type((int) $post_id) === 'product') {
        return false;
    }
    return $open;
}, 10, 2);

add_action('init', function () {
    add_rewrite_rule('^catalog/([^/]+)/?$', 'index.php?pagename=catalog&alkrona_category=$matches[1]', 'top');
});

add_filter('query_vars', function (array $vars): array {
    $vars[] = 'alkrona_category';
    return $vars;
});

function alkrona_ensure_catalog_page(): void
{
    $existing = get_page_by_path('catalog');
    if ($existing instanceof WP_Post) {
        return;
    }

    wp_insert_post([
        'post_type'    => 'page',
        'post_status'  => 'publish',
        'post_title'   => 'Каталог',
        'post_name'    => 'catalog',
        'post_content' => '',
    ]);
}

add_action('after_switch_theme', function () {
    alkrona_ensure_catalog_page();
    flush_rewrite_rules();
});

add_action('init', function () {
    $rewrite_version = ALKRONA_THEME_VERSION . '-catalog-v4';
    $stored_version  = get_option('alkrona_rewrite_version');

    if ($stored_version !== $rewrite_version) {
        alkrona_ensure_catalog_page();
        flush_rewrite_rules();
        update_option('alkrona_rewrite_version', $rewrite_version);
    }
}, 20);

add_action('pre_get_posts', function ($query) {
    if (is_admin() || !$query->is_main_query()) {
        return;
    }

    if ($query->is_post_type_archive(alkrona_product_post_type()) || $query->is_tax(alkrona_product_category_taxonomy())) {
        $query->set('posts_per_page', -1);
        $query->set('orderby', 'date');
        $query->set('order', 'DESC');
    }
});

add_filter('body_class', function ($classes) {
    $is_catalog_tax = is_tax(alkrona_product_category_taxonomy());
    if (function_exists('is_product_category') && is_product_category()) {
        $is_catalog_tax = true;
    }

    if (is_post_type_archive(alkrona_product_post_type()) || $is_catalog_tax || is_page('catalog')) {
        $classes[] = 'page--catalog';
    }
    return $classes;
});

function alkrona_catalog_url(): string
{
    $catalog_page = get_page_by_path('catalog');
    if ($catalog_page instanceof WP_Post) {
        $url = get_permalink($catalog_page->ID);
        if ($url) {
            return $url;
        }
    }

    if (alkrona_is_woocommerce_active() && function_exists('wc_get_page_permalink')) {
        $shop_url = wc_get_page_permalink('shop');
        if (is_string($shop_url) && $shop_url !== '') {
            return $shop_url;
        }
    }

    $archive_url = get_post_type_archive_link(alkrona_product_post_type());
    return $archive_url ? $archive_url : home_url('/catalog/');
}

function alkrona_has_pretty_permalinks(): bool
{
    return (string) get_option('permalink_structure') !== '';
}

function alkrona_catalog_category_url(string $slug): string
{
    $slug = sanitize_title(rawurldecode($slug));
    if ($slug === '') {
        return alkrona_catalog_url();
    }

    if (!alkrona_has_pretty_permalinks()) {
        return add_query_arg('category', $slug, alkrona_catalog_url());
    }

    return home_url(user_trailingslashit('catalog/' . $slug));
}

function alkrona_section_url(string $section_id): string
{
    $section_id = ltrim($section_id, '#');
    if (is_front_page()) {
        return '#' . $section_id;
    }
    return home_url('/#' . $section_id);
}

function alkrona_str_contains_ci(string $haystack, string $needle): bool
{
    if ($needle === '') {
        return false;
    }

    if (function_exists('mb_stripos')) {
        return mb_stripos($haystack, $needle) !== false;
    }

    return stripos($haystack, $needle) !== false;
}

function alkrona_catalog_category_url_by_keyword(string $keyword): string
{
    $exclude = [];
    $default_category_id = alkrona_default_product_category_id();
    if ($default_category_id > 0) {
        $exclude[] = $default_category_id;
    }

    $terms = get_terms([
        'taxonomy'   => alkrona_product_category_taxonomy(),
        'hide_empty' => false,
        'exclude'    => $exclude,
    ]);

    if (is_wp_error($terms)) {
        return alkrona_catalog_url();
    }

    foreach ($terms as $term) {
        if (alkrona_str_contains_ci($term->name, $keyword)) {
            return alkrona_catalog_category_url((string) $term->slug);
        }
    }

    return alkrona_catalog_url();
}

function alkrona_product_meta(int $post_id, string $meta_key, string $default = ''): string
{
    $value = get_post_meta($post_id, $meta_key, true);
    if (!is_string($value)) {
        return $default;
    }

    $value = trim($value);
    return $value === '' ? $default : $value;
}

function alkrona_price_to_float(string $raw_price): ?float
{
    $clean = preg_replace('/[^0-9,\.]/', '', trim($raw_price));
    if (!is_string($clean) || $clean === '') {
        return null;
    }

    $normalized = str_replace(',', '.', $clean);
    if (!is_numeric($normalized)) {
        return null;
    }

    return (float) $normalized;
}

function alkrona_sanitize_price_input(string $value): string
{
    $clean = preg_replace('/[^0-9,\.]/', '', trim($value));
    if (!is_string($clean)) {
        return '';
    }
    return trim($clean);
}

function alkrona_variant_has_data(array $variant): bool
{
    foreach (['pot', 'height', 'price', 'availability', 'sku'] as $key) {
        if (!empty($variant[$key])) {
            return true;
        }
    }

    return false;
}

function alkrona_variant_signature(array $variant): string
{
    $parts = [];
    foreach (['pot', 'height', 'price', 'availability', 'sku'] as $key) {
        $value = isset($variant[$key]) ? (string) $variant[$key] : '';
        if (function_exists('mb_strtolower')) {
            $value = mb_strtolower($value);
        } else {
            $value = strtolower($value);
        }
        $parts[] = trim($value);
    }
    return implode('|', $parts);
}

function alkrona_normalize_variant(array $variant): array
{
    $normalized = [
        'pot'          => '',
        'height'       => '',
        'price'        => '',
        'availability' => '',
        'sku'          => '',
    ];

    foreach ($normalized as $key => $default) {
        if (!array_key_exists($key, $variant)) {
            continue;
        }

        $value = is_string($variant[$key]) ? $variant[$key] : '';
        $value = sanitize_text_field($value);
        if ($key === 'price') {
            $value = alkrona_sanitize_price_input($value);
        }
        $normalized[$key] = trim($value);
    }

    return $normalized;
}

function alkrona_first_non_empty_variant_value(array $variants, string $key): string
{
    foreach ($variants as $variant) {
        if (!is_array($variant)) {
            continue;
        }

        $value = isset($variant[$key]) ? trim((string) $variant[$key]) : '';
        if ($value !== '') {
            return $value;
        }
    }

    return '';
}

function alkrona_woo_detect_variant_field(string $attribute_key, string $attribute_label): string
{
    $label = trim($attribute_label . ' ' . $attribute_key);
    if (
        alkrona_str_contains_ci($label, 'горш')
        || alkrona_str_contains_ci($label, 'контей')
        || alkrona_str_contains_ci($label, 'pot')
        || alkrona_str_contains_ci($label, 'container')
    ) {
        return 'pot';
    }

    if (
        alkrona_str_contains_ci($label, 'выс')
        || alkrona_str_contains_ci($label, 'height')
    ) {
        return 'height';
    }

    return '';
}

function alkrona_woo_attribute_display_value(string $taxonomy_or_key, string $raw_value): string
{
    $raw_value = trim($raw_value);
    if ($raw_value === '') {
        return '';
    }

    $taxonomy_or_key = str_replace('attribute_', '', $taxonomy_or_key);
    if (taxonomy_exists($taxonomy_or_key)) {
        $term = get_term_by('slug', $raw_value, $taxonomy_or_key);
        if ($term instanceof WP_Term) {
            return (string) $term->name;
        }
    }

    return $raw_value;
}

function alkrona_woo_collect_variant_attributes(array $attributes): array
{
    $mapped = [
        'pot'    => '',
        'height' => '',
        'extra'  => '',
    ];
    $extras = [];

    foreach ($attributes as $attribute_key => $attribute_value) {
        $taxonomy = str_replace('attribute_', '', (string) $attribute_key);
        $label = function_exists('wc_attribute_label') ? wc_attribute_label($taxonomy) : $taxonomy;
        $display = alkrona_woo_attribute_display_value($taxonomy, (string) $attribute_value);
        if ($display === '') {
            continue;
        }

        $field = alkrona_woo_detect_variant_field($taxonomy, (string) $label);
        if ($field !== '' && $mapped[$field] === '') {
            $mapped[$field] = $display;
            continue;
        }

        $extras[] = $display;
    }

    if ($extras) {
        $mapped['extra'] = implode(', ', $extras);
    }

    if ($mapped['pot'] === '' && $mapped['extra'] !== '') {
        $mapped['pot'] = $mapped['extra'];
    }

    return $mapped;
}

function alkrona_woo_product_variants(int $post_id): array
{
    if (!alkrona_is_woocommerce_active() || !function_exists('wc_get_product')) {
        return [];
    }

    $product = wc_get_product($post_id);
    if (!$product instanceof WC_Product) {
        return [];
    }

    $rows = [];
    if ($product->is_type('variable')) {
        foreach ($product->get_children() as $variation_id) {
            $variation = wc_get_product((int) $variation_id);
            if (!$variation instanceof WC_Product_Variation) {
                continue;
            }

            $mapped_attrs = alkrona_woo_collect_variant_attributes($variation->get_attributes());
            $price_raw = (string) $variation->get_regular_price();
            if ($price_raw === '') {
                $price_raw = (string) $variation->get_price();
            }

            $availability = (string) $variation->get_meta('_alkrona_availability');
            if ($availability === '') {
                $availability = $variation->is_in_stock() ? 'в наличии' : 'под заказ';
            }

            $row = alkrona_normalize_variant([
                'pot'          => (string) $mapped_attrs['pot'],
                'height'       => (string) $mapped_attrs['height'],
                'price'        => $price_raw,
                'availability' => $availability,
                'sku'          => (string) $variation->get_sku(),
            ]);

            if (alkrona_variant_has_data($row)) {
                $rows[] = $row;
            }
        }
    }

    if ($rows) {
        return $rows;
    }

    $simple_attrs = [];
    foreach ($product->get_attributes() as $attribute) {
        if (!($attribute instanceof WC_Product_Attribute)) {
            continue;
        }

        $name = $attribute->get_name();
        $options = $attribute->get_options();
        if (!$options) {
            continue;
        }

        $value = '';
        if ($attribute->is_taxonomy()) {
            $term = get_term_by('id', (int) $options[0], $name);
            if ($term instanceof WP_Term) {
                $value = (string) $term->name;
            }
        } else {
            $value = trim((string) $options[0]);
        }

        if ($value !== '') {
            $simple_attrs['attribute_' . $name] = $value;
        }
    }

    $mapped_attrs = alkrona_woo_collect_variant_attributes($simple_attrs);
    $availability = (string) $product->get_meta('_alkrona_availability');
    if ($availability === '') {
        $availability = $product->is_in_stock() ? 'в наличии' : 'под заказ';
    }

    $single_row = alkrona_normalize_variant([
        'pot'          => (string) $mapped_attrs['pot'],
        'height'       => (string) $mapped_attrs['height'],
        'price'        => (string) ($product->get_regular_price() !== '' ? $product->get_regular_price() : $product->get_price()),
        'availability' => $availability,
        'sku'          => (string) $product->get_sku(),
    ]);

    if (alkrona_variant_has_data($single_row)) {
        return [$single_row];
    }

    return [];
}

function alkrona_acf_variants_group_key(): string
{
    return 'group_699b7faca1e6b';
}

function alkrona_acf_value_to_string($value): string
{
    if (is_array($value)) {
        $flat = [];
        foreach ($value as $item) {
            if (is_scalar($item)) {
                $flat[] = trim((string) $item);
            }
        }
        return implode(',', array_filter($flat, static function ($item): bool {
            return $item !== '';
        }));
    }

    if (is_scalar($value)) {
        return trim((string) $value);
    }

    return '';
}

function alkrona_split_csv_values(string $value): array
{
    $value = trim($value);
    if ($value === '') {
        return [];
    }

    $parts = preg_split('/\s*[,;\r\n]+\s*/u', $value);
    if (!is_array($parts)) {
        return [];
    }

    return array_values(array_filter(array_map('trim', $parts), static function ($item): bool {
        return $item !== '';
    }));
}

function alkrona_value_from_list(array $values, int $index): string
{
    if (!$values) {
        return '';
    }

    if (array_key_exists($index, $values)) {
        return trim((string) $values[$index]);
    }

    if (count($values) === 1) {
        return trim((string) $values[0]);
    }

    return '';
}

function alkrona_acf_variants_field_map(): array
{
    $map = [
        'price'        => '',
        'height'       => '',
        'pot'          => '',
        'availability' => '',
    ];

    if (!function_exists('acf_get_fields')) {
        return $map;
    }

    $fields = acf_get_fields(alkrona_acf_variants_group_key());
    if (!is_array($fields)) {
        return $map;
    }

    foreach ($fields as $field) {
        if (!is_array($field)) {
            continue;
        }

        $name  = trim((string) ($field['name'] ?? ''));
        $label = trim((string) ($field['label'] ?? ''));
        if ($name === '') {
            continue;
        }

        $haystack = $label . ' ' . $name;

        if ($map['price'] === '' && (alkrona_str_contains_ci($haystack, 'цен') || alkrona_str_contains_ci($haystack, 'price') || alkrona_str_contains_ci($haystack, 'cena'))) {
            $map['price'] = $name;
            continue;
        }

        if ($map['height'] === '' && (alkrona_str_contains_ci($haystack, 'выс') || alkrona_str_contains_ci($haystack, 'height') || alkrona_str_contains_ci($haystack, 'vys'))) {
            $map['height'] = $name;
            continue;
        }

        if ($map['pot'] === '' && (alkrona_str_contains_ci($haystack, 'горш') || alkrona_str_contains_ci($haystack, 'контейн') || alkrona_str_contains_ci($haystack, 'pot') || alkrona_str_contains_ci($haystack, 'container'))) {
            $map['pot'] = $name;
            continue;
        }

        if ($map['availability'] === '' && (alkrona_str_contains_ci($haystack, 'налич') || alkrona_str_contains_ci($haystack, 'availability') || alkrona_str_contains_ci($haystack, 'stock'))) {
            $map['availability'] = $name;
            continue;
        }
    }

    return $map;
}

function alkrona_acf_variants_from_post(int $post_id): array
{
    if (!function_exists('get_field')) {
        return [];
    }

    $map = alkrona_acf_variants_field_map();
    if ($map['price'] === '' && $map['height'] === '' && $map['pot'] === '' && $map['availability'] === '') {
        return [];
    }

    $raw_price = $map['price'] !== '' ? alkrona_acf_value_to_string(get_field($map['price'], $post_id)) : '';
    $raw_height = $map['height'] !== '' ? alkrona_acf_value_to_string(get_field($map['height'], $post_id)) : '';
    $raw_pot = $map['pot'] !== '' ? alkrona_acf_value_to_string(get_field($map['pot'], $post_id)) : '';
    $raw_availability = $map['availability'] !== '' ? alkrona_acf_value_to_string(get_field($map['availability'], $post_id)) : '';

    $prices = alkrona_split_csv_values($raw_price);
    $heights = alkrona_split_csv_values($raw_height);
    $pots = alkrona_split_csv_values($raw_pot);
    $availability = alkrona_split_csv_values($raw_availability);

    $rows_count = max(count($prices), count($heights), count($pots), count($availability));
    if ($rows_count < 1) {
        return [];
    }

    $rows = [];
    $seen = [];
    for ($index = 0; $index < $rows_count; $index++) {
        $row = alkrona_normalize_variant([
            'pot'          => alkrona_value_from_list($pots, $index),
            'height'       => alkrona_value_from_list($heights, $index),
            'price'        => alkrona_value_from_list($prices, $index),
            'availability' => alkrona_value_from_list($availability, $index),
            'sku'          => '',
        ]);

        if (!alkrona_variant_has_data($row)) {
            continue;
        }

        $signature = alkrona_variant_signature($row);
        if (isset($seen[$signature])) {
            continue;
        }

        $seen[$signature] = true;
        $rows[] = $row;
    }

    return $rows;
}

function alkrona_product_manual_variants(int $post_id): array
{
    $raw = get_post_meta($post_id, '_alkrona_manual_variants', true);
    if (!is_array($raw)) {
        return [];
    }

    $rows = [];
    $seen = [];
    foreach ($raw as $raw_variant) {
        if (!is_array($raw_variant)) {
            continue;
        }

        $variant = alkrona_normalize_variant($raw_variant);
        if (!alkrona_variant_has_data($variant)) {
            continue;
        }

        $signature = alkrona_variant_signature($variant);
        if (isset($seen[$signature])) {
            continue;
        }

        $seen[$signature] = true;
        $rows[] = $variant;
    }

    return $rows;
}

function alkrona_merge_variants(array $base_rows, array $extra_rows): array
{
    $merged = [];
    $seen = [];
    foreach ([$base_rows, $extra_rows] as $set) {
        foreach ($set as $row) {
            if (!is_array($row)) {
                continue;
            }

            $variant = alkrona_normalize_variant($row);
            if (!alkrona_variant_has_data($variant)) {
                continue;
            }

            $signature = alkrona_variant_signature($variant);
            if (isset($seen[$signature])) {
                continue;
            }

            $seen[$signature] = true;
            $merged[] = $variant;
        }
    }

    return $merged;
}

function alkrona_product_variants(int $post_id): array
{
    $acf_rows = alkrona_acf_variants_from_post($post_id);
    if ($acf_rows) {
        return $acf_rows;
    }

    $woo_rows    = alkrona_woo_product_variants($post_id);
    $manual_rows = alkrona_product_manual_variants($post_id);

    return alkrona_merge_variants($woo_rows, $manual_rows);
}

function alkrona_format_price(string $raw_price): string
{
    $raw_price = trim($raw_price);
    if ($raw_price === '') {
        return '—';
    }

    $numeric = alkrona_price_to_float($raw_price);
    if ($numeric !== null) {
        return number_format($numeric, 2, '.', '') . ' руб';
    }

    return $raw_price;
}

function alkrona_product_price_prefix(int $post_id): string
{
    $prefix = alkrona_product_meta($post_id, '_alkrona_price_prefix');
    if ($prefix !== '') {
        return rtrim($prefix) . ' ';
    }

    $variants = alkrona_product_variants($post_id);
    if (count($variants) > 1) {
        return 'от ';
    }

    return '';
}

function alkrona_product_price_text(int $post_id): string
{
    $variants = alkrona_product_variants($post_id);
    if ($variants) {
        $numeric_prices = [];
        foreach ($variants as $variant) {
            $float_price = alkrona_price_to_float((string) ($variant['price'] ?? ''));
            if ($float_price !== null) {
                $numeric_prices[] = $float_price;
            }
        }

        if ($numeric_prices) {
            return alkrona_format_price((string) min($numeric_prices));
        }

        $first_price = trim((string) ($variants[0]['price'] ?? ''));
        if ($first_price !== '') {
            return alkrona_format_price($first_price);
        }
    }

    return alkrona_format_price(alkrona_product_meta($post_id, '_alkrona_price'));
}

function alkrona_product_latin_name(int $post_id): string
{
    $saved = alkrona_product_meta($post_id, '_alkrona_latin_name');
    if ($saved !== '') {
        return $saved;
    }

    $title = get_the_title($post_id);
    if (is_string($title) && preg_match('/\(([^)]+)\)/u', $title, $matches) === 1 && !empty($matches[1])) {
        return trim((string) $matches[1]);
    }

    return '';
}

function alkrona_product_container(int $post_id): string
{
    $variants = alkrona_product_variants($post_id);
    $pot = alkrona_first_non_empty_variant_value($variants, 'pot');
    if ($pot !== '') {
        return $pot;
    }
    return alkrona_product_meta($post_id, '_alkrona_container');
}

function alkrona_featured_product_ids(int $limit = 4): array
{
    if ($limit < 1) {
        return [];
    }

    if (!alkrona_is_woocommerce_active() || !function_exists('wc_get_products')) {
        return [];
    }

    $featured_ids = wc_get_products([
        'status'   => 'publish',
        'limit'    => $limit,
        'featured' => true,
        'return'   => 'ids',
        'orderby'        => 'date',
        'order'          => 'DESC',
    ]);

    if (!is_array($featured_ids)) {
        $featured_ids = [];
    }

    if (count($featured_ids) < $limit) {
        $extra_ids = wc_get_products([
            'status'  => 'publish',
            'limit'   => $limit - count($featured_ids),
            'return'  => 'ids',
            'exclude' => $featured_ids,
            'orderby'        => 'date',
            'order'          => 'DESC',
        ]);
        if (is_array($extra_ids)) {
            $featured_ids = array_merge($featured_ids, $extra_ids);
        }
    }

    return array_map('intval', $featured_ids);
}

if (!isset($content_width)) {
    $content_width = 1200;
}


// Подключаем ajax для авторизованных и неавторизованных пользователей
add_action('wp_ajax_send_contact_form', 'send_contact_form');
add_action('wp_ajax_nopriv_send_contact_form', 'send_contact_form');

function send_contact_form() {
    if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
        wp_send_json_error('Некорректный тип запроса.', 405);
    }

    // Проверка nonce (защита)
    check_ajax_referer('contact_form_nonce', 'security');

    $name         = isset($_POST['name']) ? sanitize_text_field(wp_unslash($_POST['name'])) : '';
    $phone        = isset($_POST['phone']) ? sanitize_text_field(wp_unslash($_POST['phone'])) : '';
    $email        = isset($_POST['email']) ? sanitize_email(wp_unslash($_POST['email'])) : '';
    $product_name = isset($_POST['product_name']) ? sanitize_text_field(wp_unslash($_POST['product_name'])) : '';
    $consent      = !empty($_POST['consent']);

    if ($name === '' || $phone === '') {
        wp_send_json_error('Заполните обязательные поля: имя и телефон.');
    }

    if (!$consent) {
        wp_send_json_error('Подтвердите согласие на обработку персональных данных.');
    }

    if ($email !== '' && !is_email($email)) {
        wp_send_json_error('Укажите корректный e-mail или оставьте поле пустым.');
    }

    // Приоритет: отдельная опция для формы, затем стандартный e-mail администратора.
    $to = get_option('alkrona_contact_form_recipient');
    if (!is_string($to) || !is_email($to)) {
        $to = get_option('admin_email');
    }

    if (!is_string($to) || !is_email($to)) {
        wp_send_json_error('Не настроен e-mail получателя заявок.');
    }

    $subject = 'Новая заявка с сайта';
    $message_rows = [
        '<p style="margin:0 0 10px;"><strong>Имя:</strong> ' . esc_html($name) . '</p>',
        '<p style="margin:0 0 10px;"><strong>Телефон:</strong> ' . esc_html($phone) . '</p>',
        '<p style="margin:0 0 10px;"><strong>Email:</strong> ' . esc_html($email !== '' ? $email : 'не указан') . '</p>',
    ];

    if ($product_name !== '') {
        $message_rows[] = '<p style="margin:0 0 10px;"><strong>Товар:</strong> '
            . '<span style="font-size:20px;font-weight:700;line-height:1.3;color:#22543D;">'
            . esc_html($product_name)
            . '</span></p>';
    }

    $message_rows[] = '<p style="margin:0;"><strong>Дата:</strong> ' . esc_html(wp_date('Y-m-d H:i:s')) . '</p>';

    $message = '<div style="font-family:Arial,Helvetica,sans-serif;font-size:15px;line-height:1.45;color:#111;">'
        . implode('', $message_rows)
        . '</div>';

    $headers = ['Content-Type: text/html; charset=UTF-8'];

    if ($email !== '') {
        $headers[] = 'Reply-To: ' . $name . ' <' . $email . '>';
    }

    if (wp_mail($to, $subject, $message, $headers)) {
        wp_send_json_success('Сообщение успешно отправлено!');
    }

    wp_send_json_error('Ошибка отправки. Попробуйте позже.');
}

function theme_scripts() {
    $contact_js_file = get_template_directory() . '/js/contact-form.js';
    $contact_js_ver  = file_exists($contact_js_file) ? filemtime($contact_js_file) : ALKRONA_THEME_VERSION;

    wp_enqueue_script('contact-form-js', get_template_directory_uri() . '/js/contact-form.js', ['alkrona-script'], $contact_js_ver, true);

    wp_localize_script('contact-form-js', 'contactForm', [
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('contact_form_nonce'),
    ]);
}
add_action('wp_enqueue_scripts', 'theme_scripts');

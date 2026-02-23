<?php
/**
 * Redirect WooCommerce product categories to canonical /catalog/<slug>/ URL.
 */

$term = get_queried_object();
if ($term instanceof WP_Term) {
    $default_category_id = alkrona_default_product_category_id();
    if ($default_category_id > 0 && (int) $term->term_id === $default_category_id) {
        wp_safe_redirect(alkrona_catalog_url(), 301);
        exit;
    }

    wp_safe_redirect(alkrona_catalog_category_url((string) $term->slug), 301);
    exit;
}

require __DIR__ . '/page-catalog.php';

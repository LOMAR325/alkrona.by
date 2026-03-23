<?php
/**
 * Catalog page fallback.
 *
 * If a page with slug "catalog" exists, WordPress will use this template.
 */

get_header();

$products_post_type = alkrona_product_post_type();
$products_taxonomy  = alkrona_product_category_taxonomy();
$default_category_id = alkrona_default_product_category_id();

$selected_term = null;
$category_arg  = '';
$category_from_rewrite = get_query_var('alkrona_category');
if (is_string($category_from_rewrite) && $category_from_rewrite !== '') {
    $category_arg = sanitize_title(rawurldecode($category_from_rewrite));
} elseif (isset($_GET['category'])) {
    $category_arg = sanitize_title(rawurldecode((string) wp_unslash($_GET['category'])));
}

if ($category_arg !== '') {
    $legacy = [
        'conifers' => 'хвой',
        'shrubs'   => 'листвен',
    ];

    if (isset($legacy[$category_arg])) {
        $terms = get_terms([
            'taxonomy'   => $products_taxonomy,
            'hide_empty' => false,
        ]);

        if (!is_wp_error($terms)) {
            foreach ($terms as $term) {
                if (alkrona_str_contains_ci($term->name, $legacy[$category_arg])) {
                    $selected_term = $term;
                    break;
                }
            }
        }
    } else {
        $term = get_term_by('slug', $category_arg, $products_taxonomy);
        if ($term instanceof WP_Term) {
            $selected_term = $term;
        }
    }
}

if ($selected_term instanceof WP_Term && $default_category_id > 0 && (int) $selected_term->term_id === $default_category_id) {
    $selected_term = null;
}

$query_args = [
    'post_type'      => $products_post_type,
    'post_status'    => 'publish',
    'posts_per_page' => -1,
    'orderby'        => 'date',
    'order'          => 'DESC',
];

if ($selected_term instanceof WP_Term) {
    $query_args['tax_query'] = [[
        'taxonomy' => $products_taxonomy,
        'field'    => 'term_id',
        'terms'    => $selected_term->term_id,
    ]];
}

$catalog_query = new WP_Query($query_args);

$page_title = ($selected_term instanceof WP_Term) ? $selected_term->name : 'Каталог растений';
$subtitle   = ($selected_term instanceof WP_Term && $selected_term->description !== '')
    ? wp_strip_all_tags($selected_term->description)
    : 'Надежные хвойные и декоративные растения для вашего сада.';

$product_cats = get_terms([
    'taxonomy'   => $products_taxonomy,
    'hide_empty' => false,
    'exclude'    => $default_category_id > 0 ? [$default_category_id] : [],
]);
?>

<main>
    <section class="catalog-hero container" id="catalog">
        <nav class="breadcrumbs" aria-label="Хлебные крошки">
            <a href="<?php echo esc_url(home_url('/')); ?>">Главная</a>
            <span aria-hidden="true">/</span>
            <span><?php echo esc_html($page_title); ?></span>
        </nav>

        <h1 class="catalog-hero__title"><?php echo esc_html($page_title); ?></h1>
        <p class="catalog-hero__subtitle"><?php echo esc_html($subtitle); ?></p>
        <div class="catalog-hero__meta" aria-live="polite">
            <span><?php echo esc_html((string) $catalog_query->found_posts); ?> позиций</span>
        </div>

        <div class="catalog-hero__filters" id="catalogFilters" aria-label="Категории каталога">
            <a class="catalog-chip <?php echo $selected_term ? '' : 'catalog-chip--active'; ?>" href="<?php echo esc_url(alkrona_catalog_url()); ?>">Все</a>
            <?php if (!is_wp_error($product_cats)) : ?>
                <?php foreach ($product_cats as $cat) : ?>
                    <?php $cat_link = alkrona_catalog_category_url((string) $cat->slug); ?>
                    <a class="catalog-chip <?php echo (($selected_term instanceof WP_Term) && ((int) $cat->term_id === (int) $selected_term->term_id)) ? 'catalog-chip--active' : ''; ?>" href="<?php echo esc_url($cat_link); ?>">
                        <?php echo esc_html($cat->name); ?>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>

    <section class="catalog-grid-section">
        <div class="container">
            <div class="catalog-grid" aria-live="polite">
                <?php if ($catalog_query->have_posts()) : ?>
                    <?php while ($catalog_query->have_posts()) : $catalog_query->the_post(); ?>
                        <?php
                        $product_id   = get_the_ID();
                        $product_name = get_the_title();
                        $price_text   = alkrona_product_price_text($product_id);
                        $price_prefix = alkrona_product_price_prefix($product_id);
                        $container    = alkrona_product_container($product_id);
                        ?>
                        <article class="product-card catalog-card">
                            <div class="product-card__img-wrap">
                                <?php
                                if (has_post_thumbnail($product_id)) {
                                    echo wp_get_attachment_image(
                                        get_post_thumbnail_id($product_id),
                                        'medium',
                                        false,
                                        ['class' => 'product-card__img', 'loading' => 'lazy']
                                    );
                                } else {
                                    echo '<img class="product-card__img" src="' .
                                        esc_url(alkrona_theme_image_url('product_placeholder', 'birucha.png')) .
                                        '" alt="' . esc_attr($product_name) . '" loading="lazy" />';
                                }
                                ?>
                            </div>
                            <h4 class="product-card__title"><?php echo esc_html($product_name); ?></h4>
                            <p class="product-card__price"><span class="product-card__price-from"><?php echo esc_html($price_prefix); ?></span><span class="product-card__price-value"><?php echo esc_html($price_text); ?></span></p>
                            <div class="product-card__footer">
                                <a class="btn btn--secondary product-card__btn" href="<?php the_permalink(); ?>">Купить</a>
                                <span class="pot-badge" aria-label="Размер горшка">
                                    <svg viewBox="0 0 53 38" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                        <line x1="12.5" y1="36.0002" x2="40.5" y2="36.0002" stroke="currentColor" stroke-width="3" stroke-linecap="round" />
                                        <line x1="1.5" y1="-1.5" x2="37.1826" y2="-1.5" transform="matrix(0.29468 -0.955596 0.957003 0.290079 41.601 37.4829)" stroke="currentColor" stroke-width="3" stroke-linecap="round" />
                                        <line x1="1.5" y1="-1.5" x2="37.1826" y2="-1.5" transform="matrix(-0.29468 -0.955596 -0.957003 0.290079 11.399 37.4651)" stroke="currentColor" stroke-width="3" stroke-linecap="round" />
                                        <text x="26.5" y="23" text-anchor="middle" font-size="18" font-weight="500" fill="currentColor"><?php echo esc_html($container); ?></text>
                                    </svg>
                                </span>
                            </div>
                        </article>
                    <?php endwhile; ?>
                    <?php wp_reset_postdata(); ?>
                <?php else : ?>
                    <p class="catalog__empty">Товары пока не добавлены.</p>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <?php include __DIR__ . '/partials/contact-block.php'; ?>
</main>

<?php get_footer();

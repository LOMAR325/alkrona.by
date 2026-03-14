<?php
// Этот файл содержит HTML внутри <main> из оригинального index.html.
?>
<section class="hero" id="top">
    <div class="container hero__inner">
        <div class="hero__text">
            <h1 class="hero__title"><span class="hero__title-bold">Питомник декоративных растений</span><br></h1>
            <p class="hero__subtitle">У нас вы найдете более 100 видов декоративных растений для профессионального озеленения и ландшафтного дизайна</p>
            <div class="hero__actions">
                <a class="btn btn--primary hero__btn" href="#catalog">Каталог</a>
                <a class="btn btn--secondary hero__btn hero__btn--accent" href="#contact-form">Оформить заказ</a>
            </div>
        </div>
        <div class="hero__image">
            <?php echo wp_get_attachment_image(352, 'full'); ?>
        </div>
    </div>
</section>

<section class="section section--soft categories" id="catalog">
    <div class="container">
        <h2 class="section-title">Категории растений</h2>
        <div class="categories__grid">
            <article class="category-card">
                <img class="category-card__img" src="<?php echo esc_url(alkrona_theme_image_url('category_conifers', 'hvoi.png')); ?>" alt="Хвойные растения" />
                <div class="category-card__body">
                    <h3 class="category-card__title">Хвойные растения</h3>
                    <a class="btn btn--secondary category-card__btn" href="<?php echo esc_url(alkrona_catalog_category_url_by_keyword('хвой')); ?>">Подробнее</a>
                </div>
            </article>

            <article class="category-card">
                <img class="category-card__img" src="<?php echo esc_url(alkrona_theme_image_url('category_shrubs', 'listven.png')); ?>" alt="Лиственные кустарники" />
                <div class="category-card__body">
                    <h3 class="category-card__title">Лиственные кустарники</h3>
                    <a class="btn btn--secondary category-card__btn" href="<?php echo esc_url(alkrona_catalog_category_url_by_keyword('листвен')); ?>">Подробнее</a>
                </div>
            </article>
        </div>
    </div>
</section>

<section class="section audience" id="why">
    <div class="container">
        <h2 class="section-title">Кому мы подходим</h2>
        <div class="audience__grid">
            <article class="audience-card">
                <img class="audience-card__icon" src="<?php echo esc_url(alkrona_theme_image_url('audience_land_icon', 'land_comp.png')); ?>" alt="Ландшафтные компании" />
                <h3 class="audience-card__title">Ландшафтные компании</h3>
                <p class="audience-card__desc">Оптовые партии, подбор под проекты</p>
            </article>

            <article class="audience-card">
                <img class="audience-card__icon" src="<?php echo esc_url(alkrona_theme_image_url('audience_clients_icon', 'clients.png')); ?>" alt="Частные клиенты" />
                <h3 class="audience-card__title">Частные клиенты</h3>
                <p class="audience-card__desc">Сад для дома и дачи</p>
            </article>

            <article class="audience-card">
                <img class="audience-card__icon" src="<?php echo esc_url(alkrona_theme_image_url('audience_dev_icon', 'dev.png')); ?>" alt="Девелоперы и застройщики" />
                <h3 class="audience-card__title">Девелоперы и застройщики</h3>
                <p class="audience-card__desc">Озеленение территорий</p>
            </article>

            <article class="audience-card">
                <img class="audience-card__icon" src="<?php echo esc_url(alkrona_theme_image_url('audience_centers_icon', 'sad_centers.png')); ?>" alt="Садовые центры" />
                <h3 class="audience-card__title">Садовые центры</h3>
                <p class="audience-card__desc">Регулярные поставки растений</p>
            </article>
        </div>
    </div>
</section>


<section class="section section--soft products">
    <div class="container">
        <h2 class="section-title">Популярные растения</h2>
        <div class="products__grid">
            <?php
            $featured_ids = alkrona_featured_product_ids(4);

            foreach ($featured_ids as $product_id) :
                $product_name = get_the_title($product_id);
                $product_url  = get_permalink($product_id);
                $price_text   = alkrona_product_price_text((int) $product_id);
                $price_prefix = alkrona_product_price_prefix((int) $product_id);
                $container    = alkrona_product_container((int) $product_id);
            ?>
                <article class="product-card">
                    <div class="product-card__img-wrap">
                        <a href="<?php echo esc_url($product_url); ?>">
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
                        </a>
                    </div>
                    <h4 class="product-card__title"><?php echo esc_html($product_name); ?></h4>
                    <p class="product-card__price"><span class="product-card__price-from"><?php echo esc_html($price_prefix); ?></span><span class="product-card__price-value"><?php echo esc_html($price_text); ?></span></p>
                    <div class="product-card__footer">
                        <a class="btn btn--secondary product-card__btn" href="<?php echo esc_url($product_url); ?>">Подробнее</a>
                        <span class="pot-badge" aria-label="Размер горшка">
                            <svg viewBox="0 0 53 38" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <line x1="12.5" y1="36.0002" x2="40.5" y2="36.0002" stroke="currentColor"
                                    stroke-width="3" stroke-linecap="round" />
                                <line x1="1.5" y1="-1.5" x2="37.1826" y2="-1.5"
                                    transform="matrix(0.29468 -0.955596 0.957003 0.290079 41.601 37.4829)"
                                    stroke="currentColor" stroke-width="3" stroke-linecap="round" />
                                <line x1="1.5" y1="-1.5" x2="37.1826" y2="-1.5"
                                    transform="matrix(-0.29468 -0.955596 -0.957003 0.290079 11.399 37.4651)"
                                    stroke="currentColor" stroke-width="3" stroke-linecap="round" />
                                <text x="26.5" y="23" text-anchor="middle" font-size="18" font-weight="500"
                                    fill="currentColor"><?php echo esc_html($container); ?></text>
                            </svg>
                        </span>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php include __DIR__ . '/contact-block.php'; ?>
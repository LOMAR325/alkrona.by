<?php
/**
 * Custom WooCommerce single product template aligned with the static layout.
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<main>
<?php while ( have_posts() ) : the_post(); ?>

    <?php
    global $product;

    $product_id = get_the_ID();
    if ( empty( $product ) || ! is_a( $product, 'WC_Product' ) ) {
        $product = wc_get_product( $product_id );
    }

    $product_title = get_the_title();

    $latin_raw = $product ? $product->get_short_description() : '';
    $latin_trimmed = trim( wp_strip_all_tags( $latin_raw ) );
    $latin_name = $latin_trimmed === '' ? '' : $latin_raw;

    $price_text   = function_exists( 'alkrona_product_price_text' ) ? alkrona_product_price_text( $product_id ) : '';
    $price_prefix = function_exists( 'alkrona_product_price_prefix' ) ? alkrona_product_price_prefix( $product_id ) : '';
    $variants     = function_exists( 'alkrona_product_variants' ) ? alkrona_product_variants( $product_id ) : [];

    $gallery_ids = [];
    if ( has_post_thumbnail( $product_id ) ) {
        $gallery_ids[] = (int) get_post_thumbnail_id( $product_id );
    }
    if ( $product instanceof WC_Product ) {
        $gallery_ids = array_merge( $gallery_ids, array_map( 'intval', $product->get_gallery_image_ids() ) );
    }
    $gallery_ids = array_values( array_unique( array_filter( $gallery_ids ) ) );
    $has_gallery_controls = count( $gallery_ids ) > 1;
    ?>

    <section class="container product-hero" id="product">
        <div class="product-hero__image">
            <div class="product-gallery" data-product-gallery tabindex="0">
                <div class="product-gallery__main">
                    <?php if ( ! empty( $gallery_ids ) ) : ?>
                        <?php foreach ( $gallery_ids as $index => $attachment_id ) : ?>
                            <div
                                class="product-gallery__slide<?php echo $index === 0 ? ' is-active' : ''; ?>"
                                data-gallery-slide
                                aria-hidden="<?php echo $index === 0 ? 'false' : 'true'; ?>">
                                <?php
                                echo wp_get_attachment_image(
                                    $attachment_id,
                                    'large',
                                    false,
                                    [
                                        'class'   => 'product-gallery__img',
                                        'loading' => $index === 0 ? 'eager' : 'lazy',
                                    ]
                                );
                                ?>
                            </div>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <div class="product-gallery__slide is-active" data-gallery-slide aria-hidden="false">
                            <img
                                class="product-gallery__img"
                                src="<?php echo esc_url( alkrona_theme_image_url( 'product_placeholder', 'birucha.png' ) ); ?>"
                                alt="<?php echo esc_attr( $product_title ); ?>"
                                loading="eager" />
                        </div>
                    <?php endif; ?>

                    <?php if ( $has_gallery_controls ) : ?>
                        <button type="button" class="product-gallery__nav product-gallery__nav--prev" data-gallery-prev aria-label="Предыдущее фото">
                            &#8249;
                        </button>
                        <button type="button" class="product-gallery__nav product-gallery__nav--next" data-gallery-next aria-label="Следующее фото">
                            &#8250;
                        </button>
                    <?php endif; ?>
                </div>

                <?php if ( $has_gallery_controls ) : ?>
                    <div class="product-gallery__thumbs">
                        <?php foreach ( $gallery_ids as $index => $attachment_id ) : ?>
                            <button
                                type="button"
                                class="product-gallery__thumb<?php echo $index === 0 ? ' is-active' : ''; ?>"
                                data-gallery-thumb="<?php echo esc_attr( (string) $index ); ?>"
                                aria-label="<?php echo esc_attr( 'Фото ' . ( $index + 1 ) ); ?>"
                                aria-current="<?php echo $index === 0 ? 'true' : 'false'; ?>">
                                <?php
                                echo wp_get_attachment_image(
                                    $attachment_id,
                                    'thumbnail',
                                    false,
                                    [
                                        'class'   => 'product-gallery__thumb-img',
                                        'loading' => 'lazy',
                                    ]
                                );
                                ?>
                            </button>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="product-hero__content">

            <h1 class="product-hero__title">
                <?php echo esc_html( $product_title ); ?>
            </h1>

            <?php if ( ! empty( $latin_name ) ) : ?>
                <div class="product-hero__latin">
                    <?php
                    echo wp_kses_post( apply_filters( 'woocommerce_short_description', $latin_name ) );
                    ?>
                </div>
            <?php endif; ?>

            <div class="product-hero__description">
                <?php
                $full_desc = $product ? $product->get_description() : '';
                echo apply_filters( 'the_content', $full_desc );
                ?>
            </div>

            <div class="product-hero__price-buy">
                <p class="product-hero__price">
                    <span class="product-card__price-from">
                        <?php echo esc_html( $price_prefix ); ?>
                    </span>
                    <span>
                        <?php echo esc_html( $price_text ); ?>
                    </span>
                </p>

                <div class="product-hero__actions">
                    <a class="btn btn--secondary btn--buy"
                       href="<?php echo esc_url( alkrona_section_url( 'contacts' ) ); ?>"
                       data-open-contact-popup
                       data-product-name="<?php echo esc_attr( $product_title ); ?>">
                        Купить
                    </a>
                </div>
            </div>

        </div>
    </section>

    <section class="price-matrix-wrap">
        <div class="container">
            <table class="price-matrix" id="priceMatrix" aria-label="Цены по объему горшка">
                <tr>
                    <th scope="col">Горшок</th>
                    <th scope="col">Высота</th>
                    <th scope="col">Цена</th>
                    <th scope="col">Наличие</th>
                </tr>

                <?php if ( $variants ) : ?>
                    <?php foreach ( $variants as $variant ) : ?>

                        <?php
                        $variant_pot          = trim( (string) ( $variant['pot'] ?? '' ) );
                        $variant_height       = trim( (string) ( $variant['height'] ?? '' ) );
                        $variant_availability = trim( (string) ( $variant['availability'] ?? '' ) );
                        $variant_price        = (string) ( $variant['price'] ?? '' );
                        ?>

                        <tr>
                            <td data-label="Горшок"><?php echo esc_html( $variant_pot ?: '—' ); ?></td>
                            <td data-label="Высота"><?php echo esc_html( $variant_height ?: '—' ); ?></td>
                            <td data-label="Цена"><?php echo esc_html( alkrona_format_price( $variant_price ) ); ?></td>
                            <td data-label="Наличие"><?php echo esc_html( $variant_availability ?: '—' ); ?></td>
                        </tr>

                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td data-label="Горшок">—</td>
                        <td data-label="Высота">—</td>
                        <td data-label="Цена">—</td>
                        <td data-label="Наличие">—</td>
                    </tr>
                <?php endif; ?>

            </table>
        </div>
    </section>

<?php endwhile; ?>

<?php include get_template_directory() . '/partials/contact-block.php'; ?>

</main>

<?php get_footer(); ?>

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
    ?>

    <section class="container product-hero" id="product">
        <div class="product-hero__image">
            <?php
            if ( has_post_thumbnail( $product_id ) ) {
                echo wp_get_attachment_image(
                    get_post_thumbnail_id( $product_id ),
                    'large',
                    false,
                    [ 'loading' => 'lazy' ]
                );
            } else {
                echo '<img src="' .
                    esc_url( alkrona_theme_image_url( 'product_placeholder', 'birucha.png' ) ) .
                    '" alt="' . esc_attr( $product_title ) .
                    '" loading="lazy" />';
            }
            ?>
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
                    <th>Горшок</th>
                    <th>Высота</th>
                    <th>Цена</th>
                    <th>Наличие</th>
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
                            <td><?php echo esc_html( $variant_pot ?: '—' ); ?></td>
                            <td><?php echo esc_html( $variant_height ?: '—' ); ?></td>
                            <td><?php echo esc_html( alkrona_format_price( $variant_price ) ); ?></td>
                            <td><?php echo esc_html( $variant_availability ?: '—' ); ?></td>
                        </tr>

                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td>—</td>
                        <td>—</td>
                        <td>—</td>
                        <td>—</td>
                    </tr>
                <?php endif; ?>

            </table>
        </div>
    </section>

<?php endwhile; ?>

<?php include get_template_directory() . '/partials/contact-block.php'; ?>

</main>

<?php get_footer(); ?>

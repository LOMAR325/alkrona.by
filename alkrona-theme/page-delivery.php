<?php
/**
 * Template for Delivery & Payment page.
 */

defined('ABSPATH') || exit;

get_header();
?>

<main>
    <section class="section section--soft delivery-page">
        <div class="container">
            <h1 class="section-title delivery-page__title">Оплата и доставка</h1>

            <div class="delivery-page__cards">
                <article class="delivery-card">
                    <h2 class="delivery-card__title">Самовывоз из питомника</h2>
                    <p class="delivery-card__text">
                        Адрес: микрорайон "Свислочь Новая", г.п. Свислочь, Минская область 220823.<br><br>
                        График работы: 9:00-17:00, суббота и воскресенье — выходной.
                    </p>
                </article>

                <article class="delivery-card">
                    <h2 class="delivery-card__title">Условия оплаты</h2>
                    <p class="delivery-card__text">
                        Наличный и безналичный расчет.
                    </p>
                </article>

                <article class="delivery-card">
                    <h2 class="delivery-card__title">Доставка по Минску и области</h2>
                    <p class="delivery-card__text">
                        Доставка по г. Минску и в радиусе 10-30 км от Минска при заказе от 500 руб.
                        согласовывается с менеджером на индивидуальных условиях.
                    </p>
                </article>

                
            </div>
        </div>
    </section>

    <?php include get_template_directory() . '/partials/contact-block.php'; ?>
</main>

<?php get_footer(); ?>

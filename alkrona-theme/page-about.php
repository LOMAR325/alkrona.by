<?php

/**
 * Template for About page.
 */

defined('ABSPATH') || exit;

get_header();
?>

<main>
    <section class="section section--soft about-hero">
        <div class="container about-hero__inner">
            <div class="about-hero__text">
                <h1 class="section-title about-hero__title">О питомнике «Алькрона»</h1>
                <p class="about-hero__lead">Мы выращиваем декоративные растения, которые хорошо приживаются и сохраняют декоративность в белорусском климате.</p>
                <p>Питомник работает с ландшафтными компаниями, садовыми центрами и частными клиентами. В работе используем проверенный посадочный материал, контроль состояния растений и аккуратную подготовку к отгрузке.</p>
                <ul class="about-hero__list">
                    <li>Собственное выращивание и регулярный уход</li>
                    <li>Подбор растений под задачи участка и проекта</li>
                    <li>Консультации по посадке и дальнейшему уходу</li>
                </ul>
            </div>
            <div class="about-hero__media">
                <?php echo wp_get_attachment_image(355, 'full'); ?>
            </div>
        </div>
    </section>

    <section class="section about-values">
        <div class="container">
            <h2 class="section-title">Как мы работаем</h2>
            <div class="about-values__grid">
                <article class="about-value-card">
                    <h3 class="about-value-card__title">Подбор ассортимента</h3>
                    <p class="about-value-card__text">Помогаем выбрать виды и размеры растений под ваш бюджет, сроки и условия участка.</p>
                </article>
                <article class="about-value-card">
                    <h3 class="about-value-card__title">Качество материала</h3>
                    <p class="about-value-card__text">Перед отправкой проверяем состояние корневой системы и надземной части каждого растения.</p>
                </article>
                <article class="about-value-card">
                    <h3 class="about-value-card__title">Поставка партиями</h3>
                    <p class="about-value-card__text">Собираем как небольшие заказы для частных участков, так и оптовые партии под объекты.</p>
                </article>
                <article class="about-value-card">
                    <h3 class="about-value-card__title">Поддержка после покупки</h3>
                    <p class="about-value-card__text">Даём понятные рекомендации по посадке, поливу и уходу в первые месяцы после высадки.</p>
                </article>
            </div>
        </div>
    </section>

    <section class="section section--soft about-gallery">
        <div class="container">
            <h2 class="section-title">Фотографии питомника</h2>
            <div class="about-gallery__grid">
                <figure class="about-gallery__item about-gallery__item--wide">
                    <?php echo wp_get_attachment_image(374, 'full'); ?>
                    <figcaption class="about-gallery__caption">Участок хвойных растений</figcaption>
                </figure>
                <figure class="about-gallery__item about-gallery__item--half">
                    <?php echo wp_get_attachment_image(381, 'full'); ?>
                    <figcaption class="about-gallery__caption">Подготовка растений к отгрузке</figcaption>
                </figure>
                <figure class="about-gallery__item about-gallery__item--half">
                    <?php echo wp_get_attachment_image(370, 'full'); ?>
                    <figcaption class="about-gallery__caption">Лиственные кустарники</figcaption>
                </figure>
                <figure class="about-gallery__item about-gallery__item--half">
                    <?php echo wp_get_attachment_image(373, 'full'); ?>
                    <figcaption class="about-gallery__caption">Материал для озеленения проектов</figcaption>
                </figure>
                <figure class="about-gallery__item about-gallery__item--half">
                    <?php echo wp_get_attachment_image(378, 'full'); ?>
                    <figcaption class="about-gallery__caption">Декоративные кустарники</figcaption>
                </figure>
            </div>
        </div>
    </section>

    <?php include get_template_directory() . '/partials/contact-block.php'; ?>
</main>

<?php get_footer(); ?>
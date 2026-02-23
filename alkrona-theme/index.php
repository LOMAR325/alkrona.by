<?php
// Fallback: show blog or static front page depending on WP settings.
if (is_front_page()) {
    require get_template_directory() . '/front-page.php';
    return;
}
get_header();
?>
<main class="container" style="padding:40px 0;">
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <article <?php post_class(); ?>>
            <h1><?php the_title(); ?></h1>
            <div><?php the_content(); ?></div>
        </article>
    <?php endwhile; else : ?>
        <p><?php esc_html_e('Ничего не найдено', 'alkrona'); ?></p>
    <?php endif; ?>
</main>
<?php get_footer();

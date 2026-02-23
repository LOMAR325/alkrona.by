<?php get_header(); ?>
<main class="container" style="padding:40px 0;">
    <header class="page-header"><h1><?php the_archive_title(); ?></h1></header>
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <article <?php post_class(); ?>>
            <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
            <div><?php the_excerpt(); ?></div>
        </article>
    <?php endwhile; the_posts_pagination(); else : ?>
        <p><?php esc_html_e('Ничего не найдено', 'alkrona'); ?></p>
    <?php endif; ?>
</main>
<?php get_footer();

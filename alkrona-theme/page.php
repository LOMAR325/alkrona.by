<?php get_header(); ?>
<main class="container" style="padding:40px 0;">
    <div class="page-content">
        <?php
        if (have_posts()) {
            while (have_posts()) {
                the_post();
                the_content();
            }
        }
        ?>
    </div>
</main>
<?php get_footer(); ?>

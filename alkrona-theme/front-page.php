<?php
/**
 * Template for the front page (single-page landing version).
 */
get_header();
?>

<main>
    <?php
    // Load static HTML sections from the legacy index.html to keep markup/styles.
    // Easiest is to include the raw file and strip the header/footer parts.
    // Here we inline the content between <main>...</main> from the static file for speed.
    include __DIR__ . '/partials/landing-main.php';
    ?>
</main>

<?php get_footer();

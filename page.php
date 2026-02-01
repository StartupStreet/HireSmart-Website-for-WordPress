<?php
/**
 * Template for displaying pages
 * 
 * This template is used for rendering all pages including
 * dashboard, login, register, and other HireSmart pages
 * 
 * @package HireSmart
 * @version 1.0.0
 */

get_header(); ?>

<div class="page-wrapper">
    <?php
    while (have_posts()) : the_post();
        ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <div class="page-content-wrapper">
                <?php
                the_content();
                ?>
            </div>
        </article>
        <?php
    endwhile;
    ?>
</div>

<style>
.page-wrapper {
    min-height: calc(100vh - 200px);
    padding: 40px 0;
}
.page-content-wrapper {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 20px;
}
</style>

<?php get_footer(); ?>

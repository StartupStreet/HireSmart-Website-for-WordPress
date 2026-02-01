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

<div class="page-content">
    <?php
    while (have_posts()) : the_post();
        ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <?php if (!is_front_page()): ?>
                <header class="page-header">
                    <h1 class="page-title"><?php the_title(); ?></h1>
                </header>
            <?php endif; ?>
            
            <div class="page-entry-content">
                <?php
                the_content();
                ?>
            </div>
        </article>
        <?php
    endwhile;
    ?>
</div>

<?php get_footer(); ?>

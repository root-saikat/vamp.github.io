<?php 
/**
 * Template Name: Homepage with slider
 * The template used for displaying the homepage with a slider on the header.
 *
 * @package prometium
 */

get_header(); ?>

    <main class="homepage">
        
        <div class="container">
            <?php if(have_posts()) : ?>
                <?php while(have_posts()) : the_post(); ?>
                    <?php the_content(); ?>
                <?php endwhile; ?>
            <?php else : ?>
                <p>No page.</p>
            <?php endif;?>     
        </div>

    </main>

<?php get_footer(); ?>
<?php get_header(); ?>

    <main>
        <div id="breadcrumb" class="text-center">
            <h2><?php prometium_get_page_title() ?></h2>
        </div>
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
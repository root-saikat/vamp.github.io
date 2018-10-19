<?php get_header(); ?>

<main>

    <div id="breadcrumb" class="text-center">
        <h2><?php prometium_get_page_title() ?></h2>
    </div>
    <?php if(have_posts()) : ?>
        <?php while(have_posts()) : the_post(); ?>
            <div class="container portfolio-single">
                <img src="<?php echo the_post_thumbnail_url('portfolio-full'); ?>" alt="<?php the_title(); ?> image" class="img-fluid">

                <div class="row">
                    <div class="col-md-9 drag-this-left">
                        <h3><?php the_title() ?></h3>
                        <p><?php the_content() ?></p>
                        <div class="portfolio-single-share">
                            <?php
                            global $post;
                            $tags = get_the_tags($post->ID);
                            $len = sizeof($tags);
                            $c = 0;
                            ?>
                            <?php if (isset($tags)) : ?>
                                <p class="tags">in 
                                    <?php foreach ($tags as $tag) : ?>
                                        <a href="<?php bloginfo('url'); ?>/tag/<?php print_r($tag->slug); ?>"><?php echo $tag->name ?></a><?php
                                        $c++;
                                        if ($c < $len) {
                                            echo ', ';
                                        }
                                        ?>
                                    <?php endforeach; ?>
                                </p>
                            <?php endif; ?>
                            <div class="social">
                                <a href="#" class="social-toggle"><i class="fa fa-share-alt fa-lg"></i></a>
                                <ul class="social-links">
                                    <li><a href=https://www.facebook.com/sharer/sharer.php?u=<?php urlencode(the_permalink()); ?>"><i class="fa fa-facebook"></i></a></li>
                                    <li><a href="https://twitter.com/home?status=<?php urlencode(the_permalink()); ?>"><i class="fa fa-twitter"></i></a></li>
                                    <li><a href="https://plus.google.com/share?url=<?php urlencode(the_permalink()); ?>"><i class="fa fa-google-plus"></i></a></li>
                                    <li><a href="https://pinterest.com/pin/create/button/?url=<?php urlencode(the_permalink()); ?>"><i class="fa fa-pinterest-p"></i></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <?php 
                        $features = '';
                        $empty = true;
                        for ($i=0; $i<5; $i++){
                            $f = get_post_meta($post->ID, 'f'.$i, true);
                            if (!empty($f)){
                                $empty = false;
                                $features .= '<li>' . $f . '</li>';
                            }
                        }
                    ?>
                    <?php if (!$empty) : ?>
                        <div class="col-md-3 drag-this-right">
                            <aside class="portfolio-single-aside">
                                <h4>PROJECT FEATURES</h4>
                                <ul>
                                    <?php echo $features; ?>
                                </ul>
                                <a href="<?php echo get_post_meta($post->ID, 'link_url', true) ?>" class="signup-button"><?php echo get_post_meta($post->ID, 'link_text', true) ?></a>
                            </aside>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endwhile; ?>
    <?php endif; ?>
    
    <?php 
         
    if(PrometiumOptions::get('single_slider')){
        $args = array(
            'post_type' => 'portfolio_item'
        );

        $portfolio = new WP_Query($args);
        if ($portfolio->have_posts()) {
            ?>
        <div class="portfolio-single-related portfolio-browse drag-this-up">
            <h3 class="text-center">Related Projects</h3>
            <div class="slick-related-projects">
        <?php
            while ($portfolio->have_posts()) {
                $portfolio->the_post();
                echo '<a href="' . get_the_permalink() . '">';
                    the_post_thumbnail('portfolio-slide');
                echo '</a>';

            }
            ?>
            </div>
        </div>
        <?php

        }
    }
    
    ?>
    
</main>

<?php get_footer(); ?>
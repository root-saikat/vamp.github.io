<?php get_header(); ?>

<main>
    <div id="breadcrumb" class="text-center">
        <div class="breadcrumb-overlay"></div>
        <h2><?php prometium_get_page_title() ?></h2>
    </div>
    <div class="container blog-listing">
        <div class="row">
            <?php if(is_active_sidebar('sidebar-1')) :?>
                <div class="col-lg-9">
            <?php else: ?>
                <div class="col-12">
            <?php endif;?>
                <?php if(have_posts()) : ?>
                    
                    <?php if(is_author()): ?>
                        <div class="blog-author author-main">
                            <?php echo get_avatar( get_the_author_meta('ID')); ?> 
                            <section>
                                <h6><?php the_author() ?></h6>
                                <p><?php the_author_meta('description'); ?></p>
                            </section>                   
                        </div>
                    <?php endif; ?>
                    
                    <?php while(have_posts()) : the_post(); ?>
                    
                        <div id="post-<?php the_ID(); ?>" class="blog-post <?php post_class() ?>">
                            <?php if (has_post_thumbnail()) : ?>
                                <img src="<?php echo the_post_thumbnail_url('full'); ?>" alt="Blog image" class="img-fluid">
                            <?php endif; ?>
                            <div class="post-meta">
                                <span class="post-date"><?php echo Date('d F, Y'); ?> </span>
                                |
                                <span class="post-author"><a href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>" class="post-author"><?php the_author(); ?></a></span>
                                |
                                <span class="comment-count"><?php echo get_comments_number(); ?> comments</span>
                            </div>
                            <h3><?php the_title(); ?></h3>
                            <p>
                                <?php the_excerpt(); ?>
                            </p>
                            <div class="blog-share">
                                <?php 
                                    global $post;
                                    $tags = get_the_tags($post->ID); 
                                    $len = sizeof($tags);
                                    $c = 0;
                                ?>
                                <?php if (is_array($tags) || is_object($tags)): ?>
                                    <p class="tags">in 
                                        <?php foreach($tags as $tag) :  ?>
                                            <a href="<?php bloginfo('url');?>/tag/<?php print_r($tag->slug);?>"><?php echo $tag->name?></a><?php 
                                                $c++;
                                                if($c < $len){
                                                    echo ', ';
                                                }
                                            ?>
                                        <?php endforeach;?>
                                    </p>
                                <?php endif; ?>
                                <div class="social">
                                    <a href="#" class="social-toggle"><i class="fa fa-share-alt fa-lg"></i></a>
                                    <ul class="social-links">
                                        <li><a href="https://www.facebook.com/sharer/sharer.php?u=<?php urlencode(the_permalink()); ?>"><i class="fa fa-facebook"></i></a></li>
                                        <li><a href="https://twitter.com/home?status=<?php urlencode(the_permalink()); ?>"><i class="fa fa-twitter"></i></a></li>
                                        <li><a href="https://plus.google.com/share?url=<?php urlencode(the_permalink()); ?>"><i class="fa fa-google-plus"></i></a></li>
                                        <li><a href="https://pinterest.com/pin/create/button/?url=<?php urlencode(the_permalink()); ?>"><i class="fa fa-pinterest-p"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                            <a href="<?php the_permalink();?>" class="signup-button">Read More</a>
                        </div>
                    <?php endwhile;?>
                <?php else : ?>
                    <p>No posts.</p>
                <?php endif;?>    
                    
                <?php prometium_pagination(); ?>
                    
            </div>
            <?php if(is_active_sidebar('sidebar-1')) :?>
                <div class="col-lg-3">
                    <aside class="blog-sidebar">
                        <?php dynamic_sidebar('sidebar-1'); ?>
                    </aside>
                </div>
            <?php endif; ?>
        </div>

    </div>

</main>

<?php get_footer(); ?>
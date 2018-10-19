<?php get_header(); ?>

<main>
    <div id="breadcrumb" class="text-center">
        <h2><?php prometium_get_page_title() ?></h2>
    </div>
    <?php 

    $view = prometium_get_portfolio_view();

    $counts = false;
    if(PrometiumOptions::get('portfolio_display_counts')){
        $counts = PrometiumOptions::get('portfolio_display_counts');
    }
    ?>

    <div class="container text-center">
        <?php
        if(isset($_GET['portfolio_category'])){
            $cat = $_GET['portfolio_category'];
        }else{
            $cat = '';
        }
        
        $terms = get_terms('portfolio_category', array('hide_empty' => true));
        if (!empty($terms) && !is_wp_error($terms)) : ?>
            <ul class="portfolio-nav">
                <li><a <?php echo $cat == "" ? "class='active'" : ""?> href="<?php echo esc_html(home_url('/'));?>portfolio_item">ALL</a></li>
                <?php foreach($terms as $term) : ?>
                    <li>    
                        <a <?php echo $cat == $term->slug ? "class='active'" : ""?> href="<?php echo esc_html(home_url('/'));?>portfolio_item/?portfolio_category=<?php echo $term->slug; ?>">
                            <?php echo $term->name; ?><?php echo $counts ? ' (' . $term->count . ')' : '' ?>
                        </a>
                    </li>
                <?php endforeach;?>
            </ul>
        <?php endif; ?>
    </div>

    <?php if(have_posts()) : ?>
        <div class="<?php echo $view == 'wide' ? 'container-fluid' : 'container'; ?> portfolio-browse active">
            <div class="row no-gutters">
                <?php while(have_posts()) : the_post(); ?>
                    <?php $size = $view =='wide' ? 'portfolio-wide' : 'portfolio-container'; ?>
                    <div class="col-md-3 col-sm-6"><a href="<?php the_permalink(); ?>"><img src="<?php the_post_thumbnail_url($size); ?>" class="img-fluid" alt="Portfolio image"></a></div>
                <?php endwhile;?>
            </div>
        </div>
        <?php prometium_pagination(); ?>
    <?php endif; ?>

</main>

<?php get_footer(); ?>
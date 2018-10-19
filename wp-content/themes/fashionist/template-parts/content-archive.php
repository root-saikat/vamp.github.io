<?php
/**
 * Template part for displaying standard posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Fashionist
 */

$categories = get_the_category();
$separator = ', ';
$output = '';
if ( ! empty( $categories ) ) {
    foreach( $categories as $category ) {
        $output .= '<a href="' . esc_url( get_category_link( $category->term_id ) ) . '" alt="' . esc_attr( sprintf( esc_html__( 'View all posts in %s', 'fashionist' ), $category->name ) ) . '">' . esc_html( $category->name ) . '</a>' . $separator;
    }
    $cat_list = trim( $output, $separator );
}

?>
<article class="standard_blog_post">
    <a href="<?php the_permalink(); ?>"><img class="img-responsive" src="<?php the_post_thumbnail_url( 'blog_full' ); ?>" alt="" /></a>
    <a href="<?php the_permalink(); ?>"><h3 class="post_title"><?php the_title(); ?></h3></a>
    
    <h4><span class="date">
    	<a href="<?php echo get_month_link(get_the_time('Y'), get_the_time('m')); ?>"><?php echo get_the_date(); ?></a></span> | <span class="author"><?php the_author_posts_link(); ?></span> | <?php echo esc_html__('in','fashionist'); ?> <span class="category"><?php echo esc_html($cat_list); ?></span>
    </h4>

	<p><?php echo wp_trim_words( get_the_content(), 30, '' ); ?></p>

    <a class="read_more pull-right" href="<?php the_permalink(); ?>"><span class="blog-icon arrow_right"></span></a>
</article>

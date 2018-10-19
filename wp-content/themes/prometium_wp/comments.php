<?php
/**
 * The template for displaying comments.
 *
 * This is the template that displays the area of the page that contains both the current comments
 * and the comment form.
 *
 * @package Prometium
 */
/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if (post_password_required()) {
    return;
}
?>

<div id="comments" class="blog-comments comments-area post_comments">

    <?php // You can start editing here -- including this comment!  ?>

    <?php if (have_comments()) : ?>
        <h3>Comments</h3>
        

        <ul class="media-list">
            <?php wp_list_comments( 'type=comment&callback=prometium_comment' ); ?>
        </ul><!-- .comment-list -->

        <?php if (get_comment_pages_count() > 1 && get_option('page_comments')) : // Are there comments to navigate through?  ?>
            <nav id="comment-nav-below" class="navigation comment-navigation" role="navigation">
                <h2 class="screen-reader-text"><?php esc_html_e('Comment navigation', 'prometium'); ?></h2>
                <div class="nav-links">

                    <div class="nav-previous"><?php previous_comments_link(); ?></div>
                    <div class="nav-next"><?php next_comments_link(); ?></div>

                </div><!-- .nav-links -->
            </nav><!-- #comment-nav-below -->
        <?php endif; // Check for comment navigation.  ?>

    <?php endif; // Check for have_comments().  ?>

    <?php
    
    if (!comments_open() && get_comments_number() && post_type_supports(get_post_type(), 'comments')) :
        ?>
        <p class="no-comments"><?php esc_html_e('Comments are closed.', 'prometium'); ?></p>
    <?php endif; ?>


    <div class="comment_form_section contact">
        <?php
        $req = get_option('require_name_email');
        $aria_req = ( $req ? " aria-required='true'" : '' );

        $comment_args = array(
            'title_reply_before' => '<strong class="comment_form_title"><span>',
            'title_reply_after' => '</span></strong>',
            'comment_notes_before' => '',
            'class_form' => 'comment_form row',
            'fields' => apply_filters('comment_form_default_fields', array(
                'author' =>
                '<div class="inline_form col-md-4 no-padding-left">' .
                '<label ' . ($req ? 'class="required-input" ' : '') . 'for="v_name">' . esc_html__('NAME', 'v') . '' .
                '</label><input id="v_name" name="author" type="text" placeholder="enter your name" value="' . esc_attr($commenter['comment_author']) .
                '" size="30"' . $aria_req . ' /></div>',
                'email' =>
                '<div class="inline_form col-md-4 no-padding-left"><label ' . ($req ? 'class="required-input" ' : '') . 'for="v_email">' . esc_html__('EMAIL', 'prometium') . ' ' .
                '</label><input id="v_email" name="email" type="text" placeholder="email" value="' . esc_attr($commenter['comment_author_email']) .
                '" size="30"' . $aria_req . ' /></div>',
                'url' =>
                '<div class="inline_form col-md-4 no-padding-left "><label for="v_website">' .
                esc_html__('Website', 'prometium') . '</label>' .
                '<input id="v_website" name="url" type="text" placeholder="https://" value="' . esc_attr($commenter['comment_author_url']) .
                '" size="30" /></div>',
                    )
            ),
            'comment_field' => '<div class="clear-fix"></div><div class="text_area_form col-md-12">' .
            '<label ' . ($req ? 'class="required-input" ' : '') . 'for="v_comment">' . esc_html__('MESSAGE', 'prometium') . '</label>' .
            '<textarea id="v_comment" placeholder="enter your text" name="comment" aria-required="true"></textarea>' .
            '</div>',
        );

        comment_form($comment_args);
        ?>
    </div>

</div><!-- #comments -->

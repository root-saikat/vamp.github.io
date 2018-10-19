<?php 

/*
 * Prometium WP theme functions file.
 */

/*Prometium - Redux integration*/

if ( ! class_exists( 'PrometiumOptions' ) ){
    class PrometiumOptions {
        static public function is( $key, $compare ) {
            $value = static::get( $key );
            return $value === $compare;
        }
        static public function not( $key, $compare ) {
            $value = static::get( $key );
            return $value !== $compare;
        }
        static public function has( $key ) {
            $value = static::get( $key );
            return ! empty( $value );
        }
        static public function get( $key ) {
            global $prometium_options;
            if ($prometium_options == null) {
                return null;
            }
            return isset( $prometium_options[ $key ] ) ? $prometium_options[ $key ] : null;
        }
    }
}


function prometium_theme_setup(){ 
    add_theme_support('post-thumbnails');
    add_theme_support( 'automatic-feed-links' );
    add_image_size( 'portfolio-full', 1170, 500, true);
    add_image_size( 'portfolio-slide', 480, 340, true);
    add_image_size( 'portfolio-container', 284 , 220, true);
    add_image_size( 'portfolio-wide', 476, 338, true);
    register_nav_menus(array(    
        'primary' => esc_html__('Primary Menu', 'prometium')
    ));
}

add_action('after_setup_theme', 'prometium_theme_setup');
$content_width = 640;


/*-------- Check whether a plugin is available or not -----*/

if( !function_exists( 'prometium_check_plugin' ) ) {
    function prometium_check_plugin($path = '')
    {
        if (strlen($path) == 0){
            return false;
        } 
        $_actived = apply_filters('active_plugins', get_option('active_plugins'));
        if (in_array(trim($path), $_actived)){
            return true;
        }
        return false;
    }
}

function prometium_get_portfolio_view(){

    $view = 'simple';

    if(PrometiumOptions::get('portfolio_layout')){
        $view = PrometiumOptions::get('portfolio_layout');
    }

    if(isset($_GET['view'])){
        if($_GET['view'] == 'wide'){
            $view = 'wide';
        }
    }
    return $view;
}

function prometium_author_excerpt (){
    echo wp_trim_words(strip_tags(get_the_author_meta('description')), 40, '...' );
}

function prometium_portfolio_pagesize( $query ) {

    if( !$query->is_main_query()){
        return;
    }

    if( is_post_type_archive('portfolio_item') ){

        if(prometium_get_portfolio_view() == 'simple'){
            $query->set( 'posts_per_page', 12 );
        }else{
            $query->set( 'posts_per_page', 8 );
        }

    }
}

add_action( 'pre_get_posts', 'prometium_portfolio_pagesize', 1 );

function prometium_pagination() {

    global $wp_query;
    $big = 999999999; // need an unlikely integer
    $pages = paginate_links(array(
        'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
        'format' => '?paged=%#%',
        'current' => max(1, get_query_var('paged')),
        'total' => $wp_query->max_num_pages,
        'type' => 'array',
        'prev_next' => false
            ));

    if (is_array($pages)) {
        $paged = ( get_query_var('paged') == 0 ) ? 1 : get_query_var('paged');
        echo '<div class="text-center paginations">';
        if ($paged > 1) {
            echo '<a class="pagination-nav" href="' . get_previous_posts_page_link() . '">PREV <i class="fa fa-angle-right"></i></a>';
        } else {
            echo '<span class="pagination-nav"><i class="fa fa-angle-left"></i> PREV</span>';
        }
        $counter = 1;
        $ext = 0;
        foreach ($pages as $page) {
            echo wp_kses_post($page);
        }
        if ($paged < $wp_query->max_num_pages) {
            echo '<a class="pagination-nav" href="' . get_next_posts_page_link() . '" class="next_page">NEXT <i class="fa fa-angle-right"></i></a>';
        } else {
            echo '<span class="pagination-nav" class="next_page">NEXT <i class="fa fa-angle-right"></i></span>';
        }
        echo '</div>';
    }
}

function prometium_comment($comment, $args, $depth){
    $add_below = 'div-comment';
    ?>
    <li class="comment clearfix" <?php comment_class( 'media' ) ?> id="comment-<?php comment_ID() ?>">
        <?php echo get_avatar( $comment,66,array('class'=>'media-object','force_default'=>false) ); ?>
        <div class="inner-comment clearfix">        
            <a href="<?php echo get_comment_author_url(); ?>" class="commenter">
                <?php echo get_comment_author(); ?>
            </a>
            <span class="postedon">
                <?php printf( esc_html__('%1$s at %2$s', 'prometium'), get_comment_date(),  get_comment_time() ); ?>
            </span>
            <?php
            if ( $comment->comment_approved == '0' ) { ?>
                <p><?php esc_html__( 'Your comment is awaiting moderation.', 'prometium'); ?></p>
            <?php
            } 
            else{ 
                comment_text(); 
                ?>
                <?php comment_reply_link( array_merge( $args, array( 'add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
                <?php           
            }
            ?>
        </div>
    <?php
}

function prometium_move_comment_field_to_bottom( $fields ) {
$comment_field = $fields['comment'];
unset( $fields['comment'] );
$fields['comment'] = $comment_field;
    
    return $fields;
}
add_filter( 'comment_form_fields', 'prometium_move_comment_field_to_bottom' );

function prometium_get_page_title() {
    global $post;
    
    if (!is_front_page()) {
        if (is_archive()) {
            echo get_the_archive_title();
        } elseif(is_single()) {
            echo get_the_title();
        } elseif (is_page() && $post->post_parent) {
            echo get_the_title();
        } elseif (is_page()) {
            echo get_the_title();
        } elseif (is_404()) {
            echo esc_html__('404', 'prometium');
        } elseif (is_search()) {
            echo esc_html__('Search Results for: ', 'prometium'), '<em>' . get_search_query() . '</em>';
        }
    }
}

function prometium_has_contact_info(){
    return strlen(prometium_get_contact_info());
}

function prometium_contact_info(){
    echo prometium_get_contact_info();
}

function prometium_get_contact_info(){
    
    $contact = '';
    
    if(PrometiumOptions::get('address_text')){
        $contact .= '<p class="footer-address"><i class="fa fa-map-marker"></i>' . PrometiumOptions::get('address_text') . '</p> ';
    }
    if(PrometiumOptions::get('call_text')){
        $contact .= '<p class="footer-phone"><i class="fa fa-phone"></i>' . PrometiumOptions::get('call_text') . '</p>';
    }
    if(PrometiumOptions::get('email_text')){
        $contact .= '<p class="footer-email"><span>@</span>' . PrometiumOptions::get('email_text') . '</p>';
    }
    return $contact;                
}

function prometium_get_is_home(){
    return get_page_template_slug() == 'page-home.php' || is_front_page();
}

function get_footer_background(){
   
    if(PrometiumOptions::get('footer_background')){
        $option = PrometiumOptions::get('footer_background');

        if($option == 'all'){
            return 'with-background';
        }else if($option == 'home'){
            return !prometium_get_is_home() ? 'with-background' : '';
        }else{
            return '';
        }
    }
    return '';
}

function prometium_archive_title( $title ) {
    if ( is_category() ) {
        $title = single_cat_title( '', false );
    } elseif ( is_tag() ) {
        $title = single_tag_title( '', false );
    } elseif ( is_author() ) {
        $title = '<span class="vcard">' . get_the_author() . '</span>';
    } elseif ( is_post_type_archive() ) {
        $title = post_type_archive_title( '', false );
    } elseif ( is_tax() ) {
        $title = single_term_title( '', false );
    }
  
    return $title;
}
 
add_filter( 'get_the_archive_title', 'prometium_archive_title' );


function prometium_breadcrumb(array $options = array()) {
    global $post;
    $separator = " <i class='fa fa-angle-right'></i> "; // Simply change the separator to what ever you need e.g. / or >
    
    if (!prometium_get_is_home()) {
        echo '<a href="' . esc_url(home_url('/')) . '">';
        esc_html_e('Home', 'prometium');
        echo "</a>" . $separator;
        if (is_archive()) {
            echo get_the_archive_title();
        } elseif (is_category() || is_single()) {
            echo "";
            the_category($separator);
            echo "";
            if (is_single()) {
                echo $separator . get_the_title();
            }
        } elseif (is_page() && $post->post_parent) {
            $home = get_page(get_option('page_on_front'));
            for ($i = count($post->ancestors) - 1; $i >= 0; $i--) {
                if (($home->ID) != ($post->ancestors[$i])) {
                    echo '<a href="';
                    echo get_permalink($post->ancestors[$i]);
                    echo '">';
                    echo get_the_title($post->ancestors[$i]);
                    echo "</a>" . $separator;
                }
            }
            echo "<a class='active'>" . get_the_title() . "</a>";
        } elseif (is_page()) {
            echo "<a class='active'>" . get_the_title() . "</a>";
        } elseif (is_404()) {
            echo "<a class='active'>" . esc_html__('404', 'prometium') . "</a>";
        } elseif (is_search()) {
            echo "<a class='active'>" . esc_html__('Search Results for: ', 'prometium'), '<span>' . get_search_query() . '</span>' . "</a>";
        }
    }
}

function prometium_widgets_init() {
    register_sidebar( array(
        'name'          => esc_html__( 'Sidebar', 'prometium' ),
        'id'            => 'sidebar-1',
        'description'   => esc_html__('Sidebar for blog pages','prometium'),
        'before_widget' => '<div id="%1$s" class="aside-widget">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4>',
        'after_title'   => '</h4>'
    ) );
    register_sidebar( array(
        'name'          => esc_html__( 'Footer', 'prometium' ),
        'id'            => 'footer-1',
        'description'   => esc_html__('Sidebar for footer columns','prometium'),
        'before_widget' => '<div id="%1$s" class="col-lg-2 col-sm-4 col-12 clearfix">',
        'after_widget'  => '</div>',
        'before_title'  => '<h6>',
        'after_title'   => '</h6>',
    ) );
    register_sidebar( array(
        'name'          => esc_html__( 'Footer instagram', 'prometium' ),
        'id'            => 'footer-instagram',
        'description'   => esc_html__('Sidebar for footer instagram column','prometium'),
        'before_widget' => '<div id="%1$s" class="col-lg-3 col-sm-6 col-12 clearfix">',
        'after_widget'  => '</div>',
        'before_title'  => '<h6>',
        'after_title'   => '</h6>',
    ) );
}
add_action( 'widgets_init', 'prometium_widgets_init' );

/*Ajax register form*/

add_action( 'wp_ajax_nopriv_homepage_registration_widget', 'homepage_registration_widget' );

function homepage_registration_widget(){
    
    global $reg_errors;
    $reg_errors = new WP_Error;
    
    $username = $_POST['username'];
    $first_name     =   sanitize_text_field( $_POST['fname'] );
    $last_name      =   sanitize_text_field( $_POST['lname'] );
    $email          = $_POST['email'];
    $password       =   esc_attr( $_POST['password'] );
    
    if ( username_exists( $username ) ){
        $reg_errors->add('user_name', 'Sorry, that username already exists!');
    }   
    if ( ! validate_username( $username ) ) {
        $reg_errors->add( 'username_invalid', 'Sorry, the username you entered is not valid' );
    }
    if ( !is_email( $email ) ) {
        $reg_errors->add( 'email_invalid', 'Email is not valid' );
    }
    if (email_exists($email)) {
        $reg_errors->add('email', 'Email Already in use');
    }
    
    if ( sizeof($reg_errors->get_error_messages()) >  0) {
        foreach ( $reg_errors->get_error_messages() as $error ) {
            echo '<strong>ERROR</strong>: ';
            echo $error . '<br/>';
        }
        die();
    }
    
    wp_insert_user( 
        array(
            'user_login'    =>   $username,
            'user_email'    =>   $email,
            'user_pass'     =>   $password,
            'first_name'    =>   $first_name,
            'last_name'     =>   $last_name
        )
    );
    
    echo 'Registration complete. Goto <a href="' . wp_login_url() . '">login page</a>.';   
    die();
}

/*Custom Widgets*/

Class My_Recent_Posts_Widget extends WP_Widget_Recent_Posts {

    function widget($args, $instance) {

        extract($args);

        $title = apply_filters('widget_title', empty($instance['title']) ? __('Recent Posts') : $instance['title'], $instance, $this->id_base);

        if (empty($instance['number']) || !$number = absint($instance['number']))
            $number = 10;

        $r = new WP_Query(apply_filters('widget_posts_args', array('posts_per_page' => $number, 'no_found_rows' => true, 'post_status' => 'publish', 'ignore_sticky_posts' => true)));
        if ($r->have_posts()) :

            echo $before_widget;
            if ($title)
                echo $before_title . $title . $after_title;
            ?>
            <ul>
                <?php while ($r->have_posts()) : $r->the_post(); ?>				
                    <div class="recent-post clearfix">
                        <?php the_post_thumbnail(array(70,70)) ?>
                        <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
                        <span><?php the_time('d F, Y'); ?></span>
                    </div>
                <?php endwhile; ?>
            </ul>
            			 
            <?php
            echo $after_widget;

            wp_reset_postdata();

        endif;
    }
}

function my_recent_widget_registration() {
    unregister_widget('WP_Widget_Recent_Posts');
    register_widget('My_Recent_Posts_Widget');
}

add_action('widgets_init', 'my_recent_widget_registration');


function prometium_incl() {

    wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/assets/libs/bootstrap/css/bootstrap.min.css', array(), null );
    wp_enqueue_style( 'font-awesome', get_template_directory_uri() . '/assets/libs/font-awesome/css/font-awesome.min.css', array(), null );
    wp_enqueue_style( 'jquery-ui', get_template_directory_uri() . '/assets/libs/jquery-ui/jquery-ui.min.css', array(), null );
    wp_enqueue_style( 'slick', get_template_directory_uri() . '/assets/libs/slick/slick.css', array(), null );
    wp_enqueue_style( 'wpb-google-fonts', 'https://fonts.googleapis.com/css?family=Montserrat:400,500,600%7CRaleway:400,600%7CWork+Sans:400,500%7CVarela+Round', false );
    wp_enqueue_style( 'prometium-style', get_stylesheet_uri(),array(), null );
    
    wp_enqueue_script( 'jquery', get_template_directory_uri() . '/assets/libs/jquery/jquery.js' , array(), null , true );
    wp_enqueue_script( 'jqueryui', get_template_directory_uri() . '/assets/libs/jquery-ui/jquery-ui.min.js' , array(), null , true );
    wp_enqueue_script( 'slick', get_template_directory_uri() . '/assets/libs/slick/slick.min.js' , array(), null , true );
    wp_enqueue_script( 'visible', get_template_directory_uri() . '/assets/libs/visible/visible.js' , array(), null , true );
    wp_enqueue_script( 'prometium', get_template_directory_uri() . '/assets/js/main.js' , array(), null , true );
    wp_localize_script( 'prometium', 'register_ajax', array( 'ajax_url' => admin_url('admin-ajax.php')) );

    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }
}

add_action( 'wp_enqueue_scripts', 'prometium_incl' );

require_once get_template_directory() . '/inc/class-tgm-plugin-activation.php';
add_action( 'tgmpa_register', 'prometium_register_required_plugins' );

/**
 * Register the required plugins for this theme.
 *
 * In this example, we register five plugins:
 * - one included with the TGMPA library
 * - two from an external source, one from an arbitrary source, one from a GitHub repository
 * - two from the .org repo, where one demonstrates the use of the `is_callable` argument
 *
 * The variables passed to the `tgmpa()` function should be:
 * - an array of plugin arrays;
 * - optionally a configuration array.
 * If you are not changing anything in the configuration array, you can remove the array and remove the
 * variable from the function call: `tgmpa( $plugins );`.
 * In that case, the TGMPA default settings will be used.
 *
 * This function is hooked into `tgmpa_register`, which is fired on the WP `init` action on priority 10.
 */

function prometium_register_required_plugins() { 
    /*
     * Array of plugin arrays. Required keys are name and slug.
     * If the source is NOT from the .org repo, then source is also required.
     */
    $plugins = array(
        // This is an example of how to include a plugin bundled with a theme.
        array(
            'name'               => esc_html__('Prometium Plugin' , 'prometium'), // The plugin name.
            'slug'               => 'prometium-plugin', // The plugin slug (typically the folder name).
            'source'             => 'http://previewtheme.com/prometium/wp-content/plugins/prometium-plugin.zip', // The plugin source.
            'required'           => true, // If false, the plugin is only 'recommended' instead of required.
            'version'            => '', // E.g. 1.0.0. If set, the active plugin must be this version or higher. If the plugin version is higher than the plugin version installed, the user will be notified to update the plugin.
            'force_activation'   => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
            'force_deactivation' => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
            'external_url'       => '', // If set, overrides default API URL and points to an external URL.
            'is_callable'        => '', // If set, this callable will be be checked for availability to determine if a plugin is active.
        ),
        array(
            'name'               => esc_html__('Slider Revolution','prometium'), // The plugin name.
            'slug'               => 'revslider', // The plugin slug (typically the folder name).
            'source'             => 'http://previewtheme.com/prometium/wp-content/plugins/revslider.zip', // The plugin source.
            'required'           => true, // If false, the plugin is only 'recommended' instead of required.
            'version'            => '', // E.g. 1.0.0. If set, the active plugin must be this version or higher. If the plugin version is higher than the plugin version installed, the user will be notified to update the plugin.
            'force_activation'   => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
            'force_deactivation' => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
            'external_url'       => '', // If set, overrides default API URL and points to an external URL.
            'is_callable'        => '', // If set, this callable will be be checked for availability to determine if a plugin is active.
        ),
        array(
            'name'               => esc_html__('Visual Composer','prometium'), // The plugin name.
            'slug'               => 'js_composer', // The plugin slug (typically the folder name).
            'source'             => 'http://previewtheme.com/prometium/wp-content/plugins/js_composer.zip', // The plugin source.
            'required'           => true, // If false, the plugin is only 'recommended' instead of required.
            'version'            => '', // E.g. 1.0.0. If set, the active plugin must be this version or higher. If the plugin version is higher than the plugin version installed, the user will be notified to update the plugin.
            'force_activation'   => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
            'force_deactivation' => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
            'external_url'       => '', // If set, overrides default API URL and points to an external URL.
            'is_callable'        => '', // If set, this callable will be be checked for availability to determine if a plugin is active.
        ),
        array(
            'name'               => esc_html__('Contact Form 7','prometium'), // The plugin name.
            'slug'               => 'contact-form-7', // The plugin slug (typically the folder name).
            'source'             => 'https://wordpress.org/plugins/contact-form-7/', // The plugin source.
            'required'           => true, // If false, the plugin is only 'recommended' instead of required.
            'version'            => '', // E.g. 1.0.0. If set, the active plugin must be this version or higher. If the plugin version is higher than the plugin version installed, the user will be notified to update the plugin.
            'force_activation'   => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
            'force_deactivation' => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
            'external_url'       => '', // If set, overrides default API URL and points to an external URL.
            'is_callable'        => '', // If set, this callable will be be checked for availability to determine if a plugin is active.
        )

    );

    /*
     * Array of configuration settings. Amend each line as needed.
     *
     * TGMPA will start providing localized text strings soon. If you already have translations of our standard
     * strings available, please help us make TGMPA even better by giving us access to these translations or by
     * sending in a pull-request with .po file(s) with the translations.
     *
     * Only uncomment the strings in the config array if you want to customize the strings.
     */
    $config = array(
        'id'           => 'prometium',             // Unique ID for hashing notices for multiple instances of TGMPA.
        'default_path' => '',                      // Default absolute path to bundled plugins.
        'menu'         => 'tgmpa-install-plugins', // Menu slug.
        'has_notices'  => true,                    // Show admin notices or not.
        'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
        'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
        'is_automatic' => false,                   // Automatically activate plugins after installation or not.
        'message'      => '',                      // Message to output right before the plugins table.
    );


    tgmpa( $plugins, $config );
}
<html <?php language_attributes(); ?>>
    <head>
        <meta charset="<?php bloginfo('charset'); ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1"/>
        <meta name="description" content="<?php bloginfo('description'); ?>">
        <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
        <?php 
            if(PrometiumOptions::get('favicon_icon') ){
                echo '<link rel="icon" href="'. esc_url(PrometiumOptions::get('favicon_icon')['url']) .'" type="image/x-icon"/>';
            }
        ?>
        <?php wp_head(); ?>
    </head>
    
    <body class="<?php body_class()?>">

        <header <?php if(prometium_get_is_home()){
                echo 'class="homepage"';
            }
            ?>>
            <div id="topbar">
                <div class="container">
                    <div class="extend-wrap">
                        <div class="header-account">                       
                            <?php if (is_user_logged_in()) : ?>
                                <a href="<?php echo wp_logout_url(home_url()); ?>"><?php esc_html_e('Logout', 'prometium'); ?> </a>
                            <?php else : ?>
                                <a href="<?php echo wp_login_url(); ?>"><?php esc_html_e('Login', 'prometium'); ?></a>
                            <?php endif; ?>
                            <?php if(PrometiumOptions::get('get_started_text') && PrometiumOptions::get('get_started_url')): ?>
                                <a href="<?php echo esc_url(PrometiumOptions::get('get_started_url'))?>"><?php echo PrometiumOptions::get('get_started_text')?></a>
                            <?php endif; ?>
                        </div>
                        <div class="logo">
                            <h1>
                                <a href="<?php echo esc_html(home_url('/')); ?>">	
                                    <?php if (PrometiumOptions::get('header_logo')) { ?>
                                        <img src="<?php echo esc_url(PrometiumOptions::get('header_logo')['url']); ?>" alt="Site Logo">
                                    <?php } else { ?>
                                        <img src="<?php echo get_template_directory_uri(); ?>/img/logo.png">
                                    <?php } ?>
                                </a>
                                <?php bloginfo('name'); ?>
                            </h1>
                        </div>
                        <nav class="text-center navbar navbar-toggleable-md navbar-light bg-faded">
                            <button class="navbar-toggler mx-auto" type="button" data-toggle="collapse" data-target="#navbarToggler" aria-controls="navbarToggler" aria-expanded="false" aria-label="Toggle navigation">
                                <span class="navbar-toggler-icon"></span>
                            </button>
                            <div class="collapse navbar-collapse" id="navbarToggler">
                                <?php wp_nav_menu( array( 'theme_location' => 'primary') ); ?>
                            </div>
                        </nav>
                    </div>
                </div>
            </div>
        </header>
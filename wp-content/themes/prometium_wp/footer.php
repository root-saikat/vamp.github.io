
<footer class="<?php echo get_footer_background() ?>">

            <div class="container">
                <div class="row">
                    <?php if(is_active_sidebar('footer-1')) :?>
                        <?php dynamic_sidebar('footer-1'); ?>
                    <?php endif; ?>
                    <?php if(is_active_sidebar('footer-instagram')) :?>
                        <?php dynamic_sidebar('footer-instagram'); ?>
                    <?php endif; ?>
                    <?php 
                        if(prometium_has_contact_info()) :
                    ?>
                        <div class="col-lg-3 col-sm-6 col-12 footer-contact">
                            <h6>CONTACT US</h6>
                            <?php prometium_contact_info(); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <hr>
            <div class="container">
                <div class="row">
                    <div class="col-sm-2 logo">
                        <h1>
                            <a href="<?php echo esc_html(home_url('/')); ?>">	
                                <?php if (PrometiumOptions::get('footer_logo')) { ?>
                                    <img src="<?php echo esc_url(PrometiumOptions::get('footer_logo')['url']); ?>" alt="Site Logo">
                                <?php } else { ?>
                                    <img src="<?php echo get_template_directory_uri(); ?>/img/logo.png">
                                <?php } ?>
                            </a>
                            <?php bloginfo('name'); ?>
                        </h1>
                    </div>
                    <div class="col-sm-7">
                        <p class="copyright text-center">
                            <?php
                                if (PrometiumOptions::get('copyright_text')) { 
                                    echo PrometiumOptions::get('copyright_text');
                                }
                            ?>
                        </p>
                    </div>
                    <div class="col-sm-3">
                        <div class="credit-info">
                            <ul>
                                <?php 
                                    for($i=1;$i<6;$i++){
                                        if (PrometiumOptions::get('card' . $i . 'icon') && PrometiumOptions::get('card'. $i . 'link')) { 
                                            echo '<li>';
                                                echo '<a href="' . PrometiumOptions::get('card'. $i . 'link') . '">';
                                                    echo '<img src="' . esc_url(PrometiumOptions::get('card' . $i . 'icon')['url']) . '">';
                                                echo '</a>';
                                            echo '</li> ';
                                        }
                                    }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <?php wp_footer(); ?>
        </footer>
    </body>
</html>
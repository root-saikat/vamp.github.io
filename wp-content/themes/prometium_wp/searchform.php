<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
    <label>
        <span aria-hidden="false" class="screen-reader-text d-none"><?php echo _x( 'Search for:', 'label', 'prometium' ); ?></span>
        <input type="search" class="search-field blog-search" placeholder="search for something" value="<?php echo get_search_query(); ?>" name="s" />
    </label>
    <button type="submit" class="search-submit d-none"><span class="screen-reader-text"><?php echo _x( 'Search', 'submit button', 'prometium' ); ?></span></button>
</form>
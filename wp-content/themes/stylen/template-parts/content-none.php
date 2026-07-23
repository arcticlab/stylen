<?php ?>
<section class="no-results not-found">
    <header class="page-header">
        <h1 class="page-title"><?php _e( 'Nothing Found', 'stylen' ); ?></h1>
    </header>

    <div class="page-content">
        <?php if ( is_search() ) : ?>
            <p><?php _e( 'No results found. Try different keywords.', 'stylen' ); ?></p>
            <?php get_search_form(); ?>
        <?php else : ?>
            <p><?php _e( 'No content yet. Check back soon.', 'stylen' ); ?></p>
        <?php endif; ?>
    </div>
</section>

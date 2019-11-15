<div>
    <div class="top-gallery">
        <div class="container">
            <?php dynamic_sidebar('smartslider_area_1'); ?>
        </div>
    </div>

    <div class="container-widget">
        <div class="container">
            
            <?php if ( is_active_sidebar( 'top-main' ) ) : ?>

                <?php dynamic_sidebar( 'top-main' ); ?>

            <?php endif; ?>

        </div>
    </div>

    <main class="container-page">

        <h2>
            <span class="main-icon">
                <i class="fas fa-flag fa-2x"></i>
            </span>
            Les derniers résultats
        </h2>
        <hr>
            <div class="container">
                <?php if ( is_active_sidebar( 'top-main-2' ) ) : ?>
                    <?php dynamic_sidebar( 'top-main-2' ); ?>
                <?php endif; ?>
            </div>
        <hr>

        <div class="description">
            <div>
                <span class="main-icon">
                    <i class="fas fa-comment fa-3x"></i>
                </span>
                <?php the_content(); ?>
            </div>
            <div>
                <?php the_post_thumbnail(); ?>
            </div>
        </div>
    </main>
</div>

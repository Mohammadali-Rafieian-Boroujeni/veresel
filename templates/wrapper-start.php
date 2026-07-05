<div class="vsl-carousel">

    <div class="vsl-header">

        <div class="vsl-title">

            <?php echo ! empty( $title ) ? esc_html( $title ) : esc_html__( 'محصولات', 'veresel' ); ?>

        </div>

        <a class="vsl-view-all" href="<?php echo esc_url( $shop_link ); ?>">

            <?php esc_html_e( 'مشاهده همه', 'veresel' ); ?>

        </a>

    </div>

    <div class="vsl-nav vsl-prev">

        <span>&#10095;</span>

    </div>

    <div class="vsl-nav vsl-next">

        <span>&#10094;</span>

    </div>

    <div class="vsl-swiper">

        <div class="swiper-wrapper">
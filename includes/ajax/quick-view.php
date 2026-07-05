<?php

defined('ABSPATH') || exit;

/*
|--------------------------------------------------------------------------
| Product
|--------------------------------------------------------------------------
*/

$product_id = isset($_GET['product_id'])
    ? absint($_GET['product_id'])
    : 0;

$product = wc_get_product($product_id);

if (!$product) {
    wp_die();
}

?>

<div class="vsl-modal active">

    <div class="vsl-modal-overlay"></div>

    <div class="vsl-modal-box">

        <button
            type="button"
            class="vsl-close"
            aria-label="<?php esc_attr_e('بستن', 'veresel'); ?>"
        >
            &times;
        </button>

        <div class="vsl-modal-content">

            <div class="vsl-qv-grid">

                <!-- Image -->
                <div class="vsl-qv-image">

                    <?php
                    echo $product->get_image('large');
                    ?>

                </div>

                <!-- Info -->
                <div class="vsl-qv-info">

                    <h2 class="vsl-qv-title">

                        <?php echo esc_html($product->get_name()); ?>

                    </h2>

                    <?php if (wc_review_ratings_enabled()) : ?>

                        <div class="vsl-qv-rating">

                            <?php
                            echo wc_get_rating_html(
                                $product->get_average_rating()
                            );
                            ?>

                        </div>

                    <?php endif; ?>

                    <div class="vsl-qv-price">

                        <?php
                        echo $product->get_price_html();
                        ?>

                    </div>
                    <div class="vsl-qv-meta">

    <?php if ($product->get_sku()) : ?>

        <div class="vsl-meta-row">

            <strong><?php esc_html_e('کد کالا:', 'veresel'); ?></strong>

            <?php echo esc_html($product->get_sku()); ?>

        </div>

    <?php endif; ?>

    <div class="vsl-meta-row">

        <strong><?php esc_html_e('وضعیت:', 'veresel'); ?></strong>

        <?php

        echo $product->is_in_stock()
            ? esc_html__('موجود', 'veresel')
            : esc_html__('ناموجود', 'veresel');

        ?>

    </div>

    <div class="vsl-meta-row">

        <strong><?php esc_html_e('دسته:', 'veresel'); ?></strong>

        <?php

        echo wc_get_product_category_list(
            $product->get_id(),
            '، '
        );

        ?>

    </div>

</div>

                    <?php if ($product->get_short_description()) : ?>

                        <div class="vsl-qv-description">

                            <?php
                            echo wpautop(
                                wp_kses_post(
                                    $product->get_short_description()
                                )
                            );
                            ?>

                        </div>

                    <?php endif; ?>

                    <div class="vsl-qv-cart">

                        <?php
                        echo do_shortcode(
                            '[add_to_cart id="' .
                            $product->get_id() .
                            '" show_price="false"]'
                        );
                        ?>

                    </div>

                    <p class="vsl-qv-link">

                        <a
                            class="button"
                            href="<?php echo esc_url(get_permalink($product->get_id())); ?>"
                        >

                            <?php esc_html_e('مشاهده صفحه محصول', 'veresel'); ?>

                        </a>

                    </p>

                </div>

            </div>

        </div>

    </div>

</div>

<?php wp_die(); ?>
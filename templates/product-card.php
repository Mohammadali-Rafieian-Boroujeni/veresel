<?php

defined('ABSPATH') || exit;

global $product;

if (!$product || !$product->is_visible()) {
    return;
}

$gallery = $product->get_gallery_image_ids();

?>

<div class="swiper-slide vsl-card">

    <a class="vsl-image" href="<?php the_permalink(); ?>">

        <!-- Badges -->
        <div class="vsl-badges">

            <?php if (!$product->is_in_stock()) : ?>

                <span class="vsl-badge vsl-out">
                    ناموجود
                </span>

            <?php elseif ($product->is_on_sale()) : ?>

                <span class="vsl-badge vsl-sale">

                    <?php

                    $regular = (float) $product->get_regular_price();
                    $sale    = (float) $product->get_sale_price();

                    if ($regular > 0 && $sale > 0) {

                        $percent = round((($regular - $sale) / $regular) * 100);

                        echo '-' . $percent . '%';

                    }

                    ?>

                </span>

            <?php endif; ?>

        </div>

        <!-- Actions -->
        <div class="vsl-actions">

            <button
                type="button"
                class="vsl-action vsl-quick"
                title="مشاهده سریع"
                data-product="<?php echo esc_attr(get_the_ID()); ?>"
            >
                👁
            </button>

            <button
                type="button"
                class="vsl-action vsl-wishlist"
                title="علاقه‌مندی"
            >
                ❤
            </button>

            <button
                type="button"
                class="vsl-action vsl-compare"
                title="مقایسه"
            >
                ⇄
            </button>

        </div>

        <!-- Images -->
        <div class="vsl-image-wrapper">

            <?php

            echo get_the_post_thumbnail(
                get_the_ID(),
                'woocommerce_thumbnail',
                array(
                    'class' => 'vsl-product-image first',
                    'loading' => 'lazy',
                    'decoding' => 'async'
                )
            );

            if (!empty($gallery)) {

                echo wp_get_attachment_image(
                    $gallery[0],
                    'woocommerce_thumbnail',
                    false,
                    array(
                        'class' => 'vsl-product-image second',
                        'loading' => 'lazy',
                        'decoding' => 'async'
                    )
                );

            }

            ?>

        </div>

    </a>

    <div class="vsl-content">

        <h3 class="vsl-title">

            <a href="<?php the_permalink(); ?>">

                <?php the_title(); ?>

            </a>

        </h3>

        <?php if (wc_review_ratings_enabled()) : ?>

            <div class="vsl-rating">

                <?php echo wc_get_rating_html($product->get_average_rating()); ?>

            </div>

        <?php endif; ?>

        <div class="vsl-price">

            <?php echo $product->get_price_html(); ?>

        </div>

        <div class="vsl-cart">

            <?php woocommerce_template_loop_add_to_cart(); ?>

        </div>

    </div>

</div>
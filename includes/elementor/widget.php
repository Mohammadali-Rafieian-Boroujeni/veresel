<?php

defined('ABSPATH') || exit;

/**
 * Elementor widget for the Veresel product carousel.
 *
 * Deliberately thin: every control here maps 1:1 to a VSL_Query /
 * VSL_Shortcode attribute so there is a single source of truth for the
 * carousel query + rendering logic (see includes/core/query.php and
 * includes/core/renderer.php). This widget never re-implements that logic.
 */
class VSL_Elementor_Widget extends \Elementor\Widget_Base
{
    public function get_name()
    {
        return 'veresel_carousel';
    }

    public function get_title()
    {
        return __('کاروسل محصولات ورسل', 'veresel');
    }

    public function get_icon()
    {
        return 'eicon-products';
    }

    public function get_categories()
    {
        return array('veresel');
    }

    public function get_keywords()
    {
        return array('veresel', 'carousel', 'product', 'woocommerce', 'کاروسل', 'محصولات');
    }

    protected function register_controls()
    {
        $this->start_controls_section(
            'vsl_section_query',
            array(
                'label' => __('محصولات', 'veresel'),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            )
        );

        $this->add_control(
            'source',
            array(
                'label'       => __('منبع محصولات', 'veresel'),
                'type'        => \Elementor\Controls_Manager::SELECT,
                'default'     => '',
                'options'     => $this->get_provider_options(),
                'description' => __('پیش‌فرض از فیلترهای پایین (دسته/مرتب‌سازی و...) استفاده می‌کند. یک منبع خاص انتخاب کنید تا از موتور Provider استفاده شود.', 'veresel'),
            )
        );

        $this->add_control(
            'tag',
            array(
                'label'       => __('برچسب‌ها (برای منبع «Tag»)', 'veresel'),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'default'     => '',
                'placeholder' => 'summer, new',
                'condition'   => array('source' => 'tag'),
            )
        );

        $this->add_control(
            'ids',
            array(
                'label'       => __('شناسه محصولات (برای منبع «Manual Selection»)', 'veresel'),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'default'     => '',
                'placeholder' => '12, 34, 56',
                'condition'   => array('source' => 'manual'),
            )
        );

        $this->add_control(
            'title',
            array(
                'label'   => __('عنوان کاروسل', 'veresel'),
                'type'    => \Elementor\Controls_Manager::TEXT,
                'default' => __('محصولات', 'veresel'),
            )
        );

        $this->add_control(
            'category',
            array(
                'label'       => __('دسته‌بندی‌ها', 'veresel'),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'default'     => '',
                'placeholder' => __('اسلاگ دسته‌ها، با کاما جدا کنید', 'veresel'),
                'description' => __('خالی بگذارید برای نمایش همه‌ی دسته‌ها.', 'veresel'),
            )
        );

        $this->add_control(
            'limit',
            array(
                'label'   => __('تعداد محصولات', 'veresel'),
                'type'    => \Elementor\Controls_Manager::NUMBER,
                'default' => 12,
                'min'     => 1,
                'max'     => 50,
            )
        );

        $this->add_control(
            'orderby',
            array(
                'label'   => __('مرتب‌سازی بر اساس', 'veresel'),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'default' => 'date',
                'options' => array(
                    'date'  => __('جدیدترین', 'veresel'),
                    'title' => __('عنوان', 'veresel'),
                    'price' => __('قیمت', 'veresel'),
                    'rand'  => __('تصادفی', 'veresel'),
                ),
            )
        );

        $this->add_control(
            'order',
            array(
                'label'   => __('جهت مرتب‌سازی', 'veresel'),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'default' => 'DESC',
                'options' => array(
                    'DESC' => __('نزولی', 'veresel'),
                    'ASC'  => __('صعودی', 'veresel'),
                ),
            )
        );

        $this->add_control(
            'featured',
            array(
                'label'        => __('فقط محصولات ویژه', 'veresel'),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label_on'     => __('بله', 'veresel'),
                'label_off'    => __('خیر', 'veresel'),
                'return_value' => 'yes',
                'default'      => '',
            )
        );

        $this->add_control(
            'onsale',
            array(
                'label'        => __('فقط محصولات حراجی', 'veresel'),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label_on'     => __('بله', 'veresel'),
                'label_off'    => __('خیر', 'veresel'),
                'return_value' => 'yes',
                'default'      => '',
            )
        );

        $this->add_control(
            'instock',
            array(
                'label'        => __('فقط موجود در انبار', 'veresel'),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label_on'     => __('بله', 'veresel'),
                'label_off'    => __('خیر', 'veresel'),
                'return_value' => 'yes',
                'default'      => '',
            )
        );

        $this->end_controls_section();
    }

    /**
     * Build the {source_id => label} options for the "Products Source"
     * control from every registered Provider (see app/Providers/).
     */
    private function get_provider_options()
    {
        $options = array('' => __('پیش‌فرض (فیلترهای پایین)', 'veresel'));

        if (!class_exists('\Veresel\Providers\ProviderEngine')) {
            return $options;
        }

        foreach (\Veresel\Providers\ProviderEngine::instance()->registry()->all() as $provider) {
            $options[$provider->get_id()] = $provider->get_label();
        }

        return $options;
    }

    protected function render()
    {
        if (!class_exists('VSL_Query') || !class_exists('VSL_Renderer')) {
            return;
        }

        // Belt-and-suspenders: make sure assets are on the page even if the
        // wp_enqueue_scripts detection (post-content / Elementor data scan)
        // didn't already catch this render (e.g. dynamically injected widget).
        if (class_exists('VSL_Assets')) {
            VSL_Assets::enqueue();
        }

        $settings = $this->get_settings_for_display();

        $atts = array(
            'title'    => isset($settings['title']) ? $settings['title'] : '',
            'category' => isset($settings['category']) ? $settings['category'] : '',
            'limit'    => isset($settings['limit']) ? absint($settings['limit']) : 12,
            'orderby'  => isset($settings['orderby']) ? $settings['orderby'] : 'date',
            'order'    => isset($settings['order']) ? $settings['order'] : 'DESC',
            'featured' => (isset($settings['featured']) && 'yes' === $settings['featured']),
            'onsale'   => (isset($settings['onsale']) && 'yes' === $settings['onsale']),
            'instock'  => (isset($settings['instock']) && 'yes' === $settings['instock']),
            'offset'   => 0,
            'source'   => isset($settings['source']) ? $settings['source'] : '',
            'tag'      => isset($settings['tag']) ? $settings['tag'] : '',
            'ids'      => isset($settings['ids']) ? $settings['ids'] : '',
        );

        $atts['shop_link'] = function_exists('wc_get_page_permalink')
            ? wc_get_page_permalink('shop')
            : '';

        if (!empty($atts['source']) && class_exists('\Veresel\Providers\ProviderEngine')) {
            $query = \Veresel\Providers\ProviderEngine::instance()->execute($atts['source'], $atts);
        } else {
            $query = VSL_Query::products($atts);
        }

        // VSL_Renderer already escapes everything it outputs (see templates/).
        echo VSL_Renderer::render($query, $atts); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }
}

<?php
defined('ABSPATH')||exit;
add_action('elementor/widgets/register', function($widgets_manager){
 require_once __DIR__.'/widgets/product-carousel.php';
 if(class_exists('VSL_Elementor_Product_Carousel')){
   $widgets_manager->register(new \VSL_Elementor_Product_Carousel());
 }
});

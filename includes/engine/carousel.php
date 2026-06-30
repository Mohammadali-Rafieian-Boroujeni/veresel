<?php

defined('ABSPATH') || exit;

class VSL_Carousel
{
    public static function config()
    {
        return array(

            'speed' => 450,

            'spaceBetween' => 12,

            'grabCursor' => true,

            'simulateTouch' => true,

            'watchOverflow' => true,

            'breakpoints' => array(

                0 => array(

                    'slidesPerView' => 1.15,
                    'spaceBetween' => 10,

                ),

                480 => array(

                    'slidesPerView' => 2,

                ),

                768 => array(

                    'slidesPerView' => 3,

                ),

                1024 => array(

                    'slidesPerView' => 4,

                ),

                1440 => array(

                    'slidesPerView' => 5,

                ),

            ),

        );
    }
}
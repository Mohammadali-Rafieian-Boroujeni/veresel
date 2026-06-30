<?php
defined('ABSPATH')||exit;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
class VSL_Elementor_Product_Carousel extends Widget_Base{
 public function get_name(){return 'veresel_product_carousel';}
 public function get_title(){return 'Veresel Product Carousel';}
 public function get_icon(){return 'eicon-products';}
 public function get_categories(){return ['general'];}
 protected function register_controls(){
  $this->start_controls_section('content',['label'=>'Content']);
  $this->add_control('category',['label'=>'Category Slug','type'=>Controls_Manager::TEXT]);
  $this->add_control('limit',['label'=>'Products','type'=>Controls_Manager::NUMBER,'default'=>8]);
  $this->add_control('desktop',['label'=>'Desktop Columns','type'=>Controls_Manager::SELECT,'options'=>['3'=>'3','4'=>'4','5'=>'5'],'default'=>'4']);
  $this->add_control('tablet',['label'=>'Tablet Columns','type'=>Controls_Manager::SELECT,'options'=>['2'=>'2','3'=>'3'],'default'=>'2']);
  $this->add_control('mobile',['label'=>'Mobile Columns','type'=>Controls_Manager::SELECT,'options'=>['1'=>'1','1.2'=>'1.2'],'default'=>'1.2']);
  $this->end_controls_section();
 }
 protected function render(){
  $s=$this->get_settings_for_display();
  echo do_shortcode('[veresel category="'.$s['category'].'" limit="'.$s['limit'].'"]');
 }
}

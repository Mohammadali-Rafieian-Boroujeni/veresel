<?php
defined('ABSPATH') || exit;
class VSL_Admin{
 public function __construct(){add_action('admin_menu',[$this,'menu']);}
 public function menu(){
  add_menu_page('Veresel','Veresel','manage_options','veresel',[$this,'dashboard'],'dashicons-images-alt2',56);
  add_submenu_page('veresel','Dashboard','Dashboard','manage_options','veresel',[$this,'dashboard']);
  add_submenu_page('veresel','Carousels','Carousels','manage_options','veresel-carousels',[$this,'carousels']);
  add_submenu_page('veresel','Card Designer','Card Designer','manage_options','veresel-card',[$this,'card']);
  add_submenu_page('veresel','Quick View','Quick View','manage_options','veresel-qv',[$this,'qv']);
  add_submenu_page('veresel','Mobile','Mobile','manage_options','veresel-mobile',[$this,'mobile']);
  add_submenu_page('veresel','Performance','Performance','manage_options','veresel-performance',[$this,'perf']);
  add_submenu_page('veresel','Settings','Settings','manage_options','veresel-settings',[$this,'settings']);
 }
 private function page($title,$body){
 echo '<div class="wrap"><h1>'.$title.'</h1><div style="background:#fff;padding:20px;border:1px solid #ddd;border-radius:8px">';
 echo $body;
 echo '</div></div>';
 }
 public function dashboard(){ $this->page('Veresel Dashboard','<p>Welcome to Veresel 0.8 architecture.</p>');}
  public function card(){ $this->page('Card Designer','Coming soon.');}
 public function qv(){ $this->page('Quick View','Coming soon.');}
 public function mobile(){ $this->page('Mobile Layout','Coming soon.');}
 public function perf(){ $this->page('Performance','Coming soon.');}
 public function settings(){ $this->page('Settings','Global plugin settings.');}


public function carousels(){
    if(isset($_POST['vsl_add'])){
        check_admin_referer('vsl_carousel');
        $items=get_option('vsl_carousels',[]);
        $items[]=[
          'id'=>time(),
          'name'=>sanitize_text_field($_POST['name']),
          'category'=>sanitize_text_field($_POST['category']),
          'limit'=>absint($_POST['limit'])
        ];
        update_option('vsl_carousels',$items);
        echo '<div class="updated"><p>Carousel created.</p></div>';
    }
    if(isset($_POST['vsl_save'])){

        check_admin_referer('vsl_carousel');
        update_option('vsl_default_limit', absint($_POST['limit']));
        update_option('vsl_default_mobile', floatval($_POST['mobile']));
        echo '<div class="updated"><p>Saved.</p></div>';
    }
    $limit=(int)get_option('vsl_default_limit',8);
    $mobile=get_option('vsl_default_mobile','1.2');
    echo '<div class="wrap"><h1>Carousels</h1>';
    $items=get_option("vsl_carousels",[]);
    echo '<h2>Add Carousel</h2><form method="post">';
    wp_nonce_field("vsl_carousel");
    echo '<p><input name="name" placeholder="Name"></p>';
    echo '<p><input name="category" placeholder="Category slug" class="regular-text"></p>';echo '<p><label>Desktop <input name="desktop" type="number" value="4"></label></p>';echo '<p><label>Tablet <input name="tablet" type="number" value="2"></label></p>';echo '<p><label>Mobile <input name="mobileview" value="1.2"></label></p>';
    echo '<p><input name="limit" type="number" value="8"></p>';
    echo '<p><button class="button button-primary" name="vsl_add">Create</button></p></form>';
    if($items){
      echo '<hr><table class="widefat"><tr><th>Name</th><th>Category</th><th>Limit</th><th>Shortcode</th></tr>';
      foreach($items as $c){
        echo '<tr><td>'.esc_html($c['name']).'</td><td>'.esc_html($c['category']).'</td><td>'.intval($c['limit']).'</td><td>[veresel id=&quot;'.intval($c['id']).'&quot;]</td></tr>';
      }
      echo '</table>';
    }
    echo '<form method="post">';
    wp_nonce_field('vsl_carousel');
    echo '<table class="form-table">';
    echo '<tr><th>Default products</th><td><input name="limit" type="number" value="'.esc_attr($limit).'"></td></tr>';
    echo '<tr><th>Mobile slides</th><td><input name="mobile" value="'.esc_attr($mobile).'"></td></tr>';
    echo '</table><p><input class="button button-primary" name="vsl_save" type="submit" value="Save"></p></form></div>';
}

}
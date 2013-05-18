<?php
  /*
    Plugin Name: Category posts content
    Plugin URI: http://www.yensaby.com/Category-posts-content
    Description: ปั๊กอินสำหรับการแสดง หมวดหมู่ เรื่อง และ หน้า โดยใช้ Shortcode [catpc] , และ การกำหนดจำนวนการแสดง. คุณสามารถกำหนด [catpc] ไว้ได้หลากหลาย ส่วนในเพจ. ตัวอย่างเช่น: [catpc argument1=value1 argument2=value2].
    Version: 0.1
    Author: ศิริวัฒน์  ชนะคุณ
    Author URI: http://www.yensaby.com/

    Text Domain:   category-posts-content
    Domain Path:   /languages/
  */


load_plugin_textdomain( 'category-posts-content', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

include 'include/CategoryPostsContentWidget.php';
require_once 'include/CatPCDisplayer.php';

class CategoryPostsContent{

  function catpc_func($atts, $content = null) {
    $atts = shortcode_atts(array(
                             'id' => '0',
                             'name' => '',
                             'orderby' => 'date',
                             'order' => 'desc',
                             'numberposts' => '6',
                             'date' => 'no',
                             'date_tag' => '',
                             'date_class' =>'',
                             'dateformat' => get_option('date_format'),
                             'author' => 'no',
                             'author_tag' =>'',
                             'author_class' => '',
                             'template' => 'default',
                             'excerpt' => 'yes',
                             'excerpt_size' => '55',
                             'excerpt_strip' => 'yes',
                             'excerpt_overwrite' => 'no',
                             'excerpt_tag' =>'',
                             'excerpt_class' =>'',
                             'exclude' => '0',
                             'excludeposts' => '0',
                             'offset' => '0',
                             'tags' => '',
                             'exclude_tags' => '',
                             'content' => 'no',
                             'content_tag' => '',
                             'content_class' => '',
                             'catlink' => 'no',
                             'catlink_string' => '',
                             'catlink_tag' =>'',
                             'catlink_class' => '',
                             'comments' => 'no',
                             'comments_tag' => '',
                             'comments_class' => '',
                             'thumbnail' => 'yes',
                             'thumbnail_size' => 'medium', // 'thumbnail', 'medium', 'large', 'full'
                             'thumbnail_class' => '',
                             'title_tag' => 'h3',
                             'title_class' => '',
                             'post_type' => '',
                             'post_status' => '',
                             'post_parent' => '0',
                             'class' => 'catnew',
                             'customfield_name' => '',
                             'customfield_value' =>'',
                             'customfield_display' =>'',
                             'taxonomy' => '',
                             'categorypage' => '',
                             'morelink' => '',
                             'morelink_class' => '',
                             'posts_morelink' => '',
                             'posts_morelink_class' => '',
                             'year' => '',
                             'monthnum' => '',
                             'search' => ''
                           ), $atts);

    $catpc_displayer = new CatPCDisplayer($atts);
    return $catpc_displayer->display();
  }
}

add_shortcode( 'catpc', array('CategoryPostsContent', 'catpc_func') );


function catpc_meta($links, $file) {
  $plugin = plugin_basename(__FILE__);

  if ($file == $plugin):
    return array_merge(
      $links,
      array( sprintf('<a href="http://www.yensaby.com/Category-posts-content">%s</a>', __('การใช้งาน','category-posts-content')) ),
      array( sprintf('<a href="http://www.yensaby.com/Category-posts-content">%s</a>', __('สนับสนุน','category-posts-content')) ),
      array( sprintf('<a href="https://github.com/yensaby/Category-Posts-Content">%s</a>', __('การพัฒนา','category-posts-content')) )
    );
  endif;

  return $links;
}

add_filter( 'plugin_row_meta', 'catpc_meta', 10, 2 );

function wptuts_styles_with_the_lot()
{
  wp_register_style( 'custom-style', plugins_url( '/css/style.css', __FILE__ ), array(), '20130508', 'all' );
	wp_enqueue_style( 'custom-style' );
}
add_action( 'wp_enqueue_scripts', 'wptuts_styles_with_the_lot' );

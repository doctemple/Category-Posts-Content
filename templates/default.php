<?php
/*
Plugin Name: List Category Posts - Template
Plugin URI: http://www.yensaby.com/wordpress/wp-category-posts-content-plugin/
Description: Template file for Category Post Content Plugin for Wordpress which is used by plugin by argument template=value.php
Version: 0.1
Author: ศิริวัฒน์ ชนะคุณ
Author URI: http://www.yensaby.com
*/

$output = '';
$output .= $this->get_category_link('strong');
$output .= '<ul  >';


foreach ($this->catpc->get_categories_posts() as $single):

    $output .= "<li >";
    $output .= $this->get_post_title($single);
    $output .= $this->get_comments($single);
    $output .= ' ' . $this->get_date($single);
    $output .= $this->get_author($single);
    $output .= $this->get_custom_fields($this->params['customfield_display'], $single->ID);
    $output .= $this->get_thumbnail($single);
    $output .= $this->get_content($single, 'p', 'catpc_content');
    $output .= $this->get_excerpt($single, 'div', 'catpc_excerpt');
    $output .= '</li>';
endforeach;

$output .= '</ul><div class="cleared"></div>';
$this->catpc_output = $output;

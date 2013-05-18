<?php

require_once 'CatPC.php';

class CatPCDisplayer {
  private $catpc;
  private $params = array();
  private $catpc_output;

  public function __construct($atts) {
    $this->params = $atts;
    $this->catpc = new CatPC($atts);
    $this->template();
  }

  public function display(){
    return $this->catpc_output;
  }


  private function template(){
    $tplFileName = null;
    $possibleTemplates = array(
      TEMPLATEPATH.'/category-posts-content/'.$this->params['template'].'.php',
      STYLESHEETPATH.'/category-posts-content/'.$this->params['template'].'.php'
    );

    foreach ($possibleTemplates as $key => $file) :
      if ( is_readable($file) ) :
        $tplFileName = $file;
      endif;
    endforeach;

    if ( !empty($tplFileName) && is_readable($tplFileName) ) :
      require($tplFileName);
    else:
      switch($this->params['template']):
      case "default":
        $this->build_output('ul');
        break;
      case "div":
        $this->build_output('div');
        break;
      default:
        $this->build_output('ul');
        break;
      endswitch;
    endif;
  }

  private function build_output($tag){
    $this->catpc_output .= $this->get_category_link('strong');
    $this->catpc_output .= '<' . $tag;

    if (isset($this->params['class'])):
      $this->catpc_output .= ' class="' . $this->params['class'] . '"';
    endif;
    $this->catpc_output .= '>';
    $inner_tag = ($tag == 'ul') ? 'li' : 'li';

    foreach ($this->catpc->get_categories_posts() as $single) :
      if ( !post_password_required($single) ) :
        $this->catpc_output .= $this->catpc_build_post($single, $inner_tag);
      endif;
    endforeach;

    $this->catpc_output .= '</' . $tag . '><div class="cleared"></div>';

    if (!empty($this->params['morelink'])) :
      $href = 'href="' . get_category_link($this->catpc->get_category_id()) . '"';
      $class = "";
      if (!empty($this->params['morelink_class'])) :
        $class = 'class="' . $this->params['morelink_class'] . '" ';
      endif;
      $readmore = $this->params['morelink'];
      $this->catpc_output .= '<a ' . $href . ' ' . $class . ' >' . $readmore . '</a>';
    endif;
  }

  private function catpc_build_post($single, $tag){
    global $post;
    $class ='';
    if ( $post->ID == $single->ID ):
      $class = " class = current ";
    endif;
    $output = '<'. $tag . $class . '>';

    if (!empty($this->params['title_tag'])):
      if (!empty($this->params['title_class'])):
        $output .= $this->get_post_title($single,
                                         $this->params['title_tag'],
                                         $this->params['title_class']);
      else:
        $output .= $this->get_post_title($single, $this->params['title_tag']);
      endif;
    else:
      $output .= $this->get_post_title($single) . ' ';
    endif;


    if (!empty($this->params['comments_tag'])):
      if (!empty($this->params['comments_class'])):
        $output .= $this->get_comments($single,
                                       $this->params['comments_tag'],
                                       $this->params['comments_class']);
      else:
        $output .= $this->get_comments($single, $this->params['comments_tag']);
      endif;
    else:
      $output .= $this->get_comments($single);
    endif;


    if (!empty($this->params['date_tag'])):
      if (!empty($this->params['date_class'])):
        $output .= $this->get_date($single,
                                   $this->params['date_tag'],
                                   $this->params['date_class']);
      else:
        $output .= $this->get_date($single, $this->params['date_tag']);
      endif;
    else:
      $output .= $this->get_date($single);
    endif;

    if (!empty($this->params['author_tag'])):
      if (!empty($this->params['author_class'])):
        $output .= $this->get_author($single,
                                     $this->params['author_tag'],
                                     $this->params['author_class']);
      else:
        $output .= $this->get_author($single, $this->params['author_tag']);
      endif;
    else:
      $output .= $this->get_author($single);
    endif;


    if (!empty($this->params['customfield_display'])) :
      $output .=
        $this->get_custom_fields($this->params['customfield_display'],
                                 $single->ID);
    endif;

    $output .= $this->get_thumbnail($single);

    if (!empty($this->params['content_tag'])):
      if (!empty($this->params['content_class'])):
        $output .= $this->get_content($single,
                                     $this->params['content_tag'],
                                     $this->params['content_class']);
      else:
        $output .= $this->get_content($single, $this->params['content_tag']);
      endif;
    else:
      $output .= $this->get_content($single);
    endif;

    if (!empty($this->params['excerpt_tag'])):
      if (!empty($this->params['excerpt_class'])):
        $output .= $this->get_excerpt($single,
                                     $this->params['excerpt_tag'],
                                     $this->params['excerpt_class']);
      else:
        $output .= $this->get_excerpt($single, $this->params['excerpt_tag']);
      endif;
    else:
      $output .= $this->get_excerpt($single);
    endif;

    if (!empty($this->params['posts_morelink'])) :
      $href = 'href="'.get_permalink($single->ID) . '"';
      $class = "";
      if (!empty($this->params['posts_morelink_class'])) :
        $class = 'class="' . $this->params['posts_morelink_class'] . '" ';
      endif;
      $readmore = $this->params['posts_morelink'];
      $output .= ' <a ' . $href . ' ' . $class . ' >' . $readmore . '</a>';
    endif;

    $output .= '</' . $tag . '>';
    return $output;
  }


  private function get_author($single, $tag = null, $css_class = null){
    $info = $this->catpc->get_author_to_show($single);
    return $this->assign_style($info, $tag, $css_class);
  }

  private function get_comments($single, $tag = null, $css_class = null){
    $info = $this->catpc->get_comments_count($single);
    return $this->assign_style($info, $tag, $css_class);
  }

  private function get_content($single, $tag = null, $css_class = null){
    $info = $this->catpc->get_content($single);
    return $this->assign_style($info, $tag, $css_class);
  }

  private function get_custom_fields($custom_key, $post_id, $tag = null, $css_class = null){
    $info = $this->catpc->get_custom_fields($custom_key, $post_id);
    return $this->assign_style($info, $tag, $css_class);
  }

  private function get_date($single, $tag = null, $css_class = null){
    $info = $this->catpc->get_date_to_show($single);
    return $this->assign_style($info, $tag, $css_class);
  }

  private function get_excerpt($single, $tag = null, $css_class = null){
    $info = $this->catpc->get_excerpt($single);
    $info = preg_replace('/\[.*\]/', '', $info);
    return $this->assign_style($info, $tag, $css_class);
  }

  private function get_thumbnail($single, $tag = null){
    if ( !empty($this->params['thumbnail_class']) ) :
      $catpcthumb_class = $this->params['thumbnail_class'];
      $info = $this->catpc->get_thumbnail($single, $catpcthumb_class);
    else:
      $info = $this->catpc->get_thumbnail($single);
    endif;

    return $this->assign_style($info, $tag);
  }

  private function get_post_title($single, $tag = null, $css_class = null){
    $info = '<h3><a href="' . get_permalink($single->ID) .
      '" title="'. $single->post_title . '">' .
      apply_filters('the_title', $single->post_title, $single->ID) . '</a></h3>';
    return $this->assign_style($info, $tag, $css_class);
  }

  private function get_category_link($tag = null, $css_class = null){
    $info = $this->catpc->get_category_link();
    return $this->assign_style($info, $tag, $css_class);
  }


  private function assign_style($info, $tag = null, $css_class = null){
    if (!empty($info)):
      if (empty($tag)):
        return $info.'<div class="cleared"></div>';
      elseif (!empty($tag) && empty($css_class)) :
        return '<' . $tag . '>' . $info . '</' . $tag . '><div class="cleared"></div>';
      endif;
      return '<' . $tag . ' class="' . $css_class . '">' . $info . '</' . $tag . '><div class="cleared"></div>';
    endif;
  }
}

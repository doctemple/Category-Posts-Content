<?php

class CatPC{
  private $params = array();
  private $catpc_category_id = 0;
  private $category_param;

  public function __construct($atts) {
    $this->params = $atts;
    $this->get_catpc_category();
    $this->set_catpcparameters();
  }


  private function set_catpcparameters(){
    if (is_array($this->catpc_category_id)):
      $args = array('category__and' => $this->catpc_category_id);
    else:
      $args = array('cat'=> $this->catpc_category_id);
    endif;

    $args = array_merge($args, array(
      'numberposts' => $this->params['numberposts'],
      'orderby' => $this->params['orderby'],
      'order' => $this->params['order'],
      'offset' => $this->params['offset']
    ));


    if( $this->catpcnot_empty('excludeposts') ):
      $args['exclude'] = $this->params['excludeposts'];
      if (strpos($args['exclude'], 'this') !== FALSE) :
        $args['exclude'] = $args['exclude'] .
          ",". $this->catpcget_current_post_id();
      endif;
    endif;


    if($this->catpcnot_empty('post_type')):
      $args['post_type'] = $this->params['post_type'];
    endif;

    if($this->catpcnot_empty('post_status')):
      $args['post_status'] = $this->params['post_status'];
    endif;

    if($this->catpcnot_empty('post_parent')):
      $args['post_parent'] = $this->params['post_parent'];
    endif;

    if($this->catpcnot_empty('year')):
      $args['year'] = $this->params['year'];
    endif;

    if($this->catpcnot_empty('monthnum')):
      $args['monthnum'] = $this->params['monthnum'];
    endif;

    if($this->catpcnot_empty('search')):
      $args['s'] = $this->params['search'];
    endif;



    if( $this->catpcnot_empty('customfield_value') ):
      $args['meta_key'] = $this->params['customfield_name'];
      $args['meta_value'] = $this->params['customfield_value'];
    endif;


    if(is_user_logged_in()):
      $args['post_status'] = array('publish','private');
    endif;

    if ( $this->catpcnot_empty('exclude_tags') ):
      $excluded_tags = explode(",", $this->params['exclude_tags']);
      $tag_ids = array();
      foreach ( $excluded_tags as $excluded):
        $tag_ids[] = get_term_by('slug', $excluded, 'post_tag')->term_id;
      endforeach;
      $args['tag__not_in'] = $tag_ids;
    endif;


    if ( $this->catpcnot_empty('taxonomy') && $this->catpcnot_empty('tags') ):
      $args['tax_query'] = array(array(
                               'taxonomy' => $this->params['taxonomy'],
                               'field' => 'slug',
                               'terms' => explode(",",$this->params['tags'])
                                 ));
    elseif ( !empty($this->params['tags']) ):
      $args['tag'] = $this->params['tags'];
    endif;

    $this->catpccategories_posts = get_posts($args);
  }

  private function catpcnot_empty($param){
    if ( ( isset($this->params[$param]) ) &&
         ( !empty($this->params[$param]) ) &&
         ( $this->params[$param] != '0' ) &&
         ( $this->params[$param] != '') ) :
      return true;
    else:
      return false;
    endif;
  }


  private function catpcget_current_post_id(){
    global $post;
    return $post->ID;
  }


  private function get_catpc_category(){
    if ( $this->catpcnot_empty('categorypage') &&
         $this->params['categorypage'] == 'yes' ||
         $this->params['id'] == -1):
      $this->catpc_category_id = $this->catpcget_current_category();
    elseif ( $this->catpcnot_empty('name') ):
      if (preg_match('/\+/', $this->params['name'])):
        $categories = array();
        $cat_array = explode("+", $this->params['name']);
        foreach ($cat_array as $category) :
          $id = $this->get_category_id_by_name($category);
          $categories[] = $id;
        endforeach;
        $this->catpc_category_id = $categories;

      elseif (preg_match('/,/', $this->params['name'])):
        $categories = '';
        $cat_array = explode(",", $this->params['name']);

        foreach ($cat_array as $category) :
          $id = $this->get_category_id_by_name($category);
          $categories .= $id . ",";
        endforeach;

        $this->catpc_category_id = $categories;

      else:
        $this->catpc_category_id = $this->get_category_id_by_name($this->params['name']);
      endif;
    elseif ( isset($this->params['id']) && $this->params['id'] != '0' ):
      if (preg_match('/\+/', $this->params['id'])):
        $this->catpc_category_id = explode("+", $this->params['id']);
      else:
        $this->catpc_category_id = $this->params['id'];
      endif;
    endif;
  }

  public function catpcget_current_category(){
    $category = get_category( get_query_var( 'category' ) );
    if(isset($category->errors) && $category->errors["invalid_term"][0] == "Empty Term"):
      global $post;
      $categories = get_the_category($post->ID);
      return $categories[0]->cat_ID;
    endif;
    return $category->cat_ID;
  }


  private function get_category_id_by_name($cat_name){

    $term = get_term_by('slug', $cat_name, 'category');
    if (!$term):
      $term = get_term_by('name', $cat_name, 'category');
    endif;

    return ($term) ? $term->term_id : 0;
  }

  public function get_category_id(){
      return $this->catpc_category_id;
  }

  public function get_categories_posts(){
    return $this->catpccategories_posts;
  }


  public function get_category_link(){
    if($this->params['catlink'] == 'yes' && $this->catpc_category_id != 0):
      $cat_link = get_category_link($this->catpc_category_id);
      $cat_title = get_cat_name($this->catpc_category_id);

      return '<a href="' . $cat_link . '" title="' . $cat_title . '">' .
        ($this->params['catlink_string'] !== '' ? $this->params['catlink_string'] : $cat_title) . '</a>';
    else:
      return null;
    endif;
  }


  public function get_custom_fields($custom_key, $post_id){
    if($this->params['customfield_display'] != ''):
      $catpccustoms = '';

      $custom_key = trim($custom_key);

      $custom_array = explode(",", $custom_key);

      $custom_fields = get_post_custom($post_id);

      foreach ($custom_array as $something) :
        $my_custom_field = $custom_fields[$something];
        if (sizeof($my_custom_field) > 0 ):
          foreach ( $my_custom_field as $key => $value ) :
            $catpccustoms .= "<div class=\"lcp-customfield\">" .
              $something. " : " . $value . "</div>";
          endforeach;
        endif;
      endforeach;

      return $catpccustoms;

    else:
      return null;
    endif;
  }

  public function get_comments_count($single){
    if (isset($this->params['comments']) &&
        $this->params['comments'] == 'yes'):
      return ' (' . $single->comment_count . ')';
    else:
      return null;
    endif;
  }

  public function get_author_to_show($single){
    if ($this->params['author']=='yes'):
      $catpcuserdata = get_userdata($single->post_author);
      return $catpcuserdata->display_name;
    else:
      return null;
    endif;
  }



  public function get_date_to_show($single){
    if ($this->params['date']=='yes'):
      return  get_the_time($this->params['dateformat'], $single);
    else:
      return null;
    endif;
  }

  public function get_content($single){
    if (isset($this->params['content']) &&
        $this->params['content'] =='yes' &&
        $single->post_content):

      $catpccontent = $single->post_content;

      $catpccontent = apply_filters('the_content', $catpccontent);
      $catpccontent = str_replace(']]>', ']]&gt', $catpccontent);
      return $catpccontent;
    else:
      return null;
    endif;
  }

  public function get_excerpt($single){
    if ($this->params['excerpt']=='yes' &&
        !($this->params['content']=='yes' &&
        $single->post_content) ):

      if($single->post_excerpt && $this->params['excerpt_overwrite'] != 'yes'):
        return $catpcexcerpt = $this->catpctrim_excerpt($single->post_excerpt);
      endif;

      return $catpcexcerpt = $this->catpctrim_excerpt($single->post_content);
    else:
      return null;
    endif;
  }

  private function catpctrim_excerpt($text = ''){
    $excerpt_length = intval($this->params['excerpt_size']);

    $text = strip_shortcodes($text);
    $text = apply_filters('the_content', $text);
    $text = str_replace(']]>',']]&gt;', $text);

    if( $this->catpcnot_empty('excerpt_strip') &&
        $this->params['excerpt_strip'] == 'yes'):
      $text = strip_tags($text);
    endif;

    $words = explode(' ', $text, $excerpt_length + 1);
    if(count($words) > $excerpt_length) :
      array_pop($words);
      array_push($words, '...');
      $text = implode(' ', $words);
    endif;
    return $text."<div class=\"cleared\"></div>";
  }

  public function get_thumbnail($single, $catpcthumb_class = null){
    if ($this->params['thumbnail']=='yes'):
      $catpcthumbnail = '';
      if ( has_post_thumbnail($single->ID) ):

        if ( in_array( $this->params['thumbnail_size'],
                       array('thumbnail', 'medium', 'large', 'full')
             )):
          $catpcthumb_size = $this->params['thumbnail_size'];

        elseif ($this->params['thumbnail_size']):
          $catpcthumb_size = explode(",", $this->params['thumbnail_size']);
        else:
          $catpcthumb_size = 'thumbnail';
        endif;

        $catpcthumbnail = '<a href="' . get_permalink($single->ID).'">';

        $catpcthumbnail .= get_the_post_thumbnail(
          $single->ID,
          $catpcthumb_size,
          ($catpcthumb_class != null) ? array('class' => $catpcthumb_class ) : null
        );
        $catpcthumbnail .= '</a>';
      endif;
      return $catpcthumbnail;

    else:
      return null;
    endif;
  }
}

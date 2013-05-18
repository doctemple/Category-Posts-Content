<?php

  $default = array (
                    'title' => '',
                    'categoryid' => '',
                    'limit' => '',
                    'orderby'=>'',
                    'order'=>'',
                    'show_date'=>'',
                    'show_author'=>'',
                    'show_excerpt'=>'',
                    'excerpt_size' =>'',
                    'exclude'=>'',
                    'excludeposts'=>'',
                    'thumbnail' =>'',
                    'offset'=>'',
                    'show_catlink'=>'',
                    'morelink' =>''
                    );
  $instance = wp_parse_args( (array) $instance, $default);

  $title = strip_tags($instance['title']);
  $limit = strip_tags($instance['limit']);
  $orderby = strip_tags($instance['orderby']);
  $order = strip_tags($instance['order']);
  $showdate = strip_tags($instance['show_date']);
  $showauthor = strip_tags($instance['show_author']);
  $exclude = strip_tags($instance['exclude']);
  $excludeposts = strip_tags($instance['excludeposts']);
  $offset = strip_tags($instance['offset']);
  $showcatlink = strip_tags($instance['show_catlink']);
  $categoryid = strip_tags($instance['categoryid']);
  $showexcerpt = strip_tags($instance['show_excerpt']);
  $excerptsize = strip_tags($instance['excerpt_size']);
  $thumbnail = strip_tags($instance['thumbnail']);
  $thumbnail_size = strip_tags($instance['thumbnail_size']);
  $morelink = strip_tags($instance['morelink']);

?>

<p>
  <label for="<?php echo $this->get_field_id('title'); ?>">
    <?php _e("หัวข้อ", 'category-posts-content')?>
  </label>
  <br/>
  <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>"
    name="<?php echo $this->get_field_name('title'); ?>" type="text"
    value="<?php echo esc_attr($title); ?>" />
</p>

<p>
  <label for="<?php echo $this->get_field_id('categoryid'); ?>">
    <?php _e("หมวดหมู่", 'category-posts-content')?>
  </label>
  <br/>
  <select id="<?php echo $this->get_field_id('categoryid'); ?>" name="<?php echo $this->get_field_name('categoryid'); ?>">
    <?php
      $categories=  get_categories();
      $option = '<option value="-1"';
      if ($categoryid == -1) :
        $option .= ' selected = "selected" ';
      endif;
      $option .= '">' . "หมวดหมู่ ทั้งหมด" . '</option>';
      echo $option;

      foreach ($categories as $cat) :
        $option = '<option value="' . $cat->cat_ID . '" ';
        if ($cat->cat_ID == $categoryid) :
          $option .= ' selected = "selected" ';
        endif;
        $option .=  '">';
        $option .= $cat->cat_name;
        $option .= '</option>';
        echo $option;
      endforeach;
    ?>
  </select>
</p>

<p>
  <label><?php _e("ขนาดรูป", 'category-posts-content')?>: </label><br/>
  <input type="checkbox" <?php checked( (bool) $instance['thumbnail'], true ); ?>
    name="<?php echo $this->get_field_name( 'thumbnail'); ?>" /> <?php _e("Thumbnail - size", 'category-posts-content')?>
    <select id="<?php echo $this->get_field_id('thumbnail_size'); ?>"
      name="<?php echo $this->get_field_name( 'thumbnail_size' ); ?>" type="text">
      <option value='thumbnail'>ย่อ</option>
      <option value='medium'>ปานกลาง</option>
      <option value='large'>ใหญ่</option>
      <option value='full'>เต็ม</option>
    </select>
</p>

<p>
  <input class="checkbox"  type="checkbox"
    <?php checked( (bool) $instance['show_date'], true ); ?>
    name="<?php echo $this->get_field_name( 'show_date' ); ?>" />
  <?php _e("วันที่", 'category-posts-content')?>
</p>
<p>
  <input class="checkbox" input type="checkbox"
    <?php checked( (bool) $instance['show_author'], true ); ?>
    name="<?php echo $this->get_field_name( 'show_author' ); ?>" />
  <?php _e("ผู้เขียน", 'category-posts-content')?>
</p>
<p>
  <input class="checkbox" input type="checkbox"
    <?php checked( (bool) $instance['show_catlink'], true ); ?>
    name="<?php echo $this->get_field_name( 'show_catlink' ); ?>" />
  <?php _e("ลิงค์หมวดหมู่", 'category-posts-content')?>
</p>
<p>
  <input class="checkbox" input type="checkbox"
    <?php checked( (bool) $instance['show_excerpt'], true ); ?>
      name="<?php echo $this->get_field_name( 'show_excerpt' ); ?>" />
  <?php _e("เนื้อหาย่อ", 'category-posts-content')?>
</p>
<p>
  <label for="<?php echo $this->get_field_id('excerpt_size'); ?>">
    <?php _e("ขนาดเนื้อหาย่อ", 'category-posts-content')?>:
  </label>
  <br/>
  <input class="widefat" id="<?php echo $this->get_field_id('excerpt_size'); ?>"
    name="<?php echo $this->get_field_name('excerpt_size'); ?>" type="text"
    value="<?php echo esc_attr($excerptsize); ?>" />
</p>
<p>
  <label for="<?php echo $this->get_field_id('morelink'); ?>">
    <?php _e("ลิงค์ เพิ่มเติม", 'category-posts-content')?>:
  </label>
  <br/>
  <input class="widefat" id="<?php echo $this->get_field_id('morelink'); ?>"
    name="<?php echo $this->get_field_name('morelink'); ?>" type="text"
    value="<?php echo esc_attr($morelink); ?>" />
</p>

<p>
  <label for="<?php echo $this->get_field_id('limit'); ?>">
    <?php _e("จำนวนเรื่อง", 'category-posts-content')?>
  </label>
  <br/>
  <input size="2" id="<?php echo $this->get_field_id('limit'); ?>"
    name="<?php echo $this->get_field_name('limit'); ?>" type="text"
    value="<?php echo esc_attr($limit); ?>" />
</p>

<p>
  <label for="<?php echo $this->get_field_id('offset'); ?>">
    <?php _e("Offset", 'category-posts-content')?>: <br/>
      <input size="2" id="<?php echo $this->get_field_id('offset'); ?>"
        name="<?php echo $this->get_field_name('offset'); ?>" type="text"
        value="<?php echo esc_attr($offset); ?>" />
  </label>
</p>

<p>
  <label for="<?php echo $this->get_field_id('orderby'); ?>">
    <?php _e("เรียงตาม", 'category-posts-content')?>
  </label> <br/>
    <select  id="<?php echo $this->get_field_id('orderby'); ?>"
      name="<?php echo $this->get_field_name('orderby'); ?>" type="text" >
      <?php $catpcorders = array("date" => __("วันที่", "category-posts-content"),
                                "title" => __("หัวข้อ", "category-posts-content"),
                                "author" => __("ผู้เขียน", "category-posts-content"),
                                "rand" => __("สุ่ม", "category-posts-content"));
      foreach ($catpcorders as $key=>$value):
        $option = '<option value="' . $key . '" ';
        if ($orderby == $key):
          $option .= ' selected = "selected" ';
        endif;
        $option .=  '>';
        echo $option;
        _e($value, 'category-posts-content');
        echo '</option>';
      endforeach;
    ?>
  </select>
</p>

<p>
  <label for="<?php echo $this->get_field_id('order'); ?>">
    <?php _e("รูปแบบการจัดเรียง", 'category-posts-content')?>
  </label>
  <br/>
  <select id="<?php echo $this->get_field_id('order'); ?>"
    name="<?php echo $this->get_field_name('order'); ?>" type="text">
    <option value='desc' <?php if($order == 'desc'): echo "selected: selected"; endif;?>>
      <?php _e("มากไปน้อย", 'category-posts-content')?>
    </option>
    <option value='asc' <?php if($order == 'asc'): echo "selected: selected"; endif; ?>>
      <?php _e("น้อยไปมาก", 'category-posts-content')?>
    </option>
  </select>
</p>

<p>
  <label for="<?php echo $this->get_field_id('exclude'); ?>">
    <?php _e("หมวดหมู่ ที่ไม่เอา (id's)", 'category-posts-content')?>
  </label>
  <br/>
  <input id="<?php echo $this->get_field_id('exclude'); ?>"
    name="<?php echo $this->get_field_name('exclude'); ?>" type="text"
    value="<?php echo esc_attr($exclude); ?>" />
</p>

<p>
  <label for="<?php echo $this->get_field_id('excludeposts'); ?>">
    <?php _e("เรื่อง ที่ไม่เอา (id's)", 'category-posts-content')?>
  </label>
  <br/>
  <input id="<?php echo $this->get_field_id('excludeposts'); ?>"
    name="<?php echo $this->get_field_name('excludeposts'); ?>" type="text"
    value="<?php echo esc_attr($excludeposts); ?>" />
</p>

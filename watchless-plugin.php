
<?php
/**
* Plugin Name: Watchless Plugin
* Plugin URI: 
* Description: Plugin that tests my shortcodes
* Author: Badr Al-Attiyat & Ayman AbuShaban
* Author URIL:
* Version: 1.0
*/


//Base code for how to complete this was supplied from lecture. 
// Based on our understanding, we modified this code to become a weekly archives widget with similar options. 
//Our shortcodes provide a variety of unique options in terms of aethetics for our users. 


class BAWeeklyArchivesWidget extends WP_Widget {
  public function __construct() {
  $widget_ops = array(
    'classname' => 'widget_archive',
    'description' => __( 'A weekly archive of your site&#8217;s Posts.') );
    parent::__construct('weekly_archives', __('Weekly Archives', 'B&A'), $widget_ops);
  }

  public function widget( $args, $instance ) {
    
    $c = ! empty( $instance['count'] ) ? '1' : '0'; 
    $d = ! empty( $instance['dropdown'] ) ? '1' : '0';
    $title = apply_filters('widget_title', empty($instance['title']) ? __('Weekly Archives',  'B&A') : $instance['title'], $instance, $this->id_base); 

echo $args['before_widget']; 
    if ( $title ) {
      echo $args['before_title'] . $title . $args['after_title'];
    } ?>
<ul>
    <?php 

    if ( $d ) {
      $dropdown_id = "{$this->id_base}-dropdown-{$this->number}";
    ?>
      <label class="screen-reader-text" for="<?php echo esc_attr( $dropdown_id ); ?>"><?php echo $title; ?></label>
      <select id="<?php echo esc_attr( $dropdown_id ); ?>" name="archive-dropdown" onchange='document.location.href=this.options[this.selectedIndex].value;'> 
        <?php $dropdown_args = apply_filters( 'widget_archives_dropdown_args', array(
            'type'            => 'weekly',
            'format'          => 'option',
            'show_post_count' => $c       ) ); ?> 
          <option value="<?php echo __( 'Select Week! :)', 'B&A' ); ?>"><?php echo __( 'Select Week! :)', 'B&A' ); ?></option>
          <?php wp_get_archives( $dropdown_args ); ?>
        </select>
    <?php } else { 
      wp_get_archives( apply_filters( 'widget_archives_args', 
        array(
          'type'            => 'weekly',
          'show_post_count' => $c
      ) ) ); 
    ?>
</ul>
<?php
    }
  // No brace here! 
  //    
    echo $args['after_widget']; 
  }

  public function form( $instance ) {
    $instance = wp_parse_args( (array) $instance, array( 'title' => '', 'count' => 0, 'dropdown' => '') );
    $title = strip_tags($instance['title']);
    $count = $instance['count'] ? 'checked="checked"' : '';
    $dropdown = $instance['dropdown'] ? 'checked="checked"' : '';
?>


    <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label> <input   class="widefat" 
        id="<?php echo $this->get_field_id('title'); ?>" 
        name="<?php echo $this->get_field_name('title'); ?>" 
        type="text" value="<?php echo esc_attr($title); ?>" />
    </p>
    <p><input class="checkbox"
          type="checkbox" <?php echo $dropdown; ?> 
        id="<?php echo $this->get_field_id('dropdown'); ?>" 
        name="<?php echo $this->get_field_name('dropdown'); ?>" /> 
    <label for="<?php echo $this->get_field_id('dropdown'); ?>"><?php _e('Display as dropdown'); ?></label><br/>
    <input class="checkbox" 
      type="checkbox" <?php echo $count; ?>
      id="<?php echo $this->get_field_id('count'); ?>" 
      name="<?php echo $this->get_field_name('count'); ?>" /> 
    <label for="<?php echo $this->get_field_id('count'); ?>"><?php _e('Show post counts'); ?></label></p>
  <?php 

  }

  public function update( $new_instance, $old_instance ) {
    $instance = $old_instance;
    $new_instance = wp_parse_args( (array) $new_instance, 
      array( 'title' => '', 'count' => 0, 'dropdown' => '') );
    $instance['title'] = strip_tags($new_instance['title']);
    $instance['count'] = $new_instance['count'] ? 1 : 0;
    $instance['dropdown'] = $new_instance['dropdown'] ? 1 : 0;

    return $instance;
  }

}

add_action( 'widgets_init', function(){
     register_widget( 'BAWeeklyArchivesWidget' );
});


function register_plugin_styles() {
	wp_register_style( 'my-plugin', plugins_url( 'watchless-plugin/css/plugin-style.css' ) );
	wp_enqueue_style( 'my-plugin' );
}



//shortcodes for a night mode option and googlemaps
 function night_mode($atts, $content = null) {
  ?>
   <script type="text/javascript">
         function changeBGC(color, text){
            document.body.style.backgroundColor = color;
            document.body.style.color = text;
          }
    </script>
    
    <img src="http://i.imgur.com/TQw3HrJ.gif" alt= "Black Background" width="50" type="button" value='Black Background' onclick="changeBGC('#000000', '#ffffff');return false" />
    <img src="http://i.imgur.com/AfdKTXM.gif" alt= "White Background" width="50"type='button' value='White Background' onclick="changeBGC('#ffffff','#000000');return false" />
  <?php return ;
}
function c_googlemaps($atts, $content = null) {
   extract(shortcode_atts(array(
      "width" => '640',
      "height" => '480',
      "source" => ''
   ), $atts));
   return '<iframe width="'.$width.'" height="'.$height.'" src="'.$source.'&output=embed" ></iframe>';
}
add_shortcode("googlemap", "c_googlemaps");
add_shortcode("night_mode", "night_mode");
add_action( 'wp_enqueue_scripts', 'register_plugin_styles' );

?>
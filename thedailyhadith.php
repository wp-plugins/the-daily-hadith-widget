<?php
/*
Plugin Name: The Daily Hadith Widget
Plugin URI: http://iknowledge.islamicnature.com/extras.php
Description: A widget that displays a different hadith a day
Author: Umar Sheikh
Author URI: http://www.indezinez.com
Version: 1.4
*/

add_action('widgets_init', 'load_daily_hadith');

function load_daily_hadith(){
  if(function_exists('register_widget')){
    register_widget('Daily_Hadith');
	}
}

class Daily_Hadith extends WP_Widget {

	function Daily_Hadith(){
	
		$widget_ops = array('classname' => 'dailyhadith', 'description' => __('A widget that displays a different hadith a day', 'dailyhadith'));
		$control_ops = array('width' => 300, 'height' => 350, 'id_base' => 'daily-hadith');
		$this->WP_Widget('daily-hadith', __('Daily Hadith', 'dailyhadith'), $widget_ops, $control_ops);
	
	}

	function widget($args, $instance){
	
		extract($args);
		$title = apply_filters('widget_title', $instance['title']);
		echo $before_widget;
		if($title){
			echo $before_title.$title.$after_title;
		}
		if(function_exists('file_get_contents')){
      if($file = @file_get_contents('http://iknowledge.islamicnature.com/dh_script.php')){
        $file = preg_replace('/document\.write\(\'(.*)\'\)\;/','$1',$file);
        echo '<p>'.str_replace("\'","'",$file).'</p>';
      }else{
        echo '
        <p>
        <script type="text/javascript" src="http://iknowledge.islamicnature.com/dh_script.php"></script>
        <noscript>Please enable javascript. <a href="http://iknowledge.islamicnature.com">iKnowledge</a></noscript>
        </p>
        ';
      }
		}else{
      echo '
      <p>
      <script type="text/javascript" src="http://iknowledge.islamicnature.com/dh_script.php"></script>
      <noscript>Please enable javascript. <a href="http://iknowledge.islamicnature.com">iKnowledge</a></noscript>
      </p>
      ';
		}
		echo $after_widget;
		
	}

	function update($new_instance, $old_instance){
	
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		return $instance;
		
	}

	function form($instance){

		$defaults = array('title' => __('Daily Hadith', 'dailyhadith'));
		$instance = wp_parse_args((array) $instance, $defaults); ?>

		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'hybrid'); ?></label>
			<input id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
		</p>

	<?php
	}
}

?>
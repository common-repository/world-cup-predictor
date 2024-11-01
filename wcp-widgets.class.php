<?php
/**
 * Widgets
 * 
 * Widget class for Wordpress plugin World Cup Predictor
 * 
 * @package WorldCup
 * @version $Id: wcp-widgets.class.php 1647773 2017-04-29 00:49:48Z landoweb $
 * @author landoweb
 * Copyright Landoweb Programador, 2014
 * 
 */
 
class WorldCupRankingWidget extends WP_Widget {
	
	/**
	 * Constructor.
	 */
	public function __construct() {
		$widget_ops = array('classname' => 'widget_'.WCP_TD, 'description' => __('Display Leaders of WCP', WCP_TD) );
		parent::__construct(WCP_TD, __('WCP Top Ranking', WCP_TD), $widget_ops);		
	}
	
	function widget($args, $instance) {
		// prints the widget
		extract($args);
		$title = apply_filters('widget_title', $instance['title']);
		$max = $instance['max'];
		$url = $instance['url'];
		$name = $instance['name'];
		$avatar = $instance['avatar'];
		$highlight = $instance['highlight'];
		
		echo $before_widget;
		if ( $title )
		echo $before_title . $title . $after_title;
		
		require_once(dirname(__FILE__).'/wcp-reports.class.php');
		$r = new WorldCupReport();
		
		echo $r->user_ranking($max, $avatar, $highlight);
		
		if (!empty($url) && !empty($name)) {
			echo '<p><a href="'.$url.'">'.$name.'</a></p>';
		}
		
		echo $after_widget;
	}
	
	function update($new_instance, $old_instance) {
		//save the widget
		$instance = $old_instance;
		$new_instance = wp_parse_args((array) $new_instance, array( 'title' => ''));
		$instance['title'] = strip_tags($new_instance['title']);
		$new_instance = wp_parse_args((array) $new_instance, array( 'max' => 10));
		$instance['max'] = strip_tags($new_instance['max']);
		$new_instance = wp_parse_args((array) $new_instance, array( 'url' => ''));
		$instance['avatar'] = strip_tags($new_instance['avatar']);
		$new_instance = wp_parse_args((array) $new_instance, array( 'avatar' => 0));
		$instance['url'] = strip_tags($new_instance['url']);
		$new_instance = wp_parse_args((array) $new_instance, array( 'name' => 'Full ranking'));
		$instance['name'] = strip_tags($new_instance['name']);
		$new_instance = wp_parse_args((array) $new_instance, array( 'highlight' => ''));
		$instance['highlight'] = strip_tags($new_instance['highlight']);
		
		return $instance;
	}
	
	function form($instance) {
		
		global $wpdb;
		
		//widgetform in backend
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'max' => 10, 'avatar' => 0, 'url' => '', 'name' => 'Full ranking', 'highlight' => '') );
		$title = $instance['title'];
		$max = $instance['max'];
		if (!is_numeric($max)) $max = 10;
		$avatar = $instance['avatar'];
		$url = $instance['url'];
		$name = $instance['name'];
		$highlight = $instance['highlight'];
		
?>
		<p><?php _e('Display Rankings.', WCP_TD); ?></p>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', WCP_TD); ?>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></label></p>
		
		<p><label for="<?php echo $this->get_field_id('max'); ?>"><?php _e('Max rankings to show:', WCP_TD); ?>
		<input class="widefat" id="<?php echo $this->get_field_id('max'); ?>" name="<?php echo $this->get_field_name('max'); ?>" type="text" value="<?php echo esc_attr($max); ?>" /></label></p>
		
		<p><label for="<?php echo $this->get_field_id('avatar'); ?>"><?php _e('Show Avatars:', WCP_TD); ?>
		<input class="widefat" id="<?php echo $this->get_field_id('avatar'); ?>" name="<?php echo $this->get_field_name('avatar'); ?>" type="checkbox" value="1" <?php echo $avatar ? ' checked ' : ''; ?>" /></label></p>
		
		<p><label for="<?php echo $this->get_field_id('highlight'); ?>"><?php _e('CSS for current user:', WCP_TD); ?>
		<input class="widefat" id="<?php echo $this->get_field_id('highlight'); ?>" name="<?php echo $this->get_field_name('highlight'); ?>" type="text" value="<?php echo esc_attr($highlight); ?>" /></label></p>
		
		<p><label for="<?php echo $this->get_field_id('url'); ?>"><?php _e('Full rankings page URL:', WCP_TD); ?>
		<input class="widefat" id="<?php echo $this->get_field_id('url'); ?>" name="<?php echo $this->get_field_name('url'); ?>" type="text" value="<?php echo esc_attr($url); ?>" /></label></p>
		
		<p><label for="<?php echo $this->get_field_id('name'); ?>"><?php _e('Full rankings link name:', WCP_TD); ?>
		<input class="widefat" id="<?php echo $this->get_field_id('name'); ?>" name="<?php echo $this->get_field_name('name'); ?>" type="text" value="<?php echo esc_attr($name); ?>" /></label></p>
<?php
	}
}

class WorldCupPredictionsWidget extends WP_Widget {
	
	/**
	 * Constructor.
	 */	
	public function __construct() {
		$widget_ops = array('classname' => 'widget_user_'.WCP_TD, 'description' => __('Display User Predictions of WCP', WCP_TD) );
		parent::__construct(WCP_TD.'user', __('WCP User Predictions', WCP_TD), $widget_ops);
	}
	
	function widget($args, $instance) {
		// prints the widget
		extract($args);
		$title = apply_filters('widget_title', $instance['title']);
		$url = $instance['url'];
		$name = $instance['name'];
		$results = $instance['results'];
		
		if(is_user_logged_in()) {
			
			echo $before_widget;
			if ( $title )
			echo $before_title . $title . $after_title;
			
			require_once(dirname(__FILE__).'/wcp-reports.class.php');
			$r = new WorldCupReport();
			
			echo $r->widget_predictions($results);
			
			if (!empty($url) && !empty($name)) {
				echo '<p><a href="'.$url.'">'.$name.'</a></p>';
			}
			
			echo $after_widget;
		}
	}
	
	function update($new_instance, $old_instance) {
		//save the widget
		$instance = $old_instance;
		$new_instance = wp_parse_args((array) $new_instance, array( 'title' => ''));
		$instance['title'] = strip_tags($new_instance['title']);
		$new_instance = wp_parse_args((array) $new_instance, array( 'avatar' => 0));
		$instance['url'] = strip_tags($new_instance['url']);
		$new_instance = wp_parse_args((array) $new_instance, array( 'name' => 'My Predictions'));
		$instance['name'] = strip_tags($new_instance['name']);
		$new_instance = wp_parse_args((array) $new_instance, array( 'results' => 0));
		$instance['results'] = strip_tags($new_instance['results']);
		return $instance;
	}
	
	function form($instance) {
		
		global $wpdb;
		
		//widgetform in backend
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'url' => '', 'name' => 'User Predictions', 'results' => 0) );
		$title = $instance['title'];
		$url = $instance['url'];
		$name = $instance['name'];
		$results = $instance['results'];
?>
		<p><?php _e('Display User Predictions.', WCP_TD); ?></p>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', WCP_TD); ?>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></label></p>
		
		<p><label for="<?php echo $this->get_field_id('url'); ?>"><?php _e('My predictions page URL:', WCP_TD); ?>
		<input class="widefat" id="<?php echo $this->get_field_id('url'); ?>" name="<?php echo $this->get_field_name('url'); ?>" type="text" value="<?php echo esc_attr($url); ?>" /></label></p>
		
		<p><label for="<?php echo $this->get_field_id('name'); ?>"><?php _e('My predictions link name:', WCP_TD); ?>
		<input class="widefat" id="<?php echo $this->get_field_id('name'); ?>" name="<?php echo $this->get_field_name('name'); ?>" type="text" value="<?php echo esc_attr($name); ?>" /></label></p>		
		
		<p><label for="<?php echo $this->get_field_id('results'); ?>"><?php _e('Show Results:', WCP_TD); ?>
		<input class="widefat" id="<?php echo $this->get_field_id('results'); ?>" name="<?php echo $this->get_field_name('results'); ?>" type="checkbox" value="1" <?php echo $results ? ' checked ' : ''; ?> /></label></p>
<?php
	}
}

class WorldCupStandingsWidget extends WP_Widget {
	
	/**
	 * Constructor.
	 */	
	public function __construct() {
		$widget_ops = array('classname' => 'widget_standings_'.WCP_TD, 'description' => __('Display Championship Standings', WCP_TD) );
		parent::__construct(WCP_TD.'standings', __('WCP Standings', WCP_TD), $widget_ops);
	}
	
	function widget($args, $instance) {
		// prints the widget
		extract($args);
		$title = apply_filters('widget_title', $instance['title']);
		
		echo $before_widget;
		if ( $title )
		echo $before_title . $title . $after_title;
		
		require_once(dirname(__FILE__).'/wcp-reports.class.php');
		$r = new WorldCupReport();
		
		echo $r->group_tables(0, false, '100%', true);
		
		echo $after_widget;
	}
	
	function update($new_instance, $old_instance) {
		//save the widget
		$instance = $old_instance;
		$new_instance = wp_parse_args((array) $new_instance, array( 'title' => ''));
		$instance['title'] = strip_tags($new_instance['title']);
		return $instance;
	}
	
	function form($instance) {
		
		global $wpdb;
		
		//widgetform in backend
		$instance = wp_parse_args( (array) $instance, array( 'title' => '') );
		$title = $instance['title'];
?>
		<p><?php _e('Display Championship Standings.', WCP_TD); ?></p>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', WCP_TD); ?>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></label></p>
<?php
	}
}

class WorldCupMyPointsWidget extends WP_Widget {
	
	/**
	 * Constructor.
	 */
	public function __construct() {
		$widget_ops = array('classname' => 'widget_points_'.WCP_TD, 'description' => __('Show the total points of current user', WCP_TD) );
		parent::__construct(WCP_TD.'points', __('WCP My Points', WCP_TD), $widget_ops);
	}

	function widget($args, $instance) {
		// prints the widget
		extract($args);
		$title = apply_filters('widget_title', $instance['title']);

		if(is_user_logged_in()) {
			
			echo $before_widget;
			if ( $title )
				echo $before_title . $title . $after_title;
			
			require_once(dirname(__FILE__).'/wcp-reports.class.php');
			$r = new WorldCupReport();
			
			printf(__('You currently have %d points', WCP_TD), $r->my_points());
			
			echo $after_widget;
		}
	}

	function update($new_instance, $old_instance) {
		//save the widget
		$instance = $old_instance;
		$new_instance = wp_parse_args((array) $new_instance, array( 'title' => ''));
		$instance['title'] = strip_tags($new_instance['title']);
		
		return $instance;
	}
	
	function form($instance) {

		global $wpdb;
		
		//widgetform in backend
		$instance = wp_parse_args( (array) $instance, array( 'title' => '') );
		$title = $instance['title'];
		
		?>
		<p><?php _e('Show Total Points.', WCP_TD); ?></p>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', WCP_TD); ?>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></label></p>
<?php
	}
}
<?php
/*
Plugin Name: List Box
Description: List Box is a plugin show posts in widget.
Plugin URI: https://wordpress.org/plugins/list-box
Author: PB One
Author URI: http://photoboxone.com/
Version: 1.0.1
License: GPL-2.0+
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/
defined('ABSPATH') or die;

define('WP_LB_URL_AUTHOR', 'http://photoboxone.com/' ); 

/**
 * Custom Widget for displaying ...
 *
 * @link http://codex.wordpress.org/Widgets_API#Developing_Widgets
 *
 * @package Posts
 * @subpackage Posts
 * @since Posts 1.0
 */

class List_Box_Widget extends WP_Widget {

	/**
	 * Constructor.
	 *
	 * @since PB 1.0
	 *
	 * @return FB_Posts_Widget
	 */
	public function __construct() {
		parent::__construct( 'widget_list_box', 'List Box', array(
			'classname'   => 'widget_list_box',
			'description' => 'Use this widget to list your posts.'
		) );
	}
	
	/**
	 * Deal with the settings when they are saved by the admin.
	 *
	 * Here is where any validation should happen.
	 *
	 * @since PB 1.0
	 *
	 * @param array $new_instance New widget instance.
	 * @param array $instance     Original widget instance.
	 * @return array Updated widget instance.
	 */
	function update( $new_instance, $instance ) {
		$instance['title'] 		= empty( $new_instance['title'] ) ? '' : esc_attr($new_instance['title']);
		$instance['show_title'] = empty( $new_instance['show_title'] ) ? 0 : absint($new_instance['show_title']);
		$instance['category']  	= empty( $new_instance['category'] ) ? 0 : absint( $new_instance['category'] );
		$instance['displays']  	= empty( $new_instance['displays'] ) ? 3 : absint($new_instance['displays']);
		$instance['orderby'] 	= empty( $new_instance['orderby'] ) ? '' : esc_attr( $new_instance['orderby'] );
		$instance['order'] 		= empty( $new_instance['order'] ) ? 'DESC' : esc_attr( $new_instance['order'] );
		$instance['meta_value'] = empty( $new_instance['meta_value'] ) ? '' : esc_attr( $new_instance['meta_value'] );
		
		return $instance;
	}

	/**
	 * Display the form for this widget on the Widgets page of the Admin area.
	 *
	 * @since PB 1.0
	 *
	 * @param array $instance
	 */
	public function form( $instance ) {
		$title  		= empty( $instance['title'] ) ? '' : esc_attr( $instance['title'] );
		$show_title 	= empty( $instance['show_title'] ) ? 0 : absint( $instance['show_title'] );
		$category 		= empty( $instance['category'] ) ? 0 : absint( $instance['category'] );
		$displays  		= empty( $instance['displays'] ) ? 3 : absint($instance['displays']);
		$orderby  		= empty( $instance['orderby'] ) ? '' : esc_attr( $instance['orderby'] );
		$order  		= empty( $instance['order'] ) ? '' : esc_attr( $instance['order'] );
		$meta_value  	= empty( $instance['meta_value'] ) ? '' : esc_attr( $instance['meta_value'] );
		
		?>
			<p><label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title:' ); ?></label></p>
			<p><input id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" value="<?php echo esc_attr( $title ); ?>" /></p>
			<p><input type="checkbox" value="1" id="<?php echo esc_attr( $this->get_field_id( 'show_title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_title' ) ); ?>" <?php echo $show_title?'checked':'';?> /><label for="<?php echo esc_attr( $this->get_field_id( 'show_title' ) ); ?>"><?php _e( 'Show Title' ); ?></label></p>			
			<p><label for="<?php echo esc_attr( $this->get_field_id( 'category' ) ); ?>"><?php _e( 'Category' ); ?>:</label></p>
			<p><select id="<?php echo esc_attr( $this->get_field_id( 'category' ) ); ?>" class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'category' ) ); ?>">
				<option value="0"><?php _e('All');?></option>
			<?php foreach(get_categories() as $item):?>
				<option value="<?php echo $item->term_id;?>" <?php selected( $item->term_id, $category);?>><?php echo $item->cat_name;?></option>
			<?php endforeach;?>
			</select></p>
			<p><label for="<?php echo $this->get_field_id( 'displays' ); ?>"><?php _e( 'Display' ); ?>:</label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'displays' ); ?>" name="<?php echo $this->get_field_name( 'displays' ); ?>" type="text" value="<?php echo $displays; ?>"  style="width:70px;"/></p>
			<p><label for="<?php echo esc_attr( $this->get_field_id( 'orderby' ) ); ?>"><?php _e( 'Order By:' ); ?></label>
			<p><select id="<?php echo esc_attr( $this->get_field_id( 'orderby' ) ); ?>" class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'orderby' ) ); ?>">
			<?php foreach( array( '' => 'Default', 'menu_order' => 'Order', 'title' => 'Title', 'meta_value' => 'Meta Value', 'meta_value_num' => 'Meta Value Number' ) as $k => $v):?>
				<option value="<?php echo $k;?>" <?php selected( $k, $orderby);?>><?php echo $v;?></option>
			<?php endforeach;?>
			</select></p>
			<p><label for="<?php echo esc_attr( $this->get_field_id( 'order' ) ); ?>"><?php _e( 'Order:' ); ?></label>
			<p><select id="<?php echo esc_attr( $this->get_field_id( 'order' ) ); ?>" class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'order' ) ); ?>">
			<?php foreach( array( 'DESC', 'ASC' ) as $v):?>
				<option value="<?php echo $v;?>" <?php selected( $v, $order);?>><?php echo $v;?></option>
			<?php endforeach;?>
			</select></p>
			<p><label for="<?php echo esc_attr( $this->get_field_id( 'meta_value' ) ); ?>"><?php _e( 'Meta Value:' ); ?></label></p>
			<p><input id="<?php echo esc_attr( $this->get_field_id( 'meta_value' ) ); ?>" class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'meta_value' ) ); ?>" value="<?php echo esc_attr( $meta_value ); ?>" /></p>
			
		<?php
	}
	
	/**
	 * Output the HTML for this widget.
	 *
	 * @access public
	 * @since PB 1.0
	 *
	 * @param array $args     An array of standard parameters for widgets in this theme.
	 * @param array $instance An array of settings for this widget instance.
	 */
	public function widget( $args, $instance ) {
		
		$title  		= apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
		$show_title 	= empty( $instance['show_title'] ) ? 0 : absint( $instance['show_title'] );
		$category 		= empty( $instance['category'] ) ? 0 : absint( $instance['category'] );
		$displays 		= empty( $instance['displays'] ) ? 3 : absint($instance['displays']);
		$orderby  		= empty( $instance['orderby'] ) ? '' : esc_attr( $instance['orderby'] );
		$order  		= empty( $instance['order'] ) ? 'DESC' : esc_attr( $instance['order'] );
		$meta_value		= empty( $instance['meta_value'] ) ? '' : esc_attr( $instance['meta_value'] );
		
		echo $args['before_widget'];
		
		if ( $title != '' && $show_title ) :
			echo $args['before_title'].$title.$args['after_title'];
		endif;
		
		
		$args = array(
			'category' 			=> $category,
			'posts_per_page' 	=> $displays,
		);
		
		if( $orderby!='' ){
			$args['orderby'] = $orderby;
			$args['order'] = $order;
			
			if( preg_match('/meta_value/i',$orderby) ){
				$args['meta_key'] = $meta_value;
			}
		}
		
		// var_dump($args);
		
		$posts = get_posts($args);
		
		if( $count = count($posts) ):
			$i = 0;
		?>
			<div class="fb_ants_list clearfix">
				<ul>
					<?php foreach($posts as $p) : $i++; 
						// $url = wp_get_attachment_url( get_post_thumbnail_id($p->ID) ); ?>
					<li class="item-<?php echo $i.($count==$i?' item-last':'')?>">
						<a href="<?php echo get_the_permalink($p->ID); ?>" rel="bookmark"><?php echo $p->post_title; ?></a>
					</li>
					<?php endforeach; ?>
				</ul>
			</div>
		<?php
		endif;
		
		echo $args['after_widget'];
		
	}

}

// setup widget
add_action( 'widgets_init', function(){
	register_widget( 'List_Box_Widget' );
});
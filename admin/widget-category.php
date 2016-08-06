<?php
/**
 * The widget-specific functionality of the plugin.
 *
 * @link       http://www.patricelaurent.net
 * @since      1.0.0
 *
 * @package    Dwn_Repo_Pro
 * @subpackage Dwn_Repo_Pro/admin
 */

/**
 * The widget-specific functionality of the plugin.
 *
 * register widget.
 *
 * @package    Dwn_Repo_Pro
 * @subpackage Dwn_Repo_Pro/admin
 * @author     patrice LAURENT <laurent.patrice@gmail.com>
 */

register_widget('Down_Repo_Widget_Cat');
class Down_Repo_Widget_Cat extends WP_Widget {

	function __construct() {
		// Instantiate the parent object
		parent::__construct( false, __('Downloads Categories','down_repo') );
	}

	function widget( $args, $instance ) {
		// Widget output
		extract($args);
		// Widget options
		if ( array_key_exists( 'title', $instance ) ) {
			$title = apply_filters('widget_title', $instance['title'] ); // Title
		} else {
			$title = '';
		}
		echo $before_widget;
		echo '<div id="dwrp-widget-'.$tax.'-container" class="list-custom-taxonomy-widget">';
		if ( $title ) echo $before_title . $title . $after_title;
		$taxonomy_object = get_taxonomy( 'down_cat' );
		$argsCat = array(
			'show_option_all'    => false,
			'show_option_none'   => '',
			'show_count'         => true,
			'hide_empty'         => true,
			'echo'               => 1,
			'title_li'		=>'',
			'hierarchical'       => true,
			'id'                 => 'dwrp-widget-down_cat',
			'depth'              => 0,
			'taxonomy'           => 'down_cat',
			'hide_if_empty'      => true,
			);
		echo '<ul id="dwrp-widget-down_cat">';
		wp_list_categories($argsCat);
		echo '</ul>';
	}

	function update( $new_instance, $old_instance ) {
		// Save widget options
		$instance = $old_instance;
		$instance['title']  = strip_tags( $new_instance['title'] );
		return $instance;
	}

	function form( $instance ) {
		// Output admin widget options form
		// instance exist? if not set defaults
		if ( $instance ) {
			$title  = $instance['title'];
		} else {
			$title  = '';
		}
		// The widget form
		?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php echo __( 'Title:' ); ?></label>
			<input id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" class="widefat" />
		</p>
		<?php
	}
}

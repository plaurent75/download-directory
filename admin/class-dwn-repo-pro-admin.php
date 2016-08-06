<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://www.patricelaurent.net
 * @since      1.0.0
 *
 * @package    Dwn_Repo_Pro
 * @subpackage Dwn_Repo_Pro/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Dwn_Repo_Pro
 * @subpackage Dwn_Repo_Pro/admin
 * @author     patrice LAURENT <laurent.patrice@gmail.com>
 */
class Dwn_Repo_Pro_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

/**
	* Include Custom Taxonomies
	*
	* @since    1.0.0
	*/
	public function down_repo_taxonomy() {
		include 'custom-category.php';
		include 'custom-tag.php';
	}

	/**
	* Include Custom Post Type
	*
	* @since    1.0.0
	*/
	public function down_repo(){
		include 'post-type.php';
	}

	/**
	* Include Custom Meta Box
	*
	* @since    1.0.0
	*/
	public function down_metabox() {
		include 'metabox.php';
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Dwn_Repo_Pro_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Dwn_Repo_Pro_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/dwn-repo-pro-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Dwn_Repo_Pro_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Dwn_Repo_Pro_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_media();
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/dwn-repo-pro-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Register the required plugin.
	 *
	 * @since    1.0.0
	 */
	public function theme_slug_register_required_plugins(){
		$plugins = array(
			array(
				'name'      => 'WordPress Popular Posts',
				'slug'      => 'wordpress-popular-posts',
				'required'  => false,
				),
			);
		$config = array(
			'id'           => 'down_repo',
			'default_path' => '' ,
			'menu'         => 'tgmpa-install-plugins',
			'parent_slug'  => 'plugins.php',
			'capability'   => 'manage_options',
			'has_notices'  => true,
			'dismissable'  => true,
			'dismiss_msg'  => '',
			'is_automatic' => false,
			'message'      => '',
			'plugin_name'      =>$this->plugin_name
			);
		#tgmpa( $plugins, $config );
	}

	public function widget_sidebar(){
		register_sidebar( array(
			'name'          => __( 'Downloading Page', 'down_repo' ),
			'id'            => 'down_repo_downloading',
			'description'   => __( 'Widgets in this area will be shown on the downloading page.', 'down_repo' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<span class="widgettitle">',
			'after_title'   => '</span>',
			) );
		include 'widget-category.php';
	}

	// Add it to a column in WP-Admin
	function posts_column_views($defaults){
		if('down_repo'==get_post_type()) {
			$defaults['post_views'] = __('Views','down_repo');
		}
		return $defaults;
	}
	/**
	* Notification when version software updated
	*
	* @since    1.0.0
	*/
	function notification_update($meta_id, $post_id, $meta_key, $meta_value){
		if($meta_key=='software_informations_version'){
			global $wpdb;
			$softTitle=get_the_title($post_id );
			$tablename=$wpdb->prefix.'dwrp_alert';
			$checkit = $wpdb->get_results( "SELECT email,identity_key FROM $tablename WHERE post_id='$post_id' " );
			remove_filter( 'the_title', array($plugin_templates,'add_cat_to_title'), 10 );
			foreach ( $checkit as $m )
			{
				$activation_link = add_query_arg( array( 'down_alert' => $m->identity_key, 'down_email' => $m->email ), site_url('/'));
				$to=$m->email;
				$subject=__('New Version for','down_repo').' '.$softTitle;
				$message= __('Dear user,','down_repo')."\n\n";
				$message.=$softTitle.' '.__('have been updated to the release','down_repo')." $meta_value\n";
				$message.=__('You can download it by following this link :','down_repo')."\n";
				$message.=get_permalink($post_id )."\n\n";
				$message.=__('You receive this alerte because your email adress have been added to our updated list.','down_repo')."\n";
				$message.=__('You can unsuscribe from this alert by following this link.','down_repo')."\n";
				$message.=$activation_link."\n";
				$message.="\n\n";
				$message.=__('Your Alert Key: ','down_repo')." ".$m->identity_key." \n";
				$message.=__('Your Alert Email: ','down_repo')." ".$m->email." \n";
				$message.="\n\n";
				$message.=__('Kind Regards','down_repo')."\n";
				$message.=get_bloginfo('name').' '.__('team','down_repo')."\n";
				$message.=get_bloginfo('url')."\n";
				$status = wp_mail($to,$subject,$message);
			}
			add_filter( 'the_title', array($plugin_templates,'add_cat_to_title'), 10 );
		}
		//exit();
	}
	/**
	* Display Admin Notices
	*
	* @since    1.0.0
	*/

	public function admin_notices(){
		if('down_repo'==get_post_type()) {
			?>
			<div class="notice notice-error is-dismissible">
				<p><?php _e( 'Do not update VERSION FIELD if not needed. Updating VERSION FIELD will run the mail alert. All the subscriber for the current software will receive an update alert', 'down_repo' ); ?></p>
			</div>
			<?php
		}
	}
}

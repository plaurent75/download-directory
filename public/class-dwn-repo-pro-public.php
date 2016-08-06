<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://www.patricelaurent.net
 * @since      1.0.0
 *
 * @package    Dwn_Repo_Pro
 * @subpackage Dwn_Repo_Pro/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Dwn_Repo_Pro
 * @subpackage Dwn_Repo_Pro/public
 * @author     patrice LAURENT <laurent.patrice@gmail.com>
 */
class Dwn_Repo_Pro_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Adds a default single view template for a job opening
	 *
	 * @param 	string 		$template 		The name of the template
	 * @return 	mixed 						The single template
	 * @since    1.0.0
	 */
	public function single_cpt_template( $template ) {
		global $post;
		$return = $template;
		if ( $post->post_type == 'down_repo' ) {
			$return =down_repo_get_template( 'single-down_repo' );
		}
		return $return;
	}


	 /**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	 public function enqueue_styles() {

		/**
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Dwn_Repo_Pro_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Dwn_Repo_Pro_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		$cssname=plugin_dir_url( __FILE__ ) . 'css/dwn-repo-pro-public.css';
		$cssname = apply_filters('down_repo_style',$cssname);
		if($cssname) wp_enqueue_style( $this->plugin_name, $cssname, array(), $this->version, 'all' );
		wp_enqueue_style("wp-jquery-ui-dialog");

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Dwn_Repo_Pro_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Dwn_Repo_Pro_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script('jquery-ui-dialog');
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/dwn-repo-pro-public.js', array( 'jquery'), $this->version, false );
		wp_localize_script( $this->plugin_name, 'ajax_alert_object', array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'loadingmessage' => __('Registering in progress, please wait...','down_repo'),
			'post_id'=> get_the_id()
			));
		wp_localize_script( $this->plugin_name, 'ajax_unalert_object', array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'loadingmessage' => __('Unregistering in progress, please wait...','down_repo'),
			));
	}

	/**
	 * Redirect to custom thank you page.
	 *
	 * hooked by down_repo_thank_you
	 *
	 * @since    1.0.0
	 */
	public function get_thank_you_page(){
		do_action( 'down_repo_thank_you', $pageid );
		$link = get_permalink($pageid);
		return $link;
	}

	/**
	 * add meta refresh to downloading page.
	 *
	 *
	 * @since    1.0.0
	 */
	public function add_meta_header(){
		if('down_repo'==get_post_type() && get_query_var(__('downloading','down_repo'))=='get'){
			$redir_time=25;
			$redir_time=apply_filters('down_repo_redirect_time', $redir_time);
			echo'<meta http-equiv="refresh" content="'.$redir_time.';url='.$this->get_thank_you_page().'" />';
		}
	}

	/**
	 * add subscriber for alert from ajax.
	 *
	 *
	 * @since    1.0.0
	 */
	public function add_alert_down(){
		global $wpdb;
		// First check the nonce, if it fails the function will break
		check_ajax_referer( 'ajax-alert-down-nonce', 'security' );
		$email_address=sanitize_email($_POST['email']);
		$post_id=(int) json_decode(sanitize_text_field($_POST['post_id']));
		$post_title=get_the_title($post_id);
		if(is_email( $email_address )){
			$tablename=$wpdb->prefix.'dwrp_alert';
			//Check is this mail already in db for this Software
			$checkit = $wpdb->get_var( "SELECT COUNT(*) FROM $tablename WHERE email='$email_address' AND post_id='$post_id'" );
			if($checkit>0) {
				echo json_encode(array(
					'message'=>__('Your email adress','down_repo').' '.__('is already registered for','down_repo').' '.$post_title
					));
			}else{
				$code = sha1( $email_address . time() );
				$data=array(
					'email'=>$email_address,
					'post_id'=>$post_id,
					'identity_key'=>$code,
					);
				$format=array(
					'%s',
					'%d',
					'%s',
					);
				if($wpdb->insert( $tablename, $data, $format )){
					echo json_encode(array(
						'message'=>__('Your email adress','down_repo').' '.__('is sucessfully added to the update alert for','down_repo').' '.$post_title
						));
				}else{
					//$res=$wpdb->last_error;
					echo json_encode(array(
						'message'=>__('An error occur. Please contact the admin','down_repo')
						));
				}
			}

		}else{
			echo json_encode(array(
				'message'=> __('Error : Invalid email adress', 'down_repo')
				));
		}
		die();
	}

	/**
	 * remove subscriber for alert from ajax.
	 *
	 *
	 * @since    1.0.0
	 */
	public function remove_alert_down(){
		global $wpdb;
		// First check the nonce, if it fails the function will break
		check_ajax_referer( 'ajax-alert-down-nonce', 'security' );
		$subid=(int) sanitize_text_field($_POST['subid']);
		$post_id=(int) sanitize_text_field($_POST['post_id']);
		$key=sanitize_text_field($_POST['key']);
		$mail=sanitize_email($_POST['mail']);
		if(is_email( $mail )){
			if($subid && $post_id && !is_null($post_id) && $key ){
				$tablename=$wpdb->prefix.'dwrp_alert';
				$checkit = $wpdb->get_var( "SELECT id FROM $tablename WHERE id='$subid' AND identity_key='$key' AND email='$mail' AND post_id='$post_id'" );
				if($checkit && $checkit > 0 && $checkit==$subid){
					if($wpdb->delete( $tablename, array( 'id' => $checkit ), array( '%d' ) ))
					{
						echo json_encode(array(
							'message'=> __('Success : you have been unregistered from the update alert for', 'down_repo').' '.get_the_title($post_id ),
							));
					}else{
						echo json_encode(array(
							'message'=> __('Error :informations provided are not valids. Wa are unable to proceed', 'down_repo')
							));
					}
				}

			}
		}else{
			echo json_encode(array(
				'message'=> __('Error :informations provided are not valids. Wa are unable to proceed', 'down_repo')
				));
		}
		die();

	}

}

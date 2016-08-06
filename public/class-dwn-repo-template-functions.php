<?php

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the methods for creating the templates.
 *
 * @link       http://www.patricelaurent.net
 * @since      1.0.0
 *
 * @package    Dwn_Repo_Pro
 * @subpackage Dwn_Repo_Pro/public
 */

class Down_Repo_Template_Functions {

	/**
	 * Private static reference to this class
	 * Useful for removing actions declared here.
	 *
	 * @var 	object 		$_this
 	 */
	private static $_this;

	/**
	 * The post meta data
	 *
	 * @since 		1.0.0
	 * @access 		private
	 * @var 		string 			$meta    			The post meta data.
	 */
	private $meta;

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
	 * @param      string    $version 			The version of this plugin.
	 */

	public function __construct( $plugin_name, $version ) {

		self::$_this = $this;

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	} // __construct()

	/**
	 * display the license taxonomy for current post_id
	 *
	 * @param 		object 		$licenses 		The taxonomy object
	 * @return 		string 		$on_draught 		The html list of license taxonomy
	 */

	public function show_licences($licenses){
		$draught_links = array();
		foreach ($licenses as $term) {
			$draught_links[] = $term->name;
		}
		$on_draught = join( ", ", $draught_links );
		return $on_draught;
	}
	/**
	 * Includes the single down post metadata for info
	 *
	 *
	 * @return 		string 		$meta 		The post metadata
	 */
	public function single_post_info() {
		$licenses=get_the_terms(get_the_ID(),'down_license');
		$version_down=$this->get_meta_soft('software_informations_version');
		$editor=$this->get_meta_soft('software_informations_editor');
		$editorurl=$this->get_meta_soft('software_informations_editor_website');
		$size=$this->get_meta_soft('software_informations_size');
		$meta_info='<div class="dwnrp-meta"><ul class="dwnrp-info">';
		if ( ! empty( $version_down) ){
			$meta_info.= '<li class="dwnrp-version">'.__('Version','download-directory').' : <span>'.$version_down.'</span></li>';
		}
		if ( ! empty( $editor) ){
			$meta_info.= '<li class="dwnrp-editor">'.__('Editor','download-directory').' :  <span>'.$editor.'</span></li>';
		}
		if ( ! empty( $editorurl) ){
			$meta_info.= '<li class="dwnrp-editorurl">'.__('Editor Website','download-directory').' :  <span><a href="'.$editorurl.'" rel="nofollow" target="_blank">'.$this->get_domain($editorurl).'</a></span></li>';
		}
		if ( ! empty( $size) ){
			$meta_info.= '<li class="dwnrp-size">'.__('Download Size','download-directory').' :  <span>'.$size.'</span></li>';
		}
		if ( $licenses && ! is_wp_error( $licenses ) ){

			$meta_info.= '<li class="dwnrp-license">'.__('License','download-directory').' :  <span>'.$this->show_licences($licenses).'</span></li>';
		}
		$meta_info.='</ul></div>';
		return $meta_info;

	} // single_post_info()

	/**
	 * Get Domain Name from URL.
	 *
	 * @param 		string 		$url 		The link url
	 * @return 		string 		regs['domain']; 		The domain of the link
	 * @since    1.0.0
	 */
	public function get_domain($url){
		$pieces = parse_url($url);
		$domain = isset($pieces['host']) ? $pieces['host'] : '';
		if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs)) {
			return $regs['domain'];
		}
		return false;
	}

	/**
	 * Add Root Category to title.
	 *
	 * @param 		string 		$title 		The current post title
	 * @return 		string 		$title 		The  post title
	 * @since    1.0.0
	 */
	public function add_cat_to_title($title){
		if(in_the_loop() && 'down_repo'==get_post_type() && !is_admin() && is_singular( )){
			$current=$title;
			$rootcat=$this->get_root_cat();
			if(get_query_var(__('downloading','download-directory'))=='get') $title=__('Downloading','download-directory').' ';
			else $title=__('Download','download-directory').' ';
			$title.= $current.' '.__('for','download-directory').' '.$rootcat['name'];
		}
		return $title;
	}

	/**
	 * Get Root Category.
	 *
	 * @since    1.0.0
	 * @param 		int 		$post_id 		The current post_id
	 * @param 		int 		$category_id 		the category ID
	 * @return 		array 		name,slug 		Root Category Name & slug
	 */
	public function get_root_cat($post_id='', $category_id='') {
		if(!$post_id) $post_id=get_the_ID();
		$post = get_post($post_id);
		if (!$post) return false;
		if(!$category_id)
		{
			$category = wp_get_object_terms($post->ID, 'down_cat');
			$category_id=$category[0]->term_id;
		}
		$mycat = get_term($category_id, 'down_cat');
		$myparent = $mycat->parent;

		if ($myparent > 0) return $this->get_root_cat($post_id,$myparent);
		else $terms=get_term($mycat->term_id, 'down_cat');

		//var_dump($terms);
		if (!is_wp_error($terms) && !empty($terms) && is_object($terms))
		{
			$taxonomy_slug = $terms->slug;
			$taxonomy_name=$terms->name;
		}else{
			$taxonomy_slug =__( 'main-dowload', 'download-directory' );
		}

		return array("name" =>$taxonomy_name, "slug" => $taxonomy_slug);
	}

	/**
	 * Add Root Category to single permalink.
	 *
	 * @since    1.0.0
	 * @param 		string 		$post_link 		The current permalink
	 * @param 		object 		$id 		post ID
	 * @return 		string 		$post_link 		The  permalink
	 */
	public function down_repo_custom_slug($post_link, $id = 0 )
	{
		if('down_repo'==get_post_type()){
			$root=$this->get_root_cat();
			return str_replace( '%down_cat%' , $root['slug'] , $post_link );
		}
		return $post_link;
	}

	/**
	 * Add rewrite endpoint pour custom post type.
	 *
	 * @since    1.0.0
	 */
	public function dwn_repo_rewrite_endpoint() {
		add_rewrite_endpoint(__('downloading','download-directory'), EP_PERMALINK);
		flush_rewrite_rules();
	}

	/**
	 * Fix query var for downloading page.
	 *
	 * @since    1.0.0
	 * @param 		array 		$vars 		Query Vars
	 * @return 		array 		$vars 		Query vars
	 */
	function rewrite_filter_request( $vars )
	{
		if( isset( $vars[__('downloading','download-directory')] )&& $vars[__('downloading','download-directory')] === '' ) $vars[__('downloading','download-directory')] = 'get';
		return $vars;
	}

	/**
	 * Get custom meta post for post->id.
	 *
	 * @since    1.0.0
	 * @global 		object 		$post 		Post Object
	 * @return 		array 		$meta 		The custom meta post object
	 */
	public function get_meta(){
		global $post;
		$meta = get_post_custom( $post->ID );
		return $meta;
	}

	/**
	 * Get custom meta post from key array.
	 *
	 * @since    1.0.0
	 * @param 		array 		$key 		Post Excerpt Object
	 * @return 		string 		$value 		The custom meta post value
	 */
	public function get_meta_soft($key){
		$meta=$this->get_meta();
		if ( ! empty( $meta[$key][0]) ) {
			return $meta[$key][0];
		}
		return false;
	}
	/**
	 * remove Down repo Excerpt for down_repo single page.
	 *
	 * @since    1.0.0
	 * @param 		object 		$excerpt 		Post Excerpt Object
	 * @return 		string 		$excerpt 		The new excerpt
	 */

	public function remove_excerpt($excerpt){
		if('down_repo'==get_post_type() && is_singular( 'down_repo') && in_the_loop()){
			remove_filter( 'the_content', array($this,'down_excerpt'), 1 );
			$excerpt= $this->down_excerpt($excerpt,1);
		}
		return $excerpt;
	}
	/**
	 * Form Alert.
	 *
	 * @since    1.0.0
	 * @return 		string 		$form 		The html code
	 */
	public function form_alert(){
		ob_start();
		include down_repo_get_template( 'down_repo-form-mail');
		$form = ob_get_clean();
		ob_flush();
		return $form;
	}
	/**
	 * Edit Excerpt for down_repo.
	 *
	 * @since    1.0.0
	 * @param 		object 		$excerpt 		Post Excerpt Object
	 * @param 		string 		$is_execerpt 		Option to set if we are in exceprt or in content object
	 * @return 		string 		$excerpt 		The new excerpt
	 */
	public function down_excerpt($excerpt,$is_execerpt=0){
		global $post;
		if(!isset($is_execerpt)) $is_execerpt=0;
		if('down_repo'==get_post_type() && get_query_var(__('downloading','download-directory'))!='get' && is_singular( )){
			$current=$excerpt;
			$label=
			$version_down=$this->get_meta_soft('software_informations_version');
			$editor=$this->get_meta_soft('software_informations_editor');
			$editorurl=$this->get_meta_soft('software_informations_editor_website');
			$editSpace='';
			$title='';
			$resume='<p class="dwrp-excerpt">'.$post->post_excerpt.'</p>';
			$dlbutton='<div class="btn-group btn-group-lg">'.$this->download_button(get_permalink($post->ID ).__('downloading','download-directory')).$this->be_alerted().'</div>';

			if ( ! empty( $version_down) ) {
				$version_down=' <span>'.$version_down.'</span>';
			}
			if ( empty( $editor) && !empty($editorurl) ) {
				$editor=__('Editor','download-directory');
			}
			if(has_post_thumbnail($post->ID )){
				remove_filter('post_thumbnail_html',  array($this,'no_thumb'), 99, 5);
				$title.= '<p class="alignleft">'.get_the_post_thumbnail($post->ID,'thumbnail',array('class'=>'alignleft')).'</p>';
				add_filter('post_thumbnail_html',  array($this,'no_thumb'), 99, 5);
			}
			$title.='<h2 class="dwnrp-title">'.$post->post_title.''.$version_down.'</h2>';
			if(!empty($editor)){
				$editSpace.='<p class="dwrp-subtitle">';
				if(!empty($editorurl)){
					$editSpace .= '<a href="'.$editorurl.'" rel="noreferrer nofollow" target="_blank">'.$editor.'</a>';
				}else{
					$editSpace .=$editor;
				}
				$editSpace.='</p>';
			}
			if($is_execerpt==1) {
				$resume='<p class="dwrp-excerpt">'.$current.'</p>';
				$current=null;
				$dlbutton2=null;

			}
			add_filter( 'the_content', 'wpautop' );

			$viewed= '<p class="dwrp-count">'.__('Downloaded','download-directory').' '.$this->getPostViews(get_the_ID()).' '.__('time(s)','download-directory').'</p>';
			$excerpt='<div class="down_ex_wrap">'.$title.$editSpace.$viewed.'</div>'.$resume.$dlbutton.$current.$this->form_alert();
		}
		return $excerpt;
	}
	/**
	 * Create the alert button.
	 *
	 * @return   string   $content html code of the alert button.
	 * @since    1.0.0
	 */

	public function be_alerted(){
		$content ='<button  type="button" class="dwrp-alerte btn btn-danger">'.__('Alert when update available', 'download-directory').'</button>';
		return $content;
	}
	/**
	 * Create the download button.
	 *
	 * @param   string   $url  the desired url.
	 * @param   string   $label  the desired label.
	 * @return   string   html code of the download button.
	 * @since    1.0.0
	 */
	public function download_button($url='',$label='') {
		global $post;
		if($url=='') $url=get_permalink($post->ID ).__('downloading','download-directory');
		if($label=='') $label=__('Download Now','download-directory');
		return '<a class="btn btn-success" href="'.$url.'">'.$label.'</a>';
	}
	/**
	 * Get Mirors Links
	 *
	 * @return   string   $content  return the miror link.
	 * @since   1.0.0
	 */
	public function get_mirror(){
		if($this->get_meta_soft('software_informations_mirror-1') || $this->get_meta_soft('software_informations_mirror-2') || $this->get_meta_soft('software_informations_mirror-3')){
			$content= '<div class="dwrp-subauto">'.__('You can also try a mirror link :','download-directory').' <ul class="dwrp-mirror">';
			if($this->get_meta_soft('software_informations_mirror-1')) {
				$content.= '<li class="dwrp-mirror-main"><a href="'.$this->get_meta_soft('software_informations_mirror-1').'" rel="noreferrer nofollow" target="_blank">'.__('Mirror','download-directory').' 1</a></li>';
			}
			if($this->get_meta_soft('software_informations_mirror-2')) {
				$content.= '<li class="dwrp-mirror-main"><a href="'.$this->get_meta_soft('software_informations_mirror-2').'" rel="noreferrer nofollow" target="_blank">'.__('Mirror','download-directory').' 2</a></li>';
			}
			if($this->get_meta_soft('software_informations_mirror-3')) {
				$content.= '<li class="dwrp-mirror-main"><a href="'.$this->get_meta_soft('software_informations_mirror-3').'" rel="noreferrer nofollow" target="_blank">'.__('Mirror','download-directory').' 3</a></li>';
			}
			$content.= '</ul></div>';
			return $content;
		}
		return false;
	}
	/**
	 * add content
	 *
	 * @param   string   $content  The  current content.
	 * @return   string   $content  return the new content.
	 * @since   1.0.0
	 */
	public function filter_content($content){
		global $post;
		if('down_repo'==get_post_type() && get_query_var(__('downloading','download-directory'))=='get'){

			$alertlink=apply_filters('down_repo_alertlink','#alertlink');
			$dnwLink=$this->get_meta_soft('software_informations_download-link');
			$editorLink=$this->get_meta_soft('software_informations_editor_website');
			$content = '<div class="downloadProject"></div>';
			$content .='<p class="dwrp-auto">'.__('Your download for','download-directory').' '.$post->post_title.' '.__('will begin in <span id="downloadall_cpt">5</span> seconds','download-directory').'</p>';
			$content .= '<p class="dwrp-subauto">'.__('If it does not start automatically, you can click', 'download-directory');
			$content .= ' <a class="dwrp-downloading-link" href="'.$dnwLink.'" rel="noreferrer nofollow" target="_blank">'.__('this link','download-directory').'</a></p>';
			$content .= $this->get_mirror();
			$content .= '<p class="dwrp-subauto">'.__('If the download link is broken, you should visit', 'download-directory');
			$content .= ' <a class="dwrp-downloading-link" href="'.$editorLink.'" rel="noreferrer nofollow" target="_blank">'.__('editor website','download-directory').'</a></p>';
			$content .='<div class="btn-group btn-group-lg">';
			remove_filter( 'the_title',array($this,'add_cat_to_title'), 10 );
			$content .=$this->download_button(get_permalink($post->ID ),__('Back to description','download-directory'));
			add_filter( 'the_title',array($this,'add_cat_to_title'), 10 );
			$content .=$this->be_alerted().'</div>';
			echo $content;
			include down_repo_get_template( 'down_repo-form-mail');
			include down_repo_get_template( 'down_repo-downloading');
			return null;

		}elseif('down_repo'==get_post_type() && get_query_var(__('downloading','download-directory'))!='get' && is_singular( 'down_repo')){
			$content=$this->single_post_info().$content;
		}
		return $content;
	}
	/**
	 * Disable post_thumbnail
	 *
	 * @param   string   $html  The  html code.
	 * @return   string   $html  return the html code.
	 * @since   1.0.0
	 */
	public function no_thumb($html, $post_id, $post_thumbnail_id, $size, $attr ){
		if('down_repo'==get_post_type() && is_singular('down_repo' )) return null;
		else return $html;
	}
	/**
	 * Display downloaded times
	 *
	 * @param   string   $postID  The  post_id.
	 * @return   string   $count  The downloaded counter.
	 * @since   1.0.0
	 */
	public function getPostViews($postID){
		$count_key = 'down_repo_count';
		$count = get_post_meta($postID, $count_key, true);
		if($count==''){
			delete_post_meta($postID, $count_key);
			add_post_meta($postID, $count_key, '0');
			return "0";
		}
		return $count;
	}
	/**
	 * Count downloaded times
	 *
	 * @param   string   $postID  The  post_id.
	 * @since   1.0.0
	 */
	public function setPostViews($postID) {
		$count_key = 'down_repo_count';
		$count = get_post_meta($postID, $count_key, true);
		if($count==''){
			$count = 0;
			delete_post_meta($postID, $count_key);
			add_post_meta($postID, $count_key, '0');
		}else{
			$count++;
			update_post_meta($postID, $count_key, $count);
		}
	}
	/**
	 * Add Downloaeded times colone to post in admin area
	 *
	 * @param   string   $column_name  Thecolumn name page.
	 * @param   string   $id  The current post_id.
	 * @since   1.0.0
	 */
	public function posts_custom_column_views($column_name, $id){
		if($column_name === 'post_views'){
			echo $this->getPostViews($id);
		}
	}
	/**
	 * Check if its a wp referer AND redrirect if its not the case
	 *
	 * @since   1.0.0
	 */
	public function check_referer(){
		global $post;
		if('down_repo'==get_post_type() && get_query_var(__('downloading','download-directory'))=='get'){
			if(!wp_get_referer()) {
				wp_redirect( get_permalink($post->ID ));
				exit();
			}
		}
	}
	/**
	 * Render unsubscription URL
	 *
	 * @since   1.0.0
	 * @param   array   $posts  The current (pseudo) page.
	 * @return  string   The current (pseudo) page, rendered.
	 */
	public function render_manage_alert_page($posts){
		$post_id=$posts['post_id'];
		$key=$posts['authcode'];
		$mail=$posts['email_addr'];
		$soft=get_the_title($post_id);
		$content= '<h3>'.$soft.'</h3>';
		if(isset($posts['subscriber_id']) && $posts['subscriber_id']!=''){
			$content.='<p>'.__('You are currently receiving alert when update is available for','download-directory').' '.$soft."\n";
			$content.=__('You can unsuscribe from this alert if you want','download-directory').' </p>';
			$content.='<form action="remove_alert_down" method="post" id="oldAlertForm">';
			$content.= wp_nonce_field( 'ajax-alert-down-nonce', 'security',true,false );
			$content.='<input type="hidden" value="'.$posts['subscriber_id'].'" id="subid">';
			$content.='<input type="hidden" value="'.$post_id.'" id="post_id">';
			$content.='<input type="hidden" value="'.$key.'" id="key">';
			$content.='<input type="hidden" value="'.$mail.'" id="mail">';
			$content.='<button type="submit" class="btn btn-warning">'.__('Unsuscribe Now','download-directory').' </button>';
			$content.='</form><div id="down-repo-feedback"></div>';
		}else{
			$content.=__('Invalid link. We are unable to retrieve the record for your registration. Please check and verify your registration key and email','download-directory');
		}

		return $content;
	}
	/**
	 * Process unsubscription URL, set subscriber as confirmed to
	 *
	 * @since   1.0.0
	 * @param   array   $posts  The current (pseudo) page.
	 * @return  array   The current (pseudo) page, rendered.
	 */
	public function down_alert_manage($posts){
		if(filter_input( INPUT_GET, 'down_alert' ) && filter_input( INPUT_GET, 'down_email' )){
			global $wpdb,$wp_query;
			// reset wp_query properties to simulate a found page

			$tablename=$wpdb->prefix.'dwrp_alert';
			$email_address=filter_input( INPUT_GET, 'down_email' );
			$key=filter_input( INPUT_GET, 'down_alert' );
			$k = $wpdb->get_row( "SELECT * FROM $tablename WHERE email='$email_address' AND identity_key='$key'" );
			$kid=$k->id;
			$post_id=$k->post_id;
			$email=$k->email;
			$key=$k->identity_key;
			$params_arr = array(
				'email_addr' => $email_address
				,'authcode' => $key
				,'subscriber_id' => $kid
				,'page_title' =>__('Manage subscription alert','download-directory')
				,'post_id' => $post_id
				);

			return $this->create_fake_page( $posts, 'render_manage_alert_page', $params_arr );
		}
		return $posts;
	}
	/**
	 * Create fake page object, to apply blog's current theme page template to
	 *  all Post Notif-related page data.
	 *
	 * @since   1.0.0
	 * @access  private
	 * @param   array   $posts  The current (pseudo) page.
	 * @param   string  $content_function   The function that generates content for current (pseudo) page.
	 * @param   array   $params_arr The parameters for DB operations and page title/greeting.
	 * @return  array   The current (pseudo) page.
	 */
	private function create_fake_page( $posts, $content_function, $params_arr ) {

		$posts = null;

		$post = new stdClass();
		$post->post_content = $this->$content_function( $params_arr );
		$post->post_title = $params_arr['page_title'];

	    //  Add page object properties to prevent attributes (category, author, and
	    //      post date/time) and functionality (add comment) from appearing on
	    //      subscriber preferences pages
		$post->post_type = 'page';
		$post->comment_status = 'closed';

		$posts[] = $post;

		return $posts;

	}
	/**
	 * Returns a reference to this class. Used for removing
	 * actions and/or filters declared using an object of this class.
	 *
	 * @see  	http://hardcorewp.com/2012/enabling-action-and-filter-hook-removal-from-class-based-wordpress-plugins/
	 * @return 	object 		This class
	 */
	static function this() {
		return self::$_this;

	} // this()

} // class

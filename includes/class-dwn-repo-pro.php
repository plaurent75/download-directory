<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://www.patricelaurent.net
 * @since      1.0.0
 *
 * @package    Dwn_Repo_Pro
 * @subpackage Dwn_Repo_Pro/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Dwn_Repo_Pro
 * @subpackage Dwn_Repo_Pro/includes
 * @author     patrice LAURENT <laurent.patrice@gmail.com>
 */
class Dwn_Repo_Pro {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Dwn_Repo_Pro_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->plugin_name = 'dwn-repo-pro';
		$this->version = '1.0.0';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$this->define_template_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Dwn_Repo_Pro_Loader. Orchestrates the hooks of the plugin.
	 * - Dwn_Repo_Pro_i18n. Defines internationalization functionality.
	 * - Dwn_Repo_Pro_Admin. Defines all hooks for the admin area.
	 * - Dwn_Repo_Pro_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-dwn-repo-pro-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-dwn-repo-pro-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-dwn-repo-pro-admin.php';

		/**
		 * The class responsible for the admin metabox.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/metabox.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-dwn-repo-pro-public.php';

		/**
		 * The class responsible for defining all actions creating the templates.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-dwn-repo-template-functions.php';

		/**
		 * The class responsible for all global functions.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/dwn-repo-global-functions.php';

		/**
		 * The class responsible for call required plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-tgm-plugin-activation.php';

		$this->loader = new Dwn_Repo_Pro_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Dwn_Repo_Pro_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Dwn_Repo_Pro_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Dwn_Repo_Pro_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'init',  $plugin_admin, 'down_repo_taxonomy');
		$this->loader->add_action( 'init',  $plugin_admin, 'down_repo' );
		//$this->loader->add_filter('post_link',  $plugin_admin, 'put_cat_permalink', 1, 3);
		$this->loader->add_action( 'tgmpa_register', $plugin_admin, 'theme_slug_register_required_plugins' );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'widgets_init', $plugin_admin, 'widget_sidebar' );
		$this->loader->add_filter('manage_posts_columns', $plugin_admin, 'posts_column_views');

		//Notification on post Update
		$this->loader->add_action( 'update_post_meta', $plugin_admin, 'notification_update', 10, 4 );
		$this->loader->add_action( 'admin_notices', $plugin_admin, 'admin_notices' );


	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Dwn_Repo_Pro_Public( $this->get_plugin_name(), $this->get_version() );

		//$this->loader->add_filter( 'single_template', $plugin_public, 'single_cpt_template' );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		//redirect after XX secondes the downloading page to the thnak you page
		// Time can be changed with filter down_repo_redirect_time
		$this->loader->add_action( 'wp_head', $plugin_public, 'add_meta_header' );
		//Filter to change the url for alert link
		//$this->loader->add_filter( 'down_repo_alertlink', $plugin_public, 'down_repo_alertlink' );

		$this->loader->add_action( "wp_ajax_add_alert_down", $plugin_public, "add_alert_down" );
		$this->loader->add_action( "wp_ajax_nopriv_add_alert_down", $plugin_public, "add_alert_down" );

		$this->loader->add_action( "wp_ajax_remove_alert_down", $plugin_public, "remove_alert_down" );
		$this->loader->add_action( "wp_ajax_nopriv_remove_alert_down", $plugin_public, "remove_alert_down" );

	}

	/**
	 * Register all of the hooks related to the templates.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_template_hooks() {
		$plugin_templates = new Down_Repo_Template_Functions( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'template_redirect', $plugin_templates, 'check_referer' );


		//rewrting rules
		//$this->loader->add_filter('init',  $plugin_templates, 'rewrite_slug',0);
		//$this->loader->add_filter('post_type_link',  $plugin_templates, 'down_generate_slug', 1, 3);
		$this->loader->add_filter( 'post_type_link', $plugin_templates, 'down_repo_custom_slug', 1, 3 );

		$this->loader->add_action( 'init', $plugin_templates,'dwn_repo_rewrite_endpoint' );
		$this->loader->add_filter( 'request', $plugin_templates,'rewrite_filter_request' );

		//Downloading Page
		$this->loader->add_filter('post_thumbnail_html',  $plugin_templates,'no_thumb', 99, 5);
		$this->loader->add_action('manage_posts_custom_column', $plugin_templates, 'posts_custom_column_views',5,2);

		// Singular
		$this->loader->add_filter( 'the_title', $plugin_templates,'add_cat_to_title', 10 );
		$this->loader->add_filter( 'get_the_excerpt', $plugin_templates,'remove_excerpt', 1 );
		$this->loader->add_filter( 'the_content', $plugin_templates,'down_excerpt', 1 );
		$this->loader->add_filter( 'the_content', $plugin_templates, 'filter_content', 0 );

		//Mail alert managing
		$this->loader->add_filter( 'the_posts', $plugin_templates, 'down_alert_manage' );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Dwn_Repo_Pro_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}

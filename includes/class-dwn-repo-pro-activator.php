<?php

/**
 * Fired during plugin activation
 *
 * @link       http://www.patricelaurent.net
 * @since      1.0.0
 *
 * @package    Dwn_Repo_Pro
 * @subpackage Dwn_Repo_Pro/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Dwn_Repo_Pro
 * @subpackage Dwn_Repo_Pro/includes
 * @author     patrice LAURENT <laurent.patrice@gmail.com>
 */
class Dwn_Repo_Pro_Activator {


	public static function activate() {
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-dwn-repo-pro-admin.php';
		Dwn_Repo_Pro_Admin::down_repo_taxonomy();
		Dwn_Repo_Pro_Admin::down_repo();
		flush_rewrite_rules();

		global $wpdb;
		$dwrp_db_version=1;
		$table_name = $wpdb->prefix . 'dwrp_alert';
		$charset_collate = $wpdb->get_charset_collate();
		$sql = "CREATE TABLE $table_name (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			email varchar(255) DEFAULT '' NOT NULL,
			post_id mediumint(9) NOT NULL,
			identity_key varchar(255) NOT NULL,
			UNIQUE KEY id (id),
			KEY post_id (post_id),
			KEY email (email)
			) $charset_collate;";

require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
dbDelta( $sql );

add_option( 'dwrp_db_version', $dwrp_db_version );
}

}

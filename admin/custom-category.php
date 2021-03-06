<?php
/**
 * The custom-category functionality of the plugin.
 *
 * @link       http://www.patricelaurent.net
 * @since      1.0.0
 *
 * @package    Dwn_Repo_Pro
 * @subpackage Dwn_Repo_Pro/admin
 */

/**
 * The custom-category functionality of the plugin.
 *
 * register taxonomy
 *
 * @package    Dwn_Repo_Pro
 * @subpackage Dwn_Repo_Pro/admin
 * @author     patrice LAURENT <laurent.patrice@gmail.com>
 */

$labels = array(
	'name'                       => _x( 'Downloads Categories', 'Taxonomy General Name', 'download-directory' ),
	'singular_name'              => _x( 'Downloads Category', 'Taxonomy Singular Name', 'download-directory' ),
	'menu_name'                  => __( 'Downloads Category', 'download-directory' ),
	);
$rewrite = array(
	'slug'                       => __( 'downloads', 'download-directory' ),
	'with_front'                 => false,
	'hierarchical'               => true,
	);
$args = array(
	'labels'                     => $labels,
	'hierarchical'               => true,
	'public'                     => true,
	'show_ui'                    => true,
	'show_admin_column'          => true,
	'show_in_nav_menus'          => true,
	'show_tagcloud'              => true,
	'rewrite'                    => $rewrite,
	);
register_taxonomy( 'down_cat', array( 'down_repo' ), $args );

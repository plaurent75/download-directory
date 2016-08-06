<?php
/**
 * The post-type-specific functionality of the plugin.
 *
 * @link       http://www.patricelaurent.net
 * @since      1.0.0
 *
 * @package    Dwn_Repo_Pro
 * @subpackage Dwn_Repo_Pro/admin
 */

/**
 * The post-type-specific functionality of the plugin.
 *
 * register post type.
 *
 * @package    Dwn_Repo_Pro
 * @subpackage Dwn_Repo_Pro/admin
 * @author     patrice LAURENT <laurent.patrice@gmail.com>
 */

	$labels = array(
		'name'                  => _x( 'Downloads', 'Post Type General Name', 'download-directory' ),
		'singular_name'         => _x( 'Download', 'Post Type Singular Name', 'download-directory' ),
		'menu_name'             => __( 'Downloads', 'download-directory' ),
		'name_admin_bar'        => __( 'Downloads', 'download-directory' ),
		'archives'              => __( 'Downloads Archives', 'download-directory' ),
		'all_items'             => __( 'All Downloads', 'download-directory' ),
		'add_new_item'          => __( 'Add New Download', 'download-directory' ),
	);
	$rewrite = array(
		'slug'                  => __('download','download-directory').'/%down_cat%',
		'with_front'            => false,
		'pages'                 => true,
		'feeds'                 => true,
	);
	$args = array(
		'label'                 => __( 'Download', 'download-directory' ),
		'description'           => __( 'Manage your Download Repository', 'download-directory' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'trackbacks', 'revisions', ),
		'taxonomies'            => array( 'down_cat', 'down_tag','down_license' ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 5,
		'menu_icon'             => 'dashicons-download',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => __('downloads','download-directory'),
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'rewrite'               => $rewrite,
		'capability_type'       => 'post',
	);
	register_post_type( 'down_repo', $args );


?>

<?php
/**
 * The custom-taxonomy-specific functionality of the plugin.
 *
 * @link       http://www.patricelaurent.net
 * @since      1.0.0
 *
 * @package    Dwn_Repo_Pro
 * @subpackage Dwn_Repo_Pro/admin
 */

/**
 * The custom-taxonomy-specific functionality of the plugin.
 *
 * register custom taxonomy.
 *
 * @package    Dwn_Repo_Pro
 * @subpackage Dwn_Repo_Pro/admin
 * @author     patrice LAURENT <laurent.patrice@gmail.com>
 */

$labels = array(
	'name'                       => _x( 'Downloads Tags', 'Taxonomy General Name', 'down_repo' ),
	'singular_name'              => _x( 'Downloads Tag', 'Taxonomy Singular Name', 'down_repo' ),
	'menu_name'                  => __( 'Downloads Tag', 'down_repo' ),

	);
$rewrite = array(
	'slug'                       =>__( 'downloads-filter', 'down_repo' ),
	'with_front'                 => false,
	'hierarchical'               => false,
	);
$args = array(
	'labels'                     => $labels,
	'hierarchical'               => false,
	'public'                     => true,
	'show_ui'                    => true,
	'show_admin_column'          => true,
	'show_in_nav_menus'          => false,
	'show_tagcloud'              => true,
	'rewrite'                    => $rewrite,
	);
register_taxonomy( 'down_tag', array( 'down_repo' ), $args );

$labelsLic = array(
	'name'                       => _x( 'Downloads Licenses', 'Taxonomy General Name', 'down_repo' ),
	'singular_name'              => _x( 'Downloads License', 'Taxonomy Singular Name', 'down_repo' ),
	'menu_name'                  => __( 'Downloads License', 'down_repo' ),

	);
$rewriteLic = array(
	'slug'                       =>__( 'downloads-license', 'down_repo' ),
	'with_front'                 => false,
	'hierarchical'               => false,
	);
$argsLic = array(
	'labels'                     => $labelsLic,
	'hierarchical'               => false,
	'public'                     => true,
	'show_ui'                    => true,
	'show_admin_column'          => true,
	'show_in_nav_menus'          => true,
	'show_tagcloud'              => true,
	'rewrite'                    => $rewriteLic,
	);
register_taxonomy( 'down_license', array( 'down_repo' ), $argsLic );
?>

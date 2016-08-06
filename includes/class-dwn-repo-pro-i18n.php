<?php
/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Dwn_Repo_Pro
 * @subpackage Dwn_Repo_Pro/includes
 * @author     patrice LAURENT <laurent.patrice@gmail.com>
 */
class Dwn_Repo_Pro_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'down_repo',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
			);
		load_plugin_textdomain(
			'tgmpa',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/tgmpa/'
			);
		//Manual translation to be added in pot catalogu.
		$trans=__('Version','down_repo');
		$trans=__('Editor','down_repo');
		$trans=__('Editor Website','down_repo');
		$trans=__('Download Size','down_repo');
		$trans=__('Download Link','down_repo');
		$trans=__('Mirror Link 1','down_repo');
		$trans=__('Mirror Link 2','down_repo');
		$trans=__('Mirror Link 3','down_repo');

	}



}

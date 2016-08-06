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
			'download-directory',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
			);
		load_plugin_textdomain(
			'tgmpa',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/tgmpa/'
			);
		//Manual translation to be added in pot catalogu.
		$trans=__('Version','download-directory');
		$trans=__('Editor','download-directory');
		$trans=__('Editor Website','download-directory');
		$trans=__('Download Size','download-directory');
		$trans=__('Download Link','download-directory');
		$trans=__('Mirror Link 1','download-directory');
		$trans=__('Mirror Link 2','download-directory');
		$trans=__('Mirror Link 3','download-directory');

	}



}

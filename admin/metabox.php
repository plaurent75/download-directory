<?php
/**
 * The metabox-specific functionality of the plugin.
 *
 * @link       http://www.patricelaurent.net
 * @since      1.0.0
 *
 * @package    Dwn_Repo_Pro
 * @subpackage Dwn_Repo_Pro/admin
 */

/**
 * The metabox-specific functionality of the plugin.
 *
 * create metabox.
 *
 * @package    Dwn_Repo_Pro
 * @subpackage Dwn_Repo_Pro/admin
 * @author     patrice LAURENT <laurent.patrice@gmail.com>
 */

/**
 * Generated by the WordPress Meta Box Generator at http://goo.gl/8nwllb
 */
class Rational_Meta_Box {
	private $screens = array(
		'down_repo',
		);
	private $fields = array(
		array(
			'id' => 'version',
			'label' => 'Version',
			'type' => 'text',
			),
		array(
			'id' => 'editor',
			'label' => 'Editor',
			'type' => 'text',
			),
		array(
			'id' => 'editor_website',
			'label' => 'Editor Website',
			'type' => 'text',
			),
		array(
			'id' => 'size',
			'label' => 'Download Size',
			'type' => 'text',
			),
		/* Better use featured image
		array(
			'id' => 'icon',
			'label' => 'Software Icon',
			'type' => 'file' ,
			),
			*/
		array(
			'id' => 'download-link',
			'label' => 'Download Link',
			'type' => 'text',
			),
		array(
			'id' => 'mirror-1',
			'label' => 'Mirror Link 1',
			'type' => 'text',
			),
		array(
			'id' => 'mirror-2',
			'label' => 'Mirror Link 2',
			'type' => 'text',
			),
		array(
			'id' => 'mirror-3',
			'label' => 'Mirror Link 3',
			'type' => 'text',
			),
		);

	/**
	 * Class construct method. Adds actions to their respective WordPress hooks.
	 */
	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_action( 'save_post', array( $this, 'save_post' ) );
	}

	/**
	 * Hooks into WordPress' add_meta_boxes function.
	 * Goes through screens (post types) and adds the meta box.
	 */
	public function add_meta_boxes() {
		foreach ( $this->screens as $screen ) {
			add_meta_box(
				'software-informations',
				__( 'software Informations', 'down_repo' ),
				array( $this, 'add_meta_box_callback' ),
				$screen,
				'side',
				'high'
				);
		}
	}

	/**
	 * Generates the HTML for the meta box
	 *
	 * @param object $post WordPress post object
	 */
	public function add_meta_box_callback( $post ) {
		wp_nonce_field( 'software_informations_data', 'software_informations_nonce' );
		 _e('Detailled Software Informations','down_repo');
		$this->generate_fields( $post );
	}

	/**
	 * Generates the field's HTML for the meta box.
	 */
	public function generate_fields( $post ) {
		$output = '';

		foreach ( $this->fields as $field ) {
			$label = '<label for="' . $field['id'] . '">' . __($field['label'],'down_repo') . '</label>';
			$db_value = get_post_meta( $post->ID, 'software_informations_' . $field['id'], true );
			switch ( $field['type'] ) {
				case 'file':
				//$image = ! $db_value ? '' : wp_get_attachment_image_src( $db_value, 'thumbnail', false, array('style' => 'max-width:100%;height:auto;') )[0];
				$input = sprintf(
					'<input id="%s" name="%s" type="%s" value="%s">',
					$field['id'],
					$field['id'],
					'hidden',
					$db_value
					);
				$input .= '<input type="button" target="'.$field['id'].'" id="'.$field['id'].'_button"  class="button meta-box-upload-button" value="Upload" />';
				$input .='<div class="image-preview"><img src="'.$db_value.'" style="width:100%" /></div>';
				break;

				default:
				$input = sprintf(
					'<input id="%s" name="%s" type="%s" value="%s">',
					$field['id'],
					$field['id'],
					$field['type'],
					$db_value
					);

			}
			$output .= '<p>' . $label . '<br>' . $input . '</p>';
		}
		echo $output;
	}

	/**
	 * Hooks into WordPress' save_post function
	 */
	public function save_post( $post_id ) {
		if ( ! isset( $_POST['software_informations_nonce'] ) )
			return $post_id;

		$nonce = $_POST['software_informations_nonce'];
		if ( !wp_verify_nonce( $nonce, 'software_informations_data' ) )
			return $post_id;

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
			return $post_id;

		foreach ( $this->fields as $field ) {
			if ( isset( $_POST[ $field['id'] ] ) ) {
				switch ( $field['type'] ) {
					case 'image':
					$_POST[ $field['id'] ] = wp_get_attachment_image(sanitize_text_field( $_POST[ $field['id'] ], 'thumbnail', false ));
					case 'email':
					$_POST[ $field['id'] ] = sanitize_email( $_POST[ $field['id'] ] );
					break;
					case 'text':
					$_POST[ $field['id'] ] = sanitize_text_field( $_POST[ $field['id'] ] );
					break;
				}
				update_post_meta( $post_id, 'software_informations_' . $field['id'], $_POST[ $field['id'] ] );
			} else if ( $field['type'] === 'checkbox' ) {
				update_post_meta( $post_id, 'software_informations_' . $field['id'], '0' );
			}
		}
	}

}
new Rational_Meta_Box;

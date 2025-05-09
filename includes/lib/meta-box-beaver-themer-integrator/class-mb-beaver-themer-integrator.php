<?php
/**
 * Integrates Meta Box custom fields with Beaver Themer.
 *
 * @package    Meta Box
 * @subpackage Meta Box Beaver Themer Integrator
 */

/**
 * The plugin main class.
 */
class MB_Beaver_Themer_Integrator {
	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'fl_page_data_add_properties', array( $this, 'add_to_posts' ) );
	}

	/**
	 * Add Meta Box Field to posts group.
	 */
	public function add_to_posts() {
		FLPageData::add_post_property( 'meta_box', array(
			'label'  => __( 'Meta Box Field', 'vsstemmart' ),
			'group'  => 'posts',
			'type'   => array(
				'string',
				'html',
				'photo',
				'multiple-photos',
				'url',
				'custom_field',
			),
			'getter' => array( $this, 'get_field_value' ),
			'form'   => 'meta_box',
		) );
		FLPageData::add_post_property_settings_fields( 'meta_box', array(
			'field'      => array(
				'type'    => 'select',
				'label'   => __( 'Field Name', 'vsstemmart' ),
				'options' => $this->get_post_fields(),
			),
			'image_size' => array(
				'type'  => 'photo-sizes',
				'label' => __( 'Image Size', 'vsstemmart' ),
			),
		) );
	}

	/**
	 * Display Meta Box field.
	 *
	 * @param object $settings Property settings.
	 * @param string $property Property.
	 *
	 * @return mixed
	 */
	public function get_field_value( $settings, $property ) {
		$field_id = $settings->field;
		$field    = rwmb_get_field_settings( $field_id );
		$args     = array();

		switch ( $field['type'] ) {
			case 'image':
			case 'image_advanced':
			case 'plupload_image':
				$value = rwmb_get_value( $field_id );
				return array_keys( $value );
			case 'single_image':
				$args['size'] = $settings->image_size;
				$value        = rwmb_get_value( $field_id, $args );
				$value['id']  = $value['ID'];
				return $value;
		}

		$value = rwmb_the_value( $field_id, $args, '', false );

		return $value;
	}

	/**
	 * Get list of Meta Box fields for posts.
	 *
	 * @return array
	 */
	public function get_post_fields() {
		$sources = array();
		$fields  = rwmb_get_registry( 'field' )->get_by_object_type( 'post' );
		foreach ( $fields as $post_type => $list ) {
			$post_type_object = get_post_type_object( $post_type );
			if ( ! $post_type_object ) {
				continue;
			}
			$options = array();
			foreach ( $list as $field ) {
				if ( in_array( $field['type'], array( 'heading', 'divider', 'custom_html', 'button' ), true ) ) {
					continue;
				}
				$options[ $field['id'] ] = $field['name'] ? $field['name'] : $field['id'];
			}
			$sources[ $post_type ] = array(
				'label'   => $post_type_object->labels->singular_name,
				'options' => $options,
			);
		}

		return $sources;
	}
}

<?php
// Exit if accessed directly.
defined('ABSPATH') || exit;

add_action( 'after_setup_theme', 'velocityiklan_theme_setup', 9 );
function velocityiklan_theme_setup() {
	
	if (class_exists('Kirki')) :

		Kirki::add_panel('panel_vmpc', [
			'priority'    => 10,
			'title'       => esc_html__('Velocity Iklan Baris', 'justg'),
			'description' => esc_html__('', 'justg'),
		]);
		
		Kirki::add_section('section_headervmpc', [
			'panel'    => 'panel_header',
			'title'    => __('Header Image', 'justg'),
			'priority' => 10,
		]);
		Kirki::add_field('justg_config', [
			'type'        => 'image',
			'settings'    => 'header_image',
			'label'       => esc_html__('Image', 'kirki'),
			'description' => esc_html__('', 'kirki'),
			'section'     => 'section_headervmpc',
		]);

		///Section Biaya
		Kirki::add_section('section_biayavmpc', [
			'panel'    => 'panel_vmpc',
			'title'    => __('Biaya Iklan', 'justg'),
			'priority' => 10,
		]);
		Kirki::add_section('section_catatanvmpc', [
			'panel'    => 'panel_vmpc',
			'title'    => __('Catatan Single', 'justg'),
			'priority' => 10,
		]);
		
		// Field Biaya
		new \Kirki\Field\Number([
    		'settings' => 'jml_free',
    		'label'    => esc_html__( 'Iklan Free (dalam hari)', 'justg' ),
    		'section'  => 'section_biayavmpc',
    		'description' => esc_html__( 'Jumlah hari untuk iklan free di publish', 'justg' ),
    		'default'  => 5,
    		'choices'  => [
    			'min'  => 0,
    			'step' => 1,
    		],
    	]);
    	
		new \Kirki\Field\Number([
    		'settings' => 'biaya_iklan',
    		'label'    => esc_html__( 'Biaya Iklan Perbulan', 'justg' ),
    		'section'  => 'section_biayavmpc',
    		'description' => esc_html__( 'Contoh:10000', 'justg' ),
    		'default'  => 10000,
    		'choices'  => [
    			'min'  => 0,
    			'step' => 1,
    		],
    	]);
    	new \Kirki\Field\Number([
    		'settings' => 'biaya_banner',
    		'label'    => esc_html__( 'Biaya Banner Perbulan', 'justg' ),
    		'section'  => 'section_biayavmpc',
    		'description' => esc_html__( 'Contoh:10000', 'justg' ),
    		'default'  => 10000,
    		'choices'  => [
    			'min'  => 0,
    			'step' => 1,
    		],
    	]);
    	new \Kirki\Field\Number([
    		'settings' => 'biaya_link',
    		'label'    => esc_html__( 'Biaya Link Perbulan', 'justg' ),
    		'section'  => 'section_biayavmpc',
    		'description' => esc_html__( 'Contoh:10000', 'justg' ),
    		'default'  => 10000,
    		'choices'  => [
    			'min'  => 0,
    			'step' => 1,
    		],
    	]);
    	new \Kirki\Field\Editor([
    		'settings'    => 'catatan_single',
    		'label'       => esc_html__( 'Catatan Single', 'justg' ),
    		'description' => esc_html__( 'This is an editor control.', 'justg' ),
    		'section'     => 'section_catatanvmpc',
    		'default'     => '',
    	]);

	endif;

}
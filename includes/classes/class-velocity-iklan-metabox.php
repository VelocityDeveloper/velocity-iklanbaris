<?php

/**
 * Class Velocity_Iklan_Meta_Box
 */
class Velocity_Iklan_Meta_Box {
    
    /**
     * Velocity_Iklan_Meta_Box constructor.
     */
    public function __construct() {
        add_filter('rwmb_meta_boxes', array($this, 'register_meta_boxes'));
    }

    /**
     * Register meta boxes.
     *
     * @param array $meta_boxes Meta boxes.
     *
     * @return array
     */
    public function register_meta_boxes($meta_boxes) {
        $meta_boxes[] = [
    		'id' => 'untitled',
    		'title' => esc_html__( 'Data Iklan', 'metabox-online-generator' ),
    		'post_types' => ['link'],
    		'context' => 'advanced',
    		'priority' => 'default',
    		'autosave' => 'false',
    		'fields' => [
    			[
    				'id' => 'lama',
    				'type' => 'text',
    				'name' => esc_html__( 'Masa Aktif (Bulan)', 'metabox-online-generator' ),
    			],
    			[
    				'id' => 'wb-blog',
    				'type' => 'text',
    				'name' => esc_html__( 'URL', 'metabox-online-generator' ),
    			],
    			[
    				'id' => 'divider_2',
    				'type' => 'divider',
    				'name' => esc_html__( 'Divider', 'metabox-online-generator' ),
    			],
    			[
    				'id' => 'nama',
    				'type' => 'text',
    				'name' => esc_html__( 'Nama', 'metabox-online-generator' ),
    			],
    			[
    				'id' => 'alamatemail',
    				'type' => 'text',
    				'name' => esc_html__( 'Email', 'metabox-online-generator' ),
    			],
    			[
    				'id' => 'kota',
    				'type' => 'text',
    				'name' => esc_html__( 'Kota', 'metabox-online-generator' ),
    			],
    			[
    				'id' => 'hp',
    				'type' => 'text',
    				'name' => esc_html__( 'HP', 'metabox-online-generator' ),
    			],
    		],
    	];


    	$meta_boxes[] = [
    		'id' => 'untitled',
    		'title' => esc_html__( 'Data Iklan', 'metabox-online-generator' ),
    		'post_types' => ['banner'],
    		'context' => 'advanced',
    		'priority' => 'default',
    		'autosave' => 'false',
    		'fields' => [
    			[
    				'id' => 'lama',
    				'type' => 'text',
    				'name' => esc_html__( 'Masa Aktif (Bulan)', 'metabox-online-generator' ),
    			],
    			[
    				'id' => 'wb-blog',
    				'type' => 'text',
    				'name' => esc_html__( 'URL', 'metabox-online-generator' ),
    			],
    			[
    				'id' => 'divider_2',
    				'type' => 'divider',
    				'name' => esc_html__( 'Divider', 'metabox-online-generator' ),
    			],
    			[
    				'id' => 'nama',
    				'type' => 'text',
    				'name' => esc_html__( 'Nama', 'metabox-online-generator' ),
    			],
    			[
    				'id' => 'alamatemail',
    				'type' => 'text',
    				'name' => esc_html__( 'Email', 'metabox-online-generator' ),
    			],
    			[
    				'id' => 'kota',
    				'type' => 'text',
    				'name' => esc_html__( 'Kota', 'metabox-online-generator' ),
    			],
    			[
    				'id' => 'hp',
    				'type' => 'text',
    				'name' => esc_html__( 'HP', 'metabox-online-generator' ),
    			],
    		],
    	];
    	
    	$meta_boxes[] = [
    		'id' => 'untitled',
    		'title' => esc_html__( 'Data Iklan', 'metabox-online-generator' ),
    		'post_types' => ['iklan'],
    		'context' => 'advanced',
    		'priority' => 'default',
    		'autosave' => 'false',
    		'fields' => [
    			[
    				'id' => 'lama',
    				'type' => 'text',
    				'name' => esc_html__( 'Masa Aktif (Bulan)', 'metabox-online-generator' ),
    			],
    			[
    				'id' => 'wb-blog',
    				'type' => 'text',
    				'name' => esc_html__( 'URL', 'metabox-online-generator' ),
    			],
    			[
    				'id' => 'divider_2',
    				'type' => 'divider',
    				'name' => esc_html__( 'Divider', 'metabox-online-generator' ),
    			],
    			[
    				'id' => 'nama',
    				'type' => 'text',
    				'name' => esc_html__( 'Nama', 'metabox-online-generator' ),
    			],
    			[
    				'id' => 'alamatemail',
    				'type' => 'text',
    				'name' => esc_html__( 'Email', 'metabox-online-generator' ),
    			],
    			[
    				'id' => 'kota',
    				'type' => 'text',
    				'name' => esc_html__( 'Kota', 'metabox-online-generator' ),
    			],
    			[
    				'id' => 'hp',
    				'type' => 'text',
    				'name' => esc_html__( 'HP', 'metabox-online-generator' ),
    			],
    			[
    				'id' => 'label',
    				'type' => 'text',
    				'name' => esc_html__( 'Label / Tag', 'metabox-online-generator' ),
    			],
    		],
    	];

        return $meta_boxes;
    }
}

// Inisialisasi class Velocity_Iklan_Meta_Box
new Velocity_Iklan_Meta_Box();
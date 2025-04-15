<?php

// Register New post type & taxonomy
add_action('init', 'velocity_ikaln_post_type_taxonomy');
function velocity_ikaln_post_type_taxonomy() {
    
    // Iklan Post Type
    register_post_type('iklan', [
        'labels' => [
            'name' => 'Iklan',
            'singular_name' => 'iklan',
            'add_new' => 'Tambah Iklan Baru',
            'add_new_item' => 'Tambah Iklan Baru',
            'edit_item' => 'Ubah Iklan',
            'view_item' => 'Lihat Iklan',
            'search_items' => 'Cari iklan',
            'not_found' => 'Tidak ditemukan iklan',
            'not_found_in_trash' => 'Tidak ada iklan di kotak sampah'
        ],
        'menu_position' => 5,
        'menu_icon' => 'dashicons-welcome-widgets-menus',
        'public' => true,
        'has_archive' => true,
        'rewrite' => true,
        'capability_type' => 'post',
        'taxonomies' => ['kategori'],
        'supports' => [
            'title',
            'editor',
            'comments',
            'thumbnail',
			'author',
        ],
    ]);
    
    // Iklan Banner Post Type
    register_post_type('banner', [
        'labels' => [
            'name' => 'Iklan Banner',
            'singular_name' => 'banner',
            'add_new' => 'Tambah Iklan Banner Baru',
            'add_new_item' => 'Tambah Iklan Banner Baru',
            'edit_item' => 'Ubah Iklan Banner',
            'view_item' => 'Lihat Iklan Banner',
            'search_items' => 'Cari iklan banner',
            'not_found' => 'Tidak ditemukan iklan banner',
            'not_found_in_trash' => 'Tidak ada iklan banner di kotak sampah'
        ],
        'menu_position' => 5,
        'menu_icon' => 'data:image/svg+xml;base64,' . base64_encode('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-card-image" viewBox="0 0 16 16"><path d="M6.002 5.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0"/><path d="M1.5 2A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2zm13 1a.5.5 0 0 1 .5.5v6l-3.775-1.947a.5.5 0 0 0-.577.093l-3.71 3.71-2.66-1.772a.5.5 0 0 0-.63.062L1.002 12v.54L1 12.5v-9a.5.5 0 0 1 .5-.5z"/></svg>'),
        'public' => true,
        'has_archive' => true,
        'rewrite' => true,
        'capability_type' => 'post',
        'taxonomies' => ['lokasi'],
        'supports' => [
            'title',
            'comments',
            'thumbnail',
			'author',
        ],
    ]);
    
    // Iklan Link Post Type
    register_post_type('link', [
        'labels' => [
            'name' => 'Iklan Link',
            'singular_name' => 'link',
            'add_new' => 'Tambah Iklan Link Baru',
            'add_new_item' => 'Tambah Iklan Link Baru',
            'edit_item' => 'Ubah Iklan Link',
            'view_item' => 'Lihat Iklan Link',
            'search_items' => 'Cari iklan link',
            'not_found' => 'Tidak ditemukan iklan link',
            'not_found_in_trash' => 'Tidak ada iklan link di kotak sampah'
        ],
        'menu_position' => 5,
        'menu_icon' => 'data:image/svg+xml;base64,' . base64_encode('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-link-45deg" viewBox="0 0 16 16"><path d="M4.715 6.542 3.343 7.914a3 3 0 1 0 4.243 4.243l1.828-1.829A3 3 0 0 0 8.586 5.5L8 6.086a1 1 0 0 0-.154.199 2 2 0 0 1 .861 3.337L6.88 11.45a2 2 0 1 1-2.83-2.83l.793-.792a4 4 0 0 1-.128-1.287z"/><path d="M6.586 4.672A3 3 0 0 0 7.414 9.5l.775-.776a2 2 0 0 1-.896-3.346L9.12 3.55a2 2 0 1 1 2.83 2.83l-.793.792c.112.42.155.855.128 1.287l1.372-1.372a3 3 0 1 0-4.243-4.243z"/></svg>'),
        'public' => true,
        // 'publicly_queryable' => false,
        'has_archive' => true,
        'rewrite' => true,
        'capability_type' => 'post',
        'taxonomies' => [],
        'supports' => [
            'title',
            'comments',
            'thumbnail',
			'author',
        ],
    ]);
   
   
	register_taxonomy(
		'kategori',
		array('iklan'),
		array(
			'label' => __( 'Kategori Iklan' ),
			'rewrite' => array( 'slug' => 'kategori' ),
			'show_ui' => true,
            'show_in_menu' => true,
            'show_in_nav_menus' => true,
            'show_in_rest' => true,
            'show_tagcloud' => true,
            'show_in_quick_edit' => true,
            'hierarchical' => true,
            'query_var' => true,
            'rewrite' => true,
            'show_admin_column' => true,
            'show_in_rest' => true,
		)
	);
	register_taxonomy(
		'lokasi',
		array('banner'),
		array(
			'label' => __( 'Lokasi Iklan' ),
			'rewrite' => array( 'slug' => 'lokasi' ),
			'show_ui' => true,
            'show_in_menu' => true,
            'show_in_nav_menus' => true,
            'show_in_rest' => true,
            'show_tagcloud' => true,
            'show_in_quick_edit' => true,
            'hierarchical' => true,
            'query_var' => true,
            'rewrite' => true,
            'show_admin_column' => true,
            'show_in_rest' => true,
		)
	);
}

//Displaying kategori-iklan Columns
add_filter( 'manage_taxonomies_for_posts_columns', 'kategori_iklan_columns' );
function kategori_iklan_columns( $taxonomies ) {
    $taxonomies[] = 'kategori';
    $taxonomies[] = 'lokasi';
    return $taxonomies;
}

// Tampilkan kolom tertentu
add_filter('manage_posts_columns' , 'custom_columns');
function custom_columns($defaults,$post_id='') {
    $screen = get_current_screen();
	if($screen->post_type == 'iklan' || $screen->post_type == 'banner' || $screen->post_type == 'link'){
        $columns = array(
            'cb'                => '<input type="checkbox" />',
            'featured_image'    => 'Image',
            'title'             => 'Title',
            'date'              => 'Tanggal',
            'status'            => 'Aktif Sampai',
            'data'              => 'Data Pengiklan',
         );
         
         // Tambahkan kolom spesifik berdasarkan post_type
        if ($screen->post_type == 'iklan') {
            $columns['taxonomy-kategori'] = 'Kategori';
        } elseif ($screen->post_type == 'banner') {
            $columns['taxonomy-lokasi'] = 'Lokasi';
            $columns['shortcode_id'] = 'Shortcode ID';
            $columns['shortcode_lokasi'] = 'Shortcode Lokasi';
        }
        $columns['hit'] = 'Hit';
        $columns['tindakan'] = 'Tindakan';
        //  $columns['status'] = __( 'Aktif Sampai', 'vsstemmart' );
        return $columns;
	}else{
		return $defaults;
	}
}

add_action( 'manage_posts_custom_column' , 'custom_columns_data', 10, 2 );
function custom_columns_data( $column, $post_id ) {
    $hari_free = get_theme_mod('jml_free');
    switch ( $column ) {
    case 'featured_image':
        echo '<img style="width: 75px;height: auto;" src="'.get_the_post_thumbnail_url($post_id ,'thumbnail').'" alt="" />';
    break;
    
    case 'status' :
        $date = get_post_meta( $post_id , 'lama' , true );
        $jumlah = floatval($date) * floatval('30');
        if(get_post_type($post_id) == 'iklan'){
            if($date != 'gratis' ){
                $ddd =  date_add(date_create(get_the_date('d-m-Y')),date_interval_create_from_date_string($jumlah." days"));
                $expired = date('U', date("U", strtotime(date_format($ddd,"Y-m-d"))));
                $now     = date('U');
                if($expired>=$now && get_post_status( $post_id ) != 'draft' && get_post_status( $post_id ) != 'pending'){
                    echo date_format($ddd, 'd F Y');
                    echo '<b style="color:green;"> - Aktif</b>';
                    echo '<br> ('.$date.' Bulan)';
                } else {
                    echo '<b style="color:red;">- Expired</b>';
                    echo '<br> ('.$date.' Bulan)';
                }
            } else {
                $ddd =  date_add(date_create(get_the_date('d-m-Y')),date_interval_create_from_date_string($hari_free." days"));
                $expired = date('U', date("U", strtotime(date_format($ddd,"Y-m-d"))));
                $now     = date('U');
                if($expired>=$now && get_post_status( $post_id ) != 'draft' && get_post_status( $post_id ) != 'pending'){
                    echo date_format($ddd, 'd F Y').'<b style="color:green;"> - Aktif</b>';
                    echo '<br/><span class="btn btn-sm btn-warning">Gratis</span>';
                } else {
                    echo '<b style="color:red;">- Expired</b>';
                    echo '<br/><span class="btn btn-sm btn-warning">Gratis</span>';
                }
                
            }
        } else {
            if($date != 'gratis'){
                $ddd =  date_add(date_create(get_the_date('d-m-Y')),date_interval_create_from_date_string($date." days"));
                
                $expired = date('U', date("U", strtotime(date_format($ddd,"Y-m-d"))));
                $now     = date('U');
                if($expired>=$now){
                    echo date_format($ddd, 'Y-m-d');
                    echo '<b style="color:green;">- Aktif</b>';
                    echo '<br> ('.$date.' Bulan)';
                } else {
                    echo '<b style="color:red;">- Expired</b>';
                    echo '<br> ('.$date.' Bulan)';
                }
            } else {
                $ddd =  date_add(date_create(get_the_date('d-m-Y')),date_interval_create_from_date_string($hari_free." days"));
                $expired = date('U', date("U", strtotime(date_format($ddd,"Y-m-d"))));
                $now     = date('U');
                if($expired>=$now && get_post_status( $post_id ) != 'draft' && get_post_status( $post_id ) != 'pending'){
                    echo date_format($ddd, 'd F Y').'<b style="color:green;"> - Aktif</b>';
                    echo '<br/><span class="btn btn-sm btn-warning">Gratis</span>';
                } else {
                    echo '<b style="color:red;">- Expired</b>';
                    echo '<br/><span class="btn btn-sm btn-warning">Gratis</span>';
                }
            }
        }
        break;
    case 'stok' :
        echo get_post_meta( $post_id , 'stok' , true ); 
        break;
    case 'data' :
        echo '';
        echo get_post_meta( $post_id , 'nama' , true ).', '; 
        echo get_post_meta( $post_id , 'hp' , true ).', '; 
        echo get_post_meta( $post_id , 'alamatemail' , true ).', '; 
        echo get_post_meta( $post_id , 'kota' , true ); 
        break;
    case 'hit' :
        echo get_post_meta( $post_id , 'hit' , true ); 
        break;
    case 'shortcode_id' :
        $banner_id = '[banner_image post_id="' . $post_id . '"]';
        echo esc_html($banner_id); // Output: [banner id="123"]
        break;
    case 'shortcode_lokasi' :
        $location_terms = get_the_terms($post_id, 'lokasi');
        $lokasi = $location_terms[0]->slug;
        $banner_lokasi = '[banner_image lokasi="' . $lokasi . '"]';
        echo esc_html($banner_lokasi); // Output: [banner id="header"]
        break;
    case 'tindakan' :
        $date = get_post_meta( $post_id , 'lama' , true );
        $jumlah = floatval($date) * floatval('30');
        if(get_post_type($post_id) == 'iklan'){
            if($date != 'gratis' ){
                $ddd =  date_add(date_create(get_the_date('d-m-Y')),date_interval_create_from_date_string($jumlah." days"));
                // echo date_format($ddd, 'Y-m-d');
                $expired = date('U', date("U", strtotime(date_format($ddd,"Y-m-d"))));
                $now     = date('U');
                if($expired>=$now && get_post_status( $post_id ) != 'draft' && get_post_status( $post_id ) != 'pending'){
                    echo '<a class="btn btn-danger btn-sm unpublishpost text-white" data-id="'.$post_id.'">Unpublish</a>';
                } else {
                    echo '<a class="btn btn-success btn-sm publishpost text-white" data-id="'.$post_id.'">Publish</a>';
                }
            } else {
                $ddd =  date_add(date_create(get_the_date('d-m-Y')),date_interval_create_from_date_string($hari_free." days"));
                // echo date_format($ddd, 'Y-m-d');
                $expired = date('U', date("U", strtotime(date_format($ddd,"Y-m-d"))));
                $now     = date('U');
                if($expired>=$now && get_post_status( $post_id ) != 'draft' && get_post_status( $post_id ) != 'pending'){
                    echo '<a class="btn btn-danger btn-sm unpublishpost text-white" data-id="'.$post_id.'">Unpublish</a>';
                } else {
                    echo '<a class="btn btn-success btn-sm publishpost text-white" data-id="'.$post_id.'">Publish</a>';
                }
            }
        } else {
            if($date != 'gratis' ){
                $ddd =  date_add(date_create(get_the_date('d-m-Y')),date_interval_create_from_date_string($date." days"));
                // echo date_format($ddd, 'Y-m-d');
                $expired = date('U', date("U", strtotime(date_format($ddd,"Y-m-d"))));
                $now     = date('U');
                if($expired>=$now && get_post_status( $post_id ) != 'draft' && get_post_status( $post_id ) != 'pending'){
                    echo '<a class="btn btn-danger btn-sm unpublishpost text-white" data-id="'.$post_id.'">Unpublish</a>';
                } else {
                    echo '<a class="btn btn-success btn-sm publishpost text-white" data-id="'.$post_id.'">Publish</a>';
                }
            }
        }

        break; 
    }
}


// Register New Page
add_action( 'init', 'velocity_iklan_create_page' );
function velocity_iklan_create_page() {
    $new_pages = array(
        array(
            'slug'      => 'akun-saya',
            'title'     => 'Akun Saya',
            'content'   => '[velocity-iklan-profile]'
        ),
        array(
            'slug'      => 'mylogin',
            'title'     => 'Login',
            'content'   => '[velocity-iklan-login]'
        ),
        array(
            'slug'      => 'myregistrasi',
            'title'     => 'Registrasi',
            'content'   => '[velocity-iklan-registrasi]'
        ),
        array(
            'slug'      => 'cari-iklan',
            'title'     => 'Pencarian Iklan',
            'content'   => '[velocity-iklan-filter]'
        )
    );
    foreach ($new_pages as $page) {
        if ( null == get_page_by_path($page['slug']) ) {
            wp_insert_post(
                array(
                    'comment_status'    => 'closed',
                    'ping_status'       => 'closed',
                    'post_author'       => 1,
                    'post_name'         => $page['slug'],
                    'post_title'        => $page['title'],
                    'post_content'      => $page['content'],
                    'post_status'       => 'publish',
                    'post_type'         => 'page',
                    'page_template'     => 'page-templates/empty.php'
                )
            );
        }
    }
}


//register iklan template
add_filter( 'template_include', 'velocityiklan_register_template' );
function velocityiklan_register_template( $template ) {    
    if ( is_singular('iklan') ) {
        $template = VELOCITY_IKLAN_PLUGIN_DIR . 'public/templates/single-iklan.php';
    }
    if ( is_singular('banner') ) {
        $template = VELOCITY_IKLAN_PLUGIN_DIR . 'public/templates/single-banner.php';
    }
    if ( is_post_type_archive('iklan') ) {
        $template = VELOCITY_IKLAN_PLUGIN_DIR . 'public/templates/archive-kategori.php';
    }
    if ( is_post_type_archive('banner') || is_tax('lokasi') ) {
        $template = VELOCITY_IKLAN_PLUGIN_DIR . 'public/templates/archive-banner.php';
    }
    if ( is_post_type_archive('link') ) {
        $template = VELOCITY_IKLAN_PLUGIN_DIR . 'public/templates/archive-link.php';
    }
    // if ( is_tax('kategori') ) {
    //     $template = VELOCITY_IKLAN_PLUGIN_DIR . 'public/templates/archive-kategori.php';
    // }
    if ( is_author() ) {
        $template = VELOCITY_IKLAN_PLUGIN_DIR . 'public/templates/author.php';
    }
    return $template;
}
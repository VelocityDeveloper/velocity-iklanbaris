<?php
// [velocity-iklan-login]
add_shortcode('velocity-iklan-login', 'velocity_iklan_login_form');
function velocity_iklan_login_form() {
    ob_start();
    
    echo '<div style="max-width:35em" class="mx-auto w-100 my-4 p-3 form-login card">';
    if ( is_user_logged_in() ) {
        $current_user = wp_get_current_user();
        echo '<div class="alert alert-warning" role="alert">Halo, ' . esc_html( $current_user->display_name ) . '! Kamu sudah login. <a href="' . wp_logout_url( get_permalink() ) . '">Logout</a></div>';
    } else {

    // Tampilkan form login
    ?>
    <form class="form-mylogin" id="mylogin" action="mylogin" method="post">
        <h5 class="fw-bold">Login Akun</h5>
        <div class="form-floating mb-3">
            <input type="text" class="form-control" name="username" id="username" placeholder="Username" required>
            <label for="username">Username</label>
        </div>
        <div class="form-floating mb-3">
            <input type="password" class="form-control" name="password" id="password" placeholder="Password" required>
            <label for="password">Password</label>
        </div>
        
        <div class="form-group">
            <?php echo velocityiklan_display_recaptcha();?>
        </div>
        
        <p class="status"></p>

        <input id="redirect" type="hidden" value="<?php echo get_home_url();?>">
        <?php wp_nonce_field( 'ajax-login-nonce', 'security' );?>
        
        <div class="form-group">
            <input class="btn btn-success w-100 mb-2" type="submit" value="Login" name="submit">
        </div>
        
        <div class="text-center">
            Belum punya akun ? <a class="link-success" href="<?php echo home_url();?>/myregistrasi" title="Daftar">Daftar</a>
        </div>
    </form>
    <?php
    }
    echo '</div>';
    return ob_get_clean();
}

// [velocity-iklan-registrasi]
add_shortcode('velocity-iklan-registrasi', 'velocity_iklan_registrasi_form');
function velocity_iklan_registrasi_form() {
    ob_start();

    echo '<div style="max-width:35em" class="mx-auto w-100 my-4 p-3 form-login card">';
    if ( is_user_logged_in() ) {
        $current_user = wp_get_current_user();
        echo '<div class="alert alert-warning" role="alert">Halo, ' . esc_html( $current_user->display_name ) . '! Kamu sudah login. <a href="' . wp_logout_url( get_permalink() ) . '">Logout</a></div>';
    } else {
        if ( isset($_POST['velocity_register_submit']) ) {
            $username    = sanitize_user( $_POST['username'] );
            $password    = $_POST['password'];
            $email       = sanitize_email( $_POST['email'] );
            $first_name  = sanitize_text_field( $_POST['first_name'] );

            $errors = new WP_Error();

            // Validasi
            if ( username_exists( $username ) ) {
                $errors->add( 'username', 'Username sudah digunakan.' );
            }

            if ( !validate_username( $username ) ) {
                $errors->add( 'username_invalid', 'Username tidak valid.' );
            }

            if ( email_exists( $email ) ) {
                $errors->add( 'email', 'Email sudah digunakan.' );
            }

            if ( !is_email( $email ) ) {
                $errors->add( 'email_invalid', 'Email tidak valid.' );
            }

            if ( empty($password) ) {
                $errors->add( 'password', 'Password tidak boleh kosong.' );
            }

            // Jika tidak ada error, daftarkan user
            if ( empty( $errors->errors ) ) {
                $user_id = wp_create_user( $username, $password, $email );
                if ( !is_wp_error($user_id) ) {
                    wp_update_user( array(
                        'ID'         => $user_id,
                        'first_name' => $first_name,
                    ));
                    
                    // Kirim email ke user baru
                    ///set header Email
					$headers[]  = 'MIME-Version: 1.0';
					$headers[]  = 'Content-type: text/html; charset=iso-8859-1';
					// Additional headers
					$headers[]  = 'From: '.get_bloginfo( 'name' ).' <'.$emailblog.'>';
                    $subject = 'Registrasi Berhasil di ' . get_bloginfo('name');
                    $message = "Halo $first_name,\n\n";
                    $message .= "Akun kamu berhasil dibuat di " . get_bloginfo('name') . ".\n\n";
                    $message . "Username: $username\n";
                    $message . "Password: $password\n";
                    $message . "Silakan login di: " . wp_login_url() . "\n\n";
                    $message . "Terima kasih.";

                    wp_mail( $email, $subject, $message, implode("\r\n", $headers)  );

                    echo '<div class="alert alert-success" role="alert">Registrasi berhasil. Silakan <a href="' . wp_login_url() . '">login</a>.</div>';
                } else {
                    echo '<div class="alert alert-danger" role="alert">Terjadi kesalahan saat registrasi.</div>';
                }
            } else {
                foreach ( $errors->get_error_messages() as $error ) {
                    echo '<div class="alert alert-danger" role="alert">' . esc_html($error) . '</div>';
                }
            }
        }

        // Tampilkan form
        ?>
        <form class="form-myregistrasi" id="adduser" method="post" action="">
            <h5 class="fw-bold">Daftar Akun</h5>
            
            <div class="form-floating mb-3">
                <input type="text" class="form-control" name="username" id="username" placeholder="Username" required>
                <label for="username">Username</label>
            </div>
            
            <div class="form-floating mb-3">
                <input type="text" class="form-control" name="first_name" id="first_name" placeholder="Nama Lengkap" required>
                <label for="first_name">Nama Lengkap</label>
            </div>
            
            <div class="form-floating mb-3">
                <input type="email" class="form-control" name="email" id="email" placeholder="Alamat Email" required>
                <label for="email">Email</label>
            </div>
            
            <div class="form-floating mb-3">
                <input type="password" class="form-control" name="password" id="password" placeholder="Password" required>
                <label for="password">Password</label>
            </div>
            
            <div class="form-group">
                <?php echo velocityiklan_display_recaptcha();?>
            </div>
            
            <div class="form-group">
                <input type="submit" class="btn btn-success w-100 mb-2" name="velocity_register_submit" value="Daftar">
            </div>
        </form>
        <?php
    }
    echo '</div>';

    return ob_get_clean();
}


//[velocity-iklan-ratio-image size="large" ratio="16:9"]
add_shortcode('velocity-iklan-ratio-image', 'velocity_iklan_ratio_image');
function velocity_iklan_ratio_image($atts) {
	global $post;
    $atribut = shortcode_atts( array(
        'size'      => 'large', // thumbnail, medium, large, full
        'ratio'     => '16:9', // 16:9, 8:5, 4:3, 3:2, 1:1
        'post_id'  	=> $post->ID,
    ), $atts );
    $post_id    = $atribut['post_id'];
    $size       = $atribut['size'];
    $ratio      = $atribut['ratio'];
    $ratio      = $ratio?str_replace(":","-",$ratio):'';

    $attachments = get_posts( array(
        'post_type' 		=> 'attachment',
        'posts_per_page' 	=> 1,
        'post_parent' 		=> $post_id,
        'orderby'          => 'date',
        'order'            => 'DESC',
    ));
    if (has_post_thumbnail($post_id)){
	    $urlimg = get_the_post_thumbnail_url($post_id,$size);    
	} elseif($attachments) {
        $urlimg = wp_get_attachment_url( $attachments[0]->ID, 'full' );
    } else{        
        $urlimg = VELOCITY_IKLAN_PLUGIN_URL.'public/img/no-image.png';
    }

    $html = '<div class="velocitymp-ratio-image">';
        $html .= '<a class="velocitymp-ratio-image-link" href="'.get_the_permalink($post_id).'" title="'.get_the_title($post_id).'">';
            $html .= '<div class="velocitymp-ratio-image-box velocitymp-ratio-image-'.$ratio.'" style="background-image: url('.$urlimg.');">';
                $html .= '<img src="'.$urlimg.'" loading="lazy" class="velocitymp-ratio-image-img d-none"/>';
            $html .= '</div>';
        $html .= '</a>';
    $html .= '</div>';
	return $html;
}


// [velocity-iklan-search]
add_shortcode('velocity-iklan-search', function() {
    $lokasi     = isset($_GET['lokasi']) ? $_GET['lokasi'] : '';
    $kategori   = isset($_GET['cp']) ? $_GET['cp'] : '';
    $s          = isset($_GET['sq']) ? $_GET['sq'] : '';
    $html = '';
    $html .= '<div class="velocity-iklan-search bg-color-theme rounded p-3">';
        $html .= '<form action="'.home_url('/cari-iklan').'" method="get" class="row align-items-center">';
            $html .= '<div class="col-sm-6 col-md-3 pe-sm-1 mb-2 mb-md-0">';
                $html .= '<select class="form-select border-0" name="lokasi">';
                    $html .= '<option value="">Semua Lokasi</option>';
                    $terms = get_terms(array(
                        'taxonomy' => 'lokasi',
                        'hide_empty' => true,
                        'parent' => 0,
                    ));                        
                    if (!empty($terms)) {
                        foreach ($terms as $term) {
                            $child_terms = get_terms(array(
                                'taxonomy' => 'lokasi',
                                'hide_empty' => true,
                                'parent' => $term->term_id,
                            ));
                            $childs = '';
                            $jml = array();
                            if (!empty($child_terms)) {
                                foreach ($child_terms as $child) {
                                    $terpilih = $lokasi == $child->term_id ? ' selected="selected"' : '';
                                    $childs .= '<option value="'.$child->term_id.'"'.$terpilih.'>&nbsp;&nbsp;'.$child->name.' ('.$child->count.')</option>';
                                    $jml[] = $child->count;
                                }
                            }
                            $selected = $lokasi == $term->term_id ? ' selected="selected"' : '';
                            $html .= '<option value="'.$term->term_id.'"'.$selected.'>'.$term->name.' ('.array_sum($jml).')</option>';
                            $html .= $childs;
                        }
                    }
                $html .= '</select>';
            $html .= '</div>';
            $html .= '<div class="col-sm-6 ps-sm-1 ps-md-2 col-md-3 pe-md-0 mb-2 mb-md-0">';
                $html .= '<select class="form-select border-0" name="cp">';
                    $html .= '<option value="">Semua Kategori</option>';
                        $terms = get_terms(array(
                            'taxonomy' => 'kategori',
                            'hide_empty' => true,
                            'parent' => 0,
                        ));                        
                        if (!empty($terms)) {
                            foreach ($terms as $term) {
                                $child_terms = get_terms(array(
                                    'taxonomy' => 'kategori',
                                    'hide_empty' => true,
                                    'parent' => $term->term_id,
                                ));
                                $childs = '';
                                $jml = array();
                                if (!empty($child_terms)) {
                                    foreach ($child_terms as $child) {
                                        $terpilih = $kategori == $child->term_id ? ' selected="selected"' : '';
                                        $childs .= '<option value="'.$child->term_id.'"'.$terpilih.'>&nbsp;&nbsp;'.$child->name.' ('.$child->count.')</option>';
                                        $jml[] = $child->count;
                                    }
                                }
                                $selected = $kategori == $term->term_id ? ' selected="selected"' : '';
                                $html .= '<option value="'.$term->term_id.'"'.$selected.'>'.$term->name.' ('.array_sum($jml).')</option>';
                                $html .= $childs;
                            }
                        }
                $html .= '</select>';
            $html .= '</div>';
            $html .= '<div class="col-sm-6 col-md-4 pe-sm-1 pe-md-1 mb-2 mb-sm-0">';
                $html .= '<input type="text" placeholder="Cari iklan" class="form-control border-0" value="'.$s.'" name="sq">';
            $html .= '</div>';
            $html .= '<div class="col-sm-6 col-md-2 ps-sm-1 ps-md-2">';
                $html .= '<button type="submit" class="btn btn-light w-100 m-0">Cari</button>';
            $html .= '</div>';
        $html .= '</form>';
    $html .= '</div>';
    return $html;
});


// [velocity-iklan-harga]
// add_shortcode('velocity-iklan-harga', function($atts) {
//     global $post;
//     $atribut = shortcode_atts( array(
//         'post_id'   => $post->ID,
//     ), $atts );
// 	$post_id = $atribut['post_id'];
//     $price = get_post_meta($post_id,'harga',true);
//     $harga = preg_replace('/[^0-9]/', '', $price);
//     $html = velocity_number_money($harga);
//     return $html;
// });


// [velocity-iklan-kategori]
add_shortcode('velocity-iklan-kategori', function($atts) {
    $terms = get_terms(array(
        'taxonomy' => 'kategori',
        'hide_empty' => true,
        'parent' => 0,
    ));  
    $html = '';                      
    if(!empty($terms)) {
        $html .= '<div class="row">';
        foreach ($terms as $term) {
            $html .= '<div class="col-6 col-md-3 mb-4 text-center">';
                $html .= '<div class="h-100 border rounded-0">';
                    $html .= '<a class="h-100 d-block py-2 px-1" href="'.get_term_link($term->term_id).'">';
                        $html .= velocity_term_image($term->term_id,'large',array('class'=>'mb-2 velocity-term-image'));
                        $html .= '<div class="lh-sm">';
                            $html .= $term->name;
                        $html .= '</div>';
                    $html .= '</a>';
                $html .= '</div>';
            $html .= '</div>';
        }
        $html .= '</div>';
    }
    return $html;
});


// [velocity-iklan-taxonomy]
add_shortcode('velocity-iklan-taxonomy', function($atts) {
    $atts = shortcode_atts( array(
        'post_id' => get_the_ID(),
        'taxonomy' => 'kategori',
        'separator' => ', ',
    ), $atts, 'custom_taxonomy' );

    $post_id = intval($atts['post_id']);
    $taxonomy = sanitize_key($atts['taxonomy']);
    $separator = $atts['separator'];

    $terms = wp_get_post_terms($post_id, $taxonomy);

    if (empty($terms) || is_wp_error($terms)) {
        return '';
    }

    // Urutkan array terms berdasarkan parent-child hierarchy
    usort($terms, function($a, $b) {
        return $a->parent - $b->parent;
    });

    $term_links = array();
    foreach ($terms as $term) {
        $term_link = '<a href="' . esc_url(get_term_link($term)) . '" class="text-muted">' . esc_html($term->name) . '</a>';
        $term_links[] = $term_link;
    }

    $output = implode($separator, $term_links);

    return $output;
});



// [velocity-iklan-profile]
add_shortcode('velocity-iklan-profile', function() {
    require_once VELOCITY_IKLAN_PLUGIN_DIR . 'public/page-profile.php';
});

// [velocity-iklan-filter]
add_shortcode('velocity-iklan-filter', function() {
    require_once VELOCITY_IKLAN_PLUGIN_DIR . 'public/page-iklan-filter.php';
});

// [velocity-link-loop post_id="" class=""]
add_shortcode('velocity-link-loop', function($atts){
    ob_start();
    global $post;
    $atribut = shortcode_atts( array(
        'class' 	=> 'block-primary border-bottom h-100 p-2',
        'post_id' 	=> $post->ID
    ), $atts );
    $post_id = $atribut['post_id'];
    $class = $atribut['class'];
    $url_iklan  = get_post_meta($post_id,'wb-blog', true);
    $author_id = get_post_field( 'post_author', $post_id );
    if($author) {
        $author_id = $author;
    }
    $author_name = get_the_author_meta( 'display_name', $author_id );
    $author_phone = get_user_meta($author_id, 'phone', true);
    if (substr($author_phone, 0, 1) === '0') {
        $author_phone   = '62' . substr($author_phone, 1);
    }
    $whatsapp_icon = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-whatsapp" viewBox="0 0 16 16"><path d="M13.601 2.326A7.85 7.85 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.9 7.9 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.9 7.9 0 0 0 13.6 2.326zM7.994 14.521a6.6 6.6 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.56 6.56 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592m3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.73.73 0 0 0-.529.247c-.182.198-.691.677-.691 1.654s.71 1.916.81 2.049c.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232"/></svg>';
    echo '<article class="'.$class.'" id="post-'.$post_id.'">';
		echo '<div class="entry-content">';
			echo '<div class="col-content">';
				echo '<div class="fw-bold mb-2"><a class="text-primary" href="'.$url_iklan.'" rel="bookmark" taget="_blank">'.wp_trim_words(get_the_title($post_id), 10, '...').'</a></div>';
				echo '<div class="d-flex align-items-center justify-content-between">';
				    echo '<div class="m-1"><i class="fa fa-map-o pe-2" aria-hidden="true"></i> '.do_shortcode('[velocity-iklan-meta key="kota" post_id="'.$post_id.'"]').'</div>';
				    echo '<div class="m-1"><a href="'.$url_iklan.'" class="w-100"><i class="fa fa-link" aria-hidden="true"></i> Kunjungi Link</a></div>';
				    echo '<div class="m-1"><a href="https://wa.me/'.$author_phone.'?text='.get_the_title($post_id).'" class="w-100">'.$whatsapp_icon.' Whatsapp</a></div>';
				echo '</div>';
			echo '</div>';
		echo '</div>';
	echo '</article>';
    
    return ob_get_clean();
});

// [velocity-iklan-loop post_id="" class=""]
add_shortcode('velocity-iklan-loop', function($atts) {
	global $post;
    $atribut = shortcode_atts( array(
        'class' 	=> 'block-primary h-100 p-2',
        'post_id' 	=> $post->ID
    ), $atts );
    $post_id = $atribut['post_id'];
    $class = $atribut['class'];
    $jenis = get_post_meta($post_id, 'jenis', true );
    $url_iklan  = get_post_meta($post_id,'wb-blog', true);
    $iklan_class = $jenis == 'premium' ? 'iklan-premium ' : 'iklan-biasa ';
	$post_classes = get_post_class($iklan_class.$class,$post_id);
	$html = '';
	$html .= '<article class="'.esc_attr(implode(' ',$post_classes)).'" id="post-'.$post_id.'">';
		$html .= '<div class="entry-content">';
			$html .= '<div class="ratio ratio-4x3 bg-light overflow-hidden mb-2">';
				$html .= '<a href="'.get_the_permalink($post_id).'">';
				if (has_post_thumbnail($post_id)) {
					$html .= get_the_post_thumbnail($post_id, 'medium', array('class' => 'w-100', 'loading' => 'lazy'));
				} else {
					$attachments = get_posts( array(
						'post_type'         => 'attachment',
						'posts_per_page'    => 1,
						'post_parent'       => $post_id,
					));
					if($attachments && isset($attachments[0]->ID)){
						$html .= wp_get_attachment_image( $attachments[0]->ID, 'medium', "", array('class' => 'w-100', 'loading' => 'lazy') );
					} else {
						$html .= '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" viewBox="0 0 60 60" style="padding: 2rem;background-color: #ececec;width: 100%;height: 100%;enable-background:new 0 0 60 60;" xml:space="preserve" width="600" height="300"><g><g><path d="M55.201,15.5h-8.524l-4-10H17.323l-4,10H12v-5H6v5H4.799C2.152,15.5,0,17.652,0,20.299v29.368   C0,52.332,2.168,54.5,4.833,54.5h50.334c2.665,0,4.833-2.168,4.833-4.833V20.299C60,17.652,57.848,15.5,55.201,15.5z M8,12.5h2v3H8   V12.5z M58,49.667c0,1.563-1.271,2.833-2.833,2.833H4.833C3.271,52.5,2,51.229,2,49.667V20.299C2,18.756,3.256,17.5,4.799,17.5H6h6   h2.677l4-10h22.646l4,10h9.878c1.543,0,2.799,1.256,2.799,2.799V49.667z" data-original="#000000" class="active-path" data-old_color="#000000" fill="#5F7D95"/><path d="M30,14.5c-9.925,0-18,8.075-18,18s8.075,18,18,18s18-8.075,18-18S39.925,14.5,30,14.5z M30,48.5c-8.822,0-16-7.178-16-16   s7.178-16,16-16s16,7.178,16,16S38.822,48.5,30,48.5z" data-original="#000000" class="active-path" data-old_color="#000000" fill="#5F7D95"/><path d="M30,20.5c-6.617,0-12,5.383-12,12s5.383,12,12,12s12-5.383,12-12S36.617,20.5,30,20.5z M30,42.5c-5.514,0-10-4.486-10-10   s4.486-10,10-10s10,4.486,10,10S35.514,42.5,30,42.5z" data-original="#000000" class="active-path" data-old_color="#000000" fill="#5F7D95"/><path d="M52,19.5c-2.206,0-4,1.794-4,4s1.794,4,4,4s4-1.794,4-4S54.206,19.5,52,19.5z M52,25.5c-1.103,0-2-0.897-2-2s0.897-2,2-2   s2,0.897,2,2S53.103,25.5,52,25.5z" data-original="#000000" class="active-path" data-old_color="#000000" fill="#5F7D95"/></g></g> </svg>';
					}							
				}
				$html .= '</a>';
			$html .= '</div>';
			$html .= '<div class="col-content">';
				$html .= '<div class="fw-bold mb-2">'.do_shortcode('[judul-post length="10"]').'</div>';
				$html .= '<div class="mb-1"><i class="fa fa-map-o pe-2" aria-hidden="true"></i> '.do_shortcode('[velocity-iklan-meta key="kota" post_id="'.$post_id.'"]').'</div>';
				if(get_post_type($post_id) == 'iklan'){
				    $html .= '<div class="mb-1"><i class="fa fa-tag pe-2" aria-hidden="true"></i> '.do_shortcode('[velocity-iklan-taxonomy taxonomy="kategori" post_id="'.$post_id.'"]').'</div>';
				} elseif(get_post_type($post_id) == 'banner'){
				    $html .= '<div class="mb-1"><i class="fa fa-tag pe-2" aria-hidden="true"></i> '.do_shortcode('[velocity-iklan-taxonomy taxonomy="lokasi" post_id="'.$post_id.'"]').'</div>';
				}
				$html .= '<div class="m-1"><a href="'.$url_iklan.'" class="w-100" target="_blank"><i class="fa fa-link" aria-hidden="true"></i> Kunjungi Link</a></div>';
			$html .= '</div>';
		$html .= '</div>';
	$html .= '</article>';
    return $html;
});


// [velocity-iklan-penjual]
add_shortcode('velocity-iklan-penjual', function($atts) {
    global $post;
    $atribut = shortcode_atts( array(
        'post_id'   => $post->ID,
        'author_id'   => '',
    ), $atts );
	$post_id = $atribut['post_id'];
	$author = $atribut['author_id'];
    $author_id = get_post_field( 'post_author', $post_id );
    if($author) {
        $author_id = $author;
    }
    $author_name = get_the_author_meta( 'display_name', $author_id );
    $author_email = get_the_author_meta( 'user_email', $author_id );
    $author_phone = get_user_meta($author_id, 'phone', true);
    $author_bio = get_user_meta($author_id, 'bio', true);
    $author_alamat = get_user_meta($author_id, 'alamat', true);
    $avatar_url = get_user_meta($author_id, 'avatar', true);
    $user_info = get_userdata($author_id);
    $user_registered = $user_info->user_registered;
    $lastlogin = get_user_meta($author_id, 'lastlogin', true);
    
    if (substr($author_phone, 0, 1) === '0') {
        $author_phone   = '62' . substr($author_phone, 1);
    }
    
    $html = '';
    $html .= '<div class="text-center border bg-light">';
        $html .= '<div class="p-2 bg-color-theme text-white fs-6">Detail Penjual</div>';
        $html .= '<div class="py-3">';
            if($avatar_url){
                $html .= '<img class="foto-profil-penjual" src="'.$avatar_url.'" />';
            } else {
                $html .= '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="text-black-50 p-4 bi bi-image" viewBox="0 0 16 16"><path d="M6.002 5.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0"/><path d="M2.002 1a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2zm12 1a1 1 0 0 1 1 1v6.5l-3.777-1.947a.5.5 0 0 0-.577.093l-3.71 3.71-2.66-1.772a.5.5 0 0 0-.63.062L1.002 12V3a1 1 0 0 1 1-1z"/></svg>';
            }
        $html .= '</div>';
        $html .= '<div class="border-top text-dark bg-muted p-3">';
            $html .= '<div class="fw-bold mb-2"><a class="text-dark" href="'.get_author_posts_url($author_id).'" title="'.$author_name.'">'.$author_name.'</a></div>';
            $html .= $author_bio;
            if($author_alamat){
                $html .= '<p class="border-top pt-2 mt-2 mb-0"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-house-door me-1" viewBox="0 0 16 16"> <path d="M8.354 1.146a.5.5 0 0 0-.708 0l-6 6A.5.5 0 0 0 1.5 7.5v7a.5.5 0 0 0 .5.5h4.5a.5.5 0 0 0 .5-.5v-4h2v4a.5.5 0 0 0 .5.5H14a.5.5 0 0 0 .5-.5v-7a.5.5 0 0 0-.146-.354L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293zM2.5 14V7.707l5.5-5.5 5.5 5.5V14H10v-4a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5v4z"/> </svg> '.$author_alamat.'</p>';
            }
            if($user_registered){
                $tgl = date('d/m/Y', strtotime($user_registered));
                $html .= '<p class="border-top pt-2 mt-2 mb-0"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar4-event me-1" viewBox="0 0 16 16"> <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5M2 2a1 1 0 0 0-1 1v1h14V3a1 1 0 0 0-1-1zm13 3H1v9a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1z"/> <path d="M11 7.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5z"/> </svg> Sejak '.$tgl.'</p>';
            }
            if($lastlogin){
                $tgllogin = human_time_diff(strtotime($lastlogin), strtotime(date('Y-m-d H:i:s')));
                $html .= '<p class="border-top pt-2 mt-2 mb-0"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clock me-1" viewBox="0 0 16 16"> <path d="M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71z"/> <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16m7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0"/> </svg> Aktif ' . $tgllogin . ' lalu</p>';
            }
            if($author_phone){
                $phone_icon = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-telephone me-1 align-middle" viewBox="0 0 16 16"> <path d="M3.654 1.328a.678.678 0 0 0-1.015-.063L1.605 2.3c-.483.484-.661 1.169-.45 1.77a17.6 17.6 0 0 0 4.168 6.608 17.6 17.6 0 0 0 6.608 4.168c.601.211 1.286.033 1.77-.45l1.034-1.034a.678.678 0 0 0-.063-1.015l-2.307-1.794a.68.68 0 0 0-.58-.122l-2.19.547a1.75 1.75 0 0 1-1.657-.459L5.482 8.062a1.75 1.75 0 0 1-.46-1.657l.548-2.19a.68.68 0 0 0-.122-.58zM1.884.511a1.745 1.745 0 0 1 2.612.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.68.68 0 0 0 .178.643l2.457 2.457a.68.68 0 0 0 .644.178l2.189-.547a1.75 1.75 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.6 18.6 0 0 1-7.01-4.42 18.6 18.6 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877z"/> </svg>';
                $html .= '<a href="tel:'.$author_phone.'" class="mt-2 w-100 btn-sm btn btn-outline-dark">'.$phone_icon.' Telepon</a>';
            }
            if($author_phone){
                $whatsapp_icon = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-whatsapp" viewBox="0 0 16 16"><path d="M13.601 2.326A7.85 7.85 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.9 7.9 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.9 7.9 0 0 0 13.6 2.326zM7.994 14.521a6.6 6.6 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.56 6.56 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592m3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.73.73 0 0 0-.529.247c-.182.198-.691.677-.691 1.654s.71 1.916.81 2.049c.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232"/></svg>';
                $html .= '<a href="https://wa.me/'.$author_phone.'?text='.get_the_title($post_id).'" class="mt-2 w-100 btn-sm btn btn-outline-dark">'.$whatsapp_icon.' Whatsapp</a>';
            }
            $email_icon = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-envelope me-1 align-middle" viewBox="0 0 16 16"><path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1zm13 2.383-4.708 2.825L15 11.105zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741M1 11.105l4.708-2.897L1 5.383z"/></svg>';
            $html .= '<a href="mailto:'.$author_email.'?subject='.get_the_title($post_id).'&body='.urlencode(get_the_permalink($post_id)).'" class="mt-2 w-100 btn-sm btn btn-outline-dark">'.$email_icon.' Email</a>';
        $html .= '</div>';
    $html .= '</div>';
    return $html;
});


// [velocity-iklan-galeri]
add_shortcode('velocity-iklan-galeri', function($atts){
    $atribut = shortcode_atts( array(
        'post_id'   => get_the_ID(),
    ), $atts );
	$post_id = $atribut['post_id'];
    
    // Mendapatkan post meta 'galeri' berdasarkan ID pos yang diberikan
    $galeri_ids = get_post_meta($post_id, 'gallery', true);
    
    // Mendapatkan URL thumbnail post
    $thumbnail_url = get_the_post_thumbnail_url($post_id, 'large');

    $id = rand(100, 999);
    $output = '';
    if ($thumbnail_url || $galeri_ids) {
        // Menginisialisasi variabel untuk menyimpan output HTML
        $output .= '<div class="velocity-iklan-galeri parent-container-'.$id.'">';
            $output .= '<div id="galeri-slider-'.$id.'" class="carousel carousel-dark slide" data-bs-ride="carousel">';
                $output .= '<div class="carousel-inner text-center">';
                
                    // Menambahkan thumbnail post sebagai slide pertama
                    if ($thumbnail_url) {
                        $output .= '<div class="carousel-item active">';
                            $output .= '<a href="' . $thumbnail_url . '">';
                                $output .= '<img src="' . $thumbnail_url . '" class="w-auto">';
                            $output .= '</a>';
                        $output .= '</div>';
                    }
                    
                if (!empty($galeri_ids)) {
                    // Loop melalui setiap ID gambar dalam galeri
                    foreach ($galeri_ids as $key => $galeri_id) {
                        // Mendapatkan URL gambar
                        $image_url = wp_get_attachment_image_url($galeri_id, 'large');
                        // Mengecek apakah ini gambar pertama dalam galeri
                        $active_class = ($key == 0 && !$thumbnail_url) ? ' active' : '';
                        // Membuat elemen HTML untuk setiap gambar dalam slider
                        $output .= '<div class="carousel-item' . $active_class . '">';
                            $output .= '<a href="' . $image_url . '">';
                                $output .= '<img src="' . $image_url . '" class="w-auto">';
                            $output .= '</a>';
                        $output .= '</div>';
                    }

                }
                
                $output .= '</div>';
                
                if (!empty($galeri_ids)) {
                    // Menambahkan tombol navigasi
                    $output .= '<button class="carousel-control-prev" type="button" data-bs-target="#galeri-slider-'.$id.'" data-bs-slide="prev">';
                        $output .= '<span class="carousel-control-prev-icon" aria-hidden="true"></span>';
                        $output .= '<span class="visually-hidden">Previous</span>';
                    $output .= '</button>';
                    
                    $output .= '<button class="carousel-control-next" type="button" data-bs-target="#galeri-slider-'.$id.'" data-bs-slide="next">';
                        $output .= '<span class="carousel-control-next-icon" aria-hidden="true"></span>';
                        $output .= '<span class="visually-hidden">Next</span>';
                    $output .= '</button>';
                }
            
            $output .= '</div>';
        $output .= '</div>';

        // Menambahkan script untuk Magnific Popup
        $output .= '<script>
            jQuery(document).ready(function($) {
                $(".parent-container-'.$id.'").magnificPopup({
                    delegate: "a",
                    type: "image",
                    gallery:{
                        enabled:true
                    }
                });
            });
        </script>';
    }

    return $output;
});

// [velocity-iklan-meta]
add_shortcode('velocity-iklan-meta', function($atts){
    $atribut = shortcode_atts( array(
        'key'   => '',
        'post_id'   => get_the_ID(),
    ), $atts );
	$post_id = $atribut['post_id'];
	$key = $atribut['key'];
    $value = get_post_meta($post_id, $key, true);
    $html = '';
    if(empty($key)){
        $html .= 'key is required, example: [velocity-iklan-meta key="detail"]';
    } elseif (is_array($value)) {
        $html .= '<table class="table">';
            $html .= '<tbody>';
                foreach($value as $str){
                    $cek = strpos($str, '=');
                    if($cek == true){
                        $nilai = explode('=',$str);
                        $html .= '<tr>';
                            $html .= '<td class="fw-bold">'.$nilai[0].'</td>';
                            $html .= '<td class="text-muted">'.$nilai[1].'</td>';
                        $html .= '</tr>';
                    } else {
                        $html .= '<tr>';
                            $html .= '<td class="fw-bold" colspan="2">'.$str.'</td>';
                        $html .= '</tr>';
                    }
                }
            $html .= '</tbody>';
        $html .= '</table>';
    } elseif($value) {
        $html .= $value;
    }
    return $html;
});

add_shortcode('banner_image', 'banner_image');
function banner_image($atts) {
    ob_start();
    // Default attributes
    $atts = shortcode_atts([
        'post_id'      => '',    // ID post banner spesifik
        'lokasi'  => '',    // Slug taxonomy lokasi
        'class'   => '',    // Custom CSS class
        'target'  => '_blank' // Link target (_blank, _self)
    ], $atts);

    // Siapkan argumen query
    $args = array(
        'post_type'      => 'banner',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'tax_query'      => [],
    );

    // Jika ada lokasi, tambahkan tax_query
    if (!empty($atts['lokasi'])) {
        $args['tax_query'][] = [
            'taxonomy' => 'lokasi',
            'field'    => 'slug',
            'terms'    => sanitize_text_field($atts['lokasi']),
        ];
    }

    // Jika ada post_id, tambahkan post__in dan ubah relasi tax_query jika perlu
    if (!empty($atts['post_id'])) {
        $args['post__in'] = [intval($atts['post_id'])];

        // Kalau ada tax_query juga, pastikan relasinya OR
        if (!empty($args['tax_query'])) {
            $args['tax_query']['relation'] = 'OR';
        }
    }

    // Get the banner post
    $query = new WP_Query($args);

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $featured_img = get_the_post_thumbnail(get_the_ID(), 'full', ['class' => 'banner-img ' . esc_attr($atts['class'])]);
            $link = get_post_meta(get_the_ID(), 'wb-blog', true);

            // Output
            if (!empty($link)) {
                echo '<div class="text-center mb-2">';
                    echo '<a href="'.esc_url($link).'" target="'.esc_attr($atts['target']).'">'.$featured_img.'</a>';
                echo '</div>';
            } else {
                echo '<div class="text-center mb-2">'.$featured_img.'</div>';
            }
        }
        wp_reset_postdata();
    } else {
        echo '<p class="banner-error">Banner tidak ditemukan.</p>';
    }
    
    return ob_get_clean();
}

// [view]
add_shortcode( 'view', 'velocity_hit' );
function velocity_hit(){
    global $post;
    $postID         = $post->ID;
    $count_key      = 'hit';
    $count          = get_post_meta($postID, $count_key, true);
    if($count==''){
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
        return "0 Kali";
    }
    return $count.' Kali';
}

// [judul-post length="10"]
add_shortcode('judul-post', 'judul_post');
function judul_post($atts){
    ob_start();
    global $post;

    $atribut = shortcode_atts( array(
        'length'      => 8,
    ), $atts );

    $length       = $atribut['length'];
    $title        = get_the_title($post->ID);
    $title        = wp_trim_words ($title, $length, '...');

    echo '<a href="'.get_the_permalink($post->ID).'" title="'.get_the_title($post->ID).'">'.$title.'</a>';
    return ob_get_clean();
}
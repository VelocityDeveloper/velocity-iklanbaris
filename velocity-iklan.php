<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://velocitydeveloper.com
 * @since             1.0.0
 * @package           Velocity_Iklan_Baris
 *
 * @wordpress-plugin
 * Plugin Name:       Velocity Iklan Baris
 * Plugin URI:        https://velocitydeveloper.com
 * Description:       Plugin iklan baris oleh Velocity Developer (hanya untuk klien Velocity Developer)
 * Version:           1.0.0
 * Author:            Velocity Developer
 * Author URI:        https://velocitydeveloper.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       velocity-iklan
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
/**
 * Define constants
 *
 * @since 1.0.0
 */
if ( ! defined( 'VELOCITY_IKLAN_VERSION' ) ) define( 'VELOCITY_IKLAN_VERSION' , '1.0.0' ); // Plugin version constant
if ( ! defined( 'VELOCITY_IKLAN_PLUGIN' ) )  define( 'VELOCITY_IKLAN_PLUGIN' , trim( dirname( plugin_basename( __FILE__ ) ), '/' ) ); // Name of the plugin folder eg - 'velocity-toko'
if ( ! defined( 'VELOCITY_IKLAN_PLUGIN_DIR' ) )	define( 'VELOCITY_IKLAN_PLUGIN_DIR'	, plugin_dir_path( __FILE__ ) ); // Plugin directory absolute path with the trailing slash. Useful for using with includes eg - /var/www/html/wp-content/plugins/velocity-iklan/
if ( ! defined( 'VELOCITY_IKLAN_PLUGIN_URL' ) )	define( 'VELOCITY_IKLAN_PLUGIN_URL'	, plugin_dir_url( __FILE__ ) ); // URL to the plugin folder with the trailing slash. Useful for referencing src eg - http://localhost/wp/wp-content/plugins/velocity-iklan/

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
// define( 'VELOCITY_IKLAN_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-velocity-iklan-activator.php
 */
function activate_velocity_iklan() {
	require_once VELOCITY_IKLAN_PLUGIN_DIR . 'includes/classes/class-velocity-iklan-activator.php';
	Velocity_Iklan_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-velocity-iklan-deactivator.php
 */
function deactivate_velocity_iklan() {
	require_once VELOCITY_IKLAN_PLUGIN_DIR . 'includes/classes/class-velocity-iklan-deactivator.php';
	Velocity_Iklan_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_velocity_iklan' );
register_deactivation_hook( __FILE__, 'deactivate_velocity_iklan' );

// Load everything
$includes = [
    'includes/classes/Database.php',                                                        // Class Database
    'includes/classes/Lokasi.php',                                                          // Class Lokasi
    'includes/classes/class-velocity-iklan-frontpost.php',                                  // Class Frontpost
    'includes/lib/meta-box/meta-box.php',                                                   // Libary Meta Box
	'includes/lib/meta-box-beaver-themer-integrator/meta-box-beaver-themer-integrator.php',	// Libary Meta Box Beaver Themer Integrator
    'admin/partials/velocity-iklan-admin-display.php',                                      // Admin load display
    'includes/lokasi.php',                                                                  // get Lokasi function
    'includes/ajax.php',                                                                    // Ajax Function
    'includes/classes/class-velocity-iklan-metabox.php',                                            // Class Meta Box
    'includes/post-type-taxonomy.php',                                                      // Post Type & Taxonomy
    'includes/shortcode.php',                                                               // Shortcode
    'includes/customizer.php',                                                              // Customizer
 ];
	
foreach( $includes as $include ) {
    require_once( VELOCITY_IKLAN_PLUGIN_DIR . $include );
}

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require VELOCITY_IKLAN_PLUGIN_DIR . 'includes/class-velocity-iklan.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_velocity_iklan() {

	$plugin = new Velocity_Iklan();
	$plugin->run();

}
run_velocity_iklan();

function velocityiklan_display_recaptcha() {
    echo '<div class="velocitytoko-recaptcha my-2">';
        if (class_exists('Velocity_Addons_Captcha')){
            $captcha = new Velocity_Addons_Captcha;
            $captcha->display();
        }
    echo '</div>';
}

/**
 * Validates the reCAPTCHA for the VelocityToko.
 *
 * This function checks if the reCAPTCHA verification is successful for the VelocityToko. 
 * If the reCAPTCHA verification fails, it returns the error message. Otherwise, it returns true.
 *
 * @throws Exception if an error occurs during the reCAPTCHA verification process.
 * @return bool|string Returns true if the reCAPTCHA verification is successful, otherwise returns the error message.
 */
function velocityiklan_validate_recaptcha() {
    if (class_exists('Velocity_Addons_Captcha')) {
        $captcha = new Velocity_Addons_Captcha();
        $verify = $captcha->verify();
        
        if (!$verify['success']) {
            return $verify['message'];
        }
    }
    return true;
}

// Number Format
function velocity_number_money($number = null) {
    if(empty($number))
    return false;

    return 'Rp '.number_format((float)$number,0,',','.');    
}

// List Paket Iklan
function list_paket($jml, $harga, $include_free = false) {
    $paket_options = [];
    
    // Tambahkan opsi gratis jika diminta
    if ($include_free) {
        $paket_options['gratis'] = 'Free (Aktif selama 7 hari)';  // Key: 'gratis', Value: 'Free'
    }
    
    // Generate opsi berbayar dengan format konsisten
    for ($i = 1; $i <= $jml; $i++) {
        // $key = '"'.$i.'"';  // Membuat key unik
        $value = $i . ' Bulan - ' . velocity_number_money($i * $harga);
        $paket_options[$i] = $value;
    }
    
    return $paket_options;
}

//Redirect user_logout jika mengakses wp-login atau wp-admin
add_action('init', 'redirect_wp_login_for_logged_out_users');
function redirect_wp_login_for_logged_out_users() {
    // Cek jika ini halaman login
    $is_login_page = (strpos($_SERVER['REQUEST_URI'], 'wp-login.php') !== false);

    // Jika user tidak login & mencoba akses wp-login.php (bukan logout action)
    if ( $is_login_page && !is_user_logged_in() && !isset($_GET['action']) ) {
        wp_redirect( home_url('/mylogin') ); // Ganti dengan URL login kamu
        exit;
    }
}

// mendapatkan waktu terakhir user login
function velocity_iklan_last_login() {
    if (is_user_logged_in()) {
        $current_user = wp_get_current_user();
        // Mengambil semua nilai meta data 'lastlogin'
        $lastlogin = get_user_meta($current_user->ID, 'lastlogin', false);
        // Memperbarui atau menambahkan meta data 'lastlogin'
        update_user_meta($current_user->ID, 'lastlogin', date("Y-m-d H:i:s"));
    }
}
add_action('wp', 'velocity_iklan_last_login');

add_filter( 'human_time_diff', function($since, $diff, $from, $to) {
    $replace = array(
        'second'  => 'detik',
        'seconds'  => 'detik',
        'min'  => 'menit',
        'mins'  => 'menit',
        'hour'  => 'jam',
        'hours' => 'jam',
        'day'   => 'hari',
        'days'  => 'hari',
        'week'   => 'minggu',
        'weeks'  => 'minggu',
        'month'   => 'bulan',
        'months'  => 'bulan',
        'year'   => 'tahun',
        'years'  => 'tahun',
    );
    return strtr($since, $replace);
}, 10, 4 );


// Tambahkan kolom ke halaman admin untuk menampilkan jumlah post_type 'iklan' untuk setiap pengguna
function custom_users_columns($column_headers) {
    $column_headers['total_iklan'] = 'Iklan';
    return $column_headers;
}
add_filter('manage_users_columns', 'custom_users_columns');

function custom_users_column_content($value, $column_name, $user_id) {
    if ($column_name === 'total_iklan') {
        $total_iklan = count_user_posts($user_id, 'iklan');
        return $total_iklan;
    }
    return $value;
}
add_filter('manage_users_custom_column', 'custom_users_column_content', 10, 3);



// Menambahkan field gambar pada halaman edit taxonomy 'kategori'
function custom_taxonomy_image_field($term) {
    // Ambil nilai ID gambar yang tersimpan, jika ada
    $image_id = get_term_meta($term->term_id, 'image_id', true);
    ?>
    <tr class="form-field">
        <th scope="row" valign="top">
            <label for="kategori_image"><?php _e('Kategori Image'); ?></label>
        </th>
        <td>
            <?php 
			$image_url = '';
			$delete_button = '';
			if ($image_id) {
				$image_url = wp_get_attachment_url($image_id);
				$delete_button = '<div class="button button-link-delete remove_kategori_image">Remove Image</div>';
				echo '<input type="hidden" name="image_exist" id="image_exist" value="yes">';
			} ?>
			<div class="velocity-image-frame">
				<img class="kategori_image" src="<?php echo $image_url;?>" style="max-width: 150px;">				
			</div>
            <input type="hidden" name="image_id" id="image_id" value="<?php echo $image_id; ?>">
            <input type="button" class="button button-secondary" value="<?php _e('Upload/Select Image'); ?>" id="upload-kategori-image">
            <?php echo $delete_button;?>
			<?php wp_enqueue_media(); ?>
			<script>
				jQuery(document).ready(function($) {
					$('#upload-kategori-image').click(function() {
						var custom_uploader = wp.media({
							title: 'Upload Image',
							button: {
								text: 'Use This Image'
							},
							multiple: false
						});
						custom_uploader.on('select', function() {
							var attachment = custom_uploader.state().get('selection').first().toJSON();
							$('#image_id').val(attachment.id);
							$('.kategori_image').attr('src', attachment.url);
						});
						custom_uploader.open();
					});
					$('.remove_kategori_image').click(function() {
						$('#image_id').val('');
						$('.kategori_image').attr('src', '');
					});
				});
			</script>
        </td>
    </tr>
	<?php  
}
add_action('kategori_edit_form_fields', 'custom_taxonomy_image_field', 10, 2);

// Simpan data ID gambar saat menyimpan taxonomy 'kategori'
function save_taxonomy_image_field($term_id) {
    if (isset($_POST['image_id'])) {
        $image_id = $_POST['image_id'];
        update_term_meta($term_id, 'image_id', $image_id);
    } elseif (isset($_POST['image_exist']) && empty($_POST['image_id'])) {
        delete_term_meta($term_id, 'image_id');
    }
}
add_action('edited_kategori', 'save_taxonomy_image_field', 10, 2);


// menampilkan gambar pada taxonomy 
function velocity_term_image($term_id = null, $size = 'thumbnail', $attr = array()) {
	if($term_id){
		$image_id = get_term_meta($term_id, 'image_id', true);
		if($image_id){
			$html = wp_get_attachment_image($image_id,$size,false,$attr);
		} else {
			$no_image = plugins_url('/', __FILE__).'public/img/no-image.png';
			$class = $attr['class'] ? ' class="'.$attr['class'].'"' : '';
			$html = '<img'.$class.' src="'.$no_image.'" />';
		}
		return $html;
	} else {
		return false;
	}
}

// Fungsi untuk menyembunyikan admin bar
function hide_admin_bar_for_non_admin_logged_in() {
    if (is_user_logged_in() && !current_user_can('administrator')) {
        show_admin_bar(false);
    }
}
add_action('after_setup_theme', 'hide_admin_bar_for_non_admin_logged_in');

// Fungsi untuk memblokir akses ke wp-admin
function block_wp_admin_for_non_admin_logged_in() {
    if (is_user_logged_in() && !current_user_can('administrator') && !(defined('DOING_AJAX') && DOING_AJAX)) {
    //if (is_user_logged_in() && !current_user_can('administrator')) {
        wp_redirect(home_url());
        exit;
    }
}
add_action('admin_menu', 'block_wp_admin_for_non_admin_logged_in');

// menambah link pada menu
function add_logout_link_to_menu($items, $args) {
	$page = get_page_by_path('akun-saya');
	if ($page) {
		$page_id = $page->ID; 
		$page_title = $page->post_title;
		$page_permalink = get_permalink($page_id);
	}
    if ($args->theme_location == 'primary' && is_user_logged_in() && $page) {
		if ($page) {
			$logout_url = wp_logout_url(get_home_url());
			$items .= '<li itemscope="itemscope" itemtype="https://www.schema.org/SiteNavigationElement" class="menu-item menu-item-type-post_type menu-item-object-page current-menu-item page_item current_page_item menu-item-has-children dropdown active nav-item"><a title="Akun Saya" href="'.$page_permalink.'" data-toggle="dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="dropdown-toggle nav-link" aria-current="page"><span data-href="'.$page_permalink.'">'.$page_title.'</span></a>
			<ul class="dropdown-menu">
				<li itemscope="itemscope" itemtype="https://www.schema.org/SiteNavigationElement" class="menu-item menu-item-type-custom menu-item-object-custom nav-item"><a title="Iklan Saya" href="'.$page_permalink.'?hal=dashboard" class="dropdown-item">Iklan Saya</a></li>
				<li itemscope="itemscope" itemtype="https://www.schema.org/SiteNavigationElement" class="menu-item menu-item-type-custom menu-item-object-custom nav-item"><a title="Pasang Iklan" href="'.$page_permalink.'?hal=pasang-iklan" class="dropdown-item">Pasang Iklan</a></li>
				<li itemscope="itemscope" itemtype="https://www.schema.org/SiteNavigationElement" class="menu-item menu-item-type-custom menu-item-object-custom nav-item"><a title="Pasang Iklan" href="'.$page_permalink.'?hal=pasang-banner" class="dropdown-item">Pasang Banner</a></li>
				<li itemscope="itemscope" itemtype="https://www.schema.org/SiteNavigationElement" class="menu-item menu-item-type-custom menu-item-object-custom nav-item"><a title="Pasang Iklan" href="'.$page_permalink.'?hal=pasang-link" class="dropdown-item">Pasang Link</a></li>
				<li itemscope="itemscope" itemtype="https://www.schema.org/SiteNavigationElement" class="menu-item menu-item-type-custom menu-item-object-custom nav-item"><a title="Profil" href="'.$page_permalink.'?hal=ubah-profil" class="dropdown-item">Profil</a></li>
				<li itemscope="itemscope" itemtype="https://www.schema.org/SiteNavigationElement" class="menu-item menu-item-type-custom menu-item-object-custom nav-item"><a title="Keluar" href="'.esc_url($logout_url).'" class="dropdown-item">Keluar</a></li>
			</ul>
			</li>';
		}		
    } elseif ($args->theme_location == 'primary' && $page) {
        $items .= '<li class="menu-item menu-item-type-custom menu-item-object-custom nav-item"><a class="nav-link" href="' . $page_permalink . '">'.$page_title.'</a></li>';
	}
    return $items;
}
add_filter('wp_nav_menu_items', 'add_logout_link_to_menu', 10, 2);


// ubah login logo pada wp-admin
function velocity_iklan_login_logo() {
    $custom_logo_id = get_theme_mod( 'custom_logo' );
    $image_url = wp_get_attachment_image_src( $custom_logo_id , 'full' );
    if($custom_logo_id){ ?>
    <style type="text/css">
        #login h1 a, .login h1 a {
            background-image: url(<?php echo $image_url[0]; ?>);
            height: 65px;
            width: 320px;
            background-size: 320px auto;
            background-repeat: no-repeat;
            padding-bottom: 30px;
        }
    </style>
<?php }
}
add_action( 'login_enqueue_scripts', 'velocity_iklan_login_logo' );


// Menambahkan kemampuan mengelola media mereka sendiri pada peran Subscriber
function modify_subscriber_role() {
    $subscriber = get_role('subscriber');

    // Menambahkan kemampuan mengelola media mereka sendiri
    $subscriber->add_cap('upload_files');
    $subscriber->add_cap('delete_posts'); // Memberi kemampuan menghapus media mereka sendiri
    $subscriber->add_cap('edit_posts'); // Memberi kemampuan mengedit media mereka sendiri
}
add_action('init', 'modify_subscriber_role');

// Filter untuk membatasi tampilan media
function filter_media_library($query) {
    // Hanya jalankan filter jika pengguna adalah 'subscriber'
    if (current_user_can('subscriber')) {
        $query['author'] = get_current_user_id();
    }
    return $query;
}
add_filter('ajax_query_attachments_args', 'filter_media_library');


// judul
function vmpc_title(){
    if (is_category()) {
        $title = single_cat_title('', false);
    } elseif (is_tag()) {
        $title = single_tag_title('', false);
    } elseif (is_author()) {
        $title = get_the_author();
	} elseif (is_post_type_archive()) {
		$post_type = post_type_archive_title('', false);
		if ($post_type) {
			$title = $post_type;
		}
	} elseif (is_tax()) {
		$taxonomy = single_term_title('', false);
		if ($taxonomy) {
			$title = $taxonomy;
		}
    } else {
		$title = wp_title('');
	}
	return $title;
}

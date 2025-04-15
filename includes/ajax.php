<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'wp_ajax_ajaxlogin', 'ajax_login' );
add_action( 'wp_ajax_nopriv_ajaxlogin', 'ajax_login' );
function ajax_login(){

    // First check the nonce, if it fails the function will break
    check_ajax_referer( 'ajax-login-nonce', 'security' );

    // Nonce is checked, get the POST data and sign user on
    $info                           = array();
    $info['user_login']             = $_POST['username'];
    $info['user_password']          = $_POST['password'];
    $info['g-recaptcha-response']   = $_POST['g-recaptcha-response'];
    $info['remember']               = true;
    
    if (is_ssl()) {
        $sll = true;
    } else {
        $sll = false;
    }
    $user_signon = wp_signon( $info, $sll );
    if ( is_wp_error($user_signon) ){
        echo json_encode(array('loggedin'=>false, 'message'=>$user_signon->get_error_message() ));
    } else {
        echo json_encode(array('loggedin'=>true, 'message'=>__('Login successful, redirecting...')));
    }
    
    die();
}


// Add custom action for updating avatar
add_action( 'wp_ajax_nopriv_update_avatar_action', 'update_avatar_action_ajax' );
add_action('wp_ajax_update_avatar_action', 'update_avatar_action_ajax');
function update_avatar_action_ajax() {
    // Check nonce field
    if (!isset($_POST['update_avatar_nonce']) || !wp_verify_nonce($_POST['update_avatar_nonce'], 'update_avatar_nonce')) {
        wp_die('Akses tidak sah.');
    }

    // Check if avatar URL is set
    if (!empty($_POST['avatar_url'])) {
        // Sanitize avatar URL
        $avatar_url = esc_url($_POST['avatar_url']);

        // Update user avatar
        $user_id = get_current_user_id();
        update_user_meta($user_id, 'avatar', $avatar_url);
    }
}

// hapus iklan
add_action('wp_ajax_deleteproduct', 'deleteproduct_ajax');
function deleteproduct_ajax() {
    $id = isset($_POST['id']) ? $_POST['id'] : '';
    if($id){
        wp_delete_post($id);
    }
    wp_die();
}

// pengajuan iklan premium
add_action('wp_ajax_iklanpremium', 'iklanpremium_ajax');
function iklanpremium_ajax() {
    $id = isset($_POST['id']) ? $_POST['id'] : '';
    if($id){
        update_post_meta($id, 'jenis', 'pengajuan' );
    }
    wp_die();
}

// tindakan admin untuk iklan premium
add_action('wp_ajax_konfirmasipremium', 'konfirmasipremium_ajax');
function konfirmasipremium_ajax() {
    $id = isset($_POST['id']) ? $_POST['id'] : '';
    $confirm = isset($_POST['confirm']) ? $_POST['confirm'] : '';
    if($id && $confirm == 'Hapus'){
        delete_post_meta( $id, 'jenis');
    } elseif($id && $confirm == 'Terima'){
        update_post_meta($id, 'jenis', 'premium' );
    }
    wp_die();
}

add_action('wp_ajax_updatepost', 'updatepost_ajax');
function updatepost_ajax() {
    $time = current_time('mysql');
    $id             = isset($_POST['dataid']) ? $_POST['dataid'] : '';
    wp_update_post(
        array (
            'ID'            => $id, // ID of the post to update
            'post_date'     => $time,
            'post_status'   =>  'publish',
            'post_date_gmt' => get_gmt_from_date( $time )
        )
    );
    
    
    wp_die();
}

add_action('wp_ajax_oldpost', 'oldpost_ajax');
function oldpost_ajax() {
    $id             = isset($_POST['dataid']) ? $_POST['dataid'] : '';
    wp_update_post(array(
        'ID'    =>  $id,
        'post_status'   =>  'draft'
    ));
    wp_die();
}

add_action('wp_ajax_nopriv_kecamatan', 'kecamatan_ajax');
add_action('wp_ajax_kecamatan', 'kecamatan_ajax');
function kecamatan_ajax() {
    $a                  = isset($_POST['city_destination']) ? $_POST['city_destination'] : '';
    $data_Subdistrict   = getSubdistrict($a);
    echo "<option value=''>Kecamatan</option>";
    if($data_Subdistrict) {
        for ($x=0; $x < count($data_Subdistrict); $x++) {
            echo "<option value='".$data_Subdistrict[$x]['subdistrict_id']."' class='". $data_Subdistrict[$x]['city_id']."' >".$data_Subdistrict[$x]['subdistrict_name']."</option>";
        }
    }
    wp_die();
}

add_action('wp_ajax_nopriv_kota', 'kota_ajax');
add_action('wp_ajax_kota', 'kota_ajax');
function kota_ajax() {
    $prov  = isset($_POST['prov_destination']) ? $_POST['prov_destination'] : '';
    echo "<option value=''>Kota</option>";
	  $data_City    = getCity();
      for ($x=0; $x < count($data_City); $x++) {
          if($prov==$data_City[$x]['province_id']) {
            $type = $data_City[$x]['type'];
            if( $type == 'Kabupaten'){
                $type = 'Kab';
            }
            echo "<option value='".$data_City[$x]['city_id']."' class='". $data_City[$x]['province_id']."' >".$type." ".$data_City[$x]['city_name']."</option>";
          }
      }
    wp_die();
}
<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class Database
{

    public $wpdb;
    public $tb_province;
    public $tb_city;
    public $tb_subdistric;
    public $tb_keranjang;
    public $tb_order;
    public $tb_kupon;
    public $tb_ongkir;

    function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->tb_province = $wpdb->prefix . 'vd_province';
        $this->tb_city = $wpdb->prefix . 'vd_city';
        $this->tb_subdistric = $wpdb->prefix . 'vd_subdistricts';
    }

    public function init_action(){
        add_action('admin_footer', [$this,'admin_footer_action']);
    }

    public function admin_footer_action(){
        $this->create_tables();
    }

    public function create_tables()
    {
        //versi database velocity toko
        // update_option('velocity_toko_db_version', 1);
        if (get_option('velocity_toko_db_version', 1) < 14) {

            global $wpdb;

            //update versi database velocity toko
            update_option('velocity_toko_db_version', 15);

            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

            
            $sql = "CREATE TABLE IF NOT EXISTS $this->tb_province
            (
                province_id INT NOT NULL,
                province varchar(255) NOT NULL,
                code varchar(255) NOT NULL,
                PRIMARY KEY  (province_id)
            );  
            ";
            dbDelta($sql);
            
            $sql = "CREATE TABLE IF NOT EXISTS $this->tb_city
            (
                city_id INT NOT NULL,
                province_id INT NOT NULL,
                province varchar(255) NOT NULL,
                type varchar(255) NOT NULL,
                city_name varchar(255) NOT NULL,
                postal_code INT NOT NULL,
                PRIMARY KEY  (city_id)
            );  
            ";
            dbDelta($sql);

            $sql = "CREATE TABLE IF NOT EXISTS $this->tb_subdistric
            (
                subdistrict_id INT NOT NULL,
                city_id INT NOT NULL,
                province_id INT NOT NULL,
                province varchar(255) NOT NULL,
                type varchar(255) NOT NULL,
                city varchar(255) NOT NULL,
                subdistrict_name varchar(255) NOT NULL,
                PRIMARY KEY  (subdistrict_id)
            );  
            ";
            dbDelta($sql);

            $wpdb->query($sql);

        }
    }
}

$database = new Database();
$database->init_action();
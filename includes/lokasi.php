<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


function getCityProvince() {
    // delete_option( 'daftarkotadiprovinsi');
    $get_data = get_option( 'daftarkotadiprovinsi');
    
    if($get_data) {
        $respon = $get_data;
    } else {
        $Lokasi = new Lokasi();
        $city = $Lokasi->city();
        $kota = [];
        //$kota[0] = 'Pilih kota asal';
        foreach($city as $data){
            $kota[$data['city_id']] = $data['type'].' '.$data['city_name'].' - '.$data['province'];
        }
        update_option( 'daftarkotadiprovinsi', $kota, '', 'yes' );
        $respon = get_option( 'daftarkotadiprovinsi', true );
    }
    
    return $respon;
}

function getProvince($id=null) {
    $Lokasi = new Lokasi();
    return $Lokasi->province($id); 
}

function getCity($id=null) {
    $Lokasi = new Lokasi();
    return $Lokasi->city($id); 
}

function getSubdistrict($a) {
    $Lokasi = new Lokasi();
    return $Lokasi->subdistrictbycity($a); 
}
function getSingleSubdistrict($id) {
    $Lokasi = new Lokasi();
    return $Lokasi->subdistrict($id); 
}
function getSingleCity($id) {
    $Lokasi = new Lokasi();
    return $Lokasi->city($id); 
}

//Fungsi Dropdown
function velocity_list_provinsi() {
    $Lokasi     = new Lokasi();
    $getdata    = $Lokasi->province();
    $result     = [];

    if($getdata){
        foreach ($getdata as $key => $data) {
            $nameporv = $data['province'];
            $result[$nameporv] = $nameporv;            
        }
    }
    return $result;
}

function velocity_list_kota() {
    $choice     = isset($_POST['parent_option']) ? $_POST['parent_option'] : '';
    $Lokasi     = new Lokasi();
    $getdata    = $Lokasi->city();
    $result     = [];

    if($getdata){
        foreach ($getdata as $key => $data) {
            if($choice === $data['province']) {
                $namecity = $data['type'].' '.$data['city_name'];
                $result[$namecity] = $namecity; 
            }           
        }
    }
    return $result;
}

function velocity_list_kecamatan($opt=null) {
    $choice     = isset($_POST['parent_option']) ? $_POST['parent_option'] : $opt;
    $choice     = substr(strstr($choice," "), 1);
    $Lokasi     = new Lokasi();
    $getdata    = $Lokasi->getCity();
    $result     = [];
    $city_id    = '';

    if($getdata){
        foreach ($getdata as $key => $data) {
            if($data->city_name == $choice){
                $city_id = $data->city_id; 
            }
        }
        $getSubd = getSubdistrict($city_id);
        if($getSubd){
            foreach ($getSubd as $key => $data) {
                $result[$data['subdistrict_name']] = $data['subdistrict_name']; 
            }
        }
    }
    return $result;
}
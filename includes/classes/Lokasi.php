<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class Lokasi
{

    public $wpdb;
    public $tableprov;
    public $tablecity;
    public $tablesubdistric;
    private $key = "b42467445e8f435ac91a173e55f4fa54";

    function __construct($tableprov = 'vd_province', $tablecity = 'vd_city', $tablesubdistric = 'vd_subdistricts')
    {
        global $wpdb;
        $this->wpdb             = $wpdb;
        $this->tableprov        = $wpdb->prefix . $tableprov;
        $this->tablecity        = $wpdb->prefix . $tablecity;
        $this->tablesubdistric  = $wpdb->prefix . $tablesubdistric;     
    }

    public function create_prov_table()
    {
        $sql = "CREATE TABLE IF NOT EXISTS $this->tableprov
        (
            province_id INT NOT NULL,
            province varchar(255) NOT NULL,
            code varchar(255) NOT NULL,
            PRIMARY KEY  (province_id)
        );  
        ";
        dbDelta($sql);
    }

    public function create_city_table()
    {
        $sql = "CREATE TABLE IF NOT EXISTS $this->tablecity
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
    }

    public function create_subd_table()
    {
        $sql = "CREATE TABLE IF NOT EXISTS $this->tablesubdistric
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
    }

    /// add data Province
    public function addProvince($provid = null, $prov = null, $code = null)
    {
        if ($provid && $prov && $code) {
            //check if avalibe
            $getdata = $this->wpdb->get_results("SELECT * FROM $this->tableprov WHERE province_id = $provid");
            if (empty($getdata)) { //if empty. insert new data
                $this->wpdb->insert($this->tableprov, array(
                    'province_id'   => $provid,
                    'province'      => $prov,
                    'code'          => $code,
                ));
            }
        }
    }

    /// get data Province
    public function getProvince($provid = null)
    {
        if ($provid) {
            $getdata = $this->wpdb->get_results("SELECT * FROM $this->tableprov WHERE province_id = $provid");
        } else {
            $getdata = $this->wpdb->get_results("SELECT * FROM $this->tableprov ORDER BY province_id ASC");
        }
        return $getdata;
    }

    /// province func
    public function province($id = null)
    {
        $sumProv = $this->wpdb->get_var("SELECT COUNT(*) FROM $this->tableprov");
        //jika data prov kurang dari 34
        if (34 > $sumProv) {
            $remoteProv = wp_remote_get('http://pro.rajaongkir.com/api/province', ['headers' => ['key' => $this->key]]);
            if (is_string($remoteProv['body']) && is_array(json_decode($remoteProv['body'], true)['rajaongkir']['results'])) {
                foreach (json_decode($remoteProv['body'], true)['rajaongkir']['results'] as $val) {
                    $this->addProvince($val['province_id'], $val['province'], $val['province_id']);
                }
            }
        }

        if ($id != null) {
            $result = $this->getProvince($id);
        } else {
            $result = $this->getProvince();
        }

        $result = json_encode($result);
        $result = json_decode($result, true);
        return $result;
    }

    /// add data City
    public function addCity($cityid = null, $provid = null, $prov = null, $type = null, $cityname = null, $postal_code = null)
    {
        if ($cityid && $provid && $cityname) {
            //check if avalibe
            $getdata = $this->wpdb->get_results("SELECT * FROM $this->tablecity WHERE city_id = $cityid");
            if (empty($getdata)) { //if empty. insert new data
                $this->wpdb->insert($this->tablecity, array(
                    'city_id'       => $cityid,
                    'province_id'   => $provid,
                    'province'      => $prov,
                    'type'          => $type,
                    'city_name'     => $cityname,
                    'postal_code'   => $postal_code,
                ));
            }
        }
    }
    /// get data City
    public function getCity($cityid = null)
    {
        if ($cityid) {
            $getdata = $this->wpdb->get_results("SELECT * FROM $this->tablecity WHERE city_id = $cityid");
        } else {
            $getdata = $this->wpdb->get_results("SELECT * FROM $this->tablecity ORDER BY city_id ASC");
        }
        return $getdata;
    }

    //city func
    public function city($id = null)
    {
        $sumCity = $this->wpdb->get_var("SELECT COUNT(*) FROM $this->tablecity");

        //jika data prov kurang dari 501
        if (501 > $sumCity) {
            $remoteCity = wp_remote_get('http://pro.rajaongkir.com/api/city', ['headers' => ['key' => $this->key]]);
            if (is_string($remoteCity['body']) && is_array(json_decode($remoteCity['body'], true)['rajaongkir']['results'])) {
                $resultcity = json_decode($remoteCity['body'], true)['rajaongkir'];
                if ($resultcity['status']['code'] == 200) {
                    foreach (json_decode($remoteCity['body'], true)['rajaongkir']['results'] as $val) {
                        $this->addCity($val['city_id'], $val['province_id'], $val['province'], $val['type'], $val['city_name'], $val['postal_code']);
                    }
                }
            }
        }

        if ($id != null) {
            $result = $this->getCity($id);
        } else {
            $result = $this->getCity();
        }

        $result = json_encode($result);
        $result = json_decode($result, true);
        return $result;
    }

    /// add data City
    public function addSubdistrict($subdid = null, $cityid = null, $provid = null, $prov = null, $type = null, $city = null, $subdistrictname = null)
    {
        if ($subdid && $cityid && $provid && $subdistrictname) {
            //check if avalibe
            $getdata = $this->wpdb->get_results("SELECT * FROM $this->tablesubdistric WHERE subdistrict_id = $subdid");
            if (empty($getdata)) { //if empty. insert new data
                $this->wpdb->insert($this->tablesubdistric, array(
                    'subdistrict_id'    => $subdid,
                    'city_id'           => $cityid,
                    'province_id'       => $provid,
                    'province'          => $prov,
                    'type'              => $type,
                    'city'              => $city,
                    'subdistrict_name'  => $subdistrictname,
                ));
            }
        }
    }
    /// get data Subdistrict
    public function getSubdistrict($id = null, $type = null)
    {
        if ($type == 'subdistrict') {
            $getdata = $this->wpdb->get_results("SELECT * FROM $this->tablesubdistric WHERE subdistrict_id = $id");
        } elseif ($type == 'city') {
            $getdata = $this->wpdb->get_results("SELECT * FROM $this->tablesubdistric WHERE city_id = $id ORDER BY subdistrict_id ASC");
        } else {
            $getdata = $this->wpdb->get_results("SELECT * FROM $this->tablesubdistric ORDER BY subdistrict_id ASC");
        }
        return $getdata;
    }

    public function subdistrictbycity($id = null)
    {
        if ($id != null) {
            $datasubdistrict = $this->getSubdistrict($id, 'city');
            if (empty($datasubdistrict)) {
                $remoteSub = wp_remote_get("http://pro.rajaongkir.com/api/subdistrict?city=$id", ['headers' => ['key' => $this->key]]);
                if (is_string($remoteSub['body']) && is_array(json_decode($remoteSub['body'], true)['rajaongkir']['results'])) {
                    foreach (json_decode($remoteSub['body'], true)['rajaongkir']['results'] as $val) {
                        $this->addSubdistrict($val['subdistrict_id'], $val['city_id'], $val['province_id'], $val['province'], $val['type'], $val['city'], $val['subdistrict_name']);
                    }
                }
                $result = $this->getSubdistrict($id, 'city');
            } else {
                $result = $datasubdistrict;
            }
            $result = json_encode($result);
            $result = json_decode($result, true);
        } else {
            $result = '';
        }

        return $result;
    }

    public function subdistrict($id = null)
    {
        $result = '';
        if ($id != null) {
            $datasubdistrict = $this->getSubdistrict($id, 'subdistrict');
            if (empty($datasubdistrict)) {
                $singleSub = wp_remote_get("http://pro.rajaongkir.com/api/subdistrict?id=$id", ['headers' => ['key' => $this->key]]);
                if (is_string($singleSub['body']) && is_array(json_decode($singleSub['body'], true)['rajaongkir']['results'])) {
                    $body   = json_decode($singleSub['body'], true);
                    $idcity = isset($body['rajaongkir']['results']['city_id']) ? $body['rajaongkir']['results']['city_id'] : '';

                    $remoteSub = wp_remote_get("http://pro.rajaongkir.com/api/subdistrict?city=$idcity", ['headers' => ['key' => $this->key]]);
                    if (is_string($remoteSub['body']) && is_array(json_decode($remoteSub['body'], true))) {
                        foreach (json_decode($remoteSub['body'], true)['rajaongkir']['results'] as $val) {
                            $this->addSubdistrict($val['subdistrict_id'], $val['city_id'], $val['province_id'], $val['province'], $val['type'], $val['city'], $val['subdistrict_name']);
                        }
                    }
                }
                $result = $this->getSubdistrict($id, 'subdistrict');
            } else {
                $result = $datasubdistrict;
            }
            $result = json_encode($result);
            $result = json_decode($result, true);
        }
        return $result;
    }
}


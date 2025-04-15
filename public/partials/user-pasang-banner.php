<?php
$current_user   = wp_get_current_user();
$userId         = $current_user->ID;
$kota_users      = getCity(get_user_meta($userId, 'city_destination', true));

$action         = isset($_GET['action'])?$_GET['action']:'';
$idpost         = isset($_GET['id'])?$_GET['id']:'';
$harga_banner   = get_theme_mod('biaya_banner');
$list_pakets    = list_paket(20, $harga_banner, false);

$action     = $action=='edit'?'edit':'add';
$post_status    = $action=='edit'?'publish':'pending';

$args       = [
    'post_type' => 'banner',
    'post_status'   => $post_status,
];
$metakey    = [
    'lama'=> [
        'type'      => 'select',
        'title'     => 'Paket',
        'desc'      => 'Pilih paket iklan banner',
        'required'  => true,
        'options'   => $list_pakets,
    ],
    'post_title'    => [
        'type'      => 'text',
        'title'     => 'Judul',
        'desc'      => 'Judul / Nama produk',
        'required'  => true,
    ],
    'wb-blog'    => [
        'type'      => 'text',
        'title'     => 'URL Web/Blog*',
        'desc'      => 'Isi dengan url',
        'required'  => false,
    ],
    'lokasi'        => [
        'type'      => 'taxonomy',
        'title'     => 'Lokasi Produk',
        'desc'      => '',
        'required'  => true,
    ],
    '_thumbnail_id'=> [
        'type'      => 'featured',
        'title'     => 'Gambar',
        'desc'      => 'Foto Utama',
        'required'  => true,
    ],
    'nama'=> [
        'type'      => 'hidden',
        'title'     => 'Nama',
        'desc'      => '',
        'default'   => $current_user->first_name,
        'required'  => false,
    ],
    'alamatemail'=> [
        'type'      => 'hidden',
        'title'     => 'Email',
        'desc'      => '',
        'default'   => $current_user->user_email,
        'required'  => false,
    ],
    'kota'=> [
        'type'      => 'hidden',
        'title'     => 'Kota',
        'desc'      => '',
        'default'   => $kota_users[0]['city_name'],
        'required'  => false,
    ],
    'hp'=> [
        'type'      => 'hidden',
        'title'     => 'HP',
        'desc'      => '',
        'default'   => get_user_meta($userId, 'phone', true),
        'required'  => false,
    ],
];

if($action=='edit' && $idpost){
    $args['ID'] = $idpost;
}

$form = New Frontpost();

$titlecard = $action=='add'?'<i class="fa fa-plus-circle me-1"></i> Pasang Iklan':' <i class="fa fa-pencil me-1"></i> Edit Iklan';
echo '<div class="card shadow mx-auto my-3">';
    echo '<div class="card-header">';
        echo '<span class="font-weight-bold fs-5">'.$titlecard.'</span>';
    echo '</div>';
    echo '<div class="card-body p-md-4">';
        echo $form->formPost($args,$action,$metakey);
    echo '</div>';
echo '</div>';

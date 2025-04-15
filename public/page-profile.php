<?php $hal = isset($_GET['hal']) ? $_GET['hal'] : ''; ?>
<div class="container py-3">
  <?php if ( is_user_logged_in() ) { ?>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
      <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav">
            <?php 
            $menus = array(
                'dashboard' => 'Dashboard',
                'iklan-saya' => 'Iklan Saya',
                'link-saya' => 'Link Saya',
                'banner-saya' => 'Banner Saya',
                // 'pasang-iklan' => 'Pasang Iklan',
                // 'pasang-banner' => 'Pasang Banner',
                // 'pasang-link' => 'Pasang Link',
                'ubah-profil' => 'Profil',
            );
            foreach($menus as $slug => $name){
                $class = $hal == $slug || $slug == 'dashboard' && empty($hal) ? ' active' : '';
                echo '<li class="nav-item">';
                    echo '<a class="nav-link'.$class.'" href="?hal='.$slug.'">'.$name.'</a>';
                echo '</li>';
            } ?>
            <li class="nav-item">
              <a class="nav-link" href="<?php echo wp_logout_url(get_home_url()); ?>">Keluar</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
    <?php
    if ($hal == 'pasang-iklan') {
        require_once VELOCITY_IKLAN_PLUGIN_DIR . 'public/partials/user-pasang-iklan.php';
    } elseif ($hal == 'pasang-banner') {
        require_once VELOCITY_IKLAN_PLUGIN_DIR . 'public/partials/user-pasang-banner.php';
    } elseif ($hal == 'pasang-link') {
        require_once VELOCITY_IKLAN_PLUGIN_DIR . 'public/partials/user-pasang-link.php';
    } elseif ($hal == 'ubah-profil') {
        require_once VELOCITY_IKLAN_PLUGIN_DIR . 'public/partials/user-ubah-profil.php';
    } elseif($hal == 'iklan-saya'){
        require_once VELOCITY_IKLAN_PLUGIN_DIR . 'public/partials/user-iklan-saya.php';
    } elseif($hal == 'link-saya'){
        require_once VELOCITY_IKLAN_PLUGIN_DIR . 'public/partials/user-link-saya.php';
    } elseif($hal == 'banner-saya'){
        require_once VELOCITY_IKLAN_PLUGIN_DIR . 'public/partials/user-banner-saya.php';
    } else {
        require_once VELOCITY_IKLAN_PLUGIN_DIR . 'public/partials/user-dashboard.php';
    }
  } else {
    echo '<div class="container py-3 px-0">';
      echo '<div class="card">';
        echo '<div class="card-body">';
          echo '<div class="alert alert-warning py-2">';
            echo 'Anda harus masuk dahulu untuk melihat halaman ini.';
          echo '</div>';
          echo '<a class="btn btn-sm btn-warning px-3 me-2" href="'.esc_url(home_url('/mylogin')).'">Masuk</a>';
          echo '<a class="btn btn-sm btn-warning px-3" href="'.esc_url(home_url('/myregistrasi')).'">Daftar</a>';
        echo '</div>';
      echo '</div>';
    echo '</div>';
  }
  ?>
</div>
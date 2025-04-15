<div class="my-4 row">
    <div class="col-sm-4 col-md-3">
        <?php require_once VELOCITY_IKLAN_PLUGIN_DIR . 'public/partials/user-foto-profil.php'; ?>
    </div>
    <div class="col-sm-8 col-md-9">
    <?php
        // Periksa jika formulir disubmit
        if (isset($_POST['update_account'])) {
            // Mendapatkan ID pengguna yang sedang masuk
            $current_user_id = get_current_user_id();

            // Mengambil data dari formulir
            $user_password      = $_POST['user_password'];
            $confirm_password   = $_POST['confirm_password'];
            $new_user_email     = sanitize_email($_POST['new_user_email']);
            $first_name         = sanitize_text_field($_POST['first_name']);
            $description        = sanitize_text_field($_POST['description']);
            $alamat             = sanitize_text_field($_POST['alamat']);
            $phone              = sanitize_text_field($_POST['phone']);
            $phone              = preg_replace("/[^0-9]/", "", $phone);
            $prov               = $_POST['prov_destination'];
            $city               = $_POST['city_destination'];
            $subst              = $_POST['subdistrict_destination'];
            
            $error = '';
            // Periksa apakah password baru diisi
            if (!empty($user_password)) {
                // Periksa apakah password baru dan pengulangan password sama
                if ($user_password == $confirm_password) {
                    // Mengupdate kata sandi pengguna
                    wp_set_password($user_password, $current_user_id);
                } else {
                    $error = 'Password dan pengulangan password tidak cocok.';
                }
            }

            // Periksa apakah alamat email baru diisi
            if (!empty($new_user_email)) {
                // Periksa apakah alamat email sudah terpakai
                if (email_exists($new_user_email) && email_exists($new_user_email) != $current_user_id) {
                    $error = 'Email sudah terdaftar oleh pengguna lain.';
                } else {
                    // Mengupdate alamat email pengguna
                    wp_update_user(array('ID' => $current_user_id, 'user_email' => $new_user_email));
                }
            }

            if(empty($error)){
                // Mengupdate nama pengguna
                wp_update_user(array('ID' => $current_user_id, 'first_name' => $first_name, 'display_name' => $first_name));

                // Mengupdate user
                update_user_meta($current_user_id, 'phone', $phone);
                update_user_meta($current_user_id, 'description', $description);
                update_user_meta($current_user_id, 'alamat', $alamat);
                update_user_meta($current_user_id, 'prov_destination', $prov);
                update_user_meta($current_user_id, 'city_destination', $city);
                update_user_meta($current_user_id, 'subdistrict_destination', $subst);

                echo '<div class="alert alert-success">Informasi pengguna telah berhasil diperbarui.</div>';
            } elseif($error) {
                echo '<div class="alert alert-danger">'.$error.'</div>';
            }
        }

        // Mendapatkan informasi pengguna saat ini
        $current_user = wp_get_current_user();
        $user_bio = get_user_meta($current_user->ID, 'description', true);
        $user_alamat = get_user_meta($current_user->ID, 'alamat', true);
        $user_prov  = get_user_meta($current_user->ID, 'prov_destination', true);
        $user_city  = get_user_meta($current_user->ID, 'city_destination', true);
        $user_subst  = get_user_meta($current_user->ID, 'subdistrict_destination', true);

        // Tampilkan formulir pengaturan akun
        ?>
        <form method="post" action="" id="update-account-form">

            <div class="form-floating mb-3">
                <input type="text" value="<?php echo esc_attr($current_user->user_login); ?>" class="form-control" disabled>
                <label for="user_login">Username</label>
                <small>Username tidak dapat diganti</small>
            </div>

            <div class="form-floating mb-3">
                <input type="text" name="first_name" value="<?php echo esc_attr($current_user->first_name); ?>" class="form-control" required>
                <label for="first_name">Nama</label>
            </div>

            <div class="form-floating mb-3">
                <input type="text" name="phone" value="<?php echo esc_attr(get_user_meta($current_user->ID, 'phone', true)); ?>" class="form-control" required>
                <label for="phone">Telepon</label>
                <small>Hanya angka saja, contoh: <strong>08123456789</strong></small>
            </div>

            <div class="form-floating mb-3">
                <input type="email" name="new_user_email" value="<?php echo esc_attr($current_user->user_email); ?>" class="form-control" required>
                <label for="new_user_email">Email</label>
            </div>

            <div class="form-floating mb-3">
                <textarea class="form-control" id="description" name="description" rows="3"><?php echo esc_textarea($user_bio); ?></textarea>
                <label for="description">Deskripsi:</label>
            </div>
            
            <div class="form-floating mb-3">
                <select name="prov_destination" id="prov-destination" class="form-select datapengiriman"><option class="" value="">Provinsi</option>
                    <?php
                    $data_province = getProvince();
                    for ($i=0; $i < count($data_province); $i++) {
                            $selec = ($data_province[$i]['province_id'] == $user_prov)?'selected':'';
                            echo "<option value='".$data_province[$i]['province_id']."' ".$selec.">".$data_province[$i]['province']."</option>";
                    }
                    ?>
                </select>
                <label for="prov_destination">Provinsi</label>
            </div>
            <div class="form-floating mb-3">
                <select name="city_destination" id="city-destination" class="form-select datapengiriman"><option selected class="" value="" >Kota</option>
                        <?php
                        $data_City = getCity();
                        for ($x=0; $x < count($data_City); $x++) {
                            $type = $data_City[$x]['type'];
                            if( $type == 'Kabupaten'){
                                $type = 'Kab';
                            }
                            $selec = ($data_City[$x]['city_id'] == $user_city)?'selected':'';
                            echo "<option value='".$data_City[$x]['city_id']."' class='". $data_City[$x]['province_id']."' ".$selec.">".$type." ".$data_City[$x]['city_name']."</option>";
                        }
                        ?>
                </select>
                <label for="city_destination">Kota</label>
            </div>
            <div class="form-floating mb-3">
                <select name="subdistrict_destination" id="subdistrict-destination" class="form-select datapengiriman">
                <?php
                if($user_city){
                    $a = $user_city;
                    $data_Subdistrict = getSubdistrict($a);
                    echo "<option value=''>Kecamatan</option>";
                    for ($x=0; $x < count($data_Subdistrict); $x++) {
                        $selec = ($data_Subdistrict[$x]['subdistrict_id'] == $user_subst)?'selected':'';
                        echo "<option value='".$data_Subdistrict[$x]['subdistrict_id']."' class='". $data_Subdistrict[$x]['city_id']."' ".$selec.">".$data_Subdistrict[$x]['subdistrict_name']."</option>";
                    }
                }
                ?>
                </select>
                <label for="subdistrict_destination">Kecamatan</label>
            </div>

            <div class="form-floating mb-3">
                <textarea class="form-control" id="alamat" name="alamat" rows="3" required><?php echo esc_textarea($user_alamat); ?></textarea>
                <label for="alamat">Alamat Lengkap</label>
            </div>

            <div class="form-floating mb-3">
                <input type="password" name="user_password" class="form-control">
                <label for="user_password">Password Baru</label>
            </div>

            <div class="form-floating mb-3">
                <input type="password" name="confirm_password" class="form-control">
                <label for="confirm_password">Konfirmasi Password Baru</label>
                <small id="password-message"></small>
            </div>

            <button type="submit" name="update_account" class="btn btn-primary">Update Account</button>
        </form>
    </div>
</div>

<script>
    jQuery(function($) {
        $(document).ready(function() {
            $('#update-account-form input[name="confirm_password"]').keyup(function() {
                var password = $('#update-account-form input[name="user_password"]').val();
                var confirmPassword = $(this).val();

                if (password !== confirmPassword) {
                    $('#password-message').html('<span class="text-danger">Password tidak cocok.</span>');
                } else {
                    $('#password-message').html('<span class="text-success">Password cocok.</span>');
                }
            });
        });
    });
</script>
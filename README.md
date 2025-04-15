# Plugin Iklan Baris

Plugin Iklan Baris untuk projek Velocity Developer https://iklanbaris.velocitydeveloper.com/

## Shortcode

#### Thumbnail

```php
[velocity-iklan-ratio-image size="large" ratio="16:9"]
```

- ratio: rasio gambar
- size: ukuran gambar
- post_id: post ID

#### Loop Iklan Link

```php
[velocity-link-loop post_id="" class=""]
```

- class: kelas looping
- post_id: post ID


#### Loop Iklan Banner & Iklan Utama

```php
[velocity-iklan-loop post_id="" class=""]
```

- class: kelas looping
- post_id: post ID

#### Detail profil pengirima iklan

```php
[velocity-iklan-penjual post_id="" author_id=""]
```

- author_id: author id
- post_id: post ID

#### Memanggil meta key

```php
[velocity-iklan-meta post_id="" key=""]
```
- key: nama meta key
- post_id: post ID

#### Menampilkan banner

```php
[banner_image post_id="" lokasi=""]
```
- lokasi: slug taxonomy lokasi
- post_id: post ID

#### Menampilkan jumlah views

```php
[view]
```

#### Judul Ppost

```php
[judul-post length="10"]
```
- length: jumlah kata
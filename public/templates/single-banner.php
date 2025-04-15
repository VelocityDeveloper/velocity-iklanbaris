<?php get_header(); ?>

<div class="container py-3">
<?php if (have_posts()) :
    while (have_posts()) : the_post(); ?>
    <div class="row">
        <div class="col-md-8">
        <div class="border p-3 bg-white">
            <div class="fs-5 fw-bold"><?php the_title(); ?></div>
            <div class="mt-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-tag" viewBox="0 0 16 16"> <path d="M6 4.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m-1 0a.5.5 0 1 0-1 0 .5.5 0 0 0 1 0"/> <path d="M2 1h4.586a1 1 0 0 1 .707.293l7 7a1 1 0 0 1 0 1.414l-4.586 4.586a1 1 0 0 1-1.414 0l-7-7A1 1 0 0 1 1 6.586V2a1 1 0 0 1 1-1m0 5.586 7 7L13.586 9l-7-7H2z"/> </svg> <?php echo do_shortcode('[velocity-iklan-taxonomy taxonomy="lokasi"]');?>
            </div>
            <div class="mt-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-geo-alt" viewBox="0 0 16 16"><path d="M12.166 8.94c-.524 1.062-1.234 2.12-1.96 3.07A32 32 0 0 1 8 14.58a32 32 0 0 1-2.206-2.57c-.726-.95-1.436-2.008-1.96-3.07C3.304 7.867 3 6.862 3 6a5 5 0 0 1 10 0c0 .862-.305 1.867-.834 2.94M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10"/><path d="M8 8a2 2 0 1 1 0-4 2 2 0 0 1 0 4m0 1a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/></svg>
                <?php $alamat = do_shortcode('[velocity-iklan-meta key="kota"]');
                if($alamat){
                    echo $alamat;
                } ?>
            </div>
            <div class="mt-2">
                <?php $url_iklan  = get_post_meta(get_the_ID(),'wb-blog', true);?>
                <a href="<?php echo $url_iklan;?>" class="w-100" target="_blank"><i class="fa fa-link" aria-hidden="true"></i> Kunjungi Link</a>
            </div>
            <div class="mt-2 py-3 border-top border-bottom">
                <?php echo do_shortcode('[velocity-iklan-galeri]'); ?>
            </div>
            <div class="mt-2">
            <?php $content = apply_filters('the_content', get_the_content()); echo $content; ?>
            </div>
        </div>
        </div>
        <div class="col-md-4">
            <?php echo do_shortcode('[velocity-iklan-penjual]'); ?>
            <div class="mt-3 py-2 px-3 bg-light border">
                <?php echo 'Iklan ini sudah dilihat <strong>'.do_shortcode('[view]').'</strong>.'; ?>
            </div>
        </div>
    </div>
    <?php endwhile;
endif; ?>
</div>

<?php
get_footer();
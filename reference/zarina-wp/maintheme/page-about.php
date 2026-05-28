<?php
/*
Template Name: Об отеле
*/
get_header();
?>
<?php
 // Функция форматирования размера
        function my_format_file_size( $bytes ) {
            if ( $bytes >= 1024 * 1024 ) {
                $size = $bytes / (1024 * 1024);
                return round($size, 1) . ' Mb';
            } else {
                $size = $bytes / 1024;
                return round($size, 0) . ' Kb';
            }
        } 
?>
<?php $banner = get_field('banner'); ?>
<section class="billbord-page" <?php if(!empty($banner['izobrazhenie_bannera'])){ ?>style="background-image: url(<?php echo $banner['izobrazhenie_bannera']['url']; ?>);"<?php } ?>>
    <div class="container">
        <div class="billbord__content">
            <h1 class="billbord-page__title"><?php echo $banner['zagolovok']; ?></h1>
            <h2 class="billbord-page__descr"><?php echo $banner['podzagolovok']; ?></h2>
        </div>
    </div>
</section>
<?php $about = get_field('ob_otele'); ?>
<section class="page-about">
    <div class="container">
        <div class="row">
            <div class="col-lg-5">
                <?php echo $about['opisanie']; ?>
            </div>
            <?php if(!empty($about['izobrazhenie'])){ ?>
                <div class="col-lg-7">
                    <img src="<?php echo $about['izobrazhenie']['url']; ?>" alt="alt">
                </div>
            <?php } ?>
        </div>
        <?php if(!empty($about['chisla'])){ ?>
            <div class="numbers-wrapper">
                <?php foreach ($about['chisla'] as $v_ch) { ?>
                    <div class="item-number">
                        <div class="item-number__value"><?php echo $v_ch['chislo']; ?></div>
                        <p><?php echo $v_ch['tekst']; ?></p>
                    </div>
                <?php } ?>              
            </div>
        <?php } ?>
        <div class="row">
            <?php if(!empty($about['izobrazhenie2'])){ ?>
                <div class="col-lg-7">
                    <img src="<?php echo $about['izobrazhenie2']['url']; ?>" alt="alt">
                </div>
            <?php } ?>
            <div class="col-lg-5">
                <?php echo $about['opisanie2']; ?>
            </div>
        </div>
        <?php $bottom = get_field('nizhnij_blok'); ?>
        <div class="row">
            <div class="col-lg-5">
                <?php echo $bottom['opisanie']; ?>
            </div>
             <?php if(!empty($bottom['izobrazhenie1'])){ ?>
            <div class="col-lg-3">
                <img src="<?php echo $bottom['izobrazhenie1']['url']; ?>" alt="alt">
            </div>
             <?php } if(!empty($bottom['izobrazhenie2'])){ ?>
            <div class="col-lg-4">
                <img src="<?php echo $bottom['izobrazhenie2']['url']; ?>" alt="alt">
            </div>
            <?php } ?>
        </div>
    </div>
</section>
<?php $sertificats = get_field('liczenzii_i_sertifikaty'); ?>
<section class="sertificats">
    <div class="container">
        <h2 class="title-section"><?php echo $sertificats['zagolovok']; ?></h2>
        <div class="row">
            <div class="col-lg-8">
                <div class="sertificats__content">
                    <div class="sertificats__title"><?php echo $sertificats['podzagolovok']; ?></div>
                    <p><?php echo $sertificats['opisanie']; ?></p>
                    <br>
                    <p>Номер реестровой записи: <span class="copy"><span class="copy__value"><?php echo $sertificats['nomer_reestrovoj_zapisi']; ?></span><span class="copy__btn"></span></span></p>
                    <?php if(!empty($sertificats['posmotret_v_oficzialnom_reestre'])){ ?>
                        <a href="<?php echo $sertificats['posmotret_v_oficzialnom_reestre']; ?>" class="btn-main" target="_blank">Посмотреть в официальном реестре</a>
                    <?php } ?>
                </div>
            </div>
            <?php if(!empty($sertificats['fajly'])){ ?>
                <div class="col-lg-4">
                    <div class="slider-documents">
                        <?php foreach ($sertificats['fajly'] as $v_f) {
                            if(!empty($v_f['fajl_pdf'])){
                                $file = $v_f['fajl_pdf']; // или get_sub_field('my_file');

                            if ( $file && ! empty($file['ID']) ) {
                                $file_path = get_attached_file( $file['ID'] );

                                if ( $file_path && file_exists( $file_path ) ) {
                                    $bytes = filesize( $file_path );

                                

                                    $size_text = my_format_file_size( $bytes );

                                   // echo '<span class="file-size">' . esc_html( $size_text ) . '</span>';
                                }
                            }                                ?>
                            <div>
                                <a href="<?php echo $v_f['fajl_pdf']['url']; ?>" class="item-document" target="_blank">
                                    <span class="item-document__title"><?php echo $v_f['nazvanie']; ?></span>
                                    <span class="item-document__bottom">
                                        <span class="item-document__type">pdf</span>
                                        <span class="item-document__info">
                                            <span class="item-document__date">
                                                <?php
                                                $timestamp = strtotime( $file['date'] );
                                                $date_formatted = date_i18n( 'd.m.Y', $timestamp );
                                                echo esc_html( $date_formatted );
                                                ?>
                                            </span>
                                            <span class="item-document__size"><?php echo esc_html( $size_text ); ?></span>
                                        </span>
                                    </span>
                                </a>
                            </div>
                        <?php }
                        } ?>                        
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</section>

<?php 
    include('template-parts/sect_form.php');
?>

<?php
get_footer();
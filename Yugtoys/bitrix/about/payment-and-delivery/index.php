<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Правила оплаты и доставки компании ЮГ Тойз");
?>
<section class="page-delivery">
    <div class="container">
        <h1 class="title-section">Оплата и доставка</h1>
        <div class="intro-block">
            <p>Юг Тойз — надежный поставщик детских игрушек, канцелярии и спорттоваров оптом для ИП и организаций в Краснодаре и Краснодарском крае. Мы работаем строго по документам, предлагая удобные условия оплаты, доставки и самовывоза. Узнайте, как оформить заказ и получить товары для вашего бизнеса.</p>
        </div><br>
        <div class="delivery-block">
            <div class="row">
                <div class="col-lg-3 col-md-6">
                    <div class="item-delivery">
                        <?$APPLICATION->IncludeComponent("bitrix:main.include", "", ["AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."about/payment-and-delivery/b1.php"], false, ['HIDE_ICONS' => 'N']);?>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="item-delivery">
                        <?$APPLICATION->IncludeComponent("bitrix:main.include", "", ["AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."about/payment-and-delivery/b2.php"], false, ['HIDE_ICONS' => 'N']);?>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="item-delivery">
                        <?$APPLICATION->IncludeComponent("bitrix:main.include", "", ["AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."about/payment-and-delivery/b3.php"], false, ['HIDE_ICONS' => 'N']);?>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="item-delivery">
                        <?$APPLICATION->IncludeComponent("bitrix:main.include", "", ["AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."about/payment-and-delivery/b4.php"], false, ['HIDE_ICONS' => 'N']);?>
                    </div>
                </div>
            </div>
        </div><br>
        <div class="additional-info">
            <?$APPLICATION->IncludeComponent("bitrix:main.include", "", ["AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."about/payment-and-delivery/b6.php"], false, ['HIDE_ICONS' => 'N']);?>
        </div><br>
        <?$APPLICATION->IncludeComponent("bitrix:main.include", "", ["AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/forms/subscribe_tg.php"], false, ['HIDE_ICONS' => 'Y']);?>
        <div class="title-block">
            Также возможен самовывоз
        </div>
        <div class="row row_delivery">
            <div class="col-lg-6">
                <div class="unit-delivery">
                    <div class="title-block">
                        <?$APPLICATION->IncludeComponent("bitrix:main.include", "", ["AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."about/payment-and-delivery/delivery/d1_title.php"], false, ['HIDE_ICONS' => 'N']);?>
                    </div>
                    <div class="row">
                        <div class="col-sm-8">
                            <?$APPLICATION->IncludeComponent("bitrix:main.include", "", ["AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."about/payment-and-delivery/delivery/d1_address.php"], false, ['HIDE_ICONS' => 'N']);?>
                        </div>
                        <div class="col-sm-4 col_delivery">
                            <?$APPLICATION->IncludeComponent("bitrix:main.include", "", ["AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."about/payment-and-delivery/delivery/d1_work.php"], false, ['HIDE_ICONS' => 'N']);?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="unit-delivery">
                    <div class="title-block">
                        <?$APPLICATION->IncludeComponent("bitrix:main.include", "", ["AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."about/payment-and-delivery/delivery/d2_title.php"], false, ['HIDE_ICONS' => 'N']);?>
                    </div>
                    <div class="row">
                        <div class="col-sm-8">
                            <?$APPLICATION->IncludeComponent("bitrix:main.include", "", ["AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."about/payment-and-delivery/delivery/d2_address.php"], false, ['HIDE_ICONS' => 'N']);?>
                        </div>
                        <div class="col-sm-4 col_delivery">
                            <?$APPLICATION->IncludeComponent("bitrix:main.include", "", ["AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."about/payment-and-delivery/delivery/d2_work.php"], false, ['HIDE_ICONS' => 'N']);?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="title-block title-block_delivery">
            <?$APPLICATION->IncludeComponent("bitrix:main.include", "", ["AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."about/payment-and-delivery/b5.php"], false, ['HIDE_ICONS' => 'N']);?>
        </div>
        <?$APPLICATION->IncludeComponent("bitrix:main.include", "", ["AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/forms/form_contacts_pay.php"], false, ['HIDE_ICONS' => 'N']);?>
    </div>
</section>
<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
?>
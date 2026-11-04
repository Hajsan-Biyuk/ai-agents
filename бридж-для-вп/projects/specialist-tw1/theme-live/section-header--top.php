<section class="section-header--top">
    <a href="/" class="logo header-logo"></a>
    <div class="main-menu-wrap">
        <div class="hamburger">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </div>

        <div class="close-menu-wrap">
            <button class="close-menu-btn">X</button>
        </div>
        
        <?php 
            wp_nav_menu(array(
                "container" => "nav",
                "container_id" => "menu-main",
                "menu_class" => "nav-main"
            )); 
        ?>

    </div>
    
    <div class="header-contacts">
        <div class="phone-wrap">
            <a href="tel:+7 (978) 729-33-38" class="phone">+7 (978) 729-33-38</a>
            <a href="tel:+7 (978) 905-41-97" class="phone">+7 (978) 905-41-97</a>
            <span>Бесплатная консультация</span>
        </div>
    </div>
</section>
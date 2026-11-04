
<section class="section-our-clients">
    <div class="section-title"><h4>Наши <span class="color color-main">клиенты и партнеры</span></h4></div>
    
    <div class="our-clients container">
        <div class="our-client-item">
            <img src="<?=IMG_DIR?>/clients/partner-1.jpg" alt="Voltmart" title="Voltmart">
        </div>
        <div class="our-client-item">
            <img src="<?=IMG_DIR?>/clients/partner-2.png" alt="Владоград" title="Владоград">
        </div>
        <div class="our-client-item">
            <img src="<?=IMG_DIR?>/clients/partner-3.png" alt="Буренушка" title="Буренушка">
        </div>
        <div class="our-client-item">
            <img src="<?=IMG_DIR?>/clients/partner-4.png" alt="ЦЭРОТ" title="ЦЭРОТ">
        </div>
        <div class="our-client-item">
            <img src="<?=IMG_DIR?>/clients/partner-5.png" alt="Пауэр Сервисез" title="Пауэр Сервисез">
        </div>
        <div class="our-client-item">
            <img src="<?=IMG_DIR?>/clients/partner-6.png" alt="Солнечная Манечка" title="Солнечная Манечка">
        </div>
        <div class="our-client-item">
            <img src="<?=IMG_DIR?>/clients/partner-7.png" alt="ISP INKO-NET" title="ISP INKO-NET">
        </div>
        <div class="our-client-item">
            <img src="<?=IMG_DIR?>/clients/partner-8.png" alt="Пивокомбинат Крым" title="Пивокомбинат Крым">
        </div>
        <div class="our-client-item">
            <img src="<?=IMG_DIR?>/clients/partner-9.png" alt="Детский Крым" title="Детский Крым">
        </div>
        <div class="our-client-item">
            <img src="<?=IMG_DIR?>/clients/partner-10.png" alt="Ника Престиж" title="Ника Престиж">
        </div>
        <div class="our-client-item">
            <img src="<?=IMG_DIR?>/clients/partner-11.png" alt="Монолит" title="Монолит">
        </div>
        <div class="our-client-item">
            <img src="<?=IMG_DIR?>/clients/partner-12.png" alt="Партнер" title="Партнер">
        </div>
        <div class="our-client-item">
            <img src="<?=IMG_DIR?>/clients/partner-13.jpg" alt="Партнер" title="Ивик Крым">
        </div>
        <div class="our-client-item">
            <img src="<?=IMG_DIR?>/clients/partner-14.jpg" alt="Партнер" title="Ивик Крым">
        </div>
    </div>

</section>

<section class="section-map">
    <!-- <script type="text/javascript" charset="utf-8" async src="https://api-maps.yandex.ru/services/constructor/1.0/js/?um=constructor%3A501ffa18700d6e4feadf00380c01fad7deefa27501f035826c4133943be49453&amp;width=100%25&amp;height=400&amp;lang=ru_RU&amp;scroll=false"></script> -->
    <script type="text/javascript" charset="utf-8" async src="https://api-maps.yandex.ru/services/constructor/1.0/js/?um=constructor%3Acc3a044b7a082d99fa3132fb492e26dcf33f8dd2410ca86b806aa9047d9d42ff&amp;width=100%25&amp;height=400&amp;lang=ru_RU&amp;scroll=false"></script>
</section>



<footer>
    <div class="footer-info  wow fadeInLeft">
        <a href="/" class="logo footer-logo">
            <img src="<?=IMG_DIR?>/logo-black.png" alt="Учебный центр Специалист" title="Учебный центр Специалист">
        </a>
        <p>ООО «КЦППК«СПЕЦИАЛИСТ»</p>
        <p>ОГРН 1169102091289</p>
        <p>ИНН 9102221710</p>

        <div class="social-icons">
            <p class="social-icons--title">Присоединяйтесь к нам!</p>
            <ul>
                <li>
                    <a href="https://vk.com/specialist_82_rf" class="social-icons--item social-icons--item-vk" target="_blank"></a>
                </li>
                <li>
                    <a href="https://ok.ru/group/58736569810957" class="social-icons--item social-icons--item-ok" target="_blank"></a>
                </li>
                <li>
                    <a href="https://rutube.ru/channel/30077692/" class="social-icons--item social-icons--item-youtube" target="_blank"></a>
                </li>
            </ul>
        </div>
    </div>
    <div class="footer-contacts">
        <p class="footer-contacts--title">Наши контакты</p>
        <ul class="footer-contacts-list">
            <li class="footer-contacts-list--phone">
                <a href="tel:+7978 905-41-97" class="phone">+7 (978) 905-41-97</a>
                <a href="tel:+7978 729-33-38" class="phone">+7 (978) 729-33-38</a>
                <span>Бесплатная консультация</span>
            </li>
            <li class="footer-contacts-list--address">
                г. Симферополь,<br>ул. Горького 8/10, оф. 26
            </li>
            <li class="footer-contacts-list--email">
                <a href="mailto:kzppk82@gmail.com">kzppk82@gmail.com</a>
            </li>
        </ul>
    </div>
    <form action="/wp-content/themes/spec/mail.php" method="post" class="footer-form  wow fadeInRight">
        <p class="footer-form--title">Остались вопросы?</p>
        <p class="footer-form--sub-title">Заполни форму и мы ответим в ближайшее время</p>
        <input type="text" name="name" placeholder="Имя" required>
        <input type="text" name="phone" placeholder="Телефон" required>
        <input type="email" name="email" placeholder="E-mail" required>
        <textarea name="message" placeholder="Сообщение" required></textarea>
        <input type="hidden" name="form_type" value="Футер">
        <?php include "pd-consent.php"; ?>
        <button type="submit" class="btn">Отправить</button>
    </form>
    <div class="copyright">
        © Copyright 2016-<?=date("Y");?>  Все права защищены
        <nav class="footer-legal">
            <a href="<?= esc_url(home_url('/privacy-policy/')) ?>">Политика конфиденциальности</a>
            <a href="<?= esc_url(home_url('/soglasie-na-obrabotku-personalnyh-dannyh/')) ?>">Согласие на обработку персональных данных</a>
        </nav>
    </div>
</footer>

<button class="open-modal btn--open-modal" data-modal-id="#modal-form">Оставить заявку</button>

<div id="modal-form" class="modal-form modal">
    <form class="modal__content form" action="/wp-content/themes/spec/mail.php" method="POST">
        <span class="modal__close">X</span>
        <h3 class="modal__title ">Оставить заявку</h3>
        
        <input type="text" name="name" placeholder="Ваше имя" required>
        <select name="training_direction" id="" required>
            <option value="">Выберите направление обучения...</option>
            <option value="Охрана труда">Охрана труда</option>
            <option value="Пожарная безопасность">Пожарная безопасность</option>
            <option value="Безопасность дорожного движения">Безопасность дорожного движения</option>
            <option value="Профессиональная переподготовка">Профессиональная переподготовка</option>
            <option value="Водитель погрузчика">Водитель погрузчика</option>
            <option value="Экологическая безопасность">Экологическая безопасность</option>
            <option value="Гражданская оборона">Гражданская оборона</option>
            <option value="Оказание первой помощи">Оказание первой помощи</option>
            <option value="Рабочие профессии">Рабочие профессии</option>
            <option value="Промышленная безопасность">Промышленная безопасность</option>
            <option value="Электробезопасность">Электробезопасность</option>
            <option value="Работы на высоте">Работы на высоте</option>
            <option value="Эксплуатация и обслуживание лифтов">Эксплуатация и обслуживание лифтов</option>
            <option value="Машинист экскаватора">Машинист экскаватора</option>
            <option value="Водитель квадроцикла">Водитель квадроцикла</option>
            <option value="Водитель маломерных судов">Водитель маломерных судов</option>
            <option value="Матрос-спасатель">Матрос-спасатель</option>
            <option value="Тракторист">Тракторист</option>
            <option value="Бульдозерист">Бульдозерист</option>
            <option value="Крановщик">Крановщик</option>
        </select>
        <input type="text" name="phone" placeholder="Телефон" required>
        <input type="email" name="email" placeholder="E-mail">
        <input type="hidden" name="form_type" value="Окно заявки">
        <?php include "pd-consent.php"; ?>
        <button type="submit" class="btn form__btn button">Отправить</button>
        
    </form>
</div>

<div id="modal-success" class="modal modal-success">
    <div class="modal__content">
        <span class="modal__close">X</span>
        <p class="modal-success__title">Заявка успешно отправлена!</p>
    </div>
</div>

<div id="overlay"></div>

<!-- Yandex.Metrika counter -->

<script type="text/javascript" >

(function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};

m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})

(window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

ym(51868316, "init", {

id:51868316,

clickmap:true,

trackLinks:true,

accurateTrackBounce:true,

webvisor:true

});

</script>

<noscript><div><img src="https://mc.yandex.ru/watch/51868316" style="position:absolute; left:-9999px;" alt="" /></div></noscript>

<!-- /Yandex.Metrika counter -->

<?php require_once get_template_directory() . "/recaptcha-config.php"; ?>
<script>
window.specRecaptchaSiteKey = <?= json_encode(spec_recaptcha_site_key()) ?>;
</script>
<script src="https://www.google.com/recaptcha/api.js?render=<?= esc_attr(spec_recaptcha_site_key()) ?>"></script>
<script src="<?= esc_url(JS_DIR . "/recaptcha.js?v=1") ?>"></script>
<?php wp_footer(); ?>

</body>
</html>

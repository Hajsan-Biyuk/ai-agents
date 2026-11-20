<?php
/**
 * Plugin Name: Специалист — всплывающее окно на главной
 * Description: Окно акции при открытии главной, один раз за визит. Поля — ACF-группа «Всплывающее окно» на странице «Шапка и подвал».
 * Version: 1.0.0
 */

defined('ABSPATH') || exit;

add_action('wp_footer', function () {
	if (!is_front_page() || !function_exists('get_field') || !get_field('popup_show', 'option')) {
		return;
	}
	$title    = (string) get_field('popup_title', 'option');
	$subtitle = (string) get_field('popup_subtitle', 'option');
	$rows     = get_field('popup_rows', 'option') ?: [];
	$image    = (int) get_field('popup_image', 'option');
	$btn_text = (string) get_field('popup_btn_text', 'option');
	$btn_link = (string) get_field('popup_btn_link', 'option') ?: '#consult';
	?>
<style>
.sp-promo{margin:auto;padding:0;border:0;background:transparent;max-width:none;max-height:none;overflow:visible;width:min(1440px,calc(100vw - 32px))}
.sp-promo::backdrop{background:rgba(9,23,54,.6)}
html:has(.sp-promo[open]){overflow:hidden}
.sp-promo__box{position:relative;outline:none;overflow:hidden auto;max-height:calc(100dvh - 32px);border-radius:28px;background:#144286;font-family:Inter,sans-serif;color:#fff}
.sp-promo__bg{position:absolute;top:0;left:0;height:100%;pointer-events:none}
.sp-promo__bg--arc{width:68.96%}
.sp-promo__bg--main{width:57.57%}
.sp-promo__inner{position:relative;display:flex;justify-content:space-between;align-items:center;gap:40px;padding:48px 64px}
.sp-promo__photo{flex:0 1 544px;aspect-ratio:544/590;border-radius:16px;overflow:hidden;background:#fff}
.sp-promo__photo img{display:block;width:100%;height:100%;object-fit:cover}
.sp-promo__content{flex:0 1 714px;align-self:stretch;display:flex;flex-direction:column;justify-content:space-between;min-width:0}
.sp-promo__title{margin:0 0 10px;font-weight:700;font-size:48px;line-height:1.3;text-transform:uppercase;color:#fff}
.sp-promo__sub{margin:0;font-size:16px;line-height:1.2}
.sp-promo__list{display:flex;flex-direction:column;gap:12px;margin:36px 0 0;padding:0;list-style:none}
.sp-promo__row{display:flex;align-items:center;gap:12px;padding:12px 0;border-bottom:1px solid #dee4ec}
.sp-promo__name{flex:1;font-size:20px;line-height:1.4}
.sp-promo__price{font-weight:600;font-size:24px;line-height:1.2;white-space:nowrap}
.sp-promo__btn{display:inline-flex;justify-content:center;align-items:center;gap:16px;width:409px;max-width:100%;height:65px;margin-top:64px;border-radius:12px;background:#00b0c7;font-weight:600;font-size:20px;color:#fff;text-decoration:none;transition:background .2s}
.sp-promo__btn:hover,.sp-promo__btn:focus-visible{background:#0097ab;color:#fff}
.sp-promo__close{position:absolute;z-index:1;top:14px;right:16px;width:24px;height:24px;padding:0;border:0;background:none;cursor:pointer;color:#fff}
.sp-promo__close svg{display:block}
@media (max-width:1400px){
	.sp-promo__inner{padding:40px 48px}
	.sp-promo__title{font-size:40px}
	.sp-promo__name{font-size:18px}
	.sp-promo__price{font-size:22px}
	.sp-promo__btn{margin-top:40px}
}
@media (max-width:1200px){
	.sp-promo__title{font-size:32px}
	.sp-promo__list{margin-top:24px;gap:6px}
}
@media (max-width:992px){
	.sp-promo{width:min(600px,calc(100vw - 32px))}
	.sp-promo__inner{flex-direction:column;align-items:stretch;gap:24px;padding:48px 24px 24px}
	.sp-promo__photo{display:none}
	.sp-promo__content{flex:none}
	.sp-promo__bg--arc,.sp-promo__bg--main{width:100%}
	.sp-promo__btn{width:100%;margin-top:28px}
}
@media (max-width:576px){
	.sp-promo__box{border-radius:20px}
	.sp-promo__inner{padding:44px 16px 16px}
	.sp-promo__title{font-size:26px}
	.sp-promo__sub{font-size:14px}
	.sp-promo__row{padding:10px 0}
	.sp-promo__name{font-size:16px}
	.sp-promo__price{font-size:18px}
	.sp-promo__btn{height:56px;font-size:18px}
}
</style>
<dialog class="sp-promo" id="sp-promo" aria-labelledby="sp-promo-title">
	<div class="sp-promo__box" tabindex="-1" autofocus>
		<svg class="sp-promo__bg sp-promo__bg--arc" viewBox="0 0 993 686" preserveAspectRatio="none" aria-hidden="true"><path d="M0 0H993C863.571 147.288 831.744 247.162 846.596 353.088C855.083 453.971 901.763 564.941 945.26 686H0V0Z" fill="url(#sp-promo-g1)"/><defs><linearGradient id="sp-promo-g1" x1="0" y1="274.4" x2="993" y2="274.4" gradientUnits="userSpaceOnUse"><stop stop-color="#12366B"/><stop offset=".8" stop-color="#1B5BA4"/><stop offset="1" stop-color="#3AA0E5"/></linearGradient></defs></svg>
		<svg class="sp-promo__bg sp-promo__bg--main" viewBox="0 0 829 686" preserveAspectRatio="none" aria-hidden="true"><path d="M0 0H829C743.207 124.085 730.238 226.985 740.214 337.956C749.193 448.926 792.089 575.029 824.012 686H0V0Z" fill="url(#sp-promo-g2)"/><defs><linearGradient id="sp-promo-g2" x1="0" y1="411.6" x2="829" y2="411.6" gradientUnits="userSpaceOnUse"><stop stop-color="#103466"/><stop offset=".75" stop-color="#174D94"/><stop offset="1" stop-color="#216FBB"/></linearGradient></defs></svg>
		<button class="sp-promo__close" type="button" aria-label="Закрыть"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M16 8l-8 8M8 8l8 8"/></svg></button>
		<div class="sp-promo__inner">
			<?php if ($image) : ?>
				<div class="sp-promo__photo"><?php echo wp_get_attachment_image($image, 'large', false, ['loading' => 'eager']); ?></div>
			<?php endif; ?>
			<div class="sp-promo__content">
				<div>
					<?php if ($title) : ?><h2 class="sp-promo__title" id="sp-promo-title"><?php echo esc_html($title); ?></h2><?php endif; ?>
					<?php if ($subtitle) : ?><p class="sp-promo__sub"><?php echo esc_html($subtitle); ?></p><?php endif; ?>
					<?php if ($rows) : ?>
						<ul class="sp-promo__list">
							<?php foreach ($rows as $row) : ?>
								<li class="sp-promo__row">
									<span class="sp-promo__name"><?php echo esc_html($row['popup_row_name'] ?? ''); ?></span>
									<span class="sp-promo__price"><?php echo esc_html($row['popup_row_price'] ?? ''); ?></span>
								</li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>
				</div>
				<?php if ($btn_text) : ?>
					<div><a class="sp-promo__btn" href="<?php echo esc_attr($btn_link); ?>"><?php echo esc_html($btn_text); ?><svg width="26" height="20" viewBox="0 0 26 20" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M1.5 10h23M16 1.5l8.5 8.5-8.5 8.5"/></svg></a></div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</dialog>
<script>
(function () {
	var d = document.getElementById('sp-promo'), key = 'spPromoSeen';
	if (!d || !d.showModal) return;
	try { if (sessionStorage.getItem(key)) return; sessionStorage.setItem(key, '1'); } catch (e) {}
	d.querySelector('.sp-promo__close').addEventListener('click', function () { d.close(); });
	d.addEventListener('click', function (e) { if (e.target === d) d.close(); });
	// Закрываем до обработчика темы: он по #consult откроет свою модалку с формой
	var btn = d.querySelector('.sp-promo__btn');
	if (btn) btn.addEventListener('click', function () { d.close(); });
	d.showModal();
})();
</script>
	<?php
}, 20);

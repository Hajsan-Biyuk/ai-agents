<?php
/**
 * Login Form (custom theme override)
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.9.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

do_action( 'woocommerce_before_customer_login_form' );

$registration_enabled = ( 'yes' === get_option( 'woocommerce_enable_myaccount_registration' ) );
?>

<section class="page-authorization">
	<div class="container">

		<?php if ( $registration_enabled ) : ?>
		<ul class="tabs tabs--card">
			<li class="active"><a href="#tab_authorization1">вход</a></li>
			<li><a href="#tab_authorization2">регистрация</a></li>
		</ul>
		<?php endif; ?>

		<div class="tab-container">

			<!-- TAB: LOGIN -->
			<div class="tab-pane" id="tab_authorization1">
				<div class="form-page">
					<div class="title-middle">личный кабинет</div>

					<?php wc_print_notices(); ?>

					<form class="woocommerce-form woocommerce-form-login login" method="post" novalidate>
						<?php do_action( 'woocommerce_login_form_start' ); ?>

						<div class="item-form">
							<input
								type="text"
								class="woocommerce-Input woocommerce-Input--text input-text"
								name="username"
								id="username"
								autocomplete="username"
								placeholder="Логин или Email*"
								value="<?php echo ( ! empty( $_POST['username'] ) && is_string( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>"
								required
								aria-required="true"
							/>
						</div>

						<div class="item-form">
							<input
								class="woocommerce-Input woocommerce-Input--text input-text"
								type="password"
								name="password"
								id="password"
								autocomplete="current-password"
								placeholder="Пароль*"
								required
								aria-required="true"
							/>
							<div class="show-password"><i class="far fa-eye"></i></div>
						</div>

						<div class="recover-link">
							<a href="<?php echo esc_url( wp_lostpassword_url() ); ?>">Забыли пароль?</a>
						</div>

						<?php do_action( 'woocommerce_login_form' ); ?>

						<?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
						<input type="hidden" name="redirect" value="<?php echo esc_url( apply_filters( 'woocommerce_login_redirect', wc_get_page_permalink( 'myaccount' ), null ) ); ?>">
						<button
							type="submit"
							class="woocommerce-button btn-page"
							name="login"
							value="<?php esc_attr_e( 'Log in', 'woocommerce' ); ?>"
						>войти</button>

						<div class="checkbox">
							<label>
								<input type="checkbox" required="required" checked="checked" />
								<span>Я даю согласие на обработку моих персональных данных в целях обработки заявки и обратной связи. Политика конфиденциальности — <a href="#">по ссылке.</a></span>
							</label>
						</div>

						<?php do_action( 'woocommerce_login_form_end' ); ?>
					</form>
				</div>
			</div>

			<?php if ( $registration_enabled ) : ?>
			<!-- TAB: REGISTER -->
			<div class="tab-pane" id="tab_authorization2">
				<div class="form-page">
					<div class="title-middle">регистрация</div>
					<div class="descr-form">Зарегистрируйтесь, чтобы использовать все возможности личного кабинета: отслеживание заказов, настройку подписки, связи с социальными сетями</div>

					<form method="post" class="woocommerce-form woocommerce-form-register register" <?php do_action( 'woocommerce_register_form_tag' ); ?>>
						<?php do_action( 'woocommerce_register_form_start' ); ?>

						<!-- ФИО -->
						<div class="item-form">
							<input
								type="text"
								name="billing_first_name"
								id="reg_billing_first_name"
								placeholder="Фамилия Имя Отчество*"
								value="<?php echo ( ! empty( $_POST['billing_first_name'] ) ) ? esc_attr( wp_unslash( $_POST['billing_first_name'] ) ) : ''; ?>"
								required
							/>
						</div>

						<!-- Email -->
						<div class="item-form">
							<input
								type="email"
								class="woocommerce-Input woocommerce-Input--text input-text"
								name="email"
								id="reg_email"
								autocomplete="email"
								inputmode="email"
								placeholder="Почта*"
								value="<?php echo ( ! empty( $_POST['email'] ) ) ? esc_attr( wp_unslash( $_POST['email'] ) ) : ''; ?>"
								required
								aria-required="true"
							/>
						</div>

						<!-- Телефон -->
						<div class="item-form">
							<input
								type="tel"
								name="billing_phone"
								id="reg_billing_phone"
								class="input-phone"
								inputmode="tel"
								placeholder="Телефон*"
								value="<?php echo ( ! empty( $_POST['billing_phone'] ) ) ? esc_attr( wp_unslash( $_POST['billing_phone'] ) ) : ''; ?>"
								required
							/>
						</div>

						<!-- Вид деятельности -->
						<div class="item-form">
							<select name="activity_type" id="reg_activity_type" class="woocommerce-Input input-text" required>
								<option value="" disabled <?php selected( empty( $_POST['activity_type'] ) ); ?>>Вид деятельности*</option>
								<?php
								$activities = array(
									'landscaper'    => 'Ландшафтный дизайнер',
									'architect'     => 'Архитектор',
									'interior'      => 'Дизайнер интерьеров',
									'developer'     => 'Застройщик',
									'nursery'       => 'Владелец питомника или садового центра',
									'florist'       => 'Флорист',
									'gardener'      => 'Садовод-любитель',
									'other'         => 'Другое (укажите)',
								);
								$selected_activity = ! empty( $_POST['activity_type'] ) ? sanitize_text_field( wp_unslash( $_POST['activity_type'] ) ) : '';
								foreach ( $activities as $value => $label ) :
								?>
									<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $selected_activity, $value ); ?>><?php echo esc_html( $label ); ?></option>
								<?php endforeach; ?>
							</select>
						</div>

						<!-- Поле "Другое" (скрыто по умолчанию) -->
						<div class="item-form" id="other-activity-wrap" style="display:none;">
							<input
								type="text"
								name="activity_other"
								id="reg_activity_other"
								placeholder="Укажите вид деятельности"
								value="<?php echo ( ! empty( $_POST['activity_other'] ) ) ? esc_attr( wp_unslash( $_POST['activity_other'] ) ) : ''; ?>"
							/>
						</div>

						<?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>
						<!-- Пароль -->
						<div class="item-form">
							<input
								type="password"
								class="woocommerce-Input woocommerce-Input--text input-text"
								name="password"
								id="reg_password"
								autocomplete="new-password"
								placeholder="Пароль*"
								required
								aria-required="true"
							/>
							<div class="show-password"><i class="far fa-eye"></i></div>
						</div>
						<?php else : ?>
						<p><?php esc_html_e( 'A link to set a new password will be sent to your email address.', 'woocommerce' ); ?></p>
						<?php endif; ?>

						<?php do_action( 'woocommerce_register_form' ); ?>

						<?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>
						<button
							type="submit"
							class="woocommerce-Button btn-page"
							name="register"
							value="<?php esc_attr_e( 'Register', 'woocommerce' ); ?>"
						>зарегистрироваться</button>

						<div class="checkbox">
							<label>
								<input type="checkbox" required="required" checked="checked" />
								<span>Я даю согласие на обработку моих персональных данных в целях обработки заявки и обратной связи. Политика конфиденциальности — <a href="#">по ссылке.</a></span>
							</label>
						</div>

						<?php do_action( 'woocommerce_register_form_end' ); ?>
					</form>
				</div>
			</div>
			<?php endif; ?>

		</div><!-- .tab-container -->
	</div><!-- .container -->
</section>

<script>
(function(){
	var sel = document.getElementById('reg_activity_type');
	var wrap = document.getElementById('other-activity-wrap');
	var otherInput = document.getElementById('reg_activity_other');
	if (!sel) return;
	function toggle() {
		if (sel.value === 'other') {
			wrap.style.display = '';
			otherInput.setAttribute('required', 'required');
		} else {
			wrap.style.display = 'none';
			otherInput.removeAttribute('required');
		}
	}
	sel.addEventListener('change', toggle);
	toggle();
})();
</script>

<?php do_action( 'woocommerce_after_customer_login_form' ); ?>

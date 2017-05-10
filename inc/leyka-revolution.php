<?php if( !defined('WPINC') ) die;

/**
 ** Temp functions to implement revolution development
 **
 **/

//add form JS/CSS to campaign page
add_filter('the_content', 'leyka_rev_campaign_page');
function leyka_rev_campaign_page($content) {

	if(!is_singular('leyka_campaign'))
		return $content;

	$campaign_id = get_queried_object_id();
	$before = '';
	$after = '';

	if(isset($_GET['rev']) && (int)$_GET['rev'] == 1) {
		$before = leyka_rev_campaign_top($campaign_id);
		$after = leyka_rev_campaign_bottom($campaign_id);
	}


	return $before.$content.$after;
}

add_action('wp_enqueue_scripts', 'leyka_rev_cssjs');
function leyka_rev_cssjs() {
	//for dev just load them everywhere

	wp_enqueue_style(
		'leyka-rev',
		LEYKA_PLUGIN_BASE_URL.'assets/css/public.css',
		array(),
		LEYKA_VERSION
	);

	wp_enqueue_script(
        'leyka-rev',
        LEYKA_PLUGIN_BASE_URL.'assets/js/public.js',
		array('jquery'),
        LEYKA_VERSION,
        true
    );

	$js_data = apply_filters('leyka_js_localized_strings', array(
        'ajaxurl' => admin_url('admin-ajax.php')
    ));

    wp_localize_script('leyka-rev', 'leykarev', $js_data);
}

add_action('wp_head', 'leyka_inline_js');
function leyka_inline_js() {

//detect if we have JS
?>
<script>
	document.documentElement.classList.add("leyka-js");
</script>
<?php
}

/** Templates **/
function leyka_rev_campaign_top($campaign_id) {

?>
<div id="leyka-pf-<?php echo $campaign_id;?>" class="leyka-pf">
<?php include(LEYKA_PLUGIN_DIR.'assets/svg/svg.svg');?>
<div class="leyka-pf__overlay"></div>

<div class="leyka-pf__module">
	<div class="leyka-pf__close leyka-js-close-form">x</div>
	<div class="leyka-pf__card inpage-card">
		<?php $thumb_url = get_the_post_thumbnail_url($campaign_id, 'post-thumbnail'); ?>
		<?php  if($thumb_url) { //add other terms ?>
			<div class="inpage-card__thumb" style="background-image: url(<?php echo $thumb_url;?>);"></div>
		<?php  } ?>

		<div class="inpage-card__content">
			<div class="inpage-card_title"><?php echo get_the_title($campaign_id);?></div>

			<div class="inpage-card_scale">
				<!-- NB: add class .fin to progress when it's 100% in fav of border-radius -->
				<div class="scale"><div class="progress" style="width:20%;"></div></div>
				<div class="target">50 000<span class="curr-mark">&#8381;</span></div>
				<div class="info">собрано из 250 000<span class="curr-mark">&#8381;</span></div>
			</div>

			<div class="inpage-card__note supporters">
				<strong>Поддержали:</strong> Василий Иванов, Мария Петрова, Семен Луковичный, Даниил Черный, Ольга Богуславская и <a href="#" class="history-more">еще 35 человек</a>
			</div>

			<div class="inpage-card__action">
				<button type="button" class="leyka-js-open-form">Поддержать</button>
			</div>
		</div>
	</div>

	<div class="leyka-pf__form">

	<form action="#" method="post" novalidate="novalidate">
	<!-- step amount -->
	<div class="step step--amount step--active">
		<div class="step__selection"></div>

		<div class="step__content">
			<div class="step__title">Укажите сумму</div>

			<div class="step__fields amount">

				<div class="amount__figure">
					<input type="text" name="leyka_amount" value="500" autocomplete="off" />
					<span class="curr-mark">&#8381;</span>
					<input type="hidden" name="monthly" value="0">
				</div>

				<div class="amount__icon">
					<svg class="svg-icon pic-money-middle"><use xlink:href="#pic-money-middle" /></svg>
					<div class="amount__error">Укажите сумму от 10 до 30&nbsp;000 руб.</div>
				</div>

				<div class="amount_range">
					<input  name="amount-range" type="range" min="100" max="2500" step="200" value="500">
				</div>

			</div>

			<div class="step__action">
				<!-- hidden field to store choice ? -->
				<a href="cards" class="leyka-js-amount">Поддержать разово</a>
				<a href="person" class="leyka-js-amount monthly">
					<svg class="svg-icon icon-card"><use xlink:href="#icon-card" /></svg>Ежемесячно</a>
			</div>
		</div>
	</div>

	<!-- step pm -->
	<div class="step step--cards">
		<div class="step__selection">
			<a href="amount" class="leyka-js-another-step">
				<span class="remembered-amount">500</span>&nbsp;<span class="curr-mark">&#8381;</span>
			</a>
		</div>

		<div class="step__content">
			<div class="step__title">Выберите способ оплаты</div>

			<div class="step__fields payments-grid">
			<!-- hidden field to store choice ? -->
			<?php
				$items = array(
					'bcard' => array('label' => 'Банковская карта', 'icon' => 'pic-bcard'),
					'yandex' => array('label' => 'Яндекс.Деньги', 'icon' => 'pic-yandex'),
					'sber' => array('label' => 'Сбербанк Онлайн', 'icon' => 'pic-sber'),
					'check' => array('label' => 'Квитанция', 'icon' => 'pic-check'),
				);

				foreach($items as $key => $item) {
			?>
				<div class="payment-opt">
					<label class="payment-opt__button">
						<input class="payment-opt__radio" name="payment_option" value="<?php echo esc_attr($key);?>" type="radio">
						<span class="payment-opt__icon">
							<svg class="svg-icon <?php echo esc_attr($item['icon']);?>"><use xlink:href="#<?php echo esc_attr($item['icon']);?>"/></svg>
						</span>
					</label>
					<span class="payment-opt__label"><?php echo $item['label'];?></span>
				</div>
			<?php } ?>
			</div>
		</div>
	</div>

	<!-- step data -->
	<div class="step step--person">
		<div class="step__selection">
			<a href="amount" class="leyka-js-another-step">
				<span class="remembered-amount">500</span>&nbsp;<span class="curr-mark">&#8381;</span>
				<span class="remembered-monthly">ежемесячно </span>
			</a>
			<a href="cards" class="leyka-js-another-step"><span class="remembered-payment">Банковская карта</span></a>
		</div>

		<div class="step__content step__content--border">
			<div class="step__title">Кого нам благодарить?</div>

			<div class="step__fields donor">

				<div class="donor__textfield donor__textfield--name ">
					<label for="leyka_donor_name">
						<span class="donor__textfield-label leyka_donor_name-label">Имя</span>
						<span class="donor__textfield-error leyka_donor_name-error">Укажие имя</span>
					</label>
					<input type="text" name="leyka_donor_name" value="" autocomplete="off">
				</div>

				<div class="donor__textfield donor__textfield--email">
					<label for="leyka_donor_email">
						<span class="donor__textfield-label leyka_donor_name-label">Email</span>
						<span class="donor__textfield-error leyka_donor_email-error">Укажие email в формате test@test.ru</span>
					</label>
					<input type="email" name="leyka_donor_email" value="" autocomplete="off">
				</div>

				<div class="donor__submit">
					<input type="submit" value="Продолжить">
				</div>

				<div class="donor__oferta">
					<span><input type="checkbox" name="leyka_agree" value="1" checked="checked">
					<label for="leyka_agree">Я принимаю  <a href="#" class="leyka-js-oferta-trigger">договор-оферту</a></label></span>
					<span class="donor__oferta-error leyka_agree-error">Укажите согласие с офертой</span>
				</div>

			</div>
		</div>

		<div class="step__note">
			<p><a href="http://www.consultant.ru/document/cons_doc_LAW_162595/" target="_blank">110-ФЗ от 5 мая 2014 года</a> обязывает нас спрашивать имя и почту.</p>
		</div>

	</div>
	</form>
	</div>

	<div class="leyka-pf__redirect">
		<div class="waiting">
			<div class="waiting__card">
				<div class="loading">
					<div class="spinner">
						<div class="bounce1"></div>
						<div class="bounce2"></div>
						<div class="bounce3"></div>
					</div>
				</div>
				<div class="waiting__card-text">Ждем ответа платежной системы</div>
			</div>
		</div>
	</div>

	<div class="leyka-pf__oferta ">
		<div class="leyka-pf__oferta-action"><a href="#" class="leyka-js-oferta-close">Я принимаю договор-оферту</a></div>
		<?php echo apply_filters('leyka_terms_of_service_text', do_shortcode(leyka_options()->opt('terms_of_service_text')));?>
		<div class="leyka-pf__oferta-action"><a href="#" class="leyka-js-oferta-close">Я принимаю договор-оферту</a></div>
	</div>
</div><!-- columnt -->
</div>
<?php
	$out = ob_get_contents();
	ob_end_clean();

	return $out;
}

function leyka_rev_campaign_bottom($campaign_id) {

	ob_start();
?>
<div rel="leyka-pf-<?php echo $campaign_id;?>" class="leyka-pf-bottom">
	<div class="">Сделайте пожертвование</div>
	<div class="">
		<input type="text" value="500" name="leyka_temp_amount">
		<span>&#8381;</span>
	</div>
	<div class="">
		<button type="button">Поддержать</button>
	</div>
	<div class="">
		<strong>Поддержали:</strong> Василий Иванов, Мария Петрова, Семен Луковичный, Даниил Черный, Ольга Богуславская и еще 35 человек
	</div>
</div>
<?php

	$out = ob_get_contents();
	ob_end_clean();

	return $out;
}
<?php
$extension_name = "Import / Export Pro";
$api_url = defined('DEVMAN_SERVER_TEST') ? DEVMAN_SERVER_TEST : 'https://devmanextensions.com/';
$extension_name_image = '<a href="https://devmanextensions.com/" target="_blank"><img src="'. $api_url . 'opencart_admin/common/img/devman_face.png"> DevmanExtensions.com</a> - '.$extension_name;

$_['extension_version'] = '9.1.9';
// Heading
$_['heading_title']    = $extension_name_image.' (V.'.$_['extension_version'].')';
$_['heading_title_2']  = $extension_name;

$_['text_buttom']      = 'Import / Export Pro';
$_['text_license_info'] = '<h3>Где можно найти ID заказа (ID лицензии)?</h3>
<p>После оформления заказа, Вы получите полную информацию о лицензии на электронную почту, которая использовалась при оформлении заказа. Обязательно проверьте <b>папку SPAMr</b>.</p>
<br>
<p>В зависимости от того, где вы приобрели лицензию, идентификатор заказа будет отличаться:</p>
<ul>
<li>Лицензия из магазина <a href="https://devmanextensions.com/extensions-shop" target="_blank">Devman Store</a>: <b>MLXXXXXX</b></li>
<li>Лицензия из магазина Opencart: <b>XXXXXX</b> ("XXXXXX" числовое значение).</li>
<li>Лицензия из магазина Opencartforum: <b>of-XXXXXX</b> ("XXXXXX" числовое значение).</li>
<li>Лицензия из магазина IsenseLabs: <b>isenselabs-XXXXXX</b> ("XXXXXX" числовое значение).</li>
</ul>
';
$_['curl_error'] = '<b>Ошибка CURL: %s</b><br><br>
<p>Соединение между Вашим сервером и сервером для проверки лицензии не было установлено.</p>
<p><b>Свяжитесь со службой поддержки Вашего хостинга</b>, они смогут помочь в решении этой проблемы.</p>
<p>Это расширение создает простой запрос CURL для домена https://devmanextensions.com (217.61.128.42).</p>';

<?php
//** Prevod - Translated by: Berdice.si - Do not remove! **//
$extension_name = "Uvoz / Izvoz Pro";
$api_url = defined('DEVMAN_SERVER_TEST') ? DEVMAN_SERVER_TEST : 'https://devmanextensions.com/';
$extension_name_image = '<a href="https://devmanextensions.com/" target="_blank"><img src="'. $api_url . 'opencart_admin/common/img/devman_face.png"> DevmanExtensions.com</a> - '.$extension_name;

$_['extension_version'] = 'EXTENSION_VERSION';
// Heading
$_['heading_title']    = $extension_name_image.' (V.'.$_['extension_version'].')';
$_['heading_title_2']  = $extension_name;

$_['text_buttom']      = 'Uvoz / Izvoz Pro';
$_['text_license_info'] = '<h3>Kje lahko najdem ID naročila (ID licence)?</h3>
<p>Po nakupu bi morali prejeti vse podatke o svoji licenci na e-pošto, ki ste jo uporabili pri nakupu licence, preverite svoj <b>SPAM mapo</b> e-pošte.</p>
<br>
<p>ID naročila bo odvisen od tega, kje je kupljena licenca:</p>
<ul>
<li>Licenca kupljena v <a href="https://devmanextensions.com/extensions-shop" target="_blank">Devman Store</a>: <b>MLXXXXXX</b></li>
<li>Licenca kupljena v Opencart marketplace: <b>XXXXXX</b> ("XXXXXX" je številčna vrednost).</li>
<li>Licenca kupljena v Opencartforum: <b>of-XXXXXX</b> ("XXXXXX" je številčna vrednost).</li>
<li>Licenca kupljena v IsenseLabs: <b>isenselabs-XXXXXX</b> ("XXXXXX" je številčna vrednost).</li>
</ul>
';
$_['curl_error'] = '<b>CURL ŠT. NAPAKE: %s</b><br><br>
<p>Vaš strežnik ni dovolil povezave z našim API-jem za preverjanje licence.</p>
<p><b>Povežite se z ekipo za podporo internet gostovanja</b>, težave so zunanje narave.</p>
<p>Ta razširitev izvaja preprost klic v CURL domeno https://devmanextensions.com (217.61.128.42).</p>';
//** Prevod - Translated by: Berdice.si - Do not remove! **//

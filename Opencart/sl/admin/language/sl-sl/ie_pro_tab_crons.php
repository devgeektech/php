<?php
//** Prevod - Translated by: Berdice.si - Do not remove! **//
$_['cron_version'] = 'EXTENSION_VERSION';
$_['thead_cron_cron'] = 'Profil';
$_['thead_cron_status'] = 'Status';
$_['thead_cron_email'] = 'Prijavi e-pošto';
$_['thead_cron_period'] = 'Obdobje ponovitve';
$_['thead_cron_configurator'] = 'Nastavitve CRON obdelav';
$_['cron_all_minutes'] = 'Vse minute';
$_['cron_all_hours'] = 'Vse ure';
$_['cron_all_days'] = 'Vse dni meseca';
$_['cron_all_months'] = 'Vse mesece';
$_['cron_all_weekdays'] = 'Vse delavnike';
$_['cron_php_path'] = 'Pot do php';
$_['cron_php_path_remodal_title'] = 'Pot do php';
$_['cron_php_path_remodal_description'] = '        <p>Tu vnesete vašo PHP pot, če niste prepričani stopite v stik s svojim internet gostiteljskim podjetjem.</p>
        <p>Nekaj pogostih poti je:</p>
        <ul>
            <li>/usr/bin/php</li>
            <li>/usr/local/bin/php</li>
            <li>/usr/local/cpanel/3rdparty/bin/php</li>
        </ul>
    ';
$_['cron_php_path_remodal_link'] = '<b>POMEMBNO:</b> Kliknite za branje';
$_['cron_config_remodal_title'] = 'Nastavitve CRON obdelav';
$_['cron_config_remodal_description'] = '<p>Here you can find 3 options for configure CRON Jobs <b>in your server settings</b>, we are not responsible for your actions carried out in the configuration of your server and the CRON Jobs settings <b>are not included in support</b>.</p>
<p>In Opencart side, for CRON Jobs can works, you have to <b>enable it</b> in this tab table (nothing more), also, if you enter an email address inside the input field "<b>Email</b>", a CRON job report will be sent to email address.</p>
<p>Do not forget click button "<b>Save CRONs configuration</b>" for save CRON Jobs settings.</p>
<br>
<h1>CRON Configuration - Server side</h1>
<p style="color: #0D4AA2;"><b>OPTION 1 - SETTING CRON JOBS WITH HOSTING INTERFACE:</b></p>
<p>Some modern hostings like "Plesk", "Cpanel"... has an interface to configure CRON Jobs, here you can see some example:</p>

<ol>
    <li>Select your webspace (option may not be available in your panel).</li>
    <li><b>Task type</b>: Run a PHP script</li>
    <li>Put CRON file path: <a href="javascript:{}" onclick="copy_text_to_clipboard($(this).next(\'div.to_copy\').html())">Copy path</a><div class="to_copy" style="display: none">CRON_PATH</div></a></li>
    <li>Put CRON arguments: <a href="javascript:{}" onclick="copy_text_to_clipboard($(this).next(\'div.to_copy\').html())">Copy arguments</a><div class="to_copy" style="display: none">CRON_ARGUMENTS</div></a></li>
    <li>Set <b>Run</b> periods when you CRON will be executed.</li>
    <li>Set a <b>Description</b></li>
    <li>Click <b>OK button </b>. (or Save or Apply)</li>
</ol>
<img style="width: 605px;" src="%s">
<br><br>
<p style="color: #0D4AA2;"><b>OPTION 2 - INSERT CRON VIA SSH:</b></p>
<ol>
    <li>Access your Server <b>via SSH</b>. </li>
    <li>Execute command: <b>crontab –e</b></li>
    <li><b>Paste</b> CRON command (examples at bottom).</li>
    <li>Make desired changes and hit “<b>Ctr+X</b>” followed by “<b>Y</b>”.</li>
</ol>
<b><u>Example 1 - Every 15 minutes</u></b> - <a href="javascript:{}" onclick="copy_text_to_clipboard($(this).next(\'div.to_copy\').html())">Copy example</a><div class="to_copy" style="display: none">*/15 * * * * PATH_TO_PHP CRON_PATH CRON_ARGUMENTS</div><br>
<b><u>Example 2 - 1 time per day at 00:00</u></b> - <a href="javascript:{}" onclick="copy_text_to_clipboard($(this).next(\'div.to_copy\').html())">Copy example</a><div class="to_copy" style="display: none">0 0 * * * PATH_TO_PHP CRON_PATH CRON_ARGUMENTS</div><br>
<b><u>Example 3 - 2 times per day at 00:00 and 12:00</u></b> - <a href="javascript:{}" onclick="copy_text_to_clipboard($(this).next(\'div.to_copy\').html())">Copy example</a><div class="to_copy" style="display: none">0 */12 * * * PATH_TO_PHP CRON_PATH CRON_ARGUMENTS</div><br>
<b><u>Example 4 - Every Sunday at 00:00</u></b> - <a href="javascript:{}" onclick="copy_text_to_clipboard($(this).next(\'div.to_copy\').html())">Copy example</a><div class="to_copy" style="display: none">0 0 * * 0 PATH_TO_PHP CRON_PATH CRON_ARGUMENTS</div><br>
<b><u>Example 5 - Every Month</u></b> - <a href="javascript:{}" onclick="copy_text_to_clipboard($(this).next(\'div.to_copy\').html())">Copy example</a><div class="to_copy" style="display: none">0 0 30 * * PATH_TO_PHP CRON_PATH CRON_ARGUMENTS</div><br>
<br>
<b style="color: #f00;">PATH_TO_PHP:</b> In copied examples, you will see "PATH_TO_PHP", you have to resplace it by your server path to PHP, if you are not sure where you cant find it, put in contact with your hosting company.
<br><br>
<p style="color: #0D4AA2;"><b>OPTION 3 - WGET Command:</b></p>
<p>If you are having problems configuring CRON Jobs with the tradicional way, you can use "wget" command for execute your CRON Job</p>
<ol>
    <li>Make sure that your CRON Job is in mode "<b>Command</b>".</li>
    <li><a href="javascript:{}" onclick="copy_text_to_clipboard($(this).next(\'div.to_copy\').html())">Copy next command</a><div class="to_copy" style="display: none">CRON_WGET_COMMAND</div></a> and paste it in your "command" text area.</li>
    <li>Save your CRON Jobs settings.</li>
</ol>
<h1>Execute this CRON Job manually</h1>
You can execute a simulation of your CRON Job in <a href="EXECUTE_PROFILE_NOW" target="_blank">next link</a>. You will be unlogged of admin area.';

$_['cron_config_remodal_link'] = 'Vodič - Navodila';
$_['cron_error_profile_id'] = 'Napaka: Izberite profil iz CRON tabele.';
$_['cron_error_path_to_php'] = 'Napaka: Zaprite to okno in vnesite \"<b>Pot do PHP</b>\", ki je potrebena za izvedbo vaše CRON Obdelave.';
$_['cron_command_copied'] = 'Kopirano v odložišče.';
$_['cron_month_1'] = 'Januar';
$_['cron_month_2'] = 'Februar';
$_['cron_month_3'] = 'Marec';
$_['cron_month_4'] = 'April';
$_['cron_month_5'] = 'Maj';
$_['cron_month_6'] = 'Junij';
$_['cron_month_7'] = 'Julij';
$_['cron_month_8'] = 'Avgust';
$_['cron_month_9'] = 'September';
$_['cron_month_10'] = 'Oktober';
$_['cron_month_11'] = 'November';
$_['cron_month_12'] = 'December';
$_['cron_weekday_0'] = 'Ponedeljek';
$_['cron_weekday_1'] = 'Torek';
$_['cron_weekday_2'] = 'Sreda';
$_['cron_weekday_3'] = 'Četrtek';
$_['cron_weekday_4'] = 'Petek';
$_['cron_weekday_5'] = 'Sobota';
$_['cron_weekday_6'] = 'Nedelja';
$_['cron_save'] = 'Shranite CRON nastavitve';
$_['cron_config_save_sucessfully'] = 'Nastavitve so bile uspešno shranjene!';
$_['cron_config_save_error_repeat_profiles'] = '<b>Napaka:</b> Najden je podvojen profil';
$_['cron_error_disabled'] = 'Profil "<b>%s</b>" je v CRON nastavitvah onemogočen.';
$_['cron_error_not_found'] = '"<b>%s</b>" profila ni mogoče najti v CRON nastavitvah.';
//** Prevod - Translated by: Berdice.si - Do not remove! **//
?>
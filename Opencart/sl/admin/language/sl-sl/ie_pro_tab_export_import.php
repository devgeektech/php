<?php
//** Prevod - Translated by: Berdice.si - Do not remove! **//

$_['export_import_launch_profile_main_title'] = 'Zaženi profil';
$_['export_import_launch_profile_description'] = '<p>Dobrodošli v <b>Uvoz Izvoz PRO</b>. Tukaj lahko zaženete že ustvarjene profile za Uvoz/Izvoz. Če še niste ustvarili profila, pojdite na razdelek "<b>Odprite ali Uredite Uvoz/Izvoz profila</b>", kjer lahko ustvarite svoj prvi profil.</p>
<p>Profil odprete tako, da ga izberete v desnem delu in pritisnete rdeči gumb "<b>Zaženi izbrani profil</b>".</p>
<p>Na zavihku "<a href="javascript:{}" onclick="$(\'a.tab_cron-jobs, a.tab_cron-задания, a.tab_cron-obdelave\').click()"><b>CRON Obdelave</b></a>" lahko dodajate in spreminjate CRON Obdelave tako, da samodejno zaženejo postopke uvoza ali izvoza, kadar to želite. To je idealno za sinhronizacijo podatkov trgovine z dobavitelji (zaloge, cene ...), ali pa popolno varnostno kopijo sistema.</p>';
$_['export_import_profile_legend_text'] = 'Izberite profil za nadaljevanje postopka Izvoza/Uvoza.';
$_['export_import_profile_load_select'] = 'Izberite profil';
$_['export_import_profile_upload_file'] = 'Naloži datoteko';
$_['export_import_profile_input_from'] = 'Od (samo številke)';
$_['export_import_profile_input_from_help'] = 'Pustite prazno, da odstranite območje "Od"';
$_['export_import_profile_input_to'] = 'Do (samo številke)';
$_['export_import_profile_input_to_help'] = 'Pustite prazno, da odstranite območje "Do"';
$_['export_import_profile_upload_file_help'] = 'Upoštevajte, da mora biti datoteka zapisa združljiva s formatom, shranjenim v vašem profilu.';
$_['export_import_start_button'] = 'Zaženi izbrani profil';
$_['export_import_error_empty_profile'] = '<b>Napaka:</b> Izberite profil';
$_['export_import_error_profile_not_found'] = '<b>Napaka:</b> Profila ni mogoče najti.';
$_['export_import_error_xml_item_node'] = '<b>Napaka:</b> Element XML vozlišča je prazen. Naložite nastavitve svojega profila in določite Element XML vozlišča.';
$_['export_import_error_xml_item_node_not_found'] = '<b>Napaka:</b> XML vozlišča "<b>%s</b>" ni mogoče najti v XML podatkih.';
$_['export_import_remodal_process_title'] = 'Operacija v teku';
$_['export_import_remodal_process_subtitle'] = 'PHP procesa zagnanega iz spletnega brskalnika ni mogoče ročno ustaviti. Ustavil se bo samodejno ob koncu postopka. Medtem tem lahko opazite, da vaše spletno mesto deluje počasneje. To vpliva samo na to sejo brskalnika, ostali obiskovalci ne bodo opazili razlike.';
$_['export_import_remodal_server_config_title'] = 'Omejitve strežnika';
$_['export_import_remodal_server_config_description'] = '        <p>Trudimo se, da bi dosegli čim boljšo optimizacijo Uvozno Izvoznih postopkov. Če boste izvajali procese, ki presegajo omejitve vašega strežnika, se bodo pojavile napake in procesi ne bodo končani.</p>
<p><b>Takšne napake niso povezane s tem orodjem</b>, preveriti boste morali naslednje PHP direktive vašega strežnika:</p>

        <p>Postopek presega nekatere vrednosti teh PHP direktiv:</p>
        <ul>
            <li><b>memory_limit</b> (Megabytes)</li>
            <li><b>max_execution_time</b> (Seconds)</li>
            <li><b>upload_max_filesize</b> (Megabytes)</li>
            <li><b>post_max_size</b> (Megabytes)</li>
        </ul>

        <p>Vrednosti so odvisne od velikosti postopka oz. datoteke, ki jo poskušate naložiti, na primer:</p>
        <ul>
            <li><b>memory_limit</b>: 512M</li>
            <li><b>max_execution_time</b>: 800</li>
            <li><b>upload_max_filesize</b>: 240M</li>
            <li><b>post_max_size</b>: 250M</li>
        </ul>

        <p>Najboljši način za spremembo teh vrednosti je neposredno v nastavitvah strežnika. Če tega ne morete spremeniti sami priporočamo, da o tem vprašate ponudnika internet gostovanja.</p>
        <p>---------------------------</p>
        <p>Preizkusite lahko tudi naslednje ročne postopke:</p>

        <p><b>1 NAČIN: SPREMENITE ALI DODAJTE NASLEDNJE VREDNOSTI V ADMIN/PHP.INI:</b></p>
        <p>
        memory_limit = 512M<br>
        max_execution_time = 800<br>
        upload_max_filesize = 240M<br>
        post_max_size = 250M<br>
        </p>

        <p><b>2 NAČIN: USTVARI ADMIN/.HTACCESS DATOTEKO Z NASLEDNJO VSEBINO:</b></p>
        <p>
        php_value memory_limit 512M<br>
        php_value max_execution_time 800<br>
        php_value upload_max_filesize 240M<br>
        php_value post_max_size 250M<br>
        </p>

        <p><b>KAKO PREVERJATI, ALI SE VREDNOSTI UPORABLJAJO:</b></p>
        <ol>
            <li>Ustvari datoteko v vaši korenski poti "phpinfo.php" z naslednjo PHP vsebino:<pre>&#60;?php phpinfo(); ?></pre></li>
            <li>Dostop do http://yourdomain.com/phpinfo.php</li>
            <li>Pritisnite CTRL+F/COMMAND+F in poiščite imena direktiv</li>
        </ol>

        <p>Če še vedno vidite stare vrednosti je možno, da 1 in 2 NAČIN nista delovala, ker vaš strežnik ne dovoljuje tovrstnih sprememb v php.ini/.htaccess datotekah. V tem primeru je potrebno spremembe izvesti neposredno v konfiguraciji strežnika, apache pa bo potrebno znova zagnati.</p>
        ';
$_['export_import_remodal_server_config_link'] = 'POMEMBNO! PREBERITE PRED ZAGONOM PROFILA';
$_['progress_export_starting_process'] = '<b>Začetek postopka izvoza...</b>';
$_['progress_export_element_numbers'] = 'Podatki za izvoz <b>%s</b>';
$_['progress_export_processing_elements'] = '<b>Obdelovanje podatkov za izvoz...</b>';
$_['progress_export_processing_elements_processed'] = 'Podatkov obdelanih: <b>%s</b> od <b>%s</b>';
$_['progress_export_elements_inserted'] = 'Podatkov dodanih: <b>%s</b> od <b>%s</b>';
$_['progress_export_error_range'] = '<b>Napaka:</b> Razpon "<b>od</b>" je večji od dosega "<b>do</b>"';
$_['progress_export_error_fixed_columns_match_operation'] = 'Naslednja matematična operacija ni mogoča: "<b>%s</b>" za element: %s';

$_['progress_import_error_columns'] = '<b>Napaka:</b> Sistem je zaznal, da naložena datoteka nima stolpcev, ki jih pričakuje konfiguracija vašega profila:
        <br><br>
        <b>Stolpci v DATOTEKI:</b>
        %s
        <br>
        <b>Stolpci v PROFILU:</b>
        %s
    ';
$_['progress_import_starting_process'] = '<b>Začetek postopka uvoza...</b>';
$_['progress_import_from_product_creating_categories'] = '<b>Priprava kategorij...</b>';
$_['progress_import_from_product_created_categories'] = 'Kategorije pripravljene <b>%s</b>';
$_['progress_import_from_product_error_cat_repeat_categories'] = '<b>Napaka:</b> Ime kategorije <a href="%s" target="_blank"><b>%s</b></a> se ponovi, preimenujete ga ali uporabite "Drevo kategorij" v svojem profilu.';
$_['progress_import_from_product_creating_filter_groups'] = '<b>Priprava filtrirnih skupin...</b>';
$_['progress_import_from_product_created_filter_groups'] = 'Filtrirne skupine pripravljene <b>%s</b>';
$_['progress_import_from_product_creating_filter_groups_error_repeat'] = '<b>Napaka:</b> Ime Filtrirne skupine <a href="%s">"<b>%s</b>"</a> se ponovi.';
$_['progress_import_from_product_creating_filters'] = '<b>Priprava filtrov...</b>';
$_['progress_import_from_product_created_filters'] = 'Filtri pripravljeni <b>%s</b>';
$_['progress_import_from_product_creating_filters_error_no_group'] = 'Filtra ni mogoče ustvariti "<b>%s</b>", Filtrirna skupina temu filtru ni bila dodeljena.';
$_['progress_import_from_product_creating_attribute_groups'] = '<b>Priprava lastnosti skupin...</b>';
$_['progress_import_from_product_created_attribute_groups'] = 'Lastnosti skupin pripravljene <b>%s</b>';
$_['progress_import_from_product_creating_attribute_groups_error_repeat'] = '<b>Napaka:</b> Ime Lastnosti skupine <a href="%s">"<b>%s</b>"</a> se ponovi.';
$_['progress_import_from_product_creating_attributes'] = '<b>Priprava Lastnosti...</b>';
$_['progress_import_from_product_created_attributes'] = 'Lastnosti pripravljene <b>%s</b>';
$_['progress_import_from_product_creating_attributes_error_no_group'] = 'Sistem ne more ustvariti Lastnosti "<b>%s</b>", Lastnost skupine tej Lastnosti ni bila dodeljena.';
$_['progress_import_from_product_creating_manufacturers'] = '<b>Priprava proizvajalcev...</b>    ';
$_['progress_import_from_product_created_manufacturers'] = 'Proizvajalci pripravljeni <b>%s</b>';
$_['progress_import_from_product_creating_options_error_empty_main_field'] = '<b>Napaka:</b>  ID-ja izdelka "<b>%s</b>" v vaši datoteki ni bilo mogoče najti.
Če želite uporabiti možnosti izdelka, omogočite ID izdelka v nastavitvah profila. V nasprotnem primeru onemogočite stolpce z možnostmi "<b>Možnost XXXX</b>".';
$_['progress_import_from_product_creating_options'] = '<b>Priprava Možnosti...</b>';
$_['progress_import_from_product_created_options'] = 'Možnosti pripravljene <b>%s</b>';
$_['progress_import_from_product_creating_options_error_repeat'] = '<b>Napaka:</b> Ime možnosti <a href="%s">"<b>%s</b>"</a>, tip "<b>%s</b>" se ponovi.';
$_['progress_import_from_product_creating_options_error_option_type'] = '<b>Napaka:</b> Če želite spreminjati možnostmi izdelka, je treba tej možnosti dodeliti Tip možnost "<b>%s</b>"';
$_['progress_import_from_product_creating_option_values'] = '<b>Priprava možnih vrednosti...</b>';
$_['progress_import_from_product_created_option_values'] = 'Možne vrednosti pripravljene <b>%s</b>';
$_['progress_import_from_product_creating_option_values_error_option_type'] = 'Napaka vrstice <b>%s</b>: Če želite spreminjati možnostmi izdelka, je treba tej možnosti dodeliti Možne vrednosti "<b>%s</b>"';
$_['progress_import_from_product_creating_option_values_error_option'] = 'Napaka vrstice <b>%s</b>: Za upravljanje z možnimi vrednostmi izdelka je potrebno dodeliti Možne vrednosti "<b>%s</b>"';
$_['progress_import_from_product_creating_downloads'] = '<b>Priprava prenosa...</b>';
$_['progress_import_from_product_created_downloads'] = 'Prenos pripravljen <b>%s</b>';
$_['progress_import_product_error_option_data_in_main_row'] = '<b>Napaka vrstice %s</b>: Zaznani podatki o možnostih v glavni vrstici izdelka. Izbriši vsebino vseh "<b>Možnost xxxxx</b>" stolpcev.';
$_['progress_import_product_error_product_related_not_found'] = '<b>Napaka v vrstici %s</b>: Povezanega modela izdelka %s ni bilo mogoče najti v vaši trgovini. Prepričajte se, da se izdelek pojavlja v vaši preglednici <b>PRED vrstico %s</b>.';
$_['progress_import_elements_process_start'] = '<b>Začetek obdelave podatkov...</b>';
$_['progress_import_elements_processed'] = 'Podatki obdelani: <b>%s</b> od <b>%s</b>';
$_['progress_import_error_main_identificator'] = 'Glavni identifikator izdelka "<b>%s</b>" ne obstaja v vaših podatkih, preverite, ali ste omogočili ta stolpec v "<b>Mapiranje stolpcev</b>" odseku ali ta <b>stolpec obstaja</b> v datoteki, ki jo poskušate uvoziti.';
$_['progress_import_process_format_data_file'] = '<b>Oblikovanje podatkovne datoteke...</b>';
$_['progress_import_process_format_data_file_progress'] = 'Elementi so oblikovani: <b>%s</b> od <b>%s</b>';
$_['progress_import_elements_conversion_start'] = '<b>Pretvarjanje vrednosti elementov...</b>';
$_['progress_import_elements_converted'] = 'Pretvorjene vrednosti elementov:  <b>%s</b> of <b>%s</b>';
$_['progress_import_process_start'] = '<b>Začetek postopka uvoza...</b> Opozorilo: prosimo, bodite potrpežljivi, postopek običajno traja dlje časa! %s';
$_['progress_import_process_imported'] = 'Uvoženi elementi:  <b>%s</b> od <b>%s</b>';
$_['progress_import_applying_changes_safely'] = '<b>Varna uporaba sprememb...</b>';
$_['progress_import_finished'] = '<b>%s</b><b>Uvoz je bil uspešno končan!</b>
                <ul>
                    <li>Elementi pripravljeni: <b>%s</b></li>
                    <li>Elementi spremenjeni: <b>%s</b></li>
                    <li>Elementi izbrisani: <b>%s</b></li>
                </ul>';
$_['progress_import_error_updating_conditions'] = 'NOTRANJA NAPAKA: Poskusi posodobitev vrstice tabele brez pogojev: <b>%s</b>';
$_['progress_import_error_skipped_all_elements'] = 'Vsi elementi znotraj te datoteke so preskočeni, preverite "<b>Pred-filter</b>" nastavitve v profilu.';
$_['progress_import_error_empty_data'] = '<b>Napaka:</b> Prazni podatki. Prepričajte se, da je naložena datoteka združljiva s stolpci vašega profila.';
$_['export_import_download_empy_file'] = 'Kliknite za prenos vzorčne datoteke profila';
$_['progress_import_elements_splitted_values_start'] = '<b>Razdelitev in pridobivanje vrednosti...</b>';
$_['progress_import_elements_splitted_progress'] = 'Elementi obdelani:  <b>%s</b> of <b>%s</b>';
$_['progress_import_export_error_wrong_conditional_value'] = 'Pogojna vrednost "<b>%s</b>" ni pravilno pripravljena. Preverite "<b>Pogojna vrednost</b>" pomoč.';
$_['progress_import_export_error_wrong_conditional_value_multiple_symbols'] = 'Conditional value "<b>%s</b>" is not constructed correctly. One or more conditional value "<b>%s</b>" found. Check "<b>Conditional value</b>" pomoč.';
$_['progress_import_export_error_incorrect_quoted_string'] = 'Napačno citiran niz (manjka začetni ali končni citat): %s';
$_['progress_import_export_error_missing_conditional_filter'] = 'Napačno ali manjkajoče ime pogojnega filtra: "%s"';
$_['progress_import_export_error_evaluating_filter'] = 'Napaka pri oceni filtra "%s": %s';
$_['progress_import_export_error_invalid_filter_syntax'] = 'Neveljavna sintaksa filtra: "%s"';
$_['progress_import_export_error_invalid_boolean_filter'] = 'Neveljavna logična vrednost filtra (pričakovana 1 ali 0): "%s"';
$_['progress_import_export_error_conditional_missing_symbol'] = 'Pogojni izraz: manjkajoč primerjalnik: "%s"';
$_['progress_import_product_error_empty_description'] = '<b>Napaka pri ustvarjanju izdelka</b>: Poskus ustvarjanja izdelka brez opisnih podatkov (ime, opis itd.), Json izdelek: %s.
';
$_['progress_import_elements_no_numeric_id'] = '<b>ID napake ni numeričen</b>: V nekaj stolpcih ste omogočili "ID namesto imen", sistem zazna neštevilčni ID: <b>%s</b>.';
$_['progress_import_product_option_values_error_option_doesnt_exist'] = '<b>Napaka v vrstici datoteke %s:</b> Možnost "<b>%s</b>" ne obstaja. Preverite, ali ste uvozili vse možnosti, preden uvozite asociacije vrednosti izdelka.';
$_['progress_import_product_option_values_error_not_product_identificator'] = '<b>Napaka v vrstici datoteke %s:</b> Identifikator izdelka ne obstaja';
$_['progress_import_applying_pre_filters'] = '<b>Uporaba Pred-Filtrov</b>';
$_['progress_import_applying_file_filters'] = 'Uporaba <b>file-filters</b>';
$_['progress_import_applying_shop_filters'] = 'Uporaba <b>shop-filters</b>';
$_['progress_import_elements_deleted'] = '<b>%s</b> Elementi izbrisani';
$_['progress_import_elements_skipped'] = '<b>%s</b> Elementi preskočeni';
$_['progress_import_elements_disabled'] = '<b>%s</b> Elementi onemogočeni';
$_['progress_import_mapping_categories'] = '<b>Mapiranje Kategorij</b>';

//** Prevod - Translated by: Berdice.si - Do not remove! **//
?>

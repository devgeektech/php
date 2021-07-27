<?php
//отображение блока в окне при выборе блока
$_['display']          = true;
//Категория(content, social, structure)
$_['category']         = 'section';
//отображать название блока
$_['display_title']    = false;
//Может содержать дочерние блоки
$_['child_blocks']     = true;
//Уровень доступный для добавления блока
$_['level_min']        = 4;
$_['level_max']        = 4;
//Расположение кнопок управления
$_['control_position'] = 'popup';
//Отображение кнопок управления
$_['display_control']  = true;
//Кнопка перетаскивания
$_['button_drag']      = false;
//Кнопка редатирования
$_['button_edit']      = true;
//Кнопка копирования
$_['button_copy']      = false ;
//Кнопка сворачивания
$_['button_collapse']  = true;
//Кнопка удаления
$_['button_remove']    = true;
//Свой шаблон
$_['custom_layout']    = 'section';
//Доступен пре-рендер
$_['pre_render'] = false;
//Доступно сохранение в html
$_['save_html'] = false;
//Типы полей
$_['types']           = array(
    'title' => 'string'
);
//Настройки по умолчанию
$_['setting'] = array(
    'section_id' => substr(md5(time()), 0, 7),
    'title' => 'Section'
);
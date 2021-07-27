<?php
//отображение блока в окне при выборе блока
$_['display']          = true;
//Порядковый номер
$_['sort_order']       = 9.1;
//Категория(content, social, structure)
$_['category']         = 'social';
//отображать название блока
$_['display_title']    = true;
//Может содержать дочерние блоки
$_['child_blocks']     = false;
//Уровень доступный для добавления блока
$_['level_min']       = 3;
$_['level_max']       = 7;
//Расположение кнопок управления
$_['control_position'] ='popup';
//Отображение кнопок управления
$_['display_control']  = true;
//Кнопка перетаскивания
$_['button_drag']      = true;
//Кнопка редатирования
$_['button_edit']      = true;
//Кнопка копирования
$_['button_copy']      = true ;
//Кнопка сворачивания
$_['button_collapse']  = true;
//Кнопка удаления
$_['button_remove']    = true;
//Доступен пре-рендер
$_['pre_render'] = false;
//Доступно сохранение в html
$_['save_html'] = false;
//Типы полей
$_['types']            = array(
    'href' => 'string',
    'theme' => 'string',
    'width' => 'string',
    'height' => 'string',
    'color_link' => 'string',
    'design_margin_bottom' => 'string'
);
//Настройки по умолчанию
$_['setting'] = array(
    'href' => 'https://twitter.com/dreamvention',
    'theme' => 'light',
    'width' => '',
    'height' => '500px',
    'color_link' => '#1DA1F2',
    'design_margin_bottom' => '15px'
);
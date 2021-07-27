<?php
//отображение блока в окне при выборе блока
$_['display']         = true;
//Порядковый номер
$_['sort_order']      = 24;
//Категория(content, social, structure)
$_['category'] = 'content';
//отображать название блока
$_['display_title']   = true;
//Может содержать дочерние блоки
$_['child_blocks']    = false;
//Уровень доступный для добавления блока
$_['level_min']       = 3;
$_['level_max']       = 7;
//Расположение кнопок управления
$_['control_position'] ='popup';
//Отображение кнопок управления
$_['display_control'] = true;
//Кнопка перетаскивания
$_['button_drag']     = true;
//Кнопка редатирования
$_['button_edit']     = true;
//Кнопка копирования
$_['button_copy']     = true ;
//Кнопка сворачивания
$_['button_collapse'] = true;
//Кнопка удаления
$_['button_remove']   = true;
//Доступен пре-рендер
$_['pre_render'] = false;
//Доступно сохранение в html
$_['save_html'] = false;
//Типы полей
$_['types']            = array(
    'title' => 'string',
    'value' => 'string',
    'label_value' => 'string',
    'units' => 'string',
    'bar_color' => 'string',
    'track_color' => 'string',
    'line_width' => 'string',
    'size' => 'string'
);
//Настройки по умолчанию
$_['setting'] = array(
    'title' => '',
    'value' => '40',
    'label_value' => '',
    'units' => '',
    'bar_color' => '#00C1CF',
    'track_color' => '#f9f9f9',
    'line_width' => '3px',
    'size' => '150px'
);
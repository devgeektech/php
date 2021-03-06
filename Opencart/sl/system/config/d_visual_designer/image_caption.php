<?php
//отображение блока в окне при выборе блока
$_['display']          = true;
//Порядковый номер
$_['sort_order']       = 10;
//Категория(content, social, structure)
$_['category']         = 'content';
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
$_['pre_render'] = true;
//Доступно сохранение в html
$_['save_html'] = true;
//Типы полей
$_['types']           = array(
    'text' => 'string',
    'image' => 'string',
    'title' => 'string',
    'image_alt' => 'string',
    'image_title' => 'string',
    'link' => 'string',
    'width' => 'string',
    'height' => 'string',
    'style' => 'string',
    'align' => 'string',
    'animate' => 'string',
    'size' => 'string',
    'onclick' => 'string',
    'display_border' => 'boolean',
    'padding_text' => 'string'
);
//Настройки по умолчанию
$_['setting'] = array(
    'text' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus in erat eu lacus varius venenatis ut ac urna.',
    'position_text' => 'right',
    'image' => '',
    'title' => '',
    'image_alt' => '',
    'image_title' => '',
    'link' => '',
    'width' => '200px',
    'height' => '100px',
    'style' => '',
    'align' => 'center',
    'animate' => '',
    'size' => 'responsive',
    'onclick' => 'popup',
    'display_border' => 1,
    'padding_text' => '20px'
);
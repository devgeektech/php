<?php
//отображение блока в окне при выборе блока
$_['display']          = true;
//Порядковый номер
$_['sort_order']       = 4;
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
    'title' => 'string',
    'title_align' => 'string',
    'color_title' => '#string',
    'font_size' => 'string',
    'font_family' => 'string',
    'bold' => 'boolean',
    'underline' => 'boolean',
    'italic' => 'boolean',
    'border_width' => 'string',
    'border_color' => 'string',
    'border_style' => 'string',
    'width' => 'string',
    'align' => 'string'
);
//Настройки по умолчанию
$_['setting']          = array(
    'title' => 'Title',
    'title_align' => 'center',
    'color_title' => '#000',
    'font_size' => '30px',
    'font_family' => '',
    'bold' => 0,
    'underline' => 0,
    'italic' => 0,
    'border_width' => '1px',
    'border_color' => '#000',
    'border_style' => 'solid',
    'width' => '60%',
    'align' => 'left'
);
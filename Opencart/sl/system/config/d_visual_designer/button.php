<?php
//отображение блока в окне при выборе блока
$_['display']         = true;
//Порядковый номер
$_['sort_order']      = 17;
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
$_['pre_render'] = true;
//Доступно сохранение в html
$_['save_html'] = true;
//Типы полей
$_['types']            = array(
    'text' => 'string',
    'action' => 'string',
    'link' => 'string',
    'product_id' => 'number',
    'quantity' => 'number',
    'new_window' => 'boolean',
    'style' => 'string',
    'shape' => 'string',
    'color' => 'string',
    'color_text' => 'string',
    'color_hover' => 'string',
    'size' => 'string',
    'alignment' => 'string',
    'full_width' => 'boolean',
    'display_icon' => 'boolean',
    'library' => 'string',
    'icon' => 'string',
    'border_width' => 'string',
    'border_color' => 'string',
    'border_color_hover' => 'string',
    'border_radius' => 'string',
    'width' => 'string',
    'height' => 'string',
    'letter_spacing' => 'string',
    'font_size' => 'string',
    'icon_color' => 'string',
    'icon_align' => 'string',
    'display_icon_hover' => 'boolean',
    'italic' => 'boolean',
    'bold' => 'boolean',
    'underline' => 'boolean',
    'font_family' => 'string'
);
//Настройки по умолчанию
$_['setting'] = array(
    'text' => 'Button',
    'action' => 'link',
    'link' => '',
    'product_id' => '',
    'quantity' => '',
    'new_window' => 0,
    'style' => 'modern',
    'shape' => 'rounded',
    'color' => '#fff',
    'color_text' => '#333',
    'color_hover' => '#e6e6e6',
    'size' => 'md',
    'alignment' => 'left',
    'full_width' => 0,
    'display_icon' => 0,
    'library' => 'fontawesome',
    'icon' => 'fa fa-adjust',
    'animate' => '',
    'border_width' => '1px',
    'border_color' => '#ccc',
    'border_color_hover' => '#adadad',
    'border_radius' => '4px',
    'width' => '',
    'height' => '',
    'letter_spacing' => '0px',
    'font_size' => '20px',
    'icon_color' => '#000',
    'icon_align' => 'left',
    'display_icon_hover' => '0',
    'italic' => '0',
    'bold' => '0',
    'underline' => '0',
    'font_family' => 'Open Sans'
);
<?php
//отображение блока в окне при выборе блока
$_['display']          = true;
//Порядковый номер
$_['sort_order']       = 21;
//Категория(content, social, structure)
$_['category']         = 'conversion';
//отображать название блока
$_['display_title']    = true;
//Может содержать дочерние блоки
$_['child_blocks']     = false;
//Уровень доступный для добавления блока
$_['level_min']       = 3;
$_['level_max']       = 7;
//Расположение кнопок управления
$_['control_position'] = 'popup';
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
$_['button_remove']   = true;
//Доступен пре-рендер
$_['pre_render'] = false;
//Доступно сохранение в html
$_['save_html'] = false;
//Типы полей
$_['types']            = array(
    'api' => 'string',
    'list_id' => 'string',
    'display_firstname' => 'boolean',
    'display_lastname' => 'boolean',
    'design_margin_bottom' => 'string',
    'inline' => 'boolean',
    'final_text' => 'string',
    
    'input_color_text' => 'string',
    'input_background_color' => 'string',
    'input_border_color' => 'string',
    'input_focus_border_color' => 'string',
    'input_border_width' => 'string',
    'input_border_radius' => 'string',
    'input_width' => 'string',
    'input_height' => 'string',
    'input_font_size' => 'string',
    
    'button_style' => 'string',
    'button_title' => 'string',
    'button_background_color' => 'string',
    'button_hover_background_color' => 'string',
    'button_color_text' => 'string',
    'button_hover_color_text' => 'string',
    'button_border_width' => 'string',
    'button_border_color' => 'string',
    'button_hover_border_color' => 'string',
    'button_border_radius' => 'string',
    'button_width' => 'string',
    'button_height' => 'string',
    'button_font_size' => 'string'
);
//Настройки по умолчанию
$_['setting'] = array(
    'api' => '',
    'list_id' => '',
    'display_firstname' => '0',
    'display_lastname' => '0',
    'design_margin_bottom' => '15px',
    'inline' => true,
    'button_style' => 'custom',
    'input_style' => 'custom',
    'final_text' => 'final',
    
    'input_color_text' => '#555',
    'input_background_color' => '#fff',
    'input_border_color' => '#ccc',
    'input_focus_border_color' => '#66afe9',
    'input_border_width' => '1px',
    'input_border_radius' => '4px',
    'input_width' => '161px',
    'input_height' => '34px',
    'input_font_size' => '12px',
    
    'button_style' => 'custom',
    'button_title' => 'Subscribe',
    'button_background_color' => '#f67131',
    'button_hover_background_color' => '#f67131',
    'button_color_text' => '#fff',
    'button_hover_color_text' => '#fff',
    'button_border_width' => '2px',
    'button_border_color' => '#dc6329',
    'button_hover_border_color' => '#dc6329',
    'button_border_radius' => '5px',
    'button_width' => '230px',
    'button_height' => '50px',
    'button_font_size' => '18px'
);

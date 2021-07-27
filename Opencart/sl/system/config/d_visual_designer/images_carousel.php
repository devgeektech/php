<?php
//отображение блока в окне при выборе блока
$_['display']         = true;
//Порядковый номер
$_['sort_order']      = 12;
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
$_['types']           = array(
    'title' => 'string',
    'size' => 'string',
    'onclick' => 'string',
    'link' =>'string',
    'link_target' => 'string',
    'speed' => 'number',
    'slides_per_view' => 'number',
    'auto_play' => 'boolean',
    'hide_pagination_control' => 'boolean',
    'hide_next_prev_button' => 'boolean',
    'stopOnHover' => 'boolean',
    'lazyLoad' => 'boolean',
    'animate' => 'string',
    'width' => 'string',
    'height' => 'string'
);
//Настройки по умолчанию
$_['setting'] = array(
    'images' => array(),
    'title' => '',
    'size' => 'original',
    'onclick' => '',
    'link' => '',
    'link_target' => 'new',
    'speed' => '5000',
    'slides_per_view' => 1,
    'auto_play' => false,
    'hide_pagination_control' => false,
    'hide_next_prev_button' => false,
    'stopOnHover' => false,
    'lazyLoad' => false,
    'animate' => '',
    'width' => '0px',
    'height' => '0px'
);
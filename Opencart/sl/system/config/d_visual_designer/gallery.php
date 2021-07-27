<?php
//отображение блока в окне при выборе блока
$_['display']         = true;
//Порядковый номер
$_['sort_order']      = 11;
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
    'link' => 'string',
    'onclick' => 'string',
    'link' => 'string',
    'link_target' => 'string',
    'type_gallery' => 'string',
    'auto_rotate' => 'number',
    'width' => 'string',
    'height' => 'string'
);
//Настройки по умолчанию
$_['setting'] = array(
    'title' => '',
    'images' => array(),
    'size' => 'original',
    'link' => '',
    'onclick' => '',
    'link' => '',
    'link_target' => 'new',
    'type_gallery' => 'image_grid',
    'auto_rotate' => '2000',
    'width' => '200px',
    'height' => '100px'
);
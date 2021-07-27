<?php
//отображение блока в окне при выборе блока
$_['display']         = true;
//Порядковый номер
$_['sort_order']      = 19;
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
$_['save_html'] = false;
//Типы полей
$_['types']           = array(
    'title' => 'string',
    'mode' => 'string',
    'count_product' => 'number',
    'width' => 'number',
    'width_tablet' => 'number',
    'width_phone' => 'number'
);
//Настройки по умолчанию
$_['setting'] = array(
    'title' => '',
    'mode' => 'latest',
    'count_product' => 4,
    'width' => 4,
    'width_tablet' => 12,
    'width_phone' => 12,
    'products' => array(),
    'category' => array()
);
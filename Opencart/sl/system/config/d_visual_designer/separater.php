<?php
//отображение блока в окне при выборе блока
$_['display']         = true;
//Порядковый номер
$_['sort_order']      = 3;
//Категория(content, social, structure)
$_['category'] = 'content';
//отображать название блока
$_['display_title']   = false;
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
    'color' => 'string',
    'border_width' => 'string',
    'width' => 'string',
    'style' => 'string',
    'align' => 'string'
);
//Настройки по умолчанию
$_['setting'] = array(
    'color' => '#000',
    'border_width' => '1px',
    'width' => '100%',
    'style' => 'solid',
    'align' => 'left'
);
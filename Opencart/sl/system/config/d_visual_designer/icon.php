<?php
//отображение блока в окне при выборе блока
$_['display']         = true;
//Порядковый номер
$_['sort_order']      = 2;
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
	'library' => 'string',
    'icon' => 'string',
    'color' => 'string',
    'background_style' => 'string',
    'background_color' => 'string',
    'size' => 'string',
    'align' => 'string',
    'link' => 'string',
    'size_phone' => 'string',
    'align_phone' => 'string',
    'size_tablet' => 'string',
    'align_tablet' => 'string'
);
//Настройки по умолчанию
$_['setting'] = array(
	'library' => 'fontawesome',
    'icon' => 'fa fa-adjust',
    'color' => '#fff',
    'background_style' => 'rounded',
    'background_color' => '#5472d2',
    'size' => 'md',
    'align' => 'left',
    'link' => '',
    'size_phone' => '',
    'align_phone' => '',
    'size_tablet' => '',
    'align_tablet' => '',
);
<?php
//отображение блока в окне при выборе блока
$_['display']          = true;
//Порядковый номер
$_['sort_order']       = 17.2;
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
$_['button_remove']    = true;
//Доступен пре-рендер
$_['pre_render'] = false;
//Доступно сохранение в html
$_['save_html'] = false;
//Типы полей
$_['types']            = array(
    'datetime' => 'string',
    'display_title' => 'boolean',
    'text_arround_timer' => 'string',
    'color_title' => 'string',
    'background' => 'string',
    'color_number' => 'string',
    'style' => 'string',
    'design_margin_bottom' => 'string',
    'scale' => 'number',
    'position' => 'string',
    'display_day' => 'boolean',
    'display_hour' => 'boolean',
    'display_second' => 'boolean',
    'counter_mode' => 'string',
    'time' => 'string',
    'day_week' => 'string'
);
//Настройки по умолчанию
$_['setting'] = array(
    'datetime' => date('Y-m-d'),
    'display_title' => '1',
    'text_arround_timer' => '{{timer}}',
    'color_title' => '#323434',
    'background' => 'rgb(51, 51, 51)',
    'color_number' => 'rgb(204, 204, 204)',
    'style' => 'default',
    'design_margin_bottom' => '15px',
    'scale' => 1,
    'position' => 'center',
    'display_day' => 0,
    'display_hour' => 1,
    'display_second' => 1,
    'counter_mode' => 'specify_date',
    'time' => date('H:i', strtotime('1 hour')),
    'day_week' => ''
);
<?php
//отображение блока в окне при выборе блока
$_['display']         = true;
//Порядковый номер
$_['sort_order']      = 25;
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
$_['pre_render'] = false;
//Доступно сохранение в html
$_['save_html'] = false;
//Типы полей
$_['types']           = array(
    'title' => 'string',
    'stroke_width' => 'string',
    'display_legend' => 'boolean',
    'mode' => 'string',
    'animate' => 'string',
    'units' => 'string'
);
//Настройки по умолчанию
$_['setting'] = array(
    'title' => 'Browser market shares January, 2015 to May, 2015',
    'stroke_width' => '2',
    'display_legend' => '1',
    'values' => array(
       '0' => array(
            'label' => 'Microsoft Internet Explorer',
            'value' => '56.33',
            'color' => '#95CEFF'
        ),
        '1' => array(
            'label' => 'Chrome',
            'value' => '24.03',
            'color' => '#434348'
        ),
        '2' => array(
            'label' => 'Firefox',
            'value' => '10.38',
            'color' => '#90ED7D'
        ),
        '3' => array(
            'label' => 'Safari',
            'value' => '4.77',
            'color' => '#F7A35C'
        ),
        '4' => array(
            'label' => 'Opera',
            'value' => '0.91',
            'color' => '#8085E9'
        ),
        '5' => array(
            'label' => 'Proprietary or Undetectable',
            'value' => '0.2',
            'color' => '#F58FA7'
        )
    ),
    'mode' => 'pie',
    'animate' => 'easeoutbounce',
    'units' => ''
);
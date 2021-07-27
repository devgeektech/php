<?php
//отображение блока в окне при выборе блока
$_['display']         = true;
//Порядковый номер
$_['sort_order']      = 23;
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
    'units' => 'string',
    'animate' => 'boolean',
    'stripes' => 'boolean'
);
//Настройки по умолчанию
$_['setting'] = array(
    'title' => '',
    'values' => array(
        '0' => array(
            'label' => 'Development',
            'value' => '90',
            'color' => '#95CEFF'
        ),
        '1' => array(
            'label' => 'Design',
            'value' => '80',
            'color' => '#434348'
        ),
        '2' => array(
            'label' => 'Marketing',
            'value' => '70',
            'color' => '#90ED7D'
        ),
    ),
    'units' => '%',
    'animate' => '1',
    'stripes' => '1'
);
<?php
//отображение блока в окне при выборе блока
$_['display']          = true;
//Порядковый номер
$_['sort_order']       = 13;
//Категория(content, social, structure)
$_['category']         = 'structure';
//отображать название блока
$_['display_title']    = false;
//Может содержать дочерние блоки
$_['child_blocks']     = true;
//Обязательынй дочерний блок
$_['child']            = 'section';
//Уровень доступный для добавления блока
$_['level_min']        = 3;
$_['level_max']        = 3;
//Расположение кнопок управления
$_['control_position'] ='top-bordered';
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
//Свой шаблон
$_['custom_layout']    = 'tabs';
//Доступен пре-рендер
$_['pre_render'] = false;
//Доступно сохранение в html
$_['save_html'] = false;
//Типы полей
$_['types']           = array(
    'active_section' => 'string',
    'title' => 'string',
    'align' => 'string',
    'design_margin_top' => 'string'
);
//Настройки по умолчанию
$_['setting']          = array(
    'active_section' => '1',
    'title' => '',
    'align' => 'left',
    'design_margin_top' => '40px'
);
<?php
//отображение блока в окне при выборе блока
$_['display']          = true;
//Порядковый номер
$_['sort_order']       = 34;
//Категория(content, social, structure)
$_['category']         = 'conversion';
//отображать название блока
$_['display_title']    = false;
//Может содержать дочерние блоки
$_['child_blocks']     = true;
//Обязательынй дочерний блок
$_['child']            = 'pricing_table_section';
//Уровень доступный для добавления блока
$_['level_min']        = 3;
$_['level_max']        = 3;
//Расположение кнопок управления
$_['control_position'] ='top';
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
$_['custom_layout'] = 'pricing_table';
//Доступен пре-рендер
$_['pre_render'] = true;
//Доступно сохранение в html
$_['save_html'] = true;
//Типы полей
$_['types']            = array(
    'design_margin_top' => 'string',
    'width_between' => 'string',
    'color_text' => 'string'
);
//Настройки по умолчанию
$_['setting'] = array(
    'design_margin_top' => '40px',
    'width_between' => '10px',
    'color_text' => '#000000'
);
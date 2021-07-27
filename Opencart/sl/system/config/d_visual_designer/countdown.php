<?php
//отображение блока в окне при выборе блока
$_['display']          = true;
//Порядковый номер
$_['sort_order']       = 17.1;
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
    'text' => 'string',
    'final_text' => 'string'
);
//Настройки по умолчанию
$_['setting'] = array(
    'datetime' => date('Y-m-d H:i', strtotime('1 hour')),
    'text' => '<div style="text-align: center; line-height: 1.2;"><span style="font-size: 70px;"><font face="Arial">%d:%H:%M:%S</font></span></div>',
    'final_text' => '<div style="text-align: center;"><span style="font-size: 70px;"><font face="Arial">Finish﻿</font></span></div>'
);
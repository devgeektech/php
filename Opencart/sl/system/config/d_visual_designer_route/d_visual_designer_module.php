<?php
//Название конфига
$_['name']            = 'Visual Designer Module';
//Статус Frontend редатора
$_['frontend_status'] = '1';
//GET параметр route в админке 
$_['backend_route']   = 'extension/module/d_visual_designer_module';
//GET параметр route на Frontend 
$_['frontend_route']    = 'extension/d_visual_designer/designer/frontend';
//GET параметр содержащий id страницы в админке
$_['backend_param']   = 'module_id';
//GET параметр содержащий id страницы на Frontend
$_['frontend_param']  = 'id';
//Полный список параметров на Frontend editor
$_['frontend_full_param'] = 1;
//Путь для сохранения описания на Frontend
$_['edit_route']        = '';
//События необходимые для работы данного route
$_['events']          = array(
    'admin/model/extension/module/d_visual_designer_module/saveSuccess/after' => 'extension/event/d_visual_designer_module/save_success_after',
    'admin/view/extension/module/d_visual_designer_module/after' => 'extension/event/d_visual_designer_module/view_after',
    'catalog/view/extension/module/d_visual_designer_module/before' => 'extension/event/d_visual_designer_module/view_before',
    );
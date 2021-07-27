<?php
//Название конфига
$_['name']                = 'Visual Designer Landing';
//Статус Frontend редатора
$_['frontend_status']     = '1';
//GET параметр route в админке 
$_['backend_route']       = 'extension/module/d_visual_designer_landing/editVariation';
//REGEX для GET параметров route в админке
$_['backend_route_regex'] = 'extension/module/d_visual_designer_landing/*Variation';
//GET параметр route на Frontend
$_['frontend_route']      = 'extension/module/d_visual_designer_landing';
//GET параметр содержащий id страницы в админке
$_['backend_param']       = 'variation_id';
//GET параметр содержащий id страницы на Frontend
$_['frontend_param']      = 'variation_id';
//Путь для сохранения описания на Frontend
$_['edit_route']          = 'extension/module/d_visual_designer_landing/ajaxSave';
//События необходимые для работы данного route
$_['events']            = array(
    'admin/view/extension/d_visual_designer_landing/variation_form/after' => 'extension/event/d_visual_designer_landing/view_variation_after',
    'admin/model/extension/module/d_visual_designer_landing/addVariation/after' => 'extension/event/d_visual_designer_landing/model_addVariation_after',
    'admin/model/extension/module/d_visual_designer_landing/addVariation/before' => 'extension/event/d_visual_designer_landing/model_addVariation_before',
    'admin/model/extension/module/d_visual_designer_landing/editVariation/after' => 'extension/event/d_visual_designer_landing/model_editVariation_after',
    'admin/model/extension/module/d_visual_designer_landing/editVariation/before' => 'extension/event/d_visual_designer_landing/model_editVariation_before',
    'catalog/view/extension/module/d_visual_designer_landing/before' => 'extension/event/d_visual_designer_landing/view_variation_before',
);

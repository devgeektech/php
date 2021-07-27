<?php
$_['d_visual_designer_landing_setting'] = array(
    'list_limit' => '20',
    'sheet' => array(
        'landing_page' => array(
            'code' => 'landing_page',
            'name' => 'text_landing_page',
            'unique_url' => true,
            'exception_data' => 'variation_id, page_id, sort, order, page, limit, codename, secret, bfilter, ajaxfilter, edit, gclid, utm_source, utm_medium, utm_campaign',
            'short_url' => false,
        ),
    ),
    'custom_page_exception_routes' => array()
);

$_['d_visual_designer_landing_field_setting'] = array(
    'sheet' => array(
        'landing_page' => array(
            'code' => 'landing_page',
            'name' => 'text_landing_page',
            'icon' => 'fa-list',
            'sort_order' => '30',
            'field' => array(
                'title' => array(
                    'code' => 'title',
                    'name' => 'text_page_title',
                    'description' => '',
                    'type' => 'text',
                    'sort_order' => '1',
                    'multi_store' => true,
                    'multi_language' => true,
                    'multi_store_status' => false,
                    'required' => true
                ),
                'meta_title' => array(
                    'code' => 'meta_title',
                    'name' => 'text_meta_title',
                    'description' => '',
                    'type' => 'text',
                    'sort_order' => '4',
                    'multi_store' => true,
                    'multi_language' => true,
                    'multi_store_status' => false,
                    'required' => true
                ),
                'meta_description' => array(
                    'code' => 'meta_description',
                    'name' => 'text_meta_description',
                    'description' => '',
                    'type' => 'textarea',
                    'sort_order' => '5',
                    'multi_store' => true,
                    'multi_language' => true,
                    'multi_store_status' => false,
                    'required' => false
                ),
                'meta_keyword' => array(
                    'code' => 'meta_keyword',
                    'name' => 'text_meta_keyword',
                    'description' => '',
                    'type' => 'textarea',
                    'sort_order' => '6',
                    'multi_store' => true,
                    'multi_language' => true,
                    'multi_store_status' => false,
                    'required' => false
                ),
                'target_keyword' => array(
                    'code' => 'target_keyword',
                    'name' => 'text_target_keyword',
                    'description' => 'help_target_keyword',
                    'type' => 'textarea',
                    'sort_order' => '20',
                    'multi_store' => true,
                    'multi_language' => true,
                    'multi_store_status' => false,
                    'required' => false
                ),
                'url_keyword' => array(
                    'code' => 'url_keyword',
                    'name' => 'text_url_keyword',
                    'description' => 'help_url_keyword',
                    'type' => 'text',
                    'sort_order' => '30',
                    'multi_store' => true,
                    'multi_language' => true,
                    'multi_store_status' => false,
                    'required' => false
                ),
                'seo_rating' => array(
                    'code' => 'seo_rating',
                    'name' => 'text_seo_rating',
                    'description' => 'help_seo_rating',
                    'type' => 'info',
                    'sort_order' => '50',
                    'multi_store' => true,
                    'multi_language' => true,
                    'multi_store_status' => false,
                    'required' => false
                )
            )
        )
    )
);

$_['d_visual_designer_landing_url_generator_setting'] = array(
    'sheet' => array(
        'landing_page' => array(
            'code' => 'landing_page',
            'name' => 'text_landing_page',
            'sort_order' => '30',
            'icon' => 'fa-list',
            'field' => array(
                'url_keyword' => array(
                    'code' => 'url_keyword',
                    'name' => 'text_url_keyword',
                    'sort_order' => '1',
                    'template_default' => '[title]',
                    'template_button' => array(
                        '[title]' => '[title]',
                        '[target_keyword]' => '[target_keyword]'
                    ),
                    'multi_language' => true,
                    'translit_language_symbol_status' => false,
                    'transform_language_symbol_id' => '1',
                    'overwrite' => false,
                    'button_generate' => true,
                    'button_clear' => true
                )
            ),
            'button_popup' => array(
                '[target_keyword]' => array(
                    'code' => '[target_keyword]',
                    'name' => 'text_insert_target_keyword',
                    'field' => array(
                        'number' => array(
                            'code' => 'number',
                            'name' => 'text_keyword_number',
                            'type' => 'text',
                            'value' => '1'
                        )
                    )
                )
            )
        )
    )
);

$_['d_visual_designer_landing_meta_generator_setting'] = array(
    'sheet' => array(
        'landing_page' => array(
            'code' => 'landing_page',
            'name' => 'text_landing_page',
            'icon' => 'fa-list',
            'sort_order' => '30',
            'field' => array(
                'meta_title' => array(
                    'code' => 'meta_title',
                    'name' => 'text_meta_title',
                    'sort_order' => '1',
                    'template_default' => '[title]',
                    'template_button' => array(
                        '[title]' => '[title]',
                        '[target_keyword]' => '[target_keyword]',
                        '[store_name]' => '[store_name]',
                        '[store_title]' => '[store_title]'
                    ),
                    'multi_language' => true,
                    'translit_symbol_status' => false,
                    'translit_language_symbol_status' => false,
                    'transform_language_symbol_id' => '0',
                    'overwrite' => false,
                    'button_generate' => true,
                    'button_clear' => true
                ),
                'meta_description' => array(
                    'code' => 'meta_description',
                    'name' => 'text_meta_description',
                    'sort_order' => '2',
                    'template_default' => '[title] - [target_keyword#number=1]',
                    'template_button' => array(
                        '[title]' => '[title]',
                        '[target_keyword]' => '[target_keyword]',
                        '[store_name]' => '[store_name]',
                        '[store_title]' => '[store_title]'
                    ),
                    'multi_language' => true,
                    'translit_symbol_status' => false,
                    'translit_language_symbol_status' => false,
                    'transform_language_symbol_id' => '0',
                    'overwrite' => false,
                    'button_generate' => true,
                    'button_clear' => true
                ),
                'meta_keyword' => array(
                    'code' => 'meta_keyword',
                    'name' => 'text_meta_keyword',
                    'sort_order' => '3',
                    'template_default' => '[title], [target_keyword#number=1]',
                    'template_button' => array(
                        '[title]' => '[title]',
                        '[target_keyword]' => '[target_keyword]',
                        '[store_name]' => '[store_name]',
                        '[store_title]' => '[store_title]'
                    ),
                    'multi_language' => true,
                    'translit_symbol_status' => false,
                    'translit_language_symbol_status' => false,
                    'transform_language_symbol_id' => '0',
                    'overwrite' => false,
                    'button_generate' => true,
                    'button_clear' => true
                )
            ),
            'button_popup' => array(
                '[target_keyword]' => array(
                    'code' => '[target_keyword]',
                    'name' => 'text_insert_target_keyword',
                    'field' => array(
                        'number' => array(
                            'code' => 'number',
                            'name' => 'text_keyword_number',
                            'type' => 'text',
                            'value' => '1'
                        )
                    )
                )
            )
        ),
    )
);

$_['d_visual_designer_landing_manager_setting'] = array(
	'sheet' => array(
		'landing_page' => array(
			'code' => 'landing_page',
			'name' => 'text_landing_page',
            'sort_order' => '11',
            'icon' => 'fa-list',
			'field_index' => 'page_id',
			'field' => array(
				'page_id' => array(
					'code' => 'page_id',
					'name' => 'text_page_id',
					'type' => 'link',
					'sort_order' => '1',
					'multi_store' => false,
					'multi_language' => false,
					'list_status' => true,
					'export_status' => true,
					'required' => true
				),
				'title' => array(
					'code' => 'title',
					'name' => 'text_page_title',
					'type' => 'text',
					'sort_order' => '2',
					'multi_store' => true,
					'multi_language' => true,
					'list_status' => true,
					'export_status' => true,
					'required' => false
				),
				'meta_title' => array(
					'code' => 'meta_title',
					'name' => 'text_meta_title',
					'type' => 'text',
					'sort_order' => '5',
					'multi_store' => true,
					'multi_language' => true,
					'list_status' => true,
					'export_status' => true,
					'required' => false
				),
				'meta_description' => array(
					'code' => 'meta_description',
					'name' => 'text_meta_description',
					'type' => 'textarea',
					'sort_order' => '6',
					'multi_store' => true,
					'multi_language' => true,
					'list_status' => true,
					'export_status' => true,
					'required' => false
				),
				'meta_keyword' => array(
					'code' => 'meta_keyword',
					'name' => 'text_meta_keyword',
					'type' => 'textarea',
					'sort_order' => '7',
					'multi_store' => true,
					'multi_language' => true,
					'list_status' => true,
					'export_status' => true,
					'required' => false
				),
				'target_keyword' => array(
					'code' => 'target_keyword',
					'name' => 'text_target_keyword',
					'type' => 'textarea',
					'sort_order' => '20',
					'multi_store' => true,
					'multi_language' => true,
					'list_status' => true,
					'export_status' => true,
					'required' => false
				),
				'url_keyword' => array(
					'code' => 'url_keyword',
					'name' => 'text_url_keyword',
					'type' => 'text',
					'sort_order' => '31',
					'multi_store' => true,
					'multi_language' => true,
					'list_status' => true,
					'export_status' => true,
					'required' => false
				)
			)
		),
	)
);
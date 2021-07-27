<?php
// Heading
$_['heading_title']    = 'Warehouse Locations';
$_['heading_title_transaction']    = 'Warehouse transactions';
$_['heading_title_producteditview']    = 'Warehouse Product Edit View';
$_['heading_title_settings']    = 'Settings';
$_['heading_title_import']    = 'Import Export Warehouse Stock Using Csv';

// Text
$_['text_success']     = 'Success: You have modified warehouse !';
$_['text_list']        = 'List';
$_['text_add']         = 'Add New';
$_['text_edit']        = 'Edit';
$_['text_default']     = 'Default';
$_['text_location']    = 'Assign warehouse to product';

// Column
$_['column_warehouse']      = 'Warehouse';
$_['column_name']      		= 'Name';
$_['column_qty']      		= 'Qty';
$_['column_date_added']     = 'Date Added';
$_['column_order_id']      = 'Order Id';
$_['column_zone']      = 'Zone Name';
$_['column_contactperson_details']      = 'Contact Person Details';
$_['column_sort_order']    = 'Sort Order';
$_['column_action']    = 'Action';

// Entry
$_['entry_name']       = 'Warehouse Name';
$_['entry_zone']       = 'Zone';
$_['entry_country']    = 'Country';
$_['entry_image']      = 'Warehouse Image';
$_['entry_comment']    = 'Description';
$_['help_comment']     = 'About Warehouse';
$_['entry_sort_order']   = 'Sort Order';
$_['help_sort_order']    = 'Sort order is used if you have enabled automatic stock reduction for orders';
$_['entry_selectstates']  = "Stock Deduction From States";
$_['help_selectstates'] = "Enter states for which you want to deduct stock from this warehouse";
$_['entry_geolocation']	= "Geo Location";
$_['help_geolocation']	= "You can add iframe html code inside. It will be shown on the warehouse list page for admin reference";
$_['entry_contactperson_name']	= "Warehouse Keeper Name";
$_['entry_contactperson_image']	= "Warehouse Keeper Image";
$_['entry_contactperson_mobile']	= "Warehouse keeper Mobile";
$_['entry_contactperson_phone']	= "Warehouse Phone";


//Product page form admin side
$_['entry_warehouse'] 	  = "Assign individual warehouse quantity: <br><br>";
$_['entry_warehouse_qty'] = "Enter qty";
$_['text_reset']  = 'Reset';
$_['text_sumup']  = 'Sum & Add To Main Qty'; 
$_['text_success_rest']	 = "Warehouse qty has been reset to zero";
$_['text_success_sumqty']= "Warehouse qty + Main qty has been saved";
$_['text_success_qty']	 = "Warehouse qty has been saved";

$_['error_no_warehouse'] = "No warehouse found. <a href='%s'>Add here</a>";

//Settings page
$_['text_automaticstockreduce']	 = "Automatic Stock Reduction";
$_['text_orderstatus']			 = "Reduction for selected order status";
$_['text_preference']			 = "Sort Order For Automatic Stock Reduction";
$_['text_negativestock']			 = "Allow negative stock";
$_['text_showininvoice']			 = "Show warehouse deduction in Admin Invoice";
$_['text_stopcheckout']			 = "Restrict checkout if stock not available";
$_['text_state']	 		 	 = "Based On Warehouse State Field ";
$_['text_reduceafterorderplaced'] = "Reduce stock on order placement";
$_['text_sortorder']	 		 = "Based On Warehouse Sort Order";
$_['text_automaticreduce']       = 'If you want to use the scheduling features, your server has to support Cron functions.<br>
The cron daemon is a long running process that executes commands at specific dates and times<br>
In Cron jon command on your cPanel you must set enter this command below:<br>
<b>wget -O - %sindex.php?route=extension/module/warehouse/stockupdate >/dev/null 2>&1</b>';


$_['tab_general']				 = "General";
$_['tab_automaticreduce']				 = "Automatic Stock Reduction";
$_['tab_cronjob']		 = "Cron Job";

//Order page

$_['button_warehouseorder']		 = "Warehouse Stock";
$_['text_selectwarehouse_warehouseorder']       = 'Warehouse stock reduction for order';
$_['text_subtractisno']				 = "Subtact is NO for this item";
$_['column_image']					 = 'Image';
$_['column_product']				 = "Product Name";
$_['column_quantity']       		 = 'Qty Ordered';
$_['column_quantityavailable']       = 'Qty Available';
$_['column_selectwarehouse']       	 = 'Qty Debited';

//menu
$_['text_menu_warehouses']	 = "Warehouses";
$_['text_menu_transactions']	 = "Transactions";
$_['text_menu_productassignment']	 = "Product Warehouse Assignment";
$_['text_menu_settings']	 = "Settings";
$_['text_menu_import']	 = "Import via CSV";


//Warehouse Import CSV
$_['heading_title_warehouseimport'] = "Warehouse CSV Import/Export";
$_['text_form']						= "Export / Import form";
$_['text_helpguide_import'] 	    = '1) It is easy to import warehouse\'s.<br>
								2) Click Export Reference Sheet to download the CSV file format.<br>
								3) Add your details in that csv template.<br>
								4) Now click "Import through sheet" button.<br>';
$_['entry_sum_it']				= "Sum Existing Warehouse Stock";
$_['help_sum_it']			    = "If ticked, this will add to already existing warehouse stock";
$_['entry_main_qty']				= "Sum all existing Warehouse and Add To Main Product Quantity";
$_['help_main_qty']			    = "If ticked, the warehouse quantity will be added to main product quantity including other warehouse.";
$_['entry_selectfile']			= "Select File";
$_['entry_selectwarehouse']				= "Select Warehouse";
$_['samefiletype']				 = "It should same filetype as exported csv file";
$_['help_exportwarehouse']		 = "This file should be exported for downloading current warehouse stock, you can use same file to edit and then import";
$_['exportwarehouse']	 		 = "Export Warehouse Stock";
$_['help_importwarehouse']		 = "This shall help you import the warehouse csv file as downloaded from above button";
$_['importwarehouses']			 = "Import Warehouse Stock";
$_['importc']					 = "Import";
$_['exportc']					 = "Export Warehouse Stock";
$_['text_exportreference']		 = "Export Reference";
	
//Warehouse transaction
$_['column_product']				 = "Product Name";
$_['column_quantity']       		 = 'Qty';

$_['entry_product_name']	= "Product / option name";
$_['entry_order_id']	= "Order id";
$_['entry_quantity']	= "Qty";
$_['entry_date_added']	= "Date Added";
$_['entry_status']	= "Status";


//Product Edit View Page
$_['entry_option']		= "Option Name";
$_['entry_model']		= "Model";
$_['entry_sku']			= "Sku";
$_['entry_subtract']			= "Subtract";
$_['entry_options']			= "Show product options";
$_['entry_category']	= "Select Category";
$_['entry_manufacturer']	= "Select Manufacturer";

$_['column_option']			= "Option Name";

$_['text_howitworks'] = "About this page";
$_['text_howitworks_listcontent'] = "This page will help you manage your different warehouses.
2) You can add unlimited number of warehouses from here.
3) Click on plus button on top right to add a new warehouse.
4) Click edit button to edit the warehouse.
5) You can select state and sort order for each warehouse.
6) This will help you if you want to auto reduce stock after purchase.";

$_['text_howitworks_transaction'] = "This page will help you check your different warehouse transactions for stock.
2) From this page, you can see which order or product was reduced from which warehouse.
3) The transaction edit page is available per product as well on order info page.
4) Use filters to find the required transaction.";

$_['text_howitworks_producteditview'] = "This page is a single page editor tool to save product / option warehouse stock.
2) You can find any product and its respective option.
3) You can add the warehouse qty for them and click on save button.
4) There is also sum plus save button which adds up warehouse qty to save it main qty.
5) You can filter this page using different filters including category / manufacturer.";

$_['text_howitworks_setting'] = "This page controls the settings used for warehouse stock management.
2) The first tab is for Automatic stock reduction.
3) You can enable the settings as per your need.
4) The sort order helps you set the preference of the warehouse.
5) If you want to reduce stock based on warehouse state, keep sort order for state as lower.
6) Stocks would be reduced from other warehouse if negative stocks are disabled.
7) For automatic stock reduction it is reommended that you set up cron job on server for every half hour.";

// Error
$_['error_permission'] = 'Warning: You do not have permission to modify warehouse !';
$_['error_name']       = 'Warehouse name must be at least 1 character!';

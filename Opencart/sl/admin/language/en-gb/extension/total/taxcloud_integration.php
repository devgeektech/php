<?php
//==============================================================================
// TaxCloud Integration v303.5
// 
// Author: Clear Thinking, LLC
// E-mail: johnathan@getclearthinking.com
// Website: http://www.getclearthinking.com
// 
// All code within this file is copyright Clear Thinking, LLC.
// You may not copy or reuse code within this file without written permission.
//==============================================================================

$version = 'v303.5';

//------------------------------------------------------------------------------
// Heading
//------------------------------------------------------------------------------
$_['heading_title']						= 'TaxCloud Integration';

//------------------------------------------------------------------------------
// Extension Settings
//------------------------------------------------------------------------------
$_['tab_extension_settings']			= 'Extension Settings';
$_['heading_extension_settings']		= 'Extension Settings';

$_['entry_status']						= 'Status: <div class="help-text">Set the status for the extension as a whole.</div>';
$_['entry_sort_order']					= 'Sort Order: <div class="help-text">The sort order for the extension, relative to other Order Totals. Any Order Totals that appear before the TaxCloud Integration will be taxed as well.</div>';
$_['entry_title']						= 'Title: <div class="help-text">The title for the Order Total line item. HTML is supported. Use the shortcodes [zipcode] and [state] in place of the customer\'s zip code and state if you want to add one of those to the title.</div>';
$_['entry_google_apikey']				= 'Google Maps API Key: <div class="help-text">Optionally enter your <a target="_blank" href="https://cloud.google.com/maps-platform/">Google Maps API Key</a> here. This is used to get the City name when the OpenCart shipping estimator is used. If you do not want to use this feature just leave the field blank, the extension will still function fine once the customer enters their shipping address in the checkout. When signing up for the API Key, make sure you choose to enable the "Geocoding" API, which is the API used by the extension.</div>';

$_['help_info']							= 'All Order Total line items that come before this extension\'s line item will be taxed. Set any non-taxable line items to have a higher sort order than this extension.<br><br>Make sure the "Taxes" Order Total is disabled to avoid double-taxing. If you want prices to display including estimated tax, you can leave your Tax Classes set up as normal. However, prices including tax will be displayed based on the OpenCart tax rates, not the TaxCloud rates.<br><br>The reason it functions this way is because taxes on products in OpenCart are calculated every time the page is loaded, so basing the tax on TaxCloud rates would result in a huge number of API calls to TaxCloud. This could cause significant performance issues. Additionally, because customers need to enter their full address to retrieve the correct tax rate for their address, prices including tax would be based on the store location until they enter their address, so it would still be inaccurate until checkout.';

//------------------------------------------------------------------------------
// TaxCloud Settings
//------------------------------------------------------------------------------
$_['tab_taxcloud_settings']				= 'TaxCloud Settings';
$_['heading_taxcloud_settings']			= 'TaxCloud Settings';

$_['entry_api_id']						= 'TaxCloud API ID: <div class="help-text">Enter your API ID, which can be found in your TaxCloud admin panel under Websites > API ID</div>';
$_['entry_api_key']						= 'TaxCloud API Key: <div class="help-text">Enter your API Key, which can be found in your TaxCloud admin panel under Websites > API Key</div>';
$_['entry_usps_id']						= 'USPS ID: <div class="help-text">Optionally enter a <a target="_blank" href="https://registration.shippingapis.com">USPS Web Tools ID</a>, which will be used for address validation when looking up tax rates. This will improve the accuracy of the tax rate lookups.</div>';

$_['entry_store_address']				= 'Store Address: <div class="help-text">Enter your store\'s physical address, used for determining tax rates. If a USPS ID is filled, this information will be validated after reloading the page.</div>';
$_['placeholder_address_line_1']		= 'Address Line 1';
$_['placeholder_address_line_2']		= 'Address Line 2 (optional)';
$_['placeholder_city']					= 'City';
$_['placeholder_state']					= 'State';
$_['placeholder_zip_code']				= 'Zip Code';
$_['placeholder_zip4']					= 'Zip4';

$_['entry_tic_field']					= 'Product TIC Field: <div class="help-text">Select the product field that stores the TIC (Taxability Information Code). Any product that does not have a TIC filled in will use your TaxCloud Default TIC. This can be set in your TaxCloud admin panel under Websites > Default TIC.<br /><br />To look up TICs for your products, visit your TaxCloud admin panel and click on Taxability Codes > Explore TICs.<br /><br />If multiple TICs with different tax rates are in the cart, coupons and other Order Total extensions will use the highest tax rate (if they have a tax class applied).</div>';
$_['text_always_use_default_tic']		= '--- Always Use Default TIC ---';

$_['entry_precheckout_pages']			= 'Pre-Checkout Pages: <div class="help-text">Choose whether pre-checkout pages use a TaxCloud API request to get the current tax amount, or use the fallback tax rate to preview the taxes for the customer, or just hide the tax altogether. You can reduce the number of API calls to TaxCloud by choosing not to get the actual tax amount on pre-checkout pages. The checkout page itself will always get the tax from TaxCloud, no matter what this is set to.</div>';
$_['text_get_tax_amount']				= 'Get tax amount';
$_['text_use_fallback_rate']			= 'Use fallback rate';
$_['text_hide']							= 'Hide';

$_['entry_view_sent_orders']			= 'View Sent Orders: <div class="help-text">Click to view a report of all orders that have been sent to TaxJar.</div>';
$_['button_view_sent_orders']			= 'View Sent Orders';
$_['column_order_id']					= 'Order ID';
$_['column_customer']					= 'Customer';
$_['column_status']						= 'Status';
$_['column_total']						= 'Total';
$_['column_date_added']					= 'Date Added';
$_['column_date_sent']					= 'Date Sent';
$_['text_no_orders_have_been_sent']		= 'No orders have been sent';

$_['entry_batch_order_send']			= 'Batch Order Send: <div class="help-text">Fill in the "Starting Order ID" and "Ending Order ID" fields to send a partial list of your orders. The starting and ending ID values are inclusive. Leave blank to send all orders.</div>';
$_['text_starting_order_id']			= 'Starting Order ID:';
$_['text_ending_order_id']				= '&nbsp; Ending Order ID:';
$_['button_send']						= 'Send';

// Fallback Tax Rates
$_['heading_fallback_tax_rates']		= 'Fallback Tax Rates';
$_['help_fallback_tax_rates']			= 'Set a fallback tax rate for each geo zone, which will be used if the customer\'s address is not filled in yet, or if the API call to TaxJar fails.';

//------------------------------------------------------------------------------
// Order Criteria
//------------------------------------------------------------------------------
$_['tab_order_criteria']				= 'Order Criteria';
$_['heading_order_criteria']			= 'Order Criteria';

$_['entry_stores']						= 'Store(s): <div class="help-text">Select the stores which are eligible for taxes.</div>';

$_['entry_geo_zones']					= 'Geo Zone(s): <div class="help-text">Select the geo zones which are eligible for taxes. "Everywhere Else" applies to all locations not within any geo zone.</div>';
$_['text_everywhere_else']				= '<em>Everywhere Else</em>';
$_['text_guests']						= '<em>Guests</em>';

$_['entry_customer_groups']				= 'Customer Group(s): <div class="help-text">Select the customer groups which are eligible for taxes. "Guests" applies to all customers not logged in.</div>';

//------------------------------------------------------------------------------
// Testing Mode
//------------------------------------------------------------------------------
$_['tab_testing_mode']					= 'Testing Mode';
$_['testing_mode_help']					= 'Enable testing mode if things are not working as expected on the front end. Messages logged during testing can be viewed below.';
$_['heading_testing_mode']				= 'Testing Mode';

$_['entry_testing_mode']				= 'Testing Mode: <div class="help-text">Enabling will record all API requests to and responses to this log.</div>';
$_['entry_testing_messages']			= 'Messages:';
$_['button_refresh_log']				= 'Refresh Log';
$_['button_download_log']				= 'Download Log';
$_['button_clear_log']					= 'Clear Log';

//------------------------------------------------------------------------------
// Standard Text
//------------------------------------------------------------------------------
$_['copyright']							= '<hr /><div class="text-center" style="margin: 15px">' . $_['heading_title'] . ' (' . $version . ') &copy; <a target="_blank" href="http://www.getclearthinking.com/contact">Clear Thinking, LLC</a></div>';

$_['standard_autosaving_enabled']		= 'Auto-Saving Enabled';
$_['standard_confirm']					= 'This operation cannot be undone. Continue?';
$_['standard_error']					= '<strong>Error:</strong> You do not have permission to modify ' . $_['heading_title'] . '!';
$_['standard_max_input_vars']			= '<strong>Warning:</strong> The number of settings is close to your <code>max_input_vars</code> server value. You should enable auto-saving to avoid losing any data.';
$_['standard_please_wait']				= 'Please wait...';
$_['standard_saved']					= 'Saved!';
$_['standard_saving']					= 'Saving...';
$_['standard_select']					= '--- Select ---';
$_['standard_success']					= 'Success!';
$_['standard_testing_mode']				= "Your log is too large to open! If you need to archive it, you can download it using the button above.\n\nTo start a new log, (1) click the Clear Log button, (2) reload the admin panel page, then (3) run your test again.";

$_['standard_module']					= 'Modules';
$_['standard_shipping']					= 'Shipping';
$_['standard_payment']					= 'Payments';
$_['standard_total']					= 'Order Totals';
$_['standard_feed']						= 'Feeds';
?>
<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_form; ?></h3>
      </div>
      <div class="panel-body">
          <ul class="nav nav-tabs">
            <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
            <li><a href="#tab-products" data-toggle="tab"><?php echo $tab_products; ?></a></li>
            <li><a href="#tab-categories" data-toggle="tab"><?php echo $tab_categories; ?></a></li>
            <li><a href="#tab-support" data-toggle="tab"><i class="fa fa-support"></i> <?php echo $tab_support; ?></a></li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="tab-general">
              <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-customergroups" class="form-horizontal">
                <div class="row">
                  <div class="col-sm-6 col-xs-12">
                    <div class="form-group">
                      <label class="col-sm-12 control-label"><?php echo $entry_customer_group; ?></label>
                      <div class="col-sm-12">
                        <div class="customer-groups">
                          <?php foreach ($customer_groups as $customer_group) { ?>
                          <div class="checkbox-inline">
                            <label>
                              <input type="checkbox" name="customer_group_ids[]" value="<?php echo $customer_group['customer_group_id']; ?>" <?php if (in_array($customer_group['customer_group_id'], $customer_group_ids)) { ?>checked="checked"<?php } ?> />
                              <?php echo $customer_group['name']; ?>
                            </label>
                          </div>
                          <?php } ?>
                        </div>
                        <?php if ($error_customer_group) { ?>
                        <div class="text-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_customer_group; ?></div>
                        <?php } ?>
                      </div>
                    </div>
                  </div>
                  <div class="col-sm-6 col-xs-12">
                    <div class="form-group buttons pull-right">
                      <label class="col-sm-12 control-label">&nbsp;</label>
                      <button type="button" data-text-loading="<?php echo $text_loading; ?>" class="btn btn-primary assign"><i class="fa fa-check" data-class="fa fa-check"></i> <?php echo $button_save; ?></button>
                      <button type="button" data-text-loading="<?php echo $text_loading; ?>" class="btn btn-success save_data"><i class="fa fa-save" data-class="fa fa-save"></i> <?php echo $button_save_data; ?></button>
                    </div>
                  </div>
                </div>

                <div class="row products-categories">
                  <div class="col-sm-6 col-xs-12">
                    <div class="form-group">
                      <label class="col-sm-12 control-label"><?php echo $entry_category; ?> <br/><span class="help"><?php echo $help_category; ?></span></label>
                      <div class="col-sm-12">
                        <input type="text" name="category" value="" placeholder="<?php echo $entry_category; ?>" id="input-category" class="form-control" />
                        <div id="category" class="well well-sm" style="height: 150px; overflow: auto;">
                          <?php foreach ($categories as $category) { ?>
                          <div id="category<?php echo $category['category_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $category['name']; ?>
                            <input type="hidden" name="categories[]" value="<?php echo $category['category_id']; ?>" />
                          </div>
                          <?php } ?>
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-12 control-label" for="input-all_categories"><?php echo $entry_all_categories; ?><br/><span class="help"><?php echo $help_all_categories; ?></span></label>
                      <div class="col-sm-12">
                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                          <label class="btn btn-default <?php if ($all_categories) { ?>active<?php } ?>"><input type="radio" name="all_categories" value="1" <?php if ($all_categories) { ?>checked="checked"<?php } ?> /> <?php echo $text_yes; ?></label>
                          <label class="btn btn-default <?php if (!$all_categories) { ?>active<?php } ?>"><input type="radio" name="all_categories" value="0" <?php if (!$all_categories) { ?>checked="checked"<?php } ?> /> <?php echo $text_no; ?></label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-sm-6 col-xs-12">
                    <div class="form-group">
                      <label class="col-sm-12 control-label"  for="input-product"><?php echo $entry_product; ?><br/><span class="help"><?php echo $help_product; ?></span></label>
                      <div class="col-sm-12">
                        <input type="text" name="product" value="" placeholder="<?php echo $entry_product; ?>" id="input-product" class="form-control" />
                        <div id="product" class="well well-sm" style="height: 150px; overflow: auto;">
                          <?php foreach ($products as $product) { ?>
                          <div id="product<?php echo $product['product_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $product['name']; ?>
                            <input type="hidden" name="products[]" value="<?php echo $product['product_id']; ?>" />
                          </div>
                          <?php } ?>
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-12 control-label" for="input-all_products"><?php echo $entry_all_products; ?><br/><span class="help"><?php echo $help_all_products; ?></span></label>
                      <div class="col-sm-12">
                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                          <label class="btn btn-default <?php if ($all_products) { ?>active<?php } ?>"><input type="radio" name="all_products" value="1" <?php if ($all_products) { ?>checked="checked"<?php } ?> /> <?php echo $text_yes; ?></label>
                          <label class="btn btn-default <?php if (!$all_products) { ?>active<?php } ?>"><input type="radio" name="all_products" value="0" <?php if (!$all_products) { ?>checked="checked"<?php } ?> /> <?php echo $text_no; ?></label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <?php if ($error_none) { ?>
                  <div class="text-danger col-sm-12"><i class="fa fa-exclamation-circle"></i> <?php echo $error_none; ?></div>
                  <?php } ?>
                </div>
              </form>
            </div>
            <div class="tab-pane" id="tab-products">
              <fieldset id="product_quick_update">
                <legend><?php echo $legend_product_quick_update; ?></legend>
                <div class="row">
                  <div class="col-sm-5 col-xs-12">
                    <div class="form-group" id="products_customergroups">
                      <label class="col-sm-12 control-label" ><?php echo $entry_customer_group; ?></label>
                      <div class="col-sm-12">
                        <div class="products_customergroups">
                          <?php foreach ($customer_groups as $customer_group) { ?>
                          <div class="checkbox-inline">
                            <label>
                              <input type="checkbox" name="products_customer_group_ids[]" value="<?php echo $customer_group['customer_group_id']; ?>" checked="checked" />
                              <?php echo $customer_group['name']; ?>
                            </label>
                          </div>
                          <?php } ?>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-sm-7 col-xs-12">
                    <div class="form-group pull-right" >
                      <button type="button" class="btn btn-primary products_update"><i class="fa fa-gear" data-class="fa fa-gear"></i> <?php echo $button_quick_update; ?></button>
                      <button type="button" class="btn btn-info btn-import_products"><i class="fa fa-arrow-down" data-class="fa fa-arrow-down"></i> <?php echo $button_import; ?></button>
                      <button type="button" class="btn btn-warning btn-export_products"><i class="fa fa-arrow-up" data-class="fa fa-arrow-up"></i> <?php echo $button_export; ?></button>
                      <a href="<?php echo $samplefile_products; ?>" target="_blank" class="btn btn-success btn-sample_products"><i class="fa fa-desktop"></i> <?php echo $button_download; ?></a>
                    </div>
                  </div>
                </div>
              </fieldset>
              <div class="well">
                <div class="row">
                  <div class="col-sm-4">
                    <div class="form-group">
                      <label class="control-label" for="input-product_name"><?php echo $entry_product_name; ?></label>
                      <input type="text" name="filter_product_name" value="<?php echo $filter_product_name; ?>" placeholder="<?php echo $entry_product_name; ?>" id="input-product_name" class="form-control" />
                    </div>
                  </div>
                  <div class="col-sm-4">
                    <div class="form-group">
                      <label class="control-label" for="input-product_model"><?php echo $entry_product_model; ?></label>
                      <input type="text" name="filter_product_model" value="<?php echo $filter_product_model; ?>" placeholder="<?php echo $entry_product_model; ?>" id="input-product_model" class="form-control" />
                    </div>
                  </div>
                  <div class="col-sm-4">
                    <div class="form-group">
                      <label class="control-label" for="input-product_status"><?php echo $entry_status; ?></label>
                      <select name="filter_product_status" id="input-product_status" class="form-control">
                        <option value="*"></option>
                        <?php if ($filter_product_status) { ?>
                        <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                        <?php } else { ?>
                        <option value="1"><?php echo $text_enabled; ?></option>
                        <?php } ?>
                        <?php if (!$filter_product_status && !is_null($filter_product_status)) { ?>
                        <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                        <?php } else { ?>
                        <option value="0"><?php echo $text_disabled; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                  <div class="col-sm-12">
                    <div class="text-right">
                      <button type="button" id="button-product_filter" class="btn btn-primary"><i class="fa fa-filter" data-class="fa fa-filter"></i> <?php echo $button_filter; ?></button>
                      <button type="button" id="button-product_clear_filter" class="btn btn-danger"><i class="fa fa-refresh" data-class="fa fa-refresh"></i> <?php echo $button_clear; ?></button>
                    </div>
                  </div>
                </div>
              </div>
              <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-customergroups-product" class="form-horizontal">
                <div id="products-list"></div>
              </form>
            </div>
            <div class="tab-pane" id="tab-categories">
              <fieldset id="category_quick_update">
                <legend><?php echo $legend_category_quick_update; ?></legend>
                <div class="row">
                  <div class="col-sm-5 col-xs-12">
                    <div class="form-group" id="categories_customergroups">
                      <label class="col-sm-12 control-label" ><?php echo $entry_customer_group; ?></label>
                      <div class="col-sm-12">
                        <div class="categories_customergroups">
                          <?php foreach ($customer_groups as $customer_group) { ?>
                          <div class="checkbox-inline">
                            <label>
                              <input type="checkbox" name="categories_customer_group_ids[]" value="<?php echo $customer_group['customer_group_id']; ?>" checked="checked" />
                              <?php echo $customer_group['name']; ?>
                            </label>
                          </div>
                          <?php } ?>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-sm-7 col-xs-12">
                    <div class="form-group pull-right" >
                      <button type="button" class="btn btn-primary categories_update"><i class="fa fa-gear" data-class="fa fa-gear"></i> <?php echo $button_quick_update; ?></button>
                      <button type="button" class="btn btn-info btn-import_categories"><i class="fa fa-arrow-down" data-class="fa fa-arrow-down"></i> <?php echo $button_import; ?></button>
                      <button type="button" class="btn btn-warning btn-export_categories"><i class="fa fa-arrow-up" data-class="fa fa-arrow-up"></i> <?php echo $button_export; ?></button>
                      <a href="<?php echo $samplefile_categories; ?>" target="_blank" class="btn btn-success btn-sample_categories"><i class="fa fa-desktop"></i> <?php echo $button_download; ?></a>
                    </div>
                  </div>
                </div>
              </fieldset>
              <div class="well">
                <div class="row">
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label class="control-label" for="input-category_name"><?php echo $entry_category_name; ?></label>
                      <input type="text" name="filter_category_name" value="<?php echo $filter_category_name; ?>" placeholder="<?php echo $entry_category_name; ?>" id="input-category_name" class="form-control" />
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label class="control-label" for="input-category_status"><?php echo $entry_status; ?></label>
                      <select name="filter_category_status" id="input-category_status" class="form-control">
                        <option value="*"></option>
                        <?php if ($filter_category_status) { ?>
                        <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                        <?php } else { ?>
                        <option value="1"><?php echo $text_enabled; ?></option>
                        <?php } ?>
                        <?php if (!$filter_category_status && !is_null($filter_category_status)) { ?>
                        <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                        <?php } else { ?>
                        <option value="0"><?php echo $text_disabled; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                  <div class="col-sm-12">
                    <div class="text-right">
                      <button type="button" id="button-category_filter" class="btn btn-primary"><i class="fa fa-filter" data-class="fa fa-filter"></i> <?php echo $button_filter; ?></button>
                      <button type="button" id="button-category_clear_filter" class="btn btn-danger"><i class="fa fa-refresh" data-class="fa fa-refresh"></i> <?php echo $button_clear; ?></button>
                    </div>
                  </div>
                </div>
              </div>
              <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-customergroups-categories" class="form-horizontal">
                <div id="categories-list"></div>
              </form>
            </div>
            <div class="tab-pane" id="tab-support">
              <div class="card-deck mb-3 text-center">
                <div class="card mb-4 shadow-sm">
                  <div class="card-header">
                    <h4 class="my-0 font-weight-normal">Support</h4>
                  </div>
                  <div class="card-body">
                    <h4 class="card-title pricing-card-title">For Support Send E-mail at <big class="text-muted">extensionstudio.oc@gmail.com</big></h4>
                    <a target="_BLANK" href="https://www.opencart.com/index.php?route=marketplace/extension&filter_member=ExtensionStudio" class="btn btn-lg btn-primary">View Other Extensions</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
      </div>
    </div>
  </div>
<!-- // categories list -->
<div class="modal fade jmodal" id="importCategoriesModal" tabindex="-1" role="dialog" aria-labelledby="importCategoriesLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h5 class="modal-title" id="importCategoriesLabel"><i class="fa fa-arrow-down"></i> <?php echo $text_title_categories_import; ?></h5>
      </div>
      <div class="modal-body">
        <form id="form-categoriesimport" class="form-horizontal">
          <div class="row">
            <div class="col-sm-10 col-sm-offset-2">
              <input type="file" id="jc_categoriesfile" name="jc_categoriesfile" accept=".xls,.xlsx,.csv" required="required" style="display: none;" />
              <button for="jc_categoriesfile" type="button" class="btn btn-info btn-sm upload_jc_file"><i class="fa fa-check-circle-o"></i> <?php echo $button_select_file; ?></button>
              <label id="upload-categoriesname"></label>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times"></i> <?php echo $button_close; ?></button>
        <button type="button" class="btn btn-info" id="cg_categories_import"><i data-class="fa fa-arrow-down" class="fa fa-arrow-down"></i> <?php echo $button_import; ?></button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade jmodal" id="exportCategoriesModal" tabindex="-1" role="dialog" aria-labelledby="exportCategoriesModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h5 class="modal-title" id="exportCategoriesModalLabel"><i class="fa fa-arrow-up"></i> <?php echo $text_title_categories_export; ?></h5>
      </div>
      <div class="modal-body">
        <div id="form-categories-export" class="form-horizontal">
          <div class="row">
            <div class="col-sm-8 col-xs-12">
              <div class="form-group">
                <label class="control-label" for="input-categories_export_ids"><?php echo $entry_category_ids; ?><br/><span class="help"><?php echo $help_category_ids; ?></span></label>
                <input class="form-control" id="input-categories_export_ids" type="text" name="categories_export_ids" placeholder="<?php echo $entry_category_ids; ?>" value="<?php echo $categories_export_ids; ?>" />
              </div>
              <div class="form-group">
                <label class="control-label" for="input-categories_export_start_end_limit"><?php echo $entry_start_end_limit; ?><br/><span class="help"><?php echo $help_start_end_limit; ?></span></label>
                <input class="form-control" id="input-categories_export_start_end_limit" type="text" name="categories_export_start_end_limit" placeholder="<?php echo $entry_start_end_limit; ?>" value="<?php echo $categories_export_start_end_limit; ?>" />
              </div>
              <div class="form-group">
                <label class="control-label" for="input-categories_export_format"><?php echo $entry_format; ?></label>
                <select name="categories_export_format" class="form-control" id="input-categories_export_format">
                  <option value="xls" <?php if ($categories_export_format == 'xls') { ?>selected="selected"<?php } ?>><?php echo $text_xls; ?></option>
                  <option value="xlsx" <?php if ($categories_export_format == 'xlsx') { ?>selected="selected"<?php } ?>><?php echo $text_xlsx; ?></option>
                  <option value="csv" <?php if ($categories_export_format == 'csv') { ?>selected="selected"<?php } ?>><?php echo $text_csv; ?></option>
                </select>
              </div>
              <div class="form-group">
                <label class="control-label" for="input-categories_export_status"><?php echo $entry_category_status; ?></label>
                <select name="categories_export_status" class="form-control" id="input-categories_export_status">
                  <option value=""><?php echo $text_select; ?></option>
                  <option value="1" <?php if ($categories_export_status == '1') { ?>selected="selected"<?php } ?>><?php echo $text_enabled; ?></option>
                  <option value="0" <?php if ($categories_export_status == '0') { ?>selected="selected"<?php } ?>><?php echo $text_disabled; ?></option>
                </select>
              </div>
            </div>
            <div class="col-sm-4 col-xs-12">
              <div class="form-group export_products">
                <label class="control-label" for="input-categories_export_categories"><?php echo $entry_export_categories; ?></label>
                <input type="text" name="categories_export_categories_find" value="" placeholder="<?php echo $entry_export_categories; ?>" id="input-categories_export_categories" class="form-control" />
                <div id="categories_export_categories" class="well well-sm" style="min-height: 150px;">
                  <?php foreach ($categories_export_categories as $export_category) { ?>
                  <div id="categories_export_categories-<?php echo $export_category['category_id'];?>"><i class="fa fa-minus-circle"></i> <?php echo $export_category['name']; ?> <input type="hidden" name="categories_export_categories[]" value="<?php echo $export_category['category_id'];?>"></div>
                  <?php } ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times"></i> <?php echo $button_close; ?></button>
        <button type="button" class="btn btn-warning" id="categories_export"><i data-class="fa fa-arrow-up" class="fa fa-arrow-up"></i> <?php echo $button_export; ?></button>
        <button type="button" class="btn btn-primary save_categories_export_data"><i class="fa fa-save" data-class="fa fa-save"></i> <?php echo $button_save_export_settings; ?></button>
      </div>
    </div>
  </div>
</div>

<!-- // categories list -->

<!-- // products list -->

<div class="modal fade jmodal" id="importProductsModal" tabindex="-1" role="dialog" aria-labelledby="importProductsLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h5 class="modal-title" id="importProductsLabel"><i class="fa fa-arrow-down"></i> <?php echo $text_title_products_import; ?></h5>
      </div>
      <div class="modal-body">
        <form id="form-productsimport" class="form-horizontal">
          <div class="row">
            <div class="col-sm-10 col-sm-offset-2">
              <input type="file" id="jc_productsfile" name="jc_productsfile" accept=".xls,.xlsx,.csv" required="required" style="display: none;" />
              <button for="jc_productsfile" type="button" class="btn btn-info btn-sm upload_jc_file"><i class="fa fa-check-circle-o"></i> <?php echo $button_select_file; ?></button>
              <label id="upload-productsname"></label>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times"></i> <?php echo $button_close; ?></button>
        <button type="button" class="btn btn-info" id="cg_products_import"><i data-class="fa fa-arrow-down" class="fa fa-arrow-down"></i> <?php echo $button_import; ?></button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade jmodal" id="exportProductsModal" tabindex="-1" role="dialog" aria-labelledby="exportProductsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h5 class="modal-title" id="exportProductsModalLabel"><i class="fa fa-arrow-up"></i> <?php echo $text_title_products_export; ?></h5>
      </div>
      <div class="modal-body">
        <div id="form-products-export" class="form-horizontal">
          <div class="row">
            <div class="col-sm-8 col-xs-12">
              <div class="form-group">
                <label class="control-label" for="input-products_export_store"><?php echo $entry_store; ?></label>
                <select name="products_export_store_id" id="input-products_export_store" class="form-control">
                  <?php foreach ($stores as $store) { ?>
                    <option value="<?php echo $store['store_id']; ?>" <?php if ($store['store_id'] == $products_export_store_id) { ?>selected="selected"<?php } ?>><?php echo $store['name']; ?></option>
                  <?php } ?>
                </select>
              </div>
              <div class="form-group">
                <label class="control-label" for="input-products_export_ids"><?php echo $entry_product_ids; ?><br/><span class="help"><?php echo $help_product_ids; ?></span></label>
                <input class="form-control" id="input-products_export_ids" type="text" name="products_export_ids" placeholder="<?php echo $entry_product_ids; ?>" value="<?php echo $products_export_ids; ?>" />
              </div>
              <div class="form-group">
                <label class="control-label" for="input-products_export_start_end_limit"><?php echo $entry_start_end_limit; ?><br/><span class="help"><?php echo $help_start_end_limit; ?></span></label>
                <input class="form-control" id="input-products_export_start_end_limit" type="text" name="products_export_start_end_limit" placeholder="<?php echo $entry_start_end_limit; ?>" value="<?php echo $products_export_start_end_limit; ?>" />
              </div>
              <div class="form-group">
                <label class="control-label" for="input-products_export_format"><?php echo $entry_format; ?></label>
                <select name="products_export_format" class="form-control" id="input-products_export_format">
                  <option value="xls" <?php if ($products_export_format == 'xls') { ?>selected="selected"<?php } ?>><?php echo $text_xls; ?></option>
                  <option value="xlsx" <?php if ($products_export_format == 'xlsx') { ?>selected="selected"<?php } ?>><?php echo $text_xlsx; ?></option>
                  <option value="csv" <?php if ($products_export_format == 'csv') { ?>selected="selected"<?php } ?>><?php echo $text_csv; ?></option>
                </select>
              </div>
              <div class="form-group">
                <label class="control-label" for="input-products_export_status"><?php echo $entry_product_status; ?></label>
                <select name="products_export_status" class="form-control" id="input-products_export_status">
                  <option value=""><?php echo $text_select; ?></option>
                  <option value="1" <?php if ($products_export_status == '1') { ?>selected="selected"<?php } ?>><?php echo $text_enabled; ?></option>
                  <option value="0" <?php if ($products_export_status == '0') { ?>selected="selected"<?php } ?>><?php echo $text_disabled; ?></option>
                </select>
              </div>
            </div>
            <div class="col-sm-4 col-xs-12">
              <div class="form-group products_export_products">
                <label class="control-label" for="input-products_export_products"><?php echo $entry_export_products; ?></label>
                <input type="text" name="products_export_products_find" value="" placeholder="<?php echo $entry_export_products; ?>" id="input-products_export_products" class="form-control" />
                <div id="products_export_products" class="well well-sm" style="min-height: 150px;">
                  <?php foreach ($products_export_products as $export_product) { ?>
                  <div id="products_export_products-<?php echo $export_product['product_id'];?>"><i class="fa fa-minus-circle"></i> <?php echo $export_product['name']; ?> <input type="hidden" name="products_export_products[]" value="<?php echo $export_product['product_id'];?>"></div>
                  <?php } ?>
                </div>
              </div>
              <div class="form-group products_export_category_products">
                <label class="control-label" for="input-products_export_category_products"><?php echo $entry_export_categories; ?></label>
                <input type="text" name="products_export_category_products_find" value="" placeholder="<?php echo $entry_export_categories; ?>" id="input-products_export_category_products" class="form-control" />
                <div id="products_export_category_products" class="well well-sm" style="min-height: 150px;">
                  <?php foreach ($products_export_category_products as $export_category_product) { ?>
                  <div id="products_export_category_products-<?php echo $export_category_product['category_id'];?>"><i class="fa fa-minus-circle"></i> <?php echo $export_category_product['name']; ?> <input type="hidden" name="products_export_category_products[]" value="<?php echo $export_category_product['category_id'];?>"></div>
                  <?php } ?>
                </div>
              </div>
              <div class="form-group products_export_manufacturer_products">
                <label class="control-label" for="input-products_export_manufacturer_products"><?php echo $entry_export_manufacturer; ?></label>
                <input type="text" name="products_export_manufacturer_products_find" value="" placeholder="<?php echo $entry_export_manufacturer; ?>" id="input-products_export_manufacturer_products" class="form-control" />
                <div id="products_export_manufacturer_products" class="well well-sm" style="min-height: 150px;">
                  <?php foreach ($products_export_manufacturer_products as $export_manufacturer_product) { ?>
                  <div id="products_export_manufacturer_products-<?php echo $export_manufacturer_product['manufacturer_id'];?>"><i class="fa fa-minus-circle"></i> <?php echo $export_manufacturer_product['name']; ?> <input type="hidden" name="products_export_manufacturer_products[]" value="<?php echo $export_manufacturer_product['manufacturer_id'];?>"></div>
                  <?php } ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times"></i> <?php echo $button_close; ?></button>
        <button type="button" class="btn btn-warning" id="products_export"><i data-class="fa fa-arrow-up" class="fa fa-arrow-up"></i> <?php echo $button_export; ?></button>
        <button type="button" class="btn btn-primary save_products_export_data"><i class="fa fa-save" data-class="fa fa-save"></i> <?php echo $button_save_export_settings; ?></button>
      </div>
    </div>
  </div>
</div>
<!-- // products list -->
<script type="text/javascript"><!--
// Category
$('input[name=\'category\']').autocomplete({
  'source': function(request, response) {
    $.ajax({
      url: 'index.php?route=catalog/category/autocomplete&<?php echo $joctoken; ?>=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
      dataType: 'json',
      success: function(json) {
        response($.map(json, function(item) {
          return {
            label: item['name'],
            value: item['category_id']
          }
        }));
      }
    });
  },
  'select': function(item) {
    $('input[name=\'category\']').val('');

    $('#category' + item['value']).remove();

    $('#category').append('<div id="category' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="categories[]" value="' + item['value'] + '" /></div>');
  }
});

$('#category').delegate('.fa-minus-circle', 'click', function() {
  $(this).parent().remove();
});

// product
$('input[name=\'product\']').autocomplete({
  'source': function(request, response) {
    $.ajax({
      url: 'index.php?route=catalog/product/autocomplete&<?php echo $joctoken; ?>=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
      dataType: 'json',
      success: function(json) {
        response($.map(json, function(item) {
          return {
            label: item['name'],
            value: item['product_id']
          }
        }));
      }
    });
  },
  'select': function(item) {
    $('input[name=\'product\']').val('');

    $('#product' + item['value']).remove();

    $('#product').append('<div id="product' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="products[]" value="' + item['value'] + '" /></div>');
  }
});

$('#product').delegate('.fa-minus-circle', 'click', function() {
  $(this).parent().remove();
});

$('.assign').on('click', function() {

  var $this = $(this);
  var $i = $this.find('i');
  $.ajax({
    url: 'index.php?route=customergroupjproduct/customergroupjproduct/assign&<?php echo $joctoken; ?>=<?php echo $token; ?>',
    type: 'post',
    data: $('#form-customergroups').serialize(),
    dataType: 'json',
    beforeSend: function() {
      $this.attr('disabled','disabled');
      $i.attr('class', 'fa fa-spinner fa-spin');
    },
    complete: function() {
      $this.removeAttr('disabled');
      $i.attr('class', $i.attr('data-class'));
    },
    success: function(json) {
      $('.alert, .text-danger').remove();

      if(typeof json['error'] != 'undefined') {
        if (json['error']['warning']) {
          $this.parent().parent().after('<div class="alert alert-danger"><i class="fa fa-check-circle"></i> ' + json['error']['warning'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
        }
        if (json['error']['none']) {
          $('.products-categories').append('<div class="text-danger col-sm-12"><i class="fa fa-check-circle"></i> ' + json['error']['none'] + '</div>');
        }
        if (json['error']['customer_group']) {
          $('.customer-groups').parent().append('<div class="text-danger"><i class="fa fa-check-circle"></i> ' + json['error']['customer_group'] + '</div>');
        }
      }


      if (json['success']) {
        $this.parent().parent().after('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');

        $('input[name="category"]').val('');
        $('input[name="product"]').val('');
      }
    },
    error: function(xhr, ajaxOptions, thrownError) {
      alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
    }
  });
});

$('.save_data').on('click', function() {

  var $this = $(this);
  var $i = $this.find('i');
  $.ajax({
    url: 'index.php?route=customergroupjproduct/customergroupjproduct/saveData&<?php echo $joctoken; ?>=<?php echo $token; ?>',
    type: 'post',
    data: $('#form-customergroups').serialize(),
    dataType: 'json',
    beforeSend: function() {
      $this.attr('disabled','disabled');
      $i.attr('class', 'fa fa-spinner fa-spin');
    },
    complete: function() {
      $this.removeAttr('disabled');
      $i.attr('class', $i.attr('data-class'));
    },
    success: function(json) {
      $('.alert, .text-danger').remove();

      if(typeof json['error'] != 'undefined') {
        if (json['error']['warning']) {
          $this.parent().parent().after('<div class="alert alert-danger"><i class="fa fa-check-circle"></i> ' + json['error']['warning'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
        }
        if (json['error']['none']) {
          $('.products-categories').append('<div class="text-danger col-sm-12"><i class="fa fa-check-circle"></i> ' + json['error']['none'] + '</div>');
        }
        if (json['error']['customer_group']) {
          $('.customer-groups').parent().append('<div class="text-danger"><i class="fa fa-check-circle"></i> ' + json['error']['customer_group'] + '</div>');
        }
      }


      if (json['success']) {
        $this.parent().parent().after('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');

        $('input[name="category"]').val('');
        $('input[name="product"]').val('');
      }
    },
    error: function(xhr, ajaxOptions, thrownError) {
      alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
    }
  });
});

// products list
$('#products-list').delegate('.pagination a', 'click', function(e) {
  e.preventDefault();
  $('#products-list').load(this.href);
});

$('#products-list').delegate('a.sort_order', 'click', function(e) {
  e.preventDefault();
  $('#products-list').load(this.href);
});


$('#products-list').load('index.php?route=customergroupjproduct/customergroupjproduct/productsList&<?php echo $joctoken; ?>=<?php echo $token; ?>');

$('#button-product_filter').on('click', function() {
  var $this = $(this);
  var $i = $this.find('i');
  var filters = productsListFilters([]);
  $this.attr('disabled', 'disabled');
  $i.attr('class', 'fa fa-spinner fa-spin');
  $('.alert, .text-danger').remove();
  var url = 'index.php?route=customergroupjproduct/customergroupjproduct/productsList&<?php echo $joctoken; ?>=<?php echo $token; ?>';
  if (filters) {
    url += '&';
  }
  url += filters.join('&');

  $('#products-list').load(url, function() {
    $i.attr('class', $i.attr('data-class'));
    $this.removeAttr('disabled');
  });
});

$('#button-product_clear_filter').on('click', function() {
  var $this = $(this);
  var $i = $this.find('i');
  $this.attr('disabled', 'disabled');
  $i.attr('class', 'fa fa-spinner fa-spin');
  $('.alert, .text-danger').remove();
  var url = 'index.php?route=customergroupjproduct/customergroupjproduct/productsList&<?php echo $joctoken; ?>=<?php echo $token; ?>';
  $('#products-list').load(url, function() {
    productsListClearFilters();
    $i.attr('class', $i.attr('data-class'));
    $this.removeAttr('disabled');
  });
});

function productsListClearFilters() {
  $('input[name=\'filter_product_name\']').val('');
  $('input[name=\'filter_product_model\']').val('');
  $('select[name=\'filter_product_status\']').val('*');
}

function productsListFilters(filters) {
  filters = filters || [];

  var filter_name = $('input[name=\'filter_product_name\']').val();

  if (filter_name) {
    filters.push('filter_name=' + encodeURIComponent(filter_name));
  }

  var filter_model = $('input[name=\'filter_product_model\']').val();

  if (filter_model) {
    filters.push('filter_model=' + encodeURIComponent(filter_model));
  }

  var filter_status = $('select[name=\'filter_product_status\']').val();

  if (filter_status != '*') {
    filters.push('filter_status=' + encodeURIComponent(filter_status));
  }

  return filters;
}

$('.products_update').on('click', function() {
  var $this = $(this);
  var $i = $this.find('i');

  var data = [];
  $('#products-list input[name*="pselected"]:checked').each(function() {
    console.log('product_id :' + this.value);
    data.push('products[]=' +  encodeURIComponent(this.value));
  });

  $('#products_customergroups input[type="checkbox"]:checked').each(function() {
    console.log("customer_group_id :" + this.value);
    data.push('customer_group_ids[]=' +  encodeURIComponent(this.value));
  });

  $.ajax({
    url: 'index.php?route=customergroupjproduct/customergroupjproduct/quickUpdateProducts&<?php echo $joctoken; ?>=<?php echo $token; ?>',
    type: 'post',
    data: data.join('&'),
    dataType: 'json',
    beforeSend: function() {
      $this.attr('disabled','disabled');
      $i.attr('class', 'fa fa-spinner fa-spin');
    },
    complete: function() {
      $this.removeAttr('disabled');
      $i.attr('class', $i.attr('data-class'));
    },
    success: function(json) {
      $('.alert, .text-danger').remove();

      if(typeof json['error'] != 'undefined') {
        if (json['error']['warning']) {
          $('#product_quick_update').after('<div class="alert alert-danger"><i class="fa fa-check-circle"></i> ' + json['error']['warning'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
        }
        if (json['error']['none']) {
          $('#product_quick_update').after('<div class="alert alert-danger"><i class="fa fa-check-circle"></i> ' + json['error']['none'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
        }
        if (json['error']['customer_group']) {
          $('#products_customergroups').append('<div class="col-sm-12 text-danger"><i class="fa fa-check-circle"></i> ' + json['error']['customer_group'] + '</div>');
        }
      }

      if (json['success']) {
        $('#product_quick_update').after('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
      }
    },
    error: function(xhr, ajaxOptions, thrownError) {
      alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
    }
  });
});
$(document).delegate('.product_update', 'click', function() {
  var $this = $(this);
  var $i = $this.find('i');

  var $tr = $(this).parents('.products');
  var product_id = $tr.attr('id').replace('product_','');
  console.log("product_id :" + product_id);

  var data = [];

  $('#product_customer_groups_'+ product_id +' input[type="checkbox"]:checked').each(function() {
    console.log("customer_group_id :" + this.value)
    data.push('customer_group_ids[]=' +  encodeURIComponent(this.value));
  });

  data.push('products[]=' +  encodeURIComponent(product_id));

  $.ajax({
    url: 'index.php?route=customergroupjproduct/customergroupjproduct/quickUpdateProducts&<?php echo $joctoken; ?>=<?php echo $token; ?>',
    type: 'post',
    data: data.join('&'),
    dataType: 'json',
    beforeSend: function() {
      $this.attr('disabled','disabled');
      $i.attr('class', 'fa fa-spinner fa-spin');
    },
    complete: function() {
      $this.removeAttr('disabled');
      $i.attr('class', $i.attr('data-class'));
    },
    success: function(json) {
      $('.alert, .text-danger').remove();

      if(typeof json['error'] != 'undefined') {
        if (json['error']['warning']) {
          $('#products-list').before('<div class="alert alert-danger"><i class="fa fa-check-circle"></i> ' + json['error']['warning'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
        }
        if (json['error']['none']) {
          $('#products-list').before('<div class="text-danger col-sm-12"><i class="fa fa-check-circle"></i> ' + json['error']['none'] + '</div>');
        }
        if (json['error']['customer_group']) {
          $('#product_customer_groups_'+product_id).after('<div class="text-danger"><i class="fa fa-check-circle"></i> ' + json['error']['customer_group'] + '</div>');
        }
      }


      if (json['success']) {
        $('#products-list').before('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
      }
    },
    error: function(xhr, ajaxOptions, thrownError) {
      alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
    }
  });
});

// products list

// categories list
$('#categories-list').delegate('.pagination a', 'click', function(e) {
  e.preventDefault();
  $('#categories-list').load(this.href);
});

$('#categories-list').delegate('a.sort_order', 'click', function(e) {
  e.preventDefault();
  $('#categories-list').load(this.href);
});


$('#categories-list').load('index.php?route=customergroupjproduct/customergroupjproduct/categoriesList&<?php echo $joctoken; ?>=<?php echo $token; ?>');

$('#button-category_filter').on('click', function() {
  var $this = $(this);
  var $i = $this.find('i');
  var filters = categoriesListFilters([]);
  $this.attr('disabled', 'disabled');
  $i.attr('class', 'fa fa-spinner fa-spin');
  $('.alert, .text-danger').remove();
  var url = 'index.php?route=customergroupjproduct/customergroupjproduct/categoriesList&<?php echo $joctoken; ?>=<?php echo $token; ?>';
  if (filters) {
    url += '&';
  }
  url += filters.join('&');

  $('#categories-list').load(url, function() {
    $i.attr('class', $i.attr('data-class'));
    $this.removeAttr('disabled');
  });
});

$('#button-category_clear_filter').on('click', function() {
  var $this = $(this);
  var $i = $this.find('i');
  $this.attr('disabled', 'disabled');
  $i.attr('class', 'fa fa-spinner fa-spin');
  $('.alert, .text-danger').remove();
  var url = 'index.php?route=customergroupjproduct/customergroupjproduct/categoriesList&<?php echo $joctoken; ?>=<?php echo $token; ?>';
  $('#categories-list').load(url, function() {
    categoriesListClearFilters();
    $i.attr('class', $i.attr('data-class'));
    $this.removeAttr('disabled');
  });
});

function categoriesListClearFilters() {
  $('input[name=\'filter_category_name\']').val('');
  $('select[name=\'filter_category_status\']').val('*');
}

function categoriesListFilters(filters) {
  filters = filters || [];

  var filter_name = $('input[name=\'filter_category_name\']').val();

  if (filter_name) {
    filters.push('filter_name=' + encodeURIComponent(filter_name));
  }

  var filter_status = $('select[name=\'filter_category_status\']').val();

  if (filter_status != '*') {
    filters.push('filter_status=' + encodeURIComponent(filter_status));
  }

  return filters;
}

$('.categories_update').on('click', function() {
  var $this = $(this);
  var $i = $this.find('i');

  var data = [];
  $('#categories-list input[name*="cselected"]:checked').each(function() {
    console.log('category_id :' + this.value);
    data.push('categories[]=' +  encodeURIComponent(this.value));
  });

  $('#categories_customergroups input[type="checkbox"]:checked').each(function() {
    console.log("customer_group_id :" + this.value);
    data.push('customer_group_ids[]=' +  encodeURIComponent(this.value));
  });

  $.ajax({
    url: 'index.php?route=customergroupjproduct/customergroupjproduct/quickUpdateCategories&<?php echo $joctoken; ?>=<?php echo $token; ?>',
    type: 'post',
    data: data.join('&'),
    dataType: 'json',
    beforeSend: function() {
      $this.attr('disabled','disabled');
      $i.attr('class', 'fa fa-spinner fa-spin');
    },
    complete: function() {
      $this.removeAttr('disabled');
      $i.attr('class', $i.attr('data-class'));
    },
    success: function(json) {
      $('.alert, .text-danger').remove();

      if(typeof json['error'] != 'undefined') {
        if (json['error']['warning']) {
          $('#category_quick_update').after('<div class="alert alert-danger"><i class="fa fa-check-circle"></i> ' + json['error']['warning'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
        }
        if (json['error']['none']) {
          $('#category_quick_update').after('<div class="alert alert-danger"><i class="fa fa-check-circle"></i> ' + json['error']['none'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
        }
        if (json['error']['customer_group']) {
          $('#categories_customergroups').append('<div class="col-sm-12 text-danger"><i class="fa fa-check-circle"></i> ' + json['error']['customer_group'] + '</div>');
        }
      }

      if (json['success']) {
        $('#category_quick_update').after('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
      }
    },
    error: function(xhr, ajaxOptions, thrownError) {
      alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
    }
  });
});
$(document).delegate('.category_update', 'click', function() {
  var $this = $(this);
  var $i = $this.find('i');

  var $tr = $(this).parents('.categories');
  var category_id = $tr.attr('id').replace('category_','');
  console.log("category_id :" + category_id);

  var data = [];

  $('#category_customer_groups_'+ category_id +' input[type="checkbox"]:checked').each(function() {
    console.log("customer_group_id :" + this.value)
    data.push('customer_group_ids[]=' +  encodeURIComponent(this.value));
  });

  data.push('categories[]=' +  encodeURIComponent(category_id));

  $.ajax({
    url: 'index.php?route=customergroupjproduct/customergroupjproduct/quickUpdateCategories&<?php echo $joctoken; ?>=<?php echo $token; ?>',
    type: 'post',
    data: data.join('&'),
    dataType: 'json',
    beforeSend: function() {
      $this.attr('disabled','disabled');
      $i.attr('class', 'fa fa-spinner fa-spin');
    },
    complete: function() {
      $this.removeAttr('disabled');
      $i.attr('class', $i.attr('data-class'));
    },
    success: function(json) {
      $('.alert, .text-danger').remove();

      if(typeof json['error'] != 'undefined') {
        if (json['error']['warning']) {
          $('#categories-list').before('<div class="alert alert-danger"><i class="fa fa-check-circle"></i> ' + json['error']['warning'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
        }
        if (json['error']['none']) {
          $('#categories-list').before('<div class="text-danger col-sm-12"><i class="fa fa-check-circle"></i> ' + json['error']['none'] + '</div>');
        }
        if (json['error']['customer_group']) {
          $('#category_customer_groups_'+category_id).after('<div class="text-danger"><i class="fa fa-check-circle"></i> ' + json['error']['customer_group'] + '</div>');
        }
      }


      if (json['success']) {
        $('#categories-list').before('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
      }
    },
    error: function(xhr, ajaxOptions, thrownError) {
      alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
    }
  });
});

// categories list

// import export
$('.upload_jc_file').on('click', function() {
  $('#'+$(this).attr('for')).trigger('click');
});

function getUploadFileInfo (name) {
  var el = document.getElementById(name);
  // console.log(el.files.item(0));
  // console.log(el.files[0]);
  return el.files.item(0);
};

// categories list
$("#jc_categoriesfile").on('change', function() {
  var file_info = getUploadFileInfo('jc_categoriesfile');
  $('#upload-categoriesname').html(file_info.name);
});

$('#exportCategoriesModal').on('shown.bs.modal', function(e) {
  var $i = $(e.relatedTarget).find('i');
  if ($i.attr('data-class')) {
    $i.attr('class', $i.attr('data-class'));
  }
});

$('#importCategoriesModal').on('hidden.bs.modal', function(e) {
  $('#upload-categoriesname').html('');
  $("#jc_file").val('');
});

$('#importCategoriesModal').on('shown.bs.modal', function(e) {
  var $i = $(e.relatedTarget).find('i');
  if ($i.attr('data-class')) {
    $i.attr('class', $i.attr('data-class'));
  }
});

$('.btn-export_categories').on('click', function() {
  var $i = $(this).find('i');
  if ($i.attr('data-class')) {
    $i.attr('class', 'fa fa-spinner fa-spin');
  }
  // show export modal
  $('#exportCategoriesModal').modal('show', this);
});
$('.btn-import_categories').on('click', function() {
  var $i = $(this).find('i');
  if ($i.attr('data-class')) {
    $i.attr('class', 'fa fa-spinner fa-spin');
  }
  // show import modal
  $('#importCategoriesModal').modal('show', this);
});

$('#cg_categories_import').click(function() {
    var that = this;
    var $i = $(that).find('i');

    var $modal = $('#importCategoriesModal');

    if ($('#form-categoriesimport input[name=\'jc_categoriesfile\']').val() != '') {

      $.ajax({
        url: 'index.php?route=customergroupjproduct/customergroupjproduct/doCategoriesImport&<?php echo $joctoken; ?>=<?php echo $token; ?>',
        type: 'post',
        dataType: 'json',
        data: new FormData($('#form-categoriesimport')[0]),
        cache: false,
        contentType: false,
        processData: false,
        beforeSend: function() {
          $(that).attr('disabled', 'disabled');
          $i.attr('class','fa fa-spinner fa-spin');
        },
        complete: function() {
          $(that).removeAttr('disabled', 'disabled');
          $i.attr('class', $i.attr('data-class'));
        },
        success: function(json) {
          $('.alert, .text-danger').remove();

          if (json['error']) {
              if (json['error']['file']) {
                $(that).after('<div class="text text-danger alert-dismissible"><i class="fa fa-exclamation-circle"></i> ' + json['error']['file'] + ' <button type="button" class="close" onclick="$(this).parent().remove();" data-dismiss="alert">&times;</button></div>');
              }
              if (json['error']['warning']) {
                $modal.find('.modal-header').before('<div class="alert alert-danger alert-dismissible"><i class="fa fa-exclamation-circle"></i> ' + json['error']['warning'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
            }
          }

          if (json['success']) {
            $modal.find('.modal-header').before('<div class="alert alert-success alert-dismissible"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
          }
        },
        error: function(xhr, ajaxOptions, thrownError) {
          alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
      });
    }
});



$('input[name=\'categories_export_categories_find\']').autocomplete({
  'source': function(request, response) {
    $.ajax({
      url: 'index.php?route=catalog/category/autocomplete&<?php echo $joctoken; ?>=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
      dataType: 'json',
      success: function(json) {
        response($.map(json, function(item) {
          return {
            label: item['name'],
            value: item['category_id']
          }
        }));
      }
    });
  },
  'select': function(item) {

    $('input[name=\'categories_export_categories_find\']').val('');

    $('#categories_export_categories-' + item['value']).remove();

    $('#categories_export_categories').append('<div id="categories_export_categories-' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="categories_export_categories[]" value="' + item['value'] + '" /></div>');
  }
});

$('#categories_export_categories').delegate('.fa-minus-circle', 'click', function() {
  $(this).parent().remove();
});

$('.save_categories_export_data').on('click', function() {
  var that = this;
  var $i = $(that).find('i');
  var data = $('#form-categories-export input[type="text"], #form-categories-export input[type="hidden"], #form-categories-export input[type="radio"]:checked, #form-categories-export input[type="checkbox"]:checked, #form-categories-export select, #form-categories-export textarea').serialize();

  $.ajax({
    url: 'index.php?route=customergroupjproduct/customergroupjproduct/saveCategoriesExportData&j=1&<?php echo $joctoken; ?>=<?php echo $token; ?>',
    type: 'post',
    data: data,
    dataType: 'json',
    beforeSend: function() {
      $i.attr('class','fa fa-spinner fa-spin');
      $(that).attr('disabled','disabled');
    },
    complete: function() {
      $i.attr('class', $i.attr('data-class'));
      $(that).removeAttr('disabled');
    },
    success: function(json) {
      $('#exportCategoriesModal .alert, #exportCategoriesModal .text-danger').remove();

      if (json['error']) {
        if (typeof json['error']['warning'] != 'undefined' && json['error']['warning']) {
          $('#exportCategoriesModal').find('.modal-footer').before('<div class="alert alert-danger"><i class="fa fa-check-circle"></i> '+ json['error']['warning'] +' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
        }
      }

      if (json['success']) {
        $('#exportCategoriesModal').find('.modal-footer').before('<div class="alert alert-success"><i class="fa fa-check-circle"></i> '+ json['success'] +' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
      }
    },
    error: function(xhr, ajaxOptions, thrownError) {
      alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
    }
  });
});


$('#categories_export').click(function() {
  var that = this;
  var $i = $(that).find('i');
  var $modal = $('#exportCategoriesModal');
  $.ajax({
    url: 'index.php?route=customergroupjproduct/customergroupjproduct/doCategoriesExport&<?php echo $joctoken; ?>=<?php echo $token; ?>',
    type: 'post',
    data: $('#form-categories-export input[type=\'text\'], #form-categories-export input[type=\'hidden\'], #form-categories-export select, #form-categories-export input[type=\'checkbox\']:checked, #form-categories-export input[type=\'radio\']:checked'),
    dataType: 'json',
    beforeSend: function() {
      $(that).attr('disabled', 'disabled');
      $i.attr('class','fa fa-spinner fa-spin');
    },
    complete: function() {
      $(that).removeAttr('disabled', 'disabled');
      $i.attr('class', $i.attr('data-class'));
    },
    success: function(json) {

      $('#exportCategoriesModal .alert, #exportCategoriesModal .text-danger').remove();
      if(json['error']) {
        $modal.find('.modal-footer').before('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> '+ json['error'] +' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');

        // $('html, body').animate({ scrollTop: 0 }, 'slow');
      }

      if(json['href']) {
        window.location = json['href'];
      }
    },
    error: function(xhr, ajaxOptions, thrownError) {
      $('#exportCategoriesModal .alert, #exportCategoriesModal .text-danger').remove();
      if(xhr.responseText) {
        $modal.find('.modal-footer').before('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> '+ xhr.responseText +' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
      }
    }
  });
});
// categories list

// products list
$("#jc_productsfile").on('change', function() {
  var file_info = getUploadFileInfo('jc_productsfile');
  $('#upload-productsname').html(file_info.name);
});

$('#exportProductsModal').on('shown.bs.modal', function(e) {
  var $i = $(e.relatedTarget).find('i');
  if ($i.attr('data-class')) {
    $i.attr('class', $i.attr('data-class'));
  }
});

$('#importProductsModal').on('hidden.bs.modal', function(e) {
  $('#upload-productsname').html('');
  $("#jc_file").val('');
});

$('#importProductsModal').on('shown.bs.modal', function(e) {
  var $i = $(e.relatedTarget).find('i');
  if ($i.attr('data-class')) {
    $i.attr('class', $i.attr('data-class'));
  }
});

$('.btn-export_products').on('click', function() {
  var $i = $(this).find('i');
  if ($i.attr('data-class')) {
    $i.attr('class', 'fa fa-spinner fa-spin');
  }
  // show export modal
  $('#exportProductsModal').modal('show', this);
});
$('.btn-import_products').on('click', function() {
  var $i = $(this).find('i');
  if ($i.attr('data-class')) {
    $i.attr('class', 'fa fa-spinner fa-spin');
  }
  // show import modal
  $('#importProductsModal').modal('show', this);
});

$('#cg_products_import').click(function() {
    var that = this;
    var $i = $(that).find('i');

    var $modal = $('#importProductsModal');

    if ($('#form-productsimport input[name=\'jc_productsfile\']').val() != '') {

      $.ajax({
        url: 'index.php?route=customergroupjproduct/customergroupjproduct/doProductsImport&<?php echo $joctoken; ?>=<?php echo $token; ?>',
        type: 'post',
        dataType: 'json',
        data: new FormData($('#form-productsimport')[0]),
        cache: false,
        contentType: false,
        processData: false,
        beforeSend: function() {
          $(that).attr('disabled', 'disabled');
          $i.attr('class','fa fa-spinner fa-spin');
        },
        complete: function() {
          $(that).removeAttr('disabled', 'disabled');
          $i.attr('class', $i.attr('data-class'));
        },
        success: function(json) {
          $('.alert, .text-danger').remove();

          if (json['error']) {
              if (json['error']['file']) {
                $(that).after('<div class="text text-danger alert-dismissible"><i class="fa fa-exclamation-circle"></i> ' + json['error']['file'] + ' <button type="button" class="close" onclick="$(this).parent().remove();" data-dismiss="alert">&times;</button></div>');
              }
              if (json['error']['warning']) {
                $modal.find('.modal-header').before('<div class="alert alert-danger alert-dismissible"><i class="fa fa-exclamation-circle"></i> ' + json['error']['warning'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
            }
          }

          if (json['success']) {
            $modal.find('.modal-header').before('<div class="alert alert-success alert-dismissible"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
          }
        },
        error: function(xhr, ajaxOptions, thrownError) {
          alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
      });
    }
});


$('input[name=\'products_export_products_find\']').autocomplete({
  'source': function(request, response) {
    $.ajax({
      url: 'index.php?route=catalog/product/autocomplete&<?php echo $joctoken; ?>=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
      dataType: 'json',
      success: function(json) {
        response($.map(json, function(item) {
          return {
            label: item['name'],
            value: item['product_id']
          }
        }));
      }
    });
  },
  'select': function(item) {

    $('input[name=\'products_export_products_find\']').val('');

    $('#products_export_products-' + item['value']).remove();

    $('#products_export_products').append('<div id="products_export_products-' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="products_export_products[]" value="' + item['value'] + '" /></div>');
  }
});

$('#products_export_products').delegate('.fa-minus-circle', 'click', function() {
  $(this).parent().remove();
});

$('input[name=\'products_export_category_products_find\']').autocomplete({
  'source': function(request, response) {
    $.ajax({
      url: 'index.php?route=catalog/category/autocomplete&<?php echo $joctoken; ?>=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
      dataType: 'json',
      success: function(json) {
        response($.map(json, function(item) {
          return {
            label: item['name'],
            value: item['category_id']
          }
        }));
      }
    });
  },
  'select': function(item) {

    $('input[name=\'products_export_category_products_find\']').val('');

    $('#products_export_category_products-' + item['value']).remove();

    $('#products_export_category_products').append('<div id="products_export_category_products-' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="products_export_category_products[]" value="' + item['value'] + '" /></div>');
  }
});

$('#products_export_category_products').delegate('.fa-minus-circle', 'click', function() {
  $(this).parent().remove();
});

$('input[name=\'products_export_manufacturer_products_find\']').autocomplete({
  'source': function(request, response) {
    $.ajax({
      url: 'index.php?route=catalog/manufacturer/autocomplete&<?php echo $joctoken; ?>=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
      dataType: 'json',
      success: function(json) {
        response($.map(json, function(item) {
          return {
            label: item['name'],
            value: item['manufacturer_id']
          }
        }));
      }
    });
  },
  'select': function(item) {

    $('input[name=\'products_export_manufacturer_products_find\']').val('');

    $('#products_export_manufacturer_products-' + item['value']).remove();

    $('#products_export_manufacturer_products').append('<div id="products_export_manufacturer_products-' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="products_export_manufacturer_products[]" value="' + item['value'] + '" /></div>');
  }
});

$('#products_export_manufacturer_products').delegate('.fa-minus-circle', 'click', function() {
  $(this).parent().remove();
});

$('.save_products_export_data').on('click', function() {
  var that = this;
  var $i = $(that).find('i');
  var data = $('#form-products-export input[type="text"], #form-products-export input[type="hidden"], #form-products-export input[type="radio"]:checked, #form-products-export input[type="checkbox"]:checked, #form-products-export select, #form-products-export textarea').serialize();

  $.ajax({
    url: 'index.php?route=customergroupjproduct/customergroupjproduct/saveProductsExportData&j=1&<?php echo $joctoken; ?>=<?php echo $token; ?>',
    type: 'post',
    data: data,
    dataType: 'json',
    beforeSend: function() {
      $i.attr('class','fa fa-spinner fa-spin');
      $(that).attr('disabled','disabled');
    },
    complete: function() {
      $i.attr('class', $i.attr('data-class'));
      $(that).removeAttr('disabled');
    },
    success: function(json) {
      $('#exportProductsModal .alert, #exportProductsModal .text-danger').remove();

      if (json['error']) {
        if (typeof json['error']['warning'] != 'undefined' && json['error']['warning']) {
          $('#exportProductsModal').find('.modal-footer').before('<div class="alert alert-danger"><i class="fa fa-check-circle"></i> '+ json['error']['warning'] +' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
        }
      }

      if (json['success']) {
        $('#exportProductsModal').find('.modal-footer').before('<div class="alert alert-success"><i class="fa fa-check-circle"></i> '+ json['success'] +' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
      }
    },
    error: function(xhr, ajaxOptions, thrownError) {
      alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
    }
  });
});


$('#products_export').click(function() {
  var that = this;
  var $i = $(that).find('i');
  var $modal = $('#exportProductsModal');
  $.ajax({
    url: 'index.php?route=customergroupjproduct/customergroupjproduct/doProductsExport&<?php echo $joctoken; ?>=<?php echo $token; ?>',
    type: 'post',
    data: $('#form-products-export input[type=\'text\'], #form-products-export input[type=\'hidden\'], #form-products-export select, #form-products-export input[type=\'checkbox\']:checked, #form-products-export input[type=\'radio\']:checked'),
    dataType: 'json',
    beforeSend: function() {
      $(that).attr('disabled', 'disabled');
      $i.attr('class','fa fa-spinner fa-spin');
    },
    complete: function() {
      $(that).removeAttr('disabled', 'disabled');
      $i.attr('class', $i.attr('data-class'));
    },
    success: function(json) {

      $('#exportProductsModal .alert, #exportProductsModal .text-danger').remove();
      if(json['error']) {
        $modal.find('.modal-footer').before('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> '+ json['error'] +' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');

        // $('html, body').animate({ scrollTop: 0 }, 'slow');
      }

      if(json['href']) {
        window.location = json['href'];
      }
    },
    error: function(xhr, ajaxOptions, thrownError) {
      $('#exportProductsModal .alert, #exportProductsModal .text-danger').remove();
      if(xhr.responseText) {
        $modal.find('.modal-footer').before('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> '+ xhr.responseText +' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
      }
    }
  });
});
// products list




// import export

//--></script></div>
<?php echo $footer; ?>
<div>
  <div class="table-responsive">
    <table class="table table-bordered table-hover">
      <thead>
        <tr>
          <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'cselected\']').prop('checked', this.checked);" /></td>
          <td class="text-center"><?php echo $column_image; ?></td>
          <td class="text-left"><?php if ($sort == 'name') { ?>
            <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?> sort_order"><?php echo $column_name; ?></a>
            <?php } else { ?>
            <a href="<?php echo $sort_name; ?>" class="sort_order"><?php echo $column_name; ?></a>
            <?php } ?></td>
          <td class="text-left"><?php if ($sort == 'c.status') { ?>
            <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?> sort_order"><?php echo $column_status; ?></a>
            <?php } else { ?>
            <a href="<?php echo $sort_status; ?>" class="sort_order"><?php echo $column_status; ?></a>
            <?php } ?></td>
          <td class="text-left"><?php echo $column_customer_groups; ?></td>
          <td class="text-right"><?php echo $column_action; ?></td>
        </tr>
      </thead>
      <tbody>
        <?php if ($categories) { ?>
        <?php foreach ($categories as $category) { ?>
        <tr id="category_<?php echo $category['category_id']; ?>" class="categories">
          <td class="text-center"><?php if (in_array($category['category_id'], $selected)) { ?>
            <input type="checkbox" name="cselected[]" value="<?php echo $category['category_id']; ?>" checked="checked" />
            <?php } else { ?>
            <input type="checkbox" name="cselected[]" value="<?php echo $category['category_id']; ?>" />
            <?php } ?></td>
          <td class="text-center"><?php if ($category['image']) { ?>
            <img src="<?php echo $category['image']; ?>" alt="<?php echo $category['name']; ?>" class="img-thumbnail" />
            <?php } else { ?>
            <span class="img-thumbnail list"><i class="fa fa-camera fa-2x"></i></span>
            <?php } ?></td>
          <td class="text-left"><?php echo $category['name']; ?></td>
          <td class="text-left"><?php echo $category['status']; ?></td>
          <td class="text-left">
            <div class="form-group">
              <div class="col-sm-12">
                <div class="customer_groups" id="category_customer_groups_<?php echo $category['category_id']; ?>">
                  <?php foreach ($customer_groups as $customer_group) { ?>
                  <div class="checkbox">
                    <label>
                      <input type="checkbox" name="customer_group_ids[]" value="<?php echo $customer_group['customer_group_id']; ?>" <?php if (in_array($customer_group['customer_group_id'], $category['customer_groups'])) { ?>checked="checked"<?php } ?> />
                      <?php echo $customer_group['name']; ?>
                    </label>
                  </div>
                  <?php } ?>
                </div>
              </div>
            </div>
          </td>
          <td class="text-right"><button type="button" class="btn btn-primary category_update"><i class="fa fa-pencil" data-class="fa fa-pencil"></i> <?php echo $button_update; ?></button></td>
        </tr>
        <?php } ?>
        <?php } else { ?>
        <tr>
          <td class="text-center" colspan="8"><?php echo $text_no_results; ?></td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
  <div class="row">
    <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
    <div class="col-sm-6 text-right"><?php echo $results; ?></div>
  </div>
</div>
<?php $p_active = $rule->all_product ? 'disabled' : ''; ?>
<div class="form-horizontal <?php echo $rule->type == 'N' ? 'hide' : ''; ?>" id="product-percentage">
  <div class="form-group">
    <label class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
      <h4 class="bold">Include</h4>
    </label>
  </div>
  <div class="form-group">
    <label class="col-lg-1-harf col-md-2 col-sm-2 col-xs-12 form-label">All products</label>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
      <div class="btn-group">
        <button type="button" class="btn btn-sm btn-70 <?php echo $rule->all_product ? 'btn-primary' : ''; ?>" id="btn-all-product-yes" onclick="toggleAllProduct(1)">Yes</button>
        <button type="button" class="btn btn-sm btn-70 <?php echo $rule->all_product ? '' : 'btn-primary'; ?>" id="btn-all-product-no" onclick="toggleAllProduct(0)">No</button>
      </div>
    </div>
  </div>
  <!-- SKU -->
  <div class="form-group">
    <label class="col-lg-1-harf col-md-2 col-sm-2 col-xs-12 form-label">SKU</label>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-8">
      <input type="text" class="form-control input-sm" id="include-product" placeholder="Specify the SKU to include in this discount rule." <?php echo $p_active; ?> />
    </div>
    <div class="col-lg-1-harf col-md-2 col-sm-2 col-xs-4 padding-5">
      <input type="file" class="hide" name="skuFile" id="sku-file" accept=".xlsx" />
      <button type="button" class="btn btn-xs btn-success btn-white btn-block" id="btn-import-product" onclick="getSkuFile()" <?php echo $p_active; ?>><i class="fa fa-plus"></i> Import Excel</button>
    </div>
    <div class="col-lg-7-harf col-lg-offset-1-harf col-md-8 col-md-offset-2 col-sm-8 col-sm-offset-2 col-xs-12 <?php echo $rule->all_product ? 'hide' : ''; ?>" id="product-table" style="max-height:300px; overflow-y:auto; margin-top:10px;">
      <table class="table table-striped table-hover table-narrow border-1" style="min-width:480px; margin-bottom:0px;">
        <thead>
          <tr>
            <th class="fix-width-40 text-center">
              <label>
                <input type="checkbox" class="ace" id="chk-all-include" onchange="toggleAllInclude(this)" />
                <span class="lbl"></span>
              </label>
            </th>
            <th class="fix-width-40 text-center">No.</th>
            <th class="fix-width-100">SKU</th>
            <th class="min-width-250">Description</th>
            <th class="fix-width-50 text-right"><button type="button" class="btn btn-minier btn-danger" onclick="removeIncludeChecked()"><i class="fa fa-trash"></i></button></th>
          </tr>
        </thead>
        <tbody id="include-product-table">
          <?php if (!empty($products)) : ?>
            <?php $no = 1; ?>
            <?php foreach ($products as $rs) : ?>
              <tr id="include-row-<?php echo $rs->product_id; ?>">
                <td class="middle text-center">
                  <label>
                    <input type="checkbox" class="ace chk-include" data-id="<?php echo $rs->product_id; ?>" data-code="<?php echo $rs->code; ?>" data-sellprice="<?php echo $rs->sell_price; ?>" />
                    <span class="lbl"></span>
                  </label>
                </td>
                <td class="middle text-center in-no"><?php echo $no; ?></td>
                <td class="middle"><?php echo $rs->code; ?></td>
                <td class="middle"><?php echo $rs->name; ?></td>
                <td class="middle text-center">
                  <a href="javascript:void(0)" class="red" onclick="removeIncludeItem('<?php echo $rs->product_id; ?>')" title="Remove this row"><i class="fa fa-times fa-lg"></i></a>
                </td>
              </tr>
              <?php $no++; ?>
            <?php endforeach; ?>
          <?php else : ?>
            <tr id="no-product-row">
              <td colspan="5" class="text-center">No Include SKU</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Model -->
  <div class="form-group">
    <label class="col-lg-1-harf col-md-2 col-sm-2 col-xs-12 form-label">Model</label>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
      <select class="width-100 select2" id="product-model" multiple="multiple" data-placeholder="Please select" <?php echo $p_active; ?>>
        <option value="">&nbsp;</option>
        <?php echo selectMultipleModels($pdModels); ?>
      </select>
    </div>
  </div>

  <!-- type -->
  <div class="form-group">
    <label class="col-lg-1-harf col-md-2 col-sm-2 col-xs-12 form-label">Type</label>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
      <select class="width-100 select2" id="product-type" multiple="multiple" <?php echo $p_active; ?>>
        <option value="">&nbsp;</option>
        <?php echo selectMultipleTypes($pdTypes); ?>
      </select>
    </div>
  </div>

  <!-- category -->
  <div class="form-group">
    <label class="col-lg-1-harf col-md-2 col-sm-2 col-xs-12 form-label">Category</label>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
      <select class="width-100 select2" id="product-category" multiple="multiple" <?php echo $p_active; ?>>
        <option value="">&nbsp;</option>
        <?php echo selectMultipleCategory($pdCategories); ?>
      </select>
    </div>
  </div>

  <!-- brand -->
  <div class="form-group">
    <label class="col-lg-1-harf col-md-2 col-sm-2 col-xs-12 form-label">Brand</label>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
      <select class="width-100 select2" id="product-brand" multiple="multiple" <?php echo $p_active; ?>>
        <option value="">&nbsp;</option>
        <?php echo selectMultipleBrands($pdBrands); ?>
      </select>
    </div>
  </div>

  <div class="divider"></div>

  <div class="form-group">
    <label class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
      <h4 class="bold">Exclude</h4>
    </label>
  </div>
  <div class="form-group">
    <label class="col-lg-1-harf col-md-2 col-sm-2 col-xs-12 form-label">SKU</label>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
      <input type="text" class="form-control input-sm" id="exclude-product" placeholder="Specify the SKU to exclude from this discount rule." />
    </div>
  </div>
  <div class="form-group">
    <label class="col-lg-1-harf col-md-2 col-sm-2 col-xs-12 form-label">&nbsp;</label>
    <div class="col-lg-7-harf col-md-8 col-sm-8 col-xs-12" style="max-height:300px; overflow-y:auto;">
      <table class="table table-striped table-hover table-narrow border-1" style="min-width:480px;">
        <thead>
          <tr>
            <th class="fix-width-40 text-center">
              <label>
                <input type="checkbox" class="ace" id="chk-all-exclude" onchange="toggleAllExclude(this)" />
                <span class="lbl"></span>
              </label>
            </th>
            <th class="fix-width-40 text-center">No.</th>
            <th class="fix-width-100">SKU</th>
            <th class="min-width-250">Description</th>
            <th class="fix-width-50 text-right"><button type="button" class="btn btn-minier btn-danger" onclick="removeExcludeChecked()"><i class="fa fa-trash"></i></button></th>
          </tr>
        </thead>
        <tbody id="exclude-product-table">
          <?php if (!empty($pdExclude)) : ?>
            <?php $no = 1; ?>
            <?php foreach ($pdExclude as $rs) : ?>
              <tr id="exclude-row-<?php echo $rs->product_id; ?>">
                <td class="middle text-center">
                  <label>
                    <input type="checkbox" class="ace chk-exclude" data-id="<?php echo $rs->product_id; ?>" data-code="<?php echo $rs->code; ?>" />
                    <span class="lbl"></span>
                  </label>
                </td>
                <td class="middle text-center ex-no"><?php echo $no; ?></td>
                <td class="middle"><?php echo $rs->code; ?></td>
                <td class="middle"><?php echo $rs->name; ?></td>
                <td class="middle text-center">
                  <a href="javascript:void(0)" class="red" onclick="removeExcludeItem('<?php echo $rs->product_id; ?>')" title="Remove this row"><i class="fa fa-times fa-lg"></i></a>
                </td>
              </tr>
              <?php $no++; ?>
            <?php endforeach; ?>
          <?php else : ?>
            <tr id="no-exclude-row">
              <td colspan="5" class="text-center">No Exclude SKU</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<div class="form-horizontal <?php echo $rule->type == 'N' ? '' : 'hide'; ?>" id="product-net-price">
  <div class="form-group">
    <label class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
      <h4 class="bold">Include</h4>
    </label>
  </div>
  <div class="form-group">
    <label class="col-lg-1-harf col-md-1 col-sm-1 col-xs-12 form-label">SKU</label>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-8">
      <input type="text" class="form-control input-sm" id="sku-net-price" placeholder="Specify the SKU to include in this discount rule." />
    </div>
    <div class="col-lg-1-harf col-md-2 col-sm-2 col-xs-4 padding-5">
      <input type="file" class="hide" id="net-price-file" accept=".xlsx" />
      <button type="button" class="btn btn-xs btn-success btn-white btn-block" id="btn-import-net-price" onclick="getNetPriceFile()"><i class="fa fa-plus"></i> Import Excel</button>
    </div>    
    <div class="col-lg-7-harf col-lg-offset-1-harf col-md-11 col-md-offset-1 col-sm-11 col-sm-offset-1 col-xs-12" style="max-height:300px; overflow-y:auto;">
      <table class="table table-striped table-hover table-narrow border-1" style="min-width:640px; margin-bottom:0px; margin-top:10px;">
        <thead>
          <tr>
            <th class="fix-width-40 text-center">
              <label>
                <input type="checkbox" class="ace" id="chk-all-net-price" onchange="toggleAllNetPrice(this)" />
                <span class="lbl"></span>
              </label>
            </th>
            <th class="fix-width-40 text-center">No.</th>
            <th class="fix-width-100">SKU</th>
            <th class="min-width-250">Description</th>
            <th class="fix-width-80 text-right">Std. Price</th>
            <th class="fix-width-80 text-right">Net Price</th>
            <th class="fix-width-50 text-right">
              <button type="button" class="btn btn-minier btn-danger" onclick="removeNetPriceChecked()"><i class="fa fa-trash"></i></button>
            </th>
          </tr>
        </thead>
        <tbody id="net-price-table">
          <?php if ($rule->type == 'N') : ?>
            <?php if (!empty($products)) : ?>
              <?php $no = 1; ?>
              <?php foreach ($products as $rs) : ?>
                <tr id="net-price-row-<?php echo $rs->product_id; ?>">
                  <td class="middle text-center">
                    <label>
                      <input type="checkbox" class="ace chk-net-price" data-id="<?php echo $rs->product_id; ?>" />
                      <span class="lbl"></span>
                    </label>
                  </td>
                  <td class="middle text-center np-no"><?php echo $no; ?></td>
                  <td class="middle"><?php echo $rs->code; ?></td>
                  <td class="middle"><?php echo $rs->name; ?></td>
                  <td class="middle text-right"><?php echo number($rs->price, 2); ?></td>
                  <td class="middle text-right">
                    <input type="number" class="form-control input-xs text-right net-price" data-id="<?php echo $rs->product_id; ?>" data-code="<?php echo $rs->code; ?>" value="<?php echo $rs->sell_price; ?>" />
                  </td>
                  <td class="middle text-center">
                    <a href="javascript:void(0)" class="red" onclick="removeNetPriceItem('<?php echo $rs->product_id; ?>')" title="Remove this row"><i class="fa fa-times fa-lg"></i></a>
                  </td>
                </tr>
                <?php $no++; ?>
              <?php endforeach; ?>
            <?php else : ?>
              <tr id="no-net-price-row">
                <td colspan="7" class="text-center">No Net Price SKU</td>
              </tr>
            <?php endif; ?>
          <?php else : ?>
            <tr id="no-net-price-row">
              <td colspan="7" class="text-center">No Net Price SKU</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div><!-- form-horizontal -->


<script>
  $('#product-model').select2({
    placeholder: "Please select",
    closeOnSelect: false,
    allowClear: true,
  });

  $('#product-type').select2({
    placeholder: "Please select",
    closeOnSelect: false,
    allowClear: true,
  });

  $('#product-category').select2({
    placeholder: "Please select",
    closeOnSelect: false,
    allowClear: true,
  });

  $('#product-brand').select2({
    placeholder: "Please select",
    closeOnSelect: false,
    allowClear: true,
  });
</script>
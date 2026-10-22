<div class="form-horizontal" id="product-percentage">
  <div class="form-group">
    <label class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
      <h4 class="bold">Include</h4>
    </label>
  </div>
  <div class="form-group">
    <label class="col-lg-1-harf col-md-2 col-sm-2 col-xs-12 form-label">All products</label>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
      <div class="btn-group">
        <button type="button" class="btn btn-sm btn-70" id="btn-all-product-yes" onclick="toggleAllProduct(1)">Yes</button>
        <button type="button" class="btn btn-sm btn-primary btn-70" id="btn-all-product-no" onclick="toggleAllProduct(0)">No</button>
      </div>
    </div>
  </div>
  <!-- SKU -->
  <div class="form-group">
    <label class="col-lg-1-harf col-md-2 col-sm-2 col-xs-12 form-label">SKU</label>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-8">
      <input type="text" class="form-control input-sm" id="include-product" placeholder="Specify the SKU to include in this discount rule." />
    </div>
    <div class="col-lg-1-harf col-md-2 col-sm-2 col-xs-4 padding-5">
      <input type="file" class="hide" name="skuFile" id="sku-file" accept=".xlsx" />
      <button type="button" class="btn btn-xs btn-success btn-white btn-block" id="btn-import-product" onclick="getSkuFile()"><i class="fa fa-plus"></i> Import Excel</button>
    </div>
    <div class="col-lg-7-harf col-lg-offset-1-harf col-md-8 col-md-offset-2 col-sm-8 col-sm-offset-2 col-xs-12" id="product-table" style="max-height:300px; overflow-y:auto; margin-top:10px;">
      <table class="table table-striped table-hover table-narrow border-1" style="margin-bottom:0px;">
        <thead>
          <tr>
            <th class="fix-width-30 text-center">
              <label>
                <input type="checkbox" class="ace" id="chk-all-include" onchange="toggleAllInclude(this)" />
                <span class="lbl"></span>
              </label>
            </th>
            <th class="fix-width-30 text-center">No.</th>
            <th class="fix-width-100">SKU</th>
            <th class="min-width-250">Description</th>
            <th class="fix-width-50 text-right"><button type="button" class="btn btn-minier btn-danger" onclick="removeIncludeChecked()"><i class="fa fa-trash"></i></button></th>
          </tr>
        </thead>
        <tbody id="include-product-table">
          <tr id="no-product-row">
            <td colspan="5" class="text-center">No Include SKU</td>
          </tr>
        </tbody>
      </table>
    </div>

  </div>

  <!-- Model -->
  <div class="form-group">
    <label class="col-lg-1-harf col-md-2 col-sm-2 col-xs-12 form-label">Model</label>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
      <select class="width-100 select2" id="product-model" multiple="multiple" data-placeholder="Please select">
        <option value="">&nbsp;</option>
        <?php echo selectMultipleModels(); ?>
      </select>
    </div>
  </div>

  <!-- type -->
  <div class="form-group">
    <label class="col-lg-1-harf col-md-2 col-sm-2 col-xs-12 form-label">Type</label>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
      <select class="width-100 select2" id="product-type" multiple="multiple">
        <option value="">&nbsp;</option>
        <?php echo selectMultipleTypes(); ?>
      </select>
    </div>
  </div>

  <!-- category -->
  <div class="form-group">
    <label class="col-lg-1-harf col-md-2 col-sm-2 col-xs-12 form-label">Category</label>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
      <select class="width-100 select2" id="product-category" multiple="multiple">
        <option value="">&nbsp;</option>
        <?php echo selectMultipleCategory(); ?>
      </select>
    </div>
  </div>

  <!-- brand -->
  <div class="form-group">
    <label class="col-lg-1-harf col-md-2 col-sm-2 col-xs-12 form-label">Brand</label>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
      <select class="width-100 select2" id="product-brand" multiple="multiple">
        <option value="">&nbsp;</option>
        <?php echo selectMultipleBrands(); ?>
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
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12" style="max-height:300px; overflow-y:auto;">
      <table class="table table-striped table-hover table-narrow border-1">
        <thead>
          <tr>
            <th class="fix-width-20 text-center">
              <label>
                <input type="checkbox" class="ace" id="chk-all-exclude" onchange="toggleAllExclude(this)" />
                <span class="lbl"></span>
              </label>
            </th>
            <th class="fix-width-30 text-center">No.</th>
            <th class="fix-width-100">SKU</th>
            <th class="min-width-250">Description</th>
            <th class="fix-width-50 text-right"><button type="button" class="btn btn-minier btn-danger" onclick="removeExcludeChecked()"><i class="fa fa-trash"></i></button></th>
          </tr>
        </thead>
        <tbody id="exclude-product-table">
          <tr id="no-exclude-row">
            <td colspan="5" class="text-center">No Exclude SKU</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div> <!-- form-horizontal -->


<div class="form-horizontal hide" id="product-net-price">
  <div class="form-group">
    <label class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
      <h4 class="bold">Include</h4>
    </label>
  </div>
  <div class="form-group">
    <label class="col-lg-1-harf col-md-2 col-sm-2 col-xs-12 form-label">SKU</label>
    <div class="col-lg-7 col-md-7 col-sm-7 col-xs-8">
      <input type="text" class="form-control input-sm" id="sku-net-price" placeholder="Specify the SKU to include in this discount rule." />
    </div>
    <div class="col-lg-1-harf col-md-2 col-sm-2 col-xs-4 padding-5">
      <input type="file" class="hide" id="net-price-file" accept=".xlsx" />
      <button type="button" class="btn btn-xs btn-success btn-white btn-block" id="btn-import-net-price" onclick="getNetPriceFile()"><i class="fa fa-plus"></i> Import Excel</button>
    </div>
    <div class="col-lg-7 col-lg-offset-1-harf col-md-9 col-md-offset-2 col-sm-9 col-sm-offset-2 col-xs-12" style="max-height:300px; overflow-y:auto; margin-top:10px;">
      <table class="table table-striped table-hover table-narrow border-1" style="margin-bottom:0px; min-width:600px;">
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
            <th class="fix-width-100 text-right">Std. Price</th>
            <th class="fix-width-100 text-right">Net Price</th>
            <th class="fix-width-30 text-right">
              <button type="button" class="btn btn-minier btn-danger" onclick="removeNetPriceChecked()"><i class="fa fa-trash"></i></button>
            </th>
          </tr>
        </thead>
        <tbody id="net-price-table">
          <tr id="no-net-price-row">
            <td colspan="7" class="text-center">No Net Price SKU</td>
          </tr>
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
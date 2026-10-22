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
        <button type="button" class="btn btn-sm btn-70 <?php echo $rule->all_product ? 'btn-primary' : ''; ?>" disabled>Yes</button>
        <button type="button" class="btn btn-sm btn-70 <?php echo $rule->all_product ? '' : 'btn-primary'; ?>" disabled>No</button>
      </div>
    </div>
  </div>
  <!-- SKU -->
  <div class="form-group">
    <label class="col-lg-1-harf col-md-2 col-sm-2 col-xs-12 form-label">SKU</label>    
    <div class="col-lg-6 col-md-8 col-sm-8 col-xs-12" style="max-height:300px; overflow-y:auto; margin-top:10px;">
      <table class="table table-striped table-hover table-narrow border-1" style="min-width:450px; margin-bottom:0px;">
        <thead>
          <tr>            
            <th class="fix-width-40 text-center">#</th>
            <th class="fix-width-150">SKU</th>
            <th class="min-width-250">Description</th>            
          </tr>
        </thead>
        <tbody id="include-product-table">
          <?php if (!empty($products)) : ?>
            <?php $no = 1; ?>
            <?php foreach ($products as $rs) : ?>
              <tr>                
                <td class="middle text-center in-no"><?php echo $no; ?></td>
                <td class="middle"><?php echo $rs->code; ?></td>
                <td class="middle"><?php echo $rs->name; ?></td>               
              </tr>
              <?php $no++; ?>
            <?php endforeach; ?>
          <?php else : ?>
            <tr id="no-product-row">
              <td colspan="3" class="text-center">No Include SKU</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Model -->
  <div class="form-group">
    <label class="col-lg-1-harf col-md-2 col-sm-2 col-xs-12 form-label">Model</label>
    <div class="col-lg-6 col-md-8 col-sm-8 col-xs-12">
      <select class="width-100 select2" id="product-model" multiple="multiple" data-placeholder="No model selected" disabled>
        <option value="">&nbsp;</option>
        <?php echo selectMultipleModels($pdModels); ?>
      </select>
    </div>
  </div>

  <!-- type -->
  <div class="form-group">
    <label class="col-lg-1-harf col-md-2 col-sm-2 col-xs-12 form-label">Type</label>
    <div class="col-lg-6 col-md-8 col-sm-8 col-xs-12">
      <select class="width-100 select2" id="product-type" multiple="multiple" data-placeholder="No type selected" disabled>
        <option value="">&nbsp;</option>
        <?php echo selectMultipleTypes($pdTypes); ?>
      </select>
    </div>
  </div>

  <!-- category -->
  <div class="form-group">
    <label class="col-lg-1-harf col-md-2 col-sm-2 col-xs-12 form-label">Category</label>
    <div class="col-lg-6 col-md-8 col-sm-8 col-xs-12">
      <select class="width-100 select2" id="product-category" multiple="multiple" data-placeholder="No category selected" disabled>
        <option value="">&nbsp;</option>
        <?php echo selectMultipleCategory($pdCategories); ?>
      </select>
    </div>
  </div>

  <!-- brand -->
  <div class="form-group">
    <label class="col-lg-1-harf col-md-2 col-sm-2 col-xs-12 form-label">Brand</label>
    <div class="col-lg-6 col-md-8 col-sm-8 col-xs-12">
      <select class="width-100 select2" id="product-brand" multiple="multiple" data-placeholder="No brand selected" disabled>
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
    <div class="col-lg-6 col-md-8 col-sm-8 col-xs-12" style="max-height:300px; overflow-y:auto;">
      <table class="table table-striped table-hover table-narrow border-1" style="min-width:450px;">
        <thead>
          <tr>            
            <th class="fix-width-40 text-center">#</th>
            <th class="fix-width-150">SKU</th>
            <th class="min-width-250">Description</th>            
          </tr>
        </thead>
        <tbody id="exclude-product-table">
          <?php if (!empty($pdExclude)) : ?>
            <?php $no = 1; ?>
            <?php foreach ($pdExclude as $rs) : ?>
              <tr>                
                <td class="middle text-center ex-no"><?php echo $no; ?></td>
                <td class="middle"><?php echo $rs->code; ?></td>
                <td class="middle"><?php echo $rs->name; ?></td>                
              </tr>
              <?php $no++; ?>
            <?php endforeach; ?>
          <?php else : ?>
            <tr id="no-exclude-row">
              <td colspan="3" class="text-center">No Exclude SKU</td>
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
      <h4 class="bold">Price override</h4>
    </label>
  </div>
  <div class="form-group">
    <label class="col-lg-1 col-md-1 col-sm-1 col-xs-12 form-label">SKU</label>    
    <div class="col-lg-8 col-md-11 col-sm-11 col-xs-12" style="max-height:300px; overflow-y:auto;">
      <table class="table table-striped table-hover table-narrow border-1" style="min-width:550px; margin-bottom:0px;">
        <thead>
          <tr>            
            <th class="fix-width-40 text-center">#</th>
            <th class="fix-width-100">SKU</th>
            <th class="min-width-250">Description</th>
            <th class="fix-width-80 text-right">Std. Price</th>
            <th class="fix-width-80 text-right">Net Price</th>            
          </tr>
        </thead>
        <tbody id="net-price-table">
          <?php if ($rule->type == 'N') : ?>
            <?php if (!empty($products)) : ?>
              <?php $no = 1; ?>
              <?php foreach ($products as $rs) : ?>
                <tr>                  
                  <td class="middle text-center np-no"><?php echo $no; ?></td>
                  <td class="middle"><?php echo $rs->code; ?></td>
                  <td class="middle"><?php echo $rs->name; ?></td>
                  <td class="middle text-right"><?php echo number($rs->price, 2); ?></td>
                  <td class="middle text-right"><?php echo number($rs->sell_price, 2); ?></td>                   
                </tr>
                <?php $no++; ?>
              <?php endforeach; ?>
            <?php else : ?>
              <tr id="no-net-price-row">
                <td colspan="5" class="text-center">No Net Price SKU</td>
              </tr>
            <?php endif; ?>
          <?php else : ?>
            <tr id="no-net-price-row">
              <td colspan="5" class="text-center">No Net Price SKU</td>
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
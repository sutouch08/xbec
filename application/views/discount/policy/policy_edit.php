<?php $this->load->view('include/header'); ?>
<div class="row">
  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 padding-5">
    <h3 class="title"><?php echo $this->title; ?></h3>
  </div>
  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 padding-5 text-right">
    <button type="button" class="btn btn-white btn-default" onclick="goBack()"><i class="fa fa-arrow-left"></i> Back</button>
    <?php if ($this->pm->can_add or $this->pm->can_edit) : ?>
      <button type="button" class="btn btn-white btn-danger" onclick="removeCheckedRules()"><i class="fa fa-times"></i> Remove Rules</button>
      <button type="button" class="btn btn-white btn-primary" onclick="getActiveRuleList()"><i class="fa fa-plus"></i> &nbsp; Add Rules</button>
    <?php endif; ?>
  </div>
</div><!-- End Row -->
<hr />
<div class="row">
  <div class="col-lg-1-harf col-md-1-harf col-sm-2 col-xs-4 padding-5">
    <label>Document No</label>
    <input type="text" class="form-control input-sm text-center" id="code" value="<?php echo $policy->code; ?>" disabled />
  </div>
  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-8 padding-5">
    <label>Description</label>
    <input type="text" class="form-control input-sm r" maxlength="100" id="name" value="<?php echo $policy->name; ?>" autocomplete="off" />
  </div>
  <div class="col-lg-1 col-md-1-harf col-sm-1-harf col-xs-4 padding-5">
    <label>Start date</label>
    <input type="text" class="form-control input-sm text-center r" id="start-date" value="<?php echo thai_date($policy->start_date); ?>" autocomplete="off" />
  </div>
  <div class="col-lg-1 col-md-1-harf col-sm-1-harf col-xs-4 padding-5">
    <label>End date</label>
    <input type="text" class="form-control input-sm text-center r" id="end-date" value="<?php echo thai_date($policy->end_date); ?>" autocomplete="off" />
  </div>
  <?php if ($can_approve) : ?>
    <div class="col-lg-1 col-md-1-harf col-sm-1-harf col-xs-3 padding-5">
      <label class="display-block" style="margin-left:4px;">Status</label>
      <label style="height: 22px; padding-top:4px;">
        <input class="ace ace-switch ace-switch-6" type="checkbox" id="active" value="1" <?php echo is_checked(1, $policy->active); ?> />
        <span class="lbl"></span>
      </label>
    </div>
  <?php else : ?>
    <div class="col-lg-1 col-md-1-harf col-sm-1-harf col-xs-3 padding-5">
      <label>Status</label>
      <input type="text" class="form-control input-sm text-center" value="<?php echo $policy->active ? 'Active' : 'Inactive'; ?>" disabled />
    </div>
  <?php endif; ?>
  <div class="col-lg-1 col-md-1 col-sm-1 col-xs-4 padding-5">
    <label class="display-block not-show">buton</label>
    <button type="button" class="btn btn-white btn-success btn-block" style="height:31px; padding:4px;" onclick="update()">Save</button>
  </div>
  
  <input type="hidden" id="id-policy" value="<?php echo $policy->id; ?>" />

</div><!-- row -->
<hr class="margin-top-15">


<?php $this->load->view('discount/policy/policy_rule_list'); ?>

<div class="modal fade" id="rule-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="width:800px; max-width:90vw;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">เลือกเงื่อนไขส่วนลด</h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-5" style="max-height:400px; overflow:auto;">
            <table class="table table-striped table-bordered table-narrow border-1" style="min-width:500px;">
              <thead>
                <tr>
                  <th class="fix-width-40 text-center">
                    <label>
                      <input type="checkbox" class="ace" id="chk-all" onchange="toggleCheckRuleAll()" />
                      <span class="lbl"></span>
                    </label>
                  </th>
                  <th class="fix-width-100">Rule No.</th>
                  <th class="fix-width-100">Method</th>
                  <th class="min-width-250">Description</th>
                </tr>
              </thead>
              <tbody id="rule-table">
                
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-white btn-default btn-100" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-white btn-primary btn-100" id="btn-add-rule" onclick="addRule()"><i class="fa fa-plus"></i> Add Rules</button>
      </div>
    </div>
  </div>
</div>

<script id="rule-template" type="text/x-handlebars-template">
  {{#each this}}
    {{#if nodata}}
      <tr>
        <td colspan="4" class="text-center">--- No Discount Rule ---</td>
      </tr>
    {{else}}
      <tr>
        <td class="text-center">
          <input type="checkbox" class="ace chk-rule" value="{{id}}" />
          <span class="lbl"></span>
        </td>
        <td>{{code}}</td>
        <td>{{type}}</td>
        <td>{{name}}</td>
      </tr>          
    {{/if}}
  {{/each}}
</script>

<script src="<?php echo base_url(); ?>scripts/discount/policy/policy.js"></script>
<script src="<?php echo base_url(); ?>scripts/discount/policy/policy_add.js"></script>

<?php $this->load->view('include/footer'); ?>
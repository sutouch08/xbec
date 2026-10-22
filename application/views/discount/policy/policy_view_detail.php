<?php $this->load->view('include/header'); ?>
<div class="row">
  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 padding-5">
    <h3 class="title"><?php echo $this->title; ?></h3>
  </div>
  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 padding-5 text-right">
    <?php if(! isset($_GET['nomenu'])) : ?>
      <button type="button" class="btn btn-white btn-default" onclick="goBack()"><i class="fa fa-arrow-left"></i> Back</button>
    <?php endif; ?>    
    <?php if ($this->pm->can_edit) : ?>
      <button type="button" class="btn btn-white btn-warning" onclick="edit(<?php echo $policy->id; ?>)"><i class="fa fa-pencil"></i>&nbsp; Edit</button>
    <?php endif; ?>
  </div>
</div><!-- End Row -->
<hr />
<div class="row">
  <div class="col-lg-1-harf col-md-1-harf col-sm-2 col-xs-4 padding-5">
    <label>Document No</label>
    <input type="text" class="form-control input-sm text-center" value="<?php echo $policy->code; ?>" readonly />
  </div>
  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-8 padding-5">
    <label>Description</label>
    <input type="text" class="form-control input-sm r" maxlength="100" value="<?php echo $policy->name; ?>" autocomplete="off" readonly />
  </div>
  <div class="col-lg-1 col-md-1-harf col-sm-1-harf col-xs-4 padding-5">
    <label>Start date</label>
    <input type="text" class="form-control input-sm text-center r" value="<?php echo thai_date($policy->start_date); ?>" autocomplete="off" readonly />
  </div>
  <div class="col-lg-1 col-md-1-harf col-sm-1-harf col-xs-4 padding-5">
    <label>End date</label>
    <input type="text" class="form-control input-sm text-center r" value="<?php echo thai_date($policy->end_date); ?>" autocomplete="off" readonly />
  </div>
  <div class="col-lg-1 col-md-1-harf col-sm-1-harf col-xs-3 padding-5">
    <label>Status</label>
    <input type="text" class="form-control input-sm text-center" value="<?php echo $policy->active ? 'Active' : 'Inactive'; ?>" readonly />
  </div>

  <input type="hidden" id="id-policy" value="<?php echo $policy->id; ?>" />

</div><!-- row -->
<hr class="margin-top-15">

<div class="row">
  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-5 table-responsive">
    <table class="table table-striped table-bordered table-narrow border-1" style="min-width:1240px;">
      <thead>
        <tr>
          <th colspan="6" class="middle text-center">Details</th>
          <th colspan="6" class="middle text-center">Conditions</th>
        </tr>
        <tr class="font-size-10">
          <th class="fix-width-40 middle text-center">#</th>
          <th class="fix-width-100 middle text-center">Code</th>
          <th class="min-width-200 middle text-center">Description</th>
          <th class="fix-width-100 middle text-center">Methods</th>
          <th class="fix-width-120 middle text-center">Discount</th>
          <th class="fix-width-100 middle text-center">Premium Qty.</th>
          <th class="fix-width-80 text-center">Customer</th>
          <th class="fix-width-80 text-center">Product</th>
          <th class="fix-width-80 text-center">Channels</th>
          <th class="fix-width-80 text-center">Payment</th>
          <th class="fix-width-80 text-center">Min Qty.</th>
          <th class="fix-width-100 text-center">Min Amount.</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($rules)) : ?>
          <?php $no = 1; ?>
          <?php foreach ($rules as $rs) : ?>
            <tr class="font-size-12" id="row_<?php echo $rs->id; ?>">
              <td class="middle text-center"><?php echo $no; ?></td>
              <td class="middle text-center">
                <a style="color:inherit" href="javascript:viewRuleDetail(<?php echo $rs->id; ?>)"><?php echo $rs->code; ?></a>
              </td>
              <td class="middle"><?php echo $rs->name; ?></td>
              <td class="middle text-center"><?php echo ($rs->type == 'N' ? 'Net Price' : ($rs->type == 'P' ? 'Percentage' : 'Premium')); ?></td>
              <td class="middle text-center"><?php echo discount_label($rs->type, $rs->price, $rs->disc1, $rs->disc2, $rs->disc3, $rs->disc4, $rs->disc5); ?></td>
              <td class="middle text-center"><?php echo $rs->freeQty; ?></td>
              <td class="middle text-center"><?php echo ($rs->all_customer == 1 ? 'ทั้งหมด' : 'กำหนดค่า'); ?></td>
              <td class="middle text-center"><?php echo ($rs->all_product == 1 ? 'ทั้งหมด' : 'กำหนดค่า'); ?></td>
              <td class="middle text-center"><?php echo ($rs->all_channels == 1 ? 'ทั้งหมด' : 'กำหนดค่า'); ?></td>
              <td class="middle text-center"><?php echo ($rs->all_payment == 1 ? 'ทั้งหมด' : 'กำหนดค่า'); ?></td>
              <td class="middle text-center"><?php echo (empty($rs->minQty) ? "No" : number($rs->minQty)); ?></td>
              <td class="middle text-center"><?php echo (empty($rs->minAmount) ? "No" : number($rs->minAmount, 2)); ?></td>
            </tr>
            <?php $no++; ?>
          <?php endforeach; ?>
        <?php else : ?>
          <tr>
            <td id="no-disc-row" colspan="13" class="text-center">--- No Discount Rule ---</td>
          </tr>

        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<div class="row" style="position:fixed; bottom:60px;">
  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-5">
    <p class="log-text">Created by : <?php echo $policy->user; ?> &nbsp;&nbsp;&nbsp;&nbsp; Created at : <?php echo thai_date($policy->date_add, TRUE); ?></p>
    <?php if(! empty($policy->update_user)) : ?>
      <p class="log-text">Updated by : <?php echo $policy->update_user; ?> &nbsp;&nbsp;&nbsp;&nbsp; Updated at : <?php echo thai_date($policy->date_upd, TRUE); ?></p>
    <?php endif; ?>
    <?php if(! empty($policy->active_by)) : ?>
      <p class="log-text">Activated by : <?php echo $policy->active_by; ?> &nbsp;&nbsp;&nbsp;&nbsp; Activated at : <?php echo thai_date($policy->active_date, TRUE); ?></p>
    <?php endif; ?>
  </div>
</div>

<script src="<?php echo base_url(); ?>scripts/discount/policy/policy.js"></script>
<script src="<?php echo base_url(); ?>scripts/discount/policy/policy_add.js"></script>

<?php $this->load->view('include/footer'); ?>
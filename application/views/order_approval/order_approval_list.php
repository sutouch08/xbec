<?php $this->load->view('include/header'); ?>
<div class="row">
  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-5">
    <h3 class="title"><?php echo $this->title; ?></h3>
  </div>
</div><!-- End Row -->
<hr />
<form id="searchForm" method="post" action="<?php echo current_url(); ?>">
  <div class="row">
    <div class="col-lg-1-harf col-md-2 col-sm-2 col-xs-6 padding-5">
      <label>Web No.</label>
      <input type="text" class="form-control input-sm search-box" name="code" value="<?php echo $code; ?>" />
    </div>

    <div class="col-lg-1-harf col-md-2 col-sm-2 col-xs-6 padding-5">
      <label>Customer</label>
      <input type="text" class="form-control input-sm search-box" name="customer" value="<?php echo $customer; ?>" />
    </div>

    <div class="col-lg-1-harf col-md-2 col-sm-2 col-xs-6 padding-5">
      <label>User</label>
      <select class="form-control input-sm" name="user_id" id="user_id">
        <option value="all">ทั้งหมด</option>
        <?php echo select_user($user_id); ?>
      </select>
    </div>

    <div class="col-lg-1-harf col-md-2 col-sm-2 col-xs-6 padding-5">
      <label>Channels</label>
      <select class="form-control input-sm" name="channels" id="channels">
        <option value="all">ทั้งหมด</option>
        <?php echo select_channels($channels); ?>
      </select>
    </div>
    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-6 padding-5">
      <label>Projects</label>
      <select class="form-control input-sm" name="project" id="project">
        <option value="all">ทั้งหมด</option>
        <?php echo select_projects($project); ?>
      </select>
    </div>

    <div class="col-lg-2 col-md-2-harf col-sm-2-harf col-xs-6 padding-5">
      <label>Sale Employee</label>
      <select class="form-control input-sm" name="sale_id" id="sale_id">
        <option value="all">ทั้งหมด</option>
        <?php echo select_sales_person($sale_id); ?>
      </select>
    </div>

    <div class="col-lg-1 col-md-2 col-sm-2 col-xs-6 padding-5">
      <label>Payment</label>
      <select class="form-control input-sm" name="payment" id="payment">
        <option value="all">ทั้งหมด</option>
        <?php echo select_payments($payment); ?>
      </select>
    </div>

    <div class="col-lg-1 col-md-2 col-sm-2 col-xs-6 padding-5">
      <label>Order Role</label>
      <select class="form-control input-sm" name="role" id="role">
        <option value="all">ทั้งหมด</option>
        <option value="S" <?php echo is_selected($role, 'S'); ?>>BEC</option>
        <option value="C" <?php echo is_selected($role, 'C'); ?>>Customer</option>
      </select>
    </div>

    <div class="col-lg-2 col-md-3 col-sm-3 col-xs-6 padding-5">
      <label>Document date</label>
      <div class="input-daterange input-group width-100">
        <input type="text" class="form-control input-sm width-50 text-center from-date" name="from_date" id="fromDate" value="<?php echo $from_date; ?>" />
        <input type="text" class="form-control input-sm width-50 text-center" name="to_date" id="toDate" value="<?php echo $to_date; ?>" />
      </div>
    </div>

    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-3 padding-5">
      <label class="display-block not-show">buton</label>
      <button type="button" class="btn btn-xs btn-primary btn-block" onclick="getSearch()">Search</button>
    </div>
    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-3 padding-5">
      <label class="display-block not-show">buton</label>
      <button type="button" class="btn btn-xs btn-warning btn-block" onclick="clearFilter()">Reset</button>
    </div>
  </div>
  <input type="hidden" name="search" value="1" />
</form>
<hr />
<?php echo $this->pagination->create_links(); ?>

<div class="row">
  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-5 table-responsive">
    <table class="table table-striped table-hover table-narrow border-1" style="min-width:1300px;">
      <thead>
        <tr>
          <th class="fix-width-30 middle"></th>
          <th class="fix-width-40 middle text-center">#</th>
          <th class="fix-width-80 middle text-center">Doc. date</th>
          <th class="fix-width-100 middle">Web No.</th>
          <th class="min-width-300 middle">Customer</th>
          <th class="fix-width-100 middle text-right">Amount</th>
          <th class="fix-width-80 middle text-center">Payment</th>
          <th class="fix-width-100 middle">Channels</th>
          <th class="fix-width-100 middle">Sales team</th>
          <th class="fix-width-250 middle">Project</th>
          <th class="fix-width-100 middle">User</th>
        </tr>
      </thead>
      <tbody>
        <?php if (! empty($data)) : ?>
          <?php $pageNo = get_zero($this->uri->segment($this->segment)); ?>
          <?php $no = $pageNo + 1; ?>
          <?php $payments = payments_array(); ?>
          <?php $sales_teams = sales_team_array(); ?>
          <?php $channels = channels_array(); ?>
          <?php $projects = project_array(); ?>

          <?php foreach ($data as $rs) : ?>
            <tr id="row-<?php echo $rs->id; ?>">
              <td class="middle">
                <button type="button" class="btn btn-minier btn-info" onclick="viewDetail('<?php echo $rs->code; ?>', '<?php echo $pageNo; ?>')"><i class="fa fa-eye"></i></button>
              </td>
              <td class="middle text-center no"><?php echo $no; ?></td>
              <td class="middle text-center"><?php echo thai_date($rs->DocDate, FALSE, '.'); ?></td>
              <td class="middle"><?php echo $rs->code; ?></td>
              <td class="middle"><?php echo $rs->CardCode . '&nbsp;&nbsp;:&nbsp;&nbsp;' . $rs->CardName; ?></td>
              <td class="middle text-right"><?php echo number($rs->DocTotal, 2); ?></td>
              <td class="middle text-center"><?php echo isset($payments[$rs->Payment]) ? $payments[$rs->Payment] : ''; ?></td>
              <td class="middle"><?php echo isset($channels[$rs->Channels]) ? $channels[$rs->Channels] : ''; ?></td>
              <td class="middle"><?php echo isset($sales_teams[$rs->sale_team]) ? $sales_teams[$rs->sale_team] : ''; ?></td>
              <td class="middle"><?php echo isset($projects[$rs->projectCode ?? 'x']) ? $projects[$rs->projectCode ?? 'x'] : ''; ?></td>
              <td class="middle"><?php echo $rs->uname; ?></td>
            </tr>
            <?php $no++; ?>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<script src="<?php echo base_url(); ?>scripts/order_approval/order_approval.js?v=<?php echo date('Ymd'); ?>"></script>
<script>
  $('#sale_id').select2();
  $('#user_id').select2();
  $('#channels').select2();
  $('#project').select2();
  $('#payment').select2();
</script>

<?php $this->load->view('include/footer'); ?>
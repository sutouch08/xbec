<?php $this->load->view('include/header'); ?>
<style>
  .freez > th {
    top:0;
    position: sticky;
    background-color: #f0f3f7;
    min-height: 30px;
    z-index: 100;
  }
</style>

<div class="row">
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    <h3 class="title">
      <?php echo $this->title; ?>
    </h3>
  </div>
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
		<p class="pull-right top-p">
			<button type="button" class="btn btn-sm btn-warning" onclick="goBack()"><i class="fa fa-arrow-left"></i> Back</button>
		</p>
	</div>
</div><!-- End Row -->
<hr class="margin-bottom-30"/>
<form class="form-horizontal" id="addForm" method="post">
	<div class="form-group">
    <label class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label">Username</label>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
			<input type="text" name="uname" id="uname" class="form-control width-100" maxlength="50" value="<?php echo $user->uname; ?>" disabled/>
    </div>
		<div class="col-xs-12 col-sm-reset inline red" id="uname-error"></div>
  </div>

	<div class="form-group">
    <label class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label">Display name</label>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
			<input type="text" name="dname" id="dname" class="form-control width-100" maxlength="100" value="<?php echo $user->name; ?>" />
    </div>
		<div class="col-xs-12 col-sm-reset inline red" id="dname-error"></div>
  </div>

	<div class="form-group">
  	<label class="col-lg-3 col-md-3 col-sm-3 col-xs-6 control-label">Employee</label>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
			<select class="width-100" id="emp_id">
				<option value="">-No Employee-</option>
				<?php echo select_employee($user->emp_id); ?>
      </select>
    </div>
  </div>

	<div class="form-group">
  	<label class="col-lg-3 col-md-3 col-sm-3 col-xs-6 control-label">Sales Employee</label>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
			<select class="width-100" id="sale_id">
			<?php echo select_saleman($user->sale_id); ?>
      </select>
    </div>
  </div>

	<div class="form-group">
    <label class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label">Sale Team</label>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
			<select class="form-control" id="team_id">
				<option value="">-No Customer Team-</option>
      <?php echo select_sales_team($user->team_id); ?>
      </select>
    </div>
  </div>

	<div class="form-group">
		<label class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label ">Quota No.</label>
		<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
			<select class="form-control" id="quota_no">
				<option value="">-No Quota-</option>
				<?php echo select_quota($user->quota_no); ?>
			</select>
		</div>
  </div>


	<div class="form-group">
    <label class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label">User Group</label>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
			<select class="form-control" id="is_customer" onchange="toggleCustomer()">
        <option value="0" <?php echo is_selected('0', $user->is_customer); ?>>BEC</option>
				<option value="1" <?php echo is_selected('1', $user->is_customer); ?>>Customer</option>
      </select>
    </div>
	</div>
	<div class="form-group">
		<label class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label ">Customer Code</label>
		<div class="col-lg-1-harf col-md-2 col-sm-2 col-xs-12">
			<input type="text" class="form-control"
			id="customer" placeholder="Code Or Name"
			value="<?php echo $user->customer_code; ?>"
			<?php echo ($user->is_customer == 0 ? "disabled" : ""); ?>>
			<input type="hidden" id="customer_code" value="<?php echo $user->customer_code; ?>">
		</div>
		<div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
			<input type="text" class="form-control"
			id="customer_name" placeholder="Code Or Name"
			value="<?php echo $user->customer_name; ?>"	disabled />
		</div>
		<div class="col-xs-12 col-sm-reset inline red" id="customer-error"></div>
  </div>

	<div class="form-group">
		<label class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label ">Channels</label>
		<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
			<select class="form-control filter" name="channels" id="channels" <?php echo ($user->is_customer == 0) ? "disabled" : ""; ?>>
				<option value="">Please Select</option>
				<?php echo select_channels($user->channels); ?>
			</select>
		</div>
		<div class="col-xs-12 col-sm-reset inline red" id="channels-error"></div>
  </div>

	<?php $table = $user->is_customer ? '' : 'hide'; ?>
	<div class="form-group">
		<label class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label ">Warehouse</label>
		<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
			<select class="form-control filter" name="warehouse" id="warehouse" <?php echo ($user->is_customer == 0) ? "disabled" : ""; ?>>
				<option value="">Please Select</option>
				<?php echo select_warehouse($user->warehouse_code); ?>
			</select>
		</div>
		<div class="col-lg-6 col-lg-offset-3 col-md-6 col-md-offset-3 col-sm-6 col-sm-offset-3 col-xs-12 <?php echo $table; ?>" id="wh-table" style="margin-top:10px; height:400px; overflow-y:scroll;">
			<table class="table table-bordered border-1">
				<thead>
					<tr class="freez">
						<th class="width-10 text-center">#</th>
						<th class="width-20 text-left">Code</th>
						<th class="width-70 text-left">Name</th>
					</tr>
				</thead>
				<tbody>
				<?php if( ! empty($whList)) : ?>
					<?php foreach($whList as $rs) : ?>
						<?php $checked = isset($uw[$rs->id]) ? 'checked' : ''; ?>
						<tr>
							<td class="middle text-center">
								<label>
									<input type="checkbox" class="ace chk-wh" data-id="<?php echo $rs->id; ?>" id="wh-<?php echo $rs->id; ?>" value="<?php echo $rs->code; ?>" <?php echo $checked; ?> />
									<span class="lbl"></span>
								</label>
							</td>
							<td class="middle"><?php echo $rs->code; ?></td>
							<td class="middle"><?php echo $rs->name; ?></td>
						</tr>
					<?php endforeach; ?>
				<?php else : ?>
					<tr><td colspan="3" class="text-center">Please Define Warehouse</td></tr>
				<?php endif; ?>
				</tbody>
			</table>
		</div>
		<div class="col-xs-12 col-sm-reset inline red" id="warehouse-error"></div>
  </div>


  <div class="form-group">
    <label class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label">Permission Profile</label>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
			<select class="form-control" name="profile" id="profile">
        <option value="">Please Select</option>
        <?php echo select_profile($user->id_profile); ?>
      </select>
    </div>
		<div class="col-xs-12 col-sm-reset inline red" id="profile-error"></div>
  </div>


	<div class="form-group">
    <label class="col-lg-3 col-md-3 col-sm-3 hidden-xs control-label"></label>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
			<label style="margin-top:7px; padding-left:10px;">
				<input type="checkbox" class="ace" id="active" <?php echo is_checked($user->active, '1'); ?> />
				<span class="lbl">&nbsp; Active</span>
			</label>
    </div>
  </div>


	<div class="divider-hidden"></div>

  <div class="form-group">
    <label class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label"></label>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
      <p class="pull-right">
        <button type="button" class="btn btn-sm btn-success btn-100" onclick="update()">Update</button>
      </p>
    </div>
  </div>

	<input type="hidden" id="user_id" value="<?php echo $user->id; ?>">
</form>


<script>
	$('#emp_id').select2();
	$('#sale_id').select2();
</script>
<script src="<?php echo base_url(); ?>scripts/users/users.js?v=<?php echo date('Ymd'); ?>"></script>
<?php $this->load->view('include/footer'); ?>

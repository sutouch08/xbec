<?php $this->load->view('include/header'); ?>
<script>
	var USE_STRONG_PWD = <?php echo getConfig('USE_STRONG_PWD'); ?>;
</script>

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
			<input type="text" name="uname" id="uname"
			class="form-control" maxlength="50"
			onkeyup="validCode(this)"
			placeholder="Allow &nbsp; a-z,  A-Z,  0-9,  &quot;-&quot;,  &quot;_&quot;,  &quot;.&quot;,  &quot;@&quot;"
			autofocus required />
    </div>
		<div class="col-xs-12 col-sm-reset inline red" id="uname-error"></div>
  </div>

	<div class="form-group">
    <label class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label">Display name</label>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
			<input type="text" name="dname" id="dname" class="form-control" maxlength="100" required />
    </div>
		<div class="col-xs-12 col-sm-reset inline red" id="dname-error"></div>
  </div>

	<div class="form-group">
  	<label class="col-lg-3 col-md-3 col-sm-3 col-xs-6 control-label">Employee</label>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
			<select class="width-100" id="emp_id">
				<option value="">-No Employee-</option>
				<?php echo select_employee(); ?>
      </select>
    </div>
  </div>

	<div class="form-group">
  	<label class="col-lg-3 col-md-3 col-sm-3 col-xs-6 control-label">Sales Employee</label>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
			<select class="width-100" id="sale_id">
			<?php echo select_saleman(); ?>
      </select>
    </div>
  </div>

	<div class="form-group">
    <label class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label">Sale Team</label>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
			<select class="form-control" id="team_id">
				<option value="">-No Customer Team-</option>
      <?php echo select_sales_team(); ?>
      </select>
    </div>
  </div>

	<div class="form-group">
		<label class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label ">Quota No.</label>
		<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
			<select class="form-control" id="quota_no">
				<option value="">-No Quota-</option>
				<?php echo select_quota(); ?>
			</select>
		</div>
		<div class="col-xs-12 col-sm-reset inline red" id="quota-no-error"></div>
  </div>

	<div class="form-group">
    <label class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label">User Group</label>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
			<select class="form-control" id="is_customer" onchange="toggleCustomer()">
        <option value="0">BEC</option>
				<option value="1">Customer</option>
      </select>
    </div>
	</div>

	<div class="form-group">
		<label class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label ">Customer Code</label>
		<div class="col-lg-1-harf col-md-2 col-sm-2 col-xs-12">
			<input type="text" class="form-control"	id="customer" value="" disabled />
			<input type="hidden" id="customer_code" value="">
		</div>
		<div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
			<input type="text" class="form-control" id="customer_name" value=""	disabled />
		</div>
		<div class="col-xs-12 col-sm-reset inline red" id="customer-error"></div>
  </div>

	<div class="form-group">
		<label class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label ">Channels</label>
		<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
			<select class="form-control filter" name="channels" id="channels" disabled>
				<option value="">Please Select</option>
				<?php echo select_channels(); ?>
			</select>
		</div>
		<div class="col-xs-12 col-sm-reset inline red" id="channels-error"></div>
  </div>


	<div class="form-group">
		<label class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label ">Warehouse</label>
		<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
			<select class="form-control filter" name="warehouse" id="warehouse" disabled>
				<option value="">Please Select</option>
				<?php echo select_warehouse(); ?>
			</select>
		</div>
		<div class="col-xs-12 col-sm-reset inline red" id="warehouse-error"></div>
		<div class="col-lg-6 col-lg-offset-3 col-md-6 col-md-offset-3 col-sm-6 col-sm-offset-3 col-xs-12 hide" id="wh-table" style="margin-top:10px; height:400px; overflow-y:scroll;">
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
						<tr>
							<td class="middle text-center">
								<label>
									<input type="checkbox" class="ace chk-wh" data-id="<?php echo $rs->id; ?>" id="wh-<?php echo $rs->id; ?>" value="<?php echo $rs->code; ?>" />
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

	<div class="divider"></div>

  <div class="form-group">
    <label class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label">New password</label>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
			<span class="input-icon input-icon-right width-100">
        <input type="password" name="pwd" id="pwd" class="form-control" required />
				<i class="ace-icon fa fa-key"></i>
			</span>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 red" id="pwd-error"></div>
  </div>

	<div class="form-group">
    <label class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label">Confirm password</label>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
			<span class="input-icon input-icon-right width-100">
        <input type="password" name="cm-pwd" id="cm-pwd" class="form-control" required />
				<i class="ace-icon fa fa-key"></i>
			</span>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 red" id="cm-pwd-error"></div>
  </div>




  <div class="form-group">
    <label class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label">Permission Profile</label>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
			<select class="form-control" name="profile" id="profile">
        <option value="">Please Select</option>
        <?php echo select_profile(); ?>
      </select>
    </div>
		<div class="col-xs-12 col-sm-reset inline red" id="profile-error"></div>
  </div>


	<div class="form-group">
    <label class="col-lg-3 col-md-3 col-sm-3 hidden-xs control-label"></label>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
			<label style="margin-top:7px; padding-left:10px;">
				<input type="checkbox" class="ace" id="active" checked />
				<span class="lbl">&nbsp; Active</span>
			</label>
    </div>
  </div>

	<div class="form-group">
    <label class="col-lg-3 col-md-3 col-sm-3 hidden-xs control-label"></label>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
			<label style="margin-top:7px; padding-left:10px;">
				<input type="checkbox" class="ace" id="force_reset" checked />
				<span class="lbl">&nbsp; Force user to change password</span>
			</label>
    </div>
  </div>


	<div class="divider-hidden"></div>

  <div class="form-group">
    <label class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label"></label>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
      <p class="pull-right">
        <button type="button" class="btn btn-sm btn-success btn-100" onclick="saveAdd()">Add</button>
      </p>
    </div>
  </div>
</form>

<script>
	$('#emp_id').select2();
	$('#sale_id').select2();
</script>
<script src="<?php echo base_url(); ?>scripts/users/users.js?v=<?php echo date('Ymd'); ?>"></script>
<?php $this->load->view('include/footer'); ?>

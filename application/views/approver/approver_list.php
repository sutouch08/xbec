<?php $this->load->view('include/header'); ?>
<div class="row">
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 padding-5 padding-top10">
		<h3 class="title"><?php echo $this->title; ?></h3>
	</div>
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 padding-5 text-right">
		<?php if ($this->pm->can_add) : ?>
			<button type="button" class="btn btn-white btn-success top-btn" onclick="addNew()"><i class="fa fa-plus"></i> Add new</button>
		<?php endif; ?>
	</div>
</div>
<hr class="">
<form id="searchForm" method="post" action="<?php echo current_url(); ?>">
	<div class="row">
		<div class="col-lg-2-harf col-md-2 col-sm-3 col-xs-6">
			<label>User</label>
			<select class="form-control input-sm filter" name="user_id" id="user-id">
				<option value="all">ทั้งหมด</option>
				<?php echo select_user_id($user_id); ?>
			</select>
		</div>			

		<div class="col-lg-1-harf col-md-2 col-sm-2 col-xs-6">
			<label>Sales team</label>
			<select class="form-control input-sm filter" name="team" id="team">
				<option value="all">ทั้งหมด</option>
				<?php echo select_team($team); ?>
			</select>
		</div>

		<div class="col-lg-1-harf col-md-2 col-sm-2 col-xs-6">
			<label>Brand</label>
			<select class="form-control input-sm filter" name="brand" id="brand">
				<option value="all">ทั้งหมด</option>
				<?php echo select_brand($brand); ?>
			</select>
		</div>

		<div class="col-lg-1 col-md-1-harf col-sm-1-harf col-xs-6">
			<label>Status</label>
			<select class="form-control input-sm filter" name="status">
				<option value="all">ทั้งหมด</option>
				<option value="1" <?php echo is_selected('1', $status); ?>>Active</option>
				<option value="0" <?php echo is_selected('0', $status); ?>>Inactive</option>
			</select>
		</div>
		
		<div class="col-lg-1 col-md-1-harf col-sm-1-harf col-xs-6">
			<label>Orders</label>
			<select class="form-control input-sm filter" name="ap_order">
				<option value="all">ทั้งหมด</option>
				<option value="1" <?php echo is_selected('1', $ap_order); ?>>Yes</option>
				<option value="0" <?php echo is_selected('0', $ap_order); ?>>No</option>
			</select>
		</div>

		<div class="col-lg-1 col-md-1-harf col-sm-1-harf col-xs-6">
			<label>Promotions</label>
			<select class="form-control input-sm filter" name="ap_promotion">
				<option value="all">ทั้งหมด</option>
				<option value="1" <?php echo is_selected('1', $ap_promotion); ?>>Yes</option>
				<option value="0" <?php echo is_selected('0', $ap_promotion); ?>>No</option>
			</select>
		</div>

		<div class="col-lg-1 col-md-1-harf col-sm-1-harf col-xs-6">
			<label>GP</label>
			<select class="form-control input-sm filter" name="visible_gp">
				<option value="all">ทั้งหมด</option>
				<option value="1" <?php echo is_selected('1', $visible_gp); ?>>Yes</option>
				<option value="0" <?php echo is_selected('0', $visible_gp); ?>>No</option>
			</select>
		</div>

		<div class="col-lg-1 col-md-1 col-sm-1-harf col-xs-6">
			<label class="display-block not-show">ok</label>
			<button type="button" class="btn btn-xs btn-primary btn-block" onclick="getSearch()"><i class="fa fa-search"></i> Search</button>
		</div>
		<div class="col-lg-1 col-md-1 col-sm-1-harf col-xs-6">
			<label class="display-block not-show">reset</label>
			<button type="button" class="btn btn-xs btn-warning btn-block" onclick="clearFilter()"><i class="fa fa-retweet"></i> Reset</button>
		</div>
	</div>

	<input type="hidden" name="search" value="1" />
</form>
<hr class="margin-top-10 margin-bottom-10">
<?php echo $this->pagination->create_links(); ?>
<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 table-responsive">
		<table class="table table-striped table-hover table-narrow border-1" style="min-width:1210px;">
			<thead>
				<tr>
					<th class="fix-width-100"></th>
					<th class="fix-width-50 text-center">#</th>
					<th class="fix-width-100">Username</th>
					<th class="min-width-200">Name</th>
					<th class="fix-width-80 text-center">Active</th>
					<th class="fix-width-80 text-center">Orders</th>
					<th class="fix-width-80 text-center">Promotions</th>
					<th class="fix-width-80 text-center">Visible GP</th>
					<th class="fix-width-120">Created at</th>
					<th class="fix-width-100">Created by</th>
					<th class="fix-width-120">Last update</th>
					<th class="fix-width-100">Updated by</th>
				</tr>
			</thead>
			<tbody>
				<?php if (!empty($data)) : ?>
					<?php $no = $this->uri->segment($this->segment) + 1; ?>
					<?php $usernames = []; ?>
					<?php foreach ($data as $rs) : ?>
					<?php if(empty($usernames[$rs->user_id])) : ?>
						<?php $usernames[$rs->user_id] = $this->user_model->get_name_by_id($rs->user_id); ?>
					<?php endif; ?>
					<?php $rs->name = $usernames[$rs->user_id]; ?>
						<tr id="row-<?php echo $rs->id; ?>">
							<td class="middle">
								<button type="button" class="btn btn-minier btn-info" onclick="viewDetail(<?php echo $rs->id; ?>)"><i class="fa fa-eye"></i></button>
								<?php if ($this->pm->can_edit) : ?>
									<button type="button" class="btn btn-minier btn-warning" onclick="edit(<?php echo $rs->id; ?>)"><i class="fa fa-pencil"></i></button>
								<?php endif; ?>
								<?php if ($this->pm->can_delete) : ?>
									<button type="button" class="btn btn-minier btn-danger" onclick="confirmDelete(<?php echo $rs->id; ?>, '<?php echo $rs->uname; ?>')"><i class="fa fa-trash"></i></button>
								<?php endif; ?>
							</td>
							<td class="middle text-center no"><?php echo $no; ?></td>
							<td class="middle"><?php echo $rs->uname; ?></td>
							<td class="middle"><?php echo $rs->name; ?></td>
							<td class="middle text-center">
								<?php if ($this->pm->can_edit) : ?>
									<label style="height: 22px;" title="active/inactive approver">
										<input class="ace ace-switch ace-switch-6" type="checkbox" value="1" onchange="toggleActive(<?php echo $rs->id; ?>, this)" <?php echo $rs->status ? 'checked' : ''; ?>>
										<span class="lbl"></span>
									</label>
								<?php else : ?>
									<?php echo is_active($rs->status); ?>
								<?php endif; ?>
							</td>
							<td class="middle text-center">
								<?php if ($this->pm->can_edit) : ?>
									<label style="height: 22px;" title="this approver can/cannot approve orders">
										<input class="ace ace-switch ace-switch-6" type="checkbox" value="1" onchange="toggleOrderApproval(<?php echo $rs->id; ?>, this)" <?php echo $rs->ap_order ? 'checked' : ''; ?>>
										<span class="lbl"></span>
									</label>
								<?php else : ?>
									<?php echo is_active($rs->ap_order); ?>
								<?php endif; ?>
							</td>
							<td class="middle text-center">
								<?php if ($this->pm->can_edit) : ?>
									<label style="height: 22px;" title="this approver can/cannot approve promotions">
										<input class="ace ace-switch ace-switch-6" type="checkbox" value="1" onchange="togglePromotionApproval(<?php echo $rs->id; ?>, this)" <?php echo $rs->ap_promotion ? 'checked' : ''; ?>>
										<span class="lbl"></span>
									</label>
								<?php else : ?>
									<?php echo is_active($rs->ap_promotion); ?>
								<?php endif; ?>
							</td>
							<td class="middle text-center">
								<?php if ($this->pm->can_edit) : ?>
									<label style="height: 22px;" title="this approver can/cannot see GP">
										<input class="ace ace-switch ace-switch-6" type="checkbox" value="1" onchange="toggleVisibleGP(<?php echo $rs->id; ?>, this)" <?php echo $rs->visible_gp ? 'checked' : ''; ?>>
										<span class="lbl"></span>
									</label>
								<?php else : ?>
									<?php echo is_active($rs->visible_gp); ?>
								<?php endif; ?>
							</td>
							<td class="middle"><?php echo thai_date($rs->date_add, TRUE); ?></td>
							<td class="middle"><?php echo $rs->add_user; ?></td>
							<td class="middle"><?php echo is_null($rs->date_upd) ? '-' : thai_date($rs->date_upd, TRUE); ?></td>
							<td class="middle"><?php echo is_null($rs->update_user) ? '-' : $rs->update_user; ?></td>
						</tr>
						<?php $no++; ?>
					<?php endforeach; ?>
				<?php endif; ?>
			</tbody>
		</table>
	</div>
</div>

<script>
	$('#user-id').select2();
	$('#team').select2();
	$('#brand').select2();
</script>
<script src="<?php echo base_url(); ?>scripts/approver/approver.js?v=<?php echo date('Ymd'); ?>"></script>
<?php $this->load->view('include/footer'); ?>
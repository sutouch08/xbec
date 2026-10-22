<?php $this->load->view('include/header'); ?>
<div class="row">
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    <h3 class="title"><?php echo $this->title; ?></h3>
  </div>
  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
		<p class="pull-right top-p">
			<button type="button" class="btn btn-xs btn-warning top-btn" onclick="goBack()"><i class="fa fa-arrow-left"></i>  Back</button>
		<?php if($this->pm->can_add OR $this->pm->can_edit) : ?>
			<button type="button" class="btn btn-xs btn-success btn-100 top-btn hidden-xs" onclick="savePermission()">Save</button>
		<?php endif; ?>
		</p>
	</div>
</div><!-- End Row -->
<hr class=""/>
<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="alert alert-info">
			<p style="font-size:18px;">Permission Assignment - <?php echo $data->name; ?></p>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 table-responsive">
		<table class="table table-striped table-bordered table-hover" style="min-width:600px;">
			<thead>
				<tr class="hide">
					<th>Group-Menu</th>
					<th>View</th>
					<th>Add</th>
					<th>Edit</th>
					<th>Delete</th>
					<th>All</th>
				</tr>
			</thead>
			<tbody>
<?php if(!empty($menus)) : ?>
	<?php foreach($menus as $groups) : ?>
	<?php 	$g_code = $groups['group_code']; ?>
				<tr class="font-size-14" style="background-color:#428bca73;">
					<td class="width-50 middle"><?php echo $groups['group_name']; ?></td>
					<td class="width-10 middle text-center">
						<input id="view-group-<?php echo $g_code; ?>" type="checkbox" class="ace" onchange="groupViewCheck($(this), '<?php echo $g_code; ?>')" />
						<span class="lbl">&nbsp;View</span>
					</td>
					<td class="width-10 middle text-center">
						<input id="add-group-<?php echo $g_code; ?>" type="checkbox" class="ace" onchange="groupAddCheck($(this), '<?php echo $g_code; ?>' )">
						<span class="lbl">&nbsp;Add</span>
					</td>
					<td class="width-10 middle text-center">
						<input id="edit-group-<?php echo $g_code; ?>" type="checkbox" class="ace" onchange="groupEditCheck($(this), '<?php echo $g_code; ?>' )">
						<span class="lbl">&nbsp;Edit</span>
					</td>
					<td class="width-10 middle text-center">
						<input id="delete-group-<?php echo $g_code; ?>" type="checkbox" class="ace" onchange="groupDeleteCheck($(this), '<?php echo $g_code; ?>' )">
						<span class="lbl">&nbsp;Delete</span>
					</td>
					<td class="width-10 middle text-center">
						<input id="all-group-<?php echo $g_code; ?>" type="checkbox" class="ace" onchange="groupAllCheck($(this), '<?php echo $g_code; ?>' )">
						<span class="lbl">&nbsp;All</span>
					</td>
				</tr>

				<?php if(!empty($groups['menu'])) : ?>
					<?php foreach($groups['menu'] as $menu) : ?>
						<?php $code = $menu['menu_code']; ?>
						<?php $pm = $menu['permission']; ?>
						<tr>
							<td class="middle" style="padding-left:20px;"> -
								<?php echo $menu['menu_name']; ?>
								<input type="hidden" class="menu-code" id="menu-[<?php echo $code; ?>]" value="<?php echo $code; ?>"  />
							</td>
							<td class="middle text-center">
								<input id="view-<?php echo $code; ?>" name="view[<?php echo $code; ?>]" type="checkbox" class="ace view-<?php echo $g_code.' '.$code; ?>" <?php echo is_checked($pm->can_view, 1); ?>>
								<span class="lbl"></span>
							</td>
							<td class="middle text-center">
								<input id="add-<?php echo $code; ?>" name="add[<?php echo $code; ?>]" type="checkbox" class="ace add-<?php echo $g_code.' '.$code; ?>" <?php echo is_checked($pm->can_add, 1); ?>>
								<span class="lbl"></span>
							</td>
							<td class="middle text-center">
								<input id="edit-<?php echo $code; ?>" name="edit[<?php echo $code; ?>]" type="checkbox" class="ace edit-<?php echo $g_code.' '.$code; ?>" <?php echo is_checked($pm->can_edit, 1); ?>>
								<span class="lbl"></span>
							</td>
							<td class="middle text-center">
								<input id="delete-<?php echo $code; ?>" name="delete[<?php echo $code; ?>]" type="checkbox" class="ace delete-<?php echo $g_code.' '.$code; ?>" <?php echo is_checked($pm->can_delete, 1); ?>>
								<span class="lbl"></span>
							</td>
							<td class="middle text-center">
								<input id="all-<?php echo $code; ?>" type="checkbox" class="ace all all-<?php echo $g_code; ?>" onchange="allCheck($(this), '<?php echo $code; ?>')">
								<span class="lbl"></span>
							</td>
						</tr>
					<?php endforeach; ?>
				<?php endif; ?>
			<?php endforeach; ?>
<?php endif; ?>
			</tbody>
		</table>

		<input type="hidden" name="id" id="id" value="<?php echo $data->id; ?>" />
	</div>
	<div class="divider-hidden"></div>
	<div class="divider-hidden visible-xs"></div>
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
		<?php if($this->pm->can_add OR $this->pm->can_edit) : ?>
					<button type="button" class="btn btn-xs btn-success btn-100" onclick="savePermission()">Save</button>
		<?php endif; ?>
	</div>
</div>

<script src="<?php echo base_url(); ?>scripts/users/permission.js?v=<?php echo date('Ymd'); ?>"></script>
<?php $this->load->view('include/footer'); ?>

<?php $this->load->view('include/header'); ?>
<style>
	input[type=checkbox].ace:disabled + .lbl::before, input[type=radio].ace:disabled + .lbl::before, input[type=checkbox].ace[disabled] + .lbl::before, input[type=radio].ace[disabled] + .lbl::before, input[type=checkbox].ace.disabled + .lbl::before,
	input[type=radio].ace.disabled + .lbl::before {
	background-color: #FFF !important;
	border-color: #abbac3 !important;
	box-shadow: none !important;
	color: #6fb3e0;
}
</style>

<div class="row">
	<div class="col-lg-8 col-md-8 col-sm-8 hidden-xs">
    <h3 class="title">Permission - <?php echo $data->name; ?></h3>
  </div>
	<div class="col-xs-12 text-center visible-xs" style="background-color:#eee;">
    <h3 class="margin-top-0">Permission - <?php echo $data->name; ?></h3>
  </div>
  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
		<p class="pull-right top-p">
			<button type="button" class="btn btn-xs btn-warning top-btn" onclick="goBack()"><i class="fa fa-arrow-left"></i>  Back</button>
		</p>
	</div>
</div><!-- End Row -->
<hr class=""/>
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
				</tr>
			</thead>
			<tbody>
<?php if(!empty($menus)) : ?>
	<?php foreach($menus as $groups) : ?>
	<?php 	$g_code = $groups['group_code']; ?>
				<tr class="font-size-14" style="background-color:#428bca73;">
					<td class="width-50 middle"><?php echo $groups['group_name']; ?></td>
					<td class="width-10 middle text-center">View</td>
					<td class="width-10 middle text-center">Add</td>
					<td class="width-10 middle text-center">Edit</td>
					<td class="width-10 middle text-center">Delete</td>
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
								<input type="checkbox" class="ace disv" <?php echo is_checked($pm->can_view, 1); ?> disabled>
								<span class="lbl"></span>
							</td>
							<td class="middle text-center">
								<input type="checkbox" class="ace disv" <?php echo is_checked($pm->can_add, 1); ?> disabled>
								<span class="lbl"></span>
							</td>
							<td class="middle text-center">
								<input type="checkbox" class="ace disv" <?php echo is_checked($pm->can_edit, 1); ?> disabled>
								<span class="lbl"></span>
							</td>
							<td class="middle text-center">
								<input type="checkbox" class="ace disv" <?php echo is_checked($pm->can_delete, 1); ?> disabled>
								<span class="lbl"></span>
							</td>
						</tr>
					<?php endforeach; ?>
				<?php endif; ?>
			<?php endforeach; ?>
<?php endif; ?>
			</tbody>
		</table>
	</div>
</div>

<script src="<?php echo base_url(); ?>scripts/users/permission.js?v=<?php echo date('Ymd'); ?>"></script>
<?php $this->load->view('include/footer'); ?>

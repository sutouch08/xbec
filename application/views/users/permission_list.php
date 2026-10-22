<?php $this->load->view('include/header'); ?>
<div class="row">
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    <h3 class="title"><?php echo $this->title; ?> </h3>
  </div>
  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
  	<p class="pull-right top-p">
  	<?php if($this->pm->can_add) : ?>
			<button type="button" class="btn btn-sm btn-success" onclick="addNew()"><i class="fa fa-plus"></i> Add New</button>
		<?php endif; ?>
  	</p>
  </div>
</div><!-- End Row -->
<hr class="">
<form id="searchForm" method="post" action="<?php echo current_url(); ?>">
<div class="row">
  <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
    <label>Profile Name</label>
    <input type="text" class="form-control" name="name" value="<?php echo $name; ?>" />
  </div>

  <div class="col-lg-1 col-md-1-harf col-sm-1-harf col-xs-3">
    <label class="display-block not-show">buton</label>
    <button type="button" class="btn btn-sm btn-primary btn-block" onclick="getSearch()"><i class="fa fa-search"></i> Search</button>
  </div>
	<div class="col-lg-1 col-md-1-harf col-sm-1-harf col-xs-3">
    <label class="display-block not-show">buton</label>
    <button type="button" class="btn btn-sm btn-warning btn-block" onclick="clearFilter()"><i class="fa fa-retweet"></i> Reset</button>
  </div>
</div>
<hr class="">
</form>
<?php echo $this->pagination->create_links(); ?>

<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 table-responsive">
		<table class="table table-striped table-bordered">
			<thead>
				<tr>
					<th class="fix-width-80 middle text-center">#</th>
					<th class="min-width-350 middle">Profile Name</th>
					<th class="fix-width-100 middle text-center">Members</th>
					<th class="fix-width-150"></th>
				</tr>
			</thead>
			<tbody>
			<?php if( ! empty($data)) : ?>
				<?php $no = $this->uri->segment($this->segment) + 1; ?>

				<?php foreach($data as $rs) : ?>
					<tr>
						<td class="middle text-center"><?php echo $no; ?></td>
						<td class="middle"><?php echo $rs->name; ?></td>
						<td class="middle text-center"><?php echo number($rs->member); ?></td>
						<td class="text-right">
							<button type="button" class="btn btn-mini btn-info" onclick="viewPermission(<?php echo $rs->id; ?>)"><i class="fa fa-eye"></i></button>
						<?php if($this->pm->can_edit OR $this->pm->can_add) : ?>
							<button type="button" class="btn btn-mini btn-purple" onclick="editPermission(<?php echo $rs->id; ?>)"><i class="fa fa-lock"></i></button>
						<?php endif; ?>
						<?php if($this->pm->can_edit) : ?>
							<button type="button" class="btn btn-mini btn-warning" onclick="editProfile(<?php echo $rs->id; ?>)"><i class="fa fa-pencil"></i></button>
						<?php endif; ?>
						<?php if($this->pm->can_delete) : ?>
							<button type="button" class="btn btn-mini btn-danger" onclick="confirmDelete(<?php echo $rs->id; ?>, '<?php echo $rs->name; ?>')"><i class="fa fa-trash"></i></button>
						<?php endif; ?>
						</td>
					</tr>
					<?php $no++; ?>
				<?php endforeach; ?>
			<?php endif; ?>
			</tbody>
		</table>
	</div>
</div>

<script src="<?php echo base_url(); ?>scripts/users/permission.js?v=<?php echo date('Ymd'); ?>"></script>

<?php $this->load->view('include/footer'); ?>

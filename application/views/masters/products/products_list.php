<?php $this->load->view('include/header'); ?>
<div class="row">
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 padding-5">
		<h3 class="title"><?php echo $this->title; ?></h3>
	</div>
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 padding-5 text-right">
		<button type="button" class="btn btn-white btn-info btn-100" onclick="syncData()"><i class="fa fa-refresh"></i> Sync</button>
		<button type="button" class="btn btn-white btn-info btn-100" onclick="forceSyncData()"><i class="fa fa-refresh"></i> Sync All</button>
		<?php if ($this->pm->can_add or $this->pm->can_edit) : ?>
			<button type="button" class="btn btn-white btn-primary btn-100" onclick="updateCategory()">Update Category</button>
		<?php endif; ?>
	</div>
</div><!-- End Row -->
<hr class="padding-5" />
<form id="searchForm" method="post" action="<?php echo current_url(); ?>">
	<div class="row">
		<div class="col-lg-1-harf col-md-2 col-sm-2 col-xs-6 padding-5">
			<label>Code</label>
			<input type="text" class="form-control input-sm" name="code" value="<?php echo $code; ?>" />
		</div>

		<div class="col-lg-1-harf col-md-2 col-sm-2 col-xs-6 padding-5">
			<label>Name</label>
			<input type="text" class="form-control input-sm" name="name" value="<?php echo $name; ?>" />
		</div>

		<div class="col-lg-1-harf col-md-2 col-sm-2 col-xs-6 padding-5">
			<label>Model</label>
			<input type="text" class="form-control input-sm" name="model" value="<?php echo $model; ?>" />
		</div>

		<div class="col-lg-1-harf col-md-2 col-sm-2 col-xs-6 padding-5">
			<label>Category</label>
			<select class="form-control input-sm" name="category" onchange="getSearch()">
				<option value="all">ทั้งหมด</option>
				<?php echo select_category_level(5, $category); ?>
			</select>
		</div>

		<div class="col-lg-1-harf col-md-2 col-sm-2 col-xs-6 padding-5">
			<label>Type</label>
			<select class="form-control input-sm" name="type" onchange="getSearch()">
				<option value="all">ทั้งหมด</option>
				<?php echo select_product_type($type); ?>
			</select>
		</div>

		<div class="col-lg-1-harf col-md-2 col-sm-2 col-xs-6 padding-5">
			<label>Brand</label>
			<select class="form-control input-sm" name="brand" onchange="getSearch()">
				<option value="all">ทั้งหมด</option>
				<?php echo select_product_brand($brand); ?>
			</select>
		</div>

		<div class="col-lg-1-harf col-md-2 col-sm-2 col-xs-6 padding-5">
			<label>Status</label>
			<select class="form-control input-sm" name="status" onchange="getSearch()">
				<option value="all">ทั้งหมด</option>
				<option value="1" <?php echo is_selected('1', $status); ?>>Active</option>
				<option value="0" <?php echo is_selected('0', $status); ?>>Disactive</option>
			</select>
		</div>

		<div class="col-lg-1-harf col-md-2 col-sm-2 col-xs-6 padding-5">
			<label>นับสต็อก</label>
			<select class="form-control input-sm" name="count_stock" onchange="getSearch()">
				<option value="all">ทั้งหมด</option>
				<option value="1" <?php echo is_selected('1', $count_stock); ?>>Yes</option>
				<option value="0" <?php echo is_selected('0', $count_stock); ?>>No</option>
			</select>
		</div>

		<div class="col-lg-1-harf col-md-2 col-sm-2 col-xs-6 padding-5">
			<label>แก้ไขส่วนลด</label>
			<select class="form-control input-sm" name="allow_change_discount" onchange="getSearch()">
				<option value="all">ทั้งหมด</option>
				<option value="1" <?php echo is_selected('1', $allow_change_discount); ?>>Yes</option>
				<option value="0" <?php echo is_selected('0', $allow_change_discount); ?>>No</option>
			</select>
		</div>

		<div class="col-lg-1-harf col-md-2 col-sm-2 col-xs-6 padding-5">
			<label>ลูกค้าเห็น</label>
			<select class="form-control input-sm" name="customer_view" onchange="getSearch()">
				<option value="all">ทั้งหมด</option>
				<option value="1" <?php echo is_selected('1', $customer_view); ?>>Yes</option>
				<option value="0" <?php echo is_selected('0', $customer_view); ?>>No</option>
			</select>
		</div>

		<div class="col-lg-1 col-md-2 col-sm-2 col-xs-3 padding-5">
			<label class="display-block not-show">buton</label>
			<button type="submit" class="btn btn-xs btn-primary btn-block"><i class="fa fa-search"></i> ค้นหา</button>
		</div>
		<div class="col-lg-1 col-md-2 col-sm-2 col-xs-3 padding-5">
			<label class="display-block not-show">buton</label>
			<button type="button" class="btn btn-xs btn-warning btn-block" onclick="clearFilter()"><i class="fa fa-retweet"></i> Reset</button>
		</div>
	</div>
	<input type="hidden" name="search" value="1" />
</form>
<hr class="margin-top-15">
<?php echo $this->pagination->create_links(); ?>
<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-5 table-responsive">
		<table class="table table-striped table-narrow border-1" style="min-width:1270px;">
			<thead>
				<tr>
					<th class="fix-width-90"></th>
					<th class="fix-width-40 middle text-center">#</th>
					<th class="fix-width-60 middle text-center">Img</th>
					<th class="fix-width-100 middle">Code</th>
					<th class="min-width-200 middle">Name</th>
					<th class="fix-width-100 middle">Model</th>
					<th class="fix-width-100 middle">Type</th>
					<th class="fix-width-150 middle">Category</th>
					<th class="fix-width-100 middle">Brand</th>
					<th class="fix-width-80 middle text-center">Master pack</th>
					<th class="fix-width-60 middle text-center">Status</th>
					<th class="fix-width-60 middle text-center">Stock</th>
					<th class="fix-width-60 middle text-center">Discount</th>
					<th class="fix-width-60 middle text-center">customer</th>
				</tr>
			</thead>
			<tbody>
				<?php if (! empty($data)) : ?>
					<?php $pageNo = get_zero($this->uri->segment($this->segment)); ?>
					<?php $no = $pageNo + 1; ?>
					<?php foreach ($data as $rs) : ?>
						<tr class="font-size-12" id="row-<?php echo $rs->id; ?>">
							<td class="middle">
								<button type="button" class="btn btn-minier btn-primary" onclick="syncItem('<?php echo $rs->code; ?>')"><i class="fa fa-refresh"></i></button>
								<button type="button" class="btn btn-minier btn-info" onclick="viewDetail(<?php echo $rs->id; ?>, <?php echo $pageNo; ?>)"><i class="fa fa-eye"></i></button>
								<?php if ($this->pm->can_edit) : ?>
									<button type="button" class="btn btn-minier btn-warning" onclick="getEdit(<?php echo $rs->id; ?>, <?php echo $pageNo; ?>)"><i class="fa fa-pencil"></i></button>
								<?php endif; ?>
							</td>
							<td class="middle text-center no"><?php echo $no; ?></td>
							<td class="middle text-center"><img src="<?php echo get_product_image($rs->id, "mini"); ?>" width="20" /></td>
							<td class="middle"><?php echo $rs->code; ?></td>
							<td class="middle"><?php echo $rs->name; ?></td>
							<td class="middle"><?php echo $rs->model_name; ?></td>
							<td class="middle"><?php echo $rs->type_name; ?></td>
							<td class="middle"><?php echo $rs->category_name; ?></td>
							<td class="middle"><?php echo $rs->brand_name; ?></td>
							<td class="middle text-center"><?php echo ac_format($rs->min_order_qty); ?></td>
							<td class="middle text-center"><?php echo is_active($rs->status); ?></td>
							<td class="middle text-center">
								<label>
									<input type="checkbox" class="ace" onchange="toggleCountStock($(this))" value="<?php echo $rs->id; ?>" <?php echo is_checked(1, $rs->count_stock); ?> />
									<span class="lbl"></span>
								</label>
							</td>
							<td class="middle text-center">
								<label>
									<input type="checkbox" class="ace" onchange="toggleChangeDiscount($(this))" value="<?php echo $rs->id; ?>" <?php echo is_checked(1, $rs->allow_change_discount); ?> />
									<span class="lbl"></span>
								</label>
							</td>
							<td class="middle text-center">
								<label>
									<input type="checkbox" class="ace" onchange="toggleCustomerView($(this))" value="<?php echo $rs->id; ?>" <?php echo is_checked(1, $rs->customer_view); ?> />
									<span class="lbl"></span>
								</label>
							</td>
						</tr>
						<?php $no++; ?>
					<?php endforeach; ?>
				<?php else : ?>
					<tr>
						<td colspan="14" class="middle text-center">--- No data ---</td>
					</tr>
				<?php endif; ?>
			</tbody>
		</table>
	</div>
</div>

<script src="<?php echo base_url(); ?>scripts/masters/products.js?v=<?php echo date('Ymd'); ?>"></script>
<script src="<?php echo base_url(); ?>scripts/masters/sync_product.js?v=<?php echo date('Ymd'); ?>"></script>

<?php $this->load->view('include/footer'); ?>
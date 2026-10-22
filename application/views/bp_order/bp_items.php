<?php $this->load->view('bp_order/bp_header'); ?>
<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-5">
		<h3 class="title">Products</h3>
	</div>
</div>
<hr class="padding-5">
<form id="search-form" method="post" action="<?php echo $this->home; ?>/items" />
<div class="row">
	<div class="col-lg-3 col-md-3 col-sm-4 col-xs-6 padding-5">
		<label>Items</label>
		<input type="text" class="form-control input-sm" name="code" value="<?php echo $code; ?>" />
	</div>
	<div class="col-lg-2 col-md-2 col-sm-3 col-xs-6 padding-5">
		<label>Category</label>
		<select class="form-control input-sm" name="category">
			<option value="all">All</option>
			<?php echo select_category_level(5, $category); ?>
		</select>
	</div>
	<div class="col-lg-2 col-md-2 col-sm-2 col-xs-6 padding-5">
		<label>Brand</label>
		<select class="form-control input-sm" name="brand">
			<option value="all">All</option>
			<?php echo select_product_brand($brand); ?>
		</select>
	</div>
	<div class="col-lg-1 col-md-1-harf col-sm-1-harf col-xs-3 padding-5">
		<label class="display-block not-show">ok</label>
		<button type="button" class="btn btn-xs btn-primary btn-block" onclick="search()">Search</button>
	</div>
	<div class="col-lg-1 col-md-1-harf col-sm-1-harf col-xs-3 padding-5">
		<label class="display-block not-show">clear</label>
		<button type="button" class="btn btn-xs btn-warning btn-block" onclick="clear_search_filter()">Reset</button>
	</div>
</div>
</form>

<hr class="padding-5"/>
<?php echo $this->pagination->create_links(); ?>

<div class="row">
	<?php if( ! empty($items)) : ?>
		<?php foreach($items as $rs) : ?>
			<div class="col-lg-2 col-md-20 col-sm-3 col-xs-6">
				<div class="item-box pointer">
					<div class="img width-100 display-block" onclick="showItem('<?php echo $rs->code; ?>', <?php echo $rs->id; ?>)">
						<img src="<?php echo get_image_path($rs->id); ?>" class="width-100" />
					</div>
					<div class="item-description text-center" onclick="showItem('<?php echo $rs->code; ?>', <?php echo $rs->id; ?>)"><?php echo $rs->name; ?></div>
					<div class="item-price text-center" onclick="showItem('<?php echo $rs->code; ?>', <?php echo $rs->id; ?>)"><?php echo number($rs->price, 2); ?></div>
					<div class="text-center" id="item-card-<?php echo $rs->id; ?>">
						<?php if($this->products_model->is_favorite($this->_user->id, $rs->id)) : ?>
							<button type="button" class="btn btn-xs btn-default btn-block" onclick="removeFromFavorite(<?php echo $rs->id; ?>, 0)">Remove From Favorite</button>
						<?php else : ?>
							<button type="button" class="btn btn-xs btn-primary btn-block" onclick="addToFavorite(<?php echo $rs->id; ?>)">Add To Favorite</button>
						<?php endif; ?>
					</div>
				</div>
			</div>
		<?php endforeach; ?>
	<?php else : ?>
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 font-size-15 text-center">--- No Item Found ---</div>
	<?php endif; ?>
</div>

<div class="divider-hidden visible-xs" style="margin-bottom:35px;"></div>

<input type="hidden" id="quotaNo" value="<?php echo $this->_user->quota_no; ?>" />
<input type="hidden" id="customer_code" value="<?php echo $customer->CardCode; ?>" />
<input type="hidden" id="payment" value="<?php echo $customer->GroupNum; ?>" />
<input type="hidden" id="channels" value="<?php echo $this->_user->channels; ?>" />


<?php $this->load->view('bp_order/cart_modal'); ?>
<?php $this->load->view('bp_order/cart_bar'); ?>

<script src="<?php echo base_url(); ?>scripts/bp_order/bp_order.js?v=<?php echo date('Ymd'); ?>"></script>

<?php $this->load->view('bp_order/bp_footer'); ?>

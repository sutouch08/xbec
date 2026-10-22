<?php $this->load->view('bp_order/bp_header'); ?>
<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-5">
		<h3 class="title">Favorite Items</h3>
	</div>
</div>
<hr class="padding-5"/>
<div class="row">
	<?php if( ! empty($items)) : ?>
		<?php foreach($items as $rs) : ?>
			<div class="col-lg-2 col-md-20 col-sm-3 col-xs-6" id="item-box-<?php echo $rs->id; ?>">
				<div class="item-box pointer" data-id="<?php echo $rs->id; ?>">
					<div class="img width-100 display-block" onclick="showItem('<?php echo $rs->code; ?>', <?php echo $rs->id; ?>)">
						<img src="<?php echo get_image_path($rs->id); ?>" class="width-100" />
					</div>
					<div class="item-description text-center" onclick="showItem('<?php echo $rs->code; ?>', <?php echo $rs->id; ?>)"><?php echo $rs->name; ?></div>
					<div class="item-price text-center" onclick="showItem('<?php echo $rs->code; ?>', <?php echo $rs->id; ?>)"><?php echo number($rs->price, 2); ?></div>
					<div class="text-center margin-bottom-15" onclick="showItem('<?php echo $rs->code; ?>', <?php echo $rs->id; ?>)">
						Available : <span id="item-card-<?php echo $rs->id; ?>">Loading..</span>
					</div>
					<div class="text-center">
						<button type="button" class="btn btn-xs btn-default btn-block" onclick="removeFromFavorite(<?php echo $rs->id; ?>, 1)">Remove From Favorite</button>
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
<script>
	$(document).ready(function() {
		updateFavoriteAvailable();
	});
</script>

<?php $this->load->view('bp_order/bp_footer'); ?>

<?php $this->load->view('bp_order/bp_header'); ?>
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
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-5">
		<h3 class="title">Favorite Items</h3>
	</div>
</div>
<hr class="padding-5"/>
<div class="row">
	<?php if( ! empty($items)) : ?>
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 table-responsive">
		<table class="table table-hover table-bordered border-1">
			<thead>
				<tr>
					<th class="fix-width-60 text-center">#</th>
					<th class="fix-width-100">Item Code</th>
					<th class="fix-width-250 text-center">Description</th>
					<th class="fix-width-100 text-center">Price</th>
					<th class="fix-width-150 text-center">Discount</th>
					<th class="fix-width-100 text-center">Available</th>
					<th class="fix-width-120 text-center">Qty</th>
					<th class="fix-width-120 text-center">Amount</th>
					<th class="fix-width-40 text-center"></th>
				</tr>
			</thead>
			<tbody>

			<?php foreach($items as $rs) : ?>
			<tr id="fav-row-<?php echo $rs->id; ?>">
				<input type="hidden" id="price-<?php echo $rs->id; ?>" value="<?php echo $rs->price; ?>" />
				<input type="hidden" id="sellPrice-<?php echo $rs->id; ?>" value="<?php echo $rs->sellPrice; ?>" />
				<input type="hidden" class="item-box" id="fav-<?php echo $rs->id; ?>" data-id="<?php echo $rs->id; ?>" value="<?php echo $rs->id; ?>"/>
				<input type="hidden" id="stdPrice-<?php echo $rs->id; ?>" value="<?php echo $rs->price; ?>" />
				<input type="hidden" id="discPrcnt-<?php echo $rs->id; ?>" value="<?php echo $rs->DiscPrcnt; ?>" />
				<input type="hidden" id="product-code-<?php echo $rs->id; ?>" value="<?php echo $rs->code; ?>" />
				<td class="middle text-center"><img src="<?php echo $rs->image_path; ?>" class="width-100" /></td>
				<td class="middle"><?php echo $rs->code; ?></td>
				<td class="middle"><?php echo $rs->name; ?></td>
				<td class="middle text-center"><?php echo number($rs->price, 2); ?></td>
				<td class="middle text-center"><?php echo $rs->discountLabel; ?></td>
				<td class="middle text-center" id="item-card-<?php echo $rs->id; ?>">Loading..</td>
				<td class="middle text-center">
					<input type="number"
					class="form-control input-sm text-right input-qty"
					data-id="<?php echo $rs->id; ?>"
					id="qty-<?php echo $rs->id; ?>"
					onkeyup="recalAmount(<?php echo $rs->id; ?>)"/>
				</td>
				<td class="middle text-right fav-line-amount" id="line-amount-<?php echo $rs->id; ?>"></td>
				<td class="middle text-center">
					<button type="button"
					class="btn btn-mini btn-danger"
					title="Remove From Favorite"
					onclick="removeFavorite(<?php echo $rs->id; ?>)"><i class="fa fa-trash"></i></button></td>
			</tr>
		<?php endforeach; ?>

			</tbody>
		</table>
	</div>
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-5">
		<p class="pull-right"><button type="button" class="btn btn-sm btn-primary btn-100" id="btn-add-to-cart" onclick="addFavTocart()">Add to cart</button></p>
	</div>
	<?php else : ?>
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-5 text-center">-- No Favorite --</div>
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

<?php $this->load->view('bp_order/bp_header'); ?>
<div class="row">
	<div class="col-lg-8 col-md-8 col-sm-8 col-xs-8 padding-5">
		<h3 class="title">Filter Result</h3>
	</div>
	<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 padding-5">
		<p class="pull-right top-p">
			<button type="button" class="btn btn-sm btn-warning" onclick="goBack()"><i class="fa fa-chevron-left"></i>&nbsp; &nbsp; HOME</button>
		</p>
	</div>
</div>
<hr class="padding-5"/>
<div class="col-xs-12 padding-5 text-center visible-xs">
	ทั้งหมด <?php echo number($rows); ?> รายการ แสดง <?php echo $perpage; ?> รายการ ต่อหน้า
</div>
<?php echo $pagination; ?>
<div class="row">
	<?php if( ! empty($data)) : ?>
		<?php foreach($data as $rs) : ?>
			<div class="col-lg-2 col-md-20 col-sm-3 col-xs-6">
				<div class="item-box pointer" onclick="showItem('<?php echo $rs->code; ?>')">
					<div class="img width-100 display-block">
						<img src="<?php echo $rs->image_path; ?>" class="width-100" />
					</div>
					<div class="item-description"><?php echo $rs->name; ?></div>
					<div class="item-sku">SKU : <?php echo $rs->code; ?></div>
					<div class="item-price"><?php echo number($rs->price, 2); ?> ฿</div>
				</div>
			</div>
		<?php endforeach; ?>
	<?php else : ?>
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 font-size-15 text-center">--- No Item Found ---</div>
	<?php endif; ?>
</div>

<?php echo $pagination; ?>
<div class="divider-hidden visible-xs" style="margin-bottom:35px;"></div>


<input type="hidden" id="priceList" value="<?php echo $customer->ListNum; ?>" />
<input type="hidden" id="quotaNo" value="<?php echo $this->_user->quota_no; ?>" />
<input type="hidden" id="customer_code" value="<?php echo $customer->CardCode; ?>" />
<input type="hidden" id="payment" value="<?php echo $customer->GroupNum; ?>" />
<input type="hidden" id="channels" value="<?php echo $this->_user->channels; ?>" />

<input type="hidden" id="sell-price" value="0">
<input type="hidden" id="ItemCode" value="">

<?php $this->load->view('bp_order/cart_modal'); ?>
<?php $this->load->view('bp_order/cart_bar'); ?>

<script src="<?php echo base_url(); ?>scripts/bp_order/bp_order.js?v=<?php echo date('Ymd'); ?>"></script>

<?php $this->load->view('bp_order/bp_footer'); ?>

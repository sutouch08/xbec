<?php $this->load->view('bp_order/bp_header'); ?>
	<div class="row">
		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-8 padding-5">
			<h3 class="title"><?php echo $this->title; ?></h3>
		</div>
		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-4 padding-5">
			<p class="pull-right top-p">
				<button type="button" class="btn btn-sm btn-warning" onclick="history()"><i class="fa fa-arrow-left"></i> Back</button>
			</p>
		</div>
	</div>
	<hr class="padding-5" />
	<div class="row">
		<div class="col-lg-1-harf col-md-1-harf col-sm-2 col-xs-4 padding-5">
			<label>เลขที่</label>
			<input type="text" class="width-100 text-center" value="<?php echo $order->code; ?>" readonly>
		</div>
		<div class="col-lg-1-harf col-md-1-harf col-sm-2 col-xs-4 padding-5">
			<label>วันที่</label>
			<input type="text" class="width-100 text-center" value="<?php echo thai_date($order->DocDate, FALSE); ?>" readonly>
		</div>
		<div class="col-lg-1-harf col-md-1-harf col-sm-2 col-xs-4 padding-5">
			<label>รหัสลูกค้า</label>
			<input type="text" class="width-100 text-center" value="<?php echo $order->CardCode; ?>" readonly>
		</div>
		<div class="col-lg-7-harf col-md-7-harf col-sm-6 col-xs-12 padding-5">
			<label>ชื่อลูกค้า</label>
			<input type="text" class="width-100" value="<?php echo $order->CardName; ?>" readonly>
		</div>
		<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-5">
			<label>ที่อยู่จัดส่ง</label>
			<textarea class="width-100" readonly><?php echo $order->Address2; ?></textarea>
		</div>
		<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-5">
			<label>ที่อยู่เปิดบิล</label>
			<textarea class="width-100" readonly><?php echo $order->Address; ?></textarea>
		</div>
	</div>

	<hr class="padding-5 margin-top-15 margin-bottom-15">
<?php if($order->Status == 2) : ?>
	<?php $this->load->view('cancle_watermark'); ?>
<?php endif; ?>
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 table-responsive">
			<table class="table border-1 margin-top-5" style="min-width:1000px;">
				<thead>
					<tr>
						<th class="fix-width-60 text-center">Image</th>
						<th class="fix-width-120">Item Code</th>
						<th class="min-width-150">Item Name</th>
						<th class="fix-width-100 text-right">Price</th>
						<th class="fix-width-120 text-center">Discount</th>
						<th class="fix-width-100 text-right">Qty</th>
						<th class="fix-width-150 text-right">Amount</th>
					</tr>
				</thead>
				<tbody id="checkout-table">
					<?php $totalQty = 0; ?>
					<?php $totalAmount = 0; ?>
					<?php $totalDiscAmount = 0; ?>
					<?php $totalVat = 0; ?>
					<?php if(!empty($details)) : ?>
						<?php foreach($details as $rs) : ?>
							<?php $discLabel = discountLabel($rs->disc1, $rs->disc2, $rs->disc3, $rs->disc4, $rs->disc5, '%'); ?>
							<?php $freeRow = $rs->is_free == 1 ? 'free-row' : ''; ?>
							<?php $freeLabel = $rs->is_free == 1 ? '<span class="red">**Free</span>' : ''; ?>
							<tr>
								<td class="middle text-center">
									<img src="<?php echo get_image_path($rs->product_id, "medium"); ?>" width="60" />
								</td>
								<td class="middle"><?php echo $rs->ItemCode; ?> </td>
								<td class="middle"><?php echo $rs->ItemName; ?> <?php echo $freeLabel; ?></td>
								<td class="middle text-right"><?php echo number($rs->Price, 2); ?></td>
								<td class="middle text-center"><?php echo $rs->discLabel; ?></td>
								<td class="middle text-right" id="qtyLabel-<?php echo $rs->id; ?>"><?php echo number($rs->Qty); ?></td>
								<td class="middle text-right"><?php echo number($rs->LineTotal, 2); ?></td>
							</tr>
							<?php $totalQty += $rs->Qty; ?>
							<?php $totalDiscAmount += $rs->totalDiscAmount; ?>
							<?php $totalAmount += $rs->LineTotal; ?>
							<?php $totalVat += $rs->totalVatAmount; ?>
						<?php endforeach; ?>
							<tr class="font-size-15">
								<td colspan="5" rowspan="4" style="border-right:solid 1px #dddddd;"></td>
								<td class="text-left">จำนวนรวม</td>
								<td class="text-right" id="total-qty"><?php echo number($totalQty); ?></td>
							</tr>
							<tr class="font-size-15">
								<td class="text-left">มูลค่าสินค้า</td>
								<td class="text-right"><?php echo number($totalAmount, 2); ?></td>
							</tr>
							<tr class="font-size-15">
								<td class="text-left">VAT</td>
								<td class="text-right"><?php echo number($totalVat, 2); ?></td>
							</tr>
							<tr class="font-size-15">
								<td class="text-left">มูลค่ารวม</td>
								<td class="text-right"><?php echo number($totalAmount + $totalVat, 2); ?></td>
							</tr>
					<?php else : ?>
						<tr>
							<td colspan="8" class="text-center">--- ไม่พบรายการสินค้า ---</td>
						</tr>
					<?php endif; ?>
				</tbody>
			</table>
		</div>


<script src="<?php echo base_url(); ?>scripts/bp_order/bp_order.js?v=<?php echo date('Ymd'); ?>"></script>


<?php $this->load->view('bp_order/bp_footer'); ?>

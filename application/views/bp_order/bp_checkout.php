<?php $this->load->view('bp_order/bp_header'); ?>
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-5">
			<h3 class="title">Checkout</h3>
		</div>
	</div>
	<hr class="padding-5" />
	<div class="row">
		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
			<div class="col-lg-3 col-md-3 col-sm-4 col-xs-6">
				<label>ที่อยู่จัดส่ง</label>
				<select class="width-100" id="shipToCode" onchange="getShipToAddress()">
					<?php if(!empty($shipToCode)) : ?>
						<?php foreach($shipToCode as $sh) : ?>
							<option value="<?php echo $sh->code; ?>" <?php echo is_selected($sh->code, $shipCode); ?>><?php echo $sh->name; ?></option>
						<?php endforeach; ?>
					<?php endif; ?>
				</select>
			</div>
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<textarea id="ShipTo" class="autosize autosize-transition form-control margin-top-10" readonly><?php echo $shipTo; ?></textarea>
			</div>
		</div>

		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
			<div class="col-lg-3 col-md-3 col-sm-4 col-xs-6">
				<label>ที่อยู่เปิดบิล</label>
				<select class="width-100" id="billToCode" onchange="getBillToAddress()">
					<?php if(!empty($billToCode)) : ?>
						<?php foreach($billToCode as $sh) : ?>
							<option value="<?php echo $sh->code; ?>" <?php echo is_selected($sh->code, $billCode); ?>><?php echo $sh->name; ?></option>
						<?php endforeach; ?>
					<?php endif; ?>
				</select>
			</div>
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<textarea id="BillTo" class="autosize autosize-transition form-control margin-top-10" readonly><?php echo $billTo; ?></textarea>
			</div>
		</div>
	</div>
	<hr class="padding-5 margin-top-15 margin-bottom-15">

	<div class="row">
		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 font-size-20 margin-top-5">
			ตะกร้าสินค้า
		</div>
		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-right">
			<button type="button" class="btn btn-sm btn-primary btn-100" onclick="goBack()"><i class="fa fa-chevron-left"></i>&nbsp;&nbsp; ซื้อสินค้าต่อ</button>
		</div>
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 table-responsive">
			<table class="table border-1 margin-top-5" style="margin-bottom:10px;">
				<thead>
					<tr>
						<th class="fix-width-60 text-center">Image</th>
						<th class="fix-width-120">Item Code</th>
						<th class="min-width-150">Item Name</th>
						<th class="fix-width-100 text-right">Price</th>
						<th class="fix-width-120 text-center">Discount(%)</th>
						<th class="fix-width-100 text-center">Available</th>
						<th class="fix-width-100 text-center">Qty</th>
						<th class="fix-width-150 text-right">Amount</th>
						<th class="fix-width-40 text-center">#</th>
					</tr>
				</thead>
				<tbody id="checkout-table">
					<?php $totalQty = 0; ?>
					<?php $totalAmount = 0; ?>
					<?php $totalDiscAmount = 0; ?>
					<?php $totalVatAmount = 0; ?>
					<?php $totalSKU = 0; ?>
					<?php if(!empty($cart)) : ?>
						<?php foreach($cart as $rs) : ?>
							<?php $discLabel = discountLabel($rs->disc1, $rs->disc2, $rs->disc3, $rs->disc4, $rs->disc5, '%'); ?>
							<?php $freeRow = $rs->is_free == 1 ? 'free-row' : ''; ?>
							<?php $na = $rs->Available < $rs->Qty ? 1 : 0; ?>
							<tr id="row-<?php echo $rs->id; ?>" class="<?php echo $freeRow; ?> <?php echo $na == 1 ? 'red' : ''; ?>">
								<input type="hidden" id="product-id-<?php echo $rs->id; ?>" value="<?php echo $rs->product_id; ?>" />
								<input type="hidden" class="item-code" id="item-code-<?php echo $rs->id; ?>" data-id="<?php echo $rs->id; ?>" value="<?php echo $rs->ItemCode; ?>" />
								<input type="hidden" class="line-qty" data-no="<?php echo $rs->id; ?>" id="line-qty-<?php echo $rs->id; ?>" value="<?php echo $rs->Qty; ?>"/>
								<input type="hidden" class="line-available" data-no="<?php echo $rs->id; ?>" id="line-available-<?php echo $rs->id; ?>" value="<?php echo $rs->Available; ?>"/>
								<input type="hidden" id="line-total-<?php echo $rs->id; ?>" value="<?php echo $rs->LineTotal; ?>" />
								<input type="hidden" id="line-vat-<?php echo $rs->id; ?>" value="<?php echo $rs->totalVatAmount; ?>" />
								<input type="hidden" id="stdPrice-<?php echo $rs->id; ?>" value="<?php echo $rs->StdPrice; ?>" />
								<input type="hidden" id="price-<?php echo $rs->id; ?>" value="<?php echo $rs->Price; ?>" />
								<input type="hidden" id="sellPrice-<?php echo $rs->id; ?>" value="<?php echo $rs->SellPrice; ?>" />
								<input type="hidden" class="is-free" id="is-free-<?php echo $rs->id; ?>" value="<?php echo $rs->is_free; ?>" data-id="<?php echo $rs->id; ?>" data-parent="<?php echo $rs->parent_uid;?>" data-parentrow="<?php echo $rs->rule_id; ?>" />
								<input type="hidden" id="<?php echo $rs->uid; ?>" data-id="<?php echo $rs->id; ?>" value="<?php echo $rs->id; ?>"/>
								<input type="hidden" class="na" id="na-<?php echo $rs->id; ?>" value="<?php echo $na; ?>" />

								<td class="middle text-center">
									<img src="<?php echo get_image_path($rs->product_id, "medium"); ?>" width="60" />
								</td>
								<td class="middle"><?php echo $rs->ItemCode; ?></td>
								<td class="middle"><?php echo $rs->ItemName; ?></td>
								<td class="middle text-right" id="priceLabel-<?php echo $rs->id; ?>"><?php echo number($rs->Price, 2); ?></td>
								<td class="middle text-center" id="discLabel-<?php echo $rs->id; ?>"><?php echo $rs->discLabel; ?></td>
								<td class="middle text-center" id="availableLabel-<?php echo $rs->id; ?>"><?php echo number($rs->Available); ?></td>
								<td class="middle text-center"> <?php echo number($rs->Qty); ?></td>
								<td class="middle text-right" id="totalLabel-<?php echo $rs->id; ?>"><?php echo number($rs->LineTotal, 2); ?></td>
								<td class="middle text-center">
									<button class="btn btn-minier btn-danger" onclick="removeCheckRow(<?php echo $rs->id; ?>, '<?php echo $rs->ItemCode; ?>')"><i class="fa fa-trash"></i></button>
								</td>
							</tr>
							<?php $totalQty += $rs->Qty; ?>
							<?php $totalDiscAmount += $rs->totalDiscAmount; ?>
							<?php $totalAmount += $rs->LineTotal; ?>
							<?php $totalVatAmount += $rs->totalVatAmount; ?>
							<?php $totalSKU++; ?>
						<?php endforeach; ?>
					<?php else : ?>
						<tr>
							<td colspan="8" class="text-center">--- ไม่พบรายการสินค้า ---</td>
						</tr>
					<?php endif; ?>
				</tbody>
			</table>
		</div>
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<table class="table border-1" style="margin-bottom:0px;">
				<tr>
					<td class="width-50 font-size-14 blue text-left" style="border-top:0px;">จำนวนรวม</td>
					<td class="width-50 font-size-14 blue text-right" id="total-qty"><?php echo number($totalQty); ?></td>
				</tr>
				<tr>
					<td class="width-50 font-size-14 blue text-left">มูลค่าสินค้า</td>
					<td class="width-50 font-size-14 blue text-right" id="total-amount"><?php echo number($totalAmount, 2); ?></td>
				</tr>
				<tr>
					<td class="width-50 font-size-14 blue text-left">VAT</td>
					<td class="width-50 font-size-14 blue text-right" id="total-vat"><?php echo number($totalVatAmount, 2); ?></td>
				</tr>
				<tr>
					<td class="width-50 font-size-14 blue text-left">มูลค่ารวม</td>
					<td class="width-50 font-size-14 blue text-right" id="doc-total"><?php echo number($totalVatAmount+$totalAmount, 2); ?></td>
				</tr>
			</table>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 margin-top-10">
			ข้อความของคุณ
		</div>
	</div>
	<hr/>
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<textarea class="width-100" id="remark" maxlength="250"></textarea>
		</div>

		<div class="col-lg-2 col-lg-offset-10 col-md-2 col-md-offset-10 col-sm-3 col-sm-offset-9 col-xs-12 margin-top-10">
		<?php if( ! empty($cart)) : ?>
			<button type="button" class="btn btn-sm btn-success btn-block" onclick="placeOrder()">ยืนยันการสั่งซื้อ</button>
		<?php endif; ?>
		</div>
	</div>




<input type="hidden" id="priceList" value="<?php echo $customer->ListNum; ?>" />
<input type="hidden" id="quotaNo" value="<?php echo $this->_user->quota_no; ?>" />
<input type="hidden" id="customer_code" value="<?php echo $customer->CardCode; ?>" />
<input type="hidden" id="payment" value="<?php echo $customer->GroupNum; ?>" />
<input type="hidden" id="channels" value="<?php echo $this->_user->channels; ?>" />
<input type="hidden" id="limit-sku-per-order" value="<?php echo getConfig('CUSTOMER_ORDER_LIMIT_SKU'); ?>" />
<input type="hidden" id="total-sku" value="<?php echo $totalSKU; ?>"/>


<script id="free-row-template" type="text/x-handlebarsTemplate">
	<tr id="row-{{id}}" class="free-row">
		<input type="hidden" id="product-id-{{id}}" value="{{product_id}}" />
		<input type="hidden" class="item-code" id="item-code-{{id}}" data-id="{{id}}" value="{{ItemCode}}>" />
		<input type="hidden" class="line-qty" data-no="{{id}}" id="line-qty-{{id}}" value="{{Qty}}"/>
		<input type="hidden" class="line-available" data-no="{{id}}" id="line-available-{{id}}" value="{{available}}"/>
		<input type="hidden" id="line-total-{{id}}" value="{{LineTotal}}" />
		<input type="hidden" id="stdPrice-{{id}}" value="{{StdPrice}}" />
		<input type="hidden" id="price-{{id}}" value="{{Price}}" />
		<input type="hidden" id="sellPrice-{{id}}" value="{{SellPrice}}" />
		<input type="hidden" class="is-free" id="is-free-{{id}}" value="1" data-id="{{id}}"	data-parent="{{parent_uid}}" data-parentrow="{{rule_id}}" />
		<input type="hidden" id="{{uid}}" data-id="{{id}}" value="{{id}}"/>
		<input type="hidden" id="disc-type-{{id}}" value="F" />

		<td class="middle text-center">
			<img src="{{image_path}}" width="60" />
		</td>
		<td class="middle">{{ItemCode}}</td>
		<td class="middle">{{ItemName}} <span class="red">Free</span></td>
		<td class="middle text-right">{{PriceLabel}}</td>
		<td class="middle text-center">100</td>
		<td class="middle text-center" id="availableLabel-{{id}}">{{availableLabel}}</td>
		<td class="middle text-center" id="qtyLabel-{{id}}">{{QtyLabel}}</td>
		<td class="middle text-right">{{LineTotalLabel}}</td>
		<td class="middle text-center">
			<button class="btn btn-minier btn-danger" onclick="removeFreeRow({{id}}, '{{ItemCode}}')"><i class="fa fa-trash"></i></button>
		</td>
	</tr>
</script>


<div class="modal fade" id="free-item-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width:800px; max-width:95%;">
        <div class="modal-content">
            <div class="modal-body">
            <div class="row">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 table-responsive">
								<table class="table table-striped broder-1" style="min-width:600px;">
									<tbody id="free-item-list">

									</tbody>
								</table>
							</div>
            </div>
            </div>
        </div>
    </div>
</div>

<script id="free-input-template" type="text/x-handlebarsTemplate">
	<input type="hidden" class="free-item" id="free-{{rule_id}}" value="{{freeQty}}" data-id="{{uid}}" data-valid="0" data-rule="" data-picked="0" data-uid="{{uid}}" />
</script>

<script id="free-btn-template" type="text/x-handlebarsTemplate">
	<button type="button" class="btn btn-sm btn-primary free-btn" id="btn-free-{{rule_id}}" data-parent="{{uid}}" onclick="pickFreeItem({{rule_id}})">Free {{freeQty}}</button>
</script>

<script src="<?php echo base_url(); ?>scripts/bp_order/bp_order.js?v=<?php echo date('Ymd'); ?>"></script>


<?php $this->load->view('bp_order/bp_footer'); ?>

<style>
	.freez>th {
		top: 0;
		position: sticky;
		background-color: #f0f3f7;
		min-height: 30px;
		z-index: 100;
	}
</style>
<!--  Add New Address Modal  --------->
<div class="modal fade" id="cartModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog cart-modal">
		<div class="modal-content cart-modal">
			<div class="modal-header" id="modal-header" style="border-bottom:solid 1px #e5e5e5; background-color:white;">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title-site text-center" id="modal-title" style="margin-bottom:0px;">ตะกร้าสินค้า</h4>
			</div>
			<div class="modal-body" id="modal-body" style="padding-top:0px; min-height:100px; overflow:auto;">
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<table class="table table-hover border-1" style="margin-bottom:0px; min-width:1040px; margin-top:3px;">
							<thead>
								<tr class="freez">
									<th class="fix-width-80"></th>
									<th class="fix-width-100">Item Code</th>
									<th class="fix-width-250">Description</th>
									<th class="fix-width-100 text-right">Price</th>
									<th class="fix-width-120 text-center">Discount</th>
									<th class="fix-width-100 text-center">Available</th>
									<th class="fix-width-150 text-center">Qty</th>
									<th class="fix-width-120 text-right">Amount</th>
									<th class="fix-width-20"></th>
								</tr>
							</thead>
							<tbody id="cart-table">
								<?php $no = 1; ?>
								<?php $totalQty = 0; ?>
								<?php $totalAmount = 0; ?>
								<?php $totalVat = 0; ?>
								<?php if (! empty($cart)) : ?>

									<?php foreach ($cart as $cs) : ?>
										<tr id="cart-row-<?php echo $cs->id; ?>">
											<input type="hidden" class="line-qty" data-no="<?php echo $cs->id; ?>" id="line-qty-<?php echo $cs->id; ?>" value="<?php echo $cs->Qty; ?>" />
											<input type="hidden" id="line-total-<?php echo $cs->id; ?>" value="<?php echo $cs->LineTotal; ?>" />
											<input type="hidden" id="line-vat-<?php echo $cs->id; ?>" value="<?php echo $cs->totalVatAmount; ?>" />

											<td class="middle text-center">
												<img src="<?php echo $cs->image_path; ?>" width="60" />
											</td>
											<td class="middle" style="width:150px;"><?php echo $cs->ItemCode; ?></td>
											<td class="middle" style="max-width:300px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"><?php echo $cs->ItemName; ?></td>
											<td class="middle text-right"><?php echo number($cs->Price, 2); ?></td>
											<td class="middle text-center"><?php echo $cs->discLabel; ?></td>
											<td class="middle text-center" id="available-<?php echo $cs->id; ?>"><?php echo number($cs->available); ?></td>
											<td class="middle text-center" style="width:150px;">
												<input type="number"
													class="form-control input-sm text-center"
													id="input-qty-<?php echo $cs->id; ?>"
													value="<?php echo $cs->Qty; ?>"
													data-val="<?php echo $cs->Qty; ?>"
													onchange="updateCartQty(<?php echo $cs->id; ?>)">
											</td>
											<td class="middle text-right"><?php echo number($cs->LineTotal, 2); ?></td>
											<td class="middle fix-width-20 text-right" style="vertical-align:text-top; font-size:18px; ">
												<a href="javascript:void(0)" style="color:#d15b47;" onclick="removeRow(<?php echo $cs->id; ?>)">
													<i class="fa fa-trash"></i>
												</a>
											</td>
										</tr>

										<?php $no++; ?>
										<?php $totalQty += $cs->Qty; ?>
										<?php $totalAmount += $cs->LineTotal; ?>
										<?php $totalVat += $cs->totalVatAmount; ?>
									<?php endforeach; ?>

								<?php endif; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="modal-footer cart-item-footer" id="modal-footer" style="max-height:200px;">
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-5 font-size-18 blue text-left">จำนวนรวม</div>
				<div class="col-lg-8 col-md-8 col-sm-8 col-xs-7 font-size-18 blue text-right" id="total-qty"><?php echo number($totalQty); ?></div>
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-5 font-size-18 blue text-left">มูลค่าสินค้า</div>
				<div class="col-lg-8 col-md-8 col-sm-8 col-xs-7 font-size-18 blue text-right" id="total-amount"><?php echo number($totalAmount, 2); ?></div>
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-5 font-size-18 blue text-left">VAT</div>
				<div class="col-lg-8 col-md-8 col-sm-8 col-xs-7 font-size-18 blue text-right" id="total-vat"><?php echo number($totalVat, 2); ?></div>
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-5 font-size-18 blue text-left">มูลค่ารวม</div>
				<div class="col-lg-8 col-md-8 col-sm-8 col-xs-7 font-size-18 blue text-right" id="doc-total"><?php echo number($totalVat + $totalAmount, 2); ?></div>
				<div class="divider-hidden"></div>
				<div class="divider-hidden"></div>
				<div class="col-lg-8 col-md-8 col-sm-8 hidden-xs">&nbsp;</div>
				<div class="col-lg-2 col-md-2 col-sm-2 col-xs-4 padding-5">
					<button type="button" class="btn btn-sm btn-warning btn-block" data-dismiss="modal" aria-hidden="true"><i class="fa fa-arrow-left"></i></button>
				</div>
				<div class="col-lg-2 col-md-2 col-sm-2 col-xs-8 padding-5">
					<button type="button" class="btn btn-sm btn-success btn-block" id="btn-checkout" onclick="checkout()">Check out</button>
				</div>
			</div>
		</div>
	</div>
</div>


<div class="modal fade" id="itemModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog" style="width:350px; max-width:95%;">
		<div class="modal-content">
			<div class="modal-body" style="min-height:100px;">
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="item-table">

					</div>
				</div>
			</div>
			<div class="modal-footer width-100 text-center">
				<button type="button" class="btn btn-sm btn-default btn-100" onclick="closeModal('itemModal')">ปิด</button>
				<button type="button" class="btn btn-sm btn-success btn-100" onclick="addItemTocart()">Add</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="cateModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog" style="width:1000px; max-width:95%">
		<div class="modal-content">
			<div class="modal-body" style="min-height:100px;">
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="max-height:550px; overflow:auto;">
						<table class="table table-bordered" style="min-width:900px;">
							<thead>
								<tr class="freez">
									<th class="fix-width-60 text-center">เลือก</th>
									<th class="fix-width-100 text-center">Item Code</th>
									<th class="fix-width-250 text-center">Description</th>
									<th class="fix-width-100 text-center">Price</th>
									<th class="fix-width-120 text-center">Discount</th>
									<th class="fix-width-100 text-center">Qty</th>
									<th class="fix-width-120 text-center">Amount</th>
								</tr>
							</thead>
							<tbody id="cate-table">

							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="modal-footer width-100 text-center">
				<button type="button" class="btn btn-sm btn-warning" onclick="removeNonCheck()">ลบรายการที่ไม่เลือก</button>
				<button type="button" class="btn btn-sm btn-default" onclick="closeModal('cateModal')">ปิด</button>
				<button type="button" class="btn btn-sm btn-success btn-100" onclick="addTocart()">Add</button>
			</div>
		</div>
	</div>
</div>


<script id="cate-template" type="text/x-handlebarsTemplate">
	{{#each this}}
		<tr id="item-row-{{id}}">
			<input type="hidden" id="sellPrice-{{id}}" value="{{sellPrice}}" />
			<input type="hidden" id="price-{{id}}" value="{{price}}" />
			<input type="hidden" id="stdPrice-{{id}}" value="{{stdPrice}}" />
			<input type="hidden" id="discPrcnt-{{id}}" value="{{DiscPrcnt}}" />
			<input type="hidden" id="product-code-{{id}}" value="{{code}}" />
			<td class="middle text-center">
				<label>
					<input type="checkbox" class="ace item-chk" id="chk-{{id}}" value="{{id}}" />
					<span class="lbl"></span>
				</label>
			</td>
			<td class="middle">{{code}}</td>
			<td class="middle">{{name}}</td>
			<td class="middle text-right">{{priceLabel}}</td>
			<td class="middle text-center">{{discLabel}}</td>
			<td class="middle">
				<input type="number" class="form-control input-sm text-right input-qty" data-id="{{id}}" id="qty-{{id}}" onkeyup="recalAmount({{id}})" />
			</td>
			<td class="middle text-right" id="line-amount-{{id}}"></td>
		</tr>
	{{/each}}
</script>

<script id="item-template" type="text/x-handlebarsTemplate">

	<div class="width-100">
    <table class="table table-bordered border-1" style="margin-bottom:0px;">
      <tr><td colspan="2" class="text-center"><img src="{{image_path}}" class="width-50" /></td></tr>
      <tr><td class="width-30 text-center">Item Code</td><td calss="width-70">{{code}}</tr>
      <tr><td class="width-30 text-center">Description</td><td calss="width-70">{{name}}</tr>
      <tr><td class="width-30 text-center">Price</td><td calss="width-70">{{price}}</tr>
      <tr><td class="width-30 text-center">Discount</td><td calss="width-70">{{discLabel}}</tr>
      <tr><td class="width-30 text-center">Available</td><td calss="width-70">{{available}}</tr>
      <tr>
        <td class="width-30 text-center">Qty</td>
        <td calss="width-70">
          <input type="number" class="form-control input-small text-center" data-id="{{id}}" data-code="{{code}}" id="item-input-qty" value="1" />
        </td>
        </tr>
    </table>
    </div>


</script>


<script id="cart-template" type="text/x-handlebarsTemplate">

	{{#each this}}
		<tr id="cart-row-{{id}}">
			<input type="hidden" class="line-qty" data-no="{{id}}" id="line-qty-{{id}}" value="{{Qty}}" />
			<input type="hidden" id="line-total-{{id}}" value="{{LineTotal}}" />
			<input type="hidden" id="line-vat-{{id}}" value="{{vatAmount}}" />

			<td class="middle text-center"><img src="{{image_path}}" width="60" /></td>
			<td class="middle">{{ItemCode}}</td>
			<td class="middle">{{ItemName}}</td>
			<td class="middle text-right">{{Price}}</td>
			<td class="middle text-center">{{discLabel}}</td>
			<td class="middle text-center" id="available-{{id}}">{{available}}</td>
			<td class="middle text-center" style="width:150px;">
				<input type="number" class="form-control input-sm text-center" id="input-qty-{{id}}" value="{{Qty}}" data-val="{{Qty}}" onchange="updateCartQty({{id}})" />
			</td>
			<td class="middle text-right">{{LineTotalLabel}}</td>
			<td class="middle fix-width-20 text-right" style="vertical-align:text-top; font-size:18px; ">
				<a href="javascript:void(0)" style="color:#d15b47;" onclick="removeRow({{id}})">
					<i class="fa fa-trash"></i>
				</a>
			</td>
		</tr>
	{{/each}}
</script>

<script id="cart-row-template" type="text/x-handlebarsTemplate">
	<input type="hidden" class="line-qty" data-no="{{id}}" id="line-qty-{{id}}" value="{{Qty}}" />
  <input type="hidden" id="line-total-{{id}}" value="{{LineTotal}}" />
  <input type="hidden" id="line-vat-{{id}}" value="{{vatAmount}}" />

  <td class="middle text-center"><img src="{{image_path}}" width="60"/></td>
  <td class="middle">{{ItemCode}}</td>
  <td class="middle">{{ItemName}}</td>
  <td class="middle text-right">{{Price}}</td>
  <td class="middle text-center">{{discLabel}}</td>
  <td class="middle text-center" id="available-{{id}}">{{available}}</td>
  <td class="middle text-center">
  <input type="number" class="form-control input-sm text-center" id="input-qty-{{id}}" value="{{Qty}}" data-val={{Qty}} onchange="updateCartQty({{id}})" />
  </td>
  <td class="middle text-right">{{LineTotalLabel}}</td>
  <td class="middle fix-width-20 text-right" style="vertical-align:text-top; font-size:18px; ">
    <a href="javascript:void(0)" style="color:#d15b47;" onclick="removeRow({{id}})">
      <i class="fa fa-trash"></i>
    </a>
  </td>
</script>

<script>
	$(document).ready(function() {
		updateAvailable();
	});
</script>
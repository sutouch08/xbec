<div class="modal fade" id="free-item-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog" style="width:900px; max-width:90vw;">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title text-center" id="free-item-modal-label">เลือก n ชิ้น จากรายการต่อไปนี้</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-5" style="max-height:400px; overflow:auto;">
						<table class="table table-striped table-bordered table-narrow border-1" style="min-width:500px;">
							<thead>
								<tr>
									<th class="fix-width-100">Code</th>
									<th class="min-width-250">Description</th>
									<th class="fix-width-80 text-right">Std. Price</th>
									<th class="fix-width-80 text-right">Sell Price</th>
									<th class="fix-width-100">Qty</th>
									<th class="fix-width-50 text-center"></th>
								</tr>
							</thead>
							<tbody id="free-item-table">

							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-white btn-default btn-100" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<script id="free-item-template" type="text/x-handlebars-template">
	{{#each this}}
		{{#if nodata}}
			<tr>
				<td colspan="5" class="text-center">--- No Free Item ---</td>
			</tr>
		{{else}}
			<tr>
				<td>{{code}}</td>
				<td>{{name}}</td>
				<td class="text-right">{{stdPriceLabel}}</td>
				<td class="text-right">{{sellPriceLabel}}</td>
				<td class="text-right">
					<input type="number" class="form-control input-xs text-center"
						id="input-{{uuid}}"
						data-item="{{id}}"
						data-uid="{{uid}}"
						data-parent="{{uid}}"
						data-pdcode="{{code}}"
						data-pdname="{{name}}"
						data-cost="{{cost}}"
						data-stdprice="{{std_price}}"
						data-price="{{price}}"
						data-sellprice="{{sell_price}}"
						data-stdpricelabel="{{stdPriceLabel}}"
						data-pricelabel="{{priceLabel}}"
						data-sellpricelabel="{{sellPriceLabel}}"
						data-discpercent="{{discPercent}}"
						data-discamount="{{discAmount}}"
						data-uom="{{uom}}"
						data-uomcode="{{uom_code}}"
						data-rule="{{rule_id}}"
						data-rulecode="{{rule_code}}"
						data-policy="{{id_policy}}"
						data-policycode="{{policy_code}}"
						data-policyname="{{policy_name}}"
						data-vatcode="{{vat_group}}"
						data-vatrate="{{vat_rate}}"
						data-qty="{{qty}}"
						data-img="{{img}}"
						value="1" />
				</td>
				<td class="middle">
					<button class='btn btn-info btn-minier btn-block' id='btn-{{uuid}}' onclick="addFreeRow('{{uuid}}')">Add</button>
				</td>
			</tr>
		{{/if}}
	{{/each}}
</script>

<script id="row-template" type="text/x-handlebars-template">
	<tr id="row-{{no}}">
		<input type="hidden" id="product-id-{{no}}" value="0" />
		<input type="hidden" id="cost-{{no}}" value="0" />
		<input type="hidden" id="line-cost-{{no}}" value="0" />
		<input type="hidden" id="stdPrice-{{no}}" value="0" />
		<input type="hidden" id="price-{{no}}" value="0" />
		<input type="hidden" id="sellPrice-{{no}}" value="0" />
		<input type="hidden" id="sysSellPrice-{{no}}" value="0" />
		<input type="hidden" class="line-num" id="line-num-{{no}}" value="{{no}}" />
		<input type="hidden" id="disc-amount-{{no}}" value="0"/>
		<input type="hidden" id="line-disc-amount-{{no}}" value="0" />
		<input type="hidden" id="totalDiscPercent-{{no}}" value="0" />
		<input type="hidden" id="line-total-{{no}}" value="0" />
		<input type="hidden" id="vat-rate-{{no}}" value="0" />
		<input type="hidden" id="vat-amount-{{no}}" value="0" />
		<input type="hidden" id="vat-total-{{no}}" value="0" />
		<input type="hidden" id="sys-disc-label-{{no}}"  value="0" />
		<input type="hidden" id="uom-code-{{no}}" value="" />
		<input type="hidden" class="disc-diff" id="disc-diff-{{no}}" value="0" />
		<input type="hidden" id="rule-id-{{no}}" value="" />
		<input type="hidden" class="policy" id="policy-id-{{no}}" data-code="" data-name="" data-uid="{{no}}" value="" />
		<input type="hidden" class="disc-error" id="disc-error-{{no}}" value="0" data-id="{{no}}"/>
		<input type="hidden" class="is-free" id="is-free-{{no}}" value="0" data-id="{{no}}" data-parent="" data-parentrow=""/>
		<input type="hidden" id="{{uid}}" data-id="{{no}}" value="{{no}}"/>
		<input type="hidden" id="disc-type-{{no}}" value="P" />
		<input type="hidden" id="count-stock-{{no}}" value="1" />
		<input type="hidden" id="allow-change-discount-{{no}}" value="1" />

		<td class="middle text-center fix-no no handle" id="no-{{no}}" scope="row"></td>
		<td class="middle text-center fix-chk" scope="row">
			<input type="checkbox" class="ace del-chk" value="{{no}}"/>
			<span class="lbl"></span>
		</td>
		<td class="middle text-center fix-img" scope="row" id="img-{{no}}"></td>
		<td class="middle fix-item" scope="row">
			<input type="text" class="form-control input-sm item-code" data-id="{{no}}" id="itemCode-{{no}}" />
		</td>
		<td class="middle fix-desc" scope="row">
			<input type="text" class="form-control input-sm item-name" data-id="{{no}}" id="itemName-{{no}}" />
		</td>

		<td class="middle">
			<select class="form-control input-sm whs" data-id="{{no}}" id="whs-{{no}}" onchange="getStock('{{no}}')">
				<option value=""></option>
				<?php echo $whsList; ?>
			</select>
		</td>

		<td class="middle">
			<input type="text" class="form-control input-sm text-right" id="instock-{{no}}" disabled/>
		</td>

		<td class="middle">
			<select class="form-control input-sm quota" data-id="{{no}}" id="quota-{{no}}" onchange="getStock('{{no}}')">
				<option value=""></option>
				<?php echo $quotaList; ?>
			</select>
		</td>

		<td class="middle">
			<input type="text" class="form-control input-sm text-right" id="team-{{no}}" disabled/>
		</td>

		<td class="middle">
			<input type="text" class="form-control input-sm text-right" id="commit-{{no}}" disabled/>
		</td>

		<td class="middle">
			<input type="text" class="form-control input-sm text-right" id="available-{{no}}" disabled/>
		</td>

		<td class="middle">
			<input type="text" class="form-control input-sm text-right" id="master-pack-{{no}}" value="{{masterPack}}" disabled />
		</td>

		<td class="middle">
			<input type="number" class="form-control input-sm text-right line-qty" data-id="{{no}}" id="line-qty-{{no}}" onkeyup="recalAmount('{{no}}')" />
		</td>
		<td class="middle">
			<input type="text" class="form-control input-sm text-center" id="uom-{{no}}" disabled/>
		</td>

		<td class="middle">
			<input type="text" class="form-control input-sm text-right number" id="stdPrice-label-{{no}}" disabled/>
		</td>

		<td class="middle">
			<input type="text" class="form-control input-sm text-right number" id="price-label-{{no}}" onchange="recalAmount('{{no}}')" disabled/>
		</td>

		<td class="middle">
			<input type="text" class="form-control input-sm text-right r" id="disc-label-{{no}}" onchange="recalDiscount('{{no}}')"/>
		</td>
		<td class="middle">
			<input type="text" class="form-control input-sm text-center" id="vat-code-{{no}}" value="" disabled/>
		</td>

		<td class="middle">
			<input type="text" class="form-control input-sm text-right" id="sell-price-{{no}}" value="" readonly disabled>
		</td>

		<td class="middle">
			<input type="text" class="form-control input-sm text-right number input-amount" id="total-label-{{no}}" readonly disabled />
		</td>
		<td class="middle">
			<input type="text" class="form-control input-sm text-center" id="disc-rule-{{no}}" value="" readonly disabled />
		</td>
		<td class="middle text-center">
		</td>
	</tr>
</script>


<script id="free-row-template" type="text/x-handlebars-template">
	<tr id="row-{{no}}" class="free-row">
		<input type="hidden" id="product-id-{{no}}" value="{{product_id}}" />
		<input type="hidden" id="cost-{{no}}" value="{{cost}}" />
		<input type="hidden" id="line-cost-{{no}}" value="{{lineCost}}" />
		<input type="hidden" id="stdPrice-{{no}}" value="{{stdPrice}}" />
		<input type="hidden" id="price-{{no}}" value="{{price}}" />
		<input type="hidden" id="sellPrice-{{no}}" value="{{sellPrice}}" />
		<input type="hidden" id="sysSellPrice-{{no}}" value="{{sellPrice}}" />
		<input type="hidden" class="line-num" id="line-num-{{no}}" value="{{no}}" />
		<input type="hidden" id="disc-amount-{{no}}" value="{{discAmount}}"/>
		<input type="hidden" id="line-disc-amount-{{no}}" value="{{lineDiscAmount}}" />
		<input type="hidden" id="totalDiscPercent-{{no}}" value="{{discPercent}}" />
		<input type="hidden" id="line-total-{{no}}" value="0" />
		<input type="hidden" id="vat-rate-{{no}}" value="{{vat_rate}}" />
		<input type="hidden" id="vat-amount-{{no}}" value="0" />
		<input type="hidden" id="vat-total-{{no}}" value="0" />
		<input type="hidden" id="sys-disc-label-{{no}}"  value="{{discPercent}}" />
		<input type="hidden" id="uom-code-{{no}}" value="{{uom_code}}" />
		<input type="hidden" class="disc-diff" id="disc-diff-{{no}}" value="0" />
		<input type="hidden" id="rule-id-{{no}}" value="{{rule_id}}" />
		<input type="hidden" class="policy" id="policy-id-{{no}}" data-code="{{policy_code}}" data-name="{{policy_name}}" data-uid="{{no}}" value="{{policy_id}}" />
		<input type="hidden" class="disc-error" id="disc-error-{{no}}" value="0" data-id="{{no}}"/>
		<input type="hidden" class="is-free" id="is-free-{{no}}" value="1" data-id="{{no}}" data-parent="{{parent_uid}}" data-parentrow="{{parent_row}}"/>
		<input type="hidden" id="{{uid}}" data-id="{{no}}" value="{{no}}"/>
		<input type="hidden" id="disc-type-{{no}}" value="F" />
		<input type="hidden" id="count-stock-{{no}}" value="1" />
		<input type="hidden" id="allow-change-discount-{{no}}" value="0" />


		<td class="middle text-center fix-no no handle" id="no-{{no}}" scope="row"></td>
		<td class="middle text-center fix-chk" scope="row">
			<input type="checkbox" class="ace del-chk" value="{{no}}"/>
			<span class="lbl"></span>
		</td>
		<td class="middle text-center fix-img" scope="row" id="img-{{no}}"><img src="{{img}}" width="40" height="40" /></td>
		<td class="middle fix-item" scope="row">
			<input type="text" class="form-control input-sm item-code" data-id="{{no}}" id="itemCode-{{no}}" value="{{product_code}}" disabled/>
		</td>
		<td class="middle fix-desc" scope="row">
			<input type="text" class="form-control input-sm item-name" data-id="{{no}}" id="itemName-{{no}}" value="{{product_name}}" disabled/>
		</td>

		<td class="middle">
			<select class="form-control input-sm whs" data-id="{{no}}" id="whs-{{no}}" onchange="getStock('{{no}}')">
				<option value=""></option>
				<?php echo $whsList; ?>
			</select>
		</td>

		<td class="middle">
			<input type="text" class="form-control input-sm" id="instock-{{no}}" disabled/>
		</td>

		<td class="middle">
			<select class="form-control input-sm quota" data-id="{{no}}" id="quota-{{no}}" onchange="getStock('{{no}}')">
				<option value=""></option>
				<?php echo $quotaList; ?>
			</select>
		</td>

		<td class="middle">
			<input type="text" class="form-control input-sm" id="team-{{no}}" disabled/>
		</td>

		<td class="middle">
			<input type="text" class="form-control input-sm" id="commit-{{no}}" disabled/>
		</td>

		<td class="middle">
			<input type="text" class="form-control input-sm" id="available-{{no}}" disabled/>
		</td>

		<td class="middle">
			<input type="text" class="form-control input-sm text-right" id="master-pack-{{no}}" value="{{masterPack}}" disabled />
		</td>

		<td class="middle">
			<input type="number" class="form-control input-sm text-right line-qty" data-id="{{no}}" id="line-qty-{{no}}" value="{{qty}}" disabled/>
		</td>
		<td class="middle">
			<input type="text" class="form-control input-sm text-center" id="uom-{{no}}" value="{{uom_name}}" disabled/>
		</td>

		<td class="middle">
			<input type="text" class="form-control input-sm text-right number" id="stdPrice-label-{{no}}" value="{{stdPriceLabel}}" readonly disabled/>
		</td>

		<td class="middle">
			<input type="text" class="form-control input-sm text-right number" id="price-label-{{no}}" value="{{priceLabel}}" readonly disabled/>
		</td>

		<td class="middle">
			<input type="text" class="form-control input-sm text-right r" id="disc-label-{{no}}" value="{{discPercent}}" disabled/>
		</td>
		<td class="middle">
			<input type="text" class="form-control input-sm text-center" id="vat-code-{{no}}" value="{{vat_code}}" disabled/>
		</td>

		<td class="middle">
			<input type="text" class="form-control input-sm text-right" id="sell-price-{{no}}" value="{{sellPriceLabel}}" readonly disabled>
		</td>

		<td class="middle">
			<input type="text" class="form-control input-sm text-right number input-amount" id="total-label-{{no}}" value="0.00" readonly disabled />
		</td>
		<td class="middle">
			<input type="text" class="form-control input-sm text-center" id="disc-rule-{{no}}" value="{{rule_code}}" readonly disabled />
		</td>
		<td class="middle text-center">Y</td>
	</tr>
</script>

<script id="free-input-template" type="text/x-handlebars-template">
	<input type="hidden" class="free-item" id="free-{{rule_id}}" value="{{freeQty}}" data-id="{{uid}}" data-valid="0" data-rule="" data-picked="0" data-uid="{{uid}}" />
</script>

<script id="free-btn-template" type="text/x-handlebars-template">
	<button type="button" class="btn btn-sm btn-primary free-btn" id="btn-free-{{rule_id}}" data-parent="{{uid}}" onclick="pickFreeItem('{{rule_id}}')">Premium {{freeQty}}</button>
</script>

<script id="ship-to-template" type="text/x-handlebars-template">
	{{#each this}}
		<option
			value="{{code}}"
			data-code="{{code}}"
			data-name="{{name}}"
			data-address="{{address}}"
			data-subdistrict="{{sub_district}}"
			data-district="{{district}}"
			data-province="{{province}}"
			data-postcode="{{postcode}}"
			data-country="{{country}}">{{code}} : {{name}}</option>
	{{/each}}
</script>

<script id="bill-to-template" type="text/x-handlebars-template">
	{{#each this}}
		<option
			value="{{code}}"
			data-code="{{code}}"
			data-name="{{name}}"
			data-address="{{address}}"
			data-subdistrict="{{sub_district}}"
			data-district="{{district}}"
			data-province="{{province}}"
			data-postcode="{{postcode}}"
			data-country="{{country}}">{{code}} : {{name}}</option>
	{{/each}}
</script>

<script id="promotion-applied-template" type="text/x-handlebars-template">
	Promotions applied : 
	{{#each this}}
		<span class="label label-info label-white middle pointer" title="{{code}}">{{name}} @row : {{#each rows}} {{no}},{{/each}}</span>
	{{/each}}
</script>
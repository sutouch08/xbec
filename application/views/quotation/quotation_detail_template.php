<script id="row-template" type="text/x-handlebars-template">
	<tr id="row-{{no}}">
		<input type="hidden" id="price-{{no}}" value="0" />
  	<input type="hidden" id="stdPrice-{{no}}" value="0" />
		<input type="hidden" id="sellPrice-{{no}}" value="0" />
		<input type="hidden" id="sysSellPrice-{{no}}" value="0" />
		<input type="hidden" class="line-num" id="line-num-{{no}}" value="{{no}}" />
		<input type="hidden" id="disc-amount-{{no}}" value="0"/>
		<input type="hidden" id="line-disc-amount-{{no}}" value="0" />
		<input type="hidden" id="totalDiscPercent-{{no}}" value="0.00" />
		<input type="hidden" id="line-total-{{no}}" value="0" />
		<input type="hidden" id="vat-rate-{{no}}" value="0" />
		<input type="hidden" id="vat-amount-{{no}}" value="0" />
		<input type="hidden" id="vat-total-{{no}}" value="0" />
		<input type="hidden" id="sys-disc-label-{{no}}"  value="0" />
		<input type="hidden" id="uom-code-{{no}}" value="" />
		<input type="hidden" class="disc-diff" id="disc-diff-{{no}}" value="0" />
		<input type="hidden" id="rule-id-{{no}}" value="" />
		<input type="hidden" id="policy-id-{{no}}" value="" />
		<input type="hidden" class="disc-error" id="disc-error-{{no}}" value="0" data-id="{{no}}"/>
		<input type="hidden" class="free-item" id="free-item-{{no}}" value="0" data-id="{{no}}"
			data-rule="0" data-valid="0" data-picked="0" data-uid="{{uid}}" data-parent=""/>
		<input type="hidden" class="is-free" id="is-free-{{no}}" value="0" data-id="{{no}}" data-parent="" data-parentrow=""/>
		<input type="hidden" id="{{uid}}" data-id="{{no}}" value="{{no}}"/>
		<input type="hidden" id="disc-type-{{no}}" value="{{discType}}" />

		<td class="middle text-center fix-no no handle" scope="row"></td>
		<td class="middle text-center fix-chk" scope="row">
			<input type="checkbox" class="ace del-chk" value="{{no}}"/>
			<span class="lbl"></span>
		</td>
		<td class="middle text-center fix-type" scope="row">
			<select class="form-control input-sm toggle-text" id="type-{{no}}" onchange="toggleText($(this))" data-id="{{no}}">
				<option value="0">-</option>
				<option value="1">Text</option>
			</select>
		</td>
		<td class="middle text-center fix-img" scope="row" id="img-{{no}}"></td>
		<td class="middle fix-code" scope="row">
			<input type="text" class="form-control input-sm item-code" data-id="{{no}}" id="itemCode-{{no}}" />
		</td>
		<td class="middle fix-desc" scope="row">
			<input type="text" class="form-control input-sm item-name" data-id="{{no}}" id="itemName-{{no}}" />
		</td>

		<td class="middle">
			<select class="form-control input-sm whs" data-id="{{no}}" id="whs-{{no}}" onchange="getStock('{{no}}')">
				<option value=""></option>
				<?php echo $whs; ?>
			</select>
		</td>

		<td class="middle">
			<input type="text" class="form-control input-sm text-right" id="instock-{{no}}" disabled/>
		</td>

		<td class="middle">
			<select class="form-control input-sm quota" data-id="{{no}}" id="quota-{{no}}" onchange="getStock('{{no}}')">
				<option value=""></option>
				<?php echo $qn; ?>
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
			<input type="text" class="form-control input-sm text-right" id="master-pack-{{no}}" value="" disabled />
		</td>

		<td class="middle">
			<input type="number" class="form-control input-sm text-right line-qty" data-id="{{no}}" id="line-qty-{{no}}" />
		</td>
		<td class="middle">
			<input type="text" class="form-control input-sm text-center" id="uom-{{no}}" disabled/>
		</td>
		<td class="middle">
			<input type="text" class="form-control input-sm text-right number price" data-id="{{no}}" id="price-label-{{no}}" value=""/>
		</td>

		<td class="middle">
			<input type="text" class="form-control input-sm text-right disc" id="disc-label-{{no}}" onchange="recalDiscount('{{no}}')"/>
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
	</tr>
</script>

<script id="normal-template" type="text/x-handlebars-template">
	<input type="hidden" id="price-{{no}}" value="0" />
	<input type="hidden" id="stdPrice-{{no}}" value="0" />
	<input type="hidden" id="sellPrice-{{no}}" value="0" />
	<input type="hidden" id="sysSellPrice-{{no}}" value="0" />
	<input type="hidden" class="line-num" id="line-num-{{no}}" value="{{no}}" />
	<input type="hidden" id="disc-amount-{{no}}" value="0"/>
	<input type="hidden" id="line-disc-amount-{{no}}" value="0" />
	<input type="hidden" id="totalDiscPercent-{{no}}" value="0.00" />
	<input type="hidden" id="line-total-{{no}}" value="0" />
	<input type="hidden" id="vat-rate-{{no}}" value="0" />
	<input type="hidden" id="vat-amount-{{no}}" value="0" />
	<input type="hidden" id="vat-total-{{no}}" value="0" />
	<input type="hidden" id="sys-disc-label-{{no}}"  value="0" />
	<input type="hidden" id="uom-code-{{no}}" value="" />
	<input type="hidden" class="disc-diff" id="disc-diff-{{no}}" value="0" />
	<input type="hidden" id="rule-id-{{no}}" value="" />
	<input type="hidden" id="policy-id-{{no}}" value="" />
	<input type="hidden" class="disc-error" id="disc-error-{{no}}" value="0" data-id="{{no}}"/>
	<input type="hidden" class="free-item" id="free-item-{{no}}" value="0" data-id="{{no}}"
		data-rule="0" data-valid="0" data-picked="0" data-uid="{{uid}}" data-parent=""/>
	<input type="hidden" class="is-free" id="is-free-{{no}}" value="0" data-id="{{no}}" data-parent="" data-parentrow=""/>
	<input type="hidden" id="{{uid}}" data-id="{{no}}" value="{{no}}"/>
	<input type="hidden" id="disc-type-{{no}}" value="{{discType}}" />

	<td class="middle text-center fix-no no handle" scope="row"></td>
	<td class="middle text-center fix-chk" scope="row">
		<input type="checkbox" class="ace del-chk" value="{{no}}"/>
		<span class="lbl"></span>
	</td>
	<td class="middle text-center fix-type" scope="row">
		<select class="form-control input-sm toggle-text" id="type-{{no}}" onchange="toggleText($(this))" data-id="{{no}}">
			<option value="0" selected>-</option>
			<option value="1">Text</option>
		</select>
	</td>
	<td class="middle text-center fix-img" scope="row" id="img-{{no}}"></td>
	<td class="middle fix-code" scope="row">
		<input type="text" class="form-control input-sm item-code" data-id="{{no}}" id="itemCode-{{no}}" />
	</td>
	<td class="middle fix-desc" scope="row">
		<input type="text" class="form-control input-sm item-name" data-id="{{no}}" id="itemName-{{no}}" />
	</td>

	<td class="middle">
		<select class="form-control input-sm whs" data-id="{{no}}" id="whs-{{no}}" onchange="getStock('{{no}}')">
			<option value=""></option>
			<?php echo $whs; ?>
		</select>
	</td>

	<td class="middle">
		<input type="text" class="form-control input-sm text-right" id="instock-{{no}}" disabled/>
	</td>

	<td class="middle">
		<select class="form-control input-sm quota" data-id="{{no}}" id="quota-{{no}}" onchange="getStock('{{no}}')">
			<option value=""></option>
			<?php echo $qn; ?>
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
    <input type="text" class="form-control input-sm text-right" id="master-pack-{{no}}" value="" disabled />
  </td>

	<td class="middle">
		<input type="number" class="form-control input-sm text-right line-qty" data-id="{{no}}" id="line-qty-{{no}}" />
	</td>
	<td class="middle">
		<input type="text" class="form-control input-sm text-center" id="uom-{{no}}" disabled/>
	</td>
	<td class="middle">
		<input type="text" class="form-control input-sm text-right number price" data-id="{{no}}" id="price-label-{{no}}" value=""/>
	</td>

	<td class="middle">
		<input type="text" class="form-control input-sm text-right disc" id="disc-label-{{no}}" onchange="recalDiscount('{{no}}')"/>
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
</script>

<script id="text-template" type="text/x-handlebars-template">
	<td class="middle text-center fix-no no handle" scope="row"></td>
	<td class="middle text-center fix-chk" scope="row">
		<input type="checkbox" class="ace del-chk" value="{{no}}"/>
		<span class="lbl"></span>
	</td>
	<td class="middle text-center fix-type" scope="row">
		<select class="form-control input-sm toggle-text" id="type-{{no}}" onchange="toggleText($(this))" data-id="{{no}}">
			<option value="0">-</option>
			<option value="1" selected>Text</option>
		</select>
	</td>
	<td colspan="3" class="fix-text" scope="row">
    <textarea id="text-{{no}}" class="autosize autosize-transition width-100" style="height:100px;"></textarea>
  </td>
	<td colspan="14"></td>
</script>

<script id="ship-to-template" type="text/x-handlebars-template">
	{{#each this}}
		<option value="{{code}}">{{code}} : {{name}}</option>
	{{/each}}
</script>

<script id="bill-to-template" type="text/x-handlebars-template">
	{{#each this}}
		<option value="{{code}}">{{code}} : {{name}}</option>
	{{/each}}
</script>
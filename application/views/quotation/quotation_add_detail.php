<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-5">
		<button type="button" class="btn btn-white btn-info" onclick="addRow()">Add Row</button>
		<button type="button" class="btn btn-white btn-warning" onclick="removeRow()">Delete Row</button>
		<button type="button" class="btn btn-white btn-success" onclick="getImportFile()">Import Excel</button>
		<button type="button" class="btn btn-white btn-purple" onclick="getTemplateFile()">Download Template</button>
	</div>

	<div class="divider-hidden"></div>

	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 border-1 table-responsive" style="padding:0px; margin-left:5px; height:400px; overflow:auto;">
		<table class="table table-bordered border-1 tableFixHead table-narrow" style="width:2080px;">
			<thead>
				<tr class="freez">
					<th class="fix-width-40 middle text-center fix-header fix-no">#</th>
					<th class="fix-width-30 middle text-center fix-header fix-chk">
						<label>
							<input type="checkbox" class="ace" id="chk-all" onchange="toggleCheckAll(this)" />
							<span class="lbl"></span>
						</label>
					</th>
					<th class="fix-width-60 middle text-center fix-header fix-type">Type</th>
					<th class="fix-width-50 middle text-center fix-header fix-img">Image</th>
					<th class="fix-width-100 middle text-center fix-header fix-code">Item Code</th>
					<th class="fix-width-300 middle text-center fix-header fix-desc">Description.</th>
					<th class="fix-width-100 middle text-center">Warehouse</th>
					<th class="fix-width-80 middle text-center">In Stock</th>
					<th class="fix-width-100 middle text-center">Quota No.</th>
					<th class="fix-width-80 middle text-center">Quota</th>
					<th class="fix-width-80 middle text-center">Commited</th>
					<th class="fix-width-80 middle text-center">Available</th>
					<th class="fix-width-100 middle text-center">Master pack</th>
					<th class="fix-width-100 middle text-center">Quantity</th>
					<th class="fix-width-100 middle text-center">Uom</th>
					<th class="fix-width-100 middle text-center">Price</th>
					<th class="fix-width-150 middle text-center">Discount(%)</th>
					<th class="fix-width-80 middle text-center">Tax Code</th>
					<th class="fix-width-150 middle text-center">Price after discount</th>
					<th class="fix-width-150 middle text-center">Amount before tax</th>
				</tr>
			</thead>
			<tbody id="details-template">
				<?php $no = 1; ?>
				<?php $uid = genUid(8); ?>
				<?php $uuid = genUid(8); ?>
				<tr id="row-<?php echo $uid; ?>">
					<input type="hidden" id="price-<?php echo $uid; ?>" value="0" />
					<input type="hidden" id="stdPrice-<?php echo $uid; ?>" value="0" />
					<input type="hidden" id="sellPrice-<?php echo $uid; ?>" value="0" />
					<input type="hidden" id="sysSellPrice-<?php echo $uid; ?>" value="0" />
					<input type="hidden" class="line-num" id="line-num-<?php echo $uid; ?>" value="<?php echo $uid; ?>" />
					<input type="hidden" id="disc-amount-<?php echo $uid; ?>" value="0" />
					<input type="hidden" id="line-disc-amount-<?php echo $uid; ?>" value="0" />
					<input type="hidden" id="totalDiscPercent-<?php echo $uid; ?>" value="0.00" />
					<input type="hidden" id="line-total-<?php echo $uid; ?>" value="0" />
					<input type="hidden" id="vat-rate-<?php echo $uid; ?>" value="0" />
					<input type="hidden" id="vat-amount-<?php echo $uid; ?>" value="0" />
					<input type="hidden" id="vat-total-<?php echo $uid; ?>" value="0" />
					<input type="hidden" id="sys-disc-label-<?php echo $uid; ?>" value="" />
					<input type="hidden" id="uom-code-<?php echo $uid; ?>" value="" />
					<input type="hidden" class="disc-diff" id="disc-diff-<?php echo $uid; ?>" value="0" />
					<input type="hidden" id="rule-id-<?php echo $uid; ?>" value="" />
					<input type="hidden" id="policy-id-<?php echo $uid; ?>" value="" />
					<input type="hidden" class="disc-error" id="disc-error-<?php echo $uid; ?>" value="0" data-id="<?php echo $uid; ?>" />
					<input type="hidden" class="free-item" id="free-item-<?php echo $uid; ?>" value="" data-id="<?php echo $uid; ?>"
						data-valid="0" data-rule="" data-picked="0"
						data-uid="<?php echo $uuid; ?>" data-parent="" />
					<input type="hidden" class="is-free" id="is-free-<?php echo $uid; ?>"
						value="0" data-id="<?php echo $uid; ?>"
						data-parent="" data-parentrow="" />
					<input type="hidden" id="<?php echo $uuid; ?>" data-id="<?php echo $uid; ?>" value="<?php echo $uid; ?>" />
					<input type="hidden" id="disc-type-<?php echo $uid; ?>" value="P" />

					<td class="middle text-center fix-no no handle" scope="row"><?php echo $no; ?></td>
					<td class="middle text-center fix-chk" scope="row">
						<input type="checkbox" class="ace del-chk" value="<?php echo $uid; ?>" />
						<span class="lbl"></span>
					</td>
					<td class="middle text-center fix-type" scope="row">
						<select class="form-control input-sm toggle-text" id="type-1" onchange="toggleText($(this))" data-id="<?php echo $uid; ?>">
							<option value="0">-</option>
							<option value="1">Text</option>
						</select>
					</td>
					<td class="middle text-center fix-img" scope="row" id="img-<?php echo $uid; ?>">
					</td>
					<td class="middle fix-code" scope="row">
						<input type="text" class="form-control input-sm item-code" data-id="<?php echo $uid; ?>" id="itemCode-<?php echo $uid; ?>" value="" />
					</td>
					<td class="middle fix-desc" scope="row">
						<input type="text" class="form-control input-sm item-name" data-id="<?php echo $uid; ?>" id="itemName-<?php echo $uid; ?>" value="" />
					</td>

					<td class="middle">
						<select class="form-control input-sm whs" data-id="<?php echo $uid; ?>" id="whs-<?php echo $uid; ?>" onchange="getStock('<?php echo $uid; ?>')">
							<option value=""></option>
							<?php echo $whs; ?>
						</select>
					</td>

					<td class="middle">
						<input type="text" class="form-control input-sm text-right" id="instock-<?php echo $uid; ?>" value="" disabled />
					</td>

					<td class="middle">
						<select class="form-control input-sm quota" data-id="<?php echo $uid; ?>" id="quota-<?php echo $uid; ?>" onchange="getStock('<?php echo $uid; ?>')">
							<option value=""></option>
							<?php echo $qn; ?>
						</select>
					</td>

					<td class="middle">
						<input type="text" class="form-control input-sm text-right" id="team-<?php echo $uid; ?>" value="" disabled />
					</td>

					<td class="middle">
						<input type="text" class="form-control input-sm text-right" id="commit-<?php echo $uid; ?>" value="" disabled />
					</td>

					<td class="middle">
						<input type="text" class="form-control input-sm text-right" id="available-<?php echo $uid; ?>" value="" disabled />
					</td>

					<td class="middle">
						<input type="text" class="form-control input-sm text-right" id="master-pack-<?php echo $uid; ?>" value="" disabled />
					</td>

					<td class="middle">
						<input type="number" class="form-control input-sm text-right line-qty"
							data-id="<?php echo $uid; ?>" id="line-qty-<?php echo $uid; ?>"
							value="" />
					</td>
					<td class="middle">
						<input type="text" class="form-control input-sm text-center" id="uom-<?php echo $uid; ?>" value="" disabled />
					</td>
					<td class="middle">
						<input type="text" class="form-control input-sm text-right number price"
							data-id="<?php echo $uid; ?>"
							id="price-label-<?php echo $uid; ?>"
							onchange="recalAmount('<?php echo $uid; ?>')"
							value="" />
					</td>

					<td class="middle">
						<input type="text" class="form-control input-sm text-right disc" id="disc-label-<?php echo $uid; ?>"
							value="" onchange="recalDiscount('<?php echo $uid; ?>')" />
					</td>
					<td class="middle">
						<input type="text" class="form-control input-sm text-center" id="vat-code-<?php echo $uid; ?>" value="" disabled />
					</td>

					<td class="middle">
						<input type="text" class="form-control input-sm text-right" id="sell-price-<?php echo $uid; ?>" value="" readonly disabled>
					</td>

					<td class="middle">
						<input type="text" class="form-control input-sm text-right number input-amount" id="total-label-<?php echo $uid; ?>" value="" readonly disabled />
					</td>
				</tr>
				<?php $no++; ?>
			</tbody>
		</table>
	</div>
	<input type="hidden" id="first-uid" value="<?php echo $uid; ?>" />	
</div>
<hr />
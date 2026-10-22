
var click = 0;

function save(option = 0) {
	//-- 0 = save, 1 = draft
	if(click !== 0) {
		return;
	}

	click = 1;

	clearErrorByClass('r');
	clearErrorByClass('disc');

	let mustApprove = 0;
	let max_diff = 0;

	let ds = {		
		'isDraft': option === 1 ? 1 : 0,
		'SlpCode': $('#sale_id').val(),
		'CardCode': $('#CardCode').val().trim(),
		'CardName': $('#CardName').val().trim(),
		'ContactPerson': $('#contact').val().trim(),
		'Phone': $('#phone').val().trim(),
		'Payment': $('#payment').val(),
		'Channels': $('#channels').val(),
		'ProjectCode': $('#projects').val(),
		'dimCode5': $('#dimCode5').val(),
		'OwnerCode': $('#owner').val(),
		'ShipToCode': $('#shipToCode').val(),
		'ShipTo': $('#ShipTo').val(),		
		'DocDate': $('#DocDate').val(),
		'DocDueDate': $('#ShipDate').val(),
		'TextDate': $('#TextDate').val(),
		'PayToCode': $('#billToCode').val(),
		'BillTo': $('#BillTo').val(),		
		'comments': $('#comments').val().trim(),
		'discPrcnt': parseDefaultFloat($('#discPrcnt').val(), 0),
		'disAmount': parseDefaultFloat($('#discAmount').val(), 0),
		'roundDif': parseDefaultFloat($('#roundDif').val(), 0),
		'tax': parseDefaultFloat($('#tax').val(), 0), //-- VatSum
		'docTotal': parseDefaultFloat($('#docTotal').val(), 0),
		'mustApprove': mustApprove > 0 ? 1 : 0,
		'maxDiff': max_diff,
		'VatGroup': $('#vat_code').val(),
		'VatRate': $('#vat_rate').val(),
		'sale_team': $('#sale_team').val(),
		'user_id': $('#user_id').val(),
		'uname': $('#uname').val(),
		'details': []
	};
	
	if (ds.CardCode.length === 0) {
		swal("กรุณาระบุลูกค้า");
		$('#CardCode').hasError();
		click = 0;		
		return false;
	}
	
	if (!isDate(ds.DocDate)) {
		swal("Invalid Posting Date");
		$('#DocDate').hasError();
		click = 0;
		return false;
	}
	
	if (!isDate(ds.DocDueDate)) {
		swal("Invalid Delivery Date");
		$('#DocDueDate').hasError();
		click = 0;
		return false;
	}
	
	if (!isDate(ds.TextDate)) {
		swal("Invalid Document Date");
		$('#TextDate').hasError();
		click = 0;
		return false;
	}
	
	if (ds.OwnerCode == '') {
		swal("Please Select Owner");
		$('#owner').hasError();
		click = 0;
		return false;
	}
	
	if (ds.dimCode5 == '') {
		swal("กรุณาเลือกแผนก");
		$('#dimCode5').hasError();
		click = 0;
		return false;
	}

	let disc_error = 0;
	
	//--- check discount
	$('.disc-error').each(function () {
		let no = $(this).data('id');
		if ($(this).val() == 1) {
			$(`#disc-label-${no}`).hasError();
			disc_error++;
		}
	});

	if (disc_error > 0) {
		swal({
			title: 'Invalid Discount',
			type: 'error'
		});

		click = 0;
		return false;
	}

	if (ds.discPrcnt < 0 || ds.discPrcnt > 100) {
		swal({
			title: "Invalid bill discount",
			type: 'error'
		});

		click = 0;
		return false;
	}
			
	let lineNum = 0;

	$('.toggle-text').each(function () {
		let no = $(this).data('id');
		let type = $(this).val();
		if (type == 0) {
			let itemCode = $(`#itemCode-${no}`).val();

			if (itemCode.length) {
				//--- ถ้ามีการระบุข้อมูล
				ds.details.push({
					"type": type,
					"LineNum": lineNum,
					"ItemCode": itemCode,
					"Description": $(`#itemName-${no}`).val(),
					"StdPrice": $(`#stdPrice-${no}`).val(),
					"Price": $(`#price-${no}`).val(),
					"SellPrice": $(`#sellPrice-${no}`).val(),
					"sysSellPrice": $(`#sysSellPrice-${no}`).val(),
					"Quantity": $(`#line-qty-${no}`).val(),
					"UomCode": $(`#uom-code-${no}`).val(),
					"discLabel": $(`#disc-label-${no}`).val(),
					"sysDiscLabel": $(`#sys-disc-label-${no}`).val(),
					"discAmount": $(`#disc-amount-${no}`).val(),
					"totalDiscAmount": $(`#line-disc-amount-${no}`).val(),
					"DiscPrcnt": $(`#totalDiscPercent-${no}`).val(),
					"VatGroup": $(`#vat-code-${no}`).val(),
					"VatRate": $(`#vat-rate-${no}`).val(),
					"VatAmount": $(`#vat-amount-${no}`).val(),
					"totalVatAmount": $(`#vat-total-${no}`).val(),
					"LineTotal": $(`#line-total-${no}`).val(),
					"policy_id": $(`#policy-id-${no}`).val(),
					"rule_id": $(`#rule-id-${no}`).val(),
					'discDiff': $(`#disc-diff-${no}`).val(),
					'uid': $(`#free-item-${no}`).data('uid'),
					'free_item': $(`#free-item-${no}`).val(),
					'parent_uid': $(`#free-item-${no}`).data('parent'),
					'picked': $(`#free-item-${no}`).data('picked'),
					'is_free': $(`#is-free-${no}`).val(),
					'discType': $(`#disc-type-${no}`).val(),
					'WhsCode': $(`#whs-${no}`).val(),
					'QuotaNo': $(`#quota-${no}`).val(),
					'sale_team': $('#sale_team').val()
				});

				lineNum++;
			}
		}
		else {
			let text = $(`#text-${no}`).val().trim();
			if (text.length) {
				ds.details.push({
					"type": 1,
					"LineText": text,
					"AfLineNum": lineNum - 1
				});				
			}
		}
	}); //--- end each function


	if (ds.details.length === 0) {
		swal("ไม่พบรายการสินค้า");
		click = 0;
		return false;
	}
		
	load_in();

	$.ajax({
		url: `${HOME}add`,
		type: 'POST',
		cache: false,
		data: JSON.stringify(ds),
		success: function (rs) {
			click = 0;
			load_out();
			if (isJson(rs)) {
				let ds = JSON.parse(rs);
				if (ds.status === 'success') {
					if(ds.ex == 1)
					{
						swal({
							title: 'Export Failed',
							text:'Create Quotation success but export data to SAP failed. <br>Please export data manually.',
							type:'info',
							html: true
						}, function() {
							viewDetail(ds.code);
						});
					}
					else {
						swal({
							title: 'Success',
							type: 'success',
							timer: 1000
						});

						setTimeout(() => { viewDetail(ds.code);	}, 1200);
					}					
				}
				else {
					showError(ds.message);
				}
			}
			else {
				showError(rs);
			}			
		},
		error:function(rs) {
			click = 0;
			showError(rs);
		}
	});
}

function update(option = 0) {
	//-- option 0 = save, 1 = draft
	if(click !== 0) {
		return;
	}

	click = 1;

	clearErrorByClass('r');
	clearErrorByClass('disc');

	let mustApprove = 0;
	let max_diff = 0;

	let ds = {
		'isDraft' : option === 1 ? 1 : 0,
		'code' : $('#code').val(),
		'SlpCode' : $('#sale_id').val(),
		'OwnerCode' : $('#owner').val(),
		'CardCode' : $('#CardCode').val().trim(),
		'CardName' : $('#CardName').val().trim(),
		'ContactPerson' : $('#contact').val().trim(),
		'Phone' : $('#phone').val().trim(),
		'Payment' : $('#payment').val(),
		'Channels' : $('#channels').val(),
		'ProjectCode' : $('#projects').val(),		
		'dimCode5' : $('#dimCode5').val(),
		'ShipToCode' : $('#shipToCode').val(),
		'ShipTo' : $('#ShipTo').val(),
		'PayToCode' : $('#billToCode').val(),
		'BillTo' : $('#BillTo').val(),
		'DocDate' : $('#DocDate').val(),
		'DocDueDate' : $('#ShipDate').val(),
		'TextDate' : $('#TextDate').val(),
		'comments' : $('#comments').val().trim(),
		'discPrcnt' : parseDefaultFloat($('#discPrcnt').val(), 0),
		'discAmount' : parseDefaultFloat($('#discAmount').val(), 0),
		'roundDif' : parseDefaultFloat($('#roundDif').val(), 0),
		'tax' : parseDefaultFloat($('#tax').val(), 0),
		'docTotal' : parseDefaultFloat($('#docTotal').val(), 0),
		'mustApprove' : mustApprove > 0 ? 1 : 0,
		'maxDiff' : max_diff,
		'VatGroup' : $('#vat_code').val(),
		'VatRate' : $('#vat_rate').val(),
		'sale_team' : $('#sale_team').val(),
		'user_id' : $('#user_id').val(),
		'uname' : $('#uname').val(),
		'details' : []
	};

	if (ds.CardCode.length === 0) {
		swal("กรุณาระบุลูกค้า");
		$('#CardCode').hasError();
		click = 0;
		return false;
	}

	if (!isDate(ds.DocDate)) {
		swal("Invalid Posting Date");
		$('#DocDate').hasError();
		click = 0;
		return false;
	}

	if (!isDate(ds.DocDueDate)) {
		swal("Invalid Delivery Date");
		$('#DocDueDate').hasError();
		click = 0;
		return false;
	}

	if (!isDate(ds.TextDate)) {
		swal("Invalid Document Date");
		$('#TextDate').hasError();
		click = 0;
		return false;
	}

	if (ds.OwnerCode == '') {
		swal("Please Select Owner");
		$('#owner').hasError();
		click = 0;
		return false;
	}

	if (ds.dimCode5 == '') {
		swal("กรุณาเลือกแผนก");
		$('#dimCode5').hasError();
		click = 0;
		return false;
	}

	let disc_error = 0;

	//--- check discount
	$('.disc-error').each(function () {
		let no = $(this).data('id');
		if ($(this).val() == 1) {
			$(`#disc-label-${no}`).hasError();
			disc_error++;
		}
	});

	if (disc_error > 0) {
		swal({
			title: 'Invalid Discount',
			type: 'error'
		});

		click = 0;
		return false;
	}

	if (ds.discPrcnt < 0 || ds.discPrcnt > 100) {
		swal({
			title: "Invalid bill discount",
			type: 'error'
		});

		click = 0;
		return false;
	}

	let lineNum = 0;

	$('.toggle-text').each(function () {
		let no = $(this).data('id');
		let type = $(this).val();
		if (type == 0) {
			let itemCode = $(`#itemCode-${no}`).val();

			if (itemCode.length) {
				//--- ถ้ามีการระบุข้อมูล
				ds.details.push({
					"type": type,
					"LineNum": lineNum,
					"ItemCode": itemCode,
					"Description": $(`#itemName-${no}`).val(),
					"StdPrice": $(`#stdPrice-${no}`).val(),
					"Price": $(`#price-${no}`).val(),
					"SellPrice": $(`#sellPrice-${no}`).val(),
					"sysSellPrice": $(`#sysSellPrice-${no}`).val(),
					"Quantity": $(`#line-qty-${no}`).val(),
					"UomCode": $(`#uom-code-${no}`).val(),
					"discLabel": $(`#disc-label-${no}`).val(),
					"sysDiscLabel": $(`#sys-disc-label-${no}`).val(),
					"discAmount": $(`#disc-amount-${no}`).val(),
					"totalDiscAmount": $(`#line-disc-amount-${no}`).val(),
					"DiscPrcnt": $(`#totalDiscPercent-${no}`).val(),
					"VatGroup": $(`#vat-code-${no}`).val(),
					"VatRate": $(`#vat-rate-${no}`).val(),
					"VatAmount": $(`#vat-amount-${no}`).val(),
					"totalVatAmount": $(`#vat-total-${no}`).val(),
					"LineTotal": $(`#line-total-${no}`).val(),
					"policy_id": $(`#policy-id-${no}`).val(),
					"rule_id": $(`#rule-id-${no}`).val(),
					'discDiff': $(`#disc-diff-${no}`).val(),
					'uid': $(`#free-item-${no}`).data('uid'),
					'free_item': $(`#free-item-${no}`).val(),
					'parent_uid': $(`#free-item-${no}`).data('parent'),
					'picked': $(`#free-item-${no}`).data('picked'),
					'is_free': $(`#is-free-${no}`).val(),
					'discType': $(`#disc-type-${no}`).val(),
					'WhsCode': $(`#whs-${no}`).val(),
					'QuotaNo': $(`#quota-${no}`).val(),
					'sale_team': $('#sale_team').val()
				});

				lineNum++;
			}
		}
		else {
			let text = $(`#text-${no}`).val().trim();
			if (text.length) {
				ds.details.push({
					"type": 1,
					"LineText": text,
					"AfLineNum": lineNum - 1
				});
			}
		}
	}); //--- end each function

	if (ds.details.length === 0) {
		swal("ไม่พบรายการสินค้า");
		click = 0;
		return false;
	}

	load_in();

	$.ajax({
		url: `${HOME}update`,
		type: 'POST',
		cache: false,
		data: JSON.stringify(ds),
		success: function (rs) {
			click = 0;
			load_out();
			if (isJson(rs)) {
				let ds = JSON.parse(rs);
				if (ds.status === 'success') {
					if (ds.ex == 1) {
						swal({
							title: 'Export Failed',
							text: 'Create Quotation success but export data to SAP failed. <br>Please export data manually.',
							type: 'info',
							html: true
						}, function () {
							viewDetail(ds.code);
						});
					}
					else {
						swal({
							title: 'Success',
							type: 'success',
							timer: 1000
						});

						setTimeout(() => { viewDetail(ds.code); }, 1200);
					}
				}
				else {
					showError(ds.message);
				}
			}
			else {
				showError(rs);
			}
		},
		error: function (rs) {
			click = 0;
			showError(rs);
		}
	});
}

$('#CardCode').autocomplete({
	source: `${BASE_URL}auto_complete/get_customer_code_and_name`,
	autoFocus: true,
	open: function (event) {
		var $ul = $(this).autocomplete('widget');
		$ul.css('width', 'auto');
	},
	close: function () {
		var rs = $(this).val();
		var cust = rs.split(' | ');
		if (cust.length === 2) {
			let code = cust[0];
			let name = cust[1];
			$('#CardCode').val(code);
			$('#CardName').val(name);

			get_customer(code);

			//---- create Address ship to
			get_address_ship_to_code(code);

			//---- create Address bill to
			get_address_bill_to_code(code);

			let uid = $('#first-uid').val();

			$('#itemCode-' + uid).focus();
		}
		else {
			$('#CardCode').val('');
			$('#CardName').val('');
			$('#priceList').val('');
			$('#payment').val(-1);
		}
	}
});

function get_customer(code) {
	$.ajax({
		url: `${HOME}get_customer_order_data`,
		type: 'GET',
		cache: false,
		data: {
			'CardCode': code
		},
		success: function (rs) {
			if (isJson(rs)) {
				let ds = JSON.parse(rs);
				$('#payment').val(ds.GroupNum);
				$('#priceList').val(ds.ListNum);
				$('#sale_id').val(ds.SlpCode).trigger('change');
				$('#sale_name').val(ds.sale_name);
			}
		}
	});
}

function get_address_ship_to_code(code) {
	$.ajax({
		url: `${HOME}get_address_ship_to_code`,
		type: 'GET',
		cache: false,
		data: {
			'CardCode': code
		},
		success: function (rs) {
			var rs = $.trim(rs);
			if (isJson(rs)) {
				let data = JSON.parse(rs);
				let source = $('#ship-to-template').html();
				let output = $('#shipToCode');
				render(source, data, output);
				get_address_ship_to();
			}
			else {
				$('#shipToCode').html('');
			}
		}
	});
}

function get_address_ship_to() {
	let h = {
		'CardCode': $('#CardCode').val(),
		'Address': $('#shipToCode').val()
	};
	
	$.ajax({
		url: `${HOME}get_address_ship_to`,
		type: 'GET',
		cache: false,
		data: h,
		success: function (rs) {			
			if (isJson(rs)) {
				let ds = JSON.parse(rs);
				let address = ds.address === "" ? "" : ds.address + " ";
				let sub_district = ds.sub_district === "" ? "" : ds.sub_district + " ";
				let district = ds.district === "" ? "" : ds.district + " ";
				let province = ds.province === "" ? "" : ds.province + " ";
				let postcode = ds.postcode === "" ? "" : ds.postcode + " "
				let country = ds.country === 'TH' ? '' : ds.countryName;
				let adr = address + sub_district + district + province + postcode + country;

				$('#ShipTo').val(adr);
			}
		}
	});
}

function get_address_bill_to_code(code) {
	$.ajax({
		url: `${HOME}get_address_bill_to_code`,
		type: 'GET',
		cache: false,
		data: {
			'CardCode': code
		},
		success: function (rs) {			
			if (isJson(rs)) {
				let data = JSON.parse(rs);
				let source = $('#bill-to-template').html();
				let output = $('#billToCode');
				render(source, data, output);

				get_address_bill_to();
			}
			else {
				$('#billToCode').html('');
			}
		}
	});
}

function get_address_bill_to() {
	let h = {
		'CardCode': $('#CardCode').val(),
		'Address': $('#billToCode').val()
	};
	
	$.ajax({
		url: `${HOME}get_address_bill_to`,
		type: 'GET',
		cache: false,
		data: h,
		success: function (rs) {			
			if (isJson(rs)) {
				let ds = JSON.parse(rs);
				let address = ds.address === "" ? "" : ds.address + " ";
				let sub_district = ds.sub_district === "" ? "" : ds.sub_district + " ";
				let district = ds.district === "" ? "" : ds.district + " ";
				let province = ds.province === "" ? "" : ds.province + " ";
				let postcode = ds.postcode === "" ? "" : ds.postcode + " "
				let country = ds.country === 'TH' ? '' : ds.countryName;
				let adr = address + sub_district + district + province + postcode + country;

				$('#BillTo').val(adr);
			}
		}
	});
}

async function addRow(no = null) {
	if (no === null) {
		no = uniqueId();
	}

	let data = { "no": no, "uid": uniqueId() };
	let source = $('#row-template').html();
	let output = $('#details-template');
	await render_append(source, data, output);	

	reIndex();
	await init();
	$(`#itemCode-${no}`).focus();
	return;
}

async function addItemRow(ds) {
	let no = uniqueId();
	await addRow(no);
	let price = parseDefaultFloat(ds.Price, 0.00);
	let stdPrice = parseDefaultFloat(ds.StdPrice, 0.00);
	let sellPrice = parseDefaultFloat(ds.SellPrice, 0.00);
	let lineTotal = parseDefaultFloat(ds.LineTotal, 0.00);
	
	$(`#stdPrice-${no}`).val(stdPrice);
	$(`#price-${no}`).val(price);
	$(`#sellPrice-${no}`).val(sellPrice);
	$(`#line-total-${no}`).val(lineTotal);
	$(`#disc-amount-${no}`).val(ds.discAmount);
	$(`#line-disc-amount-${no}`).val(ds.totalDiscAmount);
	$(`#vat-rate-${no}`).val(ds.VatRate);
	$(`#vat-amount-${no}`).val(ds.VatAmount);
	$(`#vat-total-${no}`).val(ds.TotalVatAmount);
	$(`#sys-disc-label-${no}`).val(ds.sysDiscLabel);	
	$(`#uom-code-${no}`).val(ds.UomCode);
	$(`#rule-id-${no}`).val(ds.rule_id);
	$(`#policy-id-${no}`).val(ds.policy_id);	
	$(`#free-item-${no}`).val(ds.freeQty);
	$(`#free-item-${no}`).data('rule', ds.rule_id);
	$(`#disc-type-${no}`).val(ds.discType);	
	$(`#itemCode-${no}`).val(ds.ItemCode);
	$(`#itemName-${no}`).val(ds.ItemName);
	$(`#instock-${no}`).val(addCommas(ds.instock));
	$(`#team-${no}`).val(addCommas(ds.team));
	$(`#commit-${no}`).val(addCommas(ds.commit));
	$(`#available-${no}`).val(addCommas(ds.available));
	$(`#master-pack-${no}`).val(ds.master_pack);
	$(`#line-qty-${no}`).val(ds.Qty);
	$(`#uom-${no}`).val(ds.UomName);
	$(`#stdPrice-label-${no}`).val(addCommas(stdPrice.toFixed(2)));
	$(`#price-label-${no}`).val(addCommas(price.toFixed(2)));
	$(`#sysSellPrice-${no}`).val(sellPrice);
	$(`#disc-label-${no}`).val(ds.discLabel);
	$(`#vat-code-${no}`).val(ds.VatGroup);
	$(`#sell-price-${no}`).val(addCommas(sellPrice.toFixed(2)));
	$(`#total-label-${no}`).val(addCommas(lineTotal.toFixed(2)));
	$(`#count-stock-${no}`).val(ds.count_stock);
	$(`#allow-change-discount-${no}`).val(ds.allow_change_discount);
	$(`#disc-rule-${no}`).val(ds.rule_code);
	$(`#img-${no}`).html('<img src="' + ds.image + '" width="40px;" height="40px;" />');	

	return;
}

async function removeRow() {
	$('.del-chk:checked').each(function () {
		let no = $(this).val();		
		$(`#row-${no}`).remove();		
	});

	$('#chk-all').prop('checked', false);

	reIndex();
	recalTotal();
}

async function removeEmptyRow() {
	$('.item-code').each(function () {
		let no = $(this).data('id');
		let itemCode = $(this).val();
		if (itemCode === '') {
			$(`#row-${no}`).remove();
		}
	});

	reIndex();
}

function getItemData(no) {
	let h = {
		'ItemCode': $(`#itemCode-${no}`).val().trim(),
		'whsCode': $(`#whs-${no}`).val(),
		'quotaNo': $(`#quota-${no}`).val()
	};

	if ($(`#CardCode`).val() == "") {
		swal('กรุณาระบุลูกค้า');
		return false;
	}

	load_in();

	$.ajax({
		url: `${HOME}get_item_data`,
		type: 'GET',
		cache: false,
		data: h,
		success: function (rs) {
			load_out();
			if (isJson(rs)) {
				let ds = JSON.parse(rs);
				let price = parseDefaultFloat(ds.Price, 0);
				let stdPrice = parseDefaultFloat(ds.StdPrice, 0);
				let sellPrice = parseDefaultFloat(ds.SellPrice, 0);
				let lineTotal = parseDefaultFloat(ds.LineTotal, 0);

				$(`#stdPrice-${no}`).val(stdPrice);
				$(`#price-${no}`).val(price);
				$(`#sellPrice-${no}`).val(sellPrice);
				$(`#lineTotal-${no}`).val(lineTotal);
				$(`#disc-amount-${no}`).val(ds.discAmount);
				$(`#line-disc-amount-${no}`).val(ds.totalDiscAmount);
				$(`#vat-rate-${no}`).val(ds.VatRate);
				$(`#vat-amount-${no}`).val(ds.VatAmount);
				$(`#vat-total-${no}`).val(ds.TotalVatAmount);
				$(`#sys-disc-label-${no}`).val(ds.sysDiscLabel);
				$(`#uom-code-${no}`).val(ds.UomCode);
				$(`#rule-id-${no}`).val(ds.rule_id);
				$(`#policy-id-${no}`).val(ds.policy_id);
				$(`#free-item-${no}`).val(ds.freeQty);
				$(`#free-item-${no}`).data('rule', ds.rule_id);
				$(`#disc-type-${no}`).val(ds.discType);
				$(`#itemName-${no}`).val(ds.ItemName);
				$(`#instock-${no}`).val(ds.instock);
				$(`#team-${no}`).val(ds.team);
				$(`#commit-${no}`).val(ds.commit);
				$(`#available-${no}`).val(ds.available);
				$(`#master-pack-${no}`).val(ds.master_pack);
				$(`#line-qty-${no}`).val(ds.Qty);
				$(`#uom-${no}`).val(ds.UomName);
				$(`#price-label-${no}`).val(addCommas(price.toFixed(2)));
				$(`#sysSellPrice-${no}`).val(sellPrice);
				$(`#disc-label-${no}`).val(ds.discLabel);
				$(`#vat-code-${no}`).val(ds.VatGroup);
				$(`#sell-price-${no}`).val(sellPrice);
				$(`#total-label-${no}`).val(addCommas(lineTotal.toFixed(2)));
				$(`#img-${no}`).html(`<img src="${ds.image}" width="40px;" height="40px;" />`);				

				$(`#line-qty-${no}`).focus();
				recalAmount(no);
			}
			else {
				showError(rs);
			}
		},
		error: function (rs) {
			showError(rs);
		}
	});
}

function getStock(no) {
	let h = {
		'itemCode': $(`#itemCode-${no}`).val(),
		'whsCode': $(`#whs-${no}`).val(),
		'quota': $(`#quota-${no}`).val()
	};

	load_in();
	$.ajax({
		url: `${HOME}get_stock`,
		type: 'GET',
		cache: false,
		data: h,
		success: function (rs) {
			load_out();
			if (isJson(rs)) {
				let ds = JSON.parse(rs);
				$(`#instock-${no}`).val(ds.OnHand);
				$(`#team-${no}`).val(ds.QuotaQty);
				$(`#commit-${no}`).val(ds.Committed);
				$(`#available-${no}`).val(ds.Available);
			}
		}
	});
}

async function recalDiscount(no) {
	$(`#disc-diff-${no}`).val(0);

	let regex = /[^0-9+.]+/gi;
	let rawDisc = $(`#disc-label-${no}`).val() || '';
	let label = rawDisc.replace(regex, '');
	let first = label.charAt(0);
	let last = label.charAt(label.length - 1);

	label = first == '+' ? label.slice(1) : label;
	label = last == '+' ? label.slice(0, -1) : label;

	$(`#disc-label-${no}`).val(label);

	let price = roundNumber(parseDefaultFloat($(`#price-${no}`).val(), 0));
	let sysSellPrice = parseDefaultFloat($(`#sysSellPrice-${no}`).val(), 0);

	if (price > 0) {
		let disc = parseDiscount(label, price);
		let sellPrice = disc.sellPrice;
		let discountAmount = disc.discountAmount;
		let discPrcnt = roundNumber((discountAmount > 0 ? (discountAmount / price) * 100 : 0.00));

		$(`#totalDiscPercent-${no}`).val(discPrcnt.toFixed(2));

		if (sysSellPrice > sellPrice) {
			let diff = roundNumber(sysSellPrice - sellPrice);
			let percentDiff = roundNumber((diff / sysSellPrice) * 100);

			$(`#disc-diff-${no}`).val(percentDiff);
		}

		sellPrice = roundNumber(sellPrice);

		$(`#sellPrice-${no}`).val(sellPrice);
		$(`#sell-price-${no}`).val(addCommas(sellPrice));

		recalAmount(no);
	}
}

async function recalAmount(no) {
	$(`#disc-error-${no}`).val(0);
	$(`#disc-label-${no}`).clearError();
	$(`#disc-diff-${no}`).val(0);

	let currentInput = removeCommas($(`#disc-label-${no}`).val());
	let val = currentInput.replace(/[A-Za-z!@#$%^&*()]/g, '');
	let qty = parseDefaultInt($(`#line-qty-${no}`).val(), 0);
	let price = roundNumber(parseDefaultFloat(removeCommas($(`#price-label-${no}`).val()), 0.00));

	$(`#price-${no}`).val(price);
	$(`#price-label-${no}`).val(addCommas(price));

	let disc = parseDiscount(val, price);
	let discountAmount = disc.discountAmount;
	let sellPrice = roundNumber(disc.sellPrice, 4);
	let discPrcnt = discountAmount > 0 ? (discountAmount / price) * 100 : 0.00;
	discPrcnt = roundNumber(discPrcnt);

	$(`#totalDiscPercent-${no}`).val(discPrcnt.toFixed(2));

	if (sellPrice < 0 || sellPrice > price) {
		$(`#disc-label-${no}`).hasError();
		$(`#disc-error-${no}`).val(1);
		return false;
	}

	let vat_rate = parseDefaultFloat($(`#vat-rate-${no}`).val(), 0) * 0.01;
	let sysSellPrice = parseDefaultFloat($(`#sysSellPrice-${no}`).val(), 0.00);
	let vatAmount = roundNumber(sellPrice * vat_rate);
	let vatTotal = roundNumber(qty * vatAmount, 4);
	let lineAmount = roundNumber(qty * sellPrice, 2);
	let lineDiscAmount = roundNumber(qty * discountAmount, 4);

	if (sysSellPrice > sellPrice) {
		let diff = roundNumber(sysSellPrice - sellPrice, 4);
		let percentDiff = roundNumber((diff / sysSellPrice) * 100);

		$(`#disc-diff-${no}`).val(percentDiff);
	}

	$(`#disc-amount-${no}`).val(discountAmount.toFixed(2));
	$(`#line-disc-amount-${no}`).val(lineDiscAmount);
	$(`#sellPrice-${no}`).val(sellPrice);
	$(`#sell-price-${no}`).val(addCommas(sellPrice));
	$(`#vat-amount-${no}`).val(vatAmount);
	$(`#vat-total-${no}`).val(vatTotal);
	$(`#line-total-${no}`).val(lineAmount);
	$(`#total-label-${no}`).val(addCommas(lineAmount));

	recalTotal();
}

function getDiscDiff(old_price, new_price) {
	let diff = old_price - new_price;

	if (diff > 0) {
		return diff / old_price * 0.01;
	}

	return 0;
}

async function recalTotal() {
	let total = 0.00; //--- total amount after row discount
	let totalTaxAmount = 0.00;
	let df_rate = parseDefaultFloat($('#vat_rate').val(), 7); //---- 7%
	let taxRate = df_rate * 0.01;
	let rounding = 0;

	$('.item-code').each(function () {
		let no = $(this).data('id');
		let qty = parseDefaultInt($(`#line-qty-${no}`).val(), 0);

		let price = roundNumber(parseDefaultFloat($(`#price-${no}`).val(), 0.00));
		let amount = roundNumber(parseDefaultFloat($(`#line-total-${no}`).val(), 0.00));
		let rate = parseDefaultFloat($(`#vat-rate-${no}`).val(), 0.00);

		if (qty > 0 && price > 0) {
			total += amount;

			if (rate > 0) {
				totalTaxAmount += amount;
			}
		}
	});

	//--- update bill discount
	let disc = roundNumber(parseDefaultFloat($('#discPrcnt').val(), 0));
	let billDiscAmount = roundNumber(parseFloat(total * (disc * 0.01)));
	$('#discAmount').val(billDiscAmount);
	$('#discAmountLabel').val(addCommas(billDiscAmount));

	//---- bill discount amount
	let amountAfterDisc = roundNumber(parseDefaultFloat(total - billDiscAmount, 0.00)); //--- มูลค่าสินค้า หลังหักส่วนลด
	let amountBeforeDiscWithTax = roundNumber(parseDefaultFloat(totalTaxAmount, 0.00)); //-- มูลค่าสินค้า เฉพาะที่มีภาษี
	//--- คำนวนภาษี หากมีส่วนลดท้ายบิล
	//--- เฉลี่ยส่วนลดออกให้ทุกรายการ โดยเอาส่วนลดท้ายบิล(จำนวนเงิน)/มูลค่าสินค้าก่อนส่วนลด
	//--- ได้มูลค่าส่วนลดท้ายบิลที่เฉลี่ยนแล้ว ต่อ บาท เช่น หารกันมาแล้ว ได้ 0.16 หมายถึงทุกๆ 1 บาท จะลดราคา 0.16 บาท
	let everageBillDisc = roundNumber(parseFloat((total > 0 ? billDiscAmount / total : 0)));

	//--- นำผลลัพธ์ข้างบนมาคูณ กับ มูลค่าที่ต้องคิดภาษี (ตัวที่ไม่มีภาษีไม่เอามาคำนวณ)
	//--- จะได้มูลค่าส่วนลดที่ต้องไปลบออกจากมูลค่าสินค้าที่ต้องคิดภาษี
	let totalDiscTax = roundNumber(amountBeforeDiscWithTax * everageBillDisc);

	let amountToPayTax = roundNumber(amountBeforeDiscWithTax - totalDiscTax);

	let taxAmount = roundNumber(amountToPayTax * taxRate);

	let docTotal = amountAfterDisc + taxAmount + rounding;

	$('#totalAmount').val(total);
	$('#totalAmountLabel').val(addCommas(total.toFixed(2)));
	$('#tax').val(taxAmount);
	$('#taxLabel').val(addCommas(taxAmount.toFixed(2)));
	$('#docTotal').val(docTotal);
	$('#docTotalLabel').val(addCommas(docTotal.toFixed(2)));
}

$('#discAmountLabel').focusout(function () {
	let total = parseDefaultFloat($('#totalAmount').val(), 0);
	let disc = parseDefaultFloat(removeCommas($(this).val()), 0);
	disc = disc > total ? total : (disc < 0 ? 0 : disc);
	let discPrcnt = roundNumber(total > 0 ? (disc / total) * 100 : 0);

	$(this).val(addCommas(disc));
	$('#discAmount').val(disc);
	$('#discPrcnt').val(discPrcnt.toFixed(2));

	recalTotal();
});

$('#discPrcnt').change(function () {
	$(this).removeClass('has-error');

	let total = parseDefaultFloat($('#totalAmount').val(), 0);
	let disc = parseDefaultFloat($(this).val(), 0);
	disc = disc > 100 ? 100 : (disc < 0 ? 0 : disc);
	let discAmount = (total * (disc * 0.01));

	$(this).val(disc);
	$('#discAmount').val(discAmount);
	$('#discAmountLabel').val(addCommas(discAmount.toFixed(2)));

	recalTotal();
});

$('#discPrcnt').focusin(function () {
	$(this).select();
});

$('#roundDif').keyup(function () {
	recalTotal();
});

async function init() {
	$('.item-code').autocomplete({
		source: `${BASE_URL}auto_complete/get_item_code_and_name`,
		autoFocus: true,
		open: function (event) {
			var $ul = $(this).autocomplete('widget');
			$ul.css('width', 'auto');
		},
		close: function () {
			var data = $(this).val();
			var arr = data.split(' | ');
			if (arr.length == 3) {
				let no = $(this).data("id");
				let code = arr[1];
				$(this).val(code);
				getItemData(no);
			}
			else {
				$(this).val('');
			}
		}
	});

	$('.line-qty').change(function () {
		let no = $(this).data('id');
		recalAmount(no);
		setTimeout(() => { $(`#price-label-${no}`).focus(); }, 100);
	});

	$('.line-qty').keyup(function (e) {
		if (e.keyCode == 13) {
			let no = $(this).data('id');
			setTimeout(() => { $(`#price-label-${no}`).focus(); }, 100);
		}
	});

	$('.price').change(function () {
		let no = $(this).data('id');
		recalAmount(no);
		setTimeout(() => { $(`#disc-label-${no}`).focus(); }, 100);
	});

	$('.price').keyup(function (e) {
		if (e.keyCode == 13) {
			let no = $(this).data('id');
			setTimeout(() => { $(`#disc-label-${no}`).focus(); }, 100);
		}
	});

	$('.line-qty').focus(function () {
		$(this).select();
	});


	$('.price').focus(function () {
		$(this).select();
	});

	$('.disc').focus(function () {
		$(this).select();
	});
}

$('#discAmount').keyup(function (e) {
	if (e.keyCode === 13) {
		$('#roundDif').focus();
	}
});

$('.autosize').autosize({ append: "\n" });

function duplicateSQ(code) {
	swal({
		title: 'Duplicate Sale Quotation',
		text: 'ต้องการสร้างใบเสนอราคาใหม่ เหมือนใบเสนอราคานี้หรือไม่ ?',
		type: 'warning',
		showCancelButton: true,
		cancelButtonText: 'Cancle',
		confirmButtonText: 'Duplicate',
		closeOnConfirm: true
	},
		function () {
			load_in();
			setTimeout(() => {
				$.ajax({
					url: `${HOME}duplicate_quotation`,
					type: 'POST',
					cache: false,
					data: {
						'code': code
					},
					success: function (rs) {
						load_out();
						if (isJson(rs)) {
							let ds = JSON.parse(rs);
							if (ds.status === 'success') {
								swal({
									title: 'Success',
									text: 'Duplicate success : ' + ds.code,
									type: 'success',
									timer: 1000
								});

								setTimeout(function () {
									edit(ds.code, ds.pageNo);
								}, 1200);
							}
							else {
								showError(ds.error);
							}
						}
						else {
							showError(rs);
						}
					},
					error: function (rs) {
						showError(rs);
					}
				})
			}, 100);
		});
}

async function toggleText(el) {
	let no = el.data('id');
	let data = { "no": no };
	let output = $(`#row-${no}`);
	let source = el.val() == 1 ? $('#text-template').html() : $('#normal-template').html();

	await render(source, data, output);
	await reIndex();
	await init();
	await recalTotal();
}

function toggleCheckAll(el) {
	let checked = el.checked;
	$('#details-template').find('input[type="checkbox"]').prop('checked', checked);
}

function dumpJson(code) {
	$.ajax({
		url: `${HOME}getJSON`,
		type: 'GET',
		cache: false,
		data: {
			'code': code
		},
		success: function (rs) {
			console.log(rs);
		}
	})
}

const dragger = () => {
	const el = document.getElementById('details-template');
	Sortable.create(el, {
		animation: 150,
		handle: '.handle',
		onEnd: function (evt) {
			console.log('Moved:', evt.oldIndex, '→', evt.newIndex);
			reIndex();
		}
	});
}

window.addEventListener('load', function () {
	init();
	dragger();
});

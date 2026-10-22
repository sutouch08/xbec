var btnClick = 0;
var click = 0;

function saveAsDraft(option) {
	$('#saveType').val(1);
	validateFreeItem(option);
}

function saveAsReserve(option) {
	let allowReserve = $('#allow-reserve').val() == '1' ? 1 : 0;

	if (allowReserve == 0) {
		swal({
			title: 'Oops!',
			text: 'คุณไม่มีสิทธิ์ใช้วงเงิน Reserve',
			type: 'error',
			html: true
		});

		return false;
	}

	$('#saveType').val(2);
	validateFreeItem(option);
}

function save(option) {
	$('#saveType').val(0);
	validateFreeItem(option);
}

async function getAvailableCredit(cardCode, orderCode) {
	let availableCredit = 0;

	const url = `${HOME}get_credit_balance`;
	const data = {
		CardCode: cardCode,
		OrderCode: orderCode
	};

	try {
		const response = await fetch(url, {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json'
			},
			body: JSON.stringify(data)
		});

		let result = await response.json();

		if (result.status === 'success') {
			return result.balance;
		}

		return availableCredit;
	} catch (err) {
		console.error('Validation error:', err);
		return 0;
	}
}

async function getAvailableReserve(orderCode = null) {
	let availableReserve = 0;
	let user_id = $('#user_id').val();
	const url = `${HOME}get_reserve_balance`;
	const data = {
		OrderCode: orderCode,
		UserID: user_id
	};

	try {
		const response = await fetch(url, {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json'
			},
			body: JSON.stringify(data)
		});

		let result = await response.json();

		if (result.status === 'success') {
			return result.balance;
		}

		return availableReserve;
	} catch (err) {
		console.error('Validation error:', err);
		return 0;
	}
}

function getFreeItemRule() {
	removeFreeRow();

	let ds = {
		'DocDate': $('#DocDate').val(),
		'CardCode': $('#CardCode').val().trim(),
		'Payment': $('#payment').val(),
		'Channels': $('#channels').val()
	};

	var items = {};
	//--- get sum item qty, amount
	$('.item-code').each(function () {
		let itemCode = $(this).val();
		if (itemCode.length) {
			let no = $(this).data('id');
			let is_free = $(`#is-free-${no}`).val();
			if (is_free == 0) {
				let product_id = $(`#product-id-${no}`).val();
				let qty = parseDefault(parseInt($(`#line-qty-${no}`).val()), 0);
				let amount = parseDefault(parseFloat($(`#line-total-${no}`).val()), 0.00);

				if (items.hasOwnProperty(product_id)) {
					qty += parseInt(items[product_id].qty);
					amount += parseFloat(items[product_id].amount);
				}

				items[product_id] = { "itemCode": itemCode, "qty": qty, "amount": amount };
			}
		}
	});

	ds.items = items;

	if (Object.keys(items).length) {
		load_in();
		$.ajax({
			url: `${HOME}get_free_item_rule`,
			type: 'POST',
			cache: false,
			data: {
				"json": JSON.stringify(ds)
			},
			success: function (rs) {
				load_out();

				if (isJson(rs)) {
					ds = JSON.parse(rs);
					ds.forEach((rule, index) => {
						let ruleId = rule.rule_id;
						if ($(`#free-${ruleId}`).length) {
							$(`#free-${ruleId}`).val(rule.freeQty);
						}
						else {
							let source = $('#free-input-template').html();
							let output = $('#free-temp');
							render_append(source, rule, output);

							template = $('#free-btn-template').html();
							result = $('#free-box');
							render_append(template, rule, result);
						}
					});
				}
			}
		})
	}

	$('#btn-save').removeAttr('disabled');
}

function validateFreeItem(option) {
	let saveType = parseDefaultInt($('#saveType').val());

	if (saveType === 1 || saveType === 2) {
		if (option == 'add') {
			saveAdd();
		}
		else {
			saveUpdate();
		}
	}

	if (saveType === 0) {
		let fRemain = 0;

		$('.free-item').each(function () {
			if ($(this).val() > 0) {
				fqty = parseDefault(parseInt($(this).val()), 0);
				picked = parseDefault(parseInt($(this).data('picked')), 0);

				if (fqty > 0 && picked < fqty) {
					fRemain += fqty - picked;
				}
			}
		});

		if (fRemain > 0) {
			title = 'พบรายการที่ได้รับสินค้า Premium แต่ยังไม่ได้เลือก เมื่อคุณบันทึกออเดอร์แล้ว คุณอาจไม่สามารถกลับมาเลือกสินค้า Premium ภายหลังได้อีก ต้องการบันทึกออเดอร์หรือไม่ ?';
			swal({
				title: 'Warning!',
				text: title,
				type: 'warning',
				showCancelButton: true,
				cancelButtonText: 'กลับไปแก้ไข',
				confirmButtonText: 'บันทึกออเดอร์',
				closeOnConfirm: true
			},
				function (isConfirm) {
					if (isConfirm) {
						if (option == 'add') {
							saveAdd();
						}
						else {
							saveUpdate();
						}
					}
				});
		}
		else {
			if (option == 'add') {
				saveAdd();
			}
			else {
				saveUpdate();
			}
		}
	}
}

async function saveAdd() {
	console.log('saveAdd');

	if (click == 0) {
		click = 1;

		let mustApprove = 0;
		let max_diff = 0;
		let emptyQuota = 0;
		let saveType = parseDefaultInt($('#saveType').val(), 0);
		let creditLimit = $('#creditLimit').val() == '1' ? 1 : 0;
		let payment = $('#payment').val();
		let disc_error = 0;
		let allowReserve = $('#allow-reserve').val() == '1' ? 1 : 0;
		let limitReserve = $('#limit-reserve').val() == '1' ? 1 : 0;

		clearErrorByClass('r');

		$('.disc-diff').each(function () {
			if ($(this).val() > 0) {
				mustApprove++;
				max_diff = $(this).val() > max_diff ? $(this).val() : max_diff;
			}
		});

		let ds = {
			'saveType': saveType,
			'SlpCode': $('#sale_id').val(),
			'CardCode': $('#CardCode').val().trim(),
			'CardName': $('#CardName').val().trim(),
			'Payment': $('#payment').val(),
			'Channels': $('#channels').val(),
			'projectCode': $('#projects').val(),
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
			'roundDif': 0,
			'tax': parseDefaultFloat($('#tax').val(), 0),
			'docTotal': parseDefaultFloat($('#docTotal').val(), 0),
			'totalCost': parseDefaultFloat($('#total-cost').val(), 0),
			'totalGP': parseDefaultFloat($('#total-gp').val(), 0),
			'mustApprove': mustApprove > 0 ? 1 : 0,
			'maxDiff': max_diff,
			'VatGroup': $('#vat_code').val(),
			'VatRate': $('#vat_rate').val(),
			'sale_team': $('#sale_team').val(),
			'user_id': $('#user_id').val(),
			'uname': $('#uname').val()
		}

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

		if (ds.dimCode5 == '') {
			swal("กรุณาเลือกหน่วยงาน");
			$('#dimCode5').hasError();
			click = 0;
			return false;
		}

		if (ds.OwnerCode == '') {
			swal("Please Select Owner");
			$('#owner').hasError();
			click = 0;
			return false;
		}

		$('.disc-error').each(function () {
			if ($(this).val() == 1) {
				$('#disc-label-' + $(this).data('id')).hasError();
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

		let count = 0;
		let details = [];
		let lineNum = 0;

		$('.item-code').each(function () {
			let no = $(this).data('id');
			let itemCode = $(this).val();
			if (itemCode.length > 0) {
				let quotaNo = $(`#quota-${no}`).val();

				if (quotaNo == "") {
					emptyQuota++;
				}

				let row = {
					"LineNum": lineNum,
					"ItemCode": itemCode,
					"Description": $(`#itemName-${no}`).val(),
					'Cost': parseDefaultFloat($(`#cost-${no}`).val(), 0),
					'totalCost': parseDefaultFloat($(`#line-cost-${no}`).val(), 0),
					"StdPrice": parseDefaultFloat($(`#stdPrice-${no}`).val(), 0),
					"Price": parseDefaultFloat($(`#price-${no}`).val(), 0),
					"SellPrice": parseDefaultFloat($(`#sellPrice-${no}`).val(), 0),
					"sysSellPrice": parseDefaultFloat($(`#sysSellPrice-${no}`).val(), 0),
					"Quantity": parseDefaultFloat($(`#line-qty-${no}`).val(), 0),
					"UomCode": $(`#uom-code-${no}`).val(),
					"discLabel": $(`#disc-label-${no}`).val(),
					"sysDiscLabel": $(`#sys-disc-label-${no}`).val(),
					"discAmount": parseDefaultFloat($(`#disc-amount-${no}`).val(), 0),
					"totalDiscAmount": parseDefaultFloat($(`#line-disc-amount-${no}`).val(), 0),
					"DiscPrcnt": parseDefaultFloat($(`#totalDiscPercent-${no}`).val(), 0),
					"VatGroup": $(`#vat-code-${no}`).val(),
					"VatRate": $(`#vat-rate-${no}`).val(),
					"VatAmount": parseDefaultFloat($(`#vat-amount-${no}`).val(), 0),
					"totalVatAmount": parseDefaultFloat($(`#vat-total-${no}`).val(), 0),
					"LineTotal": parseDefaultFloat($(`#line-total-${no}`).val(), 0),
					"policy_id": $(`#policy-id-${no}`).val(),
					"rule_id": $(`#rule-id-${no}`).val(),
					'discDiff': $(`#disc-diff-${no}`).val(),
					'uid': $(`#free-item-${no}`).data('uid'),
					'parent_uid': $(`#free-item-${no}`).data('parent'),
					'picked': $(`#free-item-${no}`).data('picked'),
					'is_free': $(`#is-free-${no}`).val(),
					'discType': $(`#disc-type-${no}`).val(),
					'WhsCode': $(`#whs-${no}`).val(),
					'QuotaNo': $(`#quota-${no}`).val(),
					'sale_team': $('#sale_team').val(),
					'count_stock': $(`#count-stock-${no}`).val(),
					'allow_change_discount': $(`#allow-change-discount-${no}`).val()
				}

				details.push(row);
				count++;
				lineNum++;
			}
		}); //--- end each function

		if (count === 0) {
			swal("ไม่พบรายการสินค้า");
			click = 0;
			return false;
		}

		if (emptyQuota > 0) {
			swal("กรุณาระบุ Quota No ให้ครบ");
			click = 0;
			return false;
		}

		let data = {};
		data.header = ds;
		data.details = details;

		if (saveType === 2 && allowReserve == 1 && limitReserve == 1) {
			let availableReserve = await getAvailableReserve();

			$('#available-reserve').val(addCommas(availableReserve.toFixed(2)));

			if (availableReserve <= ds.docTotal) {
				swal({
					title: 'Oops!',
					text: `คุณมีวงเงิน Reserve คงเหลือไม่เพียงพอ <br/> คงเหลือ **${addCommas(availableReserve.toFixed(2))}** <br/> ยอดรวมเอกสาร **${addCommas(ds.docTotal.toFixed(2))}**`,
					type: 'info',
					html: true
				});

				click = 0;
				return false;
			}
		} //--- check reserve limit

		if (creditLimit == 1 && payment != '-1') {
			let orderCode = null;

			let availableCredit = await getAvailableCredit(ds.CardCode, orderCode);

			$('#available-credit').val(addCommas(availableCredit.toFixed(2)));

			if (availableCredit < ds.docTotal) {
				difamount = ds.docTotal - availableCredit;

				swal({
					title: 'Warning!',
					text: `คุณมีเครดิตคงเหลือไม่เพียงพอ <br/> คงเหลือ **${addCommas(availableCredit.toFixed(2))}** <br/> ยอดรวมเอกสาร **${addCommas(ds.docTotal.toFixed(2))}** <br/> ยอดที่เกินเครดิต **${addCommas(difamount.toFixed(2))}** <br/><br/>คุณต้องการบันทึกออเดอร์หรือไม่ ?`,
					type: 'warning',
					html: true,
					showCancelButton: true,
					cancelButtonText: 'กลับไปแก้ไข',
					confirmButtonText: 'บันทึกออเดอร์',
					closeOnConfirm: true
				}, function (isConfirm) {
					if (isConfirm) {
						setTimeout(() => {
							add(data);
						}, 200);
					}
					else {
						click = 0;
					}
				});
			}
			else {
				add(data);
			}
		}
		else {
			add(data);
		}
	}
}

function add(data) {
	load_in();

	$.ajax({
		url: `${HOME}add`,
		type: 'POST',
		cache: false,
		data: JSON.stringify(data),
		success: function (rs) {
			load_out();

			if (isJson(rs)) {
				let ds = JSON.parse(rs);

				if (ds.status === 'success') {
					if (ds.ex == 1) {
						swal({
							title: 'Warning!',
							text: 'บันทึกออเดอร์สำเร็จ แต่ส่งข้อมูลไปยัง SAP ไม่สำเร็จ',
							type: 'warning'
						}, function () {
							setTimeout(() => {
								viewDetail(ds.code);
							}, 500);
						});
					}
					else {
						swal({
							title: 'Success',
							type: 'success',
							timer: 1000
						});

						setTimeout(() => {
							viewDetail(ds.code);
						}, 1200);
					}
				}
				else {
					showError(ds.message);
				}

				click = 0;
			}
			else {
				showError(rs);
				click = 0;
			}
		},
		error: function (rs) {
			showError(rs);
			click = 0;
		}
	});
}

async function saveUpdate() {
	if (click == 0) {
		click = 1;

		let mustApprove = 0;
		let max_diff = 0;
		let emptyQuota = 0;
		let saveType = parseDefaultInt($('#saveType').val(), 0);
		let creditLimit = $('#creditLimit').val() == '1' ? 1 : 0;
		let payment = $('#payment').val();
		let disc_error = 0;
		let allowReserve = $('#allow-reserve').val() == '1' ? 1 : 0;
		let limitReserve = $('#limit-reserve').val() == '1' ? 1 : 0;

		clearErrorByClass('r');

		$('.disc-diff').each(function () {
			if ($(this).val() > 0) {
				mustApprove++;
				max_diff = $(this).val() > max_diff ? $(this).val() : max_diff;
			}
		});

		let ds = {
			'saveType': saveType,
			'code': $('#code').val(),
			'SlpCode': $('#sale_id').val(),
			'CardCode': $('#CardCode').val().trim(),
			'CardName': $('#CardName').val().trim(),
			'Payment': $('#payment').val(),
			'Channels': $('#channels').val(),
			'projectCode': $('#projects').val(),
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
			'roundDif': 0,
			'tax': parseDefaultFloat($('#tax').val(), 0),
			'docTotal': parseDefaultFloat($('#docTotal').val(), 0),
			'totalCost': parseDefaultFloat($('#total-cost').val(), 0),
			'totalGP': parseDefaultFloat($('#total-gp').val(), 0),
			'mustApprove': mustApprove > 0 ? 1 : 0,
			'maxDiff': max_diff,
			'VatGroup': $('#vat_code').val(),
			'VatRate': $('#vat_rate').val(),
			'sale_team': $('#sale_team').val(),
			'user_id': $('#user_id').val(),
			'uname': $('#uname').val()
		}

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

		if (ds.dimCode5 == '') {
			swal("กรุณาเลือกหน่วยงาน");
			$('#dimCode5').hasError();
			click = 0;
			return false;
		}

		if (ds.OwnerCode == '') {
			swal("Please Select Owner");
			$('#owner').hasError();
			click = 0;
			return false;
		}

		$('.disc-error').each(function () {
			if ($(this).val() == 1) {
				$('#disc-label-' + $(this).data('id')).hasError();
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

		let count = 0;
		let details = [];
		let lineNum = 0;

		$('.item-code').each(function () {
			let no = $(this).data('id');
			let itemCode = $(this).val();
			if (itemCode.length > 0) {
				let quotaNo = $(`#quota-${no}`).val();

				if (quotaNo == "") {
					emptyQuota++;
				}

				let row = {
					"LineNum": lineNum,
					"ItemCode": itemCode,
					"Description": $(`#itemName-${no}`).val(),
					"Cost": parseDefaultFloat($(`#cost-${no}`).val(), 0),
					"totalCost": parseDefaultFloat($(`#line-cost-${no}`).val(), 0),
					"StdPrice": parseDefaultFloat($(`#stdPrice-${no}`).val(), 0),
					"Price": parseDefaultFloat($(`#price-${no}`).val(), 0),
					"SellPrice": parseDefaultFloat($(`#sellPrice-${no}`).val(), 0),
					"sysSellPrice": parseDefaultFloat($(`#sysSellPrice-${no}`).val(), 0),
					"Quantity": parseDefaultInt($(`#line-qty-${no}`).val(), 0),
					"UomCode": $(`#uom-code-${no}`).val(),
					"discLabel": $(`#disc-label-${no}`).val(),
					"sysDiscLabel": $(`#sys-disc-label-${no}`).val(),
					"discAmount": parseDefaultFloat($(`#disc-amount-${no}`).val(), 0),
					"totalDiscAmount": parseDefaultFloat($(`#line-disc-amount-${no}`).val(), 0),
					"DiscPrcnt": parseDefaultFloat($(`#totalDiscPercent-${no}`).val(), 0),
					"VatGroup": $(`#vat-code-${no}`).val(),
					"VatRate": $(`#vat-rate-${no}`).val(),
					"VatAmount": parseDefaultFloat($(`#vat-amount-${no}`).val(), 0),
					"totalVatAmount": parseDefaultFloat($(`#vat-total-${no}`).val(), 0),
					"LineTotal": parseDefaultFloat($(`#line-total-${no}`).val(), 0),
					"policy_id": $(`#policy-id-${no}`).val(),
					"rule_id": $(`#rule-id-${no}`).val(),
					'discDiff': $(`#disc-diff-${no}`).val(),
					'uid': $(`#free-item-${no}`).data('uid'),
					'parent_uid': $(`#free-item-${no}`).data('parent'),
					'picked': $(`#free-item-${no}`).data('picked'),
					'is_free': $(`#is-free-${no}`).val(),
					'discType': $(`#disc-type-${no}`).val(),
					'WhsCode': $(`#whs-${no}`).val(),
					'QuotaNo': $(`#quota-${no}`).val(),
					'sale_team': $('#sale_team').val(),
					'count_stock': $(`#count-stock-${no}`).val(),
					'allow_change_discount': $(`#allow-change-discount-${no}`).val()
				}

				details.push(row);
				count++;
				lineNum++;
			}
		});

		if (count === 0) {
			swal("ไม่พบรายการสินค้า");
			click = 0;
			return false;
		}

		if (emptyQuota > 0) {
			swal("กรุณาระบุ Quota No ให้ครบ");
			click = 0;
			return false;
		}

		let data = {};
		data.header = ds;
		data.details = details;

		if (saveType === 2 && allowReserve == 1 && limitReserve == 1) {
			let availableReserve = await getAvailableReserve(ds.code);

			$('#available-reserve').val(addCommas(availableReserve.toFixed(2)));

			if (availableReserve <= ds.docTotal) {
				swal({
					title: 'Oops!',
					text: `คุณมีวงเงิน Reserve คงเหลือไม่เพียงพอ <br/> คงเหลือ **${addCommas(availableReserve.toFixed(2))}** <br/> ยอดรวมเอกสาร **${addCommas(ds.docTotal.toFixed(2))}**`,
					type: 'info',
					html: true
				});

				click = 0;
				return false;
			}
		} //--- check reserve limit

		if (creditLimit == 1 && payment != '-1') {

			let availableCredit = await getAvailableCredit(ds.CardCode, ds.code);

			if (availableCredit < ds.docTotal) {
				difamount = ds.docTotal - availableCredit;

				swal({
					title: 'Warning!',
					text: `คุณมีเครดิตคงเหลือไม่เพียงพอ <br/> คงเหลือ **${addCommas(availableCredit.toFixed(2))}** <br/> ยอดรวมเอกสาร **${addCommas(ds.docTotal.toFixed(2))}** <br/> ยอดที่เกินเครดิต **${addCommas(difamount.toFixed(2))}** <br/><br/>คุณต้องการบันทึกออเดอร์หรือไม่ ?`,
					type: 'warning',
					html: true,
					showCancelButton: true,
					cancelButtonText: 'กลับไปแก้ไข',
					confirmButtonText: 'บันทึกออเดอร์',
					closeOnConfirm: true
				}, function (isConfirm) {
					if (isConfirm) {
						setTimeout(() => {
							update(data);
						}, 200);
					}
					else {
						click = 0;
					}
				});
			}
			else {
				update(data);
			}
		}
		else {
			update(data);
		}
	}
}

function update(data) {
	load_in();

	$.ajax({
		url: `${HOME}update`,
		type: 'POST',
		cache: false,
		data: JSON.stringify(data),
		success: function (rs) {
			load_out();

			if (isJson(rs)) {
				let ds = JSON.parse(rs);

				if (ds.status === 'success') {
					if (ds.ex == 1) {
						swal({
							title: 'Warning!',
							text: 'บันทึกออเดอร์สำเร็จ แต่ส่งข้อมูลไปยัง SAP ไม่สำเร็จ',
							type: 'warning'
						}, function () {
							setTimeout(() => {
								viewDetail(ds.code);
							}, 500);
						});
					}
					else {
						swal({
							title: 'Success',
							type: 'success',
							timer: 1000
						});

						setTimeout(() => {
							viewDetail(ds.code);
						}, 1200);
					}
				}
				else {
					showError(ds.message);
				}

				click = 0;
			}
			else {
				showError(rs);
				click = 0;
			}
		},
		error: function (rs) {
			showError(rs);
			click = 0;
		}
	});
}

$('#CardCode').autocomplete({
	source: BASE_URL + 'auto_complete/get_customer_code_and_name',
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
			get_available_credit(code);
			$('#itemCode-1').focus();
		}
		else {
			$('#CardCode').val('');
			$('#CardName').val('');
			$('#priceList').val('');
			$('#payment').val(-1);
		}
	}
})

function get_customer(code) {
	$.ajax({
		url: HOME + 'get_customer_order_data',
		type: 'GET',
		cache: false,
		data: {
			'CardCode': code
		},
		success: function (rs) {
			if (isJson(rs)) {
				let ds = JSON.parse(rs);
				if (ds.status === 'success') {
					let cs = ds.data.customer;
					let billTo = ds.data.billTo;
					let shipTo = ds.data.shipTo;

					$('#payment').val(cs.GroupNum);
					$('#priceList').val(cs.ListNum);
					$('#sale_id').val(cs.SlpCode).change();
					$('#sale_name').val(cs.sale_name);

					if (billTo.length > 0) {
						renderBillTo(billTo);
						$('#BillTo').val(ds.data.bill_to_address);
					}

					if (shipTo.length > 0) {
						renderShipTo(shipTo);
						$('#ShipTo').val(ds.data.ship_to_address);
					}
				}
				else {
					showError(ds.message);
				}
			}
			else {
				showError(rs);
			}
		}
	})
}

function renderBillTo(billTo) {
	var source = $('#bill-to-template').html();
	var output = $('#billToCode');
	render(source, billTo, output);
}

function renderShipTo(shipTo) {
	var source = $('#ship-to-template').html();
	var output = $('#shipToCode');
	render(source, shipTo, output);
}

function updateBillTo() {
	const billTo = $('#billToCode option:selected');
	let adres = addSpace(billTo.data('address'));
	let sub_district = addSpace(billTo.data('subdistrict'));
	let district = addSpace(billTo.data('district'));
	let province = addSpace(billTo.data('province'));
	let postcode = addSpace(billTo.data('postcode'));
	let country = billTo.data('country');

	let address = adres + sub_district + district + province + postcode + (country === 'TH' ? '' : country);
	$('#BillTo').val(address);
}

function updateShipTo() {
	console.log('updateShipTo');
	const shipTo = $('#shipToCode option:selected');
	let adres = addSpace(shipTo.data('address'));
	let sub_district = addSpace(shipTo.data('subdistrict'));
	let district = addSpace(shipTo.data('district'));
	let province = addSpace(shipTo.data('province'));
	let postcode = addSpace(shipTo.data('postcode'));
	let country = shipTo.data('country');

	let address = adres + sub_district + district + province + postcode + (country === 'TH' ? '' : country);

	$('#ShipTo').val(address);
}

function addSpace(text) {
	text = String(text);
	return text.trim() === "" ? "" : text + " ";
}

function get_available_credit(code, orderCode = null) {
	$.ajax({
		url: `${HOME}get_credit_balance`,
		type: 'POST',
		cache: false,
		data: {
			'CardCode': code,
			'orderCode': orderCode
		},
		success: function (rs) {
			if (isJson(rs)) {
				let ds = JSON.parse(rs);
				if (ds.status === 'success') {
					let available = parseDefaultFloat(ds.balance, 0);
					$('#available-credit').val(addCommas(available.toFixed(2)));
					recalTotal();
				}
				else {
					console.log(ds.message);
					$('#available-credit').val('0.00');
				}
			}
			else {
				console.log(rs);
				$('#available-credit').val('0.00');
			}
		},
		error: function (rs) {
			console.log(rs);
			$('#available-credit').val('0.00');
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

	$('#btn-save').attr('disabled', 'disabled');

	reIndex();
	await init();
	$(`#itemCode-${no}`).focus();
	return;
}

function removeRow() {
	$('.del-chk').each(function () {
		if ($(this).is(':checked')) {
			var no = $(this).val();
			var is_free = $(`#is-free-${no}`).val();
			var rule_id = $(`#rule-id-${no}`).val();

			if (is_free) {
				var pno = $(`#is-free-${no}`).data('parentrow');
				let el = $(`#free-${pno}`);
				let qty = parseDefault(parseInt($(`#line-qty-${no}`).val()), 0);
				let picked = parseDefault(parseInt(el.data('picked')), 0);
				picked = picked - qty;

				if (picked >= 0) {
					freeQty = el.val();
					balance = freeQty - picked;
					el.data('picked', picked);
					$(`#btn-free-${pno}`).text("Free " + balance);
					$(`#btn-free-${pno}`).removeClass('hide');
				}
			}

			if (is_free == 0) {
				removeFreeRow();
			}

			$(`#row-${no}`).remove();
		}
	});

	$('#btn-save').attr('disabled', 'disabled');

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

async function removeFreeRow() {
	$('.free-row').remove();
	$('.free-item').remove();
	$('.free-btn').remove();

	reIndex();
}

function updateDiscountRule(no) {
	let itemCode = $(`#itemCode-${no}`).val();
	let cardCode = $(`#CardCode`).val();
	let price = parseDefaultFloat($(`#price-${no}`).val(), 0);
	let qty = parseDefaultInt($(`#line-qty-${no}`).val(), 0);
	let docDate = $(`#DocDate`).val();
	let payment = $(`#payment`).val();
	let channels = $(`#channels`).val();

	if (itemCode.length == 0) {
		return false;
	}

	if (qty <= 0) {
		return false;
	}

	setTimeout(function () {

		if (cardCode == "") {
			swal('กรุณาระบุลูกค้า');
			return false;
		}

		load_in();

		let uid = $(`#free-item-${no}`).data('uid');

		$('.is-free').each(function () {
			let uuid = $(this).data('parent');
			if (uuid == uid) {
				let rowNo = $(this).data('id');
				let fqty = parseDefault(parseInt($(`#line-qty-${no}`).val()), 0);
				let picked = parseDefault(parseInt($(`#free-item-${no}`).data('picked')), 0);
				picked = picked - fqty;
				picked = picked < 0 ? 0 : picked;
				$(`#free-item-${no}`).data('picked', picked);
				$(`#row-${rowNo}`).remove();
			}
		})

		$.ajax({
			url: `${HOME}get_discount_data`,
			type: "GET",
			cache: false,
			data: {
				'ItemCode': itemCode,
				'CardCode': cardCode,
				'Price': price,
				'Qty': qty,
				'DocDate': docDate,
				'Payment': payment,
				'Channels': channels
			},
			success: function (rs) {
				load_out();
				if (isJson(rs)) {
					let ds = JSON.parse(rs);
					let cost = parseDefaultFloat(ds.Cost, 0.00);
					let price = parseDefaultFloat(ds.Price, 0.00);
					let stdPrice = parseDefaultFloat(ds.StdPrice, 0.00);
					let sellPrice = parseDefaultFloat(ds.SellPrice, 0.00);
					let sysSellPrice = parseDefaultFloat(ds.sysSellPrice, 0.00);
					let lineCost = qty * cost;
					let lineTotal = parseDefaultFloat(ds.LineTotal, 0.00);

					$(`#product-id-${no}`).val(ds.product_id);
					$(`#cost-${no}`).val(cost);
					$(`#line-cost-${no}`).val(lineCost);
					$(`#price-${no}`).val(price)
					$(`#stdPrice-${no}`).val(stdPrice);
					$(`#sellPrice-${no}`).val(sellPrice);
					$(`#disc-amount-${no}`).val(ds.discAmount);
					$(`#line-disc-amount-${no}`).val(ds.totalDiscAmount);
					$(`#line-total-${no}`).val(lineTotal);
					$(`#vat-rate-${no}`).val(ds.VatRate);
					$(`#vat-amount-${no}`).val(ds.VatAmount);
					$(`#vat-total-${no}`).val(ds.TotalVatAmount);
					$(`#sys-disc-label-${no}`).val(ds.sysDiscLabel);
					$(`#disc-diff-${no}`).val(0);
					$(`#uom-code-${no}`).val(ds.UomCode);
					$(`#rule-id-${no}`).val(ds.rule_id);
					$(`#policy-id-${no}`).val(ds.policy_id);
					$(`#policy-id-${no}`).data('code', ds.policy_code);
					$(`#policy-id-${no}`).data('name', ds.policy_name);
					$(`#free-item-${no}`).val(ds.freeQty);
					$(`#free-item-${no}`).data('rule', ds.rule_id);
					$(`#disc-type-${no}`).val(ds.discType);

					if (ds.freeQty > 0) {
						$(`#free-item-${no}`).data('uid', uniqueId());
						$(`#btn-free-${no}`).removeClass('hide');
					}

					$(`#itemName-${no}`).val(ds.ItemName);
					$(`#uom-${no}`).val(ds.UomName);
					$(`#stdPrice-label-${no}`).val(addCommas(stdPrice.toFixed(2)));
					$(`#price-label-${no}`).val(addCommas(price.toFixed(2)));
					$(`#sysSellPrice-${no}`).val(sysSellPrice);
					$(`#disc-label-${no}`).val(ds.discLabel);
					$(`#vat-code-${no}`).val(ds.VatGroup);
					$(`#sell-price-${no}`).val(sellPrice);
					$(`#total-label-${no}`).val(addCommas(lineTotal.toFixed(2)));
					$(`#disc-rule-${no}`).val(ds.rule_code);

					recalAmount(no);
					updatePromotionApplied();
				}
				else {
					showError(rs);
				}
			},
			error: function (rs) {
				showError(rs);
			}
		})
	}, 200);
}

function getItemData(no) {
	let h = {
		'ItemCode': $(`#itemCode-${no}`).val(),
		'CardCode': $(`#CardCode`).val(),
		'PriceList': $(`#priceList`).val(),
		'DocDate': $(`#DocDate`).val(),
		'Payment': $(`#payment`).val(),
		'Channels': $(`#channels`).val(),
		'whsCode': $(`#whs-${no}`).val(),
		'quotaNo': $(`#quota-${no}`).val()
	};


	setTimeout(function () {
		if (h.CardCode == "") {
			swal('กรุณาระบุลูกค้า');
			return false;
		}

		load_in();

		let uid = $('#free-item-' + no).data('uid');

		$('.is-free').each(function () {
			let uuid = $(this).data('parent');
			if (uuid == uid) {
				let rowNo = $(this).data('id');
				let fqty = parseDefault(parseInt($(`#line-qty-${no}`).val()), 0);
				let picked = parseDefault(parseInt($(`#free-item-${no}`).data('picked')), 0);
				picked = picked - fqty;
				picked = picked < 0 ? 0 : picked;
				$(`#free-item-${no}`).data('picked', picked);
				$(`#row-${rowNo}`).remove();
			}
		});

		$.ajax({
			url: `${HOME}get_item_data`,
			type: "GET",
			cache: false,
			data: h,
			success: function (rs) {
				load_out();
				if (isJson(rs)) {
					let ds = JSON.parse(rs);
					let price = parseDefaultFloat(ds.Price, 0.00);
					let stdPrice = parseDefaultFloat(ds.StdPrice, 0.00);
					let sellPrice = parseDefaultFloat(ds.SellPrice, 0.00);
					let lineTotal = parseDefaultFloat(ds.LineTotal, 0.00);
					let cost = parseDefaultFloat(ds.Cost, 0.00);
					let lineCost = cost * 1;

					$(`#product-id-${no}`).val(ds.product_id);
					$(`#cost-${no}`).val(cost);
					$(`#line-cost-${no}`).val(lineCost);
					$(`#price-${no}`).val(price);
					$(`#stdPrice-${no}`).val(stdPrice);
					$(`#sellPrice-${no}`).val(sellPrice);
					$(`#disc-amount-${no}`).val(ds.discAmount);
					$(`#line-disc-amount-${no}`).val(ds.totalDiscAmount);
					$(`#line-total-${no}`).val(lineTotal);
					$(`#vat-rate-${no}`).val(ds.VatRate);
					$(`#vat-amount-${no}`).val(ds.VatAmount);
					$(`#vat-total-${no}`).val(ds.TotalVatAmount);
					$(`#sys-disc-label-${no}`).val(ds.sysDiscLabel);
					$(`#uom-code-${no}`).val(ds.UomCode);
					$(`#rule-id-${no}`).val(ds.rule_id);
					$(`#policy-id-${no}`).val(ds.policy_id);
					$(`#policy-id-${no}`).data('code', ds.policy_code);
					$(`#policy-id-${no}`).data('name', ds.policy_name);
					$(`#free-item-${no}`).val(ds.freeQty);
					$(`#free-item-${no}`).data('rule', ds.rule_id);
					$(`#disc-type-${no}`).val(ds.discType);

					if (ds.freeQty > 0) {
						$(`#free-item-${no}`).data('uid', uniqueId());
						$(`#btn-free-${no}`).removeClass('hide');
					}

					$(`#itemName-${no}`).val(ds.ItemName);
					$(`#instock-${no}`).val(addCommas(ds.instock));
					$(`#team-${no}`).val(addCommas(ds.team));
					$(`#commit-${no}`).val(addCommas(ds.commit));
					$(`#available-${no}`).val(addCommas(ds.available));
					$(`#master-pack-${no}`).val(ds.masterPack);
					$(`#line-qty-${no}`).val(ds.Qty);
					$(`#uom-${no}`).val(ds.UomName);
					$(`#stdPrice-label-${no}`).val(addCommas(stdPrice.toFixed(2)));
					$(`#price-label-${no}`).val(addCommas(price.toFixed(2)));
					$(`#sysSellPrice-${no}`).val(sellPrice);
					$(`#disc-label-${no}`).val(ds.discLabel);
					$(`#vat-code-${no}`).val(ds.VatGroup);
					$(`#sell-price-${no}`).val(sellPrice);
					$(`#total-label-${no}`).val(addCommas(lineTotal.toFixed(2)));
					$(`#count-stock-${no}`).val(ds.count_stock);
					$(`#allow-change-discount-${no}`).val(ds.allow_change_discount);
					$(`#disc-rule-${no}`).val(ds.rule_code);

					$(`#img-${no}`).html('<img src="' + ds.image + '" width="40px;" height="40px;" />');

					if (ds.count_stock == '1') {
						$(`#price-label-${no}`).attr('disabled', 'disabled');
					}
					else {
						$(`#price-label-${no}`).removeAttr('disabled');
					}

					if (ds.allow_change_discount == '0') {
						$(`#disc-label-${no}`).attr('disabled', 'disabled');
					}
					else {
						$(`#disc-label-${no}`).removeAttr('disabled');
					}

					$(`#line-qty-${no}`).focus();

					recalAmount(no);

					updatePromotionApplied();
				}
				else {
					showError(rs);
				}
			},
			error: function (rs) {
				showError(rs);
			}
		})
	}, 200);

}

async function addItemRow(ds) {
	let no = uniqueId();

	await addRow(no);
	let price = parseDefaultFloat(ds.Price, 0.00);
	let stdPrice = parseDefaultFloat(ds.StdPrice, 0.00);
	let sellPrice = parseDefaultFloat(ds.SellPrice, 0.00);
	let lineTotal = parseDefaultFloat(ds.LineTotal, 0.00);
	let cost = parseDefaultFloat(ds.Cost, 0.00);
	let lineCost = cost * 1;

	$(`#product-id-${no}`).val(ds.product_id);
	$(`#cost-${no}`).val(cost);
	$(`#line-cost-${no}`).val(lineCost);
	$(`#price-${no}`).val(price);
	$(`#stdPrice-${no}`).val(stdPrice);
	$(`#sellPrice-${no}`).val(sellPrice);
	$(`#disc-amount-${no}`).val(ds.discAmount);
	$(`#line-disc-amount-${no}`).val(ds.totalDiscAmount);
	$(`#line-total-${no}`).val(lineTotal);
	$(`#vat-rate-${no}`).val(ds.VatRate);
	$(`#vat-amount-${no}`).val(ds.VatAmount);
	$(`#vat-total-${no}`).val(ds.TotalVatAmount);
	$(`#sys-disc-label-${no}`).val(ds.sysDiscLabel);
	$(`#uom-code-${no}`).val(ds.UomCode);
	$(`#rule-id-${no}`).val(ds.rule_id);
	$(`#policy-id-${no}`).val(ds.policy_id);
	$(`#policy-id-${no}`).data('code', ds.policy_code);
	$(`#policy-id-${no}`).data('name', ds.policy_name);
	$(`#free-item-${no}`).val(ds.freeQty);
	$(`#free-item-${no}`).data('rule', ds.rule_id);
	$(`#disc-type-${no}`).val(ds.discType);

	if (ds.freeQty > 0) {
		$(`#free-item-${no}`).data('uid', uniqueId());
		$(`#btn-free-${no}`).removeClass('hide');
	}

	$(`#itemCode-${no}`).val(ds.ItemCode);
	$(`#itemName-${no}`).val(ds.ItemName);
	$(`#instock-${no}`).val(addCommas(ds.instock));
	$(`#team-${no}`).val(addCommas(ds.team));
	$(`#commit-${no}`).val(addCommas(ds.commit));
	$(`#available-${no}`).val(addCommas(ds.available));
	$(`#master-pack-${no}`).val(ds.masterPack);
	$(`#line-qty-${no}`).val(ds.Qty);
	$(`#uom-${no}`).val(ds.UomName);
	$(`#stdPrice-label-${no}`).val(addCommas(stdPrice.toFixed(2)));
	$(`#price-label-${no}`).val(addCommas(price.toFixed(2)));
	$(`#sysSellPrice-${no}`).val(sellPrice);
	$(`#disc-label-${no}`).val(ds.discLabel);
	$(`#vat-code-${no}`).val(ds.VatGroup);
	$(`#sell-price-${no}`).val(sellPrice);
	$(`#total-label-${no}`).val(addCommas(lineTotal.toFixed(2)));
	$(`#count-stock-${no}`).val(ds.count_stock);
	$(`#allow-change-discount-${no}`).val(ds.allow_change_discount);
	$(`#disc-rule-${no}`).val(ds.rule_code);

	$(`#img-${no}`).html('<img src="' + ds.image + '" width="40px;" height="40px;" />');

	if (ds.count_stock == '1') {
		$(`#price-label-${no}`).attr('disabled', 'disabled');
	}
	else {
		$(`#price-label-${no}`).removeAttr('disabled');
	}

	if (ds.allow_change_discount == '0') {
		$(`#disc-label-${no}`).attr('disabled', 'disabled');
	}
	else {
		$(`#disc-label-${no}`).removeAttr('disabled');
	}

	return;
}

function getStock(no) {
	let whsCode = $('#whs-' + no).val();
	let quota = $('#quota-' + no).val();
	let itemCode = $('#itemCode-' + no).val();

	$.ajax({
		url: HOME + 'get_stock',
		type: 'GET',
		cache: false,
		data: {
			'itemCode': itemCode,
			'whsCode': whsCode,
			'quota': quota
		},
		success: function (rs) {
			if (isJson(rs)) {
				let ds = $.parseJSON(rs);
				$('#instock-' + no).val(ds.OnHand);
				$('#team-' + no).val(ds.QuotaQty);
				$('#commit-' + no).val(ds.Committed);
				$('#available-' + no).val(ds.Available);
			}
		}
	});
}

function updateFreeItem() {
	let freeQty = 0;
	$('.free-item').each(function () {
		let qty = parseDefault(parseInt($(this).val()), 0);
		if (qty > 0) {
			freeQty += qty;
		}
	});

	if (freeQty == 0) {
		$('#free-badge').text("");
	}
	else {
		$('#free-badge').text(freeQty);
	}
}

function pickFreeItem(rule_id) {
	freeQty = $('#free-' + rule_id).val();
	uid = $('#free-' + rule_id).data('uid');
	picked = $('#free-' + rule_id).data('picked');

	if (rule_id != "" && rule_id > 0 && freeQty > 0 && picked < freeQty) {
		load_in();

		$.ajax({
			url: `${HOME}get_free_item`,
			type: 'GET',
			cache: false,
			data: {
				'rule_id': rule_id,
				'freeQty': freeQty,
				'picked': picked,
				'uid': uid
			},
			success: function (rs) {
				load_out();

				if (isJson(rs)) {
					let ds = JSON.parse(rs);

					$('#free-item-modal-label').text(`กรุณาเลือก ${ds.freeQty} ชิ้น จากรายการต่อไปนี้`);

					let source = $('#free-item-template').html();
					let output = $('#free-item-table');
					let data = ds.items;

					render(source, data, output);

					$('#free-item-modal').modal('show');
				}
				else {
					showError(rs);
				}
			}
		});
	}
}

async function addFreeRow(uuid) {
	let el = $(`#input-${uuid}`);
	let qty = parseDefaultInt(el.val(), 0);
	let product_id = el.data('item');
	let product_code = el.data('pdcode');
	let product_name = el.data('pdname');
	let parent_uid = el.data('parent');
	let rule_id = el.data('rule');
	let rule_code = el.data('rulecode');
	let policy_id = el.data('policy');
	let policy_code = el.data('policycode');
	let policy_name = el.data('policyname');
	let img = el.data('img');
	let uom_code = el.data('uomcode');
	let uom_name = el.data('uom');
	let vat_code = el.data('vatcode');
	let vat_rate = el.data('vatrate');
	let cost = parseDefaultFloat(el.data('cost'), 0);
	let stdPrice = parseDefaultFloat(el.data('stdprice'), 0);
	let price = parseDefaultFloat(el.data('price'), 0);
	let sell_price = parseDefaultFloat(el.data('sellprice'), 0);
	let stdPriceLabel = el.data('stdpricelabel');
	let priceLabel = el.data('pricelabel');
	let sellPriceLabel = el.data('sellpricelabel');
	let discAmount = parseDefaultFloat(el.data('discamount'), 0);
	let discPercent = parseDefaultFloat(el.data('discpercent'), 0);
	let lineDiscAmount = discAmount * qty;
	let lineCost = cost * qty;
	let uid = uuid;
	let picked = 0;
	let freeQty = 0;
	let parent_row = "";

	$('.free-item').each(function () {
		if ($(this).data('uid') == parent_uid) {
			parent_row = rule_id;
			freeQty = parseDefaultInt($(this).val(), 0);
		}
	});


	$('.is-free').each(function () {
		if ($(this).data('parent') == parent_uid) {
			let no = $(this).data('id');
			let pick = parseDefaultInt($(`#line-qty-${no}`).val(), 0);
			picked += pick;
		}
	});

	picked = picked + qty;
	balance = freeQty - picked;

	if (balance >= 0) {
		$(`#btn-free-${rule_id}`).text("Free " + balance);
	}

	if (freeQty == picked) {
		$('#free-item-modal').modal('hide');
	}

	if (freeQty < picked) {
		$('#free-item-modal').modal('hide');
		swal("Error!", "จำนวนเกิน", "error");
		return false;
	}

	$('.item-code').each(function () {
		if ($(this).val() == '') {
			let id = $(this).data('id');
			$(`#row-${id}`).remove();
		}
	})

	if ($(`#${uid}`).length) {
		let no = $(`#${uid}`).data('id');
		let cqty = parseDefaultInt($(`#line-qty-${no}`).val(), 0);
		let nqty = cqty + qty;
		$(`#line-qty-${no}`).val(nqty);
	}
	else {

		let no = uniqueId();
		let data = {
			"no": no,
			"uid": uid,
			"parent_uid": parent_uid,
			"parent_row": parent_row,
			"product_id": product_id,
			"product_code": product_code,
			"product_name": product_name,
			"qty": qty,
			"cost": cost,
			"stdPrice": stdPrice,
			"price": price,
			"sellPrice": sell_price,
			"stdPriceLabel": stdPriceLabel,
			"priceLabel": priceLabel,
			"sellPriceLabel": sellPriceLabel,
			"sysSellPrice": sell_price,
			"discAmount": discAmount,
			"lineDiscAmount": lineDiscAmount,
			"lineCost": lineCost,
			"discPercent": discPercent,
			"vat_code": vat_code,
			"vat_rate": vat_rate,
			"rule_id": rule_id,
			"rule_code": rule_code,
			"policy_id": policy_id,
			"policy_code": policy_code,
			"policy_name": policy_name,
			"img": img,
			"uom_code": uom_code,
			"uom_name": uom_name
		};

		var source = $('#free-row-template').html();
		var output = $('#details-template');

		await render_append(source, data, output);
		await recalAmount(no, true);
		await init();
	}

	$(`#free-${parent_row}`).data('picked', picked);

	if (picked == freeQty) {
		$(`#btn-free-${parent_row}`).addClass('hide');
	}

	await reIndex();
	await updatePromotionApplied();
}

function recalDiscount(no) {
	regex = /[^0-9+.]+/gi;

	label = $(`#disc-label-${no}`).val();
	label = label.replace(regex, '');

	first = label.charAt(0);
	last = label.charAt(label.length - 1);

	label = first == '+' ? label.slice(1) : label;
	label = last == '+' ? label.slice(0, -1) : label;

	$(`#disc-label-${no}`).val(label);

	price = parseDefaultFloat($(`#price-${no}`).val(), 0);
	price = roundNumber(price);
	sysSellPrice = parseDefaultFloat($(`#sysSellPrice-${no}`).val(), 0);

	if (price > 0) {
		disc = parseDiscount(label, price);
		discountAmount = disc.discountAmount;
		sellPrice = disc.sellPrice;
		discPrcnt = discountAmount > 0 ? (discountAmount / price) * 100 : 0.00;
		discPrcnt = roundNumber(discPrcnt);

		$(`#totalDiscPercent-${no}`).val(discPrcnt.toFixed(2));

		if (sysSellPrice > sellPrice) {
			count_stock = $(`#count-stock-${no}`).val();

			if (count_stock == '1') {
				diff = sysSellPrice - sellPrice;
				percentDiff = (diff / sysSellPrice) * 100;
				percentDiff = roundNumber(percentDiff);
			}
			else {
				percentDiff = 0;
			}

			$(`#disc-diff-${no}`).val(percentDiff);
		}
		else {
			$(`#disc-diff-${no}`).val(0);
		}

		sellPrice = roundNumber(sellPrice, 4);

		$(`#sellPrice-${no}`).val(sellPrice);
		$(`#sell-price-${no}`).val(addCommas(sellPrice));

		recalAmount(no);
	}
}

function recalAmount(no, isFreeRow = false) {
	currentInput = removeCommas($(`#disc-label-${no}`).val());
	val = currentInput.replace(/[A-Za-z!@#$%^&*()]/g, '');
	priceLabel = removeCommas($(`#price-label-${no}`).val());
	price = roundNumber(parseDefaultFloat(priceLabel, 0.00), 2);

	$(`#price-${no}`).val(price);
	$(`#price-label-${no}`).val(addCommas(price));

	qty = parseDefaultInt($(`#line-qty-${no}`).val(), 0);
	disc = parseDiscount(val, price);
	discountAmount = disc.discountAmount;
	sellPrice = disc.sellPrice;
	sellPrice = roundNumber(sellPrice, 4);
	discPrcnt = discountAmount > 0 ? (discountAmount / price) * 100 : 0.00;

	$(`#totalDiscPercent-${no}`).val(discPrcnt.toFixed(2));

	if (sellPrice < 0 || sellPrice > price) {
		$(`#disc-label-${no}`).hasError();
		$(`#disc-error-${no}`).val(1);
		return false;
	}
	else {
		vat_rate = parseDefault(parseFloat($(`#vat-rate-${no}`).val()), 0) * 0.01;
		sysSellPrice = parseDefault(parseFloat($(`#sysSellPrice-${no}`).val()), 0.00);
		vatAmount = (sellPrice * vat_rate);

		vatTotal = (qty * vatAmount);
		vatTotal = roundNumber(vatTotal, 4);

		lineAmount = (qty * sellPrice);
		lineAmount = roundNumber(lineAmount, 2);

		lineDiscAmount = qty * discountAmount;
		lineDiscAmount = roundNumber(lineDiscAmount, 4);

		if (sysSellPrice > sellPrice) {
			diff = roundNumber(sysSellPrice - sellPrice, 4);
			percentDiff = (diff / sysSellPrice) * 100;
			percentDiff = roundNumber(percentDiff, 2);

			$(`#disc-diff-${no}`).val(percentDiff);
		}
		else {
			$(`#disc-diff-${no}`).val(0);
		}

		$(`#disc-error-${no}`).val(0);
		$(`#disc-label-${no}`).clearError();
		$(`#disc-amount-${no}`).val(discountAmount);
		$(`#line-disc-amount-${no}`).val(lineDiscAmount);
		$(`#sellPrice-${no}`).val(sellPrice);
		$(`#sell-price-${no}`).val(addCommas(sellPrice));
		$(`#vat-amount-${no}`).val(vatAmount);
		$(`#vat-total-${no}`).val(vatTotal);
		$(`#line-total-${no}`).val(lineAmount);
		$(`#total-label-${no}`).val(addCommas(lineAmount));

		if (!isFreeRow) {
			removeFreeRow();
			$('#btn-save').attr('disabled', 'disabled');
		}

		recalTotal();
	}
}

function getDiscDiff(old_price, new_price) {
	let diff = old_price - new_price;

	if (diff > 0) {
		return (diff / old_price) * 0.01;
	}

	return 0;
}

function recalTotal() {
	let availableCredit = parseDefaultFloat(removeCommas($('#available-credit').val()), 0.00);
	let total = 0.00;
	let totalCost = 0.00;
	let totalTaxAmount = 0.00;
	let df_rate = parseDefaultFloat($('#vat_rate').val(), 7); //--- default vat rate 7%
	let taxRate = df_rate * 0.01;
	let rounding = 0;

	$('.line-num').each(function () {
		let no = $(this).val();
		let qty = parseDefaultInt($(`#line-qty-${no}`).val(), 0);
		let lineCost = parseDefaultFloat($(`#line-cost-${no}`).val(), 0.00);
		let price = roundNumber(parseDefaultFloat($(`#price-${no}`).val(), 0.00), 2);
		let amount = roundNumber(parseDefaultFloat($(`#line-total-${no}`).val(), 0.00), 2);
		let rate = roundNumber(parseDefaultFloat($(`#vat-rate-${no}`).val(), 0.00), 2);

		if (qty > 0 && price > 0) {
			total += amount;
			totalCost += lineCost;

			if (rate > 0) {
				totalTaxAmount += amount;
			}
		}
	});

	//--- update bill discount
	let disc = roundNumber(parseDefaultFloat($('#discPrcnt').val(), 0), 2);
	let billDiscAmount = roundNumber(parseFloat(total * (disc * 0.01)), 2);

	$('#discAmount').val(billDiscAmount);
	$('#discAmountLabel').val(addCommas(billDiscAmount));

	//---- bill discount amount
	let amountAfterDisc = parseDefault(parseFloat(total - billDiscAmount), 0.00); //--- มูลค่าสินค้า หลังหักส่วนลด
	let amountBeforeDiscWithTax = parseDefault(parseFloat(totalTaxAmount), 0.00); //-- มูลค่าสินค้า เฉพาะที่มีภาษี
	//--- คำนวนภาษี หากมีส่วนลดท้ายบิล
	//--- เฉลี่ยส่วนลดออกให้ทุกรายการ โดยเอาส่วนลดท้ายบิล(จำนวนเงิน)/มูลค่าสินค้าก่อนส่วนลด
	//--- ได้มูลค่าส่วนลดท้ายบิลที่เฉลี่ยนแล้ว ต่อ บาท เช่น หารกันมาแล้ว ได้ 0.16 หมายถึงทุกๆ 1 บาท จะลดราคา 0.16 บาท
	let everageBillDisc = parseFloat((total > 0 ? billDiscAmount / total : 0));
	//everageBillDisc = roundNumber(everageBillDisc, 2); //-- ไม่ต้องปัดเศษ

	//--- นำผลลัพธ์ข้างบนมาคูณ กับ มูลค่าที่ต้องคิดภาษี (ตัวที่ไม่มีภาษีไม่เอามาคำนวณ)
	//--- จะได้มูลค่าส่วนลดที่ต้องไปลบออกจากมูลค่าสินค้าที่ต้องคิดภาษี
	let totalDiscTax = roundNumber(amountBeforeDiscWithTax * everageBillDisc, 2);
	let amountToPayTax = roundNumber(amountBeforeDiscWithTax - totalDiscTax, 2);
	let taxAmount = roundNumber(amountToPayTax * taxRate, 2);
	let docTotal = roundNumber(amountAfterDisc + taxAmount + rounding, 2);
	let totalProfit = roundNumber(amountAfterDisc - totalCost, 2);
	let gp = amountAfterDisc != 0 ? roundNumber((totalProfit / amountAfterDisc) * 100, 2) : 0;
	let creditBalance = roundNumber(availableCredit - docTotal, 2);

	$('#totalAmount').val(total);
	$('#totalAmountLabel').val(addCommas(total.toFixed(2)));
	$('#tax').val(taxAmount);
	$('#taxLabel').val(addCommas(taxAmount.toFixed(2)));
	$('#docTotal').val(docTotal);
	$('#docTotalLabel').val(addCommas(docTotal.toFixed(2)));
	$('#total-cost').val(totalCost);
	$('#total-gp').val(gp);
	$('#creditBalanceLabel').val(addCommas(creditBalance.toFixed(2)));
}

$('#discPrcnt').focusin(function () {
	$(this).select();
});

$('#discPrcnt').change(function () {
	$(this).clearError();
	let total = parseDefault(parseFloat($('#totalAmount').val()), 0);
	let disc = parseDefaultFloat($(this).val(), 0);

	if (disc < 0) {
		$(this).val(0);
	}
	else if (disc > 100) {
		$(this).hasError();
	}
	else {
		let discAmount = (total * (disc * 0.01));
		$('#discAmount').val(discAmount);
		$('#discAmountLabel').val(addCommas(discAmount.toFixed(2)));

		recalTotal();
	}
});

async function init() {
	$('.item-code').autocomplete({
		source: `${BASE_URL}auto_complete/get_item_code_and_name`,
		autoFocus: true,
		open: function (e) {
			var $ul = $(this).autocomplete('widget');
			$ul.css('width', 'auto');
		},
		close: function () {
			let arr = $(this).val().split(' | ');
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

	$('.item-name').keyup(function (e) {
		if (e.keyCode == 13) {
			let no = $(this).data("id");
			getItemData(no);
		}
	});

	$('.line-qty').change(function () {
		let no = $(this).data('id');
		updateDiscountRule(no);

		setTimeout(async () => {
			if ($(`#itemCode-${no}`).length && $(`#itemCode-${no}`).val() == "") {
				$(`#itemCode-${no}`).focus();
			}
			else {
				count = 0;
				$('.item-code').each(function () {
					if ($(this).val() == '') {
						no = $(this).data('id');
						count++;
						$(`#itemCode-${no}`).focus();
						return true;
					}
				});

				if (count == 0) {
					await addRow();
				}
			}
		}, 200)
	});



	$('.line-qty').focus(function () {
		$(this).select();
	});

} //-- end init

function nextFocus(name, el) {
	let no = getNo(el);
	$(`#${name}-${no}`).focus();
}

$('.autosize').autosize({ append: "\n" });

function duplicateSO(code) {
	swal({
		title: 'Duplicate Sales Order ',
		text: 'ต้องการสร้างใบสั่งขายใหม่ เหมือนใบสั่งขายนี้หรือไม่ ?',
		type: 'warning',
		showCancelButton: true,
		cancelButtonText: 'Cancel',
		confirmButtonText: 'Duplicate',
		closeOnConfirm: true
	}, function () {
		load_in();
		setTimeout(() => {
			$.ajax({
				url: `${HOME}duplicate_sales_order`,
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
								text: 'Duplicate Sales Order success : ' + ds.code,
								type: 'success',
								timer: 1000
							});

							setTimeout(function () {
								edit(ds.code);
							}, 1200);
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
					showError(rs);
				}
			});
		}, 200);
	});
}

function recal_all_discount() {
	let count = 0;
	let ids = [];
	$('.item-code').each(function () {
		if ($(this).val() != '') {
			count++;
			ids.push($(this).data('id'));
		}
	});

	if (count > 0) {
		swal({
			title: 'Warning',
			text: 'เนื่องจากมีการเปลี่ยนแปลงข้อมูลสำคัญที่มีผลต่อส่วนลด แนะนำให้ทำการคำนวณส่วนลดใหม่ ต้องการคำนวณส่วนลดใหม่หรือไม่ ?',
			type: 'warning',
			showCancelButton: true,
			cancelButtonText: 'ไม่ต้อง',
			confirmButtonText: 'คำนวณส่วนลดใหม่',
			closeOnConfirm: true
		}, function () {
			recal_order_discount(ids);
		});
	}
}

function recal_order_discount(ids) {
	let p = Promise.resolve();
	ids.forEach(no => {
		const code = $(`#itemCode-${no}`).val() || '';
		const is_free = $(`#is-free-${no}`).val() || 0;

		if (code.length && is_free == 0) {
			p = p.then(() => updateDiscountRule(no));
		}
	});
}

function dumpJson(code) {
	$.ajax({
		url: HOME + 'getJSON',
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
	if (el) {
		const sortable = Sortable.create(el, {
			animation: 150,
			handle: '.handle',
			onEnd: function (evt) {
				console.log('Moved:', evt.oldIndex, '→', evt.newIndex);
				reIndex();
				setTimeout(() => {
					updatePromotionApplied();
				}, 200);
			}
		});
	}
}

function updatePromotionApplied() {
	let promotions = [];
	$('.policy').each(function () {
		let el = $(this);
		let id = el.val();

		if (id != "") {
			let row = promotions.find(p => p.id === id);

			if (row) {
				let uid = el.data('uid');
				let no = $(`#no-${uid}`).text();

				row.rows.push({ no: no });
			}
			else {
				let uid = el.data('uid');
				let no = $(`#no-${uid}`).text();
				row = {
					id: id,
					code: el.data('code'),
					name: el.data('name'),
					rows: [{ no: no }]
				};

				promotions.push(row);
			}
		}
	});

	if (promotions.length > 0) {
		let source = $('#promotion-applied-template').html();
		let output = $('#promotions-applied');
		render(source, promotions, output);
	}
}

window.addEventListener('load', function () {
	init();
	dragger();
});



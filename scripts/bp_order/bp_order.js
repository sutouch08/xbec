function goBack() {
	window.location.href = HOME;
}


function goToPage(page) {
	window.location.href = HOME + page;
}


function history() {
	window.location.href = HOME + 'history';
}


function viewDetail(code) {
	window.location.href = HOME + 'view_detail/'+code;
}


function placeOrder() {
	var na = 0;

	$('.na').each(function() {
		if($(this).val() == '1') {
			na++;
		}
	});

	if(na > 0) {
		swal({
			title:'Oops!',
			text:"สินค้าคงเหลือไม่เพียงพอกรุณาตรวจสอบรายการที่เป็นสีแดง",
			type:'warning'
		});

		return false;
	}

	//--- check free item
	var balance = 0;

	$('.free-item').each(function() {
		let bf = parseDefault(parseInt($(this).data('balance')),0);

		balance += bf;
	});

	if(balance > 0) {
		title = 'พบรายการที่ได้รับของแถม แต่ยังไม่ได้เลือกของแถม เมื่อคุณบันทึกออเดอร์แล้ว คุณจะไม่สามารถกลับมาเลือกของแถมภายหลังได้อีก ต้องการบันทึกออเดอร์หรือไม่ ?';
		swal({
			title:'Warning!',
			text:title,
			type:'warning',
			showCancelButton:true,
			cancelButtonText:'กลับไปแก้ไข',
			confirmButtonText:'บันทึกออเดอร์',
			closeOnConfirm:true
		},
		function(){
			save();
		});
	}
	else {
		save();
	}
}


function save() {
	let totalSKU = parseDefault(parseInt($('#total-sku').val()), 0);
	let limitSKU = parseDefault(parseInt($('#limit-sku-per-order').val()), 0);

	if(limitSKU > 0 && totalSKU > limitSKU) {
		let orderCount = Math.ceil(totalSKU/limitSKU);
		swal({
			title:'Info',
			text:'ออเดอร์จะถูก Split เป็น '+orderCount+' ออเดอร์ <br/>เนื่องจากจำนวนรายการต่อออเดอร์เกินกว่าที่กำหนด<br/> ('+ limitSKU+' รายการ/ออเดอร์)',
			type:'info',
			html:true,
			closeOnConfirm:true
		}, function() {
			setTimeout(() => {
				saveAdd();
			}, 100);
		})
	}
	else {
		saveAdd();
	}
}


function saveAdd() {
	const customer_code = $('#customer_code').val();
	const payToCode = $('#billToCode').val();
	const address = $('#BillTo').val();
	const shipToCode = $('#shipToCode').val();
	const address2 = $('#ShipTo').val();
	const priceList = $('#priceList').val();
	const payment = $('#payment').val();
	const channels = $('#channels').val();
	const remark = $('#remark').val();

	load_in();

	$.ajax({
		url:HOME + 'confirm_order',
		type:'POST',
		cache:false,
		data:{
			'CardCode' : customer_code,
			'PayToCode' : payToCode,
			'Address' : address,
			'ShipToCode' : shipToCode,
			'Address2' : address2,
			'PriceList' : priceList,
			'Payment' : payment,
			'Channels' : channels,
			'remark' : remark
		},
		success:function(rs) {
			load_out();

			if(rs === 'success') {
				swal({
					title:'Success',
					type:'success',
					timer:1000
				});

				setTimeout(function() {
					goBack();
				}, 1200);
			}
			else {
				swal({
					title:'Error!',
					text:rs,
					type:'error'
				});
			}
		}
	})
}


function getShipToAddress() {
	let code = $('#customer_code').val();
	let shipCode = $('#shipToCode').val();

	load_in();

	$.ajax({
		url:HOME + 'get_ship_to_address',
		type:'GET',
		cache:false,
		data:{
			'CardCode' : code,
			'ShipToCode' : shipCode
		},
		success:function(rs) {
			load_out();
			if(isJson(rs)) {
				let ds = $.parseJSON(rs);
				$('#ShipTo').val(ds.address);
			}
			else {
				swal({
					title:'Error!',
					text:rs,
					type:'error'
				});
			}
		}
	});
}


function getBillToAddress() {
	let code = $('#customer_code').val();
	let billCode = $('#billToCode').val();

	load_in();

	$.ajax({
		url:HOME + 'get_bill_to_address',
		type:'GET',
		cache:false,
		data:{
			'CardCode' : code,
			'BillToCode' : billCode
		},
		success:function(rs) {
			load_out();
			if(isJson(rs)) {
				let ds = $.parseJSON(rs);
				$('#BillTo').val(ds.address);
			}
			else {
				swal({
					title:'Error!',
					text:rs,
					type:'error'
				});
			}
		}
	});
}


function addTocart() {
	setTimeout(function() {
		const quota = $('#quotaNo').val();
		const cardCode = $('#customer_code').val();
		const payment = $('#payment').val();
		const channels = $('#channels').val();

		$('#itemModal').modal('hide');

		ds = [];
		items = [];

		$('.input-qty').each(function() {
			let id = $(this).data('id');
			let qty = parseDefault(parseInt($(this).val()), 0);

			if(qty > 0) {

				let code = $('#product-code-'+id).val();
				let arr = {"ItemCode" : code, "Qty" : qty};
				items.push(arr);
			}
		});


		if(items.length == 0) {
			setTimeout(function() {
				swal({
					title:'Warning',
					text:'กรุณาระบุจำนวนสินค้าอย่างน้อย 1 รายการ',
					type:'warning',
					showCancelButton:false,
					closeOnConfirm:true
				}, function() {
					$('#itemModal').modal('show');
				})
			}, 500)


			return false;
		}

		load_in();

		$.ajax({
			url:HOME + 'add_to_cart',
			type:'POST',
			cache:false,
			data:{
				'quotaNo' : quota,
				'CardCode' : cardCode,
				'Payment' : payment,
				'Channels' : channels,
				'items' : items
			},
			success:function(rs) {
				load_out();

				if(rs === 'success') {
					swal({
						title:'Success',
						type:'success',
						timer:1000
					});

					updateCart();
				}
				else {
					swal({
						title:'Error',
						text:rs,
						type:'error'
					})
				}
			}
		})

	}, 200); //-- setTimeout
}


function addFavTocart() {
	setTimeout(function() {
		const quota = $('#quotaNo').val();
		const cardCode = $('#customer_code').val();
		const payment = $('#payment').val();
		const channels = $('#channels').val();

		ds = [];
		items = [];

		$('.input-qty').each(function() {
			let id = $(this).data('id');
			let qty = parseDefault(parseInt($(this).val()), 0);

			if(qty > 0) {

				let code = $('#product-code-'+id).val();
				let arr = {"ItemCode" : code, "Qty" : qty};
				items.push(arr);
			}
		});


		if(items.length == 0) {
			setTimeout(function() {
				swal({
					title:'Warning',
					text:'กรุณาระบุจำนวนสินค้าอย่างน้อย 1 รายการ',
					type:'warning',
					showCancelButton:false,
					closeOnConfirm:true
				})
			}, 500);


			return false;
		}

		load_in();

		$.ajax({
			url:HOME + 'add_to_cart',
			type:'POST',
			cache:false,
			data:{
				'quotaNo' : quota,
				'CardCode' : cardCode,
				'Payment' : payment,
				'Channels' : channels,
				'items' : items
			},
			success:function(rs) {
				load_out();

				if(rs === 'success') {
					swal({
						title:'Success',
						type:'success',
						timer:1000
					});

					updateCart();
					$('.input-qty').val("");
				}
				else {
					swal({
						title:'Error',
						text:rs,
						type:'error'
					})
				}
			}
		});

	}, 200); //-- setTimeout
}


function addItemTocart() {
	setTimeout(function() {
		const quota = $('#quotaNo').val();
		const cardCode = $('#customer_code').val();
		const payment = $('#payment').val();
		const channels = $('#channels').val();

		$('#itemModal').modal('hide');

		ds = [];
		items = [];

		let item = $('#item-input-qty');
		let id = item.data('id');
		let code = item.data('code');
		let qty = parseDefault(parseInt(item.val()), 0);

		if(qty > 0) {
			let arr = {"ItemCode" : code, "Qty" : qty};
			items.push(arr);
		}

		if(items.length == 0) {
			setTimeout(function() {
				swal({
					title:'Warning',
					text:'กรุณาระบุจำนวนสินค้า',
					type:'warning',
					showCancelButton:false,
					closeOnConfirm:true
				}, function() {
					$('#itemModal').modal('show');
				})
			}, 500)

			return false;
		}

		load_in();

		$.ajax({
			url:HOME + 'add_to_cart',
			type:'POST',
			cache:false,
			data:{
				'quotaNo' : quota,
				'CardCode' : cardCode,
				'Payment' : payment,
				'Channels' : channels,
				'items' : items
			},
			success:function(rs) {
				load_out();

				if(rs === 'success') {
					swal({
						title:'Success',
						type:'success',
						timer:1000
					});

					updateCart();
				}
				else {
					swal({
						title:'Error',
						text:rs,
						type:'error'
					})
				}
			}
		})

	}, 200); //-- setTimeout
}



function updateCart() {
	$.ajax({
		url:HOME + 'get_cart_table',
		type:'GET',
		cache:false,
		data:{
			'CardCode' : $('#customer_code').val()
		},
		success:function(rs) {
			if(isJson(rs))
			{
				let ds = $.parseJSON(rs);
				let source = $('#cart-template').html();
				let output = $('#cart-table');
				render(source, ds, output);

				recalTotal();
			}
		}
	});
}


function updateCartQty(id) {
	let qty = parseDefault(parseInt($('#input-qty-'+id).val()), 0);
	let prevQty = $('#input-qty-'+id).data('val');

	if(qty <= 0) {
		$('#input-qty-'+id).val(prevQty);
		return false;
	}
	else {
		$('#input-qty-'+id).data('val', qty);
		$.ajax({
			url:HOME + 'update_cart_qty',
			type:'POST',
			cache:false,
			data:{
				'id' : id,
				'qty' : qty
			},
			success:function(rs) {
				if(isJson(rs)) {
					let ds = $.parseJSON(rs);
					$('#cart-row-'+id).removeClass('red');
					$('#cart-row-'+id).addClass(ds.red);
					let source = $('#cart-row-template').html();
					let output = $('#cart-row-'+id);

					render(source, ds, output);

					recalTotal();
				}
				else {
					swal({
						title:'Error!',
						text:rs,
						type:'error'
					});
				}
			}
		})
	}
}


function updateCheckQty(id) {
	let qty = parseDefault(parseInt($('#input-qty-'+id).val()), 0);
	let prevQty = $('#input-qty-'+id).data('val');

	if(qty <= 0) {
		$('#input-qty-'+id).val(prevQty);
		return false;
	}
	else {
		$('#input-qty-'+id).data('val', qty);
		$.ajax({
			url:HOME + 'update_cart_qty',
			type:'POST',
			cache:false,
			data:{
				'id' : id,
				'qty' : qty
			},
			success:function(rs) {
				if(isJson(rs)) {
					let ds = $.parseJSON(rs);

					let qty = parseDefault(parseInt(ds.Qty), 0);
					let available = parseDefault(parseInt(ds.available), 0);
					let price = parseDefault(parseFloat(ds.Price), 0);
					let sellPrice = parseDefault(parseFloat(ds.SellPrice), 0);
					let lineTotal = parseDefault(parseFloat(ds.LineTotal), 0);
					let vatAmount = parseDefault(parseFloat(ds.available), 0);
					let na = qty > available ? 1 : 0;

					$('#line-qty-'+id).val(qty);
					$('#line-available-'+id).val(available);
					$('#line-total-'+id).val(lineTotal);
					$('#line-vat-'+id).val(vatAmount);
					$('#price-'+id).val(price);
					$('#sellPrice-'+id).val(sellPrice);
					$('#na-'+id).val(na);
					$('#priceLabel-'+id).text(addCommas(price.toFixed(2)));
					$('#discLabel-'+id).text(ds.discLabel);
					$('#availableLabel-'+id).text(addCommas(available));
					$('#totalLabel-'+id).text(addCommas(lineTotal.toFixed(2)));

					if(qty > available) {
						$('#row-'+id).addClass('red');
					}
					else {
						$('#row-'+id).removeClass('red');
					}

					recalTotal();
				}
				else {
					swal({
						title:'Error!',
						text:rs,
						type:'error'
					});
				}
			}
		})
	}
}



function viewCart() {
	let maxHeight = 400;
	let vh = $(window).height();
	let hh = 61; //$('#modal-header').outerHeight();
	let fh = $('#modal-footer').outerHeight();
	let bh = vh - hh - fh;
	bh = bh > maxHeight ? maxHeight : bh;

	$('#modal-body').outerHeight(bh);

	$('#cartModal').modal('show');

}




function showCategoryItem(categoryCode) {
	const quota = $('#quotaNo').val();
	const cardCode = $('#customer_code').val();
	const payment = $('#payment').val();
	const channels = $('#channels').val();

	load_in();

	$.ajax({
		url:HOME + 'get_category_items',
		type:'GET',
		cache:false,
		data:{
			'category_code' : categoryCode,
			'CardCode' : cardCode,
			'quotaNo' : quota,
			'Payment' : payment,
			'Channels' : channels
		},
		success:function(rs) {
			load_out();
			if(isJson(rs)) {
				let ds = $.parseJSON(rs);
				let source = $('#cate-template').html();
				let output = $('#cate-table');

				render(source, ds, output);
			}

			$('#cateModal').modal('show');
		}
	})
}



function showItem(code, id) {
	const quota = $('#quotaNo').val();
	const cardCode = $('#customer_code').val();
	const payment = $('#payment').val();
	const channels = $('#channels').val();

	load_in();

	$.ajax({
		url:HOME + 'get_item',
		type:'GET',
		cache:false,
		data:{
			'ItemCode' : code,
			'CardCode' : cardCode,
			'quotaNo' : quota,
			'Payment' : payment,
			'Channels' : channels
		},
		success:function(rs) {
			load_out();
			if(isJson(rs)) {
				let ds = $.parseJSON(rs);
				let source = $('#item-template').html();
				let output = $('#item-table');

				render(source, ds, output);
			}

			$('#itemModal').modal('show');
		}
	})
}


$('#itemModal').on('shown.bs.modal', function() {
	$('#item-input-qty').focus().select();
});

function removeNonCheck() {
	$('.item-chk').each(function() {
		if($(this).is(':checked') == false) {
			id = $(this).val();

			$('#item-row-'+id).remove();
		}
	})
}


function closeModal(name) {
	$('#'+name).modal('hide');
}



$('#item-qty').keyup(function() {
	let qty = parseDefault(parseInt($('#item-qty').val()), 0);
	let price = parseDefault(parseFloat($('#sell-price').val()), 0.00);

	if(qty <= 1) {
		qty = 1;
	}

	amount = qty * price;
	$('#item-qty').val(qty);
	$('#btn-price').text(addCommas(amount.toFixed(2)));
});


function recalTotal() {
	let totalAmount = 0;
	let totalQty = 0;
	let totalVat = 0;

	$('.line-qty').each(function() {
		let no = $(this).data('no');
		let qty = parseDefault(parseInt($(this).val()), 0);
		let amount = parseDefault(parseFloat($('#line-total-'+no).val()), 0.00);
		let vat = parseDefault(parseFloat($('#line-vat-'+no).val()), 0.00);
		totalQty += qty;
		totalAmount += amount;
		totalVat += vat;
	});

	let docTotal = totalAmount + totalVat;

	$('#total-qty').text(addCommas(totalQty));
	$('#total-amount').text(addCommas(totalAmount.toFixed(2)));
	$('#total-vat').text(addCommas(totalVat.toFixed(2)));
	$('#doc-total').text(addCommas(docTotal.toFixed(2)));

	$('#bar-amount').text(addCommas(docTotal.toFixed(2)));
	$('#top-amount').text(addCommas(docTotal.toFixed(2)));
}



function removeRow(id){
	swal({
		title:"ต้องการลบสินค้าจากตะกร้า ?",
		type: "warning",
		showCancelButton: true,
		confirmButtonColor: "#DD6B55",
		confirmButtonText: 'ยืนยัน',
		cancelButtonText: 'ยกเลิก',
		closeOnConfirm: true
		},
		function(){
			load_in();
			$.ajax({
				url:HOME + 'remove_cart_row',
				type:'POST',
				cache:false,
				data:{
					'id' : id
				},
				success:function(rs) {
					load_out();
					if(rs == 'success') {
						$('#cart-row-'+id).remove();
						recalTotal();
					}
					else {
						setTimeout(function() {
							swal({
								title:'Error!',
								type:'error',
								text:rs
							});
						}, 500);
					}
				}
			});
	});
}



function recalAmount(id) {
	qty = parseDefault(parseInt($('#qty-'+id).val()), 0);
	qty = qty < 0 ? 1 : qty;
	$('#qty-'+id).val(qty);
	sellPrice = parseDefault(parseFloat($('#sellPrice-'+id).val()), 0);

	amount = roundNumber(qty * sellPrice);

	$('#line-amount-'+id).text(addCommas(amount.toFixed(2)));
}



function checkout() {
	$('#cartModal').modal('hide');

	load_in();
	setTimeout(function() {
		window.location.href = HOME + 'checkout';
	}, 500)
}


function checkOutAll(el) {
	if(el.is(':checked')) {
		$('.chk-out').prop('checked', true);
	}
	else {
		$('.chk-out').prop('checked', false);
	}
}


function removeCheckRow(id, code) {
	swal({
		title:"ต้องการลบ "+code+" ?",
		type: "warning",
		showCancelButton: true,
		confirmButtonColor: "#DD6B55",
		confirmButtonText: 'ยืนยัน',
		cancelButtonText: 'ยกเลิก',
		closeOnConfirm: true
		},
		function(){
			load_in();
			$.ajax({
				url:HOME + 'remove_cart_row',
				type:'POST',
				cache:false,
				data:{
					'id' : id
				},
				success:function(rs) {
					load_out();
					if(rs == 'success') {
						$('#row-'+id).remove();
						getFreeItemRule();
						recalTotal();
					}
					else {
						setTimeout(function() {
							swal({
								title:'Error!',
								type:'error',
								text:rs
							});
						}, 500);
					}
				}
			});
	});
}


function removeFreeRow(id, code) {
	swal({
		title:"ต้องการลบ "+code+" ?",
		type: "warning",
		showCancelButton: true,
		confirmButtonColor: "#DD6B55",
		confirmButtonText: 'ยืนยัน',
		cancelButtonText: 'ยกเลิก',
		closeOnConfirm: true
		},
		function(){
			load_in();
			$.ajax({
				url:HOME + 'remove_cart_row',
				type:'POST',
				cache:false,
				data:{
					'id' : id
				},
				success:function(rs) {
					load_out();
					if(rs == 'success') {
						let qty = parseDefault(parseInt($('#line-qty-'+id).val()), 0);
						let rule_id = $('#is-free-'+id).data('parentrow');
						let bl = parseDefault(parseInt($('#free-'+rule_id).data('balance')), 0);
						let picked = parseDefault(parseInt($('#free-'+rule_id).data('picked')), 0);
						let freeQty = qty + bl;
						picked = picked - qty;

						$('#row-'+id).remove();
						$('#free-'+rule_id).data('balance', freeQty);
						$('#free-'+rule_id).data('picked', picked);
						$('#btn-free-'+rule_id).text('Free '+freeQty);
						$('#btn-free-'+rule_id).removeClass('hide');

					}
					else {
						setTimeout(function() {
							swal({
								title:'Error!',
								type:'error',
								text:rs
							});
						}, 500);
					}
				}
			});
	});
}



function getFreeItemRule()
{
	cardCode = $.trim($('#customer_code').val());
	today = new Date();
	dd = String(today.getDate()).padStart(2, '0');
	mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
	yyyy = today.getFullYear();

	docDate = dd + '-' + mm + '-' + yyyy;

	$.ajax({
		url:HOME + 'remove_free_rows',
		type:'POST',
		cache:false,
		data:{
			"CardCode" : cardCode
		},
		success:function(rs) {
			rs = $.trim(rs);

			if(rs === 'success') {

				$('.free-row').remove();

				ds = {
					'DocDate' : docDate,
					'CardCode' : cardCode,
					'Payment' : $('#payment').val(),
					'Channels' : $('#channels').val()
				};


				var items = {};
				//--- get sum item qty, amount
				$('.item-code').each(function() {
					itemCode = $(this).val();
					if(itemCode.length) {
						no = $(this).data('id');
						is_free = $('#is-free-'+no).val();
						if(is_free == 0) {
							product_id = $('#product-id-'+no).val();
							qty = parseDefault(parseInt($('#line-qty-'+no).val()), 0);
							amount = parseDefault(parseFloat($('#line-total-'+no).val()), 0.00);

							if(items.hasOwnProperty(product_id)) {
								qty += parseInt(items[product_id].qty);
								amount += parseFloat(items[product_id].amount);
							}

							items[product_id] = {"itemCode" : itemCode, "qty" : qty, "amount" : amount};
						}
					}
				});

				ds.items = items;

				if(Object.keys(items).length) {
					load_in();
					$.ajax({
						url:HOME + 'get_free_item_rule',
						type:'POST',
						cache:false,
						data:{
							"json" : JSON.stringify(ds)
						},
						success:function(rs) {
							load_out();

							if(isJson(rs)) {
								ds = $.parseJSON(rs);
								$.each(ds, function(index, value) {
									if($('#free-'+value.rule_id).length) {
										$('#free-'+value.rule_id).val(value.freeQty);
										$('#btn-free-'+value.rule_id).text('Free '+value.freeQty);
									}
									else {
										source = $('#free-input-template').html();
										output = $('#free-temp');
										render_append(source, value, output);

										source = $('#free-btn-template').html();
										output = $('#free-box');
										render_append(source, value, output);
									}
								});
							}
						}
					})
				}

			}
			else {
				swal({
					title:'Error!',
					text:rs,
					type:'error'
				});
			}
		}
	});

	$('#btn-save').removeClass('hide');
	$('#btn-draft').removeClass('hide');
}


function pickFreeItem(rule_id) {
	freeQty = $('#free-'+rule_id).val();
	uid = $('#free-'+rule_id).data('uid');
	picked = $('#free-'+rule_id).data('picked');
	console.log(freeQty, uid, picked);
	if(rule_id != "" && rule_id > 0 && freeQty > 0 && picked < freeQty) {
		load_in();

		$.ajax({
			url:HOME + 'get_free_item',
			type:'GET',
			cache:false,
			data:{
				'rule_id' : rule_id,
				'freeQty' : freeQty,
				'picked' : picked,
				'uid' : uid
			},
			success:function(rs) {
				load_out();
				setTimeout(function() {
					$('#free-item-list').html(rs);
					$('.auto-select').focus(function() {
						$(this).select();
					});
					$('#free-item-modal').modal('show');

				}, 500)
			}
		});
	}
}


function addFreeRow(uuid) {
	let el = $('#input-'+uuid);
	let qty = parseDefault(parseInt(el.val()), );
	let product_id = el.data('item');
	let product_code = el.data('pdcode');
	let product_name = el.data('pdname');
	let parent_uid = el.data('parent');
	let rule_id = el.data('rule');
	let policy_id = el.data('policy');
	let img = el.data('img');
	let uom_code = el.data('uomcode');
	let uom_name = el.data('uom');
	let vat_code = el.data('vatcode');
	let vat_rate = el.data('vatrate');
	let price = el.data('price');
	let priceLabel = addCommas(price);
	let uid = uuid;
	let picked = 0;
	let freeQty = 0;
	let parent_row = "";

	$('.free-item').each(function() {
		if($(this).data('uid') == parent_uid) {
			parent_row = rule_id;
			freeQty = parseDefault(parseInt($(this).val()), 0);
			//picked = parseDefault(parseInt($(this).data('picked')), 0);
		}
	});


	$('.is-free').each(function() {
		if($(this).data('parent') == parent_uid) {
			let no = $(this).data('id');
			let pick = parseDefault(parseInt($('#line-qty-'+no).val()), 0);
			picked += pick;
		}
	});

	picked = picked + qty;
	balance = freeQty - picked;

	if(balance >= 0) {
		$('#btn-free-'+rule_id).text("Free "+balance);
	}

	if(freeQty == picked) {
		$('#free-item-modal').modal('hide');
	}

	if(freeQty < picked) {
		$('#free-item-modal').modal('hide');
		swal("Error!", "จำนวนเกิน", "error");
		return false;
	}

	$('.item-code').each(function() {
		if($(this).val() == '') {
			no = $(this).data('id');
			$('#row-'+no).remove();
		}
	})

	let cardCode = $('#customer_code').val();
	let channels_id = $('#channels').val();
	let payment_id = $('#payment').val();
	let quotaNo = $('#quotaNo').val();


	var data = {
		"uid" : uid,
		"CardCode" : cardCode,
		"channels_id" : channels_id,
		"payment_id" : payment_id,
		"quotaNo" : quotaNo,
		"parent_uid" : parent_uid,
		"product_id" : product_id,
		"ItemCode" : product_code,
		"ItemName" : product_name,
		"Qty" : qty,
		"rule_id" : rule_id,
		"policy_id" : policy_id
	};

	$.ajax({
		url:HOME + 'add_free_row',
		type:'POST',
		cache:false,
		data: data,
		success:function(rs) {
			if(isJson(rs)) {
				var ds = $.parseJSON(rs);
				let id = ds.id;
				if($('#row-'+id).length) {
					let cqty = parseDefault(parseInt($('#line-qty-'+id).val()), 0);
					let nqty = cqty + qty;
					$('#line-qty-'+id).val(nqty);
					$('#qtyLabel-'+id).text(addCommas(nqty));
				}
				else {
					var source = $('#free-row-template').html();
					var output = $('#checkout-table');
					render_append(source, ds, output);
				}
			}
		}
	});


	$('#free-' + parent_row).data('picked', picked);
	$('#free-' + parent_row).data('balance', balance);

	if(picked == freeQty) {
		$('#btn-free-' + parent_row).addClass('hide');
	}
}


function search() {
	$('#search-form').submit();
}

function clearText(page) {
	goToPage(page);
}

function clear_search_filter() {
	$.get(HOME + 'clear_item_filter', function() {
		goToPage('items');
	});
}


function clear_filter() {
	$.get(HOME + 'clear_filter', function() {
		history();
	});
}


$('#from_date').datepicker({
	dateFormat:'dd-mm-yy',
	onClose:function(sd) {
		$('#to_date').datepicker('option', 'minDate', sd);
	}
});

$('#to_date').datepicker({
	dateFormat:'dd-mm-yy',
	onClose:function(sd) {
		$('#from_date').datepicker('option', 'maxDate', sd);
	}
})


function updateAvailable() {
	$.ajax({
		url:HOME + 'get_cart_avalible',
		type:'GET',
		cache:false,
		success:function(rs) {
			if(isJson(rs)) {
				let ds = $.parseJSON(rs);
				if(ds.length > 0) {
					ds.forEach(function(item) {
						let id = item.id;
						let val = parseDefault(parseInt(item.available), 0);
						let qty = parseDefault(parseInt($('#input-qty-'+id).val()), 0);
						$('#available-'+id).text(val);

						if(qty > val) {
							$('#cart-row-'+id).addClass('red');
						}
						else {
							$('#cart-row-'+id).removeClass('red');
						}

					});
				}
			}
		}
	});
}


function updateFavoriteAvailable() {
	$('.item-box').each(function() {
		let item_id = $(this).val();
		$.ajax({
			url:HOME + 'get_favorite_available/'+item_id,
			type:'GET',
			cache:false,
			success:function(rs) {
				if(isJson(rs)) {
					let ds = $.parseJSON(rs);
					let available = parseDefault(parseInt(ds.available), 0);
					$('#item-card-'+ds.id).text(available);
				}
			}
		});
	})
}

function addToFavorite(product_id, item_code) {
	let btn = '<button type="button" class="btn btn-xs btn-default btn-block" onclick="removeFromFavorite('+product_id+')">Remove From Favorite</button>';
	$.ajax({
		url:HOME + 'add_to_favorite',
		type:'POST',
		cache:false,
		data:{
			'product_id' : product_id
		},
		success:function(rs) {
			if(rs == 'success'){
				$('#item-card-'+product_id).html(btn);
			}
			else {
				swal({
					title:'Error!',
					text:rs,
					type:'error'
				});
			}
		}
	});

}


function removeFromFavorite(product_id, is_delete) {
	let btn = '<button type="button" class="btn btn-xs btn-primary btn-block" onclick="addToFavorite('+product_id+')">Add To Favorite</button>';
	$.ajax({
		url:HOME + 'remove_from_favorite',
		type:'POST',
		cache:false,
		data:{
			'product_id' : product_id
		},
		success:function(rs) {
			if(rs == 'success'){
				$('#item-card-'+product_id).html(btn);
				if(is_delete == 1) {
					$('#item-box-'+product_id).remove();
				}
			}
			else {
				swal({
					title:'Error!',
					text:rs,
					type:'error'
				});
			}
		}
	});
}

function removeFavorite(product_id) {
	$.ajax({
		url:HOME + 'remove_from_favorite',
		type:'POST',
		cache:false,
		data:{
			'product_id' : product_id
		},
		success:function(rs) {
			if(rs == 'success'){
				$('#fav-row-'+product_id).remove();
			}
			else {
				swal({
					title:'Error!',
					text:rs,
					type:'error'
				});
			}
		}
	});
}

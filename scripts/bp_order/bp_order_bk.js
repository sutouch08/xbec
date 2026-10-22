function goBack() {
	window.location.href = HOME;
}


function placeOrder() {
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
	const priceList = $('#priceList').val();
	const quota = $('#quotaNo').val();
	const cardCode = $('#customer_code').val();
	const payment = $('#payment').val();
	const channels = $('#channels').val();
	const itemCode = $('#ItemCode').val();
	const qty = $('#item-qty').val();

	$('#itemModal').modal('hide');

	load_in();

	$.ajax({
		url:HOME + 'add_to_cart',
		type:'POST',
		cache:false,
		data:{
			'PriceList' : priceList,
			'quotaNo' : quota,
			'CardCode' : cardCode,
			'Payment' : payment,
			'Channels' : channels,
			'ItemCode' : itemCode,
			'Qty' : qty
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

function viewCart() {
	$('#cartModal').modal('show');
}




function showItem(itemCode) {

	const priceList = $('#priceList').val();
	const quota = $('#quotaNo').val();
	const cardCode = $('#customer_code').val();
	const payment = $('#payment').val();
	const channels = $('#channels').val();

	load_in();

	$.ajax({
		url:HOME + 'get_item_data',
		type:'GET',
		cache:false,
		data:{
			'ItemCode' : itemCode,
			'PriceList' : priceList,
			'CardCode' : cardCode,
			'quotaNo' : quota,
			'Payment' : payment,
			'Channels' : channels
		},
		success:function(rs) {
			load_out();
			if(isJson(rs)) {
				let ds = $.parseJSON(rs);
				$('#ItemCode').val(ds.ItemCode);
				$('#img').html('<img src="'+ ds.image + '" class="width-100" />');
				$('#item-code').text(ds.ItemCode);
				$('#item-name').text(ds.ItemName);
				$('#sell-price').val(ds.SellPrice);
				$('#item-qty').val(1);

				let sellPrice = ds.SellPrice;
				let price = ds.Price;

				if(ds.SellPrice < ds.Price) {
					$('#item-price').html(addCommas(sellPrice.toFixed(2)) + "<span class='old_price'>" + addCommas(price.toFixed(2))+ "</span>");
				}
				else {
					$('#item-price').text(addCommas(price.toFixed(2)));
				}

				$('#btn-price').text(addCommas(sellPrice.toFixed(2)));
			}

			$('#itemModal').modal('show');
		}
	})
}


function spinDown() {
	let qty = parseDefault(parseInt($('#item-qty').val()), 0);
	let price = parseDefault(parseFloat($('#sell-price').val()), 0.00);

	if(qty <= 1) {
		qty = 1;
	}
	else {
		qty--;
	}

	amount = qty * price;
	$('#item-qty').val(qty);
	$('#btn-price').text(addCommas(amount.toFixed(2)));
}


function spinUp() {
	let qty = parseDefault(parseInt($('#item-qty').val()), 0);
	let price = parseDefault(parseFloat($('#sell-price').val()), 0.00);

	if(qty < 1) {
		qty = 1;
	}
	else {
		qty++;
	}

	amount = qty * price;
	$('#item-qty').val(qty);
	$('#btn-price').text(addCommas(amount.toFixed(2)));
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



function qtyUp(no) {
	let qty = parseDefault(parseInt($('#cart-qty-'+no).val()), 0);
	let price = parseDefault(parseFloat($('#sellPrice-'+no).val()), 0.00);
	let id = $('#cart-qty-'+no).data('id');

	if(qty < 1) {
		qty = 1;
	}
	else {
		qty++;
	}

	load_in();

	$.ajax({
		url:HOME + 'update_cart_qty',
		type:'POST',
		cache:false,
		data:{
			'id' : id,
			'qty' : qty
		},
		success:function(rs) {
			load_out();
			if(rs === 'success') {
				amount = qty * price;
				$('#cart-qty-'+no).val(qty);
				$('#amount-'+no).val(amount);

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
	});
}

function qtyDown(no) {
	let qty = parseDefault(parseInt($('#cart-qty-'+no).val()), 0);
	let price = parseDefault(parseFloat($('#sellPrice-'+no).val()), 0.00);
	let id = $('#cart-qty-'+no).data('id');

	if(qty <= 1) {
		qty = 1;
	}
	else {
		qty--;
	}

	load_in();

	$.ajax({
		url:HOME + 'update_cart_qty',
		type:'POST',
		cache:false,
		data:{
			'id' : id,
			'qty' : qty
		},
		success:function(rs) {
			load_out();
			if(rs === 'success') {
				amount = qty * price;
				$('#cart-qty-'+no).val(qty);
				$('#amount-'+no).val(amount);

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
	});
}


function recalTotal() {
	let totalAmount = 0;
	let totalQty = 0;

	$('.cart-qty').each(function() {
		let no = $(this).data('no');
		let qty = parseDefault(parseInt($(this).val()), 0);
		let price = parseDefault(parseFloat($('#sellPrice-'+no).val()), 0.00);
		let amount = qty * price;
		totalQty += qty;
		totalAmount += amount;
	});

	$('#total-qty').text(addCommas(totalQty));
	$('#total-amount').text(addCommas(totalAmount.toFixed(2)));

	$('#bar-amount').text(addCommas(totalAmount.toFixed(2)));
	$('#top-amount').text(addCommas(totalAmount.toFixed(2)));
}

function removeRow(no){
	let id = $('#cart-qty-'+no).data('id');

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
						$('#cart-row-'+no).remove();
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



function checkout() {
	window.location.href = HOME + 'checkout';
}

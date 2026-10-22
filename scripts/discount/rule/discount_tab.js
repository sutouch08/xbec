function saveDiscount() {
  const id = $('#rule_id').val();

	let discType = $("input[name='discType']:checked").val();
	  //--- กำหนดราคาขาย
	let price = $('#net-price').val();

	//--- กำหนดส่วนลด
	let disc1 = parseDefault(parseFloat($('#disc1').val()), 0);
	let disc2 = parseDefault(parseFloat($('#disc2').val()), 0);
	let disc3 = parseDefault(parseFloat($('#disc3').val()), 0);
	let disc4 = parseDefault(parseFloat($('#disc4').val()), 0);
	let disc5 = parseDefault(parseFloat($('#disc5').val()), 0);

	//--- จำนวนของแถม
	let freeQty = parseDefault(parseInt($('#free-qty').val()), 0);

  //--- กำหนดจำนวนขั้นต่ำ
  minQty = parseDefault(parseFloat($('#min-qty').val()), 0);

  //--- กำหนดมูลค่าขั้นต่ำ
  minAmount = parseDefault(parseFloat($('#min-amount').val()), 0);

  //--- สามารถรวมยอดได้หรือไม่
  canGroup = $("input[name='canGroup']:checked").val();

	priority = $('#priority').val();

	if(discType == 'F' && freeQty <= 0) {
		swal('ข้อผิดพลาด', 'กรุณาระบุจำนวนของแถม', 'error');
		$('#free-qty').addClass('has-error');
    return false;
	}
	else {
		$('#free-qty').removeClass('has-error');
	}

  if(discType == 'N' && price <= 0) {
    swal('ข้อผิดพลาด', 'ราคาขายต้องมากกว่า 0', 'error');
		$('#net-price').addClass('has-error');
    return false;
  }
	else {
		$('#net-price').removeClass('has-error');
	}

  if(discType == 'P' && (disc1 <= 0 || disc1 > 100 ) && freeQty <= 0) {
    swal('ข้อผิดพลาด', 'ส่วนลดไม่ถูกต้อง', 'error');
		$('#disc1').addClass('has-error');
    return false;
  }
	else {
		$('#disc1').removeClass('has-error');
	}


	if(discType == 'P' && ((disc1 <= 0 && disc2 > 0) || disc2 < 0 || disc2 > 100 || (disc1 == 100 && disc2 > 0 ))) {
    swal('ข้อผิดพลาด', 'ส่วนลดไม่ถูกต้อง', 'error');
		$('#disc2').addClass('has-error');
    return false;
  }
	else {
		$('#disc2').removeClass('has-error');
	}

	if(discType == 'P' && ((disc2 <= 0 && disc3 > 0) || disc3 < 0 || disc3 > 100 || ((disc2 == 100 || disc2 == 0) && disc3 > 0 ))) {
    swal('ข้อผิดพลาด', 'ส่วนลดไม่ถูกต้อง', 'error');
		$('#disc3').addClass('has-error');
    return false;
  }
	else {
		$('#disc3').removeClass('has-error');
	}

	if(discType == 'P' && ((disc3 <= 0 && disc4 > 0) || disc4 < 0 || disc4 > 100 || ((disc3 == 100 || disc3 == 0) && disc4 > 0 ))) {
    swal('ข้อผิดพลาด', 'ส่วนลดไม่ถูกต้อง', 'error');
		$('#disc4').addClass('has-error');
    return false;
  }
	else {
		$('#disc4').removeClass('has-error');
	}

	if(discType == 'P' && ((disc4 <= 0 && disc5 > 0 ) || disc5 < 0 || disc5 > 100 || ((disc4 == 100 || disc4 == 0) && disc5 > 0 ))) {
    swal('ข้อผิดพลาด', 'ส่วนลดไม่ถูกต้อง', 'error');
		$('#disc5').addClass('has-error');
    return false;
  }
	else {
		$('#disc5').removeClass('has-error');
	}


  if(minQty < 0) {
    swal('ข้อผิดพลาด', 'จำนวนขั้นต่ำต้องไม่น้อยกว่า 0', 'error');
		$('#min-qty').addClass('has-error');
    return false;
  }
	else {
		$('#min-qty').removeClass('has-error');
	}

  if(minAmount < 0){
    swal('ข้อผิดพลาด', 'มูลค่าขั้นต่ำต้องไม่น้อยกว่า 0', 'error');
		$('#min-amount').addClass('has-error');
    return false;
  }
	else {
		$('#min-amount').removeClass('has-error');
	}

	let ds = [
		{'name' : 'rule_id' , 'value' : id},
		{'name' : 'discType', 'value' : discType},
		{'name' : 'price' , 'value' : price},
		{'name' : 'disc1' , 'value' : disc1},
		{'name' : 'disc2' , 'value' : disc2},
		{'name' : 'disc3' , 'value' : disc3},
		{'name' : 'disc4' , 'value' : disc4},
		{'name' : 'disc5' , 'value' : disc5},
		{'name' : 'freeQty', 'value' : freeQty},
		{'name' : 'minQty' , 'value' : minQty},
		{'name' : 'minAmount' , 'value' : minAmount},
		{'name' : 'canGroup' , 'value' : canGroup},
		{'name' : 'priority', 'value' : priority}
	];


	if($('.free-item-id').length) {
		$('.free-item-id').each(function() {
			let pid = $(this).val();
			let name = "freeItems["+pid+"]";
			ds.push({"name" : name, "value" : pid});
		});
	}


  load_in();

  $.ajax({
    url:BASE_URL + 'discount/discount_rule/set_discount',
    type:'POST',
    cache:'false',
    data:ds,
    success:function(rs){
      load_out();
      var rs = $.trim(rs);
      if(rs == 'success'){
        swal({
          title:'Success',
          type:'success',
          timer:1000
        });

				setTimeout(function() {
					window.location.reload();
				}, 1200);

      }else{
        swal('Error', rs, 'error');
      }
    }
  });

}


$('#free-item-box').autocomplete({
	source: BASE_URL + 'auto_complete/get_item_code_and_name',
	autoFocus:true,
	close:function() {
		let txt = $(this).val();
		let arr = txt.split(' | ');

		if(arr.length == 3) {
			let id = arr[0];
			let code = arr[1];
			let name = arr[2];

			$(this).val(code + ' | ' + name);
			$('#temp-item-id').val(id);
		}
		else {
			$(this).val('');
			$('#temp-item-id').val('');
		}
	}
});


function addItemToList() {
	let txt = $('#free-item-box').val();
	let id = $('#temp-item-id').val();

	if(txt.length > 0 && id != "" && id != 0) {

		if($('#free-item-id-'+id).length == 0) {

			let arr = txt.split(' | ');

			if(arr.length == 2) {
				let ds = {"id" : id, "code" : arr[0], "name" : arr[1]};
				let source = $('#freeItemTemplate').html();
				let output = $('#freeItemList');
				render_append(source, ds, output);

				$('#temp-item-id').val('');
				$('#free-item-box').val('').focus();
			}
		}
	}
}


$('#free-item-box').keyup(function(e) {
	if(e.keyCode === 13) {
		addItemToList();
	}
});


function removeFreeItem() {
	$('.del-chk').each(function() {
		if($(this).is(':checked')) {
			let id = $(this).val();
			$('#free-row-'+id).remove();
		}
	})
}


function toggleFreeItem() {
	if($('#free-item').is(':checked')) {
		$('.free').removeAttr('disabled');
		$('#free-item-box').focus();
	}
	else {
		$('#free-qty').val('');
		$('.free').attr('disabled', 'disabled');
	}
}


function toggleDiscType(option) {
	if(option == 'N') {
		$('.disc-input').attr('disabled', 'disabled');
		$('#disc1').val(0.00);
		$('#disc2').val(0.00);
		$('#disc3').val(0.00);
		$('#disc4').val(0.00);
		$('#disc5').val(0.00);
		$('.free').attr('disabled', 'disabled');
		$('#free-qty').val(0);
		$('.free-row').remove();
		$('#visible-free').addClass('hide');
		$('.price-input').removeAttr('disabled');
		$('#net-price').focus();
		return;
	}

	if(option == 'P') {
		$('.price-input').attr('disabled', 'disabled');
		$('#net-price').val(0.00);
		$('.free').attr('disabled', 'disabled');
		$('#free-qty').val(0);
		$('.free-row').remove();
		$('#visible-free').addClass('hide');
		$('.disc-input').removeAttr('disabled');
		$('#disc1').focus();
		return;
	}

	if(option == 'F') {
		$('.disc-input').attr('disabled', 'disabled');
		$('#disc1').val(0.00);
		$('#disc2').val(0.00);
		$('#disc3').val(0.00);
		$('#disc4').val(0.00);
		$('#disc5').val(0.00);
		$('.price-input').attr('disabled', 'disabled');
		$('#net-price').val(0.00);
		$('.free').removeAttr('disabled');
		$('#free-qty').val(1);
		$('#visible-free').removeClass('hide');
		$('#free-item-box').focus();
		return;
	}
}

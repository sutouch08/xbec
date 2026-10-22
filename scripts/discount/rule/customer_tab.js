function saveCustomer() {
  rule_id = $('#rule_id').val();
  all_customer = $('#all_customer').val();
  customer_id = $('#customer_id').val();
  customer_group = $('#customer_group').val();
  customer_type = $('#customer_type').val();
  customer_region = $('#customer_region').val();
  customer_area = $('#customer_area').val();
  customer_grade = $('#customer_grade').val();

  countId = $('.customer-id').length;

  //--- ถ้าเลือกลูกค้าทั้งหมดจะไม่สนใจเงื่อนไขอื่นๆ
  if(all_customer == 'N') {
    //--- ถ้าเป็นการระบุชื่อลูกค้ารายคนแล้วยังไม่ได้ระบุ
    if(customer_id == 'Y' && countId == 0){
      swal('กรุณาระบุลูกค้าอย่างน้อย 1 ราย');
      return false;
    }

    if(customer_id == 'N'){
      count_group = parseInt($('.chk-group:checked').size());
      count_type  = parseInt($('.chk-type:checked').size());
      cound_region  = parseInt($('.chk-region:checked').size());
      count_area  = parseInt($('.chk-area:checked').size());
      cound_grade = parseInt($('.chk-grade:checked').size());
      sum_count = count_group + count_type + cound_region + count_area + cound_grade;


      //---- กรณีลือกลูกค้าแบบเป็นกลุ่มแล้วไม่ได้เลือก
      if(customer_group == 'Y' && count_group == 0 ){
        swal('กรุณาเลือกกลุ่มลูกค้าอย่างน้อย 1 รายการ');
        return false;
      }

      //---- กรณีลือกลูกค้าแบบเป็นชนิดแล้วไม่ได้เลือก
      if(customer_type == 'Y' && count_type == 0 ){
        swal('กรุณาเลือกชนิดลูกค้าอย่างน้อย 1 รายการ');
        return false;
      }

      //---- กรณีลือกลูกค้าแบบเป็นประเภทแล้วไม่ได้เลือก
      if(customer_region == 'Y' && cound_region == 0 ){
        swal('กรุณาเลือกประเภทลูกค้าอย่างน้อย 1 รายการ');
        return false;
      }

      //---- กรณีลือกลูกค้าแบบเป็นเขตแล้วไม่ได้เลือก
      if(customer_area == 'Y' && count_area == 0 ){
        swal('กรุณาเลือกเขตลูกค้าอย่างน้อย 1 รายการ');
        return false;
      }

      //---- กรณีลือกลูกค้าแบบเป็นเกรดแล้วไม่ได้เลือก
      if(customer_grade == 'Y' && cound_grade == 0 ){
        swal('กรุณาเลือกเกรดลูกค้าอย่างน้อย 1 รายการ');
        return false;
      }

      if(sum_count == 0){
        swal('กรุณาระบุอย่างน้อย 1 เงื่อนไข');
        return false;
      }

    } //-- end if customer_id == 'N'

  } //--- end if all_customer

  ds = [
    {'name':'rule_id', 'value':rule_id},
    {'name':'all_customer', 'value':all_customer},
    {'name':'customer_id', 'value':customer_id},
    {'name':'customer_group', 'value':customer_group},
    {'name':'customer_type', 'value':customer_type},
    {'name':'customer_region', 'value':customer_region},
    {'name':'customer_area', 'value':customer_area},
    {'name':'customer_grade', 'value':customer_grade}
  ];

  //--- เก็บข้อมูลชื่อลูกค้า
  if(customer_id == 'Y'){
    $('.customer-id').each(function(index, el) {
			let id = $(this).val();
			let name = "custId["+id+"]";
      ds.push({'name':name, 'value':id});
    });
  }

  //--- เก็บข้อมูลกลุ่มลูกค้า
  if(customer_id == 'N' && customer_group == 'Y'){
    i = 0;
    $('.chk-group').each(function(index, el) {
      if($(this).is(':checked')){
        name = 'customerGroup['+i+']';
        ds.push({'name':name, 'value':$(this).val()});
        i++;
      }
    });
  }

  //--- เก็บข้อมูลชนิดลูกค้า
  if(customer_id == 'N' && customer_type == 'Y'){
    i = 0;
    $('.chk-type').each(function(index, el) {
      if($(this).is(':checked')){
        name = 'customerType['+i+']';
        ds.push({'name':name, 'value':$(this).val()});
        i++;
      }
    });
  }

  //--- เก็บข้อมูเลือกประเภทลูกค้า
  if(customer_id == 'N' && customer_region == 'Y'){
    i = 0;
    $('.chk-region').each(function(index, el){
      if($(this).is(':checked')){
        name = 'customerRegion['+i+']';
        ds.push({'name':name, 'value':$(this).val()});
        i++;
      }
    });
  }

  //--- เก็บข้อมูลเขตลูกค้า
  if(customer_id == 'N' && customer_area == 'Y'){
    i = 0;
    $('.chk-area').each(function(index, el){
      if($(this).is(':checked')){
        name = 'customerArea['+i+']';
        ds.push({'name':name, 'value':$(this).val()});
        i++;
      }
    });
  }


  //--- เก็บข้อมูลเกรดลูกค้า
  if(customer_id == 'N' && customer_grade == 'Y'){
    i = 0;
    $('.chk-grade').each(function(index, el){
      if($(this).is(':checked')){
        name = 'customerGrade['+i+']';
        ds.push({'name':name, 'value':$(this).val()});
        i++;
      }
    });
  }

  load_in();
  $.ajax({
    url:BASE_URL + 'discount/discount_rule/set_customer_rule',
    type:'POST',
    cache:'false',
    data:ds,
    success:function(rs){
      load_out();
      if(rs == 'success'){
        swal({
          title:'Saved',
          type:'success',
          timer:1000
        });

				setTimeout(function() {
					window.location.reload();
				}, 1200);
				
      }else{
        swal('Error!', rs, 'error');
      }
    }
  });
} //--- end function



function checkCustomerAll(el) {
	if(el.is(':checked')) {
		$('.customer-chk').prop('checked', true);
	}
	else {
		$('.customer-chk').prop('checked', false);
	}
}


function showCustomerGroup(){
  $('#cust-group-modal').modal('show');
}


function showCustomerGrade(){
  $('#cust-grade-modal').modal('show');
}

function showCustomerType(){
  $('#cust-type-modal').modal('show');
}

function showCustomerRegion(){
  $('#cust-region-modal').modal('show');
}

function showCustomerArea(){
  $('#cust-area-modal').modal('show');
}


$('#txt-cust-id-box').keyup(function(e){
  if(e.keyCode == 13){
    if($(this).val() != ''){
      addCustId();
    }
  }
});


$('.chk-group').change(function(e){
  count = 0;
  $('.chk-group').each(function(index, el) {
    if($(this).is(':checked')){
      count++;
    }
  });
  $('#badge-group').text(count);
});


$('.chk-type').change(function(e){
  count = 0;
  $('.chk-type').each(function(index, el) {
    if($(this).is(':checked')){
      count++;
    }
  });
  $('#badge-type').text(count);
});


$('.chk-region').change(function(e){
  count = 0;
  $('.chk-region').each(function(index, el) {
    if($(this).is(':checked')){
      count++;
    }
  });
  $('#badge-region').text(count);
});


$('.chk-area').change(function(e){
  count = 0;
  $('.chk-area').each(function(index, el) {
    if($(this).is(':checked')){
      count++;
    }
  });
  $('#badge-area').text(count);
});


$('.chk-grade').change(function(e){
  count = 0;
  $('.chk-grade').each(function(index, el) {
    if($(this).is(':checked')){
      count++;
    }
  });
  $('#badge-grade').text(count);
});


$('#txt-cust-id-box').autocomplete({
  source: BASE_URL + 'auto_complete/get_customer_list',
  autoFocus:true,
  close:function(){
    arr = $(this).val().split(' | ');
    if(arr.length == 3){
      id = arr[0];
      code = arr[1];
			name = arr[2];
      $('#id_customer').val(id);
      $(this).val(code + ' | ' + name);
    }else{
      $(this).val('');
      $('#id_customer').val('');
    }
  }
});



function addCustId(){
  id = $('#id_customer').val();
  cust = $('#txt-cust-id-box').val();

  if(cust.length > 0 && $('#customer-id-'+id).length == 0) {
		let arr = cust.split(' | ');
		if(arr.length == 2) {
			let ds = {"id" : id, "code" : arr[0], "name" : arr[1]};
			let source = $('#customerRowTemplate').html();
			let output = $('#customerList');

			render_append(source, ds, output);
		}
	}

	$('#txt-cust-id-box').val('');
	$('#id_customer').val('');
	$('#txt-cust-id-box').focus();
}



function removeCustomer(){
  $('.customer-chk').each(function() {
		if($(this).is(':checked')) {
			let id = $(this).val();
			$('#customer-row-'+id).remove();
		}
	});
}


//--- เลือกลูกค้าทั้งหมด
function toggleAllCustomer(option){
  $('#all_customer').val(option);
  if(option == 'Y'){
    $('#btn-cust-all-yes').addClass('btn-primary');
    $('#btn-cust-all-no').removeClass('btn-primary');
    disActiveCustomerControl();
  }else if(option == 'N'){
    $('#btn-cust-all-no').addClass('btn-primary');
    $('#btn-cust-all-yes').removeClass('btn-primary');
    $('.not-all').removeAttr('disabled');
    activeCustomerControl();
  }
}



function disActiveCustomerControl(){
  toggleCustomerGroup();
  toggleCustomerType();
  toggleCustomerRegion();
  toggleCustomerArea();
  toggleCustomerGrade();
  $('.not-all').attr('disabled', 'disabled');
}




function activeCustomerControl(){
  customer_id = $('#customer_id').val();
  if(customer_id == 'Y'){
    toggleCustomerGroup();
    toggleCustomerType();
    toggleCustomerRegion();
    toggleCustomerArea();
    toggleCustomerGrade();
    return;
  }

  toggleCustomerGroup($('#customer_group').val());
  toggleCustomerType($('#customer_type').val());
  toggleCustomerRegion($('#customer_region').val());
  toggleCustomerArea($('#customer_area').val());
  toggleCustomerGrade($('#customer_grade').val());
}






function toggleCustomerId(option){
  if(option == '' || option == undefined){
    option = $('#customer_id').val();
  }

  $('#customer_id').val(option);
  if(option == 'Y'){
    $('#btn-cust-id-yes').addClass('btn-primary');
    $('#btn-cust-id-no').removeClass('btn-primary');
    $('#txt-cust-id-box').removeAttr('disabled');
    $('#btn-cust-id-add').removeAttr('disabled');

  }else if(option == 'N'){
    $('#btn-cust-id-no').addClass('btn-primary');
    $('#btn-cust-id-yes').removeClass('btn-primary');
    $('#txt-cust-id-box').attr('disabled', 'disabled');
    $('#btn-cust-id-add').attr('disabled', 'disabled');
  }

  activeCustomerControl();
}


function toggleCustomerGroup(option){
  if(option == '' || option == undefined){
    option = $('#customer_group').val();
  }

  $('#customer_group').val(option);
  all = $('#all_customer').val();
  sc = $('#customer_id').val();
  if(option == 'Y' && sc == 'N' && all == 'N'){
    $('#btn-cust-group-no').removeClass('btn-primary');
    $('#btn-cust-group-yes').addClass('btn-primary');
    $('#btn-cust-group-no').removeAttr('disabled');
    $('#btn-cust-group-yes').removeAttr('disabled');
    $('#btn-select-cust-group').removeAttr('disabled');

    return;
  }

  if(option == 'N' && sc == 'N' && all == 'N'){
    $('#btn-cust-group-yes').removeClass('btn-primary');
    $('#btn-cust-group-no').addClass('btn-primary');
    $('#btn-cust-group-no').removeAttr('disabled');
    $('#btn-cust-group-yes').removeAttr('disabled');
    $('#btn-select-cust-group').attr('disabled', 'disabled');

    return;
  }

  if(all == 'Y' || sc == 'Y'){
    $('#btn-cust-group-yes').attr('disabled', 'disabled');
    $('#btn-cust-group-no').attr('disabled', 'disabled');
    $('#btn-select-cust-group').attr('disabled', 'disabled');
    return;
  }
}



function toggleCustomerType(option){
  if(option == '' || option == undefined){
    option = $('#customer_type').val();
  }

  $('#customer_type').val(option);
  sc = $('#customer_id').val();
  all = $('#all_customer').val();
  if(option == 'Y' && all == 'N' && sc == 'N'){
    $('#btn-cust-type-no').removeClass('btn-primary');
    $('#btn-cust-type-yes').addClass('btn-primary');
    $('#btn-cust-type-no').removeAttr('disabled');
    $('#btn-cust-type-yes').removeAttr('disabled');
    $('#btn-select-cust-type').removeAttr('disabled');
    return;
  }

  if(option == 'N' && sc == 'N' && all == 'N'){
    $('#btn-cust-type-yes').removeClass('btn-primary');
    $('#btn-cust-type-no').addClass('btn-primary');
    $('#btn-cust-type-yes').removeAttr('disabled');
    $('#btn-cust-type-no').removeAttr('disabled');
    $('#btn-select-cust-type').attr('disabled', 'disabled');

    return;
  }

  if(all == 'Y' || sc == 'Y'){
    $('#btn-cust-type-yes').attr('disabled', 'disabled');
    $('#btn-cust-type-no').attr('disabled', 'disabled');
    $('#btn-select-cust-type').attr('disabled', 'disabled');
  }
}



function toggleCustomerRegion(option){
  if(option == '' || option == undefined){
    option = $('#customer_region').val();
  }


  $('#customer_region').val(option);
  sc = $('#customer_id').val();
  all = $('#all_customer').val();

  if(option == 'Y' && all == 'N' && sc == 'N'){
    $('#btn-cust-region-no').removeClass('btn-primary');
    $('#btn-cust-region-yes').addClass('btn-primary');
    $('#btn-cust-region-no').removeAttr('disabled');
    $('#btn-cust-region-yes').removeAttr('disabled');
    $('#btn-select-cust-region').removeAttr('disabled');
    return;
  }

  if(option == 'N' && sc == 'N' && all == 'N'){
    $('#btn-cust-region-yes').removeClass('btn-primary');
    $('#btn-cust-region-no').addClass('btn-primary');
    $('#btn-cust-region-no').removeAttr('disabled');
    $('#btn-cust-region-yes').removeAttr('disabled');
    $('#btn-select-cust-region').attr('disabled', 'disabled');
    return;
  }

  if(all == 'Y' || sc == 'Y'){
    $('#btn-cust-region-yes').attr('disabled', 'disabled');
    $('#btn-cust-region-no').attr('disabled', 'disabled');
    $('#btn-select-cust-region').attr('disabled', 'disabled');
  }
}



function toggleCustomerArea(option){
  if(option == '' || option == undefined){
    option = $('#customer_area').val();
  }

  $('#customer_area').val(option);
  sc = $('#customer_id').val();
  all = $('#all_customer').val();
  if(option == 'Y' && all == 'N' && sc == 'N'){
    $('#btn-cust-area-no').removeClass('btn-primary');
    $('#btn-cust-area-yes').addClass('btn-primary');
    $('#btn-cust-area-no').removeAttr('disabled');
    $('#btn-cust-area-yes').removeAttr('disabled');
    $('#btn-select-cust-area').removeAttr('disabled');
    return;
  }

  if(option == 'N' && sc == 'N' && all == 'N'){
    $('#btn-cust-area-yes').removeClass('btn-primary');
    $('#btn-cust-area-no').addClass('btn-primary');
    $('#btn-cust-area-yes').removeAttr('disabled');
    $('#btn-cust-area-no').removeAttr('disabled');
    $('#btn-select-cust-area').attr('disabled', 'disabled');
    return;
  }

  if(all == 'Y' || sc == 'Y'){
    $('#btn-cust-area-yes').attr('disabled', 'disabled');
    $('#btn-cust-area-no').attr('disabled', 'disabled');
    $('#btn-select-cust-area').attr('disabled', 'disabled');
  }
}



function toggleCustomerGrade(option){
  if(option == '' || option == undefined){
    option = $('#customer_grade').val();
  }

  $('#customer_grade').val(option);
  sc = $('#customer_id').val();
  all = $('#all_customer').val();
  if(option == 'Y' && all == 'N' && sc == 'N'){
    $('#btn-cust-grade-no').removeClass('btn-primary');
    $('#btn-cust-grade-yes').addClass('btn-primary');
    $('#btn-cust-grade-no').removeAttr('disabled');
    $('#btn-cust-grade-yes').removeAttr('disabled');
    $('#btn-select-cust-grade').removeAttr('disabled');
    return;
  }

  if(option == 'N' && sc == 'N' && all == 'N'){
    $('#btn-cust-grade-yes').removeClass('btn-primary');
    $('#btn-cust-grade-no').addClass('btn-primary');
    $('#btn-cust-grade-no').removeAttr('disabled');
    $('#btn-cust-grade-yes').removeAttr('disabled');
    $('#btn-select-cust-grade').attr('disabled', 'disabled');
    return;
  }

  if(all == 'Y' || sc == 'Y'){
    $('#btn-cust-grade-no').attr('disabled', 'disabled');
    $('#btn-cust-grade-yes').attr('disabled', 'disabled');
    $('#btn-select-cust-grade').attr('disabled', 'disabled');
  }
}


$(document).ready(function() {
  var all = $('#all_customer').val();
  var custId = $('#customer_id').val();
  toggleAllCustomer(all);
  toggleCustomerId(custId);
});

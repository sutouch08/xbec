function savePayment() {

  rule_id = $('#rule_id').val();
  all_payment = $('#all_payment').val();
  countPayment = parseInt($('.chk-payment:checked').size());

  if(all_payment == 'N' && countPayment == 0){
    swal('กรุณาระบุช่องทางการชำระเงินอย่างน้อย 1 รายการ');
    return false;
  }

  ds = [
    {'name':'rule_id', 'value':rule_id},
    {'name':'all_payment', 'value':all_payment}
  ];

  if(all_payment == 'N'){
    i = 0;
    $('.chk-payment').each(function(index, el) {
      if($(this).is(':checked')){
        name = 'payment['+i+']';
        ds.push({'name':name, 'value':$(this).val()});
        i++;
      }
    });
  }

  load_in();
  $.ajax({
    url: BASE_URL + 'discount/discount_rule/set_payment_rule',
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
				}, 1200)
				
      }else{
        swal('Error!', rs, 'error');
      }
    }
  });
}


function togglePayment(option){
  if(option == '' || option == undefined){
    option = $('#all_payment').val();
  }

  $('#all_payment').val(option);

  if(option == 'Y'){
    $('#btn-all-payment').addClass('btn-primary');
    $('#btn-select-payment').removeClass('btn-primary');
    $('#btn-show-payment').attr('disabled', 'disabled');
    return;
  }

  if(option == 'N'){
    $('#btn-all-payment').removeClass('btn-primary');
    $('#btn-select-payment').addClass('btn-primary');
    $('#btn-show-payment').removeAttr('disabled');
  }

}


$('.chk-payment').change(function(event) {
  count = 0;
  $('.chk-payment').each(function(index, el) {
    if($(this).is(':checked')){
      count++;
    }
  });
  $('#badge-payment').text(count);
});


function showSelectPayment(){
  $('#payment-modal').modal('show');
}

$(document).ready(function() {
  togglePayment();
});

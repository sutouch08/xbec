function saveChannels(){
  rule_id = $('#rule_id').val();
  all_channels = $('#all_channels').val();
  countChannels = parseInt($('.chk-channels:checked').size());

  if(all_channels == 'N' && countChannels == 0){
    swal('กรุณาระบุช่องทางการขายอย่างน้อย 1 รายการ');
    return false;
  }

  ds = [
    {'name':'rule_id', 'value':rule_id},
    {'name':'all_channels', 'value':all_channels}
  ];

  if(all_channels == 'N'){
    i = 0;
    $('.chk-channels').each(function(index, el) {
      if($(this).is(':checked')){
        name = 'channels['+i+']';
        ds.push({'name':name, 'value':$(this).val()});
        i++;
      }
    });
  }


  load_in();
  $.ajax({
    url: BASE_URL + 'discount/discount_rule/set_channels_rule',
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

				setTimeout(function(){
					window.location.reload();
				}, 1200);
				
      }else{
        swal('Error!', rs, 'error');
      }
    }
  });
}


function toggleChannels(option){
  if(option == '' || option == undefined){
    option = $('#all_channels').val();
  }

  $('#all_channels').val(option);

  if(option == 'Y'){
    $('#btn-all-channels').addClass('btn-primary');
    $('#btn-select-channels').removeClass('btn-primary');
    $('#btn-show-channels').attr('disabled', 'disabled');
    return;
  }

  if(option == 'N'){
    $('#btn-all-channels').removeClass('btn-primary');
    $('#btn-select-channels').addClass('btn-primary');
    $('#btn-show-channels').removeAttr('disabled');
  }
}


$('.chk-channels').change(function(event) {
  count = 0;
  $('.chk-channels').each(function(index, el) {
    if($(this).is(':checked')){
      count++;
    }
  });
  $('#badge-channels').text(count);
});




function showSelectChannels(){
  $('#channels-modal').modal('show');
}




$(document).ready(function() {
  toggleChannels();
});

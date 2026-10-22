function checkDocumentSetting(){
  var pre = {};
  var data = {};
  var prefix_error = 0;
  var error_message = 'รหัสเอกสารซ้ำ ';
  $('.prefix').each(function(index, el){
    name = $(this).attr('name');
    value = $(this).val();
    //--- ถ้าพบว่ามีรายการใดที่ว่าง
    if($(this).val() == ''){
      $(this).addClass('has-error');
      error_message = 'กรุณากำหนดรหัสเอกสารให้ครบทุกช่อง';
      prefix_error++;
    }

    if(value.length != 2){
      $(this).addClass('has-error');
      error_message ='กรุณากำหนดรหัสเอกสาร 2 ตัวอักษร';
      prefix_error++;
      return false;
    }

    if(pre[value] !== undefined){
      $(this).addClass('has-error');
      error_message = error_message + pre[value]+', ';
      prefix_error++;
    }else{
      $(this).removeClass('has-error');
      pre[value] = value;
      data[name] = $(this).val();
    }
  });

  if(prefix_error > 0){
    swal('Error!', error_message, 'error');
    return false;
  }


  var min = 3;
  var max = 7;
  var error = 0;
  $('.digit').each(function(index,el){
    name = $(this).attr('name');
    value = $(this).val();

    if(value < min || value > max || value == ''){
      $(this).addClass('has-error');
      error++;
    }else{
      $(this).removeClass('has-error');
    }
  });

  if(error > 0){
    swal('จำนวนหน่วยต้องอยู่ระหว่าง 3 - 7 หลัก');
    return false;
  }


  updateConfig('documentForm');

}



function checkPrefix(){
  var pre = {};
  var data = {};
  $('.prefix').each(function(index, el){
    name = $(this).attr('name');
    value = $(this).val();
    //--- ถ้าพบว่ามีรายการใดที่ว่าง
    if($(this).val() == ''){
      $(this).addClass('has-error');
      swal('กรุณากำหนดรหัสเอกสารให้ครบทุกช่อง');
      return false;
    }

    if(pre[value] !== undefined){
      $(this).addClass('has-error');
      swal('รหัสเอกสาร '+ pre[value] +' ซ้ำ');
      return false;
    }else{
      $(this).removeClass('has-error');
      pre[value] = value;
      data[name] = $(this).val();
    }
  });

  return data;
}



function checkDigit(){
  var min = 3;
  var max = 7;
  var error = 0;
  $('.digit').each(function(index,el){
    name = $(this).attr('name');
    value = $(this).val();

    if(value < min || value > max || value == ''){
      $(this).addClass('has-error');
      error++;
    }else{
      $(this).removeClass('has-error');
    }
  });

  if(error > 0){
    swal('จำนวนหน่วยต้องอยู่ระหว่าง 3 - 7 หลัก');
    return false;
  }

  return true;
}

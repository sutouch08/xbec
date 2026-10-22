
//--- properties for print
var prop 			= "width=800, height=900. left="+center+", scrollbars=yes";
var center    = ($(document).width() - 800)/2;



//--- พิมพ์ packing list แบบไม่มีบาร์โค้ด
function printOrder(){
  var order_code  = $("#order_code").val();
  var target  = BASE_URL + 'inventory/invoice/print_order/'+order_code;
  window.open(target, '_blank', prop);

}

//--- พิมพ์ packing list แบบมีบาร์โค้ด
function printOrderBarcode(){

  var order_code = $("#order_code").val();
  var target  = BASE_URL + 'inventory/invoice/print_order/'+order_code+'/barcode';
  window.open(target, '_blank', prop);

}


function printOrderSheet(){
  var order_code = $('#order_code').val();
  var target = BASE_URL + 'orders/orders/print_order_sheet/' + order_code;
  window.open(target, '_blank', prop);
}

function printOrderSheetBarcode(){
  var order_code = $('#order_code').val();
  var target = BASE_URL + 'orders/orders/print_order_sheet/' + order_code+'/barcode';
  window.open(target, '_blank', prop);
}

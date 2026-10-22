
//--- properties for print
var prop 			= "width=800, height=900. left="+center+", scrollbars=yes";
var center    = ($(document).width() - 800)/2;


//--- พิมพ์ packing list แบบไม่มีบาร์โค้ด
function printReturnLend(){
  var id = $('#id_return_lend').val();
  var target  = "controller/printController.php?printReturnLend&id_return_lend="+id;
  window.open(target, '_blank', prop);

}


//--- properties for print
var prop 			= "width=800, height=900. left="+center+", scrollbars=yes";
var center    = ($(document).width() - 800)/2;


//--- พิมพ์ packing list แบบไม่มีบาร์โค้ด
function printReturnReceived(reference){
  var target  = "controller/printController.php?printReturnReceived&reference="+reference;
  window.open(target, '_blank', prop);

}


//--- properties for print
var prop 			= "width=800, height=900. left="+center+", scrollbars=yes";
var center    = ($(document).width() - 800)/2;


//--- พิมพ์ ใบปะหน้ากล่อง กระทบยอดฝากขาย
//--- พิมพ์ packing list แบบไม่มีบาร์โค้ด
function printConsign(id){
  var target  = "controller/printController.php?printConsign&id_consign="+id;
  window.open(target, '_blank', prop);

}

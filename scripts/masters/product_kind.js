function addNew(){
  window.location.href = BASE_URL + 'masters/product_kind/add_new';
}



function goBack(){
  window.location.href = BASE_URL + 'masters/product_kind';
}


function getEdit(code){
  window.location.href = BASE_URL + 'masters/product_kind/edit/'+code;
}


function clearFilter(){
  var url = BASE_URL + 'masters/product_kind/clear_filter';
  var page = BASE_URL + 'masters/product_kind';
  $.get(url, function(rs){
    window.location.href = page;
  });
}


function getDelete(code, name){
  swal({
    title:'Are sure ?',
    text:'ต้องการลบ ' + name + ' หรือไม่ ?',
    type:'warning',
    showCancelButton: true,
		confirmButtonColor: '#FA5858',
		confirmButtonText: 'ใช่, ฉันต้องการลบ',
		cancelButtonText: 'ยกเลิก',
		closeOnConfirm: false
  },function(){
    window.location.href = BASE_URL + 'masters/product_kind/delete/' + code;
  })
}



function getSearch(){
  $('#searchForm').submit();
}

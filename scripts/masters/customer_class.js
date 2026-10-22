function addNew(){
  window.location.href = BASE_URL + 'masters/customer_class/add_new';
}



function goBack(){
  window.location.href = BASE_URL + 'masters/customer_class';
}


function getEdit(code){
  window.location.href = BASE_URL + 'masters/customer_class/edit/'+code;
}


function clearFilter(){
  var url = BASE_URL + 'masters/customer_class/clear_filter';
  var page = BASE_URL + 'masters/customer_class';
  $.get(url, function(rs){
    window.location.href = page;
  });
}



function save() {
	swal({
		title:'Success',
		type:'success',
		timer:1000
	});

	setTimeout(function() {
		window.location.reload();
	}, 1200)
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
    swal({
			title:'Deleted',
			type:'success',
			timer:1000
		})
  })
}



function getSearch(){
  $('#searchForm').submit();
}

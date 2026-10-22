var HOME = BASE_URL + 'masters/customer_address/';

function goBack() {
	window.location.href = HOME;
}

function removeAddress(id, name) {
	swal({
    title:'Are sure ?',
    text:'ต้องการลบ ' + name + ' หรือไม่ ?',
    type:'warning',
    showCancelButton: true,
		confirmButtonColor: '#FA5858',
		confirmButtonText: 'ใช่, ฉันต้องการลบ',
		cancelButtonText: 'ยกเลิก',
		closeOnConfirm: true
  },function(){
		load_in();

		$.ajax({
			url:HOME + 'delete/'+id,
			type:'POST',
			cache:false,
			success:function(rs) {
				load_out();

				rs = $.trim(rs);

				if(rs === 'success') {
					setTimeout(function() {
						swal({
							title:'Deleted',
							type:'success',
							timer:1000
						});

						$('#row-'+id).remove();
					}, 200);
				}
			}
		})

  })
}

function getDelete(code, name){

}

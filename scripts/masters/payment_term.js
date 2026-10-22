var HOME = BASE_URL + 'masters/payment_term/';


function syncData() {
	load_in();

	$.ajax({
		url:HOME + 'sync_data',
		type:'GET',
		cache:false,
		success:function(rs) {
			load_out();

			if(rs == 'success') {
				swal({
					title:'Success',
					type:'success',
					timer:1000
				});

				setTimeout(function() {
					window.location.reload();
				}, 1200);
			}
			else {
				swal({
					title:"Error!",
					type:"error",
					text: rs
				});
			}
		}
	});
}



function goBack() {
  window.location.href = HOME;
}


function getEdit(id){
  window.location.href = HOME + 'edit/'+id;
}


function update() {
	let id = $('#id').val();
	let is_default = $('#default').is(':checked') ? 1 : 0;

	$.ajax({
		url:HOME + 'update',
		type:'POST',
		cache:false,
		data:{
			'id' : id,
			'default' : is_default
		},
		success:function(rs) {
			var rs = $.trim(rs);
			if(rs === 'success') {
				swal({
					title:'Success',
					type:'success',
					timer:1000
				});
			}
			else {
				swal({
					title:'Error!',
					text:rs,
					type:'error'
				});
			}
		}
	})

}

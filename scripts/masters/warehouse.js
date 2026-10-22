function goBack(){
  window.location.href = HOME;
}

function syncData(){
	load_in();

	$.ajax({
		url:HOME + 'sync_data',
		type:'GET',
		cache:false,
		success:function(rs) {
			load_out();

			if(rs === 'success') {
				swal({
					title:'Success',
					type:'success',
					timer:1000
				});

				setTimeout(function(){
					goBack()
				}, 2000);
			}
			else {
				swal({
					title:'Error!',
					text:rs,
					type:'error'
				})
			}
		}
	})
}


function toggleList(el) {
	const list = el.is(':checked') ? 1 : 0;
	let id = el.data('id');
	$.ajax({
		url:HOME + 'set_list',
		type:'POST',
		cache:false,
		data:{
			'id' : id,
			'list' : list
		}
	});
}


function toggleCustomerList(el) {
	const list = el.is(':checked') ? 1 : 0;
	let id = el.data('id');
	$.ajax({
		url:HOME + 'set_customer_list',
		type:'POST',
		cache:false,
		data:{
			'id' : id,
			'list' : list
		}
	});
}

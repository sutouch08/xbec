var HOME = BASE_URL + 'masters/customer_group/';

function goBack() {
	window.location.href = HOME;
}



function syncData() {
	load_in();

	$.ajax({
		url:HOME + 'sync_data',
		type:'POST',
		cache:false,
		success:function(rs) {
			load_out();
			if(rs === 'success') {
				swal({
					title:'Success',
					type:'success',
					timer:1000
				});

				setTimeout(function() {
					goBack();
				}, 1500);
			}
		}
	});
}

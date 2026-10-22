const edit = (id, pageNo = 0) => {
	window.location.href = `${HOME}edit/${id}/${pageNo}`;
}

function update() {
	$('#name').clearError();
	let id = $('#id').val();
	let name = $('#name').val();

	if(name.length == 0) {
		$('#name').hasError('Required');
		return false;
	}
	
	load_in();

	$.ajax({
		url:`${HOME}update`,
		type:'POST',
		cache:false,
		data: {
			'id' : id,
			'name' : name
		},
		success:function(rs) {
			load_out();
			if(rs.trim() === 'success') {
				swal({
					title:'Success',
					type:'success',
					timer:1000
				});
			}
			else {
				showError(rs);
			}
		},
		error:function(rs) {
			clearError(rs);
		}
	});
}

function syncData() {
	load_in();

	$.ajax({
		url:`${HOME}sync_data`,
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

				setTimeout(function() {
					goBack();
				}, 1200);
			}
			else {
				showError(rs);
			}
		},
		error:function(rs) {
			load_out();
			showError(rs);
		}
	});
}

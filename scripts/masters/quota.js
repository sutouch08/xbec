function goBack() {
	window.location.href = HOME;
}


function addNew() {
	window.location.href = HOME + 'add_new';
}


$('#code').keyup(function(e) {
	if(e.keyCode === 13) {
		saveAdd();
	}
})

function saveAdd() {
	let code = $('#code').val();
	let listed = $('#listed').is(':checked') ? 1 : 0;

	if(code.length == 0) {
		$('#code').addClass('has-error');
		return false;
	}
	else {
		$('#code').removeClass('has-error');
	}

	$.ajax({
		url:HOME + 'add',
		type:'POST',
		cache:false,
		data:{
			'code' : code,
			'listed' : listed
		},
		success:function(rs) {
			if(rs == 'success') {
				swal({
					title:'Success',
					type:'success',
					timer:1000
				});

				setTimeout(function() {
					addNew();
				}, 1200);
			}
			else {
				swal({
					title:'Error!',
					text:rs,
					type:'error'
				});
			}
		}
	});
}


function toggleList(el) {
	listed = el.is(':checked') ? 1 : 0;
	id = el.data('id');

	$.ajax({
		url:HOME + 'set_list',
		type:'POST',
		cache:false,
		data:{
			'id' : id,
			'listed' : listed
		}
	});
}

const addNew = () => {
	window.location.href = `${HOME}add_new`;
};

const edit = (id, pageNo = 0) => {
	window.location.href = `${HOME}edit/${id}/${pageNo}`;
};

const viewDetail = (id, pageNo = 0) => {
	window.location.href = `${HOME}view_detail/${id}/${pageNo}`;
}

function update_sap() {
	var id = $('#id').val();
	setTimeout(function() {
		load_in();

		$.ajax({
			url:HOME + 'send_to_sap/'+id,
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
	}, 200);
}

function create_sap() {
	var id = $('#id').val();
	setTimeout(function() {
		load_in();

		$.ajax({
			url:HOME + 'create_sap/'+id,
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
	}, 200);
}

function save() {
	$('#code').clearError();
	$('#name').clearError();
	
	let code = $('#code').val();
	let name = $('#name').val();
	let parent = $('input[name=tabs]:checked').val();

	parent = parent === undefined ? 0 : parent;

	if(code.length === 0) {
		$('#code').hasError("Required");
		return false;
	}
	
	if(name.length === 0) {
		$('#name').hasError("Required");
		return false;
	}
	
	load_in();

	$.ajax({
		url:`${HOME}add`,
		type:'POST',
		cache:false,
		data:{
			'code' : code,
			'name' : name,
			'parent_id' : parent
		},
		success:function(rs) {
			load_out();
			
			if(rs.trim() === 'success') {
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
				showError(rs);
			}
		},
		error:function(rs) {
			showError(rs);
		}
	});
}

function update() {
	$('#name').clearError();
	let id = $('#id').val();
	let name = $('#name').val();
	let parent = $('input[name=tabs]:checked').val();

	parent = parent === undefined ? 0 : parent;

	if(name.length === 0) {
		$('#name').hasError("Required");
		return false;
	}	

	load_in();

	$.ajax({
		url:`${HOME}update`,
		type:'POST',
		cache:false,
		data:{
			'id' : id,
			'name' : name,
			'parent_id' : parent
		},
		success:function(rs) {
			load_out();
			
			if(rs.trim() === 'success') {
				swal({
					title:'Success',
					type:'success',
					timer:1000
				});

				setTimeout(function() {
					window.location.reload();
				}, 1200)
			}
			else {
				showError(rs);
			}
		}
	});
}

function setActive(el) {
	let id = el.data('id');
	let active = el.is(':checked') ? 1 : 0;

	$.ajax({
		url:`${HOME}set_active/${id}/${active}`,
		type:'GET',
		cache:false,
		success:function(rs) {
			console.log(rs);
		},
		error:function(rs) {
			console.error(rs);
		}
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
    swal({
			title:'Deleted',
			type:'success',
			timer:1000
		})
  })
}

function toggleTree(id) {
	let ul = $('#catchild-'+id);
	if(ul.hasClass('hide')) {
		ul.removeClass('hide');
		$('#catbox-'+id).removeClass('fa-plus-square-o');
		$('#catbox-'+id).addClass('fa-minus-square-o');
	}
	else {
		ul.addClass('hide');
		$('#catbox-'+id).removeClass('fa-minus-square-o');
		$('#catbox-'+id).addClass('fa-plus-square-o');
	}
}

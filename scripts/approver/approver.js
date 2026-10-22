const addNew = () => {
	window.location.href = `${HOME}add_new`;
}

const edit = (id) => {
	window.location.href = `${HOME}edit/${id}`;
}

const viewDetail = (id) => {
	window.location.href = `${HOME}view_detail/${id}`;
}

function toggleActive(id, el) {
	const status = $(el).is(':checked') ? 1 : 0;

	$.ajax({
		url: `${HOME}set_active`,
		type:'POST',
		data:{
			'id' : id,
			'active' : status
		},
		success:function(rs) {			
			if(rs.trim() !== 'success') {
				showError(rs);
				$(el).prop('checked', !status);
			}			
		},
		error:function(rs) {
			showError(rs);
		}
	});
}

function toggleOrderApproval(id, el) {
	const status = $(el).is(':checked') ? 1 : 0;

	$.ajax({
		url: `${HOME}set_order_approval`,
		type:'POST',
		data:{
			'id' : id,
			'ap_order' : status
		},
		success:function(rs) {			
			if(rs.trim() !== 'success') {
				showError(rs);
				$(el).prop('checked', !status);
			}			
		},
		error:function(rs) {
			showError(rs);
		}
	});
}

function togglePromotionApproval(id, el) {
	const status = $(el).is(':checked') ? 1 : 0;

	$.ajax({
		url: `${HOME}set_promotion_approval`,
		type:'POST',
		data:{
			'id' : id,
			'ap_promotion' : status
		},
		success:function(rs) {			
			if(rs.trim() !== 'success') {
				showError(rs);
				$(el).prop('checked', !status);
			}			
		},
		error:function(rs) {
			showError(rs);
		}
	});
}

function toggleVisibleGP(id, el) {
	const status = $(el).is(':checked') ? 1 : 0;

	$.ajax({
		url: `${HOME}set_visible_gp`,
		type:'POST',
		data:{
			'id' : id,
			'visible_gp' : status
		},
		success:function(rs) {			
			if(rs.trim() !== 'success') {
				showError(rs);
				$(el).prop('checked', !status);
			}			
		},
		error:function(rs) {
			showError(rs);
		}
	});
}

function checkAllTeam() {
	if($('#check-all-team').is(':checked')) {
		$('.chk-team').prop('checked', true);
	}
	else {
		$('.chk-team').prop('checked', false);
	}
}

function checkAllBrand() {
	if($('#check-all-brand').is(':checked')) {
		$('.chk-brand').prop('checked', true);
	}
	else {
		$('.chk-brand').prop('checked', false);
	}
}

function add() {
	clearErrorByClass('r');
	let error = 0;
	let h = {
		'user_id' : $('#user').val(),
		'uname' : $('#user option:selected').data('uname'),
		'team' : [],
		'brand' : [],
		'status' : $('input[name="status"]:checked').val(),
		'ap_order' : $('input[name="ap_order"]:checked').val(),
		'ap_promotion' : $('input[name="ap_promotion"]:checked').val(),
		'visible_gp' : $('input[name="visible_gp"]:checked').val()
	};
	
	if(h.user_id == "") {
		$('#user').hasError();
		swal("Please select User");
		return false;
	}		

	$('.chk-team:checked').each(function() {
		h.team.push({'id' : $(this).val()});
	});

	if(h.team.length == 0) {
		swal("Please select Sales Team");
		return false;
	}

	$('.chk-brand:checked').each(function() {
		id = $(this).val();
		percent = parseDefaultFloat($(`#brand-disc-${id}`).val(), 0.00);

		if(percent <= 0.00) {
			$('#brand-disc-'+id).hasError();
			error++;
		}
		else {
			h.brand.push({"id" : id, "max_disc" : percent});
		}
	});

	if(h.brand.length == 0) {
		swal("Please select Brand");
		return false;
	}

	if(error > 0) {
		swal("Max Disc must be greater than 0");
		return false;
	}

	load_in();

	$.ajax({
		url: `${HOME}add`,
		type:'POST',
		cache:false,
		data:{
			'data' : JSON.stringify(h)			
		},
		success:function(rs) {
			load_out();			

			if(rs.trim() === 'success') {
				swal({
					title:'Success',
					text: 'Approver has been added <br/> Do you want to add new approver ?',
					type:'success',
					html:true,
					showCancelButton:true,
					confirmButtonColor:'#DD6B55',
					confirmButtonText:'Yes',
					cancelButtonText:'No'
				}, function(isConfirm) {
					if(isConfirm) {
						addNew();
					}
					else {
						goBack();
					}
				});				
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
	clearErrorByClass('r');
	let error = 0;
	let h = {
		'id' : $('#id').val(),
		'user_id': $('#user').val(),
		'uname': $('#user option:selected').data('uname'),
		'team': [],
		'brand': [],
		'status': $('input[name="status"]:checked').val(),
		'ap_order': $('input[name="ap_order"]:checked').val(),
		'ap_promotion': $('input[name="ap_promotion"]:checked').val(),
		'visible_gp': $('input[name="visible_gp"]:checked').val()
	};

	if (h.user_id == "") {
		$('#user').hasError();
		swal("Please select User");
		return false;
	}

	$('.chk-team:checked').each(function () {
		h.team.push({ 'id': $(this).val() });
	});

	if (h.team.length == 0) {
		swal("Please select Sales Team");
		return false;
	}

	$('.chk-brand:checked').each(function () {
		id = $(this).val();
		percent = parseDefaultFloat($(`#brand-disc-${id}`).val(), 0.00);

		if (percent <= 0.00) {
			$('#brand-disc-' + id).hasError();
			error++;
		}
		else {
			h.brand.push({ "id": id, "max_disc": percent });
		}
	});

	if (h.brand.length == 0) {
		swal("Please select Brand");
		return false;
	}

	if (error > 0) {
		swal("Max Disc must be greater than 0");
		return false;
	}

	load_in();

	$.ajax({
		url: `${HOME}update`,
		type: 'POST',
		cache: false,
		data: {
			'data': JSON.stringify(h)
		},
		success: function (rs) {
			load_out();

			if (rs.trim() === 'success') {
				swal({
					title: 'Success',
					text: 'Approver has been updated',
					type: 'success',
					timer: 1000
				});
			}
			else {
				showError(rs);
			}
		},
		error: function (rs) {
			showError(rs);
		}
	});
}


function confirmDelete(id, code) {
	swal({
		title:'คุณแน่ใจ ?',
		text:'ต้องการลบ '+code+' หรือไม่ ?',
		type:'warning',
		showCancelButton:true,
		confirmButtonColor:'#DD6B55',
		confirmButtonText:'Yes',
		cancelButtonText:'No',
		html:true,
		closeOnConfirm:true
	}, function() {
		setTimeout(() => {
			doDelete(id);
		}, 100);
	});
}

function doDelete(id) {
	load_in();
	$.ajax({
		url:`${HOME}delete`,
		type:'POST',
		cache:false,
		data:{
			'id' : id
		},
		success:function(rs) {
			load_out();			
			if(rs.trim() === 'success') {
				swal({
					title:'Deleted',
					type:'success',
					timer:1000
				});

				$(`#row-${id}`).remove();
				reIndex();
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

$('.disc').focusin(function() {
	$(this).select();
});

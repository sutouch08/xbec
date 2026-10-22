let click = 0;

const addNew = () => {
	window.location.href = `${HOME}add_new`;
}

const edit = (id) => {
	window.location.href = `${HOME}edit/${id}`;
}

$('#reserve-amount').focusin(function() {
	$(this).select();
});

$('#reserve-amount').focusout(function() {
	let reserv_amount = parseDefaultFloat(removeCommas($(this).val()), 0);
	$(this).val(addCommas(reserv_amount.toFixed(2)));
});

function add() {
	if(click == 0) {
		click = 1;

		clearErrorByClass('r');
		let code = $('#code').val().trim();
		let name = $('#name').val().trim();
		let reserve_amount = parseDefaultFloat(removeCommas($('#reserve-amount').val()), 0);
		let draft_age = parseDefaultInt($('#draft-age').val(), 0);
		let reserve_age = parseDefaultInt($('#reserve-age').val(), 0);

		if (code.length === 0) {
			$('#code').hasError('Required');
			click = 0;
			return false;
		}

		if (name.length === 0) {
			$('#name').hasError('Required');
			click = 0;
			return false;
		}

		load_in();

		$.ajax({
			url: `${HOME}add`,
			type: 'POST',
			cache: false,
			data: {
				'code': code,
				'name': name,
				'reserve_amount': reserve_amount,
				'draft_age': draft_age,
				'reserve_age': reserve_age
			},
			success: function (rs) {
				click = 0;
				load_out();
				if (rs === 'success') {
					swal({
						title: 'Success',
						type: 'success',
						timer: 1000
					});

					setTimeout(() => {
						addNew();
					}, 1200);
				}
				else {
					showError(rs);
					click = 0;
				}
			},
			error: function (rs) {
				showError(rs);
			}
		});
	}	
}


function update() {
	clearErrorByClass('r');
	let id = $('#id').val();
	let name = $('#name').val().trim();
	let reserve_amount = parseDefaultFloat(removeCommas($('#reserve-amount').val()), 0);
	let draft_age = parseDefaultInt($('#draft-age').val(), 0);
	let reserve_age = parseDefaultInt($('#reserve-age').val(), 0);

	if(name.length === 0) {
		$('#name').hasError('Required');
		click = 0;
		return false;
	}

	load_in();

	$.ajax({
		url: `${HOME}update`,
		type:'POST',
		cache:false,
		data:{
			'id' : id,
			'name' : name,
			'reserve_amount' : reserve_amount,
			'draft_age' : draft_age,
			'reserve_age' : reserve_age
		},
		success:function(rs) {
			click = 0;
			load_out();
			if(rs == 'success') {
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
			showError(rs);
			click = 0;
		}
	})
}


function getDelete(id, name){
  swal({
    title:'Are sure ?',
    text:'ต้องการลบ '+name+' หรือไม่ ?',
    type:'warning',
    showCancelButton: true,
		confirmButtonColor: '#FA5858',
		confirmButtonText: 'ใช่, ฉันต้องการลบ',
		cancelButtonText: 'ยกเลิก',
		closeOnConfirm: false
  },function(){
    $.ajax({
			url:HOME + 'delete',
			type:'POST',
			cache:false,
			data:{
				'id' : id
			},
			success:function(rs) {
				if(rs === 'success') {
					swal({
						title:'Deleted',
						type:'success',
						timer:1000
					});

					setTimeout(() => {
						goBack();
					}, 1200);
				}
				else {
					swal({
						title:"Error!",
						type:"error",
						text:rs
					});
				}
			},
			error:function(rs) {
				showError(rs);
			}
		})
  })
}


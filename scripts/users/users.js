var validUname = true;
var validDname = true;
var validPm = true;
var validCust = true;
var validPwd = true;



function addNew() {
  window.location.href = HOME +'add_new';
}



function goBack() {
  window.location.href = HOME;
}

function getEdit(id) {
  window.location.href = HOME + 'edit/'+id;
}


function viewDetail(id) {
	window.location.href = HOME + 'view_detail/'+id;
}


function getReset(id) {
  window.location.href = HOME + 'reset_password/'+id;
}


function saveAdd() {
	validUserName();
	validDisplayName();
	validProfile();
	validCustomer();
	validPWD();

	if( !validUname || !validDname || !validCust || !validPm || !validPwd ) {
		return false;
	}

	const uname = $('#uname').val();
	const dname = $('#dname').val();
	const sale_id = $('#sale_id').val();
	const emp_id = $('#emp_id').val();
	const team_id = $('#team_id').val();
	const quota_no = $('#quota_no').val();
	const is_customer = $('#is_customer').val();
	const customer_code = $('#customer_code').val();
	const channels = $('#channels').val();
	const pwd = $('#pwd').val();
	const profile = $('#profile').val();
	const active = $('#active').is(':checked') ? 1 : 0;
	const force_reset = $('#force_reset').is(':checked') ? 1 : 0;
  const warehouse = $('#warehouse').val();
  let whList = [];

  if(is_customer == 1) {
    $('.chk-wh').each(function() {
      if($(this).is(':checked')) {
        whList.push({"id" : $(this).data('id'), "code" : $(this).val()});
      }
    });
  }

  if(is_customer == 1 && quota_no == "") {
    set_error($('#quota_no'), $('#quota-no-error'), "Required");
    $('#quota_no').focus();
    return false;
  }
  else {
    clear_error($('#quota_no'), $('#quota-no-error'));
  }

	if(is_customer == 1 && channels == "") {
		set_error($('#channels'), $('#channels-error'), "Required");
    $('#channels').focus();
		return false;
	}
	else {
		clear_error($('#channels'), $('#channels-error'));
	}

  if(is_customer == 1 && warehouse == "") {
    set_error($('#warehouse'), $('#warehouse-error'), "Required");
    $('#warehouse').focus();
    return false;
  }
  else {
    clear_error($('#warehouse'), $('#warehouse-error'));
  }

  if(is_customer == 1 && whList.length == 0) {
    set_error($('#wh-table'), $('#warehouse-error'), "Required");
    $('#wh-table').focus();
    return false;
  }
  else {
      clear_error($('#wh-table'), $('#warehouse-error'));
  }

	load_in();

	$.ajax({
		url:HOME + 'add',
		type:'POST',
		cache:false,
		data:{
			'uname' : uname,
			'dname' : dname,
			'sale_id' : sale_id,
			'team_id' : team_id,
			'quota_no' : quota_no,
			'is_customer' : is_customer,
			'customer_code' : customer_code,
			'channels' : channels,
      'warehouse' : warehouse,
      'warehouse_list' : JSON.stringify(whList),
			'pwd' : pwd,
			'profile' : profile,
			'active' : active,
			'force_reset' : force_reset
		},
		success:function(rs) {
			load_out();

			rs = $.trim(rs);

			if(rs === 'success') {
				swal({
					title:'Success',
					type:'success',
					timer:1000
				});

				setTimeout(function() {
					addNew();
				}, 1500);
			}
			else {
				swal({
					title:'Error!',
					text: rs,
					type:'error'
				});
			}
		},
		error:function(xhr) {
			load_out();
			swal({
				title:"Error!",
				text: xhr.responseText,
				type:'error',
				html:true
			});
		}
	});
}



function update() {
	validDisplayName();
	validProfile();
	validCustomer();

	if( !validDname || !validCust || !validPm ) {
		return false;
	}

	const id = $('#user_id').val();
  const uname = $('#uname').val();
	const dname = $('#dname').val();
	const sale_id = $('#sale_id').val();
	const emp_id = $('#emp_id').val();
	const team_id = $('#team_id').val();
	const quota_no = $('#quota_no').val();
	const is_customer = $('#is_customer').val();
	const customer_code = $('#customer_code').val();
	const channels = $('#channels').val();
	const profile = $('#profile').val();
	const active = $('#active').is(':checked') ? 1 : 0;
  const warehouse = $('#warehouse').val();

  let whList = [];

  if(is_customer == 1) {
    $('.chk-wh').each(function() {
      if($(this).is(':checked')) {
        whList.push({"id" : $(this).data('id'), "code" : $(this).val()});
      }
    });
  }

  if(is_customer == 1 && quota_no == "") {
    set_error($('#quota_no'), $('#quota-no-error'), "Required");
    $('#quota_no').focus();
    return false;
  }
  else {
    clear_error($('#quota_no'), $('#quota-no-error'));
  }

	if(is_customer == 1 && channels == "") {
		set_error($('#channels'), $('#channels-error'), "Required");
    $('#channels').focus();
		return false;
	}
	else {
		clear_error($('#channels'), $('#channels-error'));
	}

  if(is_customer == 1 && warehouse == "") {
    set_error($('#warehouse'), $('#warehouse-error'), "Required");
    $('#warehouse').focus();
    return false;
  }
  else {
    clear_error($('#warehouse'), $('#warehouse-error'));
  }

  if(is_customer == 1 && whList.length == 0) {
    set_error($('#wh-table'), $('#warehouse-error'), "Required");
    $('#wh-table').focus();
    return false;
  }
  else {
      clear_error($('#wh-table'), $('#warehouse-error'));
  }

	load_in();

	$.ajax({
		url:HOME + 'update',
		type:'POST',
		cache:false,
		data:{
			'id' : id,
      'uname' : uname,
			'dname' : dname,
			'sale_id' : sale_id,
			'emp_id' : emp_id,
			'team_id' : team_id,
			'quota_no' : quota_no,
			'is_customer' : is_customer,
			'customer_code' : customer_code,
			'channels' : channels,
      'warehouse' : warehouse,
      'warehouse_list' : JSON.stringify(whList),
			'profile' : profile,
			'active' : active
		},
		success:function(rs) {
			load_out();

			rs = $.trim(rs);

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
					text: rs,
					type:'error'
				});
			}
		},
		error:function(xhr) {
			load_out();
			swal({
				title:"Error!",
				text: xhr.responseText,
				type:'error',
				html:true
			});
		}
	});
}



function changePassword()
{
	validPWD();

	if( ! validPwd) {
		return false;
	}

	const id = $('#user_id').val();
	const pwd = $('#pwd').val();
	const force = $('#force_reset').is(':checked') ? 1 : 0;

	$.ajax({
		url:HOME + 'change_pwd',
		type:'POST',
		cache:false,
		data:{
			'id' : id,
			'pwd' : pwd,
			'force_reset' : force
		},
		success:function(rs) {
			rs = $.trim(rs);

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
					text: rs,
					type:'error'
				});
			}
		}
	});
}

function getDelete(id, uname){
  swal({
    title:'Are sure ?',
    text:'ต้องการลบ '+ uname +' หรือไม่ ?',
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
			data: {
				'id' : id
			},
			success:function(rs) {
				if(rs === 'success') {
					swal({
						title:'Deleted',
						type:'success',
						timer:1000
					});

					setTimeout(function() {
						goBack();
					}, 1500);
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
  })
}



function validatePassword(input)
{
	if(USE_STRONG_PWD == 1) {
		var passw = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,20}$/;

		if(input.match(passw))
		{
			return true;
		}

		return false;
	}

	return true;
}



function validPWD() {
  var pwd = $('#pwd').val();
  var cmp = $('#cm-pwd').val();
  if(pwd.length > 0) {

		if(!validatePassword(pwd)) {
			$('#pwd-error').text('รหัสผ่านต้องมีความยาว 8 - 20 ตัวอักษร และต้องประกอบด้วย ตัวอักษรภาษาอังกฤษ พิมพ์เล็ก พิมพ์ใหญ่ และตัวเลขอย่างน้อย อย่างละตัว');
      $('#pwd').addClass('has-error');
			validPwd = false;
      return false;
		}
		else {
			$('#pwd-error').text('');
			$('#pwd').removeClass('has-error');
			validPwd = true;
		}

    if(pwd != cmp) {
      $('#cm-pwd-error').text('Password missmatch!');
      $('#cm-pwd').addClass('has-error');
      validPwd = false;
			return false;
    }
		else {
      $('#cm-pwd-error').text('');
      $('#cm-pwd').removeClass('has-error');
      validPwd = true;
    }
  }
	else {
    $('#pwd-error').text('Password is required!');
    $('#pwd').addClass('has-error');
    validPwd = false;
  }
}





function validUserName() {
  var uname = $('#uname').val();
  var id = $('#user_id').val();
  if(uname.length > 0) {
		$.ajax({
			url:HOME + 'valid_uname',
			type:'GET',
			cache:false,
			data:{
				'id' : id,
				'uname' : uname
			},
			success:function(rs) {
				rs = $.trim(rs);
        if(rs === 'exists'){
          $('#uname-error').text('User name already exists!');
          $('#uname').addClass('has-error');
          validUname = false;
        }
				else {
          $('#uname-error').text('');
          $('#uname').removeClass('has-error');
          validUname = true;
        }
			}
		})
  }
	else {
    $('#uname-error').text('User name is required!');
    $('#uname').addClass('has-error');
    validUname = false;
  }
}



function validDisplayName() {
  var dname = $('#dname').val();
  var id = $('#user_id').val();
  if(dname.length > 0){
    $.ajax({
			url:HOME + 'valid_dname',
			type:'GET',
			cache:false,
			data:{
				'id' : id,
				'dname' : dname
			},
			success:function(rs) {
				var rs = $.trim(rs);
				if(rs === 'exists'){
	        $('#dname-error').text('Display name already exists!');
	        $('#dname').addClass('has-error');
	        validDname = false;
	      }
				else {
	        $('#dname-error').text('');
	        $('#dname').removeClass('has-error');
	        validDname = true;
	      }
			}
		});
  }
	else {
    $('#dname-error').text('Display name is required!');
    $('#dname').addClass('has-error');
    validDname = false;
  }
}



function validCustomer() {
	const el = $('#customer');
	const label = $('#customer-error');
	const is_customer = $('#is_customer').val();
	const customer_code = $('#customer_code').val();

	if(is_customer == 1 && customer_code.length == 0) {
		set_error(el, label, "Customer is required!");
		validCust = false;
	}
	else {
		clear_error(el, label);
		validCust = true;
	}
}


function validProfile() {
	const el = $('#profile');
	const label = $('#profile-error');

	if(el.val() == "") {
		set_error(el, label, "Please select profile");
		validPm = false;
	}
	else {
		clear_error(el, label);
		validPm = true;
	}
}



$('#dname').focusout(function(){
  validDisplayName();
});


$('#uname').focusout(function(){
  validUserName();
});

$('#profile').focusout(function() {
	validProfile();
});

$('#pwd').focusout(function(){
  validPWD();
});


$('#cm-pwd').keyup(function(e){
  validPWD();
});

$('#cm-pwd').focusout(function(){
  validPWD();
});



function getSearch(){
  $('#searchForm').submit();
}


function toggleCustomer() {
	let is_customer = $('#is_customer').val();

	if(is_customer == 1) {
		$('#customer').removeAttr('disabled');
		$('#channels').removeAttr('disabled');
    $('#warehouse').removeAttr('disabled');
    $('#wh-table').removeClass('hide');
		$('#customer').focus();
	}
	else {
		$('#customer').val('');
		$('#customer_code').val('');
		$('#channels').val('').attr('disabled', 'disabled');
    $('#warehouse').attr('disabled', 'disabled');
    $('#wh-table').addClass('hide');
		$('#customer').attr('disabled', 'disabled');

	}
}


$('#customer').autocomplete({
	source: BASE_URL + "auto_complete/get_customer_code_and_name",
	autoFocus:true,
	close:function() {
		let rs = $(this).val();
		let ar = rs.split(' | ');

		if(ar.length === 2) {
			$(this).val(ar[0]);
			$('#customer_code').val(ar[0]);
			$('#customer_name').val(ar[1]);
		}
		else {
			$(this).val('');
			$('#customer_code').val('');
			$('#customer_name').val('');
		}
	}
})

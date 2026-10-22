
var validPwd = true;

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

function checkPassword() {
	const uname = $('#uname').val();
	const current = $('#cu-pwd');
	const newPass = $('#pwd');
	const conPass = $('#cm-pwd');
	const pasErr = $('#pwd-error');
	const cuErr = $('#cu-pwd-error');
	const cmErr = $('#cm-pwd-error');

	if(current.val().length === 0) {
		current.addClass('has-error');
		cuErr.text("กรุณาใส่รหัสผ่านปัจจุบัน");
		return false;
	}
	else {
		current.removeClass('has-error');
		cuErr.text('');
	}

	if(newPass.val().length === 0) {
		newPass.addClass('has-error');
		pasErr.text('กรุณากำหนดรหัสผ่าน');
		return false;
	}
	else {
		newPass.removeClass('has-error');
		pasErr.text('');
	}

	//--- check use same as current passsword ?
	if(newPass.val() === current.val()) {
		newPass.addClass('has-error');
		pasErr.text("รหัสใหม่ต้องไม่ซ้ำกับรหัสปัจจุบัน");
		return false;
	}
	else {
		newPass.removeClass('has-error');
		pasErr.text('');
	}

	//--- check complexity
	if(!validatePassword(newPass.val())) {
		newPass.addClass('has-error');
		pasErr.text('รหัสผ่านต้องมีความยาว 8 - 20 ตัวอักษร และต้องประกอบด้วย ตัวอักษรภาษาอังกฤษ พิมพ์เล็ก พิมพ์ใหญ่ และตัวเลขอย่างน้อย อย่างละตัว');
		return false;
	}
	else {
		newPass.removeClass('has-error');
		pasErr.text('');
	}


	if(newPass.val() !== conPass.val()) {
		conPass.addClass('has-error');
		cmErr.text('ยืนยันรหัสผ่านไม่ตรงกับรหัสผ่านใหม่');
		return false;
	}
	else {
		conPass.removeClass('has-error');
		cmErr.text('');
	}

	return true;
}


function changePassword() {

	if(checkPassword()) {
		const uname = $('#uname').val();
		const current = $('#cu-pwd');
		const newPass = $('#pwd');
		const cuErr = $('#cu-pwd-error');
		const pasErr = $('#pwd-error');

		$.ajax({
			url:BASE_URL + 'user_pwd/check_current_password',
			type:"POST",
			cache:false,
			data: {
				"uname" : uname,
				"pwd" : current.val()
			},
			success:function(rs) {
				if(rs === 'valid') {
					$.ajax({
						url:BASE_URL + 'user_pwd/change_password',
						type:'POST',
						cache:false,
						data:{
							'uname' : uname,
							'pwd' : current.val(),
							'new_pwd' : newPass.val()
						},
						success:function(rs) {
							var rs = $.trim(rs);
							if(rs === 'success') {
								swal({
									title:'Success',
									type:'success',
									timer:1000
								})
							}
							else {
								current.addClass('has-error');
								pasErr.text(rs);
								pasErr.removeClass('hide');
								return false;
							}
						}
					})
				}
				else if(rs === 'invalid') {
					current.addClass('has-error');
					cuErr.text('รหัสผ่านไม่ถูกต้อง');
					return false;
				}
				else {
					current.addClass('has-error');
					pasErr.text(rs);
					return false;
				}
			}
		})
	}
}


$('#cu-pwd').focusout(function() {
	checkPassword();
})


$('#pwd').focusout(function(){
  checkPassword();
})


$('#cm-pwd').keyup(function(e){
  checkPassword();
})

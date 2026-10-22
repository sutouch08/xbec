
function doLogin() {
	const uname = $('#uname').val();
	const pwd = $('#pwd').val();
	const ipwd = $('#ipwd').text();
	const remember = $('#remember').is(':checked') ? 1 : 0;

	if(uname.length == 0) {
		$('#uname').focus();
		return false;
	}

	if(pwd.length == 0) {
		$('#pwd').focus();
		return false;
	}

	if(pwd != ipwd) {
		return false;
	}

	$.ajax({
		url: `${BASE_URL}users/authentication/validate_credentials`,
		type:'POST',
		cache:false,
		data:{
			'uname' : uname,
			'pwd' : ipwd,
			'remember' : remember
		},
		success:function(rs) {			
			if(rs.trim() === 'success') {
				window.location.href = BASE_URL;
			}
			else {
				$('#error-label').text(rs);
			}
		}
	});
}

$('#pwd').keyup(function(e) {
	if(e.keyCode === 13) {
		doLogin();
	}
	else {
		$('#ipwd').text($(this).val());
	}
});


$('#uname').keyup(function(e) {
	if(e.keyCode === 13) {
		doLogin();
	}
});

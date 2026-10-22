
function updateConfig(formName)
{
	load_in();
	var formData = $("#"+formName).serialize();
	$.ajax({
		url: HOME + "update_config",
		type:"POST",
    cache:"false",
    data: formData,
		success: function(rs){
			load_out();
      rs = $.trim(rs);
      if(rs == 'success'){
        swal({
          title:'Updated',
          type:'success',
          timer:1000
        });
      }else{
        swal('Error!', rs, 'error');
      }
		}
	});
}

function toggleOption(el) {
	let name = el.data('name');
	let option = el.is(':checked') ? 1 : 0;
	$("input[name='" + name + "']").val(option);
	console.log(name + ' : ' + $("input[name='" + name + "']").val());
}



function openSystem()
{
	$("#closed").val(0);
	$("#btn-close").removeClass('btn-danger');
	$('#btn-freze').removeClass('btn-warning');
	$("#btn-open").addClass('btn-success');
}



function closeSystem()
{
	$("#closed").val(1);
	$("#btn-open").removeClass('btn-success');
	$('#btn-freze').removeClass('btn-warning');
	$("#btn-close").addClass('btn-danger');
}


function frezeSystem()
{
	$("#closed").val(2);
	$("#btn-open").removeClass('btn-success');
	$("#btn-close").removeClass('btn-danger');
	$('#btn-freze').addClass('btn-warning');
}


function toggleStrongPWD(option) {
	$('#use-strong-pwd').val(option);

	if(option == 1) {
		$('#btn-strong-on').addClass('btn-primary');
		$('#btn-strong-off').removeClass('btn-primary');
	}
	else {
		$('#btn-strong-on').removeClass('btn-primary');
		$('#btn-strong-off').addClass('btn-primary');
	}
}


function toggleCreditLimit(option) {
	$('#credit-limit').val(option);

	if(option == 1) {
		$('#btn-limit-on').addClass('btn-primary');
		$('#btn-limit-off').removeClass('btn-primary');
	}
	else {
		$('#btn-limit-on').removeClass('btn-primary');
		$('#btn-limit-off').addClass('btn-primary');
	}
}


function toggleStock(option) {
	$('#available-stock').val(option);

	if(option == 1) {
		$('#btn-stock-on').addClass('btn-primary');
		$('#btn-stock-off').removeClass('btn-primary');
	}
	else {
		$('#btn-stock-off').addClass('btn-primary');
		$('#btn-stock-on').removeClass('btn-primary');
	}
}


function changeURL(tab)
{
	var url = HOME + 'index/'+tab;
	var stObj = { stage: 'stage' };
	window.history.pushState(stObj, 'setting', url);
}

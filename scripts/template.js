
function goTo(url) {
	window.location.href = url;
}

//--- save side bar layout to cookie
function toggle_layout(){
	var sidebar_layout = getCookie('sidebar_layout');
	if(sidebar_layout == 'menu-min'){
		setCookie('sidebar_layout', '', 90);
	}else{
		setCookie('sidebar_layout', 'menu-min', 90);
	}
}

function load_in() {	
	$("#loader").css("display","block");
	$('#loader-backdrop').css('display', 'block');	
	$("#loader").animate({opacity:0.8},300);
}

function load_out() {
	$("#loader").animate({
		opacity:0
	},300,
	function() {
		$("#loader").css("display","none");
		$('#loader-backdrop').css('display', 'none');
	});
}

function set_error(el, label, message) {
	el.addClass('has-error');
	label.text(message);
}

function clear_error(el, label) {
	el.removeClass('has-error');
	label.text('');
}

function isDate(txtDate) {	
	let currVal = txtDate;
	if(currVal == '') {
		return false;
	}
	 
	let rxDatePattern = /^(\d{1,2})(\/|-)(\d{1,2})(\/|-)(\d{4})$/;
	let dtArray = currVal.match(rxDatePattern); // is format OK?
	if (dtArray == null){
	  return false;
	}

	let dtDay= dtArray[1];
	let dtMonth = dtArray[3];
	let dtYear = dtArray[5];

	if (dtMonth < 1 || dtMonth > 12){
	  return false;
	}
	else if (dtDay < 1 || dtDay> 31){
	  return false;
	}
	else if ((dtMonth==4 || dtMonth==6 || dtMonth==9 || dtMonth==11) && dtDay ==31){
	  return false;
	}
	else if (dtMonth == 2){
	  let isleap = (dtYear % 4 == 0 && (dtYear % 100 != 0 || dtYear % 400 == 0));
	  if (dtDay> 29 || (dtDay ==29 && !isleap)){
	    return false;
		}
	}
	return true;
}

function removeCommas(str) {
	str = str.toString();
	return str.replace(/,/g, '');
}

function addCommas(number) {
	number = number.toString();
	let parts = number.split(".");
	parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
	return parts.join(".");
}

function render(source, data, output){
	let template = Handlebars.compile(source);
	let html = template(data);
	output.html(html);
}

function render_prepend(source, data, output){
	let template = Handlebars.compile(source);
	let html = template(data);
	output.prepend(html);
}

function render_append(source, data, output){
	let template = Handlebars.compile(source);
	let html = template(data);
	output.append(html);
}

function render_after(source, data, output) {
	let template = Handlebars.compile(source);
	let html = template(data);
	output.insertAfter(html);
}

function set_rows()
{
	let rows = $('#set_rows').val();

	$.ajax({
		url: `${BASE_URL}main/set_rows`,
		type:'POST',
		cache:false,
		data:{
			'set_rows' : rows
		},
		success:function(){
			window.location.reload();
		}
	});
}

$('#set_rows').keyup(function(e){
	if(e.keyCode == 13 && $(this).val() > 0){
		set_rows();
	}
});

function reIndex(className = 'no') {
  $('.' + className).each(function(index) {
    let no = index + 1;
    $(this).text(addCommas(no));
  });
}

var downloadTimer;

function get_download(token) {
	load_in();
	downloadTimer = window.setInterval(function() {
		let cookie = getCookie("file_download_token");
		if(cookie == token) {
			finished_download();
		}
	}, 1000);
}

function finished_download(){
	window.clearInterval(downloadTimer);
	deleteCookie("file_down_load_token");
	load_out();
}

function isJson(str) {
	try {
		JSON.parse(str);
	}
	catch(e) {
		return false;
	}

	return true;
}

function printOut(url) {
	const width = 800;
	const height = 900;
	const left = ($(document).width() - width) / 2;
	window.open(url, "_blank", "width="+width+", height="+height+", left="+left+", scrollbars=yes");		
}

function setCookie(cname, cvalue, exdays) {
  let d = new Date();
  d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
  let expires = "expires="+d.toUTCString();
  document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function getCookie(cname) {
  let name = cname + "=";
  let ca = document.cookie.split(';');
  for(let i = 0; i < ca.length; i++) {
    let c = ca[i];
    while (c.charAt(0) == ' ') {
      c = c.substring(1);
    }
    if (c.indexOf(name) == 0) {
      return c.substring(name.length, c.length);
    }
  }

  return "";
}

function deleteCookie( name ) {
  document.cookie = name + '=; expires=Thu, 01 Jan 1970 00:00:01 GMT;';
}

function parseDefault(value, def) {
	return (isNaN(value)) ? def : value;
}

function parseDefaultInt(value, def){
	const parsedValue = parseInt(value);
	return (isNaN(parsedValue)) ? def : parsedValue;
}
	
function parseDefaultFloat(value, def){
	const parsedValue = parseFloat(value);
	return (isNaN(parsedValue)) ? def : parsedValue;
}

function parseDiscount(label, price = 0) {
	let discLabel = {
		"discLabel1" : 0,
		"discUnit1" : '',
		"discLabel2" : 0,
		"discUnit2" : '',
		"discLabel3" : 0,
		"discUnit3" : '',
		"discLabel4" : 0,
		"discUnit4" : '',
		"discLabel5" : 0,
		"discUnit5" : '',
		"discountAmount" : 0,
		"sellPrice" : price
	};
	
	if(label != '' && label != 0) {
		let arr = label.split('+');
		discLabel['sellPrice'] = price;
		arr.forEach(function(item, index){
			let i = index + 1;
			if(i <= 5) {				
				let disc = item.split('%');
				let value = parseDefaultFloat(disc[0], 0);
				discLabel[`discLabel${i}`] = value;
				let amount = (value * 0.01) * price;
				discLabel[`discUnit${i}`] = '%';
				discLabel["discountAmount"] += amount;
				price -= amount;
				discLabel['sellPrice'] = price;
			}
		});		
	}

	return discLabel;
}

function goBack(url = null) {
	window.location.href = (url) ? url : HOME;	
}

function getSearch() {
  $('#searchForm').submit();
}

function clearFilter() {
	fetch(`${HOME}clear_filter`).then(() => {
		goBack();
	});
}

function clearFilterx() {
	const url = `${HOME}clear_filter`;
	$.get(url, function (rs) { goBack(); });
}

$('.search-box').keyup(function(e){
	if(e.keyCode === 13) {
		getSearch();
	}
});

$('.filter').change(function() {
	getSearch();
})

function sort(field) {
	let el = $("#sort_"+field);
	let sort_by = el.hasClass('sorting_desc') ? 'ASC' : 'DESC';
	let sort_class = el.hasClass('sorting_desc') ? 'sorting_asc' : 'sorting_desc';

	$('.sorting').removeClass('sorting_desc');
	$('.sorting').removeClass('sorting_asc');

	el.addClass(sort_class);
	$('#sort_by').val(sort_by);
	$('#order_by').val(field);

	getSearch();
}

function validCode(input, regex) {
  var regex = regex === undefined ? /[^a-z0-9-_.@]+/gi : regex;
  input.value = input.value.replace(regex, '');
}

function changeUserPwd() {
	window.location.href = `${BASE_URL}user_pwd`;
}

function uniqueId(length = 8) {
	if (length < 6 || length > 32) {
		throw new Error("UID length must be between 6 and 32 characters");
	}

	const chars = "abcdefghijklmnopqrstuvwxyz0123456789";
	let uid = "";

	for (let i = 0; i < length; i++) {
		uid += chars.charAt(Math.floor(Math.random() * chars.length));
	}

	return uid;
}

function roundNumber(num, digit) {
	digit = digit ? parseInt(digit) : 2;
	return Number(parseDefaultFloat(num, 0).toFixed(digit));
}

$.fn.hasError = function (msg) {
	let name = this.attr('id');
	$(`#${name}-error`).text(msg);	
	return this.addClass('has-error');
};

$.fn.clearError = function () {
	this.removeClass('has-error');
	let name = this.attr('id');
	return $(`#${name}-error`).text('');
};

function clearErrorByClass(className) {
	$(`.${className}`).each(function () {
		let name = $(this).attr('id');
		$(`#${name}-error`).text('');
		$(this).removeClass('has-error');
	});
}

function showError(response) {
	load_out();
	setTimeout(() => {
		swal({
			title: 'Error!',
			text: (typeof response === 'object') ? response.responseText : response,
			type: 'error',
			html: true
		})
	}, 100);
}

function showWarning(message) {
	load_out();
	setTimeout(() => {
		swal({
			title: 'Warning!',
			text: (typeof message === 'object') ? message.responseText : message,
			type: 'warning',
			html: true
		})
	}, 100);
}
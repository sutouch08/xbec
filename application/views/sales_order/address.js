//--- auto fill ship to
$('#sSubDistrict').autocomplete({
	source:BASE_URL + 'auto_complete/sub_district',
	autoFocus:true,
	open:function(event){
		var $ul = $(this).autocomplete('widget');
		$ul.css('width', 'auto');
	},
	close:function(){
		var rs = $.trim($(this).val());
		var adr = rs.split('>>');
		if(adr.length == 4){
			$('#sSubDistrict').val(adr[0]);
			$('#sDistrict').val(adr[1]);
			$('#sProvince').val(adr[2]);
			$('#sPostCode').val(adr[3]);
			$('#sPostCode').focus();
		}
	}
});


$('#sDistrict').autocomplete({
	source:BASE_URL + 'auto_complete/district',
	autoFocus:true,
	open:function(event){
		var $ul = $(this).autocomplete('widget');
		$ul.css('width', 'auto');
	},
	close:function(){
		var rs = $.trim($(this).val());
		var adr = rs.split('>>');
		if(adr.length == 3){
			$('#sDistrict').val(adr[0]);
			$('#sProvince').val(adr[1]);
			$('#sPostCode').val(adr[2]);
			$('#sPostCode').focus();
		}
	}
});


$('#sProvince').autocomplete({
	source:BASE_URL + 'auto_complete/district',
	autoFocus:true,
	open:function(event){
		var $ul = $(this).autocomplete('widget');
		$ul.css('width', 'auto');
	},
	close:function(){
		var rs = $.trim($(this).val());
		var adr = rs.split('>>');
		if(adr.length == 2){
			$('#sProvince').val(adr[0]);
			$('#sPostCode').val(adr[1]);
			$('#sPostCode').focus();
		}
	}
})

$('#sSubDistrict').keyup(function(e){
	if(e.keyCode == 13){
		$('#sDistrict').focus();
	}
})


$('#sDistrict').keyup(function(e){
	if(e.keyCode == 13){
		$('#province').focus();
	}
})

$('#sProvince').keyup(function(e){
	if(e.keyCode == 13){
		$('#sPostCode').focus();
	}
})






//--- auto fill bill to
$('#bSubDistrict').autocomplete({
	source:BASE_URL + 'auto_complete/sub_district',
	autoFocus:true,
	open:function(event){
		var $ul = $(this).autocomplete('widget');
		$ul.css('width', 'auto');
	},
	close:function(){
		var rs = $.trim($(this).val());
		var adr = rs.split('>>');
		if(adr.length == 4){
			$('#bSubDistrict').val(adr[0]);
			$('#bDistrict').val(adr[1]);
			$('#bProvince').val(adr[2]);
			$('#bPostCode').val(adr[3]);
			$('#bPostCode').focus();
		}
	}
});


$('#bDistrict').autocomplete({
	source:BASE_URL + 'auto_complete/district',
	autoFocus:true,
	open:function(event){
		var $ul = $(this).autocomplete('widget');
		$ul.css('width', 'auto');
	},
	close:function(){
		var rs = $.trim($(this).val());
		var adr = rs.split('>>');
		if(adr.length == 3){
			$('#bDistrict').val(adr[0]);
			$('#bProvince').val(adr[1]);
			$('#bPostCode').val(adr[2]);
			$('#bPostCode').focus();
		}
	}
});


$('#bProvince').autocomplete({
	source:BASE_URL + 'auto_complete/district',
	autoFocus:true,
	open:function(event){
		var $ul = $(this).autocomplete('widget');
		$ul.css('width', 'auto');
	},
	close:function(){
		var rs = $.trim($(this).val());
		var adr = rs.split('>>');
		if(adr.length == 2){
			$('#bProvince').val(adr[0]);
			$('#bPostCode').val(adr[1]);
			$('#bPostCode').focus();
		}
	}
})

$('#bSubDistrict').keyup(function(e){
	if(e.keyCode == 13){
		$('#bDistrict').focus();
	}
})


$('#bDistrict').keyup(function(e){
	if(e.keyCode == 13){
		$('#province').focus();
	}
})

$('#bProvince').keyup(function(e){
	if(e.keyCode == 13){
		$('#bPostCode').focus();
	}
})

function saveProduct() {
  rule_id = $('#rule_id').val();
  all_product = $('#all_product').val();
  product_model = $('#product_model').val();
  product_category = $('#product_category').val();
  product_type = $('#product_type').val();
  product_brand = $('#product_brand').val();

	countProduct = $('.item-id').length;
  countModel = $('.model-id').length;

  //--- ถ้าเลือกสินค้าทั้งหมดจะไม่สนใจเงื่อนไขอื่นๆ
  if(all_product == 'N'){

		if(product_id == 'Y' && countProduct == 0) {
			swal("Please select atleast 1 SKU");
			return false;
		}

    //--- ถ้าเป็นการระบุชื่อสินค้ารายคนแล้วยังไม่ได้ระบุ
    if(product_model == 'Y' && countModel == 0){
      swal('Please select atleast 1 Model');
      return false;
    }

    if(product_id == 'N' && product_model == 'N') {
      count_type  = parseInt($('.chk-pd-type:checked').size());
      count_cate  = parseInt($('.chk-pd-cat:checked').size());
      count_brand = parseInt($('.chk-pd-brand:checked').size());
      sum_count = count_type + count_cate + count_brand;

      //---- กรณีลือกสินค้าแบบเป็นหมวดหมู่แล้วไม่ได้เลือก
      if(product_category == 'Y' && count_cate == 0 ){
        swal('Please select product category');
        return false;
      }

      //---- กรณีลือกสินค้าแบบเป็นชนิดแล้วไม่ได้เลือก
      if(product_type == 'Y' && count_type == 0 ){
        swal('Please select product type');
        return false;
      }


      //---- กรณีลือกสินค้าแบบเป็นยี่ห้อแล้วไม่ได้เลือก
      if(product_brand == 'Y' && count_brand == 0 ){
        swal('Please select product brand');
        return false;
      }


      if(sum_count == 0){
        swal('Please select atleast 1 of attributes');
        return false;
      }

    } //-- end if product_model == 'N'

  } //--- end if all_product

  ds = [
    {'name':'rule_id', 'value':rule_id},
    {'name':'all_product', 'value':all_product},
		{'name':'product_id', 'value':product_id},
    {'name':'product_model', 'value':product_model},
    {'name':'product_category', 'value':product_category},
    {'name':'product_type', 'value':product_type},
    {'name':'product_brand', 'value':product_brand}
  ];

  //--- เก็บข้อมูลชื่อสินค้า
	if(product_id == 'Y') {
		let i = 0;
		$('.item-id').each(function(index, el) {
			let name = 'productId['+i+']';
			ds.push({'name':name, 'value':$(this).val()});
			i++;
		});
	}

  if(product_model == 'Y'){
		let i = 0;
    $('.model-id').each(function(index, el) {
			let name = 'modelId['+i+']';
			ds.push({'name':name, 'value':$(this).val()});
			i++;
    });
  }

  //--- เก็บข้อมูลหมวดหมู่สินค้า
  if(product_id == 'N' && product_model == 'N' && product_category == 'Y'){
    i = 0;
    $('.chk-pd-cat').each(function(index, el) {
      if($(this).is(':checked')){
        name = 'productCategory['+i+']';
        ds.push({'name':name, 'value':$(this).val()});
        i++;
      }
    });
  }


  //--- เก็บข้อมูลชนิดสินค้า
  if(product_id == 'N' && product_model == 'N' && product_type == 'Y'){
    i = 0;
    $('.chk-pd-type').each(function(index, el) {
      if($(this).is(':checked')){
        name = 'productType['+i+']';
        ds.push({'name':name, 'value':$(this).val()});
        i++;
      }
    });
  }


  //--- เก็บข้อมูลยี่ห้อสินค้า
  if(product_id == 'N' && product_model == 'N' && product_brand == 'Y'){
    i = 0;
    $('.chk-pd-brand').each(function(index, el){
      if($(this).is(':checked')){
        name = 'productBrand['+i+']';
        ds.push({'name':name, 'value':$(this).val()});
        i++;
      }
    });
  }


  load_in();
  $.ajax({
    url: BASE_URL + 'discount/discount_rule/set_product_rule',
    type:'POST',
    cache:'false',
    data:ds,
    success:function(rs){
      load_out();
      if(rs == 'success'){
        swal({
          title:'Saved',
          type:'success',
          timer:1000
        });

				setTimeout(function() {
					window.location.reload();
				}, 1200);

      }else{
        swal('Error!', rs, 'error');
      }
    }
  });


} //--- end function


function checkItemAll(el) {
	if(el.is(':checked')) {
		$('.item-chk').prop('checked', true);
	}
	else {
		$('.item-chk').prop('checked', false);
	}
}

function checkModelAll(el) {
	if(el.is(':checked')) {
		$('.model-chk').prop('checked', true);
	}
	else {
		$('.model-chk').prop('checked', false);
	}
}

function showProductType(){
  $('#pd-type-modal').modal('show');
}



function showProductCategory(){
  $('#pd-cat-modal').modal('show');
}




function showProductBrand(){
  $('#pd-brand-modal').modal('show');
}


$('#txt-product-id-box').keyup(function(e){
  if(e.keyCode == 13){
    if($(this).val() != ''){
      addProductId();
    }
  }
});


$('#txt-model-id-box').keyup(function(e){
  if(e.keyCode == 13){
    if($(this).val() != ''){
      addModelId();
    }
  }
});




$('.chk-pd-type').change(function(e){
  count = 0;
  $('.chk-pd-type').each(function(index, el) {
    if($(this).is(':checked')){
      count++;
    }
  });
  $('#badge-pd-type').text(count);
});




$('.chk-pd-cat').change(function(e){
  count = 0;
  $('.chk-pd-cat').each(function(index, el) {
    if($(this).is(':checked')){
      count++;
    }
  });
  $('#badge-pd-cat').text(count);
});



$('.chk-pd-brand').change(function(e){
  count = 0;
  $('.chk-pd-brand').each(function(index, el) {
    if($(this).is(':checked')){
      count++;
    }
  });
  $('#badge-pd-brand').text(count);
});


$('#txt-product-id-box').autocomplete({
  source: BASE_URL + 'auto_complete/get_item_code_and_name',
  autoFocus:true,
  close:function(){
    arr = $(this).val().split(' | ');
    if(arr.length == 3){
      id = arr[0];
			code = arr[1];
			name = arr[2];
      $('#id_product').val(id);
      $(this).val(code + ' | ' + name);
    }else{
      $(this).val('');
      $('#id_product').val('');
    }
  }
});



function addProductId(){
  let id = $('#id_product').val();
  let code = $('#id_product option:selected').text(); //$('#txt-product-id-box').val();
	console.log(code);
  if(code.length > 0 && id != "") {
		addProduct(id, code);
		$('#id_product').val(null).trigger('change');
    // $('#txt-model-id-box').val('');
    // $('#id_model').val('');
    // $('#txt-model-id-box').focus();
  }
}



function addProduct(id, code) {
	if(code.length && $('#item-id-'+id).length == 0) {
		let arr = code.split(' | ');
		if(arr.length == 2) {
			let ds = {"id" : id, "code" : arr[0], "name" : arr[1]};
			let source = $('#itemRowTemplate').html();
			let output = $('#itemList');

			render_append(source, ds, output);
		}
	}

	// $('#txt-product-id-box').val('');
	// $('#id_product').val('');
	// $('#txt-product-id-box').focus();
}


function removeItem(){
  $('.item-chk').each(function() {
		if($(this).is(':checked')) {
			let id = $(this).val();
			$('#item-row-'+id).remove();
		}
	})
}



$('#txt-model-id-box').autocomplete({
  source: BASE_URL + 'auto_complete/get_model_name',
  autoFocus:true,
  close:function(){
    arr = $(this).val().split(' | ');
    if(arr.length == 2){
      id = arr[0];
			name = arr[1];
      $('#id_model').val(id);
      $(this).val(name);
    }else{
      $(this).val('');
      $('#id_model').val('');
    }
  }
});


function addModelId(){
  id = $('#id_model').val();
  name = $('#id_model option:selected').text(); //$('#txt-model-id-box').val();
  if(name.length > 0 && id != ''){
		addModel(id, name);
		$('#id_model').val(null).trigger('change');
    // $('#txt-model-id-box').val('');
    // $('#id_model').val('');
    // $('#txt-model-id-box').focus();
  }
}


function addModel(id, code) {

	if(code.length && $('#model-id-'+id).length == 0) {
		let arr = code.split(' | ');
		if(arr.length == 2) {
			let ds = {"id" : id, "code" : arr[0], "name" : arr[1]};
			let source = $('#modelRowTemplate').html();
			let output = $('#modelList');

			render_append(source, ds, output);
		}
	}

	// $('#txt-model-id-box').val('');
	// $('#id_model').val('');
	// $('#txt-model-id-box').focus();
}


function removeModel(){
  $('.model-chk').each(function() {
		let id = $(this).val();

		if($(this).is(':checked')) {
			$('#model-row-'+id).remove();
		}
	});
}


//--- เลือกสินค้าทั้งหมด
function toggleAllProduct(option){
  $('#all_product').val(option);

  if(option == 'Y'){
    $('#btn-pd-all-yes').addClass('btn-primary');
    $('#btn-pd-all-no').removeClass('btn-primary');
    disActiveProductControl();
  }else if(option == 'N'){
    $('#btn-pd-all-no').addClass('btn-primary');
    $('#btn-pd-all-yes').removeClass('btn-primary');
    $('.not-pd-all').removeAttr('disabled');
		toggleProductId();
		toggleModelId();
    activeProductControl();
  }
}



function disActiveProductControl(){
  $('.not-pd-all').attr('disabled', 'disabled');
}




function activeProductControl(){
	product_id = $('#product_id').val();
  product_model = $('#product_model').val();

  if(product_model == 'Y' || product_id == 'Y'){
    toggleProductCategory();
    toggleProductType();
    toggleProductBrand();
    return;
  }

  toggleProductCategory($('#product_category').val());
  toggleProductType($('#product_type').val());
  toggleProductBrand($('#product_brand').val());
}



function toggleProductId(option){
  if(option == '' || option == undefined){
    option = $('#product_id').val();
  }

  $('#product_id').val(option);

	all = $('#all_product').val();

  if(option == 'Y' && all == 'N') {
    $('#btn-product-id-yes').addClass('btn-primary');
    $('#btn-product-id-no').removeClass('btn-primary');
		$('#id_product').removeAttr('disabled');
		$('#btn-product-id-add').removeAttr('disabled');
    // $('#txt-product-id-box').removeAttr('disabled');
		// $('#btn-product-import').removeAttr('disabled');
		// $('#btn-show-product-name').removeAttr('disabled');

  }
	else if(option == 'N') {
    $('#btn-product-id-no').addClass('btn-primary');
    $('#btn-product-id-yes').removeClass('btn-primary');
		$('#id_product').attr('disabled', 'disabled');
		$('#btn-product-id-add').attr('disabled', 'disabled');
    // $('#txt-product-id-box').attr('disabled', 'disabled');
		// $('#btn-product-import').attr('disabled', 'disabled');
		// $('#btn-show-product-name').attr('disabled', 'disabled');
  }

  activeProductControl();
}



function toggleModelId(option){
  if(option == '' || option == undefined){
    option = $('#product_model').val();
  }

  $('#product_model').val(option);
  if(option == 'Y'){
    $('#btn-model-id-yes').addClass('btn-primary');
    $('#btn-model-id-no').removeClass('btn-primary');
		$('#btn-model-id-add').removeAttr('disabled');
		$('#id_model').removeAttr('disabled');

    // $('#txt-model-id-box').removeAttr('disabled');
		// $('#btn-model-import').removeAttr('disabled');
		// $('#btn-show-model-name').removeAttr('disabled');

  }else if(option == 'N'){
    $('#btn-model-id-no').addClass('btn-primary');
    $('#btn-model-id-yes').removeClass('btn-primary');
		$('#btn-model-id-add').attr('disabled', 'disabled');
		$('#id_model').attr('disabled', 'disabled');
		//
    // $('#txt-model-id-box').attr('disabled', 'disabled');
		// $('#btn-model-import').attr('disabled', 'disabled');
		// $('#btn-show-model-name').attr('disabled', 'disabled');
  }

  activeProductControl();
}


function toggleProductCategory(option){
  if(option == '' || option == undefined){
    option = $('#product_category').val();
  }


  $('#product_category').val(option);
  sc = $('#product_model').val();
	pd = $('#product_id').val();
  all = $('#all_product').val();

  if(option == 'Y' && all == 'N' && sc == 'N' && pd == 'N' ){
    $('#btn-pd-cat-no').removeClass('btn-primary');
    $('#btn-pd-cat-yes').addClass('btn-primary');
    $('#btn-pd-cat-no').removeAttr('disabled');
    $('#btn-pd-cat-yes').removeAttr('disabled');
    $('#btn-select-pd-cat').removeAttr('disabled');
    return;
  }

  if(option == 'N' && sc == 'N' && pd == 'N' && all == 'N'){
    $('#btn-pd-cat-yes').removeClass('btn-primary');
    $('#btn-pd-cat-no').addClass('btn-primary');
    $('#btn-pd-cat-no').removeAttr('disabled');
    $('#btn-pd-cat-yes').removeAttr('disabled');
    $('#btn-select-pd-cat').attr('disabled', 'disabled');
    return;
  }

  if(all == 'Y' || sc == 'Y' || pd == 'Y'){
    $('#btn-pd-cat-yes').attr('disabled', 'disabled');
    $('#btn-pd-cat-no').attr('disabled', 'disabled');
    $('#btn-select-pd-cat').attr('disabled', 'disabled');
  }
}



function toggleProductType(option){
  if(option == '' || option == undefined){
    option = $('#product_type').val();
  }

  $('#product_type').val(option);
  sc = $('#product_model').val();
	pd = $('#product_id').val();
  all = $('#all_product').val();
  if(option == 'Y' && all == 'N' && sc == 'N' && pd == 'N'){
    $('#btn-pd-type-no').removeClass('btn-primary');
    $('#btn-pd-type-yes').addClass('btn-primary');
    $('#btn-pd-type-no').removeAttr('disabled');
    $('#btn-pd-type-yes').removeAttr('disabled');
    $('#btn-select-pd-type').removeAttr('disabled');
    return;
  }

  if(option == 'N' && sc == 'N' && pd == 'N' && all == 'N'){
    $('#btn-pd-type-yes').removeClass('btn-primary');
    $('#btn-pd-type-no').addClass('btn-primary');
    $('#btn-pd-type-yes').removeAttr('disabled');
    $('#btn-pd-type-no').removeAttr('disabled');
    $('#btn-select-pd-type').attr('disabled', 'disabled');

    return;
  }

  if(all == 'Y' || sc == 'Y' || pd == 'Y'){
    $('#btn-pd-type-yes').attr('disabled', 'disabled');
    $('#btn-pd-type-no').attr('disabled', 'disabled');
    $('#btn-select-pd-type').attr('disabled', 'disabled');
  }
}



function toggleProductBrand(option){
  if(option == '' || option == undefined){
    option = $('#product_brand').val();
  }


  $('#product_brand').val(option);
  sc = $('#product_model').val();
	pd = $('#product_id').val();
  all = $('#all_product').val();

  if(option == 'Y' && all == 'N' && sc == 'N' && pd == 'N'){
    $('#btn-pd-brand-no').removeClass('btn-primary');
    $('#btn-pd-brand-yes').addClass('btn-primary');
    $('#btn-pd-brand-no').removeAttr('disabled');
    $('#btn-pd-brand-yes').removeAttr('disabled');
    $('#btn-select-pd-brand').removeAttr('disabled');
    return;
  }

  if(option == 'N' && sc == 'N' && all == 'N' && pd =='N'){
    $('#btn-pd-brand-yes').removeClass('btn-primary');
    $('#btn-pd-brand-no').addClass('btn-primary');
    $('#btn-pd-brand-no').removeAttr('disabled');
    $('#btn-pd-brand-yes').removeAttr('disabled');
    $('#btn-select-pd-brand').attr('disabled', 'disabled');
    return;
  }

  if(all == 'Y' || sc == 'Y' || pd == 'Y'){
    $('#btn-pd-brand-yes').attr('disabled', 'disabled');
    $('#btn-pd-brand-no').attr('disabled', 'disabled');
    $('#btn-select-pd-brand').attr('disabled', 'disabled');
  }
}


$(document).ready(function() {
  var all = $('#all_product').val();
  var modelId = $('#product_model').val();
	var productId = $('#product_id').val();

  toggleAllProduct(all);
  toggleModelId(modelId);
	toggleProductId(productId);
});



function getUploadFile(){
	$('#upload-modal').modal('show');
}



function getFile(){
	$('#uploadFile').click();
}








$("#uploadFile").change(function(){
	if($(this).val() != '')
	{
		var file 		= this.files[0];
		var name		= file.name;
		var type 		= file.type;
		var size		= file.size;

		if( size > 5000000 )
		{
			swal("ขนาดไฟล์ใหญ่เกินไป", "ไฟล์แนบต้องมีขนาดไม่เกิน 5 MB", "error");
			$(this).val('');
			return false;
		}
		//readURL(this);
		$('#show-file-name').text(name);
	}
});


function readExcelFile() {
		$('#upload-modal').modal('hide');
		var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.xlsx|.xls)$/;
	 /*Checks whether the file is a valid excel file*/
	 if (regex.test($("#uploadFile").val().toLowerCase())) {
			 var xlsxflag = false; /*Flag for checking whether excel is .xls format or .xlsx format*/
			 if ($("#uploadFile").val().toLowerCase().indexOf(".xlsx") > 0) {
					 xlsxflag = true;
			 }
			 /*Checks whether the browser supports HTML5*/
			 if (typeof (FileReader) != "undefined") {
					 var reader = new FileReader();
					 reader.onload = function (e) {
							 var data = e.target.result;
							 /*Converts the excel data in to object*/
							 if (xlsxflag) {
									 var workbook = XLSX.read(data, { type: 'binary' });
							 }
							 else {
									 var workbook = XLS.read(data, { type: 'binary' });
							 }
							 /*Gets all the sheetnames of excel in to a variable*/
							 var sheet_name_list = workbook.SheetNames;

							 var cnt = 0; /*This is used for restricting the script to consider only first sheet of excel*/
							 sheet_name_list.forEach(function (y) { /*Iterate through all sheets*/
									 /*Convert the cell value to Json*/
									 if (xlsxflag) {
											 var exceljson = XLSX.utils.sheet_to_json(workbook.Sheets[y]);
									 }
									 else {
											 var exceljson = XLS.utils.sheet_to_row_object_array(workbook.Sheets[y]);
									 }

									 if (exceljson.length > 0 && cnt == 0) {
											 addToList(exceljson);
											 cnt++;
									 }
							 });
					 }

					 if (xlsxflag) {/*If excel file is .xlsx extension than creates a Array Buffer from excel*/
							 reader.readAsArrayBuffer($("#uploadFile")[0].files[0]);
					 }
					 else {
							 reader.readAsBinaryString($("#uploadFile")[0].files[0]);
					 }
			 }
			 else {
					 swal({
						 title:'Error!',
						 text:"Sorry! Your browser does not support HTML5!",
						 type:'error'
					 });
			 }
	 }
	 else {
			 swal({
				 title:'Error!',
				 text:"Please upload a valid Excel file!",
				 type:'error'
			 });
	 }
}


function addToList(jsondata) {
	if(jsondata.length) {
		//--- clear current li in model-list
		$('#model-list li').remove();
		//--- clear current hidden input list
		$('.modelId').remove();
		$('#psCount').text('0');

		for (var i = 0; i < jsondata.length; i++) {
			//console.log(jsondata[i]);
			var code = $.trim(jsondata[i].Model);
			addModel(code);
		}
	}
}

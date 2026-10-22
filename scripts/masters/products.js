function getEdit(id, pageNo = 0) {
	window.location.href = `${HOME}edit/${id}/${pageNo}`;
}

function viewDetail(id, pageNo = 0) {
	window.location.href = `${HOME}view_detail/${id}/${pageNo}`;
}


function update() {
	const h = {
		'id' : $('#id').val(),
		'code' : $('#code').val().trim(),
		'model' : $('#model').val(),
		'brand' : $('#brand').val(),
		'category' : $('#category').val(),
		'cateCode1' : $('#cateCode1').val(),
		'cateCode2' : $('#cateCode2').val(),
		'cateCode3' : $('#cateCode3').val(),
		'cateCode4' : $('#cateCode4').val(),
		'type' : $('#type').val(),
		'is_cover' : $('#is_cover').is(':checked') ? 1 : 0
	}
	
	load_in();

	$.ajax({
		url: `${HOME}update`,
		type:'POST',
		cache:false,
		data:{
			'data' : JSON.stringify(h)
		},
		success:function(rs) {
			load_out();
			if(rs === 'success') {
				swal({
					title:"Success",
					type:'success',
					timer:1000
				});

				setTimeout(function() {
					window.location.reload();
				}, 1200);
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


$('#price').focus(function(){
	$(this).select();
})

$('#cost').focus(function(){
	$(this).select();
})



function changeImage() {
	$('#imageModal').modal('show');
}

function doUpload()
{
	var id = $('#id').val();
	var code = $('#code').val();
	var image	= $("#image")[0].files[0];

	if( image == '' ){
		swal('ข้อผิดพลาด', 'ไม่สามารถอ่านข้อมูลรูปภาพที่แนบได้ กรุณาแนบไฟล์ใหม่อีกครั้ง', 'error');
		return false;
	}


	$("#imageModal").modal('hide');

	var fd = new FormData();
	fd.append('image', $('input[type=file]')[0].files[0]);
	fd.append('code', code);
	fd.append('id', id);

	load_in();

	$.ajax({
		url: HOME + 'change_image',
		type:"POST",
		cache: "false",
		data: fd,
		processData:false,
		contentType: false,
		success: function(rs){
			load_out();
			var rs = $.trim(rs);
			if( rs == 'success')
			{
				swal({
					title : 'Success',
					type: 'success',
					timer: 1000
				});

				setTimeout(function(){
					window.location.reload();
				}, 1200);

			}
			else
			{
				swal("ข้อผิดพลาด", rs, "error");
			}
		},
		error:function(xhr, status, error) {
			load_out();
			swal({
				title:'Error!',
				text:"Error-"+xhr.status+": "+xhr.statusText,
				type:'error'
			})
		}
	});
}

function readURL(input)
{
	 if (input.files && input.files[0]) {
				var reader = new FileReader();
				reader.onload = function (e) {
					$('#previewImg').html('<img id="previewImg" src="'+e.target.result+'" width="200px" alt="รูปสินค้า" />');
				}
				reader.readAsDataURL(input.files[0]);
		}
}

$("#image").change(function(){
	if($(this).val() != '')
	{
		var file 		= this.files[0];
		var name		= file.name;
		var type 		= file.type;
		var size		= file.size;
		if(file.type != 'image/png' && file.type != 'image/jpg' && file.type != 'image/gif' && file.type != 'image/jpeg' )
		{
			swal("รูปแบบไฟล์ไม่ถูกต้อง", "กรุณาเลือกไฟล์นามสกุล jpg, jpeg, png หรือ gif เท่านั้น", "error");
			$(this).val('');
			return false;
		}

		if( size > 2000000 )
		{
			swal("ขนาดไฟล์ใหญ่เกินไป", "ไฟล์แนบต้องมีขนาดไม่เกิน 2 MB", "error");
			$(this).val('');
			return false;
		}

		readURL(this);

		$("#btn-select-file").css("display", "none");
		$("#block-image").animate({opacity:1}, 1000);
	}
});


function removeFile()
{
	$("#previewImg").html('');
	$("#block-image").css("opacity","0");
	$("#btn-select-file").css('display', '');
	$("#image").val('');
}


function deleteImage(id)
{
  swal({
		title: "คุณแน่ใจ ?",
		text: "ต้องการลบรูปภาพ หรือไม่ ?",
		type: "warning",
		showCancelButton: true,
		confirmButtonColor: "#FA5858",
		confirmButtonText: 'ใช่, ฉันต้องการลบ',
		cancelButtonText: 'ยกเลิก',
		closeOnConfirm: false
		}, function(){
      $.ajax({
    		url: HOME + 'delete_image/'+id,
    		type:"POST",
        cache:"false",
    		success: function(rs){
    			var rs = $.trim(rs);
    			if( rs == 'success' )
    			{
            swal({
              title:'Deleted',
              text:'ลบรูปภาพเรียบร้อยแล้ว',
              type:'success',
              timer:1000
            });

    				setTimeout(function(){
							window.location.reload();
						},1200)
    			}
    			else
    			{
    				swal({
							title:'Error!',
							text:rs,
							type:'error'
						})
    			}
    		},
				error: function(rs) {
					swal({
						title:'Error!',
						text:"Error-" + rs.status + ": "+rs.statusText,
						type:"error"
					})
				}
    	});
	});
}


function getParentCate() {
	let code = $('#category').val();

	if(code == "") {
		$('#cateCode1').val("");
		$('#cateCode2').val("");
		$('#cateCode3').val("");
		$('#cateCode4').val("");
	}
	else {
		$.ajax({
			url: `${HOME}get_category_parent_list`,
			type:'GET',
			cache:false,
			data:{
				'code' : code
			},
			success:function(rs) {
				if(isJson(rs)) {
					var ds = $.parseJSON(rs);
					$('#cateCode1').val(ds.l1);
					$('#cateCode2').val(ds.l2);
					$('#cateCode3').val(ds.l3);
					$('#cateCode4').val(ds.l4);
				}
				else {
					$('#cateCode1').val("");
					$('#cateCode2').val("");
					$('#cateCode3').val("");
					$('#cateCode4').val("");
				}
			}
		})
	}
}



function updateCategory() {
	load_in();

	$.ajax({
		url:HOME + 'update_parent_category',
		type:'POST',
		cache:false,
		success:function(rs) {
			load_out();

			swal({
				title:'Completed',
				type:'success',
				timer:1000
			});

			setTimeout(function() {
				window.location.reload();
			}, 1200);
		}
	});
}



function toggleCountStock(el) {
	let id = el.val();
	let countStock = el.is(':checked') ? 1 : 0;

	$.ajax({
		url:HOME + 'set_count_stock',
		type:'GET',
		cache:false,
		data:{
			'id' : id,
			'count_stock' : countStock
		}
	});
}


function toggleChangeDiscount(el) {
	let id = el.val();
	let allow = el.is(':checked') ? 1 : 0;

	$.ajax({
		url:HOME + 'set_allow_change_discount',
		type:'GET',
		cache:false,
		data:{
			'id' : id,
			'allow_change_discount' : allow
		}
	});
}


function toggleCustomerView(el) {
	let id = el.val();
	let allow = el.is(':checked') ? 1 : 0;

	$.ajax({
		url:HOME + 'set_customer_view',
		type:'GET',
		cache:false,
		data:{
			'id' : id,
			'customer_view' : allow
		}
	});
}

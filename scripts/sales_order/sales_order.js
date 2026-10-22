const addNew = () => {
	window.location.href = `${HOME}add_new`;
}

const edit = (code, pageNo = 0) => {
	load_in();
	window.location.href = `${HOME}edit/${code}/${pageNo}`;
}

const goEdit = (code, pageNo = 0) => {
	load_in();
	window.location.href = `${HOME}edit/${code}/${pageNo}`;
}

const viewDetail = (code, pageNo = 0) => {
	load_in();
	window.location.href = `${HOME}view_detail/${code}/${pageNo}`;
}

function viewSQ(sqNo) {
	const url = `${BASE_URL}orders/quotation/view_detail/${sqNo}`;	
	const width = 1500;
	const height = 800;
	const left = (screen.width - width) / 2;
	window.open(url, '_blank', `width=${width},height=${height},left=${left},top=100`);
}

function cancleOrder(code){
	swal({
		title: "คุณแน่ใจ ?",
		text: "ต้องการยกเลิกออเดอร์หรือไม่ ?",
		type: "warning",
		showCancelButton: true,
		confirmButtonColor: "#DD6B55",
		confirmButtonText: 'ใช่, ฉันต้องการยกเลิก',
		cancelButtonText: 'ไม่ใช่',
		closeOnConfirm: true
		},
		function(){
			load_in();
			$.ajax({
				url:`${HOME}cancle_order`,
				type:'POST',
				cache:false,
				data:{
					'code' : code
				},
				success:function(rs) {
					load_out();

					if(rs == 'success') {
						setTimeout(function(rs) {
							swal({
								title:'Success',
								type:'success',
								timer:1000
							});

							setTimeout(function() {
								window.location.reload();
							}, 1200);
						}, 500);
					}
					else {
						setTimeout(function() {
							swal({
								title:'Error!',
								type:'error',
								text:rs
							});
						}, 500);
					}
				}
			});
	});
}

function toggleOnlyMe() {
	let option = parseDefault(parseInt($('#onlyMe').val()), 0);

	if(option == 1) {
		$('#onlyMe').val(0);
	}
	else {
		$('#onlyMe').val(1);
	}

	getSearch();
}

function doApprove(code) {
  $.ajax({
    url:`${HOME}approve`,
    type:'POST',
    cache:false,
    data:{
      'code' : code
    },
    success:function(rs) {      
      if(rs.trim() === 'success') {
        swal({
          title:'Success',
          type:'success',
          timer:1000
        });

        setTimeout(function(){
          window.location.reload();
        }, 1200);
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

function doReject(code) {
  $.ajax({
    url:`${HOME}reject`,
    type:'POST',
    cache:false,
    data:{
      'code' : code
    },
    success:function(rs) {     
      if(rs.trim() === 'success') {
        swal({
          title:'Success',
          type:'success',
          timer:1000
        });

        setTimeout(function(){
          window.location.reload();
        }, 1200);
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

function cancleSap(code) {
	swal({
    title:'ยกเลิกเอกสารบน SAP',
    text:'คุณต้องการยกเลิกเอกสารนี้บน SAP หรือไม่ ?',
    type:'warning',
    showCancelButton:true,
		confirmButtonColor: "#DD6B55",
    cancelButtonText:'ไม่ใช่',
    confirmButtonText:'ใช่ ต้องการยกเลิก',
		closeOnConfirm:false
  },
  function(){
		load_in();
		$.ajax({
			url:`${HOME}cancle_sap_order`,
			type:'POST',
			cache:false,
			data: {
				'code' : code
			},
			success:function(rs) {
				load_out();
				if(rs == 'success') {
					swal({
						title:'Success',
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
						text:rs,
						type:'error'
					});
				}
			}
		});
  });
}

function sendToSap(code) {

  load_in();

  $.ajax({
    url:`${HOME}send_to_sap`,
    type:'POST',
    cache:false,
    data:{
      'code' : code
    },
    success:function(rs) {
      load_out();
      var rs = $.trim(rs);
      if(rs === 'success') {
        swal({
          title:'Success',
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
          text:rs,
          type:'error'
        })
      }
    }
  })
}

function leave(backUrl = null) {
  swal({
    title:'คุณแน่ใจ ?',
    text:'รายการทั้งหมดจะไม่ถูกบันทึก ต้องการออกหรือไม่ ?',
    type:'warning',
    showCancelButton:true,
    cancelButtonText:'ไม่ใช่',
    confirmButtonText:'ออกจากหน้านี้',
  },
  function(){
		if(backUrl == null) {
			goBack();
		}
		else {
    	window.location.href = backUrl;
		}
  });
}

function showMessage(code) {
	$.ajax({
		url:`${HOME}get_order_message`,
		type:'GET',
		cache:false,
		data:{
			'code' : code
		},
		success:function(rs) {
			if(isJson(rs)) {
				let ds = $.parseJSON(rs);
				let source = $('#failed-template').html();
				let output = $('#failed-table');

				render(source, ds, output);

				$('#failedModal').modal('show');
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
}

$("#fromDate").datepicker({
	dateFormat: 'dd-mm-yy',
	onClose: function(ds){
		$("#toDate").datepicker("option", "minDate", ds);
	}
});

$("#toDate").datepicker({
	dateFormat: 'dd-mm-yy',
	onClose: function(ds){
		$("#fromDate").datepicker("option", "maxDate", ds);
	}
});

$('#DocDate').datepicker({
  dateFormat:'dd-mm-yy',
  onClose:function(ds) {
    $('#ShipDate').datepicker("option", "minDate", ds);
  }
});

$('#ShipDate').datepicker({
  dateFormat:'dd-mm-yy'
});

$('#TextDate').datepicker({
  dateFormat:'dd-mm-yy'
});

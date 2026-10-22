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

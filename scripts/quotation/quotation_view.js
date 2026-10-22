function createSO(code) {
	swal({
    title:'Create Sale Order',
    text:'ต้องการสร้าง Sale Order ใหม่ จาก Sale Quotation นี้หรือไม่ ?',
    type:'warning',
    showCancelButton:true,
    cancelButtonText:'ไม่ใช่',
    confirmButtonText:'ใช่ ฉันต้องการ',
		closeOnConfirm:true
  },
  function() {
		load_in();
		setTimeout(() => {
			$.ajax({
				url: `${BASE_URL}orders/orders/create_from_sq`,
				type: 'POST',
				cache: false,
				data: {
					'sq_code': code
				},
				success: function (rs) {
					load_out();
					if (isJson(rs)) {
						let ds = JSON.parse(rs);
						if (ds.status === 'success') {
							swal({
								title: 'Success',
								text: 'Sale Order Created Successfull : ' + ds.code,
								type: 'success',
								timer: 1000
							});

							setTimeout(function () {
								window.location.href = `${BASE_URL}orders/orders/edit/${ds.code}/0`;
							}, 1200);
						}
						else {
							showError(ds.error);
						}
					}
					else {
						showError(rs);
					}
				},
				error: function (rs) {
					showError(rs);
				}
			});
		}, 100);		
  });
}

function printSQ() {
	const code  = $("#order_code").val();
	const width = 1200;
	const height = 950;
	const left = (window.innerWidth - width) / 2;
	const target = `${HOME}print_sq/${code}`;
	const prop = `width=${width}, height=${height}, left=${left}, scrollbars=yes`;
	window.open(target, '_blank', prop);
}

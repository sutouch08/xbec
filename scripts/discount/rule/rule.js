
const addNew = () => {
	window.location.href = `${HOME}add_new/`;
};

const edit = (id, pageNo = 0) => {
	window.location.href = `${HOME}edit/${id}/${pageNo}`;
};

const viewDetail = (id, pageNo = 0) => {
	window.location.href = `${HOME}view_detail/${id}/${pageNo}`;
};

const preview = (id) => {
	const target = `${HOME}preview/${id}`;
	const width = 800;
	const height = 900;
	const center = ($(document).width() - width) / 2;
	window.open(target, '_blank', `width=${width}, height=${height}, left=${center}, scrollbars=yes`);
};

const viewPolicyDetail = (id) => {
	const target = `${BASE_URL}discount/discount_policy/view_detail/${id}?nomenu&nonavbar`;
	const width = 1350;
	const height = 800;
	const left = (window.screen.width - width) / 2;
	const top = (window.screen.height - height) / 2;

	window.open(target, '_blank', `width=${width},height=${height},left=${left},top=${top},location=no,scrollbars=yes`);
};

const confirmDelete = (id, code) => {
	swal({
		title: "คุณแน่ใจ ?",
		text: `ต้องการลบ '${code}' หรือไม่ ?`,
		type: "warning",
		showCancelButton: true,
		confirmButtonColor: "#FA5858",
		confirmButtonText: 'Yes',
		cancelButtonText: 'No',
		closeOnConfirm: true
	}, function () {
		load_in();

		setTimeout(() => {
			$.ajax({
				url: `${HOME}delete`,
				type: "POST",
				cache: "false",
				data: {
					"id": id
				},
				success: function (rs) {
					load_out();

					if (rs.trim() == 'success') {
						swal({
							title: 'Deleted',
							type: 'success',
							timer: 1000
						});

						$(`#row-${id}`).remove();
						reIndex();					
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
};

function deleteChecked() {
	let rules = [];
	$('.chk:checked').each(function () {
		rules.push($(this).val());
	});

	if (rules.length > 0) {
		swal({
			title: "คุณแน่ใจ ?",
			text: "ต้องการลบรายการที่เลือกหรือไม่ ?",
			type: "warning",
			showCancelButton: true,
			confirmButtonColor: "#FA5858",
			confirmButtonText: 'Yes',
			cancelButtonText: 'No',
			closeOnConfirm: true
		}, function () {
			load_in();

			setTimeout(() => {
				$.ajax({
					url: `${HOME}delete_multiple`,
					type: "POST",
					cache: false,
					data: {
						"rules": rules
					},
					success: function (rs) {
						load_out();

						if (rs.trim() == 'success') {
							swal({
								title: 'Deleted',
								type: 'success',
								timer: 1000
							});
							$('.chk:checked').each(function () {
								$("#row-" + $(this).val()).remove();
							});

							reIndex();
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
}

function toggleActive(el, id) {
	const active = el.checked ? 1 : 0;

	$.ajax({
		url: `${HOME}set_active`,
		type: 'POST',
		cache: false,
		data: {
			'id': id,
			'active': active
		},
		success: function (rs) {
			if (rs.trim() !== 'success') {
				$(el).prop('checked', !active);

				showError(rs);
			}
		},
		error: function (rs) {
			showError(rs);
		}
	});
}

function checkAll(el) {
	if (el.checked) {
		$('.chk').prop('checked', true);
	}
	else {
		$('.chk').prop('checked', false);
	}
}

$('#fromDate').datepicker({
	dateFormat: 'dd-mm-yy',
	onClose: function (selectedDate) {
		$('#toDate').datepicker("option", "minDate", selectedDate);
	}
});

$('#toDate').datepicker({
	dateFormat: 'dd-mm-yy',
	onClose: function (selectedDate) {
		$('#fromDate').datepicker("option", "maxDate", selectedDate);
	}
});
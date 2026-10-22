const edit = (id, pageNo = 0) => {
	window.location.href = `${HOME}edit/${id}/${pageNo}`;
};

async function update() {
	clearErrorByClass('r');

	let h = {
		"id" : $('#id').val(),
		"name" : $('#name').val()
	};

	if(h.name.length == 0) {
		$('#name').hasError();
		return false;
	}

	const url = `${HOME}update`;
	
	try {
		const response = await fetch(url, {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json'
			},
			body: JSON.stringify(h)
		});

		const result = await response.text();

		if(result === 'success') {
			swal({
				title:"Success",
				type:'success',
				timer:1000
			});			
		} 
		else {
			showError(result);
		}
	}
	catch (error) {
		showError(error);
	}	
}

async function syncData() {
	load_in();
	const url = `${HOME}sync_data`;
	try {
		const response = await fetch(url, {
			method: 'GET'
		});

		const result = await response.text();

		load_out();

		if(result === 'success') {
			swal({
				title:'Success',
				type:'success',
				timer:1000
			});

			setTimeout(function() {
				goBack();
			}, 1200);
		} 
		else {
			showError(result);
		}
	}
	catch (error) {		
		showError(error);
	}
}

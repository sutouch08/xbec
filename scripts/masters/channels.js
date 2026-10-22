let click = 0;

const addNew = () => {
	window.location.href = `${HOME}add_new`;
}

const edit = (id, pageNo = 0) => {
	window.location.href = `${HOME}edit/${id}/${pageNo}`;
}

async function isExistsCode(code, id = null) {
	const url = `${HOME}is_exists_code`;
	const data = {'code': code, 'id': id};
	try {
		const response = await fetch(url, {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json'
			},
			body: JSON.stringify(data)
		});

		const res = await response.text();
		return res.trim() === 'exists';
	} catch (error) {
		console.error('Error checking code existence:', error);
		return false;
	}
}

async function isExistsName(name, id = null) {
	const url = `${HOME}is_exists_name`;
	const data = {'name': name, 'id': id};
	try {
		const response = await fetch(url, {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json'
			},
			body: JSON.stringify(data)
		});

		const res = await response.text();
		return res.trim() === 'exists';
	} catch (error) {
		console.error('Error checking name existence:', error);
		return false;
	}
}


async function add() {
	if(click !== 0) {
		return false;
	}

	click = 1;
	clearErrorByClass('r');
	let h = {
		'code' : $('#code').val().trim(),
		'name' : $('#name').val().trim(),
		'position' : parseDefaultInt($('#position').val(), 10),
		'active' : $('#active').is(':checked') ? 1 : 0,
		'is_default' : $('#is_default').is(':checked') ? 1 : 0
	};

	if(h.code.length === 0) {
		$('#code').hasError('Required!');
		click = 0;
		return false;
	}

	if(h.name.length === 0) {
		$('#name').hasError('Required!');
		click = 0;
		return false;
	}

	if(await isExistsCode(h.code)) {
		$('#code').hasError(`${h.code} already exists.`);
		click = 0;
		return false;
	}

	if(await isExistsName(h.name)) {
		$('#name').hasError(`${h.name} already exists.`);
		click = 0;
		return false;
	}

	try {
		const response = await fetch(`${HOME}add`, {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json'
			},
			body: JSON.stringify(h)
		});

		const res = await response.text();
		if(res.trim() === 'success') {
			swal({
				title:'Success',
				type:'success',
				timer:1000
			});

			setTimeout(() => {
				addNew();
			}, 1200);
		} 
		else {
			showError(res);
		}
	} 
	catch (error) {
		console.error('Error adding channel:', error);
		showError('An error occurred while adding the channel.');
		click = 0;
	}
}

async function update() {
	if(click !== 0) {
		return false;
	}

	click = 1;
	clearErrorByClass('r');
	let h = {
		'id' : $('#id').val(),
		'name' : $('#name').val().trim(),
		'position' : parseDefaultInt($('#position').val(), 10),
		'active' : $('#active').is(':checked') ? 1 : 0,
		'is_default' : $('#is_default').is(':checked') ? 1 : 0
	};

	if(h.name.length === 0) {
		$('#name').hasError('Required!');
		click = 0;
		return false;
	}

	if(await isExistsName(h.name, h.id)) {
		$('#name').hasError(`${h.name} already exists.`);
		click = 0;
		return false;
	}

	try {
		const response = await fetch(`${HOME}update`, {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json'
			},
			body: JSON.stringify(h)
		});

		const res = await response.text();
		if(res.trim() === 'success') {
			swal({
				title:'Success',
				type:'success',
				timer:1000
			});
		} 
		else {
			showError(res);
		}
	} 
	catch (error) {
		console.error('Error updating channel:', error);
		showError('An error occurred while updating the channel.');
		click = 0;
	}
}

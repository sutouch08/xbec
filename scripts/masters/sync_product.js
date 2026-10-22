
let total = 0;
let limit = 100;
let updated = 0;
let resend = 3;
let send = 0;
let error = 0;
let last_sync;

async function getLastSyncDate() {
	const url = `${HOME}get_last_sync_date`;
	try {
		const response = await fetch(url);
		const lastSyncDate = await response.text();
		last_sync = lastSyncDate;
	} catch (error) {
		console.error('Error fetching last sync date:', error);
	}
}

async function countUpdateRows(lastSync) {
	const url = `${HOME}count_update_rows?last_sync_date=${lastSync}`;
	try {
		const response = await fetch(url);
		const count = await response.text();
		total = parseDefaultInt(count, 0);
	} catch (error) {
		console.error('Error counting update rows:', error);
		return 0;
	}
}

async function syncData() {
	await getLastSyncDate();
	await countUpdateRows(last_sync);
	if (total > 0) {
		load_in();
		updateData();
	}	
}

async function forceSyncData() {
	last_sync = '2020-01-01 00:00:00';
	await countUpdateRows(last_sync);

	if(total > 0) {
		load_in();
		updateData();
	}
}

function showSuccess() {	
	load_out();
	swal({
		title: 'Success',
		type: 'success',
		timer: 1000
	});

	setTimeout(function () {
		window.location.reload();
	}, 1200);
}
	
async function updateData() {
	console.log(`Sync ${updated} items out of ${total} items`);
	if (updated === total) {
		showSuccess();
		return;
	}

	const url = `${HOME}sync_data?last_sync=${last_sync}&limit=${limit}&offset=${updated}`;
	try {
		const response = await fetch(url);
		const rs = await response.text();
		const res = parseDefaultInt(rs, 0);

		if (res > 0) {
			updated += res;
			send = 0;
		}
		else {
			send++;
		}

		if(updated === total) {
			showSuccess();
			return;
		}

		if(send < resend) {
			updateData();
		}
		else {
			load_out();
			const text = `Updated ${updated} of ${total} successful`;
			swal({
				title: 'Error!',
				text: `response from API AS '${rs}' <br/>${text}`,
				type: 'error',
				html: true
			});

			return false;
		}
	} 
	catch (error) {
		console.error('Error updating data:', error);
		send++;
		if (send > resend) {
			load_out();
			const text = `Updated ${updated} of ${total} successful`;
			swal({
				title: 'Error!',
				text: `An error occurred while syncing data. <br/>${text}`,
				type: 'error',
				html: true
			});
		} 
		else {
			updateData();
		}
	}	
}

async function syncItem(code) {
	const url = `${HOME}sync_item?code=${code}`;
	try {
		load_in();
		const response = await fetch(url);
		const rs = await response.text();
		load_out();
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
			showError(rs);
		}
	} 
	catch (error) {		
		showError('An error occurred while syncing the item.');
	}
}

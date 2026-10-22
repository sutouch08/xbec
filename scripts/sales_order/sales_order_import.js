function getImportFile() {  
  const cardCode = $('#CardCode').val().trim();

  if(cardCode === '') { 
    swal("Customer is required", "Please select a customer before importing a sales order.", "info");
    return;
  }

  $('#uploadFile').click();
}

const uploadFile = document.getElementById('uploadFile');

uploadFile.addEventListener('change', async function() {
  if(this.files.length > 0) {
    const file = this.files[0];
    if (file.size > 5000000) {
      swal("File size exceeds 5MB", "Please select a smaller file.", "error");
      this.value = '';
      return;
    }

    await importSalesOrder(file);
  }
});


async function importSalesOrder(file) {
  const cardCode = $('#CardCode').val().trim();
  const channel = $('#channels').val().trim();
  const payment = $('#payment').val().trim();
  const docDate = $('#DocDate').val().trim();

  if(cardCode === '') { 
    swal("Customer is required", "Please select a customer before importing a sales order.", "info");
    return;
  }
  
  const formData = new FormData();
  formData.append('CardCode', cardCode);
  formData.append('Channels', channel);
  formData.append('Payment', payment);
  formData.append('DocDate', docDate);
  formData.append('uploadFile', file);

  const url = `${HOME}import_order`;

  load_in();

  try {
    const response = await fetch(url, {
      method: 'POST',
      body: formData
    });

    const result = await response.json();
    load_out();
    if (result.status === 'success') {
      await removeEmptyRow();
      await removeFreeRow();
      for (const item of result.data) {
        await addItemRow(item);
      }

      reIndex();
      updatePromotionApplied();
      recalTotal();
      
      // swal("Success", "Sales order imported successfully.", "success");
    } else {
      swal("Error", result.message || "Failed to import sales order.", "error");
    }
  } catch (error) {
    swal("Error", "An error occurred while importing the sales order.", "error");
    console.error('Error importing sales order:', error);
  }
}

function getTemplateFile() {
  window.location.href = `${HOME}get_template_file`;
}
function getImportFile() {  
  $('#uploadFile').click();
}

const uploadFile = document.getElementById('uploadFile');

uploadFile.addEventListener('change', async function () {
  if (this.files.length > 0) {
    const file = this.files[0];
    if (file.size > 5000000) {
      swal("File size exceeds 5MB", "Please select a smaller file.", "error");
      this.value = '';
      return;
    }

    await importSQ(file);
  }
});


async function importSQ(file) {  
  const formData = new FormData();  
  formData.append('uploadFile', file);

  const url = `${HOME}import_items`;

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
      for (const item of result.data) {
        await addItemRow(item);
      }

      reIndex();      
      recalTotal();      
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
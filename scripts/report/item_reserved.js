$('#item-code').autocomplete({
  source: `${HOME}/get_item_code`,
  autoFocus: true,
  minLength: 1,
  close:function() {
    let arr = $(this).val().split(' | ');
    if(arr.length > 1) {
      $(this).val(arr[0]);
    }
    else {
      $(this).val('');
    }
  }
});

$('#customer-code').autocomplete({
  source: `${HOME}/get_customer_code`,
  autoFocus: true,
  minLength: 1,
  close:function() {
    let arr = $(this).val().split(' | ');
    if(arr.length > 1) {
      $(this).val(arr[0]);
    }
    else {
      $(this).val('');
    }
  }
});

function getReport() {
  let h = {
    'item_code' : $('#item-code').val().trim(),
    'customer_code' : $('#customer-code').val().trim(),
    'uname' : $('#uname').val(),
    'status' : $('#status').val()
  };

  load_in();

  $.ajax({
    url: `${HOME}/get_report`,
    type: 'POST',
    data: h,
    success: function(rs) {
      load_out();
      
      if(isJson(rs)) {
        let ds = JSON.parse(rs);

        if(ds.length) {
          let template = $('#report-template').html();
          let output = $('#result-table');
          render(template, ds, output)
        }
        else {
          showError('No data found');
        }
      }
      else {
        showError(rs);
      }
    },
    error: function(rs) {
      showError(rs);
    }
  });
}

async function doExport() {  
  let token = uniqueId();
  $('#token').val(token);
  get_download(token);
  $('#export-form').submit();
}
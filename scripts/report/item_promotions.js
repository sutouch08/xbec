function getReport() {
	let h = {
    code: $('#code').val().trim(),
    name: $('#name').val().trim(),
    promotionStatus: $('#promotion-status').val(),
    ruleStatus: $('#rule-status').val(),
    ruleMethod: $('#method').val(),
    startDate: $('#start-date').val().trim(),
    endDate: $('#end-date').val().trim()
  };

  if(h.code.length == 0 && h.name.length == 0) {
    showError('Please enter item code or item description');
    return;
  }

  load_in();

  $.ajax({
    url: `${HOME}/get_report`,
    type: 'POST',
    data: h,
    success: function(rs) {
      load_out();

      if(isJson(rs)) {
        let data = JSON.parse(rs);
        let template = $('#template').html();
        let output = $('#result-table');

        render(template, data, output);
        reIndex();
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

function doExport() {
  let token = uniqueId();
  let h = {
    code: $('#code').val().trim(),
    name: $('#name').val().trim(),
    promotionStatus: $('#promotion-status').val(),
    ruleStatus: $('#rule-status').val(),
    ruleMethod: $('#method').val(),
    startDate: $('#start-date').val().trim(),
    endDate: $('#end-date').val().trim()
  };

  if (h.code.length == 0 && h.name.length == 0) {
    showError('Please enter item code or item description');
    return;
  }

  $('#token').val(token);

  get_download(token);
  
  $('#reportForm').submit();
}

$('#code').autocomplete({
  source: `${HOME}/get_item_code`,
  autoFocus: true,
  minLength: 1
});

$('#name').autocomplete({
  source: `${HOME}/get_item_name`,
  autoFocus: true,
  minLength: 1
});

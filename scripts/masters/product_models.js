function syncData() {
  load_in();

  $.ajax({
    url: `${HOME}sync_data`,
    type: 'GET',
    cache: false,
    success: function (rs) {
      load_out();
      if (rs === 'success') {
        swal({
          title: 'Success',
          type: 'success',
          timer: 1000
        });

        setTimeout(function () { goBack(); }, 1200);
      }
      else {
        showError(rs);
      }
    },
    error: function (rs) {      
      showError(rs);
    }
  });
}

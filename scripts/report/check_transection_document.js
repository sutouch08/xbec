window.addEventListener('load', () => {
  let height = $(window).height();
	let pageContentHeight = height - 128;
	// header = 80, hr = 15, table margin = 10, footer 170, margin-bottom = 15
	let itemTableHeight = pageContentHeight - (140);

	$('.page-content').css('height', pageContentHeight + 'px');
	$('#result-window').css('height', itemTableHeight + 'px');
})

function getReport() {
  let from_date = $('#fromDate').val();
  let to_date = $('#toDate').val();
  let webCode = $('#web-code').val();
  let soCode = $('#so-code').val();
  let saleId = $('#sale_id').val();
  let custCode = $('#customer').val();

  load_in();

  $.ajax({
    url:HOME + 'getReport',
    type:'POST',
    cache:false,
    data: {
      'from_date' : from_date,
      'to_date' : to_date,
      'webCode' : webCode,
      'soCode' : soCode,
      'saleId' : saleId,
      'customerCode' : custCode
    },
    success:function(rs) {
      load_out();
      if(isJson(rs)) {
        let ds = JSON.parse(rs);
        if(ds.status == 'success') {
          let source = $('#template').html();
          let output = $('#result-table');
          render(source, ds.data, output);
        }
      }
    },
    error:function(xhr) {
      load_out();
      console.log(xhr);
      swal({
        title:'Error!',
        text:xhr.responseText,
        type:'error',
        html:true
      })
    }
  })
}


function doExport() {
  let token = uniqueId();

  $('#token').val(token);
  get_download(token);
  $('#reportForm').submit();
}

$('#include-product').autocomplete({
  source: `${BASE_URL}auto_complete/get_item_code_and_name`,
  autoFocus: true,
  close: function () {
    let rs = $(this).val().trim();
    if (rs.length > 0) {
      let arr = rs.split(' | ');
      if (arr.length === 3) {
        let item = {
          "id": arr[0],
          "code": arr[1],
          "name": arr[2]
        }

        addIncludeProduct(item);        
      }
      else {
        $(this).val('');
      }
    }
  }
});

function addIncludeProduct(item) {
  $(`#include-row-${item.id}`).remove();
  $('#no-product-row').addClass('hide');
  let source = $('#include-product-template').html();
  let output = $('#include-product-table');
  render_append(source, item, output);
  reIndex('in-no');
  $('#include-product').val('').focus();  
}

function removeIncludeItem(id) {
  $(`#include-row-${id}`).remove();
  reIndex('in-no');
  if($('.chk-include').length == 0) {
    $('#no-product-row').removeClass('hide');
  }
}

function removeIncludeChecked() {
  $('.chk-include:checked').each(function () {
    let id = $(this).data('id');
    $(`#include-row-${id}`).remove();
  });

  $('#chk-all-include').prop('checked', false);
  reIndex('in-no');
  if ($('.chk-include').length == 0) {
    $('#no-product-row').removeClass('hide');
  }
}

function toggleAllInclude(el) {
  if (el.checked) {
    $('.chk-include').prop('checked', true);
  }
  else {
    $('.chk-include').prop('checked', false);
  }
}

$('#exclude-product').autocomplete({
  source: `${BASE_URL}auto_complete/get_item_code_and_name`,
  autoFocus: true,
  close: function () {
    let rs = $(this).val().trim();
    if (rs.length > 0) {
      let arr = rs.split(' | ');
      if (arr.length === 3) {
        let item = {
          "id": arr[0],
          "code": arr[1],
          "name": arr[2]
        }
        
        addExcludeProduct(item);
      }
      else {
        $(this).val('');
      }
    }
  }
});

function addExcludeProduct(item) {
  $(`#exclude-row-${item.id}`).remove();
  $('#no-exclude-row').addClass('hide');
  let source = $('#exclude-product-template').html();
  let output = $('#exclude-product-table');
  render_append(source, item, output);
  reIndex('ex-no');
  $('#exclude-product').val('').focus();  
}

function removeExcludeItem(id) {
  $(`#exclude-row-${id}`).remove();
  reIndex('ex-no');
  if($('.chk-exclude').length == 0) {
    $('#no-exclude-row').removeClass('hide');
  }
}

function removeExcludeChecked() {
  $('.chk-exclude:checked').each(function () {
    let id = $(this).data('id');
    $(`#exclude-row-${id}`).remove();
  });

  $('#chk-all-exclude').prop('checked', false);
  reIndex('ex-no');
  if ($('.chk-exclude').length == 0) {
    $('#no-exclude-row').removeClass('hide');
  }
}  

function toggleAllExclude(el) {
  if (el.checked) {
    $('.chk-exclude').prop('checked', true);
  }
  else {
    $('.chk-exclude').prop('checked', false);
  }
}  


$('#sku-net-price').autocomplete({
  source: `${BASE_URL}auto_complete/get_item_code_and_name?price=y`,
  autoFocus: true,
  close: function () {
    let rs = $(this).val().trim();
    if (rs.length > 0) {
      let arr = rs.split(' | ');
      if (arr.length === 4) {
        let item = {
          "id": arr[0],
          "code": arr[1],
          "name": arr[2],
          "price": addCommas(parseDefaultFloat(arr[3], 0.00).toFixed(2)),
          "sell_price": ''
        }

        addSkuNetPriceItem(item);
      }
      else {
        $(this).val('');
      }
    }
  }
});

function addSkuNetPriceItem(item) {
  $(`#net-price-row-${item.id}`).remove();
  $('#no-net-price-row').addClass('hide');
  let source = $('#net-price-template').html();
  let output = $('#net-price-table');
  render_append(source, item, output);
  reIndex('np-no');
  $('#sku-net-price').val('').focus();  
}

function removeNetPriceItem(id) {
  $(`#net-price-row-${id}`).remove();
  reIndex('np-no');
  if ($('.chk-net-price').length == 0) {
    $('#no-net-price-row').removeClass('hide');
  }
}

function removeNetPriceChecked() {
  $('.chk-net-price:checked').each(function () {
    let id = $(this).data('id');
    $(`#net-price-row-${id}`).remove();
  });
  $('#chk-all-net-price').prop('checked', false);
  reIndex('np-no');

  if($('.chk-net-price').length == 0) {
    $('#no-net-price-row').removeClass('hide');
  }
}

function toggleAllNetPrice(el) {
  if (el.checked) {
    $('.chk-net-price').prop('checked', true);
  }
  else {
    $('.chk-net-price').prop('checked', false);
  }
}

//--- active/inactive product input
function toggleAllProduct(option) {
  $('#all-product').val(option);
  if(option == 1) {
    $('#include-product').attr('disabled', 'disabled');
    $('#btn-import-product').attr('disabled', 'disabled');    
    $('#product-table').addClass('hide');
    $('#product-model').attr('disabled', 'disabled');
    $('#product-type').attr('disabled', 'disabled');
    $('#product-category').attr('disabled', 'disabled');
    $('#product-brand').attr('disabled', 'disabled');     
    $('#btn-all-product-yes').addClass('btn-primary');
    $('#btn-all-product-no').removeClass('btn-primary');

    setTimeout(() => {
      $('#exclude-product').focus();
    }, 100);
  }

  if(option == 0) {
    $('#include-product').removeAttr('disabled');
    $('#btn-import-product').removeAttr('disabled');
    $('#product-table').removeClass('hide');
    $('#product-model').removeAttr('disabled');
    $('#product-type').removeAttr('disabled');
    $('#product-category').removeAttr('disabled');
    $('#product-brand').removeAttr('disabled');    
    $('#btn-all-product-no').addClass('btn-primary');
    $('#btn-all-product-yes').removeClass('btn-primary');    

    setTimeout(() => {
      $('#include-product').focus();
    }, 100);
  }   
}

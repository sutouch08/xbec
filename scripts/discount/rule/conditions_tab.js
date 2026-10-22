$('#customer-id').autocomplete({
  source: `${BASE_URL}auto_complete/get_customer_list`,
  autoFocus: true,
  close: function () {
    let ds = $(this).val().trim();

    if (ds.length > 0) {
      let arr = ds.split(' | ');
      if (arr.length === 3) {
        let customer = {
          "id": arr[0],
          "code": arr[1],
          "name": arr[2]
        }

        addCustomer(customer);
      }
    }
  }
});

function addCustomer(customer) {
  $(`#customer-row-${customer.id}`).remove();  
  $('#no-customer-row').addClass('hide');
  let source = $('#customer-template').html();
  let output = $('#customer-list');
  render_append(source, customer, output);
  $('#customer-id').val('');
  reIndex('cust-no');
  $('#customer-id').focus();  
}

function removeCustomer(id) {
  $(`#customer-row-${id}`).remove();
  reIndex('cust-no');
  if ($('.chk-customer').length == 0) {
    $('#no-customer-row').removeClass('hide');
  }
}

function removeCustomerChecked() {
  $('.chk-customer:checked').each(function () {
    let id = $(this).data('id');
    $(`#customer-row-${id}`).remove();
  });

  $('#chk-all-customer').prop('checked', false);
  reIndex('cust-no');
  if ($('.chk-customer').length == 0) {
    $('#no-customer-row').removeClass('hide');
  }
}

function toggleAllCustomerCheck(el) {
  if ($(el).is(':checked')) {
    $('.chk-customer').prop('checked', true);
  }
  else {
    $('.chk-customer').prop('checked', false);
  }
}




$('#premium-sku').autocomplete({
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
          "sell_price": 0
        }

        addPremiumProduct(item);
      }
      else {
        $(this).val('');
      }
    }
  }
});

function addPremiumProduct(item) {
  $(`#premium-row-${item.id}`).remove();
  $('#no-premium-row').addClass('hide');
  let source = $('#premium-template').html();
  let output = $('#premium-table');
  render_append(source, item, output);
  $('#premium-sku').val('');
  reIndex('premium-no');
  $('#premium-sku').focus();  
}

function removePremiumItem(id) {
  $(`#premium-row-${id}`).remove();
  reIndex('premium-no');
  if ($('.chk-premium').length == 0) {
    $('#no-premium-row').removeClass('hide');
  }
}

function removePremiumChecked() {
  $('.chk-premium:checked').each(function () {
    let id = $(this).data('id');
    $(`#premium-row-${id}`).remove();
  });
  $('#chk-all-premium').prop('checked', false);
  reIndex('premium-no');
  if ($('.chk-premium').length == 0) {
    $('#no-premium-row').removeClass('hide');
  }
}




function toggleAllCustomer(option) {
  $('#all-customer').val(option);
  if (option == 1) {
    $('#customer-id').attr('disabled', 'disabled');
    $('#customer-id').val('');
    $('#btn-import-customer').attr('disabled', 'disabled');
    $('#customer-table').addClass('hide');
    $('#sales-team').attr('disabled', 'disabled');
    $('#customer-group').attr('disabled', 'disabled');
    $('#customer-type').attr('disabled', 'disabled');
    $('#customer-area').attr('disabled', 'disabled');
    $('#customer-grade').attr('disabled', 'disabled');
    $('#btn-cust-all').addClass('btn-primary');
    $('#btn-cust-none').removeClass('btn-primary');
  }

  if (option == 0) {
    $('#customer-id').removeAttr('disabled');
    $('#btn-import-customer').removeAttr('disabled');
    $('#customer-table').removeClass('hide');
    $('#sales-team').removeAttr('disabled');
    $('#customer-group').removeAttr('disabled');
    $('#customer-type').removeAttr('disabled');
    $('#customer-area').removeAttr('disabled');
    $('#customer-grade').removeAttr('disabled');
    $('#btn-cust-all').removeClass('btn-primary');
    $('#btn-cust-none').addClass('btn-primary');
  }

}

function toggleAllChannels(option) {
  $('#all-channels').val(option);
  if (option == 1) {
    $('#channels').attr('disabled', 'disabled');
    $('#btn-channels-all').addClass('btn-primary');
    $('#btn-channels-none').removeClass('btn-primary');
  }

  if (option == 0) {
    $('#channels').removeAttr('disabled');
    $('#btn-channels-all').removeClass('btn-primary');
    $('#btn-channels-none').addClass('btn-primary');
  }
}

function toggleAllPayments(option) {
  $('#all-payments').val(option);
  if (option == 1) {
    $('#payments').attr('disabled', 'disabled');
    $('#btn-payments-all').addClass('btn-primary');
    $('#btn-payments-none').removeClass('btn-primary');
  }

  if (option == 0) {
    $('#payments').removeAttr('disabled');
    $('#btn-payments-all').removeClass('btn-primary');
    $('#btn-payments-none').addClass('btn-primary');
  }
}

function toggleCanGroup(option) {
  $('#can-group').val(option);
  if (option == 1) {
    $('#btn-can-group-yes').addClass('btn-primary');
    $('#btn-can-group-no').removeClass('btn-primary');
  }

  if (option == 0) {
    $('#btn-can-group-yes').removeClass('btn-primary');
    $('#btn-can-group-no').addClass('btn-primary');
  }
}

function toggleAllPremium(el) {
  if (el.checked) {
    $('.chk-premium').prop('checked', true);
  }
  else {
    $('.chk-premium').prop('checked', false);
  }
}

//--- discount step
$('.disc-input').focusin(function () {
  $(this).select();
});

$('.disc-input').focusout(function () {
  validateDiscount();
});

function validateDiscount() {
  let valid = 1;
  for (let i = 5; i >= 1; i--) {
    let el = $(`#disc-${i}`);
    el.clearError();
    let disc = parseDefaultFloat(el.val(), 0.00);
    let step = el.data('step');
    if (disc < 0 || disc > 100) {
      el.hasError();
      valid = 0;
    }

    if (step > 1) {
      let prev = parseDefaultFloat($(`#disc-${step - 1}`).val(), 0.00);

      if ((disc > 0 && prev == 0) || disc >= 100 || disc < 0) {
        el.hasError();
        valid = 0;
      }
    }
  }

  $('#valid-discount').val(valid);

  return valid;
}

$('#min-qty').focusin(function () {
  $(this).select();
});

$('#min-qty').focusout(function () {
  let valid = 1;
  let el = $(this);
  el.clearError();
  let min = parseDefaultFloat(el.val(), 0);

  if (min < 0) {
    el.hasError();
    valid = 0;
  }

  $('#valid-min-qty').val(valid);
});

$('#min-amount').focusin(function () {
  $(this).select();
});

$('#min-amount').focusout(function () {
  let valid = 1;
  let el = $(this);
  el.clearError();
  let min = parseDefaultFloat(el.val(), 0);

  if (min < 0) {
    el.hasError();
    valid = 0;
  }

  $('#valid-min-amount').val(valid);
});

$('#premium-qty').focusin(function () {
  $(this).select();
});

$('#premium-qty').focusout(function () {
  let el = $(this);
  el.clearError();
  let min = parseDefaultFloat(el.val(), 0);

  if (min < 0) {
    el.hasError();
  }
});

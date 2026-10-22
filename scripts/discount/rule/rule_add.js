
const updateConditionLayout = () => {
  const conditionType = $('#condition-type').val();
  if(conditionType === 'P') {
    //-- Percentage 
    $('#product-percentage').removeClass('hide');
    $('#product-net-price').addClass('hide');
    $('#condition-discount').removeClass('hide');
    $('#condition-premium').addClass('hide');
  }

  if(conditionType === 'N') {
    //-- Net Price
    $('#product-percentage').addClass('hide');
    $('#product-net-price').removeClass('hide');
    $('#condition-discount').addClass('hide');
    $('#condition-premium').addClass('hide');
  }

  if(conditionType === 'F') {
    //-- Premium
    $('#product-percentage').removeClass('hide');
    $('#product-net-price').addClass('hide');
    $('#condition-discount').addClass('hide');
    $('#condition-premium').removeClass('hide');
  }
}

function getSkuTemplate() {
  window.location.href = `${HOME}get_sku_template`;
}

function getCustomerTemplate() {
  window.location.href = `${HOME}get_customer_template`;
}

function add() {
  const conditionType = $('#condition-type').val();
  const h = {
    'name': $('#name').val().trim(),
    'type': conditionType,
    'id_policy': $('#policy').val(),
    'active': $('#active').is(':checked') ? 1 : 0,
    'all_products': conditionType === 'N' ? 0 : $('#all-product').val(),
    'all_customers': $('#all-customer').val(),
    'all_channels': $('#all-channels').val(),
    'all_payments': $('#all-payments').val(),
    'premium_qty': parseDefaultFloat($('#premium-qty').val(), 0.00),
    'min_qty': $('#min-qty').val(),
    'min_amount': $('#min-amount').val(),
    'priority': $('#priority').val(),
    'can_group': $('#can-group').val(),
    'premiums': [],
    'discount': [],
    'products': [],
    'models': [],
    'product_types': [],
    'product_categories': [],
    'product_brands': [],
    'exclude_products': [],
    'customers': [],
    'sales_teams': [],
    'customer_groups': [],
    'customer_types': [],
    'customer_areas': [],
    'customer_grades': [],
    'channels': [],
    'payments': []
  };

  if(h.name.length === 0) {
    swal('ข้อผิดพลาด', 'กรุณาระบุชื่อเงื่อนไข', 'error');
    return false;
  }

  let valid_products = h.all_products == 1 ? 1 : 0; //-- to check if there is at least 1 product in the list or if all_products is checked
  let valid_customers = h.all_customers == 1 ? 1 : 0; //-- to check if there is at least 1 customer in the list or if all_customers is checked
  let valid_channels = h.all_channels == 1 ? 1 : 0; //-- to check if there is at least 1 channel in the list or if all_channels is checked
  let valid_payments = h.all_payments == 1 ? 1 : 0; //-- to check if there is at least 1 payment in the list or if all_payments is checked
  let valid_premiums = 0; //-- to check if there is at least 1 premium in the list

  //--- disacount step
  if(h.type === 'P') {
    let disc1 = parseDefaultFloat($('#disc-1').val(), 0.00);

    if(disc1 <= 0) {
      swal('ข้อผิดพลาด', 'กรุณากรอกส่วนลดให้ถูกต้อง', 'error');
      return false;
    }

    const isValidDiscount = validateDiscount();
    if (!isValidDiscount) {
      swal('ข้อผิดพลาด', 'กรุณากรอกส่วนลดให้ถูกต้อง', 'error');
      return false;
    }

    for (let i = 1; i <= 5; i++) {
      let disc = parseDefaultFloat($(`#disc-${i}`).val(), 0.00);
      h.discount.push({ 'step': i, 'discount': disc });
    }
  }

  if ($('#valid-min-qty').val() == 0) {
    swal('ข้อผิดพลาด', 'กรุณากรอกจำนวนขั้นต่ำให้ถูกต้อง', 'error');
    return false;
  }

  if ($('#valid-min-amount').val() == 0) {
    swal('ข้อผิดพลาด', 'กรุณากรอกมูลค่าขั้นต่ำให้ถูกต้อง', 'error');
    return false;
  }

  if (h.type == 'N') {
    if ($('.net-price').length > 0) {
      $('.net-price').each(function () {
        let id = $(this).data('id');
        let code = $(this).data('code');
        let sell_price = parseDefaultFloat($(this).val(), 0.00);
        h.products.push({ 'id': id, 'code': code, 'sell_price': sell_price });
      });

      valid_products++;
    }
  }

  if (h.all_products == 0) {
    if(h.type === 'P' || h.type === 'F') {
      if ($('.chk-include').length > 0) {
        $('.chk-include').each(function () {
          let id = $(this).data('id');
          let code = $(this).data('code');
          let sell_price = $(this).data('sellprice');
          h.products.push({ 'id': id, 'code': code, 'sell_price': sell_price });
        });
        valid_products++;
      }

      let models = $('#product-model').val();
      if (models && models.length > 0) {
        models.forEach(model => {
          h.models.push({ 'id': model });
        });
        valid_products++;
      }

      let types = $('#product-type').val();
      if (types && types.length > 0) {
        types.forEach(type => {
          h.product_types.push({ 'id': type });
        });
        valid_products++;
      }

      let categories = $('#product-category').val();
      if (categories && categories.length > 0) {
        categories.forEach(category => {
          h.product_categories.push({ 'id': category });
        });
        valid_products++;
      }

      let brands = $('#product-brand').val();
      if (brands && brands.length > 0) {
        brands.forEach(brand => {
          h.product_brands.push({ 'id': brand });
        });
        valid_products++;
      } 
    }

    if (valid_products == 0) {
      swal('ข้อผิดพลาด', 'กรุณาเลือกสินค้าอย่างน้อย 1 รายการ', 'error');
      return false;
    }       
  }

  if(h.type === 'F' || h.type === 'P') {
    if ($('.chk-exclude').length > 0) {
      $('.chk-exclude').each(function () {
        let id = $(this).data('id');
        let code = $(this).data('code');        
        h.exclude_products.push({ 'id': id, 'code': code });
      });      
    }
  }

  if (h.type == 'F') {
    if(h.premium_qty <= 0) {
      swal('ข้อผิดพลาด', 'กรุณากรอกจำนวนสินค้าพรีเมี่ยมให้ถูกต้อง', 'error');
      return false;
    }

    if ($('.premium-price').length > 0) {
      $('.premium-price').each(function () {
        let id = $(this).data('id');
        let code = $(this).data('code');
        let sell_price = parseDefaultFloat($(this).val(), 0.00);
        h.premiums.push({ 'id': id, 'code': code, 'sell_price': sell_price });
      });

      valid_premiums++;
    }

    if (valid_premiums == 0) {
      swal('ข้อผิดพลาด', 'กรุณาเลือกสินค้าพรีเมี่ยมอย่างน้อย 1 รายการ', 'error');
      return false;
    }
  }

  if (h.all_customers == 0) {
    if ($('.chk-customer').length > 0) {
      $('.chk-customer').each(function () {
        let id = $(this).data('id');
        let code = $(this).data('code');
        let name = $(this).data('name');
        h.customers.push({ 'id': id, 'code': code, 'name': name });
      });
      valid_customers++;
    }

    let teams = $('#sales-team').val();
    if (teams && teams.length > 0) {
      teams.forEach(team => {
        h.sales_teams.push({ 'id': team });
      });
      valid_customers++;
    }

    let groups = $('#customer-group').val();
    if (groups && groups.length > 0) {
      groups.forEach(group => {
        h.customer_groups.push({ 'id': group });
      });
      valid_customers++;
    }

    let types = $('#customer-type').val();
    if (types && types.length > 0) {
      types.forEach(type => {
        h.customer_types.push({ 'id': type });
      });
      valid_customers++;
    }

    let areas = $('#customer-area').val();
    if (areas && areas.length > 0) {
      areas.forEach(area => {
        h.customer_areas.push({ 'id': area });
      });
      valid_customers++;
    }

    let grades = $('#customer-grade').val();
    if (grades && grades.length > 0) {
      grades.forEach(grade => {
        h.customer_grades.push({ 'id': grade });
      });
      valid_customers++;
    }

    if (valid_customers == 0) {
      swal('ข้อผิดพลาด', 'กรุณาเลือกลูกค้าอย่างน้อย 1 รายการ', 'error');
      return false;
    }
  }

  if (h.all_channels == 0) {
    let channels = $('#channels').val();
    if (channels && channels.length > 0) {
      channels.forEach(channel => {
        h.channels.push({ 'id': channel });
      });
    }
    else {
      swal('ข้อผิดพลาด', 'กรุณาเลือกช่องทางขายอย่างน้อย 1 ช่องทาง', 'error');
      return false;
    }
  }

  if (h.all_payments == 0) {
    let payments = $('#payments').val();
    if (payments && payments.length > 0) {
      payments.forEach(payment => {
        h.payments.push({ 'id': payment });
      });
    }
    else {
      swal('ข้อผิดพลาด', 'กรุณาเลือกช่องทางชำระเงินอย่างน้อย 1 ช่องทาง', 'error');
      return false;
    }
  }
    
  load_in();

  $.ajax({
    url: `${HOME}add`,
    type: 'POST',
    cache: false,
    data: { "data": JSON.stringify(h) },
    success: function (rs) {
      load_out();

      if (isJson(rs)) {
        let ds = JSON.parse(rs);
        if (ds.status == 'success') {
          swal({
            title: 'Success',
            type: 'success',
            timer: 1000
          });

          setTimeout(() => {
            edit(ds.id);
          }, 1200);
        }
        else {
          showError(ds.message);
        }
      }
      else {
        showError(rs);
      }
    }
  });
}

function update() {
  const id = $('#rule_id').val();
  const conditionType = $('#condition-type').val();
  const h = {
    'id' : id,
    'name': $('#name').val().trim(),
    'type': conditionType,
    'id_policy': $('#policy').val(),
    'active': $('#active').is(':checked') ? 1 : 0,
    'all_products': conditionType === 'N' ? 0 : $('#all-product').val(),
    'all_customers': $('#all-customer').val(),
    'all_channels': $('#all-channels').val(),
    'all_payments': $('#all-payments').val(),
    'premium_qty': parseDefaultFloat($('#premium-qty').val(), 0.00),
    'min_qty': $('#min-qty').val(),
    'min_amount': $('#min-amount').val(),
    'priority': $('#priority').val(),
    'can_group': $('#can-group').val(),
    'premiums': [],
    'discount': [],
    'products': [],
    'models': [],
    'product_types': [],
    'product_categories': [],
    'product_brands': [],
    'exclude_products': [],
    'customers': [],
    'sales_teams': [],
    'customer_groups': [],
    'customer_types': [],
    'customer_areas': [],
    'customer_grades': [],
    'channels': [],
    'payments': []
  };

  if (h.name.length === 0) {
    swal('ข้อผิดพลาด', 'กรุณาระบุชื่อเงื่อนไข', 'error');
    return false;
  }

  let valid_products = h.all_products == 1 ? 1 : 0; //-- to check if there is at least 1 product in the list or if all_products is checked
  let valid_customers = h.all_customers == 1 ? 1 : 0; //-- to check if there is at least 1 customer in the list or if all_customers is checked
  let valid_channels = h.all_channels == 1 ? 1 : 0; //-- to check if there is at least 1 channel in the list or if all_channels is checked
  let valid_payments = h.all_payments == 1 ? 1 : 0; //-- to check if there is at least 1 payment in the list or if all_payments is checked
  let valid_premiums = 0; //-- to check if there is at least 1 premium in the list

  //--- disacount step
  if (h.type === 'P') {
    let disc1 = parseDefaultFloat($('#disc-1').val(), 0.00);

    if (disc1 <= 0) {
      swal('ข้อผิดพลาด', 'กรุณากรอกส่วนลดให้ถูกต้อง', 'error');
      return false;
    }

    const isValidDiscount = validateDiscount();
    if (!isValidDiscount) {
      swal('ข้อผิดพลาด', 'กรุณากรอกส่วนลดให้ถูกต้อง', 'error');
      return false;
    }

    for (let i = 1; i <= 5; i++) {
      let disc = parseDefaultFloat($(`#disc-${i}`).val(), 0.00);
      h.discount.push({ 'step': i, 'discount': disc });
    }
  }

  if ($('#valid-min-qty').val() == 0) {
    swal('ข้อผิดพลาด', 'กรุณากรอกจำนวนขั้นต่ำให้ถูกต้อง', 'error');
    return false;
  }

  if ($('#valid-min-amount').val() == 0) {
    swal('ข้อผิดพลาด', 'กรุณากรอกมูลค่าขั้นต่ำให้ถูกต้อง', 'error');
    return false;
  }

  if (h.type == 'N') {
    if ($('.net-price').length > 0) {
      $('.net-price').each(function () {
        let id = $(this).data('id');
        let code = $(this).data('code');
        let sell_price = parseDefaultFloat($(this).val(), 0.00);
        h.products.push({ 'id': id, 'code': code, 'sell_price': sell_price });
      });

      valid_products++;
    }
  }

  if (h.all_products == 0) {
    if (h.type === 'P' || h.type === 'F') {
      if ($('.chk-include').length > 0) {
        $('.chk-include').each(function () {
          let id = $(this).data('id');
          let code = $(this).data('code');
          let sell_price = $(this).data('sellprice');
          h.products.push({ 'id': id, 'code': code, 'sell_price': sell_price });
        });
        valid_products++;
      }

      let models = $('#product-model').val();
      if (models && models.length > 0) {
        models.forEach(model => {
          h.models.push({ 'id': model });
        });
        valid_products++;
      }

      let types = $('#product-type').val();
      if (types && types.length > 0) {
        types.forEach(type => {
          h.product_types.push({ 'id': type });
        });
        valid_products++;
      }

      let categories = $('#product-category').val();
      if (categories && categories.length > 0) {
        categories.forEach(category => {
          h.product_categories.push({ 'id': category });
        });
        valid_products++;
      }

      let brands = $('#product-brand').val();
      if (brands && brands.length > 0) {
        brands.forEach(brand => {
          h.product_brands.push({ 'id': brand });
        });
        valid_products++;
      }
    }

    if (valid_products == 0) {
      swal('ข้อผิดพลาด', 'กรุณาเลือกสินค้าอย่างน้อย 1 รายการ', 'error');
      return false;
    }
  }

  if (h.type === 'F' || h.type === 'P') {
    if ($('.chk-exclude').length > 0) {
      $('.chk-exclude').each(function () {
        let id = $(this).data('id');
        let code = $(this).data('code');
        h.exclude_products.push({ 'id': id, 'code': code });
      });
    }
  }

  if (h.type == 'F') {
    if (h.premium_qty <= 0) {
      swal('ข้อผิดพลาด', 'กรุณากรอกจำนวนสินค้าพรีเมี่ยมให้ถูกต้อง', 'error');
      return false;
    }

    if ($('.premium-price').length > 0) {
      $('.premium-price').each(function () {
        let id = $(this).data('id');
        let code = $(this).data('code');
        let sell_price = parseDefaultFloat($(this).val(), 0.00);
        h.premiums.push({ 'id': id, 'code': code, 'sell_price': sell_price });
      });

      valid_premiums++;
    }

    if (valid_premiums == 0) {
      swal('ข้อผิดพลาด', 'กรุณาเลือกสินค้าพรีเมี่ยมอย่างน้อย 1 รายการ', 'error');
      return false;
    }
  }

  if (h.all_customers == 0) {
    if ($('.chk-customer').length > 0) {
      $('.chk-customer').each(function () {
        let id = $(this).data('id');
        let code = $(this).data('code');
        let name = $(this).data('name');
        h.customers.push({ 'id': id, 'code': code, 'name': name });
      });
      valid_customers++;
    }

    let teams = $('#sales-team').val();
    if (teams && teams.length > 0) {
      teams.forEach(team => {
        h.sales_teams.push({ 'id': team });
      });
      valid_customers++;
    }

    let groups = $('#customer-group').val();
    if (groups && groups.length > 0) {
      groups.forEach(group => {
        h.customer_groups.push({ 'id': group });
      });
      valid_customers++;
    }

    let types = $('#customer-type').val();
    if (types && types.length > 0) {
      types.forEach(type => {
        h.customer_types.push({ 'id': type });
      });
      valid_customers++;
    }

    let areas = $('#customer-area').val();
    if (areas && areas.length > 0) {
      areas.forEach(area => {
        h.customer_areas.push({ 'id': area });
      });
      valid_customers++;
    }

    let grades = $('#customer-grade').val();
    if (grades && grades.length > 0) {
      grades.forEach(grade => {
        h.customer_grades.push({ 'id': grade });
      });
      valid_customers++;
    }

    if (valid_customers == 0) {
      swal('ข้อผิดพลาด', 'กรุณาเลือกลูกค้าอย่างน้อย 1 รายการ', 'error');
      return false;
    }
  }

  if (h.all_channels == 0) {
    let channels = $('#channels').val();
    if (channels && channels.length > 0) {
      channels.forEach(channel => {
        h.channels.push({ 'id': channel });
      });
    }
    else {
      swal('ข้อผิดพลาด', 'กรุณาเลือกช่องทางขายอย่างน้อย 1 ช่องทาง', 'error');
      return false;
    }
  }

  if (h.all_payments == 0) {
    let payments = $('#payments').val();
    if (payments && payments.length > 0) {
      payments.forEach(payment => {
        h.payments.push({ 'id': payment });
      });
    }
    else {
      swal('ข้อผิดพลาด', 'กรุณาเลือกช่องทางชำระเงินอย่างน้อย 1 ช่องทาง', 'error');
      return false;
    }
  }

  load_in();

  $.ajax({
    url: `${HOME}update`,
    type: 'POST',
    cache: false,
    data: { "data": JSON.stringify(h) },
    success: function (rs) {
      load_out();

      if(rs.trim() === 'success') {
        swal({
          title: 'Updated',
          type: 'success',
          timer: 1000
        });
      }
      else {
        showError(rs);
      }
    },
    error:function(rs) {
      showError(rs);
    } 
  });
}

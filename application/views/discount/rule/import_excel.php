<!-- Import SKU file -->
<script>
  function getSkuFile() {
    $('#sku-file').click();
  }

  function clearImportFile() {
    document.getElementById('sku-file').value = '';
  }

  const skuFile = document.getElementById('sku-file');

  skuFile.addEventListener('change', function() {
    if (this.files.length > 0) {
      const file = this.files[0];
      if (file.size > 5000000) {
        swal("ขนาดไฟล์ใหญ่เกินไป", "ไฟล์แนบต้องมีขนาดไม่เกิน 5 MB", "error");
        this.value = '';
        return false;
      }

      getSkuList();
    }
  });

  function getSkuList() {
    const file = skuFile.files[0];

    if (!file) {
      swal("กรุณาเลือกไฟล์ก่อน", "", "warning");
      return false;
    }

    const fd = new FormData();
    fd.append('uploadFile', file);

    load_in();

    $.ajax({
      url: `${HOME}get_sku_list`,
      type: "POST",
      cache: false,
      data: fd,
      processData: false,
      contentType: false,
      success: function(rs) {
        load_out();
        if (isJson(rs)) {
          const res = JSON.parse(rs);
          if (res.status === 'success') {
            const skus = res.data;
            skus.forEach(sku => {
              addIncludeProduct(sku);
            });
            clearImportFile();
          } else {
            showError(res.message);
          }
        } else {
          showError(rs);
        }
      }
    })
  }
</script>

<!-- Import Net Price file -->
<script>
  function getNetPriceFile() {
    $('#net-price-file').click();
  }

  function clearImportNetPriceFile() {
    document.getElementById('net-price-file').value = '';
  }

  const netPriceFile = document.getElementById('net-price-file');

  netPriceFile.addEventListener('change', function() {
    if (this.files.length > 0) {
      const file = this.files[0];
      if (file.size > 5000000) {
        swal("ขนาดไฟล์ใหญ่เกินไป", "ไฟล์แนบต้องมีขนาดไม่เกิน 5 MB", "error");
        this.value = '';
        return false;
      }

      getNetPriceList();
    }
  });

  function getNetPriceList() {
    const file = netPriceFile.files[0];

    if (!file) {
      swal("กรุณาเลือกไฟล์ก่อน", "", "warning");
      return false;
    }

    const fd = new FormData();
    fd.append('uploadFile', file);

    load_in();

    $.ajax({
      url: `${HOME}get_sku_list`,
      type: "POST",
      cache: false,
      data: fd,
      processData: false,
      contentType: false,
      success: function(rs) {
        load_out();
        if (isJson(rs)) {
          const res = JSON.parse(rs);
          if (res.status === 'success') {
            const skus = res.data;
            skus.forEach(sku => {
              addSkuNetPriceItem(sku);
            });
            clearImportNetPriceFile();
          } else {
            showError(res.message);
          }
        } else {
          showError(rs);
        }
      }
    })
  }
</script>

<!-- Import Customer file -->
<script>
  function getCustomerFile() {
    $('#customer-file').click();
  }

  function clearCustomerFile() {
    document.getElementById('customer-file').value = '';
  }

  const customerFile = document.getElementById('customer-file');

  customerFile.addEventListener('change', function() {
    if (this.files.length > 0) {
      const file = this.files[0];
      if (file.size > 5000000) {
        swal("ขนาดไฟล์ใหญ่เกินไป", "ไฟล์แนบต้องมีขนาดไม่เกิน 5 MB", "error");
        this.value = '';
        return false;
      }

      getCustomerList();
    }
  });

  function getCustomerList() {
    const file = customerFile.files[0];

    if (!file) {
      swal("กรุณาเลือกไฟล์ก่อน", "", "warning");
      return false;
    }

    const fd = new FormData();
    fd.append('uploadFile', file);

    load_in();

    $.ajax({
      url: `${HOME}get_customer_list`,
      type: "POST",
      cache: false,
      data: fd,
      processData: false,
      contentType: false,
      success: function(rs) {
        load_out();
        if (isJson(rs)) {
          const res = JSON.parse(rs);
          if (res.status === 'success') {
            const customers = res.data;
            customers.forEach(customer => {
              addCustomer(customer);
            });

            clearCustomerFile();
          } else {
            showError(res.message);
          }
        } else {
          showError(rs);
        }
      }
    })
  }
</script>

<!-- import Premium file -->
<script>
  function getPremiumFile() {
    $('#premium-file').click();
  }

  function clearPremiumFile() {
    document.getElementById('premium-file').value = '';
  }

  const premiumFile = document.getElementById('premium-file');

  premiumFile.addEventListener('change', function() {
    if (this.files.length > 0) {
      const file = this.files[0];
      if (file.size > 5000000) {
        swal("ขนาดไฟล์ใหญ่เกินไป", "ไฟล์แนบต้องมีขนาดไม่เกิน 5 MB", "error");
        this.value = '';
        return false;
      }

      getPremiumList();
    }
  });

  function getPremiumList() {
    const file = premiumFile.files[0];

    if (!file) {
      swal("กรุณาเลือกไฟล์ก่อน", "", "warning");
      return false;
    }

    const fd = new FormData();
    fd.append('uploadFile', file);

    load_in();

    $.ajax({
      url: `${HOME}get_sku_list`,
      type: "POST",
      cache: false,
      data: fd,
      processData: false,
      contentType: false,
      success: function(rs) {
        load_out();
        if (isJson(rs)) {
          const res = JSON.parse(rs);
          if (res.status === 'success') {
            const premiums = res.data;
            premiums.forEach(premium => {
              addPremiumProduct(premium);
            });

            clearPremiumFile();
          } else {
            showError(res.message);
          }
        } else {
          showError(rs);
        }
      }
    })
  }
</script>
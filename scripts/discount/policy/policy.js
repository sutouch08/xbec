const addNew = () => {
  window.location.href = `${HOME}add_new`;
}

const edit = (id) => {
  window.location.href = `${HOME}edit/${id}`;
}

const viewDetail = (id) => {
  window.location.href = `${HOME}view_detail/${id}`;
}

$('#start-date').datepicker({
  dateFormat:'dd-mm-yy',
  onClose:function(sd){
    $('#end-date').datepicker('option', 'minDate', sd);
  }
});

$('#end-date').datepicker({
  dateFormat:'dd-mm-yy',
  onClose:function(sd){
    $('#start-date').datepicker('option', 'maxDate', sd);
  }
});

function setActive(id, el) {
  let active = $(el).prop('checked') ? 1 : 0;
  $.ajax({
    url: `${HOME}set_active`,
    type: 'POST',
    cache: false,
    data: {
      id: id,
      active: active
    },
    success: function(rs) {
     if(rs.trim() !== 'success') {
      showError(rs);
      $(el).prop('checked', !active);
     }
    },
    error: function(rs) {
      showError(rs);
      $(el).prop('checked', !active);
    }
  });
}

function confirmDelete(id, name) {
  swal({
    title: "คุณแน่ใจ ?",
    text: "ต้องการลบ '" + name + "' หรือไม่ ?",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#FA5858",
    confirmButtonText: 'ใช่, ฉันต้องการลบ',
    cancelButtonText: 'ยกเลิก',
    closeOnConfirm: true
  }, function () {
    load_in();
    setTimeout(() => {
      $.ajax({
        url: `${HOME}delete`,
        type: 'POST',
        cache: 'false',
        data: {
          'id' : id
        },
        success: function (rs) {
          load_out();
          if (rs === 'success') {
            swal({
              title: 'Deleted',
              type: 'success',
              timer: 1000
            });
            $("#row-" + id).remove();
            reIndex();
          }
          else {
            swal({
              title: 'Error!',
              text: rs,
              type: 'error',
              html: true
            })
          }
        }
      });
    }, 200);
  });
}  

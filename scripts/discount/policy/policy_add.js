
function add() {
  clearErrorByClass('r');

  let h = {
    'name' : $('#name').val().trim(),
    'start_date' : $('#start-date').val(),
    'end_date' : $('#end-date').val()
  };

  if(h.name.length == 0) {
    $('#name').hasError();
    swal('กรุณาระบุ Promotion description');
    return false;
  }

  if(!isDate(h.start_date)) {
    $('#start-date').hasError();
    swal('วันที่เริ่มต้นไม่ถูกต้อง');
    return false;
  }

  if(!isDate(h.end_date)) {
    $('#end-date').hasError();
    swal('วันที่สิ้นสุดไม่ถูกต้อง');
    return false;
  }

  load_in();

  $.ajax({
    url:`${HOME}add`,
    type:'POST',
    cache:false,
    data:{
      'data' : JSON.stringify(h)
    },
    success:function(rs) {
      load_out();
      if(isJson(rs)) {
        let ds = JSON.parse(rs);
        if(ds.status === 'success') {
          edit(ds.id);
        }
        else {
          showError(ds.message);
        }
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

function getActiveRuleList() {
  load_in();
  $.ajax({
    url: `${HOME}get_active_rule`,
    type: 'GET',
    cache: 'false',
    success: function (rs) {
      load_out();
      if(isJson(rs)) {
        ds = JSON.parse(rs);
        source = $('#rule-template').html();
        output = $('#rule-table');

        render(source, ds, output);
        showRuleList();
      }      
    }
  });
}

function toggleCheckRuleAll() {
  if($('#chk-all').is(':checked')) {
    $('.chk-rule').prop('checked', true);
  }
  else {
    $('.chk-rule').prop('checked', false);
  }
}

function toggleRmCheckAll() {
  if($('#rm-chk-all').is(':checked')) {
    $('.rm-chk').prop('checked', true);
  }
  else {
    $('.rm-chk').prop('checked', false);
  }
}

function addRule() {
  if($('.chk-rule:checked').size() > 0) {
    let h = {
      'id' : $('#id-policy').val(),
      'rules' : []
    };

    $('.chk-rule:checked').each(function() {
      h.rules.push({'id' : $(this).val()});
    });

    if(h.rules.length == 0) {
      swal('กรุณาเลือกรายการอย่างน้อย 1 รายการ');
      return false;
    }

    $('#rule-modal').modal('hide');

    load_in();

    $.ajax({
      url: `${HOME}add_rules`,
      type: 'POST',
      cache: 'false',
      data: {
        'data' : JSON.stringify(h)
      },
      success: function (rs) {
        load_out();

        if(rs === 'success') {
          swal({
            title:'Success',
            type:'success',
            timer:1000
          });

          setTimeout(function() {
            window.location.reload();
          }, 1200);
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
}

function showRuleList(){
  $('#rule-modal').modal('show');
}

function viewRuleDetail(id_rule){
  const url = `${HOME}view_rule_detail/${id_rule}?nomenu&nonavbar`;
  const width = 1200;
  const height = 800;
  const left = (window.screen.width - width) / 2;
  const top = (window.screen.height - height) / 2;

  window.open(url, '_blank', `width=${width},height=${height},left=${left},top=${top},location=no,scrollbars=yes`);
}

function removeCheckedRules() {
  if($('.rm-chk:checked').size() > 0) {
    let h = {
      'id' : $('#id-policy').val(),
      'rules' : []
    };

    $('.rm-chk:checked').each(function() {
      h.rules.push({'id' : $(this).val()});
    });

    swal({
      title: "คุณแน่ใจ ?",
      text: "ต้องการลบรายการที่เลือกออกจาก Promotion หรือไม่ ?",
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: "#FA5858",
      confirmButtonText: 'ใช่, ฉันต้องการลบ',
      cancelButtonText: 'ยกเลิก',
      closeOnConfirm: true
    }, function() {
      load_in();

      setTimeout(() => {
        $.ajax({
          url: `${HOME}remove_rules`,
          type: 'POST',
          cache: 'false',
          data: {
            'data' : JSON.stringify(h)
          },
          success: function (rs) {
            load_out();

            if(rs === 'success') {
              swal({
                title:'Success',
                type:'success',
                timer:1000
              });

              setTimeout(function() {
                window.location.reload();
              }, 1200);
            }
            else {
              showError(rs);
            }
          },
          error: function (rs) {
            showError(rs);
          }
        });
      }, 100);
    });
  }
}

function update() {
  clearErrorByClass('r');

  let h = {
    'id' : $('#id-policy').val(),
    'name' : $('#name').val().trim(),
    'start_date' : $('#start-date').val(),
    'end_date' : $('#end-date').val(),
    'active' : $('#active').length ? ($('#active').is(':checked') ? 1 : 0) : 0
  }

  if(h.name.length == 0) {
    $('#name').hasError();
    swal('กรุณาระบุ Promotion description');
    return false;
  }

  if(!isDate(h.start_date)) {
    $('#start-date').hasError();
    swal('วันที่เริ่มต้นไม่ถูกต้อง');
    return false;
  }

  if(!isDate(h.end_date)) {
    $('#end-date').hasError();
    swal('วันที่สิ้นสุดไม่ถูกต้อง');
    return false;
  }

  load_in();

	$.ajax({
		url: `${HOME}update`,
		type:'POST',
		cache:false,
		data: {
			'data' : JSON.stringify(h)
		},
		success:function(rs) {
			load_out();

			if(rs.trim() === 'success') {
				swal({
					title:'Success',
					type:'success',
					timer:1000
				});

				setTimeout(function(rs) {
					window.location.reload();
				}, 1200);
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
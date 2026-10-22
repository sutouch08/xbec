const viewDetail = (id, pageNo = 0) => {
  window.location.href = `${HOME}view_detail/${id}/${pageNo}`;
}

function getDelete(code, name) {
  swal({
    title:'Are you sure ?',
    text:'Do you really want to delete ' + code + ' : ' + name + '<br/>' + 'This action cannot be undone. Do you want to process ?',
    type:'warning',
    html:true,
    showCancelButton:true,
    confirmButtonText:'Yes',
    cancelButtonText:'No',
    confirmButtonColor:'#d15b47',
    closeOnConfirm:true
  }, function() {
    load_in();

    setTimeout(() => {
      $.ajax({
        url:`${HOME}delete`,
        type:'POST',
        cache:false,
        data:{
          'code' : code
        },
        success:function(rs) {
          load_out();

          if(rs == 'success') {
            swal({
              title:'Success',
              type:'success',
              timer:1000
            });

            setTimeout(() => {
              window.location.reload();
            }, 1200);
          }
          else {
            swal({
              title:'Error!',
              text:rs,
              type:'error'
            })
          }
        }
      })
    }, 200);
  })
}
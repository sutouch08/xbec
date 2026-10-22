<?php $this->load->view('include/header'); ?>
<div class="row">
  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 padding-5">
    <h4 class="title"><?php echo $this->title; ?></h4>
  </div>
  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 padding-5">
    <p class="pull-right top-p">
<?php if($this->pm->can_add) : ?>
      <button type="button" class="btn btn-sm btn-primary" onclick="getUploadFile()"><i class="fa fa-file-excel-o"></i> Import file</button>
<?php endif; ?>
<?php if($this->pm->can_edit) : ?>
      <button type="button" class="btn btn-sm btn-purple" onclick="getHeader()"><i class="fa fa-cogs"></i> Config</button>
<?php endif; ?>
<?php if($this->pm->can_delete) : ?>
        <button type="button" class="btn btn-sm btn-danger" onclick="getClearData()"><i class="fa fa-trasj"></i> Clear data</button>
<?php endif; ?>
    </p>
  </div>
</div>
<hr class="">
<?php $this->load->view('report/style_sheet'); ?>
<form id="searchForm" method="post" action="<?php echo current_url(); ?>">
  <div class="row">
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-6 padding-5">
      <label>Customer</label>
      <input type="text" class="form-control input-sm text-center search-box" name="customer" value="<?php echo $customer; ?>"/>
    </div>
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-6 padding-5">
      <label>Division</label>
      <select class="form-control input-sm filter" name="division">
        <option value="all">All</option>
        <?php echo $division_list; ?>
      </select>
    </div>

    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-6 padding-5">
      <label>Sales Emp</label>
      <select class="form-control input-sm filter" name="sales">
        <option value="all">All</option>
        <?php echo $sales_list; ?>
      </select>
    </div>

    <div class="col-lg-1-harf col-md-1-harf col-sm-1-harf col-xs-3 padding-5">
      <label class="display-block not-show">search</label>
      <button type="button" class="btn btn-xs btn-primary btn-block" onclick="getSearch()">Search</button>
    </div>
    <div class="col-lg-1-harf col-md-1-harf col-sm-1-harf col-xs-3 padding-5">
      <label class="display-block not-show">reset</label>
      <button type="button" class="btn btn-xs btn-warning btn-block" onclick="clearFilter()">Reset</button>
    </div>
  </div>
</form>
<hr class="margin-top-15">
<?php $arr = ['E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z']; ?>
<div class="row">
  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center padding-5">
    <h4><?php echo $header['title']; ?></h4>
  </div>
</div>
<div class="row">
  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 border-1" id="result-window" style="margin-left:5px; padding-left:0; padding-right:5px; overflow:auto;">
    <table class="table table-bordered tableFixHead" style="min-width:1770px; margin-bottom:5px;">
      <thead>
        <tr class="">
          <th class="fix-width-40 text-center fix-no fix-header" style="z-index:11;">#</th>
          <th class="fix-width-80 fix-code fix-header" style="z-index:11;"><?php echo $header['A']; ?></th>
          <th class="fix-width-350 fix-customer fix-header" style="z-index:11;"><?php echo $header['B']; ?></th>
          <th class="fix-width-100 fix-header"><?php echo $header['C']; ?></th>
          <th class="fix-width-100 fix-header"><?php echo $header['D']; ?></th>
      <?php foreach($arr as $key) : ?>
            <?php $key1 = $key."1"; ?>
          <?php if($header[$key1] == 'Y') : ?>
            <th class="fix-width-100 text-center fix-header"><?php echo $header[$key]; ?></th>
          <?php endif; ?>
      <?php endforeach; ?>
        </tr>
      </thead>
      <tbody>
<?php if( ! empty($data)) : ?>
  <?php $no = 1; ?>
  <?php foreach($data as $rs) : ?>
        <tr class="" style="font-size:11px;">
          <td class="hi text-center fix-no no" scope="row"><?php echo $no; ?></td>
          <td class="hi text-center fix-code" scope="row"><?php echo $rs->A; ?></td>
          <td class="hi fix-customer" scope="row"><?php echo $rs->B; ?></td>
          <td class="hi"><?php echo $rs->C; ?></td>
          <td class="hi"><?php echo $rs->D; ?></td>
          <?php foreach($arr as $key) : ?>
              <?php $key1 = $key."1"; ?>
              <?php if($header[$key1] == 'Y') : ?>
                <td class="hi text-right"><?php echo number($rs->$key, 2); ?></td>
              <?php endif; ?>
          <?php endforeach; ?>
        </tr>
    <?php $no++; ?>
  <?php endforeach; ?>
<?php else : ?>
      <tr>
        <td colspan="20" height="150px;" class="font-size-24 middle text-center"> --- NO DATA FOUND --- </td>
      </tr>
<?php endif; ?>
      </tbody>
    </table>
  </div>
</div>


<div class="modal fade" id="header-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
 <div class="modal-dialog" style="max-width:90vw;">
   <div class="modal-content">
     <div class="modal-header">
       <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
       <h4 class="modal-title text-center">กำหนดชื่อหัวตาราง</h4>
     </div>
     <div class="modal-body">
       <form id="header-form" method="post" action="<?php echo $this->home; ?>/update_header">
         <div class="row">
           <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="max-height:400px; overflow:auto;">
             <table class="table table-bordered">
               <thead>
                 <tr>
                   <th class="width-15 text-center">คอลัมภ์</th>
                   <th class="width-70 text-center">ชื่อ</th>
                   <th class="width-15 text-center">แสดงผล</th>
                 </tr>
               </thead>
               <tbody>
                 <tr>
                   <td class="middle text-center">Title</td>
                   <td colspan="2">
                     <input type="text" class="form-control input-sm" name="title" maxlength="250" value="<?php echo $header['title']; ?>" placeholder="Limit 250 digit"/>
                   </td>
                 </tr>
                 <tr>
                   <td class="middle text-center">A</td>
                   <td class="middle">
                     <input type="text" class="form-control input-sm" name="A" maxlength="100" value="<?php echo $header['A']; ?>" placeholder="Limit 100 digit"/>
                   </td>
                   <td class="middle text-center"><label><input type="checkbox" class="ace" name="A1" value="Y" checked disabled /><span class="lbl"></span></label></td>
                 </tr>
                 <tr>
                   <td class="middle text-center">B</td>
                   <td class="middle"><input type="text" class="form-control input-sm" name="B" maxlength="100" value="<?php echo $header['B']; ?>" placeholder="Limit 100 digit"/></td>
                   <td class="middle text-center"><label><input type="checkbox" class="ace" name="B1" value="Y" checked disabled /><span class="lbl"></span></label></td>
                 </tr>
                 <tr>
                   <td class="middle text-center">C</td>
                   <td class="middle"><input type="text" class="form-control input-sm" name="C" maxlength="100" value="<?php echo $header['C']; ?>" placeholder="Limit 100 digit"/></td>
                   <td class="middle text-center"><label><input type="checkbox" class="ace" name="C1" value="Y" checked disabled /><span class="lbl"></span></label></td>
                 </tr>
                 <tr>
                   <td class="middle text-center">D</td>
                   <td class="middle"><input type="text" class="form-control input-sm" name="D" maxlength="100" value="<?php echo $header['D']; ?>" placeholder="Limit 100 digit"/></td>
                   <td class="middle text-center"><label><input type="checkbox" class="ace" name="D1" value="Y" checked disabled /><span class="lbl"></span></label></td>
                 </tr>
                 <?php foreach($arr as $key) : ?>
                     <?php $key1 = $key."1"; ?>
                     <tr>
                       <td class="middle text-center"><?php echo $key; ?></td>
                       <td class="middle"><input type="text" class="form-control input-sm" name="<?php echo $key; ?>" maxlength="100" value="<?php echo $header[$key]; ?>" placeholder="Limit 100 digit" /></td>
                       <td class="middle text-center">
                         <label>
                           <input type="checkbox" class="ace" onchange="toggleValue($(this), '<?php echo $key; ?>1')" <?php echo is_checked('Y', $header[$key1]); ?> />
                           <span class="lbl"></span>
                         </label>
                         <input type="hidden" id="<?php echo $key; ?>1" name="<?php echo $key; ?>1" value="<?php echo $header[$key1]; ?>" />
                       </td>
                     </tr>
                 <?php endforeach; ?>
               </tbody>
             </table>
           </div>
         </div>
       </form>
     </div>
     <div class="modal-footer">
       <button type="button" class="btn btn-sm btn-success btn-100" onclick="updateHeader()">บันทึก</button>
     </div>
   </div>
 </div>
</div>


<div class="modal fade" id="upload-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
 <div class="modal-dialog" style="max-width:400px;">
   <div class="modal-content">
       <div class="modal-header">
       <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
       <h4 class="modal-title text-center">Import Excel File</h4>
      </div>
      <div class="modal-body">
        <form id="upload-form" name="upload-form" method="post" enctype="multipart/form-data">
        <div class="row margin-left-0 margin-right-0">
          <div class="form-group">
            <div class="col-xs-12">
              <!-- #section:custom/file-input -->
              <label class="ace-file-input">
                <input type="file" name="uploadFile" id="uploadFile" accept=".xlsx">
                <span class="ace-file-container" data-title="Choose">
                  <span class="ace-file-name" id="show-file-name" >
                    <i class="ace-icon fa fa-upload"></i> No File..
                  </span>
                </span>
                <a class="remove hide" id="btn-remove" href="#" style="margin-right:5px;"><i class=" ace-icon fa fa-times"></i></a>
              </label>
            </div>
          </div>
          <div class="divider-hidden"></div>
          <div class="divider-hidden"></div>
          <div class="divider-hidden"></div>

          <div class="form-group">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right" style="padding-right:20px;">
              <button type="button" class="btn btn-sm btn-primary btn-100" onclick="uploadfile()"><i class="fa fa-cloud-upload"></i> Upload</button>
            </div>
          </div>
        </div>
        <input type="hidden" name="555" />
        </form>
       </div>
   </div>
 </div>
</div>
<script>

window.addEventListener('load', () => {
  let height = $(window).height();
  let pageContentHeight = height - (121);
  let windowHeight = pageContentHeight - 172;

  $('.page-content').css('height', pageContentHeight + "px");
  $('#result-window').css('height', windowHeight + "px" );

  $('.hi').click(function() {
    if( $(this).parent().hasClass('focus'))
    {
      $(this).parent().removeClass('focus');
    }
    else
    {
      $(this).parent().addClass('focus');
    }
  })
});

function getUploadFile(){
  $('#upload-modal').modal('show');
}

function getFile(){
  $('#uploadFile').click();
}


$("#uploadFile").change(function() {
  var fIcon = '<i class="ace-icon fa fa-file"></i>';
  var nIcon = '<i class="ace-icon fa fa-upload"></i>';

	if($(this).val() != '')
	{
		var file 		= this.files[0];
		var name		= file.name;
		var type 		= file.type;
		var size		= file.size;

		if( size > 5000000 )
		{
			swal("ขนาดไฟล์ใหญ่เกินไป", "ไฟล์แนบต้องมีขนาดไม่เกิน 5 MB", "error");
			$(this).val('');
			return false;
		}
		//readURL(this);
    $('.ace-file-container').addClass('selected');
    $('.ace-file-container').data('title', 'Change');
    $('#show-file-name').html(fIcon + name);
    $('#btn-remove').removeClass('hide');
	}
  else
  {
    $('.ace-file-container').removeClass('selected');
    $('#show-file-name').html(nIcon + 'No File..');
    $('#btn-remove').addClass('hide');
  }
});

$('#btn-remove').click(function() {
  var nIcon = '<i class="ace-icon fa fa-upload"></i>';
  $('#uploadFile').val('');
  $('.ace-file-container').removeClass('selected');
  $('#show-file-name').html(nIcon + 'No File..');
  $('#btn-remove').addClass('hide');
});



	function uploadfile()
	{
    $('#upload-modal').modal('hide');

		var file	= $("#uploadFile")[0].files[0];
		var fd = new FormData();
		fd.append('uploadFile', $('input[type=file]')[0].files[0]);
		if( file !== '')
		{
			load_in();
			$.ajax({
				url:HOME + 'upload',
				type:"POST",
        cache:"false",
        data: fd,
        processData:false,
        contentType: false,
				success: function(rs){
					load_out();
					var rs = $.trim(rs);
          if(rs === 'success'){
            swal({
              title: 'Success',
              text : rs,
              type: 'success',
              html:true,
              timer:1000
            });

            setTimeout(function(){
              window.location.reload();
            }, 1200);
          }else{
            swal({
              title:'Error!!',
              text:rs,
              type:'error'
            });
          }
				}
			});
		}
	}


  function goBack() {
    window.location.href = HOME;
  }

  function getHeader() {
    $('#header-modal').modal('show');
  }

  function updateHeader() {
    $('#header-form').submit();
  }

  function toggleValue(el, id) {
    if(el.is(':checked')) {
      $('#'+id).val('Y');
    }
    else {
      $('#'+id).val('N');
    }
  }

  function getClearData() {
    swal({
      title:'Are you sure ?',
      text:'คุณต้องการลบข้อมูลที่นำเข้าทั้งหมดหรือไม่ ?',
      type:'warning',
      showCancelButton:true,
      confirmButtonColor:'#d15b47',
      confirmButtonText:'ยืนยัน',
      cancelButtonText:'ยกเลิก',
      closeOnConfirm:true
    }, function() {
      load_in();

      setTimeout(() => {
        $.ajax({
          url:HOME + 'clear_data',
          type:'POST',
          cache:false,
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
                type:'error',
                html:true
              })
            }
          }
        })
      }, 200);
    })
  }

</script>
<?php $this->load->view('include/footer'); ?>

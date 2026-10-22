// JavaScript Document

//----------------  Dropzone --------------------//
Dropzone.autoDiscover = false;
var myDropzone = new Dropzone("#imageForm", {
	url: BASE_URL,
	paramName: "file", // The name that will be used to transfer the file
	maxFilesize: 2, // MB
	uploadMultiple: true,
	maxFiles: 5,
	acceptedFiles: "image/*",
	parallelUploads: 5,
	autoProcessQueue: false,
	addRemoveLinks: true
});

myDropzone.on('complete', function(){
	clearUploadBox();
	//loadImageTable();
  window.location.reload();
});

function doUpload()
{
	return true; //myDropzone.processQueue();
}

function clearUploadBox()
{
	$("#uploadBox").modal('hide');
	myDropzone.removeAllFiles();
}

function showUploadBox()
{
	$("#uploadBox").modal('show');
}




function removeImage(id_img)
{
  swal({
		title: "คุณแน่ใจ ?",
		text: "ต้องการลบรูปภาพ หรือไม่ ?",
		type: "warning",
		showCancelButton: true,
		confirmButtonColor: "#FA5858",
		confirmButtonText: 'ใช่, ฉันต้องการลบ',
		cancelButtonText: 'ยกเลิก',
		closeOnConfirm: false
		}, function(){
			swal({
				title:'Deleted',
				text:'ลบรูปภาพเรียบร้อยแล้ว',
				type:'success',
				timer:1000
			});

			$("#img-"+id_img).remove();
	});
}



function showNewCover(id){
	$(".btn-cover").removeClass('btn-success');
	$("#btn-cover-"+id).addClass('btn-success');
}



function setAsCover(id_img)
{
	$(".btn-cover").removeClass('btn-success');
	$("#btn-cover-"+id_img).addClass('btn-success');
}

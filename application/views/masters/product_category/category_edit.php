<?php $this->load->view('include/header'); ?>
<div class="row">
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 padding-5">
		<h3 class="title"><?php echo $this->title; ?></h3>
	</div>
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 padding-5 text-right">
		<button type="button" class="btn btn-white btn-default" onclick="goBack('<?php echo $backUrl; ?>')"><i class="fa fa-arrow-left"></i>&nbsp; Back</button>
	</div>
</div>
<hr />

<div class="form-horizontal">
	<div class="row" style="margin-left:0px; margin-right:0px;">
		<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
			<div class="form-group margin-top-30">
				<label class="col-lg-3 col-md-3 col-sm-3 hidden-xs control-label no-padding-right">Code</label>
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-9">
					<label class="visible-xs">Code</label>
					<input type="text" class="form-control input-sm" value="<?php echo $code; ?>" disabled />
				</div>
				<div class="help-block col-xs-12 col-sm-reset inline red" id="code-error"></div>
			</div>

			<div class="form-group">
				<label class="col-lg-3 col-md-3 col-sm-3 hidden-xs control-label no-padding-right">Name</label>
				<div class="col-lg-5 col-md-3 col-sm-3 col-xs-9">
					<label class="visible-xs">Name</label>
					<input type="text" name="name" id="name" class="form-control input-sm" value="<?php echo $name; ?>" autofocus />
				</div>
				<div class="col-xs-3 visible-xs">
					<label>Level</label>
					<input type="text" class="form-control input-sm text-center" value="<?php echo $level; ?>" disabled />
				</div>
				<div class="help-block col-xs-12 col-sm-reset inline red" id="name-error"></div>
			</div>

			<div class="form-group hidden-xs">
				<label class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label no-padding-right">Level</label>
				<div class="col-lg-1 col-md-1 col-sm-1 col-xs-4">
					<input type="text" class="form-control input-sm text-center" value="<?php echo $level; ?>" disabled />
				</div>
			</div>

			<div class="form-group">
				<label class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label no-padding-right">Parent</label>
				<div class="col-lg-9 col-md-9 col-sm-9 col-xs-12" style="padding-top:8px;">
					<?php echo getCategoryTree($id); ?>
				</div>
			</div>

			<div class="divider-hidden"></div>
			<div class="divider-hidden"></div>
			<div class="divider-hidden"></div>

			<div class="form-group">
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 col-lg-offset-3 col-md-offset-3 col-sm-offset-3 hidden-xs">
					<button type="button" class="btn btn-white btn-success btn-100 btn-block-xs" onclick="update()">Update</button>
				</div>
			</div>

			<input type="hidden" id="id" value="<?php echo $id; ?>" />
			<input type="hidden" id="code" value="<?php echo $code; ?>" />
		</div>

		<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
			<div class="col-sm-12 col-xs-12 center">
				<span class="profile-picture">
					<img class="editable img-responsive" src="<?php echo get_category_path($code); ?>">
				</span>
			</div>
			<div class="divider-hidden"></div>


			<div class="col-sm-12 col-xs-12 center">
				<?php if ($this->pm->can_edit) : ?>
					<button type="button" class="btn btn-sm btn-primary" onclick="changeImage()">Upload image</button>
					<button type="button" class="btn btn-sm btn-danger" onclick="deleteImage(<?php echo $code; ?>)">Delete image</button>
				<?php endif; ?>
			</div>
		</div> <!-- end right column-->
	</div>
</div>

<div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">×</button>
				<h4 class="blue">Change Image</h4>
			</div>
			<form class="no-margin" id="imageForm">
				<div class="modal-body">
					<div style="width:75%;margin-left:12%;">
						<label id="btn-select-file" class="ace-file-input ace-file-multiple">
							<input type="file" name="image" id="image" accept="image/*" style="display:none;" />
							<span class="ace-file-container" data-title="Click to choose new Image">
								<span class="ace-file-name" data-title="No File ...">
									<i class=" ace-icon ace-icon fa fa-picture-o"></i>
								</span>
							</span>
						</label>
						<div id="block-image" style="opacity:0;">
							<div id="previewImg" class="width-100 center"></div>
							<span onClick="removeFile()" style="position:absolute; left:385px; top:1px; cursor:pointer; color:red;">
								<i class="fa fa-times fa-2x"></i>
							</span>
						</div>
					</div>
				</div>
				<div class="modal-footer center">
					<button type="button" class="btn btn-sm btn-success" onclick="doUpload()"><i class="ace-icon fa fa-check"></i> Submit</button>
					<button type="button" class="btn btn-sm" data-dismiss="modal"><i class="ace-icon fa fa-times"></i> Cancel</button>
				</div>
			</form>
		</div>
	</div>
</div>

<script>
	function changeImage() {
		$('#imageModal').modal('show');
	}

	function doUpload() {
		var id = $('#id').val();
		var code = $('#code').val();
		var image = $("#image")[0].files[0];

		if (image == '') {
			swal('ข้อผิดพลาด', 'ไม่สามารถอ่านข้อมูลรูปภาพที่แนบได้ กรุณาแนบไฟล์ใหม่อีกครั้ง', 'error');
			return false;
		}


		$("#imageModal").modal('hide');

		var fd = new FormData();
		fd.append('image', $('input[type=file]')[0].files[0]);
		fd.append('code', code);
		fd.append('id', id);

		load_in();

		$.ajax({
			url: HOME + 'change_image',
			type: "POST",
			cache: "false",
			data: fd,
			processData: false,
			contentType: false,
			success: function(rs) {
				load_out();
				var rs = $.trim(rs);
				if (rs == 'success') {
					swal({
						title: 'Success',
						type: 'success',
						timer: 1000
					});

					setTimeout(function() {
						window.location.reload();
					}, 1200);

				} else {
					swal("ข้อผิดพลาด", rs, "error");
				}
			},
			error: function(xhr, status, error) {
				load_out();
				swal({
					title: 'Error!',
					text: "Error-" + xhr.status + ": " + xhr.statusText,
					type: 'error'
				})
			}
		});
	}

	function readURL(input) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();
			reader.onload = function(e) {
				$('#previewImg').html('<img id="previewImg" src="' + e.target.result + '" width="200px" alt="รูปสินค้า" />');
			}
			reader.readAsDataURL(input.files[0]);
		}
	}

	$("#image").change(function() {
		if ($(this).val() != '') {
			var file = this.files[0];
			var name = file.name;
			var type = file.type;
			var size = file.size;
			if (file.type != 'image/png' && file.type != 'image/jpg' && file.type != 'image/gif' && file.type != 'image/jpeg') {
				swal("รูปแบบไฟล์ไม่ถูกต้อง", "กรุณาเลือกไฟล์นามสกุล jpg, jpeg, png หรือ gif เท่านั้น", "error");
				$(this).val('');
				return false;
			}

			if (size > 2000000) {
				swal("ขนาดไฟล์ใหญ่เกินไป", "ไฟล์แนบต้องมีขนาดไม่เกิน 2 MB", "error");
				$(this).val('');
				return false;
			}

			readURL(this);

			$("#btn-select-file").css("display", "none");
			$("#block-image").animate({
				opacity: 1
			}, 1000);
		}
	});


	function removeFile() {
		$("#previewImg").html('');
		$("#block-image").css("opacity", "0");
		$("#btn-select-file").css('display', '');
		$("#image").val('');
	}


	function deleteImage(id) {
		swal({
			title: "คุณแน่ใจ ?",
			text: "ต้องการลบรูปภาพ หรือไม่ ?",
			type: "warning",
			showCancelButton: true,
			confirmButtonColor: "#FA5858",
			confirmButtonText: 'ใช่, ฉันต้องการลบ',
			cancelButtonText: 'ยกเลิก',
			closeOnConfirm: false
		}, function() {
			$.ajax({
				url: HOME + 'delete_image/' + id,
				type: "POST",
				cache: "false",
				success: function(rs) {
					var rs = $.trim(rs);
					if (rs == 'success') {
						swal({
							title: 'Deleted',
							text: 'ลบรูปภาพเรียบร้อยแล้ว',
							type: 'success',
							timer: 1000
						});

						setTimeout(function() {
							window.location.reload();
						}, 1200)
					} else {
						swal({
							title: 'Error!',
							text: rs,
							type: 'error'
						})
					}
				},
				error: function(rs) {
					swal({
						title: 'Error!',
						text: "Error-" + rs.status + ": " + rs.statusText,
						type: "error"
					})
				}
			});
		});
	}
</script>
<script src="<?php echo base_url(); ?>scripts/masters/product_category.js?v=<?php echo date('Ymd'); ?>"></script>
<?php $this->load->view('include/footer'); ?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta charset="utf-8" />
		<title>Login Page - <?php echo getConfig('COMPANY_NAME'); ?></title>
		<meta name="description" content="User login page" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
		<link rel="shortcut icon" href="<?php echo base_url(); ?>assets/img/favicon.png">

		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap.css" />
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/font-awesome.css" />

		<!-- text fonts -->
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/ace-fonts.css" />

		<!-- ace styles -->
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/ace.css" />

		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/ace-rtl.css" />
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/template.css" />
		<script src="<?php echo base_url(); ?>assets/js/jquery.min.js"></script>
		<script>
			var BASE_URL = "<?php echo base_url(); ?>";
			var HOME = "<?php echo $this->home; ?>/";
			var USE_STRONG_PWD = <?php echo getConfig('USE_STRONG_PWD'); ?>;
		</script>
	</head>

	<body class="login-layout blur-login">
		<div class="main-container">
			<div class="main-content">
				<div class="row">
					<div class="col-sm-10 col-sm-offset-1">
						<div class="login-container">
							<div class="center">
								<h1>
									<span class="orange"><?php echo getConfig('COMPANY_NAME'); ?></span>
									<span class="white" id="id-text2">Web Sales</span>
								</h1>
								<h4 class="blue" id="id-company-text">&copy; <?php echo getConfig('COMPANY_FULL_NAME');?></h4>
							</div>

							<div class="space-6"></div>

							<div class="position-relative">
								<div id="login-box" class="login-box visible widget-box no-border">
									<div class="widget-body">
										<div class="widget-main">
											<h4 class="header blue lighter bigger text-center" style="border:none;">
												<?php echo empty($message) ? "" : $message; ?>
											</h4>

											<div class="space-6"></div>

											<form method="post" action="">
												<fieldset>
													<input type="hidden" id="uname" value="<?php echo $data->uname; ?>"/>

													<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input type="password" name="current_password" id="current-password" class="form-control" placeholder="รหัสผ่านปัจจุบัน" autofocus required />
															<i class="ace-icon fa fa-lock"></i>
														</span>

													</label>

													<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input type="password" name="new_password" id="new-password" class="form-control" placeholder="รหัสผ่านใหม่" required />
															<i class="ace-icon fa fa-lock"></i>
														</span>
													</label>

													<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input type="password" name="confirm_password" id="confirm-password" class="form-control" placeholder="ยืนยันรหัสผ่าน" required />
															<i class="ace-icon fa fa-lock"></i>
														</span>

													</label>

													<label class="block clearfix">
														<span style="font-size:12px; color:red; text-align:center;" id="error-label"></span>
													</label>
													<div class="space"></div>

													<div class="clearfix">
														<button type="button" id="btn-change" class="width-100 pull-right btn btn-sm btn-primary" onclick="checkPassword()">
															<span class="bigger-110">เปลี่ยนรหัสผ่าน</span>
														</button>
													</div>

													<div class="space-4"></div>

												</fieldset>

											</form>


										</div><!-- /.widget-main -->
										<div class="toolbar clearfix">
											<div>
												<a href="<?php echo base_url(); ?>users/authentication" class="forgot-password-link">
													<i class="ace-icon fa fa-arrow-left"></i>
													กลับไปเข้าระบบอีกครั้ง
												</a>
											</div>

											<div>
												<a href="<?php echo base_url(); ?>users/authentication/logout" class="user-signup-link">
													ออกจากระบบ
													<i class="ace-icon fa fa-arrow-right"></i>
												</a>
											</div>
										</div>
									</div><!-- /.widget-body -->
								</div><!-- /.login-box -->


							</div><!-- /.position-relative -->


						</div>
					</div><!-- /.col -->
				</div><!-- /.row -->
			</div><!-- /.main-content -->
		</div><!-- /.main-container -->
		<script src="<?php echo base_url();?>scripts/change_password.js?v=<?php echo date('Ymd'); ?>"></script>
	</body>
</html>

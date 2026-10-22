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
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/ace-fonts.css" />
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/ace.css" />
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/ace-rtl.css" />
		<script src="<?php echo base_url(); ?>assets/js/jquery.min.js"></script>
	</head>

	<body class="login-layout blur-login">

		<script>
			var BASE_URL = "<?php echo base_url(); ?>";
		</script>

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
											<h4 class="header blue lighter bigger">Please Enter Your Information</h4>

											<div class="space-6"></div>

											<form method="post">
												<fieldset>
													<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input type="text" id="uname" class="form-control" placeholder="Username" autocomplete="off" autofocus />
															<i class="ace-icon fa fa-user"></i>
														</span>
													</label>

													<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input type="password" id="pwd" class="form-control" placeholder="Password" />
															<i class="ace-icon fa fa-key"></i>
														</span>
													</label>

													<div class="space"></div>

													<div class="clearfix">

														<label class="inline">
															<input type="checkbox" name="remember" id="remember" class="ace" value="1" />
															<span class="lbl"> Remember Me</span>
														</label>

														<button type="button" id="login_btn" onclick="doLogin()" class="width-35 pull-right btn btn-sm btn-primary">
															<i class="ace-icon fa fa-key"></i>
															<span class="bigger-110">Login</span>
														</button>
													</div>

													<div class="space-4"></div>
													<div class="clearfix">
														<div class="space-4"></div>
														<div class="space-4"></div>
														<!-- error message goes here -->
														<p id="error-label" style="color:red; text-align:center;"></p>
													</div>
												</fieldset>
											</form>

											<!-- Bypass robot-->
											<span style="display:none;" id="ipwd"></span>
										</div><!-- /.widget-main -->
									</div><!-- /.widget-body -->
								</div><!-- /.login-box -->
							</div><!-- /.position-relative -->
						</div>
					</div><!-- /.col -->
				</div><!-- /.row -->
			</div><!-- /.main-content -->
		</div><!-- /.main-container -->

		<script src="<?php echo base_url(); ?>scripts/login.js?v=<?php echo date('Ymd'); ?>"></script>
	</body>
</html>

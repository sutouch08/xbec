
<!DOCTYPE html>
<html lang="th">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta charset="utf-8" />

		<title><?php echo $this->title; ?></title>
		<meta name="description" content="" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
		<link rel="shortcut icon" href="<?php echo base_url(); ?>assets/img/favicon.png">
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap.css" />
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/font-awesome.css" />
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/ace-fonts.css" />
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/ace.css" class="ace-main-stylesheet" id="main-ace-style" />
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/ace-skins.css" />
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jquery-ui-1.10.4.custom.min.css " />
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/template.css?v=2.1"/>
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/owlcarousel/owl.carousel.min.css">
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/owlcarousel/owl.theme.default.min.css">
		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=Sarabun:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">

		<!-- ace settings handler -->
		<script src="<?php echo base_url(); ?>assets/js/ace-extra.js"></script>
		<script src="<?php echo base_url(); ?>assets/js/jquery.min.js"></script>
		<script src="<?php echo base_url(); ?>assets/js/jquery-ui-1.10.4.custom.min.js"></script>
		<script src="<?php echo base_url(); ?>assets/js/bootstrap.js"></script>
		<script src="<?php echo base_url(); ?>assets/js/ace/ace.js"></script>


		<script src="<?php echo base_url(); ?>assets/js/sweet-alert.js"></script>
		<script src="<?php echo base_url(); ?>assets/js/handlebars-v3.js"></script>
		<script src="<?php echo base_url(); ?>assets/js/select2.js"></script>
		<script src="<?php echo base_url(); ?>assets/js/owlcarousel/owl.carousel.min.js"></script>

		<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/sweet-alert.css">

	</head>
	<body class="no-skin">
		<script type="text/javascript">
			var BASE_URL = '<?php echo base_url(); ?>';
			var HOME = '<?php echo $this->home . '/'; ?>';
		</script>
		<div id="loader">
        <div class="loader"></div>
		</div>
		<div id="loader-backdrop" style="position: fixed; width:100vw; height:100vh; background-color:white; opacity:0.3; display:none; z-index:9;">
		</div>
		<!-- #section:basics/navbar.layout -->
		<div id="navbar" class="navbar navbar-default">
			<div class="navbar-container" id="navbar-container">
				<!-- #section:basics/sidebar.mobile.toggle -->
				<button type="button" class="navbar-toggle menu-toggler pull-left" id="menu-toggler" data-target="#sidebar">
					<span class="sr-only">Toggle sidebar</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>

				<div class="navbar-header pull-left">
					<a href="javascript:void(0)" class="navbar-brand" style="min-width:167px;">
						<small><?php echo getConfig('COMPANY_NAME'); ?></small>
					</a>
				</div>

				<div class="navbar-buttons navbar-header pull-right" role="navigation">
					<ul class="nav ace-nav">
						<?php if($this->show_cart) : ?>
						<li class="salmon hidden-xs">
							<a href="javascript:void(0)" onclick="viewCart()">
								<span class="badge badge-inverse"><i class="ace-icon fa fa-shopping-basket"></i>&nbsp;	<span id="top-amount"><?php echo number($docTotal, 2); ?></span> THB</span>
							</a>
						</li>
						<?php endif; ?>
						<li class="salmon">
							<a data-toggle="dropdown" href="#" class="dropdown-toggle">

								<span class="user-info">
									<small><?php echo get_cookie('uname'); ?></small>
									<?php echo get_cookie('displayName'); ?>
								</span>

								<i class="ace-icon fa fa-caret-down"></i>
							</a>

							<ul class="user-menu dropdown-menu-right dropdown-menu dropdown-caret dropdown-close">
								<li>
									<a href="JavaScript:void(0)" onclick="history()">
										<i class="ace-icon fa fa-shopping-basket"></i>
										ประวัติการสั่งซื้อ
									</a>
								</li>
								<li class="divider"></li>
								<li>
									<a href="JavaScript:void(0)" onclick="changeUserPwd()">
										<i class="ace-icon fa fa-key"></i>
										เปลี่ยนรหัสผ่าน
									</a>
								</li>
								<li class="divider"></li>

								<li>
									<a href="<?php echo base_url(); ?>users/authentication/logout">
										<i class="ace-icon fa fa-sign-out"></i>
										ออกจากระบบ
									</a>
								</li>
							</ul>
						</li>
					</ul>
				</div>
			</div><!-- /.navbar-container -->
		</div>

		<!-- /section:basics/navbar.layout -->
		<div class="main-container" id="main-container">

				<div id="sidebar" class="sidebar responsive" data-sidebar="true" data-sidebar-scoll="true" data-sidebar-hover="true">
					<?php $this->load->view('bp_order/bp_menu'); ?>
				</div>

			<div class="main-content" >
				<div class="main-content-inner">
					<div class="page-content">

<!DOCTYPE html>
<html lang="th">

<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<meta charset="utf-8" />

	<title><?php echo $this->title; ?></title>
	<meta name="description" content="" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
	<link rel="shortcut icon" href="<?php echo base_url(); ?>assets/img/favicon.png">
	<?php $this->load->view('include/header_include'); ?>

	<style>
		.ui-helper-hidden-accessible {
			display: none;
		}

		.ui-autocomplete {
			max-height: 250px;
			overflow-y: auto;
			/* prevent horizontal scrollbar */
			overflow-x: hidden;
		}

		.ui-widget {
			width: auto;
		}
	</style>
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
	<?php if (! isset($_GET['nonavbar'])) : ?>
		<div id="navbar" class="navbar navbar-default">

			<div class="navbar-container" id="navbar-container">
				<?php if (! isset($_GET['nomenu'])) : ?>
					<button type="button" class="navbar-toggle menu-toggler pull-left" id="menu-toggler" data-target="#sidebar">
						<span class="sr-only">Toggle sidebar</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
				<?php endif; ?>

				<div class="navbar-header pull-left">
					<a href="<?php echo base_url(); ?>" class="navbar-brand" style="min-width:167px;">
						<small>
							<?php echo getConfig('COMPANY_NAME'); ?>
						</small>
					</a>
				</div>
				<div class="navbar-buttons navbar-header pull-right" role="navigation">
					<ul class="nav ace-nav">
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
									<a href="JavaScript:void(0)" onclick="changeUserPwd()">
										<i class="ace-icon fa fa-keys"></i>
										เปลี่ยนรหัสผ่าน
									</a>
								</li>
								<li class="divider"></li>
								<li>
									<a href="<?php echo base_url(); ?>users/authentication/logout">
										<i class="ace-icon fa fa-power-off"></i>
										ออกจากระบบ
									</a>
								</li>
							</ul>
						</li>
					</ul>
				</div>
			</div>
		</div>
	<?php endif; ?>
	<!-- /section:basics/navbar.layout -->
	<div class="main-container" id="main-container">
		<script type="text/javascript">
			try {
				ace.settings.check('main-container', 'fixed')
			} catch (e) {}
		</script>
		<!-- #section:basics/sidebar -->
		<?php if ($this->_customer === TRUE) : ?>
			<?php if (! empty($this->side_filter)) : ?>
				<div id="sidebar" class="sidebar responsive <?php echo get_cookie('sidebar_layout'); ?>" data-sidebar="true" data-sidebar-scoll="true" data-sidebar-hover="true">
					<script type="text/javascript">
						try {
							ace.settings.check('sidebar', 'fixed')
						} catch (e) {}
					</script>
					<!--- side menu  ------>
					<?php $this->load->view('include/side_filter'); ?>
				</div>
			<?php endif; ?>
		<?php else : ?>			
				<?php if (! isset($_GET['nomenu'])) : ?>
					<?php $this->load->view("include/side_menu"); ?>
				<?php endif; ?>							
		<?php endif; ?>
		<!-- /section:basics/sidebar -->
		<div class="main-content">
			<div class="main-content-inner">
				<div id="sidebar2" class="sidebar h-sidebar navbar-collapse collapse" data-sidebar="true" data-sidebar-scoll="true"
					data-sidebar-hover="true" aria-expanded="false" style="height:1px;">
				</div>
				<div class="page-content">
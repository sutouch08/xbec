<?php $this->load->view('include/header'); ?>

<?php
$company = $tab == 'company' ? 'active in' : '';
$document = $tab == 'document' ? 'active in' : '';
$order = $tab == 'order' ? 'active in' : '';
$sap = $tab == 'SAP' ? 'active in' : '';
$system = $tab == 'system' ? 'active in' : '';
?>

<style>

@media (min-width: 768px){

	#content-block {
		 border-left:solid 1px #ccc;
	}

}

</style>

<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-5">
    	<h4 class="title"><?php echo $this->title; ?></h4>
	</div>
</div>
<hr class="padding-5">

<div class="row">
	<div class="col-lg-1-harf col-md-2 col-sm-2 padding-5 padding-top-15 hidden-xs">
		<ul id="myTab1" class="setting-tabs" style="margin-left:0px;">
			<li class="li-block <?php echo $company; ?>" onclick="changeURL('company')"><a href="#company" data-toggle="tab">ข้อมูลบริษัท</a></li>
		  <li class="li-block <?php echo $document; ?>" onclick="changeURL('document')"><a href="#document" data-toggle="tab">เลขที่เอกสาร</a></li>
			<li class="li-block <?php echo $order; ?>" onclick="changeURL('order')"><a href="#order" data-toggle="tab">ออเดอร์</a></li>
			<li class="li-block <?php echo $sap; ?>" onclick="changeURL('SAP')"><a href="#SAP" data-toggle="tab">ข้อมูล SAP</a></li>
			<li class="li-block <?php echo $system; ?>" onclick="changeURL('system')"><a href="#system" data-toggle="tab">ระบบ</a></li>
		</ul>
	</div>

	<div class="col-xs-12 padding-5 visible-xs">
		<ul id="myTab1" class="setting-tabs width-100" style="margin-left:0px;">
			<li class="li-block inline border-1 margin-bottom-5 <?php echo $company; ?>" onclick="changeURL('company')"><a href="#company" data-toggle="tab">ข้อมูลบริษัท</a></li>
		  <li class="li-block inline border-1 margin-bottom-5 <?php echo $document; ?>" onclick="changeURL('document')"><a href="#document" data-toggle="tab">เลขที่เอกสาร</a></li>
			<li class="li-block inline border-1 margin-bottom-5 <?php echo $order; ?>" onclick="changeURL('order')"><a href="#order" data-toggle="tab">ออเดอร์</a></li>
			<li class="li-block inline border-1 margin-bottom-5 <?php echo $sap; ?>" onclick="changeURL('SAP')"><a href="#SAP" data-toggle="tab">ข้อมูล SAP</a></li>
			<li class="li-block inline border-1 margin-bottom-5 <?php echo $system; ?>" onclick="changeURL('system')"><a href="#system" data-toggle="tab">ระบบ</a></li>
		</ul>
	</div>

	<div class="divider visible-xs" style="margin-bottom:0px;"></div>

	<div class="col-lg-10-harf col-md-10 col-sm-10 col-xs-12 padding-5" id="content-block" style="min-height:600px; ">
		<div class="tab-content" style="border:0px;">

			<!---  ตั้งค่าบริษัท  ------------------------------------------------------>
			<div class="tab-pane fade <?php echo $company; ?>" id="company">
			<?php $this->load->view('setting/setting_company'); ?>
			</div>
			<!---  ตั้งค่าเอกสาร  --------------------------------------------------->
			<div class="tab-pane fade <?php echo $document; ?>" id="document">
			<?php $this->load->view('setting/setting_document'); ?>
			</div>

			<div class="tab-pane fade <?php echo $order; ?>" id="order">
			<?php $this->load->view('setting/setting_order'); ?>
			</div>

			<div class="tab-pane fade <?php echo $sap; ?>" id="SAP">
			<?php $this->load->view('setting/setting_sap'); ?>
			</div>

			<div class="tab-pane fade <?php echo $system; ?>" id="system">
			<?php $this->load->view('setting/setting_system'); ?>
			</div>


		</div>
	</div><!--/ col-sm-9  -->
</div><!--/ row  -->


<script src="<?php echo base_url(); ?>scripts/setting/setting.js?v=<?php echo date('Ymd'); ?>"></script>
<script src="<?php echo base_url(); ?>scripts/setting/setting_document.js?v=<?php echo date('Ymd'); ?>"></script>
<?php $this->load->view('include/footer'); ?>

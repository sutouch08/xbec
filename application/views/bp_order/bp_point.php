<?php $this->load->view('bp_order/bp_header'); ?>
<style>
.i-blue {  background-color: #4A89DC; }
.c-blue {  background-color: #5D9CEC; }
.i-green {  background-color:  #8CC152;}
.c-green {  background-color:  #A0D468;}
.i-yellow { background-color: #F6BB42;}
.c-yellow { background-color: #FFCE54;}
.i-orange { background-color: #E9573F;}
.c-orange { background-color: #FC6E51;}
.i-red { background-color: #DA4453;}
.c-red { background-color: #ED5565;}

.card-container {
	width:100%;
	max-height:200px;
	border-radius: 10px;
	padding:0px;
	margin-bottom: 10px;
}

.card-header {
	width:100%;
	height: 60px;
	padding: 10px;
	text-align: center;
	font-size: 24px;
	color:white;
	border-top-left-radius: 10px;
	border-top-right-radius: 10px;
}

.card-value {
	width:100%;
	height: 100px;
	padding:15px;
	font-size: 24px;
	color:white;
	text-align: center;
	border-bottom-left-radius: 10px;
	border-bottom-right-radius: 10px;
}
</style>
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-5">
			<h3 class="title"><?php echo $header['title']; ?></h3>
		</div>
	</div>
	<hr class="padding-5" />
	<?php $arr = ['E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X','Z']; ?>
	<div class="row">
	<?php foreach($arr as $key) : ?>
		<?php $key1 = $key."1"; ?>
		<?php  if($header[$key1] == 'Y') : ?>
			<div class="col-lg-3 col-md-4 col-sm-4 col-xs-12 padding-5">
				<div class="card-container ">
					<div class="card-header i-blue"><?php echo $header[$key]; ?></div>
					<div class="card-value c-blue"><?php echo empty($data) ? 0.00 : number($data->$key, 2); ?></div>
				</div>
			</div>
		<?php endif; ?>
	<?php endforeach; ?>


	</div>
<script src="<?php echo base_url(); ?>scripts/bp_order/bp_order.js?v=<?php echo date('Ymd'); ?>"></script>


<?php $this->load->view('bp_order/bp_footer'); ?>

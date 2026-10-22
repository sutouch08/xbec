<?php $this->load->view('bp_order/bp_header'); ?>
<div class="row">
<form id="search-form" method="post" action="<?php echo $this->home; ?>" />
	<div class="col-lg-4 col-lg-offset-4 col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-12 padding-5">
		<span class="input-icon input-icon-right width-100">
			<input type="text" class="width-100 input-lg" name="searchBox" id="search-box" value="<?php echo $search_text; ?>" autofocus>
		<?php if(empty($search_text)) : ?>
			<i id="search-icon" class="ace-icon fa fa-search" style="font-size:20px; line-height:40px;" onclick="search()"></i>
		<?php else : ?>
			<i id="clear-icon" class="ace-icon fa fa-times" style="font-size:20px; line-height:40px;" onclick="clearText('index')"></i>
		<?php endif; ?>
		</span>
	</div>
</form>
</div>
<div class="divider">	</div>
<div class="row">
	<?php if( ! empty($cate)) : ?>
		<?php foreach($cate as $rs) : ?>
			<div class="col-lg-2 col-md-20 col-sm-3 col-xs-6">
				<div class="item-box pointer" onclick="showCategoryItem('<?php echo $rs->code; ?>')">
					<div class="img width-100 display-block">
						<img src="<?php echo get_category_path($rs->code); ?>" class="width-100" />
					</div>
					<div class="item-description text-center"><?php echo $rs->name; ?></div>
				</div>
			</div>
		<?php endforeach; ?>
	<?php else : ?>
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 font-size-15 text-center">--- No Item Found ---</div>
	<?php endif; ?>
</div>

<div class="divider-hidden visible-xs" style="margin-bottom:35px;"></div>

<input type="hidden" id="quotaNo" value="<?php echo $this->_user->quota_no; ?>" />
<input type="hidden" id="customer_code" value="<?php echo $customer->CardCode; ?>" />
<input type="hidden" id="payment" value="<?php echo $customer->GroupNum; ?>" />
<input type="hidden" id="channels" value="<?php echo $this->_user->channels; ?>" />


<?php $this->load->view('bp_order/cart_modal'); ?>
<?php $this->load->view('bp_order/cart_bar'); ?>

<script src="<?php echo base_url(); ?>scripts/bp_order/bp_order.js?v=<?php echo date('Ymd'); ?>"></script>
<script>
	$(document).ready(function() {
		let input = document.getElementById('search-box');
		let end = input.value.length;
		input.setSelectionRange(end, end);
		input.focus();
	})
</script>
<?php $this->load->view('bp_order/bp_footer'); ?>

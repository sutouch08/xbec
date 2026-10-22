<?php $this->load->view('bp_order/bp_header'); ?>
<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-5">
    <h3 class="title">
      <?php echo $this->title; ?>
    </h3>
  </div>
</div><!-- End Row -->
<hr class="padding-5"/>
<form id="searchForm" method="post" action="<?php echo current_url(); ?>">
<div class="row">
  <div class="col-lg-1-harf col-md-2 col-sm-2 col-xs-6 padding-5">
    <label>เลขที่</label>
    <input type="text" class="width-100 search-box" name="code"  value="<?php echo $code; ?>" />
  </div>

	<?php if($this->_customer === FALSE) : ?>
	<div class="col-lg-2 col-md-2 col-sm-2 col-xs-6 padding-5">
		<label>ลูกค้า</label>
		<input type="text" class="width-100 search-box" name="customer" value="<?php echo $customer; ?>" />
	</div>
	<?php endif; ?>

	<div class="col-lg-2 col-md-2 col-sm-2 col-xs-6 padding-5">
		<label>สถานะ</label>
		<select class="width-100 filter" name="status" id="status">
			<option value="all">ทั้งหมด</option>
			<option value="O" <?php echo is_selected('O', $status); ?>>Pending</option>
			<option value="C" <?php echo is_selected('C', $status); ?>>Success</option>
			<option value="D" <?php echo is_selected('D', $status); ?>>Canceled</option>
		</select>
	</div>

	<div class="col-lg-2 col-md-3 col-sm-3 col-xs-6 padding-5">
		<label>วันที่</label>
		<div class="input-daterange input-group width-100">
			<input type="text" class="width-50 text-center from-date" name="from_date" id="from_date" value="<?php echo $from_date; ?>" />
			<input type="text" class="width-50 text-center" name="to_date" id="to_date" value="<?php echo $to_date; ?>" />
		</div>
	</div>


	<div class="col-lg-1 col-md-1 col-sm-1 col-xs-4 padding-5">
		<label class="display-block not-show">buton</label>
		<button type="button" class="btn btn-xs btn-primary btn-block" onclick="getSearch()" >Search</button>
	</div>
	<div class="col-lg-1 col-md-1 col-sm-1 col-xs-4 padding-5">
		<label class="display-block not-show">buton</label>
		<button type="button" class="btn btn-xs btn-warning btn-block" onclick="clear_filter()">Reset</button>
	</div>
</div>

</form>
<hr class="padding-5 "/>
<?php echo $this->pagination->create_links(); ?>

<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-5 table-responsive">
		<table class="table table-striped table-hover dataTable border-1">
			<thead>
				<tr style="font-size:10px;">
					<th class="fix-width-40 middle text-center">#</th>
					<th class="fix-width-80 middle">วันที่</th>
					<th class="fix-width-120 middle">เลขที่</th>
					<th class="min-width-200 middle">ชื่อ</th>
					<th class="fix-width-100 middle text-right">มูลค่า</th>
					<th class="fix-width-100 middle text-center">การชำระเงิน</th>
					<th class="fix-width-80 middle text-center">สถานะ</th>
					<th class="fix-width-120 middle"></th>
				</tr>
			</thead>
			<tbody style="font-size:12px;">
		<?php if( ! empty($data)) : ?>
			<?php $no = $this->uri->segment($this->segment) + 1; ?>
			<?php foreach($data as $rs) : ?>
				<?php $term = $rs->term > 0 ? "{$rs->term} วัน" : "เงินสด"; ?>
				<tr>
					<td class="middle text-center no"><?php echo $no; ?></td>
					<td class="middle"><?php echo thai_date($rs->DocDate, FALSE, '.'); ?></td>
					<td class="middle"><?php echo $rs->code; ?></td>
					<td class="middle"><?php echo $rs->CardName; ?></td>
					<td class="middle text-right"><?php echo number($rs->DocTotal, 2); ?></td>
					<td class="middle text-center"><?php echo $term; ?></td>
					<td class="middle text-center">
						<?php if($rs->so_status == 'O') : ?>
							<span class="orange">Pending</span>
						<?php elseif($rs->so_status == 'C') : ?>
							<span class="green">Success</span>
						<?php elseif($rs->so_status == 'D') : ?>
							<span class="red">Canceled</span>
						<?php else : ?>
							<span class="orange">Pending</span>
						<?php endif; ?>
					</td>
					<td class="middle text-right">
						<button type="button" class="btn btn-mini btn-info top-btn" onclick="viewDetail('<?php echo $rs->code; ?>')">รายละเอียด</button>
					</td>
				</tr>
				<?php $no++; ?>
			<?php endforeach; ?>
		<?php endif; ?>
			</tbody>
		</table>
	</div>
</div>

<script src="<?php echo base_url(); ?>scripts/bp_order/bp_order.js?v=<?php echo date('Ymd'); ?>"></script>

<?php $this->load->view('bp_order/bp_footer'); ?>

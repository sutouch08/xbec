<div class="row">
  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-5 table-responsive">
    <table class="table table-striped border-1 table-narrow" style="min-width:1250px;">
      <thead>
        <tr>
					<th class="fix-width-40 text-center">#</th>
					<th class="fix-width-60 middle text-center">Type</th>
          <th class="fix-width-50 middle text-center">Image</th>
          <th class="fix-width-100 middle">Item Code</th>
          <th class="min-width-250 middle">Description.</th>
          <th class="fix-width-100 middle text-right">Quantity</th>
          <th class="fix-width-100 middle text-center">Uom</th>
          <th class="fix-width-100 middle text-right">Price</th>
          <th class="fix-width-150 middle text-center">Discount(%)</th>
          <th class="fix-width-80 middle text-center">Tax Code</th>
					<th class="fix-width-100 middle text-right">Price after discount</th>
          <th class="fix-width-120 middle text-right">Amount before tax</th>
        </tr>
      </thead>
      <tbody id="details-template">
			<?php if(!empty($details)) : ?>
				<?php $no = 1; ?>
				<?php foreach($details as $rs) : ?>
					<?php if($rs->type == 0) : ?>
		        <tr>
							<td class="middle text-center"><?php echo $no; ?></td>
							<td class="middle text-center">-</td>
		          <td class="middle text-center" id="img-<?php echo $no; ?>"><img src="<?php echo $rs->image; ?>" width="40" height="40" /></td>
		          <td class="middle"><?php echo $rs->ItemCode; ?></td>
		          <td class="middle"><?php echo $rs->ItemName; ?></td>
		          <td class="middle text-right"><?php echo number($rs->Qty, 2); ?></td>
		          <td class="middle text-center"><?php echo $rs->uom_name; ?></td>
		          <td class="middle text-right"><?php echo number($rs->Price, 2); ?></td>
		          <td class="middle text-center"><?php echo $rs->discLabel; ?></td>
		          <td class="middle text-center"><?php echo $rs->VatGroup; ?></td>
		          <td class="middle text-right"><?php echo number($rs->SellPrice, 4); ?></td>
		          <td class="middle text-right"><?php echo number($rs->LineTotal, 2); ?></td>
		        </tr>
					<?php else : ?>
						<tr>
							<td class="middle text-center"><?php echo $no; ?></td>
							<td class="middle text-center">Text</td>
							<td colspan="10"class="middle" style="white-space:pre-wrap;"><?php echo $rs->LineText; ?></td>
		        </tr>
					<?php endif; ?>
          <?php $no++; ?>
        <?php endforeach; ?>
			<?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
<hr class="padding-5"/>

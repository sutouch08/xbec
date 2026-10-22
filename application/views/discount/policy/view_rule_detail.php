<?php
echo $this->printer->doc_header();
$currency = getConfig('CURRENTCY');
?>
<?php if(!$rule_id) : ?>
<?php    $sc .= "ERROR"; ?>
<?php else : ?>
<div class="container">
<div class="row">
  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-5 table-responsive">
    <table class="table table-striped table-bordered">
      <tr class="">
        <td class="width-15 middle text-right"><strong>Rule Code</strong></td>
        <td class="width-20 middle"><?php echo $rule->code; ?></td>
        <td class="width-15 middle text-right"><strong>Description</strong></td>
        <td class="width-50 middle" ><?php echo $rule->name; ?></td>
      </tr>
      <tr>
        <td class="middle text-right"><strong>Promo. Code</strong></td>
        <td class="middle"><?php echo empty($policy) ? '' : $policy->code; ?></td>
        <td class="middle text-right"><strong>Description</strong></td>
        <td class="middle" ><?php echo empty($policy) ? '' : $policy->name; ?></td>
      </tr>
      <tr class="">
        <td class="middle text-right"><strong>Create date</strong></td>
        <td class="middle"><?php echo thai_date($rule->date_add); ?></td>
        <td class="middle text-right"><strong>Create user</strong></td>
        <td class="middle" ><?php echo $this->user_model->get_name($rule->user); ?></td>
      </tr>
      <tr>
        <td class="middle text-right"><strong>Last Update</strong></td>
        <td class="middle"><?php echo thai_date($rule->date_upd); ?></td>
        <td class="middle text-right"><strong>Udate user</strong></td>
        <td class="middle" ><?php echo $this->user_model->get_name($rule->update_user); ?></td>
      </tr>
      <tr class="">
        <td class="middle text-right"><strong>Disc. Type</strong></td>
        <td class="middle"><?php echo $rule->type == 'N' ? "Net price" : ($rule->type == 'F' ? 'Get Free' :"Discount"); ?></td>
        <td class="middle text-right"><strong>Value</strong></td>
        <td class="middle">
					<?php if($rule->type == 'P') : ?>
						<?php echo discount_label($rule->type, $rule->price, $rule->disc1, $rule->disc2, $rule->disc3, $rule->disc4, $rule->disc5); ?>
					<?php elseif($rule->type == 'N') : ?>
						<?php echo $rule->price; ?> THB.
					<?php elseif($rule->type == 'F') : ?>
						<?php echo $rule->freeQty; ?> PCS.
					<?php endif; ?>
				</td>
      </tr>
      <tr>
        <td class="middle text-right"><strong>Min Quantity</strong></td>
        <td class="middle"><?php echo ($rule->minQty > 0 ? number($rule->minQty) : 'No'); ?></td>
        <td class="middle text-right"><strong>Min Amount</strong></td>
        <td class="middle"><?php echo ($rule->minAmount > 0 ? number($rule->minAmount, 2) : 'No'); ?></td>
      </tr>

			<tr class="">
				<td class="middle text-right"><strong>Recursive</strong></td>
				<td class="middle"><?php echo $rule->canGroup == 1 ? 'Yes' : 'No'; ?></td>
				<td class="middle text-right"><strong>Priority</strong></td>
        <td class="middle"><?php echo $rule->priority; ?></td>
			</tr>

			<tr class="">
				<td class="middle text-right"><strong>Free Items</strong></td>
				<td class="middle" colspan="3">
					<?php if($rule->type == 'F') : ?>
						<?php $qs = $this->discount_rule_model->getRuleFreeProduct($rule_id); ?>
						<?php if( ! empty($qs)) : ?>
							<?php $i = 1; ?>
							<?php foreach($qs as $rs) : ?>
								<?php echo $i == 1 ? $rs->code.' : '.$rs->name : '<br/> '.$rs->code.' : '.$rs->name; ?>
								<?php $i++; ?>
							<?php endforeach; ?>
						<?php else : ?>
							NO
						<?php endif; ?>
					<?php else : ?>
						No
					<?php endif; ?>
				</td>
			</tr>

      <tr>
        <td colspan="4" class="text-center"><strong>Customers</strong></td>
      </tr>
      <?php if($rule->all_customer == 1) : ?>
      <tr class="">
        <td class="middle text-right"><strong>Customers</strong></td>
        <td colspan="3"><?php echo 'ทั้งหมด'; ?></td>
      </tr>
      <?php endif; ?>
      <!-- รายชื่อลูกค้าแบบกำหนดรายบุคคล -->
      <?php if($rule->all_customer == 0) : ?>
      <?php   $qs = $this->discount_rule_model->getCustomerRuleList($rule_id); ?>
      <?php   if(!empty($qs)) : ?>
        <tr class="">
          <td class="middle text-right"><strong>Customers</strong></td>
          <td class="middle" colspan="3">
          <?php $i = 1; ?>
        <?php   foreach($qs as $rs) : ?>
          <?php echo $i == 1 ? $rs->customer_code.' : '.$rs->customer_name : '<br/> '.$rs->customer_code.' : '.$rs->customer_name; ?>
          <?php $i++; ?>
        <?php endforeach; ?>
          </td>
        </tr>
        <?php endif; ?>
      <!--- จบรายชื่อลูกค้า  ---->
      <!--- กลุ่มลูกค้า --->
      <?php   $qs = $this->discount_rule_model->getCustomerGroupRule($rule_id); ?>
      <?php   if(!empty($qs)) : ?>
        <tr class="">
          <td class="middle text-right"><strong>Group</strong></td>
          <td class="middle" colspan="3">
          <?php $i = 1; ?>
        <?php   foreach($qs as $rs) : ?>
          <?php echo $i == 1 ? $rs->name : ', '.$rs->name; ?>
          <?php $i++; ?>
        <?php endforeach; ?>
          </td>
        </tr>
        <?php endif; ?>

      <!-- จบกลุ่มลูกค้า --->
      <!-- ชนิดลูกค้า --->
      <?php   $qs = $this->discount_rule_model->getCustomerTypeRule($rule_id); ?>
      <?php   if(! empty($qs)) : ?>
        <tr class="">
          <td class="middle text-right"><strong>Type</strong></td>
          <td class="middle" colspan="3">
          <?php $i = 1; ?>
        <?php   foreach($qs as $rs) : ?>
          <?php echo $i == 1 ? $rs->name : ', '.$rs->name; ?>
          <?php $i++; ?>
        <?php endforeach; ?>
          </td>
        </tr>
        <?php endif; ?>
      <!-- จบชนิดลูกค้า --->
      <!-- ประเภทลูกค้า --->
      <?php   $qs = $this->discount_rule_model->getCustomerRegionRule($rule_id); ?>
      <?php   if(! empty($qs)) : ?>
        <tr class="">
          <td class="middle text-right"><strong>Sales Team</strong></td>
          <td class="middle" colspan="3">
          <?php $i = 1; ?>
        <?php   foreach($qs as $rs) : ?>
          <?php echo $i == 1 ? $rs->name : ', '.$rs->name; ?>
          <?php $i++; ?>
        <?php endforeach; ?>
          </td>
        </tr>
        <?php endif; ?>
      <!-- จบประเภทลูกค้า --->
      <!-- เขตลูกค้า --->
      <?php   $qs = $this->discount_rule_model->getCustomerAreaRule($rule_id); ?>
      <?php   if(! empty($qs)) : ?>
        <tr class="">
          <td class="middle text-right"><strong>Area</strong></td>
          <td class="middle" colspan="3">
          <?php $i = 1; ?>
        <?php   foreach($qs as $rs) : ?>
          <?php echo $i == 1 ? $rs->name : ', '.$rs->name; ?>
          <?php $i++; ?>
        <?php endforeach; ?>
          </td>
        </tr>
        <?php endif; ?>
      <!-- จบเชตลูกค้า --->
      <!-- เกรดลูกค้า --->
      <?php   $qs = $this->discount_rule_model->getCustomerGradeRule($rule_id); ?>
      <?php   if(! empty($qs)) : ?>
        <tr class="">
          <td class="middle text-right"><strong>Grade</strong></td>
          <td class="middle" colspan="3">
          <?php $i = 1; ?>
        <?php   foreach($qs as $rs) : ?>
          <?php echo $i == 1 ? $rs->name : ', '.$rs->name; ?>
          <?php $i++; ?>
        <?php endforeach; ?>
          </td>
        </tr>
        <?php endif; ?>
      <!-- จบเกรดลูกค้า --->
      <?php endif; ?>
      <tr>
        <td colspan="4" class="text-center"><strong>Products</strong></td>
      </tr>
      <?php if($rule->all_product == 1) : ?>
      <tr class="">
        <td class="middle text-right"><strong>All Products</strong></td>
        <td colspan="3"><?php echo 'Yes'; ?></td>
      </tr>
      <?php endif; ?>
      <!-- ถ้าไม่ได้เลือกสินค้าทั้งหมด แต่เลือกเป็นรุ่น -->
      <?php if($rule->all_product == 0) : ?>
				<?php   $qs = $this->discount_rule_model->getProductItemRule($rule_id); ?>
	      <?php   if(! empty($qs)) : ?>
	        <tr class="">
	          <td class="middle text-right"><strong>SKU</strong></td>
	          <td class="middle" colspan="3">
	          <?php $i = 1; ?>
	        <?php   foreach($qs as $rs) : ?>
	          <?php echo $i == 1 ? $rs->code.' : '.$rs->name : '<br/>'.$rs->code.' : '.$rs->name; ?>
	          <?php $i++; ?>
	        <?php endforeach; ?>
	          </td>
	        </tr>
	        <?php endif; ?>

      <?php   $qs = $this->discount_rule_model->getProductModelRule($rule_id); ?>
      <?php   if(! empty($qs)) : ?>
        <tr class="">
          <td class="middle text-right"><strong>Model</strong></td>
          <td class="middle" colspan="3">
          <?php $i = 1; ?>
        <?php   foreach($qs as $rs) : ?>
          <?php echo $i == 1 ? $rs->name : '<br/>'.$rs->name; ?>
          <?php $i++; ?>
        <?php endforeach; ?>
          </td>
        </tr>
        <?php endif; ?>
      <!-- จบรุ่นสินค้า  ---->
      <!-- ชนิดสินค้า --->
      <?php   $qs = $this->discount_rule_model->getProductTypeRule($rule_id); ?>
      <?php   if(! empty($qs)) : ?>
        <tr class="">
          <td class="middle text-right"><strong>Type</strong></td>
          <td class="middle" colspan="3">
          <?php $i = 1; ?>
        <?php   foreach($qs as $rs) : ?>
          <?php echo $i == 1 ? $rs->name : ', '.$rs->name; ?>
          <?php $i++; ?>
        <?php endforeach; ?>
          </td>
        </tr>
        <?php endif; ?>
      <!-- จบชนิดสินค้า --->
      <!-- หมวดหมู่สินค้า --->
      <?php   $qs = $this->discount_rule_model->getProductCategoryRule($rule_id); ?>
      <?php   if(! empty($qs)) : ?>
        <tr class="">
          <td class="middle text-right"><strong>Category</strong></td>
          <td class="middle" colspan="3">
          <?php $i = 1; ?>
        <?php   foreach($qs as $rs) : ?>
          <?php echo $i == 1 ? $rs->name : ', '.$rs->name; ?>
          <?php $i++; ?>
        <?php endforeach; ?>
          </td>
        </tr>
        <?php endif; ?>
      <!-- จบหมวดหมู่สินค้า --->
      <!-- ยี่ห้อสินค้า --->
      <?php   $qs = $this->discount_rule_model->getProductBrandRule($rule_id); ?>
      <?php   if(! empty($qs)) : ?>
        <tr class="">
          <td class="middle text-right"><strong>Brand</strong></td>
          <td class="middle" colspan="3">
          <?php $i = 1; ?>
        <?php   foreach($qs as $rs) : ?>
          <?php echo $i == 1 ? $rs->name : ', '.$rs->name; ?>
          <?php $i++; ?>
        <?php endforeach; ?>
          </td>
        </tr>
        <?php endif; ?>
      <!-- จบยี่ห้อสินค้า --->

    <?php endif; ?>

    <tr>
      <td colspan="4" class="text-center"><strong>Sales Channels and Payment</strong></td>
    </tr>
    <tr class="">
      <td class="middle text-right"><strong>Channels</strong></td>
      <td colspan="3">
        <?php if($rule->all_channels == 1) : ?>
            ทั้งหมด
        <?php else : ?>
          <?php $qs = $this->discount_rule_model->getChannelsRule($rule_id); ?>
          <?php if(! empty($qs)) : ?>
            <?php $i = 1; ?>
            <?php foreach($qs as $rs) : ?>
              <?php echo $i == 1 ? $rs->name : ', '.$rs->name; ?>
							<?php $i++; ?>
            <?php endforeach; ?>
          <?php endif; ?>

        <?php endif; ?>
      </td>
    </tr>
    <tr class="">
      <td class="middle text-right"><strong>Payment</strong></td>
      <td colspan="3">
        <?php if($rule->all_payment == 1) : ?>
            ทั้งหมด
        <?php else : ?>
          <?php $qs = $this->discount_rule_model->getPaymentRule($rule_id); ?>
          <?php if(! empty($qs)) : ?>
            <?php $i = 1; ?>
            <?php foreach($qs as $rs) : ?>
              <?php echo $i == 1 ? $rs->name : ', '.$rs->name; ?>
							<?php $i++; ?>
            <?php endforeach; ?>
          <?php endif; ?>

        <?php endif; ?>
      </td>
    </tr>

    </table>
  </div>
</div>
</div>
<?php endif; ?>
<?php
echo $this->printer->doc_footer();
 ?>

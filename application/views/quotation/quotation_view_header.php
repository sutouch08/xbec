<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-5 table-responsive">
		<table class="table table-striped table-bordered border-1 table-narrow">
			<tr>
				<td class="fix-width-150  bg-grey">Web Order</td>
				<td class=""><?php echo $order->code; ?></td>
				<td class="fix-width-150 bg-grey ">Posting Date</td>
				<td class="fix-width-150"><?php echo thai_date($order->DocDate, FALSE); ?></td>
			</tr>

			<tr>
				<td class="bg-grey">Customer</td>
				<td class=""><?php echo $order->CardCode; ?> &nbsp;&nbsp;:&nbsp;&nbsp;<?php echo $order->CardName; ?></td>
				<td class="bg-grey ">Valid Until</td>
				<td class=""><?php echo thai_date($order->DocDueDate, FALSE); ?></td>
			</tr>
			<tr>
				<td class=" bg-grey">Contact Person</td>
				<td class=""><?php echo $order->ContactPerson; ?></td>
				<td class="bg-grey ">Document Date</td>
				<td class=""><?php echo thai_date($order->TextDate, FALSE); ?></td>
			</tr>
			<tr>
				<td class="bg-grey">Phone No.</td>
				<td class=""><?php echo $order->Phone; ?></td>
				<td class=" bg-grey">Payment</td>
				<td class=""><?php echo $order->payment_name; ?></td>
			</tr>
			<tr>
				<td class="bg-grey">Sales Channels</td>
				<td class=""><?php echo $order->channels_name; ?></td>
				<td class="bg-grey">Status</td>
				<td class=""><?php echo order_status_name($order->Status, $order->Approved); ?></td>
			</tr>
			<tr>
				<td class="bg-grey">Project</td>
				<td class=""><?php echo project_name($order->projectCode); ?></td>
				<td class="bg-grey ">Original SQ No.</td>
				<td class=""><?php echo $order->OriginalSQ; ?></td>
			</tr>
			<tr>
				<td class="bg-grey">Ship To (<?php echo $order->ShipToCode; ?>)</td>
				<td class=""><?php echo $order->Address2; ?></td>
				<td class="bg-grey ">SAP NO.</td>
				<td class=""><?php echo $order->DocNum; ?></td>
			</tr>
			<tr>
				<td class="bg-grey ">Bill To (<?php echo $order->PayToCode; ?>)</td>
				<td class=""><?php echo $order->Address; ?></td>
				<td class="bg-grey ">สายงาน</td>
				<td class=""><?php echo $dimCode; ?></td>
			</tr>
		</table>
	</div>
</div>
<hr  />
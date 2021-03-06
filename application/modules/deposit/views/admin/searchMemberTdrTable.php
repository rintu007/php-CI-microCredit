<?php
	if(!empty($singleMemberSdf)){
		foreach($singleMemberSdf as $k=> $each){
			$year = explode('-',$each['sdfDate']);
			$year = current($year);
			$sortData[$year][] = $each;
		}
	}

	//print_r($sortData);
	
	extract($singleMemberInfo[0]);
	$runningBalance = 0;
	$productBalance = 0;
	$yearBalance = 0; // Product and Balance R same;
?>
<div class="portlet box blue">
	<div class="portlet-title">
		<div class="caption text-right">
			Member Name: <?php echo $memberName; ?> | Member ID: <?php echo $memberAcID; ?>
		</div>
	</div>
	<div class="portlet-body">

<?php if(!empty($singleMemberSdf)): foreach($sortData as $key=>$each): 
	
	$yearCr = 0;
	$yearDr = 0;
	$yearInterest = 0;
	?>
		<div class="panel-group accordion" id="accordion<?php echo $key;?>">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title">
						<a class="accordion-toggle accordion-toggle-styled collapsed" data-toggle="collapse" data-parent="#accordion<?php echo $key;?>" href="#collapse<?php echo $key;?>">
						<?php echo $key;?> </a>
					</h4>
				</div>
				<div id="collapse<?php echo $key;?>" class="panel-collapse collapse">
					<div class="panel-body">
						<table class="table table-bordered">
							<thead>
								<tr>
									<th>SL</th>
									<th>Date</th>
									<th>Dr</th>
									<th>Cr</th>
									<th>Balance</th>
									<th>Product</th>
									<th>Interest</th>
								</tr>
							</thead>
							<tbody>
							<?php $currentMonth = explode('-',$each[0]['sdfDate']);
								$currentMonth = $currentMonth[1];
								$monthName = date("F", mktime(0, 0, 0, $currentMonth, 10));
							?>
							<tr>
								<td colspan="7" class="monthStyle"><?php echo $monthName;?></td>
							</tr>

							<?php foreach($each as $k=>$each2): 

								$currentMonth2 = explode('-',$each2['sdfDate']);
								$currentMonth2 = $currentMonth2[1];

								if($each2['dr_cr']=='CR'){
									$runningBalance += $each2['sdfAmount'];
									$yearBalance += $each2['sdfAmount'];
								} else { // dr debit
									$runningBalance -= $each2['sdfAmount'];
									$yearBalance -= $each2['sdfAmount'];
								}
								
								
								$yearCr += $each2['dr_cr']=='CR'?$each2['sdfAmount']:'';
								$yearDr += $each2['dr_cr']=='DR'?$each2['sdfAmount']:'';
								$yearInterest = ($yearBalance*$formData['sdfInterest'])/$formData['sdfDay'];
								$productBalance += $runningBalance;
								?>

								<?php if($currentMonth !== $currentMonth2): 
									$monthName2 = date("F", mktime(0, 0, 0, $currentMonth2, 10)); $currentMonth = $currentMonth2;
								?>
									<tr>
										<td colspan="7" class="monthStyle"><?php echo $monthName2;?></td>
									</tr>
								<?php endif;?>
								<tr>
									<td><?php echo $k+1;?></td>
									<td><?php echo $each2['sdfDate'];?></td>
									<td><?php echo $each2['dr_cr']=='DR'?$each2['sdfAmount']:'';?></td>
									<td><?php echo $each2['dr_cr']=='CR'?$each2['sdfAmount']:'';?></td>
									<td><?php echo $runningBalance;?></td>
									<td><?php echo $runningBalance;?></td>
									<td><?php echo number_format((float)$yearInterest, 2, '.', '');?></td>
								</tr>
							<?php endforeach; ?>							
							</tbody>
							<tfoot>
								<tr>
									<th colspan="2">Yearly Balance</th>
									<th><?php echo $yearDr; ?></th>
									<th><?php echo $yearCr; ?></th>
									<th><?php echo $yearBalance; ?></th>
									<th><?php echo $productBalance; ?></th>
									<th><?php echo number_format((float)$yearInterest, 2, '.', '');?></th>
								</tr>
							</tfoot>
						</table>
					</div>
				</div>
			</div>			
		</div>

<?php endforeach; else:?>

	<p>No Data Found</p>

<?php endif;?>

	</div>
</div>
<div class="right_col" role="main">
	<div class="card card-outline card-primary">
		<div class="card-header">
			<h3 class="card-title">List of Charges/Fees</h3>
			<div class="card-tools">
				<a href="javascript:void(0)" id="create_new" class="btn btn-flat btn-primary"><span class="fa fa-plus"></span>  Add New</a>
			</div>
		</div>
		<div class="card-body">
			<div class="container-fluid">
				<table class="table table-bordered table-striped jambo_table">
					<colgroup>
						<col width="5%">
						<col width="15%">
						<col width="20%">
						<col width="20%">
						<col width="20%">
						<col width="20%">
					</colgroup>
					<thead>
						<tr>
							<th>#</th>
							<th>Date Created</th>
							<th>Amount From</th>
							<th>Amount To</th>
							<th>Charge</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<?php 
						$i = 1;
							$qry = $conn->query("SELECT * from `fee_list`  order by CAST(`fee` as integer) asc ");
							while($row = $qry->fetch_assoc()):
						?>
							<tr>
								<td class="text-center"><?php echo $i++; ?></td>
								<td><?php echo date("Y-m-d H:i",strtotime($row['date_created'])) ?></td>
								<td class="text-right"><?php echo number_format($row['amount_from'],2) ?></td>
								<td class="text-right"><?php echo number_format($row['amount_to'],2) ?></td>
								<td class="text-right"><?php echo number_format($row['fee'],2) ?></td>
								<td align="center">

										<a class="edit_data btn btn-sm btn-primary" title="view" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="fa fa-edit"></span></a>
										<a class="btn btn-sm btn-danger delete_data" title="delete" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="fa fa-trash"></span></a>
							
								</td>
							</tr>
						<?php endwhile; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>	
</div>
<script>
	$(document).ready(function(){
		$('.delete_data').click(function(){
			_conf("Are you sure to delete this Fee/Charge permanently?","delete_category",[$(this).attr('data-id')])
		})
		$('#create_new').click(function(){
			uni_modal("<i class='fa fa-plus'></i> Add New Fee/Charge","maintenance/manage_fee.php","mid-large")
		})
		$('.edit_data').click(function(){
			uni_modal("<i class='fa fa-plus'></i> Add New Fee/Charge","maintenance/manage_fee.php?id="+$(this).attr('data-id'),"mid-large")
		})
		$('.table td,.table th').addClass('py-1 px-2 align-middle')
		$('.table').dataTable();
	})
	function delete_category($id){
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Master.php?f=delete_fee",
			method:"POST",
			data:{id: $id},
			dataType:"json",
			error:err=>{
				console.log(err)
				alert_toast("An error occured.",'error');
				end_loader();
			},
			success:function(resp){
				if(typeof resp== 'object' && resp.status == 'success'){
					location.reload();
				}else{
					alert_toast("An error occured.",'error');
					end_loader();
				}
			}
		})
	}
</script>
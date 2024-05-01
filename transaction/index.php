<?php require_once('config.php');
 $otp=null;
if($_settings->userdata('type') != 1):
  $current_user_id = $_SESSION['userdata']['id'];
endif;
?>

<div class="right_col" role="main">
    <div class="page-title">
        <div class="title_left">
            <h3>List of Transaction</h3>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="row" style="display: block;">
        <div class="col-md-12 col-sm-12  ">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Transactions </h2>

                    <div class="clearfix"></div>
                </div>
                <div class="x_content">

                    <table class="table table-bordered table-striped jambo_table ">
                        <colgroup>
                            <col width="5%">
                            <col width="10%">
                            <col width="10%">
                            <col width="10%">
                            <col width="10%">
                            <col width="10%">
                            <col width="10%">
                            <col width="10%">
                            <col width="10%">
                        </colgroup>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Date Created</th>
                                <th>Transaction Code</th>
                                <th>Sender Staff ID</th>
                                <th>Sent By</th>
                                <th>Sent To</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
						$i = 1;
						$uwhere ="";
						if($_settings->userdata('type') == 2 )
						$uwhere .= " where  transaction_list.user_id = '{$_settings->userdata('id')}' ";
						$user_qry =$conn->query("SELECT *,concat(firstname,' ',lastname) as `name` FROM `users`");
						$user_res = $user_qry->fetch_all(MYSQLI_ASSOC);
						$user_arr = array_column($user_res,'name','id');
						$qry = $conn->query("SELECT *,`transaction_list`.id as t_id from `transaction_list` order by unix_timestamp(`date_created`) desc ");
						while($row = $qry->fetch_assoc()):
							// print_r($row);
							?>
                            <tr>
                                <td class="text-center"><?php echo $i++; ?></td>
                                <td><?php echo date("Y-m-d H:i", strtotime($row['date_created'])); ?></td>
                                <td><?php echo $row['tracking_code']; ?></td>
                                <td><?php echo $row['user_id']; // Sender Staff ID ?></td>
                                <td>
                                    <?php 
										$sentByUserId = $row['sent_by'];

										// Additional query to get data from transaction_meta_details
										$metaQuery = $conn->query("SELECT * FROM transaction_meta_details WHERE id = '$sentByUserId'");
										if ($metaQuery->num_rows > 0) {
											$metaRow = $metaQuery->fetch_assoc();
											echo $metaRow['first_name'].' '.$metaRow['last_name'];
										} else {
											// Handle the case where no data is found
											echo 'N/A';
										}
									?>
                                </td>
                                <td>
                                    <?php 
										$sentToUserId = $row['sent_to'];

										// Additional query to get data from transaction_meta_details
										$metaQuery = $conn->query("SELECT * FROM transaction_meta_details WHERE id = '$sentToUserId'");
										if ($metaQuery->num_rows > 0) {
											$metaRow = $metaQuery->fetch_assoc();
											echo $metaRow['first_name'].' '.$metaRow['last_name'];
										} else {
											// Handle the case where no data is found
											echo 'N/A';
										}
									?>
                                </td>
                                <td class="text-right"><?php echo number_format($row['sending_amount'],2) ?></td>
                                <td class="text-center">
                                    <?php if($row['status'] == 0): ?>
                                    <span class="badge badge-primary rounded-pill">Pending</span>
                                    <?php elseif($row['status'] == 1): ?>
                                    <span class="badge badge-success rounded-pill">Received</span>
                                    <?php endif; ?>
                                </td>
                                <td align="center">

                                    <a class="btn btn-sm btn-primary" title="view"
                                        href="<?php echo base_url ?>?page=transaction/view_details&id=<?php echo $row['t_id'] ?>"><span
                                            class="fa fa-eye"></span> </a>
                                    <?php if($row['status'] == 0): ?>
                                    <!-- <a class="btn btn-sm btn-success" title="edit"
                                        href="<?php echo base_url ?>?page=transaction/send&id=<?php echo $row['t_id'] ?>"><span
                                            class="fa fa-edit"></span> </a> -->
                                    <?php endif; ?>
                                    <a class="btn btn-sm btn-danger delete_data" title="delete"
                                        href="javascript:void(0)" data-id="<?php echo $row['t_id'] ?>"><span
                                            class="fa fa-trash"></span> </a>

                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function() {
    $('.delete_data').click(function() {
        _conf("Are you sure to delete this transaction permanently?", "delete_transaction", [$(this)
            .attr('data-id')
        ])
    })
    $('.table td,.table th').addClass('py-1 px-2 align-middle')
    $('.table').dataTable();
})

function delete_transaction($id) {
    start_loader();
    $.ajax({
        url: _base_url_ + "classes/Master.php?f=delete_transaction",
        method: "POST",
        data: {
            id: $id
        },
        dataType: "json",
        error: err => {
            console.log(err)
            alert_toast("An error occured.", 'error');
            end_loader();
        },
        success: function(resp) {
            if (typeof resp == 'object' && resp.status == 'success') {
                location.reload();
            } else {
                alert_toast("An error occured.", 'error');
                end_loader();
            }
        }
    })
}
</script>
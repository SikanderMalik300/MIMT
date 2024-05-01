<?php 
require_once('config.php');

// User Authentication and Access Control
if($_settings->userdata('type') != 1):
  $current_user_id = $_SESSION['userdata']['id'];
endif;

$date_from = isset($_GET['date_from']) ? $_GET['date_from'] : date("Y-m-d", strtotime(date("Y-m-d") . ' -1 week'));
$date_to = isset($_GET['date_to']) ? $_GET['date_to'] : date("Y-m-d");

?>
<style>
.hide-print {
    display: none;
}
</style>

<div class="right_col" role="main">
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">Transaction Reports</h3>
        </div>
        <div class="card-body">
            <div class="container-fluid">
                <div class="col-12">
                    <form action="" id="filter">
                        <div class="row align-items-end">
                            <div class="form-group col-md-4">
                                <label for="date_from" class="control-label">Date From</label>
                                <input type="date" name="date_from" class="form-control"
                                    value="<?php echo $date_from ?>" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="date_to" class="control-label">Date To</label>
                                <input type="date" name="date_to" class="form-control" value="<?php echo $date_to ?>"
                                    required>
                            </div>
                            <div class="form-group col-md-4">
                                <button class="btn btn-flat btn-primary">Filter</button>
                                <button class="btn btn-flat btn-success" type="button" id="print"><i
                                        class="fa fa-print"></i> Print</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="container-fluid" id="print_out">
                    <div class="hide-print">
                        <?php if ($date_from == $date_to) : ?>
                        <h3 class="text-center">As of <?php echo date("F d, Y", strtotime($date_from)) ?></h3>
                        <?php else : ?>
                        <h3 class="text-center">As of <?php echo date("F d, Y", strtotime($date_from)) ?> -
                            <?php echo date("F d, Y", strtotime($date_to)) ?></h3>
                        <?php endif; ?>
                    </div>
                    <table class="table table-bordered table-striped jambo_table">
                        <colgroup>
                            <col width="5%">
                            <col width="15%">
                            <col width="15%">
                            <col width="10%">
                            <col width="15%">
                            <col width="20%">
                            <col width="10%">
                            <col width="15%">
                        </colgroup>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Date Created</th>
                                <th>Transaction Code</th>
                                <th>Receiver Staff ID</th>
                                <th>Sent By</th>
                                <th>Info</th>
                                <th>Amount</th>
                                <th>Received At</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 1;
                            $uwhere = " WHERE date(transaction_list.date_created) BETWEEN '{$date_from}' and '{$date_to}' ";
// if ($_settings->userdata('type') == 2 && $_settings->userdata('branch_id') != null)
    

$sql = "SELECT transaction_list.*, 
               branch_list.name as branch_name 
        FROM transaction_list 
        LEFT JOIN users ON transaction_list.receiver_staff_id = users.id 
        LEFT JOIN branch_list ON users.branch_id = branch_list.id 
        {$uwhere} 
        ORDER BY unix_timestamp(transaction_list.date_created) DESC";
                            
                            $qry = $conn->query($sql);
                            while ($row = $qry->fetch_assoc()) :
                                // Retrieving sender and receiver names from transaction_meta_details
                                $sentBy = $conn->query("SELECT first_name, last_name FROM transaction_meta_details WHERE id = ".$row['sent_by']);
                                $sentByRow = $sentBy->fetch_assoc();
                                $sentTo = $conn->query("SELECT first_name, last_name FROM transaction_meta_details WHERE id = ".$row['sent_to']);
                                $sentToRow = $sentTo->fetch_assoc();
                                ?>
                            <tr>
                                <td class="text-center"><?php echo $i++; ?></td>
                                <td><?php echo date("Y-m-d H:i", strtotime($row['date_created'])) ?></td>
                                <td><?php echo $row['tracking_code'] ?></td>
                                <td><?php echo $row['receiver_staff_id'] ?? 'N/A';?></td>
                                <td>
                                    <?php echo $sentByRow['first_name'].' '.$sentByRow['last_name']; // Sent By ?>
                                </td>

                                <td class="lh-1">
                                    <?php if ($row['status'] == 0) : ?>
                                    <span class="text-muted">Status: </span><span
                                        class="badge badge-primary rounded-pill">Pending</span>
                                    <?php elseif ($row['status'] == 1) : ?>
                                    <span class="text-muted">Received By:</span>
                                    <span><?php echo $sentToRow['first_name'].' '.$sentToRow['last_name']; ?></span><br>
                                    <span class="text-muted">Claim ID Type :</span>
                                    <span><?php echo $row['claimid_type'] ?></span><br>
                                    <span class="text-muted">Claim ID:</span>
                                    <span><?php echo $row['claimid'] ?></span>
                                    <br>
                                    <span class="text-muted">Status: </span><span
                                        class="badge badge-success rounded-pill">Received</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-right">
                                    <?php echo number_format($row['sending_amount'] + $row['fee'], 2) ?></td>
                                <td><?php echo $row['branch_name'] ?? 'N/A'; ?></td>
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
$(function() {
    $('#filter').submit(function(e) {
        e.preventDefault();
        location.href = "./?page=reports&" + $(this).serialize();
    })
    $('#print').click(function() {
        start_loader()
        var _el = $('<div>')
        var _head = $('head').clone()
        _head.find('title').text("Transaction Details - Print View")
        var p = $('#print_out').clone()
        p.find('hr.border-light').removeClass('.border-light').addClass('border-dark')
        p.find('.btn').remove()
        _el.append(_head)
        _el.append('<div class="d-flex justify-content-center">' +
            '<div class="col-1 text-right">' +
            '<img src="<?php echo validate_image($_settings->info('logo')) ?>" width="65px" height="65px" />' +
            '</div>' +
            '<div class="col-10">' +
            '<h4 class="text-center"><?php echo $_settings->info('name') ?></h4>' +
            '<h4 class="text-center">Transaction Report</h4>' +
            '</div>' +
            '<div class="col-1 text-right">' +
            '</div>' +
            '</div><hr/>')
        _el.append(p.html())
        var nw = window.open("", "", "width=1200,height=900,left=250,location=no,titlebar=yes")
        nw.document.write(_el.html())
        nw.document.close()
        setTimeout(() => {
            nw.print()
            setTimeout(() => {
                nw.close()
                end_loader()
            }, 200);
        }, 500);

    })
})
</script>
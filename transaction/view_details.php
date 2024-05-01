<?php 
require_once('config.php');

if(isset($_GET['id']) && $_GET['id'] > 0){
    $transaction_id = $_GET['id'];

    // Corrected SQL query without the comment
    $qry = $conn->query("SELECT tl.*, 
                     s.first_name as sender_first_name, s.last_name as sender_last_name, s.phone_number as sender_phone, s.address as sender_address,
                     r.first_name as receiver_first_name, r.last_name as receiver_last_name, r.phone_number as receiver_phone, r.address as receiver_address
                    FROM transaction_list tl
                    LEFT JOIN transaction_meta_details s ON tl.sent_by = s.id
                    LEFT JOIN transaction_meta_details r ON tl.sent_to = r.id
                    WHERE tl.id = '{$transaction_id}'");

    if($qry && $qry->num_rows > 0){
        $transaction = $qry->fetch_assoc();
    } else {
        echo "Transaction not found";
        exit;
    }
}
// Rest of your code...
?>
<div class="right_col" role="main">
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">Transaction Details</h3>
        </div>
        <div class="card-body">
            <div class="container-fluid" id="print_out">
                <div id='transaction-printable-details' class='position-relative'>
                    <div>
                        <label for="text-muted">Tracking Code:</label>
                        <h3 class="fw-bolder ps-5 text-info">
                            <?php echo isset($transaction['tracking_code']) ? $transaction['tracking_code'] : ''; ?>
                        </h3>
                    </div>
                    <hr class="border-light">
                    <div class="row">
                        <div class="col-md-6">
                            <fieldset>
                                <legend class="text-info">Sender Information</legend>
                                <div class="col-12">
                                    <dl>
                                        <dt class="text-muted">Name:</dt>
                                        <dd class="pl-4">
                                            <?php echo isset($transaction['sender_first_name']) ? $transaction['sender_first_name'] . ' ' . $transaction['sender_last_name'] : 'N/A'; ?>
                                        </dd>
                                        <dt class="text-muted">Contact:</dt>
                                        <dd class="pl-4">
                                            <?php echo isset($transaction['sender_phone']) ? $transaction['sender_phone'] : 'N/A'; ?>
                                        </dd>
                                        <dt class="text-muted">Address:</dt>
                                        <dd class="pl-4">
                                            <?php echo isset($transaction['sender_address']) ? $transaction['sender_address'] : 'N/A'; ?>
                                        </dd>
                                    </dl>
                                </div>
                            </fieldset>
                        </div>

                        <div class="col-md-6">
                            <fieldset>
                                <legend class="text-info">Receiver Information</legend>
                                <div class="col-12">
                                    <dl>
                                        <dt class="text-muted">Name:</dt>
                                        <dd class="pl-4">
                                            <?php echo isset($transaction['receiver_first_name']) ? $transaction['receiver_first_name'] . ' ' . $transaction['receiver_last_name'] : 'N/A'; ?>
                                        </dd>
                                        <dt class="text-muted">Contact:</dt>
                                        <dd class="pl-4">
                                            <?php echo isset($transaction['receiver_phone']) ? $transaction['receiver_phone'] : 'N/A'; ?>
                                        </dd>
                                        <dt class="text-muted">Address:</dt>
                                        <dd class="pl-4">
                                            <?php echo isset($transaction['receiver_address']) ? $transaction['receiver_address'] : 'N/A'; ?>
                                        </dd>
                                    </dl>
                                </div>
                            </fieldset>
                        </div>
                    </div>

                    <hr class="border-light">

                    <fieldset>
                        <legend class="text-info">Payment Details</legend>
                        <div class="col-12">
                            <dl>
                                <dt class="text-muted">Amount:</dt>
                                <dd class="pl-4">
                                    <?php echo isset($transaction['sending_amount']) ? number_format($transaction['sending_amount'], 2) : 'N/A'; ?>
                                </dd>
                                <dt class="text-muted">Fee:</dt>
                                <dd class="pl-4">
                                    <?php echo isset($transaction['fee']) ? number_format($transaction['fee'], 2) : 'N/A'; ?>
                                </dd>
                                <dt class="text-muted">Total Transaction Amount:</dt>
                                <dd class="pl-4">
                                    <?php echo (isset($transaction['sending_amount']) && isset($transaction['fee'])) ? number_format($transaction['sending_amount'] + $transaction['fee'], 2) : 'N/A'; ?>
                                </dd>
                                <dt class="text-muted">Purpose/Remarks:</dt>
                                <dd class="pl-4">
                                    <?php echo isset($transaction['purpose']) && !empty($transaction['purpose']) ? $transaction['purpose'] : 'N/A'; ?>
                                </dd>
                            </dl>
                        </div>
                    </fieldset>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button class="btn btn-flat btn-primary" type="button" id="print"><i class="fa fa-print"></i> Print</button>
            <a class="btn btn-flat btn-default" href="<?php echo base_url ?>?page=transaction">Cancel</a>
        </div>
    </div>
</div>
<script>
$(function() {
    $('#print').click(function() {
        var _el = $('<div>');
        var _head = $('head').clone();
        _head.find('title').text("Transaction Details - Print View");
        var p = $('#print_out').clone();
        p.find('hr.border-light').removeClass('border-light').addClass('border-dark');
        p.find('.btn').remove();
        _el.append(_head);
        _el.append(p);
        var nw = window.open("", "", "width=1200,height=900,left=250,location=no,titlebar=yes");
        nw.document.write(_el.html());
        nw.document.close();
        setTimeout(() => {
            nw.print();
            setTimeout(() => {
                nw.close();
            }, 200);
        }, 500);
    });
});
</script>
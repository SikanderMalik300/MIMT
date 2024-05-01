<?php
if(isset($_GET['id']) && $_GET['id'] > 0){
    $qry = $conn->query("SELECT tl.*, 
                         s.first_name as sender_first_name, s.last_name as sender_last_name, 
                         s.phone_number as sender_phone, s.address as sender_address,
                         r.first_name as receiver_first_name, r.last_name as receiver_last_name, 
                         r.phone_number as receiver_phone, r.address as receiver_address
                         FROM transaction_list tl
                         LEFT JOIN transaction_meta_details s ON tl.sent_by = s.id
                         LEFT JOIN transaction_meta_details r ON tl.sent_to = r.id
                         WHERE tl.id = '{$_GET['id']}' ");
    if($qry && $qry->num_rows > 0){
        $transaction = $qry->fetch_assoc();
    } else {
        echo "Transaction not found";
        exit;
    }
}
?>
<div class="right_col" role="main">
    <div class="card card-outline card-info">
        <div class="card-header">
            <h3 class="card-title">Receiving Transaction</h3>
        </div>
        <div class="card-body">
            <div class="container-fluid" id="print_out">
                <div id='transaction-printable-details' class='position-relative'>
                    <!-- Content -->
                    <div>
                        <label for="text-muted">Tracking Code:</label>
                        <h3 class="fw-bolder ps-5 text-info">
                            <?php echo isset($transaction['tracking_code']) ? $transaction['tracking_code'] : ''; ?>
                        </h3>
                    </div>
                    <hr class="border-light">
                    <!-- Sender Information -->
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
                        <!-- Receiver Information -->
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
                    <!-- Payment Details -->
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
            <!-- Form for claim type ID and claim ID -->
            <hr class="border-light">
            <form action="" id="receive-form">
                <input type="hidden" name="id"
                    value="<?php echo isset($transaction['id']) ? $transaction['id'] : ''; ?>">
                <div class="form-group">
                    <label for="claimtype_id">Claim Type ID:</label>
                    <input type="text" name="claimtype_id" id="claimtype_id" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="claimid">Claim ID:</label>
                    <input type="text" name="claimid" id="claimid" class="form-control" required>
                </div>
                <div class="card-footer">
                    <button class="btn btn-flat btn-primary" form="receive-form">Receive Now</button>
                    <a class="btn btn-flat btn-default" href="<?php echo base_url ?>?page=transaction">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
$(document).ready(function() {
    $('.select2').select2({
        width: 'resolve'
    })
    $('#receive-form').submit(function(e) {
        e.preventDefault();
        var _this = $(this)
        $('.err-msg').remove();
        start_loader();
        $.ajax({
            url: _base_url_ + "classes/Master.php?f=save_receive",
            data: new FormData($(this)[0]),
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',
            type: 'POST',
            dataType: 'json',
            error: err => {
                console.log(err)
                alert_toast("An error occurred", 'error');
                end_loader();
            },
            success: function(resp) {
                if (typeof resp == 'object' && resp.status == 'success') {
                    print();
                } else if (resp.status == 'failed' && !!resp.error) {
                    var el = $('<div>')
                    el.addClass("alert alert-danger err-msg").text(resp.error)
                    _this.prepend(el)
                    el.show('slow')
                    $("html, body").animate({
                        scrollTop: _this.closest('.card').offset().top
                    }, "fast");
                    end_loader()
                } else {
                    alert_toast("An error occurred", 'error');
                    end_loader();
                    console.log(resp)
                }
            }
        })
    })

})


function print() {
    var _el = $('<div>')
    var _head = $('head').clone()
    _head.find('title').text("Transaction Details - Print View")
    var p = $('#print_out').clone()
    p.find('hr.border-light').removeClass('.border-light').addClass('border-dark')
    p.find('.btn').remove()
    p.find('.hide-print').remove()
    _el.append(_head)
    _el.append('<div class="d-flex justify-content-center">' +
        '<div class="col-1 text-right">' +
        '<img src="<?php echo validate_image($_settings->info('logo')) ?>" width="65px" height="65px" />' +
        '</div>' +
        '<div class="col-10">' +
        '<h4 class="text-center"><?php echo $_settings->info('name') ?></h4>' +
        '<h4 class="text-center">Transaction Details</h4>' +
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
            location.href = "./?page=transaction";
        }, 200);
    }, 500);

}
</script>
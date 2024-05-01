<?php require_once('config.php');
 $otp=null;
if($_settings->userdata('type') != 1):
  $user_balance = $_SESSION['userdata']['balance'];
endif;

if(isset($_GET['id']) && $_GET['id'] > 0){
    $qry = $conn->query("SELECT * from `transaction_list` where id = '{$_GET['id']}' ");
    if($qry->num_rows > 0){
        foreach($qry->fetch_assoc() as $k => $v){
            $$k=stripslashes($v);
        }
    }
    $qry_meta = $conn->query("SELECT * FROM transaction_meta where transaction_id = '{$id}'");
    while($row = $qry_meta->fetch_array()){
        $meta[$row['meta_field']] = $row['meta_value'];
    }
} 
?>
<div class="right_col" role="main">

    <div class="card card-outline card-info">
        <div class="card-header">
            <h3 class="card-title"><?php echo isset($id) ? "Update ": "Add " ?> Transaction</h3>
        </div>
        <div class="card-body">
            <form action="detail.php" method="POST" id="transaction-form">
                <hr class="border-light">
                <fieldset>
                    <legend>Sender Details</legend>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="sending_amount" class="control-label">First Name</label>
                                <input type="text" id="sender_first_name"
                                    class="form-control form-control-sm rounded-0 text-left" name="sender_first_name"
                                    required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="fee" class="control-label">Last Name</label>
                                <input type="text" id="sender_last_name"
                                    class="form-control form-control-sm rounded-0 text-left" name="sender_last_name"
                                    required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="payable_amount" class="control-label">Phone Number</label>
                                <input type="text" id="sender_phone_number"
                                    class="form-control form-control-sm rounded-0 text-left" name="sender_phone_number"
                                    required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="purpose" class="control-label">Address</label>
                                <input type="text" id="sender_address"
                                    class="form-control form-control-sm rounded-0 text-left" name="sender_address"
                                    required>
                            </div>
                        </div>
                        <?php if($_settings->userdata('type') == 1): ?>
                        <div class="col-md-6">
                            <label for="branch_id">Branch</label>
                            <select name="branch_id" id="branch_id" class="custom-select custom-select-sm select2">
                                <?php 
                                    $branch_qry = $conn->query("SELECT * FROM branch_list where `status` = 1 ".(isset( $meta['branch_id']) &&  $meta['branch_id'] > 0 ? " OR id = '{$meta['branch_id']}'" : '' )." order by `name` asc ");
                                    while($row = $branch_qry->fetch_assoc()):
                                ?>
                                <option value="<?php echo $row['id'] ?>"
                                    <?php echo isset($branch_id) && $branch_id == $row['id'] ? 'selected' : '' ?>>
                                    <?php echo $row['name'] ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                    </div>
                    <?php else: ?>
                    <input type="hidden" name="branch_id" value="<?php echo $_settings->userdata('branch_id') ?>">
                    <?php endif; ?>
                    <input type="hidden" name="transection_type" value="sent">
                </fieldset>
                <fieldset>
                    <legend>Receiver Details</legend>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="sending_amount" class="control-label">First Name</label>
                                <input type="text" id="receiver_first_name"
                                    class="form-control form-control-sm rounded-0 text-left" name="receiver_first_name"
                                    required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="fee" class="control-label">Last Name</label>
                                <input type="text" id="receiver_last_name"
                                    class="form-control form-control-sm rounded-0 text-left" name="receiver_last_name"
                                    required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="payable_amount" class="control-label">Phone Number</label>
                                <input type="text" id="receiver_phone_number"
                                    class="form-control form-control-sm rounded-0 text-left"
                                    name="receiver_phone_number" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="purpose" class="control-label">Address</label>
                                <input type="text" id="receiver_address"
                                    class="form-control form-control-sm rounded-0 text-left" name="receiver_address"
                                    required>
                            </div>
                        </div>
                        <?php if($_settings->userdata('type') == 1): ?>
                        <div class="col-md-6">
                            <label for="branch_id">Branch</label>
                            <select name="branch_id" id="branch_id" class="custom-select custom-select-sm select2">
                                <?php 
                                    $branch_qry = $conn->query("SELECT * FROM branch_list where `status` = 1 ".(isset( $meta['branch_id']) &&  $meta['branch_id'] > 0 ? " OR id = '{$meta['branch_id']}'" : '' )." order by `name` asc ");
                                    while($row = $branch_qry->fetch_assoc()):
                                ?>
                                <option value="<?php echo $row['id'] ?>"
                                    <?php echo isset($branch_id) && $branch_id == $row['id'] ? 'selected' : '' ?>>
                                    <?php echo $row['name'] ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                    </div>
                    <?php else: ?>
                    <input type="hidden" name="branch_id" value="<?php echo $_settings->userdata('branch_id') ?>">
                    <?php endif; ?>
                    <input type="hidden" name="transection_type" value="sent">
                </fieldset>
                <hr class="border-light">
                <fieldset>
                    <legend>Payment Details</legend>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="sending_amount" class="control-label">Amount to Send</label>
                                <input type="text" pattern="[0-9.]+" max="<?php echo $total;?>"
                                    class="form-control form-control-sm rounded-0 text-right" id="sending_amount"
                                    required name="sending_amount"
                                    value="<?php echo isset($sending_amount) ? $sending_amount :''?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="fee" class="control-label">Transaction Fee</label>
                                <input type="text" pattern="[0-9.]+"
                                    class="form-control form-control-sm rounded-0 text-right" id="fee" required
                                    name="fee" value="<?php echo isset($fee) ? $fee :0 ?>" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="payable_amount" class="control-label">Payable Amount</label>
                                <input type="text" pattern="[0-9.]+"
                                    class="form-control form-control-sm rounded-0 text-right" id="payable_amount"
                                    name="payable_amount"
                                    value="<?php echo isset($sending_amount) && isset($fee) ? $fee + $sending_amount :0 ?>"
                                    readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="purpose" class="control-label">Purpose/Remarks</label>
                                <input type="text" class="form-control form-control-sm rounded-0" id="purpose"
                                    name="purpose" value="<?php echo isset($purpose) ? $purpose :'' ?>">
                            </div>
                        </div>
                        <?php if($_settings->userdata('type') == 1): ?>
                        <div class="col-md-6">
                            <label for="branch_id">Branch</label>
                            <select name="branch_id" id="branch_id" class="custom-select custom-select-sm select2">
                                <?php 
                                    $branch_qry = $conn->query("SELECT * FROM branch_list where `status` = 1 ".(isset( $meta['branch_id']) &&  $meta['branch_id'] > 0 ? " OR id = '{$meta['branch_id']}'" : '' )." order by `name` asc ");
                                    while($row = $branch_qry->fetch_assoc()):
                                ?>
                                <option value="<?php echo $row['id'] ?>"
                                    <?php echo isset($branch_id) && $branch_id == $row['id'] ? 'selected' : '' ?>>
                                    <?php echo $row['name'] ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                    </div>
                    <?php else: ?>
                    <input type="hidden" name="branch_id" value="<?php echo $_settings->userdata('branch_id') ?>">
                    <?php endif; ?>
                    <input type="hidden" name="transection_type" value="sent">
                </fieldset>

        </div>
        <div class="card-footer">
            <button class="btn btn-flat btn-primary save_trans" form="transaction-form" id="saveButton">Save</button>
            <a class="btn btn-flat btn-default" href="<?php echo base_url ?>?page=transaction">Cancel</a>
        </div>
    </div>
</div>
</form>
<script>
$(document).ready(function() {

    $('#sending_amount').on('input', function(e) {
        var amount = $(this).val();
        $.ajax({
            url: _base_url_ + 'classes/Master.php?f=get_fee',
            method: 'POST',
            data: {
                amount: amount
            },
            dataType: 'json',
            error: err => {
                console.log(err);
                alert_toast("An error occurred while fetching the transaction amount fee.",
                    'error');
            },
            success: function(resp) {
                if (resp.status == 'success') {
                    $('#fee').val(resp.fee);
                    payable = parseFloat(amount) + parseFloat(resp.fee);
                    if (isNaN(payable)) {
                        payable = 0;
                    }
                    $('#payable_amount').val(payable);
                } else {
                    console.log(resp);
                    alert_toast(
                        "An error occurred while fetching the transaction amount fee.",
                        'error');
                }
            }
        });
    });

    $('#saveButton').on('click', function(e) {
        var userBalance = parseFloat(<?php echo json_encode($user_balance); ?>);
        var payableAmount = parseFloat($('#payable_amount').val());

        console.log("User Balance: " + userBalance); // Debugging
        console.log("Payable Amount: " + payableAmount); // Debugging

        if (isNaN(userBalance) || isNaN(payableAmount)) {
            console.log("Invalid user balance or payable amount."); // Debugging
            alert_toast("Invalid balance or payable amount.", 'error');
            e.preventDefault(); // Prevent form submission
            return;
        }

        if (userBalance < payableAmount) {
            console.log("Insufficient balance."); // Debugging
            alert_toast("Insufficient balance.", 'error');
            e.preventDefault(); // Prevent form submission
        }
    });

});
</script>
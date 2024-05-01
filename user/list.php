<?php if($_settings->chk_flashdata('success')): ?>
<script>
alert_toast("<?php echo $_settings->flashdata('success') ?>", 'success')
</script>
<?php endif;?>

<style>
.img-avatar {
    width: 45px;
    height: 45px;
    object-fit: cover;
    object-position: center center;
    border-radius: 100%;
}
</style>
<div class="right_col" role="main">
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">List of System Users</h3>
            <div class="card-tools">
                <a href="<?php echo base_url ?>?page=user/manage_user" class="btn btn-flat btn-primary"><span
                        class="fa	 fa-plus"></span> Create New</a>
            </div>
        </div>
        <div class="card-body">
            <div class="container-fluid">
                <div class="container-fluid">
                    <table class="table table-bordered table-striped jambo_table">
                        <colgroup>
                            <col width="5%">
                            <col width="10%">
                            <col width="15%">
                            <col width="15%">
                            <col width="15%">
                            <col width="15%">
                            <col width="15%">
                            <col width="10%">
                        </colgroup>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Avatar</th>
                                <th>Name</th>
                                <th>Username</th>
                                <th>Branch</th>
                                <th>Phone</th>
                                <th>Type</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
							$i = 1;
							$branch_qry = $conn->query("SELECT * FROM branch_list");
							$result = $branch_qry->fetch_all(MYSQLI_ASSOC);
							$branch_arr = array_column($result,'name','id');
							$qry = $conn->query("SELECT *,concat(firstname,' ',lastname) as name from `users` where id != '1' and id != '{$_settings->userdata('id')}' and `type` != 3 order by concat(firstname,' ',lastname) asc ");
							while($row = $qry->fetch_assoc()):
						?>
                            <tr>
                                <td class="text-center"><?php echo $i++; ?></td>
                                <td class="text-center"><img src="<?php echo validate_image($row['avatar']) ?>"
                                        class="img-avatar img-thumbnail p-0 border-2" alt="user_avatar"></td>
                                <td><?php echo ucwords($row['name']) ?></td>
                                <td>
                                    <p class="m-0 truncate-1"><?php echo $row['username'] ?></p>
                                </td>
                                <td>
                                    <p class="m-0 truncate-1"
                                        title="<?php echo ($row['branch_id'] != null && isset($branch_arr[$row['branch_id']])) ? $branch_arr[$row['branch_id']] : 'N/A' ?>">
                                        <?php echo ($row['branch_id'] != null && isset($branch_arr[$row['branch_id']])) ? $branch_arr[$row['branch_id']] : 'N/A' ?>
                                    </p>
                                </td>
                                <td><?php echo $row['phone'] ?> </td>
                                <td><?php echo ($row['type'] == 1) ? 'Administrator' : 'Staff' ?></td>
                                <td align="center">
                                    <a class="btn btn-sm btn-primary edit_data" title="Edit"
                                        href="<?php echo base_url ?>?page=user/manage_user&id=<?php echo $row['id'] ?>"><span
                                            class="fa fa-edit "></span> </a>
                                    <a class="btn btn-sm btn-danger delete_data" title="Delete"
                                        href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span
                                            class="fa fa-trash"></span></a>
                                    <a class="btn btn-sm btn-success add_balance" title="Add Balance"
                                        href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">$</a>
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

<!-- Modal for adding balance -->
<div class="modal fade" id="balanceModal" tabindex="-1" role="dialog" aria-labelledby="balanceModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="balanceModalLabel">Add Balance</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="balanceForm">
                    <div class="form-group">
                        <label for="balanceAmount">Enter Balance Amount:</label>
                        <input type="number" class="form-control" id="balanceAmount" name="balanceAmount"
                            pattern="[0-9.]+" max=1000000>
                        <input type="hidden" id="balanceUserId" name="userId">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="submitBalance">Add Balance</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Balance Confirmation -->
<div class="modal fade" id="balanceConfirmationModal" tabindex="-1" role="dialog"
    aria-labelledby="balanceConfirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="balanceConfirmationModalLabel">Balance Added Successfully</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Balance has been added successfully.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<script>
$(document).ready(function() {
    $('.delete_data').click(function() {
        _conf("Are you sure to delete this User permanently?", "delete_user", [$(this).attr('data-id')])
    })

    $('.add_balance').click(function() {
        var userId = $(this).data('id');
        $('#balanceModal').modal('show');
        $('#balanceUserId').val(userId);
    });

    $('.table td,.table th').addClass('py-1 px-2 align-middle')
    $('.table').dataTable();

    $('#submitBalance').click(function() {
        var userId = $('#balanceUserId').val();
        add_balance(userId);
    });

    if (response.status === 'success') {
        $('#balanceConfirmationModal').modal('show');
    }
});

function delete_user($id) {
    start_loader();
    $.ajax({
        url: _base_url_ + "classes/Users.php?f=delete",
        method: "POST",
        data: {
            id: $id
        },
        dataType: "json",
        error: err => {
            console.log(err)
            alert_toast("An error occurred.", 'error');
            end_loader();
        },
        success: function(resp) {
            if (typeof resp == 'object' && resp.status == 'success') {
                location.reload();
            } else {
                alert_toast("An error occurred.", 'error');
                end_loader();
            }
        }
    })
}

function add_balance($id) {
    start_loader();
    var balanceAmount = $('#balanceAmount').val();
    $.ajax({
        url: _base_url_ + "classes/Users.php?f=add_balance",
        method: "POST",
        data: {
            id: $id,
            balanceAmount: balanceAmount
        },
        dataType: "json",
        error: err => {
            console.log(err)
            alert_toast("An error occurred.", 'error');
            end_loader();
        },
        success: function(resp) {
            if (typeof resp == 'object' && resp.status == 'success') {
                location.reload();
            } else {
                alert_toast("An error occurred.", 'error');
                end_loader();
            }
        }
    })
}
</script>
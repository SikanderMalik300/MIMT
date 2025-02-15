<?php
require_once('../config.php');
Class Master extends DBConnection {
	private $settings;
	public function __construct(){
		global $_settings;
		$this->settings = $_settings;
		parent::__construct();
	}
	public function __destruct(){
		parent::__destruct();
	}
	function capture_err(){
		if(!$this->conn->error)
			return false;
		else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
			return json_encode($resp);
			exit;
		}
	}
	function save_branch(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('id'))){
				if(!empty($data)) $data .=",";
				$data .= " `{$k}`='{$v}' ";
			}
		}
		$check = $this->conn->query("SELECT * FROM `branch_list` where `name` = '{$name}' ".(!empty($id) ? " and id != {$id} " : "")." ")->num_rows;
		if($this->capture_err())
			return $this->capture_err();
		if($check > 0){
			$resp['status'] = 'failed';
			$resp['msg'] = "Branch Name already exist.";
			return json_encode($resp);
			exit;
		}
		if(empty($id)){
			$sql = "INSERT INTO `branch_list` set {$data} ";
			$save = $this->conn->query($sql);
		}else{
			$sql = "UPDATE `branch_list` set {$data} where id = '{$id}' ";
			$save = $this->conn->query($sql);
		}
		if($save){
			$resp['status'] = 'success';
			if(empty($id))
				$this->settings->set_flashdata('success',"New Branch successfully saved.");
			else
				$this->settings->set_flashdata('success',"Branch successfully updated.");
		}else{
			$resp['status'] = 'failed';
			$resp['err'] = $this->conn->error."[{$sql}]";
		}
		return json_encode($resp);
	}
	function delete_branch(){
		extract($_POST);
		$del = $this->conn->query("DELETE FROM `branch_list` where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success',"Branch successfully deleted.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);

	}
	function save_fee(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('id'))){
				$v = $this->conn->real_escape_string($v);
				if(!empty($data)) $data .=",";
				$data .= " `{$k}`='{$v}' ";
			}
		}
		$check = $this->conn->query("SELECT * FROM `fee_list` where `amount_from` = '{$amount_from}' and `amount_to` = '{$amount_to}' ".(!empty($id) ? " and id != {$id} " : "")." ")->num_rows;
		if($this->capture_err())
			return $this->capture_err();
		if($check > 0){
			$resp['status'] = 'failed';
			$resp['msg'] = "Amount Range already exists.";
			return json_encode($resp);
			exit;
		}
		if(empty($id)){
			$sql = "INSERT INTO `fee_list` set {$data} ";
			$save = $this->conn->query($sql);
		}else{
			$sql = "UPDATE `fee_list` set {$data} where id = '{$id}' ";
			$save = $this->conn->query($sql);
		}
		if($save){
			$resp['status'] = 'success';
			if(empty($id))
				$this->settings->set_flashdata('success',"New Amount Charge/Fee successfully saved.");
			else
				$this->settings->set_flashdata('success',"Amount Charge/Fee successfully updated.");
		}else{
			$resp['status'] = 'failed';
			$resp['err'] = $this->conn->error."[{$sql}]";
		}
		return json_encode($resp);
	}
	function delete_fee(){
		extract($_POST);
		$del = $this->conn->query("DELETE FROM `fee_list` where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success',"Amount Charge/Fee  successfully deleted.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);

	}
	function get_fee(){
		extract($_POST);
		$fee_qry = $this->conn->query("SELECT fee FROM fee_list where `amount_from` <= '{$amount}'  and `amount_to` >= '{$amount}' order by unix_timestamp(`date_created`) desc limit 1 ");
		$fee = 0;
		if($fee_qry->num_rows > 0){
			$fee = $fee_qry->fetch_array()['fee'];
		}
		$resp['status'] = 'success';
		$resp['fee'] = $fee;
		$resp['payable'] = floatval($fee) + floatval($amount);
		return json_encode($resp);
	}
	// function save_transaction(){
	// 	if(empty($_POST['id'])){
	// 		$prefix = substr(str_shuffle(implode("",range("A","Z"))),0,3);
	// 		$code = $prefix."-".(sprintf("%'.012d",rand(1,999999999999)));
	// 		while(true){
	// 			$check_code = $this->conn->query("SELECT * FROM `transaction_list` where tracking_code ='{$code}' ")->num_rows;
	// 			if($check_code > 0){
	// 				$code = $prefix."-".(sprintf("%'.012d",rand(1,999999999999)));
	// 			}else{
	// 				break;
	// 			}
	// 		}
	// 		$_POST['tracking_code'] = $code;
	// 	}
	// 	$_POST['user_id'] = $this->settings->userdata('id');
	// 	$user_type = $this->settings->userdata('type');
	// 	extract($_POST);
	// 	if($user_type!=1){
			
	// 		$user_id = $_POST['user_id'];
	// 		$amount = $payable_amount;
	// 		$update_user = "UPDATE `users` set balance = balance - {$amount} where id = '{$user_id}'";
	// 		$this->conn->query($update_user);
	// 	}
	// 	$data = "";
	// 	foreach($_POST as $k =>$v){
	// 		if(in_array($k,array('tracking_code','purpose','status','sending_amount','fee','user_id','send_to','sent_from','branch_id'))){
	// 			$v= $this->conn->real_escape_string($v);
	// 			if(!empty($data)) $data .=", ";
	// 			$data .=" `{$k}` = '{$v}' ";
	// 		}
	// 	}
	// 	if(empty($id)){
	// 		$sql = "INSERT INTO `transaction_list` set {$data}";
	// 	}else{
	// 		$sql = "UPDATE `transaction_list` set {$data} where id = '{$id}'";
	// 	}
	// 	$save = $this->conn->query($sql);
	// 	if($save){
	// 		$tid = empty($id) ? $this->conn->insert_id : $id;
	// 		$data = "";
	// 		$resp['status'] = 'success';
	// 	}else{
	// 		$resp['status'] = 'failed';
	// 		$resp['msg'] = 'An error occured. Error: '.$this->conn->error;
	// 	}
	// 	if($resp['status'] == 'success')
	// 		$this->settings->set_flashdata('success'," Transaction's Details Successfully updated.");
	// 		return json_encode($resp);
	// }
	function delete_transaction(){
		extract($_POST);
		$del = $this->conn->query("DELETE FROM `transaction_list` where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success',"transaction's Details Successfully deleted.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);

	}
	function find_transaction(){
		extract($_POST);
		$curent_user_id = $_SESSION['userdata']['id']; 
		$where = '';
		if($this->settings->userdata('type') == 1 )
			$where = " and send_to={$curent_user_id}";
		$qry = $this->conn->query("SELECT * FROM `transaction_list` where tracking_code = '{$tracking_code}' $where ");
		if($qry->num_rows > 0){
			$res = $qry->fetch_array();
			if($res['status'] != 1){
				$resp['status'] ='success';
				$resp['id'] =$res['id'];

				
			}else{
				$resp['status'] ='failed';
				$resp['msg'] ="Transaction already received.";
			}
		}else{
			$resp['status'] ='failed';
			$resp['msg'] ="Unkown Tracking Code";
		}
		return json_encode($resp);
	}
	function save_receive() {
    $user_id = $this->settings->userdata('id'); // Fetch current user ID
    extract($_POST);

    // Fetch the current transaction data
    $qry = $this->conn->query("SELECT * FROM `transaction_list` where id = '{$id}'");
    if ($qry->num_rows > 0) {
        $row = $qry->fetch_array();
    } else {
        // Handle case where transaction is not found
        return json_encode(['status' => 'failed', 'error' => 'Transaction not found. cake']);
    }

    // Update transaction status and claim information
    $sql = "UPDATE `transaction_list` SET 
                status = 1, 
                claimid_type = '{$this->conn->real_escape_string($claimtype_id)}', 
                claimid = '{$this->conn->real_escape_string($claimid)}',
                receiver_staff_id = '{$user_id}' 
            WHERE id = '{$id}'";
    $save = $this->conn->query($sql);
    if ($save) {
        // Success response
        $resp = ['status' => 'success'];
        $this->settings->set_flashdata('success', "Transaction's Details Successfully updated.");
    } else {
        // Failure response
        $resp = ['status' => 'failed', 'error' => 'Error updating transaction details.'];
        // Optionally, log error details here
    }

    return json_encode($resp);
}

}

$Master = new Master();
$action = !isset($_GET['f']) ? 'none' : strtolower($_GET['f']);
$sysset = new SystemSettings();
switch ($action) {
	case 'save_branch':
		echo $Master->save_branch();
	break;
	case 'delete_branch':
		echo $Master->delete_branch();
	break;
	case 'save_fee':
		echo $Master->save_fee();
	break;
	case 'delete_fee':
		echo $Master->delete_fee();
	break;
	case 'get_fee':
		echo $Master->get_fee();
	break;
	case 'save_transaction':
		echo $Master->save_transaction();
	break;
	case 'delete_transaction':
		echo $Master->delete_transaction();
	break;
	case 'find_transaction':
		echo $Master->find_transaction();
	break;
	case 'save_receive':
		echo $Master->save_receive();
	break;
	default:
		// echo $sysset->index();
		break;
}
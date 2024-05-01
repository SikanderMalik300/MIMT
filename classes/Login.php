<?php
require_once '../config.php';
class Login extends DBConnection {
	private $settings;
	public function __construct(){
		global $_settings;
		$this->settings = $_settings;

		parent::__construct();
		ini_set('display_error', 1);
	}
	public function __destruct(){
		parent::__destruct();
	}
	public function index(){
		echo "<h1>Access Denied</h1> <a href='".base_url."'>Go Back.</a>";
	}
	public function login(){
		extract($_POST);
		$qry = $this->conn->query("SELECT * from users where (username = '$username' OR email = '$username')  and password = md5('$password') ");
		if($qry->num_rows > 0){
			$user_id = null;
			$user_type = null;
			$email = null;
			foreach($qry->fetch_array() as $k => $v){
				if(!is_numeric($k) && $k != 'password'){
					$this->settings->set_userdata($k,$v);
				}
				
			}
			$user_id= $this->settings->userdata('id');
			$user_type= $this->settings->userdata('type');
			$email= $this->settings->userdata('email');
			
			if($user_type !=1 && $user_id !=null ):
				$otp = random_int(100000, 999999);
				$this->conn->query("UPDATE otp_record set `otp`='$otp',`status`=0 where `user_id`='$user_id'");
				$msg = "Your OTP is $otp ";
				$Subject = 'OTP for verification';
				$headers = "From: webmaster@example.com";
				mail($email,$Subject,$msg,$headers);
			endif;
			$this->settings->set_userdata('login_type',1);
		return json_encode(array('status'=>'success'));
		}else{
		return json_encode(array('status'=>'incorrect','last_qry'=>"SELECT * from users where username = '$username' and password = md5('$password') "));
		}
	}
	public function forget(){
		$email = $_POST['email'];
		$qry = $this->conn->query("SELECT * from users where email = '$email'");
		if($qry->num_rows > 0){
			$user = $qry -> fetch_array(MYSQLI_ASSOC);
			$newpassword = $user['username'].'123'; 
			$qry = $this->conn->query("UPDATE users SET `password`=md5('$newpassword') where email = '$email'");
			$msg = "Your new password is $newpassword ";
			// send email
			$headers = "From: webmaster@example.com";
			if(mail($email,"Your new password",$msg,$headers)){
				return json_encode(array('status'=>'success'));
			}else{
				return json_encode(array('status'=>'incorrect','Email Sending failed. Please try again.'));
			}
		}else{
			return json_encode(array('status'=>'incorrect','Email does not exist'));
		}
	}
	public function logout(){
		if($this->settings->sess_des()){
			redirect('login.php');
		}
	}
	function login_user(){
		extract($_POST);
		$qry = $this->conn->query("SELECT * from clients where email = '$email' and password = md5('$password') ");
		if($qry->num_rows > 0){
			foreach($qry->fetch_array() as $k => $v){
				$this->settings->set_userdata($k,$v);
			}
			$this->settings->set_userdata('login_type',1);
		$resp['status'] = 'success';
		}else{
		$resp['status'] = 'incorrect';
		}
		if($this->conn->error){
			$resp['status'] = 'failed';
			$resp['_error'] = $this->conn->error;
		}
		return json_encode($resp);
	}
}
$action = !isset($_GET['f']) ? 'none' : strtolower($_GET['f']);
$auth = new Login();
switch ($action) {
	case 'login':
		echo $auth->login();
		break;
	case 'login_user':
		echo $auth->login_user();
		break;
	case 'logout':
		echo $auth->logout();
		break;
	case 'forget':
		echo $auth->forget();
		break;
	default:
		echo $auth->index();
		break;
}


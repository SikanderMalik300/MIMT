<?php


require_once('../config.php');

Class Users extends DBConnection {
	private $settings;
	public $mail;
	public function __construct(){
		global $_settings;
		$this->settings = $_settings;
		parent::__construct();
	}
	public function __destruct(){
		parent::__destruct();
	}
	public function save_users(){
		extract($_POST);
		$data = '';
		if(isset($_POST['type']) && $_POST['type'] == 1)
		$_POST['branch_id'] = null;
		$chk = $this->conn->query("SELECT * FROM `users` where username ='{$username}' ".($id>0? " and id!= '{$id}' " : ""))->num_rows;
		if($chk > 0){
			return 3;
			exit;
		}
		foreach($_POST as $k => $v){
			if(!in_array($k,array('id','password'))){
				if(!empty($data)) $data .=" , ";
				$data .= " {$k} = '{$v}' ";
			}
		}
		if(!empty($password)){
			$password = md5($password);
			if(!empty($data)) $data .=" , ";
			$data .= " `password` = '{$password}' ";
		}

		if(empty($id)){
			$qry = $this->conn->query("INSERT INTO users set {$data}");
			if($qry){
				$id = $this->conn->insert_id;
				$this->settings->set_flashdata('success','User Details successfully saved.');
				$resp['status'] = 1;
			}else{
				$resp['status'] = 2;
			}
			$otp = random_int(100000, 999999);
			$this->conn->query("INSERT INTO otp_record set `user_id`='$id',`otp`='$otp',`status`=0");

		}else{
			$qry = $this->conn->query("UPDATE users set $data where id = {$id}");
			if($qry){
				$this->settings->set_flashdata('success','User Details successfully updated.');
				if($id == $this->settings->userdata('id')){
					foreach($_POST as $k => $v){
						if($k != 'id'){
							if(!empty($data)) $data .=" , ";
							$this->settings->set_userdata($k,$v);
						}
					}
					if(isset($fname) && isset($move))
					$this->settings->set_userdata('avatar',$fname);
				}
				$resp['status'] = 1;
			}else{
				$resp['status'] = 2;
			}
			
		}
		
		if(isset($_FILES['img']) && $_FILES['img']['tmp_name'] != ''){
			$fname = 'uploads/avatar-'.$id.'.png';
			$dir_path =base_app. $fname;
			$upload = $_FILES['img']['tmp_name'];
			$type = mime_content_type($upload);
			$allowed = array('image/png','image/jpeg');
			if(!in_array($type,$allowed)){
				$resp['msg'].=" But Image failed to upload due to invalid file type.";
			}else{
				$new_height = 200; 
				$new_width = 200; 
		
				list($width, $height) = getimagesize($upload);
				$t_image = imagecreatetruecolor($new_width, $new_height);
				$gdImg = ($type == 'image/png')? imagecreatefrompng($upload) : imagecreatefromjpeg($upload);
				imagecopyresampled($t_image, $gdImg, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
				if($gdImg){
						if(is_file($dir_path))
						unlink($dir_path);
						$uploaded_img = imagepng($t_image,$dir_path);
						imagedestroy($gdImg);
						imagedestroy($t_image);
				}else{
				$resp['msg'].=" But Image failed to upload due to unkown reason.";
				}
			}
			if(isset($uploaded_img)){
				$this->conn->query("UPDATE users set `avatar` = CONCAT('{$fname}','?v=',unix_timestamp(CURRENT_TIMESTAMP)) where id = '{$id}' ");
			}
		}
		return  $resp['status'];
	}
	public function get_user($id=null){
		if($id==null){
			$id = $_SESSION['userdata']['id'];
		}
		$chk = $this->conn->query("SELECT * FROM `users` where  id= '{$id}'");
		$user = NULL;
		if($chk->num_rows > 0){
			$user = $chk->fetch_array(MYSQLI_ASSOC);
		}
		return $user;
	}
	public function save_rusers(){
		// extract($_POST);
		
		$username = $_POST['username'];
		$email = $_POST['email'];
		$data = '';

		$chk = $this->conn->query("SELECT * FROM `users` where username ='{$username}' OR email='{$email}' ")->num_rows;
		if($chk > 0){
			return json_encode(array('status'=>'incorrect','Already Registered'));
			exit;
		}
		$firstname = $_POST['firstname'];
		$lastname = $_POST['lastname'];
		$type = $_POST['type'];
		$password = $_POST['password'];
		$branch_id = $_POST['branch_id'];
		$phone = $_POST['phone'];

		$data = "`firstname`='$firstname',`lastname`='$lastname',`username`='$username',`email`='$email',`branch_id`='$branch_id',`phone`='$phone',`type`=$type,`balance`=0 ";
		if(!empty($password)){
			$password = md5($password);
			if(!empty($data)) $data .=" , ";
			$data .= " `password` = '{$password}' ";
		}

		$qry = $this->conn->query("INSERT INTO users set {$data}");
		if($qry){
			$user_id = $this->conn->insert_id;
			$otp = random_int(100000, 999999);
			$this->conn->query("INSERT INTO otp_record set `user_id`='$user_id',`otp`='$otp',`status`=0");
			$msg = "Your OTP is $otp ";
			$Subject = 'OTP for verification';
			$headers = "From: webmaster@example.com";
			mail($email,$Subject,$msg,$headers);
			//send the message, check for errors
			$qry1 = $this->conn->query("SELECT * from users where `username` = '$username' and `password` = '$password' ");
			if($qry1->num_rows > 0){
				foreach($qry1->fetch_array() as $k => $v){
					if(!is_numeric($k) && $k != 'password'){
						$_SESSION['userdata'][$k]= $v;
					}
				}
				$_SESSION['userdata']['login_type']=2;
				return json_encode(array('status'=>'success'));
			}else{
				return json_encode(array('status'=>'incorrect','last_qry'=>"SELECT * from users where username = '$username' and password = '$password' "));
			}
			$this->settings->set_flashdata('success','User Details successfully saved.');
			$resp['status'] = 1;
		}else{
			$resp['status'] = 2;
		}
		return  $resp['status'];
	}
	public function verifyotp(){
		$curent_user_id = $_SESSION['userdata']['id'];
		$otp = $_POST['otp'];
		$chk = $this->conn->query("SELECT * FROM otp_record where `user_id`=$curent_user_id and `otp`='$otp'");
		if($chk->num_rows>0){
			$id = $chk -> fetch_array(MYSQLI_ASSOC);
			$this->conn->query("UPDATE  otp_record set `status`=1 WHERE id ={$id['id']}  ");
			return json_encode(array('status'=>'success'));
		}else{
			return json_encode(array('status'=>'incorrect'));
;		}
	}
	public function delete_users(){
		extract($_POST);
		$avatar = $this->conn->query("SELECT avatar FROM users where id = '{$id}'")->fetch_array()['avatar'];
		$qry = $this->conn->query("DELETE FROM users where id = $id");
		if($qry){
			$this->settings->set_flashdata('success','User Details successfully deleted.');
			if(is_file(base_app.$avatar))
				unlink(base_app.$avatar);
			$resp['status'] = 'success';
		}else{
			$resp['status'] = 'failed';
		}
		return json_encode($resp);
	}
	public function add_balance($id, $balanceAmount)
    {
        $balanceAmount = (float) $balanceAmount;

        // Check if the user ID exists
        $userCheck = $this->conn->query("SELECT * FROM users WHERE id = $id");
        if ($userCheck->num_rows == 0) {
            $resp['status'] = 'error';
            $resp['message'] = 'User not found.';
            return json_encode($resp);
        }

        // Update the user's balance
        $sql = "UPDATE users SET balance = balance + $balanceAmount WHERE id = $id";
        $updateBalance = $this->conn->query($sql);

        if ($updateBalance) {
            // Balance updated successfully
            $this->settings->set_flashdata('success', 'Balance added successfully.');
            $resp['status'] = 'success';
        } else {
            // Error occurred while updating balance
            $resp['status'] = 'error';
            $resp['message'] = 'Failed to add balance.';
        }

        return json_encode($resp);
    }


	public function save_fusers(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k => $v){
			if(!in_array($k, array('id','password'))){
				if(!empty($data)) $data .= ", ";
				$data .= " `{$k}` = '{$v}' ";
			}
		}

			if(!empty($password))
			$data .= ", `password` = '".md5($password)."' ";
		
			if(isset($_FILES['img']) && $_FILES['img']['tmp_name'] != ''){
				$fname = 'uploads/'.strtotime(date('y-m-d H:i')).'_'.$_FILES['img']['name'];
				$move = move_uploaded_file($_FILES['img']['tmp_name'],'../'. $fname);
				if($move){
					$data .=" , avatar = '{$fname}' ";
					if(isset($_SESSION['userdata']['avatar']) && is_file('../'.$_SESSION['userdata']['avatar']))
						unlink('../'.$_SESSION['userdata']['avatar']);
				}
			}
			$sql = "UPDATE faculty set {$data} where id = $id";
			$save = $this->conn->query($sql);

			if($save){
			$this->settings->set_flashdata('success','User Details successfully updated.');
			foreach($_POST as $k => $v){
				if(!in_array($k,array('id','password'))){
					if(!empty($data)) $data .=" , ";
					$this->settings->set_userdata($k,$v);
				}
			}
			if(isset($fname) && isset($move))
			$this->settings->set_userdata('avatar',$fname);
			return 1;
			}else{
				$resp['error'] = $sql;
				return json_encode($resp);
			}

	} 

	public function save_susers(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k => $v){
			if(!in_array($k, array('id','password'))){
				if(!empty($data)) $data .= ", ";
				$data .= " `{$k}` = '{$v}' ";
			}
		}

			if(!empty($password))
			$data .= ", `password` = '".md5($password)."' ";
		
			if(isset($_FILES['img']) && $_FILES['img']['tmp_name'] != ''){
				$fname = 'uploads/'.strtotime(date('y-m-d H:i')).'_'.$_FILES['img']['name'];
				$move = move_uploaded_file($_FILES['img']['tmp_name'],'../'. $fname);
				if($move){
					$data .=" , avatar = '{$fname}' ";
					if(isset($_SESSION['userdata']['avatar']) && is_file('../'.$_SESSION['userdata']['avatar']))
						unlink('../'.$_SESSION['userdata']['avatar']);
				}
			}
			$sql = "UPDATE students set {$data} where id = $id";
			$save = $this->conn->query($sql);

			if($save){
			$this->settings->set_flashdata('success','User Details successfully updated.');
			foreach($_POST as $k => $v){
				if(!in_array($k,array('id','password'))){
					if(!empty($data)) $data .=" , ";
					$this->settings->set_userdata($k,$v);
				}
			}
			if(isset($fname) && isset($move))
			$this->settings->set_userdata('avatar',$fname);
			return 1;
			}else{
				$resp['error'] = $sql;
				return json_encode($resp);
			}

	} 
	
}

$users = new users();
$action = !isset($_GET['f']) ? 'none' : strtolower($_GET['f']);
switch ($action) {
	case 'save':
		echo $users->save_users();
	break;
	case 'fsave':
		echo $users->save_fusers();
	break;
	case 'ssave':
		echo $users->save_susers();
	break;
	case 'rsave':
		echo $users->save_rusers();
	break;
	case 'delete':
		echo $users->delete_users();
	break;
	case 'verifyotp':
		echo $users->verifyotp();
	break;
	case 'add_balance':
        $id = $_POST['id'];
        $balanceAmount = $_POST['balanceAmount'];
        echo $users->add_balance($id, $balanceAmount);
        break;
	default:
		// echo $sysset->index();
		break;
}
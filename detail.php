<?php require_once('config.php');
 $otp=null;
if($_settings->userdata('type') != 1):
  $curent_user_id = $_SESSION['userdata']['id'];
  $user_data = $conn->query("SELECT * FROM users where `id`='$curent_user_id'");
  
endif;
// Assuming you have a database connection established
$servername = "localhost";
$username = "root";
$password = "";
$database = "mtms_db";

$conn = new mysqli($servername, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


function generateRandomCode() {
    $prefix = 'BFW-'; // You can customize the prefix as needed
    $randomNumbers = mt_rand(100000000000, 999999999999); // Generate 12 random digits

    $randomCode = $prefix . $randomNumbers;

    return $randomCode;
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {



    // Process the form data
    $senderFirstName = $_POST['sender_first_name'];
    $senderLastName = $_POST['sender_last_name'];
    $senderPhoneNumber = $_POST['sender_phone_number'];
    $senderAddress = $_POST['sender_address'];

    $receiverFirstName = $_POST['receiver_first_name'];
    $receiverLastName = $_POST['receiver_last_name'];
    $receiverPhoneNumber = $_POST['receiver_phone_number'];
    $receiverAddress = $_POST['receiver_address'];

    $purpose = $_POST['purpose'];
    $amount = floatval($_POST['sending_amount']);
    $payable_amount = floatval($_POST['payable_amount']); // Total amount including the fee
    $fee = floatval($_POST['fee']);
    $branch_id = $_SESSION['userdata']['branch_id'];
    $code = generateRandomCode();
    $status = 0;
    $transaction_type = 'sent';

    $balance = floatval($_SESSION['userdata']['balance']);
    if ($balance < $payable_amount) {
        echo "Insufficient balance.";
        exit;
    }


    $insertQuery = "INSERT INTO transaction_meta_details (first_name, last_name, phone_number, address) 
                    VALUES ('$senderFirstName', '$senderLastName', '$senderPhoneNumber', '$senderAddress')";

    if ($conn->query($insertQuery) === TRUE) {
        $senderDetailsID = $conn->insert_id;
        
        $insertQuery2 = "INSERT INTO transaction_meta_details (first_name, last_name, phone_number, address) 
                    VALUES ('$receiverFirstName', '$receiverLastName', '$receiverPhoneNumber', '$receiverAddress')";

        if ($conn->query($insertQuery2) === TRUE) {
            $receiverDetailsID = $conn->insert_id;

            $insertQuery3 = "INSERT INTO transaction_list (tracking_code, branch_id, sending_amount, fee, purpose, 
                             user_id, sent_to, sent_by, status, transection_type) 
                             VALUES ('$code', '$branch_id', '$amount', '$fee', '$purpose', 
                                     '$curent_user_id', '$receiverDetailsID', '$senderDetailsID', '$status', '$transaction_type')";

            if ($conn->query($insertQuery3) === TRUE) {
                $querya = "UPDATE users
           SET balance = balance - ($fee + $amount)
           WHERE id = $curent_user_id;";



                if ($conn->query($querya) === TRUE) {
                    header("Location: http://localhost/MIMT/");
                }

            } else {
                echo "Error: " . $insertQuery3 . "<br>" . $conn->error;
            } 
        } 
        else{
            echo "Error: " . $insertQuery2 . "<br>" . $conn->error;
        }
    } else {
        echo "Error: " . $insertQuery . "<br>" . $conn->error;
    }

    $conn->close();
}
?>
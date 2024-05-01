<html>
<?php require_once('config.php'); ?>
<?php require_once('inc/header.php'); 
 $otp=null;
if($_settings->userdata('type') != 1):
  $curent_user_id = $_SESSION['userdata']['id'];
  $check_otp = $conn->query("SELECT * FROM otp_record where `user_id`='$curent_user_id'");
  if($check_otp->num_rows > 0){
    $otp = $check_otp -> fetch_array(MYSQLI_ASSOC);
    $otp = $otp['status'];
  }
endif;?>
  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
        
    <?php 
      if($_settings->userdata('type')== 1 || $otp==1 ):
        require_once('inc/navigation.php');
      endif;
      ?>
    <?php require_once('inc/topBarNav.php') ?>
    <?php if($_settings->chk_flashdata('success')): ?>
      <script>
        alert_toast("<?php echo $_settings->flashdata('success') ?>",'success')
      </script>
      <?php endif;?>    
     <?php $page = isset($_GET['page']) ? $_GET['page'] : 'home';  ?>

            <?php 
             $page = trim($page);
              if(!file_exists($page.".php") && !is_dir($page)){
                  include '404.html';
              }else{
                if(is_dir($page))
                  include $page.'/index.php';
                else
                  include $page.'.php';

              }
            ?>
        <!-- /.content -->
        <div class="modal fade" id="confirm_modal" role='dialog'>
          <div class="modal-dialog modal-md modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
              <h5 class="modal-title">Confirmation</h5>
            </div>
            <div class="modal-body">
              <div id="delete_content"></div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-primary" id='confirm' onclick="">Continue</button>
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
            </div>
          </div>
        </div>
        <div class="modal fade" id="uni_modal" role='dialog'>
          <div class="modal-dialog modal-md modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
              <h5 class="modal-title"></h5>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-primary" id='submit' onclick="$('#uni_modal form').submit()">Save</button>
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            </div>
            </div>
          </div>
        </div>
        <div class="modal fade" id="uni_modal_right" role='dialog'>
          <div class="modal-dialog modal-full-height  modal-md" role="document">
            <div class="modal-content">
              <div class="modal-header">
              <h5 class="modal-title"></h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span class="fa fa-arrow-right"></span>
              </button>
            </div>
            <div class="modal-body">
            </div>
            </div>
          </div>
        </div>
        <div class="modal fade" id="viewer_modal" role='dialog'>
          <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                    <button type="button" class="btn-close" data-dismiss="modal"><span class="fa fa-times"></span></button>
                    <img src="" alt="">
            </div>
          </div>
        </div>

      <!-- /.content-wrapper -->
      <?php require_once('inc/footer.php') ?>
      </div>
    </div>
    <script type="module">
    // Import the functions you need from the SDKs you need
    import { initializeApp } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-app.js";
    import { getAnalytics } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-analytics.js";
    // TODO: Add SDKs for Firebase products that you want to use
    // https://firebase.google.com/docs/web/setup#available-libraries

    // Your web app's Firebase configuration
    // For Firebase JS SDK v7.20.0 and later, measurementId is optional
    const firebaseConfig = {
      apiKey: "AIzaSyAR843Hc-1O6lVsdDLR0MWB6TiH73gachU",
      authDomain: "my-project-1479123719699.firebaseapp.com",
      databaseURL: "https://my-project-1479123719699-default-rtdb.firebaseio.com",
      projectId: "my-project-1479123719699",
      storageBucket: "my-project-1479123719699.appspot.com",
      messagingSenderId: "818374629407",
      appId: "1:818374629407:web:d7b044d7dec36406595d2a",
      measurementId: "G-KKB19KT69Z"
    };

    // Initialize Firebase
    const app = initializeApp(firebaseConfig);
    const analytics = getAnalytics(app);
    
   
  </script>
  </body>
</html>
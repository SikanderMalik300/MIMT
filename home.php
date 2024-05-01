        <!-- page content -->
        <div class="right_col" role="main">
        <h1 class="text-dark">Welcome to <?php echo $_settings->info('name') ?></h1>
        <hr class="border-dark">
        <?php if( $_settings->userdata('type') != 1 && ($otp==null || $otp==0)):?>
          <form action="" id="verify_otp">
            <p id="msg"></p>
            <h3>Check your email to find OTP for verification</h3>
          <label for="">Enter your OTP</label><br>
          <input type="number" name="otp" required class="form-control col-md-3" >
          
          <button class="btn btn-primary">Verify Now</button>
          </form>
        <?php endif;?>

      </div>
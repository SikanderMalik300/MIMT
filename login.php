<?php require_once('config.php') ?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <!-- Meta, title, CSS, favicons, etc. -->
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <?php require_once('inc/header.php') ?>
  <style>
    body {
      background-image: url("<?php echo validate_image($_settings->info('cover')) ?>");
      background-size: cover;
      background-repeat: no-repeat;
    }
  </style>
</head>

<body class="login">
  <div>
    <a class="hiddenanchor" id="signup"></a>
    <a class="hiddenanchor" id="signin"></a>
    <a class="hiddenanchor" id="forget"></a>

    <div class="login_wrapper">
      <h1 class="h1 text-center"><?php echo $_settings->info('name') ?></h1>
      <div id="msg"></div>
      <div class="animate form login_form">
        <section class="login_content">
          <form id="login-frm" action="" method="post">
            <h1>Login Form</h1>
            <div>
              <input type="text" class="form-control" name="username" placeholder="Username" required="" />
            </div>
            <div>
              <input type="password" class="form-control" name="password" placeholder="Password" required="" />
            </div>
            <div>
              <button type="submit" class="btn btn-default submit">Log in</button>
              <a class="reset_pass" href="#forget">Lost your password?</a>
            </div>

            <div class="clearfix"></div>

            <div class="separator">
              <p class="change_link">
                <a href="#signup" class="to_register"> Create Account </a>
              </p>

              <div class="clearfix"></div>
            </div>
          </form>
        </section>
      </div>

      <div id="register" class="animate form registration_form">
        <section class="login_content">
            <form action="" id="manage-ruser" method="post">
              <h1>Create Account</h1>
              <div>
                <input type="text" name="firstname" id="firstname" class="form-control" required placeholder="firstname">
              </div>
              <div>
                <input type="text" name="lastname" id="lastname" class="form-control" required placeholder="lastname">
              </div>
              <div>
                <input type="text" name="email" id="email" class="form-control" required placeholder="email">
              </div>
              <div>
                <input type="text" name="username" id="username" class="form-control" required placeholder="username" autocomplete="off">
              </div>
              <div>
                <input type="password" name="password" id="password" class="form-control" required placeholder="password" autocomplete="off">
              </div>
              <div>
                <input type="phone" name="phone" id="phone" placeholder="Phone" class="form-control" required autocomplete="off">
              </div>
              <div style="margin: 0 0 20px;">
                <input type="hidden" name="type" class="custom-select" value="2">
              </div>
              <div>
                <select name="branch_id" id="branch_id" class="custom-select custom-select-sm select2">
                  <option value="" disabled >Select Branch</option>
                  <?php 
                    $branch_qry = $conn->query("SELECT * FROM branch_list where `status` = 1 ".(isset( $meta['branch_id']) &&  $meta['branch_id'] > 0 ? " OR id = '{$meta['branch_id']}'" : '' )." order by `name` asc ");
                    while($row = $branch_qry->fetch_assoc()):
                  ?>
                  <option value="<?php echo $row['id'] ?>" <?php echo isset($meta['branch_id']) && $meta['branch_id'] == $row['id'] ? 'selected' : '' ?>><?php echo $row['name'] ?></option>
                  <?php endwhile; ?>
                </select>
              </div>
              <div>
                <button class="btn btn-default submit" type="submit">Register</button>
              </div>

              <div class="clearfix"></div>

              <div class="separator">
                <p class="change_link">Already a member ?
                  <a href="#signin" class="to_register"> Log in </a>
                </p>

                <div class="clearfix"></div>
              </div>
            </form>
        </section>
      </div>
      
      <div id="forget" class="animate form forget_form">
        <section class="login_content">
          
          <form id="forget-frm" action="" method="post">
            <h1>Forget Form</h1>
            <div>
              <input type="text" class="form-control" name="email" placeholder="email" required="" />
            </div>
            <div>
              <button type="submit" class="btn btn-default submit">Submit</button>
            </div>

            <div class="clearfix"></div>

            <div class="separator">
              <p class="change_link">
                <a href="#login" class="to_register"> Login </a>
              </p>

              <div class="clearfix"></div>
            </div>
          </form>
        </section>
      </div>
    </div>
  </div>
</body>

</html>

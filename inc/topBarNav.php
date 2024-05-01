        <?php
        $curent_user_id = $_SESSION['userdata']['id']; 
        $sql = "SELECT balance from `users` where id = {$curent_user_id} ";
        $qry = $conn->query($sql);
        $received = $qry->fetch_assoc();
        $total = $received['balance'] ;
        ?>
        <!-- top navigation -->
        <div class="top_nav">
          <div class="nav_menu">
            <div class="nav toggle">
              <a id="menu_toggle"><i class="fa fa-bars"></i></a>
            </div>
            <nav class="nav navbar-nav">
              <ul class=" navbar-right">
                <li class="nav-item dropdown open" style="padding-left: 15px;">
                  <a href="javascript:;" class="user-profile dropdown-toggle" aria-haspopup="true" id="navbarDropdown" data-toggle="dropdown" aria-expanded="false">
                    <img src="<?php echo validate_image($_settings->userdata('avatar')) ?>" alt=""><?php echo ucwords($_settings->userdata('firstname').' '.$_settings->userdata('lastname')) ?>
                  </a>
                  <div class="dropdown-menu dropdown-usermenu pull-right" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item"  href="<?php echo base_url.'?page=user' ?>"> Profile</a>
                    <a class="dropdown-item"  href="<?php echo base_url.'classes/Login.php?f=logout' ?>"><i class="fa fa-sign-out pull-right"></i> Log Out</a>
                  </div>
                </li>
                <?php if($_settings->userdata('type') != 1): ?>
                  <li class="nav-item" style="padding-left: 15px;font-size:18px">
                    <span class="badge bg-green p-2">Balance <?php echo $total;?></span>
                  </li>
                <?php endif;?>
              </ul>
            </nav>
          </div>
        </div>
          <!-- /top navigation -->
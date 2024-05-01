        <div class="col-md-3 left_col">
            <div class="left_col scroll-view">
                <div class="navbar nav_title" style="border: 0;">
                    <a href="<?php echo base_url ?>" class="site_title">
                        <img src="<?php echo validate_image($_settings->info('logo')) ?>" alt="" id="cimg"
                            class="img-fluid rounded-circle" style="width: 50px !important;"></i>
                        <span><?php echo $_settings->info('short_name');?></span></a>
                </div>

                <div class="clearfix"></div>

                <!-- menu profile quick info -->
                <div class="profile clearfix">
                    <div class="profile_pic">
                        <img src="<?php echo validate_image($_settings->userdata('avatar')) ?>" alt=""
                            class="img-circle profile_img">
                    </div>
                    <div class="profile_info">
                        <span>Welcome,</span>
                        <h2><?php echo ucwords($_settings->userdata('firstname').' '.$_settings->userdata('lastname')) ?>
                        </h2>
                    </div>
                </div>
                <!-- /menu profile quick info -->

                <br />

                <!-- sidebar menu -->
                <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
                    <div class="menu_section">
                        <h3>General</h3>
                        <ul class="nav side-menu">
                            <li><a href="<?php echo base_url;?>"><i class="fa fa-dashboard"></i> Dashboard</span></a>
                            </li>
                            <?php if($_settings->userdata('type') == 2): ?>
                            <li>
                                <a href="#"><i class="fa fa-list"></i> Transaction <span
                                        class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    <li><a href="<?php echo base_url ?>?page=transaction/send">Send</a></li>
                                    <li><a href="javascript:void(0)" id="receive-nav">Receive</a></li>
                                    <li><a href="<?php echo base_url ?>?page=transaction">View Details</a></li>
                                </ul>
                            </li>
                            <?php endif; ?>
                            <?php if($_settings->userdata('type') == 1): ?>
                            <li><a href="<?php echo base_url ?>?page=transaction"><i class="fa fa-list"></i> View
                                    Transactions
                                    </span></a></li>
                            <?php endif; ?>
                            <li><a href="<?php echo base_url ?>?page=reports"><i class="fa fa-file"></i>
                                    Reports</span></a></li>
                        </ul>
                    </div>
                    <?php if($_settings->userdata('type') == 1): ?>
                    <div class="menu_section">
                        <h3>Maintenance</h3>
                        <ul class="nav side-menu">
                            <li><a href="<?php echo base_url ?>?page=maintenance/branch"><i class="fa fa-code-fork"></i>
                                    Branch List</span></a></li>
                            <li><a href="<?php echo base_url ?>?page=maintenance/fee"><i class="fa fa-table"></i> Fee
                                    Table</span></a></li>
                            <li><a href="<?php echo base_url ?>?page=user/list"><i class="fa fa-users"></i> Users
                                    List</span></a></li>
                            <li><a href="<?php echo base_url ?>?page=system_info"><i class="fa fa-cogs"></i>
                                    Settings</span></a></li>
                        </ul>
                    </div>
                    <?php endif; ?>
                </div>
                <!-- /sidebar menu -->


                <!-- /menu footer buttons -->

                <!-- /menu footer buttons -->
            </div>
        </div>
        <!-- Main Sidebar Container -->
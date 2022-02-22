<!DOCTYPE html>
<html lang="en">
  <head>
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <meta charset="utf-8" />
  <title>Dashboard - Ace Admin</title>
  <meta name="description" content="overview &amp; stats" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
  <base href="<?php echo base_url(); ?>">
  <!-- bootstrap & fontawesome -->
  <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
  <link rel="stylesheet" href="assets/font-awesome/4.5.0/css/font-awesome.min.css" />
  <!-- page specific plugin styles -->
  <!-- text fonts -->
  <link rel="stylesheet" href="assets/css/fonts.googleapis.com.css" />
  <!-- ace styles -->
  <link rel="stylesheet" href="assets/css/ace.min.css" class="ace-main-stylesheet" id="main-ace-style" />
  <link rel="stylesheet" href="assets/css/ace-skins.min.css" />
  <link rel="stylesheet" href="assets/css/ace-rtl.min.css" />

  <link href="assets/datatables/datatables.min.css" rel="stylesheet" type="text/css"/>
  <link href="assets/datatables/dataTables.customLoader.walker.css" rel="stylesheet" type="text/css"/>
  <link href="assets/datatables/plugins/bootstrap/datatables.bootstrap.css" rel="stylesheet" type="text/css"/>
  <link rel="stylesheet" type="text/css" href="assets/sweetalerts/sweetalert2.scss">
  <link href="assets/sweetalerts/sweetalert.css" rel="stylesheet" type="text/css" />
  <link href="assets/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
  <link href="assets/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />

  <script type="text/javascript">
    var base_url = '<?php echo base_url();?>';
    var csrf_token = '<?php echo $this->security->get_csrf_hash(); ?>';
    var csrf_token_name = '<?php echo $this->security->get_csrf_token_name(); ?>';
  </script>
  <script src="assets/js/jquery-2.1.4.min.js"></script>
  <script src="assets/js/ace-extra.min.js"></script>
  </head>
  <style type="text/css">
    .modal-footer{
      background-color: white !important;
    }
  </style>

  <body class="no-skin">
    <div id="navbar" class="navbar navbar-default          ace-save-state">
      <div class="navbar-container ace-save-state" id="navbar-container">
        <button type="button" class="navbar-toggle menu-toggler pull-left" id="menu-toggler" data-target="#sidebar">
          <span class="sr-only">Toggle sidebar</span>

          <span class="icon-bar"></span>

          <span class="icon-bar"></span>

          <span class="icon-bar"></span>
        </button>

        <div class="navbar-header pull-left">
          <a href="index.html" class="navbar-brand">
            <small>
              <i class="fa fa-leaf"></i>
              Ace Admin
            </small>
          </a>
        </div>

        <div class="navbar-buttons navbar-header pull-right" role="navigation">
          <ul class="nav ace-nav">

            <li class="purple dropdown-modal">
              <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                <i class="ace-icon fa fa-bell icon-animated-bell"></i>
                <span class="badge badge-important">8</span>
              </a>

              <ul class="dropdown-menu-right dropdown-navbar navbar-pink dropdown-menu dropdown-caret dropdown-close">
                <li class="dropdown-header">
                  <i class="ace-icon fa fa-exclamation-triangle"></i>
                  8 Notifications
                </li>

                <li class="dropdown-content">
                  <ul class="dropdown-menu dropdown-navbar navbar-pink">
                    <li>
                      <a href="#">
                        <div class="clearfix">
                          <span class="pull-left">
                            <i class="btn btn-xs no-hover btn-pink fa fa-comment"></i>
                            New Comments
                          </span>
                          <span class="pull-right badge badge-info">+12</span>
                        </div>
                      </a>
                    </li>

                    <li>
                      <a href="#">
                        <i class="btn btn-xs btn-primary fa fa-user"></i>
                        Bob just signed up as an editor ...
                      </a>
                    </li>

                    <li>
                      <a href="#">
                        <div class="clearfix">
                          <span class="pull-left">
                            <i class="btn btn-xs no-hover btn-success fa fa-shopping-cart"></i>
                            New Orders
                          </span>
                          <span class="pull-right badge badge-success">+8</span>
                        </div>
                      </a>
                    </li>

                    <li>
                      <a href="#">
                        <div class="clearfix">
                          <span class="pull-left">
                            <i class="btn btn-xs no-hover btn-info fa fa-twitter"></i>
                            Followers
                          </span>
                          <span class="pull-right badge badge-info">+11</span>
                        </div>
                      </a>
                    </li>
                  </ul>
                </li>

                <li class="dropdown-footer">
                  <a href="#">
                    See all notifications
                    <i class="ace-icon fa fa-arrow-right"></i>
                  </a>
                </li>
              </ul>
            </li>

            <li class="light-blue dropdown-modal">
              <a data-toggle="dropdown" href="#" class="dropdown-toggle">
                <img class="nav-user-photo" src="assets/images/avatars/user.jpg" alt="Jason's Photo" />
                <span class="user-info">
                  <small>Welcome,</small>
                  Jason
                </span>

                <i class="ace-icon fa fa-caret-down"></i>
              </a>

              <ul class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
                <li>
                  <a href="#">
                    <i class="ace-icon fa fa-cog"></i>
                    Settings
                  </a>
                </li>

                <li>
                  <a href="profile.html">
                    <i class="ace-icon fa fa-user"></i>
                    Profile
                  </a>
                </li>

                <li class="divider"></li>

                <li>
                  <a href="<?php echo base_url('admin/auth/logout');?>">
                    <i class="ace-icon fa fa-power-off"></i>
                    Logout
                  </a>
                </li>
              </ul>
            </li>
          </ul>
        </div>
      </div><!-- /.navbar-container -->
    </div>

    <div class="main-container ace-save-state" id="main-container">
      <script type="text/javascript">
        try{ace.settings.loadState('main-container')}catch(e){}
      </script>

      <div id="sidebar" class="sidebar                  responsive                    ace-save-state">
        <script type="text/javascript">
          try{ace.settings.loadState('sidebar')}catch(e){}
        </script>

        <div class="sidebar-shortcuts" id="sidebar-shortcuts">
          <div class="sidebar-shortcuts-large" id="sidebar-shortcuts-large">
            <button class="btn btn-success">
              <i class="ace-icon fa fa-signal"></i>
            </button>
            <button class="btn btn-info">
              <i class="ace-icon fa fa-pencil"></i>
            </button>
            <button class="btn btn-warning">
              <i class="ace-icon fa fa-users"></i>
            </button>
            <button class="btn btn-danger">
              <i class="ace-icon fa fa-cogs"></i>
            </button>
          </div>
          <div class="sidebar-shortcuts-mini" id="sidebar-shortcuts-mini">
            <span class="btn btn-success"></span>
            <span class="btn btn-info"></span>
            <span class="btn btn-warning"></span>
            <span class="btn btn-danger"></span>
          </div>
        </div><!-- /.sidebar-shortcuts -->

        <ul class="nav nav-list">
          <li class="<?php echo($this->selected_tab == 'dashboard' ? 'active' : ''); ?>">
            <a href="<?php echo base_url('dashboard/index');?>">
              <i class="menu-icon fa fa-tachometer"></i>
              <span class="menu-text"> Dashboard </span>
            </a>
            <b class="arrow"></b>
          </li>

          <li class="<?php echo($this->selected_tab == 'users' ? 'active' : ''); ?>">
            <a href="<?php echo base_url('admin/users/index');?>">
              <i class="menu-icon fa fa-users"></i>
              <span class="menu-text"> Users </span>
            </a>
            <b class="arrow"></b>
          </li>

          <li class="<?php echo($this->selected_tab == 'companies' ? 'active' : ''); ?>">
            <a href="<?php echo base_url('admin/companies/index');?>">
              <i class="menu-icon fa fa-list"></i>
              <span class="menu-text"> Companies </span>
            </a>
            <b class="arrow"></b>
          </li>

          <li class="<?php echo($this->selected_tab == 'leads' ? 'active' : ''); ?>">
            <a href="<?php echo base_url('admin/leads/index');?>">
              <i class="menu-icon fa fa-list"></i>
              <span class="menu-text"> Leads </span>
            </a>
            <b class="arrow"></b>
          </li>
          <li class="<?php echo($this->selected_tab == 'api_leads' ? 'active' : ''); ?>">
            <a href="<?php echo base_url('admin/api_leads/index');?>">
              <i class="menu-icon fa fa-list"></i>
              <span class="menu-text"> API Leads </span>
            </a>
            <b class="arrow"></b>
          </li>
          <li class="<?php echo($this->selected_tab == 'invalid_leads' ? 'active' : ''); ?>">
            <a href="<?php echo base_url('admin/leads/invalid_leads');?>">
              <i class="menu-icon fa fa-list"></i>
              <span class="menu-text"> Invalid Leads </span>
            </a>
            <b class="arrow"></b>
          </li>
          <li class="<?php echo($this->selected_tab == 'manual_orders' ? 'active' : ''); ?>">
            <a href="<?php echo base_url('admin/orders/manual_orders');?>">
              <i class="menu-icon fa fa-list"></i>
              <span class="menu-text"> Manual Orders </span>
            </a>
            <b class="arrow"></b>
          </li>
          <li class="<?php echo($this->selected_tab == 'apis' ? 'active' : ''); ?>">
            <a href="<?php echo base_url('admin/apis/');?>">
              <i class="menu-icon fa fa-list"></i>
              <span class="menu-text"> APIs </span>
            </a>
            <b class="arrow"></b>
          </li>
          <li class="<?php echo ($this->selected_tab == 'user_apis' ? 'active' : ''); ?>">
            <a href="<?php echo base_url('admin/user_apis/');?>">
              <i class="menu-icon fa fa-list"></i>
              <span class="menu-text">Users APIs</span>
            </a>
            <b class="arrow"></b>
          </li>
          <li class="<?php echo ($this->selected_tab == 'phone_numbers' ? 'active' : ''); ?>">
            <a href="<?php echo base_url('admin/phone_numbers/');?>">
              <i class="menu-icon fa fa-list"></i>
              <span class="menu-text">Phone Numbers</span>
            </a>
            <b class="arrow"></b>
          </li>
          <li class="<?php echo ($this->selected_tab == 'setting' ? 'active' : ''); ?>">
            <a href="<?php echo base_url('admin/Settings/');?>">
              <i class="menu-icon fa fa-list"></i>
              <span class="menu-text">Settings</span>
            </a>
            <b class="arrow"></b>
          </li>

        </ul><!-- /.nav-list -->

        <div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
          <i id="sidebar-toggle-icon" class="ace-icon fa fa-angle-double-left ace-save-state" data-icon1="ace-icon fa fa-angle-double-left" data-icon2="ace-icon fa fa-angle-double-right"></i>
        </div>
      </div>

      <div class="main-content">
        <div class="main-content-inner">
          <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
              <li>
                <i class="ace-icon fa fa-home home-icon"></i>
                <a href="#">Home</a>
              </li>
              <li class="active">Dashboard</li>
            </ul><!-- /.breadcrumb -->

            <div class="nav-search" id="nav-search">
              <form class="form-search">
                <span class="input-icon">
                  <input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input" autocomplete="off" />
                  <i class="ace-icon fa fa-search nav-search-icon"></i>
                </span>
              </form>
            </div><!-- /.nav-search -->
          </div>

          {_yield}

        </div>
      </div><!-- /.main-content -->

      <div class="footer">
        <div class="footer-inner">
          <div class="footer-content">
            <span class="bigger-120">
              <span class="blue bolder">Ace</span>
              Application &copy; 2013-2014
            </span>

            &nbsp; &nbsp;
            <span class="action-buttons">
              <a href="#">
                <i class="ace-icon fa fa-twitter-square light-blue bigger-150"></i>
              </a>

              <a href="#">
                <i class="ace-icon fa fa-facebook-square text-primary bigger-150"></i>
              </a>

              <a href="#">
                <i class="ace-icon fa fa-rss-square orange bigger-150"></i>
              </a>
            </span>
          </div>
        </div>
      </div>

      <a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
        <i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
      </a>
    </div><!-- /.main-container -->

    <script type="text/javascript">
      if('ontouchstart' in document.documentElement) document.write("<script src='assets/js/jquery.mobile.custom.min.js'>"+"<"+"/script>");
    </script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/jquery-ui.custom.min.js"></script>
    <script src="assets/js/jquery.ui.touch-punch.min.js"></script>
    <script src="assets/js/jquery.easypiechart.min.js"></script>
    <script src="assets/js/jquery.sparkline.index.min.js"></script>
    <script src="assets/js/jquery.flot.min.js"></script>
    <script src="assets/js/jquery.flot.pie.min.js"></script>
    <script src="assets/js/jquery.flot.resize.min.js"></script>
    <!-- ace scripts -->
    <script src="assets/js/ace-elements.min.js"></script>
    <script src="assets/js/ace.min.js"></script>

    <script src="assets/datatables/datatables.min.js" type="text/javascript"></script>
    <script src="assets/datatables/datatables.all.min.js" type="text/javascript"></script>
    <script src="assets/datatables/plugins/bootstrap/datatables.bootstrap.js" type="text/javascript"></script>
    <script src="assets/developer_js/table-advanced.js" type="text/javascript"></script>
    <script src="assets/developer_js/sfs_functions.js" type="text/javascript"></script>
    <script type="text/javascript" src="assets/bootbox/bootbox.min.js"></script>
    <script src="assets/bootstrap-notify/bootstrap-notify.min.js" type="text/javascript"></script>
    <!--<script src="assets/sweetalerts/sweetalert2.js"></script>-->
    <script src="assets/sweetalerts/sweetalert.min.js" type="text/javascript"></script>
    <script src="assets/sweetalerts/ui-sweetalert.min.js" type="text/javascript"></script>
    <script src="assets/select2/js/select2.full.min.js" type="text/javascript"></script>
    <script src="assets/developer_js/components-select2.js" type="text/javascript"></script>
    <script type="text/javascript">
    $(document).ready(function(){
      $('.select2').select2();
    });
    </script>

  </body>
</html>

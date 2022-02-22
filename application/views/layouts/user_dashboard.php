<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
<meta name="description" content="">
<meta name="author" content="">
<link rel="icon" href="">

<title>My Eco Leads</title>
<base href="<?php echo base_url(); ?>">
<!-- Bootstrap core CSS -->
<link rel="stylesheet" href="assets/css/bootstrap.min.css" />
<link href="assets/public/css/bootstrap-theme.min.css" rel="stylesheet">

<link href="assets/public/css/theme.css" rel="stylesheet">

<link href="assets/datatables/datatables.min.css" rel="stylesheet" type="text/css"/>
<link href="assets/datatables/dataTables.customLoader.walker.css" rel="stylesheet" type="text/css"/>
<link href="assets/datatables/plugins/bootstrap/datatables.bootstrap.css" rel="stylesheet" type="text/css"/>

<link rel="stylesheet" type="text/css" href="assets/sweetalerts/sweetalert2.scss">
<link href="assets/sweetalerts/sweetalert.css" rel="stylesheet" type="text/css" />
<link href="assets/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
<link href="assets/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />

<!-- Custom styles for this template -->
<link href="assets/public/css/admin.css" rel="stylesheet">

<link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,900" rel="stylesheet"> 
<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Poppins:400,700" rel="stylesheet">  

<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<link href="assets/public/assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet">

<link href="assets/public/css/font-awesome.min.css" rel="stylesheet" type="text/css" media="all">

<script type="text/javascript">
var base_url = '<?php echo base_url();?>';
</script>
<script src="assets/js/jquery-2.1.4.min.js"></script>
<script src="assets/public/assets/js/ie-emulation-modes-warning.js"></script>
</head>

<body>

<div class="container-fluid top-header">
<div class="row">
<div class="col-lg-4 col-md-3 col-sm-4 top-logo">
<img src="assets/public/images/logo-mel.png" alt="" />
</div>
<div class="col-lg-8 col-md-9 col-sm-8 col-xs-12 nav-top">
<nav class="b-nav">
<div class="navbar-header">
	<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#nav"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
</div>
<div class="b-nav__list pull-right">
	<div class="collapse navbar-collapse navbar-main-slide" id="nav">
        <ul class="navbar-nav-menu">
            <li><a class="<?php echo (isset($this->selected_tab) && $this->selected_tab=='dashboard')?'active':''; ?>" href="<?php echo base_url('dashboard/');?>">Dashboard</a></li>
            <li class="dropdown">
                <a class="dropdown-toggle <?php echo (isset($this->selected_tab) && $this->selected_tab=='account')?'active':''; ?>" data-toggle="dropdown" href="#">Account<span class="fa fa-caret-down"></span></a>
                <ul class="dropdown-menu h-nav">
                    <li>
                        <a href="<?php echo base_url('auth/update/');?>">Edit Profie</a>
                    </li>
                    <li>
                        <a href="<?php echo base_url('change-password');?>">Change Password</a>
                    </li>
                    <li>
                        <a href="<?php echo base_url('auth/logout/');?>">Logout</a>
                    </li>
                </ul>
            </li>
            <!-- <li><a class="<?php //echo (isset($this->selected_tab) && $this->selected_tab=='api_leads')?'active':''; ?>" href="<?php //echo base_url('api_leads/');?>">API Leads</a></li> -->
            <!-- <li><a class="<?php //echo (isset($this->selected_tab) && $this->selected_tab=='user_api')?'active':''; ?>" href="<?php //echo base_url('apis/');?>">APIs</a></li> -->
            <li><a class="<?php echo (isset($this->selected_tab) && $this->selected_tab=='companies')?'active':''; ?>" href="<?php echo base_url('companies/');?>">My Company</a></li>
            <li><a class="<?php echo (isset($this->selected_tab) && $this->selected_tab=='orders')?'active':''; ?>" href="<?php echo base_url('orders/');?>">Orders</a></li>
            
        </ul>

		<!-- <ul class="navbar-nav-menu">
            <li><a class="<?php echo (isset($this->selected_tab) && $this->selected_tab=='dashboard')?'active':''; ?>" href="<?php echo base_url('dashboard/');?>">Dashboard</a></li>
            <li><a class="<?php echo (isset($this->selected_tab) && $this->selected_tab=='companies')?'active':''; ?>" href="<?php echo base_url('companies/');?>">My Company</a></li>
            <li><a class="<?php echo (isset($this->selected_tab) && $this->selected_tab=='orders')?'active':''; ?>" href="<?php echo base_url('orders/');?>">Orders</a></li>
			<li class="dropdown">
                <a class="dropdown-toggle <?php echo (isset($this->selected_tab) && $this->selected_tab=='account')?'active':''; ?>" data-toggle="dropdown" href="#">Account<span class="fa fa-caret-down"></span></a>
                <ul class="dropdown-menu h-nav">
                    <li>
                        <a href="<?php echo base_url('auth/update/');?>">Edit Profie</a>
                    </li>
                    <li>
                        <a href="<?php echo base_url('change-password');?>">Change Password</a>
                    </li>
                    <li>
                        <a href="<?php echo base_url('auth/logout/');?>">Logout</a>
                    </li>
                </ul>
            </li>
		</ul> -->
	</div>
</div>
</nav>
</div>
</div>                
</div>

<!-- <div class="container-fluid a-navbar">
<div class="">

<nav class="a-nav">
<div class="navbar-header">
<button type="button" class="navbar-toggle gray-bg" data-toggle="collapse" data-target="#nav2"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
</div>

<div class="a-nav__list">
<div class="collapse navbar-collapse navbar-main-slide" id="nav2">
<ul class="navbar-nav-menu">
<li><a class="" href="#">Dashboard</a></li>
<li><a href="#">Leads</a></li>

<li class="dropdown">
    <a class="dropdown-toggle" data-toggle="dropdown" href="#">Reports<span class="fa fa-caret-down"></span></a>
    <ul class="dropdown-menu h-nav">
        <li><a href="compare.html">Settings 1 </a>
        </li>
        <li><a href="shipping.html">Settings 2</a>
        </li>
        <li><a href="contact.html">Settings 3</a>
        </li>

    </ul>
</li>



<li class="dropdown">
    <a class="dropdown-toggle" data-toggle="dropdown" href="#">Settings<span class="fa fa-caret-down"></span></a>
    <ul class="dropdown-menu h-nav">
        <li><a href="compare.html">Settings 1 </a>
        </li>
        <li><a href="shipping.html">Settings 2</a>
        </li>
        <li><a href="contact.html">Settings 3</a>
        </li>

    </ul>
</li>
 <li><a href="#">Integrations</a></li>

</ul>
</div>
</div>
</nav>        
</div>
</div> -->

{_yield}

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 copyright"> 
<p align="center">Copyright 2022 My Eco Leads. All Rights Reserved.</p>
</div>

<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script>
window.jQuery || document.write('<script src="assets/public/assets/js/vendor/jquery.min.js"><\/script>')
</script>
<script src="assets/public/js/bootstrap.min.js"></script>
<script src="assets/public/js/docs.min.js"></script>
<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<script src="assets/public/assets/js/ie10-viewport-bug-workaround.js"></script>
<script src="assets/bootstrap-notify/bootstrap-notify.min.js" type="text/javascript"></script>

<script src="assets/datatables/datatables.min.js" type="text/javascript"></script>
<script src="assets/datatables/datatables.all.min.js" type="text/javascript"></script>
<script src="assets/datatables/plugins/bootstrap/datatables.bootstrap.js" type="text/javascript"></script>
<script src="assets/developer_js/table-advanced.js" type="text/javascript"></script>
<script src="assets/developer_js/sfs_functions.js" type="text/javascript"></script>
<script type="text/javascript" src="assets/bootbox/bootbox.min.js"></script>
<script src="assets/bootstrap-notify/bootstrap-notify.min.js" type="text/javascript"></script>
<script src="assets/sweetalerts/sweetalert2.js"></script>
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

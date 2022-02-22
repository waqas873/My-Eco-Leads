<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<meta charset="utf-8" />
	<title>Login WLC</title>
	<meta name="description" content="User login page" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
	<!-- bootstrap & fontawesome -->
	<base href="<?php echo base_url(); ?>">
	<link rel="stylesheet" href="assets/css/bootstrap.min.css" />
	<link rel="stylesheet" href="assets/font-awesome/4.5.0/css/font-awesome.min.css" />
	<!-- text fonts -->
	<link rel="stylesheet" href="assets/css/fonts.googleapis.com.css" />
	<!-- ace styles -->
	<link rel="stylesheet" href="assets/css/ace.min.css" />
	<link rel="stylesheet" href="assets/css/ace-rtl.min.css" />
	<link rel="stylesheet" type="text/css" href="assets/sweetalerts/sweetalert2.scss">
    <link href="assets/sweetalerts/sweetalert.css" rel="stylesheet" type="text/css" />
    <link href="assets/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript">
	  var base_url = '<?php echo base_url();?>';
	</script>
	<script src="assets/js/jquery-2.1.4.min.js"></script>
</head>

<body class="login-layout blur-login">
	<div class="main-container">
		<div class="main-content">
			<div class="row">
				<div class="col-sm-10 col-sm-offset-1">
					<div class="login-container">
						<div class="center">
							<h1>
								<i class="ace-icon fa fa-leaf green"></i>
								<span class="red">Web</span>
								<span class="white" id="id-text2">Leed</span>
							</h1>
							<h4 class="light-blue" id="id-company-text">&copy; Company</h4>
						</div>

						<div class="space-6"></div>

						<div class="position-relative">
							{_yield}	
						</div><!-- /.position-relative -->

					</div>
				</div><!-- /.col -->
			</div><!-- /.row -->
		</div><!-- /.main-content -->
	</div><!-- /.main-container -->

	<!-- basic scripts -->
<script type="text/javascript">
	if('ontouchstart' in document.documentElement) document.write("<script src='assets/js/jquery.mobile.custom.min.js'>"+"<"+"/script>");
</script>
<script src="assets/bootstrap-notify/bootstrap-notify.min.js" type="text/javascript"></script>
<script src="assets/sweetalerts/sweetalert2.js"></script>
<script src="assets/sweetalerts/sweetalert.min.js" type="text/javascript"></script>
<script src="assets/sweetalerts/ui-sweetalert.min.js" type="text/javascript"></script>
<script src="assets/select2/js/select2.full.min.js" type="text/javascript"></script>
<script src="assets/developer_js/components-select2.js" type="text/javascript"></script>

</body>

</html>

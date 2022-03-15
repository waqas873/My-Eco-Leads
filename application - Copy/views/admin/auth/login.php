<style type="text/css">
  .error{
    color: red;
  }
  .login_errors p{
    color: red !important;
  }
</style>
<div id="login-box" class="login-box visible widget-box no-border">
	<div class="widget-body">
		<div class="widget-main">
			<h4 class="header blue lighter bigger">
				<i class="ace-icon fa fa-coffee green"></i>
				Please Enter Your Information
			</h4>
			<div class="space-6"></div>
			<form id="login_form" method="post" action="">
				<input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>" class="csrf_token" />
				<fieldset>
					<label class="block clearfix">
						<span class="block input-icon input-icon-right">
							<input type="email" name="email" class="form-control" placeholder="email" />
							<i class="ace-icon fa fa-user"></i>
						</span>
					</label>
					<span class="login_errors" id="email_error"></span>
					<label class="block clearfix">
						<span class="block input-icon input-icon-right">
							<input type="password" name="password" class="form-control" placeholder="Password" />
							<i class="ace-icon fa fa-lock"></i>
						</span>
					</label>
					<span class="login_errors" id="password_error"></span>    
					<div class="space"></div>
					<div class="clearfix">
						<button type="button" id="login_btn" class="width-35 pull-right btn btn-sm btn-primary">
							<i class="ace-icon fa fa-key"></i>
							<span class="bigger-110">Login</span>
						</button>
					</div>
					<div class="space-4"></div>
				</fieldset>
			</form>
		</div><!-- /.widget-main -->

		<div class="toolbar clearfix">
			<!-- <div>
				<a href="#" data-target="#forgot-box" class="forgot-password-link">
					<i class="ace-icon fa fa-arrow-left"></i>
					I forgot my password
				</a>
			</div> -->
		</div>
	</div><!-- /.widget-body -->
</div><!-- /.login-box -->
<script src="assets/developer_js/admin/auth.js" type="text/javascript"></script>
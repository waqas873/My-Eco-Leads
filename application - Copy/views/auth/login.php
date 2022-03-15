<style type="text/css">
  .error{
    color: red;
  }
  .login_errors p{
    color: red !important;
  }
</style>
<?php if($this->session->flashdata('success_message')) { ?>
  <script type="text/javascript">
    $(document).ready(function(){
      swal({
        type: 'success',
        title: 'Success!',
        text: "<?php echo $this->session->flashdata('success_message'); ?>"
      })
    });
  </script>
<?php } ?>
<?php if($this->session->flashdata('error_message')) { ?>
  <script type="text/javascript">
    $(document).ready(function(){
      swal({
        type: 'error',
        title: 'Error!',
        text: "<?php echo $this->session->flashdata('error_message'); ?>"
      })
    });
  </script>
<?php } ?>

<div class="container-fluid login-page">
   <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="login-section">
                <div class="hand-icon"><img src="assets/public/images/hand.png" width="86" height="52" alt=""/></div>
                <h1>Login</h1>
                <form id="login_form" method="post" action="">
                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>" class="csrf_token" />

                <input  type="text" name="email" value="" placeholder="Email Address">
                <span class="login_errors" id="email_error"></span>
                <input  type="password" name="password" value="" placeholder="Password">  
                <span class="login_errors" id="password_error"></span>       
                <a href="javascript::" id="login_btn" class="login-btn">Login</a>
                </form>
                <a href="<?php echo base_url('forgot-password');?>">Forgot your password?</a> </div>
             
            </div>
        </div>
    </div>     
</div> 
<script src="assets/developer_js/auth.js" type="text/javascript"></script>
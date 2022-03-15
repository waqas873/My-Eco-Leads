<style type="text/css">
  .error{
    color: red;
  }
  .all_errors p{
    color: red !important;
  }
  #reset_password_btn{
    width: 192px !important;
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
                <h1>Reset Password</h1>
                <form id="reset_password_form" method="post" action="">
                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>" class="csrf_token" />
                <input type="hidden" name="user_id" value="<?php echo (isset($user))?$user['user_id']:''; ?>" />
                <input  type="password" class="all_inputs" name="password" value="" placeholder="Password">  
                <span class="all_errors" id="password_error"></span>
                <input  type="password" class="all_inputs" name="cpassword" value="" placeholder="Confirm Password">  
                <span class="all_errors" id="cpassword_error"></span>       
                <a href="javascript::" id="reset_password_btn" class="login-btn">Reset Password</a>
                </form>
                <a href="<?php echo base_url('sign-in');?>">Login</a> </div>
                
            </div>
        </div>
    </div>     
</div> 
<script src="assets/developer_js/auth.js" type="text/javascript"></script>
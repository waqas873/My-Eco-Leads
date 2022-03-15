<style type="text/css">
  .error{
    color: red;
  }
  .all_errors{
    position: relative;
    top: -8px;
  }
  .all_errors p{
    color: red !important;
  }
  .interests{
    width: 100%;
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

<div id="signup-box" class="signup-box widget-box no-border visible">
  <div class="widget-body">
    <div class="widget-main">
      <h4 class="header green lighter bigger">
        <i class="ace-icon fa fa-users blue"></i>
        New User Registration
      </h4>
      <div class="space-6"></div>
      <p> Enter your details to begin: </p>
      <form id="register_form" class="register_form" method="post">
        <fieldset>

          <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>" class="csrf_token" />

          <label class="block clearfix">
            <span class="block input-icon input-icon-right">
              <input type="text" class="form-control" name="first_name" placeholder="First Name" />
              <i class="ace-icon fa fa-user"></i>
            </span>
          </label>
          <span class="all_errors" id="first_name_error"></span>

          <label class="block clearfix">
            <span class="block input-icon input-icon-right">
              <input type="text" class="form-control" name="last_name" placeholder="Last Name" />
              <i class="ace-icon fa fa-user"></i>
            </span>
          </label>
          <span class="all_errors" id="last_name_error"></span>

          <label class="block clearfix">
            <span class="block input-icon input-icon-right">
              <input type="email" class="form-control" name="email" placeholder="Email" />
              <i class="ace-icon fa fa-envelope"></i>
            </span>
          </label>
          <span class="all_errors" id="email_error"></span>

          <label class="block clearfix">
            <span class="block input-icon input-icon-right">
              <input type="password" class="form-control" name="password" placeholder="Password" />
              <i class="ace-icon fa fa-lock"></i>
            </span>
          </label>
          <span class="all_errors" id="password_error"></span>

          <label class="block clearfix">
            <span class="block input-icon input-icon-right">
              <input type="password" class="form-control" name="cpassword" placeholder="Repeat password" />
              <i class="ace-icon fa fa-retweet"></i>
            </span>
          </label>
          <span class="all_errors" id="cpassword_error"></span>
          
          <span class="lbl">
            Interests:
          </span>
          <label class="block clearfix">
            <span class="block">
              <select name="ineterest_id[]" multiple="multiple" class="interests select2">
                <?php 
                  if(isset($interests) && !empty($interests)){
                    foreach ($interests as $row) {
                ?>
                <option value="<?php echo $row['interest_id'];?>"><?php echo $row['name'];?></option>
                <?php } } ?>
              </select>
            </span>
          </label>
          <!-- <label class="block">
            <input type="checkbox" class="ace" />
            <span class="lbl">
              I accept the
              <a href="#">User Agreement</a>
            </span>
          </label> -->

          <div class="space-24"></div>

          <div class="clearfix">
            <button type="button" id="reset_btn" class="width-30 pull-left btn btn-sm">
              <i class="ace-icon fa fa-refresh"></i>
              <span class="bigger-110">Reset</span>
            </button>
            <button type="button" id="register_btn" class="width-65 pull-right btn btn-sm btn-success">
              <span class="bigger-110">Register</span>
              <i class="ace-icon fa fa-arrow-right icon-on-right"></i>
            </button>
          </div>
        </fieldset>
      </form>
    </div>

    <div class="toolbar center">
      <a href="<?php echo base_url('sign-in');?>" class="back-to-login-link">
        <i class="ace-icon fa fa-arrow-left"></i>
        Back to login
      </a>
    </div>
  </div><!-- /.widget-body -->
</div><!-- /.signup-box -->

<script src="assets/developer_js/auth.js" type="text/javascript"></script>










<style type="text/css">
  .error{
    color: red;
  }
  .all_errors p{
    color: red !important;
  }
  .labels{
    color: #AA3F97 !important;
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
                <h1>Send Message</h1>
                <form id="send_sms_form" method="post" action="">
                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>" class="csrf_token" />
                
                <textarea name="msg_content" class="all_inputs" id="msg_content" style="width: 100% !important;margin-bottom: 20px;"></textarea>
                <span class="all_errors" id="msg_content_error"></span>

                <button type="button" id="send_sms_btn" class="btn btn-primary creat-btn">Send Message</button>
                </form>
                <br/>
            </div>
        </div>
    </div>     
</div> 
<script src="assets/developer_js/messages.js" type="text/javascript"></script>

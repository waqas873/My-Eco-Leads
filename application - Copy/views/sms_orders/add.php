<style type="text/css">
  .error{
    color: red;
  }
  .errors p{
    color: red !important;
  }
  .order_msg{
    margin: auto;
    width: 80%;
    margin-top: 20px;
    margin-bottom: 20px;
    font-size: 17px;
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
                <h1>Enter SMS</h1>
                <form id="order_form" method="post" action="">
                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>" class="csrf_token" />

                <input  type="number" name="total_sms" value="" placeholder="Enter no of sms" class="all_inputs">
                <span class="errors" id="total_sms_error"></span>
                <a href="javascript::" id="order_btn" class="login-btn">Order</a>
                </form> </div>
             
            </div>
        </div>
    </div>     
</div>

<script src="assets/developer_js/sms_orders.js" type="text/javascript"></script>
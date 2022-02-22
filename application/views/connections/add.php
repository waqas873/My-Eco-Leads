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
                <h1>Get Number</h1>
                <form id="get_twilio_num_form" method="post" action="">
                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>" class="csrf_token" />

                <!-- <input type="text" id="country_code" class="all_inputs" name="country_code" placeholder="Enter Country Code">
                <span class="all_errors" id="country_code_error"></span> -->

                <div class="form-group" id="available_numbers">
                <?php 
                  if(!empty($numbers)){
                    echo $numbers;
                  }
                ?>
                </div>
                <span class="all_errors" id="twilio_number_error"></span>

                <!-- <button type="button" id="fetch_numbers_btn" class="btn btn-primary creat-btn">Fetch Numbers</button> -->
                <button type="button" id="purchase_btn" class="btn btn-primary creat-btn">Purchase</button>
                </form>
                <br/>
            </div>
        </div>
    </div>     
</div> 
<script src="assets/developer_js/connections.js" type="text/javascript"></script>

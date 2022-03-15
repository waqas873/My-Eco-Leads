<style type="text/css">
  .error{
    color: red;
  }
  .errors p{
    color: red !important;
    margin-top: -15px;
  }
  .questions{
    color: #F3C42C;
    font-size: 21px !important;
  }
  .answers{
    margin-top: -15px !important;
    font-size: 18px !important;
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

<div class="container-fluid contact-page">
   <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="dashboard-section">     
                <h1>Company <span class="orange-text">Registration</span></h1>
               <hr>
              <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                  <form id="company_form" action="" method="post">
                  <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>" class="csrf_token" />

			            <p><strong>Please fill in your company information below.</strong></p>
                  <p class="questions">Why do we ask you for this information?</p>
                  <p class="answers">We need to know that you are a working trading and regulated company.</p>
                  <p class="questions">Do I have to put all the information in?</p>
                  <p class="answers">Yes, we need the following information to make sure that we can contact you and deliver all leads.</p>

                  <input type="text" value="<?php echo (isset($data))?$data['user_name']:''; ?>" name="user_name" placeholder="Name">
                  <span class="errors" id="user_name_error"></span>
                  <input  type="text" value="<?php echo (isset($data))?$data['name']:''; ?>" name="name" placeholder="Company Name">
                  <span class="errors" id="name_error"></span>
                  <input  type="text" value="<?php echo (isset($data))?$data['registration_no']:''; ?>" name="registration_no" placeholder="Company Reg Number">
                  <span class="errors" id="registration_no_error"></span>
                  <input  type="text" value="<?php echo (isset($data))?$data['fca_license_no']:''; ?>" name="fca_license_no" placeholder="Fca or AR license number">
                  <span class="errors" id="fca_license_no_error"></span>
                 <input  type="text" value="<?php echo (isset($data))?$data['address']:''; ?>" name="address" placeholder="Address">
                 <span class="errors" id="address_error"></span>
                 <input  type="text" value="<?php echo (isset($data))?$data['contact_no']:''; ?>" name="contact_no" placeholder="Enter contact no">
                 <span class="errors" id="contact_no_error"></span>
                  <a href="javascript::" id="company_btn" class="dashboard-btn">Save Company</a>
                  </form>
              </div>
                     
             </div> 
            
            </div>
        </div>
    </div>     
</div> 

</div>

<script src="assets/developer_js/companies.js" type="text/javascript"></script>
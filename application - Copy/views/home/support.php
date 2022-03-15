<style type="text/css">
  .error{
    color: red;
  }
  .errors p{
    color: red !important;
    margin-top: -15px;
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
                         
                        <h1>Support <span class="orange-text"></span></h1>
                        <p>Please leave the best time to call you. And also leave your best contact number</p>
						
                        <hr>
						<p align="center"><span class="text20">Have a great day from all us here at the My Eco Leads team.</span></p>
                       <hr>
                      <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<p><strong>Support Form</strong></p>
              <form id="support_form">
                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>" class="csrf_token" />
                <input class="all_inputs" name="name" type="text" value="" placeholder="Name">
                <span class="errors" id="name_error"></span>
                <input class="all_inputs" name="email" type="email" value="" placeholder="Email">
                <span class="errors" id="email_error"></span>
                <input class="all_inputs" name="phone_no" type="search" value="" placeholder="Phone">
                <span class="errors" id="phone_no_error"></span>
                <input class="all_inputs" name="subject" type="search" value="" placeholder="Subject">    
                <span class="errors" id="subject_error"></span>
                
                <textarea class="all_inputs" name="message" rows="6" placeholder="Your Message"></textarea>
                <span class="errors" id="message_error"></span>
                <a href="javascript::" class="dashboard-btn" id="send_message">Send Message</a>
              </form>
                      </div>
                             
                     </div> 
                     
                    </div>
                </div>
            </div>     
     </div> 
     
     </div>
<script src="assets/developer_js/contact_us.js" type="text/javascript"></script>
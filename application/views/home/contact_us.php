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
                         
                        <h1>Contact <span class="orange-text">Us</span></h1>
                        <p>If you would like support or if you would like to contact us feel free to contact us here, we are always ready to take your enquiry.</p>
						<p><span class="text20 orange-text">We offer 24/7 support,</span> if you would like to get instant chat support you can hit the bottom right of this screen and see if we have a little monster ready to chat away! </p>
                        <hr>
						<p align="center"><span class="text20">Have a great day from all us here at the call monster team.</span></p>
                       <hr>
                      <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<p><strong>Contact Us Form</strong></p>
              <form id="contact_us_form">
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
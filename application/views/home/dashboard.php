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
<div class="container-fluid dashboard-page">
   <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

            <div class="dashboard-section">
                 
                <h1>Welcome <span class="orange-text">Alice</span></h1>
  	<span class="text20">So your ready to explode your business with phone calls? <span class="orange-text">Great!</span></span>
                <p>We are ready to start sending you calls straight from the customers looking for your service in your area.
  Simply fill in the fields below and we will generate you your custom offer, once you accept the offer the monster will get to work getting phone calls in your area direct to your phone!</p>
  <p>All you have to do is answer the phone and quote the job and simply do what you do best, and that’s running your business.</p>
  	<p>Once we create offer, I think it needs to interact bit more it says <span class="text20 orange-text">“Offer saved successfully”</span> can you add text under that as well, saying <span class="text20">“My Eco Leads is running around the internet getting the best price for your calls we will send you your unique offer very soon, sit tight its on its way”</span></p><br>
               
              <div class="row">
              <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                  <input  type="search" value="" placeholder="Provide Your Business Name">
                  <input  type="search" value="" placeholder="Alice">
                  <input  type="search" value="" placeholder="alice@website.com">                          

                  <select class="form-control" name="size">
                        <option value="">Select Country</option>
                  </select>

  			  <select class="form-control" name="size">
                        <option value="">Select State</option>
                  </select>
                  
                  <select class="form-control" name="size">
                        <option value="">Select City</option>
                  </select> 

                  <input  type="search" value="" placeholder="Services Keyword">
                  <input  type="search" value="" placeholder="Contact Number 1">
                  <input  type="search" value="" placeholder="Contact Number 2">
                                         
                  <a href="#" class="dashboard-btn">Save</a>
              </div>
              
              <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                  <input  type="search" value="" placeholder="Number of Calls e.g(10)">
                  <a href="#" class="dashboard-btn">Create Offer</a>
              </div>
             </div> 
             
            </div>
        </div>
    </div>     
  </div> 

  </div>
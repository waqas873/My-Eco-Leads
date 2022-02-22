<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <base href="<?php echo base_url(); ?>">
    <link rel="icon" href="">
    <title>My Eco Leads</title>
    <!-- Bootstrap core CSS -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap theme -->
    <link href="assets/public/css/bootstrap-theme.min.css" rel="stylesheet">
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="assets/public/assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="assets/public/css/theme.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,900" rel="stylesheet"> 
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Poppins:400,700" rel="stylesheet">  
    <link href="assets/public/css/font-awesome.min.css" rel="stylesheet" type="text/css" media="all">

    <link href="assets/datatables/datatables.min.css" rel="stylesheet" type="text/css"/>
    <link href="assets/datatables/dataTables.customLoader.walker.css" rel="stylesheet" type="text/css"/>
    <link href="assets/datatables/plugins/bootstrap/datatables.bootstrap.css" rel="stylesheet" type="text/css"/>

    <link rel="stylesheet" type="text/css" href="assets/sweetalerts/sweetalert2.scss">
    <link href="assets/sweetalerts/sweetalert.css" rel="stylesheet" type="text/css" />
    <link href="assets/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />

    <script type="text/javascript">
        var base_url = '<?php echo base_url();?>';
    </script>
    <script src="assets/js/jquery-2.1.4.min.js"></script>
    <script src="assets/public/assets/js/ie-emulation-modes-warning.js"></script>
  </head>

  <body>

<body>

    <div class="container-fluid top-header">

        <div class="container">
            <div class="row">

                <div class="col-lg-4 col-md-3 col-sm-4 col-xs-6 top-logo">
                    <img src="assets/public/images/logo-mel.png" alt="" />
                </div>

                  <!-- <div class="col-lg-8 col-md-9 col-sm-8 col-xs-6 nav-phone">
                   
                  <div class="header-social-link">
                    <ul class="list-unstyled social hover bg text-sm-center">
                    <li ><a class="facebook" data-toggle="tooltip" title="Facebook" href="#"><i class="fa fa-facebook"></i></a></li>
                    <li ><a class="twitter" data-toggle="tooltip" title="Twitter" href="#"><i class="fa fa-twitter"></i></a></li>
                    <li ><a class="linkedin" data-toggle="tooltip" title="linkedin" href="#"><i class="fa fa-linkedin"></i></a></li>
                    <li ><a class="googleplus" data-toggle="tooltip" title="Google" href="#"><i class="fa fa-google-plus"></i></a></li>
                    <li ><a class="pinterest" data-toggle="tooltip" title="Pinterest" href="#"><i class="fa fa-pinterest"></i></a></li>
                    <li ><a class="instagram" data-toggle="tooltip" title="Instagram" href="#"><i class="fa fa-instagram"></i></a></li>
                    </ul>
                </div>
                </div> -->
               
            </div>
        </div>
    </div> 
    
    
<div class="container-fluid nav-bg">

    <div class="container">
        <div class="row">
            <nav class="b-nav">
                <div class="b-nav__list wow slideInRight" data-wow-delay="0.3s">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#nav">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                    </div>
                    <div class="collapse navbar-collapse navbar-main-slide" id="nav">
                        <ul class="navbar-nav-menu">                 
                            <li><a class="<?php echo (isset($this->selected_tab) && $this->selected_tab=='home')?'active':''; ?>" href="<?php echo base_url('home/');?>">Home</a></li>
                            <?php if(!$this->session->userdata('user')) { ?>
                            <li><a class="<?php echo (isset($this->selected_tab) && $this->selected_tab=='auth')?'active':''; ?>" href="<?php echo base_url('sign-in');?>">Login</a></li>
                            <?php } ?>                                         
                        </ul>
                    </div>
                </div>

            </nav>
  </div>
  </div>
</div>

    {_yield}
  
 <div class="container-fluid bottom-links">
           <div class="container">             
              <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> 
              <ul>     
                <li><a href="<?php echo base_url('');?>">Home</a></li>                
                <li><a href="<?php echo base_url('home/about_us');?>">About Us</a></li>                
                <!-- <li><a href="quote.html">Get a Quote</a></li>
                <li><a href="terms-conditions.html">Terms and Conditions</a></li> -->
                <li><a href="<?php echo base_url('home/contact_us');?>">Contact</a></li>
                <li><a href="<?php echo base_url('home/support');?>">Support</a></li>
                <li><a href="<?php echo base_url('home/faqs');?>">FAQs</a></li>
            <li><a href="assets/public/images/Debt-Monster-Guidelines.pdf" target="_blank">Downloads</a></li>                  

             </ul>
                          
              </div>     
          </div>
      </div>
      
      
    
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 copyright"> 
        <p align="center">Copyright 2022 My Eco Leads. All Rights Reserved.</p>
  </div>

    



<!-- Popup Create Account form  -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script> -->
    <script>
        window.jQuery || document.write('<script src="assets/public/assets/js/vendor/jquery.min.js"><\/script>')
    </script>
    <script src="assets/public/js/bootstrap.min.js"></script>
    <script src="assets/public/js/docs.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="assets/public/assets/js/ie10-viewport-bug-workaround.js"></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js'></script>
    <script src='https://cdn.jsdelivr.net/jquery.slick/1.6.0/slick.min.js'></script>
    <script  src="assets/public/js/slider.js"></script>

    <script src="assets/datatables/datatables.min.js" type="text/javascript"></script>
    <script src="assets/datatables/datatables.all.min.js" type="text/javascript"></script>
    <script src="assets/datatables/plugins/bootstrap/datatables.bootstrap.js" type="text/javascript"></script>

    <script src="assets/developer_js/sfs_functions.js" type="text/javascript"></script>
    <script type="text/javascript" src="assets/bootbox/bootbox.min.js"></script>
    <script src="assets/bootstrap-notify/bootstrap-notify.min.js" type="text/javascript"></script>
    <script src="assets/sweetalerts/sweetalert2.js"></script>
    <script src="assets/sweetalerts/sweetalert.min.js" type="text/javascript"></script>
    <script src="assets/sweetalerts/ui-sweetalert.min.js" type="text/javascript"></script>
    <script src="assets/select2/js/select2.full.min.js" type="text/javascript"></script>
    <script src="assets/developer_js/components-select2.js" type="text/javascript"></script>
    <script type="text/javascript">
    $(document).ready(function(){
      $('.select2').select2();
    });
    </script>
 
</body>
</html>

<style type="text/css">
  .login-section p{
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
                <h1>Order Confirmation</h1>
                <p>You have ordered <?php echo (isset($total_leads))?$total_leads:'';?> leads. Total price of these leads is &pound;<?php echo (isset($total_amount))?$total_amount:'';?>. Please confirm your order.</p>
                <form action="<?php echo base_url('orders/process_payment');?>" method="POST" id="pay_form">
                  <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>" class="csrf_token" />
                  <?php 
                    // stripe uses amount in cents so convert dollars into cents.
                    $amount = $total_amount*100;
                  ?>
                  <script
                          src="https://checkout.stripe.com/checkout.js" class="stripe-button payButton"
                          data-key="<?php echo STRIPE_LIVE_PUB_KEY;?>"
                          data-amount="<?php echo $amount;?>"
                          data-name="WLC Billing"
                          data-description="Widget"
                          data-currency="gbp"
                          data-image="https://stripe.com/img/documentation/checkout/marketplace.png"
                          data-locale="auto">
                  </script>
                  <input type="hidden" id="amount" name="amount" value="<?php echo $total_amount;?>">
                  <input type="hidden" id="total_leads" name="total_leads" value="<?php echo $total_leads;?>">
                </form>
             </div>
            </div>
        </div>
    </div>     
</div>

<script src="assets/developer_js/orders.js" type="text/javascript"></script>
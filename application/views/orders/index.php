<style type="text/css">
/*.dashboard-page {
  background-image: none !important;
}*/
.dashboard-section {
  margin-top: 240px !important;
  width: 88% !important;
}
.no-padding-right{
  margin-top: 7px;
}
.click_here{
  color: #743876 !important;
  font-weight: 600;
}
.date_range{
    line-height: 15px !important;
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

<div class="container-fluid dashboard-page">
  <div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
      <div class="dashboard-section">
          <h1>List of Orders <span class="orange-text"></span></h1>
          <div class="row">
              <div class="col-md-3 col-sm-4 col-xs-4 col-md-offset-3"> 
                <div class="leades total">
                  <div class="no"><?php echo (isset($total_orders))?$total_orders:'' ;?></div>Total Orders</div>
              </div>
              <div class="col-md-3 col-sm-4 col-xs-4"> 
                <div class="leades quotable">
                  <div class="no"><span>&#163;</span><?php echo (isset($orders_amount))?$orders_amount:'' ;?></div>Total Orders Amount</div>
              </div>
          </div>
          
          <div class="row" style="margin-top: 20px;">
            <div class="col-md-4">
               <div class="form-group filters">
                    <label class="col-sm-2 control-label no-padding-right" for="form-field-1"> Status: </label>
                    <div class="col-sm-8">
                        <select class="status_filter all_filters select2" id="status_filter" name="status_filter">
                            <option value="">Select Status</option>
                            <option value="1">Completed</option>
                            <option value="0">Approve</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
               <div class="form-group filters">
                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> From Date: </label>
                    <div class="col-sm-8">
                        <input type="date" name="from_date" id="from_date" class="form-control date_range">
                    </div>
                </div>
            </div>
            <div class="col-md-4">
               <div class="form-group filters">
                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> To Date: </label>
                    <div class="col-sm-8">
                        <input type="date" name="to_date" id="to_date" class="form-control date_range">
                    </div>
                </div>
            </div>
          </div>
          <div class="row" style="margin-top: 20px;">
            <div class="col-md-2">
               <a href="<?php echo base_url('add_order');?>" class="dashboard-btn">New Order</a>
            </div>
          </div>
          <div class="row" style="margin-top: 20px;">
            <div class="col-md-12"> 
              <div class="table-list">        
                <table class="table-responsive table-bordered table-striped table-hover" width="100%" id="sample_orders">
                <thead>
                <tr>
                  <th>Ordered Leads</th>
                  <th>Remaining Leads</th>
                  <th>Order Price</th>
                  <th>Status</th>
                  <th>Leads</th>
                  <th>Order Date</th>
                </tr>
                </thead>
                </table>
              </div>
            </div>
          </div>
      </div>
    </div>
  </div>   
</div>

<script src="assets/developer_js/orders.js" type="text/javascript"></script>
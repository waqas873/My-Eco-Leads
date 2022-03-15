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
.value-box {
  border-radius: 5px;
  -moz-border-radius: 5px;
  -webkit-border-radius: 5px;
  color: #919191;
  background-color: #f5f5f5;
  border: 1px solid #CD83B7;
  padding: 12px 10px;
  text-align: center;
  font-family: 'Lato', sans-serif;
  font-weight: normal;
  font-size: 22px;
}
</style>

<div class="container-fluid dashboard-page">
  <div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
      <div class="dashboard-section">

        <input type="hidden" value="<?php echo (isset($order))?$order['order_id']:''; ?>" id="order_id">

          <h1>Delivered Leads of an Order <span class="orange-text"></span></h1>

          <div class="row">
            <div class="col-md-4 col-sm-4 col-xs-12"> 
              <div class="value-box"> Total Leads <b> <?php echo (isset($order))?$order['total_leads']:'0'; ?></b></div>
            </div>
            
            <div class="col-md-4 col-sm-4 col-xs-12"> 
              <div class="value-box">Delivered Leads <b> <?php echo (isset($order))?$order['total_leads']-$order['remaining_leads']:'0'; ?> </b></div>
            </div>
            
            <div class="col-md-4 col-sm-4 col-xs-12"> 
              <div class="value-box"> Remaining Leads  <b> <?php echo (isset($order))?$order['remaining_leads']:'0'; ?></b> </div>
            </div>
          </div>

          <!-- <div class="row" style="margin-top: 15px;">
            <div class="col-md-4">
               <div class="form-group filters">
                    <label class="col-sm-2 control-label no-padding-right" for="form-field-1"> Status: </label>
                    <div class="col-sm-8">
                        <select class="status_filter all_filters select2" id="status_filter" name="status_filter">
                            <option value="">Select Status</option>
                            <option value="1">Completed</option>
                            <option value="0">Pending</option>
                        </select>
                    </div>
                </div>
            </div>
          </div> -->
          <div class="row" style="margin-top: 20px;">
            <div class="col-md-12"> 
              <div class="table-list">        
                <table class="table-responsive table-bordered table-striped table-hover" width="100%" id="sample_leads">
                <thead>
                <tr>
                  <th>First Name</th>
                  <th>Last Name</th>
                  <th>Email</th>
                  <th>Mobile No</th>
                  <th>Status</th>
                  <th>Delivered Date</th>
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
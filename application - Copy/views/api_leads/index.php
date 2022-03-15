<style type="text/css">
.dashboard-section {
  margin-top: 240px !important;
  width: 88% !important;
}
.no-padding-right{
  margin-top: 7px;
}
.date_range{
    line-height: 15px !important;
}
.send_again{
  color: #CB85B7 !important;
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
          <h1>API Leads <span class="orange-text"></span></h1>
          
          <div class="row" style="margin-top: 20px;">
            <div class="col-md-4">
               <div class="form-group filters">
                    <label class="col-sm-2 control-label no-padding-right" for="form-field-1"> Status: </label>
                    <div class="col-sm-8">
                        <select class="status_filter all_filters select2" id="status_filter" name="status_filter">
                            <option value="">Select Status</option>
                            <option value="1">Success</option>
                            <option value="0">Failed</option>
                        </select>
                    </div>
                </div>
            </div>
          </div>
          <div class="row" style="margin-top: 20px;">
            <div class="col-md-12"> 
              <div class="table-list">        
                <table class="table-responsive table-bordered table-striped table-hover" width="100%" id="sample_api_leads">
                <thead>
                <tr>
                  <th>First Name</th>
                  <th>Last Name</th>
                  <th>Phone No</th>
                  <th>Status</th>
                  <th>Failed Attempts</th>
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

<script src="assets/developer_js/api_leads.js" type="text/javascript"></script>
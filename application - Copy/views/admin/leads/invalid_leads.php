<style type="text/css">
.all_filters{
    width: 100%;
}
.filters{
    margin-top: 6px;
}
.date_range{
    line-height: 15px !important;
}
.form-group > label{
    margin-top: 4px;
}
#status_filter{
    height: 34px !important;
}
.question {
    font-size: 17px;
    color: #F29200;
}
.answer {
    font-size: 17px;
}
.modal-title {
    font-size: 21px;
    color: #F29200;
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

<div class="page-content">
    <div class="page-header">
        <h1>Invalid Leads
			<small>
				<i class="ace-icon fa fa-angle-double-right"></i>
				All Invalid Leads
			</small>
		</h1>
    </div>
    <!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
            <div class="row">
                <div class="col-md-4">
                   <div class="form-group filters">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> From Date: </label>
                        <div class="col-sm-8">
                            <input type="date" name="from_date" id="in_from_date" class="form-control date_range">
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                   <div class="form-group filters">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> To Date: </label>
                        <div class="col-sm-8">
                            <input type="date" name="to_date" id="in_to_date" class="form-control date_range">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" style="margin-top: 10px;">
                <div class="col-md-4">
                    <div class="form-group filters">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> User: </label>
                        <div class="col-sm-8">
                            <select class="user_id all_filters select2" id="user_id" name="user_id">
                                <option value="">Select User</option>
                                <?php 
                                  if(isset($users) && !empty($users)){
                                    foreach ($users as $row) {
                                ?>
                                <option value="<?php echo $row['user_id'];?>"><?php echo $row['first_name'].' '.$row['last_name'];?></option>
                                <?php } } ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <a href="javascript::" id="deliver_leads" class="btn btn-success">Deliver Leads</a>
                </div>
            </div>
            <!-- PAGE CONTENT BEGINS -->
            <div class="row">
                <div class="col-xs-12">
                    <div class="clearfix">
                        <div class="pull-right tableTools-container"></div>
                    </div>
                    <div class="table-header">
                        All Leads Details
                    </div>
                    <div>
                        <form id="invalid_leads_form" action="<?php echo base_url('admin/leads/process_invalid_leads');?>" method="post">
                            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>" class="csrf_token" />
                            <input type="hidden" name="form_user_id" id="form_user_id">
                            <table id="sample_invalid_leads" class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Send Leads</th>
                                        <th>Force</th>
                                        <th>First Name</th>
                                        <th>Last Name</th>
                                        <th>Email</th>
                                        <th>Mobile No</th>
                                        <th>Info</th>
                                        <th>Creation Date</th>
                                    </tr>
                                </thead>
                            </table>
                        </form>
                    </div>
                </div>
            </div>

            <!-- PAGE CONTENT ENDS -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
</div>
<!-- /.page-content -->

<div class="modal" id="info_modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Lead Info</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p id="lead_info"></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script src="assets/developer_js/admin/leads.js" type="text/javascript"></script>
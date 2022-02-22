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
.modal-dialog {
    width: 549px !important;
}    
.all_inputs{
    width: 100%;
}
.modal-title{
    color: #307ECC;
}
.all_errors{
    color: red;
}
.question {
    font-size: 17px;
    color: #F29200;
}
.answer {
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
<div class="page-content">
    <div class="page-header">
        <h1>Phone Numbers
			<small>
				<i class="ace-icon fa fa-angle-double-right"></i>
				All Phone Numbers
			</small>
		</h1>
    </div>
    <!-- /.page-header -->
    <div class="row">
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
    <div class="row">
        <div class="col-xs-12">
            <div class="row">
                <div class="col-md-2">
                    <!-- <a href="javascript::" class="btn btn-success" id="add_api">Add New API</a> -->
                </div>
            </div>
            <!-- PAGE CONTENT BEGINS -->
            <div class="row">
                <div class="col-xs-12">
                    <div class="clearfix">
                        <div class="pull-right tableTools-container"></div>
                    </div>
                    <div class="table-header">
                        All Phone Numbers Details
                    </div>
                    <div>
                        <table id="sample_phone_numbers" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Phone Number</th>
                                    <th class="hidden-480">Valid</th>
                                    <th>View Response</th>
                                </tr>
                            </thead>
                        </table>
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

<div class="modal" id="view_response_modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Response Details</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p id="view_response"></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script src="assets/developer_js/admin/phone_numbers.js" type="text/javascript"></script>
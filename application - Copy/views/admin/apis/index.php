<style type="text/css">
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
.all_filters{
    width: 100%;
}
.filters{
    margin-top: 6px;
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
        <h1>APIs
			<small>
				<i class="ace-icon fa fa-angle-double-right"></i>
				All APIs
			</small>
		</h1>
    </div>
    <!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
            <div class="row">
                <div class="col-md-2">
                    <a href="javascript::" class="btn btn-success" id="add_api">Add New API</a>
                </div>
            </div>
            <!-- PAGE CONTENT BEGINS -->
            <div class="row">
                <div class="col-xs-12">
                    <div class="clearfix">
                        <div class="pull-right tableTools-container"></div>
                    </div>
                    <div class="table-header">
                        All APIs Details
                    </div>
                    <div>
                        <table id="sample_apis" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th class="hidden-480">Status</th>
                                    <th>Creation Date</th>
                                    <th>Action</th>
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

<div id="add_modal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">ADD API</h4>
      </div>
      <div class="modal-body">
        <form class="form-horizontal" role="form" id="add_api_form" action="" method="post">
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>" class="csrf_token" />
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label class="col-sm-2 control-label no-padding-right" for="form-field-1"> API Name: </label>
                        <div class="col-sm-10">
                            <input type="text" id="api_name" name="api_name" class="col-xs-12 col-sm-12 all_inputs" />
                            <span class="all_errors" id="api_name_error"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label class="col-sm-2 control-label no-padding-right" for="form-field-1">  </label>
                        <div class="col-sm-10">
                            <button type="button" id="add_api_btn" class="btn btn-primary">ADD API</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<div id="update_modal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Update API</h4>
      </div>
      <div class="modal-body">
        <form class="form-horizontal" role="form" id="update_api_form" action="" method="post">
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>" class="csrf_token" />
            <input type="hidden" class="all_inputs" name="api_id" id="api_id">
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label class="col-sm-2 control-label no-padding-right" for="form-field-1"> API Name: </label>
                        <div class="col-sm-10">
                            <input type="text" id="update_api_name" name="api_name" class="col-xs-10 col-sm-5 all_inputs" />
                            <span class="all_errors" id="update_api_name_error"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label class="col-sm-2 control-label no-padding-right" for="form-field-1"> API Status: </label>
                        <div class="col-sm-10">
                            <select name="status" id="api_status" class="col-xs-10 col-sm-5 all_inputs"></select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label class="col-sm-2 control-label no-padding-right" for="form-field-1">  </label>
                        <div class="col-sm-10">
                            <button type="button" id="update_api_btn" class="btn btn-primary">Update API</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<script src="assets/developer_js/admin/apis.js" type="text/javascript"></script>
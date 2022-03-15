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
.api_label{
  color: #A964AC;
  font-size: 18px;
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
        <h1>Users APIs
			<small>
				<i class="ace-icon fa fa-angle-double-right"></i>
				All APIs
			</small>
		</h1>
    </div>
    <!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
            <!-- <div class="row">
                <div class="col-md-2">
                    <a href="javascript::" class="btn btn-success" id="add_api">Add New API</a>
                </div>
            </div> -->
            <!-- PAGE CONTENT BEGINS -->
            <div class="row">
                <div class="col-xs-12">
                    <div class="clearfix">
                        <div class="pull-right tableTools-container"></div>
                    </div>
                    <div class="table-header">
                        Users APIs Details
                    </div>
                    <div>
                        <table id="sample_user_apis" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th class="hidden-480">Email</th>
                                    <th>API Name</th>
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

<div id="hubsolv_modal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Update API</h4>
      </div>
      <div class="modal-body">
        <form class="form-horizontal" role="form" id="hubsolv_form" action="" method="post">
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>" class="csrf_token" />
            <input type="hidden" class="all_inputs user_api_id" name="user_api_id">
            <input type="hidden" class="all_inputs api_name" name="api_name">
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1">HUBSOLV-API-KEY:</label>
                        <div class="col-sm-9">
                            <input type="text" id="hubsolv_api_key" name="hubsolv_api_key" class="col-xs-10 col-sm-5 all_inputs" />
                            <span class="all_errors" id="hubsolv_api_key_error"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Username:</label>
                        <div class="col-sm-9">
                            <input type="text" id="username" name="username" class="col-xs-10 col-sm-5 all_inputs" />
                            <span class="all_errors" id="username_error"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Password:</label>
                        <div class="col-sm-9">
                            <input type="text" id="password" name="password" class="col-xs-10 col-sm-5 all_inputs" />
                            <span class="all_errors" id="password_error"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1">API Url:</label>
                        <div class="col-sm-9">
                            <input type="text" id="api_url" name="api_url" class="col-xs-10 col-sm-5 all_inputs" />
                            <span class="all_errors" id="api_url_error"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1">  </label>
                        <div class="col-sm-9">
                            <button type="button" id="hubsolv_update_btn" class="btn btn-primary">Update API</button>
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

<div id="zeavo_modal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Update API</h4>
      </div>
      <div class="modal-body">
        <form class="form-horizontal" role="form" id="zeavo_form" action="" method="post">
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>" class="csrf_token" />
            <input type="hidden" class="all_inputs user_api_id" name="user_api_id">
            <input type="hidden" class="all_inputs api_name" name="api_name">
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1">API Url:</label>
                        <div class="col-sm-9">
                            <input type="text" id="zeavo_api_url" name="zeavo_api_url" class="col-xs-10 col-sm-5 all_inputs" />
                            <span class="all_errors" id="zeavo_api_url_error"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1">API-KEY:</label>
                        <div class="col-sm-9">
                            <input type="text" id="api_key" name="api_key" class="col-xs-10 col-sm-5 all_inputs" />
                            <span class="all_errors" id="api_key_error"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Lead Group Id:</label>
                        <div class="col-sm-9">
                            <input type="text" id="lead_group_id" name="lead_group_id" class="col-xs-10 col-sm-5 all_inputs" />
                            <span class="all_errors" id="lead_group_id_error"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1">  </label>
                        <div class="col-sm-9">
                            <button type="button" id="zeavo_update_btn" class="btn btn-primary">Update API</button>
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

<div class="modal" id="api_detail_modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">API Detail</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p id="api_detail"></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script src="assets/developer_js/admin/user_apis.js" type="text/javascript"></script>
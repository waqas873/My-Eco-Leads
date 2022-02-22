<style type="text/css">
.modal-dialog {
    width: 715px !important;
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
<div class="page-content">
    <div class="page-header">
        <h1>Users
			<small>
				<i class="ace-icon fa fa-angle-double-right"></i>
				All Lists 
			</small>
		</h1>
    </div>
    <!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
            <div class="row">
                <div class="col-md-2">
                    <a href="<?php echo base_url('admin/lists/add');?>" class="btn btn-success">Add New List</a>
                </div>
                <div class="col-md-10">
                    <form action="" class="form-horizontal" method="post">
                        <div class="form-group filters">
                            <label class="col-sm-1 control-label no-padding-right" for="form-field-1"> Status: </label>
                            <div class="col-sm-2">
                                <select class="status_filter all_filters" id="status_filter" name="status_filter">
                                    <option value="">Select Status</option>
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- PAGE CONTENT BEGINS -->
            <div class="row">
                <div class="col-xs-12">
                    <div class="clearfix">
                        <div class="pull-right tableTools-container"></div>
                    </div>
                    <div class="table-header">
                        All Lists Details
                    </div>
                    <div>
                        <table id="sample_lists" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <!-- <th class="center">
                                        <label class="pos-rel">
                                            <input type="checkbox" class="ace" />
                                            <span class="lbl"></span>
                                        </label>
                                    </th> -->
                                    <th>Name</th>
                                    <th>Catagory Name</th>
                                    <th class="hidden-480">Status</th>
                                    <th>Leads</th>
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

<div id="list_modal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Update List</h4>
      </div>
      <div class="modal-body">
        <form class="form-horizontal" role="form" id="update_list_form" action="" method="post">
            <input type="hidden" class="all_inputs" name="list_id" id="list_id">
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> List Name: </label>
                        <div class="col-sm-9">
                            <input type="text" id="list_name" name="list_name" class="col-xs-10 col-sm-5 all_inputs" />
                            <span class="all_errors" id="list_name_error"></span>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> CRM: </label>
                        <div class="col-sm-9">
                            <input type="number" name="crm_id" class="col-xs-10 col-sm-5 all_inputs" id="crm_id">
                            <span class="all_errors" id="crm_id_error"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Status: </label>
                        <div class="col-sm-9">
                            <select class="col-xs-10 col-sm-5 all_inputs" name="status" id="status" name="status">

                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Category: </label>
                        <div class="col-sm-9">
                            <select class="col-xs-10 col-sm-5 all_inputs" name="list_category" id="list_category" name="list_category">

                            </select>
                            <span class="all_errors" id="list_category_error"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1">  </label>
                        <div class="col-sm-9">
                            <button type="button" id="update_list" class="btn btn-primary">Update List</button>
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

<script src="assets/developer_js/lists.js" type="text/javascript"></script>
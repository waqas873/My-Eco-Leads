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
<h1>Users

<small>
    <i class="ace-icon fa fa-angle-double-right"></i>
All Users List

</small>
</h1>
</div>
<!-- /.page-header -->
<div class="row">
<div class="col-xs-12">
<div class="row">
    <div class="col-xs-12">
        <form action="" class="form-horizontal" method="post">
            <div class="form-group">
                <label class="col-sm-1 control-label no-padding-right" for="form-field-1"> Status: </label>
                <div class="col-sm-2">
                    <select class="status_filter all_filters" id="status_filter" name="status_filter">
                        <option value="">Select Status</option>
                        <option value="0">Pending</option>
                        <option value="1">Active</option>
                        <option value="2">Blocked</option>
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
All Users List
</div>
        <div>
            <table id="sample_users" class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <!-- <th class="center"><label class="pos-rel"><input type="checkbox" class="ace" /><span class="lbl"></span></label></th> -->
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th class="hidden-480">Status</th>
                        <th>Days</th>
                        <th>Time</th>
                        <th>Orders</th>
                        <th>Removed</th>
                        <th>Action</th>
                        <th>Connections</th>
                        <th>Joining Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <!-- <tbody><tr><td class="center"><label class="pos-rel"><input type="checkbox" class="ace" /><span class="lbl"></span></label></td><td><a href="#">app.com</a></td><td>$45</td><td class="hidden-480">3,330</td><td>Feb 12</td><td class="hidden-480"><span class="label label-sm label-warning">Expiring</span></td><td><div class="hidden-sm hidden-xs action-buttons"><a class="green" href="#"><i class="ace-icon fa fa-pencil bigger-130"></i></a><a class="red" href="#"><i class="ace-icon fa fa-trash-o bigger-130"></i></a></div></td></tr></tbody> -->
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
<div id="user_modal" class="modal fade" role="dialog">
<div class="modal-dialog">
<!-- Modal content-->
<div class="modal-content">
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title">Update User</h4>
</div>
<div class="modal-body">
    <form class="form-horizontal" role="form" id="update_user_form" action="" method="post">
        <input type="hidden" class="all_inputs" name="user_id" id="user_id">
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> First Name: </label>
                        <div class="col-sm-9">
                            <input type="text" id="first_name" name="first_name" class="col-xs-10 col-sm-5 all_inputs" />
                            <span class="all_errors" id="first_name_error"></span>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Last Name: </label>
                        <div class="col-sm-9">
                            <input type="text" id="last_name" name="last_name" class="col-xs-10 col-sm-5 all_inputs" />
                            <span class="all_errors" id="last_name_error"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Email: </label>
                        <div class="col-sm-9">
                            <input type="text" id="email" name="email" class="col-xs-10 col-sm-5 all_inputs" />
                            <span class="all_errors" id="email_error"></span>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Status: </label>
                        <div class="col-sm-9">
                            <select class="col-xs-10 col-sm-5 all_inputs" id="status" name="status">
                                <option value="0">Pending</option>
                                <option value="1">Active</option>
                                <option value="2">Blocked</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6" id="connection_dd">
                   
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"></label>
                        <div class="col-sm-9">
                            <button type="button" id="update_user" class="btn btn-primary">Update User</button>
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
<script src="assets/developer_js/users.js" type="text/javascript"></script>
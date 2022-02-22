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
</style>

<div class="page-content">
    <div class="page-header">
        <h1>API Leads
			<small>
				<i class="ace-icon fa fa-angle-double-right"></i>
				All API Leads
			</small>
		</h1>
    </div>
    <!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
            <div class="row">
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
            <div class="row" style="margin-top: 10px;">
                <div class="col-md-4">
                    <div class="form-group filters">
                        <label class="col-sm-2 control-label no-padding-right" for="form-field-1"> User: </label>
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
            </div>
            <!-- PAGE CONTENT BEGINS -->
            <div class="row">
                <div class="col-xs-12">
                    <div class="clearfix">
                        <div class="pull-right tableTools-container"></div>
                    </div>
                    <div class="table-header">
                        API Leads Details
                    </div>
                    <div>
                        <table id="sample_api_leads" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Email</th>
                                    <th>Mobile No</th>
                                    <th>Status</th>
                                    <th>To User</th>
                                    <th>Failed Attempts</th>
                                    <th>Delivered Date</th>
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

<script src="assets/developer_js/admin/api_leads.js" type="text/javascript"></script>
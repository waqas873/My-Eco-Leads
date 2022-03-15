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
.small_email{
    font-size: 11px !important;
}
</style>

<?php 
  if($this->session->userdata('order_added')){
    $this->session->unset_userdata('order_added');
?>
  <script type="text/javascript">
    $(document).ready(function(){
      swal({
        type: 'success',
        title: 'Success!',
        text: "Order has been added successfully."
      })
    });
  </script>
<?php } ?>

<?php 
  if($this->session->userdata('order_updated')){
    $this->session->unset_userdata('order_updated');
?>
  <script type="text/javascript">
    $(document).ready(function(){
      swal({
        type: 'success',
        title: 'Success!',
        text: "Order has been updated successfully."
      })
    });
  </script>
<?php } ?>

<div class="page-content">
    <div class="page-header">
        <h1>Manual Orders
			<small>
				<i class="ace-icon fa fa-angle-double-right"></i>
				All manual Orders
			</small>
		</h1>
    </div>
    <!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
            <div class="row">
                <div class="col-md-2">
                    <a href="<?php echo base_url('admin/orders/manual_add');?>" class="btn btn-success">
                        New Order
                    </a>
                </div>
                <div class="col-md-4">
                   <div class="form-group filters">
                        <label class="col-sm-2 control-label no-padding-right" for="form-field-1"> Status: </label>
                        <div class="col-sm-8">
                            <select class="status_filter all_filters select2" id="status_manual_filter" name="status_filter">
                                <option value="">Select Status</option>
                                <option value="1">Completed</option>
                                <option value="0">Pending</option>
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
                        All Orders Details
                    </div>
                    <div>
                        <table id="sample_manual_orders" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Total Leads</th>
                                    <th>User</th>
                                    <th>Delivered Leads</th>
                                    <th>Remaining Leads</th>
                                    <th>Price</th>
                                    <th>Status</th>
                                    <th>Creation Date</th>
                                    <th>Edit</th>
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

<script src="assets/developer_js/admin/orders.js" type="text/javascript"></script>
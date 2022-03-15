<style type="text/css">
.all_inputs{
    width: 100%;
}
.all_errors{
    color: red;
}
.form_section{
    margin-top: 60px;
}
#add_list_btn{
    margin-top: 10px;
}
</style>
<div class="page-content">
    <div class="page-header">
        <h1>Update Order
			<small>
				<i class="ace-icon fa fa-angle-double-right"></i>
				Update Order
			</small>
		</h1>
    </div>
    <!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
            <!-- PAGE CONTENT BEGINS -->
            <div class="row">
                <div class="col-xs-12">
                    <div class="clearfix">
                        <div class="pull-right tableTools-container"></div>
                    </div>
                    <div class="table-header">
                        Updating Order
                    </div>
                    <div class="form_section">
                        <form action="" method="post" id="update_order_form">
                          <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>" class="csrf_token" />
                          <input type="hidden" name="order_id" value="<?php echo (isset($order))?$order['order_id']:''; ?>" />
                          <div class="form-row">
                            <div class="form-group col-md-6">
                              <label for="total_leads">Total Leads</label>
                              <input type="number" name="total_leads" value="<?php echo (isset($order))?$order['total_leads']:''; ?>" class="form-control all_inputs" id="total_leads" placeholder="Total Leads">
                              <span class="all_errors" id="total_leads_error"></span>
                            </div>
                            <div class="form-group col-md-6">
                              <label for="user_id">To User</label>
                              <select id="user_id" class="form-control select2" name="user_id">
                                <option value="">Select User</option>
                                <?php 
                                  if(isset($users) && !empty($users)){
                                    foreach ($users as $row) {
                                ?>
                                <option value="<?php echo $row['user_id'];?>" <?php echo (isset($order) && $order['user_id']==$row['user_id'])?'selected':''; ?> ><?php echo $row['first_name'].' '.$row['last_name'];?></option>
                                <?php } } ?>
                              </select>
                              <span class="all_errors" id="user_id_error"></span>
                            </div>
                          </div>
                          <div class="form-row">
                            <div class="form-group col-md-6">
                              <label for="email">User Email</label>
                              <select id="email" class="form-control select2" name="email">
                                <option value="">Select Email</option>
                                <?php 
                                  if(isset($users) && !empty($users)){
                                    foreach ($users as $row) {
                                ?>
                                <option value="<?php echo $row['user_id'];?>" <?php echo (isset($order) && $order['user_id']==$row['user_id'])?'selected':''; ?> ><?php echo $row['email'];?></option>
                                <?php } } ?>
                              </select>
                              <span class="all_errors" id="email_error"></span>
                            </div>
                            <div class="form-group col-md-6">
                              <label for="amount">Total Amount</label>
                              <input type="number" name="amount" class="form-control all_inputs" id="amount" value="<?php echo (isset($order))?$order['amount']:''; ?>" placeholder="Total Leads">
                              <span class="all_errors" id="amount_error"></span>
                            </div>
                          </div>
                          <div class="form-row">
                            <div class="form-group col-md-12">
                                <button type="button" id="update_order_btn" class="btn btn-primary">Update Order</button>
                            </div>
                          </div>
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

<script src="assets/developer_js/admin/orders.js" type="text/javascript"></script>
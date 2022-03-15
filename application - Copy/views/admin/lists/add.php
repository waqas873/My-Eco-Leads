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
            <!-- PAGE CONTENT BEGINS -->
            <div class="row">
                <div class="col-xs-12">
                    <div class="clearfix">
                        <div class="pull-right tableTools-container"></div>
                    </div>
                    <div class="table-header">
                        Adding New List
                    </div>
                    <div class="form_section">
                        <form action="" method="post" id="add_list_form">
                          <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>" class="csrf_token" />
                          <div class="form-row">
                            <div class="form-group col-md-6">
                              <label for="list_name">Name</label>
                              <input type="text" name="list_name" class="form-control all_inputs" id="list_name" placeholder="Name">
                              <span class="all_errors" id="list_name_error"></span>
                            </div>
                            <div class="form-group col-md-6">
                              <label for="crm_id">CRM</label>
                              <input type="number" name="crm_id" class="form-control all_inputs" id="crm_id">
                              <span class="all_errors" id="crm_id_error"></span>
                            </div>
                          </div>
                          <div class="form-row">
                            <div class="form-group col-md-6">
                              <label for="list_category">Category</label>
                              <select id="list_category" class="form-control select2" name="list_category">
                                <option value="">Choose...</option>
                                <?php 
                                  if(isset($interests) && !empty($interests)){
                                    foreach ($interests as $row) {
                                ?>
                                <option value="<?php echo $row['interest_id'];?>"><?php echo $row['name'];?></option>
                                <?php } } ?>
                              </select>
                              <span class="all_errors" id="list_category_error"></span>
                            </div>
                            <div class="form-group col-md-6">
                              <label for="status">Status</label>
                              <select id="status" name="status" class="form-control select2">
                                <option value="1" selected>Active</option>
                                <option value="0">Inactive</option>
                              </select>
                            </div>
                          </div>
                          <div class="form-row">
                            <div class="form-group col-md-12">
                                <button type="button" id="add_list_btn" class="btn btn-primary">Add List</button>
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

<script src="assets/developer_js/lists.js" type="text/javascript"></script>
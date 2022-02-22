<style type="text/css">
.all_filters{
    width: 100%;
}
.filters{
    margin-top: 6px;
}
.form-group > label{
    margin-top: 4px;
}
#status_filter{
    height: 34px !important;
}
.all_errors{
    color: red;
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
        <h1>Companies
            <small>
                <i class="ace-icon fa fa-angle-double-right"></i>
                All Companies
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
                                <option value="1">Approved</option>
                                <option value="0">Unapproved</option>
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
                        Users Companies Details
                    </div>
                    <div>
                        <table id="sample_companies" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>User Name</th>
                                    <th>Company Name</th>
                                    <th>Registration No</th>
                                    <th>FCA/License No</th>
                                    <th>Address</th>
                                    <th>Contact No</th>
                                    <th>Status</th>
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

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">ADD URL TO COMPANY</h4>
      </div>
      <form id="company_url_form" class="company_url_form" method="post">
      <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>" class="csrf_token" />
      <input type="hidden" class="all_inputs" name="company_id" id="company_id">
      <div class="modal-body">           
        <div class="form-group">
          <label for="fullName">Enter Url </label>
          <input class="form-control all_inputs" id="company_url" name="company_url" placeholder="Enter Url" required="required" type="text">
          <span class="all_errors" id="company_url_error"></span>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" id="company_url_btn" class="btn btn-primary creat-btn">Send Url</button>
      </div>
      </form>
    </div>
  </div>
</div>

<script src="assets/developer_js/admin/companies.js" type="text/javascript"></script>
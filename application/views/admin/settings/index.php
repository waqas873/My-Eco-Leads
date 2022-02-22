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
.card{
    text-align:center;
    border: 1px solid #e2e2e2;
    border-radius: 5px;
    width: 50%;
    box-shadow: 5px 6px 6px 0 rgba(32, 33, 36, .28)
}
.card-title {
  padding-bottom: 18px;
  color: cadetblue;
  font-weight: bold;
}
.card-text {
  padding-bottom: 18px;
}
#add_price {
  margin-bottom: 20px;
}
.price_span {
  /*! background-color: ; */
  color: white;
  background-color: cadetblue;
  padding: 10px 41px 10px 22px;
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
        <h1>Settings
			<small>
				<i class="ace-icon fa fa-angle-double-right"></i>
				All settings
			</small>
		</h1>
    </div>
    <!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
          
            <!-- PAGE CONTENT BEGINS -->
            <div class="card" >
              <div class="card-body">
              <h1 class="card-title">Lead Price</h1>
              <h4 class="card-text"><span class="price_span">Price : &#163;<?php echo isset($result)?$result['value']:''; ?></span> </h4>
                    <a href="javascript::" class="btn btn-success" id="add_price">Update Lead Price</a>  
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
        <h4 class="modal-title">ADD Price</h4>
      </div>
      <div class="modal-body">
        <form class="form-horizontal" role="form" id="add_price_form" action="" method="post">
       <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>" class="csrf_token" />
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label class="col-sm-2 control-label no-padding-right" for="form-field-1">  price: </label>
                        <div class="col-sm-10">
                            <input type="text" id="lead_price" name="value" class="col-xs-12 col-sm-12 all_inputs" value="<?php echo isset($result)?$result['value']:'' ?>" />
                            <span class="all_errors" id="price_error"></span>
                        </div>
                        <input type="hidden" name="name" class="col-xs-12 col-sm-12 all_inputs" value="lead_price" />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label class="col-sm-2 control-label no-padding-right" for="form-field-1">  </label>
                        <div class="col-sm-10">
                            <a type="button"  id="add_price_btn" class="btn btn-primary">UPDATE PRICE</a>
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
<script src="assets/developer_js/admin/settings.js" type="text/javascript"></script>
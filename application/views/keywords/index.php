<style type="text/css">
	.kt-app .kt-app__content{
		margin-left: 0px !important;
	}
	table.dataTable thead > tr > th.sorting_asc, table.dataTable thead > tr > th.sorting_desc, table.dataTable thead > tr > th.sorting, table.dataTable thead > tr > td.sorting_asc, table.dataTable thead > tr > td.sorting_desc, table.dataTable thead > tr > td.sorting {
    color: white !important;
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

<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">

<!-- begin:: Content -->
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">

<!--Begin::App-->
<div class="kt-grid kt-grid--desktop kt-grid--ver kt-grid--ver-desktop kt-app">

<!--Begin:: App Content-->
<div class="kt-grid__item kt-grid__item--fluid kt-app__content" id="kt_chat_content">
<div class="kt-chat">
<div class="kt-portlet kt-portlet--head-lg- kt-portlet--last">
<div class="kt-portlet__foot">
	<div class="row">
		<div class="col-md-12">
			<h2>All KEYWORDS</h2>
		</div>
	</div>
	<div class="row" style="margin-top: 10px;margin-bottom: 10px;">
		<div class="col-md-12">
			<a href="<?php echo base_url('keywords/add/');?>" class="btn btn-primary">Add Keyword</a>
		</div>
	</div>
	<div class="kt-chat__input">
		<table class="table table-dark" id="sample_keywords">
		    <thead>
		      <tr>
		        <th>Keywords</th>
		        <th>Reply Text</th>
		        <th>Creation Date</th>
		        <th>Actions</th>
		      </tr>
		    </thead>
		  </table>
	</div>
</div>
</div>
</div>
</div>

<!--End:: App Content-->
</div>

<!--End::App-->
</div>

<!-- end:: Content -->
</div>

<script src="assets/developer_js/keywords.js" type="text/javascript"></script>
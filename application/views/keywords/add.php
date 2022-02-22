<style type="text/css">
	.kt-app .kt-app__content{
		margin-left: 0px !important;
	}
	.all_errors p{
        color: red;
        margin-top: 5px;
	}
</style>

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
			<h2>ADD KEYWORD</h2>
		</div>
	</div>
	<div class="kt-chat__input">
		<form id="add_form" method="post" action="" style="margin-top: 20px;">
		  <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>" class="csrf_token" />
		  <div class="form-group">
		    <label for="group_keyword">Keyword</label>
		    <input type="text" class="form-control" id="group_keyword" name="group_keyword" placeholder="Enter Keyword">
		    <div class="all_errors" id="group_keyword_error"></div>
		  </div>
		  <div class="form-group">
		    <label for="group_reply_text">Message</label>
		    <textarea name="group_reply_text" id="group_reply_text" class="form-control"></textarea>
		    <div class="all_errors" id="group_reply_text_error"></div>
		  </div>
		  <button type="submit" id="add_button" class="btn btn-primary">Save</button>
		  <h5 id="plz_wait">Please Wait</h5>
		</form>
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
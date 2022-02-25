<style type="text/css">
.all_filters{
    width: 100%;
}
.filters{
    margin-top: 6px;
}
#address_info {
  text-align: center;
}
table, td, th {
  border: 1px solid #333;
}
.addresses {
  padding: 10px;
}
#address_info{
  margin-bottom: 10px;
  margin-left: 10px;
}
.modal-dialog{
  width: 1100px;
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
#lead_info{
	font-size: 17px;
    color: #EF9D0C;
}
.note_des{
  font-size: 18px;
}

.address-content {
  display: flex;
  justify-content: flex-start;
  align-items: center;
}
.ndate{
  font-size: 12px;
}
.answer{
  color: #A964AC;
}
.add_note{
  margin-bottom: 5px;
}
.action_select{background-color: #ffffff !important;color: black !important;}
.called{background-color: #00ACE7 !important;}
.call_back{background-color: #FF9800 !important;}
.no_answer{background-color: #70AE39 !important;}
.not_interested{background-color: #F04523 !important;}
.pack_out{background-color: #6424C9 !important;}
.value-box{color:white !important;}
.action{color:white !important;}

.table-scrollable{
    overflow-x: auto !important;
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

<div class="container-fluid">
  <h1>Dashboard</h1>
  <div class="notifications">
    <?php 
      $notifications = msg_notifications();
      if(!empty($notifications)){
        $total = count($notifications);
        foreach($notifications as $key=>$notify){
          $i = $key+1;
          $sprtr = ($i<$total)?' | ':'';
          echo '<a href="'.base_url('chat/index/'.createBase64($notify['lead_id'])).'" target="_blank"><span class="badge badge-success">'.$notify['msgs'].'</span> new messages from '.$notify['name'].'</a>'.$sprtr;
        }
      }
    ?>
  </div>
</div>
        <div class="container-fluid leades-section">
            <div class="">
                <div class="row">
				<div class="col-md-2 col-sm-4 col-xs-4"> 
					<div class="leades total">
						<div class="no"><?php echo (isset($ordered_leads))?$ordered_leads:'' ;?></div>Total Ordered Leads</div>
				</div>
				
				<div class="col-md-2 col-sm-4 col-xs-4"> 
					<div class="leades total">
						<div class="no"><?php echo (isset($delivered_leads))?$delivered_leads:'' ;?></div>Total Delivered Leads</div>
				</div>
				
				<div class="col-md-2 col-sm-4 col-xs-4"> 
					<div class="leades total">
						<div class="no"><?php echo (isset($remaining_leads))?$remaining_leads:'' ;?></div>Total Remaining Leads</div>
				</div>
				
				<div class="col-md-2 col-sm-4 col-xs-4"> 
					<div class="leades total">
						<div class="no"><?php echo (isset($today_delivered_leads))?$today_delivered_leads:'' ;?></div>Today Recieved Leads</div>
				</div>
				<!-- <div class="col-md-2 col-sm-4 col-xs-4"> 
					<div class="leades">
						<div class="no">4</div><span class="not-set">Not Set</span> Leads</div>
				</div> -->
          </div>
          </div>
      </div>

        <div class="container-fluid">
            <div class="row">
				<div class="col-md-4 col-sm-4 col-xs-12"> 
					<div class="value-box called">Total Called Leads <b id="called"><?php echo (isset($called))?$called:'' ;?></b> </div>
				</div>
				<div class="col-md-4 col-sm-4 col-xs-12"> 
					<div class="value-box call_back">Total Call Back Leads <b id="call_back"><?php echo (isset($call_back))?$call_back:'' ;?> </b></div>
				</div>
				<div class="col-md-4 col-sm-4 col-xs-12"> 
					<div class="value-box not_interested">Total Not Interested Leads <b id="not_interested"><?php echo (isset($not_interested))?$not_interested:'' ;?></b> </div>
				</div>
            </div>
            <div class="row" style="margin-top: 15px;">
				<div class="col-md-4 col-sm-4 col-xs-12"> 
					<div class="value-box no_answer">Total No Answer Leads <b id="no_answer"><?php echo (isset($no_answer))?$no_answer:'' ;?></b> </div>
				</div>
				<!-- <div class="col-md-4 col-sm-4 col-xs-12"> 
					<div class="value-box pack_out">Total Pack Out Leads <b id="pack_out"><?php //echo (isset($pack_out))?$pack_out:'' ;?> </b></div>
				</div> -->
            </div>
        </div>
        <div class="container-fluid">
        	<div class="row" style="margin-top: 15px;">
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
            <div class="row" style="margin-top: 15px;">
                <div class="col-md-4">
                   <div class="form-group filters">
                        <label class="col-sm-2 control-label no-padding-right" for="form-field-1"> Action: </label>
                        <div class="col-sm-8">
                            <select class="status_filter all_filters select2" id="action_filter" name="action_filter">
                                <option value="">Select Action</option>
                                <option value="called">Called</option>
                                <option value="call_back">Call Back</option>
                                <option value="not_interested">Not Interested</option>
                                <option value="no_answer">No Aanswer</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="container-fluid table-section">
           
         <div class="">
               								
                <div class="row">
                
                <div class="col-md-12"> 
					<div class="fliter-box">
					<button class="btn fliter"> Fliter <span class="n-leads"><span id="n_leads">0</span> Leads</span></button>
					</div>
				</div>
					
                <div class="col-md-12"> 
                <div class="table-list">
                
<table class="table-responsive table-bordered table-striped table-hover" width="100%" id="sample_leads">
  <thead>
    <tr>
      <th>Sr#</th>
        <th>First Name</th>
	    <th>Last Name</th>
	    <th>Email</th>
	    <th>Mobile No</th>
      <th>House Number</th>
      <th>Household Benefits</th>
	    <th>Status</th>
	    <th>Action</th>
      <!-- <th>Conversation</th> -->
      <th>Notes</th>
      <th>Addresses</th>
	    <th>View Info</th>
	    <th>Appeal</th>
	    <th>Creation Date</th>
    </tr>
  </thead>
</table>
	 		
		 		</div>
			 
				 </div>

				</div>
                
			  </div>   
			</div>

<div class="modal" id="info_modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Lead Info</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p id="lead_info"></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div class="modal" id="addresses_modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Address Info</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="address-content">
          <div>Select Address</div>
          <div id="address_info"></div>
        </div>
        <div class="info_table">
          <table>
            <thead>
              <tr>
                <th>uprn-source</th>
                <th>floor-height</th>
                <th>heating-cost-potential</th>
                <th>hot-water-cost-potential</th>
                <th>construction-age-band</th>
                <th>potential-energy-rating</th>
                <th>mainheat-energy-eff</th>
                <th>windows-env-eff</th>
                <th>lighting-energy-eff</th>
                <th>environment-impact-potential</th>
              </tr>
            </thead>
            <tbody class="address_body">

            </tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div class="modal" id="view_notes_modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">View Notes</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p id="view_notes"></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="sms_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Send SMS</h4>
      </div>
      <form id="message_form" class="message_form" method="post">
      <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>" class="csrf_token" />
      <div class="modal-body">
        <input type="hidden" name="lead_id" id="lead_id">
        <div class="form-group">
          <label for="email">Message</label>
          <textarea name="msg_content" class="form-control all_inputs"></textarea>
          <span class="all_errors" id="msg_content_error"></span>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" id="reply_button" class="btn btn-primary creat-btn">Send Message</button>
        <h5 class="plz_wait">Please Wait</h5>
      </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="note_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Create New Note</h4>
      </div>
      <form id="note_form" class="note_form" method="post">
      <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>" class="csrf_token" />
      <div class="modal-body">
        <input type="hidden" name="lead_id" class="lead_id">
        <div class="form-group">
          <label for="email">Note Description</label>
          <textarea name="description" class="form-control all_inputs"></textarea>
          <span class="all_errors" id="description_error"></span>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" id="note_button" class="btn btn-primary creat-btn">Save Note</button>
        <h5 class="plz_wait">Please Wait</h5>
      </div>
      </form>
    </div>
  </div>
</div>

			
<script src="assets/developer_js/leads.js" type="text/javascript"></script>
<link href="assets/css/pretty-textbox.css" rel="stylesheet">
<style type="text/css">
  .error{
    color: red;
  }
  .errors p{
    color: red !important;
    margin-top: -15px;
  }
  .all_errors p{
    color: red !important;
    margin-top: 5px;
  }
  .dashboard-section{
    width: 95% !important;
  }
  #sample_apis{
    line-height: 2.529 !important;
  }
  .text_center{
    text-align: center !important;
  }
  .pretty {
    margin-right: 0px !important;
  }
  .hub_checkbox{
    padding-left: 10px;
  }
  .form-group{
    margin-bottom: 0px !important;
  }
  label{
    margin-bottom: 0px !important;
  }
  .mg_botm{
    margin-top: 36px;
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

<div class="container-fluid contact-page">
  <div class="container">
      <div class="row">
          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="dashboard-section">     
                <h1>All <span class="orange-text">APIs</span></h1>
               <hr>
              <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                  <div class="table-list">
                  <table class="table-responsive table-bordered table-striped table-hover" width="100%" id="sample_apis">
                    <thead>
                    <tr>
                      <th></th>
                      <th class="text_center">Sr#</th>
                      <th class="text_center">API Name</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php 
                      if(isset($apis) && !empty($apis)){
                        $counter = 1;
                        foreach($apis as $api){
                    ?>
                    <tr data-toggle="collapse" data-target="#collapseExample_<?php echo $api['api_id'];?>" aria-expanded="false" aria-controls="collapseExample">
                      <td class="hub_checkbox">
                        <div class="pretty p-default">
                            <input type="checkbox" <?php echo (!empty($api['is_checked']))?'checked':''; ?> id="checkbox_<?php echo $api['api_id'];?>" rel="<?php echo $api['api_id'];?>" class="checkbox"/>
                            <div class="state p-success">
                                <label></label>
                            </div>
                        </div>
                      </td>
                      <td class="text_center"><?php echo $counter;?></td>
                      <td class="text_center"><?php echo ucwords($api['api_name']);?></td>
                    </tr>
                    <tr>
                      <td colspan="12">
                        <div class="collapse" id="collapseExample_<?php echo $api['api_id'];?>">
                        <div class="card card-body">
                          <?php if($api['api_id']==2) { ?>
                          <form action="" method="post" id="api_form_<?php echo $api['api_id'];?>" class="api_form">
                            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>" class="csrf_token" />
                            <div class="form-row">
                              <div class="form-group col-md-6">
                                <label for="hubsolv_api_key_<?php echo $api['api_id'];?>">HUBSOLV-API-KEY</label>
                                <input type="text" name="hubsolv_api_key" class="form-control" id="hubsolv_api_key_<?php echo $api['api_id'];?>" value="<?php echo isset($api['hubsolv_api_key'])?$api['hubsolv_api_key']:'';?>" placeholder="HUBSOLV-API-KEY">
                                <span class="errors" id="hubsolv_api_key_error_<?php echo $api['api_id'];?>"></span>
                              </div>
                              <div class="form-group col-md-6">
                                <label for="username_<?php echo $api['api_id'];?>">Username</label>
                                <input type="text" name="username" class="form-control" id="username_<?php echo $api['api_id'];?>" value="<?php echo isset($api['hubsolv_username'])?$api['hubsolv_username']:'';?>" placeholder="Username">
                                <span class="errors" id="username_error_<?php echo $api['api_id'];?>"></span>
                              </div>
                            </div>
                            <div class="form-row">
                              <div class="form-group col-md-6">
                                <label for="password_<?php echo $api['api_id'];?>">Password</label>
                                <input type="text" name="password" class="form-control" id="password_<?php echo $api['api_id'];?>" value="<?php echo isset($api['hubsolv_password'])?$api['hubsolv_password']:'';?>" placeholder="Password">
                                <span class="errors" id="password_error_<?php echo $api['api_id'];?>"></span>
                              </div>
                              <div class="form-group col-md-6">
                                <label for="api_url_<?php echo $api['api_id'];?>">API URL</label>
                                <input type="text" name="api_url" class="form-control" id="api_url_<?php echo $api['api_id'];?>" value="<?php echo isset($api['hubsolv_api_url'])?$api['hubsolv_api_url']:'';?>" placeholder="API URL">
                                <span class="errors" id="api_url_error_<?php echo $api['api_id'];?>"></span>
                              </div>
                            </div>
                            <div class="form-row">
                              <div class="form-group col-md-6">
                                <label for="campaignid_<?php echo $api['api_id'];?>">Campaign Id</label>
                                <input type="number" name="campaignid" class="form-control" id="campaignid_<?php echo $api['api_id'];?>" value="<?php echo isset($api['hubsolv_campaignid'])?$api['hubsolv_campaignid']:'';?>" placeholder="Campaign Id">
                                <span class="errors" id="campaignid_error_<?php echo $api['api_id'];?>"></span>
                              </div>
                              <div class="form-group col-md-6">
                                <label for="lead_source_<?php echo $api['api_id'];?>">Lead Source</label>
                                <input type="text" name="lead_source" class="form-control" id="lead_source_<?php echo $api['api_id'];?>" value="<?php echo isset($api['hubsolv_lead_source'])?$api['hubsolv_lead_source']:'';?>" placeholder="Lead Source">
                                <span class="errors" id="lead_source_error_<?php echo $api['api_id'];?>"></span>
                              </div>
                            </div>
                            <div class="form-row">
                              <div class="form-group col-md-6">
                                <label for="status_<?php echo $api['api_id'];?>">Status</label>
                                <select name="status" class="form-control" id="status_<?php echo $api['api_id'];?>">
                                  <option value="1" <?php echo (isset($api['hubsolv_status']) && $api['hubsolv_status']==1)?'selected':'' ?> >Enabled</option>
                                  <option value="0" <?php echo (isset($api['hubsolv_status']) && $api['hubsolv_status']==0)?'selected':'' ?>>Disabled</option>
                                </select>
                              </div>
                            </div>
                            <div class="form-row">
                              <div class="form-group col-md-6">
                                <button type="submit" id="save_btn" class="btn btn-primary mg_botm save_btn" rel="<?php echo $api['api_id'];?>"><span id="btn_txt_<?php echo $api['api_id'];?>"><?php echo (!empty($api['hubsolv_username']))?'Update Record':'Save Record'; ?></span></button>
                                <?php if(!empty($api['hubsolv_api_url'])) { ?>
                                  <button type="button" id="vision_api_test" class="btn btn-primary mg_botm">API Test</button>
                                <?php } ?>
                              </div>
                            </div>
                          </form>
                          <?php } ?>
                          <?php if($api['api_id']==3) { ?>
                          <form action="" method="post" id="api_form_<?php echo $api['api_id'];?>" class="api_form">
                            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>" class="csrf_token" />
                            <div class="form-row">
                              <div class="form-group col-md-6">
                                <label for="api_url_<?php echo $api['api_id'];?>">API URL</label>
                                <input type="text" name="api_url" class="form-control" id="api_url_<?php echo $api['api_id'];?>" value="<?php echo isset($api['zeavo_api_url'])?$api['zeavo_api_url']:'';?>" placeholder="API URL">
                                <span class="errors" id="api_url_error_<?php echo $api['api_id'];?>"></span>
                              </div>
                              <div class="form-group col-md-6">
                                <label for="api_key_<?php echo $api['api_id'];?>">API KEY</label>
                                <input type="text" name="api_key" class="form-control" id="api_key_<?php echo $api['api_id'];?>" value="<?php echo isset($api['zeavo_api_key'])?$api['zeavo_api_key']:'';?>" placeholder="API KEY">
                                <span class="errors" id="api_key_error_<?php echo $api['api_id'];?>"></span>
                              </div>
                            </div>
                            <div class="form-row">
                              <div class="form-group col-md-6">
                                <label for="lead_group_id_<?php echo $api['api_id'];?>">Lead Group Id</label>
                                <input type="text" name="lead_group_id" class="form-control" id="lead_group_id_<?php echo $api['api_id'];?>" value="<?php echo isset($api['zeavo_lead_group_id'])?$api['zeavo_lead_group_id']:'';?>" placeholder="Lead group id">
                                <span class="errors" id="lead_group_id_error_<?php echo $api['api_id'];?>"></span>
                              </div>
                            </div>
                            <div class="form-row">
                              <div class="form-group col-md-6">
                                <label for="status_<?php echo $api['api_id'];?>">Status</label>
                                <select name="status" class="form-control" id="status_<?php echo $api['api_id'];?>">
                                  <option value="1" <?php echo (isset($api['zeavo_status']) && $api['zeavo_status']==1)?'selected':'' ?> >Enabled</option>
                                  <option value="0" <?php echo (isset($api['zeavo_status']) && $api['zeavo_status']==0)?'selected':'' ?>>Disabled</option>
                                </select>
                              </div>
                            </div>
                            <div class="form-row">
                              <div class="form-group col-md-6">
                                <button type="submit" id="zeavo_save_btn" class="btn btn-primary zeavo_save_btn" rel="<?php echo $api['api_id'];?>" style="margin-bottom: 13px;"><span id="btn_txt_<?php echo $api['api_id'];?>"><?php echo (!empty($api['zeavo_api_key']))?'Update Record':'Save Record'; ?></span></button>
                              </div>
                            </div>
                          </form>
                          <?php } ?>
                          <?php if($api['api_id']==4) { ?>
                          <form action="" method="post" id="api_form_<?php echo $api['api_id'];?>" class="api_form">
                            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>" class="csrf_token" />
                            <div class="form-row">
                              <div class="form-group col-md-6">
                                <label for="api_url_<?php echo $api['api_id'];?>">API URL</label>
                                <input type="text" name="api_url" class="form-control" id="api_url_<?php echo $api['api_id'];?>" value="<?php echo isset($api['abbotts_api_url'])?$api['abbotts_api_url']:'';?>" placeholder="API URL">
                                <span class="errors" id="api_url_error_<?php echo $api['api_id'];?>"></span>
                              </div>
                              <div class="form-group col-md-6">
                                <label for="api_token_<?php echo $api['api_id'];?>">API TOKEN</label>
                                <input type="text" name="api_token" class="form-control" id="api_token_<?php echo $api['api_id'];?>" value="<?php echo isset($api['abbotts_api_token'])?$api['abbotts_api_token']:'';?>" placeholder="API TOKEN">
                                <span class="errors" id="api_token_error_<?php echo $api['api_id'];?>"></span>
                              </div>
                            </div>
                            <div class="form-row">
                              <div class="form-group col-md-6">
                                <label for="team_id_<?php echo $api['api_id'];?>">TEAM Id</label>
                                <input type="text" name="team_id" class="form-control" id="team_id_<?php echo $api['api_id'];?>" value="<?php echo isset($api['abbotts_team_id'])?$api['abbotts_team_id']:'';?>" placeholder="Team id">
                                <span class="errors" id="team_id_error_<?php echo $api['api_id'];?>"></span>
                              </div>
                            </div>
                            <div class="form-row">
                              <div class="form-group col-md-6">
                                <label for="lead_group_id_<?php echo $api['api_id'];?>">Lead Group Id</label>
                                <input type="text" name="lead_group_id" class="form-control" id="lead_group_id_<?php echo $api['api_id'];?>" value="<?php echo isset($api['abbotts_lead_group_id'])?$api['abbotts_lead_group_id']:'';?>" placeholder="Lead group id">
                                <span class="errors" id="lead_group_id_error_<?php echo $api['api_id'];?>"></span>
                              </div>
                            </div>
                            <div class="form-row">
                              <div class="form-group col-md-6">
                                <label for="status_<?php echo $api['api_id'];?>">Status</label>
                                <select name="status" class="form-control" id="status_<?php echo $api['api_id'];?>">
                                  <option value="1" <?php echo (isset($api['abbotts_status']) && $api['abbotts_status']==1)?'selected':'' ?> >Enabled</option>
                                  <option value="0" <?php echo (isset($api['abbotts_status']) && $api['abbotts_status']==0)?'selected':'' ?>>Disabled</option>
                                </select>
                              </div>
                            </div>
                            <div class="form-row">
                              <div class="form-group col-md-6">
                                <button type="submit" id="abbotts_save_btn" class="btn btn-primary abbotts_save_btn" rel="<?php echo $api['api_id'];?>" style="margin-top: 36px;"><span id="btn_txt_<?php echo $api['api_id'];?>"><?php echo (!empty($api['abbotts_api_key']))?'Update Record':'Save Record'; ?></span></button>
                                  <?php if(!empty($api['abbotts_api_url'])) { ?>
                                      <button type="button" id="vision_api_test" class="btn btn-primary mg_botm">API Test</button>
                                  <?php } ?>
                              </div>
                            </div>
                          </form>
                          <?php } ?>
                            <?php if($api['api_id']==5) { ?>
                                <form action="" method="post" id="api_form_<?php echo $api['api_id'];?>" class="api_form">
                                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>" class="csrf_token" />
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="api_url_<?php echo $api['api_id'];?>">Campaign Id</label>
                                            <input type="text" name="campaign_id" class="form-control" id="campaign_id_<?php echo $api['api_id'];?>" value="<?php echo isset($api['byte_campaign_id'])?$api['byte_campaign_id']:'';?>" placeholder="Compaign Id">
                                            <span class="errors" id="campaign_error_<?php echo $api['api_id'];?>"></span>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="api_token_<?php echo $api['api_id'];?>">SID</label>
                                            <input type="text" name="sid" class="form-control" id="sid_<?php echo $api['api_id'];?>" value="<?php echo isset($api['byte_sid'])?$api['byte_sid']:'';?>" placeholder="SID">
                                            <span class="errors" id="sid_error_<?php echo $api['api_id'];?>"></span>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="status_<?php echo $api['api_id'];?>">Status</label>
                                            <select name="status" class="form-control" id="status_<?php echo $api['api_id'];?>">
                                                <option value="1" <?php echo (isset($api['byte_status']) && $api['byte_status']==1)?'selected':'' ?>>Enabled</option>
                                                <option value="0" <?php echo (isset($api['byte_status']) && $api['byte_status']==0)?'selected':'' ?>>Disabled</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <button type="submit" id="lead_byte_save_btn" class="btn btn-primary" rel="<?php echo $api['api_id'];?>" style="margin-top: 36px;"><span id="btn_txt_<?php echo $api['api_id'];?>"><?php echo (!empty($api['abbotts_api_key']))?'Update Record':'Save Record'; ?></span></button>
                                        </div>
                                    </div>
                                </form>
                            <?php } ?>
                        </div>
                        </div>
                      </td>
                    </tr>
                    <?php $counter++; } } ?>
                    </tbody>
                  </table>
                  </div>
                </div>
             </div> 
            </div>
        </div>
    </div>     
  </div> 
</div>
<!--for testing api vision-->
<div class="modal fade" id="vision-myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Vision Blue Testing</h4>
            </div>
            <form id="blue_vision-form" class="hubsolv_form" method="post">
                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>" class="csrf_token" />
                <div class="modal-body">
                    <div class="form-group">
                        <label for="firstname">First Name </label>
                        <input class="form-control all_inputs" id="firstname" name="firstname" placeholder="Enter your first name" required="required" type="text">
                        <span class="all_errors" id="firstname_error"></span>
                    </div>
                    <div class="form-group">
                        <label for="lastname">Last Name </label>
                        <input class="form-control all_inputs" id="lastname" name="lastname" placeholder="Enter your last name" required="required" type="text">
                        <span class="all_errors" id="lastname_error"></span>
                    </div>
                    <div class="form-group">
                        <label for="email">Email </label>
                        <input class="form-control all_inputs" id="email" name="email" placeholder="Enter Your Email Address" required="required" type="email">
                        <span class="all_errors" id="email_error"></span>
                    </div>
                    <div class="form-group">
                        <label for="phone_mobile">Phone No</label>
                        <input class="form-control all_inputs" id="phone_mobile" name="phone_mobile" placeholder="Enter Your mobile number" required="required" type="phone_mobile">
                        <span class="all_errors" id="phone_mobile_error"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="blue_vision_btn" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>



<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Hubsolve Testing</h4>
      </div>
      <form id="hubsolv_form" class="hubsolv_form" method="post">
      <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>" class="csrf_token" />
      <div class="modal-body">           
        <div class="form-group">
          <label for="firstname">First Name </label>
          <input class="form-control all_inputs" id="firstname" name="firstname" placeholder="Enter your first name" required="required" type="text">
          <span class="all_errors" id="firstname_error"></span>
        </div>
        <div class="form-group">
          <label for="lastname">Last Name </label>
          <input class="form-control all_inputs" id="lastname" name="lastname" placeholder="Enter your last name" required="required" type="text">
          <span class="all_errors" id="lastname_error"></span>
        </div>
        <div class="form-group">
          <label for="email">Email </label>
          <input class="form-control all_inputs" id="email" name="email" placeholder="Enter Your Email Address" required="required" type="email">
          <span class="all_errors" id="email_error"></span>
        </div>
        <div class="form-group">
          <label for="phone_mobile">Phone No</label>
          <input class="form-control all_inputs" id="phone_mobile" name="phone_mobile" placeholder="Enter Your mobile number" required="required" type="phone_mobile">
          <span class="all_errors" id="phone_mobile_error"></span>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" id="hubsolv_btn" class="btn btn-primary">Submit</button>
      </div>
      </form>
    </div>
  </div>
</div>

<script src="assets/developer_js/user_api.js" type="text/javascript"></script>
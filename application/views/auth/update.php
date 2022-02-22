<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/djibe/clockpicker@1d03466e3b5eebc9e7e1dc4afa47ff0d265e2f16/dist/bootstrap4-clockpicker.min.css">
<script src="https://cdn.jsdelivr.net/gh/djibe/clockpicker@6d385d49ed6cc7f58a0b23db3477f236e4c1cd3e/dist/bootstrap4-clockpicker.min.js"></script>
<link href="assets/css/pretty-textbox.css" rel="stylesheet">
<style type="text/css">
  .error{
    color: red;
  }
  .all_errors p{
    color: red !important;
  }
  .labels{
    color: #AA3F97 !important;
  }
  .select2-selection__rendered {
    color: #C25597 !important;
  }
  .clockpicker-popover .popover-header {
    background-color: var(--primary,#9C27B0) !important;
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

<?php 
function check_in_array($interest_id, $user_interests){
  foreach ($user_interests as $interest) {
     if($interest['interest_id']==$interest_id)
      return true;
  }
  return false;
}
?>

<div class="container-fluid login-page">
   <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="login-section">
                <div class="hand-icon"><img src="assets/public/images/hand.png" width="86" height="52" alt=""/></div>
                <h1>Update Profile</h1>
                <form id="update_profile_form" method="post" action="">
                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>" class="csrf_token" />

                <input  type="text" id="first_name" class="all_inputs" name="first_name" value="<?php echo (isset($user))?$user['first_name']:''; ?>" placeholder="Name">
                <span class="all_errors" id="first_name_error"></span>

                <input  type="text" id="last_name" class="all_inputs" name="last_name" value="<?php echo (isset($user))?$user['last_name']:''; ?>" placeholder="Name">
                <span class="all_errors" id="last_name_error"></span>

                <input  type="email" id="email" class="all_inputs" name="email" value="<?php echo (isset($user))?$user['email']:''; ?>" placeholder="Email">
                <span class="all_errors" id="email_error"></span>

                <!-- <div class="form-group">
                  <label for="Interests">Interests </label>
                  <select name="ineterest_id[]" multiple="multiple" class="form-control select2">
                    <?php 
                      if(isset($interests) && !empty($interests)){
                        foreach ($interests as $row) {
                    ?>
                    <option value="<?php echo $row['interest_id'];?>" <?php echo isset($user_interests) && check_in_array($row['interest_id'], $user_interests)? 'selected="selected"': ''; ?> ><?php echo $row['name'];?></option>
                    <?php } } ?>
                  </select>
                </div> -->
                <?php 
                  $days = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];
                  $from_day = '';
                  $to_day = '';
                  $start_time = '';
                  $end_time = '';
                  if(isset($user) && !empty($user['days'])){
                    $user_days = explode(' to ', $user['days']);
                    $from_day = $user_days[0];
                    $to_day = $user_days[1];
                  }
                  if(isset($user) && !empty($user['time'])){
                    $time = explode(' to ', $user['time']);
                    $start_time = $time[0];
                    $end_time = $time[1];
                  }
                ?>

                <input type="text" id="start_time" class="all_inputs clockpicker" name="start_time"  value="<?php echo $start_time;?>" placeholder="Start Time">
                <span class="all_errors" id="start_time_error"></span>
                 
                <input  type="text" id="end_time" class="all_inputs clockpicker" value="<?php echo $end_time;?>" name="end_time" placeholder="End Time">
                <span class="all_errors" id="end_time_error"></span>
                
                <div class="form-group">
                  <label for="Interests" class="labels">From Day</label>
                  <select name="from_day" class="form-control select2">
                    <?php 
                        foreach ($days as $day) {
                    ?>
                    <option value="<?php echo $day;?>" <?php echo ($day==$from_day)?'selected':''; ?> ><?php echo $day;?></option>
                    <?php } ?>
                  </select>
                </div>
                <div class="form-group">
                  <label for="Interests" class="labels">To Day</label>
                  <select name="to_day" class="form-control select2">
                    <?php 
                        foreach ($days as $day) {
                    ?>
                    <option value="<?php echo $day;?>" <?php echo ($day==$to_day)?'selected':''; ?> ><?php echo $day;?></option>
                    <?php } ?>
                  </select>
                </div>

                <div class="form-group">
                  <div class="row">
                    <div class="col-md-1">
                      <div class="pretty p-default">
                        <input type="checkbox" <?php echo (isset($user) && $user['is_email_notification']==1)?'checked':''; ?> id="checkbox" name="is_email_notification" value="1" class="checkbox"/>
                        <div class="state p-success">
                            <label></label>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-11">
                      <label for="Interests" class="labels">Enable/Disable Email Notification</label>
                    </div>
                  </div>
                </div>

                <!--<div class="form-group">-->
                <!--  <div class="row">-->
                <!--    <div class="col-md-12">-->
                <!--      <label for="Interests" class="labels">If you would like to pause at any point you can here</label>-->
                <!--    </div>-->
                <!--  </div>-->
                <!--  <div class="row">-->
                <!--    <div class="col-md-3">-->
                <!--      <div class="pretty p-default p-curve">-->
                <!--        <input type="radio" <?php echo (isset($user) && $user['is_paused']==1)?'checked':''; ?> value="1" name="is_paused" />-->
                <!--        <div class="state p-success-o">-->
                <!--            <label>Puased</label>-->
                <!--        </div>-->
                <!--      </div>-->
                <!--    </div>-->
                <!--    <div class="col-md-3">-->
                <!--      <div class="pretty p-default p-curve">-->
                <!--        <input type="radio" <?php echo (isset($user) && $user['is_paused']==0)?'checked':''; ?> value="0" name="is_paused" />-->
                <!--        <div class="state p-success-o">-->
                <!--            <label>Resume</label>-->
                <!--        </div>-->
                <!--      </div>-->
                <!--    </div>-->
                <!--  </div>-->
                <!--</div>-->

                <label for="Interests" class="labels">You can add 3 emails with comma separation.</label>
                <input  type="text" id="email" class="all_inputs" name="secondary_email" value="<?php echo (isset($user))?$user['secondary_email']:''; ?>" placeholder="Secondary Email">
                <span class="all_errors" id="secondary_email_error"></span>

                <div class="form-group">
                  <div class="row">
                    <div class="col-md-1">
                      <div class="pretty p-default">
                        <input type="checkbox" <?php echo (isset($user) && $user['enable_auto_reply']==1)?'checked':''; ?> id="checkbox" name="enable_auto_reply" value="1" class="checkbox"/>
                        <div class="state p-success">
                            <label></label>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-11">
                      <label for="Interests" class="labels">Enable/Disable Auto Reply</label>
                    </div>
                  </div>
                </div>
                <label for="Interests" class="labels">Please enter auto reply message.</label>
                <textarea class="all_inputs" style="width:100% !important;margin-bottom:10px;padding:10px;" name="auto_reply_message" value="<?php echo (isset($user))?$user['auto_reply_message']:''; ?>" placeholder="Enter message here..."><?php echo (isset($user))?$user['auto_reply_message']:'';?></textarea>

                <button type="button" id="update_profile_btn" class="btn btn-primary creat-btn">Update Account</button>
                </form>
                <br/>
                <a href="<?php echo base_url('change-password');?>">Change your password?</a> </div>
            </div>
        </div>
    </div>     
</div> 
<script src="assets/developer_js/auth.js" type="text/javascript"></script>
<script type="text/javascript">
  $(document).ready(function(){
    $('.clockpicker').clockpicker({
      'default': 'now',
      vibrate: true,
      placement: "top",
      align: "left",
      autoclose: true,
      twelvehour: false
    });
    $(document).on('keypress', '.clockpicker', function (e) {
      return false;
    });
  });
</script>

$(document).ready(function(){

function hideModal() {
  $("#myModal").removeClass("in");
  $(".modal-backdrop").remove();
  $('body').removeClass('modal-open');
  $('body').css('padding-right', '');
  $("#myModal").hide();
}

$(document).on('click', '#register_btn', function (e) {
  $url = base_url + "auth/process_add";
  $('#register_btn').prop('disabled', true);
  $.ajax({
      url: $url,
      type: "POST",
      dataType: 'json',
      data: $('#register_form').serialize(),
      success: function (data) {
        $('.csrf_token').val(data.regenerate_token);
        $('.all_errors').empty();
        if(data.response){
          $('.all_inputs').val('');
          hideModal();
          swal({
            type: 'success',
            title: 'Success!',
            text: "You are registered successfully.Please check your email for password."
          })
        }
        else{
          $('#first_name_error').html(data.first_name_error);
          $('#last_name_error').html(data.last_name_error);
          $('#email_error').html(data.email_error);
          $('#register_btn').prop('disabled', false);
        }
      }
  });
  // setTimeout(function() {
  //   $('#register_btn').prop('disabled', false);
  // }, 12000);
  // return false;
});

$(document).on('click', '#login_btn', function (e) {
  $('.login_errors').empty();
  $url = base_url + "auth/process_login";
  $.ajax({
      url: $url,
      type: "POST",
      dataType: 'json',
      data: $('#login_form').serialize(),
      success: function (data) {
        $('.csrf_token').val(data.regenerate_token);
        if(data.response){
          window.location.href = base_url + "dashboard/index";
        }
        else{
          if(data.notifiy_error){
            $.notify({
              title: '<strong>Warning !</strong> ',
              message: data.notifiy_msg
            },{
              type: 'danger'
            });
          }
          $('#email_error').html(data.email_error);
          $('#password_error').html(data.password_error);
        }
      }
  });
  return false;
});

$(document).on('click', '#change_password_btn', function (e) {
  $url = base_url + "auth/process_change_password";
  $.ajax({
      url: $url,
      type: "POST",
      dataType: 'json',
      data: $('#change_password_form').serialize(),
      success: function (data) {
        $('.csrf_token').val(data.regenerate_token);
        $('.all_errors').empty();
        if(data.response){
          var cr = $('#cr').val();
          var redirect = (cr=='company_registration')?'company_registration':'cps_no';
          window.location.href = base_url + "dashboard/change_password_success/"+redirect;
        }
        else{
          $('#old_password_error').html(data.old_password_error);
          $('#password_error').html(data.password_error);
          $('#cpassword_error').html(data.cpassword_error);
        }
      }
  });
  return false;
});

$(document).on('click', '#reset_password_btn', function (e) {
  $url = base_url + "auth/process_reset_password";
  $.ajax({
      url: $url,
      type: "POST",
      dataType: 'json',
      data: $('#reset_password_form').serialize(),
      success: function (data) {
        $('.csrf_token').val(data.regenerate_token);
        $('.all_errors').empty();
        if(data.response){
          $('.all_inputs').val('');
          swal({
            type: 'success',
            title: 'Success!',
            text: "Your password has been reset successfully."
          })
        }
        else{
          $('#password_error').html(data.password_error);
          $('#cpassword_error').html(data.cpassword_error);
        }
      }
  });
  return false;
});

$(document).on('click', '#forgot_password_btn', function (e) {
  $url = base_url + "auth/forgot_password_link";
  $.ajax({
      url: $url,
      type: "POST",
      dataType: 'json',
      data: $('#forgot_password_form').serialize(),
      success: function (data) {
        $('.csrf_token').val(data.regenerate_token);
        $('.all_errors').empty();
        if(data.response){
          swal({
            type: 'success',
            title: 'Success!',
            text: "An email sent to your email id please follow the link given in email to reset password within 10 minutes."
          })
        }
        else{
          $('#email_error').html(data.email_error);
        }
      }
  });
  return false;
});

$(document).on('click', '#update_profile_btn', function (e) {
  $url = base_url + "auth/process_update";
  $.ajax({
      url: $url,
      type: "POST",
      dataType: 'json',
      data: $('#update_profile_form').serializeArray(),
      success: function (data) {
        $('.csrf_token').val(data.regenerate_token);
        $('.all_errors').empty();
        if(data.response){
          swal({
            type: 'success',
            title: 'Success!',
            text: "Your profile has been updated successfully."
          })
        }
        else{
          $('#first_name_error').html(data.first_name_error);
          $('#last_name_error').html(data.last_name_error);
          $('#email_error').html(data.email_error);
          $('#start_time_error').html(data.start_time_error);
          $('#end_time_error').html(data.end_time_error);
          $('#secondary_email_error').html(data.secondary_email_error);
        }
      }
  });
  return false;
});

});

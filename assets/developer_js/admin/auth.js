$(document).ready(function(){

$(document).on('click', '#login_btn', function (e) {
  $('.login_errors').empty();
  $url = base_url + "admin/auth/process_login";
  $.ajax({
      url: $url,
      type: "POST",
      dataType: 'json',
      data: $('#login_form').serialize(),
      success: function (data) {
        $('.csrf_token').val(data.regenerate_token);
        if(data.response){
          window.location.href = base_url + "admin/dashboard/index";
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

});

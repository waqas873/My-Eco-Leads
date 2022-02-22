$(document).ready(function(){

$(document).on('click', '#send_sms_btn', function (e) {
  $url = base_url + "chat/process_send_sms";
  //$('#send_sms_btn').prop('disabled', true);
  $.ajax({
      url: $url,
      type: "POST",
      dataType: 'json',
      data: $('#send_sms_form').serializeArray(),
      success: function (data) {
        $('.csrf_token').val(data.regenerate_token);
        $('.all_errors').empty();
        if(data.response){
          $('#send_sms_form').trigger("reset");
          swal({
            type: 'success',
            title: 'Success!',
            text: "Message sent successfully."
          })
        }
        else{
          $('#msg_content_error').html(data.msg_content_error);
          //$('#send_sms_btn').prop('disabled', false);
        }
      }
  });
});

});

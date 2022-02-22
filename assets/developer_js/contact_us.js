$(document).ready(function(){
$('#send_message').attr("disabled","disabled");
$(document).on('click', '#send_message', function (e) {
  $url = base_url + "home/process_contact_us";
  $.ajax({
      url: $url,
      type: "POST",
      dataType: 'json',
      data: $('#contact_us_form').serializeArray(),
      success: function (data) {
        $('.csrf_token').val(data.regenerate_token);
        $('.errors').empty();
        if(data.response){
          $('.all_inputs').val('');
          $('#send_message').attr("disabled","disabled");
          swal({
            type: 'success',
            title: 'Success!',
            text: 'Your message has been sent successfully.'
          })
        }
        else{
          $('#name_error').html(data.name_error);
          $('#email_error').html(data.email_error);
          $('#phone_no_error').html(data.phone_no_error);
          $('#subject_error').html(data.subject_error);
          $('#message_error').html(data.message_error);
        }
      }
  });
  return false;
});

});

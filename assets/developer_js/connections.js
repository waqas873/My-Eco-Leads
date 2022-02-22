$(document).ready(function(){

//$('#purchase_btn').hide();

$(document).on('click', '#fetch_numbers_btn', function (e) {
  $url = base_url + "connections/fetch_numbers";
  $('#available_numbers').empty();
  //$('#fetch_numbers_btn').prop('disabled', true);
  $.ajax({
      url: $url,
      type: "POST",
      dataType: 'json',
      data: $('#get_twilio_num_form').serializeArray(),
      success: function (data) {
        $('.csrf_token').val(data.regenerate_token);
        $('.all_errors').empty();
        if(data.response){
          //$('.all_inputs').val('');
          if(data.numbers!=''){
            $('#available_numbers').append(data.numbers);
            $('#fetch_numbers_btn').hide();
            $('#purchase_btn').fadeIn();
          }
        }
        else{
          $('#country_code_error').html(data.country_code_error);
          //$('#fetch_numbers_btn').prop('disabled', false);
        }
      }
  });
});

$(document).on('click', '#purchase_btn', function (e) {
  
  return false;

  $url = base_url + "connections/purchase_num";
  //$('#purchase_btn').prop('disabled', true);
  $.ajax({
      url: $url,
      type: "POST",
      dataType: 'json',
      data: $('#get_twilio_num_form').serializeArray(),
      success: function (data) {
        $('.csrf_token').val(data.regenerate_token);
        $('.all_errors').empty();
        if(data.response){
          $('#get_twilio_num_form').trigger("reset");
          $('#available_numbers').empty();
          if(data.duplicate){
            swal({
              type: 'error',
              title: 'Waring!',
              text: "You have already added a number."
            })
          }
          else{
            $('#purchase_btn').hide();
            $('#fetch_numbers_btn').fadeIn();
            swal({
              type: 'success',
              title: 'Success!',
              text: "You have purchased number successfully."
            })
          }
        }
        else{
          $('#twilio_number_error').html(data.twilio_number_error);
          //$('#fetch_numbers_btn').prop('disabled', false);
        }
      }
  });
});


});

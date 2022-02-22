$(document).ready(function(){

  $(document).on('click', '#add_price', function (e) {
    $('.all_inputs').empty();
    $('#add_modal').modal('show');
  });
    
  $(document).on('click', '#add_price_btn', function (e) {
    $url = base_url + "admin/settings/process_add";
    $.ajax({
        url: $url,
        type: "POST",
        dataType: 'json',
        data: $('#add_price_form').serializeArray(),
        success: function (data) {
          $('.csrf_token').val(data.regenerate_token);
          $('.all_errors').empty();
          if(data.response){
            var redirect = base_url + "admin/settings";
            window.location.replace(redirect);
          }
          else{
            $('#price_error').html(data.price_error);
          }
        }
    });
    return false;
  });

});
    
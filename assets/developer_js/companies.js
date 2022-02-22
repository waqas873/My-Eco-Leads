$(document).ready(function(){

$(document).on('click', '#company_btn', function (e) {
  $url = base_url + "companies/process_company";
  $.ajax({
      url: $url,
      type: "POST",
      dataType: 'json',
      data: $('#company_form').serialize(),
      success: function (data) {
        $('.csrf_token').val(data.regenerate_token);
        $('.errors').empty();
        if(data.response){
          swal({
            type: 'success',
            title: 'Success!',
            text: data.msg
          })
        }
        else{
          $('#user_name_error').html(data.user_name_error);
          $('#name_error').html(data.name_error);
          $('#registration_no_error').html(data.registration_no_error);
          $('#fca_license_no_error').html(data.fca_license_no_error);
          $('#address_error').html(data.address_error);
          $('#contact_no_error').html(data.contact_no_error);
        }
      }
  });
  return false;
});

});

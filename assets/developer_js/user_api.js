$(document).ready(function(){

function hideModal() {
  $("#myModal").removeClass("in");
  $(".modal-backdrop").remove();
  $('body').removeClass('modal-open');
  $('body').css('padding-right', '');
  $("#myModal").hide();
}

$(document).on('click', '.save_btn', function (e) {
  var obj = $(this);
  var api_id = obj.attr('rel');
  data = $('#api_form_'+api_id).serializeArray();
  data.push({'name':'api_id','value':api_id});
  $url = base_url + "apis/process_api";
  $.ajax({
      url: $url,
      type: "POST",
      dataType: 'json',
      data: data,
      success: function (data) {
        $('.csrf_token').val(data.regenerate_token);
        $('.errors').empty();
        if(data.response){
          $("#checkbox_"+api_id).prop("checked", true);
          swal({
            type: 'success',
            title: 'Success!',
            text: data.msg
          })
        }
        else{
          $('#hubsolv_api_key_error_'+api_id).html(data.hubsolv_api_key_error);
          $('#username_error_'+api_id).html(data.username_error);
          $('#password_error_'+api_id).html(data.password_error);
          $('#lead_source_error_'+api_id).html(data.lead_source_error);
        }
      }
  });
  return false;
});

$(document).on('click', '#vision_api_test', function (e) {
  $('#vision-myModal').modal('show');
});

    $(document).on('click', '#test_btn', function (e) {
        $('#vision_api_myModal').modal('show');
    });
    $(document).on('click', '#blue_vision_btn', function (e) {
        data = $('#blue_vision-form').serializeArray();
        $url = base_url + "apis/blueVision_api_test";
        $.ajax({
            url: $url,
            type: "POST",
            dataType: 'json',
            data: data,
            success: function (data) {
                $('.csrf_token').val(data.regenerate_token);
                $('.errors').empty();
                if(data.response){
                    $('#blue_vision-form').trigger("reset");
                    //hideModal();
                    if(data.api_response){
                        swal({
                            type: 'success',
                            title: 'Valid Response',
                            text: 'Testing completed successfully.'
                        })
                    }
                    else{
                        swal({
                            type: 'error',
                            title: 'Invalid Response',
                            text: 'Invalid Response'
                        })
                    }
                }
                else{
                    $('#firstname_error').html(data.firstname_error);
                    $('#lastname_error').html(data.lastname_error);
                    $('#email_error').html(data.email_error);
                    $('#phone_mobile_error').html(data.phone_mobile_error);
                }
            }
        });
        return false;
    });
$(document).on('click', '#hubsolv_btn', function (e) {
  data = $('#hubsolv_form').serializeArray();
  $url = base_url + "apis/hubsolv_api_test";
  $.ajax({
      url: $url,
      type: "POST",
      dataType: 'json',
      data: data,
      success: function (data) {
        $('.csrf_token').val(data.regenerate_token);
        $('.errors').empty();
        if(data.response){
          $('#hubsolv_form').trigger("reset");
          //hideModal();
          if(data.api_response){
            swal({
              type: 'success',
              title: 'Valid Response',
              text: 'Testing completed successfully.'
            })
          }
          else{
            swal({
              type: 'error',
              title: 'Invalid Response',
              text: 'Invalid Response'
            })
          }
        }
        else{
          $('#firstname_error').html(data.firstname_error);
          $('#lastname_error').html(data.lastname_error);
          $('#email_error').html(data.email_error);
          $('#phone_mobile_error').html(data.phone_mobile_error);
        }
      }
  });
  return false;
});

$(document).on('click', '.zeavo_save_btn', function (e) {
  var obj = $(this);
  var api_id = obj.attr('rel');
  data = $('#api_form_'+api_id).serializeArray();
  data.push({'name':'api_id','value':api_id});
  $url = base_url + "apis/process_zeavo_api";
  $.ajax({
      url: $url,
      type: "POST",
      dataType: 'json',
      data: data,
      success: function (data) {
        $('.csrf_token').val(data.regenerate_token);
        $('.errors').empty();
        if(data.response){
          $("#checkbox_"+api_id).prop("checked", true);
          swal({
            type: 'success',
            title: 'Success!',
            text: data.msg
          })
        }
        else{
          $('#api_key_error_'+api_id).html(data.api_key_error);
          $('#api_url_error_'+api_id).html(data.api_url_error);
          $('#lead_group_id_error_'+api_id).html(data.lead_group_id_error);
        }
      }
  });
  return false;
});

$(document).on('click', '.abbotts_save_btn', function (e) {
  var obj = $(this);
  var api_id = obj.attr('rel');
  data = $('#api_form_'+api_id).serializeArray();
  data.push({'name':'api_id','value':api_id});
  $url = base_url + "apis/process_abbotts_api";
  $.ajax({
      url: $url,
      type: "POST",
      dataType: 'json',
      data: data,
      success: function (data) {
        $('.csrf_token').val(data.regenerate_token);
        $('.errors').empty();
        if(data.response){
          $("#checkbox_"+api_id).prop("checked", true);
          swal({
            type: 'success',
            title: 'Success!',
            text: data.msg
          })
        }
        else{
          $('#api_token_error_'+api_id).html(data.api_token_error);
          $('#api_url_error_'+api_id).html(data.api_url_error);
          $('#lead_group_id_error_'+api_id).html(data.lead_group_id_error);
          $('#team_id_error_'+api_id).html(data.team_id_error);
        }
      }
  });
  return false;
});
//for Byte Lead
    $(document).on('click', '#lead_byte_save_btn', function (e) {
        var obj = $(this);
        var api_id = obj.attr('rel');
        data = $('#api_form_'+api_id).serializeArray();
         data.push({'name':'api_id','value':api_id});
        $url = base_url + "apis/byte_lead_api";
        $.ajax({
            url: $url,
            type: "POST",
            dataType: 'json',
            data: data,
            success: function (data) {
                $('.csrf_token').val(data.regenerate_token);
                $('.errors').empty();
                if(data.response){
                    $("#checkbox_"+api_id).prop("checked", true);
                    swal({
                        type: 'success',
                        title: 'Success!',
                        text: data.msg
                    })
                }
                else{

                   // $('#api_token_error_'+api_id).html(data.api_token_error);
                    $('#campaign_error_'+api_id).html(data.campaign_id);
                    $('#sid_error_'+api_id).html(data.sid);
                }
            }
        });
        return false;
    });
$(document).on('click', '.checkbox', function (e) {
  var obj = $(this);
  var api_id = obj.attr('rel');
  $url = base_url + "apis/check_api";
  $.ajax({
      url: $url,
      type: "POST",
      dataType: 'json',
      data: {'api_id':api_id},
      success: function (data) {
        if(data.response){
          $("#checkbox_"+api_id).prop("checked", true);
        }
        else{
          $("#checkbox_"+api_id).prop("checked", false);
        }
      }
  });
  return false;
});

});

$(document).ready(function(){

$('.kt-scroll').scrollTop($('.kt-scroll')[0].scrollHeight);
$(document).on('click', '.lead_user', function (e) {
  var lead_id = $(this).attr("rel");
  if(lead_id==''){
    return false;
  }
  $("#chat_box").empty();
  $("#lead_id").val('');
  $('.all_dots').hide();
  $('.lead_user').css({"color":"#61586D"});
  $('#single_dot_'+lead_id).fadeIn();
  $('#lead_user_'+lead_id).css({"color":"#B269AE"});
  $url = base_url + "chat/fetch_chat";
  $.ajax({
      url: $url,
      type: "POST",
      dataType: 'json',
      data: {'lead_id':lead_id},
      success: function (data) {
        if(data.response){
          $("#chat_box").append(data.chat);
          $("#lead_id").val(lead_id);
        }
        else{
          
        }
      }
  });
});

$('#plz_wait').hide();

$(document).on('click', '#reply_button', function (e) {
  $url = base_url + "chat/process_send_sms";
  var lead_id = $('#lead_id').val();
  var formData = $('#message_form').serializeArray();
  formData.push({'name':'lead_id','value':lead_id});
  $('#reply_button').hide();
  $('#plz_wait').fadeIn();
  $.ajax({
      url: $url,
      type: "POST",
      dataType: 'json',
      data: formData,
      success: function (data) {
        $('.csrf_token').val(data.regenerate_token);
        $('#message_form').trigger("reset");
        if(data.response){
          $('#chat_box').append(data.message);
        }
        else{
          if(data.stop){
            swal({
              type: 'error',
              title: 'Warning!',
              text: "This user has stopped message service."
            })
          }
          else if(data.sms_unavailable){
            swal({
              type: 'error',
              title: 'Warning!',
              text: "You don't have sms.Please purchase sms for this service."
            })
          }
          else{
            swal({
              type: 'error',
              title: 'Warning!',
              text: "Invalid request to send message."
            })
          }
        }
        $('#plz_wait').hide();
        $('#reply_button').fadeIn();
      }
  });
});

$(document).on('keyup', '#search_leads', function (e) {
  $url = base_url + "chat/fetch_leads";
  var search = $(this).val();
  $.ajax({
      url: $url,
      type: "POST",
      dataType: 'json',
      data: {'search':search},
      success: function (data) {
        $('#leads_list').empty();
        if(data.response){
          $('#leads_list').append(data.leads_list);
        }
        else{
          var no_leads = '<p style="text-align:center;">No Leads Available.</p>';
          $('#leads_list').append(no_leads);
        }
      }
  });
});

setInterval(function() {
  var lead_id_1 = $('#lead_id').val();
  if(lead_id_1!=''){
    $.ajax({
      url: base_url + "chat/user_replies",
      type: "POST",
      dataType: 'json',
      data: {'lead_id':lead_id_1},
      success: function (data) {
        if(data.response){
          $('#chat_box').append(data.messages);
        }
      }
    });
  }
}, 10000); 

});

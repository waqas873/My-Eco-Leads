$(document).ready(function(){

$(document).on('click', '.user_api_detail', function (e) {
  var user_api_id = $(this).attr('rel');
  $('#api_detail').empty();
  if(user_api_id != ''){
    $url = base_url + "admin/user_apis/user_api_detail";
    $.ajax({
        url: $url,
        type: "POST",
        dataType: 'json',
        data: {'user_api_id':user_api_id},
        success: function (data) {
          if(data.response){
            $('#api_detail').append(data.api_detail);
            $('#api_detail_modal').modal('show');
          }
        }
    });
  }
  return false;
});

$(document).on('click', '.edit_user_api', function (e) {
  var user_api_id = $(this).attr('rel');
  $('.all_inputs').empty();
  $url = base_url + "admin/user_apis/update/"+user_api_id;
  $.ajax({
      url: $url,
      type: "POST",
      dataType: 'json',
      success: function (data) {
        if(data.response){
          $('.user_api_id').val(data.user_api_id);
          $('.api_name').val(data.api_name);
          if(data.api_name=="hubsolv"){
            $('#hubsolv_api_key').val(data.hubsolv_api_key);
            $('#username').val(data.username);
            $('#password').val(data.password);
            $('#api_url').val(data.api_url);
            $('#hubsolv_modal').modal('show');
          }
          if(data.api_name=="zeavo"){
            $('#api_key').val(data.api_key);
            $('#lead_group_id').val(data.lead_group_id);
            $('#zeavo_api_url').val(data.zeavo_api_url);
            $('#zeavo_modal').modal('show');
          }
        }
      }
  });
  return false;
});

$(document).on('click', '#hubsolv_update_btn', function (e) {
  $url = base_url + "admin/user_apis/process_update";
  $.ajax({
      url: $url,
      type: "POST",
      dataType: 'json',
      data: $('#hubsolv_form').serializeArray(),
      success: function (data) {
        $('.csrf_token').val(data.regenerate_token);
        $('.all_errors').empty();
        if(data.response){
          $('.all_inputs').empty();
          $('#hubsolv_modal').modal('hide');
          swal({
            type: 'success',
            title: 'Success!',
            text: "API has been updated successfully."
          })
        }
        else{
          $('#hubsolv_api_key_error').html(data.hubsolv_api_key_error);
          $('#username_error').html(data.username_error);
          $('#password_error').html(data.password_error);
          $('#api_url_error').html(data.api_url_error);
        }
      }
  });
  return false;
});

$(document).on('click', '#zeavo_update_btn', function (e) {
  $url = base_url + "admin/user_apis/process_update";
  $.ajax({
      url: $url,
      type: "POST",
      dataType: 'json',
      data: $('#zeavo_form').serializeArray(),
      success: function (data) {
        $('.csrf_token').val(data.regenerate_token);
        $('.all_errors').empty();
        if(data.response){
          $('.all_inputs').empty();
          $('#zeavo_modal').modal('hide');
          swal({
            type: 'success',
            title: 'Success!',
            text: "API has been updated successfully."
          })
        }
        else{
          $('#api_key_error').html(data.api_key_error);
          $('#lead_group_id_error').html(data.lead_group_id_error);
          $('#zeavo_api_url_error').html(data.zeavo_api_url_error);
        }
      }
  });
  return false;
});

$('#sample_user_apis').DataTable({
  "ordering": true,
  "lengthChange": true,
  "searching": true,
  "processing":true,
  "serverSide": true,
  "ajax": {
      url: base_url + 'admin/user_apis/get_user_apis',
      type: 'POST',
      "data": function (d) {
          return $.extend({}, d, {
          });
      }
  },
  "order": [
      [0, 'asc']
  ],
  columnDefs: [
      {'targets': 3, 'orderable': false},
      {'targets': 4, 'orderable': false}
  ],
  "columns": [
      {"data": "user_full_name"},
      {"data": "email"},
      {"data": "api_name"},
      {"data": "created_at"},
      {"data": "action"}
  ]
});
// $(document).on('change', '#status_filter', function (e) {
//  $('#sample_apis').DataTable().ajax.reload();  
// });

});

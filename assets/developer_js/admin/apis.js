$(document).ready(function(){

$(document).on('click', '#add_api', function (e) {
  $('.all_inputs').empty();
  $('#add_modal').modal('show');
});

$(document).on('click', '#add_api_btn', function (e) {
  $url = base_url + "admin/apis/process_add";
  $.ajax({
      url: $url,
      type: "POST",
      dataType: 'json',
      data: $('#add_api_form').serializeArray(),
      success: function (data) {
        $('.csrf_token').val(data.regenerate_token);
        $('.all_errors').empty();
        if(data.response){
          $('.all_inputs').val('');
          $('#add_modal').modal('hide');
          $('#sample_apis').DataTable().ajax.reload();
          swal({
            type: 'success',
            title: 'Success!',
            text: "The API has been added successfully."
          })
        }
        else{
          $('#api_name_error').html(data.api_name_error);
        }
      }
  });
  return false;
});

$(document).on('click', '.edit_api', function (e) {
  var api_id = $(this).attr('rel');
  $('.all_inputs').empty();
  $url = base_url + "admin/apis/update/"+api_id;
  $.ajax({
      url: $url,
      type: "POST",
      dataType: 'json',
      success: function (data) {
        if(data.response){
          $('#api_id').val(data.api.api_id);
          $('#update_api_name').val(data.api.api_name);
          $('#api_status').append(data.status);
          $('#update_modal').modal('show');
        }
      }
  });
  return false;
});

$(document).on('click', '#update_api_btn', function (e) {
  $url = base_url + "admin/apis/process_update";
  $.ajax({
      url: $url,
      type: "POST",
      dataType: 'json',
      data: $('#update_api_form').serializeArray(),
      success: function (data) {
        $('.csrf_token').val(data.regenerate_token);
        $('.all_errors').empty();
        if(data.response){
          $('.all_inputs').empty();
          $('#update_modal').modal('hide');
          $('#sample_apis').DataTable().ajax.reload();
          swal({
            type: 'success',
            title: 'Success!',
            text: "API has been updated successfully."
          })
        }
        else{
          $('#update_api_name_error').html(data.api_name_error);
        }
      }
  });
  return false;
});

$('#sample_apis').DataTable({
  "ordering": true,
  "lengthChange": true,
  "searching": true,
  "processing":true,
  "serverSide": true,
  "ajax": {
      url: base_url + 'admin/apis/get_apis',
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
      {'targets': 0, 'orderable': true}
  ],
  "columns": [
      {"data": "api_name"},
      {"data": "status"},
      {"data": "created_at"},
      {"data": "action"}
  ]
});
$(document).on('change', '#status_filter', function (e) {
 $('#sample_apis').DataTable().ajax.reload();  
});


});

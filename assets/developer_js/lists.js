$(document).ready(function(){

$(document).on('click', '#add_list_btn', function (e) {
  $url = base_url + "admin/lists/process_add";
  $.ajax({
      url: $url,
      type: "POST",
      dataType: 'json',
      data: $('#add_list_form').serialize(),
      success: function (data) {
        $('.csrf_token').val(data.regenerate_token);
        $('.all_errors').empty();
        if(data.response){
          $('.all_inputs').empty();
          swal({
            type: 'success',
            title: 'Success!',
            text: "The list has been added successfully."
          })
        }
        else{
          $('#list_name_error').html(data.list_name_error);
          $('#crm_id_error').html(data.crm_id_error);
          $('#list_category_error').html(data.list_category_error);
        }
      }
  });
  return false;
});

$(document).on('click', '.edit_list', function (e) {
  var list_id = $(this).attr('rel');
  $('.all_inputs').empty();
  $url = base_url + "admin/lists/update/"+list_id;
  $.ajax({
      url: $url,
      type: "POST",
      dataType: 'json',
      success: function (data) {
        if(data.response){
          $('#list_id').val(data.list.list_id);
          $('#list_name').val(data.list.list_name);
          $('#crm_id').val(data.list.crm_id);
          $('#status').append(data.status);
          $('#list_category').append(data.list_category);
          $('#list_modal').modal('show');
        }
        else{
        }
      }
  });
  return false;
});

$(document).on('click', '#update_list', function (e) {
  $url = base_url + "admin/lists/process_update";
  $.ajax({
      url: $url,
      type: "POST",
      dataType: 'json',
      data: $('#update_list_form').serialize(),
      success: function (data) {
        $('.all_errors').empty();
        if(data.response){
          $('.all_inputs').empty();
          $('#list_modal').modal('hide');
          $('#sample_lists').DataTable().ajax.reload();
          swal({
            type: 'success',
            title: 'Success!',
            text: "List has updated successfully."
          })
        }
        else{
          $('#list_name_error').html(data.list_name_error);
          $('#crm_id_error').html(data.crm_id_error);
          $('#list_category_error').html(data.list_category_error);
        }
      }
  });
  return false;
});

$(document).on('click', '#reset_btn', function (e) {
  $("#register_form").get(0).reset();
});

$('#sample_lists').DataTable({
  "ordering": true,
  "lengthChange": true,
  "searching": true,
  "processing":true,
  "serverSide": true,
  "ajax": {
      url: base_url + 'admin/lists/get_lists',
      type: 'POST',
      "data": function (d) {
          return $.extend({}, d, {
            "status_filter": $('#status_filter').val()
          });
      }
  },
  "order": [
      [0, 'asc']
  ],
  columnDefs: [
      {'targets': 0, 'orderable': true},
      {'targets': 3, 'orderable': false}
  ],
  "columns": [
      {"data": "list_name"},
      {"data": "list_category"},
      {"data": "status"},
      {"data": "leads"},
      {"data": "created_at"},
      {"data": "action"}
  ]
});
$(document).on('change', '#status_filter', function (e) {
 $('#sample_lists').DataTable().ajax.reload();  
});


});

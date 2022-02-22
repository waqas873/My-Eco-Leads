$(document).ready(function(){

$(document).on('click', '.edit_user', function (e) {
  var user_id = $(this).attr('rel');
  $('.all_inputs').empty();
  $url = base_url + "admin/users/update/"+user_id;
  $.ajax({
      url: $url,
      type: "POST",
      dataType: 'json',
      success: function (data) {
        if(data.status){
          $('#user_id').val(data.user.user_id);
          $('#first_name').val(data.user.first_name);
          $('#last_name').val(data.user.last_name);
          $('#email').val(data.user.email);
          var roleAdmin = (data.user.user_role==1)?"selected":"";
          var roleUser = (data.user.user_role==0)?"selected":"";
          var role = '<option value="0" '+roleUser+'>User</option><option value="1" '+roleAdmin+'>Admin</option>'
          $('#user_role').append(role);
          var statusPending = (data.user.status==0)?"selected":"";
          var statusActive = (data.user.status==1)?"selected":"";
          var statusBlock = (data.user.status==2)?"selected":"";
          var status = '<option value="0" '+statusPending+'>Pending</option><option value="1" '+statusActive+'>Active</option><option value="2" '+statusBlock+'>Block</option>'
          $('#status').append(status);
          $('#connection_dd').html(data.numbers);
          $('#user_modal').modal('show');
        }
        else{
        }
      }
  });
  return false;
});

$(document).on('click', '#update_user', function (e) {
  $url = base_url + "admin/users/process_update";
  $.ajax({
      url: $url,
      type: "POST",
      dataType: 'json',
      data: $('#update_user_form').serializeArray(),
      success: function (data) {
        $('.all_errors').empty();
        if(data.response){
          $('.all_inputs').empty();
          $('#user_modal').modal('hide');
          $('#sample_users').DataTable().ajax.reload();
          swal({
            type: 'success',
            title: 'Success!',
            text: "User has updated successfully."
          })
        }
        else{
          $('#first_name_error').html(data.first_name_error);
          $('#last_name_error').html(data.last_name_error);
          $('#email_error').html(data.email_error);
        }
      }
  });
  return false;
});

$('#sample_users').DataTable({
  "ordering": true,
  "lengthChange": true,
  "searching": true,
  "processing":true,
  "serverSide": true,
  "ajax": {
      url: base_url + 'admin/users/get_users',
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
      {'targets': 6, 'orderable': false}
  ],
  "columns": [
      {"data": "first_name"},
      {"data": "last_name"},
      {"data": "email"},
      {"data": "status"},
      {"data": "days"},
      {"data": "time"},
      {"data": "orders"},
      {"data": "is_removed"},
      {"data": "is_paused"},
      {"data": "connection"},
      {"data": "created_at"},
      {"data": "action"}
  ]
});
$(document).on('change', '#status_filter', function (e) {
 $('#sample_users').DataTable().ajax.reload();  
});

});

$(document).ready(function(){

$(document).on('click', '.lead_info', function (e) {
  var lead_id = $(this).attr('rel');
  $('#lead_info').empty();
  if(lead_id!=''){
    $url = base_url + "admin/leads/lead_info";
    $.ajax({
        url: $url,
        type: "POST",
        dataType: 'json',
        data: {'lead_id':lead_id},
        success: function (data) {
          if(data.response){
            $('#lead_info').append(data.lead_info);
            $('#info_modal').modal('show');
          }
        }
    });
  }
  return false;
});

$(document).on('click', '.simple_check', function (e) {
  var obj = $(this);
  var lead_id = obj.attr('value');
  if(obj.is(':checked')){
    $('#force_check_'+lead_id).prop('checked', false);
  }
});

$(document).on('click', '.force_check', function (e) {
  var obj = $(this);
  var lead_id = obj.attr('value');
  if(obj.is(':checked')){
    $('#simple_check_'+lead_id).prop('checked', false);
  }
});

$(document).on('click', '.view_notes', function (e) {
  var lead_id = $(this).attr('rel');
  $('#view_notes').empty();
  if(lead_id!=''){
    $url = base_url + "admin/leads/view_notes";
    $.ajax({
        url: $url,
        type: "POST",
        dataType: 'json',
        data: {'lead_id':lead_id},
        success: function (data) {
          if(data.response){
            $('#view_notes').append(data.view_notes);
            $('#view_notes_modal').modal('show');
          }
          else{
            swal({
              type: 'error',
              title: 'Not Available',
              text: "There are no notes to display."
            })
          }
        }
    });
  }
  return false;
});

$(document).on('click', '#deliver_leads', function (e) {
  var user_id = $('#user_id').val();
  if(user_id==''){
      $.notify({
        title: '<strong>Warning !</strong> ',
        message: 'Please select a user'
      },{
        type: 'danger'
      });
      return false;
  }
  $('#form_user_id').val(user_id);
  $('#invalid_leads_form').submit();
});

$('#sample_leads').DataTable({
  "ordering": true,
  "lengthChange": true,
  "searching": true,
  "processing":true,
  "serverSide": true,
  "ajax": {
      url: base_url + 'admin/leads/get_leads',
      type: 'POST',
      "data": function (d) {
          return $.extend({}, d, {
            "status_filter": $('#status_filter').val(),
            //"list_id": $('#list_id').val(),
            "from_date": $('#from_date').val(),
            "to_date": $('#to_date').val(),
            "user_id": $('#user_id').val(),
            "validation_filter": $('#validation_filter').val(),
            "action_filter": $('#action_filter').val(),
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
      {"data": "first_name"},
      {"data": "last_name"},
      {"data": "email"},
      {"data": "contact_mobile"},
      {"data": "confirm_mobile_number"},
      {"data": "best_time_to_call"},
      {"data": "status"},
      {"data": "user_fullname"},
      {"data": "action"},
      {"data": "notes"},
      {"data": "lead_info"},
      {"data": "lead_appeal"},
      {"data": "created_at"}
  ]
});
$(document).on('change', '#status_filter', function (e) {
 $('#sample_leads').DataTable().ajax.reload();  
});
$(document).on('change', '#from_date', function (e) {
 $('#sample_leads').DataTable().ajax.reload();  
});
$(document).on('change', '#to_date', function (e) {
 $('#sample_leads').DataTable().ajax.reload();  
});
$(document).on('change', '#user_id', function (e) {
 $('#sample_leads').DataTable().ajax.reload();  
});
$(document).on('change', '#validation_filter', function (e) {
 $('#sample_leads').DataTable().ajax.reload();  
});
$(document).on('change', '#action_filter', function (e) {
 $('#sample_leads').DataTable().ajax.reload();  
});


$('#sample_invalid_leads').DataTable({
  "ordering": true,
  "lengthChange": true,
  "searching": true,
  "processing":true,
  "serverSide": true,
  "ajax": {
      url: base_url + 'admin/leads/get_invalid_leads',
      type: 'POST',
      "data": function (d) {
          return $.extend({}, d, {
            "from_date": $('#in_from_date').val(),
            "to_date": $('#in_to_date').val(),
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
      {"data": "simple_check"},
      {"data": "force_check"},
      {"data": "first_name"},
      {"data": "last_name"},
      {"data": "email"},
      {"data": "contact_mobile"},
      {"data": "lead_info"},
      {"data": "created_at"}
  ]
});
$(document).on('change', '#in_from_date', function (e) {
 $('#sample_invalid_leads').DataTable().ajax.reload();  
});
$(document).on('change', '#in_to_date', function (e) {
 $('#sample_invalid_leads').DataTable().ajax.reload();  
});


});

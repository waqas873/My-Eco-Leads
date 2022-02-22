$(document).ready(function(){

$(document).on('keyup', '#total_leads', function (e) {
  var total_leads = $('#total_leads').val();
  $('#amount').val('');
  if(total_leads != ''){
    $url = base_url + "admin/orders/leads_amount";
    $.ajax({
        url: $url,
        type: "POST",
        dataType: 'json',
        data: {'total_leads':total_leads},
        success: function (data) {
          if(data.response){
            $('#amount').val(data.amount);
          }
          else{
            swal({
              type: 'danger',
              title: 'Warning!',
              text: data.msg
            })
          }
        }
    });
  }
  return false;
});

$(document).on('click', '#add_order_btn', function (e) {
  $url = base_url + "admin/orders/process_manual_add";
  $.ajax({
      url: $url,
      type: "POST",
      dataType: 'json',
      data: $('#add_order_form').serializeArray(),
      success: function (data) {
        $('.csrf_token').val(data.regenerate_token);
        $('.all_errors').empty();
        if(data.response){
          window.location.replace(data.url);
        }
        else{
          $('#total_leads_error').html(data.total_leads_error);
          $('#user_id_error').html(data.user_id_error);
          $('#email_error').html(data.email_error);
          $('#amount_error').html(data.amount_error);
        }
      }
  });
  return false;
});

$(document).on('click', '#update_order_btn', function (e) {
  $url = base_url + "admin/orders/process_manual_update";
  $.ajax({
      url: $url,
      type: "POST",
      dataType: 'json',
      data: $('#update_order_form').serializeArray(),
      success: function (data) {
        $('.csrf_token').val(data.regenerate_token);
        $('.all_errors').empty();
        if(data.response){
          window.location.replace(data.url);
        }
        else{
          $('#total_leads_error').html(data.total_leads_error);
          $('#user_id_error').html(data.user_id_error);
          $('#email_error').html(data.email_error);
          $('#amount_error').html(data.amount_error);
        }
      }
  });
  return false;
});

$('#sample_orders').DataTable({
  "ordering": true,
  "lengthChange": true,
  "searching": true,
  "processing":true,
  "serverSide": true,
  "ajax": {
      url: base_url + 'admin/orders/get_orders',
      type: 'POST',
      "data": function (d) {
          return $.extend({}, d, {
            "user_id": $('#user_id').val(),
            "status_filter": $('#status_filter').val()
          });
      }
  },
  "order": [
      [0, 'asc']
  ],
  // columnDefs: [
  //     {'targets': 0, 'orderable': true},
  //     {'targets': 3, 'orderable': false}
  // ],
  "columns": [
      {"data": "total_leads"},
      {"data": "delivered_leads"},
      {"data": "remaining_leads"},
      {"data": "order_amount"},
      {"data": "status"},
      {"data": "created_at"}
  ]
});
$(document).on('change', '#status_filter', function (e) {
 $('#sample_orders').DataTable().ajax.reload();  
});

$('#sample_manual_orders').DataTable({
  "ordering": true,
  "lengthChange": true,
  "searching": true,
  "processing":true,
  "serverSide": true,
  "ajax": {
      url: base_url + 'admin/orders/get_manual_orders',
      type: 'POST',
      "data": function (d) {
          return $.extend({}, d, {
            "status_filter": $('#status_manual_filter').val()
          });
      }
  },
  "order": [
      [0, 'asc']
  ],
  // columnDefs: [
  //     {'targets': 0, 'orderable': true},
  //     {'targets': 3, 'orderable': false}
  // ],
  "columns": [
      {"data": "total_leads"},
      {"data": "user"},
      {"data": "delivered_leads"},
      {"data": "remaining_leads"},
      {"data": "order_amount"},
      {"data": "status"},
      {"data": "created_at"},
      {"data": "edit"}
  ]
});
$(document).on('change', '#status_manual_filter', function (e) {
 $('#sample_manual_orders').DataTable().ajax.reload();  
});

});

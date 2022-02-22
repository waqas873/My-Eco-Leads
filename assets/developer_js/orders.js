$(document).ready(function(){

$(document).on('click', '#order_btn', function (e) {
  $url = base_url + "orders/order";
  $.ajax({
      url: $url,
      type: "POST",
      dataType: 'json',
      data: $('#order_form').serialize(),
      success: function (data) {
        $('.csrf_token').val(data.regenerate_token);
        $('.errors').empty();
        if(data.response){
          $('.all_inputs').val('');
          window.location.href = base_url + "orders/payment/"+data.total_leads;
        }
        else{
          $('#total_leads_error').html(data.total_leads_error);
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
      url: base_url + 'orders/get_orders',
      type: 'POST',
      "data": function (d) {
          return $.extend({}, d, {
            "status_filter": $('#status_filter').val(),
            "from_date": $('#from_date').val(),
            "to_date": $('#to_date').val()
          });
      }
  },
  "order": [
      [0, 'asc']
  ],
  // columnDefs: [
  //     {'targets': 0, 'orderable': false}
  // ],
  "columns": [
      {"data": "total_leads"},
      {"data": "remaining_leads"},
      {"data": "order_amount"},
      {"data": "status"},
      {"data": "leads"},
      {"data": "created_at"}
  ]
});
$(document).on('change', '#status_filter', function (e) {
 $('#sample_orders').DataTable().ajax.reload();  
});
$(document).on('change', '#from_date', function (e) {
 $('#sample_orders').DataTable().ajax.reload();  
});
$(document).on('change', '#to_date', function (e) {
 $('#sample_orders').DataTable().ajax.reload();  
});

$('#sample_leads').DataTable({
  "ordering": true,
  "lengthChange": true,
  "searching": true,
  "processing":true,
  "serverSide": true,
  "ajax": {
      url: base_url + 'orders/get_leads',
      type: 'POST',
      "data": function (d) {
          return $.extend({}, d, {
            "order_id": $('#order_id').val()
            //"status_filter": $('#status_filter').val()
          });
      }
  },
  "order": [
      [0, 'asc']
  ],
  "columns": [
      {"data": "first_name"},
      {"data": "last_name"},
      {"data": "email"},
      {"data": "contact_mobile"},
      {"data": "status"},
      {"data": "delivered_date"}
  ]
});
// $(document).on('change', '#status_filter', function (e) {
//  $('#sample_orders').DataTable().ajax.reload();  
// });

});

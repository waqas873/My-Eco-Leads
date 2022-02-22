$(document).ready(function(){

$(document).on('click', '#order_btn', function (e) {
  $url = base_url + "sms_orders/order";
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
          window.location.href = base_url + "sms_orders/payment/"+data.total_sms;
        }
        else{
          $('#total_sms_error').html(data.total_sms_error);
        }
      }
  });
  return false;
});

$('#sample_sms_orders').DataTable({
  "ordering": true,
  "lengthChange": true,
  "searching": true,
  "processing":true,
  "serverSide": true,
  "ajax": {
      url: base_url + 'sms_orders/get_orders',
      type: 'POST',
      "data": function (d) {
          return $.extend({}, d, {
            // "status_filter": $('#status_filter').val(),
            // "from_date": $('#from_date').val(),
            // "to_date": $('#to_date').val()
          });
      }
  },
  "order": [
      [0, 'asc']
  ],
  "columns": [
      {"data": "total_sms"},
      {"data": "remaining_sms"},
      {"data": "order_amount"},
      {"data": "status"},
      {"data": "created_at"}
  ]
});
// $(document).on('change', '#status_filter', function (e) {
//  $('#sample_orders').DataTable().ajax.reload();  
// });


});

$(document).ready(function(){

$(document).on('click', '.view_response', function (e) {
  var phone_number_id = $(this).attr('rel');
  $('#view_response').empty();
  if(phone_number_id!=''){
    $url = base_url + "admin/phone_numbers/view_response";
    $.ajax({
        url: $url,
        type: "POST",
        dataType: 'json',
        data: {'phone_number_id':phone_number_id},
        success: function (data) {
          if(data.response){
            $('#view_response').append(data.view_response);
            $('#view_response_modal').modal('show');
          }
        }
    });
  }
  return false;
});

$('#sample_phone_numbers').DataTable({
  "ordering": true,
  "lengthChange": true,
  "searching": true,
  "processing":true,
  "serverSide": true,
  "ajax": {
      url: base_url + 'admin/phone_numbers/get_phone_numbers',
      type: 'POST',
      "data": function (d) {
          return $.extend({}, d, {
            "from_date": $('#from_date').val(),
            "to_date": $('#to_date').val()
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
      {"data": "phone_number"},
      {"data": "valid"},
      {"data": "view_response"}
  ]
});
$(document).on('change', '#from_date', function (e) {
 $('#sample_phone_numbers').DataTable().ajax.reload();  
});
$(document).on('change', '#to_date', function (e) {
 $('#sample_phone_numbers').DataTable().ajax.reload();  
});


});

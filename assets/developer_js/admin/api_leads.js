$(document).ready(function(){

$('#sample_api_leads').DataTable({
  "ordering": true,
  "lengthChange": true,
  "searching": true,
  "processing":true,
  "serverSide": true,
  "ajax": {
      url: base_url + 'admin/api_leads/get_api_leads',
      type: 'POST',
      "data": function (d) {
          return $.extend({}, d, {
            "status_filter": $('#status_filter').val(),
            "from_date": $('#from_date').val(),
            "to_date": $('#to_date').val(),
            "user_id": $('#user_id').val()
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
      {"data": "first_name"},
      {"data": "last_name"},
      {"data": "email"},
      {"data": "contact_mobile"},
      {"data": "status"},
      {"data": "to_user"},
      {"data": "failed_attempts"},
      {"data": "created_at"}
  ]
});
$(document).on('change', '#status_filter', function (e) {
 $('#sample_api_leads').DataTable().ajax.reload();  
});
$(document).on('change', '#from_date', function (e) {
 $('#sample_api_leads').DataTable().ajax.reload();  
});
$(document).on('change', '#to_date', function (e) {
 $('#sample_api_leads').DataTable().ajax.reload();  
});
$(document).on('change', '#user_id', function (e) {
 $('#sample_api_leads').DataTable().ajax.reload();  
});

});

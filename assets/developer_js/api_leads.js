$(document).ready(function(){

$('#sample_api_leads').DataTable({
  "ordering": true,
  "lengthChange": true,
  "searching": true,
  "processing":true,
  "serverSide": true,
  "ajax": {
      url: base_url + 'api_leads/get_api_leads',
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
  // columnDefs: [
  //     {'targets': 0, 'orderable': true},
  //     {'targets': 3, 'orderable': false}
  // ],
  "columns": [
      {"data": "first_name"},
      {"data": "last_name"},
      {"data": "contact_mobile"},
      {"data": "status"},
      {"data": "failed_attempts"},
      {"data": "created_at"}
  ]
});
$(document).on('change', '#status_filter', function (e) {
 $('#sample_api_leads').DataTable().ajax.reload();  
});

});

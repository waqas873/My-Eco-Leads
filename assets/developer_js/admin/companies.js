$(document).ready(function(){

$(document).on('click', '.send_url', function (e) {
  var company_id = $(this).attr('rel');
  if(company_id!=''){
    $('.all_errors').empty();
    $('.all_inputs').val('');
    $('#company_id').val(company_id);
    $('#myModal').modal('show');
  }
});

$(document).on('click', '#company_url_btn', function (e) {
  $('.all_errors').empty();
  var data = $('#company_url_form').serializeArray();
  $url = base_url + "admin/companies/send_url";
  $.ajax({
      url: $url,
      type: "POST",
      dataType: 'json',
      data: data,
      success: function (data) {
        $('.csrf_token').val(data.regenerate_token);
        if(data.response){
          var redirect_url = base_url + "admin/companies/url_sent/eW3hInjR";
          window.location.replace(redirect_url);
        }
        else{
          $('#company_url_error').html(data.company_url_error);
        }
      }
  });
  return false;
});

$('#sample_companies').DataTable({
  "ordering": true,
  "lengthChange": true,
  "searching": true,
  "processing":true,
  "serverSide": true,
  "ajax": {
      url: base_url + 'admin/companies/get_companies',
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
      {"data": "user_name"},
      {"data": "company_name"},
      {"data": "registration_no"},
      {"data": "fca_license_no"},
      {"data": "address"},
      {"data": "contact_no"},
      {"data": "status"},
      {"data": "created_at"},
      {"data": "action"}
  ]
});
$(document).on('change', '#status_filter', function (e) {
 $('#sample_companies').DataTable().ajax.reload();  
});

});

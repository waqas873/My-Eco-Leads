$(document).ready(function(){

$('#plz_wait').hide();

$(document).on('click', '#add_button', function (e) {
  e.preventDefault();
  $url = base_url + "keywords/process_add";
  var formData = $('#add_form').serializeArray();
  $('#add_button').hide();
  $('#plz_wait').fadeIn();
  $('.all_errors').empty();
  $.ajax({
      url: $url,
      type: "POST",
      dataType: 'json',
      data: formData,
      success: function (data) {
        $('.csrf_token').val(data.regenerate_token);
        if(data.response){
          swal({
            type: 'success',
            title: 'Success!',
            text: "Keyword added successfully."
          })
          $('#add_form').trigger("reset");
        }
        else{
          $('#group_keyword_error').html(data.group_keyword_error);
          $('#group_reply_text_error').html(data.group_reply_text_error);
        }
        $('#plz_wait').hide();
        $('#add_button').fadeIn();
      }
  });
});

$(document).on('click', '#update_btn', function (e) {
  e.preventDefault();
  $url = base_url + "keywords/process_update";
  var formData = $('#update_form').serializeArray();
  $('#update_btn').hide();
  $('#plz_wait').fadeIn();
  $('.all_errors').empty();
  $.ajax({
      url: $url,
      type: "POST",
      dataType: 'json',
      data: formData,
      success: function (data) {
        $('.csrf_token').val(data.regenerate_token);
        if(data.response){
          swal({
            type: 'success',
            title: 'Success!',
            text: "Keyword updated successfully."
          })
        }
        else{
          $('#group_keyword_error').html(data.group_keyword_error);
          $('#group_reply_text_error').html(data.group_reply_text_error);
        }
        $('#plz_wait').hide();
        $('#update_btn').fadeIn();
      }
  });
});

$('#sample_keywords').DataTable({
  "ordering": true,
  "lengthChange": true,
  "searching": true,
  "processing":true,
  "serverSide": true,
  "ajax": {
      url: base_url + 'keywords/get_keywords',
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
      {"data": "group_keyword"},
      {"data": "group_reply_text"},
      {"data": "group_stamp"},
      {"data": "actions"}
  ]
});
// $(document).on('change', '#status_filter', function (e) {
//  $('#sample_orders').DataTable().ajax.reload();  
// });

});

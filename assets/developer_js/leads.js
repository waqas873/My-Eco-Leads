$(document).ready(function () {

    $('.plz_wait').hide();

    $(document).on('change', '.action', function (e) {
        var obj = $(this);
        var val = obj.val();
        var lead_id = obj.attr('name');
        if (val != '' && lead_id != '') {
            $url = base_url + "leads/change_action";
            $.ajax({
                url: $url,
                type: "POST",
                dataType: 'json',
                data: { 'action': val, 'lead_id': lead_id },
                success: function (data) {
                    if (data.response) {
                        if (data.remove_class != '') {
                            obj.removeClass(data.remove_class);
                        }
                        obj.addClass(data.add_class);
                        $('#' + data.new_action).empty();
                        $('#' + data.new_action).append(data.new_action_count);
                        if (data.old_action_count > 0) {
                            $('#' + data.old_action).empty();
                            $('#' + data.old_action).append(data.old_action_count);
                        }
                        swal({
                            type: 'success',
                            title: 'Success!',
                            text: "Lead action has been changed successfully."
                        })
                    }
                }
            });
        }
        return false;
    });

    $(document).on('click', '.lead_info', function (e) {
        var lead_id = $(this).attr('rel');
        $('#lead_info').empty();
        if (lead_id != '') {
            $url = base_url + "leads/lead_info";
            $.ajax({
                url: $url,
                type: "POST",
                dataType: 'json',
                data: { 'lead_id': lead_id },
                success: function (data) {
                    if (data.response) {
                        $('#lead_info').append(data.lead_info);
                        $('#info_modal').modal('show');
                    }
                }
            });
        }
        return false;
    });

    $(document).on('click', '.view_addresses', function (e) {
        var lead_id = $(this).attr('rel');
        $('#address_info').empty();
        $('.lead-table').empty();
        if (lead_id != '') {
            var url = base_url + "leads/addresses_info";
            $.ajax({
                url: url,
                type: "POST",
                dataType: 'json',
                data: { 'lead_id': lead_id },
                success: function (data) {
                    if (data.response) {
                        $('#address_info').append(data.address_info);
                        $('.lead-table').append(data.address_table);
                        $('#addresses_modal').modal('show');
                    }
                }
            });
        } else {
            swal({
                type: 'warning',
                title: 'Warning',
                text: "Addresses are not available!."
            })
        }
        return false;
    });


    $(document).on('change', '#lead_address', function () {
        var row_value = $(this).val();
        $('.row-data').css("display", "none");
        $("#" + row_value).css("display", "block");
    })




    $(document).on('click', '.view_notes', function (e) {
        var lead_id = $(this).attr('rel');
        $('#view_notes').empty();
        if (lead_id != '') {
            $url = base_url + "leads/view_notes";
            $.ajax({
                url: $url,
                type: "POST",
                dataType: 'json',
                data: { 'lead_id': lead_id },
                success: function (data) {
                    if (data.response) {
                        $('#view_notes').append(data.view_notes);
                        $('#view_notes_modal').modal('show');
                    } else {
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

    $(document).on('click', '.remove_note', function (e) {
        var note_id = $(this).attr('rel');
        if (note_id != '') {
            $url = base_url + "leads/delete_note";
            $.ajax({
                url: $url,
                type: "POST",
                dataType: 'json',
                data: { 'note_id': note_id },
                success: function (data) {
                    if (data.response) {
                        $('#note' + note_id).empty();
                        swal({
                            type: 'success',
                            title: 'Success',
                            text: "Note deleted successfully."
                        })
                    } else {
                        swal({
                            type: 'error',
                            title: 'Error',
                            text: "Invalid request to delete note."
                        })
                    }
                }
            });
        }
        return false;
    });

    $(document).on('click', '.send_sms', function (e) {
        $('#lead_id').val('');
        var lead_id = $(this).attr('rel');
        if (lead_id != '') {
            $('#lead_id').val(lead_id);
            $('#sms_modal').modal('show');
        }
    });

    $(document).on('click', '.add_note', function (e) {
        $('.lead_id').val('');
        var lead_id = $(this).attr('rel');
        if (lead_id != '') {
            $('.lead_id').val(lead_id);
            $('#note_modal').modal('show');
        }
    });

    $(document).on('click', '#note_button', function (e) {
        $url = base_url + "leads/process_add_note";
        var formData = $('#note_form').serializeArray();
        $('#note_button').hide();
        $('.plz_wait').fadeIn();
        $('.all_errors').empty();
        $.ajax({
            url: $url,
            type: "POST",
            dataType: 'json',
            data: formData,
            success: function (data) {
                $('.csrf_token').val(data.regenerate_token);
                if (data.response) {
                    $('#note_form').trigger("reset");
                    $('#note_modal').modal('hide');
                    swal({
                        type: 'success',
                        title: 'Success',
                        text: "Note saved successfully."
                    })
                } else {
                    $('#description_error').html(data.description_error);
                }
                $('.plz_wait').hide();
                $('#note_button').fadeIn();
            }
        });
    });

    $(document).on('click', '#reply_button', function (e) {
        $url = base_url + "chat/process_send_sms";
        var formData = $('#message_form').serializeArray();
        $('#reply_button').hide();
        $('.plz_wait').fadeIn();
        $.ajax({
            url: $url,
            type: "POST",
            dataType: 'json',
            data: formData,
            success: function (data) {
                $('.csrf_token').val(data.regenerate_token);
                $('#message_form').trigger("reset");
                if (data.response) {
                    $('#sms_modal').modal('hide');
                    swal({
                        type: 'success',
                        title: 'Success',
                        text: "Message sent successfully."
                    })
                } else {
                    if (data.stop) {
                        swal({
                            type: 'error',
                            title: 'Warning!',
                            text: "This user has stopped message service."
                        })
                    } else if (data.sms_unavailable) {
                        swal({
                            type: 'error',
                            title: 'Warning!',
                            text: "You don't have sms.Please purchase sms for this service."
                        })
                    } else {
                        swal({
                            type: 'error',
                            title: 'Warning!',
                            text: "Invalid request to send message."
                        })
                    }
                }
                $('.plz_wait').hide();
                $('#reply_button').fadeIn();
            }
        });
    });

    $(document).on('click', '.view_conversation', function (e) {
        var lead_id = $(this).attr('rel');
        if (lead_id != '') {
            $url = base_url + "chat/index/" + lead_id;
            window.location.replace($url);
        }
        return false;
    });

    $('#sample_leads').DataTable({
        "ordering": true,
        "lengthChange": true,
        "searching": true,
        "processing": true,
        "serverSide": true,
        "ajax": {
            url: base_url + 'leads/get_leads',
            type: 'POST',
            "data": function (d) {
                return $.extend({}, d, {
                    "status_filter": $('#status_filter').val(),
                    "from_date": $('#from_date').val(),
                    "action_filter": $('#action_filter').val(),
                    "to_date": $('#to_date').val()
                });
            },
            "dataSrc": function (json) {
                //Make your callback here.
                $('#n_leads').empty();
                $('#n_leads').append(json.data.length);
                return json.data;
            }
        },
        "order": [
            [0, 'asc']
        ],
        columnDefs: [
            { 'targets': 7, 'orderable': false },
            { 'targets': 8, 'orderable': false },
            { 'targets': 9, 'orderable': false }
        ],
        "columns": [
            { "data": "sr" },
            { "data": "first_name" },
            { "data": "last_name" },
            { "data": "email" },
            { "data": "contact_mobile" },
            { "data": "house_no" },
            { "data": "household_benefits" },
            { "data": "status" },
            { "data": "action" },
            //{ "data": "conversation" },
            { "data": "notes" },
            { "data": "addresses" },
            { "data": "lead_info" },
            { "data": "lead_appeal" },
            { "data": "created_at" }
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
    $(document).on('change', '#action_filter', function (e) {
        $('#sample_leads').DataTable().ajax.reload();
    });

});
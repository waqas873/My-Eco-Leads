$(document).ready(function () {
    bind_check_uncheck();

    $('#upload_event_banner').on('click', function (evt) {
        evt.preventDefault();
        $('#upload_event_banner_file').trigger('click');
    });

    $("#upload_event_banner_file").on('change', function () {
        bulk_action('upload');
    });

    $('#apply_poll').on('change', function () {
        if ($(this).val() !== '')
            bulk_action('attach_poll');
        else {
            bootbox.alert('Select poll to attach on eventszzzzz.');
        }
    });

    $('#apply_poll_pages').on('change', function () {
        if ($(this).val() !== '')
            bulk_action('attach_poll_pages');
        else
            bootbox.alert('Select poll to attach on events.');
    });

    $('.submit_poll').on('click', function () {
        var form_id = $(this).parents("form").attr('id');
        submit_poll(form_id);
    });

    $('#news_headline').on('keyup blur', function () {
        if ($('#news_id').length > 0)
            var news_id = $('#news_id').val();
        else
            var news_id = 0;
        $.ajax({
            type: 'POST',
            url: base_url_admin + '/news/make_url',
            data: {'news_heading': $('#news_headline').val(), 'news_id': news_id},
            cache: false,
            dataType: 'json',
            success: function (data) {
                $('#news_url').val(data.news_url);
            }
        });
    });
});


function bind_check_uncheck() {
    $('.group-checkable').change(function () {
        var set = jQuery(this).attr("data-set");
        var checked = jQuery(this).is(":checked");
        jQuery(set).each(function () {
            if (checked) {
                $(this).attr("checked", true);
                //$(this).parents('tr').addClass("active");
            } else {
                $(this).attr("checked", false);
                //$(this).parents('tr').removeClass("active");
            }
        });
        jQuery.uniform.update(set);
    });
}

function bulk_action(action) {
    var check_array = [];
    $('.sub_checks').each(function (index) {
        if ($(this).is(":checked")) {
            check_array[index] = $(this).val();
        }
    });
    if (check_array.length < 1) {
        if (action == 'upload')
            bootbox.alert('Select events to upload banner for.');
        else if (action == 'attach_poll') {
            bootbox.alert('Select events to attach poll.');
            $("#apply_poll option:selected").prop("selected", false);
            $("#apply_poll option:first").prop("selected", "selected");
        }
        else if (action == 'attach_poll_pages')
            bootbox.alert('Select pages to attach poll.');
        else
            bootbox.alert('Select events to mark ' + action + '.');
    }
    else {
        bootbox.confirm("Are you sure?", function (result) {
            if (result) {
                if (action == 'upload') {
                    $('.sub_checks').parents('form:first').attr('action', base_url_admin + '/events/bulk_upload').submit();
                }
                else if (action == 'attach_poll') {
                    apply_poll_form();
                    bind_check_uncheck();
                }
                else if (action == 'attach_poll_pages') {
                    poll_ajax_call(true)
                }
                else {
                    $('.sub_checks').parents('form:first').attr('action', base_url_admin + '/events/bulk_status/' + action).submit();
                }
            }
        });
    }
}

function bulk_delete(controller) {
    var check_array = [];
    $('.sub_checks').each(function (index) {
        if ($(this).is(":checked")) {
            check_array[index] = $(this).val();
        }
    });
    if (check_array.length < 1) {
        bootbox.alert('Select ' + controller.replace('_', ' ') + ' to delete.');
    }
    else {
        bootbox.confirm("Are you sure?", function (result) {
            if (result) {
                $('.sub_checks').parents('form:first').submit();
            }
        });
    }
}

function apply_poll_form() {
    var htmls = '<div id="poll_pages_html">' +
        '<div class="row">' +
        '<div class="col-md-12">' +
        '<form role="form" class="form-horizontal" id="poll_pages_form">' +
        '<div class="form-body">' +
        '<div class="form-group">' +
        '<label class="col-md-5 control-label">All:</label>' +
        '<div class="col-md-7">' +
        '<input type="checkbox" name="all_pages" class="group-checkable form-control" data-set="#poll_pages_form .checkboxes" value="all_pages" id="poll_pages"/>' +
        '</div>' +
        '</div>';
    $.each(menu_items, function (key, value) {
        htmls += '<div class="form-group">' +
            '<label class="col-md-5 control-label">' + ucwords(value.replace(new RegExp("-", "g"), ' ')) + ':</label>' +
            '<div class="col-md-7">' +
            '<input type="checkbox" name="poll_pages[]" class="checkboxes poll_pages form-control" value="' + value + '" id="poll_pages_' + key + '"/>' +
            '</div>' +
            '</div>';
    });
    htmls += '</div>' +
        '</form>' +
        '</div>' +
        '</div>' +
        '</div>';
    //var $form = $('#poll_pages_html').html();
    bootbox.dialog({
            title: "Select Event Pages To Attach Poll On.",
            message: htmls,
            onEscape: function () {
                bootbox.hideAll();
            },
            buttons: {
                cancel: {
                    label: "Cancel",
                    className: "btn default",
                    callback: function () {
                        bootbox.hideAll();
                    }
                },
                success: {
                    label: "Attach Poll",
                    className: "btn btn-primary",
                    callback: function () {
                        poll_ajax_call(false);
                    }
                }
            }
        }
    );
}
function bootboxcall(title, message, succ_lable) {
    bootbox.dialog({
            title: title,
            message: message,
            onEscape: function () {
                bootbox.hideAll();
            },
            buttons: {
                cancel: {
                    label: "Cancel",
                    className: "btn default",
                    callback: function () {
                        bootbox.hideAll();
                    }
                },
                success: {
                    label: succ_lable,
                    className: "btn btn-primary",
                    callback: function () {
                        poll_ajax_call(false);
                    }
                }
            }
        }
    );
}
function poll_ajax_call(only_pages) {
    if (only_pages == true) {
        var ajax_data = $('#pages_form').serialize();
        var ajax_url = base_url_admin + '/events/integrate_polls_pages';
    }
    else {
        var ajax_data = $('#events_form, #poll_pages_form').serialize();
        var ajax_url = base_url_admin + '/events/integrate_polls';
    }
    $.ajax({
        type: 'POST',
        url: ajax_url,
        data: ajax_data, //{'pages': data, 'events' : events},
        cache: false,
        dataType: 'json',
        success: function (response) {
            if (response.status == 'success') {
                location.reload();
            } else {
                console.log(response.message);
            }
        }
    });
}


function delete_record(obj, controller) {
    var url = $(obj).attr('href');
    bootbox.confirm("Are you sure?", function (result) {
        if (result) {
            console.log(url);
            window.location.replace(url);
        }
    });
}

function delete_record_dt(obj) {
    var url = $(obj).attr('href');
    bootbox.confirm("Are you sure?", function (result) {
        if (result) {
            console.log(url);
            window.location.replace(url);
        }
    });
}

function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    var expires = "expires=" + d.toUTCString();
    document.cookie = cname + "=" + cvalue + "; " + expires;
}

function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') c = c.substring(1);
        if (c.indexOf(name) == 0) return c.substring(name.length, c.length);
    }
    return "";
}

function ucwords(str) {
    return (str + '')
        .replace(/^([a-z\u00E0-\u00FC])|\s+([a-z\u00E0-\u00FC])/g, function ($1) {
            return $1.toUpperCase();
        });
}

function polls_list(page_id) {
    var htmls = '';
    $.ajax({
        type: 'POST',
        url: base_url_admin + '/polls/page_polls/' + page_id,
        data: {}, //{'pages': data, 'events' : events},
        cache: false,
        dataType: 'html',
        success: function (data) {
            bootbox.dialog({
                    title: "Polls List.",
                    message: data,
                    onEscape: function () {
                        bootbox.hideAll();
                    },
                    buttons: {
                        cancel: {
                            label: "Close",
                            className: "btn default",
                            callback: function () {
                                bootbox.hideAll();
                                location.reload();
                            }
                        }
                    }
                }
            );
        }
    });
}

function remove_spaces_from_html(html_str) {
    return html_str.replace(/\s+/g, " ");
}

function submit_poll(form_id) {
    if ($('.' + form_id).find('input[name=poll_option]:checked').length < 1) {
        bootbox.alert('Select an option to vote.');
    }
    else {
        $.ajax({
            type: 'POST',
            url: base_url_admin + '/polls/submit_poll',
            data: $('#' + form_id).serialize(),
            cache: false,
            dataType: 'json',
            success: function (data) {
                $('#' + form_id).remove();

                $('.' + form_id).html(data.html);
                setTimeout(function () {
                    $('.' + form_id).append(data.message);
                }, 500);
            }
        });
    }

}


$(function() {

    $(document).ready(function() {

        var Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });


        $("#permission_data").on("changed.jstree", function(e, data) {

            var permissions = new Array();
            if (data.selected.length) {

                $(data.selected).each(function(idx) {
                    var node = data.instance.get_node(data.selected[idx]);
                    permissions.push(node.a_attr.name);
                    // console.log(node.a_attr.name);
                    //console.log(node.text);
                    // permissions.push(node.text.replace(/ /g, "_").toLowerCase());
                });
            }
            $('#permissions').val($.unique(permissions).join(',')); // CSV Permissions

        });

        permission_data = JSON.parse(permission_data);
        $('#permission_data').jstree({
            'plugins': ["checkbox", "types"],
            'core': {
                "themes": {
                    "responsive": false
                },
                'data': permission_data
            },
            "types": {
                "default": {
                    "icon": "fa fa-folder icon-state-warning icon-lg"
                },
                "file": {
                    "icon": "fa fa-file icon-state-warning icon-lg"
                }
            }
        });


        // Set permissions
        var form = '#set_permissions';

        $(form).on('submit', function(event) {

            event.preventDefault();
            var url = $(this).attr('data-action');

            $.ajax({
                url: url,
                method: 'POST',
                data: new FormData(this),
                dataType: 'JSON',
                contentType: false,
                cache: false,
                processData: false,
                success: function(response) {
                    $(form).trigger("reset");
                    Toast.fire({
                        icon: 'success',
                        title: response.success
                    })
                },
                error: function(response) {}
            });
        });

    });

});

/**
 *  Manager Permissions
 */
function manage_permissions(url, header) {

    console.log(ajax_url);
    // START of AJAX
    var data = $('#permissions').text();
    $.ajax({
        type: "POST",
        data: data,
        url: url,
        headers: {
            'X-CSRF-TOKEN': header
        },
        async: true,
        cache: false,
        beforeSend: function() {

        },
        complete: function() {

        },
        success: function(response) {
            //console.log(response);
            //var json = $.parseJSON(response);
            // if (json.success == true) {
            //     var message = "<div class=\"alert alert-success\" style=\"margin: 0px;\">";
            //     message += "<button class=\"close\" data-close=\"alert\" onclick=\"hideDiv('permissions_alert')\"></button>";
            //     message += "<span id=\"permissions_record_messag\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i>&nbsp;<b>Permissions assigned to 012</b></span>";
            //     message += "</div><br>";

            // } else {
            //     var message = "<div class=\"alert alert-danger\" style=\"margin: 0px;\">";
            //     message += "<button class=\"close\" data-close=\"alert\" onclick=\"hideDiv('permissions_alert')\"></button>";
            //     message += "<span id=\"permissions_record_messag\"><i class=\"fa fa-exclamation-triangle\" aria-hidden=\"true\"></i>&nbsp;<b>Permissions not assigned to 012</b></span>";
            //     message += "</div><br>";
            // }

            // $("#permissions_alert").html(message);
            // $("#permissions_alert").show();
            // $('html, body').animate({ scrollTop: 0 }, 'slow');
        },
        error: function(data) {
            console.log(data);
        }
    });

    // End of AJAX
}
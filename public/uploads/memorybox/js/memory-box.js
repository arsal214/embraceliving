$(function() {

    $(document).ready(function() {

        $('#memorybox_table').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "columnDefs": [
                { "width": "5%", "targets": 1 },
            ],
        });

        // Sortable
        $('.memory-box-sortable').sortable({
            stop: function(e, ui) {
                $.map($(this).find('li'), function(el) {
                    //return $(el).data("row-id") + ' = ' + $(el).data("column-id");

                    $(el).index();
                    $(el).find("#column").val(parseInt($(el).index()) + 1)
                });
            }
        });

        // Color picker 
        $('.box-color-picker').colorpicker({
            format: 'rgba'
        });

        // Example using an event, to change the color of the #demo div background:
        $('.box-color-picker').on('colorpickerChange', function(event) {
            $(this).closest('div').find('.box-color-picker').css('background-color', event.color.toString());
        });

    });

});

/**
 *  Activate Theme
 */
function activate_theme(element, route, id) {

    var status = 0;
    if (element.checked) {
        status = 1;
    }

    // prepare routes
    var url = route + "/update/" + id + "/" + status;

    // activate theme
    $.ajax({
        type: 'GET',
        url: url,
        success: function(data) {
            location.reload();
        },
        error: function(data) {
            console.log(data);
        }
    });
}

/**
 *  Display Image 
 */
function display_image(input) {

    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function(e) {
            $(input).closest('div').find('.box-image-preview')
                .attr('src', e.target.result)
                .width(150)
                .height(150);
        };

        reader.readAsDataURL(input.files[0]);
    }
}
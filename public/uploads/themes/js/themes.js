$(function() {

    $(document).ready(function() {

        $('#themes_table').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "columnDefs": [
                { "width": "15%", "targets": [2, 3, 4] },
            ],
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
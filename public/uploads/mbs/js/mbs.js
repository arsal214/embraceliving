$(function() {

    $(document).ready(function() {

        $('#mbs_table').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "columnDefs": [
                { "width": "5%", "targets": [0, 1, 3] },
                { "width": "55%", "targets": 4 },
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
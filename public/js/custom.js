jQuery(function() {

    // Tooltip
    enable_tooltip();

    //Initialize Select2 Elements
    $('.select2').select2();

    // Bootstrap switch
    $("input[data-bootstrap-switch]").each(function() {
        $(this).bootstrapSwitch('state', $(this).prop('checked'));
    })

    // Data tables 
    $('.mmt-data-table').DataTable({
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
        "columnDefs": [
            { "width": "15%", "targets": -1 },
        ],
        "pageLength" : 10,
        "lengthMenu": [
            [10, 20, 50, -1],
            [10, 20, 50, 'All'],
        ],
    });

    $('input[type="file"]').change(function(e) {
        var fileName = e.target.files[0].name;
        $(this).siblings('label').text(fileName);
    });

    $('#sidebar_collapse').on('click', function(e) {
        $(this).find('i').toggleClass("sidebar-collapse-icon"); //you can list several class names 
        e.preventDefault();
    });

});

/**
 *  Delete confirmation
 */
function confirm_deletion(e, url) {

    var form = $(e).closest("form");

    Swal.fire({
        title: `Are you sure you want to delete this record?`,
        text: "If you delete this, it will be gone forever.",
        customClass: {
            cancelButton: 'btn btn-danger'
        },
        showCancelButton: true,
        cancelButtonText: 'No',
        cancelButtonColor: '#ce4242',
        confirmButtonColor: '#004A99',
        confirmButtonText: `Yes`,
        closeOnConfirm: false
    }).then((result) => {

        if (!result.isConfirmed) return

        window.location.replace(url);

    });

}

/**
 * 
 * @param {e, url} input 
 */
function confirm_form_delete(element) {
    var form = $(element).closest("form");

    Swal.fire({
        title: `Are you sure you want to delete this record?`,
        text: "If you delete this, it will be gone forever.",
        customClass: {
            cancelButton: 'btn btn-danger'
        },
        showCancelButton: true,
        cancelButtonText: 'No',
        cancelButtonColor: '#ce4242',
        confirmButtonColor: '#004A99',
        confirmButtonText: `Yes`,
        closeOnConfirm: false
    }).then((result) => {

        if (!result.isConfirmed) return;

        jQuery(form).submit();

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
        };

        reader.readAsDataURL(input.files[0]);
    }
}

/**
 *  Enable Tooltip
 */
function enable_tooltip() {
    $('[data-toggle="tooltip"]').tooltip()
}
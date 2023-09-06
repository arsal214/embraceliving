$(function() {

    $(document).ready(function() {

        // trigger switch states
        $('#pdf_status').trigger('onload');
        $('#bingo_status').trigger('onload');

    });

});

/**
 *  Change State of Switch
 */
function change_status(status, id) {

    console.log(status);
    // toggle - yes
    if (status == 'yes') {
        $('#' + id).bootstrapSwitch('state', true);
    }

    // toggle -no
    if (status == 'no') {
        $('#' + id).bootstrapSwitch('state', false);
    }

}
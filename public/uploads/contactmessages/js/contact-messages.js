$(function() {
    'use strict';

    $(document).ready(function() {

    });

});

/**
 *  Display image
 */

function view_contact_message(message, url) {

    Swal.fire({
        title: 'Message Detail',
        width: '50%',
        html: `
        <table class="table table-bordered text-left">
            <tbody>
                <tr>
                    <th class="w-25">Sender Name</th>
                    <td>${message.sender_name}</td>
                </tr>
                <tr>
                    <th class="w-25">Sender Email</th>
                    <td>${message.sender_email}</td>
                </tr>
                <tr>
                    <th class="w-25">Phone No</th>
                    <td>${message.phone_number}</td>
                </tr>
                <tr>
                    <th class="w-25">Query Type</th>
                    <td>${message.query}</td>
                </tr>
                <tr>
                    <th class="w-25">Care Home</th>
                    <td>${message.care_home}</td>
                </tr>
                <tr>
                    <th class="w-25">Message</th>
                    <td>${message.message}</td>
                </tr>
            </tbody>
        </table>
        `,
        customClass: {
            cancelButton: 'btn btn-danger'
        },
        showCancelButton: true,
        cancelButtonText: 'Close',
        cancelButtonColor: '#ce4242',
        showCancelButton: false,
        closeOnConfirm: false
    }).then((result) => {

        // update message read status
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

    });

}


function open_reply_box(message, url, id) {

    var token = $('meta[name="csrf-token"]').attr('content');
    Swal.fire({
        title: 'Message Reply',
        width: '70%',
        html: `
        <form action="${url}" method="POST" id="reply_form_${id}" >
        <table class="table table-bordered text-left">
            <tbody>
                <tr>
                    <td>
                        <div class="form-group">
                            <label for="sender_name">Sender Name</label>
                            <input type="text" name="sender_name" class="form-control" readonly value="${message.sender_name}" />
                        </div>
                    </td>
                    <td>
                        <div class="form-group">
                            <label for="sender_email">Sender Email</label>
                            <input type="text" name="sender_email" class="form-control" readonly value="${message.sender_email}" />
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-group">
                            <label for="phone_number">Phone Number</label>
                            <input type="text" class="form-control" readonly value="${message.phone_number}" />
                        </div>
                    </td>
                    <td>
                        <div class="form-group">
                            <label for="query_type">Query Type</label>
                            <input type="text" name="sender_query" class="form-control" readonly value="${message.query}" />
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-group">
                            <label for="care_home">Care Home</label>
                            <input type="text" class="form-control" readonly value="${message.care_home}" />
                        </div>
                    </td>
                    <td>
                        <div class="form-group">
                            <label for="message">Message</label>
                            <input type="text" class="form-control" readonly value="${message.message}" />
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div class="form-group">
                            <label> Message Reply </label>
                            <textarea name="message_reply" rows="6" cols="30" class="form-control w-100 border-0" placeholder="Write reply message..." required></textarea>
                            <input type="hidden" name="_token" value="${token}" />
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        </form>
        `,
        customClass: {
            cancelButton: 'btn btn-danger'
        },
        showCancelButton: true,
        cancelButtonText: 'Close',
        cancelButtonColor: '#ce4242',
        showCancelButton: true,
        closeOnConfirm: false
    }).then((result) => {

        if (!result.isConfirmed) return

        $(`#reply_form_${id}`).submit();


    });

}
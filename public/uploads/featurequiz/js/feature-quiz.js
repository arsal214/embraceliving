$(function() {
    'use strict';

    $(document).ready(function() {

        /**
         *  Apply Data table
         */
        var weekly_quiz_table = $('#weekly_quiz_table').DataTable({
            "columnDefs": [
                { "width": "1%", "targets": 0 },
                { "width": "35%", "targets": 1 },
                { "width": "20%", "targets": 4 },
                { "width": "1%", "targets": 5 },
                {
                    className: 'align-middle text-center',
                    targets: [4, 5]
                }
            ],
            "paging": true,
            "lengthChange": false,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
        });

        // Sortable
        $('#weekly_quiz_table tbody').sortable({
            stop: function(e, ui) {
                $.map($(this).find('tr'), function(el) {
                    //return $(el).data("row-id") + ' = ' + $(el).data("column-id");

                    $(el).index();
                    $(el).find("#position").val(parseInt($(el).index()) + 1)
                });
            }
        });

        $('#weekly_quiz_table tbody').css('cursor', 'move');

        /**
         *  Add row in quiz table
         */
        $(document).on('click', '#add_quiz_question', function(e) {

            var tr_length = weekly_quiz_table.rows().count() + 1;
            e.preventDefault();
            weekly_quiz_table.row.add([
                `<td>${tr_length}</td>`,
                `<td><input type="text" name="questions[]" class="form-control" /></td>`,
                `<table class="w-100">
                    <tr>
                        <th>A</th>
                        <td><input type="text" name="option_a[]" class="form-control" required/></td>
                    </tr>
                    <tr>
                        <th>B</th>
                        <td><input type="text" name="option_b[]" class="form-control" required/></td>
                    </tr>
                    <tr>
                        <th>C</th>
                        <td><input type="text" name="option_c[]" class="form-control" required/></td>
                    </tr>
                </table>`,
                ` <td>
                    <select name="answers[]" id="" class="form-control" required>
                        <option value="A" selected>A</option>
                        <option value="B">B</option>
                        <option value="C">C</option>
                    </select>
                </td>`,
                `<td>
                    <div class="mmt-input-file">
                        <button class="info-btn"><i class="fa fa-upload"></i></button>
                        <input type="file" name="images[]" id="file" class="question-file" required onChange="displayImage(this, '.question-file')"/>
                        <label for="file" class="mb-0 font-weight-normal d-block">Select Image</label>
                        <img src="#" alt="your image" class="mw-100 w-25 d-none quiz-image-preview" />
                    </div>
                </td>`,
                `<td>
                    <button type="button" class="del_btn delete-quiz-question"><i class="fa fa-minus-circle"></i></button>
                    <input type="hidden" name="id[]" class="form-control" value=""/>
                    <input type="hidden" name="position[]" id="position" class="position" value="${tr_length}" />
                </td>`
            ]).draw(true);
        });

        /**
         *  Remove Row from quiz table
         */
        $('#weekly_quiz_table tbody').on('click', '.delete-quiz-question', function(e) {
            weekly_quiz_table
                .row($(this).parents('tr'))
                .remove()
                .draw();
        });

        /**
         *  Submit Quiz form
         */
        $('#save_quiz').on('click', function() {
            $('#weekly_quiz_form').submit();
        })

    });

});

/**
 *  Display image
 */

function displayImage(element, element_class) {
    const file = element.files[0];
    if (file) {
        let reader = new FileReader();
        reader.onload = function(event) {
            $(element).closest('.mmt-input-file').find('img').attr('src', event.target.result);
            $(element).closest('.mmt-input-file').find('img').removeClass('d-none');
        }
        reader.readAsDataURL(file);
    }

}

/**
 *  Clear Quiz Data
 */
function clear_quiz_data() {

    var week = $("#week_no").val();

    Swal.fire({
        title: `Are you sure you want to clear this quiz.?`,
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

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: 'POST',
            url: clear_all_url,
            data: {
                week_no: week,
            },
            dataType: 'JSON',
            success: function(data) {
                if (data.success == true) {

                    console.log(data.request);
                    var message = 'All questions cleared successfully';
                    var icon = 'success';
                    if (message.length > 0) {
                        Toast.fire({
                            icon: icon,
                            title: message
                        });
                    }

                    location.reload();
                }
            },
            error: function(data) {
                var message = 'Error!.';
                var icon = 'error';
                if (message.length > 0) {
                    Toast.fire({
                        icon: icon,
                        title: message
                    });
                }

            }
        });

    });

}

/**
 *  Import CSV Data
 */
function import_quiz_data(element) {

    var button = $(element).html();
    var spinner = `<span class="spinner-border spinner-border-lg" role="status" aria-hidden="true"></span> Importing..`;

    var weekly_quiz_table = $('#weekly_quiz_table').DataTable();
    var length = weekly_quiz_table.rows().count();
    var week = $("#week_no").val();
    var status = $('#status').val();
    var position = $('.position').last().val();
    position = (position) ? position : 0;

    if (length < 1) {
        var message = 'Please add rows before import.';
        var icon = 'error';
        Toast.fire({
            icon: icon,
            title: message
        });

        return;
    }

    Swal.fire({
        title: 'Select CSV file',
        showCancelButton: true,
        confirmButtonText: 'Upload',
        input: 'file',
        onBeforeOpen: () => {
            $(".swal2-file").change(function() {
                var reader = new FileReader();
                reader.readAsDataURL(this.files[0]);
            });
        }
    }).then((file) => {
        if (file.value) {
            var formData = new FormData();
            var file = $('.swal2-file')[0].files[0];
            formData.append("file_name", file);
            formData.append("week_no", week);
            formData.append("length", length);
            formData.append("status", status);
            formData.append("position", position);
            $(element).html(spinner);
            $.ajax({
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                method: 'post',
                url: import_quiz_url,
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'JSON',
                success: function(data) {
                    if (data.success == true) {

                        console.log(data.request);
                        var message = 'All questions imported successfully';
                        var icon = 'success';
                        if (message.length > 0) {
                            Toast.fire({
                                icon: icon,
                                title: message
                            });
                        }

                        location.reload();

                    }

                    $(element).html(button);
                },
                error: function(error) {
                    if (error.status === 422) {
                        console.log(error.responseText);
                        var errors = $.parseJSON(error.responseText);
                        var message = errors['errors']['file_name'];
                        var icon = 'error';
                        Toast.fire({
                            icon: icon,
                            title: message,
                        });
                    }

                    $(element).html(button);
                }
            })
        }
    })
}

/**
 * Activate Quiz
 * @param {} element 
 */
function activate_quiz(element) {

    var status = 0;
    if (element.checked) {
        status = 1;
    }
    $(element).val(status);
}
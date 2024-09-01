(function ($) {
    'use strict';

    $(document).ready(function () {

        // Check/Uncheck all checkboxes
        $('#select-courses').click(function (e) {
            if (e.target.type === 'checkbox') {
                if (e.target.id === 'id_0') {
                    const isChecked = $('#' + e.target.id).prop('checked');
                    $('#select-courses input[type="checkbox"]').prop('checked', isChecked);
                }
            }
        });

        // Reset all fields
        $('#btn-reset-students').click(function (e) {
            e.preventDefault();
            $('#select-courses').empty();
            $('#start-date').val('');
            $('#end-date').val('');
            $('#course-search-hint').val('');
        });

        $('#btn-search-courses').click(function (e) {
            e.preventDefault();

            const $startDate = $('#start-date').val();
            const $endDate = $('#end-date').val();
            const $courseSearchHint = $('#course-search-hint').val();
            const $courses = $('#select-courses');

            $.ajax({
                url: lms_report.ajaxurl,
                type: 'post',
                data: {
                    action: 'dcms_get_courses_by_hint',
                    nonce: lms_report.nonce_lms_report,
                    start_date: $startDate,
                    end_date: $endDate,
                    course_search_hint: $courseSearchHint
                }, beforeSend: function () {
                    block_ui(true);
                    $('.courses-search .loading').show();
                }
            })
                .done(function (res) {
                    $courses.empty();
                    if (res.courses.length > 0) {
                        $courses.append('<label><input type="checkbox" value="id_0" checked> Todos </label>');
                        $.each(res.courses, function (index, value) {
                            $courses.append('<label><input type="checkbox" name="courses[]" value="id_' + value.ID + '" checked> ' + value.name + '</label>');
                        });
                    }
                    else{
                        $courses.append('<li>➜ No se encontraron cursos</li>');
                    }
                })
                .always(function () {
                    block_ui(false);
                    $('.courses-search .loading').hide();
                });

        });


        $('#btn-search-students').click(function (e) {
            e.preventDefault();

            // Get all selected courses
            const $courses = $('#select-courses input[type="checkbox"]:checked');

            // Get only IDs from $courses object
            let $courses_ids = [];
            $courses.each(function (index, course) {
                const id = parseInt(course.value.substring(3));
                if (id > 0) {
                    $courses_ids.push(id);
                }
            });

            $.ajax({
                url: lms_report.ajaxurl,
                type: 'post',
                data: {
                    action: 'dcms_get_students_by_courses_id',
                    nonce: lms_report.nonce_lms_report,
                    courses: $courses_ids
                }, beforeSend: function () {
                    block_ui(true);
                    $('.students-search .loading').show();
                }
            })
                .done(function (res) {
                    console.log(res);
                })
                .always(function () {
                    block_ui(false);
                    $('.students-search .loading').hide();
                });

        });

    });

    function block_ui($isDisabled) {
        $('.date-range input').prop('disabled', $isDisabled);
        $('#course-search-hint').prop('disabled', $isDisabled);
        $('#btn-search-courses').prop('disabled', $isDisabled);
        $('#select-courses input').prop('disabled', $isDisabled);
        $('.students-search button').prop('disabled', $isDisabled);
        $('.buttons-export .btn-export').prop('disabled', $isDisabled);
    }


})(jQuery);


// Save configuration fields
// $('#btn-search-students').click(function (e) {
//     e.preventDefault();
//
//     $.ajax({
//         url: lms_forms.ajaxurl,
//         type: 'post',
//         dataType: 'json',
//         data: {
//             action: 'dcms_lms_forms_save_fields',
//             nonce: lms_forms.nonce_lms_forms,
//             fields: fields
//         }, beforeSend: function () {
//             $('#container-fields .msg-btn').text(lms_forms.sending);
//             $('#container-fields .button').prop('disabled', true);
//             $('#container-fields .loading').removeClass('hide');
//         }
//     })
//         .done(function (res) {
//             $('#container-fields .msg-btn').text(res.message);
//         })
//         .always(function () {
//             $('#container-fields .button').prop('disabled', false);
//             $('#container-fields .loading').addClass('hide');
//         });
// });
//

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

        $('#btn-search-courses').click(function (e) {
            e.preventDefault();

            const $startDate = $('#start-date').val();
            const $endDate = $('#end-date').val();
            const $courseSearchHint = $('#course-search-hint').val();
            const $courses = $('#select-courses');

            // validate fill dates fields
            if ($startDate === '' || $endDate === '') {
                alert('Debe seleccionar un rango de fechas');
                return;
            }


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
                        $courses.append('<label><input type="checkbox" id="id_0" value="id_0" checked> Todos </label>');
                        $.each(res.courses, function (index, value) {
                            $courses.append('<label><input type="checkbox" name="courses[]" value="id_' + value.ID + '" checked> ' + value.name + '</label>');
                        });
                    } else {
                        $courses.append('<li>➜ No se encontraron cursos</li>');
                    }
                })
                .always(function () {
                    block_ui(false);
                    $('.courses-search .loading').hide();
                    $('#students-courses-table tbody').empty();
                });

        });


        $('#btn-search-students').click(function (e) {
            e.preventDefault();

            // Selected courses ids
            const $courses_ids = get_selected_courses();

            $('#courses_ids').val($courses_ids.join(',')); // For export

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
                    const bodyTable = $('#students-courses-table tbody');
                    $(bodyTable).empty();
                    if (res.students.length > 0) {
                        $.each(res.students, function (index, student) {
                            const phone = student.user_phone ?? '';
                            $(bodyTable).append('<tr>' +
                                '<td>' + student.ID + '</td>' +
                                '<td>' + student.user_name + '</td>' +
                                '<td>' + student.user_email + '</td>' +
                                '<td>' + phone + '</td>' +
                                '<td>' + student.courses + '</td>' +
                                '</tr>');
                        });
                    } else {
                        $(bodyTable).append('<tr><td colspan="4"><p>➜ No se encontraron estudiantes</p></td></tr>');
                    }

                })
                .always(function () {
                    block_ui(false);
                    $('.students-search .loading').hide();
                });

        });


        $('#frm-export').submit(function (e) {
            e.preventDefault();

            if ( $('#courses_ids').val() === '') {
                alert('No hay alumnos a exportar');
                return;
            }

            // Submit form
            $(this).unbind('submit').submit();
        });


        function get_selected_courses() {
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
            return $courses_ids;
        }
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

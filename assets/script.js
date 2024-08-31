(function ($) {
    'use strict';

    $(document).ready(function () {

        // Check/Uncheck all checkboxes
        $('#select-courses #id_0').change(function () {
            const isChecked = $(this).prop('checked');
            $('#select-courses input[type="checkbox"]').prop('checked', isChecked);
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
                dataType: 'json',
                data: {
                    action: 'dcms_lms_reports_get_courses',
                    nonce: lms_report.nonce_lms_report,
                    start_date: $startDate,
                    end_date: $endDate,
                    course_search_hint: $courseSearchHint
                }, beforeSend: function () {
                    block_ui(true);

                }
            })
                .done(function (res) {
                    $courses.empty();
                    $courses.append(res.courses);
                })
                .always(function () {
                    block_ui(false);
                });

        });
    });

    function block_ui($isDisabled) {
        $('.date-range input').prop('disabled', $isDisabled);
        $('#course-search-hint').prop('disabled', $isDisabled);
        $('#btn-search-courses').prop('disabled', $isDisabled);
        $('#select-courses input').prop('disabled', $isDisabled);
        $('.students-search button').prop('disabled', $isDisabled);
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

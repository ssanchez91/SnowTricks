/**
 * Created by sstee on 20/10/2020.
 */

$(document).ready(function()
{
    $('#staticBackdrop').on('shown.bs.modal', function() {
        $(this).find('.btn-ok').attr('href', $(".delete-link").data('href'));
        $(this).find('.btn-ok').attr('href');
    });

    $('.delete-link').on('click', function (e) {
        e.preventDefault();
        $('#staticBackdrop').modal('show');
        $('[data-toggle=tooltip]').tooltip('hide');
    });

    $("[data-toggle=tooltip]").tooltip();
});



/**
 * Created by sstee on 29/10/2020.
 */
var link = null;
var typeMedia = null;
$(document).ready(function(){
    $("body").css("background-image","url('../assets/img/bg-masthead-1.jpg')").css("background-size","auto").css("background-repeat","no-repeat"); //edit, body must be in quotes!
    initLinks();
});

$(".btn.btn-primary.btn-sm.btn-ok").click(function(e) {
    e.preventDefault();
    deleteMedia(link, typeMedia);
});

function initLinks(){
    $("a[data-delete]").click(function(e){
        e.preventDefault();
        typeMedia = "picture";
        link = $(this);
        $('#staticBackdrop').modal('show');
    });

    $("a[data-delete-movie]").click(function(e){
        e.preventDefault();
        typeMedia = "movie";
        link = $(this);
        $('#staticBackdrop').modal('show');
    });
}

function deleteMedia(link, type){

    console.log(type);
    $.ajax({
        url: link.attr("href"),
        type: "DELETE",
        dataType: "JSON",
        data: {
            _token: link.data('token')
        },
        success: function(response) {
            $('#staticBackdrop').modal('hide');
            if(type == "picture")
            {
                if(response.default_picture == true)
                {
                    initAlert($("#alertEditImg"), "alert-warning", "You must select another default picture before can delete it. ");
                }
                else
                {
                    link.parent().parent().parent().remove();
                    initAlert($("#alertEditImg"), "alert-success", "Your picture has just been deleted.");
                    initCheckBox();
                }
            }
            else
            {
                if(response.delete_movie == true)
                {
                    link.parent().parent().remove();
                    initAlert($("#alertEditMovie"), "alert-success", "Your movie has just been deleted.");
                }
                else
                {
                    initAlert($("#alertEditMovie"), "alert-warning", "A problem occurred during the deletion !");
                }

            }
        },
        error: function(xhr) {
            if(type == "picture") {
                initAlert($("#alertEditImg"), "alert-warning", JSON.parse(xhr.responseText));
            }
            else
            {
                initAlert($("#alertEditMovie"), "alert-warning", JSON.parse(xhr.responseText));
            }
        }
    });
}

function initAlert(div_alert, type, msg){
    razAlert(div_alert);
    div_alert.addClass(type).append('<button type="button" class="close hideAlertEdit">'+
        '<span aria-hidden="true">&times;</span>'+
        '</button> '+msg ).removeClass('d-none');

    $(".hideAlertEdit").on('click', function(alert){
        razAlert(div_alert);
    });
}

function razAlert(div_alert){
    div_alert.removeClass('alert-success').removeClass('alert-warning').text('').addClass('d-none');
}
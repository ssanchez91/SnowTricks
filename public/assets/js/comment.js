let figure = null;
var username = null;
var pathLogo = null;

$(document).ready(function(){
    $("body").css("background-image","url('../assets/img/bg-masthead-1.jpg')").css("background-size","auto").css("background-repeat","no-repeat"); //edit, body must be in quotes!

    initComment();

    $("#btnAddComment").on("click", function() {

        let commentContent = $("textarea#comment_content").val();
        $.ajax({
            url: $("#com-form").attr("action"),
            type: "POST",
            dataType: "JSON",
            data: {
                figure: figure,
                comment: commentContent
            },
            beforeSend: function(xhr) {
                if(checkContent(commentContent) == false)
                {
                    xhr.abort();
                }
            },
            success: function(response) {
                runProcessSuccessResponse(JSON.parse(response));
            },
            error: function(xhr) {
                var errors = JSON.parse(xhr.responseText);
                console.log(errors);
            }
        });
    });

    $("#btnShowMedias").click(function(e)
    {
        if($(this).val() == "show medias")
        {
            $("#divShowMedias").css('display', 'block');
            $(this).val("hide medias")
        }
        else
        {
            $("#divShowMedias").css('display', 'none');
            $(this).val("show medias")
        }

    })
});

function runProcessSuccessResponse(comment)
{
    $("textarea#comment_content").val('');
    addNewComment(comment);
    initComment();
    initAlertComment();
}

function runProcessErrorResponse(error){
    console.log(error);
}

function addNewComment(comment){
    var date_commentaire = comment.createdAt.substring(8,10)+'/'+comment.createdAt.substring(5,7)+'/'+comment.createdAt.substring(0,4)+' '+comment.createdAt.substring(11,19);
    var new_comment = '<div class="row comment d-none">'+
        '<div class="col-lg-2 text-center m-auto">'+
        '<img style="width:75px; height:75px; border:2px solid #f0ad4e" class="rounded-circle" src="/assets/img/logo/'+ pathLogo +'" alt="user-logo"/>'+
        '</div>'+
        '<div class="col-lg-10">'+
        '<div class="bd-callout bd-callout-warning">'+
        '<p><i class="fas fa-comment"></i> '+ comment.content +'</p>'+
        '<p class="text-muted"><i class="fas fa-user"></i> Author : '+ username +' - <i class="fas fa-calendar"></i> Created date : '+ date_commentaire +'</p>'+
        '</div>'+
        '</div>'+
        '</div>';
    $("#listComment").prepend(new_comment);
}

function initComment(){
    $(".comment").addClass("d-none");
    let limitComment = 5;
    let listComment =  $(".comment");
    listComment.slice(0, limitComment).removeClass("d-none");
    $("#show-more-comment").on("click", function(e) {
        limitComment += 5;
        e.preventDefault();
        listComment.slice(0, limitComment).removeClass("d-none");
    });
    if(listComment.length > 5){
        $("#show-more-comment").removeClass('d-none');
    }
}

function initAlertComment(){

    razAlertComment();

    $("#alertComment").addClass('alert-success').append('<button type="button" class="close" id="hideAlertComment">'+
        '<span aria-hidden="true">&times;</span>'+
        '</button>Your comment has just been added !').removeClass('d-none');

    $("#hideAlertComment").on('click', function(){
        razAlertComment();
    });
}

function razAlertComment(){
    $("#alertComment").removeClass('alert-success').removeClass('alert-warning').text('').addClass('d-none');
}

function checkContent(commentContent){
    if(commentContent.length < 15) {

        razAlertComment();

        $("#alertComment").addClass('alert-warning').append('<button type="button" class="close" id="hideAlertComment">' +
            '<span aria-hidden="true">&times;</span>' +
            '</button>Your comment is too short ! (min : 15 caracters)').removeClass('d-none');

        return false;
    }

    return true;
}


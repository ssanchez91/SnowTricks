/**
 * Created by sstee on 29/10/2020.
 */
$(document).ready(function(){
    initCheckBox();
});

$("#addNewPictureButton").click(function(e){
    e.preventDefault();
    $("#addNewPictureDiv").removeClass('d-none');
});

$("#removeNewPictureLink").click(function(e){
    e.preventDefault();
    $("#addNewPictureDiv input").val('');
    $("#addNewPictureDiv").addClass('d-none');
});

$("#uploadNewPictureLink").click(function(e){
    e.preventDefault();
    addPicture($("#addNewPictureDiv input"));
});

function addPicture(picture) {
    let file_data = picture.prop('files')[0];
    let form_data = new FormData();
    form_data.append('file', file_data);
    //form_data.append('figureId', 1);
    $.ajax({
        url: picture.attr('data-href'),
        type: "POST",
        dataType: "text",
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        success: function (response) {
            let json_decode_response = JSON.parse(response);
            let picture = JSON.parse(json_decode_response.add_picture);

            if (picture == 'error') {
                initAlert($("#alertEditImg"), "alert-warning", "A problem occured during the import!");
            }
            else
            {
                insertNewPicture(picture);
                $("#removeNewPictureLink").click();
                initAlert($("#alertEditImg"), "alert-success", "You had added a new picture!");
                initLinks();
                initCheckBox();
            }
        },
        error: function (response) {
            initAlert($("#alertEditImg"), "alert-warning", JSON.parse(response.responseText).error);
        }
    });
}

function initCheckBox(){
    let listCheckBox = $("input[data-default]");
    listCheckBox.each(function(){
        $(this).click(function(e){
            if($(this).is(':checked'))
            {
                defaultPicture($(this));
                listCheckBox.each(function(){
                    $(this).prop('checked', false).prop('disabled', false);
                });
                $(this).prop('checked', true).attr('disabled', 'disabled');
            }
        })
    });
}

function defaultPicture(picture){
    $.ajax({
        url: picture.attr("data-href"),
        type: "POST",
        dataType: "JSON",
        data: {
            _token: picture.data('token')
        },
        success: function(response) {
            initAlert($("#alertEditImg"), "alert-success", "You had changed the default picture!");
        },
        error: function(xhr) {
            initAlert($("#alertEditImg"), "alert-warning", JSON.parse(xhr.responseText));
        }
    });
}

/**
 * Created by sstee on 29/10/2020.
 */

$("#addNewMovieButton").click(function(e){
    e.preventDefault();
    $("#addNewMovieDiv").removeClass('d-none');
});

$("#removeNewMovieLink").click(function(e){
    e.preventDefault();
    $("#addNewMovieDiv input").val('');
    $("#addNewMovieDiv").addClass('d-none');
});

$("#uploadNewMovieLink").click(function(e){
    e.preventDefault();
    addMovie($("#addNewMovieDiv input"));
});

function addMovie(movie) {
    let file_data = movie.val();
    let form_data = new FormData();
    form_data.append('movie', file_data);
    $.ajax({
        url: movie.attr('data-href'),
        type: "POST",
        dataType: "text",
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        success: function (response) {
            let json_decode_response = JSON.parse(response);
            let movie = JSON.parse(json_decode_response.add_movie);

            if (movie == 'error') {
                initAlert($("#alertEditMovie"), "alert-warning", "A problem occurred during the import!");
            }
            else
            {
                insertNewMovie(movie);
                $("#removeNewMovieLink").click();
                initAlert($("#alertEditMovie"), "alert-success", "You had added a new movie!");
                initLinks();
            }
        },
        error: function (response) {
            initAlert($("#alertEditMovie"), "alert-warning", JSON.parse(response.responseText).error);
        }
    });
}






/**
 * Created by sstee on 22/10/2020.
 */

// setup an "add a tag" link
var $addPictureButton = $('#addPictureButton');
var $addMovieButton = $('#addMovieButton');
var $newLinkSpanPicture = $('<span></span>');
var $newLinkSpanMovie = $('<span></span>');

jQuery(document).ready(function() {
   //Init display input file
    displayInputFile();

    // Get the ul that holds the collection of tags
    $collectionHolderPictures = $('div.pictures');
    $collectionHolderMovies = $('div.movies');

    $collectionHolderPictures.find(".img-div").each(function() {
        addDeleteLink($(this));
    });

    $collectionHolderMovies.find(".mov-div").each(function() {
        addDeleteLink($(this));
    });

    // add the "add a tag" anchor and li to the tags ul
    $collectionHolderPictures.append($newLinkSpanPicture);
    $collectionHolderMovies.append($newLinkSpanMovie);

    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    $collectionHolderPictures.data('index', $collectionHolderPictures.find('input').length);
    $collectionHolderMovies.data('index', $collectionHolderMovies.find('input').length);

    $addPictureButton.on('click', function(e) {
        e.preventDefault();
        // add a new picture form (see next code block)
        addMediaForm($collectionHolderPictures, $newLinkSpanPicture);
    });
    $addMovieButton.on('click', function(e) {
        e.preventDefault();
        // add a new picture form (see next code block)
        addMediaForm($collectionHolderMovies, $newLinkSpanMovie);
    });
});

function addDeleteLink($newFormSpan){
    var $removeFormButton = $("<div class='col-lg-1 m-auto'><a href=''><i class='fas fa-trash'></i></a></div>");
    $newFormSpan.append($removeFormButton);
    $removeFormButton.on("click", function(e) {
        e.preventDefault();
        $newFormSpan.remove();
    });
}

function addMediaForm($collectionHolder, $newLinkSpan){
    // Get the data-prototype explained earlier
    var prototype = $collectionHolder.data('prototype');

    // get the new index
    var index = $collectionHolder.data('index');

    var newForm = prototype;
    // You need this only if you didn't set 'label' => false in your tags field in TaskType
    // Replace '__name__label__' in the prototype's HTML to
    // instead be a number based on how many items we have
    // newForm = newForm.replace(/__name__label__/g, index);

    // Replace '__name__' in the prototype's HTML to
    // instead be a number based on how many items we have
    newForm = newForm.replace(/__name__/g, index);

    // increase the index with one for the next item
    $collectionHolder.data('index', index + 1);

    // Display the form in the page in an li, before the "Add a tag" link li
    var $newFormSpan = $('<div class="row">').append('<div class="col-lg-11">'+newForm+'</div>');
    $newLinkSpan.before($newFormSpan);

    addDeleteLink($newFormSpan);

    displayInputFile();
}

function displayInputFile(){
    $("#form-figure").find("input[type=file]").each(function(index, field){
        $(this).css('opacity','1');
    });
}
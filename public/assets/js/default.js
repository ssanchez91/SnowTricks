/**
 * Created by sstee on 19/10/2020.
 */
$(document).ready(function(){
    initShowTricks();
});

function initShowTricks(){

    var limtTricks = 10;

    $(".tricks").slice(0,limtTricks).removeClass('d-none');

    $("#show-more-tricks").on("click", function(e) {
        limtTricks += 5;
        e.preventDefault();
        $(".tricks").slice(0, limtTricks).removeClass("d-none");
    });
}
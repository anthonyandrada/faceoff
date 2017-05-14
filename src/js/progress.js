/*global $ */

$(document).ready(function () {
    "use strict";
    var progresspump = setInterval(function () {
        /* query the completion percentage from the server */
        $.get("/app/update-progress", function (data) {
            /* update the progress bar width */
            $("#progress").css('width', data + '%');
            /* and display the numeric value */
            $("#progress").html(data + '%');

            /* test to see if the job has completed */
            if (data > 99.999) {
                clearInterval(progresspump);
                $("#progressouter").removeClass("active");
                $("#progress").html("Done");
            }
        });
    }, 1000);
});

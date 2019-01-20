$(document).ready(function () {
    $('#search-button').click(function () {
        var searchString = $('#search-string').val();
        var param = encodeURIComponent(searchString).replace(/%20/g, '+');
        window.location.href = "/search/" + param;
    });

    $("#search-form").submit(function (event) {
        var searchString = $('#search-string').val();
        var param = encodeURIComponent(searchString).replace(/%20/g, '+');
        window.location.href = "/search/" + param;
        event.preventDefault();
    })
});


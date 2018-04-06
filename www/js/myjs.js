$(document).ready(function () {
    $('#sidebarCollapse').on('click', function () {
        $('#sidebar').toggleClass('active');
        $(this).toggleClass('active');
        setTimeout(function () {
            resize_map();
        }, 500);
    });
});
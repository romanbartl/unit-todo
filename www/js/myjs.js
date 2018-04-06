$(document).ready(function () {
    $('#sidebarCollapse').on('click', function () {
        $('#sidebar').toggleClass('active');
        $(this).toggleClass('active');
        setTimeout(function () {
            resize_map();
        }, 500);
    });
});

var count = 0;
function addAnotherBar() {
    if (count < 5){
        document.getElementById('dylytko').style.display = 'block';
        count = count + 1;
        $( ".ready-to-add" ).append( "<div id='"+count+"' class=\"another-bar\">\n" +
            "        <li>\n" +
            "            <p>ANOTHER BAR HERE</p>\n" +
            "        </li></div>" );
        if (count == 5){
            document.getElementById('additko').style.display = 'none';
        }
    } else {
        document.getElementById('additko').style.display = 'none';
    }
}

function delAnotherBar() {
    $("#"+count).remove();


    count = count - 1;
    if (count == 0){
        document.getElementById('dylytko').style.display = 'none';
    } else {
        document.getElementById('additko').style.display = 'block';
    }

}
$(document).ready(function () {
    $('#sidebarCollapse').on('click', function () {
        $('#sidebar').toggleClass('active');
        $(this).toggleClass('active');
        setTimeout(function () {
            resize_map();
        }, 500);
    });
});

/*
* Sets the tooltips to buttons on hover!
* */
$(document).ready(function(){
    $('[data-toggle="restaurant"]').tooltip();

    $('[data-toggle="place"]').tooltip();

    $('[data-toggle="mhd"]').tooltip();

    $('[data-toggle="bus"]').tooltip();

    $('[data-toggle="tram"]').tooltip();
});

/*
* Uses variable count to add mid-points in PLAN template
* */
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

/*
* Uses variable count to del mid-points in PLAN template
* */
function delAnotherBar() {
    $("#"+count).remove();


    count = count - 1;
    if (count == 0){
        document.getElementById('dylytko').style.display = 'none';
    } else {
        document.getElementById('additko').style.display = 'block';
    }

}

/*
* Toggle buttons to hide/show icons on map
* */
var restaurant = true;
var mhd = true;
var place = true;
var bus = true;
var tram = true;
function restaurantClicked() {
    $('[data-toggle="restaurant"]').tooltip("hide");
    if (restaurant){
        document.getElementById('restaurant').style.backgroundColor = '#7386D5';
        restaurant = false;
        //TODO restaurant is NOT checked in!
    } else {
        document.getElementById('restaurant').style.backgroundColor = '#6575b8';
        restaurant = true;
        //TODO restaurant is checked in!
    }
}
function mhdClicked() {
    $('[data-toggle="mhd"]').tooltip("hide");
    if (mhd){
        document.getElementById('mhd').style.backgroundColor = '#7386D5';
        mhd = false;
        //TODO mhd is NOT checked in ... MHD IN REAL TIME!
    } else {
        document.getElementById('mhd').style.backgroundColor = '#6575b8';
        mhd = true;
        //TODO mhd is checked in ... MHD IN REAL TIME!
    }
}
function placeClicked() {
    $('[data-toggle="place"]').tooltip("hide");
    if (place){
        document.getElementById('place').style.backgroundColor = '#7386D5';
        place = false;
        //TODO place is NOT checked in!
    } else {
        document.getElementById('place').style.backgroundColor = '#6575b8';
        place = true;
        //TODO place is checked in!
    }
}
function busClicked() {
    $('[data-toggle="bus"]').tooltip("hide");
    if (bus){
        document.getElementById('bus').style.backgroundColor = '#7386D5';
        bus = false;
        //TODO bus is NOT checked in BUS MOVES!
    } else {
        document.getElementById('bus').style.backgroundColor = '#6575b8';
        bus = true;
        //TODO bus is checked in BUS MOVES!
    }
}
function tramClicked() {
    $('[data-toggle="tram"]').tooltip("hide");
    if (tram){
        document.getElementById('tram').style.backgroundColor = '#7386D5';
        tram = false;
        //TODO tram is NOT checked in TRAM MOVES!
    } else {
        document.getElementById('tram').style.backgroundColor = '#6575b8';
        tram = true;
        //TODO tram is checked in TRAM MOVES!
    }
}

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
    $('[data-toggle="mhd"]').tooltip();
    $('[data-toggle="place"]').tooltip();
    $('[data-toggle="fun"]').tooltip();
    $('[data-toggle="shop"]').tooltip();
    $('[data-toggle="walk"]').tooltip();
    $('[data-toggle="car"]').tooltip();
    $('[data-toggle="mhdBrno"]').tooltip();
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
function funClicked() {
    $('[data-toggle="fun"]').tooltip("hide");
    if (fun){
        document.getElementById('fun').style.backgroundColor = '#7386D5';
        fun = false;
        //TODO fun is NOT checked in!
    } else {
        document.getElementById('fun').style.backgroundColor = '#6575b8';
        fun = true;
        //TODO fun is checked in!
    }
}
function shopClicked() {
    $('[data-toggle="shop"]').tooltip("hide");
    if (shop){
        document.getElementById('shop').style.backgroundColor = '#7386D5';
        shop = false;
        //TODO shop is NOT checked in!
    } else {
        document.getElementById('shop').style.backgroundColor = '#6575b8';
        shop = true;
        //TODO shop is checked in!
    }
}



/*
* Toggle buttons to pick way of transport
* */
function walkClicked() {
    $('[data-toggle="walk"]').tooltip("hide");
    if (walk){
        document.getElementById('walk').style.backgroundColor = '#7386D5';
        walk = false;
        //TODO walk is NOT checked in!
    } else {
        document.getElementById('walk').style.backgroundColor = '#6575b8';
        walk = true;
        //TODO walk is checked in!
    }
}function carClicked() {
    $('[data-toggle="car"]').tooltip("hide");
    if (car){
        document.getElementById('car').style.backgroundColor = '#7386D5';
        car = false;
        //TODO car is NOT checked in!
    } else {
        document.getElementById('car').style.backgroundColor = '#6575b8';
        car = true;
        //TODO car is checked in!
    }
}function mhdBrnoClicked() {
    $('[data-toggle="mhdBrno"]').tooltip("hide");
    if (mhdBrno){
        document.getElementById('mhdBrno').style.backgroundColor = '#7386D5';
        mhdBrno = false;
        //TODO mhdBrno is NOT checked in!
    } else {
        document.getElementById('mhdBrno').style.backgroundColor = '#6575b8';
        mhdBrno = true;
        //TODO mhdBrno is checked in!
    }
}
<?php

function GeoDistance($lati1, $longi1, $lati2, $longi2) { return sqrt( pow( abs($lati2-$lati1), 2) + pow( abs($longi2-$longi1), 2) ); }
function Distance($lati1, $longi1, $lati2, $longi2) {
    $a = pow(sin(($lati2-$lati1)/2),2)  +  cos($lati1) * cos($lati2) * pow(sin(($longi2-$longi1)/2),2);
    $c = 2*atan2(sqrt($a), sqrt(1-$a));
    return 6378*$c;
}

$d = Distance(deg2rad(50.15), deg2rad(14.5), deg2rad(49.15), deg2rad(16.5));
echo $d."\n";


https://maps.googleapis.com/maps/api/directions/json?origin=Boston,MA&destination=Concord,MA&waypoints=Charlestown,MA|Lexington,MA&key=YOUR_API_KEY
?>
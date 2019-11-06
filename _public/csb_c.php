<?php
$cs_link = mysqli_connect("h", "u", "p", "d");
if($cs_link === false){
    echo "There was an error connecting to CS* CC-DB. <br>Error: ";
    die(mysqli_connect_error());
}

$mfdv_link = mysqli_connect("h", "u", "p", "d");
if($mfdv_link === false){
    echo "There was an error connecting to MFDV. <br>Error: ";
    die(mysqli_connect_error());
}

?>
<?php
$cs_link = mysqli_connect("h", "u", "p", "CoLD_RP");
if($cs_link === false){
    echo "There was an error connecting to CS* CC-DB. <br>Error: ";
    die(mysqli_connect_error());
}

$mfdv_link = mysqli_connect("localhost", "u", "p", "cs_db");
if($mfdv_link === false){
    echo "There was an error connecting to local. <br>Error: ";
    die(mysqli_connect_error());
}

?>


<?php

function toCommunityID($id, $type = 1, $instance = 1) {
    if (preg_match('/^STEAM_/', $id)) {
        $parts = explode(':', str_replace('STEAM_', '', $id));
        $universe = (int)$parts[0];
        if ($universe == 0)
            $universe = 1;
        $steamID = ($universe << 56) | ($type << 52) | ($instance << 32) | ((int)$parts[2] << 1) | (int)$parts[1];
        return $steamID;
    } elseif (is_numeric($id) && strlen($id) < 16) {
        return (1 << 56) | ($type << 52) | ($instance << 32) | $id;
    } else {
        return $id; // We have no idea what this is, so just return it.
    }
}

require ('csb_c.php');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//******************************//
//START OF STEAM DATA
//******************************//

$mfdv_clear_steam = "TRUNCATE TABLE `cs_steam`;";
mysqli_query($mfdv_link, $mfdv_clear_steam);

$mfdv_steam = "SELECT `steam_id` FROM `cs_banks_primary`;";
$mfdvq_steam = mysqli_query($mfdv_link, $mfdv_steam);
while($mfdvq_check = mysqli_fetch_array($mfdvq_steam)){

    echo "<br>". $sid32 = $mfdvq_check['steam_id'];
    echo "<br>". $sid64 = toCommunityID($mfdvq_check['steam_id']);

    $url = file_get_contents("http://api.steampowered.com/IPlayerService/GetSteamLevel/v1/?key=47FF47AA44B75E2C8956D6DDA32B4B2F&steamid=".$sid64.""); 
	$content = json_decode($url, true);
	$level = $content['response']['player_level'];

    $url = file_get_contents("https://api.steampowered.com/ISteamUser/GetPlayerSummaries/v2/?key=47FF47AA44B75E2C8956D6DDA32B4B2F&steamids=".$sid64.""); 
	$content = json_decode($url, true);
    $sid64 = $content['response']['players'][0]['steamid'];
	$steam_state = $content['response']['players'][0]['profilestate'];
	$steam_name1 = $content['response']['players'][0]['personaname'];
	$steam_profileurl = $content['response']['players'][0]['profileurl'];
	$steam_avatarsml = $content['response']['players'][0]['avatar'];
	$steam_avatarmed = $content['response']['players'][0]['avatarmedium'];
    $steam_avatar = $content['response']['players'][0]['avatarfull'];
    $steam_state = $content['response']['players'][0]['personastate'];
    $steam_timecreated = $content['response']['players'][0]['timecreated'];

    $gametime_320 = 0;
    $gametime_730 = 0;

    $steam_name = mysqli_real_escape_string($mfdv_link, $steam_name1);

if($level==null){$level=0;}
if($steam_timecreated==null){$steam_timecreated=0;}

    echo '<br> SteamID64: '  .$sid64;
    echo '<br> SteamID32: '  .$sid32;
    echo '<br> Name: '       .$steam_name;
    echo '<br> Steam Level: '.$level;
    echo '<br> Avatar: '     .$steam_avatar;
    echo '<br> profile URL: '.$steam_profileurl;
    echo '<br> TimeCreated: '.$steam_timecreated;
    echo '<br><br>';


    $mfdv_update = 
    "INSERT INTO `cs_steam`(`steam_id`, `steam_id64`, `steam_level`, `steam_320`, `steam_avatar`, `steam_avatarmed`, `steam_avatarsml`, `steam_state`, `steam_name`, `steam_timecreated`, `steam_profileurl`) 
    VALUES ('$sid32', '$sid64','$level','$gametime_320','$steam_avatar','$steam_avatarmed','$steam_avatarsml','$steam_state','$steam_name','$steam_timecreated','$steam_profileurl')";
    
    mysqli_query($mfdv_link, $mfdv_update) or die(mysqli_error($mfdv_link));
}
//******************************//
//END OF STEAM DATA
//******************************//
?>
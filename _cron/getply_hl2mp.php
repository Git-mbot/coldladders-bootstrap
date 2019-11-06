<?php
require ('csb_c.php');
require_once 'SourceQuery.php';
	$url = file_get_contents("https://api.steampowered.com/ISteamUserStats/GetNumberOfCurrentPlayers/v1/?key=8D88BDB31B6869CE15A834A00318B828&appid=320"); 
	$content = json_decode($url, true);
	$GamePlyCount = $content['response']['player_count'];

	//Server Info
	$server = new SourceQuery('104.153.105.245', 27019);
	$infos  = $server->getInfos(); 
	$players  = $server->getPlayers();
	$ServerPlyCount = $infos['players'];
?>


<?php
echo "Loaded appid: 320</br>";
echo "Get player_count:".$GamePlyCount."</br>";
echo "Loaded serverID: 104.153.105.245</br>";
echo "Get player_count:".$ServerPlyCount."</br>";

if($GamePlyCount==null){
	$GamePlyCount=0;
}
if($ServerPlyCount==null){
	$ServerPlyCount=0;
}

$mfdv_update = "INSERT INTO `cs_hl2mp` (`GamePlyCount`, `ServerPlyCount`)	VALUES ('$GamePlyCount', '$ServerPlyCount')";
mysqli_query($mfdv_link, $mfdv_update) or die(mysqli_error($mfdv_link));
?>
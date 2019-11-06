<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>MFDV.ME - Cold Ladders</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel="stylesheet" href="./_assets/css/darkly/bootstrap.css" media="screen">
    <link rel="stylesheet" href="./_assets/css/custom.min.css">
    <script src="./_assets/chartjs/Chart.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
</head>

<body>
    <div class="navbar navbar-expand-lg fixed-top navbar-dark bg-dark">
        <div class="container">
            <a href="#" class="navbar-brand">MFDV.ME</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
            </nav>
        </div>
    </div>

    <?php 


require ('csb_c.php'); 
require ('tables.php'); 
require_once '_assets/lib/SourceQuery.php';

  //Handling Steam ID Conversion
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
			return $id;
	}
}

  //economy
  $mfdv_banks =   "SELECT 	cs_banks_primary.steam_id, cs_banks_primary.cash, cs_banks_primary.bank, cs_banks_primary.income, cs_banks_secondary.cash, cs_banks_secondary.bank,cs_banks_secondary.income, cs_banks_primary.name FROM cs_banks_primary INNER JOIN cs_banks_secondary ON cs_banks_primary.steam_id=cs_banks_secondary.steam_id ORDER BY cs_banks_primary.bank DESC LIMIT 25";
  $mfdvq_banks = mysqli_query($mfdv_link, $mfdv_banks);
  
  while($mfdv_row = mysqli_fetch_array($mfdvq_banks)){ $topmoney = $mfdv_row['name']; break; }
  //reset locaion in array
  mysqli_data_seek( $mfdvq_banks, 0 );


  //Combat
  $mfdv_elo = "SELECT cs_elo_primary.steam_id, cs_elo_primary.elo, cs_elo_primary.kills, cs_elo_primary.deaths, cs_elo_secondary.elo, cs_elo_secondary.kills,cs_elo_secondary.deaths, cs_elo_primary.name FROM cs_elo_primary INNER JOIN cs_elo_secondary ON cs_elo_primary.steam_id=cs_elo_secondary.steam_id ORDER BY cs_elo_primary.elo DESC LIMIT 25";
  $mfdvq_elo = mysqli_query($mfdv_link, $mfdv_elo);
  
  while($mfdv_row = mysqli_fetch_array($mfdvq_elo)){ $topelo = $mfdv_row['name']; break; }
  mysqli_data_seek( $mfdvq_elo, 0 );


  //Combat Kills
  $mfdv_kills = "SELECT * FROM cs_elo_primary ORDER BY cs_elo_primary.kills DESC LIMIT 25";
  $mfdvq_kills = mysqli_query($mfdv_link, $mfdv_kills);

  while($mfdv_row = mysqli_fetch_array($mfdvq_kills)){ $topkills = $mfdv_row['name']; break; }
  mysqli_data_seek( $mfdvq_kills, 0 );


  //Respect
  $mfdv_respect = "SELECT * FROM `cs_expresp_ladder` WHERE `respect` < '500000' ORDER BY `respect` DESC";
  $mfdvq_respect = mysqli_query($mfdv_link, $mfdv_respect);

  while($mfdv_row = mysqli_fetch_array($mfdvq_respect)){ $toprespect = $mfdv_row['name']; break; }
  mysqli_data_seek( $mfdvq_respect, 0 );


  //Experience
  $mfdv_experience = "SELECT * FROM `cs_expresp_ladder` WHERE `experience` < '500000' ORDER BY `experience` DESC";
  $mfdvq_experience = mysqli_query($mfdv_link, $mfdv_experience);

  while($mfdv_row = mysqli_fetch_array($mfdvq_experience)){ $topexperience = $mfdv_row['name']; break; }
  mysqli_data_seek( $mfdvq_experience, 0 );


  //Playtime
  $mfdv_playtime = "SELECT * FROM `cs_banks_primary` ORDER BY `cs_banks_primary`.`minutes` DESC;";
  $mfdvq_playtime = mysqli_query($mfdv_link, $mfdv_playtime);

  while($mfdv_row = mysqli_fetch_array($mfdvq_playtime)){
    if($mfdv_row['steam_id']!="STEAM_0:1:25306470"){
      $days=0; $hours=0; $minutes=0;
      $totalplaytime=0;
      $totalplaytime+=$mfdv_row['minutes'];
    
      if($mfdv_row['minutes'] < 60){
        $minutes = $mfdv_row['minutes'];
      }elseif($mfdv_row['minutes'] < 1440){
        $hours=$mfdv_row['minutes']/60;
        $minutes=$mfdv_row['minutes']%60;
      }elseif($mfdv_row['minutes'] >= 1440) {
        $days=$mfdv_row['minutes']/1440;
        $days_remainder=$mfdv_row['minutes']%1440;
        $hours=$days_remainder/60;
        $minutes=$days_remainder%60;
      }
    
      $days=number_format($days,0); $hours=number_format($hours,0); $minutes=number_format($minutes,0);
      $topplaytime = $mfdv_row['name'];
      break;
    }
  }
  mysqli_data_seek( $mfdvq_playtime, 0 );

  //Items
  $mfdv_items = "SELECT * FROM `cs_itemladder` WHERE `itemid` != '251' AND `itemid` != '259' AND `itemid` != '255' AND `quantity` > '0' ORDER BY `value` DESC";
  $mfdvq_items = mysqli_query($mfdv_link, $mfdv_items);

  //Elo Season
  $mfdv_eloseason1 = "SELECT * FROM `cs_elo_seasons` WHERE `season`='1' ORDER BY `elo` DESC LIMIT 25";
  $mfdvq_eloseason1 = mysqli_query($mfdv_link, $mfdv_eloseason1);
  
//Gang Eco
 $mfdv_gangEco = "SELECT * FROM `cs_gangs` ORDER BY `cs_gangs`.`gang_bank` DESC LIMIT 25";
 $mfdvq_gangEco = mysqli_query($mfdv_link, $mfdv_gangEco);

 //Gang Experience
 $mfdv_gangExperience = "SELECT * FROM `cs_gangs` ORDER BY `cs_gangs`.`gang_experience` DESC LIMIT 25";
 $mfdvq_gangExperience = mysqli_query($mfdv_link, $mfdv_gangExperience);

 //Gang Respect
 $mfdv_gangRespect = "SELECT * FROM `cs_gangs` ORDER BY `cs_gangs`.`gang_respect` DESC LIMIT 25";
 $mfdvq_gangRespect = mysqli_query($mfdv_link, $mfdv_gangRespect);
?>

<style>
/* width */::-webkit-scrollbar {width: 10px;}/* Track */::-webkit-scrollbar-track {background: #272727; }/* Handle */::-webkit-scrollbar-thumb {background: #3a3a3abe; }/* Handle on hover */::-webkit-scrollbar-thumb:hover {background: #3a3a3abe; }
</style>
    <div class="container" id="#top">

        <div class="col-lg-12">
            <div>
        <div class="alert alert-dismissible alert-danger">
                <strong>Visitor Notice!</strong> 
                <br>Cold Ladders on MFDV.ME will be closing <a href="#" class="alert-link">November 29th 2019</a>. 
                <br>The source code for Cold Ladders Boot-Strap will be available on <a href="https://github.com/Git-mbot/coldladders-bootstrap" class="alert-link">Github</a>.
        </div>

                <!--<ul class="nav nav-tabs">
    <li class="nav-item">
      <a class="nav-link active" onclick="toggleVisibility2('section1')">Home</a>
    </li>-->
            </div>
        </div>
        <div id="section1">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-sm-3">
                        <div class="card border-secondary mb-3" style="max-width: 20rem;">
                            <div class="card-header">
                                <h5 class="card-title">Combat Leader</h5>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <p>
                                        <?php echo $topelo; ?> has the highest ELO rating.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="card border-secondary mb-3" style="max-width: 20rem;">
                            <div class="card-header">
                                <h5 class="card-title">Playtime Leader</h5>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <p>
                                        <?php echo $topplaytime; ?>
                                        <?php echo "<br>with ". $days ."d / ".$hours."h / ". $minutes."m played. \n";?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="card border-secondary mb-3" style="max-width: 20rem;">
                            <div class="card-header">
                                <h5 class="card-title">Respect Leader</h5>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <p>
                                        <?php echo $toprespect; ?> has the most rebel respect points.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="card border-secondary mb-3" style="max-width: 20rem;">
                            <div class="card-header">
                                <h5 class="card-title">Experience Leader</h5>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <p>
                                        <?php echo $topexperience; ?> has the most cop experience points.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-sm-3">
                        <div>
                            <div class="card border-Secondary mb-3">
                                <div class="card-header">
                                    <h5 class="card-title">Filters</h5>
                                    <h6 class="card-subtitle text-muted">Choose a Ladder</h6>
                                </div>
                                <ul class="list-group list-group-flush">
                                    <div class="form-group">
                                        <li class="list-group-item">
                                            <div class="custom-control custom-radio">
                                                <input onclick="toggleVisibility('Div1')" type="radio" id="customRadio1" name="customRadio" class="custom-control-input" checked>
                                                <label class="custom-control-label" for="customRadio1">Economy</label>
                                            </div>
                                        </li>
                                        <li class="list-group-item">
                                            <div class="custom-control custom-radio">
                                                <input onclick="toggleVisibility('Div2')" type="radio" id="customRadio2" name="customRadio" class="custom-control-input">
                                                <label class="custom-control-label" for="customRadio2">Combat</label>
                                            </div>
                                        </li>
                                        <li class="list-group-item">
                                            <div class="custom-control custom-radio">
                                                <input onclick="toggleVisibility('Div3')" type="radio" id="customRadio3" name="customRadio" class="custom-control-input">
                                                <label class="custom-control-label" for="customRadio3">Playtime</label>
                                            </div>
                                        </li>
                                        <li class="list-group-item">
                                            <div class="custom-control custom-radio">
                                                <input onclick="toggleVisibility('Div4')" type="radio" id="customRadio4" name="customRadio" class="custom-control-input">
                                                <label class="custom-control-label" for="customRadio4">Respect</label>
                                            </div>
                                        </li>
                                        <li class="list-group-item">
                                            <div class="custom-control custom-radio">
                                                <input onclick="toggleVisibility('Div5')" type="radio" id="customRadio5" name="customRadio" class="custom-control-input">
                                                <label class="custom-control-label" for="customRadio5">Experience</label>
                                            </div>
                                        </li>
                                        <li class="list-group-item">
                                            <div class="custom-control custom-radio">
                                                <input onclick="toggleVisibility('Div6')" type="radio" id="customRadio6" name="customRadio" class="custom-control-input">
                                                <label class="custom-control-label" for="customRadio6">Items</label>
                                            </div>
                                        </li>
                                    </div>
                                </ul>
                                <div class="card-footer text-muted">
                                    <?php 
                $seconds = time();
                $rounded_seconds = floor($seconds / (10 * 60)) * (10 * 60);
                ?> Last Updated:
                                    <?php echo date('H:i', $rounded_seconds) ?>
                                </div>
                            </div>
                        </div>
                        <div class="card border-secondary mb-3" style="max-width: 20rem;">
                            <div class="card-header">
                                <h5 class="card-title">Gang Ladders</h5>
                                <h6 class="card-subtitle text-muted">Top Gang Totals</h6>
                            </div>
                            <ul class="list-group list-group-flush">
                                <div class="form-group">
                                    <li class="list-group-item">
                                        <div class="custom-control custom-radio">
                                            <input onclick="toggleVisibility('Div8')" type="radio" id="customRadio8" name="customRadio" class="custom-control-input">
                                            <label class="custom-control-label" for="customRadio8">Economy</label>
                                        </div>
                                    </li>
                                    <li class="list-group-item">
                                        <div class="custom-control custom-radio">

                                            <input onclick="toggleVisibility('Div9')" type="radio" id="customRadio9" name="customRadio" class="custom-control-input">
                                            <label class="custom-control-label" for="customRadio9">Experience</label>
                                        </div>
                                    </li>
                                    <li class="list-group-item">
                                        <div class="custom-control custom-radio">
                                            <input onclick="toggleVisibility('Div10')" type="radio" id="customRadio10" name="customRadio" class="custom-control-input">
                                            <label class="custom-control-label" for="customRadio10">Respect</label>
                                        </div>
                                    </li>
                                </div>
                            </ul>
                        </div>

                        <div class="card border-secondary mb-3" style="max-width: 20rem;">
                            <div class="card-header">
                                <h5 class="card-title">Combat Seasons</h5>
                                <h6 class="card-subtitle text-muted">Seasonal Ladder History</h6>
                            </div>
                            <ul class="list-group list-group-flush">
                                <div class="form-group">
                                    <li class="list-group-item">
                                        <div class="custom-control custom-radio">
                                            <input onclick="toggleVisibility('Div7')" type="radio" id="customRadio7" name="customRadio" class="custom-control-input">
                                            <label class="custom-control-label" for="customRadio7">Season 1 - <a class="text-muted">2019/09/13</a></label>
                                        </div>
                                    </li>
                                    <li class="list-group-item">
                                        <div class="custom-control custom-radio">
                                            <!-- Since the current active combat season is 2, we will just show the Combat ladder instead of making another table -->
                                            <input onclick="toggleVisibility('Div2')" type="radio" id="customRadio800" name="customRadio" class="custom-control-input">
                                            <label class="custom-control-label" for="customRadio800">Season 2 - <a style="color: green;">Active!</a></label>
                                        </div>
                                    </li>
                                </div>
                            </ul>
                        </div>

                        <?php $server = new SourceQuery('104.153.105.245', 27019);
                            $infos  = $server->getInfos(); 
                            $players  = $server->getPlayers();
                        ?>

                        <div class="card border-secondary mb-3" style="max-width: 20rem;">
                            <div class="card-header">
                                <h5 class="card-title">Server Info</h5>
                                <h6 class="card-subtitle text-muted">Basic Server Information</h6>
                            </div>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">Name:
                                    <?php echo $infos['name']; ?>
                                </li>
                                <li class="list-group-item">IP:
                                    <?php echo $infos['ip'] . ":" . $infos['port']; ?>
                                </li>
                                <li class="list-group-item">Players:
                                    <?php echo $infos['players']; ?>
                                </li>
                                <li class="list-group-item">Map:
                                    <?php echo $infos['map']; ?>
                                </li>
                            </ul>
                        </div>

                        <div class="card border-secondary mb-3" style="max-width: 20rem;">
                            <div class="card-header">
                                <h5 class="card-title">Players Online</h5>
                                <h6 class="card-subtitle text-muted">List of Connected Players</h6>
                            </div>
                            <ul class="list-group list-group-flush" style="max-height: 31.8em;overflow: scroll;overflow-x: hidden;">
                                    <?php
                                        $i=0;
                                        $days=0; $hours=0; $minutes=0;
                                        foreach ($players as $players_row){
                                        $i++;
                                        $time_minute = $players_row['time']/60;
                                        $time_minutes = floor($time_minute);
                                        if($time_minutes< 60){
                                            $minutes = $time_minutes;
                                          }elseif($time_minutes <= 1440){
                                            $hours=$time_minutes/60;
                                            $minutes=$time_minutes%60;
                                          }elseif($time_minutes > 1440) {
                                            $days=$time_minutes/1440;
                                            $days_remainder=$time_minutes%1440;
                                            $hours=$days_remainder/60;
                                            $minutes=$time_minutes%60;
                                          }

                                          $days=floor($days); $hours=floor($hours); $minutes=floor($minutes);
                                        
                                          if($time_minutes < 60){
                                            $hours=0;
                                          }elseif($time_minutes > 60) {
                                            $hours=1;
                                          }


                                            if($players_row['name'] == null) {
                                            echo "<li class='list-group-item'><a style='color:yellow;'>Connecting...</a></li> ";
                                            } else {
                                            echo "<li class='list-group-item'>".mb_strimwidth($players_row['name'], 0, 24, "...") . "<br>Time Online: ". $days ."d / ".$hours. "h / ". $minutes."m </li> ";
                                            }
                                        }
                                    ?>
                            </ul>
                        </div>
                    </div>

                    <div class="col-sm-9">
                      <div>
                        <div id="Div1">
                            <table class="table table-default">
                                <thead>
                                    <tr>
                                        <th scope="col">Rank</th>
                                        <th scope="col">Player</th>
                                        <th scope="col">Income</th>
                                        <th scope="col">Money</th>
                                        <th scope="col">Bank</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php tableEconomy($mfdvq_banks); ?>
                                </tbody>
                            </table>
                        </div>
                        <div id="Div2" style="display: none;">
                            <table class="table table-default">
                                <thead>
                                    <tr>
                                        <th scope="col">Rank</th>
                                        <th scope="col">Player</th>
                                        <th scope="col">Elo</th>
                                        <th scope="col">Kills</th>
                                        <th scope="col">Deaths</th>
                                        <th scope="col">K/D Ratio</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php tableCombat($mfdvq_elo); ?>
                                </tbody>
                            </table>
                        </div>
                        <div id="Div3" style="display: none;">
                            <table class="table table-default">
                                <thead>
                                    <tr>
                                        <th scope="col">Rank</th>
                                        <th scope="col">Player</th>
                                        <th scope="col">Time Played</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php tablePlaytime($mfdvq_playtime); ?>
                                </tbody>
                            </table>
                        </div>
                        <div id="Div4" style="display: none;">
                            <table class="table table-default">
                                <thead>
                                    <tr>
                                        <th scope="col">Rank</th>
                                        <th scope="col">Player</th>
                                        <th scope="col">Respect</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php tableRespect($mfdvq_respect); ?>
                                </tbody>
                            </table>
                        </div>
                        <div id="Div5" style="display: none;">
                            <table class="table table-default">
                                <thead>
                                    <tr>
                                        <th scope="col">Rank</th>
                                        <th scope="col">Player</th>
                                        <th scope="col">Experience</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php tableExperience($mfdvq_experience); ?>
                                </tbody>
                            </table>
                        </div>
                        <div id="Div6" style="display: none;">
                            <table class="table table-default">
                                <thead>
                                    <tr>
                                        <th scope="col">Rank</th>
                                        <th scope="col">Item</th>
                                        <th scope="col">Quantity</th>
                                        <th scope="col">Price</th>
                                        <th scope="col">Value</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php tableItems($mfdvq_items); ?>
                                </tbody>
                            </table>
                        </div>
                        <div id="Div7" style="display: none;">
                            <table class="table table-default">
                                <thead>
                                    <tr>
                                        <th scope="col">Rank</th>
                                        <th scope="col">Player</th>
                                        <th scope="col">Elo</th>
                                        <th scope="col">Kills</th>
                                        <th scope="col">Deaths</th>
                                        <th scope="col">K/D Ratio</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php tableSeason1($mfdvq_eloseason1); ?>
                                </tbody>
                            </table>
                        </div>
                        <div id="Div8" style="display: none;">
                            <table class="table table-default">
                                <thead>
                                    <tr>
                                        <th scope="col">Rank</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Money</th>
                                        <th scope="col">Bank</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php tableGangEco($mfdvq_gangEco); ?>
                                </tbody>
                            </table>
                        </div>
                        <div id="Div9" style="display: none;">
                            <table class="table table-default">
                                <thead>
                                    <tr>
                                        <th scope="col">Rank</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Experience</th>
                                        <th scope="col">Respect</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php tablegangExperience($mfdvq_gangExperience); ?>
                                </tbody>
                            </table>
                        </div>
                        <div id="Div10" style="display: none;">
                            <table class="table table-default">
                                <thead>
                                    <tr>
                                        <th scope="col">Rank</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Experience</th>
                                        <th scope="col">Respect</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php tablegangRespect($mfdvq_gangRespect); ?>
                                </tbody>
                            </table>
                        </div>


                        <!--<div class="card border-secondary mb-9" style="max-width: 100%;">
                            <div class="card-header">
                                <h5 class="card-title">Player Lookup</h5>
                                <h6 class="card-subtitle text-muted">Find more information about any player.</h6>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    Enter a SteamID
                                    <li class='list-group-item'>
                                        <br>
                                        <form action="index.php" style="max-width: 20rem;">
                                            <div class="input-group mb-3">
                                                <input type="text" class="form-control" name="steamid" placeholder="STEAM_0:0:24352559" id="steamid" value=""><br>
                                                <div class="input-group-append">
                                                    <span><button type="submit" class="btn btn-primary">Submit</button></span>
                                                </div>
                                            </div>
                                        </form>
                                    </li>
                               </div>
                                <?php
                                /*if($_GET["steamid"] == null ){ 
                                }else{
                                $id=$_GET["steamid"];
                                $id2 = mysqli_real_escape_string($mfdv_link, $id);
                                $getrank = "SELECT * FROM `ranks_economy` WHERE `steam_id` = '$id2'";
                                $getrankQ = mysqli_query($mfdv_link, $getrank);
                                $getrank = mysqli_fetch_array($getrankQ);
                                $y = $getrankQ;
                                $x = mysqli_num_rows($y);
                                    if($x == 0){
                                    echo "<p>SteamID not found or input invalid.</p>\n";
                                    }else{
                                    $bank = number_format($getrank['bank']);
                                    $income = number_format($getrank['income']);
                                    $money = number_format($getrank['money']);
                                    $experience = number_format($getrank['experience']);
                                    $respect = number_format($getrank['respect']);
                                    $url=toCommunityID($id);
                                
                                    echo "General";
                                    echo "<li class='list-group-item'>User: ".$getrank['name']."</br>";
                                    echo "Steam ID: ". $id ."</br>";
                                    echo "Steam Profile: <a style='color:#fff;' href='http://www.steamcommunity.com/profiles/". $url ."' target='_blank'>http://www.steamcommunity.com/profiles/". $url ."</a></li></br>";
                                    echo "Economy";
                                    echo "<li class='list-group-item'>Economy Rank #". $getrank['rank'] ."</br>";
                                    echo "Money: $". $money ."</br>";
                                    echo "Bank: $". $bank ."</br>";
                                    echo "Income: $". $income ."</li></br>";
                                    echo "Skill Points";
                                    echo "<li class='list-group-item'>Experience: ". $experience ."</br>";
                                    echo "Respect: ". $respect ."</li></br>";
                                    }
                                }*/
                                ?>
                            </div>
                        </div>-->
                        <!-- End of player lookup -->

                        <div class="card border-secondary mb-9" style="max-width: 100%;">
                            <div class="card-header">
                                <h5 class="card-title">Average Player Count</h5>
                                <h6 class="card-subtitle text-muted">1 Month Player Count Log.</h6>
                            </div>
                            <div class="card-body">
                                <h6 class="card-subtitle text-muted" style="align: center;">This charts reads left to right, from today into the past.</h6>
                                <div style="width:98%;">
                                    <canvas id="hl2mp"></canvas>
                                </div>
                            </div>
                        </div>

                    </div>
                    <!-- end of BS Component which manages the modules splits keep new right side modules above this -->
                </div>

            </div>
             <!-- This div marks the end of Main Div Col-SM-9 -->
        </div>
        <!-- This div marks the end of Section 1 -->
    </div>
    <!-- This div marks the end of Container -->

            <footer id="footer">
                <div class="row">
                    <div class="col-lg-12">
                        <ul class="list-unstyled">
                            <li class="float-lg-right"><a href="#top">Back to top</a></li>
                            <li><a target="_blank" href="https://www.steamcommunity.com/id/micobot">Admin</a></li>
                            <li><a target="_blank" href="https://coldcommunity.com/forum/">Database</a></li>
                        </ul>
                    </div>
                </div>
            </footer>
            <script>
                //Control showing of Ladders via Filters.
              var divs = ["Div1", "Div2", "Div3", "Div4", "Div5", "Div6", "Div7", "Div8", "Div9", "Div10"];
              var visibleDivId = null;
              function toggleVisibility(divId) {
                if(visibleDivId === divId) {
                  //visibleDivId = null;
                } else {
                  visibleDivId = divId;
                }
                hideNonVisibleDivs();
              }
              function hideNonVisibleDivs() {
                var i, divId, div;
                for(i = 0; i < divs.length; i++) {
                  divId = divs[i];
                  div = document.getElementById(divId);
                  if(visibleDivId === divId) {
                    div.style.display = "block";
                  } else {
                    div.style.display = "none";
                  }
                }
              }
            </script>

            <script>
                //Control NavBar section show/hide
                
                  var divs2 = ["section1", "section2"];
                  var visibleDivId2 = null;
                  function toggleVisibility2(divId) {
                    if(visibleDivId2 === divId) {
                      //visibleDivId = null;
                    } else {
                      visibleDivId2 = divId;
                    }
                    hideNonVisibleDivs2();
                  }
                  function hideNonVisibleDivs2() {
                    var i, divId, div;
                    for(i = 0; i < divs2.length; i++) {
                      divId = divs2[i];
                      div = document.getElementById(divId);
                      if(visibleDivId2 === divId) {
                        div.style.display = "block";
                      } else {
                        div.style.display = "none";
                      }
                    }
                  }
            </script>


        <script>
            new Chart(document.getElementById("hl2mp"), {
                    "type": "line",
                    "data": {
                        "labels": [ "Today", "1 Day Ago", "2 Days Ago", "3 Days Ago", "4 Days Ago", "5 Days Ago", "6 Days Ago", "1 Week Ago", "8 Days Ago", "9 Days Ago", "10 Days Ago", "11 Days Ago", "12 Days Ago", "13 Days Ago", "2 Weeks Ago", "15 Days Ago", "16 Days Ago", "17 Days Ago", "19 Days Ago", "20 Days Ago", "3 Weeks Ago", "22 Days Ago", "23 Days Ago", "24 Days Ago", "25 Days Ago", "26 Days Ago", "27 Days Ago", "4 Weeks Ago", "29 Days Ago", "1 Month Ago"],
                        "datasets": [{
                            "label": "Half-Life 2:Deathmatch",
                            "data": [<?php 
                            $mfdv_hl2mp_player_count = "SELECT * FROM `cs_hl2mp` ORDER BY `updatetime` DESC LIMIT 720";
                            $mfdvq_hl2mp_player_count = mysqli_query($mfdv_link, $mfdv_hl2mp_player_count);
                            $i=0;
                            $x=0; 
                            $z=0;
                            $y=0;

                            while($mfdv_row2 = mysqli_fetch_array($mfdvq_hl2mp_player_count)){
                                $i++;
                                if($i==24){
                                    $z=$y/24;
                                    $z=number_format($z);
                                    $y=0;
                                }

                                if($i==24 && $x != 30){
                                    echo $z . ", ";
                                    $x++;
                                    $i=0;
                                } else if($x == 30) {
                                    echo $z . "";
                                    $i=0;
                                }
                                $y=$y+$mfdv_row2['GamePlyCount'];
                            }
                    ?>],
                            "fill": false,
                            "borderColor": "rgb(200, 200, 200)",
                            "lineTension": 0.5
                        },{
                            "label": "Cold Community",
                            "data": [<?php 
                            $mfdv_hl2mp_player_count = "SELECT * FROM `cs_hl2mp` ORDER BY `updatetime` DESC LIMIT 720";
                            $mfdvq_hl2mp_player_count = mysqli_query($mfdv_link, $mfdv_hl2mp_player_count);
                            $i=0;
                            $x=0; 
                            $z=0;
                            $y=0;

                            while($mfdv_row2 = mysqli_fetch_array($mfdvq_hl2mp_player_count)){
                                $i++;
                                if($i==24){
                                    $z=$y/24;
                                    $z=number_format($z);
                                    $y=0;
                                }

                                if($i==24 && $x != 30){
                                    echo $z . ", ";
                                    $x++;
                                    $i=0;
                                } else if($x == 30) {
                                    echo $z . "";
                                    $i=0;
                                }
                                $y=$y+$mfdv_row2['ServerPlyCount'];
                            }
                    ?>],
                            "fill": false,
                            "borderColor": "rgb(200, 100, 100)",
                            "lineTension": 0.5
                        }]
                    },
                    "options": {}
                }

            ); 
        </script>

            <script src="../_vendor/jquery/dist/jquery.min.js"></script>
            <script src="../_vendor/popper.js/dist/umd/popper.min.js"></script>
            <script src="../_vendor/bootstrap/dist/js/bootstrap.min.js"></script>
            <script src="../_assets/js/custom.js"></script>
</body>

</html>
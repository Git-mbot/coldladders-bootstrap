<?php

function tableEconomy($mfdvq_banks){

$rank=0;
$totalmoney=0;
while($mfdv_row = mysqli_fetch_array($mfdvq_banks)){
	if ($rank==0){
        $topeconomy=$mfdv_row[7];
    }
	//The RED or GREEN shift in money.
	$cashshift = number_format($cashshi=$mfdv_row[1]-$mfdv_row[4]);
	$bankshift = number_format($bankshi=$mfdv_row[2]-$mfdv_row[5]);
	
	//Original bank amount GRAY
	$bankoriginal = number_format($mfdv_row[2]);
	$cashoriginal = number_format($mfdv_row[1]);

	$totalmoney+=$mfdv_row[2];
	$totalmoney+=$mfdv_row[1];

	$rank++;


	echo "<tr>\n";
	echo "<td>\n"; 


		// GOLD / SILVER / BRONZE Stars for Top 3
		if($rank == 1){			
			echo "<a style='color:#ffc942;font-size:18px;'>★";
		}else if($rank == 2){	
			echo "<a style='color:silver;font-size:18px;'>★";
		}else if($rank == 3){	
			echo "<a style='color:#603819;font-size:18px;'>★";
		}

		//The Users rank
		echo "</a>". $rank .".</td>\n";


		//Output the players name, or unknown if none is found.
		if($mfdv_row[7] == 'username'){
			echo "<td><a style='color:red;'>Name Unknown</a></td>\n";
		}else{
			echo "<td> ". $mfdv_row[7] ."</td>\n";
		}

		//Output the users Income.
		echo "<td> $".$mfdv_row[3]."/min</td>\n";


		//Bank Shift
		if($bankshift > 0){
			echo "<td>$". $bankoriginal ." - <span style='color:green;'>$+". $bankshift ."</span></td>\n";
		}else if($bankshift < 0){
			echo "<td>$". $bankoriginal ." - <span style='color:red;'>$". $bankshift ."</span></td>\n";
		}else{
			echo "<td>$". $bankoriginal ." </td>\n";
		}
	

		//Money Shift
		if($cashshift > 0){
			echo "<td>$". $cashoriginal ." - <span style='color:green;'>$+". $cashshift ."</span></td>\n";
		}else if($cashshift < 0){
			echo "<td>$". $cashoriginal ." - <span style='color:red;'>$". $cashshift ."</span></td>\n";
		}else{
			echo "<td>$". $cashoriginal ." </td>\n";
		}


		//End Line (row)
		echo "</tr>\n\n";

		//Breakout at 25 listed users
		if($rank >= 25){ 
		break;
		}

//End of While Loop & PHP for table.
}
//end of function
return $topeconomy;
}




function tableCombat($mfdvq_elo){
 								
$rank=0;
while($mfdv_row = mysqli_fetch_array($mfdvq_elo)){

	//Calculate changes in Elo, Kills and Deaths. RED / GREEN
	$eloshift = $mfdv_row[1]-$mfdv_row[4];
	$killshift = $mfdv_row[2]-$mfdv_row[5];
	$deathshift = $mfdv_row[3]-$mfdv_row[6];

	//Calculate the users Kill Death Ratio.
	$kdr = number_format($mfdv_row[2]/$mfdv_row[3],2);

	//The Original values GRAY
	$elooriginal = number_format($mfdv_row[1]);
	$killoriginal = number_format($mfdv_row[2]);
	$deathoriginal = number_format($mfdv_row[3]);

	//Rank
	$rank++;

		echo "<tr>\n";			
		echo "<td>\n"; 

		// GOLD / SILVER / BRONZE Stars for Top 3
		if($rank == 1){
			echo "<a style='color:#ffc942;font-size:18px;'>★";
		} else if ($rank == 2){
			echo "<a style='color:silver;font-size:18px;'>★";
		}else if ($rank == 3){
			echo "<a style='color:#603819;font-size:18px;'>★";
		}
		
		//Ranking
		echo "</a>". $rank .".</td>\n";

		//Output the players name, or unknown if none is found.
		if($mfdv_row[7] == 'username'){
			echo "<td><span style='color:red;'>Name Unknown</span></td>\n";
		}else{
			echo "<td> ". $mfdv_row[7] ."</td>\n";
		}

		//Elo Shift
		if($eloshift > 0){
			echo "<td>". $elooriginal ." <span style='color:green;'>+". $eloshift ."</span></td>\n";
		}else if($eloshift < 0){
			echo "<td>". $elooriginal ." <span style='color:red;'>". $eloshift ."</span></td>\n";
		}else{
			echo "<td>". $elooriginal ." </td>\n";
		}
			
		//Kill Shift
		if($killshift > 0){
			echo "<td>". $killoriginal ." <span style='color:green;'>+". $killshift ."</span></td>\n";
		}else if($killshift < 0){
			echo "<td>". $killoriginal ." <span style='color:red;'>". $killshift ."</span></td>\n";
		}else{
			echo "<td>". $killoriginal ." </td>\n";
		}
		//Death Shift
		if($deathshift > 0){
			echo "<td>". $deathoriginal ." <span style='color:green;'>+". $deathshift ."</span></td>\n";
		}else if($deathshift < 0){
			echo "<td>". $deathoriginal ." <span style='color:red;'>". $deathshift ."</span></td>\n";
		}else{
			echo "<td>". $deathoriginal ." </td>\n";
		}

		//Kill Death Ratio
		echo "<td>". $kdr ." </td>\n";

		echo "</tr>\n\n";

		//Breakout at 25 listed users
		if($rank >= 25){ 
		break; 
		}

//End of While Loop & PHP for table.
}
    
}

function tableRespect($mfdvq_respect){
$rank=0;
while($mfdv_row = mysqli_fetch_array($mfdvq_respect)){
	
	$respect = number_format($mfdv_row[2]);
	//Add to rank
	$rank++;

		echo "<tr>\n";			
		echo "<td>\n"; 

		// GOLD / SILVER / BRONZE Stars for Top 3
		if($rank == 1){
			echo "<a style='color:#ffc942;font-size:18px;'>★";
		} else if ($rank == 2){
			echo "<a style='color:silver;font-size:18px;'>★";
		}else if ($rank == 3){
			echo "<a style='color:#603819;font-size:18px;'>★";
		}

		//Ranking
		echo "</a>". $rank .".</td>\n";
		
		//output ColdDB Name
		if($mfdv_row['name'] == 'username'){
			echo "<td><span style='color:red;'>Name Unknown</span></td>\n";
		}else{
			echo "<td> ". $mfdv_row['name'] ."</td>\n";
		}

		//Print Respect
		echo "<td>" . $respect ."</td>\n";

		echo "</tr>\n\n";

		//Breakout at 25 listed users
		if($rank >= 25){ 
			break; 
		}

//End of While Loop & PHP for table.
}	

}

function tableExperience($mfdvq_experience){

	$rank=0;
while($mfdv_row = mysqli_fetch_array($mfdvq_experience)){
	
	$exp = number_format($mfdv_row[1]);
	//Add to rank
	$rank++;

		echo "<tr>\n";			
		echo "<td>\n"; 

		// GOLD / SILVER / BRONZE Stars for Top 3
		if($rank == 1){
			echo "<a style='color:#ffc942;font-size:18px;'>★";
		} else if ($rank == 2){
			echo "<a style='color:silver;font-size:18px;'>★";
		}else if ($rank == 3){
			echo "<a style='color:#603819;font-size:18px;'>★";
		}

		//Ranking
		echo "</a>". $rank .".</td>\n";
		
		//output ColdDB Name
		if($mfdv_row['name'] == 'username'){
			echo "<td><span style='color:red;'>Name Unknown</span></td>\n";
		}else{
			echo "<td> ". $mfdv_row['name'] ."</td>\n";
		}

		//Print Experience
		echo "<td>" . $exp ."</td>\n";

		echo "</tr>\n\n";

		//Breakout at 25 listed users
		if($rank >= 25){
			break; 
		}

//End of While Loop & PHP for table.
}	

}

function tablePlaytime($mfdvq_playtime){
	$rank=0;
	while($mfdv_row = mysqli_fetch_array($mfdvq_playtime)){
		if($mfdv_row['steam_id']=="STEAM_0:1:25306470"){
		goto skip;
		}
		$days=0;
		$hours=0;
		$minutes=0;
	
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
	
		$days=number_format($days,0);
		$hours=number_format($hours,0);
		$minutes=number_format($minutes,0);
	
		//anking
		$rank++;
	
			echo "<tr>\n";			
			echo "<td>\n"; 
	
			// GOLD / SILVER / BRONZE Stars for Top 3
			if($rank == 1){
				echo "<a style='color:#ffc942;font-size:18px;'>★";
			} else if ($rank == 2){
				echo "<a style='color:silver;font-size:18px;'>★";
			}else if ($rank == 3){
				echo "<a style='color:#603819;font-size:18px;'>★";
			}
	
			//Ranking
			echo "</a>". $rank .".</td>\n";
	
			//output ColdDB Name
			if($mfdv_row['name'] == 'username'){
				echo "<td><span style='color:red;'>Name Unknown</span></td>\n";
			}else{
				echo "<td> ". $mfdv_row['name'] ."</td>\n";
			}
			
			//Print Playtime broken down. Days/ Hours /Minutes
			echo "<td>" . $days ." Days ".$hours." Hours ". $minutes." Minutes </td>\n";
	
			echo "</tr>\n\n";
	
			//Breakout at 25 listed users
			if($rank >= 25){ 
				break; 
			}
	
		//Skipping invalid user
		skip:
	
	//End of While Loop & PHP for table.
	}	

}

function tableItems($mfdvq_items){
	$rank=0;
while($mfdv_row = mysqli_fetch_array($mfdvq_items)){
	$rank++;
	$quantity = number_format($mfdv_row['quantity']);
	$price=$mfdv_row['value'] / $mfdv_row['quantity'];
	$priceout = number_format($price);
	$value = number_format($mfdv_row['value']);
	//Add to rank

		echo "<tr>\n";			

		if($rank == 1){
			echo "<td><a style='color:#ffc942;font-size:18px;'>★</a>". $rank .".</td>\n";
		} else if ($rank == 2){
			echo "<td><a style='color:silver;font-size:18px;'>★</a>". $rank .".</td>\n";
		}else if ($rank == 3){
			echo "<td><a style='color:#603819;font-size:18px;'>★</a>". $rank .".</td>\n";
		} else {
			echo "<td>". $rank .".</td>\n";
		}

		echo "<td>" . $mfdv_row['name'] ."</td>\n";
		echo "<td>" . $quantity ."</td>\n";
		echo "<td>$" . $priceout ."</td>\n";
		echo "<td>$" . $value ."</td>\n";

		echo "</tr>\n\n";

		if($rank >= 25){ 
			break; 
		}
//End of While Loop & PHP for table.
}
}



function tableSeason($mfdvq_eloseason){
 								
	$rank=0;
	while($mfdv_row = mysqli_fetch_array($mfdvq_eloseason)){
		//Rank
		$rank++;
		//Calculate the users Kill Death Ratio.
		$kdr = number_format($mfdv_row['kills']/$mfdv_row['deaths'],2);
		$elo = number_format($mfdv_row['elo']);
		$kills = number_format($mfdv_row['kills']);
		$deaths = number_format($mfdv_row['deaths']);
			echo "<tr>\n";			
			echo "<td>\n"; 
	
			// GOLD / SILVER / BRONZE Stars for Top 3
			if($rank == 1){
				echo "<a style='color:#ffc942;font-size:18px;'>★";
			} else if ($rank == 2){
				echo "<a style='color:silver;font-size:18px;'>★";
			}else if ($rank == 3){
				echo "<a style='color:#603819;font-size:18px;'>★";
			}
			
			//Ranking
			echo "</a>". $rank .".</td>\n";
	
			//Output the players name, or unknown if none is found.
			if($mfdv_row['name'] == 'username'){
				echo "<td><span style='color:red;'>Name Unknown</span></td>\n";
			}else{
				echo "<td> ". $mfdv_row['name'] ."</td>\n";
			}

			
			echo "<td>". $elo ."</td>\n";
			echo "<td>". $kills ."</td>\n";
			echo "<td>". $deaths ."</td>\n";
	
			//Kill Death Ratio
			echo "<td>". $kdr ." </td>\n";
	
			echo "</tr>\n\n";
	
			//Breakout at 25 listed users
			if($rank >= 25){ 
			break; 
			}
	
	//End of While Loop & PHP for table.
	}
		
}

function tableSeason1($mfdvq_eloseason1){
 								
	$rank=0;
	while($mfdv_row = mysqli_fetch_array($mfdvq_eloseason1)){
		//Rank
		$rank++;
		//Calculate the users Kill Death Ratio.
		$kdr = number_format($mfdv_row['kills']/$mfdv_row['deaths'],2);
		$elo = number_format($mfdv_row['elo']);
		$kills = number_format($mfdv_row['kills']);
		$deaths = number_format($mfdv_row['deaths']);
			echo "<tr>\n";			
			echo "<td>\n"; 
	
			// GOLD / SILVER / BRONZE Stars for Top 3
			if($rank == 1){
				echo "<a style='color:#ffc942;font-size:18px;'>★";
			} else if ($rank == 2){
				echo "<a style='color:silver;font-size:18px;'>★";
			}else if ($rank == 3){
				echo "<a style='color:#603819;font-size:18px;'>★";
			}
			
			//Ranking
			echo "</a>". $rank .".</td>\n";
	
			//Output the players name, or unknown if none is found.
			if($mfdv_row['name'] == 'username'){
				echo "<td><span style='color:red;'>Name Unknown</span></td>\n";
			}else{
				echo "<td> ". $mfdv_row['name'] ."</td>\n";
			}
			
			echo "<td>". $elo ."</td>\n";
			echo "<td>". $kills ."</td>\n";
			echo "<td>". $deaths ."</td>\n";
	
			//Kill Death Ratio
			echo "<td>". $kdr ." </td>\n";
	
			echo "</tr>\n\n";
	
			//Breakout at 25 listed users
			if($rank >= 25){ 
			break; 
			}
	
	//End of While Loop & PHP for table.
	}
		
}


function tableGangEco($mfdvq_gangEco){
 								
	$rank=0;
	while($mfdv_row = mysqli_fetch_array($mfdvq_gangEco)){
				//Rank
				$gangid = $mfdv_row['gang_id'];
				if($gangid=="-1"){
				}else{
				$rank++;
				}
		//Handle Formatting
		$cash = number_format($mfdv_row['gang_money']);
		$bank = number_format($mfdv_row['gang_bank']);

			echo "<tr>\n";			
			echo "<td>\n"; 
	
			// GOLD / SILVER / BRONZE Stars for Top 3
			if($rank == 1){
				echo "<a style='color:#ffc942;font-size:18px;'>★";
			} else if ($rank == 2){
				echo "<a style='color:silver;font-size:18px;'>★";
			}else if ($rank == 3){
				echo "<a style='color:#603819;font-size:18px;'>★";
			}
			//Ranking
			if($rank == 0){
				echo "</a>Unranked</td>\n";
			}else{
				echo "</a>". $rank .".</td>\n";
			}
			echo "<td> ". $mfdv_row['gang_name'] ."</td>\n";
			echo "<td> $". $cash  ."</td>\n";
			echo "<td> $". $bank ."</td>\n";

			echo "</tr>\n\n";
	
			//Breakout at 25 listed gangs
			if($rank >= 25){ 
			break; 
			}
	
	//End of While Loop & PHP for table.
	}
		
}

function tablegangExperience($mfdvq_gangExperience){
 								
	$rank=0;
	while($mfdv_row = mysqli_fetch_array($mfdvq_gangExperience)){
				//Rank
				$gangid = $mfdv_row['gang_id'];
				if($gangid=="-1"){
				}else{
				$rank++;
				}
		//Handle Formatting
		$exp = number_format($mfdv_row['gang_experience']);
		$resp = number_format($mfdv_row['gang_respect']);

			echo "<tr>\n";			
			echo "<td>\n"; 
	
			// GOLD / SILVER / BRONZE Stars for Top 3
			if($rank == 1){
				echo "<a style='color:#ffc942;font-size:18px;'>★";
			} else if ($rank == 2){
				echo "<a style='color:silver;font-size:18px;'>★";
			}else if ($rank == 3){
				echo "<a style='color:#603819;font-size:18px;'>★";
			}
			//Ranking
			if($rank == 0){
				echo "</a>Unranked</td>\n";
			}else{
				echo "</a>". $rank .".</td>\n";
			}
			echo "<td> ". $mfdv_row['gang_name'] ."</td>\n";
			echo "<td> ". $exp ."</td>\n";
			echo "<td> ". $resp ."</td>\n";

			echo "</tr>\n\n";
	
			//Breakout at 25 listed gangs
			if($rank >= 25){ 
			break; 
			}
	
	//End of While Loop & PHP for table.
	}	
}

function tablegangRespect($mfdvq_gangRespect){
 								
	$rank=0;
	while($mfdv_row = mysqli_fetch_array($mfdvq_gangRespect)){
		//Rank
		$gangid = $mfdv_row['gang_id'];
		if($gangid=="-1"){
		}else{
		$rank++;
		}
		//Handle Formatting
		$exp = number_format($mfdv_row['gang_experience']);
		$resp = number_format($mfdv_row['gang_respect']);

			echo "<tr>\n";			
			echo "<td>\n"; 
	
			// GOLD / SILVER / BRONZE Stars for Top 3
			if($rank == 1){
				echo "<a style='color:#ffc942;font-size:18px;'>★";
			}else if ($rank == 2){
				echo "<a style='color:silver;font-size:18px;'>★";
			}else if ($rank == 3){
				echo "<a style='color:#603819;font-size:18px;'>★";
			}
			//Ranking
			if($rank == 0){
				echo "</a>Unranked</td>\n";
			}else{
				echo "</a>". $rank .".</td>\n";
			}
			echo "<td> ". $mfdv_row['gang_name'] ."</td>\n";
			echo "<td> ". $exp ."</td>\n";
			echo "<td> ". $resp ."</td>\n";

			echo "</tr>\n\n";
	
			//Breakout at 25 listed gangs
			if($rank >= 25){ 
			break; 
			}
	
	//End of While Loop & PHP for table.
	}
		
}
?>
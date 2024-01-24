<?php

$frontend = array();
$backend = array();

$get_1 = glob("/var/www/bachaslot.com/public/games/*");
foreach($get_1 as $k1=>$d1){
	if(is_dir($d1)){
		$exp1 = explode('/',$d1);
		$game_name = end($exp1);
		$frontend[$game_name] = $game_name;
		echo $d1 . PHP_EOL;
	}
}

// $get_2 = glob("/var/www/bachaslot.com/casino/App/Games/*");
// foreach($get_2 as $k2=>$d2){
	// if(is_dir($d2)){
		// $exp2 = explode('/',$d2);
		// $game_name = end($exp2);
		// $backend[$game_name] = $game_name;
		// echo $d2 . PHP_EOL;
	// }
// }
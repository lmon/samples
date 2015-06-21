<?php
/*
Rock Paper Scissors
http://programmingpraxis.com/2013/12/10/rock-paper-scissors/

December 10, 2013
Today’s exercise is to write an interactive rock-paper-scissors game: 
	rock blunts scissors, 
	paper wraps rock, 
	scissors cut paper.

Your task is to write a program to play rock-paper-scissors with a human player, 
keeping score as you go. When you are finished, you are welcome to read or run a 
suggested solution, or to post your own solution or discuss the exercise in the comments below.

*/
// I alwasy include this so nothing runs out of control by accident
$maxloops = 100;
// 3 hands wins, no?
$handsPlayed = 0;
// Scoreboard
$hands = array('Human'=>0,'Robot'=>0);
// possible moves
$moves = array('rock','paper','scissors');
// loopcount
$count = 0;

		echo "\n Let's Play: ROCK! PAPER! SCISSORS! \n\n";


		while( ($count < $maxloops) ){
			$count++;
			$result = '';
			// talk to player
			echo "Make your move, Human: \n";
			$handle = fopen ("php://stdin","r");
			$line = fgets($handle);
		 
			// handle input by directing to appropriate functions
			if(trim($line) == "exit") {
				    echo "Quiting!\n";
			    	exit;			
			}elseif( in_array(trim($line), $moves) ) { 
				$result = gameLogic(trim($line));
			}else{ 
				// a little help
				print "Please try again. Commands: 'exit' to exit, rock/paper/scissors \n";
			}
			// provide some feedback
			if($result!=''){
				print "Human : ". $GLOBALS['moves'][$result['HumanMove']]."/".$result['HumanMove']."\n";
				print "Robot : ". $GLOBALS['moves'][$result['RobotMove']]."/".$result['RobotMove']."\n";

				print "Winner : ". $result['handWinner'] ."\n";
				print "\tHands Played ". $GLOBALS['handsPlayed'] ."\n";
			}
			// we have a winner
			if(isset($result['status']) && $result['status'] == 'finished'){
				print "\nWinner OF MATCH : ".$result['gameWinner']." \n";
				exit;
			}
		}

	/*
	function to 
	-compute winner	
	-provide feedback values
	*/

	function gameLogic($move){
		$GLOBALS['handsPlayed']++;
		$result = array();

		$playerMove = array_search($move, $GLOBALS['moves']);
		// randomly get Bot's move
		$botMove = rand(0, count($GLOBALS['moves'])-1 ); 
		$result['HumanMove'] = $playerMove;
		$result['RobotMove'] = $botMove;

		// not a big fan of this section. need to refactor
		if( (($botMove == 0 )||($playerMove== 0) ) && (($botMove== 2) || ($playerMove== 2))) {
			$a = ($botMove==0?'Robot':'Human');
			$b = ($botMove==2?'Robot':'Human');

			$result['handWinner'] = $a; 
			$GLOBALS['hands'][$a]++; // increment the wins for this player

		}elseif($playerMove>$botMove){
			$result['handWinner'] = "Human";
			$GLOBALS['hands']["Human"]++;
		}elseif ($playerMove<$botMove) {
			$result['handWinner'] = "Robot";
			$GLOBALS['hands']["Robot"]++;
		}elseif ($playerMove==$botMove) {
			$result['handWinner'] = "None";
		}else{ // ? 
			$result['handWinner'] = "None2";
		}

		if( (getWins('Robot') == 3) || (getWins('Human')==3)) {
			$result['gameWinner'] = (getWins('Robot')==3?'Robot':'Human');
			$result['status']='finished';
		}

		return $result;
	}

	function getWins($player){
		return $GLOBALS['hands'][$player];
	}

?>

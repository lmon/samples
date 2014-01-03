<?php

	$handle = fopen ("php://stdin","r");
	$varL = fgets($handle) ;
	$arrayL = explode(' ', $varL );
	$varS = fgets($handle);
/*
aba bab abb bba
bbabbababbaabbbabbb
*/
print_r($arrayL);
print "\n";
print $varS ."\n ========== \n";


print_r(AllPermutations(array('peter', 'paul', 'mary')));

function AllPermutations($InArray, $InProcessedArray = array())
{
    $ReturnArray = array();
    foreach($InArray as $Key=>$value)
    {
        $CopyArray = $InProcessedArray;
        $CopyArray[$Key] = $value;
        $TempArray = array_diff_key($InArray, $CopyArray);
        if (count($TempArray) == 0)
        {
           print "A count=".join(",",$TempArray)."\n";
            $ReturnArray[] = $CopyArray;
        }
        else
        {
         print "B count=".join(",",$TempArray)." / ". $Key ."\n";
              $ReturnArray = array_merge($ReturnArray, AllPermutations($TempArray, $CopyArray));
        }
    }
    return $ReturnArray;
}

 //print_r(getAllCombinations($arrayL) );
print_r(getAllCombinations(array('peter', 'paul', 'mary')));

function getAllCombinations($array, $items = null){
	$retArr = array();
	
	if($items != null){
		$myarr = array();
		//print "diff2=".join(",",$array)."\n";
		foreach( $array as $i){
			$myarr[] = $i;
			print $i."\n";
		}		
		return $myarr;	
	}
	
	$childArr = array();
		for($i = 0; $i<count($array); $i++){
			print 'el: '. trim($array[$i])." \n";
			
			$temp = array(trim($array[$i]));
			$diff = array_diff($array, $temp);
			print "diff=".join(",",$diff)."\n";
			$childArr[]	= array_merge($temp, getAllCombinations($diff, $temp));
			 	
		}
	$retArr[] = $childArr;
	return $retArr;

}


/*
loop thru the items
for each item in the list, create a new array in the parent element
	for each item , append the remaining items to the list

*/


	?>

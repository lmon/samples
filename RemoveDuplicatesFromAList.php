<?php
/*
Remove Duplicates From A List
December 17, 2013
http://programmingpraxis.com/2013/12/17/remove-duplicates-from-a-list/
We have today another exercise from our infinite supply of interview questions:

Return a list containing only one copy of any duplicates in an input list, with items in the output in the same order as their first appearance in the input.

Your task is to answer the interview question given above; you must provide at least two different solutions. When you are finished, you are welcome to read or run a suggested solution, or to post your own solution or discuss the exercise in the comments below.
*/

$list = str_split('abddecbbafggiak');
$deduped = getDedupedList($list);
print join(', ',$deduped);

function getDedupedList($array){
	$newarray = array();
	foreach($array as $k=>$v){
		if(!in_array($v, $newarray)){
			array_push($newarray, $v);
		}
	}
	sort($newarray);
	return $newarray;
}

?>
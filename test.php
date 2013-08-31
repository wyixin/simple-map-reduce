<?php

include 'MapReduce.class.php';

// Begin sample code ...
// Count the odd numbers and the even numbers in a list of integers
 
$mapReduce = new MapReduce();
$mapReduce->setData(array(1, 3, 5, 7, 9, 2, 4, 6, 8));
$mapReduce->setMap(function ($x) {
    if($x % 2 == 0) {
        return array('odd' => 0, 'even' => 1);
    } else {
        return array('odd' => 1, 'even' => 0);
    }
});
$mapReduce->setReduce(function ($prevResult, $next) {
    return array(
        'odd' => $prevResult['odd'] + $next['odd'],
        'even' => $prevResult['even'] + $next['even'],
    );
});
$mapReduce->setParts(3);
 
$result = $mapReduce->run();
print_r($result);



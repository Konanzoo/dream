<pre><?php

require_once('functions.php');

//$matrix = [ //test matrix 3 X 5
//    [4,5,3,4,2],
//    [7,1,8,2,8],
//    [3,2,6,3,8],
//];

//$matrix = [ // test matrix 5 x 5
//    [0, 0, 9, 7, 8],
//    [7, 0, 0, 4, 6],
//    [2, 1, 4, 1, 7],
//    [1, 5, 4, 4, 1],
//    [8, 3, 7, 1, 6],
//];


$matrix = [ // test matrix 10 x 10
    [0,7,5,6,7,7,7,6,0,7],
    [6,4,3,9,0,6,1,2,0,5],
    [5,0,6,0,4,4,3,2,6,8],
    [3,9,7,1,3,4,5,4,9,7],
    [2,1,4,8,0,2,1,2,0,6],
    [5,5,7,7,1,3,2,8,6,6],
    [1,0,5,5,2,5,0,8,0,6],
    [3,5,7,6,3,9,2,6,1,3],
    [8,8,4,5,5,0,4,1,3,7],
    [1,6,4,4,0,5,9,2,3,3],
];

//$matrix = [ // test matrix 20 x 20
//    [17,67,19,88,21,67,74,5,35,79,52,17,83,86,13,88,56,29,1,12],
//    [60,83,37,53,23,60,95,95,8,64,86,49,79,36,53,73,13,50,73,96],
//    [68,88,5,44,91,96,67,34,45,54,86,98,28,39,76,7,50,90,67,82],
//    [64,3,16,15,56,60,14,60,64,9,33,9,74,16,29,43,65,32,46,27],
//    [95,53,95,28,61,97,52,97,67,16,19,53,73,83,84,33,46,81,95,3],
//    [74,50,9,93,79,76,14,96,71,12,65,64,59,72,33,44,96,43,72,85],
//    [17,19,19,78,66,33,67,86,27,18,14,48,31,90,48,71,10,34,84,1],
//    [87,18,69,58,59,48,46,80,19,97,69,73,90,81,59,16,17,59,62,52],
//    [4,37,15,12,97,13,13,91,63,25,39,22,81,84,97,15,53,90,5,60],
//    [86,93,81,21,98,76,48,24,41,63,4,66,36,68,65,85,31,38,81,44],
//    [46,2,74,17,57,92,57,51,6,16,63,51,70,54,85,38,88,30,67,67],
//    [40,82,52,38,47,89,13,87,44,30,79,10,6,18,80,30,55,59,65,8],
//    [15,64,44,72,36,97,61,26,81,6,61,22,42,62,45,10,55,21,49,76],
//    [18,8,17,71,92,80,61,82,44,49,93,69,17,80,95,54,56,17,1,44],
//    [28,1,53,39,38,94,24,70,68,70,33,41,94,18,94,14,51,32,19,89],
//    [53,37,90,42,89,52,17,64,79,20,35,86,31,19,7,43,52,94,79,51],
//    [34,81,88,93,42,87,91,93,61,46,44,13,85,36,96,99,50,86,82,74],
//    [96,7,88,54,22,68,96,20,14,28,99,18,57,68,14,69,51,99,14,81],
//    [33,34,5,92,31,74,81,17,59,76,78,80,38,64,76,39,29,63,86,8],
//    [71,65,4,80,60,44,62,89,40,3,38,93,67,76,8,91,36,96,1,83],
//];


$rang = rang($matrix);

echo PHP_EOL . 'the rang of matrix = ' . $rang . PHP_EOL;
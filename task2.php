<pre><?php
require_once('functions.php');

$link = dbConnect();

$maxUsersAtTimeSlot = getUsersInPeriodsOfDay($link);

ksort($maxUsersAtTimeSlot);
$maxUsersOfPeriod = searchMax($maxUsersAtTimeSlot);

viewMaxPeriod($maxUsersAtTimeSlot, $maxUsersOfPeriod);


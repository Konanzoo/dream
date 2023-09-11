<pre><?php
require_once('functions.php');

if (isset($_GET['amount_events'])) {
    $amountEvents = $_GET['amount_events'];
} else {
    $amountEvents = AMOUNT_EVENTS_GENERAATE;
}

$link = dbConnect();
createTable($link, DB_NAME);
generateData($link, $amountEvents);
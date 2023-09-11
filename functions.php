<?php

define("DB_NAME", 'test_db');
define('GOAL_DAY', 3);
define('AMOUNT_EVENTS_GENERAATE', 100);

function rang($matrix) {

    // first elem mustn't = 0
    $currentRow = 0;
    $currentCol = 0;
    $matrixHorizontalSize = count($matrix[0]);

    print_matrix($matrix);

    /** transform matrix to triangular-matrix */
    while($currentCol < $matrixHorizontalSize) {
        if ($matrix[$currentRow][$currentCol] == 0) {
            /** search notnull elem in current-col */
            foreach ($matrix as $rowIndex => $row) {
                if ($currentRow >= $rowIndex) {
                    continue;
                }
                /** replace nullable and not-nullable-elem */
                if ($matrix[$rowIndex][$currentCol] != 0) {
                    list($matrix[$rowIndex], $matrix[$currentRow]) = array($matrix[$currentRow], $matrix[$rowIndex]);
                    break;
                }
            }
        }

        /** transform to null first elem of each row */
        $rowCnt = $currentRow + 1;
        $matrixVerticalSize = count($matrix);
        while($rowCnt < $matrixVerticalSize) {
            $colCnt = $currentCol;

            if ($matrix[$rowCnt][$currentCol] != 0) {
                # coeff for trancform first elem to null and to calculate each elem of current row by product
                $coefficient = $matrix[$rowCnt][$currentCol] / $matrix[$currentRow][$currentCol];

                while ($colCnt < $matrixHorizontalSize) {
                    $matrix[$rowCnt][$colCnt] = $matrix[$rowCnt][$colCnt] - $matrix[$currentRow][$colCnt] * $coefficient;
                    $colCnt++;
                }
            }
            $rowCnt++;
        }
        $currentRow++;
        $currentCol++;
//        print_matrix($matrix);
    }

    // calculate rang
    $rang = 0;
    foreach ($matrix as $row) {
        $isNotNullableRow = false;

        foreach ($row as $col => $elem) {
            if ($elem != 0) {
                $isNotNullableRow = true;
                break;
            }
        }
        if ($isNotNullableRow) {
            $rang++;
        }
    }
    return $rang;
}

// debug function push data in output
function print_matrix($matrix) {
    foreach ($matrix as $row ) {
        foreach($row as $elem) {
            echo $elem . " ";
        }
        echo PHP_EOL;
    }
    echo PHP_EOL . PHP_EOL . PHP_EOL;
}


function dbConnect() {
    $link = mysqli_connect($_ENV["MYSQL_HOST"], $_ENV["MYSQL_USER"], $_ENV["MYSQL_PASSWORD"], $_ENV["MYSQL_DATABASE"]);

    if (mysqli_connect_errno()) {
        echo "error: %s\n" . mysqli_connect_error();
        exit();
    }

    mysqli_query($link, "SET NAMES utf8");
    return $link;
}

function createTable($link, $dbName = 'test_db')
{
    $result = mysqli_query($link, "SHOW TABLES from test_db LIKE 'Session';");
    if (mysqli_fetch_array($result)[0] == 'session') {
        return;
    }
    $sql = "CREATE TABLE `$dbName`.`Session` (
        `id` INT NOT NULL AUTO_INCREMENT ,
        `user_id` INT NOT NULL ,
        `login_time` TIMESTAMP NOT NULL ,
        `logout_time` TIMESTAMP NULL ,
        PRIMARY KEY (`id`)) ENGINE = InnoDB;";
    $result = mysqli_query($link, $sql);
}

function generateSession($link, $dbTame = 'test_db')
{
    $loginSec = time() - rand(1, (60*60*24*7));
    $logoutSec = $loginSec + rand(1, (time() - $loginSec + 60*60));

    $dateLoginTime = "'" . date("Y-m-d h:i:s", $loginSec) . "'";
    //echo '$dateLoginTime = ' . $dateLoginTime . PHP_EOL;

    if ($logoutSec <= time() && $logoutSec > $loginSec) {
        $dateLogoutTime = "'" . date("Y-m-d h:i:s", $logoutSec) . "'";
        //echo '$dateLogoutTime = ' . $dateLogoutTime . PHP_EOL;
    } else {
        $dateLogoutTime = 'NULL'; // online user
    }

    $userId = rand(1, 1024);

    $sql = "INSERT INTO `Session` (`id`, `user_id`, `login_time`, `logout_time`) VALUES (NULL, $userId, $dateLoginTime, $dateLogoutTime);";

    //echo $sql . PHP_EOL;
    $result = mysqli_query($link, $sql);

    if ($result == false) {
        echo "Error!" . PHP_EOL;
    } else {
        $id = mysqli_insert_id($link);
        echo $id . PHP_EOL;
    }
}

function generateData($link, $amountEvents = 100) {
    $cnt = 0;
    while ($cnt < $amountEvents) {
        generateSession($link);
        $cnt++;
    }
}

function getGoalDay() {

    if (isset($_POST['day']) && 0 < (integer)$_POST['day']) {
        $goalDay = $_POST['day'];
    } else {
        $goalDay = GOAL_DAY;
    }
    return $goalDay;
}

function getUsersInPeriodsOfDay($link) {
    $sql = "select * from Session where login_time > CURRENT_TIMESTAMP - INTERVAL " . getGoalDay() . " DAY AND ((logout_time < CURRENT_TIMESTAMP - INTERVAL " . (getGoalDay()-1  ) . " DAY) OR logout_time is null);";

    $result = mysqli_query($link, $sql);
    $maxUsersAtTimeSlot = [];

    // @todo  возмоно есть смысл убрать if() оставить только while()
    if ($result != false) {
        $startIndex = strtotime(date("Y-m-d", time())) - 1 - 60 * 60 * 24 * (getGoalDay() - 1);
        while(!empty($logInfo = mysqli_fetch_array($result))) {

            $startTimeInterval = $startIndex;
            $endTimeInterval = $startTimeInterval + 60 * 60 * 24;
            // debug
//            echo '$startTimeInterval = ' . $startTimeInterval;
//            echo PHP_EOL;
//            echo '$endTimeInterval = ' . $endTimeInterval;
//            echo PHP_EOL;
//            echo '$startTimeInterval = ' . date("Y-m-d h:i:s", $startTimeInterval);
//            echo PHP_EOL;
//            echo '$endTimeInterval = ' . date("Y-m-d h:i:s", $endTimeInterval);
//            echo PHP_EOL;
            $loginTimeSec = strtotime($logInfo['login_time']);
            $logoutTimeSec = (empty($logInfo['logout_time']) || $logInfo['logout_time'] == 'NULL') ? $endTimeInterval : strtotime($logInfo['logout_time']);

            for ($currentTimePointer = $startTimeInterval; $currentTimePointer <= $endTimeInterval; $currentTimePointer = $currentTimePointer + 60 * 10) {
                if ($currentTimePointer > $loginTimeSec && $currentTimePointer < $logoutTimeSec) {
                    $maxUsersAtTimeSlot[$currentTimePointer] = isset($maxUsersAtTimeSlot[$currentTimePointer]) ? $maxUsersAtTimeSlot[$currentTimePointer] + 1 : 1;
                }
                // debug
//                echo ($currentTimePointer > $loginTimeSec) ? '->' : '  ';
//                echo '$loginTimeSec =       ' . $loginTimeSec . ' ' . $logInfo['login_time'] . PHP_EOL;
//                echo '  $currentTimePointer = ' . $currentTimePointer . ' ' . date("Y-m-d h:i:s", $currentTimePointer);
//                echo PHP_EOL;
//                echo ($currentTimePointer < $logoutTimeSec) ? '->' : '  ';
//                echo '$logoutTimeSec =      ' . $logoutTimeSec . ' ' . date("Y-m-d h:i:s", $logoutTimeSec) . PHP_EOL;
//                echo PHP_EOL;

            }
//            print_r($logInfo);
//            print_r($maxUsersAtTimeSlot);
        }
    } else {
        echo "Error!" . PHP_EOL;
    }

    return $maxUsersAtTimeSlot;

}


function searchMax($maxUsersAtTimeSlot) {
    $maxUsers = 0;
    foreach ($maxUsersAtTimeSlot as $amountUsers) {
        if ($amountUsers > $maxUsers) {
            $maxUsers = $amountUsers;
        }
    }
    return $maxUsers;
}

function viewMaxPeriod($maxUsersAtTimeSlot, $max) {
    $startGoadPeriod = 0;
    $currentGoalElem = 0;
    foreach ($maxUsersAtTimeSlot as $datestamp => $amountUsers) {
        if ($startGoadPeriod == 0 && $currentGoalElem == 0) {
            echo PHP_EOL;
        }

        if ($amountUsers == $max) {
            if ($startGoadPeriod == 0) {
                $startGoadPeriod = $datestamp;
                $currentGoalElem = $startGoadPeriod + 60*10;
                echo date("Y-m-d h:i:s", $datestamp) . ' - ';
            } else {
                $currentGoalElem = $datestamp;
            }
        } elseif ($startGoadPeriod) {
            echo date("Y-m-d h:i:s", $currentGoalElem) . PHP_EOL;
            $startGoadPeriod = 0;
            $currentGoalElem = 0;
        }
    }
    echo date("Y-m-d h:i:s", $currentGoalElem);
}
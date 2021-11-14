<?php

require_once("TransactionDB.php");
require_once("Testing.php");

$test = false;
if ($test) {
    $Tests = new Testing();
    $Tests->runTests();
}
else {
    $DB = new TransactionDB();
    $DB->start();
}

?>
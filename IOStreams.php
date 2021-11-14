<?php
define("BUFFER", 1024);

// read a line from the stdin
function readline($message = null) {
    $result;
    consoleWrite($message);
    $handle = fopen("php://stdin", "r");
    if ($handle) {
        $result = fgets($handle, BUFFER);
        // trim the newline and return of the input
        $result = trim($result, "\r\n");
        fclose($handle);
    }
    else {
        // LOG
    }
    
    return $result;
}

// wrapper to mimic a console write
function consoleWrite($message) {
    $message = "console >> " . $message;
    write($message);
}

// write a line to stdout
function write($message) {
    $handle = fopen("php://stdin", "w") ;
    if ($handle) {
        fprintf($handle, "$ " . $message);
        fclose($handle);
    }
    else {
        // LOG
    }
}

// wrapper to insert a End of Line and write
function writeline($message) {
    $lastChar = substr($message, -1);
    if ($lastChar != PHP_EOL) {
        $message = $message . PHP_EOL;
    }
    write($message);
}
?>
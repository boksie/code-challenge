<?php

require_once("IOStreams.php");
require_once("Transaction.php");
require_once("Stack.php");

class TransactionDB
{
    private $dictionary;
    private Stack $transactionStack;
    private Transaction $transaction;
    private $isRunning;

    public function __construct() {
        $this->dictionary = [];
        $this->transactionStack = new Stack();
        $this->transaction = new Transaction($this->dictionary);
        $this->isRunning = true;
    }

    // Start the console loop
    public function start() {
        while ($this->isRunning)
        {
            try {
                $response = readline();
                $input = $this->parseInput($response);
                $this->runCommand($input);
            }
            catch (Exception $e) {
                writeline($e->getMessage());
            }
        }
    }

    // Parse the input, max 3 arguments per line
    private function parseInput($input) {
        $result = [];
        $offset = 0;
        // Max 3 input lines
        for ($i = 0; $i < 3; $i++) {
            // Get position of first space after offsetted with the last recorded space
            $pos = strpos($input, ' ', $offset);
            if ($pos == 0 || $i == 2 ) {
                $result[] = substr($input, $offset);
                break;
            }
            $result[] = substr($input, $offset, $pos - $offset);
            $offset = $pos + 1;
        }
        return $result;
    }


    private function runCommand($arguments) {
        switch ($arguments[0]) {
            case "GET":
                $response = $this->transaction->get($arguments[1]);
                writeline($response);
                break;
            case "SET":
                $this->transaction->set($arguments[1], $arguments[2]);
                break;
            case "DELETE":
            case "DEL":
                $deleted = $this->transaction->delete($arguments[1]);
                break;
            case "START":
                $this->transactionStack->push($this->transaction);
                $this->transaction->startTransaction($arguments[1]);
                break;
            case "COMMIT":
                $this->transaction->commit();
                break;
            case "ROLLBACK":
                $this->transaction = $this->transactionStack->pop();
                $this->transaction->rollback();
                break;
            default:
                throw new Exception("Invalid input!");
                break;
            }
    }
}
?>
<?php

require_once("IOStreams.php");
require_once("Transaction.php");
require_once("Stack.php");

class TransactionDB
{
    private Stack $transactionStack;
    private Transaction $transaction;
    private $isRunning;

    public function __construct() {
        $this->transactionStack = new Stack();
        $this->transaction = new Transaction(null, []);
    }

    public function getArray() {
        $transaction = $this->transaction;
        while ($this->transactionStack->peek()) {
            $transaction = $this->transactionStack->pop();
        };
        return $transaction->getArray();
    }

    // Start the console loop
    public function start() {
        $this->isRunning = true;
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


    public function runCommand($arguments) {
        switch ($arguments[0]) {
            case "GET":
                $response = $this->transaction->get($arguments[1]);
                writeline($response);
                break;
            case "SET":
                if (count($arguments) == 3) {
                    $this->transaction->set($arguments[1], $arguments[2]);
                }
                else {
                    throw new Exception("Invalid amount of arguments!");
                }
                break;
            case "DELETE":
            case "DEL":
                $this->transaction->delete($arguments[1]);
                break;
            case "START":
                $this->transactionStack->push($this->transaction);
                $this->transaction = new Transaction($this->transaction);
                $this->transaction->startTransaction();
                break;
            case "COMMIT":
                $this->transaction->commit();
                $this->transaction = $this->transactionStack->pop();
                break;
            case "ROLLBACK":
                $this->transaction->rollback();
                $this->transaction = $this->transactionStack->pop();
                break;
            default:
                throw new Exception("Invalid input!");
                break;
            }
    }
}
?>
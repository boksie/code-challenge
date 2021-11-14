<?php

require_once("TransactionDB.php");
require_once("IOStreams.php");

class Testing
{
    private $testDel = [
        "name" => "Deletion Test",
        "commands" => [
            ["SET", "x", 1],
            ["DEL", "x"],
        ],
        "result" => []
    ];

    private $testCommit = [
        "name" => "Commit Test",
        "commands" => [
            ["SET", "x", 1],
            ["SET", "y", 10],
            ["START"],
            ["SET", "x", 2],
            ["DEL", "y"],
            ["COMMIT"]
        ],
        "result" => ["x" => 2]
    ];

    private $testRollback = [
        "name" => "Rollback Test",
        "commands" => [
            ["SET", "x", 1],
            ["SET", "y", 10],
            ["START"],
            ["SET", "x", 2],
            ["DEL", "y"],
            ["ROLLBACK"]
        ],
        "result" => ["x" => 1, "y" => 10]
    ];

    public function runTests()
    {
        $this->runTest($this->testDel);
        $this->runTest($this->testCommit);
        $this->runTest($this->testRollback);
    }

    private function runTest($test) {
        $DB = new TransactionDB();
        foreach ($test["commands"] as $commandArgs) {
            try {
                $DB->runCommand($commandArgs);
            } catch (Exception $e) {
                writeline($e->getMessage());        
            }
        }
        if ($DB->getArray() == $test["result"]) {
            writeline($test["name"] . ": SUCCES!");
        }
        else {
            writeline($test["name"] . ": FAILED!");
        }
    }
    
}

?>
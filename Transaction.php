<?php

require_once("StorageInterface.php");

class Transaction implements StorageInterface
{
    private $array = [];
    private $started = false;
    private $dictionary;

    public function __construct($dictionary) {
        $this->dictionary = $dictionary;
    }
    /**
     * Return a stored value
     * Throws an exception when the given name does not exist
     *
     * @param string $name
     * @return mixed
     *
     * @throws \Exception
     */
    public function get(string $name) {
        if ($this->exists($name)) {
            return $this->array[$name]; 
        }
        throw new Exception("Key does not exist!");
    }

    /**
     * Save $value with the given name
     *
     * @param string $name
     * @param mixed $value
     */
    public function set(string $name, $value): void {
        $this->array[$name] = $value;
    }

    /**
     * Return true if the given value exists
     *
     * @param string $name
     * @return bool
     */
    public function exists(string $name): bool {
        return array_key_exists($name, $this->array);
    }

    /**
     * Remove a value
     *
     * @param string $name
     */
    public function delete(string $name): void {
        if ($this->exists($name)) {
            unset($this->array[$name]);
        }
    }

    /**
     * Start a new transaction
     */
    public function startTransaction(): void {
        $started = true;
    }

    /**
     * Persist all actions done in the current transaction
     * Throws an exception when there is no active transaction to commit
     *
     * @throws \Exception
     */
    public function commit(): void {
        if ($started) {
            array_merge($dictionary, $array);
        }
        throw new Exception("No active transaction!");
    }

    /**
     * Revert all actions done in the current transaction
     * Throws an exception when there is no active transaction to rollback
     *
     * @throws \Exception
     */
    public function rollback(): void {
        if ($started) {
            $started = false;
            $array = [];
        }
        throw new Exception("No active transaction!");
    }
}
?>
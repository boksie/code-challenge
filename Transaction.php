<?php

require_once("StorageInterface.php");

class Transaction implements StorageInterface
{
    private $array = [];
    private $delarray = [];
    private $active = false;
    private $parentTransaction;

    public function __construct($parentTransaction) {
        if ($parentTransaction != null) {
            $this->parentTransaction = $parentTransaction;
            foreach ($parentTransaction->getArray() as $key => $value) {
                $this->array[$key] = $value;
            }
        }
    }

    public function getArray() {
        return $this->array;
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
        throw new Exception("ERR: Cannot find a value by the name of \"{$name}\"");
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
            // Keep track of deleted items, so if committed they should also be deleted in parent
            $this->delarray[$name] = $this->array[$name];
            unset($this->array[$name]);
        }
    }

    /**
     * Start a new transaction
     */
    public function startTransaction(): void {
        $this->active = true;
    }

    /**
     * Persist all actions done in the current transaction
     * Throws an exception when there is no active transaction to commit
     *
     * @throws \Exception
     */
    public function commit(): void {
        if ($this->active) {
            foreach ($this->array as $key => $value) {
                $this->parentTransaction->set($key, $value);
            }
            foreach ($this->delarray as $key => $value) {
                $this->parentTransaction->delete($key);
            }
        } 
        else {
            throw new Exception("No active transaction!");
        }
    }

    /**
     * Revert all actions done in the current transaction
     * Throws an exception when there is no active transaction to rollback
     *
     * @throws \Exception
     */
    public function rollback(): void {
        if ($this->active) {
            $this->active = false;
            $this->array = [];
            $this->delarray = [];
        }
        else {
            throw new Exception("No active transaction!");
        }
    }
}
?>
<?php
class Stack
{
    private ?Node $head = null;
    private int $size = 0;

    // return last enterd item on the stack
    public function pop() {
        if ($this->head == null) {
            return null;
        }
        $result = $this->head;
        if ($this->head != null) {
            $this->head = $this->head->next;
        }
        $this->size--;
        return $result->value;
    }

    // push item on the stack
    public function push($value) {
        $previousNode = $this->head;
        $this->head = new Node($value);
        $this->head->next = $previousNode;
        $this->size++;
    }

    public function peek() {
        if ($this->head == null) {
            return null;
        }
        return $result->value;
    }

    public function getSize() {
        return $this->size;
    }
}

class Node 
{
    public $value;
    public ?Node $next = null;

    public function __construct($value) {
        $this->value = $value;
    }
}
?>
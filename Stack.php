<?php
class Stack
{
    private ?Node $head = null;

    // return last enterd item on the stack
    public function pop() {
        if ($this->head == null) {
            return null;
        }
        $result = $this->head;
        if ($this->head != null) {
            $this->head = $this->head->next;
        }
        return $result->value;
    }

    // push item on the stack
    public function push($value) {
        $previousNode = $this->head;
        $this->head = new Node($value);
        $this->head->next = $previousNode;
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
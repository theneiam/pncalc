<?php

/**
 *
 */

namespace Calcs;

class PrefixNotation
{
    /**
     * @var null|\SplStack
     */
    protected $stack = null;

    /**
     * @var array
     */
    protected $operations = [];

    public function __construct()
    {
        $this->stack = new \SplStack();
        $this->operations = $this->loadBuiltInOperations();
    }

    /**
     * Extend calculator by adding custom operations
     *
     * @param string $name
     * @param \Closure $implementation
     */
    public function addOperation($name, $implementation)
    {
        $this->operations[$name] = $implementation;
    }

    /**
     * @param $expression
     * @return mixed
     */
    protected function filterExpression($expression)
    {
        return trim(preg_replace(['/[()]+/', '/\s{2,}+/'], ['', ' '], $expression));
    }

    /**
     * Load built-in operations for calculator
     *
     * @return array
     */
    protected function loadBuiltInOperations()
    {
        return [
            '+' => function($a, $b) { return $a + $b; },
            '-' => function($a, $b) { return $a - $b; },
            '*' => function($a, $b) { return $a * $b; },
            '/' => function($a, $b) { return $a / $b; }
        ];
    }

    /**
     * Calculate expression in prefix notation, such as + 2 3 = 5
     *
     * @param string $expression
     * @return int
     */
    public function calculate($expression)
    {
        $expression = explode(' ', $this->filterExpression($expression));
        for ($i = (count($expression) - 1); $i >= 0; $i--) {
            $char = $expression[$i];
            if (isset($this->operations[$char])) {
                $operand1 = $this->stack->pop();
                $operand2 = $this->stack->pop();
                $operation = $this->operations[$char];
                $result = $operation($operand1, $operand2);
                $this->stack->push($result);
            } else {
                $this->stack->push($char);
            }
        }

        return $this->stack->top();
    }
}

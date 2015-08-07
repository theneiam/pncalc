<?php

/**
 * Prefix (Polish) notation calculator
 *
 * @author Eugene Nezhuta <eugene.nezhuta@gmail.com>
 */

namespace Calcs;

class PrefixNotation
{
    /**
     * Stack to store operands and operators
     *
     * @var null|\SplStack
     */
    protected $stack = null;

    /**
     * List of operations calculator can handle
     *
     * @var array
     */
    protected $operations = [];

    /**
     * Init stack, load built-in operations
     *
     * @see PrefixNotation::loadBuiltInOperations
     */
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
     * Tests the ability of the calc to perform particular operation
     *
     * @param string $operationName
     * @return bool
     */
    public function can($operationName)
    {
        return isset($this->operations[$operationName]);
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
        // at first filter expression from unneeded symbols, etc...
        $filteredExpression = $this->filterExpression($expression);
        // explode expression to an array using space as a delimiter
        $explodedExpression = explode(' ', $filteredExpression);
        $expressionLength = count($explodedExpression);

        for ($i = ($expressionLength - 1); $i >= 0; $i--) {
            $char = $explodedExpression[$i];
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

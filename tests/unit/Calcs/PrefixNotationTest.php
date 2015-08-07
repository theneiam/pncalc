<?php

/**
 *
 */
class PrefixNotationTest extends PHPUnit_Framework_TestCase
{
    protected $calculator = null;

    public function setUp()
    {
        $this->calculator = new \Calcs\PrefixNotation();
    }

    /**
     * Run calculation tests
     *
     * @param string $expression
     * @param float $expected
     * @dataProvider provideTestsData
     */
    public function testCalculation($expression, $expected)
    {
        $result = $this->calculator->calculate($expression);
        $this->assertEquals($expected, $result);
    }

    public function provideTestsData()
    {
        return [
            // add tests
            ['+ 2 3', 5],
            ['+ 10   12', 22],
            ['  +   (( + 4 9 )( + 2 12 ))', 27],

            // sub tests
            ['- 10 3', 7],
            ['- 112   12', 100],
            ['  -   (( - 9 3 )( - 27  7))', -14],

            // mul tests
            ['* 10 3', 30],
            ['* 12 12', 144],
            ['* (( * 2 3 )( * 6 2))', 72]
        ];
    }
}

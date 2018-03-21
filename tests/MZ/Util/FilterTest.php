<?php
namespace MZ\Util;

class FilterTest extends \PHPUnit_Framework_TestCase
{
    public function testConcatKeys()
    {
        $this->assertEquals(
            ['pre.b' => null, 'pre.a' => 3, 'pre.c' => 0],
            Filter::concatKeys('pre.', ['b' => null, 'a' => 3, 'c' => 0])
        );
        $this->assertEquals(
            ['b.pos' => null, 'a.pos' => 3, 'c.pos' => 0],
            Filter::concatKeys('', ['b' => null, 'a' => 3, 'c' => 0], '.pos')
        );
        $this->assertEquals(
            ['pre.b.pos' => null, 'pre.a.pos' => 3, 'pre.c.pos' => 0],
            Filter::concatKeys('pre.', ['b' => null, 'a' => 3, 'c' => 0], '.pos')
        );
    }

    public function testKeys()
    {
        $this->assertEquals(
            ['b' => null, 'c' => 0],
            Filter::keys(
                ['b' => null, 'a' => 3, 'c' => 0],
                ['c' => true, 'b' => true]
            )
        );
    }

    public function testOrder()
    {
        $this->assertEquals(
            ['b' => -1, 'c' => 1, 'a' => 0],
            Filter::order('b:desc,c:asc,a')
        );
        $this->assertEquals(
            ['b' => -1, 'c' => 1],
            Filter::order(['b' => -1, 'c' => 1])
        );
        $this->assertEquals(
            [],
            Filter::order('')
        );
    }
}

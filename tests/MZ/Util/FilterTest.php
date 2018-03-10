<?php
namespace MZ\Util;

class FilterTest extends \PHPUnit_Framework_TestCase
{
    public function testConcatKeys()
    {
        $this->assertEquals(
            array('pre.b' => null, 'pre.a' => 3, 'pre.c' => 0),
            Filter::concatKeys('pre.', array('b' => null, 'a' => 3, 'c' => 0))
        );
        $this->assertEquals(
            array('b.pos' => null, 'a.pos' => 3, 'c.pos' => 0),
            Filter::concatKeys('', array('b' => null, 'a' => 3, 'c' => 0), '.pos')
        );
        $this->assertEquals(
            array('pre.b.pos' => null, 'pre.a.pos' => 3, 'pre.c.pos' => 0),
            Filter::concatKeys('pre.', array('b' => null, 'a' => 3, 'c' => 0), '.pos')
        );
    }

    public function testKeys()
    {
        $this->assertEquals(
            array('b' => null, 'c' => 0),
            Filter::keys(
                array('b' => null, 'a' => 3, 'c' => 0),
                array('c' => true, 'b' => true)
            )
        );
    }

    public function testOrder()
    {
        $this->assertEquals(
            array('b' => -1, 'c' => 1, 'a' => 0),
            Filter::order('b:desc,c:asc,a')
        );
        $this->assertEquals(
            array('b' => -1, 'c' => 1),
            Filter::order(array('b' => -1, 'c' => 1))
        );
        $this->assertEquals(
            array(),
            Filter::order('')
        );
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: WytheHuang
 * Date: 2019/12/24
 * Time: 0:00
 */

declare(strict_types=1);
namespace Wythe\Redis\Chapter1\tests;

use PHPUnit\Framework\TestCase;
use Wythe\Redis\Chapter1\src\Counter;
use Wythe\Redis\Client;

class CounterTest extends TestCase
{
    public function testInvoke()
    {
        $client = new Client();
        $counter = new Counter($client, 'counter::page_view');
        $this->assertInstanceOf(Counter::class, $counter);
        return $counter;
    }

    /**
     * @depends  testInvoke
     * @param \Wythe\Redis\Chapter1\src\Counter $counter
     */
    public function testIncrease(Counter $counter)
    {
        $expected = 10;
        $this->assertEquals($expected, $counter->increase(10));
    }

    /**
     * @depends testInvoke
     * @param \Wythe\Redis\Chapter1\src\Counter $counter
     */
    public function testDecrease(Counter $counter)
    {
        $expected = 5;
        $this->assertEquals($expected, $counter->decrease(5));
    }

    /**
     * @depends testInvoke
     * @param \Wythe\Redis\Chapter1\src\Counter $counter
     */
    public function testReset(Counter $counter)
    {
        $expected = 5;
        $this->assertEquals($expected, $counter->reset());
    }

    /**
     * @depends testInvoke
     * @param \Wythe\Redis\Chapter1\src\Counter $counter
     */
    public function testGet(Counter $counter)
    {
        $expected = 0;
        $this->assertEquals($expected, $counter->get());
    }
}
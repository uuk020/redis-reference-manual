<?php
/**
 * Created by PhpStorm.
 * User: WytheHuang
 * Date: 2019/12/25
 * Time: 22:34
 */

declare(strict_types = 1);
namespace Wythe\Redis\Chapter2\tests;

use PHPUnit\Framework\TestCase;
use Wythe\Redis\Chapter2\src\Counter;
use Wythe\Redis\Client;

class CounterTest extends TestCase
{
    public function testInvoke()
    {
        $client = new Client();
        $counter = new Counter($client, 'page_view_counters', '/user/wythe');
        $this->assertInstanceOf(Counter::class, $counter);
        return $counter;
    }

    /**
     * @depends testInvoke
     * @param \Wythe\Redis\Chapter2\src\Counter $counter
     */
    public function testIncrease(Counter $counter)
    {
        $expected = 10;
        $this->assertEquals($expected, $counter->increase(10));
    }

    /**
     * @depends testInvoke
     * @param \Wythe\Redis\Chapter2\src\Counter $counter
     */
    public function testDecrease(Counter $counter)
    {
        $expected = 5;
        $this->assertEquals($expected, $counter->decrease(5));
    }

    /**
     * @depends testInvoke
     * @param \Wythe\Redis\Chapter2\src\Counter $counter
     */
    public function testGet(Counter $counter)
    {
        $expected = 5;
        $this->assertEquals($expected, $counter->get());
    }

    /**
     * @depends testInvoke
     * @param \Wythe\Redis\Chapter2\src\Counter $counter
     */
    public function testReset(Counter $counter)
    {
        $counter->reset();
        $client = new Client();
        $expected = 0;
        $value = $client->handler()->hGet('page_view_counters', '/user/wythe');
        $this->assertEquals($expected, $value);
    }
}

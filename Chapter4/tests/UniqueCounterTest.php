<?php
/**
 * Created by PhpStorm.
 * User: WytheHuang
 * Date: 2020/4/15
 * Time: 22:50
 */

declare(strict_types=1);
namespace Wythe\Redis\Chapter4\tests;

use PHPUnit\Framework\TestCase;
use Wythe\Redis\Chapter4\src\UniqueCounter;
use Wythe\Redis\Client;

class UniqueCounterTest extends TestCase
{
    public function testInvoke()
    {
        $client = new Client();
        $uniqueCounter = new UniqueCounter($client, 'ip');
        $this->assertInstanceOf(UniqueCounter::class, $uniqueCounter);
        return $uniqueCounter;
    }

    /**
     * @depends testInvoke
     * @param \Wythe\Redis\Chapter4\src\UniqueCounter $counter
     */
    public function testAdd(UniqueCounter $counter)
    {
        $this->assertEquals(true, $counter->countIn('8.8.8.8'));
        $this->assertEquals(true, $counter->countIn('9.9.9.9'));
        $this->assertEquals(false, $counter->countIn('8.8.8.8'));
    }

    /**
     * @depends testInvoke
     * @param \Wythe\Redis\Chapter4\src\UniqueCounter $counter
     */
    public function testResult(UniqueCounter $counter)
    {
        $this->assertEquals(2, $counter->getResult());
    }
}

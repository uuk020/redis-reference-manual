<?php
/**
 * Created by PhpStorm.
 * User: WytheHuang
 * Date: 2019/12/24
 * Time: 0:11
 */

declare(strict_types = 1);
namespace Wythe\Redis\Chapter1\tests;

use PHPUnit\Framework\TestCase;
use Wythe\Redis\Chapter1\src\Limiter;
use Wythe\Redis\Client;

class LimiterTest extends TestCase
{
    public function testInvoke()
    {
        $client = new Client();
        $limiter = new Limiter($client, 'wrong_password_limiter');
        $this->assertInstanceOf(Limiter::class, $limiter);
        return $limiter;
    }

    /**
     * @depends testInvoke
     * @param \Wythe\Redis\Chapter1\src\Limiter $limiter
     */
    public function testSetMaxExecuteTime(Limiter $limiter)
    {
       $this->assertTrue($limiter->setMaxExecuteTimes(10));
    }

    /**
     * @depends testInvoke
     * @param \Wythe\Redis\Chapter1\src\Limiter $limiter
     */
    public function testStillValidToExecute(Limiter $limiter)
    {
        $this->assertTrue($limiter->stillValidToExecute());
    }

    /**
     * @depends testInvoke
     * @param \Wythe\Redis\Chapter1\src\Limiter $limiter
     */
    public function testRemainingExecuteTimes(Limiter $limiter)
    {
        $expected = 9;
        $this->assertEquals($expected, $limiter->remainingExecuteTimes());
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: WytheHuang
 * Date: 2019/12/16
 * Time: 22:35
 */

namespace Wythe\Redis\Chapter1\Tests;

use PHPUnit\Framework\TestCase;
use Wythe\Redis\Chapter1\Src\Lock;
use Wythe\Redis\Client;

class LockTest extends TestCase
{
    public function testInvoke()
    {
        $redis = new Client();
        $lockObject = new Lock($redis, 'lock');
        $this->assertInstanceOf(Lock::class, $lockObject);
        return $lockObject;
    }

    /**
     * @depends testInvoke
     * @param \Wythe\Redis\Chapter1\Src\Lock $lock
     */
     public function testAcquire(Lock $lock)
     {
        $this->assertTrue($lock->acquire());
     }

    /**
     * @depends testInvoke
     * @param \Wythe\Redis\Chapter1\Src\Lock $lock
     */
    public function testRelease(Lock $lock)
    {
        $this->assertTrue($lock->release());
    }
}

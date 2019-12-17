<?php
/**
 * Created by PhpStorm.
 * User: WytheHuang
 * Date: 2019/12/17
 * Time: 22:36
 */

namespace Wythe\Redis\Chapter1\Tests;
use PHPUnit\Framework\TestCase;
use Wythe\Redis\Chapter1\Src\Cache;

class CacheTest extends TestCase
{
    public function testInvoke()
    {
        $redis = new \Redis();
        $redis->connect('127.0.0.1', 6379);
        $cacheObject = new Cache($redis);
        $this->assertInstanceOf(Cache::class, $cacheObject);
        return $cacheObject;
    }

    /**
     * @depends testInvoke
     * @param \Wythe\Redis\Chapter1\Src\Cache $cache
     */
    public function testSetCache(Cache $cache)
    {
        $cache->set('test', 1);
        $this->assertEquals(1, $cache->get('test'));
    }

    /**
     * @depends testInvoke
     * @param \Wythe\Redis\Chapter1\Src\Cache $cache
     */
    public function testUpdate(Cache $cache)
    {
       $value = $cache->update('test', 2);
       $this->assertEquals(1, $value);
       $this->assertEquals(2, $cache->get('test'));
    }
}

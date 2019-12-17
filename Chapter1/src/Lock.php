<?php
/**
 * Created by PhpStorm.
 * User: WytheHuang
 * Date: 2019/12/13
 * Time: 23:32
 */

namespace Wythe\Redis\Chapter1\Src;

/**
 * Class Lock
 * @package wythe\redis\Chapter1\Src
 */
class Lock
{
    /**
     * @var string
     */
    const VALUE_OF_LOCK = 'locking';

    /**
     * @var
     */
    private $key;

    /**
     * @var \Redis
     */
    private $redisClient;

    /**
     * Lock constructor.
     * @param \Redis $redis
     * @param        $key
     */
    public function __construct(\Redis $redis, $key)
    {
        $this->redisClient = $redis;
        $this->key = $key;
    }

    /**
     * 加锁, set 参数 nx 在没有值才能设置值, 否则false
     * @return bool
     */
    public function acquire()
    {
        $result = $this->redisClient->set(self::VALUE_OF_LOCK, $this->key, ["nx"]);
        return $result === true;
    }

    /**
     * 释放锁, del 返回删除的个数
     * @return bool
     */
    public function release()
    {
        return $this->redisClient->del(self::VALUE_OF_LOCK) === 1;
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: WytheHuang
 * Date: 2019/12/13
 * Time: 23:32
 */

namespace Wythe\Redis\Chapter1\Src;

use Wythe\Redis\Client;

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
     * @var string
     */
    private $key;

    /**
     * redis
     * @var Client
     */
    protected $client;

    /**
     * Lock constructor.
     * @param \Wythe\Redis\Client $client
     * @param        $key
     */
    public function __construct(Client $client, $key)
    {
        $this->client = $client;
        $this->key = $key;
    }

    /**
     * 加锁, set 参数 nx 在没有值才能设置值, 否则false
     * @return bool
     */
    public function acquire()
    {
        $result = $this->client->handler()->set(self::VALUE_OF_LOCK, $this->key, ["nx"]);
        return $result === true;
    }

    /**
     * 释放锁, del 返回删除的个数
     * @return bool
     */
    public function release()
    {
        return $this->client->handler()->del(self::VALUE_OF_LOCK) === 1;
    }
}
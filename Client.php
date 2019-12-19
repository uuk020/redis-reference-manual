<?php
/**
 * Created by PhpStorm.
 * User: WytheHuang
 * Date: 2019/12/19
 * Time: 23:57
 */

namespace Wythe\Redis;


class Client
{
    /**
     * redis
     * @var \Redis
     */
    protected $redisClient;

    public function __construct()
    {
        $this->redisClient = new \Redis();
        $this->redisClient->connect('127.0.0.1', 6379);
    }

    /**
     * @return \Redis
     */
    public function handler()
    {
        return $this->redisClient;
    }
}
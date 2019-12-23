<?php
/**
 * Created by PhpStorm.
 * User: WytheHuang
 * Date: 2019/12/23
 * Time: 22:55
 */

namespace Wythe\Redis\Chapter1\src;


use Wythe\Redis\Client;

/**
 * Class IdGenerator
 * @package Wythe\Redis\Chapter1\src
 */
class IdGenerator
{
    /**
     * @var \Wythe\Redis\Client
     */
    private $client;

    /**
     * @var string
     */
    private $key;

    /**
     * IdGenerator constructor.
     * @param \Wythe\Redis\Client $client
     * @param string              $key
     */
    public function __construct(Client $client, string $key)
    {
        $this->key = $key;
        $this->client = $client;
    }

    /**
     * 生成ID
     * @return int
     */
    public function produce(): int
    {
        return $this->client->handler()->incr($this->key);
    }

    /**
     * 保留前n的ID
     * @param int $n
     * @return bool
     */
    public function reserve(int $n)
    {
        return $this->client->handler()->set($this->key, $n, ['nx']);
    }
}
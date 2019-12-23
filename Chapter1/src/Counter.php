<?php
/**
 * Created by PhpStorm.
 * User: WytheHuang
 * Date: 2019/12/23
 * Time: 23:23
 */
declare(strict_types=1);
namespace Wythe\Redis\Chapter1\src;

use Wythe\Redis\Client;

/**
 * Class Counter
 * @package Wythe\Redis\Chapter1\src
 */
class Counter
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
     * Counter constructor.
     * @param \Wythe\Redis\Client $client
     * @param string              $key
     */
    public function __construct(Client $client, string $key)
    {
        $this->client = $client;
        $this->key = $key;
    }

    /**
     * 计数器加n
     * @param int $n
     * @return int
     */
    public function increase(int $n = 1)
    {
        return $this->client->handler()->incrBy($this->key, $n);
    }

    /**
     * 计数器减n
     * @param int $n
     * @return int
     */
    public function decrease(int $n)
    {
        return $this->client->handler()->decrBy($this->key, $n);
    }

    /**
     * 获取当前计数器的值
     * @return int
     */
    public function get(): int
    {
        $value = $this->client->handler()->get($this->key);
        if ($value === false) {
            return 0;
        } else {
            return (int)$value;
        }
    }

    /**
     * 重置计数器
     * @return int
     */
    public function reset(): int
    {
        $oldValue = $this->client->handler()->getSet($this->key, 0);
        if ($oldValue === false) {
            return 0;
        } else {
            return (int)$oldValue;
        }
    }
}
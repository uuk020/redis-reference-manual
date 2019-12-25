<?php
/**
 * Created by PhpStorm.
 * User: WytheHuang
 * Date: 2019/12/25
 * Time: 22:26
 */

declare(strict_types = 1);
namespace Wythe\Redis\Chapter2\src;

use Wythe\Redis\Client;

class Counter
{
    private $hashKey;

    private $client;

    private $counterName;

    public function __construct(Client $client, string $hashKey, string $counterName)
    {
        $this->client = $client;
        $this->hashKey = $hashKey;
        $this->counterName = $counterName;
    }

    /**
     * 计数器加上n
     * @param int $n
     * @return int
     */
    public function increase(int $n = 1): int
    {
        return $this->client->handler()->hIncrBy($this->hashKey, $this->counterName, $n);
    }

    /**
     * 计数器减去n
     * @param int $n
     * @return int
     */
    public function decrease(int $n = 1): int
    {
        return $this->client->handler()->hIncrBy($this->hashKey, $this->counterName, -$n);
    }

    /**
     * 返回计数器的当前值
     * @return int
     */
    public function get(): int
    {
        $value = $this->client->handler()->hGet($this->hashKey, $this->counterName);
        if ($value === false) {
            return 0;
        }
        return (int)$value;
    }

    /**
     * 重置
     */
    public function reset()
    {
        $this->client->handler()->hSet($this->hashKey, $this->counterName, 0);
    }
}
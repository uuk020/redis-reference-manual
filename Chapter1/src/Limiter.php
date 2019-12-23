<?php
/**
 * Created by PhpStorm.
 * User: WytheHuang
 * Date: 2019/12/23
 * Time: 23:41
 */

declare(strict_types=1);
namespace Wythe\Redis\Chapter1\src;


use Wythe\Redis\Client;

/**
 * Class Limiter
 * @package Wythe\Redis\Chapter1\src
 */
class Limiter
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
     * Limiter constructor.
     * @param \Wythe\Redis\Client $client
     * @param string              $key
     */
    public function __construct(Client $client, string $key)
    {
        $this->client = $client;
        $this->key = $key;
    }

    /**
     * 设置最大执行次数
     * @param int $maxExecuteTimes
     * @return bool
     */
    public function setMaxExecuteTimes(int $maxExecuteTimes): bool
    {
        return $this->client->handler()->set($this->key, $maxExecuteTimes);
    }

    /**
     * 是否可以执行被限制的操作
     * @return bool
     */
    public function stillValidToExecute()
    {
        $num = $this->client->handler()->decr($this->key);
        return ($num >= 0);
    }

    /**
     * 获取剩余可执行次数
     * @return int
     */
    public function remainingExecuteTimes()
    {
        $num = $this->client->handler()->get($this->key);
        if ($num < 0) {
            return 0;
        } else {
            return (int)$num;
        }
    }
}
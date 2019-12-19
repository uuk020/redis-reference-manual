<?php
/**
 * Created by PhpStorm.
 * User: WytheHuang
 * Date: 2019/12/13
 * Time: 23:17
 */

namespace Wythe\Redis\Chapter1\Src;

use Wythe\Redis\Client;

/**
 * redis 示列缓存
 * @package wythe\redis\Chapter1\Src
 */
class Cache
{
    /**
     * redis 实例
     * @var \Wythe\Redis\Client
     */
    private $client;

    /**
     * Cache constructor.
     * @param \Wythe\Redis\Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * 设置缓存, 若有存在值,则覆盖
     * @param $key
     * @param $value
     */
    public function set($key, $value)
    {
        $this->client->handler()->set($key, $value);
    }

    /**
     * 获取缓存
     * @param $key
     * @return bool|mixed|string
     */
    public function get($key)
    {
        return $this->client->handler()->get($key);
    }

    /**
     * 对已有缓存更新, 并且返回之前缓存的值
     * @param $key
     * @param $newValue
     * @return mixed|string
     */
    public function update($key, $newValue)
    {
        return $this->client->handler()->getSet($key, $newValue);
    }
}
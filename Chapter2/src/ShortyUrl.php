<?php
/**
 * Created by PhpStorm.
 * User: WytheHuang
 * Date: 2019/12/24
 * Time: 23:24
 */

declare(strict_types = 1);
namespace Wythe\Redis\Chapter2\src;


use Wythe\Redis\Client;

/**
 * Class ShortyUrl
 * @package Wythe\Redis\Chapter2\src
 */
class ShortyUrl
{
    /**
     * ID 统计
     * @var string
     */
    const ID_COUNTER = 'ShortyUrl::id_counter';

    /**
     * 散列键
     * @var string
     */
    const URL_HASH = 'ShortyUrl::url_hash';

    /**
     * @var \Wythe\Redis\Client
     */
    private $client;

    /**
     * ShortyUrl constructor.
     * @param \Wythe\Redis\Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param int $number
     * @return string
     */
    private function base36(int $number): string
    {
        $alphabets = '0123456789ABCDEFGHIJKMNOPQRSTUVWXYZ';
        $result = '';
        while ($number !== 0) {
            $i = $number % 36;
            $number = intval($number / 36);
            $result = $alphabets[$i] . $result;
        }
        if ($result !== '') {
            return $result;
        }
        return $alphabets[0];
    }

    /**
     * 创建并存储短网址ID
     * @param string $targetUrl
     * @return string
     */
    public function shorten(string $targetUrl): string
    {
        $newId = $this->client->handler()->incr(self::ID_COUNTER);
        $shortId = $this->base36($newId);
        $this->client->handler()->hSet(self::URL_HASH, $shortId, $targetUrl);
        return $shortId;
    }

    /**
     * 根据短网址ID， 返回对应目标网站
     * @param string $shortId
     * @return string
     */
    public function restore(string $shortId): string
    {
        return $this->client->handler()->hGet(self::URL_HASH, $shortId);
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: WytheHuang
 * Date: 2020/4/15
 * Time: 22:31
 */
declare(strict_types=1);
namespace Wythe\Redis\Chapter4\src;


use Wythe\Redis\Client;

class UniqueCounter
{
    private $client;

    private $key;

    public function __construct(Client $client, string $key)
    {
        $this->client = $client;
        $this->key = $key;
    }

    public function countIn($item)
    {
        return $this->client->handler()->sAdd($this->key, $item) == 1;
    }

    public function getResult()
    {
        return $this->client->handler()->sCard($this->key);
    }
}
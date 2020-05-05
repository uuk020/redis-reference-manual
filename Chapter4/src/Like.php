<?php
/**
 * Created by PhpStorm.
 * User: WytheHuang
 * Date: 2020/4/22
 * Time: 23:23
 */

declare(strict_types=1);
namespace Wythe\Redis\Chapter4\src;


use Wythe\Redis\Client;

class Like
{
    /**
     * @var \Wythe\Redis\Client
     */
    private $client;

    /**
     * @var string
     */
    private $key;

    public function __construct(Client $client, string $key)
    {
        $this->client = $client;
        $this->key = $client;
    }

    public function cast(int $userId)
    {
        return $this->client->handler()->sAdd($this->key, $userId) == 1;
    }


}
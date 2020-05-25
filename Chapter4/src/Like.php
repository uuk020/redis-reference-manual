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

    public function cast(int $userId): bool
    {
        return $this->client->handler()->sAdd($this->key, $userId) == 1;
    }

    public function undo(int $userId)
    {
        $this->client->handler()->sRem($this->key, $userId);
    }

    public function isLiked(int $userId): bool
    {
        return $this->client->handler()->sIsMember($this->key, $userId);
    }

    public function getAllLikedUsers(): array
    {
        return $this->client->handler()->sMembers($this->key);
    }

    public function count(): int
    {
        return $this->client->handler()->sCard($this->key);
    }
}
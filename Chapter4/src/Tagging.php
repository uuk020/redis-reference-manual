<?php
/**
 * Created by PhpStorm.
 * User: WytheHuang
 * Date: 2020/4/15
 * Time: 23:04
 */

declare(strict_types=1);
namespace Wythe\Redis\Chapter4\src;


use Wythe\Redis\Client;

class Tagging
{
    private $client;

    private $key;

    public function __construct(Client $client, string $item)
    {
        $this->key = $item . "::tags";
        $this->client = $client;
    }

    public function add(...$tags)
    {
        $this->client->handler()->sAdd($this->key, ...$tags);
    }

    public function remove(...$tags)
    {
        $this->client->handler()->sRem($this->key, ...$tags);
    }

    public function isIncluded($tag)
    {
        return $this->client->handler()->sIsMember($this->key, $tag);
    }

    public function getAllTags()
    {
        return $this->client->handler()->sMembers($this->key);
    }

    public function count()
    {
        return $this->client->handler()->sCard($this->key);
    }
}
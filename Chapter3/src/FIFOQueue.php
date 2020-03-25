<?php
/**
 * Created by PhpStorm.
 * User: WytheHuang
 * Date: 2020/3/17
 * Time: 22:28
 */

declare(strict_types=1);
namespace Wythe\Redis\Chapter3\src;

use Wythe\Redis\Client;

class FIFOQueue
{
    private $key = '';

    private $client = null;

    public function __construct(Client $client, string $key)
    {
        $this->client = $client;
        $this->key = $key;
    }

    public function enqueue($item)
    {
        return $this->client->handler()->rPush($this->key, $item);
    }

    public function dequeue()
    {
        return $this->client->handler()->lPop($this->key);
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: WytheHuang
 * Date: 2019/12/19
 * Time: 23:51
 */

namespace Wythe\Redis\Chapter1\src;


use Wythe\Redis\Client;

class Log
{
    const LOG_SEPARATOR = "\n";

    private $key;

    private $client;

    public function __construct(Client $client, string $key)
    {
        $this->client = $client;
        $this->key = $key;
    }

    public function add(string $newLog)
    {
        $newLog .= self::LOG_SEPARATOR;
        $this->client->handler()->append($this->key, $newLog);
    }

    public function getAll(): array
    {
        $allLogs = $this->client->handler()->get($this->key);
        if ($allLogs) {
           return array_filter(explode("\n", $allLogs));
        } else {
            return [];
        }
    }
}
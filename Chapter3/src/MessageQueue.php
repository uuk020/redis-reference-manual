<?php
/**
 * Created by PhpStorm.
 * User: WytheHuang
 * Date: 2020/3/25
 * Time: 22:16
 */

declare(strict_types=1);
namespace Wythe\Redis\Chapter3\src;


use Wythe\Redis\Client;

class MessageQueue
{
    private $queueName = '';

    private $client = null;

    public function __construct(Client $client, string $queueName)
    {
        $this->queueName = $queueName;
        $this->client = $client;
    }

    public function addMessage(string $message)
    {
        $this->client->handler()->lPush($this->queueName, $message);
    }

    public function getMessage(int $timeout = 0)
    {
        $result = $this->client->handler()->blPop($this->queueName, $timeout);
        if (!empty($result)) {
            list(, $item) = $result;
            return $item;
        }
        return '';
    }

    public function len()
    {
        return $this->client->handler()->lLen($this->queueName);
    }
}
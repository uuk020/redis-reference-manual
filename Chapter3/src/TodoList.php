<?php
/**
 * Created by PhpStorm.
 * User: WytheHuang
 * Date: 2020/3/24
 * Time: 22:20
 */

declare(strict_types=1);
namespace Wythe\Redis\Chapter3\src;


use Wythe\Redis\Client;

class TodoList
{
    private $todoList = '';

    private $doneList = '';

    private $client = null;

    public function __construct(Client $client, int $userId)
    {
        $this->todoList = $this->makeTodoListKey($userId);
        $this->doneList = $this->makeDoneListKey($userId);
        $this->client = $client;
    }

    private function makeTodoListKey(int $userId)
    {
        return $userId . "::todo_list";
    }

    private function makeDoneListKey(int $userId)
    {
        return $userId . "::done_list";
    }

    public function add(string $event)
    {
        $this->client->handler()->lPush($this->todoList, $event);
    }

    public function remove(string $event)
    {
        $this->client->handler()->lRem($this->todoList, $event, 0);
    }

    public function done(string $event)
    {
        $this->remove($event);
        $this->client->handler()->lPush($this->doneList, $event);
    }

    public function showTodoList(): array
    {
        return $this->client->handler()->lRange($this->todoList, 0, -1);
    }

    public function showDoneList(): array
    {
        return $this->client->handler()->lRange($this->doneList, 0, -1);
    }
}
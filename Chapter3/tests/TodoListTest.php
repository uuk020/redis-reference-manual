<?php
/**
 * Created by PhpStorm.
 * User: WytheHuang
 * Date: 2020/3/24
 * Time: 22:36
 */

namespace Wythe\Redis\Chapter3\tests;

use PHPUnit\Framework\TestCase;
use Wythe\Redis\Chapter3\src\TodoList;
use Wythe\Redis\Client;

class TodoListTest extends TestCase
{
    public function testInvoke()
    {
        $client = new Client();
        $todoList = new TodoList($client, 10086);
        $this->assertInstanceOf(TodoList::class, $todoList);
        return $todoList;
    }

    /**
     * @depends testInvoke
     * @param \Wythe\Redis\Chapter3\src\TodoList $todoList
     */
    public function testAdd(TodoList $todoList)
    {
        $todoList->add('go to sleep');
        $todoList->add('finish homework');
        $todoList->add('watch tv');
        $this->assertEquals(['watch tv', 'finish homework', 'go to sleep'], $todoList->showTodoList());
    }

    /**
     * @depends testInvoke
     * @param \Wythe\Redis\Chapter3\src\TodoList $todoList
     */
    public function testRemove(TodoList $todoList)
    {
        $todoList->add('test remove');
        $todoList->remove('test remove');
        $this->assertEquals(['watch tv', 'finish homework', 'go to sleep'], $todoList->showTodoList());
    }

    /**
     * @depends testInvoke
     * @param \Wythe\Redis\Chapter3\src\TodoList $todoList
     */
    public function testDone(TodoList $todoList)
    {
        $todoList->add('test done');
        $todoList->done('test done');
        $this->assertEquals(['watch tv', 'finish homework', 'go to sleep'], $todoList->showTodoList());
        $this->assertEquals(['test done'], $todoList->showDoneList());
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: WytheHuang
 * Date: 2020/3/17
 * Time: 22:33
 */

declare(strict_types=1);
namespace Wythe\Redis\Chapter3\tests;

use PHPUnit\Framework\TestCase;
use Wythe\Redis\Chapter3\src\FIFOQueue;
use Wythe\Redis\Client;

class QueueTest extends TestCase
{
    public function testInvoke()
    {
        $client = new Client();
        $queue = new FIFOQueue($client, 'queue');
        $this->assertInstanceOf(FIFOQueue::class, $queue);
        return $queue;
    }

    /**
     * @depends testInvoke
     * @param \Wythe\Redis\Chapter3\src\FIFOQueue $queue
     */
    public function testEnqueue(FIFOQueue $queue)
    {
        $queue->enqueue(1);
        $this->assertEquals(2, $queue->enqueue(2));
    }

    /**
     * @depends testInvoke
     * @param \Wythe\Redis\Chapter3\src\FIFOQueue $queue
     */
    public function testDequeue(FIFOQueue $queue)
    {
        $this->assertEquals(1, $queue->dequeue());
    }
}

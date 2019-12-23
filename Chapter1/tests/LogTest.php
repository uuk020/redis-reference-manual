<?php
/**
 * Created by PhpStorm.
 * User: WytheHuang
 * Date: 2019/12/20
 * Time: 0:21
 */

declare(strict_types = 1);
namespace Wythe\Redis\Chapter1\tests;


use PHPUnit\Framework\TestCase;
use Wythe\Redis\Chapter1\src\Log;
use Wythe\Redis\Client;

class LogTest extends TestCase
{
    public function testInvoke()
    {
        $client = new Client();
        $logObject = new Log($client, 'logs');
        $this->assertInstanceOf(Log::class, $logObject);
        return $logObject;
    }

    /**
     * @depends testInvoke
     * @param \Wythe\Redis\Chapter1\src\Log $log
     */
    public function testGetLogs(Log $log)
    {
        $log->add('this is log1');
        $log->add('this is log2');
        $expected = ['this is log1', 'this is log2'];
        $this->assertEquals($expected, $log->getAll());
    }
}
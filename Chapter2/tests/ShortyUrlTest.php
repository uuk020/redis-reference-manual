<?php
/**
 * Created by PhpStorm.
 * User: WytheHuang
 * Date: 2019/12/24
 * Time: 23:56
 */

namespace Wythe\Redis\Chapter2\tests;

use PHPUnit\Framework\TestCase;
use Wythe\Redis\Chapter2\src\ShortyUrl;
use Wythe\Redis\Client;

class ShortyUrlTest extends TestCase
{
    public function testInvoke()
    {
        $client = new Client();
        $shortyUrl = new ShortyUrl($client);
        $this->assertInstanceOf(ShortyUrl::class, $shortyUrl);
        return $shortyUrl;
    }

    /**
     * @depends testInvoke
     * @param \Wythe\Redis\Chapter2\src\ShortyUrl $shortyUrl
     */
    public function testShorten(ShortyUrl $shortyUrl)
    {
        $expected = 1; // 第一次执行的
        $this->assertEquals($expected, $shortyUrl->shorten('http://www.github.com/uuk020'));
    }

    /**
     * @depends testInvoke
     * @param \Wythe\Redis\Chapter2\src\ShortyUrl $shortyUrl
     */
    public function testRestore(ShortyUrl $shortyUrl)
    {
        $expected = 'http://www.github.com/uuk020';
        $this->assertEquals($expected, $shortyUrl->restore(1));
    }
}

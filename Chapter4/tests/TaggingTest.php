<?php
/**
 * Created by PhpStorm.
 * User: WytheHuang
 * Date: 2020/4/15
 * Time: 23:50
 */

declare(strict_types=1);
namespace Wythe\Redis\Chapter4\tests;

use PHPUnit\Framework\TestCase;
use Wythe\Redis\Chapter4\src\Tagging;
use Wythe\Redis\Client;

class TaggingTest extends TestCase
{
    public function testInvoke()
    {
        $client = new Client();
        $tag = new Tagging($client, 'The C Programming Language');
        $this->assertInstanceOf(Tagging::class, $tag);
        return $tag;
    }

    /**
     * @depends testInvoke
     * @param \Wythe\Redis\Chapter4\src\Tagging $tagging
     */
    public function testAdd(Tagging $tagging)
    {
        $tagging->add('c');
        $tagging->add('programing');
        $tagging->add('programing language');
        $tagging->add('php');
        $this->assertTrue($tagging->isIncluded('c'));
    }

    /**
     * @depends testInvoke
     * @param \Wythe\Redis\Chapter4\src\Tagging $tagging
     */
    public function testRemove(Tagging $tagging)
    {
        $tagging->remove('php');
        $this->assertFalse($tagging->isIncluded('php'));
    }

    /**
     * @depends testInvoke
     * @param \Wythe\Redis\Chapter4\src\Tagging $tagging
     */
    public function testCount(Tagging $tagging)
    {
        $this->assertEquals(3, $tagging->count());
    }
}

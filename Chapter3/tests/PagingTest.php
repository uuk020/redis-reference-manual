<?php
/**
 * Created by PhpStorm.
 * User: WytheHuang
 * Date: 2020/3/18
 * Time: 22:22
 */

declare(strict_types=1);
namespace Wythe\Redis\Chapter3\tests;

use PHPUnit\Framework\TestCase;
use Wythe\Redis\Chapter3\src\Paging;
use Wythe\Redis\Client;

class PagingTest extends TestCase
{
    public function testInvoke()
    {
        $client = new Client();
        $page = new Paging($client, 'user-topics');
        $this->assertInstanceOf(Paging::class, $page);
        return $page;
    }

    /**
     * @depends testInvoke
     * @param \Wythe\Redis\Chapter3\src\Paging $paging
     */
    public function setUp(Paging $paging): void
    {
        for ($i = 0; $i < 20;  $i++) {
            $paging->add($i);
        }
    }

    /**
     * @depends testInvoke
     * @param \Wythe\Redis\Chapter3\src\Paging $paging
     */
    public function testPage(Paging $paging)
    {
        $this->assertEquals([19,18,17,16,15], $paging->getPage(1, 5));
    }

    /**
     * @depends testInvoke
     * @param \Wythe\Redis\Chapter3\src\Paging $paging
     */
    public function testSize(Paging $paging)
    {
        $this->assertEquals(20, $paging->size());
    }
}

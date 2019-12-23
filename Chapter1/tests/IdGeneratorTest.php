<?php
/**
 * Created by PhpStorm.
 * User: WytheHuang
 * Date: 2019/12/23
 * Time: 23:51
 */

declare(strict_types=1);
namespace Wythe\Redis\Chapter1\tests;

use PHPUnit\Framework\TestCase;
use Wythe\Redis\Chapter1\src\IdGenerator;
use Wythe\Redis\Client;

class IdGeneratorTest extends TestCase
{
    public function testInvoke()
    {
        $client = new Client();
        $idGenerator = new IdGenerator($client, 'user::id');
        $this->assertInstanceOf(IdGenerator::class, $idGenerator);
        return $idGenerator;
    }

    /**
     * @depends testInvoke
     * @param \Wythe\Redis\Chapter1\src\IdGenerator $idGenerator
     */
    public function testReserve(IdGenerator $idGenerator)
    {
        $this->assertTrue($idGenerator->reserve(1023));
    }

    /**
     * @depends testInvoke
     * @param \Wythe\Redis\Chapter1\src\IdGenerator $idGenerator
     */
    public function testProduce(IdGenerator $idGenerator)
    {
        $expected = 1024;
        $this->assertEquals($expected, $idGenerator->produce());
    }
}
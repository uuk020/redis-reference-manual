<?php
declare(strict_types=1);
namespace Wythe\Redis\Chapter4\tests;

use PHPUnit\Framework\TestCase;
use Wythe\Redis\Chapter4\src\Relationship;
use Wythe\Redis\Client;

class RelationshipTest extends TestCase
{
    public function testInvoke()
    {
        $client = new Client();
        $relationship = new Relationship($client, 1);
        $this->assertInstanceOf(Relationship::class, $relationship);
        return $relationship;
    }

    /**
     * @depends testInvoke
     * @param Relationship $relationship
     */
    public function testFollow(Relationship $relationship)
    {
        $relationship->follow(2);
        $relationship->follow(3);
        $this->assertTrue($relationship->isFollow(2));
    }

    /**
     * @depends testInvoke
     * @param Relationship $relationship
     */
    public function testUnfollow(Relationship $relationship)
    {
        $relationship->unfollow(3);
        $this->assertFalse($relationship->isFollow(3));
    }

    /**
     * @depends testInvoke
     * @param Relationship $relationship
     */
    public function testFollowingPersonAndCount(Relationship $relationship)
    {
        $this->assertEquals([2], $relationship->getAllFollowing());
        $this->assertEquals(1, $relationship->countFollowing());
    }

    /**
     * @depends testInvoke
     * @param Relationship $relationship
     */
    public function testFollowPersonAndCount(Relationship $relationship)
    {
        $this->assertEmpty($relationship->getAllFollower());
        $this->assertEmpty($relationship->countFollower());
    }
}

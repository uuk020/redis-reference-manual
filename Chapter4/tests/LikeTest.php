<?php
declare(strict_types=1);
namespace Wythe\Redis\Chapter4\tests;

use PHPUnit\Framework\TestCase;
use Wythe\Redis\Chapter4\src\Like;
use Wythe\Redis\Client;

class LikeTest extends TestCase
{
    public function testInvoke()
    {
        $client = new Client();
        $like = new Like($client, 'topic::10086::like');
        $this->assertInstanceOf(Like::class, $like);
        return $like;
    }

    /**
     * @depends testInvoke
     * @param Like $like
     */
    public function testCast(Like $like)
    {
        $like->cast(1);
        $like->cast(2);
        $like->cast(3);
        $this->assertTrue($like->isLiked(1));
    }

    /**
     * @depends testInvoke
     * @param Like $like
     */
    public function testUndo(Like $like)
    {
        $like->undo(3);
        $this->assertFalse($like->isLiked(3));
    }

    /**
     * @depends testInvoke
     * @param Like $like
     */
    public function testAllUsers(Like $like)
    {
        $actual = $like->getAllLikedUsers();
        $this->assertEquals([1, 2], $actual);
        $this->assertEquals(2, $like->count());
    }
}

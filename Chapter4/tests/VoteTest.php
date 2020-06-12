<?php
declare(strict_types=1);
namespace Wythe\Redis\Chapter4\tests;

use PHPUnit\Framework\TestCase;
use Wythe\Redis\Chapter4\src\Vote;
use Wythe\Redis\Client;

class VoteTest extends TestCase
{
    /**
     * 测试是否是vote类实例
     * @return Vote
     */
    public function testInvoke()
    {
        $client = new Client();
        $vote = new Vote($client, 'question::10086');
        $this->assertInstanceOf(Vote::class, $vote);
        return $vote;
    }

    /**
     * 测试是否投赞同票
     * @depends testInvoke
     * @param Vote $vote
     */
    public function testIsVoteUp(Vote $vote)
    {
        $vote->voteUp(1);
        $vote->voteUp(2);
        $vote->voteUp(3);
        $this->assertTrue($vote->isVoted(1));
    }

    /**
     * 测试是否投反对票
     * @depends testInvoke
     * @param Vote $vote
     */
    public function testIsVoteDown(Vote $vote)
    {
        $vote->voteDown(4);
        $vote->voteDown(5);
        $this->assertTrue($vote->isVoted(4));
    }

    /**
     * 测试取消投赞同票
     * @depends testInvoke
     * @param Vote $vote
     */
    public function testUndoVoteUp(Vote $vote)
    {
        $vote->undo(3);
        $this->assertFalse($vote->isVoted(3));
    }

    /**
     * 测试投赞同票的人数和用户
     * @depends testInvoke
     * @param Vote $vote
     */
    public function testVoteUp(Vote $vote)
    {
        $this->assertEquals(2, $vote->voteUpCount());
        $this->assertEquals([1, 2], $vote->getAllVoteUpUsers());
    }

    /**
     * 测试投反对票的人数和用户
     * @depends testInvoke
     * @param Vote $vote
     */
    public function testVoteDown(Vote $vote)
    {
        $this->assertEquals(2, $vote->voteUpCount());
        $this->assertEquals([4, 5], $vote->getAllVoteDownUsers());
    }
}

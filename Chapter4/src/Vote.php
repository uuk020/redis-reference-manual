<?php
declare(strict_types=1);
namespace Wythe\Redis\Chapter4\src;


use Wythe\Redis\Client;

/**
 * Class Vote
 * @package Wythe\Redis\Chapter4\src
 */
class Vote
{
    /**
     *  Redis 客户端实例
     * @var Client
     */
    private $client;

    /**
     * 赞同票集合
     * @var string
     */
    private $voteUpSet;

    /**
     * 反对票集合
     * @var string
     */
    private $voteDownSet;

    /**
     * 赞同票键值
     * @param string $voteTarget
     * @return string
     */
    private function voteUpKey(string $voteTarget): string
    {
        return $voteTarget . "::vote_up";
    }

    /**
     * 反对票键值
     * @param string $voteTarget
     * @return string
     */
    private function voteDownKey(string $voteTarget): string
    {
        return $voteTarget . "::vote_down";
    }

    /**
     * Vote constructor.
     * @param Client $client
     * @param string $voteTarget
     */
    public function __construct(Client $client, string $voteTarget)
    {
        $this->client = $client;
        $this->voteUpSet = $this->voteUpKey($voteTarget);
        $this->voteDownSet = $this->voteDownKey($voteTarget);
    }

    /**
     * 是否投过票
     * @param int $user
     * @return bool
     */
    public function isVoted(int $user): bool
    {
        return $this->client->handler()->sIsMember($this->voteUpSet, $user)
            or $this->client->handler()->sIsMember($this->voteDownSet, $user);
    }

    /**
     * 投赞同票
     * @param int $user
     * @return bool
     */
    public function voteUp(int $user): bool
    {
        if ($this->isVoted($user)) {
            return false;
        }
        $this->client->handler()->sAdd($this->voteUpSet, $user);
        return true;
    }

    /**
     * 投反对票
     * @param int $user
     * @return bool
     */
    public function voteDown(int $user): bool
    {
        if ($this->isVoted($user)) {
            return false;
        }
        $this->client->handler()->sAdd($this->voteDownSet, $user);
        return true;
    }

    /**
     * 取消投票
     * @param int $user
     */
    public function undo(int $user)
    {
        $this->client->handler()->sRem($this->voteUpSet, $user);
        $this->client->handler()->sRem($this->voteDownSet, $user);
    }

    /**
     * 赞同票人数
     * @return int
     */
    public function voteUpCount(): int
    {
        return $this->client->handler()->sCard($this->voteUpSet);
    }

    /**
     * 投赞同票所有用户
     * @return array
     */
    public function getAllVoteUpUsers(): array
    {
        return $this->client->handler()->sMembers($this->voteUpSet);
    }

    /**
     * 反对票人数
     * @return int
     */
    public function voteDownCount(): int
    {
        return $this->client->handler()->sCard($this->voteDownSet);
    }

    /**
     * 投反对票所有用户
     * @return array
     */
    public function getAllVoteDownUsers(): array
    {
        return $this->client->handler()->sMembers($this->voteDownSet);
    }
}
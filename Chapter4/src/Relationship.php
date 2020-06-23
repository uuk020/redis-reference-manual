<?php
declare(strict_types=1);
namespace Wythe\Redis\Chapter4\src;

use Wythe\Redis\Client;

/**
 * 社交关系
 * @package Wythe\Redis\Chapter4\src
 */
class Relationship
{
    /**
     * Redis 客户端
     * @var Client
     */
    private $client = null;

    /**
     * 用户ID
     * @var int
     */
    private $userId;

    /**
     * 正在关注的集合键值
     * @return string
     */
    private function followingKey(): string
    {
        return $this->userId . '::following';
    }

    /**
     * 关注者键值
     * @param  int $target
     * @return string
     */
    private function followerKey(int $target): string
    {
        return $target . '::follower';
    }

    /**
     * Relationship constructor.
     * @param Client $client
     * @param int $userId
     */
    public function __construct(Client $client, int $userId)
    {
        $this->client = $client;
        $this->userId = $userId;
    }

    /**
     * 关注
     * @param int $target
     */
    public function follow(int $target)
    {
//      把target添加当前用户的正在关注集合中
        $this->client->handler()->sAdd($this->followingKey(), $target);
//      把当前用户添加到target的关注者集合中
        $this->client->handler()->sAdd($this->followerKey($target), $this->userId);
    }

    /**
     * 取消关注
     * @param int $target
     */
    public function unfollow(int $target)
    {
        $this->client->handler()->sRem($this->followingKey(), $target);
        $this->client->handler()->sRem($this->followerKey($target), $this->userId);
    }


    /**
     * 是否关注
     * @param int $target
     * @return bool
     */
    public function isFollow(int $target): bool
    {
        return $this->client->handler()->sIsMember($this->followingKey(), $target);
    }

    /**
     * 获取当前用户正在关注所有人
     * @return array
     */
    public function getAllFollowing(): array
    {
        return $this->client->handler()->sMembers($this->followingKey());
    }

    /**
     * 获取当前用户所有关注者
     * @return array
     */
    public function getAllFollower(): array
    {
        return $this->client->handler()->sMembers($this->followerKey($this->userId));
    }

    /**
     * 获取当前用户正在关注所有人人数
     * @return int
     */
    public function countFollowing(): int
    {
        return $this->client->handler()->sCard($this->followingKey());
    }

    /**
     * 获取当前用户所有关注者人数
     * @return int
     */
    public function countFollower(): int
    {
        return $this->client->handler()->sCard($this->followerKey($this->userId));
    }
}
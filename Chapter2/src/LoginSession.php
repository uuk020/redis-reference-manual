<?php
/**
 * Created by PhpStorm.
 * User: WytheHuang
 * Date: 2019/12/25
 * Time: 23:16
 */

declare(strict_types = 1);
namespace Wythe\Redis\Chapter2\src;


use Wythe\Redis\Client;

/**
 * Class LoginSession
 * @package Wythe\Redis\Chapter2\src
 */
class LoginSession
{
    /**
     * token散列键
     * @var string
     */
    const SESSION_TOKEN_HASH = 'session::token';

    /**
     * 过期时间散列键
     * @var string
     */
    const SESSION_EXPIRE_TS_HASH = 'session::expire_timestamp';

    /**
     * 用户未登录
     * @var string
     */
    const SESSION_NOT_LOGIN = 'session_not_login';

    /**
     * 会话已过期
     * @var string
     */
    const SESSION_EXPIRED = 'session_expired';

    /**
     * 验证令牌成功
     * @var string
     */
    const SESSION_TOKEN_CORRECT = 'session_token_correct';

    /**
     * 验证令牌失败
     * @var string
     */
    const SESSION_TOKEN_INCORRECT = 'session_token_incorrect';

    /**
     * @var \Wythe\Redis\Client
     */
    private $client;

    /**
     * @var int
     */
    private $userId;

    /**
     * 生成token
     * @return string
     */
    private function generateToken()
    {
        $str = '';
        for ($i = 0; $i < 5; $i++)
        {
            $str .= \mt_rand(0, 9);
        }
        return hash("sha256", $str);
    }

    /**
     * LoginSession constructor.
     * @param \Wythe\Redis\Client $client
     * @param string              $userId
     */
    public function __construct(Client $client, string $userId)
    {
        $this->client = $client;
        $this->userId = $userId;
    }

    /**
     * 创建令牌
     * @param int $timeout
     * @return string
     */
    public function create(int $timeout = 120)
    {
        $userToken = $this->generateToken();
        $expireTimestamp = time() + $timeout;
        $this->client->handler()->hSet(self::SESSION_TOKEN_HASH, $this->userId, $userToken);
        $this->client->handler()->hSet(self::SESSION_EXPIRED, $this->userId, $expireTimestamp);
        return $userToken;
    }

    /**
     * 根据给定令牌验证用户身份
     * @param string $inputToken
     * @return string
     */
    public function validate(string $inputToken): string
    {
        $userToken = $this->client->handler()->hGet(self::SESSION_TOKEN_HASH, $this->userId);
        $expireTimestamp = $this->client->handler()->hGet(self::SESSION_EXPIRED, $this->userId);
        if ($userToken === false || $expireTimestamp === false) {
            return self::SESSION_NOT_LOGIN;
        }
        if (time() > (int)$expireTimestamp) {
            return self::SESSION_EXPIRED;
        }
        if ($inputToken == $userToken) {
            return self::SESSION_TOKEN_CORRECT;
        } else {
            return self::SESSION_TOKEN_INCORRECT;
        }
    }

    /**
     * 销毁会话
     */
    public function destroy()
    {
        $this->client->handler()->hDel(self::SESSION_TOKEN_HASH, $this->userId);
        $this->client->handler()->hDel(self::SESSION_EXPIRED, $this->userId);
    }
}
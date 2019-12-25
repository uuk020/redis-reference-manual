<?php
/**
 * Created by PhpStorm.
 * User: WytheHuang
 * Date: 2019/12/25
 * Time: 23:43
 */

declare(strict_types = 1);
namespace Wythe\Redis\Chapter2\tests;

use PHPUnit\Framework\TestCase;
use Wythe\Redis\Chapter2\src\LoginSession;
use Wythe\Redis\Client;

class LoginSessionTest extends TestCase
{
    public function testInvoke()
    {
        $client = new Client();
        $loginSession = new LoginSession($client, '1024');
        $this->assertInstanceOf(LoginSession::class, $loginSession);
        return $loginSession;
    }

    /**
     * @depends testInvoke
     * @param \Wythe\Redis\Chapter2\src\LoginSession $loginSession
     */
    public function testCreate(LoginSession $loginSession)
    {
        $token = $loginSession->create();
        $client = new Client();
        $expectedToken = $client->handler()->hGet(LoginSession::SESSION_TOKEN_HASH, '1024');
        $this->assertEquals($expectedToken, $token);
    }

    /**
     * @depends testInvoke
     * @param \Wythe\Redis\Chapter2\src\LoginSession $loginSession
     */
    public function testCorrect(LoginSession $loginSession)
    {
        $client = new Client();
        $token = $client->handler()->hGet(LoginSession::SESSION_TOKEN_HASH, '1024');
        $this->assertEquals(LoginSession::SESSION_TOKEN_CORRECT, $loginSession->validate($token));
    }

    /**
     * @depends testInvoke
     * @param \Wythe\Redis\Chapter2\src\LoginSession $loginSession
     */
    public function testIncorrect(LoginSession $loginSession)
    {
        $token = 'aflksdjflksdajflkjsa';
        $this->assertEquals(LoginSession::SESSION_TOKEN_INCORRECT, $loginSession->validate($token));
    }

    /**
     * @depends testInvoke
     * @param \Wythe\Redis\Chapter2\src\LoginSession $loginSession
     */
    public function testExpired(LoginSession $loginSession)
    {
        $client = new Client();
        $client->handler()->hSet(LoginSession::SESSION_EXPIRE_TS_HASH, '1024', 1);
        $token = $client->handler()->hGet(LoginSession::SESSION_TOKEN_HASH,'1024');
        $this->assertEquals(LoginSession::SESSION_EXPIRED, $loginSession->validate($token));
    }

    /**
     * @depends testInvoke
     * @param \Wythe\Redis\Chapter2\src\LoginSession $loginSession
     */
    public function testNotLogin(LoginSession $loginSession)
    {
        $loginSession->destroy();
        $this->assertEquals(LoginSession::SESSION_NOT_LOGIN, $loginSession->validate(''));
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: WytheHuang
 * Date: 2019/12/17
 * Time: 23:33
 */

namespace Wythe\Redis\Chapter1\Tests;

use PHPUnit\Framework\TestCase;
use Wythe\Redis\Chapter1\src\Article;

class ArticleTest extends TestCase
{
    /**
     * @return \Wythe\Redis\Chapter1\src\Article
     */
    public function testInvoke()
    {
        $redis = new \Redis();
        $redis->connect('127.0.0.1', 6379);
        $articleObject = new Article($redis, 1024);
        $this->assertInstanceOf(Article::class, $articleObject);
        return $articleObject;
    }

    /**
     * @depends testInvoke
     * @param \Wythe\Redis\Chapter1\src\Article $article
     */
    public function testCreate(Article $article)
    {
        $flag = $article->create('message', 'hello world', 'wythe');
        $this->assertTrue($flag);
    }

    /**
     * @depends testInvoke
     * @param \Wythe\Redis\Chapter1\src\Article $article
     */
    public function testUpdateFailure(Article $article)
    {
        $this->assertFalse($article->update());
    }

    /**
     * @depends testInvoke
     * @param \Wythe\Redis\Chapter1\src\Article $article
     */
    public function testUpdateTitle(Article $article)
    {
        $title = 'readme';
        $this->assertTrue($article->update($title));
        $redis = new \Redis();
        $redis->connect('127.0.0.1', 6379);
        $this->assertEquals($title, $redis->get('article::1024::title'));
    }

    /**
     * @depends testInvoke
     * @param \Wythe\Redis\Chapter1\src\Article $article
     */
    public function testUpdateContent(Article $article)
    {
        $content = 'redis reference manual';
        $this->assertTrue($article->update('', $content));
        $redis = new \Redis();
        $redis->connect('127.0.0.1', 6379);
        $this->assertEquals($content, $redis->get('article::1024::content'));
    }

    /**
     * @depends testInvoke
     * @param \Wythe\Redis\Chapter1\src\Article $article
     */
    public function testUpdateAuthor(Article $article)
    {
        $author = 'nathan';
        $this->assertTrue($article->update('', '', $author));
        $redis = new \Redis();
        $redis->connect('127.0.0.1', 6379);
        $this->assertEquals($author, $redis->get('article::1024::author'));
    }

    /**
     * @depends testInvoke
     * @param \Wythe\Redis\Chapter1\src\Article $article
     */
    public function testGet(Article $article)
    {
        $expected = [
            'id' => 1024,
            'title' => 'readme',
            'content' => 'redis reference manual',
            'author' => '',
            'create_at' => '2019-12-17',
        ];
        $this->assertEquals($expected, $article->get());
    }
}

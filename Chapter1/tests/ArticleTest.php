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
use Wythe\Redis\Client;

class ArticleTest extends TestCase
{
    /**
     * @return \Wythe\Redis\Chapter1\src\Article
     */
    public function testInvoke()
    {
        $client = new Client();
        $articleObject = new Article($client, 1024);
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
        $client = new Client();
        $this->assertEquals($title, $client->handler()->get('article::1024::title'));
    }

    /**
     * @depends testInvoke
     * @param \Wythe\Redis\Chapter1\src\Article $article
     */
    public function testUpdateContent(Article $article)
    {
        $content = 'redis reference manual';
        $this->assertTrue($article->update('', $content));
        $redis = new Client();
        $this->assertEquals($content, $redis->handler()->get('article::1024::content'));
    }

    /**
     * @depends testInvoke
     * @param \Wythe\Redis\Chapter1\src\Article $article
     */
    public function testUpdateAuthor(Article $article)
    {
        $author = 'nathan';
        $this->assertTrue($article->update('', '', $author));
        $redis = new Client();
        $this->assertEquals($author, $redis->handler()->get('article::1024::author'));
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

    /**
     * @depends testInvoke
     * @param \Wythe\Redis\Chapter1\src\Article $article
     */
    public function testArticleLen(Article $article)
    {
        $this->assertEquals(22, $article->getContentLen());
    }

    /**
     * @depends testInvoke
     * @param \Wythe\Redis\Chapter1\src\Article $article
     */
    public function testArticlePreview(Article $article)
    {
        $this->assertEquals('redis', $article->getContentPreview(5));
    }
}

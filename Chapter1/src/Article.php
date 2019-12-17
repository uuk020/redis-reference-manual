<?php
/**
 * Created by PhpStorm.
 * User: WytheHuang
 * Date: 2019/12/17
 * Time: 23:00
 */
declare(strict_types = 1);
namespace Wythe\Redis\Chapter1\src;


class Article
{
    private $id;

    private $titleKey;

    private $contentKey;

    private $authorKey;

    private $createAtKey;

    private $redis;

    public function __construct(\Redis $redis, int $id)
    {
        $this->redis = $redis;
        $this->id = $id;
        $this->titleKey = "article::{$id}::title";
        $this->contentKey = "article::{$id}::content";
        $this->authorKey = "article::{$id}::author";
        $this->createAtKey = "article::{$id}::create_at";
    }

    public function create(string $title, string $content, string $author): bool
    {
        $data = [
            $this->titleKey => $title,
            $this->contentKey => $content,
            $this->authorKey => $author,
            $this->createAtKey => date('Y-m-d'),
        ];
        return $this->redis->msetnx($data) === 1;
    }

    public function get(): array
    {
        $result = $this->redis->mget([$this->titleKey, $this->contentKey, $this->authorKey, $this->createAtKey]);
        return ['id' => $this->id, 'title' => $result[0], 'content' => $result[1], 'author' => $result[2], 'create_at' => $result[3]];
    }

    public function update(string $title = '', string $content = '', string $author = ''): bool
    {
        $data = [];
        if ($title !== '') {
            $data[$this->titleKey] = $title;
        }
        if ($content !== '') {
            $data[$this->contentKey] = $content;
        }
        if ($author !== '') {
            $data[$this->authorKey] = $author;
        }
        if ($data) {
            return $this->redis->mset($data);
        }
        return false;
    }
}
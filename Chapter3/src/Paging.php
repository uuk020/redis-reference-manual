<?php
/**
 * Created by PhpStorm.
 * User: WytheHuang
 * Date: 2020/3/18
 * Time: 22:17
 */

declare(strict_types=1);
namespace Wythe\Redis\Chapter3\src;


use Wythe\Redis\Client;

class Paging
{
    private $client = null;

    private $key = '';

    public function __construct(Client $client, string $key)
    {
        $this->client = $client;
        $this->key = $key;
    }

    public function add($item)
    {
        $this->client->handler()->lPush($this->key, $item);
    }

    public function getPage(int $pageNumber, int $itemPerPage)
    {
        $startIndex = ($pageNumber - 1) * $itemPerPage;
        $endIndex = $pageNumber * $itemPerPage - 1;
        return $this->client->handler()->lRange($this->key, $startIndex, $endIndex);
    }

    public function size()
    {
        return $this->client->handler()->lLen($this->key);
    }
}
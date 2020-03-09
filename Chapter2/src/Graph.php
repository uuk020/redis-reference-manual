<?php
/**
 * Created by PhpStorm.
 * User: WytheHuang
 * Date: 2020/1/10
 * Time: 23:13
 */

declare(strict_types = 1);
namespace Wythe\Redis\Chapter2\src;

use Wythe\Redis\Client;

/**
 * Class Graph
 * @package Wythe\Redis\Chapter2\src
 */
class Graph
{
    /**
     * @var \Wythe\Redis\Client
     */
    private $client;

    /**
     * @var string
     */
    private $key;

    /**
     * Graph constructor.
     * @param \Wythe\Redis\Client $client
     * @param string              $key
     */
    public function __construct(Client $client, string $key)
    {
        $this->key = $key;
        $this->client = $client;
    }

    /**
     * 组建边的名字
     * @param string $start
     * @param string $end
     * @return string
     */
    public function makeEdgeFromVertices(string $start, string $end): string
    {
       return $start . '->' . $end;
    }

    /**
     * 解析边的名字
     * @param string $name
     * @return array
     */
    public function decomposeVerticesFromEdgeName(string $name): array
    {
        return \explode('->', $name);
    }

    /**
     * 添加边和权重
     * @param string $start
     * @param string $end
     * @param int    $weight
     */
    public function addEdge(string $start, string $end, int $weight): void
    {
        $edge = $this->makeEdgeFromVertices($start, $end);
        $this->client->handler()->hSet($this->key, $edge, $weight);
    }

    /**
     * 移除边
     * @param string $start
     * @param string $end
     * @return bool|int
     */
    public function removeEdge(string $start, string $end)
    {
        $edge = $this->makeEdgeFromVertices($start, $end);
        return $this->client->handler()->hDel($this->key, $edge);
    }

    /**
     * 获取边的权重
     * @param string $start
     * @param string $end
     * @return int
     */
    public function getEdgeWeight(string $start, string $end): int
    {
        $edge = $this->makeEdgeFromVertices($start, $end);
        return (int)$this->client->handler()->hGet($this->key, $edge);
    }

    /**
     * 是否存在边
     * @param string $start
     * @param string $end
     * @return bool
     */
    public function hasEdge(string $start, string $end): bool
    {
        $edge = $this->makeEdgeFromVertices($start, $end);
        return $this->client->handler()->hExists($this->key, $edge);
    }

    /**
     * 批量加入边和权重
     * @param array $tuples
     */
    public function addMultiEdges(array $tuples)
    {
        $nodesAndWeights = [];
        foreach ($tuples as ['start' => $start, 'end' => $end, 'weight' => $weight]) {
            $edge = $this->makeEdgeFromVertices($start, $end);
            $nodesAndWeights[$edge] = $weight;
        }
        $this->client->handler()->hMSet($this->key, $nodesAndWeights);
    }

    /**
     * 批量获取边的权重
     * @param array $tuples
     * @return array
     */
    public function getMultiEdgeWeights(array $tuples): array
    {
        $edgeList = [];
        foreach ($tuples as ['start' => $start, 'end' => $end]) {
            $edge = $this->makeEdgeFromVertices($start, $end);
            $edgeList[] = $edge;
        }
        return $this->client->handler()->hMGet($this->key, $edgeList);
    }

    /**
     * 获取所有边
     * @return array
     */
    public function getAllEdges(): array
    {
        $edges = $this->client->handler()->hKeys($this->key);
        $result = [];
        foreach ($edges as $edge) {
            list($start, $end) = $this->decomposeVerticesFromEdgeName($edge);
            $result[] = [$start, $end];
        }
        return $result;
    }

    /**
     * 获取所有边的权重
     * @return array
     */
    public function getAllEdgesWithWeight()
    {
        $edgesAndWeight = $this->client->handler()->hGetAll($this->key);
        $result = [];
        foreach ($edgesAndWeight as $edge => $weight) {
            list($start, $end) = $this->decomposeVerticesFromEdgeName($edge);
            $result[] = [$start, $edge, $weight];
        }
        return $result;
    }
}
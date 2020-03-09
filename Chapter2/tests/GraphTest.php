<?php
/**
 * Created by PhpStorm.
 * User: WytheHuang
 * Date: 2020/1/14
 * Time: 23:31
 */

namespace Wythe\Redis\Chapter2\tests;


use PHPUnit\Framework\TestCase;
use Wythe\Redis\Chapter2\src\Graph;
use Wythe\Redis\Client;

class GraphTest extends TestCase
{
    public function testInvoke()
    {
        $client = new Client();
        $graph = new Graph($client, 'test-graph');
        $this->assertInstanceOf(Graph::class, $graph);
        return $graph;
    }

    /**
     * @depends testInvoke
     * @param \Wythe\Redis\Chapter2\src\Graph $graph
     */
    public function testAddEdge(Graph $graph)
    {
        $graph->addEdge("a", "b", 1);
        $this->assertTrue($graph->hasEdge("a", "b"));
        $this->assertEquals(1, $graph->getEdgeWeight("a", "b"));
    }

    /**
     * @depends testInvoke
     * @param \Wythe\Redis\Chapter2\src\Graph $graph
     */
    public function testEdgeName(Graph $graph)
    {
        $actual = $graph->decomposeVerticesFromEdgeName("a->b");
        $this->assertEquals(["a", "b"], $actual);
    }

    /**
     * @depends testInvoke
     * @param \Wythe\Redis\Chapter2\src\Graph $graph
     */
    public function testRemoveEdge(Graph $graph)
    {
        $graph->addEdge("a", "c", 2);
        $graph->removeEdge("a", "c");
        $this->assertFalse($graph->hasEdge("a", "c"));
    }

    /**
     * @depends testInvoke
     * @param \Wythe\Redis\Chapter2\src\Graph $graph
     */
    public function testMultiAddEdge(Graph $graph)
    {
        $graph->addMultiEdges([['start' => 'b', 'end' => 'd', 'weight' => 2], ['start' => 'd', 'end' => 'e', 'weight'
        => 3]]);
        $this->assertTrue($graph->hasEdge('b', 'd'));
        $this->assertTrue($graph->hasEdge('d', 'e'));
    }
}

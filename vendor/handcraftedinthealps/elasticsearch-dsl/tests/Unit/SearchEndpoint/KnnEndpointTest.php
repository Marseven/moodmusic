<?php

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ONGR\ElasticsearchDSL\Tests\Unit\SearchEndpoint;

use ONGR\ElasticsearchDSL\Knn\Knn;
use ONGR\ElasticsearchDSL\Query\MatchAllQuery;
use ONGR\ElasticsearchDSL\SearchEndpoint\KnnEndpoint;
use PHPUnit\Framework\TestCase;

/**
 * Class AggregationsEndpointTest.
 */
class KnnEndpointTest extends TestCase
{
    /**
     * Tests constructor.
     */
    public function testItCanBeInstantiated(): void
    {
        $this->assertInstanceOf(
            KnnEndpoint::class,
            new KnnEndpoint()
        );
    }

    /**
     * Tests if endpoint returns builders.
     */
    public function testEndpointGetter(): void
    {
        $knn = new Knn('acme', [1, 2, 3], 10, 100);
        $endpoint = new KnnEndpoint();
        $endpoint->add($knn, 'knn');
        $builders = $endpoint->getAll();

        $this->assertCount(1, $builders);
        $this->assertSame($knn, $builders['knn']);
    }

    /**
     * Tests if endpoint returns builders.
     */
    public function testEndpointException(): void
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Add Knn builder instead!');
        $knn = new MatchAllQuery();
        $endpoint = new KnnEndpoint();
        $endpoint->add($knn);
    }

    /**
     * Tests if endpoint returns builders.
     */
    public function testEndpointWithMultipleVector(): void
    {
        $knn1 = new Knn('acme', [1, 2, 3], 10, 100);
        $knn2 = new Knn('acme', [1, 2, 4], 10, 100);
        $endpoint = new KnnEndpoint();
        $endpoint->add($knn1, 'knn1');
        $endpoint->add($knn2, 'knn2');
        $builders = $endpoint->getAll();

        $this->assertCount(2, $builders);
        $this->assertSame($knn1, $builders['knn1']);
        $this->assertSame($knn2, $builders['knn2']);
    }
}

<?php

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ONGR\ElasticsearchDSL\Tests\Unit\Knn;

use ONGR\ElasticsearchDSL\Knn\Knn;
use ONGR\ElasticsearchDSL\Query\MatchAllQuery;

class KnnTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Tests toArray().
     */
    public function testToArray(): void
    {
        $query = new Knn('field', [1, 2, 3], 10, 100);
        $this->assertEquals([
            'field' => 'field',
            'query_vector' => [1, 2, 3],
            'k' => 10,
            'num_candidates' => 100
        ], $query->toArray());
    }

    /**
     * Tests toArray().
     */
    public function testToArrayWithFilter(): void
    {
        $query = new Knn('field', [1, 2, 3], 10, 100);
        $query->setFilter(new MatchAllQuery());
        $this->assertEquals([
            'field' => 'field',
            'query_vector' => [1, 2, 3],
            'k' => 10,
            'num_candidates' => 100,
            'filter' => [
                'match_all' => new \stdClass()
            ]
        ], $query->toArray());
    }

    /**
     * Tests toArray().
     */
    public function testToArrayWithSimilarity(): void
    {
        $query = new Knn('field', [1, 2, 3], 10, 100);
        $query->setSimilarity(1);
        $this->assertEquals([
            'field' => 'field',
            'query_vector' => [1, 2, 3],
            'k' => 10,
            'num_candidates' => 100,
            'similarity' => 1
        ], $query->toArray());
    }
}

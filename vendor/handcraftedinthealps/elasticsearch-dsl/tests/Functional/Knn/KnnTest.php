<?php
/**
 * @since     Feb 2022
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace ONGR\ElasticsearchDSL\Tests\Functional\Knn;

use Composer\InstalledVersions;
use Elastic\Elasticsearch\Client;
use ONGR\ElasticsearchDSL\Knn\Knn;
use ONGR\ElasticsearchDSL\Query\TermLevel\TermQuery;
use ONGR\ElasticsearchDSL\Search;
use ONGR\ElasticsearchDSL\Tests\Functional\AbstractElasticsearchTestCase;

class KnnTest extends AbstractElasticsearchTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function getDataArray(): array
    {
        return [
            'knn_data' => [
                'doc_1' => [
                    'label' => 1,
                    'vector_field' => [1, 2, 3],
                ],
                'doc_2' => [
                    'label' => 1,
                    'vector_field' => [1, 2, 4],
                ],
                'doc_3' => [
                    'label' => 2,
                    'vector_field' => [1, 2, 30],
                ],
            ]
        ];
    }

    protected function getMapping(): array
    {
        return [
            'properties' => [
                'label' => [
                    'type' => 'long'
                ],
                'vector_field' => [
                    'type' => 'dense_vector',
                    'similarity' => 'cosine',
                    'index' => true,
                    'dims' => 3
                ]
            ]
        ];
    }

    /**
     * Match all test
     */
    public function testKnnSearch(): void
    {
        $knn = new Knn('vector_field', [1, 2, 3], 1, 1);

        $search = new Search();
        $search->addKnn($knn);
        $results = $this->executeSearch($search, true);
        $this->assertCount(1, $results['hits']['hits']);
        $this->assertEquals('doc_1', $results['hits']['hits'][0]['_id']);
    }

    /**
     * Match all test
     */
    public function testKnnSearchWithFilter(): void
    {
        $knn = new Knn('vector_field', [1, 2, 3], 1, 1);
        $knn->setFilter(new TermQuery('label', 2));

        $search = new Search();
        $search->addKnn($knn);
        $results = $this->executeSearch($search, true);
        $this->assertCount(1, $results['hits']['hits']);
        $this->assertEquals('doc_3', $results['hits']['hits'][0]['_id']);
    }

    /**
     * Match all test
     */
    public function testMultipleKnnSearchWithBoost(): void
    {
        $knn1 = new Knn('vector_field', [1, 2, 3], 1, 1);
        $knn1->setFilter(new TermQuery('label', 2));
        $knn1->setBoost(0.5);

        $search = new Search();
        $search->addKnn($knn1);
        $results = $this->executeSearch($search, true);
        $this->assertCount(1, $results['hits']['hits']);
        $this->assertEquals('doc_3', $results['hits']['hits'][0]['_id']);
    }
}

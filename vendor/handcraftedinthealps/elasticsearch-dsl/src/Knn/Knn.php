<?php

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ONGR\ElasticsearchDSL\Knn;

use ONGR\ElasticsearchDSL\BuilderInterface;
use ONGR\ElasticsearchDSL\FieldAwareTrait;

class Knn implements BuilderInterface
{
    use FieldAwareTrait;

    /**
     * @var string
     */
    private $field;

    /**
     * @var array
     */
    private $queryVector;

    /**
     * @var int
     */
    private $k;

    /**
     * @var int
     */
    private $numCandidates;

    /**
     * @var float|null
     */
    private $boost;

    /**
     * @var float
     */
    private $similarity = null;

    /**
     * @var BuilderInterface
     */
    private $filter = null;


    /**
     * TermSuggest constructor.
     * @param string $field
     * @param array $queryVector
     * @param int $k
     * @param int $numCandidates
     */
    public function __construct(
        string $field,
        array $queryVector,
        int $k,
        int $numCandidates
    ) {
        $this->setField($field);
        $this->setQueryVector($queryVector);
        $this->setK($k);
        $this->setNumCandidates($numCandidates);
    }

    /**
     * @return string
     */
    public function getField(): string
    {
        return $this->field;
    }

    /**
     * @param string $field
     */
    public function setField(string $field): void
    {
        $this->field = $field;
    }

    /**
     * @return array
     */
    public function getQueryVector(): array
    {
        return $this->queryVector;
    }

    /**
     * @param array $queryVector
     */
    public function setQueryVector(array $queryVector): void
    {
        $this->queryVector = $queryVector;
    }

    /**
     * @return int
     */
    public function getK(): int
    {
        return $this->k;
    }

    /**
     * @param int $k
     */
    public function setK(int $k): void
    {
        $this->k = $k;
    }

    /**
     * @return int
     */
    public function getNumCandidates(): int
    {
        return $this->numCandidates;
    }

    /**
     * @param int $numCandidates
     */
    public function setNumCandidates(int $numCandidates): void
    {
        $this->numCandidates = $numCandidates;
    }

    /**
     * @return float|null
     */
    public function getSimilarity(): ?float
    {
        return $this->similarity;
    }

    /**
     * @param float $similarity
     */
    public function setSimilarity(float $similarity): void
    {
        $this->similarity = $similarity;
    }

    /**
     * @return float|null
     */
    public function getBoost(): ?float
    {
        return $this->boost;
    }

    /**
     * @param float $boost
     */
    public function setBoost(float $boost): void
    {
        $this->boost = $boost;
    }

    /**
     * @return BuilderInterface|null
     */
    public function getFilter(): ?BuilderInterface
    {
        return $this->filter;
    }

    /**
     * @param BuilderInterface $filter
     */
    public function setFilter(BuilderInterface $filter): void
    {
        $this->filter = $filter;
    }

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return 'knn';
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        $output = [
            'field' => $this->getField(),
            'query_vector' => $this->getQueryVector(),
            'k' => $this->getK(),
            'num_candidates' => $this->getNumCandidates(),
        ];

        if ($this->getSimilarity()) {
            $output['similarity'] = $this->getSimilarity();
        }

        if ($this->getBoost()) {
            $output['boost'] = $this->getBoost();
        }

        if ($this->getFilter()) {
            $output['filter'] = $this->getFilter()->toArray();
        }

        return $output;
    }
}

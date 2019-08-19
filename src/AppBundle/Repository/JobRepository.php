<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Filter;
use AppBundle\Entity\Job;
use AppBundle\Entity\Search;
use AppBundle\Widgets\SearchWidget\QueryParam;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\EntityManagerInterface;

class JobRepository
{
    /**
    * @var EntityRepository
    */
    private $repository;

    private $queryParams = [];

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->repository = $entityManager->getRepository(Job::class);
    }

    /**
     * @param QueryParam $queryParam
     * @return JobRepository
     */
    public function attachQueryParam($queryParam = null)
    {
        if (!empty($queryParam)) {
            $this->queryParams[] = $queryParam;
        }
        return $this;
    }

    /**
     * @param QueryParam[] $queryParams
     * @return JobRepository
     */
    public function attachQueryParams(array $queryParams = [])
    {
        if (!empty($queryParams)) {
            $this->queryParams = array_merge($this->queryParams, $queryParams);
        }
        return $this;
    }

    /**
     * @return QueryBuilder
     * @throws \ReflectionException
     */
    public function getBuilder()
    {
        $queryBuilder = $this->repository->createQueryBuilder('job');
        $jobProps = $this->getJobProps();

        foreach ($this->queryParams as $param) {
            if (!in_array($param->getName(), $jobProps) || $param->getName() === Search::TITLE) {
                continue;
            }
            else if ($param->getType() === Filter::TYPE_INT) {
                $queryBuilder = $queryBuilder
                    ->andWhere('job.' .$param->getName(). ' > :' .$param->getName())
                    ->setParameter($param->getName(), $param->getValue());
            }
            else if ($param->getType() === Filter::TYPE_ARRAY) {
                $queryBuilder = $queryBuilder
                    ->andWhere($queryBuilder->expr()->in('job.' .$param->getName(), ':subQuery'))
                    ->setParameter('subQuery', $param->getValue());
            }
            else {
                $queryBuilder = $queryBuilder
                    ->andWhere('job.' .$param->getName(). ' = :' .$param->getName())
                    ->setParameter($param->getName(), $param->getValue());
            }
        }

        return $queryBuilder
            ->orderBy('job.id', 'DESC');
    }

    /**
     * @return array
     * @throws \ReflectionException
     */
    private function getJobProps()
    {
        $jobProps = [];

        $jobReflection = new \ReflectionClass(Job::class);
        foreach ($jobReflection->getProperties() as $property) {
            $jobProps[] = $property->getName();
        }

        return $jobProps;
    }
}
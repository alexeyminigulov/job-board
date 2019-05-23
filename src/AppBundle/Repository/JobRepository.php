<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Filter;
use AppBundle\Entity\Job;
use AppBundle\Widgets\SearchWidget\QueryParam;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class JobRepository extends EntityRepository
{
    /**
     * @param QueryParam[] $queryParams
     * @return QueryBuilder
     * @throws \ReflectionException
     */
    public function getWithParamsQuery(array $queryParams = []): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('job');
        $jobProps = $this->getJobProps();

        foreach ($queryParams as $param) {
            if (!in_array($param->getName(), $jobProps)) {
                continue;
            }
            else if ($param->getType() === Filter::TYPE_INT) {
                $queryBuilder = $queryBuilder
                    ->andWhere('job.' .$param->getName(). ' > :' .$param->getName())
                    ->setParameter($param->getName(), $param->getValue());
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
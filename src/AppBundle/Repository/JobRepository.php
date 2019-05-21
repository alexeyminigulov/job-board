<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Filter;
use AppBundle\Entity\Job;
use AppBundle\Widgets\SearchWidget\QueryParam;
use Doctrine\ORM\EntityRepository;

class JobRepository extends EntityRepository
{
    /**
     * @param QueryParam[] $queryParams
     * @return Job[]
     */
    public function findByParams(array $queryParams = [])
    {
        $queryBuilder = $this->createQueryBuilder('job');

        foreach ($queryParams as $param) {
            if ($param->getType() === Filter::TYPE_INT) {
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
            ->orderBy('job.id', 'DESC')
            ->getQuery()
            ->execute();
    }
}
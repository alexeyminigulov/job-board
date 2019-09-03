<?php

namespace AppBundle\Repository;

use AppBundle\Entity\User\User;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * @param $email
     * @return null|User
     */
    public function findByEmail($email): ?User
    {
        $qb = $this->createQueryBuilder('u')
            ->andWhere('u.email = :email')
            ->setParameter('email', $email)
            ->orderBy('u.email', 'ASC')
            ->setMaxResults(1)
            ->getQuery();

        return $qb->getOneOrNullResult();
    }
}
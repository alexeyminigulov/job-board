<?php

namespace AppBundle\Services;

use AppBundle\Entity\User\User;
use Doctrine\ORM\EntityManagerInterface;
use AppBundle\Repository\UserRepository;
use AppBundle\Form\Profile\User\Fio\Data as FioData;

class ProfileService
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var UserRepository
     */
    private $repository;

    public function __construct(
        EntityManagerInterface $em,
        UserRepository $repository
    )
    {
        $this->em = $em;
        $this->repository = $repository;
    }

    /**
     * @param User $user
     * @param FioData $data
     * @throws \Exception
     * @return User
     */
    public function editFio(User $user, FioData $data): User
    {
        if ($user->getUsername() == $data->username) {
            throw new \Exception('New name match to former one.');
        }

        $user->changeName($data->username);
        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }
}
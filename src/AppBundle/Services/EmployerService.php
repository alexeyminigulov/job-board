<?php

namespace AppBundle\Services;

use AppBundle\Entity\Employer;
use AppBundle\Entity\Job;
use Doctrine\ORM\EntityManagerInterface;
use AppBundle\Repository\UserRepository;

class EmployerService
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var UserRepository
     */
    private $repository;

    public function __construct(EntityManagerInterface $em, UserRepository $repository)
    {
        $this->em = $em;
        $this->repository = $repository;
    }

    /**
     * @param Employer $employer
     * @throws \Exception
     */
    public function singup(Employer $employer)
    {
        if ($this->repository->findByEmail($employer->getUser()->getEmail())) {
            throw new \Exception('Another employee with the same email already exists.');
        }
        $this->em->persist($employer);
        $this->em->flush();
    }

    public function addJob(Job $job)
    {
        $this->em->persist($job);
        $this->em->flush();
    }
}
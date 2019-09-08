<?php

namespace AppBundle\Services;

use AppBundle\Entity\Company;
use AppBundle\Entity\Employer;
use AppBundle\Entity\Job;
use AppBundle\Form\Job\Data as JobData;
use Doctrine\ORM\EntityManagerInterface;
use AppBundle\Repository\UserRepository;
use AppBundle\Form\EmployerSignup\Data as SingupData;

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
     * @param SingupData $data
     * @throws \Exception
     * @return Employer
     */
    public function singup(SingupData $data): Employer
    {
        if ($this->repository->findByEmail($data->user->email)) {
            throw new \Exception('Another employee with the same email already exists.');
        }

        $employer = new Employer(
            $data->user->username,
            $data->user->email,
            $data->user->password,
            $data->company->name,
            $data->company->description
        );
        $this->em->persist($employer);
        $this->em->flush();

        return $employer;
    }

    public function addJob(JobData $data, Company $company): Job
    {
        $job = new Job($data, $company);
        $this->em->persist($job);
        $this->em->flush();

        return $job;
    }
}
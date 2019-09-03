<?php

namespace AppBundle\Services;

use AppBundle\Entity\Employee;
use Doctrine\ORM\EntityManagerInterface;
use AppBundle\Repository\UserRepository;

class EmployeeService
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
     * @param Employee $employee
     * @throws \Exception
     */
    public function singup(Employee $employee)
    {
        if ($this->repository->findByEmail($employee->getUser()->getEmail())) {
            throw new \Exception('Another employee with the same email already exists.');
        }
        $this->em->persist($employee);
        $this->em->flush();
    }
}
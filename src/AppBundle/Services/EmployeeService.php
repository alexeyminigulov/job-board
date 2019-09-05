<?php

namespace AppBundle\Services;

use AppBundle\Entity\Employee;
use AppBundle\Entity\Resume;
use AppBundle\Form\EmployeeSignup\Data;
use AppBundle\Security\PasswordEncoder;
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

    /**
     * @var PasswordEncoder
     */
    private $encoder;

    public function __construct(
        EntityManagerInterface $em,
        UserRepository $repository,
        PasswordEncoder $passwordEncoder
    )
    {
        $this->em = $em;
        $this->repository = $repository;
        $this->encoder = $passwordEncoder;
    }

    /**
     * @param Data $data
     * @throws \Exception
     * @return Employee
     */
    public function singup(Data $data): Employee
    {
        if ($this->repository->findByEmail($data->email)) {
            throw new \Exception('Another employee with the same email already exists.');
        }
        $employee = new Employee(
            $data->username,
            $data->email,
            $this->encoder->encodePassword($data->password)
        );
        $this->em->persist($employee);
        $this->em->flush();

        return $employee;
    }

    public function addResume(Resume $resume): void
    {
        $this->em->persist($resume);
        $this->em->flush();
    }
}
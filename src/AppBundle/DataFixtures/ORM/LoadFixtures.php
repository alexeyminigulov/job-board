<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Employee;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

class LoadFixtures implements FixtureInterface, ContainerAwareInterface
{
    private $container;

    private $employees = [
        0 => [
            'username' => 'user',
            'email' => 'user@slowcode.io',
            'password' => 'user@slowcode.io'
        ],
        1 => [
            'username' => 'admin',
            'email' => 'admin@slowcode.io',
            'password' => 'admin@slowcode.io'
        ],
        2 => [
            'username' => 'admin23',
            'email' => 'admin23@mail.ru',
            'password' => '$2y$10$ZSidBK3orwsjqBaNtqh1MeSF9/8YdwBckDYHgfMOG9m6bwncwX5Eu'
        ],
    ];

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        foreach ($this->employees as $key => $data) {
            $employee = new Employee(
                $data['username'],
                $data['email'],
                $data['password']
            );
            $manager->persist($employee);
        }
        $manager->flush();
    }
}
<?php

namespace Tests\AppBundle\Entity\User;

use AppBundle\Entity\User\Role;
use AppBundle\Entity\User\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testAddOptions()
    {
        $user = new User('username', 'user@mail.com', '1q2w3e4r5t');

        $this->assertEquals('username', $user->getUsername());

        $this->assertContains(Role::ROLE_USER, $user->getRoles());
    }
}
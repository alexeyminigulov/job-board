<?php

namespace AppBundle\Entity\User;

class Role
{
    public const ROLE_USER = 'ROLE_USER';
    public const ROLE_EMPLOYEE = 'ROLE_EMPLOYEE';
    public const ROLE_EMPLOYER = 'ROLE_EMPLOYER';

    public function isExist(string $role): bool
    {
        return array_key_exists($role, [
            self::ROLE_USER,
            self::ROLE_EMPLOYEE,
            self::ROLE_EMPLOYER,
        ]);
    }
}
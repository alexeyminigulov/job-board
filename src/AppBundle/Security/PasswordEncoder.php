<?php

namespace AppBundle\Security;

use Symfony\Component\Security\Core\Exception\BadCredentialsException;

class PasswordEncoder
{
    const MAX_PASSWORD_LENGTH = 72;

    private $cost;

    public function __construct($cost = 10)
    {
        $cost = (int) $cost;

        if ($cost < 4 || $cost > 31) {
            throw new \InvalidArgumentException('Cost must be in the range of 4-31.');
        }

        $this->cost = $cost;
    }

    public function encodePassword($raw)
    {
        if ($this->isPasswordTooLong($raw)) {
            throw new BadCredentialsException('Invalid password.');
        }

        $options = ['cost' => $this->cost];

        return password_hash($raw, PASSWORD_BCRYPT, $options);
    }

    public function isPasswordValid($encoded, $raw, $salt)
    {
        return !$this->isPasswordTooLong($raw) && password_verify($raw, $encoded);
    }

    private function isPasswordTooLong($password)
    {
        return \strlen($password) > static::MAX_PASSWORD_LENGTH;
    }
}
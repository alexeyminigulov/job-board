<?php

namespace AppBundle\Security\User;

use AppBundle\Entity\Employee;
use AppBundle\Entity\Employer;
use AppBundle\Entity\User\Role;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationCredentialsNotFoundException;

/**
 * AuthorizationChecker is the main authorization point of the Security component.
 *
 * It gives access to the token representing the current user authentication.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 */
class AuthorizationChecker implements AuthorizationCheckerInterface
{
    private $tokenStorage;
    private $authenticationManager;
    private $alwaysAuthenticate;

    /**
     * @param TokenStorageInterface          $tokenStorage
     * @param AuthenticationManagerInterface $authenticationManager An AuthenticationManager instance
     * @param bool                           $alwaysAuthenticate
     */
    public function __construct(TokenStorageInterface $tokenStorage, AuthenticationManagerInterface $authenticationManager, $alwaysAuthenticate = false)
    {
        $this->tokenStorage = $tokenStorage;
        $this->authenticationManager = $authenticationManager;
        $this->alwaysAuthenticate = $alwaysAuthenticate;
    }

    /**
     * {@inheritdoc}
     *
     * @throws AuthenticationCredentialsNotFoundException when the token storage has no authentication token
     */
    final public function isGranted($attributes, $subject = null)
    {
        if (null === ($token = $this->tokenStorage->getToken())) {
            throw new AuthenticationCredentialsNotFoundException('The token storage contains no authentication token. One possible reason may be that there is no firewall configured for this URL.');
        }

        if ($this->alwaysAuthenticate || !$token->isAuthenticated()) {
            $this->tokenStorage->setToken($token = $this->authenticationManager->authenticate($token));
        }

        if (!\is_array($attributes)) {
            $attributes = [$attributes];
        }

        return $this->decide($token, $attributes, $subject);
    }

    private function decide(TokenInterface $token, array $attributes, $subject): bool
    {
        $grant = array_shift($attributes);

        if ($grant == Role::ROLE_EMPLOYEE
            && in_array($grant, $token->getUser()->getRoles())
            && empty($token->getUser()->getEmployee())
        ) {
            $message = sprintf('For role @IsGranted(%s) don\'t exist entity Employee', $grant);
            throw new AccessDeniedException($message);
        }
        else if ($grant == Role::ROLE_EMPLOYER
            && in_array($grant, $token->getUser()->getRoles())
            && empty($token->getUser()->getEmployer())
        ) {
            $message = sprintf('For role @IsGranted(%s) don\'t exist entity Employer', $grant);
            throw new AccessDeniedException($message);
        }

        return true;
    }
}

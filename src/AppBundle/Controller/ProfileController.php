<?php

namespace AppBundle\Controller;

use AppBundle\Form\EmployerSignup\Form;
use AppBundle\Form\Job\Form as JobForm;
use AppBundle\Form\EmployerSignup\Data;
use AppBundle\Services\EmployerService;
use AppBundle\Form\Job\Data as JobData;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Security\LoginFormAuthenticator;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ProfileController extends Controller
{
    /**
     * @Route("/profile", name="show_profile")
     * @IsGranted("ROLE_USER")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createJobAction()
    {
        return $this->render('profile/show.html.twig');
    }
}
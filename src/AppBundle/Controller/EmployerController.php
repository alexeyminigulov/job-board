<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Employer;
use AppBundle\Form\EmployerSignupForm;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Security\LoginFormAuthenticator;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

class EmployerController extends Controller
{
    private $authenticatorHandler;
    private $authenticator;

    public function __construct(GuardAuthenticatorHandler $authenticatorHandler, LoginFormAuthenticator $authenticator)
    {
        $this->authenticatorHandler = $authenticatorHandler;
        $this->authenticator = $authenticator;
    }

    /**
     * @Route("/employer/signup", name="employer_signup")
     */
    public function signupAction(Request $request)
    {
        $form = $this->createForm(EmployerSignupForm::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Employer $employer */
            $employer = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($employer);
            $em->flush();

            $this->addFlash('success', 'Welcome ' .$employer->getUser()->getEmail());

            return $this->authenticatorHandler->authenticateUserAndHandleSuccess(
                $employer->getUser(),
                $request,
                $this->authenticator,
                'main'
            );
        }

        return $this->render('employer/signup.html.twig', [
            'signupForm' => $form->createView(),
        ]);
    }
}
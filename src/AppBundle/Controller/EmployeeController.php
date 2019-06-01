<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Employee;
use AppBundle\Form\EmployeeSignupForm;
use AppBundle\Security\LoginFormAuthenticator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

class EmployeeController extends Controller
{
    private $authenticatorHandler;
    private $authenticator;

    public function __construct(GuardAuthenticatorHandler $authenticatorHandler, LoginFormAuthenticator $authenticator)
    {
        $this->authenticatorHandler = $authenticatorHandler;
        $this->authenticator = $authenticator;
    }

    /**
     * @Route("/employee/signup", name="employee_signup")
     */
    public function indexSignup(Request $request)
    {
        $form = $this->createForm(EmployeeSignupForm::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Employee $employee */
            $employee = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($employee);
            $em->flush();

            $this->addFlash('success', 'Welcome ' .$employee->getUser()->getEmail());

            return $this->authenticatorHandler->authenticateUserAndHandleSuccess(
                $employee->getUser(),
                $request,
                $this->authenticator,
                'main'
            );
        }

        return $this->render('employee/signup.html.twig', [
            'signupForm' => $form->createView(),
        ]);
    }
}
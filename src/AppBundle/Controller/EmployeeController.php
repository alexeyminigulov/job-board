<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Employee;
use AppBundle\Entity\Resume;
use AppBundle\Form\ResumeForm;
use AppBundle\Form\EmployeeSignupForm;
use AppBundle\Security\LoginFormAuthenticator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class EmployeeController extends Controller
{
    private $authenticatorHandler;
    private $authenticator;
    private $tokenStorage;

    public function __construct(GuardAuthenticatorHandler $authenticatorHandler,
                                LoginFormAuthenticator $authenticator,
                                TokenStorageInterface $tokenStorage)
    {
        $this->authenticatorHandler = $authenticatorHandler;
        $this->authenticator = $authenticator;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @Route("/employee/signup", name="employee_signup")
     */
    public function signupAction(Request $request)
    {
        $form = $this->createForm(EmployeeSignupForm::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Employee $employee */
            $employee = $form->getData();
            $employee->getUser()->setRoles(['ROLE_EMPLOYEE']);

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

    /**
     * @Route("/employee/resume", name="employee_resume")
     * @IsGranted("ROLE_EMPLOYEE")
     */
    public function resumeAction(Request $request)
    {
        $employee = $this->tokenStorage->getToken()->getUser()->getEmployee();
        $form = $this->createForm(ResumeForm::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Resume $resume */
            $resume = $form->getData();
            $resume->setEmployee($employee);

            $em = $this->getDoctrine()->getManager();
            $em->persist($resume);
            $em->flush();

            $this->addFlash('success', 'Added resume.');

            return $this->redirectToRoute('homepage');
        }

        return $this->render('employee/resume.html.twig', [
            'resumeForm' => $form->createView(),
        ]);
    }
}
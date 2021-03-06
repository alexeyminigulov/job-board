<?php

namespace AppBundle\Controller;

use AppBundle\Form\EmployeeSignup\Data;
use AppBundle\Form\Resume\Form as ResumeForm;
use AppBundle\Form\Resume\Data as ResumeData;
use AppBundle\Form\EmployeeSignup\Form;
use AppBundle\Security\LoginFormAuthenticator;
use AppBundle\Services\EmployeeService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class EmployeeController extends Controller
{
    private $service;
    private $authenticatorHandler;
    private $authenticator;
    private $tokenStorage;

    public function __construct(EmployeeService $service,
                                GuardAuthenticatorHandler $authenticatorHandler,
                                LoginFormAuthenticator $authenticator,
                                TokenStorageInterface $tokenStorage)
    {
        $this->service = $service;
        $this->authenticatorHandler = $authenticatorHandler;
        $this->authenticator = $authenticator;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @Route("/employee/signup", name="employee_signup")
     */
    public function signupAction(Request $request)
    {
        $data = new Data();
        $form = $this->createForm(Form::class, $data);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            try {
                $employee = $this->service->singup($data);
                $this->addFlash('success', 'Welcome ' .$employee->getUser()->getEmail());
                return $this->authenticatorHandler->authenticateUserAndHandleSuccess(
                    $employee->getUser(),
                    $request,
                    $this->authenticator,
                    'main'
                );
            }
            catch (\Exception $e) {
                $this->addFlash('error', $e->getMessage());
            }
        }

        return $this->render('employee/signup.html.twig', [
            'signupForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/employee/resume", name="employee_resume")
     * @IsGranted("ROLE_EMPLOYEE")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function resumeAction(Request $request)
    {
        /**
         * TODO Handle empty employee Error
         */
        $employee = $this->tokenStorage->getToken()->getUser()->getEmployee();
        $data = new ResumeData();
        $form = $this->createForm(ResumeForm::class, $data);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->service->addResume($data, $employee);
            $this->addFlash('success', 'Added resume.');

            return $this->redirectToRoute('homepage');
        }

        return $this->render('employee/resume.html.twig', [
            'resumeForm' => $form->createView(),
        ]);
    }
}
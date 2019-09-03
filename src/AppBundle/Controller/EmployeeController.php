<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Employee;
use AppBundle\Entity\Resume;
use AppBundle\Form\EmployeeSignup\Data;
use AppBundle\Form\ResumeForm;
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

            $employee = new Employee($data);
            try {
                $this->service->singup($employee);
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
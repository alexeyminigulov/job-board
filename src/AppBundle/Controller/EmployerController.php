<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Employer;
use AppBundle\Entity\Job;
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

class EmployerController extends Controller
{
    private $service;
    private $authenticatorHandler;
    private $authenticator;
    private $tokenStorage;

    public function __construct(EmployerService $service,
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
     * @Route("/employer/signup", name="employer_signup")
     */
    public function signupAction(Request $request)
    {
        $data = new Data();
        $form = $this->createForm(Form::class, $data);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $employer = new Employer($data);
            try {
                $this->service->singup($employer);
                $this->addFlash('success', 'Welcome ' .$employer->getUser()->getEmail());

                return $this->authenticatorHandler->authenticateUserAndHandleSuccess(
                    $employer->getUser(),
                    $request,
                    $this->authenticator,
                    'main'
                );
            }
            catch (\Exception $e) {
                $this->addFlash('error', $e->getMessage());
            }
        }

        return $this->render('employer/signup.html.twig', [
            'signupForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/employer/job/create", name="create_job")
     * @IsGranted("ROLE_EMPLOYER")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function resumeAction(Request $request)
    {
        $company = $this->tokenStorage
            ->getToken()
            ->getUser()
            ->getEmployer()
            ->getCompany();
        $data = new JobData();
        $form = $this->createForm(JobForm::class, $data);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $job = new Job($data, $company);
            $this->service->addJob($job);
            $this->addFlash('success', 'Job ' .$job->getName().' has been created.');
            return $this->redirectToRoute('homepage');
        }

        return $this->render('job/create.html.twig', [
            'createJobForm' => $form->createView(),
        ]);
    }
}
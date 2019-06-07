<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Employer;
use AppBundle\Entity\Job;
use AppBundle\Form\EmployerSignupForm;
use AppBundle\Form\JobForm;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Security\LoginFormAuthenticator;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class EmployerController extends Controller
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
     * @Route("/employer/signup", name="employer_signup")
     */
    public function signupAction(Request $request)
    {
        $form = $this->createForm(EmployerSignupForm::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Employer $employer */
            $employer = $form->getData();
            $employer->getUser()->setRoles(['ROLE_EMPLOYER']);

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
        $form = $this->createForm(JobForm::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Job $job*/
            $job = $form->getData();
            $job->setCompany($company);

            $em = $this->getDoctrine()->getManager();
            $em->persist($job);
            $em->flush();

            $this->addFlash('success', 'Welcome ' .$job->getName());

            return $this->redirectToRoute('homepage');
        }

        return $this->render('job/create.html.twig', [
            'createJobForm' => $form->createView(),
        ]);
    }
}
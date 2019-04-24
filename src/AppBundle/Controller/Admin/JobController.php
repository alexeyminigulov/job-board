<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Job;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class JobController extends Controller
{
    private $breadcrumbs;

    public function __construct(Breadcrumbs $breadcrumbs)
    {
        $this->breadcrumbs = $breadcrumbs;
    }

    /**
     * @Route("/admin/jobs", name="admin_jobs")
     * @Method("GET")
     */
    public function indexAction()
    {
        $this->breadcrumbs
            ->addItem('Jobs');

        $jobs = $this->getDoctrine()
            ->getRepository(Job::class)
            ->findAll();

        return $this->render('admin/job/index.html.twig', [
            'jobs' => $jobs,
        ]);
    }

    /**
     * @Route("/admin/jobs/{id}", name="admin_jobs_show")
     * @Method("GET")
     */
    public function showAction(Job $job)
    {
        $this->breadcrumbs
            ->addItem('Jobs', $this->get('router')->generate('admin_jobs'))
            ->addItem($job->getName());

        return $this->render('admin/job/show.html.twig', [
            'job' => $job,
        ]);
    }
}

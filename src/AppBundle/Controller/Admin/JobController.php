<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Job;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class JobController extends Controller
{
    /**
     * @Route("/admin/jobs", name="admin_jobs")
     * @Method("GET")
     */
    public function indexAction()
    {
        $jobs = $this->getDoctrine()
            ->getRepository(Job::class)
            ->findAll();

        return $this->render('admin/job/index.html.twig', [
            'jobs' => $jobs,
        ]);
    }
}

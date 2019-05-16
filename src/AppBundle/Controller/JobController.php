<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Job;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class JobController extends Controller
{
    /**
     * @Route("/job/{id}", name="desc_job")
     */
    public function indexAction(Job $job)
    {
        return $this->render('job/index.html.twig', [
            'job' => $job,
        ]);
    }
}
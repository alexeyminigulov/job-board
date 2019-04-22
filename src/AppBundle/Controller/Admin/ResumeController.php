<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Resume;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ResumeController extends Controller
{
    /**
     * @Route("/admin/resumes", name="admin_resumes")
     */
    public function indexAction()
    {
        $resumes = $this->getDoctrine()
            ->getRepository(Resume::class)
            ->findAll();

        return $this->render('admin/resume/index.html.twig', [
            'resumes' => $resumes,
        ]);
    }
}

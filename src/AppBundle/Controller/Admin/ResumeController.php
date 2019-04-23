<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Resume;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ResumeController extends Controller
{
    /**
     * @Route("/admin/resumes", name="admin_resumes")
     * @Method("GET")
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

    /**
     * @Route("/admin/resumes/{id}", name="admin_resumes_show")
     * @Method("GET")
     */
    public function showAction(Resume $resume)
    {
        return $this->render('admin/resume/show.html.twig', [
            'resume' => $resume,
        ]);
    }
}

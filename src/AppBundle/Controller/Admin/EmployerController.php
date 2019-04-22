<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Employer;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class EmployerController extends Controller
{
    /**
     * @Route("/admin/employers", name="admin_employers")
     */
    public function indexAction()
    {
        $employers = $this->getDoctrine()
            ->getRepository(Employer::class)
            ->findAll();

        return $this->render('admin/employer/index.html.twig', [
            'employers' => $employers,
        ]);
    }
}

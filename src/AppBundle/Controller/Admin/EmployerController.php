<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Employer;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
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

    /**
     * @Route("/admin/employers/{id}", name="admin_employers_show")
     * @Method("GET")
     */
    public function showAction(Employer $employer)
    {
        return $this->render('admin/employer/show.html.twig', [
            'employer' => $employer,
        ]);
    }
}

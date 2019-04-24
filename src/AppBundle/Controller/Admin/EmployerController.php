<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Employer;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class EmployerController extends Controller
{
    private $breadcrumbs;

    public function __construct(Breadcrumbs $breadcrumbs)
    {
        $this->breadcrumbs = $breadcrumbs;
    }

    /**
     * @Route("/admin/employers", name="admin_employers")
     */
    public function indexAction()
    {
        $this->breadcrumbs
            ->addItem('Employers');

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
        $this->breadcrumbs
            ->addItem('Employers', $this->get('router')->generate('admin_employers'))
            ->addItem($employer->getUser()->getUserName());

        return $this->render('admin/employer/show.html.twig', [
            'employer' => $employer,
        ]);
    }
}

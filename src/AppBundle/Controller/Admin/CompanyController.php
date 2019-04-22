<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Company;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CompanyController extends Controller
{
    /**
     * @Route("/admin/companies", name="admin_companies")
     */
    public function indexAction()
    {
        $companies = $this->getDoctrine()
            ->getRepository(Company::class)
            ->findAll();

        return $this->render('admin/company/index.html.twig', [
            'companies' => $companies,
        ]);
    }
}

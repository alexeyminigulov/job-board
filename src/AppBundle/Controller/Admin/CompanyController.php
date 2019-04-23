<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Company;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
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

    /**
     * @Route("/admin/companies/{id}", name="admin_companies_show")
     * @Method("GET")
     */
    public function showAction(Company $company)
    {
        return $this->render('admin/company/show.html.twig', [
            'company' => $company,
        ]);
    }
}

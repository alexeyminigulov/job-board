<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Company;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;
use Symfony\Component\Routing\Annotation\Route;

class CompanyController extends Controller
{
    private $breadcrumbs;

    public function __construct(Breadcrumbs $breadcrumbs)
    {
        $this->breadcrumbs = $breadcrumbs;
    }

    /**
     * @Route("/admin/companies", name="admin_companies")
     */
    public function indexAction()
    {
        $this->breadcrumbs
            ->addItem('Companies');

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
     * @param Company $company
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(Company $company)
    {
        $this->breadcrumbs
            ->addItem('Companies', $this->get('router')->generate('admin_companies'))
            ->addItem($company->getName());

        return $this->render('admin/company/show.html.twig', [
            'company' => $company,
        ]);
    }
}

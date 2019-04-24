<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Employee;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class EmployeeController extends Controller
{
    private $breadcrumbs;

    public function __construct(Breadcrumbs $breadcrumbs)
    {
        $this->breadcrumbs = $breadcrumbs;
    }

    /**
     * @Route("/admin/employees", name="admin_employees")
     */
    public function indexAction()
    {
        $this->breadcrumbs
            ->addItem('Employees');

        $employees = $this->getDoctrine()
            ->getRepository(Employee::class)
            ->findAll();

        return $this->render('admin/employee/index.html.twig', [
            'employees' => $employees,
        ]);
    }

    /**
     * @Route("/admin/employees/{id}", name="admin_employees_show")
     * @Method("GET")
     */
    public function showAction(Employee $employee)
    {
        $this->breadcrumbs
            ->addItem('Employees', $this->get('router')->generate('admin_employees'))
            ->addItem($employee->getUser()->getUserName());

        return $this->render('admin/employee/show.html.twig', [
            'employee' => $employee,
        ]);
    }
}

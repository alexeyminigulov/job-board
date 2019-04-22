<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Employee;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class EmployeeController extends Controller
{
    /**
     * @Route("/admin/employees", name="admin_employees")
     */
    public function indexAction()
    {
        $employees = $this->getDoctrine()
            ->getRepository(Employee::class)
            ->findAll();

        return $this->render('admin/employee/index.html.twig', [
            'employees' => $employees,
        ]);
    }
}

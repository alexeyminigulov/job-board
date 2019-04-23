<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends Controller
{
    /**
     * @Route("/admin/users", name="admin_users")
     * @Method("GET")
     */
    public function indexAction()
    {
        $users = $this->getDoctrine()
            ->getRepository(User::class)
            ->findAll();

        return $this->render('admin/user/index.html.twig', [
            'users' => $users,
        ]);
    }

    /**
     * @Route("/admin/users/{id}", name="admin_users_show")
     * @Method("GET")
     */
    public function showAction(User $user)
    {
        return $this->render('admin/user/show.html.twig', [
            'user' => $user,
        ]);
    }
}

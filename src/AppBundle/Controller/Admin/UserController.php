<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\User\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends Controller
{
    private $breadcrumbs;

    public function __construct(Breadcrumbs $breadcrumbs)
    {
        $this->breadcrumbs = $breadcrumbs;
    }

    /**
     * @Route("/admin/users", name="admin_users")
     * @Method("GET")
     */
    public function indexAction()
    {
        $this->breadcrumbs
            ->addItem('Users');

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
        $this->breadcrumbs
            ->addItem('Users', $this->get('router')->generate('admin_users'))
            ->addItem($user->getUserName());

        return $this->render('admin/user/show.html.twig', [
            'user' => $user,
        ]);
    }
}

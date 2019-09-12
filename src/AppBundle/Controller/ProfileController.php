<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use AppBundle\Form\Profile\User\Fio\Data as UserFioData;
use AppBundle\Form\Profile\User\Fio\Form as UserFioForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class ProfileController extends Controller
{
    /**
     * @Route("/profile/user", name="show_profile")
     * @IsGranted("ROLE_USER")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function index()
    {
        return $this->render('profile/user/show.html.twig');
    }

    /**
     * @Route("/profile/user/fio", name="profile_user_fio")
     * @IsGranted("ROLE_USER")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function userFio(Request $request)
    {
        $data = new UserFioData();
        $form = $this->createForm(UserFioForm::class, $data);
        $form->handleRequest($request);

        return $this->render('profile/user/fio/show.html.twig', [
            'userFio' => $form->createView(),
        ]);
    }
}
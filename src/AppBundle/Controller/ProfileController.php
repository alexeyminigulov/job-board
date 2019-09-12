<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User\User;
use AppBundle\Services\ProfileService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use AppBundle\Form\Profile\User\Fio\Data as UserFioData;
use AppBundle\Form\Profile\User\Fio\Form as UserFioForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class ProfileController extends Controller
{
    private $service;

    public function __construct(ProfileService $service)
    {
        $this->service = $service;
    }

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
        /** @var User $user */
        $user = $this->getUser();
        $data = new UserFioData($user->getUserName());
        $form = $this->createForm(UserFioForm::class, $data);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            try {
                $user = $this->service->editFio($user, $data);
                $this->addFlash('success', 'User name was changed to ' .$user->getUsername());
                return $this->redirectToRoute('show_profile');
            }
            catch (\Exception $e) {
                $this->addFlash('error', $e->getMessage());
            }
        }

        return $this->render('profile/user/fio/show.html.twig', [
            'userFio' => $form->createView(),
        ]);
    }
}
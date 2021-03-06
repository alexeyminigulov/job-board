<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Filter;
use AppBundle\Form\FilterFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;
use Symfony\Component\Routing\Annotation\Route;

/** @Route("/admin", name="admin_") */
class FilterController extends Controller
{
    private $breadcrumbs;

    public function __construct(Breadcrumbs $breadcrumbs)
    {
        $this->breadcrumbs = $breadcrumbs;
    }

    /**
     * @Route("/filters", name="filters")
     */
    public function indexAction()
    {
        $this->breadcrumbs
            ->addItem('Filters');

        $filters = $this->getDoctrine()
            ->getRepository(Filter::class)
            ->findAll();

        return $this->render('admin/filter/index.html.twig', [
            'filters' => $filters,
        ]);
    }

    /**
     * @Route("/filters/{id}", name="filters_show", requirements={"id"="\d+"})
     * @Method("GET")
     */
    public function showAction(Filter $filter)
    {
        return $this->render('admin/filter/show.html.twig', [
            'filter' => $filter,
        ]);
    }

    /**
     * @Route("/filters/create", name="filters_create")
     */
    public function createAction(Request $request)
    {
        $form = $this->createForm(FilterFormType::class);

        // only handles data on POST
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $filter = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($filter);
            $em->flush();

            $this->addFlash('success', 'Filter created!');

            return $this->redirectToRoute('admin_filters');
        }

        return $this->render('admin/filter/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/filters/update/{id}", name="filters_update", requirements={"id"="\d+"})
     */
    public function updateAction(Filter $filter, Request $request)
    {
        $form = $this->createForm(FilterFormType::class, $filter);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $filter = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($filter);
            $em->flush();

            $this->addFlash('success', 'Filter updated!');

            return $this->redirectToRoute('admin_filters');
        }

        return $this->render('admin/filter/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/filters/delete/{id}", name="filters_delete", requirements={"id"="\d+"})
     */
    public function deleteAction(Filter $filter)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($filter);
        $em->flush();

        $this->addFlash('success', 'Filter deleted!');

        return $this->redirectToRoute('admin_filters');
    }
}
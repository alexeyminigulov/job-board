<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Filter;
use AppBundle\Widgets\SearchWidget\SearchWidget;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends Controller
{
    /**
     * @Route("/search", name="search")
     */
    public function indexAction(Request $request)
    {
        /** @var Filter[] $filters */
        $filters = $this->getDoctrine()
            ->getRepository('AppBundle:Filter')
            ->findAll();

        $searchWidget = new SearchWidget($filters, $request);

        $em = $this->getDoctrine()->getManager();

        $jobs = $em->getRepository('AppBundle:Job')
            ->findByParams($searchWidget->getQueryParams());

        return $this->render('search/index.html.twig', [
            'searchWidget' => $searchWidget,
            'jobs' => $jobs,
        ]);
    }
}

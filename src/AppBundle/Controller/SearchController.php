<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Filter;
use AppBundle\Widgets\SearchWidget\SearchWidget;
use Elastica\Search;
use FOS\ElasticaBundle\Index\IndexManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

class SearchController extends Controller
{
    /**
     * @Route("/search", name="search")
     */
    public function indexAction(Request $request, PaginatorInterface $paginator)
    {
        if ($query = $request->get('name')) {
            /** @var IndexManager $indexManager */
            $indexManager = $this->get('fos_elastica.index_manager');
            /** @var Search $search */
            $search = $indexManager->getIndex('job_board')->createSearch();
            $search->addType('job');
            $result = $search->search($query)->getResults();
            dump($result);
        }

        /** @var Filter[] $filters */
        $filters = $this->getDoctrine()
            ->getRepository('AppBundle:Filter')
            ->findAll();

        $searchWidget = new SearchWidget($filters, $request);

        $em = $this->getDoctrine()->getManager();

        $queryJobs = $em->getRepository('AppBundle:Job')
            ->getWithParamsQuery($searchWidget->getQueryParams());

        $jobs = $paginator->paginate(
            $queryJobs, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            4 /*limit per page*/
        );

        return $this->render('search/index.html.twig', [
            'searchWidget' => $searchWidget,
            'jobs' => $jobs,
        ]);
    }
}

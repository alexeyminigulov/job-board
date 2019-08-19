<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Search;
use Elastica\Result;
use AppBundle\Entity\Filter;
use AppBundle\Form\SearchForm;
use AppBundle\Repository\JobRepository;
use AppBundle\Widgets\SearchWidget\QueryParam;
use AppBundle\Widgets\SearchWidget\SearchWidget;
use FOS\ElasticaBundle\Index\IndexManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

class SearchController extends Controller
{
    /**
     * @var IndexManager
     */
    private $indexManager;

    /**
     * @var JobRepository
     */
    private $repository;

    public function __construct(IndexManager $indexManager, JobRepository $repository)
    {
        $this->indexManager = $indexManager;
        $this->repository = $repository;
    }

    /**
     * @Route("/search", name="search")
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request, PaginatorInterface $paginator)
    {
        $search = new Search();
        $form = $this->createForm(SearchForm::class, $search, [
            'action' => $this->generateUrl('search'),
            'method' => 'GET',
            'csrf_protection' => false,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $searchText = $form->get('name')->getData();

            $search = $this->indexManager->getIndex('job_board')->createSearch();
            $jobs = $search->addType('job')->search($searchText)->getResults();
            $jobIndices = array_map(function(Result $result) {
                return (int)$result->getParam('_id');
            }, $jobs);
            $indicesParam = new QueryParam('id', $jobIndices, Filter::TYPE_ARRAY);

            $this->repository->attachQueryParam($indicesParam);
        }

        /** @var Filter[] $filters */
        $filters = $this->getDoctrine()
            ->getRepository('AppBundle:Filter')
            ->findAll();
        $searchWidget = new SearchWidget($filters, $request, $form->getData());
        $this->repository->attachQueryParams($searchWidget->getQueryParams());

        try {
            $queryJobs = $this->repository->getBuilder();

            $jobs = $paginator->paginate(
                $queryJobs, /* query NOT result */
                $request->query->getInt('page', 1), /*page number*/
                4 /*limit per page*/
            );
        } catch (\ReflectionException $e) {
            $this->addFlash('error', $e->getMessage());
        }

        return $this->render('search/index.html.twig', [
            'searchWidget' => $searchWidget,
            'formSearch' => $form->createView(),
            'jobs' => $jobs,
        ]);
    }
}

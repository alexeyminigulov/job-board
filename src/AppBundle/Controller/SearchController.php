<?php

namespace AppBundle\Controller;

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
        return $this->render('search/index.html.twig');
    }
}

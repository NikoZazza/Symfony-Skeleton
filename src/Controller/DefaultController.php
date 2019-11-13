<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use Swagger\Annotations as SWG;

/**
 * Class DefaultController
 * @package App\Controller
 * @SWG\Tag(name="default")
 */
class DefaultController extends UtilityController {
    /**
     * Home page
     *
     * @param Request $request
     * @Route("/", name="index", methods={"GET"})
     * @return RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request) {

        return $this->render('@App/index.html.twig');
    }
}

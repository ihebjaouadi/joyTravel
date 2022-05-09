<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/admin", name="app_default")
     */
    public function index(): Response
    {
        return $this->render('baseBackOffice.html.twig');
    }
}
